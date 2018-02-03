<?php

/*
 *  @package EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 *  @version  0.9.9 rcc2 - Still in development, make your changes in a local environment
 *  @see BookX Module for ZenCart https://sourceforge.net/p/zencartbookx
 *  @see Readme-EP4Bookx
 *  @author mesnitu 
 *  @see https://github.com/mesnitu/EasyPopulate-4.0-BookX
 *  $Id: ep4bookx_pre_process.php [UTF-8] - 7/jan/2018-13:26:33 mesnitu  $
 */

// this define paths is a temporary fix, it could be use as a constant at the observer ep4bookx. But for some reason my online server doesn't work with then
// ------------------------------------------------------------------------
define(EP4BOOKX_MODULE_PATH, DIR_WS_MODULES . 'ep4bookx/');
define(EP4BOOKX_LAYOUTS_PATH, EP4BOOKX_MODULE_PATH . 'layouts/');
define(EP4BOOKX_TPL_PATH, EP4BOOKX_MODULE_PATH . 'tpl/');

define('EP4BOOKX_BOOK_UPDATE_TABLE_SEARCH', true);
//---------------------------------------------------------------------------

//-----------------EDIT BELLOW --------------------------------------------
// In the future all this shoud go into the configuration table or zencart configuration,
// Extra functionality 

define('EP4BOOKX_MANIPULATE_IMAGES', true);
//Temporary images folder to be resized / moved
define('EP4BOOKX_IMAGES_SRC_TEMP_FOLDER', DIR_FS_CATALOG . DIR_WS_IMAGES . 'temp/');

// Add a prefix to image or leavet blank ''. Image type to look for
define('EP4BOOKX_MANIPULATE_IMAGES_PREFIX', 'prefix_');
define('EP4BOOKX_MANIPULATE_IMAGES_EXTENSION', '.jpg');

// Authors Image Folder. Just the name
define('EP4BOOKX_AUTHORS_IMAGE_FOLDER', 'authors');
define('EP4BOOKX_AUTHORS_IMAGE_FOLDER_PATH', DIR_FS_CATALOG . DIR_WS_IMAGES . EP4BOOKX_AUTHORS_IMAGE_FOLDER . '/');

// For resize images
define('EP4BOOKX_AUTHORS_IMAGE_WIDTH','400');
define('EP4BOOKX_AUTHORS_IMAGE_HEIGHT','400');

define('EP4BOOKX_BOOK_IMAGE_WIDTH','1024');
define('EP4BOOKX_BOOK_IMAGE_HEIGHT','1024');





