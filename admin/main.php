<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartpartner;
use XoopsModules\Smartpartner\Constants;
/** @var Smartpartner\Helper $helper */
$helper = Smartpartner\Helper::getInstance();

require_once __DIR__ . '/admin_header.php';
$myts = \MyTextSanitizer::getInstance();

$op = \Xmf\Request::getString('op', '', 'GET');

switch ($op) {
    case 'createdir':
        $path = isset($_GET['path']) ? $_GET['path'] : false;
        if ($path) {
            if ('root' === $path) {
                $path = '';
            }
            $thePath = Smartpartner\Utility::getUploadDir(true, $path);

            $res = Smartpartner\Utility::mkdirAsAdmin($thePath);
            if ($res) {
                $source = SMARTPARTNER_ROOT_PATH . 'assets/images/blank.png';
                $dest   = $thePath . 'blank.png';

                try {
                    Smartpartner\Utility::copyr($source, $dest);
                }
                catch (\Exception $e) {
                }
            }
            $msg = $res ? _AM_SPARTNER_DIRCREATED : _AM_SPARTNER_DIRNOTCREATED;
        } else {
            $msg = _AM_SPARTNER_DIRNOTCREATED;
        }

        redirect_header('main.php', 2, $msg . ': ' . $thePath);

        break;
}
$pick = \Xmf\Request::getInt('pick', 0, 'GET');
$pick = \Xmf\Request::getInt('pick', $pick, 'POST');

$statussel = \Xmf\Request::getInt('statussel', 0, 'GET');
$statussel = \Xmf\Request::getInt('statussel', $statussel, 'POST');

$sortsel = isset($_GET['sortsel']) ? $_GET['sortsel'] : 'id';
$sortsel = isset($_POST['sortsel']) ? $_POST['sortsel'] : $sortsel;

$ordersel = isset($_GET['ordersel']) ? $_GET['ordersel'] : 'DESC';
$ordersel = isset($_POST['ordersel']) ? $_POST['ordersel'] : $ordersel;

$module_id = $xoopsModule->getVar('mid');

function pathConfiguration()
{
    global $xoopsModule;
    // Upload and Images Folders
    Smartpartner\Utility::collapsableBar('configtable', 'configtableicon', _AM_SPARTNER_PATHCONFIGURATION);
    echo '<br>';
    echo "<table width='100%' class='outer' cellspacing='1' cellpadding='3' border='0' ><tr>";
    echo "<td class='bg3'><b>" . _AM_SPARTNER_PATH_ITEM . '</b></td>';
    echo "<td class='bg3'><b>" . _AM_SPARTNER_PATH . '</b></td>';
    echo "<td class='bg3' align='center'><b>" . _AM_SPARTNER_STATUS . '</b></td></tr>';

    echo "<tr><td class='odd'>" . _AM_SPARTNER_PATH_IMAGES . '</td>';
    $image_path = Smartpartner\Utility::getImageDir();
    echo "<td class='odd'>" . $image_path . '</td>';
    echo "<td class='even' style='text-align: center;'>" . Smartpartner\Utility::getPathStatusAsAdmin('images') . '</td></tr>';

    echo "<tr><td class='odd'>" . _AM_SPARTNER_PATH_CATEGORY_IMAGES . '</td>';
    $image_path = Smartpartner\Utility::getImageDir('category');
    echo "<td class='odd'>" . $image_path . '</td>';
    echo "<td class='even' style='text-align: center;'>" . Smartpartner\Utility::getPathStatusAsAdmin('images/category') . '</td></tr>';

    echo '</table>';
    echo '<br>';

    Smartpartner\Utility::closeCollapsable('configtable', 'configtableicon');
}

function buildTable()
{
    global $xoopsConfig,  $xoopsModule;
    echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
    echo '<tr>';
    echo "<td class='bg3' width='200px' align='left'><b>" . _AM_SPARTNER_NAME . '</b></td>';
    echo "<td width='' class='bg3' align='left'><b>" . _AM_SPARTNER_INTRO . '</b></td>';
    echo "<td width='90' class='bg3' align='center'><b>" . _AM_SPARTNER_HITS . '</b></td>';
    echo "<td width='90' class='bg3' align='center'><b>" . _AM_SPARTNER_STATUS . '</b></td>';
    echo "<td width='90' class='bg3' align='center'><b>" . _AM_SPARTNER_ACTION . '</b></td>';
    echo '</tr>';
}

// Code for the page
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

// Creating the Partner handler object
//$partnerHandler = Smartpartner\Helper::getInstance()->getHandler('Partner');

$startentry = \Xmf\Request::getInt('startentry', 0, 'GET');

Smartpartner\Utility::getXoopsCpHeader();
$adminObject = \Xmf\Module\Admin::getInstance();
//xoops_cp_header();
$adminObject->displayNavigation(basename(__FILE__));

global $xoopsUser, $xoopsConfig, $xoopsModule;

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

// Check Path Configuration
//if ((Smartpartner\Utility::getPathStatusAsAdmin('images', true) < 0) || (Smartpartner\Utility::getPathStatusAsAdmin('images/category', true) < 0)) {
//    pathConfiguration();
//}

$adminObject->addItemButton(_AM_SPARTNER_CATEGORY_CREATE, 'category.php?op=mod', 'add', '');
$adminObject->addItemButton(_AM_SPARTNER_PARTNER_CREATE, 'partner.php?op=add', 'add', '');
$adminObject->displayButton('left', '');

// -- //
//Smartpartner\Utility::collapsableBar('index', 'indexicon', _AM_SPARTNER_INVENTORY);
//echo "<br>";
//echo "<table width='100%' class='outer' cellspacing='1' cellpadding='3' border='0' ><tr>";
//echo "<td class='head'>" . _AM_SPARTNER_TOTAL_SUBMITTED . "</td><td align='center' class='even'>" . $totalsubmitted . "</td>";
//echo "<td class='head'>" . _AM_SPARTNER_TOTAL_ACTIVE . "</td><td align='center' class='even'>" . $totalactive . "</td>";
//echo "<td class='head'>" . _AM_SPARTNER_TOTAL_REJECTED . "</td><td align='center' class='even'>" . $totalrejected . "</td>";
//echo "<td class='head'>" . _AM_SPARTNER_TOTAL_INACTIVE . "</td><td align='center' class='even'>" . $totalinactive . "</td>";
//echo "</tr></table>";
//echo "<br>";
//
//echo "<form><div style=\"margin-bottom: 24px;\">";
//echo "<input type='button' name='button' onclick=\"location='category.php?op=mod'\" value='" . _AM_SPARTNER_CATEGORY_CREATE . "'>&nbsp;&nbsp;";
//echo "<input type='button' name='button' onclick=\"location='partner.php?op=add'\" value='" . _AM_SPARTNER_PARTNER_CREATE . "'>&nbsp;&nbsp;";
//echo "</div></form>";
//Smartpartner\Utility::closeCollapsable('index', 'indexicon');

// Construction of lower table
Smartpartner\Utility::collapsableBar('allitems', 'allitemsicon', _AM_SPARTNER_ALLITEMS, _AM_SPARTNER_ALLITEMSMSG);

$showingtxt   = '';
$selectedtxt  = '';
$cond         = '';
$selectedtxt0 = '';
$selectedtxt1 = '';
$selectedtxt2 = '';
$selectedtxt3 = '';
$selectedtxt4 = '';

$sorttxtid     = '';
$sorttxttitle  = '';
$sorttxtweight = '';

$ordertxtasc  = '';
$ordertxtdesc = '';

switch ($sortsel) {
    case 'title':
        $sorttxttitle = 'selected';
        break;

    case 'weight':
        $sorttxtweight = 'selected';
        break;

    default:
        $sorttxtid = 'selected';
        break;
}

switch ($ordersel) {
    case 'ASC':
        $ordertxtasc = 'selected';
        break;

    default:
        $ordertxtdesc = 'selected';
        break;
}

switch ($statussel) {
    case Constants::_SPARTNER_STATUS_ALL:
        $selectedtxt0        = 'selected';
        $caption             = _AM_SPARTNER_ALL;
        $cond                = '';
        $status_explaination = _AM_SPARTNER_ALL_EXP;
        break;

    case Constants::_SPARTNER_STATUS_SUBMITTED:
        $selectedtxt1        = 'selected';
        $caption             = _AM_SPARTNER_SUBMITTED;
        $cond                = ' WHERE status = ' . _SPARTNER_STATUS_SUBMITTED . ' ';
        $status_explaination = _AM_SPARTNER_SUBMITTED_EXP;
        break;

    case Constants::_SPARTNER_STATUS_ACTIVE:
        $selectedtxt2        = 'selected';
        $caption             = _AM_SPARTNER_ACTIVE;
        $cond                = ' WHERE status = ' . _SPARTNER_STATUS_ACTIVE . ' ';
        $status_explaination = _AM_SPARTNER_ACTIVE_EXP;
        break;

    case Constants::_SPARTNER_STATUS_REJECTED:
        $selectedtxt3        = 'selected';
        $caption             = _AM_SPARTNER_REJECTED;
        $cond                = ' WHERE status = ' . _SPARTNER_STATUS_REJECTED . ' ';
        $status_explaination = _AM_SPARTNER_REJECTED_EXP;
        break;

    case Constants::_SPARTNER_STATUS_INACTIVE:
        $selectedtxt4        = 'selected';
        $caption             = _AM_SPARTNER_INACTIVE;
        $cond                = ' WHERE status = ' . _SPARTNER_STATUS_INACTIVE . ' ';
        $status_explaination = _AM_SPARTNER_INACTIVE_EXP;
        break;
}

/* -- Code to show selected terms -- */
echo "<form name='pick' id='pick' action='" . $_SERVER['PHP_SELF'] . "' method='POST' style='margin: 0;'>";
echo "
    <table width='100%' cellspacing='1' cellpadding='2' border='0' style='border-left: 1px solid silver; border-top: 1px solid silver; border-right: 1px solid silver;'>
        <tr>
            <td><span style='font-weight: bold; font-variant: small-caps;'>" . _AM_SPARTNER_SHOWING . ' ' . $caption . "</span></td>
            <td align='right'>" . _AM_SPARTNER_SELECT_SORT . "
                <select name='sortsel' onchange='submit()'>
                    <option value='id' $sorttxtid>" . _AM_SPARTNER_ID . "</option>
                    <option value='title' $sorttxttitle>" . _AM_SPARTNER_TITLE . "</option>
                    <option value='weight' $sorttxtweight>" . _AM_SPARTNER_WEIGHT . "</option>
                </select>
                <select name='ordersel' onchange='submit()'>
                    <option value='ASC' $ordertxtasc>" . _AM_SPARTNER_ASC . "</option>
                    <option value='DESC' $ordertxtdesc>" . _AM_SPARTNER_DESC . '</option>
                </select>
            ' . _AM_SPARTNER_SELECT_STATUS . ":
                <select name='statussel' onchange='submit()'>
                    <option value='0' $selectedtxt0>" . _AM_SPARTNER_ALL . " [$totalpartners]</option>
                    <option value='1' $selectedtxt1>" . _AM_SPARTNER_SUBMITTED . " [$totalsubmitted]</option>
                    <option value='2' $selectedtxt2>" . _AM_SPARTNER_ACTIVE . " [$totalactive]</option>
                    <option value='3' $selectedtxt3>" . _AM_SPARTNER_REJECTED . " [$totalrejected]</option>
                    <option value='4' $selectedtxt4>" . _AM_SPARTNER_INACTIVE . " [$totalinactive]</option>
                </select>
            </td>
        </tr>
    </table>
    </form>";

// Get number of entries in the selected state
$statusSelected = (0 == $statussel) ? Constants::_SPARTNER_STATUS_ALL : $statussel;

$numrows = $partnerHandler->getPartnerCount($statusSelected);
// creating the Q&As objects
$partnersObj = $partnerHandler->getPartners($helper->getConfig('perpage_admin'), $startentry, $statusSelected, $sortsel, $ordersel);

$totalPartnersOnPage = count($partnersObj);

buildTable();

if ($numrows > 0) {
    for ($i = 0; $i < $totalPartnersOnPage; ++$i) {
        $approve = '';
        switch ($partnersObj[$i]->status()) {

            case _SPARTNER_STATUS_SUBMITTED:
                $statustxt = _AM_SPARTNER_SUBMITTED;
                $approve   = "<a href='partner.php?op=mod&id=" . $partnersObj[$i]->id() . "'><img src='" . $pathIcon16 . '/on.png' . "'   title='" . _AM_SPARTNER_PARTNER_APPROVE . "' alt='" . _AM_SPARTNER_PARTNER_APPROVE . "'></a>&nbsp;";
                $delete    = "<a href='partner.php?op=del&id=" . $partnersObj[$i]->id() . "'><img src='" . $pathIcon16 . '/delete.png' . "'  title='" . _AM_SPARTNER_PARTNER_DELETE . "' alt='" . _AM_SPARTNER_PARTNER_DELETE . "'></a>&nbsp;";
                $modify    = '';
                break;

            case _SPARTNER_STATUS_ACTIVE:
                $statustxt = _AM_SPARTNER_ACTIVE;
                $approve   = '';
                $modify    = "<a href='partner.php?op=mod&id=" . $partnersObj[$i]->id() . "'><img src='" . $pathIcon16 . '/edit.png' . "' title='" . _AM_SPARTNER_PARTNER_EDIT . "' alt='" . _AM_SPARTNER_PARTNER_EDIT . "'></a>&nbsp;";
                $delete    = "<a href='partner.php?op=del&id=" . $partnersObj[$i]->id() . "'><img src='" . $pathIcon16 . '/delete.png' . "'  title='" . _AM_SPARTNER_PARTNER_DELETE . "' alt='" . _AM_SPARTNER_PARTNER_DELETE . "'></a>&nbsp;";
                break;

            case _SPARTNER_STATUS_INACTIVE:
                $statustxt = _AM_SPARTNER_INACTIVE;
                $approve   = '';
                $modify    = "<a href='partner.php?op=mod&id=" . $partnersObj[$i]->id() . "'><img src='" . $pathIcon16 . '/edit.png' . "' title='" . _AM_SPARTNER_PARTNER_EDIT . "' alt='" . _AM_SPARTNER_PARTNER_EDIT . "'></a>&nbsp;";
                $delete    = "<a href='partner.php?op=del&id=" . $partnersObj[$i]->id() . "'><img src='" . $pathIcon16 . '/delete.png' . "'  title='" . _AM_SPARTNER_PARTNER_DELETE . "' alt='" . _AM_SPARTNER_PARTNER_DELETE . "'></a>&nbsp;";
                break;

            case _SPARTNER_STATUS_REJECTED:
                $statustxt = _AM_SPARTNER_REJECTED;
                $approve   = '';
                $modify    = "<a href='partner.php?op=mod&id=" . $partnersObj[$i]->id() . "'><img src='" . $pathIcon16 . '/edit.png' . "' title='" . _AM_SPARTNER_PARTNER_EDIT . "' alt='" . _AM_SPARTNER_PARTNER_EDIT . "'></a>&nbsp;";
                $delete    = "<a href='partner.php?op=del&id=" . $partnersObj[$i]->id() . "'><img src='" . $pathIcon16 . '/delete.png' . "'  title='" . _AM_SPARTNER_PARTNER_DELETE . "' alt='" . _AM_SPARTNER_PARTNER_DELETE . "'></a>&nbsp;";
                break;

            case 'default':
            default:
                $statustxt = '';
                $approve   = '';
                $modify    = '';
                break;
        }

        echo '<tr>';
        echo "<td class='head' align='left'><a href='" . SMARTPARTNER_URL . 'partner.php?id=' . $partnersObj[$i]->id() . "'><img src='" . SMARTPARTNER_URL . "assets/images/links/partner.gif' alt=''>&nbsp;" . $partnersObj[$i]->title() . '</a></td>';
        echo "<td class='even' align='left'>" . $partnersObj[$i]->summary(100) . '</td>';
        echo "<td class='even' align='center'>" . $partnersObj[$i]->hits() . '</td>';
        echo "<td class='even' align='center'>" . $statustxt . '</td>';
        echo "<td class='even' align='center'> " . $approve . $modify . $delete . '</td>';
        echo '</tr>';
    }
} else {
    // that is, $numrows = 0, there's no entries yet
    echo '<tr>';
    echo "<td class='head' align='center' colspan= '7'>" . _AM_SPARTNER_NOPARTNERS . '</td>';
    echo '</tr>';
}
echo "</table>\n";
echo "<span style=\"color: #567; margin: 3px 0 18px 0; font-size: small; display: block; \">$status_explaination</span>";

$pagenav = new \XoopsPageNav($numrows, $helper->getConfig('perpage_admin'), $startentry, 'startentry', "statussel=$statussel&amp;sortsel=$sortsel&amp;ordersel=$ordersel");

if (1 == $helper->getConfig('useimagenavpage')) {
    echo '<div style="text-align:right; background-color: white; margin: 10px 0;">' . $pagenav->renderImageNav() . '</div>';
} else {
    echo '<div style="text-align:right; background-color: white; margin: 10px 0;">' . $pagenav->renderNav() . '</div>';
}
// ENDs code to show active entries
Smartpartner\Utility::closeCollapsable('allitems', 'allitemsicon');
// Close the collapsable div
// Check Path Configuration
if ((Smartpartner\Utility::getPathStatusAsAdmin('images', true) > 0) && (Smartpartner\Utility::getPathStatusAsAdmin('images/category', true) > 0)) {
    pathConfiguration();
}
echo '</div>';
echo '</div>';

//Smartobject\Utility::getModFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
