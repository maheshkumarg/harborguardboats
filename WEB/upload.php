<?php
date_default_timezone_set('America/Los_Angeles');

include_once ("config.php");

//include the following 2 files
require 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';
$response = "File was not uploaded. Try again!!";

if (is_uploaded_file($_FILES['file']['tmp_name'])) {
	$filename = $_FILES["file"]["tmp_name"];
	$srow = 2;
	$response = "Something went wrong. Please try again!!";
	$objPHPExcel = PHPExcel_IOFactory::load($filename);
	foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
		$highestRow = $worksheet -> getHighestRow();
		if ($highestRow > 1) {
			$response = "";
		}
		$highestColumn = $worksheet -> getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$nrColumns = ord($highestColumn) - 64;
		for ($row = $srow; $row <= $highestRow; ++$row) {
			$val = array();
			for ($col = 0; $col < $highestColumnIndex; ++$col) {
				$cell = $worksheet -> getCellByColumnAndRow($col, $row);
				$val[] = $cell -> getValue();
			}

			if ($val[0] != null) {
				$productName = mysqli_real_escape_string($db, $val[0]);
				$result = mysqli_query($db, "select id FROM products WHERE name='$productName'");
				if (mysqli_num_rows($result) > 0) {
					$rows = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$productId = $rows['id'];
					$name = mysqli_real_escape_string($db, $val[1]);

					$result = mysqli_query($db, "select count(1) FROM materials WHERE productId='$productId' AND name='$name'");
					if (mysqli_num_rows($result) < 1) {
						$actTotPrice = round($val[5] * $val[6], 2);
						$stdTotPrice = round($val[7] * $val[8], 2);

						$description = mysqli_real_escape_string($db, $val[2]);
						$partNumber = mysqli_real_escape_string($db, $val[3]);
						$barcode = mysqli_real_escape_string($db, $val[4]);
						$vendorName = mysqli_real_escape_string($db, $val[9]);
						$vendorPartNumber = mysqli_real_escape_string($db, $val[10]);

						$result = mysqli_query($db, "INSERT INTO materials(`productId`,`name`,`description`,`partNumber`,`barcode`,`actualQty`,`actualUnitPrice`,`actualTotalPrice`,`stdQty`,`stdUnitPrice`,`stdTotalPrice`,`vendorName`,`vendorPartNumber`,`createdBy`) 
						VALUES ('$productId','$name','$description','$partNumber','$barcode','$val[5]','$val[6]','$actTotPrice','$val[7]','$val[8]','$stdTotPrice','$vendorName','$vendorPartNumber','$createdBy')");

						if (mysqli_affected_rows($db) == 1) {
							$response .= "Row " . $row . " added successfully <br/>";
						} else {
							$response .= "Row " . $row . " not added. Reason: unknown <br/>";
						}
					} else {
						$response .= "Row " . $row . " not added. Reason: Material name already exists for given Boat Id <br/>";
					}
				} else {
					$response .= "Row " . $row . " not added. Reason: Boat Name not found!! <br />";
				}
			}
		}
	}
	echo $response;
	mysqli_close($db);
}
?>