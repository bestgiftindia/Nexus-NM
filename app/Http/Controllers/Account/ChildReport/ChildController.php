<?php

namespace App\Http\Controllers\Account\ChildReport;

use App\Http\Controllers\Controller;
use App\Services\Login\LoginService;
use App\Services\Child\ChildService;
use App\Models\Child\Child as ChildModel;
use App\Services\Child\ReportService;
use App\Services\flashService;
use App\Enums\Message;
use App\Http\Requests\ChildRequest;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    public ChildService $childService;
    public LoginService $loginService;
    public $flasherService;

    function __construct(ChildService $childService, flashService $flasher,  LoginService $loginService)
    {
        $this->childService = $childService;
        $this->loginService = $loginService;
        $this->flasherService = $flasher;
    }

    function list()
    {
        return view('account.child.lists');
    }
    function index()
    {
        return view('account.child.create');
    }
    function store(ChildRequest $childRequest)
    {
        $insertData = $this->childService->createService($childRequest->validated());
        $this->flasherService->successService(Message::CHILDSAVE->value);
        return redirect()->route('account.child.info', ['child' => $insertData->id]);
    }
    function edit(ChildModel $child)
    {
        return view('account.child.edit', compact('child'));
    }
    function update(ChildRequest $childRequest, ChildModel $child)
    {
        $this->childService->updateService($childRequest->validated(), $child->id);
        $this->flasherService->successService(Message::CHILDUPDATE->value);
        return redirect()->route('account.child.info', ['child' => $child->id]);
    }
    function delete(ChildModel $child)
    {
        $child->delete();
        return response()->json(['success' => 'Record Deleted Successfully!']);
    }

    function information(ChildModel $child)
    {
        return view('account.child.info',compact('child'));
    }
    
    function getlist()
    {
        $currentUser = $this->loginService->findLoginUserService();
        $lists = $this->childService->allService([
            'where' => [
                'login_user_id' => $currentUser['account_id']
            ]
        ]);

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

                $editUrl = route('account.child.edit', ['child' => $row->id]);
                $infoUrl = route('account.child.info', ['child' => $row->id]);
                $generatePdf = route('account.child.generate.pdf', ['child' => $row->id]);
                $deleteUrl = route('account.child.delete', ['child' => $row->id]);

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

    function generate_pdf(ChildModel $child){
        $generateReportId = $child->id;
        $pdf = new ReportService(
            app(ChildService::class),
            app(LoginService::class),
            $generateReportId
        );
        $pdf->generatePDF();
    }
}
