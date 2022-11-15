<?php
/*
 * Class library for uploading functions
 */
if ( ! defined('VALID_INC') ) exit('No direct script access allowed');


/********************************************************
 * Class:			Uploads
 * Description:		Functions to use for uploading images
 */
class Uploads
{
	function reArrayFiles( $file )
	{
		$file_ary = array();
		$file_count = count($file['name']);
		$file_key = array_keys($file);

		for( $i=0;$i<$file_count;$i++ )
		{
			foreach( $file_key as $val )
			{
				$file_ary[$i][$val] = $file[$val][$i];
			}
		}
		return $file_ary;
	}


	function folderPath( $origin, $folder )
	{
		$settings = new Settings;
		$ab_path = $settings->getValue( 'file_path_absolute' );

		global $img_desc;
		$file['name'] = null;
		foreach( $img_desc as $val )
		{
			$newname = $file['name'];
			if( empty( $origin ) )
			{
				$path = $ab_path."".$folder."/";
			}

			else
			{
				$path = $ab_path."".$origin."/".$folder."/";
			}
			move_uploaded_file($val['tmp_name'],$path.$val['name']);
		}
	}


	function cards()
	{
		$settings = new Settings;

		// Upload and unzip cards
		$path = $settings->getValue( 'file_path_absolute' );
		$allowedExts = array("zip", "rar");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);

		// this assumes that the upload form calls the form file field "file"
		$name  = $_FILES["file"]["name"];
		$type  = $_FILES["file"]["type"];
		$size  = $_FILES["file"]["size"];
		$tmp   = $_FILES["file"]["tmp_name"];
		$error = $_FILES["file"]["error"];
		$savepath = "images/cards/";
		$filelocation = $savepath.$name;
		$newfilename = isset( $newfilename ) ? $newfilename : null;

		// This won't upload if there was an error or if the file exists, hence the check
		if( !file_exists($filelocation) && $error == 0 )
		{
			$temp = explode(".", $_FILES["file"]["name"]);
			$newfilename = $name.".".$extension;
			move_uploaded_file($tmp, $path.$newfilename);

			$zip = new ZipArchive;
			$res = $zip->open($path.$newfilename);
			if( $res === TRUE )
			{
				$zip->extractTo($path.$savepath);
				$zip->close();
			}
			unlink($path.$newfilename);
		}

		else
		{
			unlink($path.$newfilename);
			move_uploaded_file($tmp, $filelocation);
			$error[] = "There was an error while uploading your cards.";
		}
	}


	function affiliates()
	{
		$database = new Database;
		$sanitize = new Sanitize;
		$settings = new Settings;
		$path = $settings->getValue( 'file_path_absolute' );
		$bwidth = $settings->getValue( 'button_size_width' );
		$bheight = $settings->getValue( 'button_size_height' );

		$owner = $sanitize->for_db($_POST['owner']);
		$email = $sanitize->for_db($_POST['email']);
		$url = $sanitize->for_db($_POST['url']);
		$subject = $sanitize->for_db($_POST['subject']);
		$status = $sanitize->for_db($_POST['status']);

		$fileName = $bwidth."x".$bheight."-".$subject;

		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);

		// this assumes that the upload form calls the form file field "file"
		$name  = $_FILES["file"]["name"];
		$type  = $_FILES["file"]["type"];
		$size  = $_FILES["file"]["size"];
		$tmp   = $_FILES["file"]["tmp_name"];
		$error = $_FILES["file"]["error"];
		$savepath = "images/aff/";
		$filelocation = $savepath.$name;
		$newfilename = $savepath.$fileName;

		// Check image file dimensions first and then file size
		list($width, $height) = getimagesize($tmp);
		if( $width > $bwidth || $height > $bheight || $width < $bwidth || $height < $bheight )
		{
			echo '<p>Error: Image size must be '.$bwidth.'x'.$bheight.' pixels.</p>';
		}

		else if( $size > 150000 )
		{
			echo '<p>Error: Image file must be a maximum of 150KB only.</p>';
		}

		// This won't upload if there was an error or if the file exists, hence the check
		else if( !file_exists($filelocation) && $error == 0 )
		{
			$temp = explode(".", $_FILES["file"]["name"]);
			$newfilename = $bwidth."x".$bheight."-".$subject.".".$extension;
			move_uploaded_file($tmp, $path.$savepath.$newfilename);

			$database->query("INSERT INTO `tcg_affiliates` (`aff_owner`,`aff_email`,`aff_subject`,`aff_url`,`aff_button`,`aff_status`) VALUES ('$owner','$email','$subject','$url','$newfilename','$status')");

			echo '<center><div class="box-success"><b>Success!</b> Your affiliation has been added and will be approved once checked.</div></center><br />';
		}

		else
		{
			unlink($path.$newfilename);
			move_uploaded_file($tmp, $filelocation);
			echo '<center><div class="box-error"><b>Error!</b> There was an error while processing your form.</div></center><br />';
		}
	}
}
?>