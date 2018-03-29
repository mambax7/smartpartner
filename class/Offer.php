<?php namespace XoopsModules\Smartpartner;

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
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 */

use XoopsModules\Smartpartner;
use XoopsModules\Smartobject;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');
//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobject.php';
//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjecthandler.php';

/**
 * Class Offer
 */
class Offer extends Smartpartner\BaseSmartObject
{
    /**
     * Offer constructor.
     */
    public function __construct()
    {
        $this->initVar('offerid', XOBJ_DTYPE_INT, '', true);
        $this->initVar('partnerid', XOBJ_DTYPE_INT, '', true, 255, '', false, _CO_SPARTNER_OFFER_PARTNER, _CO_SPARTNER_OFFER_PARTNER_DSC, true);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, '', true, 255, '', false, _CO_SPARTNER_OFFER_TITLE, _CO_SPARTNER_OFFER_TITLE_DSC, true);

        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '', false, null, '', false, _CO_SPARTNER_OFFER_DESC, _CO_SPARTNER_OFFER_DESC_DSC);
        $this->initVar('url', XOBJ_DTYPE_TXTBOX, '', false, 255, '', false, _CO_SPARTNER_OFFER_URL, _CO_SPARTNER_OFFER_URL_DSC, true);
        $this->initVar('image', XOBJ_DTYPE_TXTBOX, '', false, null, '', false, _CO_SPARTNER_OFFER_IMAGE, _CO_SPARTNER_OFFER_IMAGE_DSC);

        $this->initVar('date_sub', XOBJ_DTYPE_INT, 0, false, null, '', false, _CO_SPARTNER_OFFER_DATESUB, _CO_SPARTNER_OFFER_DATESUB_DSC, true);
        $this->initVar('date_pub', XOBJ_DTYPE_INT, time() - 1000, false, null, '', false, _CO_SPARTNER_OFFER_DATE_START, _CO_SPARTNER_OFFER_DATE_START_DSC, true);
        $this->initVar('date_end', XOBJ_DTYPE_INT, time() + 30 * 24 * 3600, false, null, '', false, _CO_SPARTNER_OFFER_DATE_END, _CO_SPARTNER_OFFER_DATE_END_DSC, true);

        $this->initVar('status', XOBJ_DTYPE_INT, _SPARTNER_STATUS_ONLINE, false, null, '', false, _CO_SPARTNER_OFFER_STATUS, _CO_SPARTNER_OFFER_STATUS_DSC, true);
        $this->initCommonVar('weight');
        $this->initCommonVar('dohtml', false);

        $this->setControl('image', ['name' => 'image']);

        $this->setControl('date_sub', ['name' => 'date_time']);
        $this->setControl('date_pub', ['name' => 'date_time']);
        $this->setControl('date_end', ['name' => 'date_time']);

        $this->setControl('status', [
            'name'        => false,
            'itemHandler' => 'offer',
            'method'      => 'getStatus',
            'module'      => 'smartpartner'
        ]);
        $this->setControl('partnerid', [
            'itemHandler' => 'partner',
            'method'      => 'getList',
            'module'      => 'smartpartner'
        ]);
    }

    /**
     * @param  string $key
     * @param  string $format
     * @return mixed
     */
    public function getVar($key, $format = 's')
    {
        if ('s' === $format && in_array($key, ['partnerid', 'status'])) {
            //            return call_user_func(array($this, $key));
            return $this->{$key}();
        }

        return parent::getVar($key, $format);
    }

    /**
     * @return mixed
     */
    public function partnerid()
    {
        global $partnerHandler;
        if (!$partnerHandler) {
            $partnerHandler = Smartpartner\Helper::getInstance()->getHandler('Partner');
        }
        $ret        = $this->getVar('partnerid', 'e');
        $partnerObj = $partnerHandler->get($ret);

        return $partnerObj->getVar('title');
    }

    /**
     * @return mixed
     */
    public function status()
    {
        global $statusArray;
        $ret = $this->getVar('status', 'e');

        return $statusArray [$ret];
    }

    /**
     * @param array $notifications
     */
    public function sendNotifications($notifications = [])
    {
        global $partnerHandler;
        $partnerObj  = $partnerHandler->get($this->getVar('partnerid', 'e'));
        $smartModule = Smartpartner\Utility::getModuleInfo();
        $module_id   = $smartModule->getVar('mid');

        $myts                = \MyTextSanitizer::getInstance();
        $notificationHandler = xoops_getHandler('notification');

        $tags                 = [];
        $tags['MODULE_NAME']  = $myts->displayTarea($smartModule->getVar('name'));
        $tags['PARTNER_NAME'] = $partnerObj->title(20);
        $tags['OFFER_NAME']   = $this->title(20);
        foreach ($notifications as $notification) {
            switch ($notification) {

                case _SPARTNER_NOT_OFFER_NEW:
                    $tags['OFFER_URL'] = XOOPS_URL . '/modules/' . $smartModule->getVar('dirname') . '/partner.php?id=' . $this->getVar('partnerid', 'e');
                    $notificationHandler->triggerEvent('global_partner', 0, 'new_offer', $tags);
                    break;
                case -1:
                default:
                    break;
            }
        }
    }

    /**
     * @param  string $format
     * @return array
     */
    public function toArray($format = 's')
    {
        global $myts;
        if (!$myts) {
            $myts = \MyTextSanitizer::getInstance();
        }
        $ret = parent::toArray();
        if ('e' === $format) {
            $ret['partnerid'] = $this->getVar('partnerid', 'e');
        }
        $ret['description'] = $myts->undoHtmlSpecialChars($ret['description']);

        return $ret;
    }
}
