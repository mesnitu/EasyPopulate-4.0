<?php

/**
 * Description of class.ep4bookx.
 *
 * @author mc12345678
 * @contribution mesnitu
 */
class ep4bookx extends base {

    //private $_product = array();

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
        $notifyme[] = 'EP4_EASYPOPULATE_4_LINK_END'; // This is for placing js in the end of body
        $notifyme[] = 'EP4_IMPORT_FILE_END'; // For some reason, the only way

        $this->attach($this, $notifyme);
    }

    /* Function run/called by notifier: EP4_START */

    function updateEP4Start(&$callingClass, $notifier, $paramsArray) {
        global $db, $curver, $ep4bookx, $ep4bookx_version, $ep4bookx_fields_conf, $bookx_product_type, $messageStack, $toogle_config, $toogle_text, $ep4bookx_msg;
        global $bookx_author_name_max_len, $bookx_author_types_name_max_len,
        $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len,
        $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len,
        $bookx_imprint_name_max_len, $bookx_subtitle_name_max_len;

        $curver .= '<br />'; // Intent of this is to show that the option is available not that it is "active", though if that is desired instead, then suggest adding an "active/inactive"
        $curver .= 'w/ BookX' . $ep4bookx_version;

        if ( $ep4bookx == 1 ) {

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

            // Some messages
            switch ( $ep4bookx_msg ) {
                case 'ep4bookx_fen_cnf':
                    $messageStack->add(E4BOOKX_MSG_ENABLE_FIELDS_CONFIG, 'success');
                    break;
                case 'ep4bookx_fdis_cnf':
                    $messageStack->add(E4BOOKX_MSG_DISABLE_FIELDS_CONFIG, 'success');
                    break;
                case 'save_layout':
                    $messageStack->add(E4BOOKX_MSG_LAYOUT_SAVED, 'success');
                    break;
                default:
                    break;
            }
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
        global $ep4bookx, $ep4bookx_module_path, $ep4bookx_tpl_path;
        // load header scripts
        if ( $ep4bookx == 1 ) {
            include_once $ep4bookx_module_path . 'tpl/tpl_ep4bookx_header.php';
        }
    }

    // $zco_notifier->notify('EP4_EASYPOPULATE_4_LINK_END');
    function updateEP4Easypopulate4LinkEnd(&$callingClass, $notifier, $paramsArray) {
        global $ep4bookx, $ep4bookx_module_path, $ep4bookx_tpl_path, $ep4bookx_fields_conf;
        // load footer scripts
        if ( $ep4bookx == 1 ) {
            include_once $ep4bookx_module_path . 'tpl/tpl_ep4bookx_footer.php';
        }
    }

    // $zco_notifier->notify('EP4_DISPLAY_STATUS');
    function updateEP4DisplayStatus(&$callingClass, $notifier, $paramsArray) {
        global $ep4bookx;
        /*
         * @todo - $ep4bookx is a object
         */
        $ep_bookx = (int) EASYPOPULATE_4_CONFIG_BOOKX_DATA; // 0-Disable, 1-Enable
        echo EASYPOPULATE_4_DISPLAY_ENABLE_BOOKX . $ep_bookx . '<br/>';
    }

    // $zco_notifier->notify('EP4_MAX_LEN');
    function updateEP4MaxLen(&$callingClass, $notifier, $paramsArray) {
        global $bookx_author_name_max_len, $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len,
        $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len,
        $bookx_imprint_name_max_len, $bookx_subtitle_name_max_len, $ep4bookx;
        // Not sure if this is really usefull for a user  
        if ( $ep4bookx == 1 ) {
            echo 'author_name:' . $bookx_author_name_max_len . '<br/>';
            echo 'genre_description:' . $bookx_genre_name_max_len . '<br/>';
            echo 'series_name:' . $bookx_series_name_max_len . '<br/>';
            echo 'publisher_name:' . $bookx_publisher_name_max_len . '<br/>';
            echo 'binding_description:' . $bookx_binding_name_max_len . '<br/>';
            echo 'printing_description:' . $bookx_printing_name_max_len . '<br/>';
            echo 'condition_description:' . $bookx_condition_name_max_len . '<br/>';
            echo 'imprint_name:' . $bookx_imprint_name_max_len . '<br/>';
            echo 'subtitle_name:' . $bookx_subtitle_name_max_len . '<br/>';
        }
    }

    // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_START');
//    function updateEP4ExtraFunctionsSetFilelayoutFullStart(&$callingClass, $notifier, $paramsArray) {
//        
//    }
    //$zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT');
    function updateEP4ExtraFunctionsSetFilelayoutFullFilelayout(&$callingClass, $notifier, $paramsArray) {
        global $filelayout, $langcode, $ep4bookx_query, $ep4bookx_query_join, $ep4bookx_extra_sqlwhere, $ep4bookx_extra_sqlcol, $ep4bookx_extra_sqlbind, $epdlanguage_id, $ep4bookx_flag_import;
        global $enable_ep4bookx_genre_name, $enable_ep4bookx_publisher_name, $enable_ep4bookx_series_name, $enable_ep4bookx_imprint_name, $enable_ep4bookx_binding, $enable_ep4bookx_printing, $enable_ep4bookx_condition, $enable_ep4bookx_size, $enable_ep4bookx_volume, $enable_ep4bookx_pages, $enable_ep4bookx_publishing_date, $enable_ep4bookx_author_name, $enable_ep4bookx_author_type, $enable_ep4bookx_subtitle;
        global $bind_publisher, $bind_series, $bind_binding, $bind_printing, $bind_condition, $bind_binding, $bind_publishing_date, $bind_pages, $bind_volume, $bind_size, $bind_imprint;
//@ALTERED Bookx Info - 23-04-2015
// If import flag is 1, set the conditional querys to bookx_extra table
// IF Paradaise     
        if ( (int) EASYPOPULATE_4_CONFIG_BOOKX_DATA == true ) { // probably it could be removed
            if ( !$ep4bookx_flag_import ) { // Prevents all this to be attached to import filelayout
                $ep4bookx_query = '';

                if ( $enable_ep4bookx_subtitle == true ) {
                    foreach ( $langcode as $key => $lang ) { // create variables for each language id
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_subtitle_' . $l_id;
                    }
                }

                if ( $enable_ep4bookx_genre_name == true ) {
                    foreach ( $langcode as $key => $lang ) {
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_genre_name_' . $l_id;
                    }
                }

                if ( $enable_ep4bookx_publisher_name == true ) {
                    $filelayout[] = 'v_bookx_publisher_name';
                    $ep4bookx_query .= 'bp.publisher_name AS v_bookx_publisher_name, ';
                    $ep4bookx_query_join = 'LEFT JOIN ' . TABLE_PRODUCT_BOOKX_PUBLISHERS . ' AS bp ON be.bookx_publisher_id = bp.bookx_publisher_id ';
                }

                if ( $enable_ep4bookx_series_name == true ) {
                    foreach ( $langcode as $key => $lang ) {
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_series_name_' . $l_id; // Series name, as Lang ID
                    }
                    $ep4bookx_query .='be.bookx_series_id, ';
                }

                if ( $enable_ep4bookx_imprint_name == true ) {
                    $filelayout[] = 'v_bookx_imprint_name';
                    $ep4bookx_query .= 'bi.imprint_name AS v_bookx_imprint_name, ';
                    $ep4bookx_query_join .= 'LEFT JOIN ' . TABLE_PRODUCT_BOOKX_IMPRINTS . ' AS bi ON be.bookx_imprint_id = bi.bookx_imprint_id ';
                }

                if ( $enable_ep4bookx_binding == true ) {
                    foreach ( $langcode as $key => $lang ) {
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_binding_' . $l_id; //   Lang ID
                    }
                    $ep4bookx_query .= 'be.bookx_binding_id, ';
                }
                if ( $enable_ep4bookx_printing == true ) {
                    foreach ( $langcode as $key => $lang ) {
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_printing_' . $l_id; //   Lang ID
                    }
                    $ep4bookx_query .= 'be.bookx_printing_id, ';
                }
                if ( $enable_ep4bookx_condition == true ) {
                    foreach ( $langcode as $key => $lang ) {
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_condition_' . $l_id; //  Lang ID
                    }
                    $ep4bookx_query .= 'be.bookx_condition_id, ';
                }
                // ISBN is mandatory. At least one fields must get the values from bookx_extra table. ISBN is a good one     
                $filelayout[] = 'v_bookx_isbn';
                //$ep4bookx_query = 'be.isbn AS v_bookx_isbn';
                if ( $enable_ep4bookx_size == true ) {
                    $filelayout[] = 'v_bookx_size';
                    $ep4bookx_query .= 'be.size AS v_bookx_size, ';
                }
                if ( $enable_ep4bookx_volume == true ) {
                    $filelayout[] = 'v_bookx_volume';
                    $ep4bookx_query .= 'be.volume  AS v_bookx_volume, ';
                }
                if ( $enable_ep4bookx_pages == true ) {
                    $filelayout[] = 'v_bookx_pages';
                    $ep4bookx_query .= 'be.pages AS v_bookx_pages, ';
                }
                if ( $enable_ep4bookx_publishing_date == true ) {
                    $filelayout[] = 'v_bookx_publishing_date';
                    $ep4bookx_query .= 'be.publishing_date AS v_bookx_publishing_date, ';
                }
                if ( $enable_ep4bookx_author_name == true ) {
                    $filelayout[] = 'v_bookx_author_name';
                }
                if ( $enable_ep4bookx_author_type == true ) {
                    foreach ( $langcode as $key => $lang ) {
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_author_type_' . $l_id;
                    }
                }
            }
            //For import
            if ( $ep4bookx_flag_import == true ) {

                $ep4bookx_extra_sqlwhere = '';
                $ep4bookx_extra_sqlcol = '';
                $ep4bookx_extra_sqlbind = '';

                if ( isset($filelayout['v_bookx_publisher_name']) ) {
                    $ep4bookx_extra_sqlwhere .='bookx_publisher_id = :v_publisher_id:, ';
                    $bind_publisher = 1;
                    $ep4bookx_extra_sqlcol .= 'bookx_publisher_id, ';
                    $ep4bookx_extra_sqlbind .= ':v_publisher_id:, ';
                }

                if ( isset($filelayout['v_bookx_series_name_' . $epdlanguage_id]) ) {
                    $ep4bookx_extra_sqlwhere .='bookx_series_id = :v_series_id:, ';
                    $bind_series = 1;
                    $ep4bookx_extra_sqlcol .= 'bookx_series_id, ';
                    $ep4bookx_extra_sqlbind .= ':v_series_id:, ';
                }

                if ( isset($filelayout['v_bookx_binding_' . $epdlanguage_id]) ) {
                    $ep4bookx_extra_sqlwhere .='bookx_binding_id = :v_binding_id:, ';
                    $bind_binding = 1;
                    $ep4bookx_extra_sqlcol .= 'bookx_binding_id, ';
                    $ep4bookx_extra_sqlbind .= ':v_binding_id:, ';
                }

                if ( isset($filelayout['v_bookx_printing_' . $epdlanguage_id]) ) {
                    $ep4bookx_extra_sqlwhere .='bookx_printing_id =:v_printing_id:, ';
                    $bind_printing = 1;
                    $ep4bookx_extra_sqlcol .= 'bookx_printing_id, ';
                    $ep4bookx_extra_sqlbind .= ':v_printing_id:, ';
                }

                if ( isset($filelayout['v_bookx_condition_' . $epdlanguage_id]) ) {
                    $ep4bookx_extra_sqlwhere .='bookx_condition_id= :v_condition_id:, ';
                    $bind_condition = 1;
                    $ep4bookx_extra_sqlcol .= 'bookx_condition_id, ';
                    $ep4bookx_extra_sqlbind .= ':v_condition_id:, ';
                }

                if ( isset($filelayout['v_bookx_imprint_name']) ) {
                    $ep4bookx_extra_sqlwhere .='bookx_imprint_id = :v_imprint_id:, ';
                    $bind_imprint = 1;
                    $ep4bookx_extra_sqlcol .= 'bookx_imprint_id, ';
                    $ep4bookx_extra_sqlbind .= ':v_imprint_id:, ';
                }

                if ( isset($filelayout['v_bookx_publishing_date']) ) {
                    $ep4bookx_extra_sqlwhere .='publishing_date = :v_bookx_publishing_date:, ';
                    $bind_publishing_date = 1;
                    $ep4bookx_extra_sqlcol .= 'publishing_date, ';
                    $ep4bookx_extra_sqlbind .= ':v_bookx_publishing_date:, ';
                }

                if ( isset($filelayout['v_bookx_pages']) ) {
                    $ep4bookx_extra_sqlwhere .='pages = :v_bookx_pages:, ';
                    $bind_pages = 1;
                    $ep4bookx_extra_sqlcol .= 'pages, ';
                    $ep4bookx_extra_sqlbind .= ':v_bookx_pages:, ';
                }

                if ( isset($filelayout['v_bookx_volume']) ) {
                    $ep4bookx_extra_sqlwhere .='volume = :v_bookx_volume:, ';
                    $bind_volume = 1;
                    $ep4bookx_extra_sqlcol .= 'volume, ';
                    $ep4bookx_extra_sqlbind .= ':v_bookx_volume:, ';
                }

                if ( isset($filelayout['v_bookx_size']) ) {
                    $ep4bookx_extra_sqlwhere .='size = :v_bookx_size:, ';
                    $bind_size = 1;
                    $ep4bookx_extra_sqlcol .= 'size, ';
                    $ep4bookx_extra_sqlbind .= ':v_bookx_size:, ';
                }
            }  //ends flag_import
        } //ends if data TRUE
    }

//    // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_SELECT');
//    function updateEP4ExtraFunctionsSetFilelayoutFullSQLSelect(&$callingClass, $notifier, $paramsArray) {      
//    }
//    // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_TABLE');
//    function updateEP4ExtraFunctionsSetFilelayoutFullSQLTable(&$callingClass, $notifier, $paramsArray) {      
//    }
//    // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_FILELAYOUT');
//    function updateEP4ExtraFunctionsSetFilelayoutCategoryFilelayout(&$callingClass, $notifier, $paramsArray) {      
//    }
//    //  $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_SQL_SELECT');
//    function updateEP4ExtraFunctionsSetFilelayoutCategorySQLSelect(&$callingClass, $notifier, $paramsArray) {       
//    }
//    //  $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORYMETA_FILELAYOUT');
//    function updateEP4ExtraFunctionsSetFilelayoutCategorymetaFilelayout(&$callingClass, $notifier, $paramsArray) {       
//    }
    // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CASE_DEFAULT');
    function updateEP4ExtraFunctionsSetFilelayoutCaseDefault(&$callingClass, $notifier, $paramsArray) {

        global $zco_notifier, $ep_dltype, $filelayout, $filelayout_sql, $langcode, $bookx_product_type, $enable_ep4bookx_specials, $enable_ep4bookx_metatags, $enable_ep4bookx_manufacturers, $enable_ep4bookx_weight, $sql_filter, $ep4bookx_query, $ep4bookx_query_join;

        switch ( $ep_dltype ) {
            case 'bookx':

                $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_START'); // Because this grouping is now set aside, anything that is in this observer and others, really should be brought into this for the most part because anything modified there affects here as well...
                // The file layout is dynamically made depending on the number of languages
                $filelayout[] = 'v_products_model';
                $filelayout[] = 'v_status'; // this should be v_products_status for clarity
                $filelayout[] = 'v_products_type'; // 4-23-2012
                $filelayout[] = 'v_products_image';
                foreach ( $langcode as $key => $lang ) { // create variables for each language id
                    $l_id = $lang['id'];
                    $filelayout[] = 'v_products_name_' . $l_id;
                    $filelayout[] = 'v_products_description_' . $l_id;
                    $filelayout[] = 'v_products_url_' . $l_id;
                }
                $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT');
                if ( $enable_ep4bookx_specials == 1 ) {
                    $filelayout[] = 'v_specials_price';
                    $filelayout[] = 'v_specials_date_avail';
                    $filelayout[] = 'v_specials_expires_date';
                }
                $filelayout[] = 'v_products_price';
                if ( $enable_ep4bookx_weight == 1 ) {
                    $filelayout[] = 'v_products_weight';
                }
                $filelayout[] = 'v_date_avail'; // should be changed to v_products_date_available for clarity
                $filelayout[] = 'v_date_added'; // should be changed to v_products_date_added for clarity
                $filelayout[] = 'v_products_quantity';

                if ( $enable_ep4bookx_manufacturers == 1 ) {
                    $filelayout[] = 'v_manufacturers_name';
                }

                foreach ( $langcode as $key => $lang ) { // create categories variables for each language id
                    $l_id = $lang['id'];
                    $filelayout[] = 'v_categories_name_' . $l_id;
                }

                $filelayout[] = 'v_tax_class_title';

                // metatags - 4-23-2012: added switch
                if ( (int) EASYPOPULATE_4_CONFIG_META_DATA && $enable_ep4bookx_metatags == 1 ) {
                    $filelayout[] = 'v_metatags_products_name_status';
                    $filelayout[] = 'v_metatags_title_status';
                    $filelayout[] = 'v_metatags_model_status';
                    $filelayout[] = 'v_metatags_price_status';
                    $filelayout[] = 'v_metatags_title_tagline_status';
                    foreach ( $langcode as $key => $lang ) { // create variables for each language id
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

                if ( $enable_ep4bookx_weight == 1 ) {
                    $filelayout_sql .= ' p.products_weight as v_products_weight, ';
                }
                $filelayout_sql .= '
			p.products_date_available		as v_date_avail,
			p.products_date_added			as v_date_added,
			p.products_tax_class_id			as v_tax_class_id,
			p.products_quantity				as v_products_quantity,
			p.master_categories_id		    as v_master_categories_id,
			p.manufacturers_id				as v_manufacturers_id,
			subc.categories_id				as v_categories_id,
			p.products_status				as v_status, ';
                if ( $enable_ep4bookx_metatags == 1 ) {
                    $filelayout_sql .= '
                        p.metatags_title_status         as v_metatags_title_status,
			p.metatags_products_name_status as v_metatags_products_name_status,
			p.metatags_model_status         as v_metatags_model_status,
			p.metatags_price_status         as v_metatags_price_status,
			p.metatags_title_tagline_status as v_metatags_title_tagline_status, ';
                }
                $filelayout_sql .= $ep4bookx_query;
                $filelayout_sql .= ' be.isbn AS v_bookx_isbn         

			FROM '
                    . TABLE_CATEGORIES . ' AS subc, '
                    . TABLE_PRODUCTS_TO_CATEGORIES . ' AS ptoc, '
                    . TABLE_PRODUCTS . ' AS p, '
                    . TABLE_PRODUCT_BOOKX_EXTRA . ' AS be ';
                $filelayout_sql .= $ep4bookx_query_join;
                $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_TABLE');

                $filelayout_sql .= ' WHERE p.products_type = ' . $bookx_product_type . ' AND p.products_id = ptoc.products_id 
                        AND p.products_id = be.products_id AND ';
                $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_WHERE_FILELAYOUT_FULL_SQL_TABLE');

                $filelayout_sql .= '
			ptoc.categories_id = subc.categories_id ' . $sql_filter;
                //pr($filelayout_sql);
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

        if ( (substr($project, 0, 5) == "1.3.8") || (substr($project, 0, 5) == "1.3.9") ) {
            $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
        ('Enable Products Bookx','EASYPOPULATE_4_CONFIG_BOOKX_DATA', '0', 'Enable Products Bookx Data Columns
        (default 0).<br><br>0=Disable<br>1=Enable', " . $group_id . ", '230', NULL, now(), NULL, 'zen_cfg_select_option(array(\"0\", \"1\"),')
	  ");

            $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
        ('Enable Ep4Bookx Fields Configuration','EASYPOPULATE_4_ENABLE_FIELDS_CONFIG_BOOKX_DATA', '0', 'Enable Ep4Bookx fields configuration. <br>This will load the table to config the fields.
        (default 0).<br><br>0=Disable<br>1=Enable', " . $group_id . ", '231', NULL, now(), NULL, 'zen_cfg_select_option(array(\"0\", \"1\"),')
	  ");
        } elseif ( PROJECT_VERSION_MAJOR > '1' || PROJECT_VERSION_MINOR >= '5.0' ) {
            $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
        ('Enable Products Bookx','EASYPOPULATE_4_CONFIG_BOOKX_DATA', '0', 'Enable Products Bookx Data Columns
        (default 0).<br><br>0=Disable<br>1=Enable', " . $group_id . ", '230', NULL, now(), NULL, 'zen_cfg_select_option(array(\"0\", \"1\"),')
	  ");

            $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
        ('Enable Ep4Bookx Fields Configuration','EASYPOPULATE_4_ENABLE_FIELDS_CONFIG_BOOKX_DATA', '0', 'Enable Ep4Bookx fields configuration. <br>This will load the table to config the fields.
        (default 0).<br><br>0=Disable<br>1=Enable', " . $group_id . ", '231', NULL, now(), NULL, 'zen_cfg_select_option(array(\"0\", \"1\"),')
	  ");
        } else { // unsupported version
            // i should do something here!
        }
    }

    // $zco_notifier->notify('EP4_LINK_SELECTION_END');
    function updateEP4LinkSelectionEnd(&$callingClass, $notifier, $paramsArray) {
        global $ep4bookx, $request_type, $ep4bookx_module_path, $ep4bookx_customize_files, $ep4bookx_fields_conf, $toogle_config, $toogle_text;
        global $bookx_author_name_max_len, $bookx_author_types_name_max_len,
        $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len,
        $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len,
        $bookx_imprint_name_max_len, $bookx_subtitle_max_len, $parsedown, $text;
        //global $file_location, $file, $ep4bookx_csv, $tempdir, $csv_delimiter, $csv_enclosure, $ly, $display_output;
        // Toogle Quick Enable / Disable fields table link
        switch ( $ep4bookx_fields_conf ) {
            case '1':
                $toogle_config = 'ep4bookx_fdis_cnf';
                $toogle_text = EP4BOOK_QUICK_EDIT_DISABLE;
                break;
            default:
                $toogle_config = 'ep4bookx_fen_cnf';
                $toogle_text = EP4BOOK_QUICK_EDIT_ENABLE;
                break;
        }
        /**
         * @todo Map the fields  
          $file_location = (EP4_ADMIN_TEMP_DIRECTORY !== 'true' ?  DIR_FS_CATALOG :  DIR_FS_ADMIN) . $tempdir;
          $ep4bookx_csv = ep4bookx_list_layouts($file_location, '.csv', false);
          $headers = $file_location.current($ep4bookx_csv).'.csv';
          if (!file_exists($file_location)) {
          $display_output .='<font color="red"><b>ERROR: Import file does not exist:' . $headers . '</b></font><br/>';
          } else if (!($handle = fopen($headers, "r"))) {
          $display_output .= '<font color="red"><b>ERROR: Cannot open import file:' . $headers . '</b></font><br/>';
          }
          // Read Column Headers
          if ($raw_headers = fgetcsv($handle, 0, $csv_delimiter, $csv_enclosure)) {
          $filelayout = array_flip($raw_headers);
          }
         */
        /**
         * @todo Should check if all this $ep4bookx == 1 are really necessary, since all this as been started in init file
         */
        if ( $ep4bookx == 1 ) {

            // will load the ep4bookx fields form          
            include_once $ep4bookx_module_path . 'tpl/tpl_ep4bookx_fields.php';
        }
    }

    // $zco_notifier->notify('EP4_FILENAMES');
    function updateEP4Filenames(&$callingClass, $notifier, $paramsArray) {
        global $filenames;

        $filenames = array_merge($filenames, array(
            'bookx-ep' => BOOKX_EP_DESC,
            'bookx-auth-ep' => BOOKX_AUTH_EP_DESC)
        );
    }

//    // 'EP4_EXPORT_FILE_ARRAY_START'
//    function updateEP4ExportFileArrayStart(&$callingClass, $notifier, $paramsArray) { // mc12345678 doesn't work on ZC 1.5.1 and below
//        //global $ep_dltype, $filelayout_sql, $ep_uses_mysqli, $filelayout, $row;
//    }
    // 'EP4_EXPORT_CASE_EXPORT_FILE_END'
    function updateEP4ExportCaseExportFileEnd(&$callingClass, $notifier, $paramsArray) {
        global $ep_dltype, $EXPORT_FILE, $nanobar;

        if ( $ep_dltype == 'bookx' ) {
            $EXPORT_FILE = 'BookX-EP';
            $nanobar = true; // 
        } elseif ( $ep_dltype == 'DEMO-2' ) {
            $EXPORT_FILE = 'DEMO-2-EP';
        } elseif ( $ep_dltype == 'DEMO-3' ) {
            $EXPORT_FILE = 'DEMO-3-EP';
        }
    }

    // EP4_EXPORT_WHILE_START
    function updateEP4ExportWhileStart(&$callingClass, $notifier, $paramsArray) {
        global $result;
        //pr($result);
    }

    //$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK');
//    function updateEP4ExportLoopFullOrSBAStock(&$callingClass, $notifier, $paramsArray) {      
//    }
    //  $zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_LOOP');
//    function updateEP4ExportLoopFullOrSBAStockLoop(&$callingClass, $notifier, $paramsArray) {       
//    }
//	$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_END');
//    function updateEP4ExportLoopFullOrSBAStockEnd(&$callingClass, $notifier, $paramsArray) {      
//    }
//  $zco_notifier->notify('EP4_EXPORT_SPECIALS_AFTER');
    function updateEP4ExportSpecialsAfter(&$callingClass, $notifier, $paramsArray) {
        global $ep_dltype, $db, $filelayout_sql, $ep_uses_mysqli, $filelayout, $row, $langcode, $epdlanguage_id, $ep4bookx, $category_delimiter, $ep4bookx_module_path;
        global $enable_ep4bookx_specials, $enable_ep4bookx_metatags, $enable_ep4bookx_genre_name, $enable_ep4bookx_publisher_name, $enable_ep4bookx_series_name, $enable_ep4bookx_imprint_name, $enable_ep4bookx_binding, $enable_ep4bookx_printing, $enable_ep4bookx_condition, $enable_ep4bookx_size, $enable_ep4bookx_volume, $enable_ep4bookx_pages, $enable_ep4bookx_publishing_date, $enable_ep4bookx_author_name, $enable_ep4bookx_author_type, $enable_ep4bookx_subtitle, /* $enable_ep4bookx_categories, */ $enable_ep4bookx_manufacturers, $enable_ep4bookx_weight;

        if ( $ep_dltype == 'bookx' && $ep4bookx == 1 ) {
            include $ep4bookx_module_path . 'easypopulate_4_export_bookx.php';
        }
    }

    //     $notifyme[] = 'EP4_IMPORT_START';
    function updateEP4ImportStart(&$callingClass, $notifier, $paramsArray) {
        global $ep4bookx_reports, $filelayout, $ep4bookx_flag_import;
        // @global $ep4bookx_load_config, will load the last layout config from pre_process file on import
        global $ep4bookx_load_config;
        // Flag this as import start. This will set conditions on filelayout 
        $ep4bookx_flag_import = 1;
        /* [It aggregates missing fields in a report linked to the imported book. 
         * Uses Bookx languages files as key so it can be tranlated ie: BOX_CATALOG_PRODUCT_BOOKX_PUBLISHERS]
         * @see   [adminFolder/includes/languades/YOUR_lang/extra_definitions/product_bookx.php]
         * @var array
         */
        $ep4bookx_reports = array();
    }

    // EP4_IMPORT_FILE_EARLY_ROW_PROCESSING
    function updateEP4ImportFileEarlyRowProcessing(&$callingClass, $notifier, $paramsArray) {
        global $items, $filelayout, $display_output, $continueNextRow;


        if ( $items[$filelayout['v_status']] == 10 ) {
            $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_DELETED, $items[$filelayout['v_products_model']], $items[$filelayout['v_bookx_isbn']]);
            // Using Bookx function to remove books.
            // @todo Remove from bookx_extra_description 
            ep_4_remove_product_bookx($items[$filelayout['v_products_model']]);
            $continueNextRow = true;
        }
    }

    // EP4_IMPORT_AFTER_CATEGORY
//    function updateEP4ImportAfterCategory(&$callingClass, $notifier, $paramsArray) {
//        
//    }
    // EP4_IMPORT_FILE_NEW_PRODUCT_PRODUCT_TYPE
    function updateEP4ImportFileNewProductProductType(&$callingClass, $notifier, $paramsArray) {
        global $v_products_type, $v_artists_name, $bookx_product_type, $ep4bookx;

        // Assign product_type to bookx product type (overriding any previous product type assignment)
        //  if the either of the applicable bookx product type fields are populated.  Otherwise,
        //  will use the default which is a product type of 1 (generic product), this means that
        //  for all new product, at least one of these fields must be included as a part of this
        //  addin in order for the product type to be properly entered.
        if ( $ep4bookx == 1 ) {
            $v_products_type = $bookx_product_type;
        }
    }

    function updateEP4ImportFileEnd(&$callingClass, $notifier, $paramsArray) {
        global $zco_notifier, $bookx_product_type, $langcode, $epdlanguage_id, $edit_link, $items, $filelayout, $db, $ep4bookx_reports, $display_output, $ep_error_count, $ep_warning_count, $ep4bookx_module_path, $ep4bookx_extra_sqlwhere, $ep4bookx_extra_sqlcol, $ep4bookx_extra_sqlbind, $ep4bookx_flag_import;
        global $bind_publisher, $bind_series, $bind_binding, $bind_printing, $bind_condition, $bind_binding, $bind_publishing_date, $bind_pages, $bind_volume, $bind_size, $bind_imprint;
        global $v_products_id, $v_products_model, $v_products_name, $v_bookx_isbn, $v_bookx_genre_name, $v_bookx_publisher_name, $v_bookx_volume, $v_bookx_size, $v_bookx_pages, $v_bookx_publishing_date, $v_bookx_author_name;
        global $bookx_author_name_max_len, $bookx_author_types_name_max_len,
        $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len,
        $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len,
        $bookx_imprint_name_max_len, $bookx_subtitle_name_max_len;
        /**
         * Overrides the default config values for import from pre_process. This values were first loaded 
         * on the "updateEP4ImportStart" notifier global $ep4bookx_load_config. This way, no need to loop that array, and the values are here.
         * Not sure where is the best place to work those variables, there, or here.
         * I've tested in both places, are both work. For me, I find it easier to maintain it, in pre_process file, cause this file is getting big.
         */
        global $report_ep4bookx_subtitle, $report_ep4bookx_genre_name, $report_ep4bookx_binding, $report_ep4bookx_printing, $report_ep4bookx_condition, $report_ep4bookx_isbn, $report_ep4bookx_publishing_date, $report_ep4bookx_publisher_name, $report_ep4bookx_series_name, $report_ep4bookx_imprint_name, $report_ep4bookx_author_name, $report_ep4bookx_author_type;
        global $default_ep4bookx_author_name, $default_ep4bookx_author_type, $default_ep4bookx_printing, $default_ep4bookx_binding, $default_ep4bookx_genre_name, $default_ep4bookx_publisher_name, $default_ep4bookx_imprint_name, $default_ep4bookx_condition;

        /**
         * The flag was set on import start notifier. Now calls the EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT notifier. 
         * I might be moving backwards, but this way, all file sets and conditions will be at the same place.
         * Or, what's now beeing made on that notifier, could be made in the import file....
         * I'm not sure what's best, in terms of logic and performance. I've went both ways, and either way, they work. 
         */
        if ( $ep4bookx_flag_import == 1 ) {
            $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT');
        }

        include $ep4bookx_module_path . 'easypopulate_4_import_bookx.php';
    }

    // EP4_IMPORT_FILE_PRE_DISPLAY_OUTPUT
    function updateEP4ImportFilePreDisplayOutput(&$callingClass, $notifier, $paramsArray) {
        global $ep4bookx_reports, $display_output, $edit_link;
        /**
         * @EP4Bookx
         * Reports missing fields with the book edit link
         */
        if ( !empty($ep4bookx_reports) ) {
            $display_output .= '<table class="pure-table pure-table-bordered /*bookx-reports*/"><caption>' . EASYPOPULATE_4_DISPLAY_BOOKX_REPORTS_BOOKX_HEADER . '</caption><tr class="bookx-reports-top"><th >Type</th><th>' . EASYPOPULATE_4_BOOKX_TABLE_BOOK . '</th></tr>';

            foreach ( $ep4bookx_reports as $key => $value ) {
                $display_output .= '<tr><th class="bookx-reports-th-left" rowspan ="' . (count($value) + 1) . '">' . strtoupper($key) . '</th>';
                $display_output .= '<th class="bookx-reports-th-caption">' . EASYPOPULATE_4_BOOKX_TABLE_CAPTION . '</th></tr>';

                $lastKey = count($value) - 1;

                for ( $i = 0; $i < (count($value)); $i++ ) {
                    $class = ($i & 1) ? 'pure-table-odd odd' : 'even';
                    ($i == $lastKey ? $class .=' last' : '');
                    $display_output .= '<tr ><td class="' . $class . '">' . $value[$i] . '</td></tr>';
                }
            }
            $display_output .='</table>';
        }
    }

    function update(&$callingClass, $notifier, $paramsArray) {

//        if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_START' ) {
//            $this->updateEP4ExtraFunctionsSetFilelayoutFullStart($callingClass, $notifier, $paramsArray);
//        }
        // $zco_notifier->notify('EP4_COLLATION_UTF8_ZC13X');
        if ( $notifier == 'EP4_COLLATION_UTF8_ZC13X' ) {
            $this->updateEP4CollationUTF8ZC13x($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_EASYPOPULATE_4_LINK');
        if ( $notifier == 'EP4_EASYPOPULATE_4_LINK' ) {
            $this->updateEP4Easypopulate4Link($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_EASYPOPULATE_4_LINK');
        if ( $notifier == 'EP4_EASYPOPULATE_4_LINK_END' ) {
            $this->updateEP4Easypopulate4LinkEnd($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_DISPLAY_STATUS');
        if ( $notifier == 'EP4_DISPLAY_STATUS' ) {
            $this->updateEP4DisplayStatus($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_MAX_LEN');
        if ( $notifier == 'EP4_MAX_LEN' ) {
            $this->updateEP4MaxLen($callingClass, $notifier, $paramsArray);
        }

        //$zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT');
        if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT' ) {
            $this->updateEP4ExtraFunctionsSetFilelayoutFullFilelayout($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_SELECT');
//        if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_SELECT' ) {
//            $this->updateEP4ExtraFunctionsSetFilelayoutFullSQLSelect($callingClass, $notifier, $paramsArray);
//        }
        // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_TABLE');
//        if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_TABLE' ) {
//            $this->updateEP4ExtraFunctionsSetFilelayoutFullSQLTable($callingClass, $notifier, $paramsArray);
//        }
        // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_FILELAYOUT');
//        if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_FILELAYOUT' ) {
//            $this->updateEP4ExtraFunctionsSetFilelayoutCategoryFilelayout($callingClass, $notifier, $paramsArray);
//        }
        //  $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_SQL_SELECT');
//        if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_SQL_SELECT' ) {
//            $this->updateEP4ExtraFunctionsSetFilelayoutCategorySQLSelect($callingClass, $notifier, $paramsArray);
//        }
        //  $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORYMETA_FILELAYOUT');
//        if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORYMETA_FILELAYOUT' ) {
//            $this->updateEP4ExtraFunctionsSetFilelayoutCategorymetaFilelayout($callingClass, $notifier, $paramsArray);
//        }
        // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CASE_DEFAULT');
        if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CASE_DEFAULT' ) {
            $this->updateEP4ExtraFunctionsSetFilelayoutCaseDefault($callingClass, $notifier, $paramsArray);
        }

        //  $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_INSTALL_END');
        if ( $notifier == 'EP4_EXTRA_FUNCTIONS_INSTALL_END' ) {
            $this->updateEP4ExtraFunctionsInstallEnd($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_LINK_SELECTION_END');
        if ( $notifier == 'EP4_LINK_SELECTION_END' ) {
            $this->updateEP4LinkSelectionEnd($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_FILENAMES');
        if ( $notifier == 'EP4_FILENAMES' ) {
            $this->updateEP4Filenames($callingClass, $notifier, $paramsArray);
        }

        // 'EP4_EXPORT_FILE_ARRAY_START'
//        if ( $notifier == 'EP4_EXPORT_FILE_ARRAY_START' ) {
//            $this->updateEP4ExportFileArrayStart($callingClass, $notifier, $paramsArray); // mc12345678 doesn't work on ZC 1.5.1 and below
//        }
        // 'EP4_EXPORT_CASE_EXPORT_FILE_END'
        if ( $notifier == 'EP4_EXPORT_CASE_EXPORT_FILE_END' ) {
            $this->updateEP4ExportCaseExportFileEnd($callingClass, $notifier, $paramsArray);
        }

        // EP4_EXPORT_WHILE_START
        if ( $notifier == 'EP4_EXPORT_WHILE_START' ) {
            $this->updateEP4ExportWhileStart($callingClass, $notifier, $paramsArray);
        }

        //$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK');
//        if ( $notifier == 'EP4_EXPORT_LOOP_FULL_OR_SBASTOCK' ) {
//            $this->updateEP4ExportLoopFullOrSBAStock($callingClass, $notifier, $paramsArray);
//        }
        //  $zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_LOOP');
//        if ( $notifier == 'EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_LOOP' ) {
//            $this->updateEP4ExportLoopFullOrSBAStockLoop($callingClass, $notifier, $paramsArray);
//        }
//	$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_END');
//        if ( $notifier == 'EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_END' ) {
//            $this->updateEP4ExportLoopFullOrSBAStockEnd($callingClass, $notifier, $paramsArray);
//        }
//  $zco_notifier->notify('EP4_EXPORT_SPECIALS_AFTER');
        if ( $notifier == 'EP4_EXPORT_SPECIALS_AFTER' ) {
            $this->updateEP4ExportSpecialsAfter($callingClass, $notifier, $paramsArray);
        }

//  $zco_notifier->notify('EP4_EXPORT_FULL_OR_CAT_FULL_AFTER');
        if ( $notifier == 'EP4_EXPORT_FULL_OR_CAT_FULL_AFTER' ) {
            $this->updateEP4ExportFullOrCatFullAfter($callingClass, $notifier, $paramsArray);
        }

        if ( $notifier == 'EP4_IMPORT_START' ) {
            $this->updateEP4ImportStart($callingClass, $notifier, $paramsArray);
        }

        if ( $notifier == 'EP4_IMPORT_FILE_EARLY_ROW_PROCESSING' ) {
            $this->updateEP4ImportFileEarlyRowProcessing($callingClass, $notifier, $paramsArray);
        }

//        if ( $notifier == 'EP4_IMPORT_AFTER_CATEGORY' ) {
//            $this->updateEP4ImportAfterCategory($callingClass, $notifier, $paramsArray);
//        }

        if ( $notifier == 'EP4_IMPORT_FILE_NEW_PRODUCT_PRODUCT_TYPE' ) {
            $this->updateEP4ImportFileNewProductProductType($callingClass, $notifier, $paramsArray);
        }

        if ( $notifier == 'EP4_IMPORT_FILE_END' ) {
            $this->updateEP4ImportFileEnd($callingClass, $notifier, $paramsArray);
        }

        if ( $notifier == 'EP4_IMPORT_FILE_PRE_DISPLAY_OUTPUT' ) {
            $this->updateEP4ImportFilePreDisplayOutput($callingClass, $notifier, $paramsArray);
        }
    }

// EOF Update()
}
