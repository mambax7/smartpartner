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
 * Class PartnerCatLink
 */
class PartnerCatLink extends Smartobject\BaseSmartObject
{
    /**
     * Smartpartner\PartnerCatLink constructor.
     */
    public function __construct()
    {
        $this->initVar('partner_cat_linkid', XOBJ_DTYPE_INT, '', true);
        $this->initVar('partnerid', XOBJ_DTYPE_INT, '', true);
        $this->initVar('categoryid', XOBJ_DTYPE_INT, '', true);
    }
}
