<?php

if ( !defined('IS_ADMIN_FLAG') ) {
    die('Illegal Access');
}

$ep4bookx_project = 'ep4bookx';
$ep4bookx_version = '0.9.9';
$const = get_defined_constants();

$ep4bookx_enabled = $const['EASYPOPULATE_4_CONFIG_BOOKX_DATA'];
$ep4bookx_fields_conf = $const['EASYPOPULATE_4_ENABLE_FIELDS_CONFIG_BOOKX_DATA'];
$ep4bookx_db_table = $const['TABLE_EP4BOOKX'];

$ep4bookx_module_path = DIR_WS_MODULES . $ep4bookx_project . '/';
$ep4bookx_tpl_path = $ep4bookx_module_path . 'tpl/';
$ep4bookx_layout_path = $ep4bookx_module_path . 'layouts/';

$ep4bookx_check_install = new ep4BookxVarsOverRide();
$ep4bookx_check_install->ep4BookxCheckInstall($ep4bookx_db_table);

if ( $ep4bookx_enabled == 1 ) {

    $sql = "SELECT * FROM {$const['TABLE_PRODUCT_TYPES']} WHERE type_handler = 'product_bookx' ";
    $result = $db->Execute($sql);

    if ( $result->RecordCount() !== 0 ) {
        $bookx_product_type = $result->fields['type_id'];

        include $ep4bookx_module_path . 'ep4bookx_pre_process.php';

        // Check table 
    } else {
        $messageStack->add_session(EP4BOOKX_BOOKX_NOT_FOUND ,'error');
    }
}
