<?php

/*
 *  @package EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 *  @version  0.9.9 - Still in development, make your changes in a local environment
 *  @see BookX Module for ZenCart https://sourceforge.net/p/zencartbookx
 *  @see Readme-EP4Bookx
 *  @author mesnitu 
 *  @see https://github.com/mesnitu/EasyPopulate-4.0-BookX
 *  $Id: ep4bookx_pre_process.php [UTF-8] - 7/mar/2016-13:26:33 mesnitu  $
 */

$get_action = filter_input_array(INPUT_GET);
$post_action = filter_input_array(INPUT_POST);

if ( isset($GLOBALS['current_page']) && 'easypopulate_4.php' == $GLOBALS['current_page'] ) {

    // Load the default configuration
    $enable_ep4bookx_specials = true;
    $enable_ep4bookx_metatags = true;
    $enable_ep4bookx_subtitle = true;
    $enable_ep4bookx_genre_name = true;
    $enable_ep4bookx_publisher_name = true;
    $enable_ep4bookx_series_name = true;
    $enable_ep4bookx_imprint_name = true;
    $enable_ep4bookx_binding = true;
    $enable_ep4bookx_printing = true;
    $enable_ep4bookx_condition = true;
//$enable_ep4bookx_isbn = true;
    $enable_ep4bookx_size = true;
    $enable_ep4bookx_volume = true;
    $enable_ep4bookx_pages = true;
    $enable_ep4bookx_publishing_date = true;
    $enable_ep4bookx_author_name = true;
    $enable_ep4bookx_author_type = true;
    //$enable_ep4bookx_categories = true;
    $enable_ep4bookx_manufacturers = true;
    $enable_ep4bookx_weight = true;

// true - reports ; false - Doesn't Report
    $report_ep4bookx_subtitle = false;
    $report_ep4bookx_genre_name = true;
    $report_ep4bookx_publisher_name = false;
    $report_ep4bookx_series_name = false;
    $report_ep4bookx_imprint_name = false;
    $report_ep4bookx_binding = false;
    $report_ep4bookx_printing = false;
    $report_ep4bookx_condition = false;
    $report_ep4bookx_isbn = true;
    $report_ep4bookx_publishing_date = false;
    $report_ep4bookx_author_name = true;
    $report_ep4bookx_author_type = true;

// Default values for empty fields
    $default_ep4bookx_author_name = '';
    $default_ep4bookx_author_type = '';
    $default_ep4bookx_printing = '';
    $default_ep4bookx_binding = '';
    $default_ep4bookx_genre_name = '';
    $default_ep4bookx_publisher_name = '';
    $default_ep4bookx_imprint_name = '';
    $default_ep4bookx_condition = '';

    // Fill the array with customize layouts
    $ep4bookx_customize_files = array();
    $ep4bookx_customize_files = ep4bookx_list_layouts($ep4bookx_layout_path, '.json', false);
    // count the files ( will be use to refresh the page on delete the last one
    $numf = count($ep4bookx_customize_files);
    $ep4bookx_last_conf = current($ep4bookx_customize_files);
}

if ( isset($post_action['ep4bookx_action']) ) {

    switch ( $post_action['ep4bookx_action'] ) {

        case 'delete':
            $layout_name = filter_input(INPUT_POST, 'ep4bookx_layout', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) . '.json';
           
            if ( $numf == 1 ) {
                @unlink($ep4bookx_layout_path . $layout_name);
                // This msg in on jquery return delete process
                // If numf == 1 when deleting it will reload the page
                $jmsg = 0;
                echo $jmsg;
                exit();
            } elseif ( $numf > 1 ) {
                @unlink($ep4bookx_layout_path . $layout_name);
                // This msg in on jquery return delete process
                $jmsg = 1;
                echo $jmsg;
                exit();
            }
            break;

        case 'save_layout':

            $set_fields = array();
            $ep4bookx_layout_name = strtolower(preg_replace("/[^a-zA-Z]+/", "_", $post_action['setFields']['ep4bookx_layout']));

            array_shift($post_action);

            foreach ( $post_action['setFields'] as $key => $value ) {
                /**
                 * @todo need to check this enconding.It gets here as UTF, but wrinting á é, gets all wrong. No effect on the import, but for
                 * reading the file, it's not nice. Seens that converting again, turns out ok... not sure
                 * var_dump(iconv_get_encoding(all));
                 */
                //$set_fields['setFields'][$key] = rtrim($value);
                $set_fields['setFields'][$key] = mb_convert_encoding($value, 'HTML-ENTITIES', "UTF-8");
                //echo mb_detect_encoding($value);              
            }

            $jsondata = json_encode($set_fields, JSON_PRETTY_PRINT);

            if ( file_put_contents($ep4bookx_layout_path . 'layout_' . $ep4bookx_layout_name . '.json', $jsondata) ) {
                $ep4bookx_success = 'Data successfully saved';
                $ep4bookx_msg = 'save_layout';

                $ep4bookx_customize_files = ep4bookx_list_layouts($ep4bookx_layout_path, '.json', false);
            } else {
                $ep4bookx_msg = 'Unable to save data in ' . $ep4bookx_layout_path . 'layout_' . $ep4bookx_layout_name . '.json';
            }
            break;

        case 'read':
            echo '<pre>';
            include_once $ep4bookx_layout_path . $post_action['ep4bookx_read'] . '.json';
            echo '</pre>';
            exit();
            break;

        case 'readme':
            // If there's some file , the config fomr the last one will be use
            require_once $ep4bookx_module_path . 'libs/Parsedown.php';
            $parsedown = new Parsedown();
            $text = file_get_contents($ep4bookx_module_path . 'readme.md');
            echo Parsedown::instance()
                ->setUrlsLinked(true)
                ->text($text);
            exit();
            break;

        default:
            break;
    }
}

if ( isset($get_action) ) {
    switch ( $get_action['ep4bookx_action'] ) {

        case 'ep4bookx_fen_cnf':
            // Quick enable ep4Bookx fields config form
            if ( $ep4bookx_fields_conf == 0 ) {
                $sql = "UPDATE " . TABLE_CONFIGURATION . " SET configuration_value= 1 WHERE configuration_key = 'EASYPOPULATE_4_ENABLE_FIELDS_CONFIG_BOOKX_DATA'";
                $result = $db->Execute($sql);
                if ( $result->EOF ) {
                    $ep4bookx_fields_conf = 1;
                    $ep4bookx_msg = 'ep4bookx_fen_cnf';
                }
            }
            zen_redirect(zen_href_link(FILENAME_EASYPOPULATE_4));
            break;
        case 'ep4bookx_fdis_cnf':
            // Quick disable ep4Bookx fields config form
            if ( $ep4bookx_fields_conf == 1 ) {
                $sql = "UPDATE " . TABLE_CONFIGURATION . " SET configuration_value= 0 WHERE configuration_key = 'EASYPOPULATE_4_ENABLE_FIELDS_CONFIG_BOOKX_DATA'";
                $result = $db->Execute($sql);
                if ( $result->EOF ) {
                    $ep4bookx_fields_conf = 0;
                    $ep4bookx_msg = 'ep4bookx_fdis_cnf';
                }
            }
            zen_redirect(zen_href_link(FILENAME_EASYPOPULATE_4));
            break;
        default:
            break;
    }
}

// THis is reduntant , but one is using buttons, the other a link to export the file. 
if ( isset($get_action) && ('bookx' == $get_action['export']) || isset($post_action) && ('ep4bookx_action' == $post_action['export']) ) {

    $layout_name = ($get_action['ep4bookxLayoutName'] ? filter_input(INPUT_GET, 'ep4bookxLayoutName', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : filter_input(INPUT_POST, 'ep4bookxLayoutName', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) );

    $ep4bookx_layout = $ep4bookx_layout_path . $layout_name . '.json';

    // if a layout exists, override default variabels   
    if ( file_exists($ep4bookx_layout) && isset($layout_name) && $layout_name != '' ) {

        $load_config = get_object_vars(json_decode(file_get_contents($ep4bookx_layout)));

        $enable_ep4bookx_specials = $load_config['setFields']->ep4bookx_export_specials;
        $enable_ep4bookx_metatags = $load_config['setFields']->ep4bookx_export_metatags;
        // $enable_ep4bookx_categories = $load_config['setFields']->ep4bookx_export_categories; // No good. For new products they must be on the file
        $enable_ep4bookx_manufacturers = $load_config['setFields']->ep4bookx_export_manufacturers;
        $enable_ep4bookx_weight = $load_config['setFields']->ep4bookx_export_weight;
        $enable_ep4bookx_genre_name = $load_config['setFields']->ep4bookx_export_genre;
        $enable_ep4bookx_publisher_name = $load_config['setFields']->ep4bookx_export_publisher;
        $enable_ep4bookx_series_name = $load_config['setFields']->ep4bookx_export_series;
        $enable_ep4bookx_imprint_name = $load_config['setFields']->ep4bookx_export_imprint;
        $enable_ep4bookx_binding = $load_config['setFields']->ep4bookx_export_binding;
        $enable_ep4bookx_printing = $load_config['setFields']->ep4bookx_export_printing;
        $enable_ep4bookx_condition = $load_config['setFields']->ep4bookx_export_condition;
        $enable_ep4bookx_size = $load_config['setFields']->ep4bookx_export_size;
        $enable_ep4bookx_volume = $load_config['setFields']->ep4bookx_export_pages;
        $enable_ep4bookx_pages = $load_config['setFields']->ep4bookx_export_metatags;
        $enable_ep4bookx_publishing_date = $load_config['setFields']->ep4bookx_export_publishing_date;
        $enable_ep4bookx_author_name = $load_config['setFields']->ep4bookx_export_author;
        $enable_ep4bookx_author_type = $load_config['setFields']->ep4bookx_export_author_type;
        $enable_ep4bookx_subtitle = $load_config['setFields']->ep4bookx_export_subtitle;

        // Reports
        $report_ep4bookx_genre_name = $load_config['setFields']->report_ep4bookx_genre;
        $report_ep4bookx_binding = $load_config['setFields']->report_ep4bookx_binding;
        $report_ep4bookx_printing = $load_config['setFields']->report_ep4bookx_printing;
        $report_ep4bookx_condition = $load_config['setFields']->report_ep4bookx_condition;
        $report_ep4bookx_isbn = $load_config['setFields']->report_ep4bookx_isbn;
        $report_ep4bookx_publishing_date = $load_config['setFields']->report_ep4bookx_publishing_date;
        $report_ep4bookx_publisher_name = $load_config['setFields']->report_ep4bookx_publisher;
        $report_ep4bookx_series_name = $load_config['setFields']->report_ep4bookx_series;
        $report_ep4bookx_imprint_name = $load_config['setFields']->report_ep4bookx_imprint;
        $report_ep4bookx_author_name = $load_config['setFields']->report_ep4bookx_author;
        $report_ep4bookx_author_type = $load_config['setFields']->report_ep4bookx_author_type;

        // Default values for empty fields $default_ep4bookx_author_name
        $default_ep4bookx_author_name = $load_config['setFields']->default_ep4bookx_author_name;
        $default_ep4bookx_author_type = $load_config['setFields']->default_ep4bookx_author_type;
        $default_ep4bookx_printing = $load_config['setFields']->default_ep4bookx_printing;
        $default_ep4bookx_binding = $load_config['setFields']->default_ep4bookx_binding;
        $default_ep4bookx_genre_name = $load_config['setFields']->default_ep4bookx_genre_name;
        $default_ep4bookx_publisher_name = $load_config['setFields']->default_ep4bookx_publisher_name;
        $default_ep4bookx_imprint_name = $load_config['setFields']->default_ep4bookx_imprint_name;
        $default_ep4bookx_condition = $load_config['setFields']->default_ep4bookx_condition;
    } else {
        $ep4bookx_customize_files = false;
        // @ todo I left it here, cause on export, page should be redirect to easypopulate. 
        // No doing so, upon the creation of a file, one is exporting also, but as the page doesn't reload
        // one doesn't see that the layout has been created. 
        // But, if no customize layout is found on export, it comes to here, and it can't be reloaded here, cause it will break
        // the export. 
        //zen_redirect(zen_href_link(FILENAME_EASYPOPULATE_4)); 
    }
}

if ( (mb_substr($post_action['import'], 0, 5)) == 'BookX' ) {

    $ep4bookx_load_config = get_object_vars(json_decode(file_get_contents($ep4bookx_layout_path . $ep4bookx_last_conf . '.json')));

    $report_ep4bookx_genre_name = $ep4bookx_load_config['setFields']->report_ep4bookx_genre;
    $report_ep4bookx_binding = $ep4bookx_load_config['setFields']->report_ep4bookx_binding;
    $report_ep4bookx_printing = $ep4bookx_load_config['setFields']->report_ep4bookx_printing;
    $report_ep4bookx_condition = $ep4bookx_load_config['setFields']->report_ep4bookx_condition;
    $report_ep4bookx_isbn = $ep4bookx_load_config['setFields']->report_ep4bookx_isbn;
    $report_ep4bookx_publishing_date = $ep4bookx_load_config['setFields']->report_ep4bookx_publishing_date;
    $report_ep4bookx_publisher_name = $ep4bookx_load_config['setFields']->report_ep4bookx_publisher;
    $report_ep4bookx_series_name = $ep4bookx_load_config['setFields']->report_ep4bookx_series;
    $report_ep4bookx_imprint_name = $ep4bookx_load_config['setFields']->report_ep4bookx_imprint;
    $report_ep4bookx_author_name = $ep4bookx_load_config['setFields']->report_ep4bookx_author;
    $report_ep4bookx_author_type = $ep4bookx_load_config['setFields']->report_ep4bookx_author_type;

    $default_ep4bookx_author_name = $ep4bookx_load_config['setFields']->default_ep4bookx_author_name;
    $default_ep4bookx_author_type = $ep4bookx_load_config['setFields']->default_ep4bookx_author_type;
    $default_ep4bookx_printing = $ep4bookx_load_config['setFields']->default_ep4bookx_printing;
    $default_ep4bookx_binding = $ep4bookx_load_config['setFields']->default_ep4bookx_binding;
    $default_ep4bookx_genre_name = $ep4bookx_load_config['setFields']->default_ep4bookx_genre_name;
    $default_ep4bookx_publisher_name = $ep4bookx_load_config['setFields']->default_ep4bookx_publisher_name;
    $default_ep4bookx_imprint_name = $ep4bookx_load_config['setFields']->default_ep4bookx_imprint_name;
    $default_ep4bookx_condition = $ep4bookx_load_config['setFields']->default_ep4bookx_condition;
}
