<?php

/**
 * @EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 * @version  0.9.0 - Still in development, make your changes in a local environment
 * @see Bookx module for ZenCart
 * @see Readme-EP4Bookx
 *
 * @author mesnitu
 */
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

// Not implemented
//function download_remote_file($file_url, $save_to) {
//    $content = file_get_contents($file_url);
//    file_put_contents($save_to, $content);
//}

/**
 * @todo  Remove bookx from bookx_extra_description
 */
function ep_4_remove_product_bookx($product_model) {
    global $db, $ep_debug_logging, $ep_debug_logging_all, $ep_stack_sql_error;
    $project = PROJECT_VERSION_MAJOR . '.' . PROJECT_VERSION_MINOR;
    $ep_uses_mysqli = ((PROJECT_VERSION_MAJOR > '1' || PROJECT_VERSION_MINOR >= '5.3') ? true : false);
    $sql = "SELECT products_id FROM " . TABLE_PRODUCTS . " WHERE products_model = '" . zen_db_input($product_model) . "'";
    $products = $db->Execute($sql);
    //$bookx_id = $products->fields['products_id'];
    // Bye bye
    bookx_delete_product($products->fields['products_id']);

    if (($ep_uses_mysqli ? mysqli_errno($db->link) : mysql_errno())) {
        $ep_stack_sql_error = true;
        if ($ep_debug_logging == true) {
            $string = "MySQL error " . ($ep_uses_mysqli ? mysqli_errno($db->link) : mysql_errno()) . ": " . ($ep_uses_mysqli ? mysqli_error($db->link) : mysql_error()) . "\nWhen executing:\n$sql\n";
            write_debug_log($string);
        }
    } elseif ($ep_debug_logging_all == true) {
        $string = "MySQL PASSED\nWhen executing:\n$sql\n";
        write_debug_log($string);
    }
    while (!$products->EOF) {
        zen_remove_product($products->fields['products_id']);
        $products->MoveNext();
    }
    return;
}

function pr($var, $title = null) {
    echo '<pre style="background:#ccc;">';
    if ($title):
        echo '<b>' . $title . ':</b> ';
    endif;
    print_r($var);
    echo '</pre>';
}

/**
 * ep4bookx_list_layouts 
 * 
 * List and orders directory files by filectime (inode change time of file )
 * in a multidimensional array, so that the last created layout is on top of the list. 
 *  
 * @param string $path  - the directory path
 * @param string $ext   - file extension
 * @param boolean $index   - false for a one-based array
 * @return array
 * 
 * @todo On windows if the files are moved, or just copied again to the folder,  using filectime
 *  wont catch them again. Changed to filemtime.
 */
function ep4bookx_list_layouts($path, $ext, $index = true) {
   
    $dir = opendir($path);
    $list = array();
    while ($file = readdir($dir)) {
         //clearstatcache(true, $file);
        if (strstr($file, $ext, false)) {
            $ctime = filemtime($path . $file);
            $list[$ctime] = strstr($file, '.', true);
        }
    }
    closedir($dir);   
    krsort($list);
    
    if ($index == true) {
        $list = array_combine(array_keys($list), array_values($list));
        $arr2 = array();
        foreach ($list as $key => $value) {
            $arr2[] = array("id" => $key, 'text' => $value);
        }
        return $arr2;
    } else {
        return $list;
    }
}

//function list_layouts_by_date($path, $ext) {
//    $dir = opendir($path);
//    $list = array();
//    while ($file = readdir($dir)) {
//        if (strstr($file, $ext, false)) {
//            $ctime = filectime($path . $file);
//            $list[$ctime] = strstr($file, '.', true);
//        }
//    }
//    closedir($dir);
//    krsort($list);
//    if (!empty($list)) {
//        return $list;
//    }
//}
//
//// Order files to get the latest first
//function index_list_layouts_by_date(&$arr) {
//    global $ep4bookx_module_path;   
//   
//    $arr = array_combine(array_keys(list_layouts_by_date($ep4bookx_module_path, '.json')),array_values(list_layouts_by_date($ep4bookx_module_path, '.json')) );
//
//    $arr2 = array();
//    foreach ($arr as $key => $value) {
//        $arr2[] = array("id" => $key, 'text' => $value);
//    }
//    return $arr2;
//}




