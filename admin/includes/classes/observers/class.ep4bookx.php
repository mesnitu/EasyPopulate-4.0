<?php

/**
 * Description of class.ep4bookx.
 *
 * @author mc12345678
 */
class ep4bookx extends base {

    //  private $_product = array();

    function __construct() { // ep4bookx if this class has difficulty loading
//    global $zco_notifier;
        $notifyme = array();

        $notifyme[] = 'EP4_START';
        $notifyme[] = 'EP4_COLLATION_UTF8_ZC13X';
        $notifyme[] = 'EP4_EASYPOPULATE_4_LINK';
        $notifyme[] = 'EP4_DISPLAY_STATUS';
        $notifyme[] = 'EP4_MAX_LEN';
        $notifyme[] = 'EP4_FILENAMES';
        $notifyme[] = 'EP4_LINK_SELECTION_END';
        $notifyme[] = 'EP4_EXPORT_FILE_ARRAY_START';
        $notifyme[] = 'EP4_EXPORT_CASE_EXPORT_FILE_END';
        $notifyme[] = 'EP4_EXPORT_WHILE_START';
        $notifyme[] = 'EP4_EXPORT_LOOP_FULL_OR_SBASTOCK';
        $notifyme[] = 'EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_LOOP';
        $notifyme[] = 'EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_END';
        $notifyme[] = 'EP4_EXPORT_SPECIALS_AFTER';
        $notifyme[] = 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_START';
        $notifyme[] = 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT';
        $notifyme[] = 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_SELECT';
        $notifyme[] = 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_TABLE';
        $notifyme[] = 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_FILELAYOUT';
        $notifyme[] = 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_SQL_SELECT';
        $notifyme[] = 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORYMETA_FILELAYOUT';
        $notifyme[] = 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CASE_DEFAULT';
        $notifyme[] = 'EP4_EXTRA_FUNCTIONS_INSTALL_END';
        $notifyme[] = 'EP4_EXPORT_FULL_OR_CAT_FULL_AFTER';
        $notifyme[] = 'EP4_IMPORT_START';
        $notifyme[] = 'EP4_IMPORT_FILE_EARLY_ROW_PROCESSING';
        $notifyme[] = 'EP4_IMPORT_AFTER_CATEGORY';
        $notifyme[] = 'EP4_IMPORT_FILE_NEW_PRODUCT_PRODUCT_TYPE';
        $notifyme[] = 'EP4_IMPORT_FILE_PRE_DISPLAY_OUTPUT';

        $this->attach($this, $notifyme);
    }

    /* Function run/called by notifier: EP4_START */

    function updateEP4Start(&$callingClass, $notifier, $paramsArray) {
        global $db, $curver, $ep_bookx, $bookx_product_type, $check_bookx_export_metatags;
        global $bookx_author_name_max_len, $bookx_author_types_name_max_len,
        $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len,
        $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len,
        $bookx_imprint_name_max_len, $bookx_subtitle_name_max_len;

            $curver .= '<br />'; // Intent of this is to show that the option is available not that it is "active", though if that is desired instead, then suggest adding an "active/inactive"
            $curver .= 'w/ BookX v1.0';
        //  @EP4Bookx Get the user config 
        $ep_bookx = (int) EASYPOPULATE_4_CONFIG_BOOKX_DATA; // 0-Disable, 1-Enable
        //ups, Actually BookX doesn't yet have the products metatags on the fly. 
        //So until then, check if a user as made it's own implementation. This will be use on the filelayout.
        $check_bookx_export_metatags = DIR_FS_ADMIN . DIR_WS_MODULES . 'product_bookx/collect_info_metatags.php';

        if ($ep_bookx == 1) {
            $result = $db->Execute('SELECT type_id FROM ' . TABLE_PRODUCT_TYPES . ' WHERE type_handler = \'product_bookx\'');
            $bookx_product_type = $result->fields['type_id'];
            require(DIR_FS_ADMIN . DIR_WS_LANGUAGES . $_SESSION['language'] . '/easypopulate_4_bookx.php');

            $bookx_author_name_max_len = zen_field_length(TABLE_PRODUCT_BOOKX_AUTHORS, 'author_name');
            $bookx_author_types_name_max_len = zen_field_length(TABLE_PRODUCT_BOOKX_AUTHOR_TYPES_DESCRIPTION, 'type_description');
            $bookx_genre_name_max_len = zen_field_length(TABLE_PRODUCT_BOOKX_GENRES_DESCRIPTION, 'genre_description');
            $bookx_series_name_max_len = zen_field_length(TABLE_PRODUCT_BOOKX_SERIES_DESCRIPTION, 'series_name');
            $bookx_publisher_name_max_len = zen_field_length(TABLE_PRODUCT_BOOKX_PUBLISHERS, 'publisher_name');
            $bookx_binding_name_max_len = zen_field_length(TABLE_PRODUCT_BOOKX_BINDING_DESCRIPTION, 'binding_description');
            $bookx_printing_name_max_len = zen_field_length(TABLE_PRODUCT_BOOKX_PRINTING_DESCRIPTION, 'printing_description');
            $bookx_condition_name_max_len = zen_field_length(TABLE_PRODUCT_BOOKX_CONDITIONS_DESCRIPTION, 'condition_description');
            $bookx_imprint_name_max_len = zen_field_length(TABLE_PRODUCT_BOOKX_IMPRINTS, 'imprint_name');
            $bookx_subtitle_name_max_len = zen_field_length(TABLE_PRODUCT_BOOKX_EXTRA_DESCRIPTION, 'products_subtitle');
        }
    }

    // $zco_notifier->notify('EP4_COLLATION_UTF8_ZC13X');
    function updateEP4CollationUTF8ZC13x(&$callingClass, $notifier, $paramsArray) {
        global $bookx_author_name_max_len, $bookx_author_types_name_max_len,
        $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len,
        $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len,
        $bookx_imprint_name_max_len, $bookx_subtitle_max_len;

        $bookx_author_name_max_len = $bookx_author_name_max_len / 3;
        $bookx_author_types_name_max_len = $bookx_author_types_name_max_len / 3;
        $bookx_genre_name_max_len = $bookx_genre_name_max_len / 3;
        $bookx_series_name_max_len = $bookx_series_name_max_len / 3;
        $bookx_publisher_name_max_len = $bookx_publisher_name_max_len / 3;
        $bookx_binding_name_max_len = $bookx_binding_name_max_len / 3;
        $bookx_printing_name_max_len = $bookx_printing_name_max_len / 3;
        $bookx_condition_name_max_len = $bookx_condition_name_max_len / 3;
        $bookx_imprint_name_max_len = $bookx_imprint_name_max_len / 3;
        $bookx_subtitle_max_len = $bookx_subtitle_max_len / 3;
    }

    // $zco_notifier->notify('EP4_EASYPOPULATE_4_LINK');
    function updateEP4Easypopulate4Link(&$callingClass, $notifier, $paramsArray) {
        global $ep_bookx, $nanobar;
        if ($ep_bookx == 1) {
            ?>
            <link rel="stylesheet" type="text/css" href="includes/ep4bookx.css" />
            <script language="javascript" type="text/javascript" src="includes/ep4bookx.js"></script>
            <?php if ($nanobar == 1 ) { ?>
            <script type="text/javascript">
                          var options = {
                  bg: '#000'
              };
              var nanobar = new Nanobar( options );
              //move bar
              nanobar.go( 30 ); // size bar 30%
              // Finish progress bar
              nanobar.go(100);
                        </script>
                        <?php }
            
        }
    }

    // $zco_notifier->notify('EP4_DISPLAY_STATUS');
    function updateEP4DisplayStatus(&$callingClass, $notifier, $paramsArray) {
    global $ep_bookx, $ep_bookx_default_genre_name;

        echo EASYPOPULATE_4_DISPLAY_ENABLE_BOOKX . $ep_bookx . '<br/>';
    echo EASY_POPULATE_4_BOOKX_DISPLAY_DEFAULT_GENRE_NAME . $ep_bookx_default_genre_name . '<br />';
    }

    // $zco_notifier->notify('EP4_MAX_LEN');
    function updateEP4MaxLen(&$callingClass, $notifier, $paramsArray) {
        global $bookx_author_name_max_len,
        $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len,
        $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len,
        $bookx_imprint_name_max_len, $bookx_subtitle_max_len, $ep_bookx;
        ;
        if ($ep_bookx == 1) {
            echo 'author_name:' . $bookx_author_name_max_len . '<br/>';
            echo 'genre_description:' . $bookx_genre_name_max_len . '<br/>';
            echo 'series_name:' . $bookx_series_name_max_len . '<br/>';
            echo 'publisher_name:' . $bookx_publisher_name_max_len . '<br/>';
            echo 'binding_description:' . $bookx_binding_name_max_len . '<br/>';
            echo 'printing_description:' . $bookx_printing_name_max_len . '<br/>';
            echo 'condition_description:' . $bookx_condition_name_max_len . '<br/>';
            echo 'imprint_name:' . $bookx_imprint_name_max_len . '<br/>';
        }
    }

    // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_START');
    function updateEP4ExtraFunctionsSetFilelayoutFullStart(&$callingClass, $notifier, $paramsArray) {
        
    }

    //$zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT');
    function updateEP4ExtraFunctionsSetFilelayoutFullFilelayout(&$callingClass, $notifier, $paramsArray) {
    global $filelayout, $langcode;

//@ALTERED Bookx Info - 23-04-2015
    if ((int)EASYPOPULATE_4_CONFIG_BOOKX_DATA == true) {
	   pr(EASYPOPULATE_4_CONFIG_BOOKX_DATA);
        // BOOKX_EXTRA DESCRIPTION
       // if (isset($enable_bookx_subtitle ) ) {
        foreach ($langcode as $key => $lang) { // create variables for each language id
          $l_id = $lang['id'];
          $filelayout[] = 'v_bookx_subtitle_'.$l_id;
        }
        //}
       //if ($enable_bookx_genre_name == true ) { // This is not working....
       foreach ($langcode as $key => $lang) { // create variables for each language id
         $l_id = $lang['id'];
         $filelayout[] = 'v_bookx_genre_name_'.$l_id;
       }
       //} 
       //if ($enable_bookx_publisher_name == true) {
		$filelayout[] = 'v_bookx_publisher_name';
        //}
        //if ($enable_bookx_series_name == true) {
        foreach ($langcode as $key => $lang) { 
            $l_id = $lang['id'];
            $filelayout[] = 'v_bookx_series_name_'.$l_id; // Series name, as Lang ID
        }
        //} 
        //if ($enable_bookx_imprint_name == true) {            
		$filelayout[] = 'v_bookx_imprint_name';
        //}
        
        //if ($enable_bookx_binding == true) { 
        foreach ($langcode as $key => $lang) { 
            $l_id = $lang['id'];
            $filelayout[] = 'v_bookx_binding_'.$l_id; //   Lang ID
        }
        //}
        // if ($enable_bookx_printing == true) { 
        foreach ($langcode as $key => $lang) { 
            $l_id = $lang['id'];
            $filelayout[] = 'v_bookx_printing_'.$l_id; //   Lang ID
        }
         //}
        //if ($enable_bookx_condition == true) { 
        foreach ($langcode as $key => $lang) { 
            $l_id = $lang['id'];
            $filelayout[] = 'v_bookx_condition_'.$l_id; //  Lang ID
        }
        //}
        //if($enable_bookx_isbn == true) {     
		$filelayout[] = 'v_bookx_isbn';
        //}
       //if($enable_bookx_size == true) { 
		$filelayout[] = 'v_bookx_size';
       //}
       //if($enable_bookx_volume == true) {
		$filelayout[] = 'v_bookx_volume';
       //}
        //if($enable_bookx_pages == true) {
		$filelayout[] = 'v_bookx_pages';
        //}
        //if($enable_bookx_publishing_date == true) {
		$filelayout[] = 'v_bookx_publishing_date'; 
        //}
        //if($enable_bookx_author_name == true) {        
		$filelayout[] = 'v_bookx_author_name';
        //}
        //if ($enable_bookx_author_type == true) {
        foreach ($langcode as $key => $lang) {
          $l_id = $lang['id'];
		  $filelayout[] = 'v_bookx_author_type_'.$l_id;
        }
        //}		
    }

    }

    // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_SELECT');
    function updateEP4ExtraFunctionsSetFilelayoutFullSQLSelect(&$callingClass, $notifier, $paramsArray) {
        
    }

    // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_TABLE');
    function updateEP4ExtraFunctionsSetFilelayoutFullSQLTable(&$callingClass, $notifier, $paramsArray) {
        
    }

    // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_FILELAYOUT');
    function updateEP4ExtraFunctionsSetFilelayoutCategoryFilelayout(&$callingClass, $notifier, $paramsArray) {
        
    }

    //  $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_SQL_SELECT');
    function updateEP4ExtraFunctionsSetFilelayoutCategorySQLSelect(&$callingClass, $notifier, $paramsArray) {
        
    }

    //  $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORYMETA_FILELAYOUT');
    function updateEP4ExtraFunctionsSetFilelayoutCategorymetaFilelayout(&$callingClass, $notifier, $paramsArray) {
        
    }

    // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CASE_DEFAULT');
    function updateEP4ExtraFunctionsSetFilelayoutCaseDefault(&$callingClass, $notifier, $paramsArray) {

        global $zco_notifier, $ep_dltype, $filelayout, $filelayout_sql, $langcode, $bookx_product_type, $check_bookx_export_metatags, $sql_filter;

        switch ($ep_dltype) {
            case 'bookx':
                $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_START'); // Because this grouping is now set aside, anything that is in this observer and others, really should be brought into this for the most part because anything modified there affects here as well...

                // The file layout is dynamically made depending on the number of languages
                $filelayout[] = 'v_products_model';
                $filelayout[] = 'v_status'; // this should be v_products_status for clarity
                $filelayout[] = 'v_products_type'; // 4-23-2012
                $filelayout[] = 'v_products_image';
                foreach ($langcode as $key => $lang) { // create variables for each language id
                    $l_id = $lang['id'];
                    $filelayout[] = 'v_products_name_' . $l_id;
                    $filelayout[] = 'v_products_description_' . $l_id;
                    $filelayout[] = 'v_products_url_' . $l_id;
                }
                $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT');

                $filelayout[] = 'v_specials_price';
                $filelayout[] = 'v_specials_date_avail';
                $filelayout[] = 'v_specials_expires_date';
                $filelayout[] = 'v_products_price';
                $filelayout[] = 'v_products_weight';
                $filelayout[] = 'v_date_avail'; // should be changed to v_products_date_available for clarity
                $filelayout[] = 'v_date_added'; // should be changed to v_products_date_added for clarity
                $filelayout[] = 'v_products_quantity';
                $filelayout[] = 'v_manufacturers_name';
                // NEW code for 'unlimited' category depth - 1 Category Column for each installed Language
                foreach ($langcode as $key => $lang) { // create categories variables for each language id
                    $l_id = $lang['id'];
                    $filelayout[] = 'v_categories_name_' . $l_id;
                }
                $filelayout[] = 'v_tax_class_title';
                $filelayout[] = 'v_bookx_publisher_name';
                $filelayout[] = 'v_bookx_imprint_name';
                foreach ($langcode as $key => $lang) { // create variables for each language id
                    $l_id = $lang['id'];
                    $filelayout[] = 'v_bookx_subtitle_' . $l_id;
                    $filelayout[] = 'v_bookx_genre_name_' . $l_id;
                    $filelayout[] = 'v_bookx_series_name_' . $l_id;
                    $filelayout[] = 'v_bookx_binding_' . $l_id;
                    $filelayout[] = 'v_bookx_printing_' . $l_id;
                    $filelayout[] = 'v_bookx_condition_' . $l_id;
                }

                $filelayout[] = 'v_bookx_isbn';
                $filelayout[] = 'v_bookx_size';
                $filelayout[] = 'v_bookx_volume';
                $filelayout[] = 'v_bookx_pages';
                $filelayout[] = 'v_bookx_publishing_date';
                $filelayout[] = 'v_bookx_author_name';
                foreach ($langcode as $key => $lang) {
                    $l_id = $lang['id'];
                    $filelayout[] = 'v_bookx_author_type_' . $l_id;
                }



                // metatags - 4-23-2012: added switch
                if ((int) EASYPOPULATE_4_CONFIG_META_DATA && (file_exists($check_bookx_export_metatags))) {
                    $filelayout[] = 'v_metatags_products_name_status';
                    $filelayout[] = 'v_metatags_title_status';
                    $filelayout[] = 'v_metatags_model_status';
                    $filelayout[] = 'v_metatags_price_status';
                    $filelayout[] = 'v_metatags_title_tagline_status';
                    foreach ($langcode as $key => $lang) { // create variables for each language id
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_metatags_title_' . $l_id;
                        $filelayout[] = 'v_metatags_keywords_' . $l_id;
                        $filelayout[] = 'v_metatags_description_' . $l_id;
                    }
                }

                $filelayout_sql = 'SELECT DISTINCT

			p.products_id					as v_products_id,
			p.products_model				as v_products_model,
			p.products_type					as v_products_type,
			p.products_image				as v_products_image,
			p.products_price				as v_products_price,';

                $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_SELECT');


                $filelayout_sql .= ' p.products_weight as v_products_weight,
			p.products_date_available		as v_date_avail,
			p.products_date_added			as v_date_added,
			p.products_tax_class_id			as v_tax_class_id,
			p.products_quantity				as v_products_quantity,
			p.master_categories_id				as v_master_categories_id,
			p.manufacturers_id				as v_manufacturers_id,
			subc.categories_id				as v_categories_id,
			p.products_status				as v_status, ';
                if (file_exists($check_bookx_export_metatags)) {
                    $filelayout_sql .= '
                        p.metatags_title_status         as v_metatags_title_status,
			p.metatags_products_name_status as v_metatags_products_name_status,
			p.metatags_model_status         as v_metatags_model_status,
			p.metatags_price_status         as v_metatags_price_status,
			p.metatags_title_tagline_status as v_metatags_title_tagline_status, ';
                }
                $filelayout_sql .= '        
                        be.bookx_series_id, be.bookx_binding_id, be.bookx_printing_id, be.bookx_condition_id,
                        be.publishing_date                  AS v_bookx_publishing_date,
                        be.pages                            AS v_bookx_pages, 
                        be.volume                           AS v_bookx_volume, 
                        be.size                             AS v_bookx_size, 
                        be.isbn                             AS v_bookx_isbn,
                        bp.publisher_name                   AS v_bookx_publisher_name, 
                        bi.imprint_name                     AS v_bookx_imprint_name 
			FROM '
                        . TABLE_CATEGORIES . ' AS subc, '
                        . TABLE_PRODUCTS_TO_CATEGORIES . ' AS ptoc, '
                        . TABLE_PRODUCTS . ' AS p, ' 
                         . TABLE_PRODUCT_BOOKX_EXTRA . ' AS be
                        LEFT JOIN ' . TABLE_PRODUCT_BOOKX_PUBLISHERS . ' AS bp ON be.bookx_publisher_id = bp.bookx_publisher_id
                        LEFT JOIN ' . TABLE_PRODUCT_BOOKX_IMPRINTS . ' AS bi ON be.bookx_imprint_id = bi.bookx_imprint_id ';
                $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_TABLE');

                $filelayout_sql .= ' WHERE p.products_type = ' . $bookx_product_type . ' AND p.products_id = ptoc.products_id 
                        AND p.products_id = be.products_id AND ';
                $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_WHERE_FILELAYOUT_FULL_SQL_TABLE');

                $filelayout_sql .= '
			ptoc.categories_id = subc.categories_id ' . $sql_filter;
                break;

            case 'TEST_2':
                break;

            case 'TEST_3':
                break;
        }
    }

    //  $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_INSTALL_END');
    function updateEP4ExtraFunctionsInstallEnd(&$callingClass, $notifier, $paramsArray) {
        global $db, $project;
        $group_id = $paramsArray['group_id'];

        if ((substr($project, 0, 5) == "1.3.8") || (substr($project, 0, 5) == "1.3.9")) {
            $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
        ('Enable Products Bookx','EASYPOPULATE_4_CONFIG_BOOKX_DATA', '0', 'Enable Products Bookx Data Columns
        (default 0).<br><br>0=Disable<br>1=Enable', " . $group_id . ", '230', NULL, now(), NULL, 'zen_cfg_select_option(array(\"0\", \"1\"),')
	  ");
        } elseif (PROJECT_VERSION_MAJOR > '1' || PROJECT_VERSION_MINOR >= '5.0') {
            $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
        ('Enable Products Bookx','EASYPOPULATE_4_CONFIG_BOOKX_DATA', '0', 'Enable Products Bookx Data Columns
        (default 0).<br><br>0=Disable<br>1=Enable', " . $group_id . ", '230', NULL, now(), NULL, 'zen_cfg_select_option(array(\"0\", \"1\"),')
	  ");
        } else { // unsupported version
            // i should do something here!
        }
    }

    // $zco_notifier->notify('EP4_LINK_SELECTION_END');
    function updateEP4LinkSelectionEnd(&$callingClass, $notifier, $paramsArray) {
        global $ep_bookx, $request_type;
        
        if ($ep_bookx == 1) {
            ?> 
            <!-- @altered for bookx -->
            <br />
            <b><?php echo EASYPOPULATE_4_DISPLAY_TITLE_BOOKX_FILES; ?></b>
            <br />
            <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx', $request_type); ?>"><?php echo EASYPOPULATE_4_DISPLAY_BOOKX_PRODUCTS; ?></a>
            <br />
            <!-- <a href="easypopulate_4.php?export=bookx">Export Authors</a> -->
            <br />
            <?php
        }
    }

    // $zco_notifier->notify('EP4_FILENAMES');
    function updateEP4Filenames(&$callingClass, $notifier, $paramsArray) {
        global $filenames;

        $filenames = array_merge($filenames, array('bookx-ep' => BOOKX_EP_DESC,
            'bookx-auth-ep' => BOOKX_AUTH_EP_DESC)
        );
    }

    // 'EP4_EXPORT_FILE_ARRAY_START'
    function updateEP4ExportFileArrayStart(&$callingClass, $notifier, $paramsArray) { // mc12345678 doesn't work on ZC 1.5.1 and below
        //global $ep_dltype, $filelayout_sql, $ep_uses_mysqli, $filelayout, $row;
    }

    // 'EP4_EXPORT_CASE_EXPORT_FILE_END'
    function updateEP4ExportCaseExportFileEnd(&$callingClass, $notifier, $paramsArray) {
        global $ep_dltype, $EXPORT_FILE,$nanobar ;

        if ($ep_dltype == 'bookx') {
            $EXPORT_FILE = 'BookX-EP';
            $nanobar = true; // 
        } elseif ($ep_dltype == 'DEMO-2') {
            $EXPORT_FILE = 'DEMO-2-EP';
        } elseif ($ep_dltype == 'DEMO-3') {
            $EXPORT_FILE = 'DEMO-3-EP';
        }
    }

    // EP4_EXPORT_WHILE_START
    function updateEP4ExportWhileStart(&$callingClass, $notifier, $paramsArray) {
        global $result;
        pr($result);
    }

    //$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK');
    function updateEP4ExportLoopFullOrSBAStock(&$callingClass, $notifier, $paramsArray) {
        
    }

    //  $zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_LOOP');
    function updateEP4ExportLoopFullOrSBAStockLoop(&$callingClass, $notifier, $paramsArray) {
        
    }

//	$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_END');
    function updateEP4ExportLoopFullOrSBAStockEnd(&$callingClass, $notifier, $paramsArray) {
        
    }

//  $zco_notifier->notify('EP4_EXPORT_SPECIALS_AFTER');
    function updateEP4ExportSpecialsAfter(&$callingClass, $notifier, $paramsArray) {
        global $ep_dltype, $db, $filelayout_sql, $ep_uses_mysqli, $filelayout, 
          $row, $langcode, $epdlanguage_id, $ep_bookx, $category_delimiter;
        if ($ep_dltype == 'bookx' && $ep_bookx == 1) {
            include DIR_FS_ADMIN . 'easypopulate_4_export_bookx.php';
        }
    }

    //     $notifyme[] = 'EP4_IMPORT_START';
    function updateEP4ImportStart(&$callingClass, $notifier, $paramsArray) {
        global $bookx_reports;

        /* [It aggregates missing fields in a report linked to the imported book. Uses Bookx languages files as key so it can be tranlated ie: BOX_CATALOG_PRODUCT_BOOKX_PUBLISHERS]
         * @see   [adminFolder/includes/languades/YOUR_lang/extra_definitions/product_bookx.php]
         * @var array
         */
        $bookx_reports = array();
    }

    // EP4_IMPORT_FILE_EARLY_ROW_PROCESSING
    function updateEP4ImportFileEarlyRowProcessing(&$callingClass, $notifier, $paramsArray) {
        global $items, $filelayout, $display_output,  $continueNextRow;

        if ($items[$filelayout['v_status']] == 10) {
            $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_DELETED, $items[$filelayout['v_products_model']], $items[$filelayout['v_bookx_isbn']]);
            // Using Bookx function to remove books.
            // @todo Remove from bookx_extra_description 
            ep_4_remove_product_bookx($items[$filelayout['v_products_model']]);
            $continueNextRow = true;
        }
    }
    
    // EP4_IMPORT_AFTER_CATEGORY
   function updateEP4ImportAfterCategory(&$callingClass, $notifier, $paramsArray){
    global $v_products_id, $bookx_product_type, $enable_bookx_genre_name, $langcode,
           $items, $filelayout, $db, $bookx_reports, $v_bookx_isbn, $v_bookx_genre_name,
           $display_output, $ep_error_count,
           $bookx_author_name_max_len, $bookx_author_types_name_max_len,
           $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len,
           $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len,
           $bookx_imprint_name_max_len, $bookx_subtitle_max_len, $
    $bookx_default_author_name, $bookx_default_author_type, $bookx_default_printing,
    $bookx_default_binding, $bookx_default_genre_name, $bookx_default_publisher_name,
    $bookx_default_series_name, $bookx_default_imprint_name, $bookx_default_condition,
    $report_bookx_subtitle, $report_bookx_genre_name, $report_bookx_publisher_name,
    $report_bookx_series_name, $report_bookx_imprint_name, $report_bookx_binding,
    $report_bookx_printing, $report_bookx_condition, $report_bookx_isbn,
    $report_bookx_size, $report_bookx_volume, $report_bookx_pages,
    $report_bookx_publishing_date, $report_bookx_author_name, $report_bookx_author_type;
     //include the bookx import file.   
    /**
     * @EP4Bookx 4 of 5
     * At last but not least , include the bookx import file. Try to stay clean
     */
     include DIR_FS_ADMIN . 'easypopulate_4_import_bookx.php';
    //end ep4bookx
   }
 
    // EP4_IMPORT_FILE_NEW_PRODUCT_PRODUCT_TYPE
    function updateEP4ImportFileNewProductProductType(&$callingClass, $notifier, $paramsArray) {
        global $v_products_type, $v_artists_name, $bookx_product_type, $ep_bookx;

        // Assign product_type to bookx product type (overriding any previous product type assignment)
        //  if the either of the applicable bookx product type fields are populated.  Otherwise,
        //  will use the default which is a product type of 1 (generic product), this means that
        //  for all new product, at least one of these fields must be included as a part of this
        //  addin in order for the product type to be properly entered.
        if ($ep_bookx == 1) {
            $v_products_type = $bookx_product_type;
        }
    }

    //	 $notifyme[] = 'EP4_IMPORT_END';
  //  function updateEP4ImportEnd(&$callingClass, $notifier, $paramsArray) {
  //      global $bookx_reports, $edit_link, $report_bookx_subtitle, $report_bookx_genre_name, $report_bookx_publisher_name, $report_bookx_series_name, $report_bookx_imprint_name, $report_bookx_binding, $report_bookx_printing, $report_bookx_condition, $report_bookx_isbn, $report_bookx_size, $report_bookx_volume, $report_bookx_pages, $report_bookx_publishing_date, $report_bookx_author_name, $report_bookx_author_type;
        //include DIR_FS_ADMIN . 'easypopulate_4_import_bookx.php';
   // }

    // EP4_IMPORT_FILE_PRE_DISPLAY_OUTPUT
    function updateEP4ImportFilePreDisplayOutput(&$callingClass, $notifier, $paramsArray) {
        global $bookx_reports, $display_output;
        /**
         * @EP4Bookx
         * Reports missing fields with the book edit link
         */
        if (!empty($bookx_reports)) {
            $display_output .= '<table class="bookx-reports"><caption>' . EASYPOPULATE_4_DISPLAY_BOOKX_REPORTS_BOOKX_HEADER . '</caption><tr class="bookx-reports-top"><th >Type</th><th>' . EASYPOPULATE_4_BOOKX_TABLE_BOOK . '</th></tr>';

            foreach ($bookx_reports as $key => $value) {
                $display_output .= '<tr><th class="bookx-reports-th-left" rowspan ="' . (count($value) + 1) . '">' . strtoupper($key) . '</th>';
                $display_output .= '<th class="bookx-reports-th-caption">' . EASYPOPULATE_4_BOOKX_TABLE_CAPTION . '</th></tr>';

                $lastKey = count($value) - 1;

                for ($i = 0; $i < (count($value)); $i++) {
                    $class = ($i & 1) ? 'odd' : 'even';
                    ($i == $lastKey ? $class .=' last' : '');
                    $display_output .= '<tr ><td class="' . $class . '">' . $value[$i] . '</td></tr>';
                }
            }
            $display_output .='</table>';
        }
    }

    function update(&$callingClass, $notifier, $paramsArray) {

        if ($notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_START') {
            $this->updateEP4ExtraFunctionsSetFilelayoutFullStart($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_COLLATION_UTF8_ZC13X');
        if ($notifier == 'EP4_COLLATION_UTF8_ZC13X') {
            $this->updateEP4CollationUTF8ZC13x($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_EASYPOPULATE_4_LINK');
        if ($notifier == 'EP4_EASYPOPULATE_4_LINK') {
            $this->updateEP4Easypopulate4Link($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_DISPLAY_STATUS');
        if ($notifier == 'EP4_DISPLAY_STATUS') {
            $this->updateEP4DisplayStatus($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_MAX_LEN');
        if ($notifier == 'EP4_MAX_LEN') {
            $this->updateEP4MaxLen($callingClass, $notifier, $paramsArray);
        }

        //$zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT');
        if ($notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT') {
            $this->updateEP4ExtraFunctionsSetFilelayoutFullFilelayout($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_SELECT');
        if ($notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_SELECT') {
            $this->updateEP4ExtraFunctionsSetFilelayoutFullSQLSelect($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_TABLE');
        if ($notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_TABLE') {
            $this->updateEP4ExtraFunctionsSetFilelayoutFullSQLTable($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_FILELAYOUT');
        if ($notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_FILELAYOUT') {
            $this->updateEP4ExtraFunctionsSetFilelayoutCategoryFilelayout($callingClass, $notifier, $paramsArray);
        }

        //  $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_SQL_SELECT');
        if ($notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_SQL_SELECT') {
            $this->updateEP4ExtraFunctionsSetFilelayoutCategorySQLSelect($callingClass, $notifier, $paramsArray);
        }

        //  $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORYMETA_FILELAYOUT');
        if ($notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORYMETA_FILELAYOUT') {
            $this->updateEP4ExtraFunctionsSetFilelayoutCategorymetaFilelayout($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CASE_DEFAULT');
        if ($notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CASE_DEFAULT') {
            $this->updateEP4ExtraFunctionsSetFilelayoutCaseDefault($callingClass, $notifier, $paramsArray);
        }

        //  $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_INSTALL_END');
        if ($notifier == 'EP4_EXTRA_FUNCTIONS_INSTALL_END') {
            $this->updateEP4ExtraFunctionsInstallEnd($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_LINK_SELECTION_END');
        if ($notifier == 'EP4_LINK_SELECTION_END') {
            $this->updateEP4LinkSelectionEnd($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_FILENAMES');
        if ($notifier == 'EP4_FILENAMES') {
            $this->updateEP4Filenames($callingClass, $notifier, $paramsArray);
        }

        // 'EP4_EXPORT_FILE_ARRAY_START'
        if ($notifier == 'EP4_EXPORT_FILE_ARRAY_START') {
            $this->updateEP4ExportFileArrayStart($callingClass, $notifier, $paramsArray); // mc12345678 doesn't work on ZC 1.5.1 and below
        }

        // 'EP4_EXPORT_CASE_EXPORT_FILE_END'
        if ($notifier == 'EP4_EXPORT_CASE_EXPORT_FILE_END') {
            $this->updateEP4ExportCaseExportFileEnd($callingClass, $notifier, $paramsArray);
        }

        // EP4_EXPORT_WHILE_START
        if ($notifier == 'EP4_EXPORT_WHILE_START') {
            $this->updateEP4ExportWhileStart($callingClass, $notifier, $paramsArray);
        }

        //$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK');
        if ($notifier == 'EP4_EXPORT_LOOP_FULL_OR_SBASTOCK') {
            $this->updateEP4ExportLoopFullOrSBAStock($callingClass, $notifier, $paramsArray);
        }

        //  $zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_LOOP');
        if ($notifier == 'EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_LOOP') {
            $this->updateEP4ExportLoopFullOrSBAStockLoop($callingClass, $notifier, $paramsArray);
        }

//	$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_END');
        if ($notifier == 'EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_END') {
            $this->updateEP4ExportLoopFullOrSBAStockEnd($callingClass, $notifier, $paramsArray);
        }

//  $zco_notifier->notify('EP4_EXPORT_SPECIALS_AFTER');
        if ($notifier == 'EP4_EXPORT_SPECIALS_AFTER') {
            $this->updateEP4ExportSpecialsAfter($callingClass, $notifier, $paramsArray);
        }

//  $zco_notifier->notify('EP4_EXPORT_FULL_OR_CAT_FULL_AFTER');
    if ($notifier == 'EP4_EXPORT_FULL_OR_CAT_FULL_AFTER') {
      $this->updateEP4ExportFullOrCatFullAfter($callingClass, $notifier, $paramsArray);
    }
    
        if ($notifier == 'EP4_IMPORT_START') {
            $this->updateEP4ImportStart($callingClass, $notifier, $paramsArray);
        }

        if ($notifier == 'EP4_IMPORT_FILE_EARLY_ROW_PROCESSING') {
            $this->updateEP4ImportFileEarlyRowProcessing($callingClass, $notifier, $paramsArray);
        }
        
        if ($notifier == 'EP4_IMPORT_AFTER_CATEGORY') {
            $this->updateEP4ImportAfterCategory($callingClass, $notifier, $paramsArray);
        }
   
        if ($notifier == 'EP4_IMPORT_FILE_NEW_PRODUCT_PRODUCT_TYPE') {
            $this->updateEP4ImportFileNewProductProductType($callingClass, $notifier, $paramsArray);
        }

        if ($notifier == 'EP4_IMPORT_FILE_PRE_DISPLAY_OUTPUT') {
            $this->updateEP4ImportFilePreDisplayOutput($callingClass, $notifier, $paramsArray);
        }
    }

// EOF Update()
}
