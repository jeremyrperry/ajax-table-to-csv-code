<?php
//The download page.  This is the last one, I swear!

//This function gets all of the necessary information, downloads the file, deletes it if the variable is set to true, and exits the script.
function download_file ($file, $type, $file_path, $delete_file)
{
		//Begin writing the MIME headers.
		header ('Pragma: public') ;
		header ('Expires: 0') ;
		header ('Cache-Control: must-revalidate, post-check=0, pre-check=0') ;
		header ('Cache-Control: public') ;
		header ('Content-Description: File Transfer') ;

		//Use the switch-generated Content-Type.
		header ('Content-Type: ' . $content_type) ;

		//Force the download - set the headers.
		if($type == "file"){
			$name = $file;
		}
		else{
			$name = $filename;
		}
		header ('Content-Disposition: attachment; filename=' . $name . ';') ;
		header ('Content-Transfer-Encoding: binary') ;

	//Now read the file and exit.
	if($type == "file"){
		print file_get_contents($file_path . $file) ;
		if($delete_file == "true"){
			unlink($file_path . $file);
		}
	}
	else{
		print $file;
	}
	exit;//The end.  This may seem like a lot to go through, but it all works and does so fairly quickly.
}

//Checks to ensure the submit variables are set.  I have the JavaScript set to using the get method as it is necessary for the browser to 'navigate' to this page, though it is quick enough to where the user won't even notice.
if(isset($_REQUEST['file_name'])){
	$file_name = $_REQUEST['file_name'];
	$delete_file = "false";
	if(isset($_REQUEST['delete_file'])){
		$delete_file = $_REQUEST['delete_file'];
	}
	$file_path = $_REQUEST['file_path'];
	//With all variables set, it's time to downlaod.
	download_file($file_name, "file", $file_path, $delete_file);
}

?>