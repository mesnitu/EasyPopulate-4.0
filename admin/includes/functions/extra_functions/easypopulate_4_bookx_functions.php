<?php

/**
 * @EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.5f
 * @version  0.9.9r2 - Still in development, make your changes in a local environment
 * @see Bookx module for ZenCart
 * @see Readme-EP4Bookx
 *
 * @author mesnitu
 */
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

/**
 * @todo  Remove bookx from bookx_extra_description
 * Note: it's on product_bookx_functions
 */
function ep_4_remove_product_bookx($product_model) {
    
   global $db, $ep_debug_logging, $ep_debug_logging_all, $ep_stack_sql_error;
	$project = PROJECT_VERSION_MAJOR.'.'.PROJECT_VERSION_MINOR;
	$ep_uses_mysqli = ((PROJECT_VERSION_MAJOR > '1' || PROJECT_VERSION_MINOR >= '5.3') ? true : false);
	$sql = "SELECT products_id FROM ".TABLE_PRODUCTS;
  switch (EP4_DB_FILTER_KEY) {
    case 'products_model':
      $sql .= " WHERE products_model = :products_model:";
      $sql = $db->bindVars($sql, ':products_model:', $product_model, 'string');
      break;
    case 'blank_new':
    case 'products_id':
      $sql .= " WHERE products_id = :products_id:";
      $sql = $db->bindVars($sql, ':products_id:', $product_model, 'string');
      break;
    default:
      $sql .= " WHERE products_model = :products_model:";
      $sql = $db->bindVars($sql, ':products_model:', $product_model, 'string');
      break;
  }
	$products = $db->Execute($sql);
	if (($ep_uses_mysqli ? mysqli_errno($db->link) : mysql_errno())) {
		$ep_stack_sql_error = true;
		if ($ep_debug_logging == true) {
			$string = "MySQL error ".($ep_uses_mysqli ? mysqli_errno($db->link) : mysql_errno()).": ".($ep_uses_mysqli ? mysqli_error($db->link) : mysql_error())."\nWhen executing:\n$sql\n";
			write_debug_log($string);
		}
	} elseif ($ep_debug_logging_all == true) {
		$string = "MySQL PASSED\nWhen executing:\n$sql\n";
		write_debug_log($string);
	}
	while (!$products->EOF) {
        
        bookx_delete_product($products->fields['products_id']);
        if ( function_exists( bookx_delete_table_search($products->fields['products_id'])) );
		zen_remove_product($products->fields['products_id']);
		$products->MoveNext();
	}
	return;

}

/**
 * 
 * @global type $db
 * @param type $products_id
 * @todo remove this function somewhere else since it's a extra 
 */
function bookx_delete_table_search($products_id) {
    global $db;
    
    $sql = "SELECT product_id FROM " . TABLE_PRODUCTS_BOOKX_SEARCH . " WHERE product_id = '" . (int)$products_id . "'";
    $result = $db->Execute($sql);
    
    if ($result->RecordCount() > 0 ) {
        
        while ( !$result->EOF ) {
            $sql = $db->Execute("DELETE FROM " . TABLE_PRODUCTS_BOOKX_SEARCH . " WHERE product_id = '" . (int)$products_id . "'");
            $result->MoveNext();
        }
    }
}
/**
 * 
 * @param type $obj
 * @return constant
 * @todo this is re assingned the constants. It should look or search for the constant and assing to it
 */
function _string($obj) {
    $temp = serialize($obj);
    $string = unserialize($temp);
    return constant($string);
}

/**
 * ep4bookx_list_layouts 
 * 
 * List and orders directory files by filectime (inode change time of file )
 * in a multidimensional array, so that the last created layout is on top of the list. 
 *  
 * @param string $path  - the directory path
 * @param string $ext   - file extension
 * @param boolean $index   - false for a one-based array
 * @return array
 * 
 * @todo On windows if the files are moved, or just copied again to the folder,  using filectime
 *  wont catch them again. Changed to filemtime.
 */
function ep4bookx_list_layouts($path, $ext, $index = true) {

    $dir = opendir($path);
    $list = array();

    if ($dir) {
        while (false !== ($file = readdir($dir))) {
            //clearstatcache(true, $file);
            if (strstr($file, $ext, false)) {
                $ctime = filectime($path . $file);
                $list[$ctime] = strstr($file, '.', true);
            }
        }
        closedir($dir);
    }

    
    krsort($list);

    if ($index == true) {
        $list = array_combine(array_keys($list), array_values($list));
        $arr2 = array();
        foreach ($list as $key => $value) {
            $arr2[] = array("id" => $key, 'text' => $value);
        }
        return $arr2;
    } else {
        return $list;
    }
}

/**
 * @param type $post_name 
 * @param type $type lower or capitalize words
 * @return $post_name
 */
function cleanImageName($post_name, $type = null) {

    require_once(DIR_FS_CATALOG . DIR_WS_CLASSES . 'class.CeonURIMappingAdmin.php');
    $handleUri = new CeonURIMappingAdmin();
    
    $lang_code = $_SESSION['languages_code'];
  
    $name = $handleUri->_convertStringForURI(trim($post_name), $lang_code);
    //some extra string checks
    $r = array(' ', '-', '.');

    if ($type == 'lower') {
        //for file names
        
        $post_name = str_replace($r, '_', strtolower($name));

        return $post_name;
    } else {
        // for Folders Name

        $post_name = str_replace($r, '', ucwords($name, '-'));

        return $post_name;
    }
}

function download_file_from_url($url, $imageName) {
    
    $ext = EP4BOOKX_MANIPULATE_IMAGES_EXTENSION;
    
    if (!file_exists($imageName . $ext)) {
       
        $ch = curl_init($url);
        $fp = fopen($imageName . '.jpg', 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    } 
   
}


/*
 * To resize Images 
 * @https://inkplant.com/code/php-resize-image-function
 */
function resize_image_force($image,$width,$height) {
	$w = @imagesx($image); //current width
	$h = @imagesy($image); //current height
	if ((!$w) || (!$h)) { 
        $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.'; return false;  
    }
	if (($w == $width) && ($h == $height)) { 
        return $image; 
    } //no resizing needed

	$image2 = imagecreatetruecolor ($width, $height);
	imagecopyresampled($image2,$image, 0, 0, 0, 0, $width, $height, $w, $h);

	return $image2;
}

function resize_image_max($image,$max_width,$max_height) {
	$w = imagesx($image); //current width
	$h = imagesy($image); //current height
	if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.'; return false; }

	if (($w <= $max_width) && ($h <= $max_height)) { 
        return $image; 
    } //no resizing needed
	
	//try max width first...
	$ratio = $max_width / $w;
	$new_w = $max_width;
	$new_h = $h * $ratio;
	
	//if that didn't work
	if ($new_h > $max_height) {
		$ratio = $max_height / $h;
		$new_h = $max_height;
		$new_w = $w * $ratio;
	}
	
	$new_image = imagecreatetruecolor ($new_w, $new_h);
	imagecopyresampled($new_image,$image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
	return $new_image;
}

function resize_image_crop($image,$width,$height) {
	$w = @imagesx($image); //current width
	$h = @imagesy($image); //current height
	if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.'; return false; }
	if (($w == $width) && ($h == $height)) { return $image; } //no resizing needed
	
	//try max width first...
	$ratio = $width / $w;
	$new_w = $width;
	$new_h = $h * $ratio;
	
	//if that created an image smaller than what we wanted, try the other way
	if ($new_h < $height) {
		$ratio = $height / $h;
		$new_h = $height;
		$new_w = $w * $ratio;
	}
	
	$image2 = imagecreatetruecolor ($new_w, $new_h);
	imagecopyresampled($image2,$image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);

	//check to see if cropping needs to happen
	if (($new_h != $height) || ($new_w != $width)) {
		$image3 = imagecreatetruecolor ($width, $height);
		if ($new_h > $height) { //crop vertically
			$extra = $new_h - $height;
			$x = 0; //source x
			$y = round($extra / 2); //source y
			imagecopyresampled($image3,$image2, 0, 0, $x, $y, $width, $height, $width, $height);
		} else {
			$extra = $new_w - $width;
			$x = round($extra / 2); //source x
			$y = 0; //source y
			imagecopyresampled($image3,$image2, 0, 0, $x, $y, $width, $height, $width, $height);
		}
		imagedestroy($image2);
		return $image3;
	} else {
		return $image2;
	}
}

function resize_image($method,$image_loc,$new_loc,$width,$height) {
	if (!is_array(@$GLOBALS['errors'])) { $GLOBALS['errors'] = array(); }
	//pr($image_loc.' -- '.$new_loc. '::resize_image');
	if (!in_array($method,array('force','max','crop'))) { 
        $GLOBALS['errors'][] = 'Invalid method selected.'; 
        
    }
	
	if (!$image_loc) { $GLOBALS['errors'][] = 'No source image location specified.'; }
	else {
		if ((substr(strtolower($image_loc),0,7) == 'http://') || (substr(strtolower($image_loc),0,7) == 'https://')) { 
            /*don't check to see if file exists since it's not local*/ 
            
        }
		elseif (!file_exists($image_loc)) { $GLOBALS['errors'][] = $image_loc. ' !!!Image source file does not exist.'; }
		$extension = strtolower(substr($image_loc,strrpos($image_loc,'.')));
       
		if (!in_array($extension,array('.jpg','.jpeg','.png','.gif','.bmp'))) { $GLOBALS['errors'][] = $image_loc.' -Invalid source file extension!'; }
	}
	
	if (!$new_loc) { $GLOBALS['errors'][] = $new_loc.' !!! No destination image location specified.'; }
	else {
		$new_extension = strtolower(substr($new_loc,strrpos($new_loc,'.')));
		if (!in_array($new_extension,array('.jpg','.jpeg','.png','.gif','.bmp'))) { $GLOBALS['errors'][] = 'Invalid destination file extension!'; }
	}

	$width = abs(intval($width));
	if (!$width) { $GLOBALS['errors'][] = 'No width specified!'; }
	
	$height = abs(intval($height));
	if (!$height) { $GLOBALS['errors'][] = 'No height specified!'; }
	
	if (count($GLOBALS['errors']) > 0) { echo_errors(); return false; }
	
	if (in_array($extension,array('.jpg','.jpeg'))) { 
        
        $image = imagecreatefromjpeg($image_loc); 
        
    }
	elseif ($extension == '.png') { $image = @imagecreatefrompng($image_loc); }
	elseif ($extension == '.gif') { $image = @imagecreatefromgif($image_loc); }
	elseif ($extension == '.bmp') { $image = @imagecreatefromwbmp($image_loc); }
	
	if (!$image) { $GLOBALS['errors'][] = $image_loc.' !!! Image could not be generated!'; }
	else {
		$current_width = imagesx($image);
		$current_height = imagesy($image);
		if ((!$current_width) || (!$current_height)) { $GLOBALS['errors'][] = 'Generated image has invalid dimensions!'; }
	}
	if (count($GLOBALS['errors']) > 0) { imagedestroy($image); echo_errors(); return false; }

	if ($method == 'force') { $new_image = resize_image_force($image,$width,$height); }
	elseif ($method == 'max') { $new_image = resize_image_max($image,$width,$height); }
	elseif ($method == 'crop') { $new_image = resize_image_crop($image,$width,$height); }
	
	if ((!$new_image) && (count($GLOBALS['errors'] == 0))) { $GLOBALS['errors'][] = 'New image could not be generated!'; }
	if (count($GLOBALS['errors']) > 0) { imagedestroy($image); echo_errors(); return false; }
	
	$save_error = false;
    
	if (in_array($extension,array('.jpg','.jpeg'))) { 

        imagejpeg($new_image,$new_loc, 70) or ($save_error = true); 
        
    }
	elseif ($extension == '.png') { imagepng($new_image,$new_loc) or ($save_error = true); }
	elseif ($extension == '.gif') { imagegif($new_image,$new_loc) or ($save_error = true); }
	elseif ($extension == '.bmp') { imagewbmp($new_image,$new_loc) or ($save_error = true); }
	if ($save_error) { 
        $GLOBALS['errors'][] = 'New image could not be saved!'. $image. ' ::: New Image' .$new_loc; 
        
    }
	if (count($GLOBALS['errors']) > 0) { 
        imagedestroy($image); imagedestroy($new_image); echo_errors(); return false; 
        
    }

	imagedestroy($image);
	imagedestroy($new_image);
	
	return true;
}

function echo_errors() {
	if (!is_array(@$GLOBALS['errors'])) { 
        $GLOBALS['errors'] = array('Unknown error!'); 
        
    }
	foreach ($GLOBALS['errors'] as $error) { 
        echo '<p style="color:red;font-weight:bold;">Error: '.$error.'</p>'; 
        
    }
}