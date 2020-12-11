<?php
    session_start();
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link href="setting-lab2.css" rel="stylesheet">
    <link href="hospital.css" rel="stylesheet">
    <link href="setting-loginform.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.0/angular.min.js"></script>
</head>

<body>

<?php
    include "header.php";
    
    if(isset($_GET['page'] )) {
        $page = $_GET['page'];
        include "$page.php";
    }
    include "footer.html";
?>

</body>
</html>