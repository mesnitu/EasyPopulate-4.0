<?php
/**
 *  @package EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 *  @version  0.9.9 - Still in development, make your changes in a local environment
 *  @see BookX Module for ZenCart https://sourceforge.net/p/zencartbookx
 *  @see Readme-EP4Bookx
 *  @author mesnitu
 *  @see https://github.com/mesnitu/EasyPopulate-4.0-BookX
 *  $Id: tpl_ep4bookx_fields.php [UTF-8] - 3/mar/2016-18:12:33 mesnitu  $
 *
 */
?>

<div class="ep4bookx-section">
  <div id ="load">&nbsp;</div>
  <h4><?php echo EASYPOPULATE_4_DISPLAY_TITLE_BOOKX_FILES; ?>
    <span>
      <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'ep4bookx_action=' . $toogle_config . ' class="ep4bookx-download-link" ', $request_type); ?>">
        <?php echo $toogle_text; ?></a>
    </span>
  </h4>
  <?php
// Will load the config table
  if ( $ep4bookx_fields_conf == true ) {

      if ( empty($ep4bookx_customize_files) ) {
          ?>
          <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx" class="ep4bookx-download-link"', $request_type); ?>">
            Complete Fields<?php //echo EASYPOPULATE_4_DISPLAY_BOOKX_PRODUCTS;       ?></a>
          <?php
      } else {
          // This was a test using the select form. But in the end , it's two clicks anyway
//            echo zen_draw_form('ep4bookx_export_customize', 'easypopulate_4.php?export=bookx', '', 'post', ' class=""');
//            echo zen_draw_pull_down_menu('ep4bookx_od', ep4bookx_list_layouts($ep4bookx_module_path, '.json')) . ' ';
//            echo zen_draw_input_field('ep4bookx_export_customize', 'Export', ' style="padding: 0px"', false, 'submit');
//               echo '</form>';
          ?>
          
          <span><?php echo EP4BOOKX_EXPORT_CUSTOMIZE; ?></span><a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx&ep4bookxLayoutName=' . current($ep4bookx_customize_files) . '" id="roundabout"', $request_type); ?>"><?php echo current($ep4bookx_customize_files); ?></a>
          <?php
      } // ends else
      ?>
      <!-- <a href="easypopulate_4.php?export=bookx">Export Authors</a> -->
      <button id="ep4bookx-btn-options" class="pure-button pure-button-primary" type="button">
        <i class="icon-settings"></i>
        <span> <?php //echo EP4BOOKX_FILE_OPTIONS;    ?></span>
      </button>
      <div id="ep4bookx-form">   
        <div class="ep4bookx-howto">
          <button id="ep4bookx-btn-info" class="ep4bookx-btn-info pure-button pure-button-primary" type="button"></button> 
          
          <div class="wrap-read">           
           <!-- holds the readme file -->
            </div>
          
          </div>
        
          <?php if ( !empty($ep4bookx_customize_files) ) { ?>
            <div id="customize-fields">
              <h3>Customize Layouts</h3>
              <p id="report-files"></p>
              <?php
              //$id = 0;
              foreach ( $ep4bookx_customize_files as $key => $customize_file ) {
                  ?>
                  <div class="customize-fields-single">
                      <?php
                      echo zen_draw_form('ep4bookx_export_customize_' . $key . '', 'easypopulate_4.php?export=bookx', '', 'post', ' class="form-customize-fields"');
                      echo zen_draw_hidden_field('ep4bookxLayoutName', $customize_file);
                      ?>
                    <div class="box">
                      <span> Load: <?php echo $customize_file; ?> </span>                  
                    <?php
                                  echo zen_draw_input_field('ep4bookx_action', '', ' id="' . $customize_file . '" class="read-file pure-button pure-button-primary"', false, 'button');
                                  echo zen_draw_input_field('ep4bookx_action[ep4bookx_export_customize]', EP4BOOKX_EXPORT_BUTTON, ' id="' . $customize_file . '" class="loadfile pure-button pure-button-primary"', false, 'submit');
                                  echo zen_draw_input_field('ep4bookx_export_customize', 'Delete', ' id="' . $customize_file . '" class="delete pure-button"', false, 'button');
                                  ?>                    
                    </div>
                    </form>
                  </div>
              <?php } ?>
            </div>
        <?php } ?>
        <div id="ep4bookx-options">
          
          <?php
          echo zen_draw_form('bookx_fields_enable', '#', '', 'post', 'id="bookx_fields_enable" class="pure-form"');
          echo zen_draw_hidden_field('ep4bookxLayoutId', rand());
          ?>
          
          <fieldset id="enable-fields">
            <legend class="show"><?php echo EP4BOOKX_LEGEND_FIELDS; ?></legend>
            <p class="ep4bookx-msg"></p>
            <div>
              <label for="bookx_export_specials" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_SPECIALS; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_specials', '1', false, '', 'id="bookx_export_specials"'); ?>
            </div>
            <div>
              <label for="bookx_export_metatags" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_METATAGS; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_metatags', '1', false, '', 'id="bookx_export_metatags"'); ?>
            </div>
            <div>
              <label for="bookx_export_manufacturers" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_MANUFACTURERS; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_manufacturers', '1', false, '', 'id="bookx_export_manufacturers"'); ?>
            </div>
            <!-- <div> Categories are mandatory for new products
              <label for="bookx_export_categories" class="ep4bookx-checkbox"><?php //echo EP4BOOKX_FIELD_CATEGORIES; ?></label>
              <?php //echo zen_draw_checkbox_field('ep4bookx_export_categories', '1', false, '', 'id="bookx_export_categories"'); ?>
            </div> -->
            <div>
              <label for="bookx_export_weight" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_WEIGHT; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_weight', '1', false, '', 'id="bookx_export_weight"'); ?>
            </div>
            <div>
              <label for="bookx_export_genre" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_GENRE_NAME; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_genre', '1', false, '', 'id="bookx_export_genre"'); ?>
            </div>
            <div>
              <label for="bookx_export_publisher" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_PUBLISHER_NAME; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_publisher', '1', false, '', 'id="bookx_export_publisher"'); ?>
            </div>
            <div>
              <label for="bookx_export_series" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_SERIES_NAME; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_series', '1', false, '', 'id="bookx_export_series"'); ?>
            </div>
            <div>
              <label for="bookx_export_imprint" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_IMPRINT_NAME; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_imprint', '1', false, '', 'id="bookx_export_imprint"'); ?>
            </div>
            <div>
              <label for="bookx_export_binding" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_BINDING; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_binding', '1', false, '', 'id="bookx_export_binding"'); ?>
            </div>
            <div>
              <label for="bookx_export_printing" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_PRINTING; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_printing', '1', false, '', 'id="bookx_export_printing"'); ?>
            </div>
            <div>
              <label for="bookx_export_condition" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_CONDITION; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_condition', '1', false, '', 'id="bookx_export_condition"'); ?>
            </div>
            <div>
              <label for="bookx_export_size" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_SIZE; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_size', '1', false, '', 'id="bookx_export_size"'); ?>
            </div>
            <div>
              <label for="bookx_export_pages" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_PAGES; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_pages', '1', false, '', 'id="bookx_export_pages"'); ?>
            </div>
             <div>
              <label for="bookx_export_volume" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_VOLUME; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_volume', '1', false, '', 'id="bookx_export_volume"'); ?>
            </div>
            <div>
              <label for="bookx_export_publishing_date" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_PUBLISHING_DATE; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_publishing_date', '1', false, '', 'id="bookx_export_publishing_date"'); ?>
            </div>
            <div>
              <label for="bookx_export_author" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_AUTHOR_NAME; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_author', '1', false, '', 'id="bookx_export_author"'); ?>
            </div>
            <div>
              <label for="bookx_export_author_type" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_AUTHOR_TYPE; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_author_type', '1', false, '', 'id="bookx_export_author_type"'); ?>
            </div>
            <div>
              <label for="bookx_export_subtitle" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_SUBTITLE; ?></label>
              <?php echo zen_draw_checkbox_field('ep4bookx_export_subtitle', '1', false, '', 'id="bookx_export_subtitle"'); ?>
            </div>
          </fieldset>
          <fieldset id="enable-reports">
            <legend class="show"><?php echo EP4BOOKX_LEGEND_REPORTS; ?></legend>
            <p class="ep4bookx-msg"></p>
            <div>
              <label for="report_subtitle" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_SUBTITLE; ?></label>
              <?php echo zen_draw_checkbox_field('report_ep4bookx_subtitle', '1', false, '', 'id="report_subtitle"'); ?>
            </div>
            <div>
              <label for="report_genre" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_GENRE_NAME; ?></label>
              <?php echo zen_draw_checkbox_field('report_ep4bookx_genre', '1', false, '', 'id="report_genre"'); ?>
            </div>
            <div>
              <label for="report_publisher" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_PUBLISHER_NAME; ?></label>
              <?php echo zen_draw_checkbox_field('report_ep4bookx_publisher', '1', false, '', 'id="report_publisher"'); ?>
            </div>
            <div>
              <label for="report_series" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_SERIES_NAME; ?></label>
              <?php echo zen_draw_checkbox_field('report_ep4bookx_series', '1', false, '', 'id="report_series"'); ?>
            </div>
            <div>
              <label for="report_imprint" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_IMPRINT_NAME; ?></label>
              <?php echo zen_draw_checkbox_field('report_ep4bookx_imprint', '1', false, '', 'id="report_imprint"'); ?>
            </div>
            <div>
              <label for="report_binding " class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_BINDING; ?></label>
              <?php echo zen_draw_checkbox_field('report_ep4bookx_binding ', '1', false, '', 'id="report_binding "'); ?>
            </div>
            <div>
              <label for="report_printing " class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_PRINTING; ?></label>
              <?php echo zen_draw_checkbox_field('report_ep4bookx_printing ', '1', false, '', 'id="report_printing "'); ?>
            </div>
            <div>
              <label for="report_condition" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_CONDITION; ?></label>
              <?php echo zen_draw_checkbox_field('report_ep4bookx_condition', '1', false, '', 'id="report_condition"'); ?>
            </div>
            <div>
              <label for="report_isbn" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_ISBN; ?></label>
              <?php echo zen_draw_checkbox_field('report_ep4bookx_isbn', '1', false, '', 'id="report_isbn"'); ?>
            </div>
            <div>
              <label for="report_publishing_date" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_PUBLISHING_DATE; ?></label>
              <?php echo zen_draw_checkbox_field('report_ep4bookx_publishing_date', '1', false, '', 'id="report_publishing_date"'); ?>
            </div>
            <div>
              <label for="report_author" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_AUTHOR_NAME; ?></label>
              <?php echo zen_draw_checkbox_field('report_ep4bookx_author', '1', false, '', 'id="report_author"'); ?>
            </div>
            <div>
              <label for="report_author_type" class="ep4bookx-checkbox"><?php echo EP4BOOKX_FIELD_AUTHOR_TYPE; ?></label>
              <?php echo zen_draw_checkbox_field('report_ep4bookx_author_type', '1', false, '', 'id="report_author_type"'); ?>
            </div>
          </fieldset>
          <fieldset id="default-fields" class="pure-group">
            <legend><?php echo EP4BOOKX_LEGEND_DEFAULT_NAMES; ?></legend>
            <p class="ep4bookx-msg-info"></p>
           
            <label for="default_ep4bookx_author_name"><?php echo EP4BOOKX_FIELD_AUTHOR_NAME; ?></label>
             <span class="form-group">
            <?php echo zen_draw_input_field('default_ep4bookx_author_name', '', 'class="default_values" placeholder="'.EP4BOOKX_DEFAULT_FIELD_EMPTY.'" data-invalid="'.EP4BOOKX_INVALID_FIELD.'" filter="max_length:' . $bookx_author_name_max_len . '" maxlength="' . $bookx_author_name_max_len . '"'); ?>
             </span>
            <label for="default_ep4bookx_author_type"><?php echo EP4BOOKX_FIELD_AUTHOR_TYPE; ?></label>
             <span class="form-group">
            <?php echo zen_draw_input_field('default_ep4bookx_author_type',  '', 'class="default_values" placeholder="'.EP4BOOKX_DEFAULT_FIELD_EMPTY.'" data-invalid="'.EP4BOOKX_INVALID_FIELD.'" filter="max_length:' . $bookx_author_types_name_max_len . '" maxlength="' . $bookx_author_types_name_max_len . '"'); ?>
           </span>
            <label for="default_ep4bookx_binding"><?php echo EP4BOOKX_FIELD_BINDING; ?></label>
              <span class="form-group">
            <?php echo zen_draw_input_field('default_ep4bookx_binding',  '', 'class="default_values" placeholder="'.EP4BOOKX_DEFAULT_FIELD_EMPTY.'" filter="max_length:' . $bookx_binding_name_max_len . '" maxlength="' . $bookx_binding_name_max_len . '" data-invalid="'.EP4BOOKX_INVALID_FIELD.'"'); ?>
              </span>
            <label for="default_ep4bookx_genre_name"><?php echo EP4BOOKX_FIELD_GENRE_NAME; ?></label>
             <span class="form-group">
            <?php echo zen_draw_input_field('default_ep4bookx_genre_name',  '', 'class="default_values" placeholder="'.EP4BOOKX_DEFAULT_FIELD_EMPTY.'" filter="max_length:' . $bookx_genre_name_max_len . '" maxlength="' . $bookx_genre_name_max_len . '" data-invalid="'.EP4BOOKX_INVALID_FIELD.'"'); ?>
             </span>
            <label for="default_ep4bookx_publisher_name"><?php echo EP4BOOKX_FIELD_PUBLISHER_NAME; ?></label>
            <span class="form-group">
            <?php echo zen_draw_input_field('default_ep4bookx_publisher_name',  '', 'class="default_values" placeholder="'.EP4BOOKX_DEFAULT_FIELD_EMPTY.'" filter="max_length:' . $bookx_publisher_name_max_len . '" maxlength="' . $bookx_publisher_name_max_len . '" data-invalid="'.EP4BOOKX_INVALID_FIELD.'"'); ?>
             </span>
            <label for="default_ep4bookx_imprint_name"><?php echo EP4BOOKX_FIELD_IMPRINT_NAME; ?></label>
             <span class="form-group">
            <?php echo zen_draw_input_field('default_ep4bookx_imprint_name',  '', 'class="default_values" placeholder="'.EP4BOOKX_DEFAULT_FIELD_EMPTY.'" filter="max_length:' . $bookx_imprint_name_max_len . '" maxlength="' . $bookx_imprint_name_max_len . '" data-invalid="'.EP4BOOKX_INVALID_FIELD.'"'); ?>
            <label for="default_ep4bookx_condition"><?php echo EP4BOOKX_FIELD_CONDITION; ?></label>
             <span class="form-group">
            <?php echo zen_draw_input_field('default_ep4bookx_condition',  '', 'class="default_values" placeholder="'.EP4BOOKX_DEFAULT_FIELD_EMPTY.'" filter="max_length:' . $bookx_condition_name_max_len . '" maxlength="' . $bookx_condition_name_max_len . '" data-invalid="'.EP4BOOKX_INVALID_FIELD.'"'); ?>
            <label for="default_ep4bookx_printing"><?php echo EP4BOOKX_FIELD_PRINTING; ?></label>
             <span class="form-group">
            <?php echo zen_draw_input_field('default_ep4bookx_printing',  '', 'class="default_values" placeholder="'.EP4BOOKX_DEFAULT_FIELD_EMPTY.'" filter="max_length:64" maxlength="' . $bookx_printing_name_max_len . '" data-invalid="'.EP4BOOKX_INVALID_FIELD.'"');
            ?>
             </span>
          </fieldset>
          <div id="ep4bookx-submit">
            <label><?php echo EP4BOOKX_LEGEND_LAYOUT_NAME; ?></label>
            <span class="form-group">
            <?php
            echo zen_draw_input_field('ep4bookx_layout', '', 'id="layout_name" placeholder="' . EP4BOOKX_DEFAULT_FIELD_LAYOUT_NAME . '" required="true" data-required="'.EP4BOOKX_REQUIRED_FIELD.'" filter="max_length:32"'); ?>
              </span>
            <?php 
            echo zen_draw_hidden_field('ep4bookx_action', 'save_layout');
            echo zen_draw_input_field('ep4bookx_conf', EP4BOOKX_SAVE_FILE, ' id="make_file" data-submit="'.EP4BOOKX_SUBMIT_WAIT.'" class="pure-button"', true, 'submit');
            ?>
          </div>
          </form>
        </div>
      </div>
      <?php
  } // ends fields table enable
  else { // just list the layouts, when config is disable
      if ( empty($ep4bookx_customize_files) ) {
          ?>
          <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx" class="ep4bookx-download-link"', $request_type); ?>">
            Complete Fields<?php //echo EASYPOPULATE_4_DISPLAY_BOOKX_PRODUCTS;      ?></a>
          <?php
      } else {
          foreach ( $ep4bookx_customize_files as $key => $customize_file ) {
              ?>
              <div class="customize-fields-single">
                <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx&ep4bookx_layout=' . $customize_file . '" id="roundabout"', $request_type); ?>"><?php echo $customize_file; ?></a>
              </div>
              <?php
          }
      }
  }
  ?>
</div>  <!-- ends ep4bokox section -->
