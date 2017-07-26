<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

$moduleDirName = basename(dirname(__DIR__));

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}
$adminObject = \Xmf\Module\Admin::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
//$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

$moduleHelper->loadLanguage('modinfo');

$adminmenu              = array();
$i                      = 0;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/home.png';
++$i;
$adminmenu[$i]['title'] = _MI_SPARTNER_ADMENU1;
$adminmenu[$i]['link']  = 'admin/main.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/manage.png';

++$i;
$adminmenu[$i]['title'] = _MI_SPARTNER_CATEGORIES;
$adminmenu[$i]['link']  = 'admin/category.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/category.png';
++$i;
$adminmenu[$i]['title'] = _MI_SPARTNER_ADMENU2;
$adminmenu[$i]['link']  = 'admin/partner.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/users.png';
++$i;
$adminmenu[$i]['title'] = _MI_SPARTNER_ADMENU3;
$adminmenu[$i]['link']  = 'admin/offer.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/cash_stack.png';
++$i;
$adminmenu[$i]['title'] = _MI_SPARTNER_IMPORT;
$adminmenu[$i]['link']  = 'admin/import.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/compfile.png';
++$i;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/about.png';
