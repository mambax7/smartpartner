<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param $options
 * @return array
 */
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

function b_partners_list_show($options)
{
    require_once XOOPS_ROOT_PATH . '/modules/smartpartner/include/common.php';

    // Creating the partner handler object
    $partnerHandler  = Smartpartner\Helper::getInstance()->getHandler('Partner');
    $categoryHandler = Smartpartner\Helper::getInstance()->getHandler('Category');

    // Randomize
    $partnersObj = $partnerHandler->getPartners(0, 0, _SPARTNER_STATUS_ACTIVE);
    if (count($partnersObj) > 1) {
        $key_arr  = array_keys($partnersObj);
        $key_rand = array_rand($key_arr, count($key_arr));
        for ($i = 0; ($i < count($partnersObj)) && ((0 == $options[2]) || ($i < $options[2])); ++$i) {
            $newObjs[$i] = $partnersObj[$key_rand[$i]];
        }

        $partnersObj = $newObjs;
    }

    $cat_id = [];
    foreach ($partnersObj as $partnerObj) {
        $p_cats     = $partnerObj->categoryid();
        $p_cat_rand = array_rand(explode('|', $p_cats));

        if (!in_array($p_cats[$p_cat_rand], $cat_id)) {
            $cat_id[] = $p_cats[$p_cat_rand];
        }
        //partner belong to only one category in the block
        $partnerObj->setVar('categoryid', $p_cats[$p_cat_rand]);
    }

    $block = [];
    if ($partnersObj) {
        foreach ($cat_id as $j => $jValue) {
            $categoryObj                              = $categoryHandler->get($cat_id[$j]);
            $block['categories'][$cat_id[$j]]['link'] = (0 != $jValue) ? $categoryObj->getCategoryLink() : false;

            for ($i = 0, $iMax = count($partnersObj); $i < $iMax; ++$i) {
                //if (in_array($cat_id[$j], explode('|', $partnersObj[$i]->categoryid()))) {
                if ($partnersObj[$i]->categoryid() == $jValue) {
                    $partner['id']      = $partnersObj[$i]->id();
                    $partner['urllink'] = $partnersObj[$i]->getUrlLink('block');
                    if ($partnersObj[$i]->image() && ((1 == $options[3]) || (3 == $options[3]))) {
                        $partner['image'] = $partnersObj[$i]->getImageUrl();
                    }
                    if ($partnersObj[$i]->image() && ((2 == $options[3]) || (3 == $options[3]))) {
                        $partner['title'] = $partnersObj[$i]->title();
                    } else {
                        $partner['title'] = '';
                    }
                    $smartConfig                                    = Smartpartner\Utility::getModuleConfig();
                    $image_info                                     = Smartpartner\Utility::imageResize($partnersObj[$i]->getImagePath(), $smartConfig['img_max_width'], $smartConfig['img_max_height']);
                    $partner['img_attr']                            = $image_info[3];
                    $partner['extendedInfo']                        = $partnersObj[$i]->extentedInfo();
                    $block['categories'][$cat_id[$j]]['partners'][] = $partner;
                }
            }
        }
        if (1 == $options[0]) {
            $block['insertBr'] = true;
        }
        if (1 == $options[1]) {
            $block['fadeImage'] = 'style="filter:alpha(opacity=20);" onmouseover="nereidFade(this,100,30,5)" onmouseout="nereidFade(this,50,30,5)"';
        }

        $block['see_all']          = $options[6];
        $block['lang_see_all']     = _MB_SPARTNER_LANG_SEE_ALL;
        $block['smartpartner_url'] = SMARTPARTNER_URL;
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_partners_list_edit($options)
{
    $form = "<table border='0'>";
    $form .= '<tr><td>' . _MB_SPARTNER_PARTNERS_PSPACE . '</td><td>';
    $chk  = '';
    if (0 == $options[0]) {
        $chk = ' checked';
    }
    $form .= "<input type='radio' name='options[0]' value='0'" . $chk . '>' . _NO . '';
    $chk  = '';
    if (1 == $options[0]) {
        $chk = ' checked';
    }
    $form .= "<input type='radio' name='options[0]' value='1'" . $chk . '>' . _YES . '</td></tr>';
    $form .= '<tr><td>' . _MB_SPARTNER_FADE . '</td><td>';
    $chk  = '';
    if (0 == $options[1]) {
        $chk = ' checked';
    }
    $form .= "<input type='radio' name='options[1]' value='0'" . $chk . '>' . _NO . '';
    $chk  = '';
    if (1 == $options[1]) {
        $chk = ' checked';
    }
    $form .= "<input type='radio' name='options[1]' value='1'" . $chk . '>' . _YES . '</td></tr>';
    /*$form .= "<tr><td>"._MB_SPARTNER_BRAND."</td><td>";
     $chk   = "";
     if ($options[2] == 0) {
         $chk = " checked";
     }*/
    /*$form .= "<input type='radio' name='options[2]' value='0'".$chk.">"._NO."";
     $chk   = "";
     if ($options[2] == 1) {
         $chk = " checked";
     }
     $form .= "<input type='radio' name='options[2]' value='1'".$chk.">"._YES."</td></tr>";*/
    $form .= '<tr><td>' . _MB_SPARTNER_BLIMIT . '</td><td>';
    $form .= "<input type='text' name='options[2]' size='16' value='" . $options[2] . "'></td></tr>";
    /*$form .= "<tr><td>"._MB_SPARTNER_BSHOW."</td><td>";
     $form .= "<select size='1' name='options[3]'>";
     $sel = "";
     if ($options[3] == 1) {
         $sel = " selected";
     }
     $form .= "<option value='1' ".$sel.">"._MB_SPARTNER_IMAGES."</option>";
     $sel = "";
     if ($options[3] == 2) {
         $sel = " selected";
     }
     $form .= "<option value='2' ".$sel.">"._MB_SPARTNER_TEXT."</option>";
     $sel = "";
     if ($options[3] == 3) {
         $sel = " selected";
     }
     $form .= "<option value='3' ".$sel.">"._MB_SPARTNER_BOTH."</option>";
     $form .= "</select></td></tr>";
     $form .= "<tr><td>"._MB_SPARTNER_BORDER."</td><td>";
     $form .= "<select size='1' name='options[5]'>";
     $sel = "";
     if ($options[4] == "id") {
         $sel = " selected";
     }
     $form .= "<option value='id' ".$sel.">"._MB_SPARTNER_ID."</option>";
     $sel = "";
     if ($options[4] == "hits") {
         $sel = " selected";
     }
     $form .= "<option value='hits' ".$sel.">"._MB_SPARTNER_HITS."</option>";
     $sel = "";
     if ($options[4] == "title") {
         $sel = " selected";
     }
     $form .= "<option value='title' ".$sel.">"._MB_SPARTNER_TITLE."</option>";
     if ($options[4] == "weight") {
         $sel = " selected";
     }
     $form .= "<option value='weight' ".$sel.">"._MB_SPARTNER_WEIGHT."</option>";
     $form .= "</select> ";
     $form .= "<select size='1' name='options[6]'>";
     $sel = "";
     if ($options[5] == "ASC") {
         $sel = " selected";
     }
     $form .= "<option value='ASC' ".$sel.">"._MB_SPARTNER_ASC."</option>";
     $sel = "";
     if ($options[5] == "DESC") {
         $sel = " selected";
     }
     $form .= "<option value='DESC' ".$sel.">"._MB_SPARTNER_DESC."</option>";
     $form .= "</select></td></tr>";

     $form .= "<tr><td>"._MB_SPARTNER_SEE_ALL."</td><td>";
     $chk   = "";
     if ($options[6] == 0) {
         $chk = " checked";
     }
     $form .= "<input type='radio' name='options[7]' value='0'".$chk.">"._NO."";
     $chk   = "";
     if ($options[6] == 1) {
         $chk = " checked";
     }
     $form .= "<input type='radio' name='options[7]' value='1'".$chk.">"._YES."</td></tr>";*/

    $form .= '</table>';

    return $form;
}
