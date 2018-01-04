<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team, Kazumi Ono (AKA onokazu)
 */

//TODO needs to be refactored for TCPDF

require_once __DIR__ . '/header.php';
//error_reporting(0);
//error_reporting(0);

$myts = \MyTextSanitizer::getInstance();
//require_once SMARTPARTNER_ROOT_PATH . 'fpdf/fpdf.inc.php';
require_once XOOPS_ROOT_PATH . '/class/libraries/vendor/tecnickcom/tcpdf/tcpdf.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (0 == $id) {
    redirect_header('javascript:history.go(-1)', 2, _MD_SPARTNER_NOPARTNERSELECTED);
}

// Creating the Partner object for the selected FAQ
$partnerObj = new SmartpartnerPartner($id);

// If the selected partner was not found, exit
if ($partnerObj->notLoaded()) {
    redirect_header('javascript:history.go(-1)', 2, _MD_SPARTNER_NOPARTNERSELECTED);
}

// Chech the status
if (_SPARTNER_STATUS_ACTIVE != $partnerObj->status()) {
    redirect_header('javascript:history.go(-1)', 2, _NOPERM);
}

$pdf_data['title']       = $partnerObj->title();
$pdf_data['subtitle']    = 'subtitle...';
$pdf_data['subsubtitle'] = 'subsubtitle...';
$pdf_data['date']        = 'date...';
$pdf_data['filename']    = 'filename...'; //preg_replace("/[^0-9a-z\-_\.]/i",'', $myts->htmlSpecialChars($article->topic_title()).' - '.$article->title());
$pdf_data['content']     = $partnerObj->description();
$pdf_data['author']      = 'author...';

echo 'test';
//Other stuff
$puff   = '<br>';
$puffer = '<br><br><br>';

//create the A4-PDF...
$pdf_config['slogan'] = $xoopsConfig['sitename'] . ' - ' . $xoopsConfig['slogan'];

//$pdf = new PDF();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, _CHARSET, false);
$pdf->SetCreator($pdf_config['creator']);
$pdf->SetTitle($pdf_data['title']);
$pdf->SetAuthor($pdf_config['url']);
$pdf->SetSubject($pdf_data['author']);
$out = $pdf_config['url'] . ', ' . $pdf_data['author'] . ', ' . $pdf_data['title'] . ', ' . $pdf_data['subtitle'] . ', ' . $pdf_data['subsubtitle'];
$pdf->SetKeywords($out);
$pdf->SetAutoPageBreak(true, 25);
$pdf->SetMargins($pdf_config['margin']['left'], $pdf_config['margin']['top'], $pdf_config['margin']['right']);
$pdf->Open();

//First page
$pdf->AddPage();
$pdf->SetXY(24, 25);
$pdf->SetTextColor(10, 60, 160);
$pdf->SetFont($pdf_config['font']['slogan']['family'], $pdf_config['font']['slogan']['style'], $pdf_config['font']['slogan']['size']);
$pdf->WriteHTML($pdf_config['slogan'], $pdf_config['scale']);
//$pdf->Image($pdf_config['logo']['path'],$pdf_config['logo']['left'],$pdf_config['logo']['top'],$pdf_config['logo']['width'],$pdf_config['logo']['height'],'',$pdf_config['url']);
$pdf->Line(25, 30, 190, 30);
$pdf->SetXY(25, 35);
$pdf->SetFont($pdf_config['font']['title']['family'], $pdf_config['font']['title']['style'], $pdf_config['font']['title']['size']);
$pdf->WriteHTML($pdf_data['title'], $pdf_config['scale']);

if ('' <> $pdf_data['subtitle']) {
    $pdf->WriteHTML($puff, $pdf_config['scale']);
    $pdf->SetFont($pdf_config['font']['subtitle']['family'], $pdf_config['font']['subtitle']['style'], $pdf_config['font']['subtitle']['size']);
    $pdf->WriteHTML($pdf_data['subtitle'], $pdf_config['scale']);
}
if ('' <> $pdf_data['subsubtitle']) {
    $pdf->WriteHTML($puff, $pdf_config['scale']);
    $pdf->SetFont($pdf_config['font']['subsubtitle']['family'], $pdf_config['font']['subsubtitle']['style'], $pdf_config['font']['subsubtitle']['size']);
    $pdf->WriteHTML($pdf_data['subsubtitle'], $pdf_config['scale']);
}

$pdf->WriteHTML($puff, $pdf_config['scale']);
$pdf->SetFont($pdf_config['font']['author']['family'], $pdf_config['font']['author']['style'], $pdf_config['font']['author']['size']);
$out = NEWS_PDF_AUTHOR . ': ';
$out .= $pdf_data['author'];
$pdf->WriteHTML($out, $pdf_config['scale']);
$pdf->WriteHTML($puff, $pdf_config['scale']);
$out = NEWS_PDF_DATE;
$out .= $pdf_data['date'];
$pdf->WriteHTML($out, $pdf_config['scale']);
$pdf->WriteHTML($puff, $pdf_config['scale']);

$pdf->SetTextColor(0, 0, 0);
$pdf->WriteHTML($puffer, $pdf_config['scale']);

$pdf->SetFont($pdf_config['font']['content']['family'], $pdf_config['font']['content']['style'], $pdf_config['font']['content']['size']);
$pdf->WriteHTML($pdf_data['content'], $pdf_config['scale']);

//$pdf->Output($pdf_data['filename'],'');
$pdf->Output();
