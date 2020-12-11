<?php

    $servername = "localhost";
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $dbname = "hospital";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $type = $_POST["patient_type"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $date_of_birth = $_POST["date_of_birth"];
    $gender = $_POST["gender"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $diagnosis = $_POST["diagnosis"];

    $sql = "SELECT MAX(SUBSTR(patient_id, 3, 7)) as max_id FROM patient WHERE SUBSTR(patient_id,1,2) = '$type'";
    $result = $conn->query($sql);
    $data = $result->fetch_object();
    
    $post_id = strval(intval($data->max_id)+1);
    $digit = str_split($post_id);
    while (sizeof($digit) < 5) {
        array_unshift($digit, '0');
    }
    $id = $type.implode("",$digit);
    $date_of_birth = date('Y-m-d',strtotime($date_of_birth));

    $sql = "INSERT INTO patient VALUES ('$id', '$fname', '$lname', '$date_of_birth', '$phone', '$gender', '$address')";
    $exec = $conn->query($sql);
    if (!$exec) {
        echo "FAILED";
    }

    if ($type === "IP") {
        $admission_date = $_POST["admission-date"];
        $sickroom = $_POST["sickroom"];
        $nurse_id = $_POST["nurse"];
        $sql = "UPDATE inpatient SET diagnosis='$diagnosis', date_of_admission='$admission_date', sickroom='$sickroom', nurse_id=$nurse_id WHERE patient_id='$id';";
        $conn->query($sql);
    }

    if ($type === "OP") {
        $examination_date = $_POST["examination-date"];
        $examination_2nd_date = $_POST["examination-2nd-date"];
        $doctor_id = $_POST["doctor"];
        $fee = $_POST["fee"];
        
        $sql = "INSERT INTO examination(examination_date, second_exam_date, diagnosis, fee) VALUES ('$examination_date', '$examination_2nd_date', '$diagnosis', '$fee');";
        $conn->query($sql);

        $examination_id = $conn->insert_id;
        $sql = "INSERT INTO exams(outpatient_id, examination_id, doctor_id) VALUES ('$id', $examination_id, $doctor_id)";
        $conn->query($sql);
    }

?>