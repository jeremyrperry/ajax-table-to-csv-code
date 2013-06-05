<?php
//This PHP script creates the CSV file using the data AJAX'ed from the JavaScript table_to_csv function.  All submissions can be sent by post or get, but post is recommended.
ini_set("auto_detect_line_endings", true);

//This function converts each data cell into a properly formatted CSV cell.  It does its best to account for whatever situation possible.
function csvConvert($theVal, $valPosition){
	$csvTerminated = "\n";
    $csvSeparator = ",";
    $csvEnclosed = '"';
    $csvEscaped = "\\";
	$valOutput = stripslashes(stripslashes($theVal));
	$valOutput = str_replace($csvEnclosed, $csvEscaped . $csvEnclosed, $valOutput);
	$valOutput = $csvEnclosed . $valOutput . $csvEnclosed;
	if($valPosition == "false"){
		$valOutput .= $csvSeparator;
	}
	else{
		$valOutput .= $csvTerminated;
	}
	return $valOutput;
}

//checks to ensure the script isn't accidently invoked.
if(isset($_REQUEST['csv_export'])){
	$recordContent = "";//The variable that will store all of the converted data
	$fileName = $_REQUEST['file_name'];//Pulls the file name.
	$json = json_decode($_REQUEST['json'], true);//Decodes the json into a standard PHP array/
	foreach($json as $j=>$jj){
		$arrCount = count($jj);
		$count = 1;//This variable is to detect when the number of cells is at its max so csvConvert can add the proper seperators and/or terminators.
		foreach($jj as $jjj){//Loops through each data cell, sends it off to csvConvert for formatting, and adds it to record content.
			$last = "false";
			if($count == $arrCount){
				$last = "true";
			}
			$recordContent .= csvConvert($jjj, $last);
			$count++;
		}
	}
	$status = "success";
	//An error array is created for storing any errors that may occur in creating the file
	$error = array();
	//The steps below checks for any existing file, deletes it if necessary, and creates the file for eventual downloading.
	$filePath = "temp_files/" . $fileName;
	if(file_exists($filePath)){
		unlink($filePath);
	}
	//The file is created, with an error catcher set up to detect any problems in the process
	$csvFile = fopen($filePath, "w") or $error[] = "Couldn't create file";
	fwrite($csvFile, $recordContent) or $error[] = "Couldn't write file";
	fclose($csvFile) or $error[] = "Couldn't close file";
	//The error array is checked for errors.  If multipe errors occur, only the first one is reported to the user.
	foreach($error as $e){
		if($e != ''){
			$status = $e;
			break;
		}
	}
	//Creating the output array.  Since some servers need to have the absolute server path, the file_path parameter gets this automatically.
	$output = array(
		'status'=>$status,
		'file_name'=>$fileName,
		'file_path'=>dirname(__FILE__)."/temp_files/"
	);
	echo json_encode($output);//The output array is printed out in JSON format.
}