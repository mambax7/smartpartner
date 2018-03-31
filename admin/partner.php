<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param $partnerObj
 */


use XoopsModules\Smartpartner;
use XoopsModules\Smartobject;
use XoopsModules\Smartpartner\Constants;
/** @var Smartpartner\Helper $helper */
$helper = Smartpartner\Helper::getInstance();

/**
 * @param $partnerObj
 */
function showfiles($partnerObj)
{
    // UPLOAD FILES
    //require_once XOOPS_ROOT_PATH . '/modules/smartpartner/include/functions.php';
    global $xoopsModule, $fileHandler;
    $pathIcon16 = \Xmf\Module\Admin::iconUrl('', 16);
    Smartpartner\Utility::collapsableBar('filetable', 'filetableicon', _AM_SPARTNER_FILES_LINKED);
    $filesObj = $fileHandler->getAllFiles($partnerObj->id());
    if (count($filesObj) > 0) {
        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo '<tr>';
        echo "<td width='50' class='bg3' align='center'><b>ID</b></td>";
        echo "<td width='150' class='bg3' align='left'><b>" . _AM_SPARTNER_FILENAME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_SPARTNER_DESCRIPTION . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_SPARTNER_HITS . '</b></td>';
        echo "<td width='100' class='bg3' align='center'><b>" . _AM_SPARTNER_UPLOADED_DATE . '</b></td>';
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_SPARTNER_ACTION . '</b></td>';
        echo '</tr>';

        for ($i = 0, $iMax = count($filesObj); $i < $iMax; ++$i) {
            $modify = "<a href='file.php?op=mod&fileid=" . $filesObj[$i]->fileid() . "'><img src='" . $pathIcon16 . '/edit.png' . "'  title='" . _AM_SPARTNER_EDITFILE . "' alt='" . _AM_SPARTNER_EDITFILE . "'></a>";
            $delete = "<a href='file.php?op=del&fileid=" . $filesObj[$i]->fileid() . "'><img src='" . $pathIcon16 . '/delete.png' . "'  title='" . _AM_SPARTNER_DELETEFILE . "' alt='" . _AM_SPARTNER_DELETEFILE . "'></a>";
            if (0 == $filesObj[$i]->status()) {
                $not_visible = "<img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/no.gif'>";
            } else {
                $not_visible = '';
            }
            echo '<tr>';
            echo "<td class='head' align='center'>" . $filesObj[$i]->getVar('fileid') . '</td>';
            echo "<td class='odd' align='left'>" . $not_visible . $filesObj[$i]->getFileLink() . '</td>';
            echo "<td class='even' align='left'>" . $filesObj[$i]->description() . '</td>';
            echo "<td class='even' align='center'>" . $filesObj[$i]->counter() . '';
            echo "<td class='even' align='center'>" . $filesObj[$i]->datesub() . '</td>';
            echo "<td class='even' align='center'> $modify $delete </td>";
            echo '</tr>';
        }
        echo '</table>';
        echo '<br >';
    } else {
        echo '<span style="color: #567; margin: 3px 0 12px 0; font-size: small; display: block; ">' . _AM_SPARTNER_NOFILE . '</span>';
    }

    echo '<form><div style="margin-bottom: 24px;">';
    echo "<input type='button' name='button' onclick=\"location='file.php?op=mod&id=" . $partnerObj->id() . "'\" value='" . _AM_SPARTNER_UPLOAD_FILE_NEW . "'>&nbsp;&nbsp;";
    echo '</div></form>';

    Smartpartner\Utility::closeCollapsable('filetable', 'filetableicon');
}

/**
 * @param bool $showmenu
 * @param int  $id
 */
function editpartner($showmenu = false, $id = 0)
{
    global $xoopsDB, $partnerHandler, $xoopsUser, $xoopsConfig,  $xoopsModule;
    /** @var Smartpartner\Helper $helper */
    $helper = Smartpartner\Helper::getInstance();
    if (!isset($partnerHandler)) {
        $partnerHandler = Smartpartner\Helper::getInstance()->getHandler('Partner');
    }
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    // If there is a parameter, and the id exists, retrieve data: we're editing a partner
    if (0 != $id) {
        // Creating the partner object
        $partnerObj = new Smartpartner\Partner($id);

        if ($partnerObj->notLoaded()) {
            redirect_header('partner.php', 1, _AM_SPARTNER_NOPARTNERSELECTED);
        }

        switch ($partnerObj->status()) {

            case Constants::_SPARTNER_STATUS_SUBMITTED:
                $breadcrumb_action1 = _AM_SPARTNER_SUBMITTED_PARTNERS;
                $breadcrumb_action2 = _AM_SPARTNER_APPROVING;
                $page_title         = _AM_SPARTNER_SUBMITTED_TITLE;
                $page_info          = _AM_SPARTNER_SUBMITTED_INFO;
                $button_caption     = _AM_SPARTNER_APPROVE;
                $new_status         = Constants::_SPARTNER_STATUS_ACTIVE;
                break;

            case Constants::_SPARTNER_STATUS_ACTIVE:
                $breadcrumb_action1 = _AM_SPARTNER_ACTIVE_PARTNERS;
                $breadcrumb_action2 = _AM_SPARTNER_EDITING;
                $page_title         = _AM_SPARTNER_ACTIVE_EDITING;
                $page_info          = _AM_SPARTNER_ACTIVE_EDITING_INFO;
                $button_caption     = _AM_SPARTNER_MODIFY;
                $new_status         = Constants::_SPARTNER_STATUS_ACTIVE;
                break;

            case Constants::_SPARTNER_STATUS_INACTIVE:
                $breadcrumb_action1 = _AM_SPARTNER_INACTIVE_PARTNERS;
                $breadcrumb_action2 = _AM_SPARTNER_EDITING;
                $page_title         = _AM_SPARTNER_INACTIVE_EDITING;
                $page_info          = _AM_SPARTNER_INACTIVE_EDITING_INFO;
                $button_caption     = _AM_SPARTNER_MODIFY;
                $new_status         = Constants::_SPARTNER_STATUS_INACTIVE;
                break;

            case Constants::_SPARTNER_STATUS_REJECTED:
                $breadcrumb_action1 = _AM_SPARTNER_REJECTED_PARTNERS;
                $breadcrumb_action2 = _AM_SPARTNER_EDITING;
                $page_title         = _AM_SPARTNER_REJECTED_EDITING;
                $page_info          = _AM_SPARTNER_REJECTED_EDITING_INFO;
                $button_caption     = _AM_SPARTNER_MODIFY;
                $new_status         = Constants::_SPARTNER_STATUS_REJECTED;
                break;

            case 'default':
            default:
                break;
        }

        echo "<br>\n";
        Smartpartner\Utility::collapsableBar('editpartner', 'editpartmericon', $page_title, $page_info);
    } else {
        // there's no parameter, so we're adding a partner
        $partnerObj         = $partnerHandler->create();
        $breadcrumb_action1 = _AM_SPARTNER_PARTNERS;
        $breadcrumb_action2 = _AM_SPARTNER_CREATE;
        $button_caption     = _AM_SPARTNER_CREATE;
        $new_status         = Constants::_SPARTNER_STATUS_ACTIVE;
        Smartpartner\Utility::collapsableBar('addpartner', 'addpartnericon', _AM_SPARTNER_PARTNER_CREATING, _AM_SPARTNER_PARTNER_CREATING_DSC);
    }

    // PARTNER FORM
    $sform = new \XoopsThemeForm(_AM_SPARTNER_PARTNERS, 'op', xoops_getenv('PHP_SELF'), 'post', true);
    $sform->setExtra('enctype="multipart/form-data"');

    // TITLE
    $title_text = new \XoopsFormText(_AM_SPARTNER_TITLE, 'title', 50, 255, $partnerObj->title('e'));
    $sform->addElement($title_text, true);

    // Parent Category
    $mytree = new Smartpartner\SmartTree($xoopsDB->prefix('smartpartner_categories'), 'categoryid', 'parentid');
    ob_start();
    $mytree->makeMySelBox('name', 'weight', explode('|', $partnerObj->categoryid()), 0, 'categoryid', '', true);
    //makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
    $parent_cat_select = new \XoopsFormLabel(_AM_SPARTNER_CATEGORY_BELONG, ob_get_contents());
    $parent_cat_select->setDescription(_AM_SPARTNER_BELONG_CATEGORY_DSC);
    $sform->addElement($parent_cat_select);
    ob_end_clean();

    // LOGO
    $logo_array  = XoopsLists::getImgListAsArray(Smartpartner\Utility::getImageDir());
    $logo_select = new \XoopsFormSelect('', 'image', $partnerObj->image());
    $logo_select->addOption('-1', '---------------');
    $logo_select->addOptionArray($logo_array);
    $logo_select->setExtra("onchange='showImgSelected(\"image3\", \"image\", \"" . 'uploads/' . SMARTPARTNER_DIRNAME . '/images' . '", "", "' . XOOPS_URL . "\")'");
    $logo_tray = new \XoopsFormElementTray(_AM_SPARTNER_LOGO, '&nbsp;');
    $logo_tray->addElement($logo_select);
    $logo_tray->addElement(new \XoopsFormLabel('', "<br><br><img src='" . Smartpartner\Utility::getImageDir('', false) . $partnerObj->image() . "' name='image3' id='image3' alt=''>"));
    $logo_tray->setDescription(_AM_SPARTNER_LOGO_DSC);
    $sform->addElement($logo_tray);

    // LOGO UPLOAD
    $max_size = 5000000;
    $file_box = new \XoopsFormFile(_AM_SPARTNER_LOGO_UPLOAD, 'logo_file', $max_size);
    $file_box->setExtra("size ='45'");
    $file_box->setDescription(sprintf(_AM_SPARTNER_LOGO_UPLOAD_DSC, $helper->getConfig('img_max_width'), $helper->getConfig('img_max_height')));
    $sform->addElement($file_box);

    // IMAGE_URL
    $image_url_text = new \XoopsFormText(_CO_SPARTNER_IMAGE_URL, 'image_url', 50, 255, $partnerObj->image_url());
    $image_url_text->setDescription(_CO_SPARTNER_IMAGE_URL_DSC);
    $sform->addElement($image_url_text, false);

    // URL
    $url_text = new \XoopsFormText(_AM_SPARTNER_URL, 'url', 50, 255, $partnerObj->url());
    $url_text->setDescription(_AM_SPARTNER_URL_DSC);
    $sform->addElement($url_text, false);

    // SUMMARY
    $summary_text = new \XoopsFormTextArea(_AM_SPARTNER_SUMMARY, 'summary', $partnerObj->summary(0, 'e'), 7, 60);
    $summary_text->setDescription(_AM_SPARTNER_SUMMARY_DSC);
    $sform->addElement($summary_text, true);

    // SHOW summary on partner page
    $showsum_radio = new \XoopsFormRadioYN(_AM_SPARTNER_SHOW_SUMMARY, 'showsummary', $partnerObj->getVar('showsummary'));
    $showsum_radio->setDescription(_AM_SPARTNER_SHOW_SUMMARY_DSC);
    $sform->addElement($showsum_radio);

    // DESCRIPTION
    $description_text = new \XoopsFormDhtmlTextArea(_AM_SPARTNER_DESCRIPTION, 'description', $partnerObj->description(0, 'e'), 15, 60);
    $description_text->setDescription(_AM_SPARTNER_DESCRIPTION_DSC);
    $sform->addElement($description_text, false);

    // CONTACT_NAME
    $contact_name_text = new \XoopsFormText(_CO_SPARTNER_CONTACT_NAME, 'contact_name', 50, 255, $partnerObj->contact_name('e'));
    $contact_name_text->setDescription(_CO_SPARTNER_CONTACT_NAME_DSC);
    $sform->addElement($contact_name_text, false);

    // CONTACT_EMAIL
    $contact_email_text = new \XoopsFormText(_CO_SPARTNER_CONTACT_EMAIL, 'contact_email', 50, 255, $partnerObj->contact_email('e'));
    $contact_email_text->setDescription(_CO_SPARTNER_CONTACT_EMAIL_DSC);
    $sform->addElement($contact_email_text, false);

    // EMAIL_PRIV
    $email_priv_radio = new \XoopsFormRadioYN(_CO_SPARTNER_CONTACT_EMAILPRIV, 'email_priv', $partnerObj->email_priv('e'));
    $email_priv_radio->setDescription(_CO_SPARTNER_CONTACT_EMAILPRIV_DSC);
    $sform->addElement($email_priv_radio);

    // CONTACT_PHONE
    $contact_phone_text = new \XoopsFormText(_CO_SPARTNER_CONTACT_PHONE, 'contact_phone', 50, 255, $partnerObj->contact_phone('e'));
    $contact_phone_text->setDescription(_CO_SPARTNER_CONTACT_PHONE_DSC);
    $sform->addElement($contact_phone_text, false);

    // PHONE_PRIV
    $phone_priv_radio = new \XoopsFormRadioYN(_CO_SPARTNER_CONTACT_PHONEPRIV, 'phone_priv', $partnerObj->phone_priv('e'));
    $phone_priv_radio->setDescription(_CO_SPARTNER_CONTACT_PHONEPRIV_DSC);
    $sform->addElement($phone_priv_radio);

    // ADRESS
    //$adress_text = new \XoopsFormText(_CO_SPARTNER_ADRESS, 'adress', 50, 255, $partnerObj->adress('e'));
    $adress_text = new \XoopsFormTextArea(_CO_SPARTNER_ADRESS, 'adress', $partnerObj->adress('e'));
    $adress_text->setDescription(_CO_SPARTNER_ADRESS_DSC);
    $sform->addElement($adress_text, false);

    // ADRESS_PRIV
    $adress_priv_radio = new \XoopsFormRadioYN(_CO_SPARTNER_CONTACT_ADRESSPRIV, 'adress_priv', $partnerObj->adress_priv('e'));
    $adress_priv_radio->setDescription(_CO_SPARTNER_CONTACT_ADRESSPRIV_DSC);
    $sform->addElement($adress_priv_radio);

    // STATUS
    $options       = $partnerObj->getAvailableStatus();
    $status_select = new \XoopsFormSelect(_AM_SPARTNER_STATUS, 'status', $new_status);
    $status_select->addOptionArray($options);
    $status_select->setDescription(_AM_SPARTNER_STATUS_DSC);
    $sform->addElement($status_select);

    // WEIGHT
    $weight_text = new \XoopsFormText(_AM_SPARTNER_WEIGHT, 'weight', 4, 4, $partnerObj->weight());
    $weight_text->setDescription(_AM_SPARTNER_WEIGHT_DSC);
    $sform->addElement($weight_text);

    //perms
    global $smartPermissionsHandler;
//    require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectpermission.php';
    $smartPermissionsHandler = new Smartobject\PermissionHandler($partnerHandler);

    if (0 != $partnerObj->id()) {
        $grantedGroups = $smartPermissionsHandler->getGrantedGroups('full_view', $partnerObj->id());
    } else {
        $grantedGroups = $helper->getConfig('default_full_view');
    }
    $full_view_select = new \XoopsFormSelectGroup(_CO_SPARTNER_FULL_PERM_READ, 'full_view', true, $grantedGroups, 5, true);
    $full_view_select->setDescription(_CO_SPARTNER_FULL_PERM_READ_DSC);
    $sform->addElement($full_view_select);

    if (0 != $partnerObj->id()) {
        $partGrantedGroups = $smartPermissionsHandler->getGrantedGroups('partial_view', $partnerObj->id());
    } else {
        $partGrantedGroups = $helper->getConfig('default_part_view');
    }
    $part_view_select = new \XoopsFormSelectGroup(_CO_SPARTNER_PART_PERM_READ, 'partial_view', true, $partGrantedGroups, 5, true);
    $part_view_select->setDescription(_CO_SPARTNER_PART_PERM_READ_DSC);
    $sform->addElement($part_view_select);

    // Partner id
    $sform->addElement(new \XoopsFormHidden('id', $partnerObj->id()));

    $button_tray = new \XoopsFormElementTray('', '');
    $hidden      = new \XoopsFormHidden('op', 'addpartner');
    $button_tray->addElement($hidden);

    $sform->addElement(new \XoopsFormHidden('original_status', $partnerObj->status()));

    if (!$id) {
        // there's no id? Then it's a new partner
        // $button_tray -> addElement( new \XoopsFormButton( '', 'mod', _AM_SPARTNER_CREATE, 'submit' ) );
        $butt_create = new \XoopsFormButton('', '', _AM_SPARTNER_CREATE, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addpartner\'"');
        $button_tray->addElement($butt_create);

        $butt_clear = new \XoopsFormButton('', '', _AM_SPARTNER_CLEAR, 'reset');
        $button_tray->addElement($butt_clear);

        $butt_cancel = new \XoopsFormButton('', '', _AM_SPARTNER_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);
    } else {
        // else, we're editing an existing partner
        // $button_tray -> addElement( new \XoopsFormButton( '', 'mod', _AM_SPARTNER_MODIFY, 'submit' ) );
        $butt_create = new \XoopsFormButton('', '', $button_caption, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addpartner\'"');
        $button_tray->addElement($butt_create);

        $butt_cancel = new \XoopsFormButton('', '', _AM_SPARTNER_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);
    }

    $sform->addElement($button_tray);
    $sform->display();
    unset($hidden);
    if (!$id) {
        Smartpartner\Utility::closeCollapsable('addpartner', 'addpartnericon');
    } else {
        Smartpartner\Utility::closeCollapsable('editpartner', 'editpartnericon');
    }
    if (0 != $id) {
        showfiles($partnerObj);
    }
}

require_once __DIR__ . '/admin_header.php';
include XOOPS_ROOT_PATH . '/class/xoopstree.php';

$op = '';
if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

// Where shall we start ?
$startpartner = \Xmf\Request::getInt('startpartner', 0, 'GET');

if (!isset($partnerHandler)) {
    $partnerHandler = Smartpartner\Helper::getInstance()->getHandler('Partner');
}
/* -- Available operations -- */
switch ($op) {
    case 'add':
        xoops_cp_header();
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        editpartner(true, 0);
        break;

    case 'mod':
        global $xoopsUser, $xoopsConfig, $xoopsModule;
        $id = \Xmf\Request::getInt('id', 0, GET);

        xoops_cp_header();
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        editpartner(true, $id);
        break;

    case 'addpartner':
        global $xoopsUser;

        if (!$xoopsUser) {
            if (1 == $helper->getConfig('anonpost')) {
                $uid = 0;
            } else {
                redirect_header('index.php', 3, _NOPERM);
            }
        } else {
            $uid = $xoopsUser->uid();
        }

        $id = \Xmf\Request::getInt('id', 0, 'POST');

        // Creating the partner object
        if (0 != $id) {
            $partnerObj = new Smartpartner\Partner($id);
        } else {
            $partnerObj = $partnerHandler->create();
        }

        // Uploading the logo, if any
        // Retreive the filename to be uploaded
        if ('' != $_FILES['logo_file']['name']) {
            $filename = $_POST['xoops_upload_file'][0];
            if (!empty($filename) || '' != $filename) {
//                global $xoopsModuleConfig;

                $max_size          = 10000000;
                $max_imgwidth      = $helper->getConfig('img_max_width');
                $max_imgheight     = $helper->getConfig('img_max_height');
                $allowed_mimetypes = null; //smartpartner_getAllowedMimeTypes();

                require_once XOOPS_ROOT_PATH . '/class/uploader.php';

                if ('' == $_FILES[$filename]['tmp_name'] || !is_readable($_FILES[$filename]['tmp_name'])) {
                    redirect_header('javascript:history.go(-1)', 2, _CO_SPARTNER_FILE_UPLOAD_ERROR);
                }

                $uploader = new \XoopsMediaUploader(Smartpartner\Utility::getImageDir(), $allowed_mimetypes, $max_size, $max_imgwidth, $max_imgheight);

                // TODO: prefix the image file with the partnerid, but for that we need to first save the partner to get partnerid...
                // $uploader->setTargetFileName($partnerObj->partnerid() . "_" . $_FILES['logo_file']['name']);

                if ($uploader->fetchMedia($filename) && $uploader->upload()) {
                    $partnerObj->setVar('image', $uploader->getSavedFileName());
                } else {
                    redirect_header('javascript:history.go(-1)', 2, _CO_SPARTNER_FILE_UPLOAD_ERROR . $uploader->getErrors());
                }
            }
        } else {
            $partnerObj->setVar('image', $_POST['image']);
        }

        // Putting the values in the partner object
        $partnerObj->setVar('id', isset($_POST['id']) ? (int)$_POST['id'] : 0);
        $partnerObj->setVar('categoryid', isset($_POST['categoryid']) ? implode('|', $_POST['categoryid']) : [0]);
        $partnerObj->setVar('status', isset($_POST['status']) ? (int)$_POST['status'] : 0);
        $partnerObj->setVar('title', $_POST['title']);
        $partnerObj->setVar('summary', $_POST['summary']);
        $partnerObj->setVar('image_url', $_POST['image_url']);
        $partnerObj->setVar('description', $_POST['description']);
        $partnerObj->setVar('contact_name', $_POST['contact_name']);
        $partnerObj->setVar('contact_email', $_POST['contact_email']);
        $partnerObj->setVar('contact_phone', $_POST['contact_phone']);
        $partnerObj->setVar('adress', $_POST['adress']);
        $partnerObj->setVar('url', $_POST['url']);
        $partnerObj->setVar('weight', isset($_POST['weight']) ? (int)$_POST['weight'] : 0);
        $partnerObj->setVar('email_priv', isset($_POST['email_priv']) ? (int)$_POST['email_priv'] : 0);
        $partnerObj->setVar('phone_priv', isset($_POST['phone_priv']) ? (int)$_POST['phone_priv'] : 0);
        $partnerObj->setVar('adress_priv', isset($_POST['adress_priv']) ? (int)$_POST['adress_priv'] : 0);
        $partnerObj->setVar('showsummary', isset($_POST['showsummary']) ? (int)$_POST['showsummary'] : 0);

        $redirect_msgs = $partnerObj->getRedirectMsg($_POST['original_status'], $_POST['status']);

        // Storing the partner
        if (!$partnerObj->store()) {
            redirect_header('javascript:history.go(-1)', 3, $redirect_msgs['error'] . Smartpartner\Utility::formatErrors($partnerObj->getErrors()));
        }

        if ((_SPARTNER_STATUS_SUBMITTED == $_POST['original_status']) || (_SPARTNER_STATUS_ACTIVE == $_POST['status'])) {
            $partnerObj->sendNotifications([_SPARTNER_NOT_PARTNER_APPROVED]);
        }
        if ($partnerObj->isNew()) {
            $partnerObj->sendNotifications([_SPARTNER_NOT_PARTNER_NEW]);
        }
        redirect_header('partner.php', 2, $redirect_msgs['success']);

        break;

    case 'del':
        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $_GET;

        $module_id    = $xoopsModule->getVar('mid');
        $gpermHandler = xoops_getHandler('groupperm');

        $id = \Xmf\Request::getInt('id', 0, 'POST');
        $id = \Xmf\Request::getInt('id', $id, 'GET');

        $partnerObj = new Smartpartner\Partner($id);

        $confirm = \Xmf\Request::getInt('confirm', 0, POST);
        $title   = \Xmf\Request::getString('title', '', 'POST');

        if ($confirm) {
            if (!$partnerHandler->delete($partnerObj)) {
                redirect_header('partner.php', 2, _AM_SPARTNER_PARTNER_DELETE_ERROR);
            }

            redirect_header('partner.php', 2, sprintf(_AM_SPARTNER_PARTNER_DELETE_SUCCESS, $partnerObj->title()));
        } else {
            // no confirm: show deletion condition
            $id = \Xmf\Request::getInt('id', 0, 'GET');
            xoops_cp_header();
            xoops_confirm(['op' => 'del', 'id' => $partnerObj->id(), 'confirm' => 1, 'name' => $partnerObj->title()], 'partner.php', _AM_SPARTNER_DELETETHISP . " <br>'" . $partnerObj->title() . "' <br> <br>", _AM_SPARTNER_DELETE);
            xoops_cp_footer();
        }

        exit();
        break;

    case 'default':
    default:
        Smartpartner\Utility::getXoopsCpHeader();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        $adminObject->addItemButton(_AM_SPARTNER_PARTNER_CREATE, 'partner.php?op=add', 'add', '');
        $adminObject->displayButton('left', '');

        //        echo "<br>\n";
        //        echo "<form><div style=\"margin-bottom: 12px;\">";
        //        echo "<input type='button' name='button' onclick=\"location='partner.php?op=mod'\" value='" . _AM_SPARTNER_PARTNER_CREATE . "'>&nbsp;&nbsp;";
        //        echo "</div></form>";

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModule;

        Smartpartner\Utility::collapsableBar('partners', 'partnersicon', _AM_SPARTNER_ACTIVE_PARTNERS, _AM_SPARTNER_ACTIVE_PARTNERS_DSC);

        // Get the total number of published PARTNER
        $totalpartners = $partnerHandler->getPartnerCount(Constants::_SPARTNER_STATUS_ACTIVE);
        // creating the partner objects that are published
        $partnersObj         = $partnerHandler->getPartners($helper->getConfig('perpage_admin'), $startpartner);
        $totalPartnersOnPage = count($partnersObj);

        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
        echo '<tr>';
        echo "<td class='bg3' width='200px' align='left'><b>" . _AM_SPARTNER_NAME . '</b></td>';
        echo "<td width='' class='bg3' align='left'><b>" . _AM_SPARTNER_INTRO . '</b></td>';
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_SPARTNER_HITS . '</b></td>';
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_SPARTNER_STATUS . '</b></td>';
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_SPARTNER_ACTION . '</b></td>';
        echo '</tr>';
        if ($totalpartners > 0) {
            for ($i = 0; $i < $totalPartnersOnPage; ++$i) {
                $modify = "<a href='partner.php?op=mod&id=" . $partnersObj[$i]->id() . "'><img src='" . $pathIcon16 . '/edit.png' . "'  title='" . _AM_SPARTNER_EDITPARTNER . "' alt='" . _AM_SPARTNER_EDITPARTNER . "'></a>&nbsp;";
                $delete = "<a href='partner.php?op=del&id=" . $partnersObj[$i]->id() . "'><img src='" . $pathIcon16 . '/delete.png' . "'  title='" . _AM_SPARTNER_DELETEPARTNER . "' alt='" . _AM_SPARTNER_DELETEPARTNER . "'></a>&nbsp;";

                echo '<tr>';
                echo "<td class='head' align='left'><a href='" . SMARTPARTNER_URL . 'partner.php?id=' . $partnersObj[$i]->id() . "'><img src='" . SMARTPARTNER_URL . "assets/images/links/partner.gif' alt=''>&nbsp;" . $partnersObj[$i]->title() . '</a></td>';
                echo "<td class='even' align='left'>" . $partnersObj[$i]->summary(100) . '</td>';
                echo "<td class='even' align='center'>" . $partnersObj[$i]->hits() . '</td>';
                echo "<td class='even' align='center'>" . $partnersObj[$i]->getStatusName() . '</td>';
                echo "<td class='even' align='center'> " . $modify . $delete . '</td>';
                echo '</tr>';
            }
        } else {
            $id = 0;
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '7'>" . _AM_SPARTNER_NOPARTNERS . '</td>';
            echo '</tr>';
        }
        echo "</table>\n";
        echo "<br>\n";

        $pagenav = new \XoopsPageNav($totalpartners, $helper->getConfig('perpage_admin'), $startpartner, 'startpartner');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        Smartpartner\Utility::closeCollapsable('partners', 'partnersicon');

        break;
}
//Smartobject\Utility::getModFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
