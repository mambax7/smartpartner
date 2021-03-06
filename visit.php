<?php
/**
 *
 * Module: SmartSection
 * Author: marcan <marcan@notrevie.ca>
 * Licence: GNU
 */

use XoopsModules\Smartpartner;

require_once __DIR__ . '/header.php';

$fileid = \Xmf\Request::getInt('fileid', 0, 'GET');

// Creating the item object for the selected item
$fileObj = $fileHandler->get($fileid);
$fileObj->updateCounter();

if (!preg_match("/^ed2k*:\/\//i", $fileObj->getFileUrl())) {
    header('Location: ' . $fileObj->getFileUrl());
}

echo '<html><head><meta http-equiv="Refresh" content="0; URL=' . $myts->oopsHtmlSpecialChars($fileObj->getFileUrl()) . '"></meta></head><body></body></html>';
exit();
