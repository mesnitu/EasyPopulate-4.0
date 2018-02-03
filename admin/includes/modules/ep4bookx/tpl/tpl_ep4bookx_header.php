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

<link rel="stylesheet" type="text/css" href="<?php echo EP4BOOKX_TPL_PATH . 'ep4bookx.min.css'; ?>" />

<?php if ($which_zc < "5.5" ) { // Only load jquery in zc version > 1.5.5 ?>
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
<?php }
    
if ( $ep4bookx_fields_conf == true ) {  // Only loads if the config is enable ?>
 <script src="<?php echo EP4BOOKX_TPL_PATH . 'ep4bookx.min.js'; ?>"></script>
    <?php
}
