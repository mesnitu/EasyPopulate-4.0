<?php

/**
 * Description of class.ep4bookx.
 *
 * @author mc12345678
 * @contribution mesnitu
 */
class ep4bookx extends base {

    //private $_product = array();

    public $tplForm;
    public $setFields = array();
    public $names_len = array();

    function __construct() { // ep4bookx if this class has difficulty loading
        //global $zco_notifier;
        $notifyme = array();

        $notifyme[] = 'EP4_START';
        $notifyme[] = 'EP4_COLLATION_UTF8_ZC13X';
        $notifyme[] = 'EP4_EASYPOPULATE_4_LINK';
		$notifyme[] = 'EP4_ZC155_AFTER_HEADER';	
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

        //$notifyme[] = 'EP4_IMPORT_FILE_END'; // For some reason, the only way

        $this->attach($this, $notifyme);
    }



    public function __set($variable, $value) {
        global $variable, $value;
        $this->setFields[$variable] = $value;
    }

    public function __get($variable) {
        $this->setFields[$variable];
    }

    
    /**
     * it builds the fields array
     * @param type array 
     * @param type boolean - If not null, sets the fields variables names and values. If null, it will fetch all fields ( used by tplForm) 
     * @param type string  to call the form group tpl fields
     * @return type
     */
    public function ep4bookxBuild($param, $vars = null, $group = null) {

        if ( !$vars ) {
            if ( !$group ) {
                foreach ( $param as $key => $fields ) {
                    $this->tplForm[$key] = $fields;
                }
            } else {
                $tmp = array();
                $param = get_object_vars($param);
                foreach ( $param[$group] as $k => $fields ) {
                    $tmp[$group][$k] = $fields;
                }
                foreach ( $tmp as $field_name ) {
                    foreach ( $field_name as $key => $value ) {
                        $this->tplForm[$key] = $value;
                    }
                }
                //@todo sort the names for tpl build
//                usort($this->tplForm->name, function($a, $b) {                  
//                    return strcmp($a->name, $b->name);
//                });
            }
        } else {
            foreach ( $param as $values ) {            
                foreach ( $values as $variables => $value ) {
                    $this->setFields[$variables] = $value->value;
                }
            }
        }
    }

    /* Function run/called by notifier: EP4_START */

    function updateEP4Start(&$callingClass, $notifier, $paramsArray) {
        global $db, $curver, $ep4bookx_enabled, $ep4bookx_version, $ep4bookx_fields_conf, $bookx_product_type, $messageStack, $toogle_config, $toogle_text, $ep4bookx_msg;
        global $bookx_author_name_max_len, $bookx_author_types_name_max_len,
        $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len,
        $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len,
        $bookx_imprint_name_max_len, $bookx_subtitle_name_max_len;

        global $ep4bookx_default_cnf, $build_vars, $ep4bookx_configuration;
  
        
        $curver .= '<br />'; // Intent of this is to show that the option is available not that it is "active", though if that is desired instead, then suggest adding an "active/inactive"
        $curver .= 'w/ BookX' . $ep4bookx_version;
       
        
        if ( $ep4bookx_enabled == 1 ) {
           
            require(DIR_FS_ADMIN . DIR_WS_LANGUAGES . $_SESSION['language'] . '/easypopulate_4_bookx.php');
            foreach ( $ep4bookx_default_cnf->ep4bookx_default_fields as $value ) {
                
                $bookx_author_name_max_len = $value->length;
                $bookx_author_types_name_max_len = $value->length;
                $bookx_genre_name_max_len = $value->length;
                $bookx_series_name_max_len = $value->length;
                $bookx_publisher_name_max_len = $value->length;
                $bookx_binding_name_max_len = $value->length;
                $bookx_printing_name_max_len = $value->length;
                $bookx_condition_name_max_len = $value->length;
                $bookx_imprint_name_max_len = $value->length;
                $bookx_subtitle_name_max_len = $value->length;
            }

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
    //function updateEP4Easypopulate4Link(&$callingClass, $notifier, $paramsArray) {
    //}
	
	 // $zco_notifier->notify('EP4_ZC155_AFTER_HEADER');
    function updateEP4ZC155AfterHeader(&$callingClass, $notifier, $paramsArray) {
         global $ep4bookx_enabled, $ep4bookx_module_path, $ep4bookx_tpl_path, $ep4bookx_fields_conf, $ep4bookx_configuration;
	     global $progress_bar, $maintenance, $maintenance_state, $which_zc;
		 
		$which_zc = PROJECT_VERSION_MINOR;
             
        if ( $ep4bookx_enabled == 1 ) {
			// load header scripts			
            include_once $ep4bookx_module_path . 'tpl/tpl_ep4bookx_header.php';
        }
    }
	
    // $zco_notifier->notify('EP4_DISPLAY_STATUS');
    function updateEP4DisplayStatus(&$callingClass, $notifier, $paramsArray) {
        global $ep4bookx_enabled;

        $ep_bookx = (int) EASYPOPULATE_4_CONFIG_BOOKX_DATA; // 0-Disable, 1-Enable
        echo EASYPOPULATE_4_DISPLAY_ENABLE_BOOKX . $ep_bookx . '<br/>';
    }

    // $zco_notifier->notify('EP4_MAX_LEN');
    function updateEP4MaxLen(&$callingClass, $notifier, $paramsArray) {
        global $bookx_author_name_max_len, $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len,
        $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len,
        $bookx_imprint_name_max_len, $bookx_subtitle_name_max_len, $ep4bookx_enabled;
        // Not sure if this is really usefull for a user  
        if ( $ep4bookx_enabled == 1 ) {
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
    function updateEP4ExtraFunctionsSetFilelayoutFullStart(&$callingClass, $notifier, $paramsArray) {
        
    }

    //$zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT');
    function updateEP4ExtraFunctionsSetFilelayoutFullFilelayout(&$callingClass, $notifier, $paramsArray) {
        global $filelayout, $langcode, $ep4bookx_query, $ep4bookx_query_join, $ep4bookx_extra_sqlwhere, $ep4bookx_extra_sqlcol, $ep4bookx_extra_sqlbind, $epdlanguage_id, $ep4bookx_flag_import, $ep4bookx_flag_export;

        global $bind_publisher, $bind_series, $bind_binding, $bind_printing, $bind_condition, $bind_binding, $bind_publishing_date, $bind_pages, $bind_volume, $bind_size, $bind_imprint;

        global $build_vars;


//@ALTERED Bookx Info - 23-04-2015
// If import flag is 1, set the conditional querys to bookx_extra table
// IF Paradaise     

        if ( (int) EASYPOPULATE_4_CONFIG_BOOKX_DATA == true ) { // probably it could be removed
            if ( !$ep4bookx_flag_import && $ep4bookx_flag_export == 1 ) { // Prevents all this to be attached to import filelayout
                $ep4bookx_query = '';

                if ( $build_vars->setFields['enable_ep4bookx_subtitle'] == true ) {
                    foreach ( $langcode as $key => $lang ) { // create variables for each language id
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_subtitle_' . $l_id;
                    }
                }

                if ( $build_vars->setFields['enable_ep4bookx_genre_name'] == true ) {
                    foreach ( $langcode as $key => $lang ) {
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_genre_name_' . $l_id;
                    }
                }

                if ( $build_vars->setFields['enable_ep4bookx_publisher_name'] == true ) {
                    $filelayout[] = 'v_bookx_publisher_name';
                    $ep4bookx_query .= 'bp.publisher_name AS v_bookx_publisher_name, ';
                    $ep4bookx_query_join = 'LEFT JOIN ' . TABLE_PRODUCT_BOOKX_PUBLISHERS . ' AS bp ON be.bookx_publisher_id = bp.bookx_publisher_id ';
                }

                if ( $build_vars->setFields['enable_ep4bookx_series_name'] == true ) {
                    foreach ( $langcode as $key => $lang ) {
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_series_name_' . $l_id; // Series name, as Lang ID
                    }
                    $ep4bookx_query .='be.bookx_series_id, ';
                }

                if ( $build_vars->setFields['enable_ep4bookx_imprint_name'] == true ) {
                    $filelayout[] = 'v_bookx_imprint_name';
                    $ep4bookx_query .= 'bi.imprint_name AS v_bookx_imprint_name, ';
                    $ep4bookx_query_join .= 'LEFT JOIN ' . TABLE_PRODUCT_BOOKX_IMPRINTS . ' AS bi ON be.bookx_imprint_id = bi.bookx_imprint_id ';
                }

                if ( $build_vars->setFields['enable_ep4bookx_binding'] == true ) {
                    foreach ( $langcode as $key => $lang ) {
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_binding_' . $l_id; //   Lang ID
                    }
                    $ep4bookx_query .= 'be.bookx_binding_id, ';
                }
                if ( $build_vars->setFields['enable_ep4bookx_printing'] == true ) {
                    foreach ( $langcode as $key => $lang ) {
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_printing_' . $l_id; //   Lang ID
                    }
                    $ep4bookx_query .= 'be.bookx_printing_id, ';
                }
                if ( $build_vars->setFields['enable_ep4bookx_condition'] == true ) {
                    foreach ( $langcode as $key => $lang ) {
                        $l_id = $lang['id'];
                        $filelayout[] = 'v_bookx_condition_' . $l_id; //  Lang ID
                    }
                    $ep4bookx_query .= 'be.bookx_condition_id, ';
                }
                // ISBN is mandatory. At least one fields must get the values from bookx_extra table. ISBN is a good one     
                $filelayout[] = 'v_bookx_isbn';
                //$ep4bookx_query = 'be.isbn AS v_bookx_isbn';
                if ( $build_vars->setFields['enable_ep4bookx_size'] == true ) {
                    $filelayout[] = 'v_bookx_size';
                    $ep4bookx_query .= 'be.size AS v_bookx_size, ';
                }
                if ( $build_vars->setFields['enable_ep4bookx_volume'] == true ) {
                    $filelayout[] = 'v_bookx_volume';
                    $ep4bookx_query .= 'be.volume  AS v_bookx_volume, ';
                }
                if ( $build_vars->setFields['enable_ep4bookx_pages'] == true ) {
                    $filelayout[] = 'v_bookx_pages';
                    $ep4bookx_query .= 'be.pages AS v_bookx_pages, ';
                }
                if ( $build_vars->setFields['enable_ep4bookx_publishing_date'] == true ) {
                    $filelayout[] = 'v_bookx_publishing_date';
                    $ep4bookx_query .= 'be.publishing_date AS v_bookx_publishing_date, ';
                }
                if ( $build_vars->setFields['enable_ep4bookx_author_name'] == true ) {
                    $filelayout[] = 'v_bookx_author_name';
                }
                if ( $build_vars->setFields['enable_ep4bookx_author_type'] == true ) {
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

        global $zco_notifier, $ep_dltype, $filelayout, $filelayout_sql, $langcode, $bookx_product_type, $sql_filter, $ep4bookx_query, $ep4bookx_query_join, $build_vars;

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
                if ( $build_vars->setFields['enable_ep4bookx_specials'] == 1 ) {
                    $filelayout[] = 'v_specials_price';
                    $filelayout[] = 'v_specials_date_avail';
                    $filelayout[] = 'v_specials_expires_date';
                }
                $filelayout[] = 'v_products_price';
                if ( $build_vars->setFields['enable_ep4bookx_weight'] == 1 ) {
                    $filelayout[] = 'v_products_weight';
                }
                $filelayout[] = 'v_date_avail'; // should be changed to v_products_date_available for clarity
                $filelayout[] = 'v_date_added'; // should be changed to v_products_date_added for clarity
                $filelayout[] = 'v_products_quantity';

                if ( $build_vars->setFields['enable_ep4bookx_manufacturers'] == 1 ) {
                    $filelayout[] = 'v_manufacturers_name';
                }

                foreach ( $langcode as $key => $lang ) { // create categories variables for each language id
                    $l_id = $lang['id'];
                    $filelayout[] = 'v_categories_name_' . $l_id;
                }

                $filelayout[] = 'v_tax_class_title';

                // metatags - 4-23-2012: added switch
                if ( (int) EASYPOPULATE_4_CONFIG_META_DATA && $build_vars->setFields['enable_ep4bookx_metatags'] == 1 ) {
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

                if ( $build_vars->setFields['enable_ep4bookx_weight'] == 1 ) {
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

          if ( $build_vars->setFields['enable_ep4bookx_metatags'] == 1 ) {
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
                // THe $ep4bookx_query_join could be done in this notifier bellow.
                //$zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_TABLE');

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

        if ( (substr($project, 0, 5) == "1.3.8") || (substr($project, 0, 5) == "1.3.9") ) {
            $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
        ('Enable Products Bookx','EASYPOPULATE_4_CONFIG_BOOKX_DATA', '0', 'Enable Products Bookx Data Columns
        (default 0).<br><br>0=Disable<br>1=Enable', " . $group_id . ", '1000', NULL, now(), NULL, 'zen_cfg_select_option(array(\"0\", \"1\"),')
	  ");

            $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
        ('Enable Ep4Bookx Fields Configuration','EASYPOPULATE_4_ENABLE_FIELDS_CONFIG_BOOKX_DATA', '0', 'Enable Ep4Bookx fields configuration. <br>This will load the table to config the fields.
        (default 0).<br><br>0=Disable<br>1=Enable', " . $group_id . ", '1001', NULL, now(), NULL, 'zen_cfg_select_option(array(\"0\", \"1\"),')
	  ");
        } elseif ( PROJECT_VERSION_MAJOR > '1' || PROJECT_VERSION_MINOR >= '5.0' ) {
            $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
        ('Enable Products Bookx','EASYPOPULATE_4_CONFIG_BOOKX_DATA', '0', 'Enable Products Bookx Data Columns
        (default 0).<br><br>0=Disable<br>1=Enable', " . $group_id . ", '1000', NULL, now(), NULL, 'zen_cfg_select_option(array(\"0\", \"1\"),')
	  ");

            $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
        ('Enable Ep4Bookx Fields Configuration','EASYPOPULATE_4_ENABLE_FIELDS_CONFIG_BOOKX_DATA', '0', 'Enable Ep4Bookx fields configuration. <br>This will load the table to config the fields.
        (default 0).<br><br>0=Disable<br>1=Enable', " . $group_id . ", '1001', NULL, now(), NULL, 'zen_cfg_select_option(array(\"0\", \"1\"),')
	  ");
        } else { // unsupported version
            // i should do something here!
        }
    }

    // $zco_notifier->notify('EP4_LINK_SELECTION_END');
    function updateEP4LinkSelectionEnd(&$callingClass, $notifier, $paramsArray) {
        global $ep4bookx_enabled, $request_type, $ep4bookx_module_path, $ep4bookx_customize_files, $ep4bookx_fields_conf, $toogle_config, $toogle_text;
        global $parsedown, $text;
        global $ep4bookx_fields, $ep4bookx_report_fields, $ep4bookx_default_fields;
        global $ep4bookx_default_cnf, $ep4bookx_configuration, $progress_bar, $maintenance, $optimize_table;
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
         * @todo Should check if all this $ep4bookx_enabled == 1 are really necessary, since all this as been started in init file
         */
       
        if ( $ep4bookx_enabled == 1 ) {
 
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
    function updateEP4ExportFileArrayStart(&$callingClass, $notifier, $paramsArray) { // mc12345678 doesn't work on ZC 1.5.1 and below
        global $ep_dltype, $filelayout_sql, $ep_uses_mysqli, $filelayout, $row;
        
    }

    // 'EP4_EXPORT_CASE_EXPORT_FILE_END'
    function updateEP4ExportCaseExportFileEnd(&$callingClass, $notifier, $paramsArray) {
        global $ep_dltype, $EXPORT_FILE;

        if ( $ep_dltype == 'bookx' ) {
            $EXPORT_FILE = 'BookX-EP';
        } elseif ( $ep_dltype == 'DEMO-2' ) {
            $EXPORT_FILE = 'DEMO-2-EP';
        } elseif ( $ep_dltype == 'DEMO-3' ) {
            $EXPORT_FILE = 'DEMO-3-EP';
        }
    }

    // EP4_EXPORT_WHILE_START
    function updateEP4ExportWhileStart(&$callingClass, $notifier, $paramsArray) {
        global $result, $categories_name_max_len;
    }

    //$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK');
    function updateEP4ExportLoopFullOrSBAStock(&$callingClass, $notifier, $paramsArray) {
        // global $result;
    }

    //  $zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_LOOP');
    function updateEP4ExportLoopFullOrSBAStockLoop(&$callingClass, $notifier, $paramsArray) {
        // global $result;
    }

//	$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_END');
    function updateEP4ExportLoopFullOrSBAStockEnd(&$callingClass, $notifier, $paramsArray) {
        // global $result;
    }

//  $zco_notifier->notify('EP4_EXPORT_SPECIALS_AFTER');
    function updateEP4ExportSpecialsAfter(&$callingClass, $notifier, $paramsArray) {
        global $ep_dltype, $db, $filelayout_sql, $ep_uses_mysqli, $filelayout, $row, $langcode, $epdlanguage_id, $ep4bookx_enabled, $category_delimiter, $ep4bookx_module_path, $build_vars;
        /*global $enable_ep4bookx_specials, $enable_ep4bookx_metatags, $enable_ep4bookx_genre_name, $enable_ep4bookx_publisher_name, $enable_ep4bookx_series_name, $enable_ep4bookx_imprint_name, $enable_ep4bookx_binding, $enable_ep4bookx_printing, $enable_ep4bookx_condition, $enable_ep4bookx_size, $enable_ep4bookx_volume, $enable_ep4bookx_pages, $enable_ep4bookx_publishing_date, $enable_ep4bookx_author_name, $enable_ep4bookx_author_type, $enable_ep4bookx_subtitle, /* $enable_ep4bookx_categories, $enable_ep4bookx_manufacturers, $enable_ep4bookx_weight; */


        if ( $ep_dltype == 'bookx' && $ep4bookx_enabled == 1 ) {
            include $ep4bookx_module_path . 'easypopulate_4_export_bookx.php';
        }
    }

//  $zco_notifier->notify('EP4_EXPORT_FULL_OR_CAT_FULL_AFTER');
    function updateEP4ExportFullOrCatFullAfter(&$callingClass, $notifier, $paramsArray) {       
    }

    //     $notifyme[] = 'EP4_IMPORT_START';
    function updateEP4ImportStart(&$callingClass, $notifier, $paramsArray) {
        global $ep4bookx_reports, $filelayout, $ep4bookx_flag_import;
        // @global $ep4bookx_load_config, will load the last layout config from pre_process file on import
        global $ep4bookx_load_config;
        // Flag this as import start. This will set conditions on filelayout 
        if ( $ep4bookx_flag_import == 1 ) {
            /* [It aggregates missing fields in a report linked to the imported book. 
             * Uses Bookx languages files as key so it can be tranlated ie: BOX_CATALOG_PRODUCT_BOOKX_PUBLISHERS]
             * @see   [adminFolder/includes/languades/YOUR_lang/extra_definitions/product_bookx.php]
             * @var array
             */
            $ep4bookx_reports = array();
        }
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
    function updateEP4ImportAfterCategory(&$callingClass, $notifier, $paramsArray) {
        // by @mc12345678

        global $products_name_max_len;
        global $ep_dltype, $filelayout_sql, $ep_uses_mysqli, $filelayout, $row, $items, $db;
        global $zco_notifier, $bookx_product_type, $langcode, $epdlanguage_id, $edit_link, $ep4bookx_reports, $display_output, $ep_error_count, $ep_warning_count, $ep4bookx_module_path, $ep4bookx_extra_sqlwhere, $ep4bookx_extra_sqlcol, $ep4bookx_extra_sqlbind, $ep4bookx_flag_import, $build_vars, $categories_delimiter;

        global $bind_publisher, $bind_series, $bind_binding, $bind_printing, $bind_condition, $bind_binding, $bind_publishing_date, $bind_pages, $bind_volume, $bind_size, $bind_imprint;

        global $v_bookx_isbn, $v_bookx_genre_name, $v_bookx_publisher_name, $v_bookx_volume, $v_bookx_size, $v_bookx_pages, $v_bookx_publishing_date, $v_bookx_author_name, $v_bookx_author_type;

        global $bookx_author_name_max_len, $bookx_author_types_name_max_len,
        $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len,
        $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len,
        $bookx_imprint_name_max_len, $bookx_subtitle_name_max_len;

        $chosen_key = '';
       
        switch ( EP4_DB_FILTER_KEY ) {
            case 'products_model':
                $chosen_key = 'v_products_model';
                break;
            case 'blank_new':
            case 'products_id':
                $chosen_key = 'v_products_id';
                break;
            default:
                $chosen_key = 'v_products_model';
                break;
        }

        if ( zen_not_null($items[$filelayout[$chosen_key]]) ) {
// Master key exists in file, therefore can process information...

            ${$chosen_key} = $items[$filelayout[$chosen_key]];

            // First we check to see if this is a product in the current db. 
            $sql = "SELECT products_id, products_model FROM " . TABLE_PRODUCTS;
            switch ( $chosen_key ) {
                case 'v_products_model':
                    $sql .= " WHERE (products_model = :products_model:)";
                    break;
                case 'v_products_id':
                    $sql .= " WHERE (products_id = :products_id:) LIMIT 1";
                    break;
                default:
                    $sql .= " WHERE (products_model = :products_model:)";
                    break;
            }
            $sql = $db->bindVars($sql, ':products_model:', $v_products_model, 'string');
            $sql = $db->bindVars($sql, ':products_id:', $v_products_id, 'integer');
            $result = $db->Execute($sql);
                 
        if ( $result->RecordCount() == 0 ) { // new item, insert using new file data where applicable
            // Need to identify/obtain $v_products_model and the multilinqual $v_products_name
            
            $sql = "SHOW TABLE STATUS LIKE '" . TABLE_PRODUCTS . "'";
            $result = $db->Execute($sql);
 
            $max_product_id = $result->fields['Auto_increment'];
            if ( !is_numeric($max_product_id) ) {
                $max_product_id = 1;
            }
            if ( $chosen_key == 'v_products_model' || ($chosen_key == 'v_products_id' && defined('EP4_DB_FILTER_KEY') && EP4_DB_FILTER_KEY === 'blank_new' && ${$chosen_key} == "") ) {
                $v_products_id = $max_product_id;
            }
            if ( isset($filelayout['v_products_model']) ) {
                $v_products_model = $items[$filelayout['v_products_model']];
            } else {
                $v_products_model = "";
            }

            foreach ( $langcode as $lang ) {
                $l_id = $lang['id'];

                if ( isset($filelayout['v_products_name_' . $l_id]) ) { // do for each language in our upload file if exist
                    // check products name length and display warning on error, but still process record
                    $v_products_name[$l_id] = ep_4_curly_quotes($items[$filelayout['v_products_name_' . $l_id]]);
                    if ( mb_strlen($v_products_name[$l_id]) > $products_name_max_len ) {
                        $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_PRODUCTS_NAME_LONG, $v_products_model, $v_products_name[$l_id], $products_name_max_len);
                        $ep_warning_count++;
                    }
                } else { // column doesn't exist in the IMPORT file
                    $v_products_name[$l_id] = "";
                }
            }

            if ( $ep4bookx_flag_import == 1 ) {         
                $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT');
                include $ep4bookx_module_path . 'easypopulate_4_import_bookx.php';
            }
            
        } else { //existing item, and need to use file data to update information.

            while ( !$result->EOF ) { 
               // $v_products_id = $row['products_id'];
                $v_products_id = $result->fields['products_id'];

                if ( isset($filelayout['v_products_model']) ) {
                    $v_products_model = $items[$filelayout['v_products_model']];
                } else {
                    $v_products_model = $result->fields['products_model'];
                }
              
                foreach ( $langcode as $lang ) {
                    $l_id = $lang['id'];

                    if ( isset($filelayout['v_products_name_' . $l_id]) ) { // do for each language in our upload file if exist
                        // check products name length and display warning on error, but still process record
                        $v_products_name[$l_id] = ep_4_curly_quotes($items[$filelayout['v_products_name_' . $l_id]]);
                        if ( mb_strlen($v_products_name[$l_id]) > $products_name_max_len ) {
                            $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_PRODUCTS_NAME_LONG, $v_products_model, $v_products_name[$l_id], $products_name_max_len);
                            $ep_warning_count++;
                        }
                    } else { // column doesn't exist in the IMPORT file use existing
                        // Need to Select search the existing 
                        $sql2 = "SELECT products_name FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE
                      products_id = :products_id: AND
                      language_id = :language_id:";
                        $sql2 = $db->bindVars($sql2, ':products_id:', $v_products_id, 'integer');
                        $sql2 = $db->bindVars($sql2, ':language_id:', $l_id, 'integer');
                        $result2 = ep_4_query($sql2);
                      
                        if ( ($ep_uses_mysqli ? mysqli_num_rows($result2) : mysql_num_rows($result2)) == 0 ) {
                            $v_products_name[$l_id] = "";
                        } else { // already in the description, update it
                            $row2 = ($ep_uses_mysqli ? mysqli_fetch_array($result2) : mysql_fetch_array($result2));
                            $v_products_name[$l_id] = $row2['products_name'];
                        }
                    }
                }

                if ( $ep4bookx_flag_import == 1 ) {  
                    
                    $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT');
                    include $ep4bookx_module_path . 'easypopulate_4_import_bookx.php';
                }
                $result->MoveNext();
            }
        }
		}
    }

    // EP4_IMPORT_FILE_NEW_PRODUCT_PRODUCT_TYPE
    function updateEP4ImportFileNewProductProductType(&$callingClass, $notifier, $paramsArray) {
        global $v_products_type, $v_artists_name, $bookx_product_type, $ep4bookx_enabled;

        // Assign product_type to bookx product type (overriding any previous product type assignment)
        //  if the either of the applicable bookx product type fields are populated.  Otherwise,
        //  will use the default which is a product type of 1 (generic product), this means that
        //  for all new product, at least one of these fields must be included as a part of this
        //  addin in order for the product type to be properly entered.
        if ( $ep4bookx_enabled == 1 ) {
            $v_products_type = $bookx_product_type;
        }
    }

//    function updateEP4ImportFileEnd(&$callingClass, $notifier, $paramsArray) {
//        
//    }

    // EP4_IMPORT_FILE_PRE_DISPLAY_OUTPUT
    function updateEP4ImportFilePreDisplayOutput(&$callingClass, $notifier, $paramsArray) {
        global $ep4bookx_reports, $display_output, $edit_link;
        global $ep4bookx_flag_import_ends;
        /**
         * @EP4Bookx
         * Reports missing fields with the book edit link
         */
        if ( !empty($ep4bookx_reports) ) {
            $display_output .= '<table class="pure-table pure-table-bordered bookx-reports"><caption>' . EASYPOPULATE_4_DISPLAY_BOOKX_REPORTS_BOOKX_HEADER . '</caption><tr class="bookx-reports-top"><th >Type</th><th>' . EASYPOPULATE_4_BOOKX_TABLE_BOOK . '</th></tr>';

            foreach ( $ep4bookx_reports as $key => $value ) {
                
                $display_output .= '<tr><th class="bookx-reports-th-left" rowspan ="' . (count($value) + 1) . '">' . $key . '</th>';
                $display_output .= '<th class="bookx-reports-th-caption">' . EASYPOPULATE_4_BOOKX_TABLE_CAPTION . ' : '. $key .'</th></tr>';

                $lastKey = count($value) - 1;

                for ( $i = 0; $i < (count($value)); $i++ ) {
                    $class = ($i & 1) ? 'odd' : 'even';
                    ($i == $lastKey ? $class .=' last' : '');
                    $display_output .= '<tr ><td class="' . $class . '">' . $value[$i] . '</td></tr>';
                }
            }
            $display_output .='</table>';
        }
        
        // This will signal that the import has ended, to optimize talbe 
        $_SESSION['ep4bookx_flag_import_ends'] = 1;
    }

    function update(&$callingClass, $notifier, $paramsArray) {


        if ( $notifier == 'EP4_START' ) {
            $this->updateEP4Start($callingClass, $notifier, $paramsArray);
        }
		// $zco_notifier->notify('EP4_ZC155_AFTER_HEADER');
		if ( $notifier == 'EP4_ZC155_AFTER_HEADER' ) {
            $this->updateEP4ZC155AfterHeader($callingClass, $notifier, $paramsArray);
        }
		
        if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_START' ) {
            $this->updateEP4ExtraFunctionsSetFilelayoutFullStart($callingClass, $notifier, $paramsArray);
        }
        // $zco_notifier->notify('EP4_COLLATION_UTF8_ZC13X');
        if ( $notifier == 'EP4_COLLATION_UTF8_ZC13X' ) {
            $this->updateEP4CollationUTF8ZC13x($callingClass, $notifier, $paramsArray);
        }

        // $zco_notifier->notify('EP4_EASYPOPULATE_4_LINK');
        //if ( $notifier == 'EP4_EASYPOPULATE_4_LINK' ) {
        //    $this->updateEP4Easypopulate4Link($callingClass, $notifier, $paramsArray);
        // }

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

        //$zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_SELECT');
        //if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_SELECT' ) {
        //    $this->updateEP4ExtraFunctionsSetFilelayoutFullSQLSelect($callingClass, $notifier, $paramsArray);
        //}
        //$zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_TABLE');
        //if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_SQL_TABLE' ) {
        //$this->updateEP4ExtraFunctionsSetFilelayoutFullSQLTable($callingClass, $notifier, $paramsArray);
        // }
        //$zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_FILELAYOUT');
//        if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_FILELAYOUT' ) {
//            $this->updateEP4ExtraFunctionsSetFilelayoutCategoryFilelayout($callingClass, $notifier, $paramsArray);
//        }
        //$zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_SQL_SELECT');
//        if ( $notifier == 'EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORY_SQL_SELECT' ) {
//            $this->updateEP4ExtraFunctionsSetFilelayoutCategorySQLSelect($callingClass, $notifier, $paramsArray);
//        }
        //$zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORYMETA_FILELAYOUT');
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

        //     'EP4_EXPORT_FILE_ARRAY_START'
        if ( $notifier == 'EP4_EXPORT_FILE_ARRAY_START' ) {
            $this->updateEP4ExportFileArrayStart($callingClass, $notifier, $paramsArray); // mc12345678 doesn't work on ZC 1.5.1 and below
        }
        // 'EP4_EXPORT_CASE_EXPORT_FILE_END'
        if ( $notifier == 'EP4_EXPORT_CASE_EXPORT_FILE_END' ) {
            $this->updateEP4ExportCaseExportFileEnd($callingClass, $notifier, $paramsArray);
        }

        // EP4_EXPORT_WHILE_START
        if ( $notifier == 'EP4_EXPORT_WHILE_START' ) {
            $this->updateEP4ExportWhileStart($callingClass, $notifier, $paramsArray);
        }

        //$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK');
        if ( $notifier == 'EP4_EXPORT_LOOP_FULL_OR_SBASTOCK' ) {
            $this->updateEP4ExportLoopFullOrSBAStock($callingClass, $notifier, $paramsArray);
        }
        // $zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_LOOP');
        if ( $notifier == 'EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_LOOP' ) {
            $this->updateEP4ExportLoopFullOrSBAStockLoop($callingClass, $notifier, $paramsArray);
        }
        //$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_END');
        if ( $notifier == 'EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_END' ) {
            $this->updateEP4ExportLoopFullOrSBAStockEnd($callingClass, $notifier, $paramsArray);
        }
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

        if ( $notifier == 'EP4_IMPORT_AFTER_CATEGORY' ) {
            $this->updateEP4ImportAfterCategory($callingClass, $notifier, $paramsArray);
        }

        if ( $notifier == 'EP4_IMPORT_FILE_NEW_PRODUCT_PRODUCT_TYPE' ) {
            $this->updateEP4ImportFileNewProductProductType($callingClass, $notifier, $paramsArray);
        }

//        if ( $notifier == 'EP4_IMPORT_FILE_END' ) {
//            $this->updateEP4ImportFileEnd($callingClass, $notifier, $paramsArray);
//        }

        if ( $notifier == 'EP4_IMPORT_FILE_PRE_DISPLAY_OUTPUT' ) {
            $this->updateEP4ImportFilePreDisplayOutput($callingClass, $notifier, $paramsArray);
        }
    }

// EOF Update()
}

class ep4BookxVarsOverRide {
    
    private $installed = false;
    
    public $configuration;
    
    public $name;
    /**
     *
     * It simple overrides the default values of SetFields in ep4bookx when a customize file is selected
     */
    public $setFields = array();
    
  
    public function __set($variable, $value) {
        global $variable, $value;

        $this->setFields[$variable] = $value;
    }

    public function __get($variable) {
        global $variable;
        $this->setFields[$variable];
    }

    public function ep4bookxBuild($param) {

        foreach ( $param as $values ) {

            foreach ( $values as $key => $value ) {

                $this->setFields[$key] = $value[0];
            }
        }
    }
    
        public function ep4BookxConfiguration($table) {
        global $db, $conf, $post_action, $process, $ep4bookx_flag_import_ends;

        $post_action = filter_input_array(INPUT_POST);
  
        $sql = "SELECT configuration FROM " . $table . "";
        $result = $db->Execute($sql);

        if ( $result == 1 ) {
            $cnf = json_decode($result->fields['configuration']);

            foreach ( $cnf as $value ) {
                $this->name[$value->name] = $value->text;
                $this->configuration[$value->name] = $value->value;
          
            }  
              
                foreach ( $this->configuration as $keys => $conf ) {
                if ( $this->configuration[$keys] == 1 ) {
                  
                     $process = $this->ep4BookxConfigurationAction($keys);                 
                }
            }
        }
     
         
    }

    private function ep4BookxConfigurationAction($action) {

        global $db, $maintenance, $maintenance_state, $process, $messageStack, $progress_bar;

        if ( $action == 'maintenance' ) {

            $sql = "SELECT configuration_value, configuration_key FROM " . CONFIGURATION . " WHERE  configuration_key  LIKE 'DOWN_FOR_MAINTENANCE' ";
            $result = $db->Execute($sql);

            if ( $result->fields['configuration_value'] == 'false' ) {
                $maintenance_state[] = $result->fields['configuration_value'];
            }

            if ( isset($_GET['export']) && ('bookx' == $_GET['export']) || isset($_POST['export']) && ('ep4bookx_action' == $_POST['export']) ) {
                
                if ( $maintenance_state[0] == 'false' ) {
                    $sql = "UPDATE " . CONFIGURATION . " SET configuration_value = 'true' WHERE  configuration_key  LIKE 'DOWN_FOR_MAINTENANCE' ";
                    $result = $db->Execute($sql);
                    $messageStack->add(sprintf("EP4Bookx as put down the site in maintenance while processing the file. You'll have put on line in zencart configuration"));
                }
            }
        }

        if ( $action == 'optimize_table' ) {

            if ( $_SESSION['ep4bookx_flag_import_ends'] == true ) {
                /** This part of the zencart store manager */
                $sql = "SHOW TABLE STATUS FROM `" . DB_DATABASE . "`";
                $tables = $db->Execute($sql);
                while ( !$tables->EOF ) {
                    // skip tables not matching prefixes
                    if ( DB_PREFIX != '' && substr($tables->fields['Name'], 0, strlen(DB_PREFIX)) != DB_PREFIX ) {
                        $tables->MoveNext();
                        continue;
                    }
                    zen_set_time_limit(600);
                    $db->Execute("OPTIMIZE TABLE `" . $tables->fields['Name'] . "`");
                    $i++;
                    if ( $i / 7 == (int) ($i / 7) )
                        sleep(2);
                    $tables->MoveNext();
                }
                $messageStack->add_session(SUCCESS_DB_OPTIMIZE . ' ' . $i, 'success');
                $action = '';
                unset($_SESSION['ep4bookx_flag_import_ends']);
            }
        }
        
         if ( $action == 'progress_bar' ) {
             $progress_bar = 1;
         }
    }

    private function ep4Bookxinstall() {

        global $db, $ep4bookx_install_configuration, $ep4bookx_install_default_fields_conf, $data, $data2, $messageStack;
        global $ep4bookx_db_table;

        $this->ep4BookxInstallConfig();

        $data = json_encode($ep4bookx_install_configuration);  
        $data2 = json_encode($ep4bookx_install_default_fields_conf);

        $sql = "SELECT configuration_value, configuration_key FROM " . CONFIGURATION . "  WHERE (configuration_key) LIKE 'EASYPOPULATE_4_CONFIG_BOOKX_DATA' ";
        $result = $db->Execute($sql);

        if ( $result->fields['configuration_value'] == 1 ) {

            $db->Execute("DROP TABLE IF EXISTS " . $ep4bookx_db_table . " ");

            $sql2 = " CREATE TABLE " . $ep4bookx_db_table . " ( configuration TEXT NOT NULL, default_fields TEXT NOT NULL, map_fields TEXT NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
            $result2 = $db->Execute($sql2);

            if ( $result2 == 1 ) {

                $sql_insert = "INSERT INTO " . $ep4bookx_db_table . " (configuration, default_fields) VALUES ('" . $data . "', '" . $data2 . "')";
                $result = $db->Execute($sql_insert);

                //include_once(DIR_FS_ADMIN . DIR_WS_LANGUAGES . $_SESSION['language'] . '/easypopulate_4_bookx.php');
                $messageStack->add(EP4BOOKX_MSG_INSTALL, 'success');
            }
        }
    }

    private function ep4BookxRemove() {
        
        global $db, $messageStack, $ep4bookx_db_table;

        $sql = "SELECT configuration_value, configuration_key FROM " . CONFIGURATION . "  WHERE (configuration_key) LIKE 'EASYPOPULATE_4_CONFIG_BOOKX_DATA' ";
        $result = $db->Execute($sql);

        if ( $result->fields['configuration_value'] == 0 ) {

            $db->Execute( "DROP TABLE IF EXISTS " . $ep4bookx_db_table . " ");
            //include_once(DIR_FS_ADMIN . DIR_WS_LANGUAGES . $_SESSION['language'] . '/easypopulate_4_bookx.php');
            $messageStack->add(EP4BOOKX_MSG_INSTALL_REMOVED, 'warning');
        }
    }

    public function ep4BookxCheckInstall($table) {
        global $db;

        $sql = "SHOW TABLES LIKE '" . $table . "'";
        $result = $db->Execute($sql);

        if ( $result->RecordCount() == 0 ) {
            $this->installed = false;
            if ( !$current_page['login.php'] ) {
                $this->ep4BookxInstall();
            }
        } else {
            $this->installed = true;
        }

        // If EP4 is removed we go in solidarity 
        if ( !is_null($_GET['epinstaller']) && $_GET['epinstaller'] == 'remove' || $this->installed == true ) {
            $this->ep4BookxRemove();
        }
    }

    private function ep4BookxInstallConfig() {
        
        global $ep4bookx_project, $ep4bookx_install_configuration, $ep4bookx_install_default_fields_conf;

        $proj_prefix = $ep4bookx_project . '_';

        $ep4bookx_install_configuration = array( 
            '0'=>array('name'=>'progress_bar','value' => 0,'text'=>EP4BOOKX_CONF_PROGRESS_BAR),
            '1'=>array( 'name'=>'maintenance','value' => 0, 'text'=>EP4BOOKX_CONF_MAINTENANCE_MODE),
            '2'=>array('name'=>'optimize_table','value' => 0, 'text'=>EP4BOOKX_CONF_OPTIMIZE_TABLES)
        );

        $proj_prefix = $ep4bookx_project . '_';
        $ep4bookx_install_default_fields_conf = array(
            'ep4bookx_fields' => array(
                'enable_' . $proj_prefix . 'specials' => array(
                    'name' => EP4BOOKX_FIELD_SPECIALS,
                    'value' => true),
                'enable_' . $proj_prefix . 'metatags' => array(
                    'name' => EP4BOOKX_FIELD_METATAGS,
                    'value' => true),
                'enable_' . $proj_prefix . 'subtitle' => array(
                    'name' => EP4BOOKX_FIELD_SUBTITLE,
                    'value' => true),
                'enable_' . $proj_prefix . 'genre_name' => array(
                    'name' => EP4BOOKX_FIELD_GENRE_NAME,
                    'value' => true),
                'enable_' . $proj_prefix . 'publisher_name' => array(
                    'name' => EP4BOOKX_FIELD_PUBLISHER_NAME,
                    'value' => true),
                'enable_' . $proj_prefix . 'series_name' => array(
                    'name' => EP4BOOKX_FIELD_SERIES_NAME,
                    'value' => true),
                'enable_' . $proj_prefix . 'imprint_name' => array(
                    'name' => EP4BOOKX_FIELD_IMPRINT_NAME,
                    'value' => true),
                'enable_' . $proj_prefix . 'binding' => array(
                    'name' => EP4BOOKX_FIELD_BINDING,
                    'value' => true),
                'enable_' . $proj_prefix . 'printing' => array(
                    'name' => EP4BOOKX_FIELD_PRINTING,
                    'value' => true),
                'enable_' . $proj_prefix . 'condition' => array(
                    'name' => EP4BOOKX_FIELD_CONDITION,
                    'value' => true),
                'enable_' . $proj_prefix . 'size' => array(
                    'name' => EP4BOOKX_FIELD_SIZE,
                    'value' => true),
                'enable_' . $proj_prefix . 'volume' => array(
                    'name' => EP4BOOKX_FIELD_VOLUME,
                    'value' => true),
                'enable_' . $proj_prefix . 'pages' => array(
                    'name' => EP4BOOKX_FIELD_PAGES,
                    'value' => true),
                'enable_' . $proj_prefix . 'publishing_date' => array(
                    'name' => EP4BOOKX_FIELD_PUBLISHING_DATE,
                    'value' => true),
                'enable_' . $proj_prefix . 'author_name' => array(
                    'name' => EP4BOOKX_FIELD_AUTHOR_NAME,
                    'value' => true),
                'enable_' . $proj_prefix . 'author_type' => array(
                    'name' => EP4BOOKX_FIELD_AUTHOR_TYPE,
                    'value' => true),
                'enable_' . $proj_prefix . 'manufacturers' => array(
                    'name' => EP4BOOKX_FIELD_MANUFACTURERS,
                    'value' => true),
                'enable_' . $proj_prefix . 'weight' => array(
                    'name' => EP4BOOKX_FIELD_WEIGHT,
                    'value' => true),
            ),
            'ep4bookx_report_fields' => array(
                'report_' . $proj_prefix . 'subtitle' => array(
                    'name' => EP4BOOKX_FIELD_SUBTITLE,
                    'value' => false),
                'report_' . $proj_prefix . 'genre_name' => array(
                    'name' => EP4BOOKX_FIELD_GENRE_NAME,
                    'value' => false),
                'report_' . $proj_prefix . 'publisher_name' => array(
                    'name' => EP4BOOKX_FIELD_PUBLISHER_NAME,
                    'value' => false),
                'report_' . $proj_prefix . 'series_name' => array(
                    'name' => EP4BOOKX_FIELD_SERIES_NAME,
                    'value' => false),
                'report_' . $proj_prefix . 'imprint_name' => array(
                    'name' => EP4BOOKX_FIELD_IMPRINT_NAME,
                    'value' => false),
                'report_' . $proj_prefix . 'binding' => array(
                    'name' => EP4BOOKX_FIELD_BINDING,
                    'value' => false),
                'report_' . $proj_prefix . 'printing' => array(
                    'name' => EP4BOOKX_FIELD_PRINTING,
                    'value' => false),
                'report_' . $proj_prefix . 'condition' => array(
                    'name' => EP4BOOKX_FIELD_CONDITION,
                    'value' => false),
                'report_' . $proj_prefix . 'isbn' => array(
                    'name' => EP4BOOKX_FIELD_ISBN,
                    'value' => false),
                'report_' . $proj_prefix . 'publishing_date' => array(
                    'name' => EP4BOOKX_FIELD_PUBLISHING_DATE,
                    'value' => false),
                'report_' . $proj_prefix . 'author_name' => array(
                    'name' => EP4BOOKX_FIELD_AUTHOR_NAME,
                    'value' => false),
                'report_' . $proj_prefix . 'author_type' => array(
                    'name' => EP4BOOKX_FIELD_AUTHOR_TYPE,
                    'value' => false),
            ),
            'ep4bookx_default_fields' => array(
                'default_' . $proj_prefix . 'author_name' => array(
                    'name' => EP4BOOKX_FIELD_AUTHOR_NAME,
                    'value' => '',
                    'length' => zen_field_length(TABLE_PRODUCT_BOOKX_AUTHORS, 'author_name')),
                'default_' . $proj_prefix . 'author_type' => array(
                    'name' => EP4BOOKX_FIELD_AUTHOR_TYPE,
                    'value' => '',
                    'length' => zen_field_length(TABLE_PRODUCT_BOOKX_AUTHOR_TYPES_DESCRIPTION, 'type_description')),
                'default_' . $proj_prefix . 'printing' => array(
                    'name' => EP4BOOKX_FIELD_PRINTING,
                    'value' => '',
                    'length' => zen_field_length(TABLE_PRODUCT_BOOKX_PRINTING_DESCRIPTION, 'printing_description')),
                'default_' . $proj_prefix . 'binding' => array(
                    'name' => EP4BOOKX_FIELD_BINDING,
                    'value' => '',
                    'length' => zen_field_length(TABLE_PRODUCT_BOOKX_BINDING_DESCRIPTION, 'binding_description')),
                'default_' . $proj_prefix . 'genre_name' => array(
                    'name' => EP4BOOKX_FIELD_GENRE_NAME,
                    'value' => '',
                    'length' => zen_field_length(TABLE_PRODUCT_BOOKX_GENRES_DESCRIPTION, 'genre_description')),
                'default_' . $proj_prefix . 'publisher_name' => array(
                    'name' => EP4BOOKX_FIELD_PUBLISHER_NAME,
                    'value' => '',
                    'length' => zen_field_length(TABLE_PRODUCT_BOOKX_PUBLISHERS, 'publisher_name')),
                'default_' . $proj_prefix . 'imprint_name' => array(
                    'name' => EP4BOOKX_FIELD_IMPRINT_NAME,
                    'value' => '',
                    'length' => zen_field_length(TABLE_PRODUCT_BOOKX_IMPRINTS, 'imprint_name')),
                'default_' . $proj_prefix . 'condition' => array(
                    'name' => EP4BOOKX_FIELD_CONDITION,
                    'value' => '',
                    'length' => zen_field_length(TABLE_PRODUCT_BOOKX_CONDITIONS_DESCRIPTION, 'condition_description')),
            )
        );
    }
    
}
