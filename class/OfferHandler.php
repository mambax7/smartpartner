<?php namespace XoopsModules\Smartpartner;

//
// ------------------------------------------------------------------------ //
//               XOOPS - PHP Content Management System                      //
//                   Copyright (c) 2000-2016 XOOPS.org                           //
//                      <https://xoops.org>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //

// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //

// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// URL: https://xoops.org/                                               //
// Project: XOOPS Project                                               //
// -------------------------------------------------------------------------//

use XoopsModules\Smartpartner;
/** @var Smartpartner\Helper $helper */
$helper = Smartpartner\Helper::getInstance();

// defined('XOOPS_ROOT_PATH') || die('Restricted access');
//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobject.php';
//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjecthandler.php';


/**
 * Class OfferHandler
 */
class OfferHandler extends Smartpartner\PersistableObjectHandler
{
    /**
     * OfferHandler constructor.
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'smartpartner_offer', Offer::class, 'title', false, 'smartpartner');
    }

    /**
     * @return array
     */
    public function getStatus()
    {
        global $statusArray;

        return $statusArray;
    }

    /**
     * @return array
     */
    public function getObjectsForUserSide()
    {
        global  $categoryHandler, $partnerHandler, $xoopsUser;
        /** @var Smartpartner\Helper $helper */
        $helper = Smartpartner\Helper::getInstance();

        $criteria = new \CriteriaCompo();
        $criteria->setSort($helper->getConfig('offer_sort'));
        $criteria->setOrder($helper->getConfig('offer_order'));
        $criteria->add(new \Criteria('date_pub', time(), '<'));
        $criteria->add(new \Criteria('date_end', time(), '>'));
        $criteria->add(new \Criteria('status', _SPARTNER_STATUS_ONLINE));

        $offersObj =& $this->getObjects($criteria);
        foreach ($offersObj as $offerObj) {
        }
        $catsObj     = $categoryHandler->getObjects(null, true);
        $partnersObj = $partnerHandler->getObjects(null, true);

//        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectpermission.php';
        $smartPermissionsHandler = new Smartobject\PermissionHandler($partnerHandler);
        $userGroups              = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $grantedItems            = $smartPermissionsHandler->getGrantedItems('full_view');
        $relevantCat             = [];

        foreach ($offersObj as $offerObj) {
            if (in_array($offerObj->getVar('partnerid', 'e'), $grantedItems)) {
                $categId        = $partnersObj[$offerObj->getVar('partnerid', 'e')]->categoryid();
                $parentCatArray = explode('|', $categId);
                $relevantCat    = array_merge($relevantCat, $parentCatArray);
                foreach ($parentCatArray as $p_cat) {
                    $parentid = $p_cat;
                    while (0 != $catsObj[$parentid]->parentid()) {
                        $parentid      = $catsObj[$parentid]->parentid();
                        $relevantCat[] = $parentid;
                    }
                }
            }
        }
        $relevantCat = array_unique($relevantCat);

        $partnersArray = [];
        foreach ($partnersObj as $partnerObj) {
            $grantedGroups = $smartPermissionsHandler->getGrantedGroups('full_view', $partnerObj->id());
            if (array_intersect($userGroups, $grantedGroups)) {
                $partnerArray           = [];
                $partnerArray['name']   = $partnerObj->title();
                $partnerArray['offers'] = [];
                foreach ($offersObj as $offerObj) {
                    if ($offerObj->getVar('partnerid', 'e') == $partnerObj->id()) {
                        $partnerArray['offers'][] = $offerObj->toArray();
                    }
                }
                $partnersArray[$partnerObj->id()] = $partnerArray;
                unset($partnerArray);
            }
        }

        $categoriesArray = [];
        foreach ($catsObj as $catObj) {
            if (in_array($catObj->categoryid(), $relevantCat)) {
                $categoryArray               = [];
                $categoryArray['parentid']   = $catObj->parentid();
                $categoryArray['categoryid'] = $catObj->categoryid();
                $categoryArray['name']       = $catObj->name();
                $categoryArray['partners']   = [];
                foreach ($partnersObj as $partnerObj) {
                    $catArray = explode('|', $partnerObj->categoryid());
                    if (in_array($catObj->categoryid(), $catArray)) {
                        $categoryArray['partners'][$partnerObj->id()] = $partnersArray[$partnerObj->id()];
                    }
                }
                $categoriesArray[] = $categoryArray;
                unset($categoryArray);
            }
        }

        return $this->hierarchize($categoriesArray);
    }

    /**
     * @param        $categoriesArray
     * @param  int   $parentid
     * @return array
     */
    public function hierarchize($categoriesArray, $parentid = 0)
    {
        $hierachizedArray = [];
        foreach ($categoriesArray as $cat) {
            if ($cat['parentid'] == $parentid) {
                $id                               = $cat['categoryid'];
                $hierachizedArray[$id]            = $cat;
                $hierachizedArray[$id]['subcats'] = $this->hierarchize($categoriesArray, $cat['categoryid']);
            }
        }

        return $hierachizedArray;
    }

    /**
     * @param $category
     * @return bool
     */
    public function hasOffer($category)
    {
        $partners = $category['partners'];
        $subcats  = $category['subcats'];
        $hasoffer = false;
        foreach ($partners as $partner) {
            if (isset($partner['offers'])) {
                $hasoffer = true;
            }
        }
        if ((!$hasoffer || !$partners) && !$subcats) {
            return false;
        }
        foreach ($partners as $partner) {
            if ($partner['offers']) {
                return true;
            }
        }
        foreach ($subcats as $subcat) {
            return hasOffer($subcat);
        }
    }

    /**
     * @return mixed
     */
    public function getPartnerList()
    {
        global $partnerHandler;

        return $partnerHandler->getList();
    }

    /**
     * @return array
     */
    public function getstatusList()
    {
        global $statusArray;

        return $statusArray;
    }
}
