<?php
//This PHP script creates the CSV file using the data AJAX'ed from the JavaScript table_to_csv function.  All submissions can be sent by post or get, but post is recommended.

//This function converts each data cell into a properly formatted CSV cell.  It does its best to account for whatever situation possible.
function csv_convert($the_val, $val_position){
	$csv_terminated = "\n";
    $csv_separator = ",";
    $csv_enclosed = '"';
    $csv_escaped = "\\";
	$new_line_replace = array("</p>", "<br />", "<br>");
	$val_output = stripslashes(stripslashes($the_val));
	$val_output = str_replace("<p>", "", $val_output);
	$val_output = str_replace($new_line_replace, '\n', $val_output);
	$val_output = str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $val_output);
	$val_output = $csv_enclosed . $val_output . $csv_enclosed;
	if($val_position == "false"){
		$val_output .= $csv_separator;
	}
	else{
		$val_output .= $csv_terminated;
	}
	return $val_output;
}

ini_set("auto_detect_line_endings", true);
//checks to ensure the script isn't accidently invoked.
if(isset($_REQUEST['csv_export'])){
	$record_content = "";//The variable that will store all of the converted data
	$delimiter = $_REQUEST['delimiter'];//Pulls in the delimiter
	$dr = array('"', "'");
	$delimiter = str_replace($dr, "", $delimiter);
	$file_name = $_REQUEST['file_name'];//Pulls the file name.
	$record_header = explode($delimiter, $_REQUEST['record_header']);//Explodes the record header into an array
	$header_count = count($record_header);
	$count = 1;//This variable is to detect when the number of cells is at its max so csv_convert can add the proper seperators and/or terminators.
	foreach($record_header as $header){//Loops through the created header array and sends each value off to csv_convert for proper formatting
		$last = "false";
		if($count == $header_count){
			$last = "true";
			$count = 1;
		}
		else{
			$count++;
		}
		$record_content .= csv_convert($header, $last);

	}
	$data_string = explode("<br />", $_REQUEST['data_string']);//Explodes the data rows into an array.  Be sure to replace the <br /> explode if you have done so in the JavaScript.
	foreach($data_string as $ds){//Loop through each data row array.
		$record_file = explode($delimiter, $ds);//Explodes each data row into an array
		foreach($record_file as $record){//Loops through each data cell, sends it off to csv_convert for formatting, and adds it to record content.
			$last = "false";
			if($count == $header_count){
				$last = "true";
				$count = 1;
			}
			else{
				$count++;
			}
			$record_content .= csv_convert($record, $last);

		}
	}
	$status = "success";
	//The steps below checks for any existing file, deletes it if necessary, and creates the file for eventual downloading.
	$file_path = "temp_files/" . $file_name;
	if(file_exists($file_path)){
		unlink($file_path);
	}
	$csv_file = fopen($file_path, "w");
	fwrite($csv_file, $record_content);
	fclose($csv_file);
	//Creating the output array.  Since some servers need to have the absolute server path, the file_path parameter gets this automatically.
	$output = array(
		'status'=>$status,
		'file_name'=>$file_name,
		'file_path'=>dirname(__FILE__)."/temp_files/"
	);
	print json_encode($output);//The output array is printed out in JSON format.
}

?>