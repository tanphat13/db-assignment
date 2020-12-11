
<?php
    echo "This is Contacts " ;
    if (isset($_SESSION["user"])) {
    echo "<br> Session Value: " .$_SESSION["user"]; 
    }
?>
