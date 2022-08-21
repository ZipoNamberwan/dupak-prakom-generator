<?php

namespace App\Helpers;

class TemplateContentProcessor
{
    public static function generateIIB12WordContent($phpWord, $table, $iib12)
    {
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
        $cell->addListItem($iib12->type == 'detect' ? 'Latar Belakang Permasalahan' : 'Hasil Identifikasi Permasalahan', 1, null, 'numbering');
        $cell->addText(Utilities::transformHTMLToWord($iib12->type == 'detect' ? $iib12->background : $iib12->result_ident), null, 'normalContentIndent1');
        $cell->addListItem($iib12->type == 'detect' ? 'Identifikasi Permasalahan' : 'Solusi/Alternatif Solusi', 1, null, 'numbering');
        $cell->addText(Utilities::transformHTMLToWord($iib12->type == 'detect' ? $iib12->problem_ident : $iib12->solution), null, 'normalContentIndent1');
        $cell->addListItem($iib12->type == 'detect' ? 'Analisa Permasalahan' : 'Langkah Perbaikan', 1, null, 'numbering');
        $cell->addText(Utilities::transformHTMLToWord($iib12->type == 'detect' ? $iib12->problem_analysis : $iib12->action), null, 'normalContentIndent1');

        $cell->addListItem('Dokumentasi', 0, $iib12->documentation == null ? array('color' => 'ff0000') : null, 'numbering');
        if ($iib12->documentation != null)
            $cell->addImage(
                'storage/' . $iib12->documentation,
                array(
                    'height' => 300,
                    'wrappingStyle' => 'behind',
                )
            );
        $cell->addListItem('Lembar Persetujuan', 0, $iib12->approval_letter == null ? array('color' => 'ff0000') : null, 'numbering');
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
}
