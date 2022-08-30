<?php

namespace App\Helpers;

use PhpOffice\PhpWord\ComplexType\TblWidth;

class TemplateContentProcessor
{
    public static function generateIIB12WordContent($phpWord, $table, $iib12)
    {
        //content here
        $phpWord->addParagraphStyle('normalContent', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 0.5));
        $phpWord->addParagraphStyle('normalContentIndent1', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 1));
        $phpWord->addNumberingStyle(
            'numbering' . $iib12->id,
            array(
                'type'   => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'bullet', 'text' => '●', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                ),
            )
        );
        $cell = $table->addCell(10000, array('gridSpan' => 4, 'valign' => 'top'));
        $cell->addListItem('Waktu dan Tempat', 0, null, 'numbering' . $iib12->id);
        $cell->addText(date("d F Y", strtotime($iib12->time)) . ' di ' . $iib12->roomDetail->name, null, 'normalContent');
        $cell->addListItem('Informasi Insfrastruktur', 0, null, 'numbering' . $iib12->id);
        $cell->addListItem('Nama' . "\t\t" . ': ' . $iib12->infra_name, 1, null, 'numbering' . $iib12->id);
        $cell->addListItem('Jenis' . "\t\t" . ': ' . $iib12->infraTypeDetail->name, 1, null, 'numbering' . $iib12->id);
        $cell->addListItem('Fungsi Perangkat', 0, null, 'numbering' . $iib12->id);
        $cell->addText($iib12->infra_func, null, 'normalContent');
        $cell->addListItem('Identifikasi Masalah', 0, null, 'numbering' . $iib12->id);
        $cell->addListItem($iib12->type == 'detect' ? 'Latar Belakang Permasalahan' : 'Hasil Identifikasi Permasalahan', 1, null, 'numbering' . $iib12->id);
        $cell->addText(Utilities::transformHTMLToWord($iib12->type == 'detect' ? $iib12->background : $iib12->result_ident), null, 'normalContentIndent1');
        $cell->addListItem($iib12->type == 'detect' ? 'Identifikasi Permasalahan' : 'Solusi/Alternatif Solusi', 1, null, 'numbering' . $iib12->id);
        $cell->addText(Utilities::transformHTMLToWord($iib12->type == 'detect' ? $iib12->problem_ident : $iib12->solution), null, 'normalContentIndent1');
        $cell->addListItem($iib12->type == 'detect' ? 'Analisa Permasalahan' : 'Langkah Perbaikan', 1, null, 'numbering' . $iib12->id);
        $cell->addText(Utilities::transformHTMLToWord($iib12->type == 'detect' ? $iib12->problem_analysis : $iib12->action), null, 'normalContentIndent1');

        $cell->addListItem('Dokumentasi', 0, $iib12->documentation == null ? array('color' => 'ff0000') : null, 'numbering' . $iib12->id);
        if ($iib12->documentation != null)
            $cell->addImage(
                'storage/' . $iib12->documentation,
                array(
                    'height' => 300,
                    'wrappingStyle' => 'behind',
                )
            );
        $cell->addListItem('Lembar Persetujuan', 0, $iib12->approval_letter == null ? array('color' => 'ff0000') : null, 'numbering' . $iib12->id);
        $cell->addText('Scan', $iib12->approval_letter == null ? array('color' => 'ff0000') : null, 'normalContent');
        if ($iib12->approval_letter != null)
            $cell->addImage(
                'storage/' . $iib12->approval_letter,
                array(
                    'height' => 300,
                    'wrappingStyle' => 'behind',
                )
            );
        //content close here
    }

    public static function generateIIB9WordContent($phpWord, $table, $iib9)
    {
        //content here
        $phpWord->addParagraphStyle('normalContent', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 0.5));
        $phpWord->addParagraphStyle('normalContentIndent1', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 1));
        $phpWord->addNumberingStyle(
            'numbering' . $iib9->id,
            array(
                'type'   => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'bullet', 'text' => '●', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                ),
            )
        );
        $cell = $table->addCell(10000, array('gridSpan' => 4, 'valign' => 'top'));
        $cell->addListItem('Latar Belakang dan Tujuan Pemasangan', 0, null, 'numbering' . $iib9->id);
        $cell->addText(Utilities::transformHTMLToWord($iib9->background), null, 'normalContent');
        $cell->addListItem('Waktu dan Lokasi', 0, null, 'numbering' . $iib9->id);
        $cell->addText(date("d F Y", strtotime($iib9->time)) . ' di ' . $iib9->roomDetail->name, null, 'normalContent');
        $cell->addListItem('Nama Infrastruktur TI', 0, null, 'numbering' . $iib9->id);
        $cell->addText($iib9->infraTypeDetail->name . ' ' . $iib9->infra_name, null, 'normalContent');
        $cell->addListItem('Tahapan', 0, null, 'numbering' . $iib9->id);
        $cell->addText(Utilities::transformHTMLToWord($iib9->step), null, 'normalContent');
        $cell->addListItem('Kesimpulan', 0, null, 'numbering' . $iib9->id);
        $cell->addText(Utilities::transformHTMLToWord($iib9->summary), null, 'normalContent');

        $cell->addListItem('Bukti Instalasi', 0, $iib9->documentation == null ? array('color' => 'ff0000') : null, 'numbering' . $iib9->id);
        if ($iib9->documentation != null)
            $cell->addImage(
                'storage/' . $iib9->documentation,
                array(
                    'height' => 300,
                    'wrappingStyle' => 'behind',
                )
            );
        $cell->addListItem('Lembar Persetujuan', 0, $iib9->approval_letter == null ? array('color' => 'ff0000') : null, 'numbering' . $iib9->id);
        $cell->addText('Scan', $iib9->approval_letter == null ? array('color' => 'ff0000') : null, 'normalContent');
        if ($iib9->approval_letter != null)
            $cell->addImage(
                'storage/' . $iib9->approval_letter,
                array(
                    'height' => 300,
                    'wrappingStyle' => 'behind',
                )
            );
        //content close here    }
    }

    public static function generateIIB8WordContent($phpWord, $table, $iib8)
    {
        //content here
        $phpWord->addParagraphStyle('normalContent', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 0.5));
        $phpWord->addParagraphStyle('normalContentIndent1', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 1));
        $phpWord->addNumberingStyle(
            'numbering' . $iib8->id,
            array(
                'type'   => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'bullet', 'text' => '●', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                ),
            )
        );

        $cell = $table->addCell(10000, array('gridSpan' => 4, 'valign' => 'top'));

        $cell->addListItem('Waktu, Lokasi dan Nama Petugas', 0, null, 'numbering' . $iib8->id);
        $cell->addText(date("d F Y", strtotime($iib8->time)) . ' di ruang' . $iib8->roomDetail->name . ' dilakukan oleh ' . $iib8->userDataDetail->name, null, 'normalContent');
        $cell->addListItem('Daftar Perangkat', 0, null, 'numbering' . $iib8->id);

        $tableStyle = array(
            'borderColor' => '000000',
            'borderSize'  => 6,
            'cellMargin'  => 70,
            'indent' => new  TblWidth(360)
        );
        $phpWord->addTableStyle('table_infra', $tableStyle);
        $table = $cell->addTable('table_infra');
        $tableCellStyle = array('valign' => 'top');

        $i = 1;
        $table->addRow();
        $table->addCell(400, $tableCellStyle)->addText('No', array('bold' => true), 'normalCenter');
        $table->addCell(2000, $tableCellStyle)->addText('Jenis Infrastruktur', array('bold' => true), 'normalCenter');
        $table->addCell(3000, $tableCellStyle)->addText('Nama Infrastruktur', array('bold' => true), 'normalCenter');

        foreach ($iib8->infras as $infra) {
            $table->addRow();
            $table->addCell(400, $tableCellStyle)->addText($i, null, 'normal');
            $table->addCell(2000, $tableCellStyle)->addText($infra->infraTypeDetail->name, null, 'normal');
            $table->addCell(3000, $tableCellStyle)->addText($infra->infra_name, null, 'normal');
            $i++;
        }

        $cell->addListItem('Tahapan', 0, null, 'numbering' . $iib8->id);
        $cell->addText(Utilities::transformHTMLToWord($iib8->step), null, 'normalContent');
        $cell->addListItem('Hasil', 0, null, 'numbering' . $iib8->id);
        $cell->addText(Utilities::transformHTMLToWord($iib8->result), null, 'normalContent');
        $cell->addListItem('Kesimpulan', 0, null, 'numbering' . $iib8->id);
        $cell->addText(Utilities::transformHTMLToWord($iib8->summary), null, 'normalContent');

        $cell->addListItem('Bukti Instalasi', 0, $iib8->documentation == null ? array('color' => 'ff0000') : null, 'numbering' . $iib8->id);
        if ($iib8->documentation != null)
            $cell->addImage(
                'storage/' . $iib8->documentation,
                array(
                    'height' => 300,
                    'wrappingStyle' => 'behind',
                )
            );
        $cell->addListItem('Lembar Persetujuan', 0, $iib8->approval_letter == null ? array('color' => 'ff0000') : null, 'numbering' . $iib8->id);
        $cell->addText('Scan', $iib8->approval_letter == null ? array('color' => 'ff0000') : null, 'normalContent');
        if ($iib8->approval_letter != null)
            $cell->addImage(
                'storage/' . $iib8->approval_letter,
                array(
                    'height' => 300,
                    'wrappingStyle' => 'behind',
                )
            );
        //content close here    }
    }

    public static function generateIC39WordContent($phpWord, $table, $ic39)
    {
        //content here
        $phpWord->addParagraphStyle('normalContent', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 0.5));
        $phpWord->addParagraphStyle('normalContentIndent1', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 1));
        $phpWord->addNumberingStyle(
            'numbering' . $ic39->id,
            array(
                'type'   => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'bullet', 'text' => '●', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                ),
            )
        );

        $cell = $table->addCell(10000, array('gridSpan' => 4, 'valign' => 'top'));

        $cell->addListItem('Tanggal Backup: ' . date("d F Y", strtotime($ic39->time)), 0, null, 'numbering' . $ic39->id, array('spaceAfter' => 0));
        $cell->addListItem('Dataset yang dicadangkan: Data ' . $ic39->dataset, 0, null, 'numbering' . $ic39->id, array('spaceAfter' => 0));
        $cell->addListItem('Media Penyimpanan: ' . $ic39->storage, 0, null, 'numbering' . $ic39->id, array('spaceAfter' => 0));
        $cell->addListItem('Nama file backup: ' . $ic39->filename, 0, null, 'numbering' . $ic39->id, array('spaceAfter' => 0));
        $cell->addListItem('Screenshot', 0, $ic39->documentation == null ? array('color' => 'ff0000') : null, 'numbering' . $ic39->id, array('spaceAfter' => 0));
        if ($ic39->documentation != null)
            $cell->addImage(
                'storage/' . $ic39->documentation,
                array(
                    'height' => 200,
                    'wrappingStyle' => 'behind',
                )
            );
        //content close here    }
    }

    public static function generateIIIC8WordContent($phpWord, $table, $iiic8)
    {
        //content here
        $phpWord->addParagraphStyle('normalContent', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 0.5));
        $phpWord->addParagraphStyle('normalContentIndent1', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 1));
        $phpWord->addNumberingStyle(
            'numbering' . $iiic8->id,
            array(
                'type'   => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'bullet', 'text' => '●', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                ),
            )
        );

        $cell = $table->addCell(10000, array('gridSpan' => 4, 'valign' => 'top'));

        $cell->addListItem('Deskripsi dan Latar Belakang', 0, null, 'numbering' . $iiic8->id);
        $cell->addText(Utilities::transformHTMLToWord($iiic8->background), null, 'normalContent');
        $cell->addListItem('Data dan Informasi yang Digunakan', 0, null, 'numbering' . $iiic8->id);
        $cell->addText(Utilities::transformHTMLToWord($iiic8->data), null, 'normalContent');
        $cell->addListItem('Hardware dan Software yang Digunakan', 0, null, 'numbering' . $iiic8->id);
        $cell->addText(Utilities::transformHTMLToWord($iiic8->tools), null, 'normalContent');
        $cell->addListItem('Hasil', 0, null, 'numbering' . $iiic8->id);
        $cell->addText(Utilities::transformHTMLToWord($iiic8->link), null, 'normalContent');

        //content close here    }
    }

    public static function generateIB21WordContent($phpWord, $table, $ib21)
    {
        //content here
        $phpWord->addParagraphStyle('normalContent', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 0.5));
        $phpWord->addParagraphStyle('normalContentIndent1', array('spacing' => 180, 'spaceAfter' => 0, 'indent' => 1));
        $phpWord->addParagraphStyle('normalCenter', array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0));
        $phpWord->addNumberingStyle(
            'numbering' . $ib21->id,
            array(
                'type'   => 'multilevel',
                'levels' => array(
                    array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
                    array('format' => 'bullet', 'text' => '●', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
                ),
            )
        );

        $cell = $table->addCell(10000, array('gridSpan' => 4, 'valign' => 'top'));

        $cell->addText('Laporan Permintaan dan Layanan Teknologi Informasi', array('bold' => true), 'normalCenter');
        $cell->addTextBreak();
        $cell->addText('Periode' . "\t\t\t" . ': ' . date("F", strtotime($ib21->time)) . ' ' . date("Y", strtotime($ib21->time)), null, 'normalContent');
        $cell->addText('Jumlah Permintaan' . "\t\t" . ': ' . count($ib21->services), null, 'normalContent');

        $tableStyle = array(
            'borderColor' => '000000',
            'borderSize'  => 6,
            'cellMargin'  => 70,
            'indent' => new  TblWidth(360)
        );
        $phpWord->addTableStyle('table_service', $tableStyle);
        $table = $cell->addTable('table_service');
        $tableCellStyle = array('valign' => 'top');

        $i = 1;
        $table->addRow();
        $table->addCell(400, $tableCellStyle)->addText('No', array('bold' => true), 'normalCenter');
        $table->addCell(3000, $tableCellStyle)->addText('Layanan yang Diminta', array('bold' => true), 'normalCenter');
        $table->addCell(1000, $tableCellStyle)->addText('Jenis Layanan', array('bold' => true), 'normalCenter');
        $table->addCell(1000, $tableCellStyle)->addText('Sarana', array('bold' => true), 'normalCenter');
        $table->addCell(3000, $tableCellStyle)->addText('Cara Pemenuhan Permintaan', array('bold' => true), 'normalCenter');

        foreach ($ib21->services as $service) {
            $table->addRow();
            $table->addCell(400, $tableCellStyle)->addText($i, null, 'normal');
            $table->addCell(3000, $tableCellStyle)->addText($service->description, null, 'normal');
            $table->addCell(1000, $tableCellStyle)->addText($service->serviceTypeDetail->name, null, 'normal');
            $table->addCell(1000, $tableCellStyle)->addText($service->serviceMediaDetail->name, null, 'normal');
            $table->addCell(3000, $tableCellStyle)->addText($service->service, null, 'normal');
            $i++;
        }

        $cell->addTextBreak();
        $cell->addTextBreak();

        //content close here    }
    }
}
