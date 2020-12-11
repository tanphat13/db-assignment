
<?php
    $flag = 0;
    $servername = "localhost";
    $username = "manager";
    $password = "manager";
    $dbname = "hospital";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    $conn->connect($servername, $username, $password, $dbname);

    $sql = "SELECT * FROM user";
    $result = $conn->query($sql);
    $data = $result->fetch_all();
    if ($result->num_rows >0) {
        foreach ($data as $row) {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'   ];
                if ($username == $row['1'] && $password == $row['2']) {
                    //setcookie($username, 100, ''. "/");
                    session_start();
                    $_SESSION["user"] = $username;
                    $_SESSION["permission"] = $row['3'];                 
                    $flag = 1;
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
