<?php

namespace App\Http\Controllers\Account\Pronology;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pronology\NameReport as PronologyModel;
use App\Services\Pronology\PronologyService;
use App\Services\flashService;
use App\Services\Login\LoginService;
use App\Http\Requests\PronologyRequest;
use App\Enums\Message;
use App\Services\Pronology\ReportService;

class PronologyController extends Controller
{

    public PronologyService $pronologyService;
    public LoginService $loginService;
    public $flasherService;

    function __construct(PronologyService $pronologyService, LoginService $loginService, flashService $flasher)
    {
        $this->pronologyService = $pronologyService;
        $this->loginService = $loginService;
        $this->flasherService = $flasher;
    }

    function index()
    {
        return view('account.pronology.create');
    }
    function store(PronologyRequest $pronologyRequest)
    {
        $relationshipinfo = $this->pronologyService->createService($pronologyRequest->validated());
        $this->flasherService->successService(Message::NAMEREPORTSAVE->value);
        return redirect()->route('account.relationship.info', ['relationship' => $relationshipinfo->id]);
    }

    function information(PronologyModel $pronology)
    {
        return view('account.pronology.info', compact('pronology'));
    }



    function edit(PronologyModel $pronology)
    {
        return view('account.pronology.edit', compact('pronology'));
    }
    function update(PronologyRequest $pronologyRequest, PronologyModel $pronology)
    {
        $this->pronologyService->updateService($pronologyRequest->validated(), $pronology->id);
        $this->flasherService->successService(Message::NAMEREPORTUPDATE->value);
        return redirect()->route('account.relationship.info', ['relationship' => $pronology->id]);
    }
    function destroy(PronologyModel $pronology)
    {
        $pronology->delete();
        return response()->json(['success' => 'Record Deleted Successfully!']);
    }
    function list()
    {
        return view('account.relationship.lists');
    }

    function generateReport($id)
    {
        $pdf = new ReportService($id);
        $pdf->generatePDF();
    }
}
