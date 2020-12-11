<div class="content">
    <?php
    $name = $_POST['username'];
    $password = $_POST['password'];

    echo "Name: $name";
    echo "<br>";
    echo "Password: $password";
    echo "<br>";
    
    if ($name == "thong" && $password == "123456") {
        echo "Correct";
        setcookie("username", $name);
        echo "<br>";
        echo "Saved Cookie";
    } else
        echo "Incorrect";
    ?>
</div>