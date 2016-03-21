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

<?php if ($nanobar == 1) {  // no yet implemented ?>
 <!--  <script type="text/javascript">
//        var options = {
//            bg: '#000'
//        };
//        var nanobar = new Nanobar(options);
//        //move bar
//        nanobar.go(30); // size bar 30%
//        // Finish progress bar
//        nanobar.go(100);
        
   
    </script> -->
<?php
}
