<?php namespace XoopsModules\Smartpartner;

/**
 * Contains the classes for updating database tables
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://www.smartfactory.ca The SmartFactory
 * @package    SmartPartner
 * @subpackage dbUpdater
 */

use XoopsModules\Smartpartner;


/**
 * Dbupdater class
 *
 * Class performing the database update for the module
 *
 * @package SmartPartner
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://www.smartfactory.ca The SmartFactory
 */
class Dbupdater
{
    /**
     * Dbupdater constructor.
     */
    public function __construct()
    {
    }

    /**
     * Use to execute a general query
     *
     * @param string $query   query that will be executed
     * @param string $goodmsg message displayed on success
     * @param string $badmsg  message displayed on error
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function runQuery($query, $goodmsg, $badmsg)
    {
        global $xoopsDB;
        $ret = $xoopsDB->query($query);
        if (!$ret) {
            echo "<li class='err'>$badmsg</li>";

            return false;
        } else {
            echo "<li class='ok'>$goodmsg</li>";

            return true;
        }
    }

    /**
     * Use to rename a table
     *
     * @param string $from name of the table to rename
     * @param string $to   new name of the renamed table
     *
     * @return bool true if success, false if an error occured
     */
    public function renameTable($from, $to)
    {
        global $xoopsDB;

        $from = $xoopsDB->prefix($from);
        $to   = $xoopsDB->prefix($to);

        $query = sprintf('ALTER TABLE %s RENAME %s', $from, $to);
        $ret   = $xoopsDB->query($query);
        if (!$ret) {
            echo "<li class='err'>" . sprintf(_AM_SPARTNER_DB_MSG_RENAME_TABLE_ERR, $from) . '</li>';

            return false;
        } else {
            echo "<li class='ok'>" . sprintf(_AM_SPARTNER_DB_MSG_RENAME_TABLE, $from, $to) . '</li>';

            return true;
        }
    }

    /**
     * Use to update a table
     *
     * @param object $table {@link Table} that will be updated
     *
     * @see Table
     *
     * @return bool true if success, false if an error occured
     */
    public function updateTable($table)
    {
        global $xoopsDB;

        $ret = true;

        // If table has a structure, create the table
        if ($table->getStructure()) {
            $ret = $table->createTable() && $ret;
        }

        // If table is flag for drop, drop it
        if ($table->_flagForDrop) {
            $ret = $table->dropTable() && $ret;
        }

        // If table has data, insert it
        if ($table->getData()) {
            $ret = $table->addData() && $ret;
        }

        // If table has new fields to be added, add them
        if ($table->getNewFields()) {
            $ret = $table->addNewFields() && $ret;
        }

        // If table has altered field, alter the table
        if ($table->getAlteredFields()) {
            $ret = $table->alterTable() && $ret;
        }

        // If table has updated field values, update the table
        if ($table->getUpdatedFields()) {
            $ret = $table->updateFieldsValues($table) && $ret;
        }

        // If table has dropped field, alter the table
        if ($table->getDroppedFields()) {
            $ret = $table->dropFields($table) && $ret;
        }

        return $ret;
    }
}
