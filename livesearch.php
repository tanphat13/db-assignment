
<?php
    $servername = "localhost";
    $username = "root";
    $password = "Tanphat123";
    $dbname = "hospital";
    $q = $_GET["search"];
 
    // Create connection
    
    $sql = new mysqli($servername, $username, $password, $dbname);

    if($sql->connect_error) {
        echo'Could not connect';
    }

    $mysql ="SELECT patient.patient_id, CONCAT(fname, ' ' ,lname) as full_name, phone_number 
        FROM patient
        WHERE CONCAT(fname, ' ' ,lname)  LIKE '%$q%' OR CONCAT(lname, ' ' ,fname)  LIKE '%$q%' OR phone_number LIKE '%$q%' OR patient.patient_id LIKE '%$q%' ";
    $result = $sql->query($mysql);
    $value = '';
    $data = $result->fetch_all();
    $value .= "<div class=search_list>";
    foreach($data as $row) {
    
        $count = 0;
        
        $value .= "<div class=search_item> ";
        $value .= "<a href='index.php?page=show_patient_info&ID=$row[0]'>";
        $value .= "ID: " .$row['0'] ." Name: " .$row['1'] ." Phone: " .$row['2'] ;
        $value .= "</a> </div>";    
        
    }
    $value .= "</div>";    
    echo $value;
    
    $sql->close();

?>