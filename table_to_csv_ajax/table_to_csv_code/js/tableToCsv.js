//This is the table_to_csv function.  It is highly modular, resuable, and not dependent on the overall amount of rows or cells.  I have personally tested it on a 1,500 row table.  If any modifications to the PHP directories are made, be sure to make changes here accordingly!

function tableToCsv(tableId,fileName,delimiter){
	//objects for the overall JSON and record header are created.
	var json = [];
	var recordHeader = [];
	//This sub-function assumes the table header is in the first row and loops through all child cells.  The default is to read the text.  Please feel free to switch over to HTML if preferred
	$("#" + tableId + " tr:first").children().each(function(){
		recordHeader.push(encodeURIComponent($(this).text()));
		//record_header += encodeURIComponent($(this).html()) + delimiter;
	});
	json.push(recordHeader);
	//If the file name is blank, this is the default setting.
	if(fileName == ""){
		fileName = "table_to_csv";
	}
	//The string to eventually be posted to the PHP script starts here.
	var csvString = "csv_export=true&file_name=" + fileName + ".csv";
	//This sub-function assumes the table cells are in the second and higher rows and loops through all child cells.  The default is to read the text.  Please feel free to switch over to HTML if preferred
	$("#" + tableId + " tr:gt(0)").each(function(){
		var recordString = [];
		$(this).children().each(function(){
			var theVal =$(this).text();
			//var the_val =$(this).html();
			recordString.push(encodeURIComponent(theVal));
		});
		json.push(recordString);
	});
	json = JSON.stringify(json);
	csvString += "&json=" + json;//The last addition before the AJAX post.
	//The AJAX post.  It is kept simple and clean, but the full $.ajax command can always be used if need be.
	$.post('table_to_csv_code/php/createCsv.php',csvString,function(data){
		//create_csv.php sends back a JSON response to let the rest of the code know the overall file creation status.
		var jsonData = jQuery.parseJSON(data);
		if(jsonData.status == 'success'){
			//Goes on to download.php to export and delete the file from the server.  The deletion process is optional.
			window.location.href = 'table_to_csv_code/php/download.php?file_name=' + jsonData.file_name + '&file_path='+jsonData.file_path+'&delete_file=true';
		}
		else{
			//Alerts the user to a server-side issue that came up.
			alert(jsonData.status);
		}
	});
}

