<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param $document
 * @return mixed
 */

use XoopsModules\Smartpartner;
/** @var Smartpartner\Helper $helper */
$helper = Smartpartner\Helper::getInstance();

/**
 * @param $document
 * @return null|string|string[]
 */
function smartpartner_metagen_html2text($document)
{
    // PHP Manual:: function preg_replace
    // $document should contain an HTML document.
    // This will remove HTML tags, javascript sections
    // and white space. It will also convert some
    // common HTML entities to their text equivalent.
    // Credits: newbb2

    $search = [
        "'<script[^>]*?>.*?</script>'si", // Strip out javascript
        "'<img.*?>'si", // Strip out img tags
        "'<[\/\!]*?[^<>]*?>'si", // Strip out HTML tags
        "'([\r\n])[\s]+'", // Strip out white space
        "'&(quot|#34);'i", // Replace HTML entities
        "'&(amp|#38);'i",
        "'&(lt|#60);'i",
        "'&(gt|#62);'i",
        "'&(nbsp|#160);'i",
        "'&(iexcl|#161);'i",
        "'&(cent|#162);'i",
        "'&(pound|#163);'i",
        "'&(copy|#169);'i"
    ]; // evaluate as php

    $replace = [
        '',
        '',
        '',
        "\\1",
        '"',
        '&',
        '<',
        '>',
        ' ',
        chr(161),
        chr(162),
        chr(163),
        chr(169),
    ];

    $text = preg_replace($search, $replace, $document);

    preg_replace_callback('/&#(\d+);/', function ($matches) {
        return chr($matches[1]);
    }, $document);

    return $text;
}

/**
 * @param         $description
 * @param  int    $maxWords
 * @return string
 */
function smartpartner_createMetaDescription($description, $maxWords = 100)
{
    $myts = \MyTextSanitizer::getInstance();

    $words = [];
    $words = explode(' ', smartpartner_metagen_html2text($description));

    $ret       = '';
    $i         = 1;
    $wordCount = count($words);
    foreach ($words as $word) {
        $ret .= $word;
        if ($i < $wordCount) {
            $ret .= ' ';
        }
        ++$i;
    }

    return $ret;
}

/**
 * @param $text
 * @param $minChar
 * @return array
 */
function smartpartner_findMetaKeywords($text, $minChar)
{
    $myts = \MyTextSanitizer::getInstance();

    $keywords         = [];
    $originalKeywords = explode(' ', $text);
    foreach ($originalKeywords as $originalKeyword) {
        $secondRoundKeywords = explode("'", $originalKeyword);
        foreach ($secondRoundKeywords as $secondRoundKeyword) {
            if (strlen($secondRoundKeyword) >= $minChar) {
                if (!in_array($secondRoundKeyword, $keywords)) {
                    $keywords[] = trim($secondRoundKeyword);
                }
            }
        }
    }

    return $keywords;
}

/**
 * @param        $title
 * @param string $categoryPath
 * @param string $description
 * @param int    $minChar
 */
function smartpartner_createMetaTags($title, $categoryPath = '', $description = '', $minChar = 4)
{
    global $xoopsTpl, $xoopsModule;
    /** @var Smartpartner\Helper $helper */
    $helper = Smartpartner\Helper::getInstance();
    $myts = \MyTextSanitizer::getInstance();

    $ret = '';

    $title = $myts->displayTarea($title);
    $title = $myts->undoHtmlSpecialChars($title);

    if (isset($categoryPath)) {
        $categoryPath = $myts->displayTarea($categoryPath);
        $categoryPath = $myts->undoHtmlSpecialChars($categoryPath);
    }

    // Creating Meta Keywords
    if (isset($title) && ('' != $title)) {
        $keywords = smartpartner_findMetaKeywords($title, $minChar);

        if (null !== ($helper->getModule()) && null !== ($helper->getConfig('moduleMetaKeywords'))
            && '' != $helper->getConfig('moduleMetaKeywords')) {
            $moduleKeywords = explode(',', $helper->getConfig('moduleMetaKeywords'));
            foreach ($moduleKeywords as $moduleKeyword) {
                if (!in_array($moduleKeyword, $keywords)) {
                    $keywords[] = trim($moduleKeyword);
                }
            }
        }

        $keywordsCount = count($keywords);
        foreach ($keywords as $i => $iValue) {
            $ret .= $keywords[$i];
            if ($i < $keywordsCount - 1) {
                $ret .= ', ';
            }
        }

        $xoopsTpl->assign('xoops_meta_keywords', $ret);
    }
    // Creating Meta Description
    if ('' != $description) {
        $xoopsTpl->assign('xoops_meta_description', smartpartner_createMetaDescription($description));
    }

    // Creating Page Title
    $moduleName = '';
    $titleTag   = [];

    if (isset($xoopsModule)) {
        $moduleName         = $myts->displayTarea($xoopsModule->name());
        $titleTag['module'] = $moduleName;
    }

    if (isset($title) && ('' != $title) && (strtoupper($title) != strtoupper($moduleName))) {
        $titleTag['title'] = $title;
    }

    if (isset($categoryPath) && ('' != $categoryPath)) {
        $titleTag['category'] = $categoryPath;
    }

    $ret = '';

    if (isset($titleTag['title']) && '' != $titleTag['title']) {
        $ret .= $titleTag['title'];
    }

    if (isset($titleTag['category']) && '' != $titleTag['category']) {
        if ('' != $ret) {
            $ret .= ' - ';
        }
        $ret .= $titleTag['category'];
    }
    if (isset($titleTag['module']) && '' != $titleTag['module']) {
        if ('' != $ret) {
            $ret .= ' - ';
        }
        $ret .= $titleTag['module'];
    }
    $xoopsTpl->assign('xoops_pagetitle', $ret);
}
