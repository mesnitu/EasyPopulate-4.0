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

define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_AUTHOR_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Author Name:</b> %s - exceeds max. length: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_AUTHOR_TYPES_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Author Type Name:</b> %s - exceeds max. length: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_PUBLISHER_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Publisher Name:</b> %s - exceeds max. length: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_GENRE_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Genre Name:</b> %s - exceeds max. length: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_BINDING_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Binding Name:</b> %s - exceeds max. length: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_SERIES_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Series Name:</b> %s - exceeds max. length: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_PRINTING_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Printing Name:</b> %s - exceeds max. length: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_CONDITION_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Condition Name:</b> %s - exceeds max. length: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_IMPRINT_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Book Imprint Name:</b> %s - exceeds max. length: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_SUBTITLE_NAME_LONG', '<br /><font color="red"><b>SKIPPED! - Bookx Subtitle:</b> %s - exceeds max. length: %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_DELETED', '<br /><font color="fuchsia"><b>DELETED! - Model:</b> %s, - ISBN: %s</font>');
// Errors
define('EASYPOPULATE_4_DISPLAY_BOOKX_REPORTS_BOOKX_HEADER', '<h2>BOOKX MISSING FIELDS</h2>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_ERROR_MORE_TYPES_THAN_AUTHORS', '<br /><font color="red"><b>SKIPPED! - </b> More Author Types : %s - <strong>than Authors in: </strong> %s</font>');
define('EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_UNBALANCED_AUTHOR_TYPES_ERROR_TYPES', '<br /><font color="red"><b>SKIPPED! - Error: Unbalanced AUTHOR TYPES defined in:</b> %s - <strong>in: </strong> %s</font>');



define('BOOKX_EP_DESC','Prefix: %1$s. %2$s will be processed through the @TODO .');
define('BOOKX_AUTH_EP_DESC','Prefix: %1$s. %2$s will be processed through @TODO.');

define('EASYPOPULATE_4_BOOKX_EDIT_LINK', 'Edit');
define('EASYPOPULATE_4_BOOKX_TABLE_CAPTION', 'Review some empty or defaults fields used in the import');
define('EASYPOPULATE_4_BOOKX_TABLE_LINKS', 'Links');
define('EASYPOPULATE_4_BOOKX_TABLE_BOOK', 'Book');

define('EASY_POPULATE_4_BOOKX_DISPLAY_DEFAULT_GENRE_NAME', 'Temática base: ');
//define('EASYPOPULATE_4_CONFIG_BOOKX_DEFAULT_GENRE_NAME', 'Fallback Genre');
define('EASY_POPULATE_4_BOOKX_DEFAULT_PUBLISHER_NAME', 'Editora base');
define('EASYPOPULATE_4_DISPLAY_ENABLE_BOOKX', 'Habilitar Campos Bookx : ');

