<?php

namespace App\Http\Controllers;

use App\Helpers\TemplateContentProcessor;
use App\Helpers\TemplateProcessor;
use App\Helpers\Utilities;
use App\Models\ButirKegiatan;
use App\Models\IIIC8;
use App\Models\Supervisor;
use App\Models\UserData;
use Illuminate\Http\Request;

class IIIC8Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'III.C.8'])->first();
        return view('iiic8/index-iiic8', ['butirkeg' => $butirkegiatan]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'III.C.8'])->first();
        $supervisors = Supervisor::all();
        $preferredsp = Supervisor::where(['is_preference' => true])->first()->id;

        return view('iiic8/create-iiic8', [
            'butirkeg' => $butirkegiatan,
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
            'background' => 'required',
            'data' => 'required',
            'tools' => 'required',
            'link' => 'required',
            'supervisor' => 'required',
        ]);

        $butirkegiatan = ButirKegiatan::where(['code' => 'III.C.8'])->first();

        IIIC8::create([
            'title' => $request->title,
            'time' => $request->date,
            'background' => $request->background,
            'data' => $request->data,
            'tools' => $request->tools,
            'link' => $request->link,
            'user_data_id' => 1,
            'location_id' => 1,
            'butir_kegiatan_id' => $butirkegiatan->id,
            'supervisor_id' => $request->supervisor,
        ]);

        return redirect('/IIIC8')->with('success-create', 'Butir Kegiatan III.C.8 telah ditambah!');
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
        $butirkegiatan = ButirKegiatan::where(['code' => 'III.C.8'])->first();
        $supervisors = Supervisor::all();
        $iiic8 = IIIC8::find($id);

        return view('iiic8/edit-iiic8', [
            'butirkeg' => $butirkegiatan,
            'supervisors' => $supervisors,
            'iiic8' => $iiic8
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
            'background' => 'required',
            'data' => 'required',
            'tools' => 'required',
            'link' => 'required',
            'supervisor' => 'required',
        ]);

        $iiic8 = IIIC8::find($id);
        $data = ([
            'title' => $request->title,
            'time' => $request->date,
            'background' => $request->background,
            'data' => $request->data,
            'tools' => $request->tools,
            'link' => $request->link,
            'supervisor_id' => $request->supervisor,
        ]);
        $iiic8->update($data);

        return redirect('/IIIC8')->with('success-create', 'Data Bukti Fisik telah diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $iiic8 = IIIC8::find($id);
        $iiic8->delete();
        return redirect('/IIIC8')->with('success-delete', 'Data Butir Kegiatan telah dihapus!');
    }

    public function getData(Request $request)
    {
        $recordsTotal = IIIC8::count();
        $recordsFiltered = IIIC8::where('title', 'like', '%' . $request->search["value"] . '%')
            ->count();

        $orderColumn = 'time';
        $orderDir = 'DESC';
        if ($request->order != null) {
            if ($request->order[0]['dir'] == 'asc') {
                $orderDir = 'asc';
            } else {
                $orderDir = 'desc';
            }
            if ($request->order[0]['column'] == '1') {
                $orderColumn = 'time';
            } else if ($request->order[0]['column'] == '2') {
                $orderColumn = 'title';
            }
        }
        $activities = IIIC8::where('title', 'like', '%' . $request->search["value"] . '%')
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
        $iiic8 = array(IIIC8::find($id));

        $processor = new TemplateProcessor();
        $processor->generateWordFile($user, $iiic8, function ($phpWord, $table, $iiic8) {
            TemplateContentProcessor::generateIIIC8WordContent($phpWord, $table, $iiic8);
        });
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
        $iiic8 = IIIC8::where('time', '>=', $begin)->where('time', '<=', $end)->where('user_data_id', '=', $user->id)->orderBy('time')->get();

        if (count($iiic8) > 0) {
            $processor = new TemplateProcessor();
            $processor->generateWordFile($user, $iiic8, function ($phpWord, $table, $iiic8) {
                TemplateContentProcessor::generateIIIC8WordContent($phpWord, $table, $iiic8);
            });
        }
    }

    public function showGenerateByPeriode()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.12'])->first();
        $dupakperiod = Utilities::getAllPeriodeToDate();

        return view('iiic8/generate-periode-iiic8', [
            'butirkeg' => $butirkegiatan,
            'periodes' => $dupakperiod
        ]);
    }
}
