<html>
<div>
        <h1>CPSC 304 - PC Parts Database Project</h1>
    </div>

    <div class = "button-container">
        <form action="wrapper2.php" method="get">
            <button type="submit">Insert, Update, Delete Page</button>
        </form>

        <form action="wrapper_select.php" method="get">
            <button type="submit">Select and Projection Page</button>
        </form>

        <form action="wrapper_queries.php" method="get">
            <button type="submit">Query Page</button>
        </form>
    </div>
    



    <style>
        .button-container {
            display: flex;
        }

        .button-container form {
            margin: 0;
        }
        .button-container button {
            margin-right: 20px;
            background-color:#DEF2F7;
            border-radius: 5px;
        }
    </style>
    
</html>