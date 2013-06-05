<?php
//The download page.  This is the last one, I swear!

//This function gets all of the necessary information, downloads the file, deletes it if the variable is set to true, and exits the script.
function downloadFile ($file, $type, $filePath, $deleteFile){
	//Begin writing the MIME headers.
	header ('Pragma: public') ;
	header ('Expires: 0') ;
	header ('Cache-Control: must-revalidate, post-check=0, pre-check=0') ;
	header ('Cache-Control: public') ;
	header ('Content-Description: File Transfer') ;

	//Use the switch-generated Content-Type.
	header ('Content-Type: ' . $contentType) ;

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
		echo file_get_contents($filePath . $file) ;
		if($deleteFile == "true"){
			unlink($filePath . $file);
		}
	}
	else{
		print $file;
	}
	exit;//The end.  This may seem like a lot to go through, but it all works and does so fairly quickly.
}

//Checks to ensure the submit variables are set.  I have the JavaScript set to using the get method as it is necessary for the browser to 'navigate' to this page, though it is quick enough to where the user won't even notice.
if(isset($_REQUEST['file_name'])){
	$fileName = $_REQUEST['file_name'];
	$deleteFile = "false";
	if(isset($_REQUEST['delete_file'])){
		$deleteFile = $_REQUEST['delete_file'];
	}
	$filePath = $_REQUEST['file_path'];
	//With all variables set, it's time to downlaod.
	downloadFile($fileName, "file", $filePath, $deleteFile);
}

?>