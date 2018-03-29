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
 * @author       XOOPS Development Team
 */

//defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * class Constants
 */
class Constants
{
    /**#@+
     * Constant definition
     */

    const DISALLOW = 0;

    // CONFIG displayicons
    const DISPLAYICONS_ICON = 1;
    const DISPLAYICONS_TEXT = 2;
    const DISPLAYICONS_NO = 3;

    // CONFIG submissions
    const SUBMISSIONS_NONE = 1;
    const SUBMISSIONS_DOWNLOAD = 2;
    const SUBMISSIONS_MIRROR = 3;
    const SUBMISSIONS_BOTH = 4;

    // CONFIG anonpost
    const ANONPOST_NONE = 1;
    const ANONPOST_DOWNLOAD = 2;
    const ANONPOST_MIRROR = 3;
    const ANONPOST_BOTH = 4;

    // CONFIG autoapprove
    const AUTOAPPROVE_NONE = 1;
    const AUTOAPPROVE_DOWNLOAD = 2;
    const AUTOAPPROVE_MIRROR = 3;
    const AUTOAPPROVE_BOTH = 4;

    const DEFAULT_ELEMENT_SIZE = 1;

    //---------------------------------

    // Partners status
    const SPARTNER_STATUS_NOTSET = -1;
    const SPARTNER_STATUS_ALL = 0;
    const SPARTNER_STATUS_SUBMITTED = 1;
    const SPARTNER_STATUS_ACTIVE = 2;
    const SPARTNER_STATUS_REJECTED = 3;
    const SPARTNER_STATUS_INACTIVE = 4;

    const SPARTNER_NOT_PARTNER_SUBMITTED = 1;
    const SPARTNER_NOT_PARTNER_APPROVED = 2;
    const SPARTNER_NOT_PARTNER_NEW = 3;
    const SPARTNER_NOT_OFFER_NEW = 4;

    // File status
    const SPARTNER_STATUS_FILE_NOTSET = -1;
    const SPARTNER_STATUS_FILE_ACTIVE = 1;
    const SPARTNER_STATUS_FILE_INACTIVE = 2;

    /**#@-*/
}

