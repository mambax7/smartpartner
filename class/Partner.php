<?php namespace XoopsModules\Smartpartner;

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartfaq;
use XoopsModules\Smartobject;
use XoopsModules\Smartpartner;


// defined('XOOPS_ROOT_PATH') || die('Restricted access');
//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobject.php';
//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjecthandler.php';


/**
 * Class Smartpartner\Partner
 */
class Partner extends Smartobject\BaseSmartObject
{
    public $_extendedInfo = null;

    /**
     * Smartpartner\Partner constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('categoryid', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('datesub', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('summary', XOBJ_DTYPE_TXTAREA, '', true);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('contact_name', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('contact_email', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('contact_phone', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('adress', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('url', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('image', XOBJ_DTYPE_TXTBOX, '', true);
        $this->initVar('image_url', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 0, false, 10);
        $this->initVar('hits', XOBJ_DTYPE_INT, 0, true, 10);
        $this->initVar('hits_page', XOBJ_DTYPE_INT, 0, true, 10);
        $this->initVar('status', XOBJ_DTYPE_INT, Constants::_SPARTNER_STATUS_NOTSET, false, 10);
        $this->initVar('last_update', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('email_priv', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('phone_priv', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('adress_priv', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('showsummary', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);

        if (isset($id)) {
            $smartPartnerPartnerHandler = new Smartpartner\PartnerHandler($this->db);
            $partner                    = $smartPartnerPartnerHandler->get($id);
            foreach ($partner->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->getVar('id');
    }

    /**
     * @return mixed
     */
    public function categoryid()
    {
        return $this->getVar('categoryid');
    }

    /**
     * @return mixed
     */
    public function weight()
    {
        return $this->getVar('weight');
    }

    /**
     * @return mixed
     */
    public function email_priv()
    {
        return $this->getVar('email_priv');
    }

    /**
     * @return mixed
     */
    public function phone_priv()
    {
        return $this->getVar('phone_priv');
    }

    /**
     * @return mixed
     */
    public function adress_priv()
    {
        return $this->getVar('adress_priv');
    }

    /**
     * @return mixed
     */
    public function hits()
    {
        return $this->getVar('hits');
    }

    /**
     * @return mixed
     */
    public function hits_page()
    {
        return $this->getVar('hits_page');
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function url($format = 'S')
    {
        return $this->getVar('url', $format);
    }

    /**
     * @param  string $format
     * @return mixed|string
     */
    public function image($format = 'S')
    {
        if ('' != $this->getVar('image')) {
            return $this->getVar('image', $format);
        } else {
            return 'blank.png';
        }
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function image_url($format = 'S')
    {
        return $this->getVar('image_url', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function title($format = 'S')
    {
        $ret = $this->getVar('title', $format);
        if (('s' === $format) || ('S' === $format) || ('show' === $format)) {
            $myts = \MyTextSanitizer::getInstance();
            $ret  = $myts->displayTarea($ret);
        }

        return $ret;
    }

    /**
     * @param  string $format
     * @return mixed|string
     */
    public function datesub($format = 'S')
    {
        $ret = $this->getVar('datesub', $format);
        if (('s' === $format) || ('S' === $format) || ('show' === $format)) {
            $ret = formatTimestamp($ret, 's');
        }

        return $ret;
    }

    /**
     * @param  int    $maxLength
     * @param  string $format
     * @return mixed|string
     */
    public function summary($maxLength = 0, $format = 'S')
    {
        $ret = $this->getVar('summary', $format);

        if (0 != $maxLength) {
            if (!XOOPS_USE_MULTIBYTES) {
                if (strlen($ret) >= $maxLength) {
                    $ret = xoops_substr(smartpartner_metagen_html2text($ret), 0, $maxLength);
                }
            }
        }

        return $ret;
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function description($format = 'S')
    {
        return $this->getVar('description', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function contact_name($format = 'S')
    {
        $ret = $this->getVar('contact_name', $format);
        if (('s' === $format) || ('S' === $format) || ('show' === $format)) {
            $myts = \MyTextSanitizer::getInstance();
            $ret  = $myts->displayTarea($ret);
        }

        return $ret;
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function contact_email($format = 'S')
    {
        $ret = $this->getVar('contact_email', $format);
        if (('s' === $format) || ('S' === $format) || ('show' === $format)) {
            $myts = \MyTextSanitizer::getInstance();
            $ret  = $myts->displayTarea($ret);
        }

        return $ret;
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function contact_phone($format = 'S')
    {
        $ret = $this->getVar('contact_phone', $format);
        if (('s' === $format) || ('S' === $format) || ('show' === $format)) {
            $myts = \MyTextSanitizer::getInstance();
            $ret  = $myts->displayTarea($ret);
        }

        return $ret;
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function adress($format = 'S')
    {
        $ret = $this->getVar('adress', $format);

        return $ret;
    }

    /**
     * @return mixed
     */
    public function status()
    {
        return $this->getVar('status');
    }

    /**
     * @param $forWhere
     * @return string
     */
    public function getUrlLink($forWhere)
    {
        if ('block' === $forWhere) {
            if ($this->extentedInfo()) {
                return '<a href="' . SMARTPARTNER_URL . 'partner.php?id=' . $this->id() . '">';
            } else {
                if ($this->url()) {
                    return '<a href="' . $this->url() . '" target="_blank">';
                } else {
                    return '';
                }
            }
        } elseif ('index' === $forWhere) {
            if ($this->extentedInfo()) {
                return '<a href="' . SMARTPARTNER_URL . 'partner.php?id=' . $this->id() . '">';
            } else {
                if ($this->url()) {
                    return '<a href="' . SMARTPARTNER_URL . 'vpartner.php?id=' . $this->id() . '">';
                } else {
                    return '';
                }
            }
        } elseif ('partner' === $forWhere) {
            if ($this->url()) {
                return '<a href="' . SMARTPARTNER_URL . 'vpartner.php?id=' . $this->id() . '">';
            } else {
                return '';
            }
        }
    }

    /**
     * @return mixed|string
     */
    public function getImageUrl()
    {
        if (('' !== $this->getVar('image')) && ('blank.png' !== $this->getVar('image'))
            && ('-1' !== $this->getVar('image'))) {
            return Smartpartner\Utility::getImageDir('', false) . $this->image();
        } elseif (!$this->getVar('image_url')) {
            return Smartpartner\Utility::getImageDir('', false) . 'blank.png';
        } else {
            return $this->getVar('image_url');
        }
    }

    /**
     * @return bool|string
     */
    public function getImagePath()
    {
        if (('' !== $this->getVar('image')) && ('blank.png' !== $this->getVar('image'))) {
            return Smartpartner\Utility::getImageDir() . $this->image();
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getImageLink()
    {
        $ret = "<a href='rrvpartner.php?id=" . $this->id() . "' target='_blank'>";
        if ('' != $this->getVar('image')) {
            $ret .= "<img src='" . $this->getImageUrl() . "' alt='" . $this->url() . "' border='0'></a>";
        } else {
            $ret .= "<img src='" . $this->image_url() . "' alt='" . $this->url() . "' border='0'></a>";
        }

        return $ret;
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        switch ($this->status()) {
            case Constants::_SPARTNER_STATUS_ACTIVE:
                return _CO_SPARTNER_ACTIVE;
                break;

            case Constants::_SPARTNER_STATUS_INACTIVE:
                return _CO_SPARTNER_INACTIVE;
                break;

            case Constants::_SPARTNER_STATUS_REJECTED:
                return _CO_SPARTNER_REJECTED;
                break;

            case Constants::_SPARTNER_STATUS_SUBMITTED:
                return _CO_SPARTNER_SUBMITTED;
                break;

            case Constants::_SPARTNER_STATUS_NOTSET:
            default:

                return _CO_SPARTNER_NOTSET;
                break;
        }
    }

    /**
     * @return bool
     */
    public function notLoaded()
    {
        return (0 == $this->getVar('id'));
    }

    /**
     * @return bool|null
     */
    public function extentedInfo()
    {
        if ($this->_extendedInfo) {
            return $this->_extendedInfo;
        }
        if (!$this->description() && !$this->contact_name() && !$this->contact_email() && !$this->contact_phone()
            && !$this->adress()) {
            $this->_extendedInfo = false;
        } else {
            $this->_extendedInfo = true;
        }

        return $this->_extendedInfo;
    }

    /**
     * @param  bool $force
     * @return bool
     */
    public function store($force = true)
    {
        $smartPartnerPartnerHandler = new Smartpartner\PartnerHandler($this->db);

        return $smartPartnerPartnerHandler->insert($this, $force);
    }

    /**
     * @return bool
     */
    public function updateHits()
    {
        $sql = 'UPDATE ' . $this->db->prefix('smartpartner_partner') . ' SET hits=hits+1 WHERE id = ' . $this->id();
        if ($this->db->queryF($sql)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function updateHits_page()
    {
        $sql = 'UPDATE ' . $this->db->prefix('smartpartner_partner') . ' SET hits_page=hits_page+1 WHERE id = ' . $this->id();
        if ($this->db->queryF($sql)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $notifications
     */
    public function sendNotifications($notifications = [])
    {
        $smartModule = Smartpartner\Utility::getModuleInfo();
        $module_id   = $smartModule->getVar('mid');

        $myts                = \MyTextSanitizer::getInstance();
        $notificationHandler = xoops_getHandler('notification');

        $tags                 = [];
        $tags['MODULE_NAME']  = $myts->displayTarea($smartModule->getVar('name'));
        $tags['PARTNER_NAME'] = $this->title(20);
        foreach ($notifications as $notification) {
            switch ($notification) {

                case Constants::_SPARTNER_NOT_PARTNER_SUBMITTED:
                    $tags['WAITINGFILES_URL'] = XOOPS_URL . '/modules/' . $smartModule->getVar('dirname') . '/admin/partner.php?op=mod&id=' . $this->id();
                    $notificationHandler->triggerEvent('global_partner', 0, 'submitted', $tags);
                    break;

                case Constants::_SPARTNER_NOT_PARTNER_APPROVED:
                    $tags['PARTNER_URL'] = XOOPS_URL . '/modules/' . $smartModule->getVar('dirname') . '/partner.php?id=' . $this->id();
                    $notificationHandler->triggerEvent('partner', $this->id(), 'approved', $tags);
                    break;

                case Constants::_SPARTNER_NOT_PARTNER_NEW:
                    $tags['PARTNER_URL'] = XOOPS_URL . '/modules/' . $smartModule->getVar('dirname') . '/partner.php?id=' . $this->id();
                    $notificationHandler->triggerEvent('global_partner', 0, 'new_partner', $tags);
                    break;

                case -1:
                default:
                    break;
            }
        }
    }

    /**
     * @param $original_status
     * @param $new_status
     * @return array
     */
    public function getRedirectMsg($original_status, $new_status)
    {
        $redirect_msgs = [];

        switch ($original_status) {

            case Constants::_SPARTNER_STATUS_NOTSET:
                switch ($new_status) {
                    case Constants::_SPARTNER_STATUS_ACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_NOTSET_ACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case Constants::_SPARTNER_STATUS_INACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_NOTSET_INACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;
                }
                break;

            case Constants::_SPARTNER_STATUS_SUBMITTED:
                switch ($new_status) {
                    case Constants::_SPARTNER_STATUS_ACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_SUBMITTED_ACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case Constants::_SPARTNER_STATUS_INACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_SUBMITTED_INACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case Constants::_SPARTNER_STATUS_REJECTED:
                        $redirect_msgs['success'] = _AM_SPARTNER_SUBMITTED_REJECTED_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;
                }
                break;

            case Constants::_SPARTNER_STATUS_ACTIVE:
                switch ($new_status) {
                    case Constants::_SPARTNER_STATUS_ACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_ACTIVE_ACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case Constants::_SPARTNER_STATUS_INACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_ACTIVE_INACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                }
                break;

            case Constants::_SPARTNER_STATUS_INACTIVE:
                switch ($new_status) {
                    case Constants::_SPARTNER_STATUS_ACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_INACTIVE_ACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case Constants::_SPARTNER_STATUS_INACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_INACTIVE_INACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                }
                break;

            case Constants::_SPARTNER_STATUS_REJECTED:
                switch ($new_status) {
                    case Constants::_SPARTNER_STATUS_ACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_REJECTED_ACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case Constants::_SPARTNER_STATUS_INACTIVE:
                        $redirect_msgs['success'] = _AM_SPARTNER_REJECTED_INACTIVE_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;

                    case Constants::_SPARTNER_STATUS_REJECTED:
                        $redirect_msgs['success'] = _AM_SPARTNER_REJECTED_REJECTED_SUCCESS;
                        $redirect_msgs['error']   = _AM_SPARTNER_PARTNER_NOT_UPDATED;
                        break;
                }
                break;
        }

        return $redirect_msgs;
    }

    /**
     * @return array
     */
    public function getAvailableStatus()
    {
        switch ($this->status()) {
            case Constants::_SPARTNER_STATUS_NOTSET:
                $ret = [
                    Constants::_SPARTNER_STATUS_ACTIVE   => _AM_SPARTNER_ACTIVE,
                    Constants::_SPARTNER_STATUS_INACTIVE => _AM_SPARTNER_INACTIVE
                ];
                break;
            case Constants::_SPARTNER_STATUS_SUBMITTED:
                $ret = [
                    Constants::_SPARTNER_STATUS_ACTIVE   => _AM_SPARTNER_ACTIVE,
                    Constants::_SPARTNER_STATUS_REJECTED => _AM_SPARTNER_REJECTED,
                    Constants::_SPARTNER_STATUS_INACTIVE => _AM_SPARTNER_INACTIVE
                ];
                break;

            case Constants::_SPARTNER_STATUS_ACTIVE:
                $ret = [
                    Constants::_SPARTNER_STATUS_ACTIVE   => _AM_SPARTNER_ACTIVE,
                    Constants::_SPARTNER_STATUS_INACTIVE => _AM_SPARTNER_INACTIVE
                ];
                break;

            case Constants::_SPARTNER_STATUS_INACTIVE:
                $ret = [
                    Constants::_SPARTNER_STATUS_ACTIVE   => _AM_SPARTNER_ACTIVE,
                    Constants::_SPARTNER_STATUS_INACTIVE => _AM_SPARTNER_INACTIVE
                ];
                break;

            case Constants::_SPARTNER_STATUS_REJECTED:
                $ret = [
                    Constants::_SPARTNER_STATUS_ACTIVE   => _AM_SPARTNER_ACTIVE,
                    Constants::_SPARTNER_STATUS_REJECTED => _AM_SPARTNER_REJECTED,
                    Constants::_SPARTNER_STATUS_INACTIVE => _AM_SPARTNER_INACTIVE
                ];
                break;
        }

        return $ret;
    }

    public function setUpdated()
    {
        $this->setVar('last_update', time());
        $this->store();
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        global $smartPartnerFileHandler;

        return $smartPartnerFileHandler->getAllFiles($this->id(), Constants::_SPARTNER_STATUS_FILE_ACTIVE);
    }

    /**
     * @param  string $url_link_type
     * @return mixed
     */
    public function toArray($url_link_type = 'partner')
    {
        $smartConfig = Smartpartner\Utility::getModuleConfig();

        $partner['id']         = $this->id();
        $partner['categoryid'] = $this->categoryid();
        $partner['hits']       = $this->hits();
        $partner['hits_page']  = $this->hits_page();
        $partner['url']        = $this->url();
        $partner['urllink']    = $this->getUrlLink($url_link_type);
        $partner['image']      = $this->getImageUrl();

        $partner['title']       = $this->title();
        $partner['datesub']     = $this->datesub();
        $partner['clean_title'] = $partner['title'];
        $partner['summary']     = $this->summary();

        $partner['contact_name']  = $this->contact_name();
        $partner['contact_email'] = $this->contact_email();
        $partner['contact_phone'] = $this->contact_phone();
        $partner['adress']        = $this->adress();
        $partner['email_priv']    = $this->email_priv();
        $partner['phone_priv']    = $this->phone_priv();
        $partner['adress_priv']   = $this->adress_priv();

        $image_info          = Smartpartner\Utility::imageResize($this->getImagePath(), $smartConfig['img_max_width'], $smartConfig['img_max_height']);
        $partner['img_attr'] = $image_info[3];

        $partner['readmore'] = $this->extentedInfo();
        if ((time() - $this->datesub('e')) < ($smartConfig['updated_period'] * 24 * 3600)) {
            $partner['update_status'] = 'new';
        } elseif ((time() - $this->getVar('last_update')) < ($smartConfig['updated_period'] * 24 * 3600)) {
            $partner['update_status'] = 'updated';
        } else {
            $partner['update_status'] = 'none';
        }
        //--------------
        global $smartPermissionsHandler, $smartPartnerPartnerHandler, $xoopsUser;
//        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectpermission.php';
        if (!$smartPartnerPartnerHandler) {
            $smartPartnerPartnerHandler = Smartpartner\Helper::getInstance()->getHandler('Partner');
        }
        $smartPermissionsHandler = new Smartobject\PermissionHandler($smartPartnerPartnerHandler);
        $grantedGroups           = $smartPermissionsHandler->getGrantedGroups('full_view', $this->id());
        $partGrantedGroups       = $smartPermissionsHandler->getGrantedGroups('partial_view', $this->id());

        $userGroups = is_object($xoopsUser) ? $xoopsUser->getGroups() : [XOOPS_GROUP_ANONYMOUS];

        if (array_intersect($userGroups, $grantedGroups)) {
            $partner['display_type'] = 'full';
        } elseif (array_intersect($userGroups, $partGrantedGroups)) {
            $partner['display_type'] = 'part';
        } else {
            $partner['display_type'] = 'none';
        }
        if ('' != $this->description() && 'full' === $partner['display_type']) {
            $partner['description'] = $this->description();
        } else {
            //$partner['description'] = $this->summary();
        }
        $partner['showsummary'] = $this->getVar('showsummary');

        //--------------

        // Hightlighting searched words
        $highlight = true;
        if ($highlight && isset($_GET['keywords'])) {
            $myts                     = \MyTextSanitizer::getInstance();
            $keywords                 = $myts->htmlSpecialChars(trim(urldecode($_GET['keywords'])));
            $h                        = new Smartpartner\Keyhighlighter($keywords, true, 'Smartpartner\Utility::getHighlighter');
            $partner['title']         = $h->highlight($partner['title']);
            $partner['summary']       = $h->highlight($partner['summary']);
            $partner['description']   = $h->highlight($partner['description']);
            $partner['contact_name']  = $h->highlight($partner['contact_name']);
            $partner['contact_email'] = $h->highlight($partner['contact_email']);
            $partner['contact_phone'] = $h->highlight($partner['contact_phone']);
            $partner['adress']        = $h->highlight($partner['adress']);
        }

        return $partner;
    }
}
