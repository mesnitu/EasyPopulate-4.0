<?php

/**
 *  @package EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.5f
 *  @version  0.9.9r2 - Still in development, make your changes in a local environment
 *  @see BookX Module for ZenCart https://sourceforge.net/p/zencartbookx
 *  @see Readme-EP4Bookx
 *  @author mesnitu
 *  @see https://github.com/mesnitu/EasyPopulate-4.0-BookX
 *  $Id: ep4bookx_database_names.php [UTF-8] - 02/fev/2018-18:12:33 mesnitu  $
 *
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
/**
 * Database name defines
 */
define('TABLE_EP4BOOKX', DB_PREFIX . 'ep4bookx');
