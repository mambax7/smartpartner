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
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use XoopsModules\Smartpartner;
use XoopsModules\Smartpartner\Common;
use XoopsModules\Smartpartner\Constants;

require_once __DIR__ . '/../../../include/cp_header.php';
require_once __DIR__ . '/admin_header.php';

xoops_cp_header();

$adminObject = \Xmf\Module\Admin::getInstance();

//$folder = [
//    XOOPS_ROOT_PATH . '/uploads/smartpartner/images/',
//    XOOPS_ROOT_PATH . '/uploads/smartpartner/images/category/'
//];

//check or upload folders
$configurator = new Common\Configurator();
foreach (array_keys($configurator->uploadFolders) as $i) {
    $utility::createFolder($configurator->uploadFolders[$i]);
    $adminObject->addConfigBoxLine($configurator->uploadFolders[$i], 'folder');
}
//---------------------

// Creating the Partner handler object
//$partnerHandler = Smartpartner\Helper::getInstance()->getHandler('Partner');

// Total Partners -- includes everything on the table
$totalpartners = $partnerHandler->getPartnerCount(Constants::_SPARTNER_STATUS_ALL);

// Total Submitted Partners
$totalsubmitted = $partnerHandler->getPartnerCount(Constants::_SPARTNER_STATUS_SUBMITTED);

// Total active Partners
$totalactive = $partnerHandler->getPartnerCount(Constants::_SPARTNER_STATUS_ACTIVE);

// Total inactive Partners
$totalinactive = $partnerHandler->getPartnerCount(Constants::_SPARTNER_STATUS_INACTIVE);

// Total rejected Partners
$totalrejected = $partnerHandler->getPartnerCount(Constants::_SPARTNER_STATUS_REJECTED);

//create info block
$adminObject->addInfoBox(_AM_SPARTNER_INVENTORY);

if ($totalsubmitted > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . '<a href="category.php">' . _AM_SPARTNER_TOTAL_SUBMITTED . '</a><b>' . '</infolabel>', $totalsubmitted), '', 'Green');
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_SPARTNER_TOTAL_SUBMITTED . '</infolabel>', $totalsubmitted), '', 'Green');
}
if ($totalactive > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . '<a href="partner.php">' . _AM_SPARTNER_TOTAL_ACTIVE . '</a><b>' . '</infolabel>', $totalactive), '', 'Green');
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_SPARTNER_TOTAL_ACTIVE . '</infolabel>', $totalactive), '', 'Green');
}
if ($totalrejected > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . '<a href="main.php">' . _AM_SPARTNER_TOTAL_REJECTED . '</a><b>' . '</infolabel>', $totalrejected), '', 'Green');
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_SPARTNER_TOTAL_REJECTED . '</infolabel>', $totalrejected), '', 'Green');
}
if ($totalinactive > 0) {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . '<a href="main.php">' . _AM_SPARTNER_TOTAL_INACTIVE . '</a><b>' . '</infolabel>', $totalinactive), '', 'Green');
} else {
    $adminObject->addInfoBoxLine(sprintf('<infolabel>' . _AM_SPARTNER_TOTAL_INACTIVE . '</infolabel>', $totalinactive), '', 'Green');
}
//---------------------

$adminObject->displayNavigation(basename(__FILE__));

//------------- Test Data ----------------------------

if ($helper->getConfig('displaySampleButton')) {
    xoops_loadLanguage('admin/modulesadmin', 'system');
    require_once __DIR__ . '/../testdata/index.php';

    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=load', 'add');

    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=save', 'add');

    //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), '__DIR__ . /../../testdata/index.php?op=exportschema', 'add');

    $adminObject->displayButton('left', '');
}

//------------- End Test Data ----------------------------

$adminObject->displayIndex();


echo $utility::getServerStats();

require_once __DIR__ . '/admin_footer.php';

