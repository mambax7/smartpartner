<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param $cat_id
 * @param $catsObj
 * @param $displaysubs
 * @return array
 */

use XoopsModules\Smartpartner;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');
function get_content($cat_id, $catsObj, $displaysubs)
{
    $content = [];
    $i       = 0;
    foreach ($catsObj as $catObj) {
        if ($catObj->getVar('parentid') == $cat_id) {
            $content[$catObj->getVar('categoryid')]['link']        = "<a href='" . XOOPS_URL . '/modules/smartpartner/index.php?view_category_id=' . $catObj->getVar('categoryid') . "'>" . $catObj->getVar('name') . '</a>';
            $content[$catObj->getVar('categoryid')]['categories']  = get_content($catObj->getVar('categoryid'), $catsObj, $displaysubs);
            $content[$catObj->getVar('categoryid')]['displaysubs'] = $displaysubs;
        }
        ++$i;
    }

    return $content;
}

/**
 * @param $options
 * @return array
 */
function b_categories_list_show($options)
{
    require_once XOOPS_ROOT_PATH . '/modules/smartpartner/include/common.php';

    $categoryHandler = Smartpartner\Helper::getInstance()->getHandler('Category');
    $criteria                    = new \CriteriaCompo();

    $criteria->setSort(isset($options[0]) ? $options[0] : 'name');
    $criteria->setOrder(isset($options[1]) ? $options[1] : 'ASC');

    $catsObj  = $categoryHandler->getObjects($criteria, true);
    $catArray = get_content(0, $catsObj, $options[2]);

    $block                = [];
    $block['categories']  = $catArray;
    $block['displaysubs'] = $options[2];
    if (isset($_GET['view_category_id'])) {
        $current_id       = $_GET['view_category_id'];
        $block['current'] = 0 == $catsObj[$current_id]->getVar('parentid') ? $current_id : $catsObj[$current_id]->getVar('parentid');
    } elseif (isset($_GET['id'])) {
        $partnerHandler = Smartpartner\Helper::getInstance()->getHandler('Partner');
        $partnerObj                 = $partnerHandler->get($_GET['id']);
        if (is_object($partnerObj)) {
            $parent           = $partnerObj->getVar('categoryid');
            $block['current'] = 0 == $catsObj[$parent]->getVar('parentid') ? $parent : $catsObj[$parent]->getVar('parentid');
        }
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_categories_list_edit($options)
{
    $form = "<table border='0'>";

    /*$form .= "<tr><td>"._MB_SPARTNER_BLIMIT."</td><td>";
     $form .= "<input type='text' name='options[0]' size='16' value='".$options[0]."'></td></tr>";*/
    //sort
    $form .= '<tr><td>' . _MB_SPARTNER_SORT . '</td><td>';
    $form .= "<select size='1' name='options[0]'>";
    $sel  = '';
    if ('title' === $options[0]) {
        $sel = ' selected';
    }
    $form .= "<option value='name' " . $sel . '>' . _MB_SPARTNER_TITLE . '</option>';
    $sel  = '';
    if ('weight' === $options[0]) {
        $sel = ' selected';
    }
    $form .= "<option value='weight' " . $sel . '>' . _MB_SPARTNER_WEIGHT . '</option>';
    $sel  = '';
    if ('categoryid' === $options[0]) {
        $sel = ' selected';
    }
    $form .= "<option value='categoryid' " . $sel . '>' . _MB_SPARTNER_ID . '</option>';
    $form .= '</select></td></tr>';

    //order
    $form .= '<tr><td>' . _MB_SPARTNER_ORDER . '</td><td>';
    $form .= "<select size='1' name='options[2]'>";
    $sel  = '';
    if ('ASC' === $options[1]) {
        $sel = ' selected';
    }
    $form .= "<option value='ASC' " . $sel . '>' . _MB_SPARTNER_ASC . '</option>';
    $sel  = '';
    if ('DESC' === $options[1]) {
        $sel = ' selected';
    }
    $form .= "<option value='DESC' " . $sel . '>' . _MB_SPARTNER_DESC . '</option>';

    $form .= '</select></td></tr>';

    //displaysubs
    $form .= '<tr><td>' . _MB_SPARTNER_SHOW_CURR_SUBS . '</td><td>';
    $form .= "<select size='1' name='options[3]'>";
    $sel  = '';
    if (1 == $options[2]) {
        $sel = ' selected';
    }
    $form .= "<option value='1' " . $sel . '>' . _MB_SPARTNER_YES . '</option>';
    $sel  = '';
    if (0 == $options[2]) {
        $sel = ' selected';
    }
    $form .= "<option value='0' " . $sel . '>' . _MB_SPARTNER_NO . '</option>';

    $form .= '</select></td></tr>';
    $form .= '</table>';

    return $form;
}
