<?php

/**
 * Autoloader array for EP4 BookX notification functionality. Makes sure 
 * that features available for using Bookx and EP4 are 
 * instantiated at the right point of the Zen Cart initsystem.
 * 
 * @package     EP4 bookX notifications
 * @copyright   Copyright 2003-2007 Zen Cart Development Team
 * @copyright   Portions Copyright 2003 osCommerce
 * @link        http://www.zen-cart.com/
 * @license     http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version     $Id: config.ep4bookx.php xxxx 2015-10-19 20:31:10Z mc12345678 $
 */

//if (!defined('IS_ADMIN_FLAG')) {
//	die('Illegal Access');
//} 

if ((mb_substr($_POST['import'], 0, 5)) == 'BookX' || ($_GET['export'] == 'bookx'))  {
	$autoLoadConfig[0][] = array('autoType' => 'require',
			'loadFile' => DIR_WS_MODULES.'easypopulate_4_bookx_config.php');
}
 $autoLoadConfig[0][] = array(
	'autoType' => 'class',
	'loadFile' => 'observers/class.ep4bookx.php',
	'classPath'=> DIR_WS_CLASSES
	);
 $autoLoadConfig[200][] = array(
	'autoType' => 'classInstantiate',
	'className' => 'ep4bookx',
	'objectName' => 'ep4bookx'
	); 
