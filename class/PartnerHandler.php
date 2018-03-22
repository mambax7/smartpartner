<?php namespace XoopsModules\Smartpartner;

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartobject;
use XoopsModules\Smartpartner;
use XoopsModules\Smartpartner\Constants;


// defined('XOOPS_ROOT_PATH') || die('Restricted access');
//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobject.php';
//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjecthandler.php';


/**
 * Partner handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of Partner class objects.
 *
 * @author  marcan <marcan@notrevie.ca>
 * @package SmartPartner
 */
class PartnerHandler extends Smartpartner\PersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase $db reference to a xoops_db object
     */

    public function __construct(\XoopsDatabase $db)
    {
        xoops_loadLanguage('common', 'smartpartner');
        parent::__construct($db, 'partner', Partner::class, 'title', false, 'smartpartner');
//        $this->addPermission('full_view', _CO_SPARTNER_FULL_PERM_READ, _CO_SPARTNER_FULL_PERM_READ_DSC);
//        $this->addPermission('partial_view', _CO_SPARTNER_PART_PERM_READ, _CO_SPARTNER_PART_PERM_READ_DSC);
    }

    /**
     * Singleton - prevent multiple instances of this class
     *
     * @param  null|\XoopsDatabase $db
     * @return object               <a href='psi_element://CategoryHandler'>CategoryHandler</a>
     * @access public
     */
    public function getInstance(\XoopsDatabase $db)
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($db);
        }

        return $instance;
    }

    /**
     * @param  bool $isNew
     * @return Smartpartner\Partner
     */
    public function create($isNew = true)
    {
        $partner = new Smartpartner\Partner();
        if ($isNew) {
            $partner->setNew();
        }

        return $partner;
    }

    /**
     * retrieve a Partner
     *
     * @param  int  $id        partnerid of the user
     * @param  bool $as_object
     * @param  bool $debug
     * @param  bool $criteria
     * @return mixed reference to the <a href='psi_element://Smartpartner\Partner'>Smartpartner\Partner</a> object, FALSE if failed
     *                         object, FALSE if failed
     */
    public function get($id, $as_object = true, $debug = false, $criteria = false)
    {
        if ((int)$id > 0) {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE id=' . $id;
            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $numrows = $this->db->getRowsNum($result);
            if (1 == $numrows) {
                $partner = new Smartpartner\Partner();
                $partner->assignVars($this->db->fetchArray($result));
                global $smartpartnerPartnerCatLinkHandler;
                if (!$smartpartnerPartnerCatLinkHandler) {
                    $smartpartnerPartnerCatLinkHandler = Smartpartner\Helper::getInstance()->getHandler('PartnerCatLink');
                }
                $partner->setVar('categoryid', $smartpartnerPartnerCatLinkHandler->getParentIds($partner->getVar('id')));

                return $partner;
            }
        }
        $ret = false;

        return $ret;
    }

    /**
     * insert a new Partner in the database
     *
     * @param \XoopsObject $partner
     * @param  bool        $force
     * @param  bool        $checkObject
     * @param  bool        $debug
     * @return bool        FALSE if failed, TRUE if already present and unchanged or successful
     * @internal param XoopsObject $partner reference to the <a href='psi_element://Smartpartner\Partner'>Smartpartner\Partner</a> object object
     */
    public function insert(\XoopsObject $partner, $force = false, $checkObject = true, $debug = false)
    {
        if (strtolower(get_class($partner)) != strtolower($this->className)) {
            return false;
        }

        if (!$partner->isDirty()) {
            return true;
        }

        if (!$partner->cleanVars()) {
            return false;
        }

        foreach ($partner->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        if ($partner->isNew()) {
            $sql = sprintf(
                'INSERT INTO %s (id,  weight, hits, hits_page, url, image, image_url, title, datesub, summary, description, contact_name, contact_email, contact_phone, adress, `status`, `last_update`, `email_priv`, `phone_priv`, `adress_priv`, `showsummary`) VALUES (NULL, %u, %u, %u, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %u, %u, %u, %u, %u, %u)',
                           $this->table,
                $weight,
                $hits,
                $hits_page,
                $this->db->quoteString($url),
                $this->db->quoteString($image),
                $this->db->quoteString($image_url),
                $this->db->quoteString($title),
                time(),
                $this->db->quoteString($summary),
                $this->db->quoteString($description),
                           $this->db->quoteString($contact_name),
                $this->db->quoteString($contact_email),
                $this->db->quoteString($contact_phone),
                $this->db->quoteString($adress),
                $status,
                time(),
                $email_priv,
                $phone_priv,
                $adress_priv,
                $showsummary
            );
        } else {
            $sql = sprintf(
                'UPDATE %s SET  weight = %u, hits = %u, hits_page = %u, url = %s, image = %s, image_url = %s, title = %s, datesub = %s, summary = %s, description = %s, contact_name = %s, contact_email = %s, contact_phone = %s, adress = %s, `status` = %u, `last_update` = %u, `email_priv` = %u, `phone_priv` = %u, `adress_priv` = %u, `showsummary` = %u WHERE id = %u',
                           $this->table,
                $weight,
                $hits,
                $hits_page,
                $this->db->quoteString($url),
                $this->db->quoteString($image),
                $this->db->quoteString($image_url),
                $this->db->quoteString($title),
                $this->db->quoteString($datesub),
                $this->db->quoteString($summary),
                           $this->db->quoteString($description),
                $this->db->quoteString($contact_name),
                $this->db->quoteString($contact_email),
                $this->db->quoteString($contact_phone),
                $this->db->quoteString($adress),
                $status,
                time(),
                $email_priv,
                $phone_priv,
                $adress_priv,
                $showsummary,
                $id
            );
        }

        //echo "<br>" . $sql . "<br>";exit;

        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        if (!$result) {
            return false;
        }
        if ($partner->isNew()) {
            $partner->assignVar('id', $this->db->getInsertId());
        }
        global $smartpartnerPartnerCatLinkHandler;
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('partnerid', $partner->getVar('id')));
        $links        = $smartpartnerPartnerCatLinkHandler->getObjects($criteria);
        $categoryid   = explode('|', $partner->getVar('categoryid'));
        $parent_array = [];
        foreach ($links as $link) {
            if (!in_array($link->getVar('categoryid'), $categoryid)) {
                $smartpartnerPartnerCatLinkHandler->delete($link);
            } else {
                $parent_array[] = $link->getVar('categoryid');
            }
        }
        foreach ($categoryid as $cat) {
            if (!in_array($cat, $parent_array)) {
                $linkObj = $smartpartnerPartnerCatLinkHandler->create();
                $linkObj->setVar('partnerid', $partner->getVar('id'));
                $linkObj->setVar('categoryid', $cat);
                $smartpartnerPartnerCatLinkHandler->insert($linkObj);
            }
        }
        if (isset($_POST['partial_view']) || isset($_POST['full_view'])) {
            $smartPermissionsHandler = new Smartobject\PermissionHandler($this);
            $smartPermissionsHandler->storeAllPermissionsForId($partner->id());
        }

        return true;
    }

    /**
     * delete a Partner from the database
     *
     * @param \XoopsObject $partner reference to the Partner to delete
     * @param  bool        $force
     * @return bool        FALSE if failed.
     */
    public function delete(\XoopsObject $partner, $force = false)
    {
        global $smartPartnerOfferHandler, $smartpartnerPartnerCatLinkHandler;
        $partnerModule = Smartpartner\Utility::getModuleInfo();
        $module_id     = $partnerModule->getVar('mid');

        if (strtolower(get_class($partner)) != strtolower($this->className)) {
            return false;
        }

        $sql = sprintf('DELETE FROM %s WHERE id = %u', $this->table, $partner->getVar('id'));

        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('partnerid', $partner->getVar('id')));
        $offersObj = $smartPartnerOfferHandler->getObjects($criteria);

        foreach ($offersObj as $offerObj) {
            $smartPartnerOfferHandler->delete($offerObj, 1);
        }
        $linksObj = $smartpartnerPartnerCatLinkHandler->getObjects($criteria);
        foreach ($linksObj as $linkObj) {
            $smartpartnerPartnerCatLinkHandler->delete($linkObj, 1);
        }

        return true;
    }

    /**
     * retrieve Partners from the database
     *
     * @param  \CriteriaElement $criteria  {@link CriteriaElement} conditions to be met
     * @param  bool            $id_as_key use the partnerid as key for the array?
     * @param  bool            $as_object
     * @param  bool            $sql
     * @param  bool            $debug
     * @return array  array of <a href='psi_element://Smartpartner\Partner'>Smartpartner\Partner</a> objects
     *                                    objects
     */
    public function getObjects(
        \CriteriaElement $criteria = null,
        $id_as_key = false,
        $as_object = true,
        $sql = false,
        $debug = false
    )//&getObjects($criteria = null, $id_as_key = false)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->table;

        if (null !== $criteria && is_subclass_of($criteria, 'CriteriaElement')) {
            $whereClause = $criteria->renderWhere();

            if ('WHERE ()' !== $whereClause) {
                $sql .= ' ' . $criteria->renderWhere();
                if ('' != $criteria->getSort()) {
                    $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
                }
                $limit = $criteria->getLimit();
                $start = $criteria->getStart();
            }
        }

        //echo "<br>" . $sql . "<br>";exit;
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }

        if (0 == $GLOBALS['xoopsDB']->getRowsNum($result)) {
            return $ret;
        }
        global $smartpartnerPartnerCatLinkHandler;
        if (!isset($smartpartnerPartnerCatLinkHandler)) {
            $smartpartnerPartnerCatLinkHandler = Smartpartner\Helper::getInstance()->getHandler('partner_cat_link');
        }
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $partner = new Smartpartner\Partner();
            $partner->assignVars($myrow);

            if (!$id_as_key) {
                $ret[] =& $partner;
            } else {
                $ret[$myrow['id']] =& $partner;
            }
            $partner->setVar('categoryid', $smartpartnerPartnerCatLinkHandler->getParentIds($partner->getVar('id')));
            unset($partner);
        }

        return $ret;
    }

    /**
     * count Partners matching a condition
     *
     * @param  \CriteriaElement $criteria {@link CriteriaElement} to match
     * @return int    count of partners
     */
    public function getCount(\CriteriaElement $criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->table;
        if (null !== $criteria && is_subclass_of($criteria, 'CriteriaElement')) {
            $whereClause = $criteria->renderWhere();
            if ('WHERE ()' !== $whereClause) {
                $sql .= ' ' . $criteria->renderWhere();
            }
        }

        //echo "<br>" . $sql . "<br>";
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);

        return $count;
    }

    /**
     * @param  int $status
     * @return int
     */
    public function getPartnerCount($status = Constants::_SPARTNER_STATUS_ACTIVE)
    {
        if (Constants::_SPARTNER_STATUS_ALL != $status) {
            $criteriaStatus = new \CriteriaCompo();
            $criteriaStatus->add(new \Criteria('status', $status));
        }

        $criteria = new \CriteriaCompo();
        if (isset($criteriaStatus)) {
            $criteria->add($criteriaStatus);
        }

        return $this->getCount($criteria);
    }

    /**
     * @param  array  $queryarray
     * @param  string $andor
     * @param  int    $limit
     * @param  int    $offset
     * @param  int    $userid
     * @return array
     */
    public function &getObjectsForSearch($queryarray = [], $andor = 'AND', $limit = 0, $offset = 0, $userid = 0)
    {
        global $xoopsConfig;

        $ret = [];
        $sql = 'SELECT title, id
                   FROM ' . $this->table . '
                   ';
        if (!empty($queryarray)) {
            $criteriaKeywords = new \CriteriaCompo();
            foreach ($queryarray as $iValue) {
                $criteriaKeyword = new \CriteriaCompo();
                $criteriaKeyword->add(new \Criteria('title', '%' . $iValue . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new \Criteria('summary', '%' . $iValue . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new \Criteria('description', '%' . $iValue . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new \Criteria('contact_name', '%' . $iValue . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new \Criteria('contact_email', '%' . $iValue . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new \Criteria('contact_phone', '%' . $iValue . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new \Criteria('adress', '%' . $iValue . '%', 'LIKE'), 'OR');
                $criteriaKeywords->add($criteriaKeyword, $andor);
                unset($criteriaKeyword);
            }
        }

        $criteria = new \CriteriaCompo();

        if (!empty($criteriaKeywords)) {
            $criteria->add($criteriaKeywords, 'AND');
        }

        $criteria->add(new \Criteria('status', Constants::_SPARTNER_STATUS_ACTIVE, '='), 'AND');

        if (0 != $userid) {
            $criteria->add(new \Criteria('id', $userid), 'AND');
        }

        $criteria->setSort('datesub');
        $criteria->setOrder('DESC');

        if (null !== $criteria && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . '
                    ' . $criteria->getOrder();
            }
        }

        //echo "<br>$sql<br>";

        $result = $this->db->query($sql, $limit, $offset);
        // If no records from db, return empty array
        if (!$result) {
            return $ret;
        }

        // Add each returned record to the result array
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $item['id']    = $myrow['id'];
            $item['title'] = $myrow['title'];
            $ret[]         = $item;
            unset($item);
        }

        return $ret;
    }

    /**
     * @param  int    $limit
     * @param  int    $start
     * @param  int    $status
     * @param  string $sort
     * @param  string $order
     * @param  bool   $asobject
     * @return array
     */
    public function getPartners(
        $limit = 0,
        $start = 0,
        $status = Constants::_SPARTNER_STATUS_ACTIVE,
        $sort = 'title',
        $order = 'ASC',
        $asobject = true
    ) {
        global $xoopsUser;
        if (Constants::_SPARTNER_STATUS_ALL != $status) {
            $criteriaStatus = new \CriteriaCompo();
            $criteriaStatus->add(new \Criteria('status', $status));
        }

        $criteria = new \CriteriaCompo();
        if (isset($criteriaStatus)) {
            $criteria->add($criteriaStatus);
        }
        $criteria->setLimit($limit);
        $criteria->setStart($start);
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $ret = $this->getObjects($criteria);

        return $ret;
    }

    /**
     * @param  int    $categoryid
     * @param  int    $status
     * @param  string $sort
     * @param  string $order
     * @param  bool   $asobject
     * @return array
     */
    public function getPartnersForIndex(
        $categoryid = 0,
        $status = Constants::_SPARTNER_STATUS_ACTIVE,
        $sort = 'title',
        $order = 'ASC',
        $asobject = true
    ) {
        global $xoopsUser;
        if (Constants::_SPARTNER_STATUS_ALL != $status) {
            $criteriaStatus = new \CriteriaCompo();
            $criteriaStatus->add(new \Criteria('status', $status));
        }

        $criteria = new \CriteriaCompo();
        if (isset($criteriaStatus)) {
            $criteria->add($criteriaStatus);
        }
        if (-1 != $categoryid) {
            $criteria->add(new \Criteria('categoryid', $categoryid));
        }
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $ret =& $this->getObjects($criteria);

        return $ret;
    }

    /**
     * @param  null $status
     * @return bool|mixed
     */
    public function getRandomPartner($status = null)
    {
        $ret = false;

        // Getting the number of partners
        $totalPartners = $this->getPartnerCount($status);

        if ($totalPartners > 0) {
            --$totalPartners;
            mt_srand((double)microtime() * 1000000);
            $entrynumber = mt_rand(0, $totalPartners);
            $partner     = $this->getPartners(1, $entrynumber, $status);
            if ($partner) {
                $ret =& $partner[0];
            }
        }

        return $ret;
    }

    /**
     * delete Partners matching a set of conditions
     *
     * @param  \CriteriaElement $criteria {@link CriteriaElement}
     * @return bool   FALSE if deletion failed
     */
    public function deleteAll(\CriteriaElement $criteria = null)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('smartpartner_partner');
        if (null !== $criteria && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * Change a value for a Partner with a certain criteria
     *
     * @param string          $fieldname  Name of the field
     * @param string          $fieldvalue Value to write
     * @param \CriteriaElement $criteria   {@link CriteriaElement}
     *
     * @param  bool           $force
     * @return bool
     */
    public function updateAll($fieldname, $fieldvalue, \CriteriaElement $criteria = null, $force = false)
    {
        $set_clause = is_numeric($fieldvalue) ? $fieldname . ' = ' . $fieldvalue : $fieldname . ' = ' . $this->db->quoteString($fieldvalue);
        $sql        = 'UPDATE ' . $this->db->prefix('smartpartner_partner') . ' SET ' . $set_clause;
        if (null !== $criteria && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param  int $limit
     * @param  int $status
     * @return bool
     */
    public function getRandomPartners($limit = 0, $status = Constants::_SPARTNER_STATUS_ACTIVE)
    {
        $ret = false;
        $sql = 'SELECT id FROM ' . $this->db->prefix('smartpartner_partner') . ' ';
        $sql .= 'WHERE status=' . $status;

        //echo "<br>" . $sql . "<br>";

        $result = $this->db->query($sql);

        if (!$result) {
            return $ret;
        }

        if (0 == $GLOBALS['xoopsDB']->getRowsNum($result)) {
            return $ret;
        }

        $partners_ids = [];
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $partners_ids[] = $myrow['id'];
        }

        if (count($partners_ids) > 1) {
            $key_arr  = array_values($partners_ids);
            $key_rand = array_rand($key_arr, count($key_arr));
            $ids      = implode(', ', $key_rand);
            echo $ids;

            return $ret;
        } else {
            return $ret;
        }
    }

    /*  function getFaqsFromSearch($queryarray = array(), $andor = 'AND', $limit = 0, $offset = 0, $userid = 0)
        {

        Global $xoopsUser;

        $ret = array();

        $hModule = xoops_getHandler('module');
        $hModConfig = xoops_getHandler('config');
        $smartModule =& $hModule->getByDirname('smartfaq');
        $module_id = $smartModule->getVar('mid');

        $gpermHandler = xoops_getHandler('groupperm');
        $groups = ($xoopsUser) ? ($xoopsUser->getGroups()): XOOPS_GROUP_ANONYMOUS;
        $userIsAdmin = sf_userIsAdmin();

        if ($userid != 0) {
            $criteriaUser = new \CriteriaCompo();
            $criteriaUser->add(new \Criteria('faq.uid', $userid), 'OR');
            $criteriaUser->add(new \Criteria('answer.uid', $userid), 'OR');
        }

        if (!empty($queryarray)) {
            $criteriaKeywords = new \CriteriaCompo();
            for ($i = 0, $iMax = count($queryarray); $i < $iMax; ++$i) {
                $criteriaKeyword = new \CriteriaCompo();
                $criteriaKeyword->add(new \Criteria('faq.question', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
                $criteriaKeyword->add(new \Criteria('answer.answer', '%' . $queryarray[$i] . '%', 'LIKE'), 'OR');
                $criteriaKeywords->add($criteriaKeyword, $andor);
            }
        }

        // Categories for which user has access
        if (!$userIsAdmin) {
            $categoriesGranted = $gpermHandler->getItemIds('category_read', $groups, $module_id);
            $grantedCategories = new \Criteria('faq.categoryid', "(".implode(',', $categoriesGranted).")", 'IN');
        }
        // FAQs for which user has access
        if (!$userIsAdmin) {
            $faqsGranted = $gpermHandler->getItemIds('item_read', $groups, $module_id);
            $grantedFaq = new \Criteria('faq.faqid', "(".implode(',', $faqsGranted).")", 'IN');
        }

        $criteriaPermissions = new \CriteriaCompo();
        if (!$userIsAdmin) {
            $criteriaPermissions->add($grantedCategories, 'AND');
            $criteriaPermissions->add($grantedFaq, 'AND');
        }

        $criteriaAnswersStatus = new \CriteriaCompo();
        $criteriaAnswersStatus->add(new \Criteria('answer.status', _SF_AN_STATUS_APPROVED));

        $criteriaFasStatus = new \CriteriaCompo();
        $criteriaFasStatus->add(new \Criteria('faq.status', _SF_STATUS_OPENED), 'OR');
        $criteriaFasStatus->add(new \Criteria('faq.status', _SF_STATUS_PUBLISHED), 'OR');

        $criteria = new \CriteriaCompo();
        If (!empty($criteriaUser)) {
            $criteria->add($criteriaUser, 'AND');
        }

        If (!empty($criteriaKeywords)) {
            $criteria->add($criteriaKeywords, 'AND');
        }

        If (!empty($criteriaPermissions) && (!$userIsAdmin)) {
            $criteria->add($criteriaPermissions);
        }

        If (!empty($criteriaAnswersStatus)) {
            $criteria->add($criteriaAnswersStatus, 'AND');
        }

        If (!empty($criteriaFasStatus)) {
            $criteria->add($criteriaFasStatus, 'AND');
        }

        $criteria->setLimit($limit);
        $criteria->setStart($offset);
        $criteria->setSort('faq.datesub');
        $criteria->setOrder('DESC');

        $sql = 'SELECT faq.faqid FROM '.$this->db->prefix('smartfaq_faq') . ' as faq INNER JOIN '.$this->db->prefix('smartfaq_answers') . ' as answer ON faq.faqid = answer.faqid';

        if (null !== $criteria && is_subclass_of($criteria, 'CriteriaElement')) {
            $whereClause = $criteria->renderWhere();

            If ($whereClause != 'WHERE ()') {
                $sql .= ' '.$criteria->renderWhere();
                if ($criteria->getSort() != '') {
                    $sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
                }
                $limit = $criteria->getLimit();
                $start = $criteria->getStart();
            }
        }

        //echo "<br>" . $sql . "<br>";

        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            echo "- query did not work -";

            return $ret;
        }

        If ($GLOBALS['xoopsDB']->getRowsNum($result) == 0) {
            return $ret;
        }

       while (false !== ($myrow = $this->db->fetchArray($result))) {
            $faq = new Smartfaq\Faq($myrow['faqid']);
            $ret[] =& $faq;
            unset($faq);
        }

        return $ret;
        }*/
}
