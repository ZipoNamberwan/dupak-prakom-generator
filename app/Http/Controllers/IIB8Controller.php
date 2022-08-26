<?php

namespace App\Http\Controllers;

use App\Helpers\TemplateContentProcessor;
use App\Helpers\TemplateProcessor;
use App\Helpers\Utilities;
use App\Models\ButirKegiatan;
use App\Models\IIB8;
use App\Models\IIB8InfraType;
use App\Models\InfraType;
use App\Models\Room;
use App\Models\Supervisor;
use App\Models\UserData;
use Illuminate\Http\Request;

class IIB8Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.8'])->first();
        return view('iib8/index-iib8', ['butirkeg' => $butirkegiatan]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.8'])->first();
        $rooms = Room::all();
        $infratypes = InfraType::all();
        $supervisors = Supervisor::all();
        $preferredsp = Supervisor::where(['is_preference' => true])->first()->id;

        return view('iib8/create-iib8', [
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
            'requester' => 'required',
            'result' => 'required',
            'step' => 'required',
            'summary' => 'required',
            'supervisor' => 'required',
            'infraname.*' => 'required',
            'infratype.*' => 'required',
            'maintenance_summary' => 'required',
        ]);

        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.8'])->first();

        $docPath = null;
        if ($request->hasFile('documentation')) {
            $docPath = $request->file('documentation')->store('images', 'public');
        }

        $approvalLetterPath = null;
        if ($request->hasFile('approval_letter')) {
            $approvalLetterPath = $request->file('approval_letter')->store('images', 'public');
        }

        $iib8 = IIB8::create([
            'title' => $request->title,
            'time' => $request->date,
            'room_id' => $request->room,
            'result' => $request->result,
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
            'maintenance_summary' => $request->maintenance_summary
        ]);

        for ($i = 0; $i < count($request->infraname); $i++) {
            IIB8InfraType::create([
                'IIB8_id' => $iib8->id,
                'infra_type_id' => $request->infratype[$i],
                'infra_name' => $request->infraname[$i],
            ]);
        }

        return redirect('/IIB8')->with('success-create', 'Butir Kegiatan II.B.8 telah ditambah!');
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
        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.8'])->first();
        $rooms = Room::all();
        $infratypes = InfraType::all();
        $supervisors = Supervisor::all();
        $iib8 = IIB8::find($id);

        return view('iib8/edit-iib8', [
            'butirkeg' => $butirkegiatan,
            'infratypes' => $infratypes,
            'rooms' => $rooms,
            'supervisors' => $supervisors,
            'iib8' => $iib8,

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
            'requester' => 'required',
            'result' => 'required',
            'step' => 'required',
            'summary' => 'required',
            'supervisor' => 'required',
            'infraname.*' => 'required',
            'infratype.*' => 'required',
            'maintenance_summary' => 'required',
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

        $iib8 = IIB8::find($id);
        $data = ([
            'title' => $request->title,
            'time' => $request->date,
            'room_id' => $request->room,
            'result' => $request->result,
            'step' => $request->step,
            'summary' => $request->summary,
            'requester' => $request->requester,
            'documentation' => $docPath == '' ? $iib8->documentation : $docPath,
            'approval_letter' => $approvalLetterPath == '' ? $iib8->approval_letter : $approvalLetterPath,
            'supervisor_id' => $request->supervisor,
            'maintenance_summary' => $request->maintenance_summary
        ]);
        $iib8->update($data);

        if ($request->removedinfra) {
            IIB8InfraType::whereIn('id', $request->removedinfra)->delete();
        }

        for ($i = 0; $i < count($request->infratype); $i++) {
            $infra = new IIB8InfraType();
            if ($request->infraid[$i]) {
                $infra = IIB8InfraType::find($request->infraid[$i]);
            }
            $infra->IIB8_id = $iib8->id;
            $infra->infra_type_id = $request->infratype[$i];
            $infra->infra_name = $request->infraname[$i];
            $infra->save();
        }

        return redirect('/IIB8')->with('success-create', 'Data Bukti Fisik telah diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $iib8 = IIB8::find($id);
        $iib8->delete();
        return redirect('/IIB8')->with('success-delete', 'Data Butir Kegiatan telah dihapus!');
    }

    public function getData(Request $request)
    {
        $recordsTotal = IIB8::count();
        $recordsFiltered = IIB8::where('title', 'like', '%' . $request->search["value"] . '%')
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
        $activities = IIB8::where('title', 'like', '%' . $request->search["value"] . '%')
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
        $iib9 = array(IIB8::find($id));

        $processor = new TemplateProcessor();
        $processor->generateWordFile($user, $iib9, function ($phpWord, $table, $iib9) {
            TemplateContentProcessor::generateIIB8WordContent($phpWord, $table, $iib9);
        });
    }

    public function generateApproval($id)
    {
        $processor = new TemplateProcessor();
        $iib8 = array(IIB8::find($id));

        return $processor->generateiib8ApprovalLetter($iib8);
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
        $iib9 = IIB8::where('time', '>=', $begin)->where('time', '<=', $end)->where('user_data_id', '=', $user->id)->get();

        if (count($iib9) > 0) {
            $processor = new TemplateProcessor();
            $processor->generateWordFile($user, $iib9, function ($phpWord, $table, $iib9) {
                TemplateContentProcessor::generateIIB8WordContent($phpWord, $table, $iib9);
            });
        }
    }
}
