<?php
/**
 * @EP4BookX
 * [Deletes all bookx produts with a status = 10, using a bookx function]
 * @see   [product_bookx_functions.php]
 * @param  [model] $product_model
 * @return [model]                [description]
 *
 * @todo  Remove bookx from bookx_extra_description
 */

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}
/**
 * @EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 * @version  0.9.0 - Still in development, make your changes in a local environment
 * @see Bookx module for ZenCart
 * @see Readme-EP4Bookx
 *
 * @author mesnitu
 * @todo  export with support for languages
 */
function download_remote_file($file_url, $save_to)
{
    $content = file_get_contents($file_url);
    file_put_contents($save_to, $content);
}

function ep_4_remove_product_bookx($product_model) {
    global $db, $ep_debug_logging, $ep_debug_logging_all, $ep_stack_sql_error;
    $project = PROJECT_VERSION_MAJOR.'.'.PROJECT_VERSION_MINOR;
    $ep_uses_mysqli = ((PROJECT_VERSION_MAJOR > '1' || PROJECT_VERSION_MINOR >= '5.3') ? true : false);
    $sql = "SELECT products_id FROM ".TABLE_PRODUCTS." WHERE products_model = '".zen_db_input($product_model)."'";
    $products = $db->Execute($sql);
    //$bookx_id = $products->fields['products_id'];
    // Bye bye
    bookx_delete_product($products->fields['products_id']);

    if (($ep_uses_mysqli ? mysqli_errno($db->link) : mysql_errno())) {
        $ep_stack_sql_error = true;
        if ($ep_debug_logging == true) {
            $string = "MySQL error ".($ep_uses_mysqli ? mysqli_errno($db->link) : mysql_errno()).": ".($ep_uses_mysqli ? mysqli_error($db->link) : mysql_error())."\nWhen executing:\n$sql\n";
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

/**
 * [ep_4_bookx_delete_bookx_specific_product_entries description]
 * @param  [type]  $product_id    [description]
 * @param  boolean $delete_linked [description]
 * @return [type]                 [description]
 */
// Probably to delete - not in use
function ep_4_bookx_delete_bookx_specific_product_entries($product_id = null, $delete_linked = true) {
    global $db;

    if (null != $product_id) {
        $db->Execute('DELETE FROM ' . TABLE_PRODUCT_BOOKX_EXTRA . '
                      WHERE products_id = "' . (int)$product_id . '"');

        $db->Execute('DELETE FROM ' . TABLE_PRODUCT_BOOKX_GENRES_TO_PRODUCTS . '
                      WHERE products_id = "' . (int)$product_id . '"');

        $db->Execute('DELETE FROM ' . TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS . '
                      WHERE products_id = "' . (int)$product_id . '"');
    }
}

function pr ($var,$title = null) {
    echo '<pre style="background:#ccc;">';
    if ($title):
        echo '<b>' . $title. ':</b> ';
    endif;
    print_r($var);
    echo '</pre>';
}

if (!function_exists('array_merge_recursive2')) {
    function array_merge_recursive2($paArray1, $paArray2)
    {
       if (!is_array($paArray1) or !is_array($paArray2)) {
            return $paArray2;
       }
       foreach ($paArray2 AS $sKey2 => $sValue2) {
            $paArray1[$sKey2] = array_merge_recursive2(@$paArray1[$sKey2], $sValue2);
       }
       return $paArray1;
    }
}
