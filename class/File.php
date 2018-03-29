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
 * Class File
 */
class File extends \XoopsObject
{
    /**
     * constructor
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('fileid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('description', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('filename', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('mimetype', XOBJ_DTYPE_TXTBOX, null, true, 64);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('datesub', XOBJ_DTYPE_INT, null, false);
        $this->initVar('status', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('notifypub', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('counter', XOBJ_DTYPE_INT, null, false);

        if (isset($id)) {
            global $fileHandler;
            $file = $fileHandler->get($id);
            foreach ($file->vars as $k => $v) {
                $this->assignVar($k, $v['value']);
            }
        }
    }

    /**
     * @param $post_field
     * @param $allowed_mimetypes
     * @param $errors
     * @return bool
     */
    public function checkUpload($post_field, &$allowed_mimetypes, &$errors)
    {
        require_once SMARTPARTNER_ROOT_PATH . 'class/uploader.php';
        $config = Smartpartner\Utility::getModuleConfig();

        $maxfilesize   = $config['maximum_filesize'];
        $maxfilewidth  = 100000; //$config['maximum_image_width'];
        $maxfileheight = 100000; //$config['maximum_image_height'];

        $errors = [];

        if (!isset($allowed_mimetypes)) {
            $hMime             = xoops_getModuleHandler('mimetype');
            $allowed_mimetypes = $hMime->checkMimeTypes($post_field);
            if (!$allowed_mimetypes) {
                $errors[] = _SMARTPARTNER_MESSAGE_WRONG_MIMETYPE;

                return false;
            }
        }
        $uploader = new \XoopsMediaUploader(Smartpartner\Utility::getUploadDir(), $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);

        if ($uploader->fetchMedia($post_field)) {
            return true;
        } else {
            $errors = array_merge($errors, $uploader->getErrors(false));

            return false;
        }
    }

    /**
     * @param $text
     * @return mixed
     */
    public function purifyText($text)
    {
        global $myts;
        $text = str_replace('&nbsp;', ' ', $text);
        $text = str_replace('<br>', ' ', $text);
        $text = str_replace('. ', ' ', $text);
        $text = str_replace(', ', ' ', $text);
        $text = str_replace(')', '', $text);
        $text = str_replace('(', '', $text);
        $text = str_replace(':', '', $text);
        $text = str_replace('&euro', '', $text);
        $text = str_replace(';', '', $text);
        $text = str_replace('!', ' ', $text);
        $text = str_replace('?', ' ', $text);
        $text = str_replace('é', 'e', $text);
        $text = str_replace('è', 'e', $text);
        $text = str_replace('ê', 'e', $text);
        $text = str_replace('â', 'a', $text);
        $text = str_replace('à', 'a', $text);
        $text = str_replace('ù', 'u', $text);
        $text = str_replace('û', 'u', $text);
        $text = str_replace('ô', 'o', $text);
        $text = str_replace('ñ', 'n', $text);
        $text = str_replace('É', 'e', $text);
        $text = str_replace('È', 'e', $text);
        $text = str_replace('Ê', 'e', $text);
        $text = str_replace('Â', 'A', $text);
        $text = str_replace('À', 'A', $text);
        $text = str_replace('Ù', 'U', $text);
        $text = str_replace('Û', 'U', $text);
        $text = str_replace('Ô', 'O', $text);
        $text = str_replace('Ñ', 'N', $text);
        $text = str_replace("'", '', $text);
        $text = str_replace("\\", '', $text);
        $text = strip_tags($text);
        $text = html_entity_decode($text);
        $text = $myts->undoHtmlSpecialChars($text);

        return $text;
    }

    /**
     * @param       $post_field
     * @param  null $allowed_mimetypes
     * @param       $errors
     * @return bool
     * @throws \Exception
     * @throws
     */
    public function storeUpload($post_field, $allowed_mimetypes = null, &$errors)
    {
        global $xoopsUser, $xoopsDB, $xoopsModule;
        require_once SMARTPARTNER_ROOT_PATH . 'class/uploader.php';

        $config = Smartpartner\Utility::getModuleConfig();

        $id = $this->getVar('id');

        if (!isset($allowed_mimetypes)) {
            $hMime             = xoops_getModuleHandler('mimetype');
            $allowed_mimetypes = $hMime->checkMimeTypes($post_field);
            if (!$allowed_mimetypes) {
                return false;
            }
        }

        /*$maxfilesize = $config['xhelp_uploadSize'];
        $maxfilewidth = $config['xhelp_uploadWidth'];
        $maxfileheight = $config['xhelp_uploadHeight'];*/

        $maxfilesize   = $config['maximum_filesize'];
        $maxfilewidth  = 100000; //$config['maximum_image_width'];
        $maxfileheight = 100000; //$config['maximum_image_height'];

        if (!is_dir(Smartpartner\Utility::getUploadDir())) {
            //            mkdir(Smartpartner\Utility::getUploadDir(), 0757);
            if (!@mkdir(getUploadDir(), 0757) && !is_dir(Smartpartner\Utility::getUploadDir())) {
                throw new \RuntimeException("Couldn't create this directory: " . Smartpartner\Utility::getUploadDir());
            }
        }

        $uploader = new \XoopsMediaUploader(Smartpartner\Utility::getUploadDir() . '/', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);
        if ($uploader->fetchMedia($post_field)) {
            $file_title = $this->purifyText($uploader->getMediaName());
            $uploader->setTargetFileName($id . '_' . $file_title);
            if ($uploader->upload()) {
                $this->setVar('filename', $uploader->getSavedFileName());
                if ('' == $this->getVar('name')) {
                    $this->setVar('name', $this->getNameFromFilename());
                }
                $this->setVar('mimetype', $uploader->getMediaType());

                return true;
            } else {
                $errors = array_merge($errors, $uploader->getErrors(false));

                return false;
            }
        } else {
            $errors = array_merge($errors, $uploader->getErrors(false));

            return false;
        }
    }

    /**
     * @param       $allowed_mimetypes
     * @param  bool $force
     * @param  bool $doupload
     * @return bool
     * @throws \Exception
     */
    public function store(&$allowed_mimetypes, $force = true, $doupload = true)
    {
        if ($this->isNew()) {
            $errors = [];
            if ($doupload) {
                $ret = $this->storeUpload('userfile', $allowed_mimetypes, $errors);
            } else {
                $ret = true;
            }
            if (!$ret) {
                foreach ($errors as $error) {
                    $this->setErrors($error);
                }

                return false;
            }
        }

        global $fileHandler;

        return $fileHandler->insert($this, $force);
    }

    /**
     * @return mixed
     */
    public function fileid()
    {
        return $this->getVar('fileid');
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->getVar('id');
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function name($format = 'S')
    {
        return $this->getVar('name', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function description($format = 'S')
    {
        return $this->getVar('description', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function filename($format = 'S')
    {
        return $this->getVar('filename', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function mimetype($format = 'S')
    {
        return $this->getVar('mimetype', $format);
    }

    /**
     * @return mixed
     */
    public function uid()
    {
        return $this->getVar('uid');
    }

    /**
     * @param  string $dateFormat
     * @param  string $format
     * @return string
     */
    public function datesub($dateFormat = 's', $format = 'S')
    {
        return formatTimestamp($this->getVar('datesub', $format), $dateFormat);
    }

    /**
     * @return mixed
     */
    public function status()
    {
        return $this->getVar('status');
    }

    /**
     * @return mixed
     */
    public function notifypub()
    {
        return $this->getVar('notifypub');
    }

    /**
     * @return mixed
     */
    public function counter()
    {
        return $this->getVar('counter');
    }

    /**
     * @return bool
     */
    public function notLoaded()
    {
        return (0 == $this->getVar('id'));
    }

    /**
     * @return string
     */
    public function getFileUrl()
    {
        $hModule            = xoops_getHandler('module');
        $hModConfig         = xoops_getHandler('config');
        $smartPartnerModule =& $hModule->getByDirname('smartpartner');
        $smartPartnerConfig =& $hModConfig->getConfigsByCat(0, $smartPartnerModule->getVar('mid'));

        return Smartpartner\Utility::getUploadDir(false) . $this->filename();
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        $hModule            = xoops_getHandler('module');
        $hModConfig         = xoops_getHandler('config');
        $smartPartnerModule =& $hModule->getByDirname('smartpartner');
        $smartPartnerConfig =& $hModConfig->getConfigsByCat(0, $smartPartnerModule->getVar('mid'));

        return Smartpartner\Utility::getUploadDir() . $this->filename();
    }

    /**
     * @return string
     */
    public function getFileLink()
    {
        return "<a href='" . XOOPS_URL . '/modules/smartpartner/visit.php?fileid=' . $this->fileid() . "'>" . $this->name() . '</a>';
    }

    /**
     * @return string
     */
    public function getItemLink()
    {
        return "<a href='" . XOOPS_URL . '/modules/smartpartner/partner.php?id=' . $this->id() . "'>" . $this->name() . '</a>';
    }

    public function updateCounter()
    {
        $this->setVar('counter', $this->counter() + 1);
        $this->store();
    }

    /**
     * @return mixed
     */
    public function displayFlash()
    {
        if (!defined('MYTEXTSANITIZER_EXTENDED_MEDIA')) {
            require_once SMARTPARTNER_ROOT_PATH . 'include/media.textsanitizer.php';
        }
        $media_ts = MyTextSanitizerExtension::getInstance();

        return $media_ts->_displayFlash($this->getFileUrl());
    }

    /**
     * @return mixed|string
     */
    public function getNameFromFilename()
    {
        $ret     = $this->filename();
        $sep_pos = strpos($ret, '_');
        $ret     = substr($ret, $sep_pos + 1, -$sep_pos);

        return $ret;
    }
}
