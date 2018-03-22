<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartpartner;

include __DIR__ . '/../../mainfile.php';

// This must contain the name of the folder in which reside SmartPartner
//if (!defined('SMARTPARTNER_DIRNAME')) {
//    define('SMARTPARTNER_DIRNAME', 'smartpartner');
//}


include XOOPS_ROOT_PATH . '/class/pagenav.php';

require_once __DIR__ . '/include/config.php';


/** @var Smartpartner\Helper $helper */
$helper = Smartpartner\Helper::getInstance();

// Load language files
$helper->loadLanguage('main');

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new XoopsTpl();
}
