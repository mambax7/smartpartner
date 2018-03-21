<?php namespace XoopsModules\Smartpartner;

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartpartner;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/smartpartner/include/common.php';


/**
 * Files handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of File class objects.
 *
 * @author  marcan <marcan@notrevie.ca>
 * @package SmartPartner
 */
class FileHandler extends \XoopsObjectHandler
{
    /**
     * create a new file
     *
     * @param  bool $isNew flag the new objects as "new"?
     * @return object File
     */
    public function create($isNew = true)
    {
        $file = new File();
        if ($isNew) {
            $file->setNew();
        }

        return $file;
    }

    /**
     * retrieve an file
     *
     * @param  int $id fileid of the file
     * @return mixed reference to the {@link File} object, FALSE if failed
     */
    public function get($id)
    {
        if ((int)$id > 0) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('smartpartner_files') . ' WHERE fileid=' . $id;
            if (!$result = $this->db->query($sql)) {
                return false;
            }

            $numrows = $this->db->getRowsNum($result);
            if (1 == $numrows) {
                $file = new File();
                $file->assignVars($this->db->fetchArray($result));

                return $file;
            }
        }

        return false;
    }

    /**
     * insert a new file in the database
     *
     * @param  \XoopsObject $fileObj
     * @param  bool        $force
     * @return bool        FALSE if failed, TRUE if already present and unchanged or successful
     * @internal param object $file reference to the <a href='psi_element://File'>File</a> object object
     */
    public function insert(\XoopsObject $fileObj, $force = false)
    {
        if ('smartpartnerfile' !== strtolower(get_class($fileObj))) {
            return false;
        }
        if (!$fileObj->isDirty()) {
            return true;
        }
        if (!$fileObj->cleanVars()) {
            return false;
        }

        foreach ($fileObj->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        if ($fileObj->isNew()) {
            $sql = sprintf(
                'INSERT INTO %s (fileid, id, name, description, filename, mimetype, uid, datesub, `status`, notifypub, counter) VALUES (NULL, %u, %s, %s, %s, %s, %u, %u, %u, %u, %u)',
                           $this->db->prefix('smartpartner_files'),
                $id,
                $this->db->quoteString($name),
                $this->db->quoteString($description),
                $this->db->quoteString($filename),
                           $this->db->quoteString($mimetype),
                $uid,
                time(),
                $status,
                $notifypub,
                $counter
            );
        } else {
            $sql = sprintf(
                'UPDATE %s SET id = %u, name = %s, description = %s, filename = %s, mimetype = %s, uid = %u, datesub = %u, status = %u, notifypub = %u, counter = %u WHERE fileid = %u',
                           $this->db->prefix('smartpartner_files'),
                $id,
                $this->db->quoteString($name),
                $this->db->quoteString($description),
                $this->db->quoteString($filename),
                           $this->db->quoteString($mimetype),
                $uid,
                $datesub,
                $status,
                $notifypub,
                $counter,
                $fileid
            );
        }

        //echo "<br>$sql<br>";

        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        if (!$result) {
            $fileObj->setErrors('The query returned an error. ' . $this->db->error());

            return false;
        }

        if ($fileObj->isNew()) {
            $fileObj->assignVar('fileid', $this->db->getInsertId());
        }

        $fileObj->assignVar('fileid', $fileid);

        return true;
    }

    /**
     * delete a file from the database
     *
     * @param  XoopsObject $file reference to the file to delete
     * @param  bool        $force
     * @return bool        FALSE if failed.
     */
    public function delete(\XoopsObject $file, $force = false)
    {
        if ('smartpartnerfile' !== strtolower(get_class($file))) {
            return false;
        }
        // Delete the actual file
        if (!smartpartner_deleteFile($file->getFilePath())) {
            return false;
        }
        // Delete the record in the table
        $sql = sprintf('DELETE FROM %s WHERE fileid = %u', $this->db->prefix('smartpartner_files'), $file->getVar('fileid'));

        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * delete files related to an item from the database
     *
     * @param  object $itemObj reference to the item which files to delete
     * @return bool
     */
    public function deleteItemFiles(&$itemObj)
    {
        if ('smartpartneritem' !== strtolower(get_class($itemObj))) {
            return false;
        }
        $files  = $this->getAllFiles($itemObj->id());
        $result = true;
        foreach ($files as $file) {
            if (!$this->delete($file)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * retrieve files from the database
     *
     * @param  object $criteria  {@link CriteriaElement} conditions to be met
     * @param  bool   $id_as_key use the fileid as key for the array?
     * @return array  array of {@link File} objects
     */
    public function getObjects($criteria = null, $id_as_key = false)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('smartpartner_files');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        //echo "<br>" . $sql . "<br>";
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $file = new File();
            $file->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $file;
            } else {
                $ret[$myrow['fileid']] =& $file;
            }
            unset($file);
        }

        return $ret;
    }

    /**
     * retrieve all files
     *
     * @param  int    $id
     * @param  int    $status
     * @param  int    $limit
     * @param  int    $start
     * @param  string $sort
     * @param  string $order
     * @return array  array of <a href='psi_element://File'>File</a> objects
     *                       objects
     * @internal param object $criteria <a href='psi_element://CriteriaElement'>CriteriaElement</a> conditions to be met conditions to be met
     */
    public function getAllFiles($id = 0, $status = -1, $limit = 0, $start = 0, $sort = 'datesub', $order = 'DESC')
    {
        $hasStatusCriteria = false;
        $criteriaStatus    = new \CriteriaCompo();
        if (is_array($status)) {
            $hasStatusCriteria = true;
            foreach ($status as $v) {
                $criteriaStatus->add(new \Criteria('status', $v), 'OR');
            }
        } elseif ($status != -1) {
            $hasStatusCriteria = true;
            $criteriaStatus->add(new \Criteria('status', $status), 'OR');
        }
        $criteriaItemid = new \Criteria('id', $id);

        $criteria = new \CriteriaCompo();

        if (0 != $id) {
            $criteria->add($criteriaItemid);
        }

        if ($hasStatusCriteria) {
            $criteria->add($criteriaStatus);
        }

        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($limit);
        $criteria->setStart($start);
        $files =& $this->getObjects($criteria);

        return $files;
    }

    /**
     * count files matching a condition
     *
     * @param  object $criteria {@link CriteriaElement} to match
     * @return int    count of files
     */
    public function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('smartpartner_files');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);

        return $count;
    }

    /**
     * delete files matching a set of conditions
     *
     * @param  object $criteria {@link CriteriaElement}
     * @return bool   FALSE if deletion failed
     */
    public function deleteAll($criteria = null)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('smartpartner_files');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * Change a value for files with a certain criteria
     *
     * @param string $fieldname  Name of the field
     * @param string $fieldvalue Value to write
     * @param object $criteria   {@link CriteriaElement}
     *
     * @return bool
     **/
    public function updateAll($fieldname, $fieldvalue, $criteria = null)
    {
        $set_clause = is_numeric($fieldvalue) ? $fieldname . ' = ' . $fieldvalue : $fieldname . ' = ' . $this->db->quoteString($fieldvalue);
        $sql        = 'UPDATE ' . $this->db->prefix('smartpartner_files') . ' SET ' . $set_clause;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        //echo "<br>" . $sql . "<br>";
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }

        return true;
    }
}
