<?php
    $servername = "localhost";
    $username = "manager";
    $password = "manager";
    $dbname = "hospital";
    $q = $_GET["allsearch"];
      
    //Create connection
    
    $sql = new mysqli($servername, $username, $password, $dbname);

    if($sql->connect_error) {
        echo'Could not connect';
    }

    $mysql ="SELECT * FROM products WHERE product_name LIKE '%$q%' ";
    $result = $sql->query($mysql);
    $value = '';
    $data = $result->fetch_all();
    foreach($data as $row) {
        $value .= "<div class=searchall_list>";
        $count = 0;
        foreach($row as $info) {
            $value .= "<div class=searchall_item> $info</div>";
        }
        $value .= " </div>";

    }
    echo $value;
    
    $sql->close();

?>