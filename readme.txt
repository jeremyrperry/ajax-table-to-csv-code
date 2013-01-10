This code repository is a seamless jQuery-based AJAX and PHP based tool that permits the content from a table to be read and downloaded without the need to refresh the screen.  While not as simplistic as some other JavaScript-based CSV exporters, it is very robust and offers a wide degree of flexbility of the number of tables that can be read.  It utilizes a jQuery command for calling up the function which reads the table, AJAX's the data to the server, creates a hard copy of the file, and downloads to the user's computer.  The only requirement is that the table has an element id is an overall straightforward table without any complex subtables.  With the way the code is currently set up, the first row must contain table headers (<th>Header</th>) and all subsequent rows must contain standard table cells(<td>Data Row</td>).  A web developer with sufficient knowledge of HTML, JavaScript, AJAX, and jQuery will be needed to properly set up this tool.  Experience with PHP is recommended.  The tool makes use of PHP and will only run in a server environment, but the page with the table being read is not required to be in PHP.  It has been tested in a LAMP environment, and while it should work in an IIS environment, some modifications may be necessary.  It was tested using the latest version of jQuery on jQuery.com's CDN, and either the newest version of jQuery or the newest version possible for your site is recommended.  A full demonstration is included.

This tool is open source and free for use in all non-commercial settings.  It is not to be utilized to power paid content without my express authorization.  The code is not to be sold in whole or in part without my express authorization, but there is no restriction on other web developers charging development and consulting time to properly set up the code.  You are free to modify the source code as as you see fit, but if any part of my original code is used, you must keep notation of me as either the author or that your work was based on my design.  It comes without any implied warranties and I will not provide unpaid tech support.  You are free to submit bug reports and update the repository with relevant updates.

Instructions:

1.  Install the table_to_csv_code directory on your server.  If you modify the directory names, directory structure, and/or place the directory in any other place than root folder of your website, you will need to modify the header reference code and other code sections accordingly.

2.  In the head of your document, paste the following JavaScript reference:  <script type="text/javascript" src="table_to_csv_code/js/table_to_csv.js"></script>.  This code must be after a jQuery reference, or it will not work.  Heed note 1.

3.  You will need to have a button to call the JavaScript command if you don't have another way.  Here is an example:  <input type="button" name="csv_export" id="csv_export" value="Export to CSV" />.  Please be aware that my code uses a jQuery id reference for this button, but you may modify this according to your needs.

4.  You will need a JavaScript command to call up the function.  This code may be placed either in the head or after the button reference.  Here is my jQuery-based example:  

<script>
$(document).ready(function(){
  $("#csv_export").click(function(){
		$("table").each(function(){
			if($(this).attr("id") !== undefined){
				table_to_csv($(this).attr("id"), "table_to_csv_export", "|");
			}
		});
	});
});
</script>

The example is set to read every table on the page and perform a CSV export, but you may modify accordingly.

5.  All tables to be read must have an element ID associated with it, or the code as it is set up right now will not be able to read it.  Example:  <table id="table_export">
  
7.  The exporter function call's first attribute is the table id.  The second attribute is the file name you want your export to be.  My code will add in the .csv extention automatically.  The third attribute is a temporary delimiter for the PHP code to eventually separate the data (don't worry, it will output as a true comma separated sheet regardless of the designated delimiter).  Please feel free to modify these attributes as you see fit, but I recommend that you keep the file name machine readable and the temporary delimiter a character that is not commonly used.

8.  The table_to_csv function is set to read just the text by default for both the headers and the cells.  I commented out an option to change either over HTML.

I hope you can put this tool to good use!

======================

Copyright 2013 Jeremy R Perry.  All rights reserved.


