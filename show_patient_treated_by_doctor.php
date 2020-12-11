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
            echo "<h2>This doctor have treated NO inpatient</h2>";
        }
       
        echo "
        <h3>Inpatient List</h3>
        <div class='list_container'>
        <div class='list_row list_heading'>
            <div class='data_item'>Patient ID</div>
            <div class='data_item'>Fullname</div>
            <div class='data_item'>Gender</div>
            <div class='data_item2'>Diagnosis</div>
            <div class='data_item2'>Date of admission</div>
            <div class='data_item2'>Date of discharge</div>
            <div class='data_item'>Sickroom</div>
        </div>";
        foreach($data as $row) {
            echo "
            <div class='list_row'>
                <div class='data_item'> " .$row[0] ."</div>
                <div class='data_item'> " .$row[1] ."</div>
                <div class='data_item'> " .$row[2] ."</div>
                <div class='data_item2'> " .$row[3] ."</div>
                <div class='data_item2'> " .explode(' ',$row[4])[0]  ."</div>
                <div class='data_item2'> " .$row[5] ."</div>
                <div class='data_item'> " .$row[6] ."</div>
            </div>";
        }
        echo "</div>";

        $sql = "SELECT patient.patient_id, CONCAT(fname, ' ', lname) as Fullname, patient.gender, 
        diagnosis, examination_date, second_exam_date FROM patient JOIN exams ON patient.patient_id = exams.outpatient_id 
        JOIN examination ON examination.examination_id = exams.examination_id WHERE exams.doctor_id = '$ID'";
        $result = $conn->query($sql);
        $data = $result->fetch_all();
        if(sizeof($data) == 0) {
            echo "<h2>This doctor have treated NO outpatient</h2>";
        }
        echo "
        <h3>Outpatient List</h3>
        <div class='list_container'>
        <div class='list_row list_heading'>
            <div class='data_item'>Patient ID</div>
            <div class='data_item'>Fullname</div>
            <div class='data_item'>Gender</div>
            <div class='data_item2'>Diagnosis</div>
            <div class='data_item2'>Examination date</div>
            <div class='data_item2'>Second Examination date</div>
        </div>";
        foreach($data as $row) {
            echo "
            <div class='list_row'>
                <div class='data_item'> " .$row[0] ."</div>
                <div class='data_item'> " .$row[1] ."</div>
                <div class='data_item'> " .$row[2] ."</div>
                <div class='data_item2'> " .$row[3] ."</div>
                <div class='data_item2'> " .$row[4]  ."</div>
                <div class='data_item2'> " .$row[5] ."</div>
            </div>";
        }
        echo "</div>";
?>