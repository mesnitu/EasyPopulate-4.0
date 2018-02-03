<?php
/**
 *  @package EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 *  @version  0.9.9 - Still in development, make your changes in a local environment
 *  @see BookX Module for ZenCart https://sourceforge.net/p/zencartbookx
 *  @see Readme-EP4Bookx
 *  @author mesnitu
 *  @see https://github.com/mesnitu/EasyPopulate-4.0-BookX
 *  $Id: tpl_ep4bookx_fields.php [UTF-8] - 3/mar/2018-18:12:33 mesnitu  $
 *
 */
$class_row = ( $ep4bookx_fields_conf == true ? 'row' : 'rows');
$class_col = ( $ep4bookx_fields_conf == true ? 'col-md-9' : 'rows');
?>
<div class="ep4bookx-section">
    <h4><?php echo EASYPOPULATE_4_DISPLAY_TITLE_BOOKX_FILES; ?>
        <span>
            <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'ep4bookx_action=' . $toogle_config . '" class="ep4bookx-toggle-config" ', $request_type); ?>" class="ep4bookx-toggle-config" ><?php echo $toogle_text; ?></a>

        </span>
    </h4>
    <?php
// Will load the config table
    if ($ep4bookx_fields_conf == true) {
        ?>
        <div class="<?php echo $class_row ?>">
            <div class="<?php echo $class_col ?>">  
                <?php if (empty($ep4bookx_customize_files)) { ?>
                    <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx', $request_type); ?>" class="ep4bookx-download-link">                Complete Fields <i class="glyphicon glyphicon-export"></i><?php //echo EASYPOPULATE_4_DISPLAY_BOOKX_PRODUCTS;?></a>
                    <?php } 
                    else { ?>
                    <span><?php echo EP4BOOKX_EXPORT_CUSTOMIZE; ?></span>
                    <a id="roundabout" href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx&ep4bookxLayoutName=' . current($ep4bookx_customize_files), $request_type); ?>"><?php echo current($ep4bookx_customize_files); ?>
                        <i class="glyphicon glyphicon-export"></i>
                    </a>
    <?php } ?>    
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary ep4bookx-btn-options pull-right" type="button" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
                    <span class="glyphicon glyphicon-cog"></span>
                </button>
            </div>
        </div>
        <div class="collapse" id="collapse">
            <div class="well"> <!--  the collapse div-->
            <!-- <span> <?php //echo EP4BOOKX_FILE_OPTIONS;  ?></span> -->
                <div id="ep4bookx-form">   
                    <div class="ep4bookx-howto input-group-btn">
                        <h3>Customize Layouts</h3>
                        <!-- Button trigger modal -->
                        <button type="button" id="ep4bookx-btn-info" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" data-what="read-readme">
                            <i class="glyphicon glyphicon-info-sign"></i>
                        </button>
                        <span class="ep4bookx-readme">Readme</span>
                    </div>

    <?php if (!empty($ep4bookx_customize_files)) { ?>
                        <div id="customize-fields">
                            <p id="report-files"></p>
                            <?php foreach ($ep4bookx_customize_files as $key => $customize_file) { ?>
                                <div id="<?php echo $key; ?>" class="customize-fields-single" data-layout="<?php echo $customize_file; ?>">
                                    <?php
                                    echo zen_draw_form('ep4bookx_export_customize_' . $key . '', 'easypopulate_4.php?export=bookx', '', 'post', ' class="form-customize-fields form-inline"');
                                    echo zen_draw_hidden_field('ep4bookxLayoutName', $customize_file);
                                    ?>
                                    <div class="box">
                                        <span>Load: <?php echo $customize_file; ?> </span>
                                        <div class="box-inner input-group-btn btn-primary">
                                            <?php echo zen_draw_input_field('ep4bookx_action', 'View', ' id="' . $customize_file . '" class="read-file btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" data-what="read-config"', false, 'button'); ?>
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </div>
                                        <div class="box-inner input-group-btn btn-primary">
                                            <?php echo zen_draw_input_field('ep4bookx_action[ep4bookx_export_customize]', EP4BOOKX_EXPORT_BUTTON, ' id="' . $customize_file . '" class="export-file loadfile btn btn-primary btn-xs"', false, 'submit'); ?>
                                            <i class="glyphicon glyphicon-export"></i>
                                        </div>
                                        <div class="box-inner  input-group-btn btn-danger">
                                            <?php echo zen_draw_input_field('ep4bookx_export_customize', 'Delete', ' id="' . $customize_file . '" class="delete btn btn-danger btn-xs"', false, 'button'); ?>
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            <?php } ?>
                        </div> <!-- ends customize fields -->
                    <?php } ?>
                    <div id="ep4bookx-options">
                        <?php
                        echo zen_draw_form('bookx_fields_enable', '#', '', 'post', 'id="bookx_fields_enable" class="pure-form"');
                        echo zen_draw_hidden_field('ep4bookxLayoutId', rand());
                        ?>
                        <div class="rows">
                            <fieldset id="enable-fields">
                                <legend class="show"><?php echo EP4BOOKX_LEGEND_FIELDS; ?></legend>
                                <p class="ep4bookx-msg"></p>
                                <?php
                                $build_tpl_fields_enable = new ep4bookx();
                                $build_tpl_fields_enable->ep4bookxBuild($ep4bookx_default_cnf, '', 'ep4bookx_fields');

                                foreach ($build_tpl_fields_enable->tplForm as $field => $field_name) {
                                    ?>

                                    <div class="form-group col-xs-12 col-sm-6 col-lg-3">
                                        <label for="<?php echo $field; ?>" class="ep4bookx-checkbox"><?php echo _string($field_name->name); ?></label>  
                                    <?php echo zen_draw_checkbox_field($field, '1', false, '', 'id="' . $field . '" class="pull-right"'); ?>     
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

                                foreach ($build_tpl_report->tplForm as $field => $field_name) {
                                    ?>

                                    <div class="form-group col-xs-12 col-sm-6 col-lg-4">
                                        <label for="<?php echo $field; ?>" class="ep4bookx-checkbox"><?php echo _string($field_name->name); ?></label>  
                                    <?php echo zen_draw_checkbox_field($field, '1', false, '', 'id="' . $field . '" class="pull-right"'); ?>     
                                    </div>    

    <?php } ?>

                            </fieldset>

                            <fieldset id="default-fields">
                                <legend><?php echo EP4BOOKX_LEGEND_DEFAULT_NAMES; ?></legend>
                                <p class="bg-info"></p>
                                <?php
                                $build_tpl_default_names = new ep4bookx();
                                $build_tpl_default_names->ep4bookxBuild($ep4bookx_default_cnf, '', 'ep4bookx_default_fields');

                                foreach ($build_tpl_default_names->tplForm as $field => $field_name) {
                                    ?>
                                    <div class="form-group col-lg-6">
                                        <label for="<?php echo $field; ?>"><?php echo _string($field_name->name); ?></label>
                                    <?php echo zen_draw_input_field($field, '', 'class="default_values form-control input-sm" placeholder="' . EP4BOOKX_DEFAULT_FIELD_EMPTY . '" data-invalid="' . EP4BOOKX_INVALID_FIELD . '" filter="max_length:' . $field_name->length . '" maxlength="' . $field_name->length . '"'); ?>
                                    </div>

    <?php } ?>

                            </fieldset>
                            <div id="ep4bookx-submit">
                                <div class="box"> 
                                    <div class="col-xs-3">
                                        <label><?php echo EP4BOOKX_LEGEND_LAYOUT_NAME; ?></label>  
                                    </div>
                                    <div class="col-xs-4">
                                        <?php echo zen_draw_input_field('ep4bookx_layout', '', 'id="layout_name" class="form-control input-sm col-md-4" placeholder="' . EP4BOOKX_DEFAULT_FIELD_LAYOUT_NAME . '" required="true" data-required="' . EP4BOOKX_REQUIRED_FIELD . '" filter="max_length:32"'); ?>
                                    </div>       
                                    <div class="input-group-btn btn-primary btn-sm col-xs-3">  
    <?php
    echo zen_draw_hidden_field('ep4bookx_action', 'save_layout');
    echo zen_draw_input_field('ep4bookx_conf', EP4BOOKX_SAVE_FILE, ' id="make_file" data-submit="' . EP4BOOKX_SUBMIT_WAIT . '" class="btn btn-primary "', true, 'submit');
    ?>
                                        <i class="glyphicon glyphicon-floppy-disk"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        <div id="ep4bookx-form-config">
                                <?php echo zen_draw_form('ep4bookx_configuration', 'easypopulate_4.php?kkoisa', '', 'post', ' class="form-customize-fields"'); ?>
                            <fieldset id="ep4bookx-config" class="pure-group">
                                <legend><?php echo EP4BOOKX_LEGEND_CONFIGURATION; ?></legend>
                                <p class="bg-info"><?php echo EP4BOOKX_CONF_TXT; ?></p>   
                                <?php
                                foreach ($ep4bookx_configuration->configuration as $name => $value) {

                                    $legend = _string($ep4bookx_configuration->name[$name]);
                                    ?>

                                    <div class="form-group col-xs-12 col-sm-6 col-lg-4">

                                        <label for="<?php echo $name ?>" class="ep4bookx-checkbox"><?php echo _string($ep4bookx_configuration->name[$name]) ?></label>
                                        <?php echo (!empty($value) ? '<span class="at-active">' . EP4BOOKX_CONFIGURATION_ACTIVE . '</span>' : '<span class="at-inactive">' . EP4BOOKX_CONFIGURATION_INACTIVE . '</span>' ); ?>
                                        <?php
                                        echo zen_draw_checkbox_field($name, '1', false, '', 'id=' . $name . '" class="pull-right"');
                                        echo zen_draw_hidden_field($name);
                                        ?>          
                                    </div>
    <?php } ?> 

                            </fieldset>
                            <div id="ep4bookx-conf-submit" class="box">
                                <label><?php echo EP4BOOKX_LEGEND_SAVE_CONFIGURATION; ?></label>
                                <div class="input-group-btn btn-primary">
                                    <?php
                                    echo zen_draw_input_field('ep4bookx_action', EP4BOOKX_SAVE_FILE, ' id="save_configuration" data-submit="' . EP4BOOKX_SUBMIT_WAIT . '" class="btn btn-primary"', true, 'submit');
                                    echo zen_draw_hidden_field('ep4bookx_action', 'save_configuration');
                                    ?>

                                </div>
                            </div>
                            </form>
                        </div>
                    </div> <!-- row --> 
                </div>
            </div> <!-- ends well section -->

        </div> <!-- ends collapse section -->
        <?php
    } // ends fields table enable
    else { // just list the layouts, when config is disable
        if (empty($ep4bookx_customize_files)) {
            ?>
            <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx', $request_type); ?>" class="ep4bookx-toggle-config">
                Complete Fields <i class="glyphicon glyphicon-export"></i><?php //echo EASYPOPULATE_4_DISPLAY_BOOKX_PRODUCTS;       ?></a>
        <?php } else {
        ?>
            <h4>Your Layouts:</h4>
            <ul>
        <?php foreach ($ep4bookx_customize_files as $key => $customize_file) { ?>
                    <li class="customize-fields-single">
                        <a href="<?php echo zen_href_link(FILENAME_EASYPOPULATE_4, 'export=bookx&ep4bookx_layout=' . $customize_file, $request_type); ?>" id="roundabout"><?php echo $customize_file; ?>&nbsp;</a><i class="glyphicon glyphicon-export"></i>
                    </li>
            <?php } ?>
            </ul>
        <?php
        }
    }
    ?>
</div>  <!-- ends ep4bokox section -->
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel"></h4>
                                </div>
                                <div class="modal-body">
    <?php //holds the readme.md file call with jquery  ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>