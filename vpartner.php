<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartpartner;
/** @var Smartpartner\Helper $helper */
$helper = Smartpartner\Helper::getInstance();

include __DIR__ . '/header.php';

$id = \Xmf\Request::getInt('id', 0, 'GET');

$partnerObj = new Smartpartner\Partner($id);

if ($partnerObj->notLoaded()) {
    redirect_header('javascript:history.go(-1)', 1, _CO_SPARTNER_NOPARTNERSELECTED);
}

if ($partnerObj->url()) {
    if (!isset($_COOKIE['partners'][$id])) {
        setcookie("partners[$id]", $id, $helper->getConfig('cookietime'));
        $partnerObj->updateHits();
    }
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $partnerObj->url() . ' ');
    exit();
//echo "<html><head><meta http-equiv='Refresh' content='0; URL=".$partnerObj->url()."'></head><body></body></html>";
} else {
    redirect_header('index.php', 1, _XP_NOPART);
}
