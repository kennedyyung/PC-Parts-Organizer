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
	<title>CPSC 304 - PC Parts Database Project : Queries</title>
</head>

	<!-- join -->
	<div class="join">
		<hr />
		<div class="table-continer">
			<h2> Find a Keyboard/Mouse Pair</h2>
			<div>
				<p>Choose a colour/brand to match (e.g. "I want only Corsair keyboards/mouses"):</p>
				<form method="POST" action="wrapper_queries.php">
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
		<form method="POST" action="wrapper_queries.php">
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
	<form method="POST" action="wrapper_queries.php">
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
	<form method="POST" action="wrapper_queries.php">
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




</body>

</html>