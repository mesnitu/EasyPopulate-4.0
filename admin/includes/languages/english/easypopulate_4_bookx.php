<?php
/*
 *  @package EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 *  @version  0.9.9 - Still in development, make your changes in a local environment
 *  @see BookX Module for ZenCart https://sourceforge.net/p/zencartbookx
 *  @see Readme-EP4Bookx
 *  @author mesnitu 
 *  @see https://github.com/mesnitu/EasyPopulate-4.0-BookX
 *  $Id: easypopualte_4_bookx.php [UTF-8] - mesnitu  $
 */

define('EASYPOPULATE_4_DISPLAY_RESULT_DELETED', '<br /><font color="fuchsia"><b>DELETED! - Model:</b> %s</font> - GENRE: %s');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_AUTHOR_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Author Name:</b> %s - excede tamanho max: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_AUTHOR_TYPES_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Author Type Name:</b> %s - excede tamanho max: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_PUBLISHER_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Publisher Name:</b> %s - excede tamanho max: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_GENRE_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Genre Name:</b> %s - excede tamanho max: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_BINDING_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Binding Name:</b> %s - excede tamanho max: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_SERIES_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Series Name:</b> %s - excede tamanho max: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_PRINTING_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Printing Name:</b> %s - excede tamanho max: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_CONDITION_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Condition Name:</b> %s - excede tamanho max: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_IMPRINT_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Imprint Name:</b> %s - excede tamanho max: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_SUBTITLE_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Bookx Subtitle:</b> %s - excede tamanho max: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_DELETED', '<br /><font color="fuchsia"><b>DELETED! - Model:</b> %s, - ISBN: %s</font>');
// Errors 
define('EASYPOPULATE_4_DISPLAY_BOOKX_REPORTS_BOOKX_HEADER', '<h2>BOOKX MISSING FIELDS</h2>');


define('BOOKX_EP_DESC','Prefix: %1$s. will be processed through the EP4Bookx import.');
define('BOOKX_AUTH_EP_DESC','Prefix: %1$s. %2$s will be processed through @TODO.');

define('EASYPOPULATE_4_BOOKX_EDIT_LINK', 'Edit');
define('EASYPOPULATE_4_BOOKX_TABLE_CAPTION', 'Review some empty or defaults fields used in the import');
define('EASYPOPULATE_4_BOOKX_TABLE_LINKS', 'Links');
define('EASYPOPULATE_4_BOOKX_TABLE_BOOK', 'Book');

define('EASY_POPULATE_4_BOOKX_DISPLAY_DEFAULT_GENRE_NAME', 'Temática base: ');
define('EASY_POPULATE_4_BOOKX_DEFAULT_PUBLISHER_NAME', 'Editora base');
define('EASYPOPULATE_4_DISPLAY_ENABLE_BOOKX', 'Habilitar Campos Bookx : ');

define('EASYPOPULATE_4_DISPLAY_TITLE_BOOKX_FILES', 'EP4Bookx Import / Export');

define ('EP4BOOKX_REPORT_SUBTITLE', 'Subtitle');
define ('EP4BOOKX_ERROR_MISSING_FIELD_ISBN', 'Subtitle');

// conf
define ('EP4BOOKX_SAVE_FILE', 'Save');
define ('EP4BOOKX_SUBMIT_WAIT', 'Wait...');
define ('EP4BOOKX_EXPORT_BUTTON', 'Export');
define ('EP4BOOKX_EXPORT_CUSTOMIZE', 'Customize: ');
define ('EP4BOOKX_REQUIRED_FIELD', 'Required Field');
define ('EP4BOOKX_INVALID_FIELD', 'Invalid Field. Exceeds max lenght');
define ('EP4BOOKX_DEFAULT_FIELD_EMPTY', 'none');
define ('EP4BOOKX_DEFAULT_FIELD_LAYOUT_NAME', 'Layout Name');
define ('EP4BOOKX_REVIEW_FIELDS_ERROR', 'Review %s');
define ('EP4BOOK_QUICK_EDIT_DISABLE', 'Quick Disable');
define ('EP4BOOK_QUICK_EDIT_ENABLE', 'Quick Enable');
define ('E4BOOKX_MSG_DISABLE_FIELDS_CONFIG', 'Ep4Bookx Fields Configuration are now Disable');
define ('E4BOOKX_MSG_ENABLE_FIELDS_CONFIG', 'Ep4Bookx Fields Configuration are now Enable');
define ('E4BOOKX_MSG_LAYOUT_SAVED', 'Data successfully save');
//define ('EP4BOOKX_MSG_FIELDS_HOWTO', '');

//Fields 
define ('EP4BOOKX_FIELD_SPECIALS', 'Specials');
define ('EP4BOOKX_FIELD_METATAGS', 'MetaTags');
define ('EP4BOOKX_FIELD_SUBTITLE', 'Subtitle');
define ('EP4BOOKX_FIELD_GENRE_NAME', 'Genres');
define ('EP4BOOKX_FIELD_PUBLISHER_NAME', 'Publishers');
define ('EP4BOOKX_FIELD_SERIES_NAME', 'Series ');
define ('EP4BOOKX_FIELD_IMPRINT_NAME', 'Imprint');
define ('EP4BOOKX_FIELD_BINDING', 'Binding');
define ('EP4BOOKX_FIELD_PRINTING', 'Printing');
define ('EP4BOOKX_FIELD_CONDITION', 'Condition');
define ('EP4BOOKX_FIELD_SIZE', 'Size');
define ('EP4BOOKX_FIELD_VOLUME', 'Volume');
define ('EP4BOOKX_FIELD_PAGES', 'Pages');
define ('EP4BOOKX_FIELD_PUBLISHING_DATE', 'Publishing Date');
define ('EP4BOOKX_FIELD_AUTHOR_NAME', 'Author Name');
define ('EP4BOOKX_FIELD_AUTHOR_TYPE', 'Author Type');
define ('EP4BOOKX_FIELD_CATEGORIES', 'Categories');
define ('EP4BOOKX_FIELD_MANUFACTURERS', 'Manufacturers');
define ('EP4BOOKX_FIELD_WEIGHT', 'Weight');
define ('EP4BOOKX_FIELD_ISBN', 'ISBN');
//define ('E4BOOKX_MSG_LAYOUT_SAVED', 'Data successfully save');
define ('EP4BOOKX_LEGEND_REPORTS', 'Generate Report for');
define ('EP4BOOKX_LEGEND_FIELDS', 'Export this fields');
define ('EP4BOOKX_LEGEND_DEFAULT_NAMES', 'Default Values for');
define ('EP4BOOKX_LEGEND_LAYOUT_NAME', 'Layout Name');
