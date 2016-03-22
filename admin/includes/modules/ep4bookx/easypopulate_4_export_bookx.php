<?php
/**
 * @EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 * @version  0.9.9 - Still in development, make your changes in a local environment
 * @see Bookx module for ZenCart
 * @see Readme-EP4Bookx
 *
 * @author mesnitu
 */
    // names and descriptions require that we loop thru all installed languages

foreach ($langcode as $key2 => $lang2) {
    $lid2 = $lang2['id'];
  
    $sql2 = 'SELECT * FROM ' . TABLE_PRODUCTS_DESCRIPTION . ' WHERE products_id = :products_id: AND language_id = :language_id: LIMIT 1 ';
    $sql2 = $db->bindVars($sql2, ':products_id:', $row['v_products_id'], 'integer');
    $sql2 = $db->bindVars($sql2, ':language_id:', $lid2, 'integer');
    $result2 = ep_4_query($sql2);

    $row2 = ($ep_uses_mysqli ? mysqli_fetch_array($result2) : mysql_fetch_array($result2));
      $row['v_products_name_'.$lid2] = $row2['products_name'];
      $row['v_products_description_'.$lid2] = $row2['products_description'];
      
      $row['v_products_url_'.$lid2] = $row2['products_url'];
      // metaData start
      // for each language, get the description and set the vals
      if ($enable_ep4bookx_metatags == 1) {
      $sqlMeta = 'SELECT * FROM ' . TABLE_META_TAGS_PRODUCTS_DESCRIPTION . ' WHERE products_id = :products_id: AND language_id = :language_id: LIMIT 1 ';
      $sqlMeta = $db->bindVars($sqlMeta, ':products_id:', $row['v_products_id'], 'integer');
      $sqlMeta = $db->bindVars($sqlMeta, ':language_id:', $lid2, 'integer');
      
      $resultMeta = ep_4_query($sqlMeta);
      $rowMeta = ($ep_uses_mysqli ? mysqli_fetch_array($resultMeta) : mysql_fetch_array($resultMeta));
      $row['v_metatags_title_'.$lid2] = $rowMeta['metatags_title'];
      $row['v_metatags_keywords_'.$lid2] = $rowMeta['metatags_keywords'];
      $row['v_metatags_description_'.$lid2] = $rowMeta['metatags_description'];
      }
      // metaData end
     // $zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_LOOP');
    } // foreach

    // Categories 
    $category_delimiter = "^"; //Need to move this to the admin panel
    $thecategory_id = $row['v_categories_id']; // starting category_id

    if ( EASYPOPULATE_4_CONFIG_EXPORT_URI != '0') {
      $sql_type = "SELECT type_handler FROM " . TABLE_PRODUCT_TYPES . " WHERE type_id = :type_id:";
      $sql_type = $db->bindVars($sql_type, ':type_id:', zen_get_products_type($row['v_products_id']), 'integer');
      $sql_typename = $db->Execute($sql_type);

      $row['v_html_uri'] = zen_catalog_href_link($sql_typename->fields['type_handler'] . '_info', 'cPath=' . zen_get_generated_category_path_ids($row['v_master_categories_id']) . '&products_id=' . $row['v_products_id'], 'NONSSL');

    }
     //$zco_notifier->notify('EP4_EXPORT_FULL_OR_CAT_FULL_AFTER');
     //
    // if ($enable_ep4bookx_categories == 1) { // Categories are mandatory for new products, so this gets complicated. Better to disable this option
    
// if parent_id is not null ('0'), then follow it up.  Perhaps this could be replaced by Zen's zen_not_null() function?
    while (!empty($thecategory_id)) {
      // mult-lingual categories start - for each language, get category description and name
      $sql2 = 'SELECT * FROM ' . TABLE_CATEGORIES_DESCRIPTION . ' WHERE categories_id = :categories_id: ORDER BY language_id';
      $sql2 = $db->bindVars($sql2, ':categories_id:', $thecategory_id, 'integer');
      $result2 = ep_4_query($sql2);
      while ($row2 = ($ep_uses_mysqli ? mysqli_fetch_array($result2) : mysql_fetch_array($result2))) {
        $lid = $row2['language_id'];
        $row['v_categories_name_' . $lid] = $row2['categories_name'] . $category_delimiter . $row['v_categories_name_' . $lid];
      } //while
      // look for parent categories ID
      $sql3 = 'SELECT parent_id FROM ' . TABLE_CATEGORIES . ' WHERE categories_id = :categories_id:';
      $sql3 = $db->bindVars($sql3, ':categories_id:', $thecategory_id, 'integer');
      $result3 = ep_4_query($sql3);
      $row3 = ($ep_uses_mysqli ? mysqli_fetch_array($result3) : mysql_fetch_array($result3));
      $theparent_id = $row3['parent_id'];
      if ($theparent_id != '') { // Found parent ID, set thecategoryid to get the next level
        $thecategory_id = $theparent_id;
      } else { // Category Root Found
        $thecategory_id = false;
      }
    } // while

    // trim off trailing category delimiter '^'
    foreach ($langcode as $key => $lang) {
      $lid = $lang['id'];
      $row['v_categories_name_' . $lid] = rtrim($row['v_categories_name_' . $lid], $category_delimiter);
    } // foreach
   
  //  } //ends if categories
   
  // MANUFACTURERS EXPORT - THIS NEEDS MULTI-LINGUAL SUPPORT LIKE EVERYTHING ELSE!
  // if the filelayout says we need a manfacturers name, get it for download file
  if (isset($filelayout['v_manufacturers_name'])) {
    if (($row['v_manufacturers_id'] != '0') && ($row['v_manufacturers_id'] != '')) { // '0' is correct, but '' NULL is possible
      $sql2 = 'SELECT manufacturers_name FROM ' . TABLE_MANUFACTURERS . ' WHERE manufacturers_id = :manufacturers_id:';
      $sql2 = $db->bindVars($sql2, ':manufacturers_id:', $row['v_manufacturers_id'], 'integer');
      $result2 = ep_4_query($sql2);
      $row2 = ($ep_uses_mysqli ? mysqli_fetch_array($result2) : mysql_fetch_array($result2));
      $row['v_manufacturers_name'] = $row2['manufacturers_name'];
    } else {  // this is to fix the error on manufacturers name
      // $row['v_manufacturers_id'] = '0';  blank name mean 0 id - right? chadd 4-7-09
      $row['v_manufacturers_name'] = ''; // no manufacturer name
    }
  } //End if isset v_manufacturers_name
  
        if (
                ($row['v_bookx_isbn'] != '0') && ($row['v_bookx_isbn'] != '') || 
                (isset($filelayout['v_bookx_size']) && $row['v_bookx_size'] != '0') && ($row['v_bookx_size'] != '') || 
                (isset($filelayout['v_bookx_pages']) && $row['v_bookx_pages'] != '0') && ($row['v_bookx_pages'] != '') || 
                (isset($filelayout['v_bookx_publishing_date']) && $row['v_bookx_publishing_date'] != '0') && ($row['v_bookx_publishing_date'] != '') || 
                (isset($filelayout['v_bookx_volume']) && $row['v_bookx_volume'] != '0') && ($row['v_bookx_volume'] != '0')) { // '0' is correct, but '' NULL is possible
            
            $row['v_bookx_isbn'];
            $row['v_bookx_size'];
            $row['v_bookx_pages'];
            $row['v_bookx_publishing_date'];
            $row['v_bookx_volume'];

        } else {
            $row['v_bookx_isbn'] = '';
            $row['v_bookx_size'] = '';
            $row['v_bookx_pages'] = '';
            $row['v_bookx_volume'] = '';
            $row['v_bookx_publishing_date'] = '';
        }
   // } //ends table extra

    if (isset($filelayout['v_bookx_subtitle_'.$epdlanguage_id]) ) {
        
        foreach($langcode as $lang) {
            $l_id = $lang['id'];
            $sql = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_EXTRA_DESCRIPTION." WHERE products_id = :v_products_id: AND languages_id = :languages_id: LIMIT 1";
            $sql = $db->bindVars($sql, ':v_products_id:', $row['v_products_id'], 'integer');
            $sql = $db->bindVars($sql, ':languages_id:', $l_id, 'integer');
            $result_subtitle = ep_4_query($sql);
          
            if ($row_bookx_subtitle = ($ep_uses_mysqli ? mysqli_fetch_array($result_subtitle) : mysql_fetch_array($result_subtitle))) {
                $row['v_bookx_subtitle_'.$l_id] = $row_bookx_subtitle['products_subtitle'];
            } else {
                $row['v_bookx_subtitle_'.$l_id] = '';
            }
        }
    } //ends book_extra_descritpion

    // Publisher Name
    if (isset($filelayout['v_bookx_publisher_name']) && ($row['v_bookx_publisher_name'] != '')) {
        $row['v_bookx_publisher_name'];
    } else {
        $row['v_bookx_publisher_name'] = '';
    }//ends Bookx Publisher
    
    // Imprints Name
    if (isset($filelayout['v_bookx_imprint_name']) && ($row['v_bookx_imprint_name'] != '')) {
        $row['v_bookx_imprint_name'];
    } else {
        $row['v_bookx_imprint_name'] = '';
    } //ends Bookx imprint

    // Series Name 
  
    if (isset($filelayout['v_bookx_series_name_'.$epdlanguage_id]) && ($row['bookx_series_id'] != '0') && ($row['bookx_series_id'] != '')) { // '0' is correct, but '' NULL is possible
        foreach($langcode as $lang) {
            $l_id = $lang['id'];
            $sql = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_SERIES_DESCRIPTION." WHERE bookx_series_id = :bookx_series_id: AND languages_id = :languages_id: LIMIT 1";           
            $sql = $db->bindVars($sql, ':bookx_series_id:', $row['bookx_series_id'], 'integer');
            $sql = $db->bindVars($sql, ':languages_id:', $l_id, 'integer');
            $result_series_name = ep_4_query($sql);
           
            if ($row_series_name = ($ep_uses_mysqli ? mysqli_fetch_assoc($result_series_name) : mysql_fetch_assoc($result_series_name))) {
                $row['v_bookx_series_name_'.$l_id] = $row_series_name['series_name'];
            } else {
                $row['v_bookx_series_name_'.$l_id] = '';
            }
        }
    } //ends series name

    // Bookx Binding as languages
    if (isset($filelayout['v_bookx_binding_'.$epdlanguage_id]) && ($row['bookx_binding_id'] != '0') && ($row['bookx_binding_id'] != '')) { // '0' is correct, but '' NULL is possible

        foreach($langcode as $lang) {
            $l_id = $lang['id'];
            $sql = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_BINDING_DESCRIPTION." WHERE bookx_binding_id = :bookx_binding_id:  AND languages_id = :languages_id: LIMIT 1";
            $sql = $db->bindVars($sql, ':bookx_binding_id:', $row['bookx_binding_id'], 'integer');
            $sql = $db->bindVars($sql, ':languages_id:', $l_id, 'integer');
            $result_binding_name = ep_4_query($sql);

            if ($row_binding_name = ($ep_uses_mysqli ? mysqli_fetch_array($result_binding_name) : mysql_fetch_array($result_binding_name))) {
                $row['v_bookx_binding_'.$l_id] = $row_binding_name['binding_description'];
            } else {
                $row['v_bookx_binding_'.$l_id] = '';
            }
        }
    } //ends binding

    // Bookx Printing
    if (isset($filelayout['v_bookx_printing_'.$epdlanguage_id]) && ($row['bookx_printing_id'] != '0') && ($row['bookx_printing_id'] != '')) { // '0' is correct, but '' NULL is possible
        foreach($langcode as $lang) {
            $l_id = $lang['id'];
            $sql = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_PRINTING_DESCRIPTION." WHERE bookx_printing_id = :bookx_printing_id: AND languages_id = :languages_id: LIMIT 1";
            $sql = $db->bindVars($sql, ':bookx_printing_id:', $row['bookx_printing_id'], 'integer');
            $sql = $db->bindVars($sql, ':languages_id:', $l_id, 'integer');
            $result_printing_name = ep_4_query($sql);

            if ($row_printing = ($ep_uses_mysqli ? mysqli_fetch_array($result_printing_name) : mysql_fetch_array($result_printing_name))) {
                $row['v_bookx_printing_'.$l_id] = $row_printing['printing_description'];

            } else {
                $row['v_bookx_printing_'.$l_id] = '';
            }
        }
    } // ends Printing

    // Bookx Condition $filelayout[] = 'v_bookx_condition';
    if (isset($filelayout['v_bookx_condition_'.$epdlanguage_id]) && ($row['bookx_condition_id'] != '0') && ($row['bookx_condition_id'] != '')) { // '0' is correct, but '' NULL is possible
        foreach($langcode as $lang) {
            $l_id = $lang['id'];
            $sql = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_CONDITIONS_DESCRIPTION." WHERE bookx_condition_id = :bookx_condition_id: AND languages_id = :languages_id: LIMIT 1";
            $sql = $db->bindVars($sql, ':bookx_condition_id:', $row['bookx_condition_id'], 'integer');
            $sql = $db->bindVars($sql, ':languages_id:', $l_id, 'integer');
            $result_condition = ep_4_query($sql);

            if ($row_condition = ($ep_uses_mysqli ? mysqli_fetch_array($result_condition) : mysql_fetch_array($result_condition))) {
                $row['v_bookx_condition_'.$l_id] = $row_condition['condition_description'];
            } else {
                $row['v_bookx_condition_'.$l_id] = '';
            }
        }
    } //ends Condition

    // Genre name
    if (isset($filelayout['v_bookx_genre_name_'.$epdlanguage_id])) {
	
        //$category_delimiter = '^' // stick to the same delimiter for genres and authors, already presente in EP4
        $genreID_array = array(); // Creates a empty array to get the genres_id for the loop
        $genre_names_array = array(); // Creates a empty array to get the genres_names
        // first query to get products_id related genre_id
        $sql_genres_to_products = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_GENRES_TO_PRODUCTS." WHERE products_id = :products_id:";
        $sql_genres_to_products = $db->bindVars($sql_genres_to_products, ':products_id:', $row['v_products_id'], 'integer');
        $result_genres_to_products = ep_4_query($sql_genres_to_products);
		
        $count_genres = $result_genres_to_products->num_rows; // count the num_rows (not in use, but possibly a way, to just loop, if needed)
		
		while ( $row_bookx_genres_to_products = ($ep_uses_mysqli ? mysqli_fetch_assoc($result_genres_to_products) : mysql_fetch_assoc($result_genres_to_products)) ) {
			$genreID_array[] = $row_bookx_genres_to_products['bookx_genre_id'];
			
		}

        if (!empty($genreID_array)) {
		
            // makes a index into $genre_array[] with all the genre_id related to the book
            
             $genreID_array[] = $row_bookx_genres_to_products['bookx_genre_id']; // we have all book genres_id
		
            foreach($genreID_array as $key => $value) { // start looping
                //query genre name by the values in the genreID_array
				
                foreach($langcode as $lang) {
                    $l_id = $lang['id'];
                    $sql_genres_names = "SELECT genre_description FROM ".TABLE_PRODUCT_BOOKX_GENRES_DESCRIPTION." WHERE bookx_genre_id = :bookx_genre_id: AND languages_id = :languages_id:";
                    $sql_genres_names = $db->bindVars($sql_genres_names, ':bookx_genre_id:', $value, 'integer');
                    $sql_genres_names = $db->bindVars($sql_genres_names, ':languages_id:', $l_id, 'integer');
                    $result_genres_names = ep_4_query($sql_genres_names);

                    $genre_name = ($ep_uses_mysqli ? mysqli_fetch_array($result_genres_names) : mysql_fetch_array($result_genres_names));
					
					
                    if ($genre_name['genre_description'] !='' ) {
                        // @todo : With several langs, If a genre is not translated or if a book as two genres, the string will end or start with a "^".
                        $genre_names_array[$l_id][] = $genre_name['genre_description']; // adds langcode key to the array
                    } elseif ($genre_name['genre_description'] !='' && (count($lancode)!=1)) {
                        $genre_names_array[$l_id][] = '####';
                    }
                } //ends foreach lang
            } //ends foreach
			
            foreach($genre_names_array as $lang => $value) {
                $row['v_bookx_genre_name_'.$lang] = implode($category_delimiter, $value);
            }
        } else {
            //nothing there
			
            foreach($langcode as $lang) {
                $l_id = $lang['id'];
                $row['v_bookx_genre_name_'.$l_id] = '';
				
            }
        }
    } //ends Genre name

  // The Author
    if (isset($filelayout['v_bookx_author_name'])) {
        $authorID_array = array(); // Creates a empty array to get the genres_id for the loop
        $author_names_array = array(); // Creates a empty array to get the genres_names
        $author_typeID_array = array(); // We start here authors types array. Same stuff. If there's more than on author, there's a chance they could be of difeerent types
        $author_default_type_array = array();
        $sql_authors_to_products = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS." WHERE products_id = :products_id: ";
        $sql_authors_to_products = $db->bindVars($sql_authors_to_products, ':products_id:', $row['v_products_id'], 'integer');
        $result_authors_to_products = ep_4_query($sql_authors_to_products);

        //$count_authors = $result_authors_to_products->num_rows; // count the num_rows (not in use, but possibly a way, to just loop, if the num_rows > 1)

            while ($row_bookx_authors_to_products = ($ep_uses_mysqli ? mysqli_fetch_assoc($result_authors_to_products) : mysql_fetch_assoc($result_authors_to_products))) {

                $authorID_array[] = $row_bookx_authors_to_products['bookx_author_id']; // we have all book authors_id
                $author_typeID_array[] = $row_bookx_authors_to_products['bookx_author_type_id']; // This is the type ID, not the default type.

            } //ends while
            if ($authorID_array) {
            foreach($authorID_array as $key => $value) { // start looping
                //query genre name by the values in the genreID_array
                $sql_authors_names = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_AUTHORS." WHERE bookx_author_id = :bookx_author_id:";
                $sql_authors_names = $db->bindVars($sql_authors_names, ':bookx_author_id:', $value, 'integer');
                $result_authors_names = ep_4_query($sql_authors_names);
                $row_author_name = ($ep_uses_mysqli ? mysqli_fetch_array($result_authors_names) : mysql_fetch_array($result_authors_names));

                // @todo An author can have a default type associated, or a type ID. Needs more testing.
                if ($row_author_name != '0' && $row_author_name != '') {
                    $author_default_type_array[] = $row_author_name['author_default_type'];
                }
                $author_names_array[] = $row_author_name['author_name'];
            } //ends foreach
            $row['v_bookx_author_name'] = implode($category_delimiter, $author_names_array);
        } else {
            $row['v_bookx_author_name'] = '';
        }
    } // ends authors if

    // Bookx Author Type
    if (isset($filelayout['v_bookx_author_type_'.$epdlanguage_id])) { // '0' is correct, but '' NULL is possible

        $author_type_name_array = array();
        // If AuthorA as no type, and AuthorB is of Writer, in the file something must be written, otherwise, AuthorA becomes Writer, and authorB empty on import...This is not good. Don't see a simple way, but to use a default type to fill those empty types.
        foreach($author_typeID_array as $typeID) { // start looping
            //query genre name by the values in the genreID_array
            if (($typeID != '0') && ($typeID != '')) {
                foreach($langcode as $lang) {
                    $l_id = $lang['id'];
                    $sql_author_type_name = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_AUTHOR_TYPES_DESCRIPTION." WHERE bookx_author_type_id= :bookx_author_type_id: AND languages_id = :languages_id:";
                    $sql_author_type_name = $db->bindVars($sql_author_type_name, ':bookx_author_type_id:', $typeID, 'integer');
                    $sql_author_type_name = $db->bindVars($sql_author_type_name, ':languages_id:', $l_id, 'integer');
                    $result_author_type = ep_4_query($sql_author_type_name);
                    $row_author_type_name = ($ep_uses_mysqli ? mysqli_fetch_array($result_author_type) : mysql_fetch_array($result_author_type));
                    $author_type_name_array[$l_id][] = $row_author_type_name['type_description'];
                }
            } else {
                foreach($langcode as $lang) {
                    $l_id = $lang['id'];
                    if (count(array_keys($author_typeID_array)) > 1) { // Only use the default type, if there's more than one author
                        $author_type_name_array[$l_id][] = ($bookx_default_author_type != '' ? $bookx_default_author_type = $bookx_default_author_type : $bookx_default_author_type = 'NO TYPE'); // until better idea, if theres no default type, something visible is attached as a placeholder
                        $warning = true;
                    }
                }
            }
        } //ends foreach author_typeID
        foreach($author_type_name_array as $lang => $value) {
            $row['v_bookx_author_type_'.$lang] = implode($category_delimiter, $value);
        }
        if ($bookx_default_author_type == 'NO TYPE' && $warning == true) { // not visible enough
            $display_output .= '<strong>Warning:</strong> A default type was missing, and a author type was attached to prevent changing the types order on import <br />';
        }
    }
// } //ends product bookx export
