<?php

namespace App\Http\Controllers;

use App\Helpers\TemplateContentProcessor;
use App\Helpers\TemplateProcessor;
use App\Helpers\Utilities;
use App\Models\ButirKegiatan;
use App\Models\IB21;
use App\Models\IB21Service;
use App\Models\ServiceMedia;
use App\Models\ServiceType;
use App\Models\Supervisor;
use App\Models\UserData;
use App\Rules\IB21Period;
use Illuminate\Http\Request;

class IB21Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'I.B.21'])->first();
        return view('ib21/index-ib21', ['butirkeg' => $butirkegiatan]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'I.B.21'])->first();
        $supervisors = Supervisor::all();
        $preferredsp = Supervisor::where(['is_preference' => true])->first()->id;
        $servicetypes = ServiceType::all();
        $servicemedias = ServiceMedia::all();

        return view('ib21/create-ib21', [
            'butirkeg' => $butirkegiatan,
            'supervisors' => $supervisors,
            'preferredsp' => $preferredsp,
            'servicetypes' => $servicetypes,
            'servicemedias' => $servicemedias,
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
            'date' => [
                'required',
                new IB21Period()
            ],
            'supervisor' => 'required',
            'description.*' => 'required',
            'servicemedia.*' => 'required',
            'servicetype.*' => 'required',
            'service.*' => 'required',
        ]);


        $butirkegiatan = ButirKegiatan::where(['code' => 'I.B.21'])->first();

        $ib21 = IB21::create([
            'title' => $request->title,
            'time' => $request->date,
            'user_data_id' => 1,
            'location_id' => 1,
            'butir_kegiatan_id' => $butirkegiatan->id,
            'supervisor_id' => $request->supervisor,
        ]);

        for ($i = 0; $i < count($request->servicetype); $i++) {
            IB21Service::create([
                'Ib21_id' => $ib21->id,
                'time' => $request->servicedate[$i],
                'description' => $request->description[$i],
                'service_type_id' => $request->servicetype[$i],
                'service_media_id' => $request->servicemedia[$i],
                'service' => $request->service[$i],
                'requester' => $request->requester[$i],
                'created_by' => 'admin'
            ]);
        }

        return redirect('/IB21')->with('success-create', 'Butir Kegiatan I.B.21 telah ditambah!');
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
        $butirkegiatan = ButirKegiatan::where(['code' => 'I.B.21'])->first();
        $supervisors = Supervisor::all();
        $servicetypes = ServiceType::all();
        $servicemedias = ServiceMedia::all();
        $ib21 = IB21::find($id);

        return view('ib21/edit-ib21', [
            'butirkeg' => $butirkegiatan,
            'supervisors' => $supervisors,
            'servicetypes' => $servicetypes,
            'servicemedias' => $servicemedias,
            'ib21' => $ib21,
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
            'supervisor' => 'required',
            'description.*' => 'required',
            'servicemedia.*' => 'required',
            'servicetype.*' => 'required',
            'service.*' => 'required',
        ]);

        $ib21 = IB21::find($id);
        $data = ([
            'title' => $request->title,
            'time' => $request->date,
            'supervisor_id' => $request->supervisor,
        ]);
        $ib21->update($data);

        if ($request->removedservice) {
            IB21Service::whereIn('id', $request->removedservice)->delete();
        }

        for ($i = 0; $i < count($request->service); $i++) {
            $service = new IB21Service();
            if ($request->serviceid[$i]) {
                $service = IB21Service::find($request->serviceid[$i]);
            }
            $service->IB21_id = $ib21->id;
            $service->time = $request->servicedate[$i];
            $service->description = $request->description[$i];
            $service->service_type_id = $request->servicetype[$i];
            $service->service_media_id = $request->servicemedia[$i];
            $service->service = $request->service[$i];
            $service->requester = $request->requester[$i];
            $service->created_by = 'admin';

            $service->save();
        }

        return redirect('/IB21')->with('success-create', 'Data Bukti Fisik telah diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ib21 = IB21::find($id);
        $ib21->delete();
        return redirect('/IB21')->with('success-delete', 'Data Butir Kegiatan telah dihapus!');
    }

    public function getData(Request $request)
    {
        $recordsTotal = IB21::count();
        $recordsFiltered = IB21::where('title', 'like', '%' . $request->search["value"] . '%')
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
                $orderColumn = 'periode';
            }
        }
        $activities = IB21::where('title', 'like', '%' . $request->search["value"] . '%')
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
            $activityData["periode"] =  $activity->time;
            $activityData["services_number"] = count($activity->services);
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
        $ib21 = array(IB21::find($id));

        $processor = new TemplateProcessor();
        $processor->generateWordFile($user, $ib21, function ($phpWord, $table, $ib21) {
            TemplateContentProcessor::generateIB21WordContent($phpWord, $table, $ib21);
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
        $ib21 = IB21::where('time', '>=', $begin)->where('time', '<=', $end)->where('user_data_id', '=', $user->id)->get();

        if (count($ib21) > 0) {
            $processor = new TemplateProcessor();
            $processor->generateWordFile($user, $ib21, function ($phpWord, $table, $ib21) {
                TemplateContentProcessor::generateIB21WordContent($phpWord, $table, $ib21);
            });
        }
    }

    public function showGenerateByPeriode()
    {
        $butirkegiatan = ButirKegiatan::where(['code' => 'I.B.21'])->first();
        $dupakperiod = Utilities::getAllPeriodeToDate();

        return view('ib21/generate-periode-ib21', [
            'butirkeg' => $butirkegiatan,
            'periodes' => $dupakperiod
        ]);
    }
}
