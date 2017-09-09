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


$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
//$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

$moduleHelper->loadLanguage('modinfo');

$adminmenu              = [];
$i                      = 0;
'title' =>  _AM_MODULEADMIN_HOME,
'link' =>  'admin/index.php',
'icon' =>  $pathIcon32 . '/home.png',
++$i;
'title' =>  _MI_SPARTNER_ADMENU1,
'link' =>  'admin/main.php',
'icon' =>  $pathIcon32 . '/manage.png',

++$i;
'title' =>  _MI_SPARTNER_CATEGORIES,
'link' =>  'admin/category.php',
'icon' =>  $pathIcon32 . '/category.png',
++$i;
'title' =>  _MI_SPARTNER_ADMENU2,
'link' =>  'admin/partner.php',
'icon' =>  $pathIcon32 . '/users.png',
++$i;
'title' =>  _MI_SPARTNER_ADMENU3,
'link' =>  'admin/offer.php',
'icon' =>  $pathIcon32 . '/cash_stack.png',
++$i;
'title' =>  _MI_SPARTNER_IMPORT,
'link' =>  'admin/import.php',
'icon' =>  $pathIcon32 . '/compfile.png',
++$i;
'title' =>  _AM_MODULEADMIN_ABOUT,
'link' =>  'admin/about.php',
'icon' =>  $pathIcon32 . '/about.png',
