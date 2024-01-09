<?php
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
	?>


