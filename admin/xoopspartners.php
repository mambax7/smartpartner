<?php

/**
 *
 * Module: SmartPartner
 * Author: Marius Scurtescu <mariuss@romanians.bc.ca>
 * Licence: GNU
 *
 * Import script from Xpartners to SmartPartner.
 *
 * It was tested with XoosPartners version 1.1 and SmartPartner version 1.0 beta
 *
 */


use XoopsModules\Smartpartner;

require_once __DIR__ . '/admin_header.php';

$importFromModuleName = 'Xpartners';
$scriptname           = 'xoopspartners.php';

$op = 'start';

if (isset($_POST['op']) && ('go' === $_POST['op'])) {
    $op = $_POST['op'];
}

if ('start' === $op) {
    Smartpartner\Utility::getXoopsCpHeader();
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $result = $xoopsDB->query('SELECT count(*) FROM ' . $xoopsDB->prefix('partners'));
    list($totalpartners) = $xoopsDB->fetchRow($result);
    Smartpartner\Utility::collapsableBar('bottomtable', 'bottomtableicon', sprintf(_AM_SPARTNER_IMPORT_FROM, $importFromModuleName), sprintf(_AM_SPARTNER_IMPORT_MODULE_FOUND, $importFromModuleName, $totalpartners));

    $form = new \XoopsThemeForm(_AM_SPARTNER_IMPORT_SETTINGS, 'import_form', XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/' . $scriptname);

    // Auto-Approve
    $form->addElement(new \XoopsFormLabel(_AM_SPARTNER_SMARTPARTNER_IMPORT_SETTINGS, _AM_SPARTNER_SMARTPARTNER_IMPORT_SETTINGS_VALUE));

    $form->addElement(new \XoopsFormHidden('op', 'go'));
    $form->addElement(new \XoopsFormButton('', 'import', _AM_SPARTNER_IMPORT, 'submit'));
    $form->display();

    //exit ();
}

if ('go' === $op) {
    require_once __DIR__ . '/admin_header.php';

    Smartpartner\Utility::getXoopsCpHeader();
    Smartpartner\Utility::collapsableBar('bottomtable', 'bottomtableicon', sprintf(_AM_SPARTNER_IMPORT_FROM, $importFromModuleName), _AM_SPARTNER_IMPORT_RESULT);
    $cnt_imported_partner = 0;

    $partnerHandler = Smartpartner\Helper::getInstance()->getHandler('Partner');

    $resultPartners = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('partners') . ' ');
    while (false !== ($arrPartners = $xoopsDB->fetchArray($resultPartners))) {
        extract($arrPartners, EXTR_PREFIX_ALL, 'xpartner');

        // insert partner into SmartPartner
        $partnerObj = $partnerHandler->create();

        if (0 == $xpartner_status) {
            $xpartner_status = _SPARTNER_STATUS_INACTIVE;
        } elseif (1 == $xpartner_status) {
            $xpartner_status = _SPARTNER_STATUS_ACTIVE;
        }

        $partnerObj->setVar('weight', $xpartner_weight);
        $partnerObj->setVar('hits', $xpartner_hits);
        $partnerObj->setVar('url', $xpartner_url);
        $partnerObj->setVar('image_url', $xpartner_image);
        $partnerObj->setVar('title', $xpartner_title);
        $partnerObj->setVar('summary', $xpartner_description);
        $partnerObj->setVar('status', $xpartner_status);

        if (!$partnerObj->store(false)) {
            echo sprintf('  ' . _AM_SPARTNER_IMPORT_PARTNER_ERROR, $xpartner_title) . '<br>';
            continue;
        } else {
            echo '&nbsp;&nbsp;' . sprintf(_AM_SPARTNER_IMPORTED_PARTNER, $partnerObj->title()) . '<br>';
            ++$cnt_imported_partner;
        }

        echo '<br>';
    }

    echo 'Done.<br>';
    echo sprintf(_AM_SPARTNER_IMPORTED_PARTNERS, $cnt_imported_partner) . '<br>';

    //exit ();
}
echo '</div>';
//Smartobject\Utility::getModFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
exit();
