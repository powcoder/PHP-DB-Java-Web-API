<!DOCTYPE html>
<html>
<body>
<?php
// Demonstration file to show how to:
// - connect to a database
// - create a table
// - inject data
// - display all data in a given table
// - cleanup and delete the test table

// MySQL server connection URI (Don't change this)
$servername = "localhost";

// Please set these to you supplied credential set
// CAUTION: This is NOT your University login information!
$username = "yourLogin";
$password = "yourPassword";

// Please set this to your module code, e.g. ce154_username, ce29x_username, etc
$database = "your_database";

// First let's connect to a database and create a demo table
try
{
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully to $database<br/>";
	
	// Let's create a new table, Use native SQL to drive the database
	$sql = "
	CREATE TABLE Demonstration (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50)
	)";
	
	// Execute the SQL query
	$conn->exec($sql);
	
	// If something went wrong an exception will be thrown, otherwise the script will continue
    echo "Demonstration Table created <br/>";
}
catch(PDOException $e)
{
	echo "<b>SQL Error: </b><br/>" . $e->getMessage() . "<br/>";;
}
// Clear connection to the database (even if it didn't connect)
$conn = null;

// Now we have an empty table in our database, let's put some data in there
try
{
	// Connect
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	// Using pure SQL to insert data
	$sql = "INSERT INTO Demonstration (firstname, lastname, email)
    VALUES ('John', 'Smith', 'john@example.com')";
	
	// Execute the SQL query
	$conn->exec($sql);
	
	// If something went wrong an exception will be thrown, otherwise the script will continue
    echo "Demonstration data inserted <br/>";
}
catch(PDOException $e)
{
	echo "<b>SQL Error: </b><br/>" . $e->getMessage() . "<br/>";
}
// Clear connection to the database (even if it didn't connect)
$conn = null;

// With data in the table, show it on the web page
// First echo the start of the HTML table tags
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Id</th><th>Firstname</th><th>Lastname</th></tr>";

// Next create a PHP class that defines table and allows SQL to recursively print a table
class TableRows extends RecursiveIteratorIterator 
{
    function __construct($it) {
        parent::__construct($it, self::LEAVES_ONLY);
    }

    function current() {
        return "<td style='width: 150px; border: 1px solid black;'>" . parent::current(). "</td>";
    }

    function beginChildren() {
        echo "<tr>";
    }

    function endChildren() {
        echo "</tr>" . "\n";
    }
}

// Now we can pull the data from the database
try
{
	// Connect
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	// Using a prepared SQL statement
	$stmt = $conn->prepare("SELECT id, firstname, lastname FROM Demonstration");
    $stmt->execute();

	// Set the resulting array to associative
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
	
	// Now 'print' the table contents
	foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) 
	{
        echo $v;
    }
}
catch(PDOException $e)
{
	echo "<b>SQL Error: </b><br/>" . $e->getMessage() . "<br/>";
}

// Close the HTML Table tags
echo "</table>";

// Clear connection to the database (even if it didn't connect)
$conn = null;

// That's the demo done, lets delete that database and tidy up
try
{
	// Connect
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	// Using pure SQL to insert data
	$sql = "DROP TABLE Demonstration";
	
	// Execute the SQL query
	$conn->exec($sql);
	
	// If something went wrong an exception will be thrown, otherwise the script will continue
    echo "Demonstration complete";
}
catch(PDOException $e)
{
	echo "<b>SQL Error: </b><br/>" . $e->getMessage() . "<br/>";
}
// Clear connection to the database (even if it didn't connect)
$conn = null;
?>

</body>
</html>