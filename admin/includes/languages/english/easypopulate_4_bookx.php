<?php
/**
 * Import file for EP4
 * @EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 *
 * @version  0.9.9 - Still in development, make your changes in a local environment
 * @see Bookx module for ZenCart
 * @see Readme-EP4Bookx
 * @author mesnitu
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
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_ERROR_MORE_TYPES_THAN_AUTHORS', '<br /><font color="red"><b>SKIPPED! - </b> More Author Types : %s - <strong>than Authors in: </strong> %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_UNBALANCED_AUTHOR_TYPES_ERROR_TYPES', '<br /><font color="red"><b>SKIPPED! - Error: Unbalanced AUTHOR TYPES defined in:</b> %s - <strong>in: </strong> %s</font>');



define('BOOKX_EP_DESC','Prefix: %1$s. %2$s will be processed through the @TODO .');
define('BOOKX_AUTH_EP_DESC','Prefix: %1$s. %2$s will be processed through @TODO.');

define('EASYPOPULATE_4_BOOKX_EDIT_LINK', 'Editar');
define('EASYPOPULATE_4_BOOKX_TABLE_CAPTION', 'Review some empty or defaults fields used in the import');
define('EASYPOPULATE_4_BOOKX_TABLE_LINKS', 'Links');
define('EASYPOPULATE_4_BOOKX_TABLE_BOOK', 'Book');

define('EASY_POPULATE_4_BOOKX_DISPLAY_DEFAULT_GENRE_NAME', 'Temática base: ');
//define('EASYPOPULATE_4_CONFIG_BOOKX_DEFAULT_GENRE_NAME', 'Fallback Genre');
define('EASY_POPULATE_4_BOOKX_DEFAULT_PUBLISHER_NAME', 'Editora base');
define('EASYPOPULATE_4_DISPLAY_ENABLE_BOOKX', 'Habilitar Campos Bookx : ');

/*
 * @Reports - Temporary variables to define which bookx fields are to report
 * Temporary variables to define which fields are generated in the report links. As in Zencart it is required to edit the files,  * possibly there's no need to create these fields in the database, and thus interfere less with the core EP4.
 */

// $enable_bookx_subtitle        = true;
// $enable_bookx_genre_name      = true;
// $enable_bookx_publisher_name  = true;
// $enable_bookx_series_name     = true;
// $enable_bookx_imprint_name    = true;
// $enable_bookx_binding         = true;
// $enable_bookx_printing        = true;
// $enable_bookx_condition       = true;
// $enable_bookx_isbn            = true;
// $enable_bookx_size            = true;
// $enable_bookx_volume          = true;
// $enable_bookx_pages           = true;
// $enable_bookx_publishing_date = true;
// $enable_bookx_author_name     = true;
// $enable_bookx_author_type     = true;

// true - reports ; false - Doesn't Report
$report_bookx_subtitle = false;
$report_bookx_genre_name = true;
$report_bookx_publisher_name = false;
$report_bookx_series_name = true;
$report_bookx_imprint_name = false;
$report_bookx_binding = false;
$report_bookx_printing = false;
$report_bookx_condition = true;
$report_bookx_isbn = false;
$report_bookx_size = false;
$report_bookx_volume = false;
$report_bookx_pages = false;
$report_bookx_publishing_date = false;
$report_bookx_author_name = true;
$report_bookx_author_type = false;

// Default values for empty fields
$bookx_default_author_name = '';
$bookx_default_author_type = '';
$bookx_default_printing = '';
$bookx_default_binding = '';
$bookx_default_genre_name = '';
$bookx_default_publisher_name = '';
$bookx_default_series_name = '';
$bookx_default_imprint_name = '';
$bookx_default_condition = '';
