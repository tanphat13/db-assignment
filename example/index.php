<!DOCTYPE HTML>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link href="setting-lab2.css" rel="stylesheet">
    <link href="setting-loginform.css" rel="stylesheet">
</head>
<body>

<?php
include "head.html";
    if(isset($_GET['page'] )) {
        $page = $_GET['page'];
        include "$page.php";
    }
    include "footer.html";
?>

</body>
</html>