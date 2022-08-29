<?php

namespace App\Http\Controllers;

use App\Models\ButirKegiatan;
use App\Models\IB21;
use App\Models\ServiceMedia;
use App\Models\ServiceType;
use App\Models\Supervisor;
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
        //
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
        //
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
        //
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
            $activityData["service_number"] = count($activity->services);
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
}
