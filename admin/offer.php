<?php
/**
 *
 * Module: SmartCourse
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param bool   $showmenu
 * @param int    $offerid
 * @param string $fct
 */

use XoopsModules\Smartpartner;
use XoopsModules\Smartobject;

/**
 * @param bool   $showmenu
 * @param int    $offerid
 * @param string $fct
 */
function editoffer($showmenu = false, $offerid = 0, $fct = '')
{
    global $offerHandler, $categoryHandler, $partnerHandler;

    $offerObj = $offerHandler->get($offerid);

    if ($offerObj->isNew()) {
        $breadcrumb            = _AM_SPARTNER_OFFERS . ' > ' . _AM_SPARTNER_CREATINGNEW;
        $title                 = _AM_SPARTNER_OFFER_CREATE;
        $info                  = _AM_SPARTNER_OFFER_CREATE_INFO;
        $collaps_name          = 'offercreate';
        $form_name             = _AM_SPARTNER_OFFER_CREATE;
        $submit_button_caption = null;
        $offerObj->setVar('date_sub', time());
    } else {
        $breadcrumb            = _AM_SPARTNER_OFFERS . ' > ' . _AM_SPARTNER_EDITING;
        $title                 = _AM_SPARTNER_OFFER_EDIT;
        $info                  = _AM_SPARTNER_OFFER_EDIT_INFO;
        $collaps_name          = 'offeredit';
        $form_name             = _AM_SPARTNER_OFFER_EDIT;
        $submit_button_caption = null;
    }
    $partnerObj = $partnerHandler->get($offerObj->getVar('partnerid', 'e'));
    $offerObj->hideFieldFromForm('date_sub');
    $menuTab = 3;

    if ($showmenu) {
        //Smartobject\Utility::getAdminMenu($menuTab, $breadcrumb);
    }
    echo "<br>\n";
    Smartobject\Utility::getCollapsableBar($collaps_name, $title, $info);

    $sform = $offerObj->getForm($form_name, 'addoffer', false, $submit_button_caption);
    if ('app' === $fct) {
        $sform->addElement(new \XoopsFormHidden('fct', 'app'));
    }
    $sform->display();
    Smartobject\Utility::closeCollapsable($collaps_name);
}

require_once __DIR__ . '/admin_header.php';

$op = '';
if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

switch ($op) {
    case 'mod':
        $offerid = \Xmf\Request::getInt('offerid', 0, 'GET');
        $fct     = \Xmf\Request::getString('fct', '', 'GET');
        Smartobject\Utility::getXoopsCpHeader();

        editoffer(true, $offerid, $fct);
        break;

    case 'addoffer':
//        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';

        $controller = new Smartobject\ObjectController($offerHandler);
        $offerObj   = $controller->storeSmartObject();
        $fct        = \Xmf\Request::getString('fct', '', 'POST');

        if ($offerObj->hasError()) {
            redirect_header($smart_previous_page, 3, _CO_SOBJECT_SAVE_ERROR . $offerObj->getHtmlErrors());
        } else {
            $partnerObj = $partnerHandler->get($offerObj->getVar('partnerid', 'e'));
            $partnerObj->setUpdated();
            if ('' == $_POST['offerid']) {
                $offerObj->sendNotifications([_SPARTNER_NOT_OFFER_NEW]);
            }

            redirect_header(Smartobject\Utility::getPageBeforeForm(), 3, _CO_SOBJECT_SAVE_SUCCESS);
        }
        exit;
        break;

    case 'del':
//        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new Smartobject\ObjectController($offerHandler);
        $controller->handleObjectDeletion();
        break;

    case 'default':
    default:
//        require_once XOOPS_ROOT_PATH . '/modules/smartobject/include/functions.php';
        Smartobject\Utility::getXoopsCpHeader();

        //add navigation icon
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        //add button for creating a new offer
        $adminObject->addItemButton(_AM_SPARTNER_OFFER_CREATE, 'offer.php?op=mod', 'add', '');
        $adminObject->displayButton('left', '');

        //Smartobject\Utility::getAdminMenu(3, _AM_SPARTNER_OFFERS);

        //        echo "<br>\n";
        //        echo "<form><div style=\"margin-bottom: 12px;\">";
        //        echo "<input type='button' name='button' onclick=\"location='offer.php?op=mod'\" value='" . _AM_SPARTNER_OFFER_CREATE . "'>&nbsp;&nbsp;";
        //        echo "</div></form>";

        Smartobject\Utility::getCollapsableBar('createdoffers', _AM_SPARTNER_OFFERS, _AM_SPARTNER_OFFERS_DSC);

//        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjecttable.php';
        $objectTable = new Smartobject\Table($offerHandler);
        $objectTable->addFilter('partnerid', 'getPartnerList');
        $objectTable->addFilter('status', 'getStatusList');
        $objectTable->addColumn(new Smartobject\ObjectColumn('title', 'left'));
        $objectTable->addColumn(new Smartobject\ObjectColumn('partnerid', 'center', 100));
        $objectTable->addColumn(new Smartobject\ObjectColumn('status', 'center', 100));
        $objectTable->render();

        echo '<br>';
        Smartobject\Utility::closeCollapsable('createdoffers');
        echo '<br>';

        break;
}

//Smartobject\Utility::getModFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
