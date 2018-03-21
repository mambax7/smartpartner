<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartpartner;

// require_once __DIR__ . '/../class/Helper.php';
//require_once __DIR__ . '/../include/common.php';
$helper = Smartpartner\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');


$adminmenu[] = [
    'title' => _MI_SPARTNER_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_SPARTNER_ADMENU1,
    'link'  => 'admin/main.php',
    'icon'  => $pathIcon32 . '/manage.png',
];

$adminmenu[] = [
    'title' => _MI_SPARTNER_CATEGORIES,
    'link'  => 'admin/category.php',
    'icon'  => $pathIcon32 . '/category.png',
];

$adminmenu[] = [
    'title' => _MI_SPARTNER_ADMENU2,
    'link'  => 'admin/partner.php',
    'icon'  => $pathIcon32 . '/users.png',
];

$adminmenu[] = [
    'title' => _MI_SPARTNER_ADMENU3,
    'link'  => 'admin/offer.php',
    'icon'  => $pathIcon32 . '/cash_stack.png',
];

$adminmenu[] = [
    'title' => _MI_SPARTNER_IMPORT,
    'link'  => 'admin/import.php',
    'icon'  => $pathIcon32 . '/compfile.png',
];

$adminmenu[] = [
    'title' => _MI_SPARTNER_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];
