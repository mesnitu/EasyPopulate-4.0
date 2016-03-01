<?php

/**
 * Description of class.ep4bookx
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

/* Function run/called by notifier: EP4_START*/
  function updateEP4Start(&$callingClass, $notifier, $paramsArray){
    global $db, $curver, $ep_bookx, $ep_bookx_fallback_genre_name, $bookx_product_type;
    global $bookx_author_name_max_len, $bookx_author_types_name_max_len, 
      $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len, 
      $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len, 
      $bookx_imprint_name_max_len, $bookx_subtitle_max_len;

    $curver .= "<br />";
    $curver .= "w/ BookX v1.0";
/**
 *  @EP4Bookx 
 *  Get the user config
 */

    $ep_bookx         = (int)EASYPOPULATE_4_CONFIG_BOOKX_DATA; // 0-Disable, 1-Enable
    $ep_bookx_fallback_genre_name = EASYPOPULATE_4_CONFIG_BOOKX_DEFAULT_GENRE_NAME; 

    if ($ep_bookx = 1) {
      $result = $db->Execute('SELECT type_id FROM ' . TABLE_PRODUCT_TYPES . ' WHERE type_handler = \'product_bookx\'');
      $bookx_product_type = $result->fields['type_id'];
      require(DIR_FS_ADMIN . DIR_WS_LANGUAGES . $_SESSION['language'] . '/easypopulate_4_bookx.php');
    }
    
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

  // $zco_notifier->notify('EP4_COLLATION_UTF8_ZC13X');
  function updateEP4CollationUTF8ZC13x(&$callingClass, $notifier, $paramsArray) {
    global $bookx_author_name_max_len, $bookx_author_types_name_max_len, 
      $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len, 
      $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len, 
      $bookx_imprint_name_max_len, $bookx_subtitle_max_len;
    
    $bookx_author_name_max_len = $bookx_author_name_max_len/3;
    $bookx_author_types_name_max_len = $bookx_author_types_name_max_len/3;
    $bookx_genre_name_max_len  = $bookx_genre_name_max_len/3;
    $bookx_series_name_max_len = $bookx_series_name_max_len/3;
    $bookx_publisher_name_max_len = $bookx_publisher_name_max_len/3;
    $bookx_binding_name_max_len = $bookx_binding_name_max_len/3;
    $bookx_printing_name_max_len = $bookx_printing_name_max_len/3;
    $bookx_condition_name_max_len = $bookx_condition_name_max_len/3;
    $bookx_imprint_name_max_len = $bookx_imprint_name_max_len/3;
    $bookx_subtitle_max_len = $bookx_subtitle_max_len/3;
  }

  // $zco_notifier->notify('EP4_EASYPOPULATE_4_LINK');
  function updateEP4Easypopulate4Link(&$callingClass, $notifier, $paramsArray) {
    ?>
    <link rel="stylesheet" type="text/css" href="includes/ep4bookx.css">
    <?php
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
      $bookx_imprint_name_max_len, $bookx_subtitle_max_len;

    echo 'author_name:'.$bookx_author_name_max_len.'<br/>';
    echo 'genre_description:'.$bookx_genre_name_max_len.'<br/>';		
    echo 'series_name:'.$bookx_series_name_max_len.'<br/>';
    echo 'publisher_name:'.$bookx_publisher_name_max_len.'<br/>';
    echo 'binding_description:'.$bookx_binding_name_max_len.'<br/>';
    echo 'printing_description:'.$bookx_printing_name_max_len.'<br/>';
    echo 'condition_description:'.$bookx_condition_name_max_len.'<br/>';
    echo 'imprint_name:'.$bookx_imprint_name_max_len.'<br/>';
    
  }

  // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_START');
  function updateEP4ExtraFunctionsSetFilelayoutFullStart(&$callingClass, $notifier, $paramsArray) {
  }

    //$zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_FULL_FILELAYOUT');
  function updateEP4ExtraFunctionsSetFilelayoutFullFilelayout(&$callingClass, $notifier, $paramsArray) {
    global $filelayout, $langcode;

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
    global $langcode, $filelayout;
    
  }

  //  $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CATEGORYMETA_FILELAYOUT');
  function updateEP4ExtraFunctionsSetFilelayoutCategorymetaFilelayout(&$callingClass, $notifier, $paramsArray) {
  }

    // $zco_notifier->notify('EP4_EXTRA_FUNCTIONS_SET_FILELAYOUT_CASE_DEFAULT');
  function updateEP4ExtraFunctionsSetFilelayoutCaseDefault(&$callingClass, $notifier, $paramsArray) {
    global $db, $ep_dltype, $filelayout, $filelayout_sql, $langcode;
    switch($ep_dltype) {
    
  case 'TEST_1':
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
  
    if ( (substr($project,0,5) == "1.3.8") || (substr($project,0,5) == "1.3.9") ) {
      $db->Execute("INSERT INTO ".TABLE_CONFIGURATION." (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
        ('Enable Products Bookx','EASYPOPULATE_4_CONFIG_BOOKX_DATA', '0', 'Enable Products Bookx Data Columns
        (default 0).<br><br>0=Disable<br>1=Enable', ".$group_id.", '230', NULL, now(), NULL, 'zen_cfg_select_option(array(\"0\", \"1\"),')
      ");
    } elseif (PROJECT_VERSION_MAJOR > '1' || PROJECT_VERSION_MINOR >= '5.0') {
      $db->Execute("INSERT INTO ".TABLE_CONFIGURATION." (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES 
        ('Enable Products Bookx','EASYPOPULATE_4_CONFIG_BOOKX_DATA', '0', 'Enable Products Bookx Data Columns
        (default 0).<br><br>0=Disable<br>1=Enable', ".$group_id.", '230', NULL, now(), NULL, 'zen_cfg_select_option(array(\"0\", \"1\"),')
      ");
    } else { // unsupported version 
      // i should do something here!
    }
  }

    // $zco_notifier->notify('EP4_LINK_SELECTION_END');
  function updateEP4LinkSelectionEnd(&$callingClass, $notifier, $paramsArray) {
    
  }
  
  // $zco_notifier->notify('EP4_FILENAMES');
  function updateEP4Filenames(&$callingClass, $notifier, $paramsArray) {
    global $filenames;
    
    $filenames = array_merge($filenames,
      array('bookx-ep' => BOOKX_EP_DESC,
      'bookx-auth-ep' => BOOKX_AUTH_EP_DESC)
    );
    print_r($filenames);
  }

  
  // 'EP4_EXPORT_FILE_ARRAY_START'
  function updateEP4ExportFileArrayStart(&$callingClass, $notifier, $paramsArray) { // mc12345678 doesn't work on ZC 1.5.1 and below
    global $ep_dltype, $filelayout_sql, $ep_uses_mysqli, $filelayout, $row;
  }

  // 'EP4_EXPORT_CASE_EXPORT_FILE_END'
  function updateEP4ExportCaseExportFileEnd(&$callingClass, $notifier, $paramsArray) {
    global $ep_dltype, $EXPORT_FILE;
  
    if ($ep_dltype == 'DEMO-1') {
      $EXPORT_FILE = 'DEMO-1-EP';
    } elseif ($ep_dltype == 'DEMO-2') { 
      $EXPORT_FILE = 'DEMO-2-EP';
    } elseif ($ep_dltype == 'DEMO-3') { 
      $EXPORT_FILE = 'DEMO-3-EP';
    }
  }

// EP4_EXPORT_WHILE_START
  function updateEP4ExportWhileStart(&$callingClass, $notifier, $paramsArray) {


  }

  //$zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK');
  function updateEP4ExportLoopFullOrSBAStock(&$callingClass, $notifier, $paramsArray) {
  
  }

  //  $zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_LOOP');
  function updateEP4ExportLoopFullOrSBAStockLoop(&$callingClass, $notifier, $paramsArray) {
  
  }

//    $zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_END');
  function updateEP4ExportLoopFullOrSBAStockEnd(&$callingClass, $notifier, $paramsArray) {
  }


//  $zco_notifier->notify('EP4_EXPORT_SPECIALS_AFTER');
  function updateEP4ExportSpecialsAfter(&$callingClass, $notifier, $paramsArray) {
    global $row, $filelayout, $epdlanguage_id, $langcode;

    include DIR_FS_ADMIN . 'easypopulate_4_export_bookx.php';
  }

//  $zco_notifier->notify('EP4_EXPORT_FULL_OR_CAT_FULL_AFTER');
  function updateEP4ExportFullOrCatFullAfter(&$callingClass, $notifier, $paramsArray) {
  }

  //     $notifyme[] = 'EP4_IMPORT_START';
  function updateEP4ImportStart(&$callingClass, $notifier, $paramsArray) {
    global $bookx_reports;
    /**
     * @EP4Bookx - 1 of 4
     * [It aggregates missing fields in a report linked to the imported book. Uses Bookx languages files as key so it can be tranlated ex: BOX_CATALOG_PRODUCT_BOOKX_PUBLISHERS]
     * @see   [adminFolder/includes/languades/YOUR_lang/extra_definitions/product_bookx.php]
     * @var array
     */
    $bookx_reports = array();
    //ends ep4bookx
    //
  }

  // EP4_IMPORT_FILE_EARLY_ROW_PROCESSING
  function updateEP4ImportFileEarlyRowProcessing(&$callingClass, $notifier, $paramsArray) {
    global $items, $filelayout, $display_output, $continueNextRow;

    if ($items[$filelayout['v_status']] == 10) {
      $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_BOOKX_DELETED, $items[$filelayout['v_products_model']],$items[$filelayout['v_bookx_isbn']]);
      /**
       * Using Bookx function to remove books.
       * @todo Remove from bookx_extra_description
       */
      ep_4_remove_product_bookx($items[$filelayout['v_products_model']]);

      $continueNextRow = true;
    }
    //ends ep4bookx

  }

  // EP4_IMPORT_AFTER_CATEGORY
  function updateEP4ImportAfterCategory(&$callingClass, $notifier, $paramsArray){
    global $v_products_id, $bookx_product_type, $enable_bookx_genre_name, $langcode,
           $items, $filelayout, $db, $bookx_reports, $v_bookx_isbn, $v_bookx_genre_name,
           $display_output, $ep_error_count,
           $bookx_author_name_max_len, $bookx_author_types_name_max_len,
           $bookx_genre_name_max_len, $bookx_series_name_max_len, $bookx_publisher_name_max_len,
           $bookx_binding_name_max_len, $bookx_printing_name_max_len, $bookx_condition_name_max_len,
           $bookx_imprint_name_max_len, $bookx_subtitle_max_len,
    $bookx_default_author_name, $bookx_default_author_type, $bookx_default_printing,
    $bookx_default_binding, $bookx_default_genre_name, $bookx_default_publisher_name,
    $bookx_default_series_name, $bookx_default_imprint_name, $bookx_default_condition,
    $report_bookx_subtitle, $report_bookx_genre_name, $report_bookx_publisher_name,
    $report_bookx_series_name, $report_bookx_imprint_name, $report_bookx_binding,
    $report_bookx_printing, $report_bookx_condition, $report_bookx_isbn,
    $report_bookx_size, $report_bookx_volume, $report_bookx_pages,
    $report_bookx_publishing_date, $report_bookx_author_name, $report_bookx_author_type;

    /**
     * @EP4Bookx 4 of 5
     * At last but not least , include the bookx import file. Try to stay clean
     */
    include DIR_FS_ADMIN . 'easypopulate_4_import_bookx.php';
    //end ep4bookx
  }

  // EP4_IMPORT_FILE_NEW_PRODUCT_PRODUCT_TYPE
  function updateEP4ImportFileNewProductProductType(&$callingClass, $notifier, $paramsArray) {
    global $v_products_type, $v_artists_name, $bookx_product_type, $v_bookx_genre_name, $v_bookx_isbn;

    // Assign product_type to bookx product type (overriding any previous product type assignment)
    //  if the either of the applicable bookx product type fields are populated.  Otherwise,
    //  will use the default which is a product type of 1 (generic product), this means that
    //  for all new product, at least one of these fields must be included as a part of this
    //  addin in order for the product type to be properly entered.

    if (isset($v_bookx_genre_name) || isset($v_bookx_isbn) )
    {
      $v_products_type = $bookx_product_type;
    }

  }

  // EP4_IMPORT_FILE_PRE_DISPLAY_OUTPUT
  function updateEP4ImportFilePreDisplayOutput(&$callingClass, $notifier, $paramsArray) {
    $bookx_reports, $display_output;
    /**
     * @EP4Bookx
     * Reports missing fields with the book edit link
     */
    if (!empty($bookx_reports)) {
      $display_output .= '<table class="bookx-reports"><caption>'.EASYPOPULATE_4_DISPLAY_BOOKX_REPORTS_BOOKX_HEADER.'</caption><tr class="bookx-reports-top"><th >Type</th><th>'.EASYPOPULATE_4_BOOKX_TABLE_BOOK.'</th></tr>';

      foreach ($bookx_reports as $key => $value) {
        $display_output .= '<tr><th class="bookx-reports-th-left" rowspan ="'.(count($value) + 1).'">' . strtoupper($key) . '</th>';
        $display_output .= '<th class="bookx-reports-th-caption">'. EASYPOPULATE_4_BOOKX_TABLE_CAPTION . '</th></tr>';

        $lastKey = count($value)-1;

        for ($i=0; $i < (count($value)) ; $i++) {

          $class = ($i & 1) ? 'odd' : 'even';
          ($i == $lastKey ? $class .=' last' :'');
          $display_output .= '<tr ><td class="' . $class .'">'. $value[$i] . '</td></tr>';
        }
      }
      $display_output .='</table>';
    }
    //ends ep4Bookx
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

//    $zco_notifier->notify('EP4_EXPORT_LOOP_FULL_OR_SBASTOCK_END');
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

    if ($notifier == 'EP4_IMPORT_FILE_EARLY_ROW_PROCESSING'){
      $this->updateEP4ImportFileEarlyRowProcessing($callingClass, $notifier, $paramsArray);
    }

    if ($notifier == 'EP4_IMPORT_FILE_NEW_PRODUCT_PRODUCT_TYPE') {
      $this->updateEP4ImportFileNewProductProductType($callingClass, $notifier, $paramsArray);
    }

    if ($notifier == 'EP4_IMPORT_FILE_PRE_DISPLAY_OUTPUT') {
      $this->updateEP4ImportFilePreDisplayOutput($callingClass, $notifier, $paramsArray);
    }

    } // EOF Update()
}
