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
    require(DIR_FS_ADMIN . DIR_WS_LANGUAGES . $_SESSION['language'] . '/easypopulate_4_bookx.php');
    
    $sql = "SELECT default_fields FROM  " . TABLE_EP4BOOKX . "";
    $result = $db->Execute($sql);
    
    if ( $result ) {

        $ep4bookx_default_cnf = json_decode($result->fields['default_fields']);
        
        $build_vars = new ep4bookx();      
        /**
         * Build the the default config. 
         * From this config, the vars and the tpl fields can be built.
         */
        $build_vars->ep4bookxBuild($ep4bookx_default_cnf, true);
       
        // Fill the array with customize layouts   
        $ep4bookx_customize_files = ep4bookx_list_layouts($ep4bookx_layout_path, '.json', false);
    
        if ( !empty($ep4bookx_customize_files) ) {
            // count the files ( will be use to refresh the page on delete the last one
            $numf = count($ep4bookx_customize_files);
            $ep4bookx_last_conf = current($ep4bookx_customize_files); // last config file       
        }
    }
    
    $ep4bookx_configuration = new ep4BookxVarsOverRide();
    $ep4bookx_configuration->ep4BookxConfiguration(TABLE_EP4BOOKX);
   
}   

if ( isset($post_action['ep4bookx_action']) ) {

    switch ( $post_action['ep4bookx_action'] ) {

        case 'delete':
            $layout_name = filter_input(INPUT_POST, 'ep4bookx_layout', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) . '.json';

            if ( $numf == 1 ) {
                @unlink($ep4bookx_layout_path . $layout_name);
                // This msg in on jquery return delete process
                // If numf == 1 it will reload the page
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
//        case 'maintanance':
//          $sql2 = "UPDATE " .CONFIGURATION. " SET configuration_value = 'true' WHERE  configuration_key  LIKE 'DOWN_FOR_MAINTENANCE' ";
//          $result2 = $db->Execute ($sql2);
//          $jmsg = 1;
//          echo $jmsg;
//          exit();          
//           break;
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
                $set_fields['setFields'][$key][] = mb_convert_encoding($value, 'HTML-ENTITIES', "UTF-8");
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
        case 'save_configuration':
   
           array_pop($post_action);
           array_shift($post_action);
            
            $data = array();
            
            foreach ( $post_action as $key => $values ) {
                switch ( $key ) {
                    case 'progress_bar':
                        $text = "EP4BOOKX_CONF_PROGRESS_BAR";

                        break;
                    case 'maintenance':
                        $text = "EP4BOOKX_CONF_MAINTENANCE_MODE";

                        break;
                    case 'optimize_table':
                        $text = "EP4BOOKX_CONF_OPTIMIZE_TABLES";

                        break;

                    default:
                        break;
                }
             $data[] = array('name'=>$key, 'value' => $values, 'text'=> $text); 
            }
         
            $sql = "SELECT configuration FROM ".TABLE_EP4BOOKX."";
            $result = $db->Execute($sql); 
            if ($result == 1) {
                $sql1 = "UPDATE ".TABLE_EP4BOOKX." SET configuration = '".json_encode($data)."'";
                $result1 = $db->Execute($sql1); 
  
            }
            zen_redirect(zen_href_link(FILENAME_EASYPOPULATE_4));
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
if ( isset($get_action['export']) && ('bookx' == $get_action['export']) || isset($post_action['export']) && ('ep4bookx_action' == $post_action['export']) ) {
    $ep4bookx_flag_export = 1; // The main purpose of ep4bookx is to deal with bookx fields. Cause categories are mandatory for new products they must be on the Bookx file. Some other fields such has manufatures , etc , are optional on export. But for the Full export , despite the name "Full", I think the Bookx fields should not be present, and leave this file to deal with whatever fields are present in there. So this flag is to prevent to attach bookx fields on the Full EP4 export. But perhaps one can understand "Full" as all the available fields. Maybe this could be also a option in config. For I'll leave the flag on.

    $layout_name = ($get_action['ep4bookxLayoutName'] ? filter_input(INPUT_GET, 'ep4bookxLayoutName', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) : filter_input(INPUT_POST, 'ep4bookxLayoutName', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES) );

    $ep4bookx_layout = $ep4bookx_layout_path . $layout_name . '.json';

    // if a layout exists, override default variabels   
    if ( file_exists($ep4bookx_layout) && isset($layout_name) && $layout_name != '' ) {
   
        $load_config = json_decode(file_get_contents($ep4bookx_layout_path . $ep4bookx_last_conf . '.json'));
     
        $build_vars = new ep4BookxVarsOverRide();
        $build_vars->ep4bookxBuild($load_config);

    } else {
        $ep4bookx_customize_files = false;
    }
}

if ( (mb_substr($post_action['import'], 0, 5)) == 'BookX' ) {
   
    $ep4bookx_flag_import = 1;
    
    if ( !empty($ep4bookx_customize_files) ) {
        $ep4bookx_load_config = json_decode(file_get_contents($ep4bookx_layout_path . $ep4bookx_last_conf . '.json'));

        $build_vars = new ep4BookxVarsOverRide();
        $build_vars->ep4bookxBuild($ep4bookx_load_config);
    }
}
