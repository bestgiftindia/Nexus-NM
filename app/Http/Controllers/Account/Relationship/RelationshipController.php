<?php

namespace App\Http\Controllers\Account\Relationship;

use App\Http\Controllers\Controller;
use App\Http\Requests\RelationshipRequest;
use App\Services\Relationship\ReportService;
use Illuminate\Http\Request;
use App\Models\Relationship\Relationship as RelationshipModel;
use App\Services\Login\LoginService;
use App\Services\Relationship\RelationshipService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\flashService;
use App\Enums\Message;

class RelationshipController extends Controller
{
    public RelationshipService $relationshipService;
    public LoginService $loginService;
    public $flasherService;

    function __construct(RelationshipService $relationshipService, LoginService $loginService, flashService $flasher)
    {
        $this->relationshipService = $relationshipService;
        $this->loginService = $loginService;
        $this->flasherService = $flasher;
    }
    function index()
    {
        return view('account.relationship.create');
    }
    function store(RelationshipRequest $relationshipRequest)
    {
        $relationshipinfo = $this->relationshipService->createService($relationshipRequest->validated());
        $this->flasherService->successService(Message::RELATIONSHIPSAVE->value);
        return redirect()->route('account.relationship.info', ['relationship' => $relationshipinfo->id]);
    }

    function information(RelationshipModel $relationship)
    {
        return view('account.relationship.info', compact('relationship'));
    }



    function edit(RelationshipModel $relationship)
    {
        return view('account.relationship.edit', compact('relationship'));
    }
    function update(RelationshipRequest $relationshipRequest, RelationshipModel $relationship)
    {
        $this->relationshipService->updateService($relationshipRequest->validated(), $relationship->id);
        $this->flasherService->successService(Message::RELATIONSHIPUPDATE->value);
        return redirect()->route('account.relationship.info', ['relationship' => $relationship->id]);
    }
    function destroy(RelationshipModel $relationship)
    {
        $relationship->delete();
        return response()->json(['success' => 'Record Deleted Successfully!']);
    }
    function list()
    {
        return view('account.relationship.lists');
    }
    function getlist()
    {
        $lists = $this->relationshipService->allService();
        return datatables()->of($lists)
            ->addIndexColumn()
            ->addColumn('date_time', function ($row) {
                return datetimeFormat($row->created_at);
            })
            ->addColumn('name', function ($row) {
                $html = '';
                $html .= '<h5 class="mb-0 lh-base fs-base">';
                $html .= '<a href="" class="link-reset">' . generateFullName($row) . '</a>';
                $html .= '</h5>';
                $html .= '<span>DOB: ' . (dateFormat($row->date_of_birth)) . '</span>';
                return $html;
            })
            ->addColumn('contact_info', function ($row) {
                $html = '';
                $html .= '<div class="d-flex align-items-center gap-2">';
                $html .= '<div class="d-flex flex-column">';
                if ($row->mobile_number) {
                    $html .= '<a href="tel:' . ($row->phonecode->phonecode ?? '') . $row->mobile_number . '" class="text-muted fs-sm mb-0"><i data-lucide="phone"></i> +' . ($row->phonecode->phonecode ?? '') . '-' . $row->mobile_number . '</a>';
                }
                if($row->email){
                    $html .= '<a href="mailto:' . $row->email . '" class="text-muted fs-sm mb-0"><i data-lucide="mail"></i> ' . $row->email . '</a>';
                }
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('action', function ($row) {

                $editUrl = route('account.relationship.edit', ['relationship' => $row->id]);
                $infoUrl = route('account.relationship.info', ['relationship' => $row->id]);
                $generatePdf = route('account.relationship.generate.pdf', ['relationship' => $row->id]);
                $deleteUrl = route('account.relationship.destroy', ['relationship' => $row->id]);

                $html = '';

                $html .= '<div class="dropdown">';
                $html .= '<button class="btn btn-default btn-icon btn-sm rounded-circle dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown">';
                $html .= '<i data-lucide="ellipsis-vertical" class="fs-lg text-black"></i>';
                $html .= '</button>';

                $html .= '<ul class="dropdown-menu dropdown-menu-end">';

                // Edit
                $html .= '<li>
                            <a class="dropdown-item" href="' . $editUrl . '">
                                <i data-lucide="square-pen" class="me-1"></i>
                                Edit
                            </a>
                        </li>';

                // View
                $html .= '<li>
                            <a class="dropdown-item" href="' . $infoUrl . '">
                                <i data-lucide="eye" class="me-1"></i>
                                View Report
                            </a>
                        </li>';

                // Generate Report
                $html .= '<li>
                            <a class="dropdown-item" href="' . $generatePdf . '">
                                <i data-lucide="file-text" class="me-1"></i>
                                Generate Report
                            </a>
                        </li>';



                // Delete
                $html .= '<li>
                            <a href="javascript:void(0);"
                            class="dropdown-item text-danger delete-record"
                            data-id="' . $row->id . '"
                            data-url="' . $deleteUrl . '">
                                <i data-lucide="trash-2" class="me-1"></i>
                                Delete
                            </a>
                        </li>';

                $html .= '</ul>';
                $html .= '</div>';

                return $html;
            })

            ->addColumn('gender', function ($row) {
                return getGenders()[$row->gender]['name'] ?? '';
            })

            ->rawColumns(['status', 'gender', 'name', 'contact_info', 'action'])


            ->make(true);
    }

    function generate_pdf(RelationshipModel $relationship)
    {
        $generateReportId = $relationship->id;
        $pdf = new ReportService(
            app(relationshipService::class),
            app(LoginService::class),
            $generateReportId
        );
        $pdf->generatePDF();
    }
}
