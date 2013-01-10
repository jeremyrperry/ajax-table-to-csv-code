//This is the table_to_csv function.  It is highly modular, resuable, and not dependent on the overall amount of rows or cells.  I have personally tested it on a 1,500 row table.  If any modifications to the PHP directories are made, be sure to make changes here accordingly!

function table_to_csv(table_id,file_name,delimiter){
	var record_header = "";
	//This sub-function assumes the table header is in the first row and loops through all child cells.  The default is to read the text.  Please feel free to switch over to HTML if preferred
	$("#" + table_id + " tr:first").children().each(function(){
		record_header += encodeURIComponent($(this).text()) + delimiter;
		//record_header += encodeURIComponent($(this).html()) + delimiter;
	});
	//If the file name is blank, this is the default setting.
	if(file_name == ""){
		file_name = "table_to_csv";
	}
	//If the delimiter is blank, this is the default setting.
	if(delimiter == ""){
		delimiter = "|";
	}
	record_header = record_header.slice(0, -1);
	//The string to eventually be posted to the PHP script starts here.
	var csv_string = "csv_export=true&delimiter=" + delimiter + "&file_name=" + file_name + ".csv&record_header=" + record_header;
	var data_string = "";
	//This sub-function assumes the table cells are in the second and higher rows and loops through all child cells.  The default is to read the text.  Please feel free to switch over to HTML if preferred
	$("#" + table_id + " tr:gt(0)").each(function(){
		var record_string = "";
		$(this).children().each(function(){
			var the_val =$(this).text();
			//var the_val =$(this).html();
			record_string += encodeURIComponent(the_val) + delimiter;
		});
		if(record_string != ""){
			record_string = record_string.slice(0, -1);
			record_string += '<br />';//The record_string uses a <br /> as a temporary line break.  This can be changed at will, but it will also need to be changed on csv_create.php
			data_string += record_string;
		}
	});
	csv_string += "&data_string=" + data_string;//The last addition before the AJAX post.
	//The AJAX post.  It is kept simple and clean, but the full $.ajax command can always be used if need be.
	$.post('table_to_csv_code/php/create_csv.php',csv_string,function(data){
		//create_csv.php sends back a JSON response to let the rest of the code know the overall file creation status.
		var json_data = jQuery.parseJSON(data);
		if(json_data.status == 'success'){
			//Goes on to download.php to export and delete the file from the server.  The deletion process is optional.
			window.location.href = 'table_to_csv_code/php/download.php?file_name=' + json_data.file_name + '&file_path='+json_data.file_path+'&delete_file=true';
		}
		else{
			//Alerts the user to a server-side issue that came up.
			alert("There was an error in creating the CSV data.  Please notify an administrator.");
		}
	});
}

