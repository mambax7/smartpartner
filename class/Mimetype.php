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

use XoopsModules\Smartpartner;

/**
 * @copyright      {@link https://xoops.org/ XOOPS Project}
 * @license        {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');
require_once XOOPS_ROOT_PATH . '/modules/smartpartner/include/common.php';
//require_once SMARTPARTNER_ROOT_PATH . 'class/baseObjectHandler.php';

/**
 * Mimetype class
 *
 * Information about an individual mimetype
 *
 * <code>
 * $hMime = xoops_getModuleHandler('mimetype', 'smartpartner');
 * $mimetype =& $hMime->get(1);
 * $mime_id = $mimetype->getVar('id');
 * </code>
 *
 * @author  Eric Juden <ericj@epcusa.com>
 * @access  public
 * @package smartpartner
 */
class Mimetype extends \XoopsObject
{
    /**
     * Mimetype constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->initVar('mime_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('mime_ext', XOBJ_DTYPE_TXTBOX, null, true, 60);
        $this->initVar('mime_types', XOBJ_DTYPE_TXTAREA, null, false, 1024);
        $this->initVar('mime_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('mime_admin', XOBJ_DTYPE_INT, null, false);
        $this->initVar('mime_user', XOBJ_DTYPE_INT, null, false);

        if (isset($id)) {
            if (is_array($id)) {
                $this->assignVars($id);
            }
        } else {
            $this->setNew();
        }
    }
} // end of class
