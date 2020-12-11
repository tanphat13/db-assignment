<style>
    .search_list {
        border: solid;
    }
</style>
<?php
    $servername = "localhost";
    $username = "root";
    $password = "abcdef1234";
    $dbname = "phone";
    $q = $_GET["search"];
 
    // Create connection
    
    $sql = new mysqli($servername, $username, $password, $dbname);

    if($sql->connect_error) {
        echo'Could not connect';
    }

    $mysql ="SELECT * FROM phone WHERE product_name LIKE '%$q%' ";
    $result = $sql->query($mysql);
    $value = '';
    $data = $result->fetch_all();
    foreach($data as $row) {
        $value .= "<div class=search_list>";
        $count = 0;
        // foreach($row as $info) {
        //     // echo $info ." ";
        //     // echo $row['3'] ." ";
        //     // $count +=1;
        //     // if ($count == 4) {
        //     //     $value .= "<div class=search_item> $info</div>";
        //     // }
 
        // }
        $value .= "<div class=search_item> ";
        $value .=  $row['3'];
        $value .= " </div>";

        // $value .= $row["link"];    
        $value .= "</div>";    
    }
    echo $value;
    
    $sql->close();

?>