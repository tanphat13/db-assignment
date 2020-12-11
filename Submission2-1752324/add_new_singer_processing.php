<?php
    $servername = "localhost";
    $username = "manager";
    $password = "manager";
    $dbname = "hospital";
    $Name = $_POST['Name'];
    $Year_of_birth = $_POST['Year_of_birth'];
    $Gender = $_POST['Gender'];
    
    if ( isset($_POST['Name']) && isset($_POST['Year_of_birth']) && isset($_POST['Gender'])) {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        $conn->connect($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "INSERT INTO singer (singer_name, Year_of_birth, Gender)
        VALUES ('$Name', '$Year_of_birth', '$Gender')"; //We have AUTO_INCREMENT of the ID in the Database so we do not need insert value for ID//
        $result = $conn->query($sql);
        if ($result == true) echo "New singer added <br>";
        
    }
?>
<input type="button" value="Click Here to return" onclick="window.location.href='index.php?page=music_DB'" />
