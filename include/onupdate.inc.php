<?php

use XoopsModules\Smartpartner;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

global $modversion;
if (!empty($_POST['fct']) && !empty($_POST['op']) && 'modulesadmin' === $_POST['fct'] && 'update_ok' === $_POST['op'] && $_POST['dirname'] == $modversion['dirname']) {
    // referer check
    $ref = xoops_getenv('HTTP_REFERER');
    if ('' == $ref || 0 === strpos($ref, XOOPS_URL . '/modules/system/admin.php')) {
        /* module specific part */

        /* General part */

        // Keep the values of block's options when module is updated (by nobunobu)
        include __DIR__ . '/updateblock.inc.php';
    }
}

/**
 * @param  XoopsModule $module
 * @return bool
 */
function xoops_module_update_smartpartner(\XoopsModule $module)
{
//    require_once XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname') . '/include/functions.php';
//    require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartdbupdater.php';

    $dbupdater = new Smartpartner\Dbupdater();

    ob_start();

    $dbVersion = Smartpartner\Utility::getMeta('version');

    $dbupdater = new Smartpartner\Dbupdater();

    echo '<code>' . _SDU_UPDATE_UPDATING_DATABASE . '<br>';

    //Smartpartner\Utility::createUploadFolders();

    // db migrate version = 3
    $newDbVersion = 3;
    if ($dbVersion < $newDbVersion) {
        echo 'Database migrate to version ' . $newDbVersion . '<br>';

        $table = new Smartpartner\DbTable('smartpartner_partner');
        $table->addNewField('email_priv', " tinyint(1) NOT NULL default '0'");
        $table->addNewField('phone_priv', " tinyint(1) NOT NULL default '0'");
        $table->addNewField('adress_priv', " tinyint(1) NOT NULL default '0'");

        if (!$dbupdater->updateTable($table)) {
            /**
             * @todo trap the errors
             */
        }
        unset($table);
    }
    // db migrate version =4
    $newDbVersion = 4;
    if ($dbVersion < $newDbVersion) {
        echo 'Database migrate to version ' . $newDbVersion . '<br>';
        //create new tables
        // Create table smartpartner_categories
        $table = new Smartpartner\DbTable('smartpartner_categories');
        if (!$table->exists()) {
            $table->setStructure("
              `categoryid` int(11) NOT NULL auto_increment,
              `parentid` int(11) NOT NULL default '0',
              `name` varchar(100) NOT NULL default '',
              `description` text NOT NULL,
              `image` varchar(255) NOT NULL default '',
              `total` int(11) NOT NULL default '0',
              `weight` int(11) NOT NULL default '1',
              `created` int(11) NOT NULL default '0',
              PRIMARY KEY  (`categoryid`)
            ");

            if (!$dbupdater->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }
        // Create table smartpartner_partner_cat_link
        $table = new Smartpartner\DbTable('smartpartner_partner_cat_link');
        if (!$table->exists()) {
            $table->setStructure("
              `partner_cat_linkid` int(11) NOT NULL auto_increment,
              `categoryid` int(11) NOT NULL default '0',
              `partnerid` int(11) NOT NULL default '0',
               PRIMARY KEY  (`partner_cat_linkid`)
            ");

            if (!$dbupdater->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }

        // Create table smartpartner_offer
        $table = new Smartpartner\DbTable('smartpartner_offer');
        if (!$table->exists()) {
            $table->setStructure("
               `offerid` int(11) NOT NULL auto_increment,
              `partnerid` int(11) NOT NULL default '0',
              `title` varchar(255) NOT NULL default '',
              `description` TEXT NOT NULL,
              `url` varchar(150) default '',
              `image` varchar(150) NOT NULL default '',
              `date_sub` int(11) NOT NULL default '0',
              `date_pub` int(11) NOT NULL default '0',
              `date_end` int(11) NOT NULL default '0',
              `status` int(10) NOT NULL default '-1',
              `weight` int(1) NOT NULL default '0',
              `dohtml` int(1) NOT NULL default '1',
              PRIMARY KEY  (`offerid`)
            ");

            if (!$dbupdater->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }

        // Create table smartpartner_offer
        $table = new Smartpartner\DbTable('smartpartner_files');
        if (!$table->exists()) {
            $table->setStructure("
              `fileid` int(11) NOT NULL auto_increment,
              `id` int(11) NOT NULL default '0',
              `name` varchar(255) NOT NULL default '',
              `description` TEXT NOT NULL,
              `filename` varchar(255) NOT NULL default '',
              `mimetype` varchar(64) NOT NULL default '',
              `uid` int(6) default '0',
              `datesub` int(11) NOT NULL default '0',
              `status` int(1) NOT NULL default '-1',
              `notifypub` tinyint(1) NOT NULL default '1',
              `counter` int(8) unsigned NOT NULL default '0',
              PRIMARY KEY  (`fileid`)
            ");

            if (!$dbupdater->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }
        //loop in partners to insert cat_links in partner_cat_link table
        $partnerHandler = xoops_getModuleHandler('partner', 'smartpartner');
        //        $partnerCatLinkHandler = xoops_getModuleHandler('partner_cat_link', 'smartpartner');
        $partnerCatLinkHandler = xoops_getModuleHandler('partner_cat_link', 'smartpartner');

        $modulepermHandler = xoops_getHandler('groupperm');
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname('smartpartner');
        $groupsArray   = $modulepermHandler->getGroupIds('module_read', $module->mid(), 1);

        $sql     = 'SELECT id, categoryid FROM ' . $partnerHandler->table;
        $records = $partnerHandler->query($sql);
        foreach ($records as $record) {
            if (0 != $record['categoryid']) {
                $new_link = $partnerCatLinkHandler->create();
                $new_link->setVar('partnerid', $record['id']);
                $new_link->setVar('categoryid', $record['categoryid']);
                $partnerCatLinkHandler->insert($new_link);
                unset($new_link);
            }
            foreach ($groupsArray as $group) {
                $modulepermHandler->addRight('full_view', $record['id'], $group, $module->mid());
            }
        }
        //drop cat_id in partner table
        $table = new Smartpartner\DbTable('smartpartner_partner');
        $table->addNewField('last_update', " int(11) NOT NULL default '0'");
        $table->addNewField('showsummary', " tinyint(1) NOT NULL default '0'");
        $table->addDroppedField('categoryid');
        if (!$dbupdater->updateTable($table)) {
            /**
             * @todo trap the errors
             */
        }
        unset($table);
    }
    echo '</code>';

    $feedback = ob_get_clean();
    if (method_exists($module, 'setMessage')) {
        $module->setMessage($feedback);
    } else {
        echo $feedback;
    }
    Smartpartner\Utility::setMeta('version', isset($newDbVersion) ? $newDbVersion : 0); //Set meta version to current

    return true;
}

/**
 * @param  XoopsModule $module
 * @return bool
 */
function xoops_module_install_smartpartner(\XoopsModule $module)
{
    ob_start();

//    require_once XOOPS_ROOT_PATH . '/modules/' . $module->getVar('dirname') . '/include/functions.php';

    Smartpartner\Utility::createUploadFolders();

    $feedback = ob_get_clean();
    if (method_exists($module, 'setMessage')) {
        $module->setMessage($feedback);
    } else {
        echo $feedback;
    }

    return true;
}
