<?php
/*
 *  @package EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 *  @version  0.9.9 - Still in development, make your changes in a local environment
 *  @see BookX Module for ZenCart https://sourceforge.net/p/zencartbookx
 *  @see Readme-EP4Bookx
 *  @author mesnitu 
 *  @see https://github.com/mesnitu/EasyPopulate-4.0-BookX
 *  $Id: tpl_ep4bookx_header.php [UTF-8] - 4/mar/2016-16:44:07 mesnitu  $
 * 
 * This loads the script files to the header. But it would be nice to have a option to load in to the footer also, and separate the js from css and also ** * have the possibility to include in the <body>
 */

?>

<link rel="stylesheet" type="text/css" href="<?php echo $ep4bookx_tpl_path . 'ep4bookx.css'; ?>" />

<?php if ($which_zc < "5.5" ) { // Only load jquery in zc version > 1.5.5 ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?php }
    
if ( $ep4bookx_fields_conf == true ) {  // Only loads if the config is enable ?>
   
    <script language="javascript" type="text/javascript" src="<?php echo $ep4bookx_tpl_path . 'ep4bookx.js'; ?> "></script>
    
    <script>
        jQuery.fn.getFormValues = function () {
            var formvals = {};
            jQuery.each(jQuery(':input', this).serializeArray(), function (i, obj) {
                if (formvals[obj.name] == undefined)
                    formvals[obj.name] = obj.value;
                else if (typeof formvals[obj.name] == Array)
                    formvals[obj.name].push(obj.value);
                else
                    formvals[obj.name] = [formvals[obj.name], obj.value];
            });
            return formvals;
        };
        
        $(document).ready(function () {
            
           // This was another way of placing the site in maintenance before importing. It would alert and then import or export.
           // Now it's beeing made on the class, with a SESSION message. Not so fancy, but it will do.  
//           var state = <?php //print json_encode($maintenance_state); ?>;
//           
//            $(".export-file, #roundabout").click(function() {
//                console.log(state);
//               if (state == "false") {
//                   $("#load").fadeIn("slow");
//                   var putDown = "maintanance";
//                   
//                   $.post("easypopulate_4.php", {
//                    ep4bookx_action: putDown
//                })          
//                            .done(function (data, textStatus, jqXHR) {
//                                var msg = jqXHR.responseText; 
//                                alert("The site is now in maintance mode. After the process , You'll have to put him online manually");
//                                $("#load").fadeOut("slow");
//                                      
//                        })
//                        .fail(function (jqXHR, textStatus, errorThrown) {
//                            alert(jqXHR.responseText);       
//                        });          
//               }
//               return false;             
//            });
            
            $(".pageHeading").parent().addClass("l-content");
            // add class left and right to easypolulate 
            $(".pageHeading").next().addClass("l-right");
            var findLeft = $(".l-right").next();
            findLeft.next().addClass("l-left");
            
            // CLick to open the form
            $("#ep4bookx-btn-options").click(function () {
                $(this).css("height", "auto");
                $(this).next("div").fadeToggle("fast");
                $("#ep4bookx-form").css("background", "white");
                $(".ep4bookx-section").toggleClass("ep4bookx-section-open");
            });
     
           // for input hidden field at configuration form 
           $("#ep4bookx-config input[type=checkbox]").click(function() {
               var data = $(this).val();
                $(this).next().attr("value", data);
           });

            // Click to open info           
            $(".ep4bookx-btn-info").click(function() {
                
                  var btn = $(this);
                  //var file = $(this).attr("id"); //  lang file
                  var identifier = "readme";
                   $.post("easypopulate_4.php", {
                    ep4bookx_action: identifier
                })
                        .done(function (data, textStatus, jqXHR) {
                             btn.css("dispplay", "none");
                             var result = $("<div />").append(jqXHR.responseText).addClass("wrap-read-inner");    
                             $(".wrap-read").css("display", "block").html(result);
                                
                            result.click(function() {
                                $(this).remove();
                            $(".wrap-read").css("display", "none");
                               
                                btn.css("display", "block");
                               });                                                  
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            alert(jqXHR.responseText);
                        });
                return false;                            
            });
            
            // For input values 
            $("#ep4bookx-form input[type=text]").addClass("lightcolor");
            
            // clearing input on focus
            $("#ep4bookx-form input[type=text").on("focus", function () {
                if ($(this).val() === $(this).val()) {
                    $(this).val("").removeClass("lightcolor");
                }
            });
            
            //restoring input value on blrr if the input is left blank
            $("#ep4bookx-form input[type=text]").on("blur", function () {
                if ($(this).val() === "") {
                    var inputtitle = $(this).attr("value");
                    $(this).val(inputtitle).addClass("lightcolor");
                }
            });
          
            // Delete the layout file
            $(".delete").click(function () {
                $("#load").fadeIn();
                var container = $(this).parent();
                var idToDelete = $(this).attr("id");
                var identifier = "delete";
                var load = $(this).next().attr("id");

                $.post("easypopulate_4.php", {
                    ep4bookx_layout: idToDelete,
                    ep4bookx_action: identifier
                })          
                            .done(function (data, textStatus, jqXHR) {

                                var countfiles = jqXHR.responseText;
                                
                                container.slideUp("slow", function () {
                                  $(this).remove();
                                });
                            if ( countfiles == 0 ) {
                                    location.reload(true);
                                }
                                $("#load").fadeOut("slow");
                                      
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            alert(jqXHR.responseText);
                            location.reload(true);
                        });
                return false;
            });
                              
            // To read the layout file
            $(".read-file").click(function () {
               // $("#load").fadeIn();
                var cont = $(this).parent();
                var file = $(this).attr("id");
                var identifier = "read";

                $.post("easypopulate_4.php", {
                    ep4bookx_read: file,
                    ep4bookx_action: identifier
                })
                        .done(function (data, textStatus, jqXHR) {
                            var result = $("<span />").append(jqXHR.responseText);
                            $("#report-files").html(result).fadeIn("slow");
                            result.click(function () {
                                $(this).remove();
                               // $("#load").fadeOut("fast");
                            });
                            
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            alert(jqXHR.responseText);
                        });
                return false;
            });
            
            // Form fields validation and submit
            $("#bookx_fields_enable").valida({
                form_validate: 'novalidate',
                form_autocomplete: 'off',
                tag: 'p',
                lost_focus: true,
                highlight: {
                    marker: '*',
                    position: 'post'
                },
                messages: {
                    submit: 'Wait ...',
                    required: 'Required field',
                    invalid: 'Field with invalid data',
                    textarea_help: 'Typed <span class="at-counter">{0}</span> of {1}'
                },
                use_filter: true,
                before_validate: null,
                after_validate: function (e, err) {

                    if (err) {
                        //alert('Something goes wrong before.');
                    } else {
                        $("#load").fadeIn();
                        var action = 'save_layout';
                        var forStupidIE = $('#bookx_fields_enable').getFormValues();
                        //var setFields = $('#bookx_fields_enable').getFormValues();
                        
                        $.post("easypopulate_4.php", {ep4bookx_action: action, setFields: forStupidIE })
                                .done(function (data, textStatus, jqXHR) {
                                    var result = $("<div />").append(jqXHR.responseText);
                                    $("#load").fadeOut("slow");
                                    location.reload(true);
                                    console.log(  data );
                                })
                                .fail(function (jqXHR, textStatus, errorThrown) {
                                    alert(jqXHR.responseText);
                                });
                        return false;
                    }
                    return !err; // invert the error status  
                }   // callback which runs after default validation
            });

        }); // document ready
    </script>
    <?php
}


