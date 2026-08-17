<?php

namespace App\Http\Controllers\Account\Loshugrid;

use App\Enums\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\Loshugrid\LoshugridRequest;
use App\Models\Loshugrid\LoshuGrid;
use App\Services\ActivityLogService;
use App\Services\flashService;
use App\Services\Loshugrid\LoshugridService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LoshugridController extends Controller
{
    public $loshugridService;
    public $flasherService;
    public $logActivity;

    function __construct(LoshugridService $loshugridService, flashService $flasher, ActivityLogService $logActivity)
    {
        $this->loshugridService = $loshugridService;
        $this->flasherService = $flasher;
        $this->logActivity = $logActivity;
    }

    function index()
    {
        return view('account.loshugrid.create');
    }

    function store(LoshugridRequest $loshugridRequest)
    {
        $insertData = $this->loshugridService->createService($loshugridRequest->validated());
        $this->flasherService->successService(Message::LOSHUGRIDSAVE->value);
        return redirect()->route('account.loshugrid.info', ['loshugrid' => $insertData->id]);
    }

    function lists()
    {
        return view('account.loshugrid.lists');
    }

    function info(LoshuGrid $loshugrid)
    {
        return view('account.loshugrid.info',compact('loshugrid'));
    }

    function edit(LoshuGrid $loshugrid)
    {
        return view('account.loshugrid.edit', compact('loshugrid'));
    }

    function update(LoshugridRequest $loshugridRequest, LoshuGrid $loshugrid)
    {
        $this->loshugridService->updateService($loshugrid->id, $loshugridRequest->validated());
        $this->flasherService->successService(Message::LOSHUGRIDUPDATE->value);
        return redirect()->route('account.loshugrid.info', ['loshugrid' => $loshugrid->id]);
    }

    public function destroy(LoshuGrid $loshugrid)
    {
        try {
            $oldData = $loshugrid->toArray();
            $loshugrid->delete();

            $this->logActivity->store(
                'Loshu Grid',
                'Delete',
                $loshugrid->id,
                $oldData,
                null
            );

            return response()->json([
                'status'  => true,
                'message' => 'Data deleted successfully.'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    function listdata()
    {
        $query = LoshuGrid::orderBY('id', 'DESC');

        return DataTables::eloquent($query)
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
                $html .= '<a href="tel:' . $row->phonecode->phonecode . $row->phone . '" class="text-muted fs-sm mb-0"><i data-lucide="phone"></i> +' . $row->phonecode->phonecode . '-' . $row->phone . '</a>';
                $html .= '<a href="mailto:' . $row->email . '" class="text-muted fs-sm mb-0"><i data-lucide="mail"></i> ' . $row->email . '</a>';
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('action', function ($row) {

                $editUrl = route('account.loshugrid.edit', ['loshugrid' => $row->id]);
                $infoUrl = route('account.loshugrid.info', ['loshugrid' => $row->id]);
                $deleteUrl = route('account.loshugrid.destroy', ['loshugrid' => $row->id]);

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
                            <a class="dropdown-item" href="' . $editUrl . '">
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

            ->toJson();
    }
}
