<?php
// The preceding tag tells the web server to parse the following text as PHP
// rather than HTML (the default)

// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.

include 'functions.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set some parameters

// Database access configuration
$config["dbuser"] = "ora_nelsonl1";			// change "cwl" to your own CWL
$config["dbpassword"] = "a32900045";		// change to 'a' + your student number
$config["dbserver"] = "dbhost.students.cs.ubc.ca:1522/stu";
$db_conn = null;	// login credentials are used in connectToDB()

connectToDB();

$success = true;	// keep track of errors so page redirects only if there are no errors

$show_debug_alert_messages = False; // show which methods are being triggered (see debugAlertMessage())

// The next tag tells the web server to stop parsing the text as PHP. Use the
// pair of tags wherever the content switches to PHP

if (isset($_POST['resetTablesRequest'])) {
    // The reset button was clicked, call the handleResetRequest function
    handleResetRequest();
	// echo "Reset/Initialized Tables!";
} elseif (isset($_POST['insertQueryRequest'])) {
	// The insert button was clicked, call the handleInsertRequest function
	handleInsertRequest();
	// echo "Inserted values into table!";
} elseif (isset($_POST['deleteQueryRequest'])) {
	// The delete button was clicked, call the handleDeleteRequest function
	handleDeleteRequest();
	// echo "Deleted value from table";
} elseif (isset($_POST['updateQueryRequest'])) {
	// The update button was clicked, call the handleUpdateRequest function
    handleUpdateRequest();
	// echo "Updated table!";
} elseif (isset($_POST['selectQueryRequest'])) {
	// The select/filter button was clicked, call the selectQueryRequest function
	handleSelectRequest();
	// echo "Filtered";
} elseif (isset($_POST['projectionQueryRequest'])) {
	// The select/filter button was clicked, call the projectionQueryRequest function
	handleProjectionRequest();
	// echo "Filtered";
} elseif (isset($_POST['countTupleRequest'])) {
	// The count button was clicked, call the handleCountRequest function
	handleCountRequest();
	echo "Counted tuples!!";
} elseif (isset($_POST['displayTuplesRequest'])) {
	// The display button was clicked, call the handleDisplayRequest function
	handleDisplayRequest();
	echo "Displaying tuples!!";
}


?>
<?php
include 'navbar.php';
?>

<html>

<head>
	<title>CPSC 304 - PC Parts Database Project : Select & Project</title>
</head>
    
	<style> 
		.select-col {
			display: inline-block;
    		vertical-align: top;
    		margin-right: 20px;
		}
	</style>

	<div class="select-container">
		<hr />
		<h2>Select a Keyboard</h2>
		<p>Select filters below to find keyboards that match what you want!</p>
		<div class="form-select">
			<form method="POST" action="wrapper_select.php">
				<div class="select-col">
					<p>Brand:</p>
					<label for="option1">
						<input type="checkbox" id="brand2" name="broptions[]" value="brand = 'Corsair'">
						Corsair
					</label><br>
					<label for="option2">
						<input type="checkbox" id="brand3" name="broptions[]" value="brand = 'Logitech'">
						Logitech
					</label><br>
					<label for="option3">
						<input type="checkbox" id="brand3" name="broptions[]" value="brand = 'Havit'">
						Havit
					</label><br>
				</div>

				<div class="select-col">
					<p>Colour:</p>
					<label for="option1">
						<input type="checkbox" id="brand2" name="coptions[]" value="colour = 'Black'">
						Black
					</label><br>
					<label for="option2">
						<input type="checkbox" id="brand3" name="coptions[]" value="colour = 'White'">
						White
					</label><br>
					<label for="option3">
						<input type="checkbox" id="brand3" name="coptions[]" value="colour = 'Blue'">
						Blue
					</label><br>
				</div>

				<div class="select-col">
					<p>Percentage:</p>
					<label for="option1">
						<input type="checkbox" id="brand2" name="pcoptions[]" value="percentage = 100">
						100%
					</label><br>
					<label for="option2">
						<input type="checkbox" id="brand3" name="pcoptions[]" value="percentage = 100">
						80%
					</label><br>
					<label for="option3">
						<input type="checkbox" id="brand3" name="pcoptions[]" value="percentage = 100">
						65%
					</label><br>
				</div>

				<div class="select-col">
					<p>Price:</p>
					<label for="option1">
						<input type="checkbox" id="brand2" name="proptions[]" value="price < 50">
						< $50
					</label><br>
					<label for="option2">
						<input type="checkbox" id="brand3" name="proptions[]" value="price < 100">
						< $100
					</label><br>
					<label for="option3">
						<input type="checkbox" id="brand3" name="proptions[]" value="price < 200">
						< $200
					</label><br>
				</div>

				<br></br>
				<!-- press button to query on filters -->
				<input type="Submit" value="Find Keyboards" name="selectSubmit"> 
			</form>
			<hr />
		</div>
	</div>

	<div>
		<div class="table-continer">
			<h2>Filtered Keyboard Table</h2>

			<?php
			// handle brands
			if (isset($_POST['broptions']) && !empty($_POST['broptions'])) {
				$selectedBrands = $_POST['broptions'];
				$brands = implode(' OR ', $selectedBrands);
				$brands = "(" . $brands . ")";
			} else {
				$brands = "brand IS NOT NULL";
			}
			// handle colours
			if (isset($_POST['coptions']) && !empty($_POST['coptions'])) {
				$selectedColours = $_POST['coptions'];
				$colours = implode(' OR ', $selectedColours);
				$colours = "(" . $colours . ")";
			} else {
				$colours = "colour IS NOT NULL";
			}
			// handle percentage
			if (isset($_POST['pcoptions']) && !empty($_POST['pcoptions'])) {
				$selectedPc = $_POST['pcoptions'];
				$pc = implode(' OR ', $selectedPc);
				$pc = "(" . $pc . ")";
			} else {
				$pc = "percentage <> 0";
			}
			// handle price
			if (isset($_POST['proptions']) && !empty($_POST['proptions'])) {
				$selectedPrice = $_POST['proptions'];
				$price = implode(' OR ', $selectedPrice);
				$price = "(" . $price . ")";
			} else {
				$price = "price <> 0";
			}
			
			$sql = "SELECT * FROM Keyboard WHERE " . $brands . " AND " . $colours . " AND " . $pc . " AND " . $price;
			// echo $sql;
			$result = executePlainSQL($sql);
			echo "<table border='5'>";
			printCPUCoolerTable($result);
			echo "</table>";

			?>
		</div>
	</div>


<div>
	<hr />
	<h1>Projection</h1>
	<p>Select a table from the dropdown, then click show columns and select the desired columns to see</p>
	<form method="POST" action="wrapper_select.php">
		<label for="tableDropdown">Select a table:</label>
		<select id="tableDropdown" name="selectedTable">

		<?php
			$tableQuery = "SELECT table_name FROM user_tables";
			$tableResult = executePlainSQL($tableQuery);
			
			if ($tableResult) {
				while ($tableRow = oci_fetch_assoc($tableResult)) {
					$tableName = $tableRow['TABLE_NAME'];
					echo '<option value="' . $tableName . '">' . $tableName . '</option>';
				}
			}
			
		?>

		</select>
		<input type="submit" value="Show Columns" name="showColumns">
	</form>
		
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$selectedTable = null;
	if (isset($_POST["selectedTable"])) {
		$selectedTable = $_POST["selectedTable"];
	}

    $columnQuery = "SELECT column_name FROM user_tab_columns WHERE table_name = '$selectedTable'";
    $columnResult = executePlainSQL($columnQuery);

    if ($columnResult) {
        echo "<h2>$selectedTable attributes:</h2>";
        echo '<form action="wrapper_select.php" method="POST">';
        
        while ($columnRow = oci_fetch_assoc($columnResult)) {
            $columnName = $columnRow['COLUMN_NAME'];
            echo '<input type="checkbox" name="selectedColumns[]" value="' . $columnName . '">';
            echo '<label for="' . $columnName . '">' . $columnName . '</label><br>';
        }
		//store selectedTable value for after submit
		echo '<input type="hidden" name="hiddenSelectedTable" value="' . $selectedTable . '">';

        echo '<input type="submit" value="Submit">';
        echo '</form>';

		if (isset($_POST["selectedColumns"]) && is_array($_POST["selectedColumns"])) {
            $selectedColumns = $_POST["selectedColumns"];
			$selectedTable = $_POST["hiddenSelectedTable"];

            $columnsString = implode(", ", $selectedColumns);

            $projectionQuery = "SELECT DISTINCT $columnsString FROM $selectedTable";
            $projectionResult = executePlainSQL($projectionQuery);

			echo "<table border='5'>";
			printCPUCoolerTable($projectionResult);
			echo "</table>";
        } else if ($selectedTable) {
			$table = "SELECT * FROM $selectedTable";
			$tableResult = executePlainSQL($table);

			echo "<table border='5'>";
			printCPUCoolerTable($tableResult);
			echo "</table>";
            // echo "<p>No columns selected yet</p>";
        } else if (!isset($_POST["selectedColumns"])) {
			echo "Did not select columns";
		}
    } else {
        echo "<p>Error getting columns for table $selectedTable</p>";
    }
}

oci_close($db_conn);

		?>
</html>