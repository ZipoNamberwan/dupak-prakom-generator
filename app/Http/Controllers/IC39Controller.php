<?php

namespace App\Http\Controllers;

use App\Helpers\TemplateContentProcessor;
use App\Helpers\TemplateProcessor;
use App\Helpers\Utilities;
use App\Models\ButirKegiatan;
use App\Models\IC39;
use App\Models\InfraType;
use App\Models\Supervisor;
use App\Models\UserData;
use Illuminate\Http\Request;

class IC39Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'I.C.39'])->first();
        return view('ic39/index-ic39', ['butirkeg' => $butirkegiatan]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'I.C.39'])->first();
        $supervisors = Supervisor::all();
        $preferredsp = Supervisor::where(['is_preference' => true])->first()->id;

        return view('ic39/create-ic39', [
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
            'dataset' => 'required',
            'storage' => 'required',
            'supervisor' => 'required',
            'filename.*' => 'required',
            'date.*' => 'required',
        ]);

        $butirkegiatan = ButirKegiatan::where(['code' => 'I.C.39'])->first();

        for ($i = 0; $i < count($request->date); $i++) {

            $docPath = null;
            if ($request->file('documentation') != null) {
                if (array_key_exists($i, $request->file('documentation'))) {
                    $docPath = $request->file('documentation')[$i]->store('images', 'public');
                }
            }

            IC39::create([
                'title' => $request->title . ' pada Tanggal ' . Utilities::getFormattedDate($request->date[$i]),
                'time' => $request->date[$i],
                'dataset' => $request->dataset,
                'storage' => $request->storage,
                'filename' => $request->filename[$i],
                'supervisor_id' => $request->supervisor,
                'user_data_id' => 1,
                'location_id' => 1,
                'butir_kegiatan_id' => $butirkegiatan->id,
                'documentation' => $docPath,
            ]);
        }

        return redirect('/IC39')->with('success-create', 'Butir Kegiatan I.C.39 telah ditambah!');
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
        $butirkegiatan = ButirKegiatan::where(['code' => 'I.C.39'])->first();
        $infratypes = InfraType::all();
        $supervisors = Supervisor::all();
        $ic39 = IC39::find($id);

        Utilities::getFormattedDate($ic39->time);
        return view('ic39/edit-ic39', [
            'butirkeg' => $butirkegiatan,
            'infratypes' => $infratypes,
            'supervisors' => $supervisors,
            'ic39' => $ic39,

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
            'dataset' => 'required',
            'storage' => 'required',
            'supervisor' => 'required',
            'filename.*' => 'required',
            'date.*' => 'required',
        ]);

        $docPath = '';
        if ($request->hasFile('documentation')) {
            $image = $request->file('documentation');
            $docPath = $image->store('images', 'public');
        }

        $ic39 = IC39::find($id);
        $data = ([
            'title' => $request->title,
            'time' => $request->date,
            'dataset' => $request->dataset,
            'storage' => $request->storage,
            'filename' => $request->filename,
            'supervisor_id' => $request->supervisor,
            'documentation' => $docPath,
            'documentation' => $docPath == '' ? $ic39->documentation : $docPath,
        ]);
        $ic39->update($data);

        return redirect('/IC39')->with('success-create', 'Data Bukti Fisik telah diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ic39 = IC39::find($id);
        $ic39->delete();
        return redirect('/IC39')->with('success-delete', 'Data Butir Kegiatan telah dihapus!');
    }

    public function getData(Request $request)
    {
        $recordsTotal = IC39::count();
        $recordsFiltered = IC39::where('title', 'like', '%' . $request->search["value"] . '%')
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
            } else if ($request->order[0]['column'] == '3') {
                $orderColumn = 'dataset';
            }
        }
        $activities = IC39::where('title', 'like', '%' . $request->search["value"] . '%')
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
            $activityData["dataset"] = $activity->dataset;
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
        $ic39 = array(IC39::find($id));

        $processor = new TemplateProcessor();
        $processor->generateWordFile($user, $ic39, function ($phpWord, $table, $ic39) {
            TemplateContentProcessor::generateIC39WordContent($phpWord, $table, $ic39);
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
        $ic39 = IC39::where('time', '>=', $begin)->where('time', '<=', $end)->where('user_data_id', '=', $user->id)->orderBy('time')->get();

        if (count($ic39) > 0) {
            $processor = new TemplateProcessor();
            $processor->generateWordFile($user, $ic39, function ($phpWord, $table, $ic39) {
                TemplateContentProcessor::generateIC39WordContent($phpWord, $table, $ic39);
            });
        }
    }

    public function showGenerateByPeriode()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'I.C.39'])->first();
        $dupakperiod = Utilities::getAllPeriodeToDate();

        return view('ic39/generate-periode-ic39', [
            'butirkeg' => $butirkegiatan,
            'periodes' => $dupakperiod
        ]);
    }
}
