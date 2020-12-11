
<?php
    $servername = "localhost";
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $dbname = "hospital";
    $q = $_GET["search"];
 
    // Create connection
    
    $sql = new mysqli($servername, $username, $password, $dbname);

    if($sql->connect_error) {
        echo'Could not connect';
    }

    $mysql ="SELECT employee_id, CONCAT(Fname, ' ', Lname) as Fullname FROM doctor
        WHERE CONCAT(fname, ' ' ,lname)  LIKE '%$q%' OR CONCAT(lname, ' ' ,fname)  LIKE '%$q%' OR employee_id LIKE '%$q%' ";
    $result = $sql->query($mysql);
    $value = '';
    $data = $result->fetch_all();
    $value .= "<div class=search_list>";
    foreach($data as $row) {
        
        $count = 0;
        
        $value .= "<div class=search_item> ";
        $value .= "<a href='index.php?page=show_patient_treated_by_doctor&ID=$row[0]'>";
        $value .= "Doctor ID: " .$row['0'] ." Fullname: " .$row['1']  ;
        $value .= "</a> </div>";    
            
    }
    $value .= "</div>";
    echo $value;
    
    $sql->close();

?>