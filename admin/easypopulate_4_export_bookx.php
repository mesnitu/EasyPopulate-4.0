<?php 
/**
 * @EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 * @version  0.9.9 - Still in development, make your changes in a local environment
 * @see Bookx module for ZenCart
 * @see Readme-EP4Bookx
 *
 * @author mesnitu
 * @todo  export with support for languages
 */

if ($row['v_products_type'] == $bookx_product_type) { // check bookx product type

    if (isset($filelayout['v_bookx_isbn'])) {

        $sql = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_EXTRA." WHERE products_id = :v_products_id: LIMIT 1";
        $sql = $db->bindVars($sql, ':v_products_id:', $row['v_products_id'], 'integer');
        $result_extra = ep_4_query($sql);
        $row_bookx_extra = ($ep_uses_mysqli ? mysqli_fetch_array($result_extra) : mysql_fetch_array($result_extra));

        if (($row_bookx_extra['isbn'] != '0') && ($row_bookx_extra['isbn'] != '') || ($row_bookx_extra['size'] != '0') && ($row_bookx_extra['size'] != '') || ($row_bookx_extra['pages'] != '0') && ($row_bookx_extra['pages'] != '') || ($row_bookx_extra['publishing_date'] != '0') && ($row_bookx_extra['publishing_date'] != '') || ($row_bookx_extra['v_bookx_volume'] != '0') && ($row_bookx_extra['v_bookx_volume'] != '0')) { // '0' is correct, but '' NULL is possible

            $row['v_bookx_isbn'] = $row_bookx_extra['isbn'];
            $row['v_bookx_size'] = $row_bookx_extra['size'];
            $row['v_bookx_pages'] = $row_bookx_extra['pages'];
            $row['v_bookx_publishing_date'] = $row_bookx_extra['publishing_date'];
            $row['v_bookx_volume'] = $row_bookx_extra['volume'];

        } else {
            $row['v_bookx_isbn'] = '';
            $row['v_bookx_size'] = '';
            $row['v_bookx_pages'] = '';
            $row['v_bookx_volume'] = '';
            $row['v_bookx_publishing_date'] = '';
        }
    } //ends table extra

    // From TABLE_PRODUCT_BOOKX_EXTRA_DESCRIPTION
    if (isset($filelayout['v_bookx_subtitle_'.$epdlanguage_id])) {

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
    if (isset($filelayout['v_bookx_publisher_name']) && ($row_bookx_extra['bookx_publisher_id'] != '0') && ($row_bookx_extra['bookx_publisher_id'] != '')) {

        $sql = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_PUBLISHERS." WHERE bookx_publisher_id = :bookx_publisher_id: LIMIT 1 ";
        $sql = $db->bindVars($sql, ':bookx_publisher_id:', $row_bookx_extra['bookx_publisher_id'], 'integer');
        $result_publisher = ep_4_query($sql);
        $row_publisher_name = ($ep_uses_mysqli ? mysqli_fetch_array($result_publisher) : mysql_fetch_array($result_publisher));
        $row['v_bookx_publisher_name'] = $row_publisher_name['publisher_name'];
    } else {
        $row['v_bookx_publisher_name'] = '';
    }
    //ends Bookx Publisher

    // Imprints Name 
    if (isset($filelayout['v_bookx_imprint_name']) && ($row_bookx_extra['bookx_imprint_id'] !== '') && ($row_bookx_extra['bookx_imprint_id'] !== '0')) {

        $sql = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_IMPRINTS." WHERE bookx_imprint_id = :bookx_imprint_id: LIMIT 1";
        $sql = $db->bindVars($sql, ':bookx_imprint_id:', $row_bookx_extra['bookx_imprint_id'], 'integer');
        $result_imprint_name = ep_4_query($sql);
        $row_imprint_name = ($ep_uses_mysqli ? mysqli_fetch_array($result_imprint_name) : mysql_fetch_array($result_imprint_name));

        $row['v_bookx_imprint_name'] = $row_imprint_name['imprint_name'];
    } else {
        $row['v_bookx_imprint_name'] = '';
    }
    //ends Bookx imprint

    // Series Name - has languages
    if (isset($filelayout['v_bookx_series_name_'.$epdlanguage_id]) && ($row_bookx_extra['bookx_series_id'] != '0') && ($row_bookx_extra['bookx_series_id'] != '')) { // '0' is correct, but '' NULL is possible
        foreach($langcode as $lang) {
            $l_id = $lang['id'];
            $sql = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_SERIES_DESCRIPTION." WHERE bookx_series_id = :bookx_series_id: AND languages_id = :languages_id: LIMIT 1";
            $sql = $db->bindVars($sql, ':bookx_series_id:', $row_bookx_extra['bookx_series_id'], 'integer');
            $sql = $db->bindVars($sql, ':languages_id:', $l_id, 'integer');
            $result_series_name = ep_4_query($sql);
            
            if ($row_series_name = ($ep_uses_mysqli ? mysqli_fetch_array($result_series_name) : mysql_fetch_array($result_series_name))) {
                $row['v_bookx_series_name_'.$l_id] = $row_series_name['series_name'];
            } else {
                $row['v_bookx_series_name_'.$l_id] = '';
            }
        }
    } //ends series name

    // Bookx Binding as languages 
    if (isset($filelayout['v_bookx_binding_'.$epdlanguage_id]) && ($row_bookx_extra['bookx_binding_id'] != '0') && ($row_bookx_extra['bookx_binding_id'] != '')) { // '0' is correct, but '' NULL is possible

        foreach($langcode as $lang) {
            $l_id = $lang['id'];
            $sql = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_BINDING_DESCRIPTION." WHERE bookx_binding_id = :bookx_binding_id:  AND languages_id = :languages_id: LIMIT 1";
            $sql = $db->bindVars($sql, ':bookx_binding_id:', $row_bookx_extra['bookx_binding_id'], 'integer');
            $sql = $db->bindVars($sql, ':languages_id:', $l_id, 'integer');
            $result_binding_name = ep_4_query($sql);

            if ($row_binding_name = ($ep_uses_mysqli ? mysqli_fetch_array($result_binding_name) : mysql_fetch_array($result))) {
                $row['v_bookx_binding_'.$l_id] = $row_binding_name['binding_description'];

            } else {
                $row['v_bookx_binding_'.$l_id] = '';
            }
        }
    } //ends binding

    // Bookx Printing
    if (isset($filelayout['v_bookx_printing_'.$epdlanguage_id]) && ($row_bookx_extra['bookx_printing_id'] != '0') && ($row_bookx_extra['bookx_printing_id'] != '')) { // '0' is correct, but '' NULL is possible
        foreach($langcode as $lang) {
            $l_id = $lang['id'];
            $sql = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_PRINTING_DESCRIPTION." WHERE bookx_printing_id = :bookx_printing_id: AND languages_id = :languages_id: LIMIT 1";
            $sql = $db->bindVars($sql, ':bookx_printing_id:', $row_bookx_extra['bookx_printing_id'], 'integer');
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
    if (isset($filelayout['v_bookx_condition_'.$epdlanguage_id]) && ($row_bookx_extra['bookx_condition_id'] != '0') && ($row_bookx_extra['bookx_condition_id'] != '')) { // '0' is correct, but '' NULL is possible
        foreach($langcode as $lang) {
            $l_id = $lang['id'];
            $sql = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_CONDITIONS_DESCRIPTION." WHERE bookx_condition_id = :bookx_condition_id: AND languages_id = :languages_id: LIMIT 1";
            $sql = $db->bindVars($sql, ':bookx_condition_id:', $row_bookx_extra['bookx_condition_id'], 'integer');
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
        // pr($sql_genres_to_products);
        // pr($result_genres_to_products);
        $count_genres = $result_genres_to_products->num_rows; // count the num_rows (not in use, but possibly a way, to just loop, if needed)
        if ($result_genres_to_products->num_rows != 0 || $result_genres_to_products->num_rows != '') {
            // makes a index into $genre_array[] with all the genre_id related to the book 
            while ($row_bookx_genres_to_products = ($ep_uses_mysqli ? mysqli_fetch_assoc($result_genres_to_products) : mysql_fetch_assoc($result_genres_to_products))) {
                $genreID_array[] = $row_bookx_genres_to_products['bookx_genre_id']; // we have all book genres_id
            } //ends while 
   
            foreach($genreID_array as $key => $value) { // start looping
                //query genre name by the values in the genreID_array
                foreach($langcode as $lang) {
                    $l_id = $lang['id'];
                    $sql_genres_names = "SELECT genre_description FROM ".TABLE_PRODUCT_BOOKX_GENRES_DESCRIPTION." WHERE bookx_genre_id = :bookx_genre_id: AND languages_id = :languages_id:";
                    $sql_genres_names = $db->bindVars($sql_genres_names, ':bookx_genre_id:', $value, 'integer');
                    $sql_genres_names = $db->bindVars($sql_genres_names, ':languages_id:', $l_id, 'integer');                
                    $result_genres_names = ep_4_query($sql_genres_names);
                  
                    $genre_name = ($ep_uses_mysqli ? mysqli_fetch_array($result_genres_names) : mysql_fetch_array($result_genres_names));

                    if ($genre_name['genre_description'] != '') {
                        // @todo : With several langs, If a genre is not translated or if a book as two genres, the string will end or start with a "^". 
                        $genre_names_array[$l_id][] = $genre_name['genre_description']; // adds langcode key to the array
                    } else {
                        $genre_names_array[$l_id][] = 'Missing Translation';
                    }
                } //ends foreach lang
            } //ends foreach 
            //pr($genre_names_array);
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

    /**
     * The Author
     * We also get the default_type
     */
    if (isset($filelayout['v_bookx_author_name'])) {
        $authorID_array = array(); // Creates a empty array to get the genres_id for the loop
        $author_names_array = array(); // Creates a empty array to get the genres_names
        $author_typeID_array = array(); // We start here authors types array. Same stuff. If there's more than on author, there's a change they could be of difeerent types
        $author_default_type_array = array();
        $sql_authors_to_products = "SELECT * FROM ".TABLE_PRODUCT_BOOKX_AUTHORS_TO_PRODUCTS." WHERE products_id = :products_id: ";
        $sql_authors_to_products = $db->bindVars($sql_authors_to_products, ':products_id:', $row['v_products_id'], 'integer');
        $result_authors_to_products = ep_4_query($sql_authors_to_products);
       
        $count_authors = $result_authors_to_products->num_rows; // count the num_rows (not in use, but possibly a way, to just loop, if the num_rows > 1)

        if ($result_authors_to_products->num_rows != 0 || $result_authors_to_products->num_rows != '') {

            while ($row_bookx_authors_to_products = ($ep_uses_mysqli ? mysqli_fetch_assoc($result_authors_to_products) : mysql_fetch_assoc($result_authors_to_products))) {

                $authorID_array[] = $row_bookx_authors_to_products['bookx_author_id']; // we have all book authors_id
                $author_typeID_array[] = $row_bookx_authors_to_products['bookx_author_type_id']; // This is the type ID, not the default type.

            } //ends while 
           
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
            $display_output .= '<strong>Warning:</strong> A default type was missing, and a author type was attached to prevent changing the types order on import<br>';
        }
    }

} //ends product bookx export
