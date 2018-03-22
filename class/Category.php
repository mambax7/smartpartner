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
 * Class Category
 */
class Category extends \XoopsObject
{
    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->initVar('categoryid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('parentid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, null, false, 255);
        $this->initVar('image', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('total', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('created', XOBJ_DTYPE_INT, null, false);
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
    public function parentid()
    {
        return $this->getVar('parentid');
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function name($format = 'S')
    {
        $ret = $this->getVar('name', $format);
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
    public function description($format = 'S')
    {
        return $this->getVar('description', $format);
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
     * @param  bool $falseIfNoImage
     * @return bool|mixed|string
     */
    public function getImageUrl($falseIfNoImage = false)
    {
        if (('' !== $this->getVar('image')) && ('blank.png' !== $this->getVar('image'))
            && ('-1' !== $this->getVar('image'))) {
            return smartpartner_getImageDir('category', false) . $this->image();
        } elseif ($falseIfNoImage) {
            return false;
        } elseif (!$this->getVar('image_url')) {
            return smartpartner_getImageDir('category', false) . 'blank.png';
        } else {
            return $this->getVar('image_url');
        }
    }

    /**
     * @return mixed
     */
    public function weight()
    {
        return $this->getVar('weight');
    }

    /**
     * @return bool
     */
    public function notLoaded()
    {
        return (-1 == $this->getVar('categoryid'));
    }

    /**
     * @param  bool $withAllLink
     * @return mixed|string
     */
    public function getCategoryPath($withAllLink = true)
    {
        $filename = 'category.php';
        if ($withAllLink) {
            $ret = $this->getCategoryLink();
        } else {
            $ret = $this->name();
        }
        $parentid = $this->parentid();
        global $smartPartnerCategoryHandler;
        if (0 != $parentid) {
            $parentObj = $smartPartnerCategoryHandler->get($parentid);
            if ($parentObj->notLoaded()) {
                exit;
            }
            $parentid = $parentObj->parentid();
            $ret      = $parentObj->getCategoryPath($withAllLink) . ' > ' . $ret;
        }

        return $ret;
    }

    /**
     * @return string
     */
    public function getCategoryUrl()
    {
        return smartpartner_generateSeoUrl('category', $this->categoryid(), $this->name());
    }

    /**
     * @param  bool $class
     * @return string
     */
    public function getCategoryLink($class = false)
    {
        if ($class) {
            return "<a class='$class' href='" . $this->getCategoryUrl() . "'>" . $this->name() . '</a>';
        } else {
            return "<a href='" . $this->getCategoryUrl() . "'>" . $this->name() . '</a>';
        }
    }

    /**
     * @param  bool $sendNotifications
     * @param  bool $force
     * @return mixed
     */
    public function store($sendNotifications = true, $force = true)
    {
        global $smartPartnerCategoryHandler;
        $ret = $smartPartnerCategoryHandler->insert($this, $force);
        if ($sendNotifications && $ret && $this->isNew()) {
            $this->sendNotifications();
        }
        $this->unsetNew();

        return $ret;
    }

    public function sendNotifications()
    {
        $hModule     = xoops_getHandler('module');
        $smartModule =& $hModule->getByDirname('smartpartner');
        $module_id   = $smartModule->getVar('mid');

        $myts                = \MyTextSanitizer::getInstance();
        $notificationHandler = xoops_getHandler('notification');

        $tags                  = [];
        $tags['MODULE_NAME']   = $myts->displayTarea($smartModule->getVar('name'));
        $tags['CATEGORY_NAME'] = $this->name();
        $tags['CATEGORY_URL']  = $this->getCategoryUrl();

        $notificationHandler = xoops_getHandler('notification');
        $notificationHandler->triggerEvent('global_item', 0, 'category_created', $tags);
    }

    /**
     * @param  array $category
     * @return array
     */
    public function toArray($category = [])
    {
        $category['categoryid']   = $this->categoryid();
        $category['name']         = $this->name();
        $category['categorylink'] = $this->getCategoryLink();
        $category['total']        = $this->getVar('itemcount');
        $category['description']  = $this->description();

        if ('blank.png' !== $this->image()) {
            $category['image_path'] = smartpartner_getImageDir('category', false) . $this->image();
        } else {
            $category['image_path'] = '';
        }

        return $category;
    }
}
