<!-- Test Oracle file for UBC CPSC304
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  Modified by Jason Hall (23-09-20)
  This file shows the very basics of how to execute PHP commands on Oracle.
  Specifically, it will drop a table, create a table, insert values update
  values, and then query for values
  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up All OCI commands are
  commands to the Oracle libraries. To get the file to work, you must place it
  somewhere where your Apache server can run it, and you must rename it to have
  a ".php" extension. You must also change the username and password on the
  oci_connect below to be your ORACLE username and password
-->

<?php

include "./functions.php";
?>


<!-- <?php
// The preceding tag tells the web server to parse the following text as PHP
// rather than HTML (the default)

// The following 3 lines allow PHP errors to be displayed along with the page
// content. Delete or comment out this block when it's no longer needed.
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


?> -->

<html>

<head>
	<title>CPSC 304 - PC Parts Database Project</title>
</head>

<body>
	<h2>Reset</h2>
	<p>To reset the tables to the original values, please click the "Reset" button below. If this is the first time you're running this page, please click "Reset" to initialize the tables</p>

	<form method="POST" action="wrapper.php">
		<!-- "action" specifies the file or page that will receive the form data for processing. As with this example, it can be this same file. -->
		<input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
		<p><input type="submit" value="Reset" name="reset"></p>
	</form>

	<style>
		.form-block {
			display: flex;
			justify-content: space-between;
			height: 500px;
		}
		.form-section {
			display: inline-block;
			width: 30%;
			/* height: 300px;  */
			margin-right: 2%;
		}
	</style>

	<div class="form-container">
		<div class="form-section">
			<hr />
			<h2>Insert Values into CPU Cooler Table</h2>
			<p>This will insert a new row into the currect CPU Cooler Table. (*) fields are required.</p>
			<p>NOTE: CPUCooler_Size input must be an integer and Price input must be a number!</p>
			<form method="POST" action="wrapper.php">
				<input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
				Model (*): <input type="text" name="insModel"> <br /><br />
				CPUCooler_Size (*): <input type="text" name="insCoolerSize"> <br /><br />
				Price : <input type="text" name="insPrice"> <br /><br />
				CPU_Model : <input type="text" name="insCPUModel"> <br /><br />

				<input type="submit" value="Insert" name="insertSubmit"></p>
			</form>
			<hr />
		</div>

		<div class="form-section">
			<hr />
			<h2>Delete Row in CPU Table</h2>
			<p>This deletes a row in the CPU Table. Specify the row by stating its Model.</p>
			<p>WARNING: Deleting a row here might delete a row in the CPU Cooler table!</p>
			<form method="POST" action="wrapper.php">
				<input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
				Model : <input type="text" name="delModel"> <br /><br />
				<!-- CPUCooler_Size : <input type="text" name="delCoolerSize"> <br /><br /> -->

				<input type="submit" value="Delete" name="deleteSubmit"></p>
			</form>
			<hr />
		</div>

		<div class="form-section">
			<hr />
			<h2>Update Name in CPU Cooler Table</h2>
			<p>This will change all the values that are currently the old value to the new value in the table. The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>
			<form method="POST" action="wrapper.php">
				<input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
				Old Price: <input type="text" name="oldPrice"> <br /><br />
				New Price: <input type="text" name="newPrice"> <br /><br />
				Old CPU Model: <input type="text" name="oldCPU"> <br /><br />
				New CPU Model: <input type="text" name="newCPU"> <br /><br />

				<input type="submit" value="Update" name="updateSubmit"></p>
			</form>
			<hr />
		</div>
	</div>

	<style>
        .table-container {
            display: inline-block;
            margin-right: 20px;
        }
    </style>

	<div>
		<div class="table-continer">
			<h2>CPU Cooler Table</h2>

			<?php
			$sql = "SELECT * FROM CPUCooler_On";
			$result = executePlainSQL($sql);
			echo "<table border='5'>";
			printCPUCoolerTable($result);
			echo "</table>";
			?>
		</div>

		<div class="table-continer">
			<h2>CPU Table</h2>

			<?php
			$sql = "SELECT * FROM CPU_On";
			$result = executePlainSQL($sql);
			echo "<table border='5'>";
			printCPUCoolerTable($result);
			echo "</table>";
			?>
		</div>
    </div>

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
			<form method="POST" action="wrapper.php">
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
	<form method="POST" action="wrapper.php">
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
        echo '<form action="wrapper.php" method="POST">';
        
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

	<!-- join -->
	<div class="join">
		<hr />
		<div class="table-continer">
			<h2> Find a Keyboard/Mouse Pair</h2>
			<div>
				<p>Choose a colour/brand to match (e.g. "I want only Corsair keyboards/mouses"):</p>
				<form method="POST" action="wrapper.php">
					<input type="hidden" id="joinQueryRequest" name="joinQueryRequest">
					Brand: <input type="text" name="joinBrand"> <br /><br />
					Colour: <input type="text" name="joinColour"> <br /><br />

				<input type="submit" value="Find" name="joinSubmit"></p>
				</form>
			</div>
		</div>
	</div>

	<div>
		<div class="table-continer">
			<h2>Keyboards & Mouses Table</h2>

			<?php
			// join on both
			if (!empty($_POST['joinBrand']) && !empty($_POST['joinColour'])) {
				$joinOn = "Keyboard k JOIN Mouse m ON k.brand = m.brand AND k.colour = m.colour"; 
				$tuple = array(
					":bind1" => $_POST['joinBrand'],
					":bind2" => $_POST['joinColour']
				);
				$alltuples = array(
					$tuple
				);
				$condition = "k.brand = :bind1 AND k.colour = :bind2";
			}
			// only join on brand
			else if (!empty($_POST['joinBrand']) && empty($_POST['joinColour'])) {
				$joinOn = "Keyboard k JOIN Mouse m ON k.brand = m.brand"; 
				$tuple = array(
					":bind1" => $_POST['joinBrand']
				);
				$alltuples = array(
					$tuple
				);
				$condition = "k.brand = :bind1";
			} 
			// only join on colour
			else if (empty($_POST['joinBrand']) && !empty($_POST['joinColour'])) {
				$joinOn = "Keyboard k JOIN Mouse m ON k.colour = m.colour"; 
				$tuple = array(
					":bind2" => $_POST['joinColour']
				);
				$alltuples = array(
					$tuple
				);
				$condition = "k.colour = :bind2";
			} 
			// both empty
			else {
				$joinOn = "Keyboard k JOIN Mouse m ON k.brand = m.brand AND k.colour = m.colour"; 
				$tuple = array();
				$alltuples = array(
					$tuple
				);
				$condition = "k.model IS NOT NULL";
			}			

			$cols = "k.model AS Keyboard_model, k.brand AS Keyboard_brand, k.colour AS Keyboard_colour, k.price AS Keyboard_price,
						m.model AS Mouse_model, m.brand AS Mouse_brand, m.colour AS Mouse_colour, m.price AS Mouse_price, k.price + m.price AS Total_price";
			$sql = "SELECT $cols FROM $joinOn WHERE $condition ORDER BY Total_price ASC";
			$result = executeBoundSQL($sql, $alltuples);
			echo "<table border='5'>";
			printCPUCoolerTable($result);
			echo "</table>";
			?>
		</div>
	</div>
	<hr>

	<div>
		<hr/>
	<h1>Aggregation with Group By</h1>
	<p>Find the maximum, minimum, or average price of GPU(s) based on brand, number of fans or both!</p>

	<div>
		<form method="POST" action="wrapper.php">
    		<input type="checkbox" name="groupBrand" value="brand"> Brand
    		<input type="checkbox" name="groupFans" value="fans"> Number of Fans <br><br>

    		<label for="operation">Select an operation:</label>
    		<select name="operation" id="operation">
        		<option value="MAX">Max</option>
        		<option value="MIN">Min</option>
        		<option value="AVG">Avg</option>
    		</select>

    		<input type="submit" value="Submit">
		</form>
	</div>

	<h2>GPU Table</h2>

	<?php
		$priceTableSql = "SELECT * FROM GPU_Has_Price";
		$fullTableResult = executePlainSQL($priceTableSql);
		echo "<table border='5'>";
			printCPUCoolerTable($fullTableResult);
		echo "</table>";

		if (isset($_POST['operation']) && (isset($_POST['groupBrand']) || isset($_POST['groupFans']))) {
			$selected = [];

			if (isset($_POST['groupBrand'])) {
				$selected[] = 'brand';
			}
			if (isset($_POST['groupFans'])) {
				$selected[] = 'fans';
			}

			$operation = $_POST["operation"];


			$groupedBy = implode(', ', $selected);

			$groupBySql = "SELECT $groupedBy ". ", " . $operation . "(price)" . " FROM GPU_Has_Price GROUP BY $groupedBy" ;
			$groupedByResult = executePlainSQL($groupBySql);

			echo "<h2>Result Table</h2>";
			echo "<table border='5'>";
			printCPUCoolerTable($groupedByResult);
			echo "</table>";
		}
	?>


<hr/>
</div>

<div>
	<h2>Query with Having</h2>
	<p>The following query will group by the case fans colour and print out a table where the colour has an average price less than your given input.</p>
	<form method="POST" action="wrapper.php">
		<input type="hidden" id="havingQueryRequest" name="havingQueryRequest">
		Price Lower than: <input type="text" name="havingPrice"> <br /><br />

		<input type="submit" value="Submit" name="havingQuerySubmit"></p>
	</form>

	<h2>CaseFan Table</h2>

	<?php
	$sql = "SELECT Model, CaseFan_Size, Price, Colour FROM CaseFan_Inside";
	$result = executePlainSQL($sql);
	echo "<table border='5'>";
	printCPUCoolerTable($result);
	echo "</table>";

	if (isset($_POST['havingQueryRequest']) && isset($_POST['havingPrice'])) {
		$tuple = array(
			":bind1" => $_POST['havingPrice']
		);
		$alltuples = array(
			$tuple
		);
		echo "<h2>Having Result Table</h2>";
		$havingResult = executeBoundSQL("SELECT Colour, AVG(Price) FROM CaseFan_Inside GROUP BY Colour HAVING AVG(Price) < :bind1", $alltuples);
		echo "<table border='5'>";
		printCPUCoolerTable($havingResult);
		echo "</table>";
	}
	?>
	<hr/>
</div>

<div>
	<h2>Nested Query</h2>
	<p>The following query will group the keyboards by your choosen input(s) and print out a table where the group has an average price less than the average price of all the keyboards.</p>
	<form method="POST" action="wrapper.php">
		<input type="hidden" id="nestedQueryRequest" name="nestedQueryRequest">
		<input type="checkbox" name="nestedGroupBrand" value="brand"> Brand
		<input type="checkbox" name="nestedGroupColour" value="colour"> Colour


		<input type="submit" value="Submit" name="nestedQuerySubmit"></p>
	</form>

	<h2>Keyboard Table</h2>

	<?php
	$sql = "SELECT * FROM Keyboard";
	$result = executePlainSQL($sql);
	echo "<table border='5'>";
	printCPUCoolerTable($result);
	echo "</table>";

	if (isset($_POST['nestedQueryRequest']) && (isset($_POST['nestedGroupBrand']) || isset($_POST['nestedGroupColour']))) {
		$nestedSelection = [];

		if (isset($_POST['nestedGroupBrand'])) {
			$nestedSelection[] = 'Brand';
		}
		if (isset($_POST['nestedGroupColour'])) {
			$nestedSelection[] = 'Colour';
		}

		$nestedGroupBy = implode(', ', $nestedSelection);

		echo "<h2>Nested Query Result Table</h2>";
		$nestedSql = "SELECT ".$nestedGroupBy .", AVG(Price) FROM Keyboard GROUP BY $nestedGroupBy HAVING AVG(Price) < (SELECT AVG(Price) FROM Keyboard)";
		$nestedResult = executePlainSQL($nestedSql);
		echo "<table border='5'>";
		printCPUCoolerTable($nestedResult);
		echo "</table>";
	}
	?>
	<hr/>
</div>

		</body>
	</html>
</div>

<!-- 

<div>
	<h1>Aggregation with Group By</h1>
	<p>Price of a GPU</p>

	<div>
		<form method="post" action="">
    		<input type="checkbox" name="groupBrand" value="brand"> Brand
    		<input type="checkbox" name="groupFans" value="fans"> Number of Fans

    		<label for="operation">Select an operation:</label>
    		<select name="operation" id="operation">
        		<option value="MAX">Max</option>
        		<option value="MIN">Min</option>
        		<option value="AVG">Avg</option>
    		</select>

    		<input type="submit" value="Submit">
		</form>
	</div>


	<?php
	echo"<h2>GPU_Has_Price</h2>";
	$priceTableSql = "SELECT * FROM GPU_Has_Price";
	$fullTableResult = executePlainSQL($priceTableSql);
	echo "<table border='5'>";
		printCPUCoolerTable($fullTableResult);
	echo "</table>";

	$selected = [];

	if (isset($_POST['groupBrand'])) {
		$selected[] = 'brand';
	}
	if (isset($_POST['groupFans'])) {
		$selected[] = 'fans';
	}

	$operation = $_POST["operation"];


	$groupedBy = implode(', ', $selected);

	$groupBySql = "SELECT $groupedBy ". ", " . $operation . "(price)" . " FROM GPU_Has_Price GROUP BY $groupedBy" ;
	$groupedByResult = executePlainSQL($groupBySql);

	echo "<table border='5'>";
	printCPUCoolerTable($groupedByResult);
echo "</table>";

	echo "$groupBySql";
	?>

</div> -->


	<!-- Division  -->
    <div class=table-continer>
        <h2>Find Cases That Come With All Mouses</h2>
        <form method="post">
            <button type="submit" name="divButton">Find Cases</button>
        </form>

        <h2>Cases Table</h2>
        <?php
            if (isset($_POST['divButton'])) {
                $sql = "SELECT * FROM Case_Contains CC WHERE NOT EXISTS
                            (SELECT K.Model FROM Keyboard K WHERE NOT EXISTS 
                                (SELECT C.Case_Model, C.Case_Colour, C.Case_Size FROM Connected_To C 
                                    WHERE C.Keyboard_Model = K.Model 
                                    AND CC.Model = C.Case_Model
                                    AND CC.Colour = C.Case_Colour
                                    AND CC.Case_Size = C.Case_Size))";
            } else {
                $sql = "SELECT * FROM Connected_To";
            }
            $result = executePlainSQL($sql);
            echo "<table border='5'>";
            printCPUCoolerTable($result);
            echo "</table>";
        ?>
    </div>



	<!-- <?php
	// The following code will be parsed as PHP

	function debugAlertMessage($message)
	{
		global $show_debug_alert_messages;

		if ($show_debug_alert_messages) {
			echo "<script type='text/javascript'>alert('" . $message . "');</script>";
		}
	}

	function executePlainSQL($cmdstr)
	{ //takes a plain (no bound variables) SQL command and executes it
		//echo "<br>running ".$cmdstr."<br>";
		global $db_conn, $success;

		$statement = oci_parse($db_conn, $cmdstr);
		//There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

		if (!$statement) {
			echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($db_conn); // For oci_parse errors pass the connection handle
			echo htmlentities($e['message']);
			$success = False;
		}

		$r = oci_execute($statement, OCI_DEFAULT);
		if (!$r) {
			echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
			$e = oci_error($statement); // For oci_execute errors pass the statementhandle
			echo htmlentities($e['message']);
			$success = False;
		}

		return $statement;
	}

	function executeBoundSQL($cmdstr, $list)
	{
		/* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

		global $db_conn, $success;
		$statement = oci_parse($db_conn, $cmdstr);

		if (!$statement) {
			echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
			$e = OCI_Error($db_conn);
			echo htmlentities($e['message']);
			$success = False;
		}

		foreach ($list as $tuple) {
			foreach ($tuple as $bind => $val) {
				//echo $val;
				//echo "<br>".$bind."<br>";
				oci_bind_by_name($statement, $bind, $val);
				unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
			}

			$r = oci_execute($statement, OCI_DEFAULT);
			if (!$r) {
				echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
				$e = OCI_Error($statement); // For oci_execute errors, pass the statementhandle
				echo htmlentities($e['message']);
				echo "<br>";
				$success = False;
			}
		}

		return $statement;
	}

	function printCPUCoolerTable($result)
	{
		echo "<tr>";
		for ($i = 1; $i <= oci_num_fields($result); $i++) {
			$col_name = oci_field_name($result, $i);
			echo "<th>$col_name</th>";
		}
		echo "</tr>";

		while ($row = oci_fetch_assoc($result)) {
			echo "<tr>";
			foreach ($row as $column => $value) {
				echo "<td>$value</td>";
			}
			echo "</tr>";
		}
	}

	function connectToDB()
	{
		global $db_conn;
		global $config;

		// Your username is ora_(CWL_ID) and the password is a(student number). For example,
		// ora_platypus is the username and a12345678 is the password.
		// $db_conn = oci_connect("ora_cwl", "a12345678", "dbhost.students.cs.ubc.ca:1522/stu");
		$db_conn = oci_connect($config["dbuser"], $config["dbpassword"], $config["dbserver"]);

		if ($db_conn) {
			debugAlertMessage("Database is Connected");
			return true;
		} else {
			debugAlertMessage("Cannot connect to Database");
			$e = OCI_Error(); // For oci_connect errors pass no handle
			echo htmlentities($e['message']);
			return false;
		}
	}

	function disconnectFromDB()
	{
		global $db_conn;

		debugAlertMessage("Disconnect from Database");
		oci_close($db_conn);
	}

	function handleUpdateRequest()
	{
		global $db_conn;

		$old_price = $_POST['oldPrice'];
		$new_price = $_POST['newPrice'];
		$old_cpu = $_POST['oldCPU'];
		$new_cpu = $_POST['newCPU'];

		if ($new_cpu && CPUDoesNotContain($new_cpu)) {
			insertIntoCPUTable($new_cpu);
		}

		$tuple1 = array(
			":bind1" => $old_price,
			":bind2" => $new_price
		);

		$alltuples1 = array(
			$tuple1
		);

		$tuple2 = array(
			":bind3" => $old_cpu,
			":bind4" => $new_cpu
		);

		$alltuples2 = array(
			$tuple2
		);

		// you need the wrap the old name and new name values with single quotations
		executeBoundSQL("UPDATE CPUCooler_On SET price = :bind2 WHERE price = :bind1", $alltuples1);
		executeBoundSQL("UPDATE CPUCooler_On SET cpu_model = :bind4 WHERE cpu_model = :bind3", $alltuples2);
		oci_commit($db_conn);
	}

	// returns true is new_cpu is not a key in CPU table, false otherwise
	function CPUDoesNotContain($new_cpu)
	{
		global $db_conn;

		$sql = "SELECT COUNT(*) FROM CPU_On WHERE Model = :bind1";

        $sqlStatement = oci_parse($db_conn, $sql);
        oci_bind_by_name($sqlStatement, ":bind1", $new_cpu);
        oci_execute($sqlStatement, OCI_DEFAULT);

        $count = oci_fetch_row($sqlStatement)[0];

		return $count == 0; // true if primary key does not exist
	}

	function handleResetRequest()
	{
		global $db_conn;

		// Create new table
		echo "<br> Reseting / Initializing tables for PC parts <br>";
		// executePlainSQL("start pc_project.sql");

		$scriptContents = file_get_contents("../cs304/project_a0b7q_a1s7u_s8i3z/pc_project.sql");
		$commands = explode(";", $scriptContents);

		foreach ($commands as $command) {
			$command = trim($command);
			$statement = oci_parse($db_conn, $command);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $command . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = false;
            } else {
                $r = oci_execute($statement, OCI_DEFAULT);
                if (!$r && oci_error($statement)['code'] != 942) {
					// Ignore error code 942, which indicates the table does not exist
                    echo "<br>Cannot execute the following command: " . $command . "<br>";
                    $e = oci_error($statement);
                    echo htmlentities($e['message']);
                    $success = false;
                }
            }
        }
		oci_commit($db_conn);
	}

	function handleInsertRequest()
	{
		global $db_conn;

		// Add tuple into CPU Model table first
		if ($_POST['insCPUModel']) {
			$CPUModel = $_POST['insCPUModel'];
			insertIntoCPUTable($CPUModel);
		}

		// Getting the values from user and insert data into the table
		$tuple = array(
			":bind1" => $_POST['insModel'],
			":bind2" => $_POST['insCoolerSize'],
			":bind3" => $_POST['insPrice'],
			":bind4" => $_POST['insCPUModel']
		);

		$alltuples = array(
			$tuple
		);

		executeBoundSQL("insert into CPUCooler_On (model, cpucooler_size, price, cpu_model) values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
		oci_commit($db_conn);
	}

	function insertIntoCPUTable($CPUModel)
	{
		global $db_conn, $success;

		$tuple = array(
			":bind1" => $CPUModel
		);

		$alltuples = array(
			$tuple
		);

		$sql = "INSERT INTO CPU_On (Model) VALUES (:bind1)";
		executeBoundSQL($sql, $alltuples);

        if ($success) {
            oci_commit($db_conn);
            echo "Model successfully added into CPU table.";
        } else {
            echo "Error adding model to CPU.";
        }
	}

	function handleDeleteRequest()
	{
		global $db_conn;

		//Getting the values from user and delete data from table
		$tuple = array(
			":bind1" => $_POST['delModel'],
		);

		$alltuples = array(
			$tuple
		);

		// executeBoundSQL("delete from CPUCooler_On WHERE model = :bind1 AND cpucooler_size = :bind2", $alltuples);
		executeBoundSQL("delete from CPU_On WHERE model = :bind1", $alltuples);
		oci_commit($db_conn);
	}

	function handleDisplayRequest()
	{
		global $db_conn;
		$result = executePlainSQL("SELECT * FROM demoTable");
		printResult($result);
	}

	// HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
	function handlePOSTRequest()
	{
		if (connectToDB()) {
			if (array_key_exists('resetTablesRequest', $_POST)) {
				handleResetRequest();
			} else if (array_key_exists('updateQueryRequest', $_POST)) {
				handleUpdateRequest();
			} else if (array_key_exists('insertQueryRequest', $_POST)) {
				handleInsertRequest();
			} else if (array_key_exists('deleteQueryRequest', $_POST)) {
				handleDeleteRequest();
			} else if (array_key_exists('selectQueryRequest', $_POST)) {
				handleSelectRequest();
			}

			disconnectFromDB();
		}
	}

	// HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
	function handleGETRequest()
	{
		if (connectToDB()) {
			if (array_key_exists('countTuples', $_GET)) {
				handleCountRequest();
			} elseif (array_key_exists('displayTuples', $_GET)) {
				handleDisplayRequest();
			}

			disconnectFromDB();
		}
	}

	if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['selectSubmit'])) {
		handlePOSTRequest();
	} else if (isset($_GET['countTupleRequest']) || isset($_GET['displayTuplesRequest'])) {
		handleGETRequest();
	}

	// End PHP parsing and send the rest of the HTML content
	?> -->
</body>

</html>
