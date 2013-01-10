function table_to_csv(table_id,file_name,delimiter){
	var record_header = "";
	$("#" + table_id + " tr:first").children().each(function(){
		record_header += encodeURIComponent($(this).text()) + delimiter;
	});
	if(file_name == ""){
		file_name = "table_to_csv";
	}
	record_header = record_header.slice(0, -1);
	var csv_string = "csv_export=true&delimiter=" + delimiter + "&file_name=" + file_name + ".csv&record_header=" + record_header;
	var data_string = "";
	$("#" + table_id + " tr:gt(0)").each(function(){
		var record_string = "";
		$(this).children().each(function(){
			var the_val =$(this).text();
			//alert(the_val);
			record_string += encodeURIComponent(the_val) + delimiter;
		});
		if(record_string != ""){
			record_string = record_string.slice(0, -1);
			record_string += '<br />';
			//alert(record_string);
			data_string += record_string;
		}
	});
	csv_string += "&data_string=" + data_string;
	$.post('table_to_csv_code/php/create_csv.php',csv_string,function(data){
		alert(data);
		var json_data = jQuery.parseJSON(data);
		if(json_data.status == 'success'){
			window.location.href = 'table_to_csv_code/php/download.php?file_name=' + json_data.file_name + '&file_path='+json_data.file_path+'&delete_file=true';
		}
		else{
			alert("There was an error in creating the CSV data.  Please notify an administrator.");
		}
	});
}

