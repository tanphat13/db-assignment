<?php
    $servername = "localhost";
    $username = "manager";
    $password = "manager";
    $dbname = "hospital";
    $Name = $_POST['name'];
    $price = $_POST['price'];
    $color = $_POST['color'];
    
    if ( isset($_POST['name']) && isset($_POST['price']) && isset($_POST['color'])) {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        $conn->connect($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "INSERT INTO products (product_name, product_price, product_color)
        VALUES ('$Name', '$price', '$color')"; //We have AUTO_INCREMENT of the ID in the Database so we do not need insert value for ID//
        $result = $conn->query($sql);
        if ($result == true) echo "New item added <br>";
        
    }
?>
<input type="button" value="Click Here to return" onclick="window.location.href='index.php?page=home'" />
