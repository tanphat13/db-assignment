
<?php
    $servername = "localhost";
    $username = "root";
    $password = "abcdef1234";
  
    $Newdbname = $_POST['dbname'];
    $Newtbname = $_POST['tbname'];
    
    
    if ( $Newdbname && $Newtbname) {
        // Create connection
        $conn = new mysqli($servername, $username, $password);
        // Check connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
        // $test = "CREATE DATABASE $Newdbname";
        // $result = $conn->query($test);
        // $conn->select_db($Newdbname);
        // $conn->query("CREATE TABLE $Newtbname(ID int);");
        $newdb = "CREATE DATABASE $Newdbname";
        $result1 = $conn->query($newdb);
        $conn->select_db($Newdbname);
        $result2 = $conn->query("CREATE TABLE $Newtbname");
        if ($result2 == false) {
            echo "Database name or Table name is invalid. Try again!!!!!!!!!<br>";
        }
        else {
            echo "New Database is created!!!!!!!!<br>";
        }
    }
?>
<input type="button" value="Click Here to return" onclick="window.location.href='index.php?page=music_DB'" />