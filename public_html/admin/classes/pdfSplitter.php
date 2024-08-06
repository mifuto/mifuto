<?php
use setasign\Fpdi\Fpdi;
require_once('fpdf/fpdf.php');
require_once('FPDI/autoload.php');

function splitPdf($pdfPath) {
    $pdf = new FPDI();
    $pdf->setSourceFile($pdfPath);

    $out = new FPDI();
    $out->setSourceFile($pdfPath);

    $tpl = $pdf->importPage(1);
    $size = $pdf->getTemplateSize($tpl);
    $widthF = $size['width'];
    $heightF = $size['height'];

    $i = 0;
    $pgCount = 0;

    try {
        while ($tpl = $pdf->importPage(++$i)) {
            $pgCount++;
        }
    } catch (InvalidArgumentException $e) {
        //throw $th;
    }

    for($i = 1; $i <= $pgCount; $i++) {
        $tpl = $pdf->importPage($i);
        $size = $pdf->getTemplateSize($tpl);
        $width = $size['width'];
        $height = $size['height'];

        $wr = $width / $widthF;

        $sizeNew = array($width/2, $height);

        $orientation = 'P';
        if($width / 2 > $height) $orientation = 'L';

        if($i != 1) {
            $out->addPage($orientation, $sizeNew);
            $out->useTemplate($out->importPage($i), 0,0,$width,$height);
        }
        if($i != $pgCount) {
            $out->addPage($orientation, $sizeNew);
            $out->useTemplate($out->importPage($i), -$width / 2,0,$width,$height);
        }
    }

    $current_file = $pdfPath;
    $path = dirname($current_file);
    $filename = pathinfo($current_file, PATHINFO_FILENAME);
    $extension = pathinfo($current_file, PATHINFO_EXTENSION);
    
    $new_file = $path. '/'. $filename . '_orig.' . $extension;
    rename($current_file, $new_file);


    $out->Output($current_file, 'F');

}
