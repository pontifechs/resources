<?php
	/*
	**************************************************************************************************************************
	** CORAL Resources Module v. 1.2
	** Copyright (c) 2010 University of Notre Dame
	** This file is part of CORAL.
	** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
	** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
	** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
	**************************************************************************************************************************
	*/
	session_start();
	// CSV configuration
	$required_columns = array('fundCode' => 0, 'shortName' => 0);
	if ($_POST['submit']) {
		include_once 'directory.php';
		$pageTitle='Funds import';
		include 'templates/header.php';
		$uploaddir = 'attachments/';
		$uploadfile = $uploaddir . basename($_FILES['uploadFile']['name']);
		if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadfile)) {
			print '<p>The file has been successfully uploaded.</p>';

			// Let's analyze this file
			if (($handle = fopen($uploadfile, "r")) !== FALSE) {
				if (($data = fgetcsv($handle)) !== FALSE) {
					$columns_ok = true;
					foreach ($data as $key => $value) {
						$available_columns[$value] = $key;
					}
				} else {
					$error = 'Unable to get columns headers from the file';
				}
			} else {
				$error = 'Unable to open the uploaded file';
			}
		} else {
			$error = 'Unable to upload the file';
		}
		if ($error) {
			print "<p>Error: $error.</p>";
		} else {
			print "<p>Please choose columns from your CSV file:</p>";
			print "<form action=\"importFunds.php\" method=\"post\">";
			foreach ($required_columns as $rkey => $rvalue) {
				print "<label for=\"$rkey\">" . $rkey . "</label><select name=\"$rkey\">";
				print '<option value=""></option>';
				foreach ($available_columns as $akey => $avalue) {
					print "<option value=\"$avalue\"";
					if ($rkey == $akey) print ' selected="selected"';
					print ">$akey</option>";
				}
				print '</select><br />';
			}
			print "<input type=\"hidden\" name=\"delimiter\" value=\"','\" />";
			print "<input type=\"hidden\" name=\"uploadfile\" value=\"$uploadfile\" />";
			print "<input type=\"submit\" name=\"matchsubmit\" id=\"matchsubmit\" /></form>";
		}
	// Process
	} elseif ($_POST['matchsubmit']) {
		include_once 'directory.php';
		$pageTitle='Funds import';
		include 'templates/header.php';
		$uploadfile = $_POST['uploadfile'];
		// Let's analyze this file
		if (($handle = fopen($uploadfile, "r")) !== FALSE) {
			$row = 0;
			$inserted = 0;
			while (($data = fgetcsv($handle)) !== FALSE) {
				if ($row == 0){

				}else{
					$Fund = new Fund();
					$funds = $Fund -> allAsArray();
					// Convert to UTF-8
					$data = array_map(function($row) { return mb_convert_encoding($row, 'UTF-8'); }, $data);
					$Fund->fundCode = array_values($data)[$_POST['fundCode']];
					$Fund->shortName = array_values($data)[$_POST['shortName']];
					$Fund->save();
					$inserted++;
				}
				$row++;
			}
			print "<h2>Results</h2>";
			print "<p>" . ($row - 1) . " rows have been processed. $inserted rows have been inserted.</p>";
		}
	} else {
		?>
			<form enctype="multipart/form-data" action="importFunds.php" method="post" id="importForm">
				<div id='div_updateForm'>
					<div class='formTitle' style='width:245px;'><b>Import Funds</b></div>
					<label for="uploadFile">Select File</label>
					<input type="file" name="uploadFile" id="uploadFile"/><br/><br/>
					<input type="submit" name="submit" value="Import" />
					<input type='button' value='Cancel' onclick="window.parent.tb_remove(); return false;"/>
				</div>
			</form>
		<?php
	}
?>
