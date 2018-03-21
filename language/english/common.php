<?php

/**
 *
 * Module: SmartPartner
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */


$moduleDirName      = basename(dirname(dirname(__DIR__)));
$moduleDirNameUpper = strtoupper($moduleDirName);

define('CO_' . $moduleDirNameUpper . '_GDLIBSTATUS', 'GD library support: ');
define('CO_' . $moduleDirNameUpper . '_GDLIBVERSION', 'GD Library version: ');
define('CO_' . $moduleDirNameUpper . '_GDOFF', "<span style='font-weight: bold;'>Disabled</span> (No thumbnails available)");
define('CO_' . $moduleDirNameUpper . '_GDON', "<span style='font-weight: bold;'>Enabled</span> (Thumbsnails available)");
define('CO_' . $moduleDirNameUpper . '_IMAGEINFO', 'Server status');
define('CO_' . $moduleDirNameUpper . '_MAXPOSTSIZE', 'Max post size permitted (post_max_size directive in php.ini): ');
define('CO_' . $moduleDirNameUpper . '_MAXUPLOADSIZE', 'Max upload size permitted (upload_max_filesize directive in php.ini): ');
define('CO_' . $moduleDirNameUpper . '_MEMORYLIMIT', 'Memory limit (memory_limit directive in php.ini): ');
define('CO_' . $moduleDirNameUpper . '_METAVERSION', "<span style='font-weight: bold;'>Downloads meta version:</span> ");
define('CO_' . $moduleDirNameUpper . '_OFF', "<span style='font-weight: bold;'>OFF</span>");
define('CO_' . $moduleDirNameUpper . '_ON', "<span style='font-weight: bold;'>ON</span>");
define('CO_' . $moduleDirNameUpper . '_SERVERPATH', 'Server path to XOOPS root: ');
define('CO_' . $moduleDirNameUpper . '_SERVERUPLOADSTATUS', 'Server uploads status: ');
define('CO_' . $moduleDirNameUpper . '_SPHPINI', "<span style='font-weight: bold;'>Information taken from PHP ini file:</span>");
define('CO_' . $moduleDirNameUpper . '_UPLOADPATHDSC', 'Note. Upload path *MUST* contain the full server path of your upload folder.');

define('CO_' . $moduleDirNameUpper . '_PRINT', "<span style='font-weight: bold;'>Print</span>");
define('CO_' . $moduleDirNameUpper . '_PDF', "<span style='font-weight: bold;'>Create PDF</span>");


define('CO_' . $moduleDirNameUpper . '_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('CO_' . $moduleDirNameUpper . '_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('CO_' . $moduleDirNameUpper . '_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('CO_' . $moduleDirNameUpper . '_ERROR_COLUMN', 'Could not create column in database : %s');
define('CO_' . $moduleDirNameUpper . '_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('CO_' . $moduleDirNameUpper . '_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('CO_' . $moduleDirNameUpper . '_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');

define('CO_' . $moduleDirNameUpper . '_FOLDERS_DELETED_OK', 'Upload Folders have been deleted');

// Error Msgs
define('CO_' . $moduleDirNameUpper . '_ERROR_BAD_DEL_PATH', 'Could not delete %s directory');
define('CO_' . $moduleDirNameUpper . '_ERROR_BAD_REMOVE', 'Could not delete %s');
define('CO_' . $moduleDirNameUpper . '_ERROR_NO_PLUGIN', 'Could not load plugin');


//Help
define('CO_' . $moduleDirNameUpper . '_DIRNAME', basename(dirname(dirname(__DIR__))));
define('CO_' . $moduleDirNameUpper . '_HELP_HEADER', __DIR__.'/help/helpheader.tpl');
define('CO_' . $moduleDirNameUpper . '_BACK_2_ADMIN', 'Back to Administration of ');
define('CO_' . $moduleDirNameUpper . '_OVERVIEW', 'Overview');

//define('CO_' . $moduleDirNameUpper . '_HELP_DIR', __DIR__);

//help multi-page
define('CO_' . $moduleDirNameUpper . '_DISCLAIMER', 'Disclaimer');
define('CO_' . $moduleDirNameUpper . '_LICENSE', 'License');
define('CO_' . $moduleDirNameUpper . '_SUPPORT', 'Support');

//Sample Data
define('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA', 'Import Sample Data (will delete ALL current data)');
define('CO_' . $moduleDirNameUpper . '_' . 'SAMPLEDATA_SUCCESS', 'Sample Date uploaded successfully');
define('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA', 'Export Tables to YAML');
define('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON', 'Show Sample Button?');
define('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC', 'If yes, the "Add Sample Data" button will be visible to the Admin. It is Yes as a default for first installation.');
define('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA', 'Export DB Schema to YAML');
define('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_SUCCESS', 'Export DB Schema to YAML was a success');
define('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_ERROR', 'ERROR: Export of DB Schema to YAML failed');


define('_CO_SPARTNER_ACTIVE', 'Active');
define('_CO_SPARTNER_ADRESS', 'Address');
define('_CO_SPARTNER_ADRESS_DSC', 'Postal address of this partner');
define('_CO_SPARTNER_CANCEL', 'Cancel');
define('_CO_SPARTNER_CLEAR', 'Clear');
define('_CO_SPARTNER_CREATE', 'Create');
define('_CO_SPARTNER_CONTACT', 'Contact');
define('_CO_SPARTNER_PRIVATE', 'Private');
define('_CO_SPARTNER_CONTACT_NAME', 'Contact name');
define('_CO_SPARTNER_CONTACT_NAME_DSC', 'Name of the contact for this partner');
define('_CO_SPARTNER_CONTACT_EMAIL', 'Contact email');
define('_CO_SPARTNER_CONTACT_EMAIL_DSC', 'Email of the contact for this partner');
define('_CO_SPARTNER_CONTACT_PHONE', 'Contact phone');
define('_CO_SPARTNER_CONTACT_PHONE_DSC', 'Phone of the contact for this partner');
define('_CO_SPARTNER_DELETEPARTNER', 'Delete partner');
define('_CO_SPARTNER_DESCRIPTION', 'Full description');
define('_CO_SPARTNER_DESCRIPTION_DSC', "Partner's full description. This is optional.");
define('_CO_SPARTNER_EDITPARTNER', 'Edit partner');
define('_CO_SPARTNER_EMAIL', 'Email');
define('_CO_SPARTNER_FILE_UPLOAD_ERROR', 'An error occured while uploading the logo.');
define('_CO_SPARTNER_HITS', 'Hits');
define('_CO_SPARTNER_IMAGE_URL', 'Logo URL');
define('_CO_SPARTNER_IMAGE_URL_DSC', "The partner's logo can also be an url over the web. However, we recommand you to upload the logo for better resizing functionnalities. Please note that if you select a logo in the second row of this form, the 'Logo URL' won't be taken in consideration.");
define('_CO_SPARTNER_INACTIVE', 'Inactive');
define('_CO_SPARTNER_INTRO', 'Summary');
define('_CO_SPARTNER_INVENTORY', 'Partners Summary');
define('_CO_SPARTNER_LOGO', 'Logo');
define('_CO_SPARTNER_LOGO_DSC', 'Partners logo. You can select one from the list box of uplaod a new one with the next line.');
define('_CO_SPARTNER_LOGO_UPLOAD', 'Logo upload');
define('_CO_SPARTNER_LOGO_UPLOAD_DSC', 'Select an image on your computer. This image will be uploaded to the site and set as the logo for this partner. The image must be smaller than %u X %u px.');
define('_CO_SPARTNER_MODIFY', 'Modify');
define('_CO_SPARTNER_NAME', 'Name');
define('_CO_SPARTNER_NOPARTNERS', 'There is currently no partner to display.');
define('_CO_SPARTNER_NOTSET', 'Not set');
define('_CO_SPARTNER_PAGE_BEEN_SEEN', 'This page has been seen ');
define('_CO_SPARTNER_PARTNER', 'Partner');
define('_CO_SPARTNER_PARTNER_CREATE', 'Create a partner');
define('_CO_SPARTNER_PARTNER_CREATED', 'The partner has been successfully created.');
define('_CO_SPARTNER_PARTNER_CREATING', 'Creating a new partner');
define('_CO_SPARTNER_PARTNER_CREATING_DSC', 'Fill the following form in order to create a new partner. The newly created partner will be automatically displayed in the user side.');
define('_CO_SPARTNER_PARTNER_DELETE', 'Delete this partner');
define('_CO_SPARTNER_PARTNER_EDIT', 'Edit this partner');
define('_CO_SPARTNER_PARTNER_INFORMATIONS', "Partner's information");
define('_CO_SPARTNER_PARTNER_NOT_CREATED', 'An error occured. The partner was not created.');
define('_CO_SPARTNER_PARTNER_NOT_UPDATED', 'An error occured. The partner was not updated.');
define('_CO_SPARTNER_PARTNERS', 'Partners');
define('_CO_SPARTNER_PHONE', 'Phone');
define('_CO_SPARTNER_REJECTED', 'Rejected');
define('_CO_SPARTNER_STATS', 'Statistics');
define('_CO_SPARTNER_STATUS', 'Status');
define('_CO_SPARTNER_SUBMIT', 'Submit');
define('_CO_SPARTNER_SUMMARY', 'Summary');
define('_CO_SPARTNER_SUMMARY_REQ', 'Summary*');
define('_CO_SPARTNER_SUMMARY_DSC', 'Short description of the Partner. This will be displayed in the index page.');
define('_CO_SPARTNER_SUBMITTED', 'Submitted');
define('_CO_SPARTNER_TIMES', 'times');
define('_CO_SPARTNER_TITLE', "Partner's name");
define('_CO_SPARTNER_TITLE_REQ', "Partner's name*");
define('_CO_SPARTNER_TITLE_DSC', '');
define('_CO_SPARTNER_URL', 'Web site');
define('_CO_SPARTNER_URL_BEEN_VISITED', "This partner's website has been visited ");
define('_CO_SPARTNER_URL_DSC', "URL of the partner's web site.");
define('_CO_SPARTNER_WEBSITE', 'Web site');
define('_CO_SPARTNER_WEIGHT', 'Weight');
define('_CO_SPARTNER_WEIGHT_DSC', "If the 'Sort by Weight' option is turned on in the preferences, the partners will be sort by their weight in the user side index page.");
define('_CO_SPARTNER_CONTACT_EMAILPRIV', 'Email Privacy?');
define('_CO_SPARTNER_CONTACT_EMAILPRIV_DSC', "Select 'YES' to make your email address private<br>Admin will still be able to view it!");
define('_CO_SPARTNER_CONTACT_PHONEPRIV', 'Phone Privacy?');
define('_CO_SPARTNER_CONTACT_PHONEPRIV_DSC', "Select 'YES' to make your phone number private<br>Admin will still be able to view it!");
define('_CO_SPARTNER_CONTACT_ADRESSPRIV', 'Address Privacy?');
define('_CO_SPARTNER_CONTACT_ADRESSPRIV_DSC', "Select 'YES' to make your address private<br>Admin will still be able to view it!");
define('_CO_SPARTNER_STATUS_OFFLINE', 'Offline');
define('_CO_SPARTNER_STATUS_ONLINE', 'Online');
define('_CO_SPARTNER_OFFER_TITLE', 'Title');
define('_CO_SPARTNER_OFFER_TITLE_DSC', '');
define('_CO_SPARTNER_OFFER_DESC', 'Description');
define('_CO_SPARTNER_OFFER_DESC_DSC', '');
define('_CO_SPARTNER_OFFER_URL', 'Url');
define('_CO_SPARTNER_OFFER_URL_DSC', '');
define('_CO_SPARTNER_OFFER_DATESUB', 'Creation date');
define('_CO_SPARTNER_OFFER_DATESUB_DSC', '');
define('_CO_SPARTNER_OFFER_DATE_START', 'Publication date');
define('_CO_SPARTNER_OFFER_DATE_START_DSC', '');
define('_CO_SPARTNER_OFFER_DATE_END', 'Expiration date');
define('_CO_SPARTNER_OFFER_DATE_END_DSC', '');
define('_CO_SPARTNER_OFFER_STATUS', 'Status');
define('_CO_SPARTNER_OFFER_STATUS_DSC', '');
define('_CO_SPARTNER_OFFER_IMAGE', 'Image');
define('_CO_SPARTNER_OFFER_IMAGE_DSC', '');

//new features
define('_CO_SPARTNER_OFFER_PARTNER', 'Partner');
define('_CO_SPARTNER_OFFER_PARTNER_DSC', 'Partner to which the offer belongs');
define('_CO_SPARTNER_OFFERS', 'Special Offers');
define('_CO_SPARTNER_OFFER_CLICKHERE', 'Click here for more details');
define('_CO_SPARTNER_FULL_PERM_READ', 'Full view');
define('_CO_SPARTNER_FULL_PERM_READ_DSC', 'Select groups that will have full view.');
define('_CO_SPARTNER_PART_PERM_READ', 'Partial view');
define('_CO_SPARTNER_PART_PERM_READ_DSC', 'Select groups that will have partial view.');
define('_SPARTNER_MESSAGE_FILE_ERROR', 'Error: Unable to store uploaded file for the following reasons:<br>%s');
define('_SPARTNER_MESSAGE_WRONG_MIMETYPE', 'Error: filetype is not allowed. Please re-submit.');
