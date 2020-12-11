<?php

    $servername = "localhost";
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $dbname = "hospital";
    $ID = $_GET["ID"];
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if (substr($ID,0,2) == 'IP') {
        $sql = "SELECT patient.patient_id, CONCAT(fname, ' ', lname) as Fullname, gender, phone_number FROM patient WHERE patient.patient_id = '$ID' ";
        $result = $conn->query($sql);
        $data = $result->fetch_all();
        foreach($data as $row) {
            echo "<h3>Personal Info</h3>";
            echo "<div class='report_container'> " ;
            echo "
                <div class='report_row'>
                    <div class='report_item'>Patient ID</div>
                    <div class='report_item'>$row[0]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Fullname</div>
                    <div class='report_item'>$row[1]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Gender</div>
                    <div class='report_item'>$row[2]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Phone Number</div>
                    <div class='report_item'>$row[3]</div>
                </div>";
            echo "</div>";
        }
        $sql = "SELECT  treatment.treatment_id, treatment.start_date, treatment.end_date, treatment.result, CONCAT(employee.fname, ' ', employee.lname) as DoctorName
            FROM treatment JOIN treats ON treatment.treatment_id = treats.treatment_id 
            JOIN employee ON treats.doctor_id = employee.employee_id 
            WHERE treats.inpatient_id = '$ID'";
        $result = $conn->query($sql);
        $data = $result->fetch_all();
        
        foreach($data as $row) {
            echo "<h3>Treatment Infomation: </h3>";
            echo "<div class='report_container'>";
            echo "
                <div class='report_row'>
                    <div class='report_item'>Treatment Id</div>
                    <div class='report_item'>$row[0]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Start date</div>
                    <div class='report_item'>$row[1]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>End date</div>
                    <div class='report_item'>$row[2]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Result</div>
                    <div class='report_item'>$row[3]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Doctor</div>
                    <div class='report_item'>$row[4]</div>
                </div>";
            echo "</div>";
        }
    } 
    else if (substr($ID,0,2) == 'OP') {
        $sql = "SELECT patient.patient_id, CONCAT(fname, ' ', lname) as Fullname, gender, phone_number FROM patient WHERE patient.patient_id = '$ID' ";
        $result = $conn->query($sql);
        $data = $result->fetch_all();
        foreach($data as $row) {
            echo "<h3>Personal Info</h3>";
            echo "<div class='report_container'> " ;
            echo "
                <div class='report_row'>
                    <div class='report_item'>Patient ID</div>
                    <div class='report_item'>$row[0]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Fullname</div>
                    <div class='report_item'>$row[1]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Gender</div>
                    <div class='report_item'>$row[2]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Phone Number</div>
                    <div class='report_item'>$row[3]</div>
                </div>";
            echo "</div>";
        }

        $sql = "SELECT exams.examination_id, examination.examination_date, examination.second_exam_date, examination.diagnosis, CONCAT(Fname, ' ', Lname) as DoctorName
            FROM exams JOIN examination ON exams.examination_id = examination.examination_id 
            JOIN employee ON employee.employee_id = exams.doctor_id 
            WHERE exams.outpatient_id = '$ID'";
        $result = $conn->query($sql);
        $data = $result->fetch_all();
        foreach ($data as $row) {
            echo "<h3>Examination Infomation: </h3>";
            echo "<div class='report_container'>";
            echo "
                <div class='report_row'>
                    <div class='report_item'>Examination ID</div>
                    <div class='report_item'>$row[0]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Examination Date</div>
                    <div class='report_item'>$row[1]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Next Examination Date</div>
                    <div class='report_item'>$row[2]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Diagnosis</div>
                    <div class='report_item'>$row[3]</div>
                </div>
                <div class='report_row'>
                    <div class='report_item'>Doctor</div>
                    <div class='report_item'>$row[4]</div>
                </div>";
            echo "</div>";
        }
       

    }

?>