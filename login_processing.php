
<?php
    $flag = 0;
    $servername = "localhost";
    $username = "login";
    $password = "login";
    $dbname = "user";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    $conn->connect($servername, $username, $password, $dbname);

    $sql = "SELECT * FROM user";
    $result = $conn->query($sql);
    $data = $result->fetch_all();
    
    if ($result->num_rows >0) {
        foreach ($data as $row) {
            
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $input_username = $_POST['username'];
                $input_password = $_POST['password'];
                if ($input_username == $row['1'] && $input_password == $row['2']) {
                    // session_start();
                    echo var_dump($row);
                    $_SESSION["username"] = $row['3'];
                    $_SESSION["password"] = $row['4'];                 
                    $flag = 1;
                    echo var_dump($_SESSION);
                    break;
                    
                }
            }
        }
        if ($flag == 1) {
            header('Location:index.php?page=search');
        }
        else {
            echo 'Wrong username or password';
            echo '<a href=index.php?page=login > Click here to log in again</a>';
        }

    }
?>
