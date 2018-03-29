<?php

use XoopsModules\Smartpartner;

require_once __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'smartpartner_offer.tpl';
include XOOPS_ROOT_PATH . '/header.php';

$offers = $offerHandler->getObjectsForUserSide();

$xoopsTpl->assign('offers', $offers);
$xoopsTpl->assign('lang_offer_click_here', _CO_SPARTNER_OFFER_CLICKHERE);
$xoopsTpl->assign('lang_offer_intro', _MD_SPARTNER_OFFER_INTRO);
include __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
