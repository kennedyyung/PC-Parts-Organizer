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
	<title>CPSC 304 - PC Parts Database Project : Insert, Update, Delete</title>
</head>
    <!-- <div>
        <h1>CPSC 304 - PC Parts Database Project</h1>
    </div>

    <div>
        <form action="wrapper_select.php" method="get">
            <button type="submit">Select and Projection Page</button>
        </form>
    </div> -->

    <div>
	<h2>Reset</h2>
	<p>To reset the tables to the original values, please click the "Reset" button below. If this is the first time you're running this page, please click "Reset" to initialize the tables</p>

	<form method="POST" action="wrapper2.php">
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
			<form method="POST" action="wrapper2.php">
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
			<form method="POST" action="wrapper2.php">
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
			<form method="POST" action="wrapper2.php">
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
    </div>
</html>