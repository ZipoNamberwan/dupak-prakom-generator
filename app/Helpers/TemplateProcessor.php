<?php

namespace App\Helpers;

use PhpOffice\PhpWord\PhpWord;

class TemplateProcessor
{
    private $phpWord;

    function __construct()
    {
        $this->phpWord = new PhpWord();
    }

    public function generateWordFile($user, $activities, $callback, $addLastPageBreak = false)
    {
        $this->phpWord->setDefaultFontSize(11);
        $this->phpWord->setDefaultFontName('calibri');

        $tableStyle = array(
            'borderColor' => '000000',
            'borderSize'  => 6,
            'cellMargin'  => 70
        );
        $this->phpWord->addTableStyle('myTable', $tableStyle);
        $this->phpWord->addParagraphStyle('normal', array('spaceAfter' => 0));
        $this->phpWord->addParagraphStyle('normalCenter', array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0));

        $i = 0;
        foreach ($activities as $a) {
            $section = $this->phpWord->addSection();

            $table = $section->addTable('myTable');
            $table->addRow();
            $tableCellStyle = array('valign' => 'top');

            $table->addCell(7500, array('gridSpan' => 3, 'valign' => 'top'))->addText('BUKTI FISIK KEGIATAN PRANATA KOMPUTER KEAHLIAN', array('bold' => true), 'normalCenter');
            $table->addCell(2500, $tableCellStyle)->addText('Halaman: 1 dari 5', null, 'normal');
            $table->addRow();
            $table->addCell(2500, $tableCellStyle)->addText('Nama PPK', array('bold' => true), 'normal');
            $table->addCell(2500, $tableCellStyle)->addText($user->name, null, 'normal');
            $table->addCell(2500, $tableCellStyle)->addText('Tanggal', array('bold' => true), 'normal');
            $table->addCell(2500, $tableCellStyle)->addText(date("d F Y", strtotime($a->time)), null, 'normal');
            $table->addRow();
            $table->addCell(2500, $tableCellStyle)->addText('NIP', array('bold' => true), 'normal');
            $table->addCell(2500, $tableCellStyle)->addText($user->nip, null, 'normal');
            $table->addCell(2500, $tableCellStyle)->addText('Lokasi Pekerjaan', array('bold' => true), 'normal');
            $table->addCell(2500, $tableCellStyle)->addText($a->locationDetail->name, null, 'normal');
            $table->addRow();
            $table->addCell(2500, $tableCellStyle)->addText('Pangkat/Golongan', array('bold' => true), 'normal');
            $table->addCell(2500, $tableCellStyle)->addText($user->grade, null, 'normal');
            $table->addCell(2500, $tableCellStyle)->addText('Angka Kredit', array('bold' => true), 'normal');
            $table->addCell(2500, $tableCellStyle)->addText($a->butirKegiatanDetail->credit, null, 'normal');
            $table->addRow();
            $table->addCell(2500, $tableCellStyle)->addText('Jenjang Jabatan', array('bold' => true), 'normal');
            $table->addCell(2500, $tableCellStyle)->addText($user->pos, null, 'normal');
            $table->addCell(2500, array('vMerge' => 'restart'))->addText('Nomor Urut di laporan kegiatan', array('bold' => true), 'normal');
            $table->addCell(2500, array('vMerge' => 'restart'))->addText($a->butirKegiatanDetail->subUnsurDetail->code . '.xxx', null, 'normal');
            $table->addRow();
            $table->addCell(5000, array('gridSpan' => 2, 'valign' => 'top'))->addText('BUTIR KEGIATAN: ' . $a->butirKegiatanDetail->code . ' ' . $a->butirKegiatanDetail->name, array('bold' => true), 'normal');
            $table->addCell(2500, array('vMerge' => 'continue'));
            $table->addCell(2500, array('vMerge' => 'continue'));
            $table->addRow();
            $table->addCell(10000, array('gridSpan' => 4, 'valign' => 'top'))->addText($a->title, array('bold' => true), 'normalCenter');
            $table->addRow();
            $table->addCell(10000, array('gridSpan' => 4, 'valign' => 'top'))->addText('Item Bukti Fisik*:', array('bold' => true), 'normal');
            $table->addRow();

            call_user_func($callback, $this->phpWord, $table, $a);

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

            $i++;
            if ($i != count($activities))
                $section->addPageBreak();
        }

        if ($addLastPageBreak) {
            $section->addPageBreak();
        }

        $file = $activities[0]->butirKegiatanDetail->code . '.docx';
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($this->phpWord, 'Word2007');
        $xmlWriter->save("php://output");
    }
}
