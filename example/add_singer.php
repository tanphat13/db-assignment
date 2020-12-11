<?php
    $servername = "localhost";
    $username = "manager";
    $password = "manager";
    $dbname = "hospital";
    
    $singerName = $_POST['singer-name'];
    $singerYob = $_POST['yob'];
    $singerGender = $_POST['gender'];

    $connection = new mysqli($servername, $username, $password, $dbname);
    $sql = $connection->query("INSERT INTO singers (id, singer_name, year_of_birth, gender) VALUES (NULL,'$singerName','$singerYob','$singerGender')");

    if ($sql === TRUE) {
        header('Location: http://localhost/~cutanphat/display_singer.php');
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }

    $connection->close();
?>