<link rel="stylesheet" href="hospital.css">
<?php

    $servername = "localhost";
    $username = "manager";
    $password = "manager";
    $dbname = "hospital";
    $ID = $_GET["ID"];
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $sql = "SELECT CONCAT(fname, ' ', lname) as Fullname FROM patient WHERE patient_id = '$ID'";
    $result = $conn->query($sql);
    $data = $result->fetch_row();
    echo "<h1> Patient Name: $data[0] </h1><br>"; 
    
    $sql = $conn->query("SELECT total_price_medication('$ID') as total_price;");
    $result = $sql->fetch_object();
    $records =  json_decode($result->total_price);
    
    $service_list = '';
        foreach ($records as $record) {
            echo var_dump(explode('-', $record->service));
            $service = explode('-', $record->service);
            $service_list .= "<div class='report_container'>
            <h6 class='part strong'>$service[0] $service[1]</h6>
            <div class='report_row'>
                <div class='report_item'>Medication Name</div>
                <div class='report_item'>Medication Price</div>
            </div>";
            $sql = $conn->query("SELECT M.name, M.price FROM $service[0]_medication SM JOIN medication M ON SM.medication_id = M.medication_id WHERE SM.$service[0]_id = $service[1];");
            $medication_list = $sql->fetch_all();
            foreach ($medication_list as $item) {
                $service_list .= "<div class='report_row'>
                    <div class='report_item'>$item[0]</div>
                    <div class='report_item'>$item[1]$</div>
                </div>";
            }
            $service_list .= "<div class='report_row'>
                    <div class='report_item strong'>Total</div>
                    <div class='report_item strong'>$record->price$</div>
                </div>
            </div>";
        }
    echo $service_list;

    // if (substr($ID,0,2) == 'IP') {
      
        
    //     $total_price = 0;
    //     $sql = "SELECT treats.treatment_id, treatment.start_date, treatment.end_date, medication.name, medication.price  
    //         FROM treats JOIN treatment ON treats.treatment_id = treatment.treatment_id 
    //         JOIN treatment_medication ON treatment_medication.treatment_id = treatment.treatment_id 
    //         JOIN medication ON medication.medication_id = treatment_medication.medication_id
    //         WHERE treats.inpatient_id = '$ID'";
    //     $result = $conn->query($sql);
    //     $data = $result->fetch_all();
    //     echo "<h2> Treatment ID: " .$data[0][0] ." Start date: " .$data[0][1] ." End date: " .$data[0][2] ."</h2><br>" ;
    //     echo "<div class='report_container'>";
    //     echo "<div class='report_item'> Medication Name </div>";
    //     echo "<div class='report_item'> Medication Price </div>";
    //     echo "</div>";
    //     foreach($data as $row) {
    //         echo "<div class='report_container'>";
    //         echo "<div class='report_item'> $row[3] </div>";
    //         echo "<div class='report_item'> $row[4] </div>"; 
    //         echo "</div>";
    //         $total_price += $row[4];
    //     }
    //     echo "<div class='report_container'>";
    //     echo "<div class='report_item'> Total Price of Medications </div>";
    //     echo "<div class='report_item'> $total_price </div>"; 
    //     echo "</div>";

        

    // } else if (substr($ID,0,2) == 'OP') {
    //     $total_price = 0;
    //     $sql = "SELECT exams.examination_id, examination.examination_date, medication.name, medication.price 
    //         FROM exams JOIN examination ON exams.examination_id = examination.examination_id 
    //         JOIN examination_medication ON examination_medication.examination_id = examination.examination_id 
    //         JOIN medication ON medication.medication_id = examination_medication.medication_id 
    //         WHERE exams.outpatient_id = '$ID'";
    //     $result = $conn->query($sql);
    //     $data = $result->fetch_all();
    //     echo "<h2> Examination ID: " .$data[0][0] ." Examination Date: " .$data[0][1]."</h2><br>" ;
    //     echo "<div class='report_container'>";
    //     echo "<div class='report_item'> Medication Name </div>";
    //     echo "<div class='report_item'> Medication Price </div>";
    //     echo "</div>";
    //     foreach($data as $row) {
    //         echo "<div class='report_container'>";
    //         echo "<div class='report_item'> $row[2] </div>";
    //         echo "<div class='report_item'> $row[3] </div>"; 
    //         echo "</div>";
    //         $total_price += $row[3];
    //     }
    //     echo "<div class='report_container'>";
    //     echo "<div class='report_item'> Total Price of Medications </div>";
    //     echo "<div class='report_item'> $total_price </div>"; 
    //     echo "</div>";
    // }

?>