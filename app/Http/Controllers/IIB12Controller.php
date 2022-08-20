<?php

namespace App\Http\Controllers;

use App\Models\ButirKegiatan;
use App\Models\IIB12;
use App\Models\InfraType;
use App\Models\Room;
use App\Models\UserData;
use Codedge\Fpdf\Facades\Fpdf;
use Illuminate\Http\Request;
use PDF;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

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
        return view('iib12/create-iib12', ['butirkeg' => $butirkegiatan, 'infratypes' => InfraType::all(), 'rooms' => $rooms]);
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
        ]);

        $butirkegiatan = ButirKegiatan::where(['code' => 'II.B.12'])->first();

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
        $iib12 = IIB12::find($id);

        $phpWord = new PhpWord();
        $phpWord->setDefaultFontSize(11);
        $phpWord->setDefaultFontName('calibri');
        $section = $phpWord->addSection();

        $tableStyle = array(
            'borderColor' => '000000',
            'borderSize'  => 6,
            'cellMargin'  => 70
        );
        $phpWord->addTableStyle('myTable', $tableStyle);
        $phpWord->addParagraphStyle('normal', array('spaceAfter' => 0));
        $phpWord->addParagraphStyle('normalCenter', array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0));

        $table = $section->addTable('myTable');
        $table->addRow();
        $tableCellStyle = array('valign' => 'top');

        $table->addCell(8000, array('gridSpan' => 3, 'valign' => 'top'))->addText('BUKTI FISIK KEGIATAN PRANATA KOMPUTER KEAHLIAN', array('bold' => true), 'normalCenter');
        $table->addCell(2000, $tableCellStyle)->addText('Halaman: 1 dari 5', null, 'normal');
        $table->addRow();
        $table->addCell(3000, $tableCellStyle)->addText('Nama PPK', array('bold' => true), 'normal');
        $table->addCell(3000, $tableCellStyle)->addText($user->name, null, 'normal');
        $table->addCell(2000, $tableCellStyle)->addText('Tanggal', array('bold' => true), 'normal');
        $table->addCell(2000, $tableCellStyle)->addText(date("d F Y", strtotime($iib12->time)), null, 'normal');
        $table->addRow();
        $table->addCell(3000, $tableCellStyle)->addText('NIP', array('bold' => true), 'normal');
        $table->addCell(3000, $tableCellStyle)->addText($user->nip, null, 'normal');
        $table->addCell(2000, $tableCellStyle)->addText('Lokasi Pekerjaan', array('bold' => true), 'normal');
        $table->addCell(2000, $tableCellStyle)->addText($iib12->locationDetail->name, null, 'normal');
        $table->addRow();
        $table->addCell(3000, $tableCellStyle)->addText('Pangkat/Golongan', array('bold' => true), 'normal');
        $table->addCell(3000, $tableCellStyle)->addText($user->grade, null, 'normal');
        $table->addCell(2000, $tableCellStyle)->addText('Angka Kredit', array('bold' => true), 'normal');
        $table->addCell(2000, $tableCellStyle)->addText($iib12->butirKegiatanDetail->credit, null, 'normal');
        $table->addRow();
        $table->addCell(3000, $tableCellStyle)->addText('Jenjang Jabatan', array('bold' => true), 'normal');
        $table->addCell(3000, $tableCellStyle)->addText($user->pos, null, 'normal');
        $table->addCell(2000, array('vMerge' => 'restart'))->addText('Nomor Urut di laporan kegiatan', array('bold' => true), 'normal');
        $table->addCell(2000, array('vMerge' => 'restart'))->addText($iib12->butirKegiatanDetail->subUnsurDetail->code . '.xxx', null, 'normal');
        $table->addRow();
        $table->addCell(6000, array('gridSpan' => 2, 'valign' => 'top'))->addText('BUTIR KEGIATAN: ' . $iib12->butirKegiatanDetail->code . ' ' . $iib12->butirKegiatanDetail->name, array('bold' => true), 'normal');
        $table->addCell(2000, array('vMerge' => 'continue'));
        $table->addCell(2000, array('vMerge' => 'continue'));
        $table->addRow();
        $table->addCell(10000, array('gridSpan' => 4, 'valign' => 'top'))->addText($iib12->title, array('bold' => true), 'normalCenter');
        $table->addRow();
        $table->addCell(10000, array('gridSpan' => 4, 'valign' => 'top'))->addText('Item Bukti Fisik*:', array('bold' => true), 'normal');
        $table->addRow();
        //content here
        $phpWord->addParagraphStyle('normalContent', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 0.5));
        $phpWord->addParagraphStyle('normalContentIndent1', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 1));
        $phpWord->addNumberingStyle(
            'numbering',
            array(
                'type'   => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'bullet', 'text' => '●', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                ),
            )
        );
        $cell = $table->addCell(10000, array('gridSpan' => 4, 'valign' => 'top'));
        $cell->addListItem('Waktu dan Tempat', 0, null, 'numbering');
        $cell->addText(date("d F Y", strtotime($iib12->time)) . ' di ' . $iib12->roomDetail->name, null, 'normalContent');
        $cell->addListItem('Informasi Insfrastruktur', 0, null, 'numbering');
        $cell->addListItem('Nama' . "\t\t" . ': ' . $iib12->infra_name, 1, null, 'numbering');
        $cell->addListItem('Jenis' . "\t\t" . ': ' . $iib12->infraTypeDetail->name, 1, null, 'numbering');
        $cell->addListItem('Fungsi Perangkat', 0, null, 'numbering');
        $cell->addText($iib12->infra_func, null, 'normalContent');
        $cell->addListItem('Identifikasi Masalah', 0, null, 'numbering');
        $cell->addListItem('Latar Belakang Permasalahan', 1, null, 'numbering');
        $cell->addText($iib12->background, null, 'normalContentIndent1');
        $cell->addListItem('Identifikasi Permasalahan', 1, null, 'numbering');
        $cell->addText($iib12->problem_ident, null, 'normalContentIndent1');
        $cell->addListItem('Analisa Permasalahan', 1, null, 'numbering');
        $cell->addText($iib12->problem_analysis, null, 'normalContentIndent1');

        $cell->addListItem('Dokumentasi', 0, null, 'numbering');
        $cell->addListItem('Lembar Persetujuan', 0, null, 'numbering');
        $cell->addText('Scan', null, 'normalContent');

        //content close here

        $table->addRow();
        $table->addCell(10000, array('gridSpan' => 4, 'valign' => 'top'))->addText('Keterangan:', array('bold' => true), 'normal');
        $table->addRow();
        $cell = $table->addCell(5000, array('gridSpan' => 2, 'valign' => 'top'));
        $cell->addText('Mengetahui', null, 'normalCenter');
        $cell->addText('Kepala BPS Kabupaten Probolinggo', null, 'normalCenter');
        $cell = $table->addCell(5000, array('gridSpan' => 2, 'valign' => 'top'));
        $cell->addText('Probolinggo, 31 Januari 2022', null, 'normalCenter');
        $cell->addText('Pejabat Pranata Komputer', null, 'normalCenter');
        $table->addRow();
        $cell = $table->addCell(5000, array('gridSpan' => 2, 'valign' => 'top'));
        $cell->addTextBreak();
        $cell->addTextBreak();
        $cell->addTextBreak();
        $cell->addText('Syaiful Rahman, S.E, M.T', null, 'normalCenter');
        $cell->addText('NIP: 19640621 198802 1 001', null, 'normalCenter');
        $cell = $table->addCell(5000, array('gridSpan' => 2, 'valign' => 'top'));
        $cell->addTextBreak();
        $cell->addTextBreak();
        $cell->addTextBreak();
        $cell->addText($user->name, null, 'normalCenter');
        $cell->addText('NIP: ' . $user->nip, null, 'normalCenter');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('template/iib12_result2.docx');
    }
}
