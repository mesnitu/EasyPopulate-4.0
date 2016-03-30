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
      <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'ep4bookx_action=' . $toogle_config . '" class="ep4bookx-download-link" ', $request_type); ?>" class="ep4bookx-download-link" ><?php echo $toogle_text; ?></a>

    </span>
  </h4>
  <?php
// Will load the config table
  if ( $ep4bookx_fields_conf == true ) {

      if ( empty($ep4bookx_customize_files) ) {
          ?>
          <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx', $request_type); ?>" class="ep4bookx-download-link">
            Complete Fields<?php //echo EASYPOPULATE_4_DISPLAY_BOOKX_PRODUCTS;        ?></a>
          <?php
      } else {  ?>

          <span><?php echo EP4BOOKX_EXPORT_CUSTOMIZE; ?></span><a id="roundabout" href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx&ep4bookxLayoutName=' . current($ep4bookx_customize_files), $request_type); ?>"><?php echo current($ep4bookx_customize_files); ?></a>
         
        <?php }  ?>
          
      <!-- <a href="easypopulate_4.php?export=bookx">Export Authors</a> -->
      <button id="ep4bookx-btn-options" class="pure-button pure-button-primary" type="button">
        <i class="icon-settings"></i>
        <span> <?php //echo EP4BOOKX_FILE_OPTIONS;     ?></span>
      </button>
      <div id="ep4bookx-form">   
        <div class="ep4bookx-howto">
          <button id="ep4bookx-btn-info" class="ep4bookx-btn-info pure-button pure-button-primary" type="button"></button> 
          
          <div class="wrap-read">  
            <?php 
            /**
             * holds the readme file call with jquery
             */
            ?> 
          </div>
          
        </div>

        <?php if ( !empty($ep4bookx_customize_files) ) { ?>
            <div id="customize-fields">
              <h3>Customize Layouts</h3>
              <p id="report-files"></p>
              <?php
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
                      echo zen_draw_input_field('ep4bookx_action[ep4bookx_export_customize]', EP4BOOKX_EXPORT_BUTTON, ' id="' . $customize_file . '" class="export-file loadfile pure-button pure-button-primary"', false, 'submit');
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
            <?php
            $build_tpl_fields_enable = new ep4bookx();
            $build_tpl_fields_enable->ep4bookxBuild($ep4bookx_default_cnf, '', 'ep4bookx_fields');
            //$build_tpl_fields_enable->ep4bookxBuild($ep4bookx_default_cnf, '', 'ep4bookx_fields');
            //pr($build_tpl_fields_enable->tplForm, "TESTE");
            foreach ( $build_tpl_fields_enable->tplForm as $field => $field_name ) {
                ?>

                <div>
                  <label for="<?php echo $field; ?>" class="ep4bookx-checkbox"><?php echo _string($field_name->name); ?></label>  
                  <?php echo zen_draw_checkbox_field($field, '1', false, '', 'id="' . $field . '"'); ?>     
                </div>    
            <?php } ?>           
          </fieldset>  
          <fieldset id="enable-reports">
            <legend class="show"><?php echo EP4BOOKX_LEGEND_REPORTS; ?></legend>
            <p class="ep4bookx-msg"></p>           
            <?php
            /**
             * Generate form fields from obj
             */
            $build_tpl_report = new ep4bookx();
            $build_tpl_report->ep4bookxBuild($ep4bookx_default_cnf, '', 'ep4bookx_report_fields');
           
            foreach ( $build_tpl_report->tplForm as $field => $field_name ) {
                ?>

                <div>
                  <label for="<?php echo $field; ?>" class="ep4bookx-checkbox"><?php echo _string($field_name->name); ?></label>  
        <?php echo zen_draw_checkbox_field($field, '1', false, '', 'id="' . $field . '"'); ?>     
                </div>    

    <?php } ?>

          </fieldset>
          <fieldset id="default-fields" class="pure-group">
            <legend><?php echo EP4BOOKX_LEGEND_DEFAULT_NAMES; ?></legend>
            <p class="ep4bookx-msg-info"></p>
            <?php
            $build_tpl_default_names = new ep4bookx();
            $build_tpl_default_names->ep4bookxBuild($ep4bookx_default_cnf, '', 'ep4bookx_default_fields');

            foreach ( $build_tpl_default_names->tplForm as $field => $field_name ) {
                ?>

                <label for="<?php echo $field; ?>"><?php echo _string($field_name->name); ?></label>
                <span class="form-group">
        <?php echo zen_draw_input_field($field, '', 'class="default_values" placeholder="' . EP4BOOKX_DEFAULT_FIELD_EMPTY . '" data-invalid="' . EP4BOOKX_INVALID_FIELD . '" filter="max_length:' . $field_name->length . '" maxlength="' . $field_name->length . '"'); ?>
                </span>

    <?php } ?>

          </fieldset>
          <div id="ep4bookx-submit">
            <label><?php echo EP4BOOKX_LEGEND_LAYOUT_NAME; ?></label>
            <span class="form-group">
                <?php echo zen_draw_input_field('ep4bookx_layout', '', 'id="layout_name" placeholder="' . EP4BOOKX_DEFAULT_FIELD_LAYOUT_NAME . '" required="true" data-required="' . EP4BOOKX_REQUIRED_FIELD . '" filter="max_length:32"'); ?>
            </span>
            <?php
            echo zen_draw_hidden_field('ep4bookx_action', 'save_layout');
            echo zen_draw_input_field('ep4bookx_conf', EP4BOOKX_SAVE_FILE, ' id="make_file" data-submit="' . EP4BOOKX_SUBMIT_WAIT . '" class="pure-button"', true, 'submit');
            ?>
          </div>
          </form>
        </div>
        
          <div id="ep4bookx-form-config">
            <?php
            echo zen_draw_form('ep4bookx_configuration', 'easypopulate_4.php?kkoisa', '', 'post', ' class="form-customize-fields"'); ?>
             <fieldset id="ep4bookx-config" class="pure-group">
            <legend><?php echo EP4BOOKX_LEGEND_CONFIGURATION; ?></legend>
            <p class="ep4bookx-msg-info"><?php echo EP4BOOKX_CONF_TXT; ?></p>   
         <?php   
       
         foreach ($ep4bookx_configuration->configuration as $name => $value) { 

             $legend = _string($ep4bookx_configuration->name[$name]);
                 ?>
            
            <div>
             <?php echo ( !empty($value) ? '<i class="at-active">'.EP4BOOKX_CONFIGURATION_ACTIVE.'</i>' : '<i class="at-inactive">'.EP4BOOKX_CONFIGURATION_INACTIVE.'</i>' ); ?>
             <label for="<?php echo $name ?>" class="ep4bookx-checkbox"><?php echo _string($ep4bookx_configuration->name[$name]) ?></label>
             
             <?php 
             echo zen_draw_checkbox_field($name, '1', false, '', 'id='.$name.'"'); 
             echo zen_draw_hidden_field( $name );     
             ?>          
             </div>
             
           <?php   } ?> 
                           
             </fieldset>
            <div id="ep4bookx-conf-submit">
               <label><?php echo EP4BOOKX_LEGEND_SAVE_CONFIGURATION; ?></label>
            <?php         
            echo zen_draw_input_field('ep4bookx_action', EP4BOOKX_SAVE_FILE, ' id="save_configuration" data-submit="' . EP4BOOKX_SUBMIT_WAIT . '" class="pure-button"', true, 'submit');
            echo zen_draw_hidden_field('ep4bookx_action', 'save_configuration');         
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
          <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx', $request_type); ?>" class="ep4bookx-download-link">
            Complete Fields<?php //echo EASYPOPULATE_4_DISPLAY_BOOKX_PRODUCTS;       ?></a>
          <?php
      } else {
          foreach ( $ep4bookx_customize_files as $key => $customize_file ) {
              ?>
              <div class="customize-fields-single">
                <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx&ep4bookx_layout=' . $customize_file, $request_type); ?>" id="roundabout"><?php echo $customize_file; ?></a>
              </div>
              <?php
          }
      }
  }
  ?>
</div>  <!-- ends ep4bokox section -->

<!-- This is as far as EP4 goes -->
<?php if ($progress_bar == 1) { ?>
 
<?php }
