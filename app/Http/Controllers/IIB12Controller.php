<?php

namespace App\Http\Controllers;

use App\Helpers\TemplateContentProcessor;
use App\Helpers\TemplateProcessor as HelpersTemplateProcessor;
use App\Models\ButirKegiatan;
use App\Models\IIB12;
use App\Models\InfraType;
use App\Models\Room;
use App\Models\Supervisor;
use App\Models\UserData;
use Illuminate\Http\Request;

class IIB12Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.12'])->first();
        return view('iib12/index-iib12', ['butirkeg' => $butirkegiatan, 'infratypes' => InfraType::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.12'])->first();
        $rooms = Room::all();
        $infratypes = InfraType::all();
        $supervisors = Supervisor::all();
        $preferredsp = Supervisor::where(['is_preference' => true])->first()->id;

        return view('iib12/create-iib12', [
            'butirkeg' => $butirkegiatan,
            'infratypes' => $infratypes,
            'rooms' => $rooms,
            'supervisors' => $supervisors,
            'preferredsp' => $preferredsp,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'title' => 'required',
            'date' => 'required',
            'room' => 'required',
            'infraname' => 'required',
            'infratype' => 'required',
            'infrafunc' => 'required',
            'requester' => 'required',
            'problem_summary' => 'required',
            'supervisor' => 'required',
        ]);

        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.12'])->first();

        $docPath = null;
        if ($request->hasFile('documentation')) {
            $docPath = $request->file('documentation')->store('images', 'public');
        }

        $approvalLetterPath = null;
        if ($request->hasFile('approval_letter')) {
            $approvalLetterPath = $request->file('approval_letter')->store('images', 'public');
        }

        IIB12::create([
            'title' => $request->title,
            'type' => $request->type,
            'time' => $request->date,
            'room_id' => $request->room,
            'infra_name' => $request->infraname,
            'infra_type_id' => $request->infratype,
            'infra_func' => $request->infrafunc,
            'background' => $request->background,
            'problem_ident' => $request->problem_ident,
            'problem_analysis' => $request->problem_analysis,
            'result_ident' => $request->result_ident,
            'solution' => $request->solution,
            'action' => $request->action,
            'documentation' => $request->documentation,
            'approval_letter' => $request->approval_letter,
            'user_data_id' => 1,
            'location_id' => 1,
            'butir_kegiatan_id' => $butirkegiatan->id,
            'requester' => $request->requester,
            'problem_summary' => $request->problem_summary,
            'documentation' => $docPath,
            'approval_letter' => $approvalLetterPath,
            'supervisor_id' => $request->supervisor,
        ]);

        return redirect('/IIB12')->with('success-create', 'Butir Kegiatan II.B.12 telah ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.12'])->first();
        $rooms = Room::all();
        $infratypes = InfraType::all();
        $supervisors = Supervisor::all();
        $iib12 = IIB12::find($id);

        return view('iib12/edit-iib12', [
            'butirkeg' => $butirkegiatan,
            'infratypes' => $infratypes,
            'rooms' => $rooms,
            'supervisors' => $supervisors,
            'iib12' => $iib12
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
            'title' => 'required',
            'date' => 'required',
            'room' => 'required',
            'infraname' => 'required',
            'infratype' => 'required',
            'infrafunc' => 'required',
            'requester' => 'required',
            'problem_summary' => 'required',
            'supervisor' => 'required',
        ]);

        $docPath = '';
        if ($request->hasFile('documentation')) {
            $image = $request->file('documentation');
            $docPath = $image->store('images', 'public');
        }
        $approvalLetterPath = '';
        if ($request->hasFile('approval_letter')) {
            $image = $request->file('approval_letter');
            $approvalLetterPath = $image->store('images', 'public');
        }

        $iib12 = IIB12::find($id);
        $data = ([
            'title' => $request->title,
            'type' => $request->type,
            'time' => $request->date,
            'room_id' => $request->room,
            'infra_name' => $request->infraname,
            'infra_type_id' => $request->infratype,
            'infra_func' => $request->infrafunc,
            'background' => $request->background,
            'problem_ident' => $request->problem_ident,
            'problem_analysis' => $request->problem_analysis,
            'result_ident' => $request->result_ident,
            'solution' => $request->solution,
            'action' => $request->action,
            'documentation' => $request->documentation,
            'approval_letter' => $request->approval_letter,
            'requester' => $request->requester,
            'problem_summary' => $request->problem_summary,
            'documentation' => $docPath == '' ? $iib12->documentation : $docPath,
            'approval_letter' => $approvalLetterPath == '' ? $iib12->approval_letter : $approvalLetterPath,
            'supervisor_id' => $request->supervisor,
        ]);
        $iib12->update($data);

        return redirect('/IIB12')->with('success-create', 'Data Bukti Fisik telah diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getData(Request $request)
    {
        $recordsTotal = IIB12::count();
        $recordsFiltered = IIB12::where('title', 'like', '%' . $request->search["value"] . '%')
            ->count();

        $orderColumn = 'created_at';
        $orderDir = 'DESC';
        if ($request->order != null) {
            if ($request->order[0]['dir'] == 'asc') {
                $orderDir = 'asc';
            } else {
                $orderDir = 'desc';
            }
            if ($request->order[0]['column'] == '2') {
                $orderColumn = 'title';
            } else if ($request->order[0]['column'] == '3') {
                $orderColumn = 'time';
            }
        }
        $activities = IIB12::where('title', 'like', '%' . $request->search["value"] . '%')
            ->orderByRaw($orderColumn . ' ' . $orderDir)
            ->skip($request->start)
            ->take($request->length)
            ->get();
        $activitiesArray = array();
        $i = 1;
        foreach ($activities as $activity) {
            $activityData = array();
            $activityData["index"] = $i;
            $activityData["title"] = $activity->title;
            $activityData["time"] =  $activity->time;
            $activityData["documentation"] = $activity->documentation != null ? true : false;
            $activityData["approval_letter"] = $activity->approval_letter != null ? true : false;
            $activityData["id"] = $activity->id;
            $activitiesArray[] = $activityData;
            $i++;
        }
        return json_encode([
            "draw" => $request->draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $activitiesArray
        ]);
    }

    public function generate($id)
    {
        $user = UserData::find(1);
        $iib12 = array(IIB12::find($id));

        $processor = new HelpersTemplateProcessor();
        $processor->generateWordFile($user, $iib12, function ($phpWord, $table, $iib12) {
            TemplateContentProcessor::generateIIB12WordContent($phpWord, $table, $iib12);
        });
    }
    public function generateByPeriode()
    {
        $user = UserData::find(1);
        $iib12 = IIB12::all();

        $processor = new HelpersTemplateProcessor();
        $processor->generateWordFile($user, $iib12, function ($phpWord, $table, $iib12) {
            TemplateContentProcessor::generateIIB12WordContent($phpWord, $table, $iib12);
        });
    }
    public function generateApproval($id)
    {
        $processor = new HelpersTemplateProcessor();
        $iib12 = array(IIB12::find($id));

        return $processor->generateiib12ApprovalLetter($iib12);
    }
    public function generateApprovalByPeriode($id)
    {
        $processor = new HelpersTemplateProcessor();
        $iib12 = array(IIB12::find($id));

        return $processor->generateiib12ApprovalLetter($iib12);
    }
}
