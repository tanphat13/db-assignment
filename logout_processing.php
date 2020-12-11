
<?php
    if (isset($_SESSION["user"])) {
        echo $_SESSION["user"];
        session_unset();
        session_destroy();
        header('Location:index.php');    
    }
?>
