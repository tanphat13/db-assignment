<?php

    $servername = "localhost";
    $username = "root";
    $password = "Tanphat123";
    $dbname = "hospital";
    $ID = $_GET["ID"];
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
        // $sql = "SELECT DISTINCT patient.patient_id, CONCAT(fname, ' ',lname) as Fullname, gender, address, sickroom, date_of_admission,diaganosis, date_of_discharge, result,
        //     medication.name 
        //     FROM patient, inpatient, treats, treatment, treatment_medication, medication 
        //     WHERE patient.patient_id = '$ID' AND inpatient.patient_id = '$ID' AND treats.inpatient_id = '$ID' AND treats.treatment_id = treatment.treatment_id 
        //     AND treatment.treatment_id = treatment_medication.treatment_id AND treatment_medication.medication_id = medication.medication_id";
        $sql = "SELECT patient.patient_id, CONCAT(fname, ' ', lname) as Fullname, patient.gender, 
        diagnosis, date_of_admission, date_of_discharge, sickroom FROM patient JOIN inpatient ON patient.patient_id = inpatient.patient_id 
        JOIN treats ON treats.inpatient_id = patient.patient_id WHERE treats.doctor_id = '$ID'";
        $result = $conn->query($sql);
        $data = $result->fetch_all();
        if(sizeof($data) == 0) {
            echo "<h2>This doctor have treated NO patient</h2>";
        }
       
        echo "<div class='list_container'>";
        echo "<div class='data_item'> " ."Patient ID" ."</div>" ;
        echo "<div class='data_item'> "  ."Fullname"  ."</div>" ;
        echo "<div class='data_item'> "  ."Gender "  ."</div>" ;
        echo "<div class='data_item2'> "  ."Diaganosis"  ."</div>" ;
        echo "<div class='data_item2'> "  ."Date of admission" ."</div>" ;
        echo "<div class='data_item2'> " ."Date of discharge"  ."</div>" ;
        echo "<div class='data_item'> " ."Sickroom"  ."</div>" ;            
        echo "</div>";
        foreach($data as $row) {
            echo "<div class='list_container'>";
            echo "<div class='data_item'> " .$row[0] ."</div>" ;
            echo "<div class='data_item'> " .$row[1] ."</div>" ;
            echo "<div class='data_item'> " .$row[2] ."</div>" ;
            echo "<div class='data_item2'> " .$row[3] ."</div>" ;
            echo "<div class='data_item2'> " .explode(' ',$row[4])[0]  ."</div>" ;
            echo "<div class='data_item2'> " .$row[5] ."</div>" ;
            echo "<div class='data_item'> " .$row[6] ."</div>" ;            
            echo "</div>";
            
        }

       

   

?>