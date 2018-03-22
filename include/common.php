<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartpartner;


// defined('XOOPS_ROOT_PATH') || die('Restricted access');

include __DIR__ . '/../preloads/autoloader.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper   = strtoupper($moduleDirName); //$capsDirName


/** @var \XoopsDatabase $db */
/** @var Smartpartner\Helper $helper */
/** @var Smartpartner\Utility $utility */
$db      = \XoopsDatabaseFactory::getDatabaseConnection();
$helper  = Smartpartner\Helper::getInstance();
$utility = new Smartpartner\Utility();
//$configurator = new Smartpartner\Common\Configurator();

$helper->loadLanguage('common');

//handlers
//$categoryHandler     = new Smartpartner\CategoryHandler($db);
//$downloadHandler     = new Smartpartner\DownloadHandler($db);

if (!defined($moduleDirNameUpper . '_CONSTANTS_DEFINED')) {
    define($moduleDirNameUpper . '_DIRNAME', basename(dirname(__DIR__)));
    define($moduleDirNameUpper . '_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_PATH', XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_URL', XOOPS_URL . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_IMAGE_URL', constant($moduleDirNameUpper . '_URL') . '/assets/images/');
    define($moduleDirNameUpper . '_IMAGE_PATH', constant($moduleDirNameUpper . '_ROOT_PATH') . '/assets/images');
    define($moduleDirNameUpper . '_ADMIN_URL', constant($moduleDirNameUpper . '_URL') . '/admin/');
    define($moduleDirNameUpper . '_ADMIN_PATH', constant($moduleDirNameUpper . '_ROOT_PATH') . '/admin/');
    define($moduleDirNameUpper . '_ADMIN', constant($moduleDirNameUpper . '_URL') . '/admin/index.php');
    define($moduleDirNameUpper . '_AUTHOR_LOGOIMG', constant($moduleDirNameUpper . '_URL') . '/assets/images/logoModule.png');
    define($moduleDirNameUpper . '_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . $moduleDirName); // WITHOUT Trailing slash
    define($moduleDirNameUpper . '_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . $moduleDirName); // WITHOUT Trailing slash
    define($moduleDirNameUpper . '_CONSTANTS_DEFINED', 1);
}


/** Include SmartObject framework **/
require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartloader.php';
//require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjectcategory.php';

// Creating the SmartModule object
//$smartModule = Smartpartner\Utility::getModuleInfo();
$smartModule = $helper->getModule();

// Find if the user is admin of the module
//$smartPartnerIsAdmin = Smartpartner\Utility::userIsAdmin();
$smartPartnerIsAdmin = $helper->isUserAdmin();

$myts                   = \MyTextSanitizer::getInstance();
$smartPartnerModuleName = $smartModule->getVar('name');

// Creating the SmartModule config Object
//$smartConfig = Smartpartner\Utility::getModuleConfig();
$smartConfig = $helper->getConfig();

// Creating the partner handler object
$smartPartnerPartnerHandler = Smartpartner\Helper::getInstance()->getHandler('Partner');

// Creating the category handler object
$smartPartnerCategoryHandler = Smartpartner\Helper::getInstance()->getHandler('Category');

// Creating the category link handler object
$smartpartnerPartnerCatLinkHandler = Smartpartner\Helper::getInstance()->getHandler('PartnerCatLink');

// Creating the offer handler object
$smartPartnerOfferHandler = Smartpartner\Helper::getInstance()->getHandler('Offer');

// Creating the file handler object
$smartPartnerFileHandler = Smartpartner\Helper::getInstance()->getHandler('File');

define('_SPARTNER_STATUS_OFFLINE', 0);
define('_SPARTNER_STATUS_ONLINE', 1);
$statusArray = [
    _SPARTNER_STATUS_OFFLINE => _CO_SPARTNER_STATUS_OFFLINE,
    _SPARTNER_STATUS_ONLINE  => _CO_SPARTNER_STATUS_ONLINE
];
//require_once SMARTPARTNER_ROOT_PATH . 'class/smarttree.php';


$pathIcon16    = Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = Xmf\Module\Admin::iconUrl('', 32);
//$pathModIcon16 = $helper->getModule()->getInfo('modicons16');
//$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$icons = [
    'edit'    => "<img src='" . $pathIcon16 . "/edit.png'  alt=" . _EDIT . "' align='middle'>",
    'delete'  => "<img src='" . $pathIcon16 . "/delete.png' alt='" . _DELETE . "' align='middle'>",
    'clone'   => "<img src='" . $pathIcon16 . "/editcopy.png' alt='" . _CLONE . "' align='middle'>",
    'preview' => "<img src='" . $pathIcon16 . "/view.png' alt='" . _PREVIEW . "' align='middle'>",
    'print'   => "<img src='" . $pathIcon16 . "/printer.png' alt='" . _CLONE . "' align='middle'>",
    'pdf'     => "<img src='" . $pathIcon16 . "/pdf.png' alt='" . _CLONE . "' align='middle'>",
    'add'     => "<img src='" . $pathIcon16 . "/add.png' alt='" . _ADD . "' align='middle'>",
    '0'       => "<img src='" . $pathIcon16 . "/0.png' alt='" . 0 . "' align='middle'>",
    '1'       => "<img src='" . $pathIcon16 . "/1.png' alt='" . 1 . "' align='middle'>",
];

$debug = false;

// MyTextSanitizer object
$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);
// Local icons path
if (is_object($helper->getModule())) {
    $pathModIcon16 = $helper->getModule()->getInfo('modicons16');
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');

    $GLOBALS['xoopsTpl']->assign('pathModIcon16', XOOPS_URL . '/modules/' . $moduleDirName . '/' . $pathModIcon16);
    $GLOBALS['xoopsTpl']->assign('pathModIcon32', $pathModIcon32);
}
