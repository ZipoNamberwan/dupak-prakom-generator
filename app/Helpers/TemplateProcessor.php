<?php

namespace App\Helpers;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

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
            $cell->addText($a->supervisorDetail->pos, null, 'normalCenter');
            $cell = $table->addCell(5000, array('gridSpan' => 2, 'valign' => 'top'));
            $cell->addText('Probolinggo, 31 Januari 2022', null, 'normalCenter');
            $cell->addText('Pejabat Pranata Komputer', null, 'normalCenter');
            $table->addRow();
            $cell = $table->addCell(5000, array('gridSpan' => 2, 'valign' => 'top'));
            $cell->addTextBreak();
            $cell->addTextBreak();
            $cell->addTextBreak();
            $cell->addText($a->supervisorDetail->name, null, 'normalCenter');
            $cell->addText('NIP: ' . $a->supervisorDetail->nip, null, 'normalCenter');
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

    public function generateiib12ApprovalLetter($activities)
    {
        $this->phpWord->setDefaultFontSize(11);
        $this->phpWord->setDefaultFontName('calibri');
        $this->phpWord->addParagraphStyle('normal', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 0));
        $this->phpWord->addFontStyle('title', array('size' => 20, 'bold' => true));

        $this->phpWord->addNumberingStyle(
            'multilevel',
            array(
                'type' => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 720, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'upperLetter', 'text' => '%2.', 'left' => 1080, 'hanging' => 360, 'tabPos' => 720),
                )
            )
        );

        foreach ($activities as $a) {
            $section = $this->phpWord->addSection();
            $section->addText(
                $a->type == 'detect' ? 'Lembar Persetujuan Deteksi Masalah Infrastruktur TI'
                    : 'Lembar Persetujuan Perbaikan Masalah Infrastruktur TI',
                array('bold' => true, 'size' => 17),
                array('alignment' => Jc::CENTER)
            );
            $section->addTextBreak();
            $section->addText('Infrastruktur TI berikut:', null, 'normal');
            $section->addListItem('Nama Infrastruktur' . "\t\t\t" . ': ' . $a->infra_name, 0, null, 'multilevel', 'normal');
            $section->addListItem('Jenis Infrastruktur' . "\t\t\t" . ': ' . $a->infraTypeDetail->name, 0, null, 'multilevel', 'normal');
            $section->addListItem('Lokasi Infrastruktur' . "\t\t\t" . ': ' . $a->roomDetail->name, 0, null, 'multilevel', 'normal');

            if ($a->type == 'detect') {
                $section->addText('pada tanggal ' . date("d F Y", strtotime($a->time)) . ', Tim IT (' . $a->userDataDetail->name . ') telah mendeteksi dan menganalisis permasalahannya yaitu ' . $a->problem_summary . '.', null, 'normal');
            } else {
                $section->addText('dengan permasalahan yaitu ' . $a->problem_summary . ', pada tanggal ' . date("d F Y", strtotime($a->time)) . ' Tim IT (' . $a->userDataDetail->name . ') telah melakukan perbaikan permasalahan infrastruktur IT tersebut. Hasilnya permasalahan tersebut sudah bisa teratasi.', null, 'normal');
            }

            $section->addTextBreak();

            $section->addText('Pemegang Infrastruktur', null, array('indent' => 6, 'alignment' => Jc::CENTER));
            $section->addTextBreak();
            $section->addTextBreak();
            $section->addText($a->requester, null, array('indent' => 6, 'alignment' => Jc::CENTER));
        }

        $file =  'Lembar Persetujuan ' . $activities[0]->butirKegiatanDetail->code . '.docx';
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($this->phpWord, 'Word2007');
        $xmlWriter->save("php://output");
    }

    public function generateiib9ApprovalLetter($activities)
    {
        $this->phpWord->setDefaultFontSize(11);
        $this->phpWord->setDefaultFontName('calibri');
        $this->phpWord->addParagraphStyle('normal', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 0));
        $this->phpWord->addFontStyle('title', array('size' => 20, 'bold' => true));

        $this->phpWord->addNumberingStyle(
            'multilevel',
            array(
                'type' => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 720, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'upperLetter', 'text' => '%2.', 'left' => 1080, 'hanging' => 360, 'tabPos' => 720),
                )
            )
        );

        foreach ($activities as $a) {
            $section = $this->phpWord->addSection();
            $section->addText(
                'Lembar Persetujuan Pemasangan Infrastruktur TI',
                array('bold' => true, 'size' => 17),
                array('alignment' => Jc::CENTER)
            );
            $section->addTextBreak();
            $section->addText('Infrastruktur TI berikut:', null, 'normal');
            $section->addListItem('Nama Infrastruktur' . "\t\t\t" . ': ' . $a->infra_name, 0, null, 'multilevel', 'normal');
            $section->addListItem('Jenis Infrastruktur' . "\t\t\t" . ': ' . $a->infraTypeDetail->name, 0, null, 'multilevel', 'normal');
            $section->addListItem('Lokasi Infrastruktur' . "\t\t\t" . ': ' . $a->roomDetail->name, 0, null, 'multilevel', 'normal');

            $section->addText('pada tanggal ' . date("d F Y", strtotime($a->time)) . ', Tim IT (' . $a->userDataDetail->name . ') telah melakukan pemasangan infrastruktur IT tersebut.', null, 'normal');

            $section->addTextBreak();

            $section->addText('Yang Menyatakan,', null, array('indent' => 6, 'alignment' => Jc::CENTER));
            $section->addText('Pemegang Infrastruktur', null, array('indent' => 6, 'alignment' => Jc::CENTER));
            $section->addTextBreak();
            $section->addTextBreak();
            $section->addText($a->requester, null, array('indent' => 6, 'alignment' => Jc::CENTER));
        }

        $file =  'Lembar Persetujuan ' . $activities[0]->butirKegiatanDetail->code . '.docx';
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
