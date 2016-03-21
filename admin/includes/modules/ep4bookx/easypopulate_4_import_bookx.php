<?php

/**
 * Import file for EP4 
 * @EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 *
 * @version  0.9.9 - Still in development, make your changes in a local environment
 * @see Bookx module for ZenCart
 * @see Readme-EP4Bookx
 * @author mesnitu
 * @todo improve the querys 
 *
 * $filelayout[] = 'v_bookx_subtitle';      
 * $filelayout[] = 'v_bookx_genre_name';
 * $filelayout[] = 'v_bookx_publisher_name'; // Publisher Name, no lang ID       
 * $filelayout[] = 'v_bookx_series_name'; // Series name, has Lang ID              
 * $filelayout[] = 'v_bookx_imprint'; // 
 * $filelayout[] = 'v_bookx_binding';
 * $filelayout[] = 'v_bookx_printing';
 * $filelayout[] = 'v_bookx_condition';
 * $filelayout[] = 'v_bookx_isbn';
 * $filelayout[] = 'v_bookx_size';
 * $filelayout[] = 'v_bookx_volume';
 * $filelayout[] = 'v_bookx_pages';
 * $filelayout[] = 'v_bookx_publishing_date';        
 * $filelayout[] = 'v_bookx_author_name';
 */
// Edit link to books with missing fields
$edit_link = "<a href=" . zen_href_link('product_bookx.php', 'cPath=' . zen_get_product_path($v_products_id) . '&product_type=' . $bookx_product_type . '&pID=' . $v_products_id . '&action=new_product') . ">" . EASYPOPULATE_4_BOOKX_EDIT_LINK . "</a>";
//pr($bookx_default_author_name.' AUTHOR NOME IMPORT');
pr($filelayout);

//

if ( isset($filelayout['v_bookx_isbn']) ) { // Only proceed if ISBN filelayou is present. He holds the bookx_extra table querys.
    pr($ep4bookx_extra_sqlwhere, "set_update_query");
//::: BOOKX GENRE
    if ( isset($filelayout['v_bookx_genre_name_' . $epdlanguage_id]) ) {

        $updated_id = array(); // Get the updated id's.   
        $inserted_id = array(); // Get the inserted id's 
        $flag = array();
        // pr($default_ep4bookx_genre_name, "genre anek");
        foreach ( $langcode as $lang ) { // Get the names, check the lengh
            $l_id = $lang['id'];
            if ( !empty($default_ep4bookx_genre_name) && empty($items[$filelayout['v_bookx_genre_name_' . $l_id]]) ) {
                $items[$filelayout['v_bookx_genre_name_' . $l_id]] = $default_ep4bookx_genre_name;
            }
            $genres_names_array[$l_id] = mb_split('\x5e', $items[$filelayout['v_bookx_genre_name_' . $l_id]]); // names to array 
        }

        foreach ( $genres_names_array as $key => $genres_lang_array ) {
            foreach ( $genres_lang_array as $names ) {
                ((mb_strlen($names) <= $bookx_genre_name_max_len) ? $flag[] = '1' : $flag[] = '0');
            }
        }
        //pr($genres_names_array);
        if ( (!in_array('0', $flag) && ($items[$filelayout['v_bookx_genre_name_' . $epdlanguage_id]] != '') ) ) {

            for ( $i = 0; $i < count($genres_names_array[$epdlanguage_id]); $i++ ) { // go for the default language  
                $sql = "SELECT bookx_genre_id AS genreID, genre_description, languages_id  FROM " . TABLE_PRODUCT_BOOKX_GENRES_DESCRIPTION . " WHERE genre_description = :genre_name: AND languages_id = :languages_id:";
                $sql = $db->bindVars($sql, ':genre_name:', $genres_names_array[$epdlanguage_id][$i], 'string');
                $sql = $db->bindVars($sql, ':languages_id:', $epdlanguage_id, 'integer');
                $result_genre = $db->Execute($sql);

                $v_genre_id = $result_genre->fields['genreID'];

                if ( $result_genre->RecordCount() == 0 ) {

                    $query = "INSERT INTO " . TABLE_PRODUCT_BOOKX_GENRES . " (genre_sort_order, date_added, last_modified)
						VALUES (':sort:', :CURRENT_TIMESTAMP:, :CURRENT_TIMESTAMP:)";
                    $query = $db->bindVars($query, ':sort:', 0, 'integer');
                    $query = $db->bindVars($query, ':CURRENT_TIMESTAMP:', CURRENT_TIMESTAMP, 'noquotestring');
                    $result = $db->Execute($query);

                    $v_genre_id = $db->Insert_ID();

                    foreach ( $langcode as $lang ) {
                        $l_id = $lang['id'];
                        $query2 = "INSERT INTO " . TABLE_PRODUCT_BOOKX_GENRES_DESCRIPTION . " (bookx_genre_id, languages_id, genre_description,genre_image)
						VALUES (:v_genre_id:, :languages_id:, :genre_description:, :genre_image:)";
                        $query2 = $db->bindVars($query2, ':v_genre_id:', $v_genre_id, 'integer');
                        $query2 = $db->bindVars($query2, ':languages_id:', $l_id, 'integer');
                        $query2 = $db->bindVars($query2, ':genre_description:', $genres_names_array[$l_id][$i], 'string');
                        $query2 = $db->bindVars($query2, ':genre_image:', null, 'string');
                        $result2 = $db->Execute($query2);
                    }
                } else {
                    foreach ( $langcode as $lang ) {
                        $l_id = $lang['id'];
                        $query = "UPDATE " . TABLE_PRODUCT_BOOKX_GENRES_DESCRIPTION . " SET genre_description = :genre_name: WHERE bookx_genre_id = :v_genre_id: AND languages_id = :languages_id:";
                        $query = $db->bindVars($query, ':genre_name:', $genres_names_array[$l_id][$i], 'string');
                        $query = $db->bindVars($query, ':v_genre_id:', $v_genre_id, 'integer');
                        $query = $db->bindVars($query, ':languages_id:', $l_id, 'integer');
                        $result = $db->Execute($query);
                    }
                } // ends first else 
                // Genres To Products
                $genres_to_products = "SELECT * FROM " . TABLE_PRODUCT_BOOKX_GENRES_TO_PRODUCTS . " WHERE (bookx_genre_id = :v_genre_id:) AND (products_id = :v_products_id:)";
                $genres_to_products = $db->bindVars($genres_to_products, ':v_genre_id:', $v_genre_id, 'integer');
                $genres_to_products = $db->bindVars($genres_to_products, ':v_products_id:', $v_products_id, 'integer');
                $result_genres_to_products = $db->Execute($genres_to_products);

                if ( $result_genres_to_products->RecordCount() > 0 ) {
                    $v_genre_id = $result_genres_to_products->fields['bookx_genre_id'];
                    $updated_id[] = $result_genres_to_products->fields['bookx_genre_id'];
                    $query = "UPDATE " . TABLE_PRODUCT_BOOKX_GENRES_TO_PRODUCTS . " SET bookx_genre_id = :v_genre_id: WHERE products_id = :v_products_id: and primary_id =:primary_id:";
                    $query = $db->bindVars($query, ':v_genre_id:', $v_genre_id, 'integer');
                    $query = $db->bindVars($query, ':v_products_id:', $v_products_id, 'integer');
                    $query = $db->bindVars($query, ':primary_id:', $row_genre_to_products['primary_id'], 'integer');
                    $result = $db->Execute($query);
                } else {
                    $query = "INSERT INTO " . TABLE_PRODUCT_BOOKX_GENRES_TO_PRODUCTS . " (bookx_genre_id, products_id) VALUES (:v_genre_id:, :v_products_id:)";
                    $query = $db->bindVars($query, ':v_genre_id:', $v_genre_id, 'integer');
                    $query = $db->bindVars($query, ':v_products_id:', $v_products_id, 'integer');
                    $inserted_id[] = $v_genre_id;
                    $result = $db->Execute($query);
                }
            } // ends for 
            $temp_del = array_merge($updated_id, $inserted_id); // Merge all the id's in the loop
            $q = ""; // empty string for the query
            foreach ( $temp_del as $value ) {
                $q .= " AND bookx_genre_id != '" . $value . "'"; // construct the query with the id's
            }
            $delete = "DELETE FROM " . TABLE_PRODUCT_BOOKX_GENRES_TO_PRODUCTS . " WHERE products_id= :v_products_id: " . $q . "";
            $delete = $db->bindVars($delete, ':v_products_id:', $v_products_id, 'integer');
            // $delete = $db->bindVars($delete, ':q:', $q, 'integer'); @todo
            $result = $db->Execute($delete);
            unset($delete, $q, $temp_del, $updated_id, $inserted_id);
        } else {

            if ( $report_ep4bookx_genre_name == true ) { // check and warn of empty publisher name(still updates)
                $ep4bookx_reports[BOX_CATALOG_PRODUCT_BOOKX_GENRES][] = sprintf(substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 60)) . ' - ' . $edit_link;
            }
            foreach ( $langcode as $lang ) {
                $l_id = $lang['id'];
                if ( mb_strlen($items[$filelayout['v_bookx_genre_name_' . $l_id]]) > $bookx_genres_name_max_len ) {
                    $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_SERIES_NAME_LONG, $items[$filelayout['v_bookx_genre_name_' . $l_id]], $bookx_genres_name_max_len);
                    $ep_error_count++;
                }
            }
            $v_genre_id = 0;
        }
    } // ends genres
//::: Publisher Name
    if ( isset($filelayout['v_bookx_publisher_name']) ) {

        if ( !empty($default_ep4bookx_publisher_name) && empty($items[$filelayout['v_bookx_publisher_name']]) ) {
            $v_bookx_publisher_name = $default_ep4bookx_publisher_name;
        } else {
            $v_bookx_publisher_name = $items[$filelayout['v_bookx_publisher_name']];
        }

        if ( isset($v_bookx_publisher_name) && ($v_bookx_publisher_name != '') && (mb_strlen($v_bookx_publisher_name) <= $bookx_publisher_name_max_len) ) {

            $sql = "SELECT bookx_publisher_id AS publisherID FROM " . TABLE_PRODUCT_BOOKX_PUBLISHERS . " WHERE publisher_name = :v_bookx_publisher_name: LIMIT 1";
            $sql = $db->bindVars($sql, ':v_bookx_publisher_name:', $v_bookx_publisher_name, 'string');
            $result = $db->Execute($sql);

            if ( $result->RecordCount() > 0 ) {
                $v_publisher_id = $result->fields['publisherID']; // this id goes into the product_bookx_extra table

                $sql = "UPDATE " . TABLE_PRODUCT_BOOKX_PUBLISHERS . " SET 
						publisher_name = :v_bookx_publisher_name:,
						last_modified = :CURRENT_TIMESTAMP:
						WHERE bookx_publisher_id = :v_publisher_id:";
                $sql = $db->bindVars($sql, ':v_bookx_publisher_name:', $v_bookx_publisher_name, 'string');
                $sql = $db->bindVars($sql, ':CURRENT_TIMESTAMP:', CURRENT_TIMESTAMP, 'noquotestring');
                $sql = $db->bindVars($sql, ':v_publisher_id:', $v_publisher_id, 'integer');
                $result = $db->Execute($sql);
            } else {

                $sql = "INSERT INTO " . TABLE_PRODUCT_BOOKX_PUBLISHERS . " (publisher_name, publisher_image, publisher_sort_order, date_added, last_modified)
							VALUES (:v_bookx_publisher_name:, :publisher_image:, :sort:, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
                $sql = $db->bindVars($sql, ':v_bookx_publisher_name:', $v_bookx_publisher_name, 'string');
                $sql = $db->bindVars($sql, ':publisher_image:', null, 'string');
                $sql = $db->bindVars($sql, ':sort:', 0, 'integer');
                $sql = $db->bindVars($sql, ':CURRENT_TIMESTAMP:', CURRENT_TIMESTAMP, 'noquotestring');
                $result = $db->Execute($sql);

                $v_publisher_id = $db->Insert_ID(); // id is auto_increment

                if ( $result ) { // Publisher Description needs langueges ID
                    foreach ( $langcode as $lang ) {
                        $l_id = $lang['id'];
                        $sql2 = "INSERT INTO " . TABLE_PRODUCT_BOOKX_PUBLISHERS_DESCRIPTION . " (bookx_publisher_id, languages_id, publisher_url, publisher_description) VALUES (:v_publisher_id:, :languages_id:, :publisher_url:,:publisher_description:)";
                        $sql2 = $db->bindVars($sql2, ':v_publisher_id:', $v_publisher_id, 'integer');
                        $sql2 = $db->bindVars($sql2, ':languages_id:', $l_id, 'integer');
                        $sql2 = $db->bindVars($sql2, ':publisher_url:', null, 'string');
                        $sql2 = $db->bindVars($sql2, ':publisher_description:', null, 'string');
                        $result2 = $db->Execute($sql2);
                    }
                }
            }
        } else { // $v_bookx_publisher_name length violation
            if ( $v_bookx_publisher_name == '' && $report_ep4bookx_publisher_name == true ) { // check and warn of empty publisher name(still updates)
                $ep4bookx_reports[BOX_CATALOG_PRODUCT_BOOKX_PUBLISHERS][] = sprintf(substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 60)) . ' - ' . $edit_link;
            }
            if ( mb_strlen($v_bookx_publisher_name) > $bookx_pubisher_name_max_len ) {
                $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_PUBLISHER_NAME_LONG, $v_bookx_publisher_name, $bookx_publisher_name_max_len);
                $ep_error_count++;
            }
            $v_publisher_id = 0;
        }
    } // eof Publisher Name
// Series Names 

    if ( isset($filelayout['v_bookx_series_name_' . $epdlanguage_id]) ) {
        $flag = 0;
        // check names for every language 
        foreach ( $langcode as $lang ) {
            $l_id = $lang['id'];
            ((mb_strlen($items[$filelayout['v_bookx_series_name_' . $l_id]]) <= $bookx_series_name_max_len) ? $flag = 1 : $flag = 0);
        }

        if ( ($flag == 1) && $items[$filelayout['v_bookx_series_name_' . $epdlanguage_id]] != '' ) { // no errors found, import 
            // The last inserted ID needs to be out of the loop. I guess querying one language is enough, and then loop on all others 
            $sql = "SELECT bookx_series_id AS seriesID FROM " . TABLE_PRODUCT_BOOKX_SERIES_DESCRIPTION . " WHERE series_name = :v_bookx_series_name: AND languages_id = :languages_id: LIMIT 1";
            $sql = $db->bindVars($sql, ':v_bookx_series_name:', $items[$filelayout['v_bookx_series_name_' . $epdlanguage_id]], 'string');
            $sql = $db->bindVars($sql, ':languages_id:', $epdlanguage_id, 'integer');
            $result = $db->Execute($sql);

            $v_series_id = $result->fields['seriesID']; // Goes to bookx_extra 

            if ( $result->RecordCount() == 0 ) {
                $query = "INSERT INTO " . TABLE_PRODUCT_BOOKX_SERIES . " (series_sort_order, date_added, last_modified) VALUES (:sort:, :CURRENT_TIMESTAMP:, :CURRENT_TIMESTAMP:)";
                $query = $db->bindVars($query, ':sort:', 0, 'integer');
                $query = $db->bindVars($query, ':CURRENT_TIMESTAMP:', CURRENT_TIMESTAMP, 'noquotestring');
                $result = $db->Execute($query);

                $v_series_id = $db->Insert_ID(); // id is auto_increment

                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $query2 = "INSERT INTO " . TABLE_PRODUCT_BOOKX_SERIES_DESCRIPTION . " (bookx_series_id, languages_id, series_image,series_name,series_description) VALUES (:v_series_id:, :languages_id:, :series_image:, :v_bookx_series_name:, :series_description:)";
                    $query2 = $db->bindVars($query2, ':v_series_id:', $v_series_id, 'integer');
                    $query2 = $db->bindVars($query2, ':languages_id:', $l_id, 'integer');
                    $query2 = $db->bindVars($query2, ':series_image:', null, 'string');
                    $query2 = $db->bindVars($query2, ':v_bookx_series_name:', $items[$filelayout['v_bookx_series_name_' . $l_id]], 'string');
                    $query2 = $db->bindVars($query2, ':series_description:', null, 'string');
                    $result2 = $db->Execute($query2);
                }
            } else {
                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $query = "UPDATE " . TABLE_PRODUCT_BOOKX_SERIES_DESCRIPTION . " SET languages_id = :languages_id:, series_image = :series_image:, series_name = :v_bookx_series_name:, series_description =:series_description: WHERE languages_id = :languages_id: AND bookx_series_id = :v_series_id:";
                    $query = $db->bindVars($query, ':languages_id:', $l_id, 'integer');
                    $query = $db->bindVars($query, ':series_image:', null, 'string');
                    $query = $db->bindVars($query, ':v_bookx_series_name:', $items[$filelayout['v_bookx_series_name_' . $l_id]], 'string');
                    $query = $db->bindVars($query, ':series_description:', null, 'string');
                    $query = $db->bindVars($query, ':v_series_id:', $v_series_id, 'string');
                    $result = $db->Execute($query);
                }
            }
        } else { // Empty series file fields 	
            if ( $v_bookx_series_name == '' && $report_ep4bookx_series_name == true ) {
                $ep4bookx_reports[BOX_CATALOG_PRODUCT_BOOKX_SERIES][] = sprintf(substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 60)) . ' - ' . $edit_link;
            }
            foreach ( $langcode as $lang ) {
                $l_id = $lang['id'];
                if ( mb_strlen($items[$filelayout['v_bookx_series_name_' . $l_id]]) > $bookx_series_name_max_len ) {
                    $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_SERIES_NAME_LONG, $items[$filelayout['v_bookx_series_name_' . $l_id]], $bookx_series_name_max_len);
                    $ep_error_count++;
                }
            }
            $v_series_id = 0;
        }
    } // eof series Name
//die();
//:::: Binding Cover type
    if ( isset($filelayout['v_bookx_binding_' . $epdlanguage_id]) ) {
        $flag = 0;

        foreach ( $langcode as $lang ) {
            $l_id = $lang['id'];
            if ( !empty($default_ep4bookx_binding) && empty($items[$filelayout['v_bookx_binding_' . $l_id]]) ) {
                $v_bookx_binding = $default_ep4bookx_binding;
                $items[$filelayout['v_bookx_binding_' . $l_id]] = $default_ep4bookx_binding;
            }
            ((mb_strlen($items[$filelayout['v_bookx_binding_' . $l_id]]) <= $bookx_binding_name_max_len) ? $flag = 1 : $flag = 0);
        } //ends foreach

        if ( ($flag == 1) && ($items[$filelayout['v_bookx_binding_' . $epdlanguage_id]]) != '' ) {
            //@todo improve all this querys. A lot of updates just for 3 or four types of book bindings 
            $sql = "SELECT bookx_binding_id as bindingID, binding_description  FROM " . TABLE_PRODUCT_BOOKX_BINDING_DESCRIPTION . " WHERE binding_description = :v_bookx_binding: AND languages_id = :languages_id: LIMIT 1";
            $sql = $db->bindVars($sql, ':v_bookx_binding:', $items[$filelayout['v_bookx_binding_' . $epdlanguage_id]], 'string');
            $sql = $db->bindVars($sql, ':languages_id:', $epdlanguage_id, 'integer');
            $result = $db->Execute($sql);

            $v_binding_id = $result->fields['bindingID']; // Goes to bookx_extra
            $v_binding_description = $result->fields['binding_description'];

            if ( $result->RecordCount() == 0 ) {
                $query = "INSERT INTO " . TABLE_PRODUCT_BOOKX_BINDING . " (binding_sort_order) VALUES (:sort:)";
                $query = $db->bindVars($query, ':sort:', 0, 'integer');
                $result = $db->Execute($query);

                $v_binding_id = $db->Insert_ID(); // id is auto_increment

                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $query2 = "INSERT INTO " . TABLE_PRODUCT_BOOKX_BINDING_DESCRIPTION . " (bookx_binding_id, languages_id, binding_description) VALUES (:v_binding_id:, :languages_id:, :v_bookx_binding:)";
                    $query2 = $db->bindVars($query2, ':v_binding_id:', $v_binding_id, 'integer');
                    $query2 = $db->bindVars($query2, ':languages_id:', $l_id, 'integer');
                    $query2 = $db->bindVars($query2, ':v_bookx_binding:', $items[$filelayout['v_bookx_binding_' . $l_id]], 'string');
                    $result2 = $db->Execute($query2);
                }
            } else {
                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $query = "UPDATE " . TABLE_PRODUCT_BOOKX_BINDING_DESCRIPTION . " SET languages_id = :languages_id:, binding_description = :v_bookx_binding: WHERE bookx_binding_id = :v_binding_id: AND languages_id = :languages_id:";
                    $query = $db->bindVars($query, ':languages_id:', $l_id, 'integer');
                    $query = $db->bindVars($query, ':v_bookx_binding:', $items[$filelayout['v_bookx_binding_' . $l_id]], 'string');
                    $query = $db->bindVars($query, ':v_binding_id:', $v_binding_id, 'integer');
                    $result = $db->Execute($query);
                }
            }
        } else { // Empty binding file fields 				
            if ( $v_bookx_binding == '' && $report_ep4bookx_binding == true ) {
                $ep4bookx_reports[BOX_CATALOG_PRODUCT_BOOKX_BINDING][] = sprintf(substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 60)) . ' - ' . $edit_link;
            }
            if ( mb_strlen($v_bookx_binding) > $bookx_binding_name_max_len ) {
                $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_BINDING_NAME_LONG, $v_bookx_binding, $bookx_binding_name_max_len);
                $ep_error_count++;
            }
            $v_binding_id = 0;
        }
    } // eof binding cover 
//:::: Printing 
    if ( isset($filelayout['v_bookx_printing_' . $epdlanguage_id]) ) {

        $flag = 0;
        foreach ( $langcode as $lang ) {
            $l_id = $lang['id'];
            if ( !empty($default_ep4bookx_printing) && empty($items[$filelayout['v_bookx_printing_' . $l_id]]) ) {
                $v_bookx_printing = $default_ep4bookx_printing;
                $items[$filelayout['v_bookx_printing_' . $l_id]] = $default_ep4bookx_printing;
            }
            ((mb_strlen($v_bookx_printing) <= $bookx_printing_name_max_len) ? $flag = 1 : $flag = 0);
        }

        if ( ($flag == 1) && ($items[$filelayout['v_bookx_printing_' . $epdlanguage_id]] != '') ) {

            $sql = "SELECT bookx_printing_id AS printingID FROM " . TABLE_PRODUCT_BOOKX_PRINTING_DESCRIPTION . " WHERE printing_description = :v_bookx_printing: AND languages_id = :languages_id: LIMIT 1";
            $sql = $db->bindVars($sql, ':v_bookx_printing:', $items[$filelayout['v_bookx_printing_' . $epdlanguage_id]], 'string');
            $sql = $db->bindVars($sql, ':languages_id:', $epdlanguage_id, 'integer');
            $result = $db->Execute($sql);

            if ( $result->RecordCount() > 0 ) {

                $v_printing_id = $result->fields['printingID']; // Goes to bookx_extra
                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $query = "UPDATE " . TABLE_PRODUCT_BOOKX_PRINTING_DESCRIPTION . " SET languages_id = :languages_id:, printing_description = :v_bookx_printing: WHERE bookx_printing_id = :v_printing_id: AND languages_id = :languages_id: ";
                    $query = $db->bindVars($query, ':languages_id:', $l_id, 'integer');
                    $query = $db->bindVars($query, ':v_bookx_printing:', $items[$filelayout['v_bookx_printing_' . $l_id]], 'string');
                    $query = $db->bindVars($query, ':v_printing_id:', $v_printing_id, 'integer');
                    $result = ep_4_query($query);
                }
            } else {
                $query = "INSERT INTO " . TABLE_PRODUCT_BOOKX_PRINTING . " (printing_sort_order) VALUES (:sort:)";
                $query = $db->bindVars($query, ':sort:', 0, 'integer');
                $result = $db->Execute($query);

                $v_printing_id = $db->Insert_ID(); // id is auto_increment
                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $query2 = "INSERT INTO " . TABLE_PRODUCT_BOOKX_PRINTING_DESCRIPTION . " (bookx_printing_id, languages_id, printing_description) VALUES (:v_printing_id:, :languages_id:,:v_bookx_printing:)";
                    $query2 = $db->bindVars($query2, ':v_printing_id:', $v_printing_id, 'integer');
                    $query2 = $db->bindVars($query2, ':languages_id:', $l_id, 'integer');
                    $query2 = $db->bindVars($query2, ':v_bookx_printing:', $items[$filelayout['v_bookx_printing_' . $l_id]], 'string');
                    $result2 = $db->Execute($query2);
                }
            }
        } else { // Empty printing file fields 		
            if ( $v_bookx_printing == '' && $report_ep4bookx_printing == true ) {
                $ep4bookx_reports[BOX_CATALOG_PRODUCT_BOOKX_PRINTING][] = sprintf(substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 60)) . ' - ' . $edit_link;
            }
            if ( mb_strlen($v_bookx_printing) > $bookx_printing_name_max_len ) {
                $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_PRINTING_NAME_LONG, $v_bookx_printing, $bookx_printing_name_max_len);
                $ep_error_count++;
            }
            $v_printing_id = 0;
        }
    } // ends printing type
//
//:::: Book Condition 
    if ( isset($filelayout['v_bookx_condition_' . $epdlanguage_id]) ) {

        $flag = 0;
        foreach ( $langcode as $lang ) {
            $l_id = $lang['id'];
            if ( !empty($default_ep4bookx_condition) && empty($items[$filelayout['v_bookx_condition_' . $l_id]]) ) {
                $v_bookx_printing = $default_ep4bookx_condition;
                $items[$filelayout['v_bookx_condition_' . $l_id]] = $default_ep4bookx_condition;
            }
            ((mb_strlen($items[$filelayout['v_bookx_condition_' . $l_id]]) <= $bookx_printing_name_max_len) ? $flag = 1 : $flag = 0);
        }

        if ( ($flag == 1) && ($items[$filelayout['v_bookx_condition_' . $epdlanguage_id]] != '') ) {

            $sql = "SELECT bookx_condition_id AS conditionID FROM " . TABLE_PRODUCT_BOOKX_CONDITIONS_DESCRIPTION . " WHERE condition_description = :v_bookx_condition: AND languages_id = :languages_id: LIMIT 1";
            $sql = $db->bindVars($sql, ':v_bookx_condition:', $items[$filelayout['v_bookx_condition_' . $epdlanguage_id]], 'string');
            $sql = $db->bindVars($sql, ':languages_id:', $epdlanguage_id, 'integer');
            $result = $db->Execute($sql);

            if ( $result->RecordCount() > 0 ) {

                $v_condition_id = $result->fields['conditionID']; // Goes to bookx_extra	
                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $query = "UPDATE " . TABLE_PRODUCT_BOOKX_CONDITIONS_DESCRIPTION . " SET languages_id = :languages_id:, condition_description = :v_bookx_condition: WHERE bookx_condition_id = :v_condition_id: AND languages_id = :languages_id:";
                    $query = $db->bindVars($query, ':languages_id:', $l_id, 'integer');
                    $query = $db->bindVars($query, ':v_bookx_condition:', $items[$filelayout['v_bookx_condition_' . $l_id]], 'string');
                    $query = $db->bindVars($query, ':v_condition_id:', $v_condition_id, 'integer');
                    $result = $db->Execute($query);
                }
            } else {
                $query = "INSERT INTO " . TABLE_PRODUCT_BOOKX_CONDITIONS . " (condition_sort_order) VALUES (:sort:)";
                $query = $db->bindVars($query, ':sort:', 0, 'integer');
                $result = $db->Execute($query);

                $v_condition_id = $db->Insert_ID(); // id is auto_increment
                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $query2 = "INSERT INTO " . TABLE_PRODUCT_BOOKX_CONDITIONS_DESCRIPTION . " (bookx_condition_id, languages_id, condition_description) VALUES (:v_condition_id:, :languages_id:, :v_bookx_condition:)";
                    $query2 = $db->bindVars($query2, ':v_condition_id:', $v_condition_id, 'integer');
                    $query2 = $db->bindVars($query2, ':languages_id:', $l_id, 'integer');
                    $query2 = $db->bindVars($query2, ':v_bookx_condition:', $items[$filelayout['v_bookx_condition_' . $l_id]], 'string');
                    $result2 = $db->Execute($query2);
                }
            }
        } else { // Empty condition file fields 		
            if ( $v_bookx_condition == '' && $report_ep4bookx_condition == true ) {
                $ep4bookx_reports[BOX_CATALOG_PRODUCT_BOOKX_CONDITIONS][] = sprintf(substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 60)) . ' - ' . $edit_link;
            }
            if ( mb_strlen($v_bookx_condition) > $bookx_condition_name_max_len ) {
                $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_CONDITION_NAME_LONG, $v_bookx_condition, $bookx_condition_name_max_len);
                $ep_error_count++;
            }
            $v_condition_id = 0;
        }
    } // ends bookx condition
//:::: Book Imprint 
    if ( isset($filelayout['v_bookx_imprint_name']) ) {

        if ( !empty($default_ep4bookx_imprint_name) && empty($items[$filelayout['v_bookx_imprint_name']]) ) {
            $v_bookx_imprint_name = $default_ep4bookx_imprint_name;
        }
        if ( isset($v_bookx_imprint_name) && ($v_bookx_imprint_name != '') && (mb_strlen($v_bookx_imprint_name) <= $bookx_imprint_name_max_len) ) {
            $sql = "SELECT bookx_imprint_id AS imprintID FROM " . TABLE_PRODUCT_BOOKX_IMPRINTS . " WHERE imprint_name = :v_bookx_imprint_name: LIMIT 1";
            $sql = $db->bindVars($sql, ':v_bookx_imprint_name:', $v_bookx_imprint_name, 'string');
            $result = $db->Execute($sql);

            if ( $result->RecordCount() > 0 ) {
                $v_imprint_id = $result->fields['imprintID']; // this id goes into the product_bookx_extra table
                $query = "UPDATE " . TABLE_PRODUCT_BOOKX_IMPRINTS . " SET 
						imprint_name = :v_bookx_imprint_name:,
						last_modified = :CURRENT_TIMESTAMP:
						WHERE bookx_imprint_id = :v_imprint_id: LIMIT 1";
                $query = $db->bindVars($query, ':v_bookx_imprint_name:', $v_bookx_imprint_name, 'string');
                $query = $db->bindVars($query, ':CURRENT_TIMESTAMP:', CURRENT_TIMESTAMP, 'noquotestring');
                $query = $db->bindVars($query, ':v_imprint_id:', $v_imprint_id, 'integer');
                if ( $result ) {
                    //	zen_record_admin_activity('Updated imprints  ' . (int)$v_imprint_id . ' via EP4.', 'info');
                }
            } else {
                $query = "INSERT INTO " . TABLE_PRODUCT_BOOKX_IMPRINTS . " (imprint_name, imprint_image, imprint_sort_order, date_added, last_modified) VALUES (:v_bookx_imprint_name:, :imprint_image:, :sort:, :CURRENT_TIMESTAMP:, :CURRENT_TIMESTAMP:)";
                $query = $db->bindVars($query, ':v_bookx_imprint_name:', $v_bookx_imprint_name, 'string');
                $query = $db->bindVars($query, ':imprint_image:', null, 'string');
                $query = $db->bindVars($query, ':sort:', 0, 'integer');
                $query = $db->bindVars($query, ':CURRENT_TIMESTAMP:', CURRENT_TIMESTAMP, 'noquotestring');
                $result = $db->Execute($query);

                $v_imprint_id = $db->Insert_ID(); // id is auto_increment

                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $query2 = "INSERT INTO " . TABLE_PRODUCT_BOOKX_IMPRINTS_DESCRIPTION . " (bookx_imprint_id, languages_id, imprint_description) VALUES (:v_imprint_id:, :languages_id:, :imprint_description:) ";
                    $query2 = $db->bindVars($query2, ':v_imprint_id:', $v_imprint_id, 'integer');
                    $query2 = $db->bindVars($query2, ':languages_id:', $l_id, 'integer');
                    $query2 = $db->bindVars($query2, ':imprint_description:', null, 'string');
                    $result2 = $db->Execute($query2);
                }
            }
        } else { //length violation
            if ( $v_bookx_imprint_name == '' && $report_bookx_imprint_name == true ) { // check and warn of empty imprint name(still updates)
                $ep4bookx_reports[BOX_CATALOG_PRODUCT_BOOKX_IMPRINTS][] = sprintf(substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 60)) . ' - ' . $edit_link;
            }
            if ( mb_strlen($v_bookx_imprint_name) > $bookx_imprint_name_max_len ) {
                $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_IMPRINTS_NAME_LONG, $v_bookx_imprint_name, $bookx_imprint_name_max_len);
                $ep_error_count++;
            }
            $v_imprint_id = 0;
        }
    } // ends imprint
// New approach on authors and authors types
// Either way, they have to work together
    if ( isset($filelayout['v_bookx_author_type_' . $epdlanguage_id]) ) {
        $bookx_default_author_type = 'HHHH';
        $author_types_array = array();
        $author_types_inserted_id = array();
        $author_types_inserted_ids = array(); // for testetin
        $flag = array();
        foreach ( $langcode as $lang ) {
            $l_id = $lang['id'];
            if ( !empty($bookx_default_author_type && (ep_4_curly_quotes($items[$filelayout['v_bookx_author_type_' . $epdlanguage_id]] == ''))) ) {
                $items[$filelayout['v_bookx_author_type_' . $l_id]] = $bookx_default_author_type;
            }

            $author_types_array[$l_id] = mb_split('\x5e', ep_4_curly_quotes($items[$filelayout['v_bookx_author_type_' . $l_id]]));
            // Check the lenght names
            for ( $ck_lengh = 0; $ck_lengh < (count($author_types_array[$l_id])); $ck_lengh++ ) {

                ((mb_strlen($author_types_array[$l_id][$ck_lengh]) <= $bookx_author_types_name_max_len) ? $flag[] = 1 : $flag[] = 0);
                // This makes the array lang with the same number of index
                for ( $at = (count($author_types_array[$l_id])); $at < (count($author_types_array[$epdlanguage_id])); $at++ ) {
                    // add remaining types to array. 
                    // This is not placing the default type in all languages in those are it's empty. Only where the count is greater
                    $author_types_array[$l_id][] = '';
                }
            }
        }
  
        if ( !in_array('0', $flag) ) { // Arrays $l_id ok, lenght names ok
            // Count the authors if field is present, to make the array with the same number of entrys 
            // This is used when more authors than types are found. It fills the types array with the default type ( if enable )  
            if ( isset($filelayout['v_bookx_author_name']) ) {
                $count_authors = count(mb_split('\x5e', $items[$filelayout['v_bookx_author_name']]));
                // if there's more than one author, and one of them as a empty field, but the default type in not empty
                // It really makes no sense to use the author types wiithou the authors, but if that happens even by mistake, 
                // it proccesses the author types. 
                if ( $count_authors > 1 ) {
                    for ( $i = count($author_types_array[$epdlanguage_id]); $i < $count_authors; $i++ ) { // fill the array with the default type
                        foreach ( $langcode as $lang ) {
                            $l_id = $lang['id'];
                            $author_types_array[$l_id][] = $bookx_default_author_type;
                        }
                    }
                }
            }

            $count_types = count($author_types_array[$epdlanguage_id]);

            for ( $q = 0; $q < $count_types; $q++ ) {

                if ( $author_types_array[$epdlanguage_id][$q] != '' ) {

                    $sql_author_type_id = "SELECT bookx_author_type_id AS author_typeID, type_description FROM " . TABLE_PRODUCT_BOOKX_AUTHOR_TYPES_DESCRIPTION . " WHERE type_description =:v_bookx_author_type: AND languages_id = :languages_id: LIMIT 1";
                    $sql_author_type_id = $db->bindVars($sql_author_type_id, ':v_bookx_author_type:', $author_types_array[$epdlanguage_id][$q], 'string');
                    $sql_author_type_id = $db->bindVars($sql_author_type_id, ':bookx_author_type_id:', $v_author_type_id, 'integer');
                    $sql_author_type_id = $db->bindVars($sql_author_type_id, ':languages_id:', $epdlanguage_id, 'integer');
                    $result_author_type_id = $db->Execute($sql_author_type_id);

                    $v_author_type_id = $result_author_type_id->fields['author_typeID'];
                   
                    $author_types_inserted_id['updated'][] = $v_author_type_id;
                    
                    if ( ($result_author_type_id->RecordCount() > 0 ) ) { // update
                         $author_types_inserted_ids[] = $v_author_type_id;
                        foreach ( $langcode as $lang ) {
                            $l_id = $lang['id'];
                            // if ( $result_author_type_id->fields['type_description'] !== $author_types_array[$l_id][$q] ) {
                            $sql_update = "UPDATE " . TABLE_PRODUCT_BOOKX_AUTHOR_TYPES_DESCRIPTION . " SET languages_id = :languages_id:, type_description = :v_bookx_author_type: WHERE bookx_author_type_id =:v_bookx_author_type_id: AND languages_id = :languages_id:";
                            $sql_update = $db->bindVars($sql_update, ':languages_id:', $l_id, 'integer');
                            $sql_update = $db->bindVars($sql_update, ':v_bookx_author_type_id:', $v_author_type_id, 'integer');
                            $sql_update = $db->bindVars($sql_update, ':v_bookx_author_type:', $author_types_array[$l_id][$q], 'string');
                            $result = $db->Execute($sql_update);
                            
                        }
                    } else { // insert 
                        $sql_author_type_id = "INSERT INTO " . TABLE_PRODUCT_BOOKX_AUTHOR_TYPES . " (type_sort_order) VALUES (:sort:)";
                        $sql_author_type_id = $db->bindVars($sql_author_type_id, ':sort:', 0, 'integer');
                        $result = $db->Execute($sql_author_type_id);

                        $v_author_type_id = $db->Insert_ID(); // id is auto_increment
                        $author_types_inserted_id['inserted'][] = $v_author_type_id;
                        $author_types_inserted_ids[] = $v_author_type_id;
                        foreach ( $langcode as $lang ) {
                            $l_id = $lang['id'];
                            $sql_author_type_name = "INSERT INTO " . TABLE_PRODUCT_BOOKX_AUTHOR_TYPES_DESCRIPTION . " (bookx_author_type_id, languages_id, type_description, type_image) VALUES (:v_author_type_id:, :languages_id:,:v_bookx_author_type:,  :type_image:)";
                            $sql_author_type_name = $db->bindVars($sql_author_type_name, ':v_author_type_id:', $v_author_type_id, 'integer');
                            $sql_author_type_name = $db->bindVars($sql_author_type_name, ':languages_id:', $l_id, 'integer');
                            $sql_author_type_name = $db->bindVars($sql_author_type_name, ':v_bookx_author_type:', $author_types_array[$l_id][$q], 'string');
                            $sql_author_type_name = $db->bindVars($sql_author_type_name, ':type_image:', null, 'string');
                            $result2 = $db->Execute($sql_author_type_name);
                            
                        }
                    }
                } // ends if not empty
            } //ends for
            //
//         $sql_author_type_id = "SELECT bookx_author_type_id AS author_typeID, type_description FROM " . TABLE_PRODUCT_BOOKX_AUTHOR_TYPES_DESCRIPTION . " WHERE type_description =:v_bookx_author_type: AND languages_id = :languages_id: LIMIT 1";
//      $sql_author_type_id = $db->bindVars($sql_author_type_id, ':v_bookx_author_type:', $author_type_array[$epdlanguage_id], 'string');
//      $sql_author_type_id = $db->bindVars($sql_author_type_id, ':bookx_author_type_id:', $v_author_type_id, 'integer');
//      $sql_author_type_id = $db->bindVars($sql_author_type_id, ':languages_id:', $epdlanguage_id, 'integer');
//      $result_author_type_id = $db->Execute($sql_author_type_id);
//
//      $v_author_type_id = $result_author_type_id->fields['author_typeID'];


            //pr($author_types_inserted_id);
        } else {
            $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_AUTHOR_NAME_LONG, $author_types_array[$l_id][$ck_lengh], $bookx_author_name_max_len);
            $ep_error_count++;
        }
    } else {
        
    }

// Author names
/**
 * @mesnitu The way I did this is as follow: If the authors type field is present, it gets updated and saves those id's in a array
 * Upon the authors fields, it's going to check if that types id array has something. If so, updates the types ID. If not, it doesn't remove the authors
 * types IF they already exist in database. 
 * This could be different: if there's no types fields, it would set to 0 all the authors type. 
 * I think ( for now ) , that this is probably more save, since no one's asking to delete. 
 */
    if ( isset($filelayout['v_bookx_author_name']) ) {
        $flag = array();     
        // First check if a default author name is used and the field is empty
        if ( !empty($default_ep4bookx_author_name) && empty(ep_4_curly_quotes($items[$filelayout['v_bookx_author_name']])) ) {
            $items[$filelayout['v_bookx_author_name']] = ep_4_curly_quotes($default_ep4bookx_author_name);
        }
        $authors_array = mb_split('\x5e', ep_4_curly_quotes($items[$filelayout['v_bookx_author_name']]));
        //If there's something , Check the authors names lengh
        if ( !empty(ep_4_curly_quotes($items[$filelayout['v_bookx_author_name']])) ) {
            foreach ( $authors_array as $authors_names ) {
                
                ((mb_strlen($authors_names) <= $bookx_author_name_max_len) ? $flag[] = 1 : $flag[] = 0);
                
            }
        } 
      
        if ( (!in_array('0', $flag) ) && $authors_array) {
            
        $authors_updated_id = array(); // Get the updated id's, to latter delete all others ID not presente in the book
        $authors_inserted_id = array(); // Get the inserted id's. This two arrays will merge latter. 
        
        foreach ( $authors_array as $key => $v_bookx_author_name ) {
                $v_author_id = 0; // start with 0
                // @mesnitu : The Omeyocan of types:
                // The author can have a default type, BUT in the bookx product page he can have another type without loosing is default type.
                // It's quite logic, cause a author could be a writer most of the time, but on one specific book a illustrator.
                // This is quite strange in terms of how to work with the csv file. Doesn't make much sense to have another field just
                // for the default types and other for type_id of that book. 
                // I'm not inserting the default_type , just authors type that is selected in bookx products edit. So it's 0 
                $v_author_default_type = 0; 
                $sql_author_id = "SELECT bookx_author_id AS authorID, author_name FROM " . TABLE_PRODUCT_BOOKX_AUTHORS . " WHERE author_name = :v_bookx_author_name:";
                $sql_author_id = $db->bindVars($sql_author_id, ':v_bookx_author_name:', $v_bookx_author_name, 'string');
                $result_author_id = $db->Execute($sql_author_id);

                $v_author_id = $result_author_id->fields['authorID'];               
                $author_name = $result_author_id->fields['author_name'];

                if ( $result_author_id->RecordCount() > 0 ) {
                     
                    if ($author_name !== $v_bookx_author_name ) { // update if fnot's equal
                        
                         $query = "UPDATE " . TABLE_PRODUCT_BOOKX_AUTHORS . " SET
      author_name = :v_bookx_author_name:, author_default_type = :v_author_default_type:,
      last_modified = :CURRENT_TIMESTAMP: WHERE
      bookx_author_id = :v_author_id:";
                    $query = $db->bindVars($query, ':v_bookx_author_name:', $v_bookx_author_name, 'string');
                    $query = $db->bindVars($query, ':v_author_default_type:', $v_author_default_type, 'integer');
                    $query = $db->bindVars($query, ':CURRENT_TIMESTAMP:', CURRENT_TIMESTAMP, 'noquotestring');
                    $query = $db->bindVars($query, ':v_author_id:', $v_author_id, 'integer');
                    $result = $db->Execute($query);                   
                    }
                  
                } else { //insert
                    
                    $query = "INSERT INTO " . TABLE_PRODUCT_BOOKX_AUTHORS . " (author_name, author_image, author_image_copyright, author_default_type, author_sort_order, author_url, date_added, last_modified)
      VALUES (:v_bookx_author_name:, :author_image:, :author_image_copyright:, :v_author_default_type:, :sort:, :author_url:, :CURRENT_TIMESTAMP:, :CURRENT_TIMESTAMP:)";
                    $query = $db->bindVars($query, ':v_bookx_author_name:', $v_bookx_author_name, 'string');
                    $query = $db->bindVars($query, ':author_image:', null, 'string');
                    $query = $db->bindVars($query, ':author_image_copyright:', null, 'string');
                    $query = $db->bindVars($query, ':v_author_default_type:', $v_author_default_type, 'integer');
                    $query = $db->bindVars($query, ':sort:', 0, 'integer');
                    $query = $db->bindVars($query, ':author_url:', null, 'string');
                    $query = $db->bindVars($query, ':CURRENT_TIMESTAMP:', CURRENT_TIMESTAMP, 'noquotestring');
                    $result = $db->Execute($query);
                   
                    $v_author_id = $db->Insert_ID(); // id is auto_incremented

                    foreach ( $langcode as $lang ) {
                        $l_id = $lang['id'];

                        $query2 = "INSERT INTO " . TABLE_PRODUCT_BOOKX_AUTHORS_DESCRIPTION . " (bookx_author_id, languages_id, author_description) VALUES (:v_author_id:, :languages_id:, null)";
                        $query2 = $db->bindVars($query2, ':v_author_id:', $v_author_id, 'integer');
                        $query2 = $db->bindVars($query2, ':languages_id:', $l_id, 'integer');
                        $query2 = $db->bindVars($query2, ':author_description:', '', 'string');
                        $result2 = $db->Execute($query2);                     
                    }                           
                } //ends else authors names
                               
                // Author to Products
                $sql_author_to_product = "SELECT * FROM " . TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS . " WHERE (products_id = :v_products_id:) AND (bookx_author_id = :v_author_id:)";
                $sql_author_to_product = $db->bindVars($sql_author_to_product, ':v_products_id:', $v_products_id, 'integer');
                $sql_author_to_product = $db->bindVars($sql_author_to_product, ':v_author_id:', $v_author_id, 'integer');
                $result_author_to_product = $db->Execute($sql_author_to_product);
                             
                $v_author_type_id = $result_author_to_product->fields['bookx_author_type_id'];
                
                if ( $result_author_to_product->RecordCount() == 0 ) { // insert
                    // Check if there were some insertd types ids
                    if ( $author_types_inserted_ids ) { 
                        $v_author_type_id = $author_types_inserted_ids[$key]; 
                    }
                    $query = "INSERT INTO " . TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS . " (bookx_author_id,products_id, bookx_author_type_id) VALUES (:v_author_id:, :v_products_id:, :v_author_type_id:)";
                    $query = $db->bindVars($query, ':v_author_id:', $v_author_id, 'integer');
                    $query = $db->bindVars($query, ':v_products_id:', $v_products_id, 'integer');
                    $query = $db->bindVars($query, ':v_author_type_id:', $v_author_type_id, 'integer');
                    $result = $db->Execute($query);
                  
                    $authors_inserted_id[] = $v_author_id; // Goes to array
                    
                } else {
                    
                    $v_author_id = $result_author_to_product->fields['bookx_author_id'];
                    $primary_id = $result_author_to_product->fields['primary_id'];
                    $authors_updated_id[] = $result_author_to_product->fields['bookx_author_id']; // Goes to array
                    
                    if ( $author_types_inserted_ids) {
                        $v_author_type_id = $author_types_inserted_ids[$key];                  
                    }

                    $query = "UPDATE " . TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS . " SET bookx_author_id = :v_author_id:, bookx_author_type_id = :v_author_type_id: WHERE products_id = :v_products_id: and primary_id= :primary_id:";
                    $query = $db->bindVars($query, ':v_author_id:', $v_author_id, 'integer');
                    $query = $db->bindVars($query, ':v_author_type_id:', $v_author_type_id, 'integer');
                    $query = $db->bindVars($query, ':v_products_id:', $v_products_id, 'integer');
                    $query = $db->bindVars($query, ':primary_id:', $primary_id, 'integer');
                    $result2 = $db->Execute($query);                  
                }
            } // ends foreach
            
            // Now delete whatever ID not present in the book     
            $temp_del = array_merge($authors_updated_id, $authors_inserted_id); // Merge all the id's in the loop
            $q = ""; // empty string for the query
            foreach ( $temp_del as $key => $value ) {
                $q .= " AND bookx_author_id != '" . $value . "'"; // construct the query with the id's
            }
         
            if($q !='') {
                $delete = "DELETE FROM " . TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS . " WHERE products_id='" . $v_products_id . "' " . $q . "";
            $delete = $db->bindVars($delete, ':v_products_id:', $v_products_id, 'integer');
            $result = $db->Execute($delete);    
            }
            
            unset($delete, $q, $temp_del, $updated_id, $inserted_id);
        } else {
            
             $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_AUTHOR_NAME_LONG, $authors_names, $bookx_author_name_max_len);
                    $ep_error_count++;
        }
     if ( $v_bookx_author_name == '' && $report_ep4bookx_author_name == true ) { 
      $ep4bookx_reports[BOX_CATALOG_PRODUCT_BOOKX_AUTHORS][] = sprintf(substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 60)) . ' - ' . $edit_link;
      }
       
    } // ends authors
    
    
    
    /**
     * Author names and Author types
     * 
     * @abstract This query was made before the configurable fields. I'll try to arrange it to fit this new way. 
     * Actually there's 2 places where the author type can be set in the author's tables. And if the types fields is no set in customize layout,
     * if a author already has a type, it will be overwrite to 0.  
     * In the Author's table, one can set the default type the author will be associated. 
     * On Authors to products table, one can set the authors type, but it's not his default type
     * The author's type table just gathers the types.
     * A bit confusing on how it's the best way to work this.
     */
    /*
      if ( isset($filelayout['v_bookx_author_name']) ) {

      // First check if a default author name is used and the field is empty
      if ( !empty($default_ep4bookx_author_name) && empty(ep_4_curly_quotes($items[$filelayout['v_bookx_author_name']])) ) {
      $items[$filelayout['v_bookx_author_name']] = ep_4_curly_quotes($default_ep4bookx_author_name);
      }
      // make the Authors array
      $authors_array = mb_split('\x5e', ep_4_curly_quotes($items[$filelayout['v_bookx_author_name']]));

      //If there's something , Check the authors names lengh
      if ( !empty(ep_4_curly_quotes($items[$filelayout['v_bookx_author_name']])) ) {
      foreach ( $authors_array as $authors_names ) {
      $flag_ok = 0;
      ((mb_strlen($authors_names) <= $bookx_author_name_max_len) ? $flag_ok = 1 : $flag_ok = 0);
      if ( $flag_ok == 0 ) {
      $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_AUTHOR_NAME_LONG, $authors_names, $bookx_author_name_max_len);
      $ep_error_count++;
      break;
      }
      }
      } // ends !empty($items[$filelayout['v_bookx_author_name']]))
      if ( $flag_ok == 1 ) { // Authors ok and not empty, check the author type
      // If there's a default auhtor type
      if (isset($filelayout['v_bookx_author_type_' .$epdlanguage_id])) {
      foreach ( $langcode as $lang ) {
      $l_id = $lang['id'];
      if ( !empty($bookx_default_author_type && empty(ep_4_curly_quotes($items[$filelayout['v_bookx_author_type_' . $l_id]]))) ) {

      $items[$filelayout['v_bookx_author_type_' . $l_id]] = $bookx_default_author_type;
      }
      }
      }
      // theres some author type
      // if (!empty(ep_4_curly_quotes($items[$filelayout['v_bookx_author_type_'.$epdlanguage_id]]))) {

      foreach ( $langcode as $lang ) {
      $l_id = $lang['id'];

      $author_types_array[$l_id] = mb_split('\x5e', ep_4_curly_quotes($items[$filelayout['v_bookx_author_type_' . $l_id]])); // types to array
      // Check the names lengh
      for ( $ck_lengh = 0; $ck_lengh < (count($author_types_array[$l_id])); $ck_lengh++ ) {
      $flag_ok = 0;
      ((mb_strlen($author_types_array[$l_id][$ck_lengh]) <= $bookx_author_types_name_max_len) ? $flag_ok = 1 : $flag_ok = 0);

      if ( $flag_ok == 0 ) { // Not ok, skypped
      $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_AUTHOR_NAME_LONG, $author_types_array[$l_id][$ck_lengh], $bookx_author_name_max_len);
      $ep_error_count++;
      break;
      } else {
      // If a default type is used, and the types default lang has more values than other languages, make them with the same records.
      // This works with a author default type
      if ( (count($author_types_array[$epdlanguage_id])) > (count($author_types_array[$l_id])) ) {
      // This makes possible to have more authors than author type, if a author default type is used
      // ie: authorA^authorB^authorC => writer
      // and not wrinting authorA^authorB^authorC => writer^writer^writer
      for ( $at = (count($author_types_array[$l_id])); $at < (count($author_types_array[$epdlanguage_id])); $at++ ) {
      $author_types_array[$l_id][] = $bookx_default_author_type; // add remaining type to array
      } // ends for
      } elseif ( (count($author_types_array[$epdlanguage_id])) <> (count($author_types_array[$l_id])) ) {

      // If for some reason the lang records are not equal -> error
      $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_UNBALANCED_AUTHOR_TYPES_ERROR_TYPES . $items[$filelayout['v_bookx_author_type_' . $l_id]], $v_products_name[$epdlanguage_id]);
      $ep_error_count++;
      break; // skip to next record
      } else {
      // Check the author type names lengh and flag it
      }
      } //ends else name types ok
      } // ends for $ck_lengh
      //  add the remaing empty types
      if ( (count($author_types_array[$l_id])) > (count($authors_array)) ) {

      $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_ERROR_MORE_TYPES_THAN_AUTHORS, $items[$filelayout['v_bookx_author_type_' . $l_id]], $v_products_name[$epdlanguage_id]);
      $ep_error_count++;
      break;
      } else {
      for ( $bat = (count($author_types_array[$l_id])); $bat < (count($authors_array)); $bat++ ) {

      $author_types_array[$l_id][] = $bookx_default_author_type; // add remaining type to array
      } // ends for
      }
      } // ends foreach langcode
      // combine the arrays. probably a better way to do this, or even do it upon making the authors and types arrays. Anyway, the ideia is to loop the querys on this:
      // ( [Auto A] => Array ( [1] => Ilustrador [4] => Illustrator
      //   [Auto B] => Array ( [1] => Escritor [4] => Writer )
      //   }
      $combine_array = array();
      foreach ( $authors_array as $key_authors => $value_authors ) {
      foreach ( $author_types_array as $key_types => $value_types ) {
      foreach ( $langcode as $lang ) {
      $l_id = $lang['id'];
      $combine_array[$value_authors][$l_id] = $placeholder;
      for ( $v = 0; $v < (count($value_types)); $v++ ) {
      if ( $v == $key_authors ) {
      $placeholder = $value_types[$v];
      }
      }
      }
      }
      }
      pr($combine_array);

      if ( $combine_array ) {
      $updated_id = array(); // Get the updated id's, to latter delete all others ID not presente in the book
      $inserted_id = array(); // Get the inserted id's
      //Start the loop
      //$v_author_type_id = 0;
      foreach ( $combine_array as $v_bookx_author_name => $author_type_array ) {
      echo "LOOPING";
      // Round 2. So two places where the author's type can be set. Check them both.

      $sql_author_id = "SELECT ba.bookx_author_id AS authorID, ba.author_default_type, batp.bookx_author_type_id "
      . "FROM " . TABLE_PRODUCT_BOOKX_AUTHORS . " ba LEFT JOIN ".TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS." batp on "
      . " batp.bookx_author_id = ba.bookx_author_id  WHERE author_name = :v_bookx_author_name:";
      $sql_author_id = $db->bindVars($sql_author_id, ':v_bookx_author_name:', $v_bookx_author_name, 'string');
      $result_author_id = $db->Execute($sql_author_id);

      $v_author_id = $result_author_id->fields['authorID'];
     
      $v_author_default_type = $result_author_id->fields['author_default_type']; // THe default type
      $v_author_type_id = $result_author_id->fields['bookx_author_type_id']; // The type ID on bookx ( it doesn't need to be the default type)

      //if ( $author_type_array[$epdlanguage_id] != '') { // If there's something in types array, or the field is set
      pr(count($author_type_array));
      if ( $author_type_array[$epdlanguage_id] !='') {

      //$v_author_default_type = 0;
      $sql_author_type_id = "SELECT bookx_author_type_id AS author_typeID, type_description FROM " . TABLE_PRODUCT_BOOKX_AUTHOR_TYPES_DESCRIPTION . " WHERE type_description =:v_bookx_author_type: AND languages_id = :languages_id: LIMIT 1";
      $sql_author_type_id = $db->bindVars($sql_author_type_id, ':v_bookx_author_type:', $author_type_array[$epdlanguage_id], 'string');
      $sql_author_type_id = $db->bindVars($sql_author_type_id, ':bookx_author_type_id:', $v_author_type_id, 'integer');
      $sql_author_type_id = $db->bindVars($sql_author_type_id, ':languages_id:', $epdlanguage_id, 'integer');
      $result_author_type_id = $db->Execute($sql_author_type_id);

      $v_author_type_id = $result_author_type_id->fields['author_typeID'];
      pr($sql_author_type_id);
      pr($result_author_type_id);
      //die();
      if ( ($result_author_type_id->RecordCount() > 0) ) {
      // So I'll go for author_type_id in authors to products and not the default type.
      //                        $query = "UPDATE " . TABLE_PRODUCT_BOOKX_AUTHORS . " SET
      //                    author_default_type = :v_author_type_id:,
      //                    last_modified = :CURRENT_TIMESTAMP: WHERE
      //                    bookx_author_id = :v_author_id:";

      $query = "UPDATE " . TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS . " SET
      bookx_author_type_id = :v_author_type_id:
      WHERE
      bookx_author_type_id = :v_author_id:";

      $query = $db->bindVars($query, ':v_author_type_id:', $v_author_type_id, 'integer');
      //$query = $db->bindVars($query, ':CURRENT_TIMESTAMP:', CURRENT_TIMESTAMP, 'noquotestring');
      $query = $db->bindVars($query, ':v_author_id:', $v_author_id, 'integer');
      $result = $db->Execute($query);
      pr($query);
      pr($result);
      } elseif ( ($result_author_type_id->RecordCount() > 0) && ($v_author_type_id !== $v_author_default_type) ) {

      foreach ( $author_type_array as $type_lang => $v_bookx_author_type ) {
      $sql_update = "UPDATE " . TABLE_PRODUCT_BOOKX_AUTHOR_TYPES_DESCRIPTION . " SET languages_id = :languages_id:, type_description = :v_bookx_author_type: WHERE bookx_author_type_id =:v_bookx_author_type_id: AND languages_id = :languages_id:";
      $sql_update = $db->bindVars($sql_update, ':languages_id:', $type_lang, 'integer');
      $sql_update = $db->bindVars($sql_update, ':v_bookx_author_type_id:', $v_author_type_id, 'integer');
      $sql_update = $db->bindVars($sql_update, ':v_bookx_author_type:', $v_bookx_author_type, 'string');
      $result = $db->Execute($sql_update);
      }
      } else {
      $sql_author_type_id = "INSERT INTO " . TABLE_PRODUCT_BOOKX_AUTHOR_TYPES . " (type_sort_order) VALUES (:sort:)";
      $sql_author_type_id = $db->bindVars($sql_author_type_id, ':sort:', 0, 'integer');
      $result = $db->Execute($sql_author_type_id);

      $v_author_type_id = $db->Insert_ID(); // id is auto_increment

      foreach ( $author_type_array as $type_lang => $v_bookx_author_type ) {
      $sql_author_type_name = "INSERT INTO " . TABLE_PRODUCT_BOOKX_AUTHOR_TYPES_DESCRIPTION . " (bookx_author_type_id, languages_id, type_description, type_image) VALUES (:v_author_type_id:, :languages_id:,:v_bookx_author_type:,  :type_image:)";
      $sql_author_type_name = $db->bindVars($sql_author_type_name, ':v_author_type_id:', $v_author_type_id, 'integer');
      $sql_author_type_name = $db->bindVars($sql_author_type_name, ':languages_id:', $type_lang, 'integer');
      $sql_author_type_name = $db->bindVars($sql_author_type_name, ':v_bookx_author_type:', $v_bookx_author_type, 'string');
      $sql_author_type_name = $db->bindVars($sql_author_type_name, ':type_image:', null, 'string');
      $result2 = $db->Execute($sql_author_type_name);
      }
      }
      } // ends $author_type_array[$epdlanguage_id] !=''
      // We go for the authors names
      if ( $result_author_id->RecordCount() > 0 ) {

      // In authors update the default type
      $query = "UPDATE " . TABLE_PRODUCT_BOOKX_AUTHORS . " SET
      author_name = :v_bookx_author_name:, author_default_type = :v_author_default_type:,
      last_modified = :CURRENT_TIMESTAMP: WHERE
      bookx_author_id = :v_author_id:";
      $query = $db->bindVars($query, ':v_bookx_author_name:', $v_bookx_author_name, 'string');
      $query = $db->bindVars($query, ':v_author_default_type:', $v_author_default_type, 'integer');
      $query = $db->bindVars($query, ':CURRENT_TIMESTAMP:', CURRENT_TIMESTAMP, 'noquotestring');
      $query = $db->bindVars($query, ':v_author_id:', $v_author_id, 'integer');
      $result = $db->Execute($query);

      pr($query);
      pr($result);
      } else {
      $query = "INSERT INTO " . TABLE_PRODUCT_BOOKX_AUTHORS . " (author_name, author_image, author_image_copyright, author_default_type, author_sort_order, author_url, date_added, last_modified)
      VALUES (:v_bookx_author_name:, :author_image:, :author_image_copyright:, :v_author_default_type:, :sort:, :author_url:, :CURRENT_TIMESTAMP:, :CURRENT_TIMESTAMP:)";
      $query = $db->bindVars($query, ':v_bookx_author_name:', $v_bookx_author_name, 'string');
      $query = $db->bindVars($query, ':author_image:', null, 'string');
      $query = $db->bindVars($query, ':author_image_copyright:', null, 'string');
      $query = $db->bindVars($query, ':v_author_default_type:', $v_author_default_type, 'integer');
      $query = $db->bindVars($query, ':sort:', 0, 'integer');
      $query = $db->bindVars($query, ':author_url:', null, 'string');
      $query = $db->bindVars($query, ':CURRENT_TIMESTAMP:', CURRENT_TIMESTAMP, 'noquotestring');
      $result = $db->Execute($query);

      $v_author_id = $db->Insert_ID(); // id is auto_incremented

      foreach ( $langcode as $lang ) {
      $l_id = $lang['id'];

      $query2 = "INSERT INTO " . TABLE_PRODUCT_BOOKX_AUTHORS_DESCRIPTION . " (bookx_author_id, languages_id, author_description) VALUES (:v_author_id:, :languages_id:, null)";
      $query2 = $db->bindVars($query2, ':v_author_id:', $v_author_id, 'integer');
      $query2 = $db->bindVars($query2, ':languages_id:', $l_id, 'integer');
      $query2 = $db->bindVars($query2, ':author_description:', '', 'string');
      $result2 = $db->Execute($query2);
      }
      }

      // Author to Products
      $sql_author_to_product = "SELECT * FROM " . TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS . " WHERE (products_id = :v_products_id:) AND (bookx_author_id = :v_author_id:)";
      $sql_author_to_product = $db->bindVars($sql_author_to_product, ':v_products_id:', $v_products_id, 'integer');
      $sql_author_to_product = $db->bindVars($sql_author_to_product, ':v_author_id:', $v_author_id, 'integer');
      $result_author_to_product = $db->Execute($sql_author_to_product);

      if ( $result_author_to_product->RecordCount() == 0 ) { // insert
      $query = "INSERT INTO " . TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS . " (bookx_author_id,products_id, bookx_author_type_id) VALUES (:v_author_id:, :v_products_id:, :v_author_type_id:)";
      $query = $db->bindVars($query, ':v_author_id:', $v_author_id, 'integer');
      $query = $db->bindVars($query, ':v_products_id:', $v_products_id, 'integer');
      $query = $db->bindVars($query, ':v_author_type_id:', $v_author_type_id, 'integer');
      $result = $db->Execute($query);

      $inserted_id[] = $v_author_id; // Goes to array
      } else {
      $v_author_id = $result_author_to_product->fields['bookx_author_id'];
      $primary_id = $result_author_to_product->fields['primary_id'];
      $updated_id[] = $result_author_to_product->fields['bookx_author_id']; // Goes to array

      $query = "UPDATE " . TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS . " SET bookx_author_id = :v_author_id:, bookx_author_type_id = :v_author_type_id: WHERE products_id = :v_products_id: and primary_id= :primary_id:";
      $query = $db->bindVars($query, ':v_author_id:', $v_author_id, 'integer');
      $query = $db->bindVars($query, ':v_author_type_id:', $v_author_type_id, 'integer');
      $query = $db->bindVars($query, ':v_products_id:', $v_products_id, 'integer');
      $query = $db->bindVars($query, ':primary_id:', $primary_id, 'integer');
      $result2 = $db->Execute($query);
      pr($query);
      pr($result2);
      }
      } // ends foreach combine_array
      } // ends if Flag ok
      // Now delete whatever ID not present in the book
      $temp_del = array_merge($updated_id, $inserted_id); // Merge all the id's in the loop
      $q = ""; // empty string for the query
      foreach ( $temp_del as $key => $value ) {
      $q .= " AND bookx_author_id != '" . $value . "'"; // construct the query with the id's
      }
      $delete = "DELETE FROM " . TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS . " WHERE products_id='" . $v_products_id . "' " . $q . "";
      $delete = $db->bindVars($delete, ':v_products_id:', $v_products_id, 'integer');
      $result = $db->Execute($delete);

      unset($delete, $q, $temp_del, $updated_id, $inserted_id);
      } else { // Authors not ok
      if ( $v_bookx_author_name == '' && $report_ep4bookx_author_name == true ) { // check and warn of empty imprint name(still updates)
      $ep4bookx_reports[BOX_CATALOG_PRODUCT_BOOKX_AUTHORS][] = sprintf(substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 60)) . ' - ' . $edit_link;
      }
      }
      $v_author_id = 0;
      $v_author_type_id = 0;
      }
     */
// now for PRODUCTS BOOKX EXTRA + BOOKX_EXTRA_DESCRIPTION
    if ( isset($v_bookx_isbn) ) { // it's isset 
        // The querys are built on class observer
 
        $sql = "SELECT " . $ep4bookx_extra_sqlcol . " isbn FROM " . TABLE_PRODUCT_BOOKX_EXTRA . "  WHERE 
         products_id = :v_products_id: ";
        $sql = $db->bindVars($sql, ':v_products_id:', $v_products_id, 'integer');
        $result = $db->Execute($sql);

        if ( $result->RecordCount() > 0 ) {

            $query = "UPDATE " . TABLE_PRODUCT_BOOKX_EXTRA . " SET " . $ep4bookx_extra_sqlwhere . " isbn = :v_bookx_isbn: WHERE products_id = :v_products_id:";

            ($bind_publisher == 1 ? $query = $db->bindVars($query, ':v_publisher_id:', $v_publisher_id, 'integer') : '');
            ($bind_series == 1 ? $query = $db->bindVars($query, ':v_series_id:', $v_series_id, 'integer') : '' );
            ($bind_imprint == 1 ? $query = $db->bindVars($query, ':v_imprint_id:', $v_imprint_id, 'integer') : '');
            ($bind_binding == 1 ? $query = $db->bindVars($query, ':v_binding_id:', $v_binding_id, 'integer') : '');
            ($bind_printing == 1 ? $query = $db->bindVars($query, ':v_printing_id:', $v_printing_id, 'integer') : '');
            ($bind_condition == 1 ? $query = $db->bindVars($query, ':v_condition_id:', $v_condition_id, 'integer') : '');
            ($bind_publishing_date == 1 ? $query = $db->bindVars($query, ':v_bookx_publishing_date:', $v_bookx_publishing_date, 'date') : '');
            ($bind_pages == 1 ? $query = $db->bindVars($query, ':v_bookx_pages:', $v_bookx_pages, 'string') : '');
            ($bind_volume == 1 ? $query = $db->bindVars($query, ':v_bookx_volume:', $v_bookx_volume, 'string') : '' );
            ($bind_size == 1 ? $query = $db->bindVars($query, ':v_bookx_size:', $v_bookx_size, 'string') : '');
            $query = $db->bindVars($query, ':v_bookx_isbn:', $v_bookx_isbn, 'string');
            $query = $db->bindVars($query, ':v_products_id:', $v_products_id, 'integer');
            $result_query = $db->Execute($query);

            pr($query);
            pr($result_query);
            // If the subtitle field is not set, a entry must go to TABLE_PRODUCT_BOOKX_EXTRA_DESCRIPTION
            if ( ($result2->RecordCount() > 0) && (!isset($filelayout['v_bookx_subtitle_' . $epdlanguage_id])) ) {
                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $query2 = "UPDATE " . TABLE_PRODUCT_BOOKX_EXTRA_DESCRIPTION . " SET languages_id = :languages_id:, products_subtitle = :v_bookx_subtitle:  WHERE products_id = :v_products_id: AND languages_id = :languages_id:";
                    $query2 = $db->bindVars($query2, ':languages_id:', $l_id, 'integer');
                    $query2 = $db->bindVars($query2, ':v_bookx_subtitle:', '', 'string');
                    $query2 = $db->bindVars($query2, ':v_products_id:', $v_products_id, 'integer');
                    $result2 = $db->Execute($query2);
                }
            }
            //For PRODUCT_BOOKX_EXTRA_DESCRIPTION         
        } else {

            $query = "INSERT INTO " . TABLE_PRODUCT_BOOKX_EXTRA . " (products_id, 
				" . $ep4bookx_extra_sqlcol . " isbn) VALUES (:v_products_id: ," . $ep4bookx_extra_sqlbind . " :v_bookx_isbn:)";
            $query = $db->bindVars($query, ':v_products_id:', $v_products_id, 'integer');
            ($bind_publisher == 1 ? $query = $db->bindVars($query, ':v_publisher_id:', $v_publisher_id, 'integer') : '');
            ($bind_series == 1 ? $query = $db->bindVars($query, ':v_series_id:', $v_series_id, 'integer') : '' );
            ($bind_imprint == 1 ? $query = $db->bindVars($query, ':v_imprint_id:', $v_imprint_id, 'integer') : '');
            ($bind_binding == 1 ? $query = $db->bindVars($query, ':v_binding_id:', $v_binding_id, 'integer') : '');
            ($bind_printing == 1 ? $query = $db->bindVars($query, ':v_printing_id:', $v_printing_id, 'integer') : '');
            ($bind_condition == 1 ? $query = $db->bindVars($query, ':v_condition_id:', $v_condition_id, 'integer') : '');
            ($bind_publishing_date == 1 ? $query = $db->bindVars($query, ':v_bookx_publishing_date:', $v_bookx_publishing_date, 'date') : '');
            ($bind_pages == 1 ? $query = $db->bindVars($query, ':v_bookx_pages:', $v_bookx_pages, 'string') : '');
            ($bind_volume == 1 ? $query = $db->bindVars($query, ':v_bookx_volume:', $v_bookx_volume, 'string') : '' );
            ($bind_size == 1 ? $query = $db->bindVars($query, ':v_bookx_size:', $v_bookx_size, 'string') : '');
            $query = $db->bindVars($query, ':v_bookx_isbn:', $v_bookx_isbn, 'string');
            $result_query = $db->Execute($query);

            if ( ($result2->RecordCount() > 0) && (!isset($filelayout['v_bookx_subtitle_' . $epdlanguage_id])) ) {
                 // If the subtitle field is not set, a entry must go to TABLE_PRODUCT_BOOKX_EXTRA_DESCRIPTION
                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $query2 = "INSERT INTO " . TABLE_PRODUCT_BOOKX_EXTRA_DESCRIPTION . " (products_id, languages_id, products_subtitle) VALUES (:v_products_id:, :languages_id:, :v_bookx_subtitle:)";
                    $query2 = $db->bindVars($query2, ':v_products_id:', $v_products_id, 'integer');
                    $query2 = $db->bindVars($query2, ':languages_id:', $l_id, 'integer');
                    $query2 = $db->bindVars($query2, ':v_bookx_subtitle:', '', 'string');
                    $result2 = $db->Execute($query2);
                    pr($query2, "NOT SET");
                }
            }
        }
     //For PRODUCT_BOOKX_EXTRA_DESCRIPTION
     if ( isset($filelayout['v_bookx_subtitle_' . $epdlanguage_id]) && ($items[$filelayout['v_bookx_subtitle_' . $epdlanguage_id]] != '') ) {
            $flag_subtitle = array();
            foreach ( $langcode as $lang ) {
                $l_id = $lang['id'];
                // For some reason the mbstrlen not working here
            (mb_strlen($items[$filelayout['v_bookx_subtitle_' . $l_id]]) <= $bookx_subtitle_name_max_len ? $flag_subtitle[] = '1': $flag_subtitle[] = '0');             
                  
            }
            pr($flag_subtitle);
            
            if ( !in_array('0', $flag_subtitle) ) {
                $sql2 = "SELECT * FROM " . TABLE_PRODUCT_BOOKX_EXTRA_DESCRIPTION . " WHERE products_id = :v_products_id: AND languages_id =:languages_id:";
                $sql2 = $db->bindVars($sql2, ':v_products_id:', $v_products_id, 'integer');
                $sql2 = $db->bindVars($sql2, ':languages_id:', $epdlanguage_id, 'integer');
                $result2 = $db->Execute($sql2);
                
                if ( $result2->RecordCount() > 0  ) {
                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];

                    if ( $result2->fields['products_subtitle'] == $items[$filelayout['v_bookx_subtitle_' . $l_id]] ) {
                        
                    } elseif ( $result2->fields['products_subtitle'] !== $items[$filelayout['v_bookx_subtitle_' . $l_id]] ) {

                        $query2 = "UPDATE " . TABLE_PRODUCT_BOOKX_EXTRA_DESCRIPTION . " SET languages_id = :languages_id:, products_subtitle = :v_bookx_subtitle:  WHERE products_id = :v_products_id: AND languages_id = :languages_id:";
                        $query2 = $db->bindVars($query2, ':languages_id:', $l_id, 'integer');
                        $query2 = $db->bindVars($query2, ':v_bookx_subtitle:', $items[$filelayout['v_bookx_subtitle_' . $l_id]], 'string');
                        $query2 = $db->bindVars($query2, ':v_products_id:', $v_products_id, 'integer');
                        $result2 = $db->Execute($query2);
                        pr($query2);
                    }
                }
            } else {
                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $query2 = "INSERT INTO " . TABLE_PRODUCT_BOOKX_EXTRA_DESCRIPTION . " (products_id, languages_id, products_subtitle) VALUES (:v_products_id:, :languages_id:, :v_bookx_subtitle:)";
                    $query2 = $db->bindVars($query2, ':v_products_id:', $v_products_id, 'integer');
                    $query2 = $db->bindVars($query2, ':languages_id:', $l_id, 'integer');
                    $query2 = $db->bindVars($query2, ':v_bookx_subtitle:', $items[$filelayout['v_bookx_subtitle_' . $l_id]], 'string');
                    $result2 = $db->Execute($query2);
                }
            }    
            } else {
                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];
                    $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_SUBTITLE_NAME_LONG, $items[$filelayout['v_bookx_subtitle_' . $l_id]], $bookx_subtitle_name_max_len);
                    $ep_error_count++;
                }
            }
        }
       if ( $v_bookx_author_name == '' && $report_ep4bookx_subtitle == true ) { // check and warn of empty imprint name(still updates)
      $ep4bookx_reports[EP4BOOKX_REPORT_SUBTITLE][] = sprintf(substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 60)) . ' - ' . $edit_link;
      }
        if ( $v_bookx_isbn == '' && $report_ep4bookx_isbn == true ) { // check and warn for empty ISBN
            $ep4bookx_reports[LABEL_BOOKX_ISBN][] = sprintf(substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 60)) . '...' . $edit_link;
            $ep_warning_count++;
        }
    } //ends Bookx Extra 
    //

} else { //No isbn in filelayout, no fun

    $display_output .= sprintf('EP4BOOKX_ERROR_MISSING_FIELD_ISBN', $v_bookx_isbn);
    $ep_error_count++;
}