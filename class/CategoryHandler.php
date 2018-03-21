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

//if (!class_exists('PersistableObjectHandler')) {
//    require_once XOOPS_ROOT_PATH . '/modules/smartpartner/class/object.php';
//}


/**
 * Class CategoryHandler
 */
class CategoryHandler extends Smartpartner\PersistableObjectHandler
{
    /**
     * CategoryHandler constructor.
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'smartpartner_categories', Category::class, 'categoryid', 'name');
    }

    /**
     * @param  \XoopsObject $category
     * @param  bool        $force
     * @return bool
     */
    public function delete(\XoopsObject $category, $force = false)
    {
        /*if (parent::delete($object, $force)) {
            global $xoopsModule;

            // TODO: Delete partners in this category
            return true;
        }

        return false;*/

        if ('smartpartnercategory' !== strtolower(get_class($category))) {
            return false;
        }

        // Deleting the partners
        global $smartPartnerPartnerHandler;
        if (!isset($smartPartnerPartnerHandler)) {
            $smartPartnerPartnerHandler = smartpartner_gethandler('partner');
        }
        $criteria = new \Criteria('category', $category->categoryid());
        $partners = $smartPartnerPartnerHandler->getObjects($criteria);
        if ($partners) {
            foreach ($partners as $partner) {
                $smartPartnerPartnerHandler->delete($partner);
            }
        }

        // Deleteing the sub categories
        $subcats = $this->getCategories(0, 0, $category->categoryid());
        foreach ($subcats as $subcat) {
            $this->delete($subcat);
        }

        $sql = sprintf('DELETE FROM %s WHERE categoryid = %u ', $this->db->prefix('smartpartner_categories'), $category->getVar('categoryid'));

        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * @param  int    $limit
     * @param  int    $start
     * @param  int    $parentid
     * @param  string $sort
     * @param  string $order
     * @param  bool   $id_as_key
     * @return array
     */
    public function getCategories(
        $limit = 0,
        $start = 0,
        $parentid = 0,
        $sort = 'weight',
        $order = 'ASC',
        $id_as_key = true
    ) {
        $criteria = new \CriteriaCompo();

        $criteria->setSort($sort);
        $criteria->setOrder($order);

        if ($parentid != -1) {
            $criteria->add(new \Criteria('parentid', $parentid));
        }

        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $ret = $this->getObjects($criteria, $id_as_key);

        return $ret;
    }

    /**
     * @param  int $parentid
     * @return int
     */
    public function getCategoriesCount($parentid = 0)
    {
        if ($parentid == -1) {
            return $this->getCount();
        }
        $criteria = new \CriteriaCompo();
        if (isset($parentid) && ($parentid != -1)) {
            $criteria->add(new \Criteria('parentid', $parentid));
        }

        return $this->getCount($criteria);
    }
}
