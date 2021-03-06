<?php

/**
 *
 * Module: SmartMedia
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 * @return array
 */

use XoopsModules\Smartpartner;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 * @return array
 */
function smartpartner_search($queryarray, $andor, $limit, $offset, $userid)
{
    // This must contain the name of the folder in which reside SmartPartner
    require_once __DIR__ . '/common.php';

    $ret = [];

    if (!isset($partnerHandler)) {
        $partnerHandler = Smartpartner\Helper::getInstance()->getHandler('Partner');
    }

    // Searching the partners
    $partners_result = $partnerHandler->getObjectsForSearch($queryarray, $andor, $limit, $offset, $userid);

    if ('' == $queryarray) {
        $keywords       = '';
        $hightlight_key = '';
    } else {
        $keywords       = implode('+', $queryarray);
        $hightlight_key = '&amp;keywords=' . $keywords;
    }

    foreach ($partners_result as $result) {
        $item['image'] = 'assets/images/links/partner.gif';
        $item['link']  = 'partner.php?id=' . $result['id'] . $hightlight_key;
        $item['title'] = '' . $result['title'];
        $item['time']  = '';
        $item['uid']   = '';
        $ret[]         = $item;
        unset($item);
    }

    return $ret;
}
