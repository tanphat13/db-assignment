<?php

    $servername = "localhost";
    $username = "root";
    $password = "Tanphat123";
    $dbname = "hospital";
    $ID = $_GET["ID"];
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if (substr($ID,0,2) == 'IP') {
        $sql = "SELECT patient.patient_id, CONCAT(fname, ' ', lname) as Fullname, gender, phone_number FROM patient WHERE patient.patient_id = '$ID' ";
        $result = $conn->query($sql);
        $data = $result->fetch_all();
        foreach($data as $row) {
            echo "<h3>Personal Info</h3>";
            echo "<div class='personal_info'> " ;
            echo "<div class='data_item'>" ."Patient ID: "  .$row[0] ."</div>"
                ."<div class='data_item'>" ." Fullname: " .$row[1] ."</div>"
                ."<div class='data_item'>" ." Gender: " .$row[2] ."</div>"
                ."<div class='data_item'>" ." Phone: " .$row[3] ."</div>" ;

            echo "</div>";
        }
        $sql = "SELECT  treatment.treatment_id, treatment.start_date, treatment.end_date, treatment.result, CONCAT(employee.fname, ' ', employee.lname) as DoctorName
            FROM treatment JOIN treats ON treatment.treatment_id = treats.treatment_id 
            JOIN employee ON treats.doctor_id = employee.employee_id 
            WHERE treats.inpatient_id = '$ID'";
        $result = $conn->query($sql);
        $data = $result->fetch_all();
        
        foreach($data as $row) {
            echo "<div class='treatment'>";
            echo "<h3>Treatment Infomation: </h3>";
            echo "<div class='personal_info'>";
            echo "<div class='data_item'>" ."Treatment ID: "  .$row[0] ."</div>"
                ."<div class='data_item'>" ."Start date: "  .$row[1] ."</div>"
                ."<div class='data_item'>" ."End date: "  .$row[2] ."</div>"
                ."<div class='data_item'>" ."Result: "  .$row[3] ."</div>"
                ."<div class='data_item'>" ."Doctor Name: "  .$row[4] ."</div>"

                ;
            echo "</div>";
            echo "<h3>Medication List</h3>";
            $newsql = "SELECT name FROM medication JOIN treatment_medication ON medication.medication_id = treatment_medication.medication_id WHERE treatment_medication.treatment_id = '$row[0]'";
            $query = $conn->query($newsql);
            $medications = $query->fetch_all();
            $count = 0;
            echo "<div class='medication_list'>";
            echo "<div class='medication'>";
                echo "<div class='data_item'>" ."No. "   ."</div>";
                echo "<div class='data_item'>" ."Medication Name "   ."</div>";
                echo "</div>";
            foreach($medications as $medication) {
                echo "<div class='medication'>";
                echo "<div class='data_item'>"   .$count ."</div>";
                echo "<div class='data_item'>"   .$medication[0] ."</div>";
                echo "</div>";
                $count += 1;
            }
            $count = 0;
            echo "</div>";
            echo "</div class='treatment'>";
        }
    } 
    else if (substr($ID,0,2) == 'OP') {
        $sql = "SELECT patient.patient_id, CONCAT(fname, ' ', lname) as Fullname, gender, phone_number FROM patient WHERE patient.patient_id = '$ID' ";
        $result = $conn->query($sql);
        $data = $result->fetch_all();
        foreach($data as $row) {
            echo "<h3>Personal Info</h3>";
            echo "<div class='personal_info'> " ;
            echo "<div class='data_item'>" ."Patient ID: "  .$row[0] ."</div>"
                ."<div class='data_item'>" ." Fullname: " .$row[1] ."</div>"
                ."<div class='data_item'>" ." Gender: " .$row[2] ."</div>"
                ."<div class='data_item'>" ." Phone: " .$row[3] ."</div>" ;

            echo "</div>";
        }

        $sql = "SELECT exams.examination_id, examination.examination_date, examination.second_exam_date, examination.diaganosis, CONCAT(Fname, ' ', Lname) as DoctorName
            FROM exams JOIN examination ON exams.examination_id = examination.examination_id 
            JOIN employee ON employee.employee_id = exams.doctor_id 
            WHERE exams.outpatient_id = '$ID'";
        $result = $conn->query($sql);
        $data = $result->fetch_all();
        foreach ($data as $row) {
            echo "<div class='treatment'>";
            echo "<h3>Examination Infomation: </h3>";
            echo "<div class='personal_info'>";
            echo "<div class='data_item'>" ."Examination ID: "  .$row[0] ."</div>"
                ."<div class='data_item'>" ."Examination Date: "  .$row[1] ."</div>"
                ."<div class='data_item'>" ."Next Examination Date: "  .$row[2] ."</div>"
                ."<div class='data_item'>" ."Diaganosis: "  .$row[3] ."</div>"
                ."<div class='data_item'>" ."Doctor Name: "  .$row[4] ."</div>"
                ;
            echo "</div>";
            echo "<h3>Medication List</h3>";
            $newsql = "SELECT name FROM medication JOIN examination_medication ON medication.medication_id = examination_medication.medication_id WHERE examination_medication.examination_id = '$row[0]'";
            $query = $conn->query($newsql);
            $medications = $query->fetch_all();
            $count = 0;
            echo "<div class='medication_list'>";
            echo "<div class='medication'>";
                echo "<div class='data_item'>" ."No. "   ."</div>";
                echo "<div class='data_item'>" ."Medication Name "   ."</div>";
                echo "</div>";
            foreach($medications as $medication) {
                echo "<div class='medication'>";
                echo "<div class='data_item'>"   .$count ."</div>";
                echo "<div class='data_item'>"   .$medication[0] ."</div>";
                echo "</div>";
                $count += 1;
            }
            $count = 0;
            echo "</div>";
            echo "</div class='treatment'>";
        }
       

    }

?>