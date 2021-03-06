<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartpartner;
use XoopsModules\Smartobject;

require_once __DIR__ . '/admin_header.php';

require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjectabout.php';
$aboutObj = new Smartobject\About();
$aboutObj->render();
