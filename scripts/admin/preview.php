<?php

	session_start();
	
	include('../connect.php');
	
	function image_resize($source_path, $destination_path, $new_width, $quality = FALSE, $new_height = FALSE)
	{
		ini_set("gd.jpeg_ignore_warning", 1);
		
		list($old_width, $old_height, $type) = getimagesize($source_path);
		
		switch($type)
		{
			case IMAGETYPE_JPEG:
				$typestr = 'jpeg';
				break;
			case IMAGETYPE_GIF:
				$typestr = 'gif';
				break;
			case IMAGETYPE_PNG:
				$typestr = 'png';
				break;
			default:
				break;
		}
		
		$function = "imagecreatefrom$typestr";
		$src_resource = $function($source_path);
		
		if(!$new_height)
		{
			$new_height = round($new_width * $old_height / $old_width);
		}
		elseif(!$new_width)
		{
			$new_width = round($new_height * $old_width / $old_height);
		}
		
		$destination_resource = imagecreatetruecolor($new_width, $new_height);
		
		imagecopyresampled($destination_resource, $src_resource, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
		
		if($type == 2)
		{
			imageinterlace($destination_resource, 1);
			imagejpeg($destination_resource, $destination_path, $quality);
		}
		else
		{
			$function = "image$typestr";
			$function($destination_resource, $destination_path);
		}
		
		imagedestroy($destination_resource);
		imagedestroy($src_resource);
	}

	function image_resize_h($source_path, $destination_path, $new_height, $quality = FALSE, $new_width = FALSE)
	{
		ini_set("gd.jpeg_ignore_warning", 1);
		
		list($old_width, $old_height, $type) = getimagesize($source_path);
		
		switch($type)
		{
			case IMAGETYPE_JPEG:
				$typestr = 'jpeg';
				break;
			case IMAGETYPE_GIF:
				$typestr = 'gif';
				break;
			case IMAGETYPE_PNG:
				$typestr = 'png';
				break;
			default:
				break;
		}
		
		$function = "imagecreatefrom$typestr";
		$src_resource = $function($source_path);
		
		if(!$new_height)
		{
			$new_height = round($new_width * $old_height / $old_width);
		}
		elseif(!$new_width)
		{
			$new_width = round($new_height * $old_width / $old_height);
		}
		
		$destination_resource = imagecreatetruecolor($new_width, $new_height);
		
		imagecopyresampled($destination_resource, $src_resource, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
		
		if($type == 2)
		{
			imageinterlace($destination_resource, 1);
			imagejpeg($destination_resource, $destination_path, $quality);
		}
		else
		{
			$function = "image$typestr";
			$function($destination_resource, $destination_path);
		}
		
		imagedestroy($destination_resource);
		imagedestroy($src_resource);
	}
	
	function randomName()
	{
		$symbols = array('q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', 'Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
									
		$name = "";
									
		for($i = 0; $i < 10; $i++)
		{
			$index = rand(0, 61);
			$name .= $symbols[$index];
		}
									
		$name .= time();
		
		return $name;
	}

?>