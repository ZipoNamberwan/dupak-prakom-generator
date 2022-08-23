<?php

namespace App\Http\Controllers;

use App\Helpers\TemplateContentProcessor;
use App\Helpers\TemplateProcessor;
use App\Helpers\Utilities;
use App\Models\ButirKegiatan;
use App\Models\IIB9;
use App\Models\InfraType;
use App\Models\Room;
use App\Models\Supervisor;
use App\Models\UserData;
use Illuminate\Http\Request;

class IIB9Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.9'])->first();
        return view('iib9/index-iib9', ['butirkeg' => $butirkegiatan, 'infratypes' => InfraType::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.9'])->first();
        $rooms = Room::all();
        $infratypes = InfraType::all();
        $supervisors = Supervisor::all();
        $preferredsp = Supervisor::where(['is_preference' => true])->first()->id;

        return view('iib9/create-iib9', [
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
            'title' => 'required',
            'date' => 'required',
            'room' => 'required',
            'infraname' => 'required',
            'infratype' => 'required',
            'infrafunc' => 'required',
            'requester' => 'required',
            'background' => 'required',
            'step' => 'required',
            'summary' => 'required',
            'supervisor' => 'required',
        ]);

        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.9'])->first();

        $docPath = null;
        if ($request->hasFile('documentation')) {
            $docPath = $request->file('documentation')->store('images', 'public');
        }

        $approvalLetterPath = null;
        if ($request->hasFile('approval_letter')) {
            $approvalLetterPath = $request->file('approval_letter')->store('images', 'public');
        }

        IIB9::create([
            'title' => $request->title,
            'time' => $request->date,
            'room_id' => $request->room,
            'infra_name' => $request->infraname,
            'infra_type_id' => $request->infratype,
            'infra_func' => $request->infrafunc,
            'background' => $request->background,
            'step' => $request->step,
            'summary' => $request->summary,
            'documentation' => $request->documentation,
            'approval_letter' => $request->approval_letter,
            'user_data_id' => 1,
            'location_id' => 1,
            'butir_kegiatan_id' => $butirkegiatan->id,
            'requester' => $request->requester,
            'documentation' => $docPath,
            'approval_letter' => $approvalLetterPath,
            'supervisor_id' => $request->supervisor,
        ]);

        return redirect('/IIB9')->with('success-create', 'Butir Kegiatan II.B.9 telah ditambah!');
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
        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.9'])->first();
        $rooms = Room::all();
        $infratypes = InfraType::all();
        $supervisors = Supervisor::all();
        $iib9 = IIB9::find($id);

        return view('iib9/edit-iib9', [
            'butirkeg' => $butirkegiatan,
            'infratypes' => $infratypes,
            'rooms' => $rooms,
            'supervisors' => $supervisors,
            'iib9' => $iib9
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
            'title' => 'required',
            'date' => 'required',
            'room' => 'required',
            'infraname' => 'required',
            'infratype' => 'required',
            'infrafunc' => 'required',
            'requester' => 'required',
            'background' => 'required',
            'step' => 'required',
            'summary' => 'required',
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

        $iib9 = IIB9::find($id);
        $data = ([
            'title' => $request->title,
            'time' => $request->date,
            'room_id' => $request->room,
            'infra_name' => $request->infraname,
            'infra_type_id' => $request->infratype,
            'infra_func' => $request->infrafunc,
            'background' => $request->background,
            'step' => $request->step,
            'summary' => $request->summary,
            'requester' => $request->requester,
            'documentation' => $docPath == '' ? $iib9->documentation : $docPath,
            'approval_letter' => $approvalLetterPath == '' ? $iib9->approval_letter : $approvalLetterPath,
            'supervisor_id' => $request->supervisor,
        ]);
        $iib9->update($data);

        return redirect('/IIB9')->with('success-create', 'Data Bukti Fisik telah diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $iib9 = IIB9::find($id);
        $iib9->delete();
        return redirect('/IIB9')->with('success-delete', 'Data Butir Kegiatan telah dihapus!');
    }

    public function getData(Request $request)
    {
        $recordsTotal = IIB9::count();
        $recordsFiltered = IIB9::where('title', 'like', '%' . $request->search["value"] . '%')
            ->count();

        $orderColumn = 'time';
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
        $activities = IIB9::where('title', 'like', '%' . $request->search["value"] . '%')
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
        $iib9 = array(IIB9::find($id));

        $processor = new TemplateProcessor();
        $processor->generateWordFile($user, $iib9, function ($phpWord, $table, $iib9) {
            TemplateContentProcessor::generateIIB9WordContent($phpWord, $table, $iib9);
        });
    }

    public function generateApproval($id)
    {
        $processor = new TemplateProcessor();
        $iib9 = array(IIB9::find($id));

        return $processor->generateiib9ApprovalLetter($iib9);
    }

    public function generateByPeriode(Request $request)
    {
        $begin = null;
        $end = null;
        if ($request->periode != null) {
            $periodeJson = json_decode($request->periode, true);
            $begin = $periodeJson[0];
            $end = $periodeJson[1];
        } else {
            $thisPeriod = Utilities::getSemesterPeriode(date('Y-m-d', strtotime('-6 months')));
            $begin = $thisPeriod[0];
            $end = $thisPeriod[1];
        }
        $user = UserData::find(1);
        $iib9 = IIB9::where('time', '>=', $begin)->where('time', '<=', $end)->where('user_data_id', '=', $user->id)->get();

        if (count($iib9) > 0) {
            $processor = new TemplateProcessor();
            $processor->generateWordFile($user, $iib9, function ($phpWord, $table, $iib9) {
                TemplateContentProcessor::generateIIB9WordContent($phpWord, $table, $iib9);
            });
        }
    }

    public function generateApprovalByPeriode(Request $request)
    {
        $begin = null;
        $end = null;
        if ($request->periode != null) {
            $periodeJson = json_decode($request->periode, true);
            $begin = $periodeJson[0];
            $end = $periodeJson[1];
        } else {
            $thisPeriod = Utilities::getSemesterPeriode(date('Y-m-d', strtotime('-6 months')));
            $begin = $thisPeriod[0];
            $end = $thisPeriod[1];
        }
        $user = UserData::find(1);
        $iib9 = IIB9::where('time', '>=', $begin)->where('time', '<=', $end)->where('user_data_id', '=', $user->id)->get();

        if (count($iib9) > 0) {
            $processor = new TemplateProcessor();
            $processor->generateiib9ApprovalLetter($iib9);
        }
    }

    public function showGenerateByPeriode()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.9'])->first();
        $dupakperiod = Utilities::getAllPeriodeToDate();

        return view('iib9/generate-periode-iib9', [
            'butirkeg' => $butirkegiatan,
            'periodes' => $dupakperiod
        ]);
    }

    public function showGenerateApprovalByPeriode()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.9'])->first();
        $dupakperiod = Utilities::getAllPeriodeToDate();

        return view('iib9/generate-approval-periode-iib9', [
            'butirkeg' => $butirkegiatan,
            'periodes' => $dupakperiod
        ]);
    }
}
