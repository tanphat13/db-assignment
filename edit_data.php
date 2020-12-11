<?php
    $servername = "localhost";
    $username = "manager";
    $password = "manager";
    $dbname = "hospital";
    $ID = $_POST['ID'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $color = $_POST['color'];
    
    if (isset($_POST['ID']) && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['color'])) {
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        $conn->connect($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "UPDATE products SET product_price=$price, product_name='$name', product_color='$color' 
        WHERE product_id=$ID"; 
        $result = $conn->query($sql);
        if ($result == true) echo "Edit item successful <br>";
        
    }
?>
<input type="button" value="Click Here to return" onclick="window.location.href='index.php?page=home'" />
