<?php
/**
 *  @package EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.5f
 *  @version  0.9.9r2 - Still in development, make your changes in a local environment
 *  @see BookX Module for ZenCart https://sourceforge.net/p/zencartbookx
 *  @see Readme-EP4Bookx
 *  @author mesnitu
 *  @see https://github.com/mesnitu/EasyPopulate-4.0-BookX
 *
 */

if ( !defined('IS_ADMIN_FLAG') ) {
    die('Illegal Access');
}

define('EP4BOOKX_VERSION','0.9.9r2');

$const = get_defined_constants();

$ep4bookx_enabled = $const['EASYPOPULATE_4_CONFIG_BOOKX_DATA'];
$ep4bookx_fields_conf = $const['EASYPOPULATE_4_ENABLE_FIELDS_CONFIG_BOOKX_DATA'];
$ep4bookx_db_table = $const['TABLE_EP4BOOKX'];

$ep4bookx_module_path = EP4BOOKX_MODULE_PATH;


if ( $ep4bookx_enabled == 1 ) {

    $sql = "SELECT * FROM {$const['TABLE_PRODUCT_TYPES']} WHERE type_handler = 'product_bookx' ";
    $result = $db->Execute($sql);
    
    if ( $result->RecordCount() !== 0 ) {
		
        $bookx_product_type = $result->fields['type_id'];
		
		$ep4bookx_check_install = new ep4BookxVarsOverRide();
		$ep4bookx_check_install->ep4BookxCheckInstall($ep4bookx_db_table);
       
        include $ep4bookx_module_path . 'ep4bookx_pre_process.php';

        // Check table 
    } else {
        
        $messageStack->add_session(EP4BOOKX_BOOKX_NOT_FOUND ,'error');
    
        
    }
}


