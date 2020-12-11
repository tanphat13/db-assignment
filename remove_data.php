<?php
    $servername = "localhost";
    $username = "manager";
    $password = "manager";
    $dbname = "hospital";
    $ID = $_POST['ID'];
    
    
    if (isset($_POST['ID']) ) {
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        $conn->connect($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "DELETE FROM products WHERE product_id=$ID"; 
        $result = $conn->query($sql);
        if ($result == true) echo "Edit item successful <br>";
        
    }
?>
<input type="button" value="Click Here to return" onclick="window.location.href='index.php?page=home'" />
