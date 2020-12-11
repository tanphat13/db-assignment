<?php
    if ($_SESSION == null) {
        echo "<h1>You have not login </h1>";
        echo "<a href=index.php?page=login>Click here to login</a>";
    }
    else {
    echo "<form class='search'>
            <input type='text' class='search-bar' placeholder='Search doctor' id='search_value' onkeyup='showLiveResult(this.value)'>
            <input type='button' class='btn' value='Search' id='search_all' onclick='showAllResult() ' />
            <div id='livesearch'></div>
        </form>";   
    }
    // $conn = new mysqli($servername, $username, $password, $dbname);
    // if (substr($ID,0,2) == 'IP') {
    //     // $sql = "SELECT DISTINCT patient.patient_id, CONCAT(fname, ' ',lname) as Fullname, gender, address, sickroom, date_of_admission,diaganosis, date_of_discharge, result,
    //     //     medication.name 
    //     //     FROM patient, inpatient, treats, treatment, treatment_medication, medication 
    //     //     WHERE patient.patient_id = '$ID' AND inpatient.patient_id = '$ID' AND treats.inpatient_id = '$ID' AND treats.treatment_id = treatment.treatment_id 
    //     //     AND treatment.treatment_id = treatment_medication.treatment_id AND treatment_medication.medication_id = medication.medication_id";
    //     $sql = "SELECT patient.patient_id, CONCAT(fname, ' ', lname) as Fullname, gender, phone_number FROM patient WHERE patient.patient_id = '$ID' ";
    //     $result = $conn->query($sql);
    //     $data = $result->fetch_all();
    //     foreach($data as $row) {
    //         echo "<h3>Personal Info</h3>";
    //         echo "<div class='personal_info'> " ;
    //         echo "<div class='data_item'>" ."Patient ID: "  .$row[0] ."</div>"
    //             ."<div class='data_item'>" ." Fullname: " .$row[1] ."</div>"
    //             ."<div class='data_item'>" ." Gender: " .$row[2] ."</div>"
    //             ."<div class='data_item'>" ." Phone: " .$row[3] ."</div>" ;

    //         echo "</div>";
    //     }

    //     $sql = "SELECT date_of_admission, date_of_discharge, sickroom, diaganosis FROM inpatient WHERE patient_id = '$ID' ";
    //     $result = $conn->query($sql);
    //     $data = $result->fetch_all();
      
    //     foreach($data as $row) {
    //         echo "<h3>Accommodation</h3>";
    //         echo "<div class='personal_info'> " ;
    //         echo "<div class='data_item'>" ."Date of admission: "  .$row[0] ."</div>"
    //             ."<div class='data_item'>" ." Date of admission: " .$row[1] ."</div>"
    //             ."<div class='data_item'>" ." Sickroom: " .$row[2] ."</div>"
    //             ."<div class='data_item'>" ." Diaganosis: " .$row[3] ."</div>" ;

    //         echo "</div>";
            
    //     }

    //     $sql = "SELECT medication.name FROM medication JOIN treatment_medication 
    //         ON treatment_medication.medication_id = medication.medication_id 
    //         JOIN treats ON treats.treatment_id = treatment_medication.treatment_id 
    //         WHERE treats.inpatient_id = '$ID';
    //         -- WHERE treats.inpatient_id = '$ID' AND treats.treatment_id = treatment.treatment_ID AND treatment.treatment_id = treatment_medication.treatment_id 
    //         -- AND treatment_medication.medication_id = medication.medication_id ";
    //     $result = $conn->query($sql);
    //     $data = $result->fetch_all();
        
    //     echo "<h3>Medication List</h3><div class=medication_list>";
    //     echo "<div class='medication_header'> No.";
    //     echo "<div class='data_item'> Medication Name</div></div>";
    //     $count = 1;
    //     foreach($data as $row) {
    //         echo "<div class='medication'> " ;
    //         echo "<div class='data_item'>" .$count ."</div>";
    //         echo "<div class='data_item'>" .$row['0'] ."</div>";
    //         $count += 1;
                
    //         echo "</div>";
    //     }
    //     $count = 0;
    //     echo "</div>";

    // } else if (substr($ID,0,2) == 'OP') {
    //     echo "OP";
    // }

?>

<script>
    function showLiveResult(str) {
    if (str.length==0) {
        document.getElementById("livesearch").innerHTML="";
        document.getElementById("livesearch").style.border="0px";
        return;
    }
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
        document.getElementById("livesearch").innerHTML=this.responseText;
        document.getElementById("livesearch").style.border="1px solid #A5ACB2";
        }
    }
    xmlhttp.open("GET","doctor_livesearch.php?search="+str,true);
    xmlhttp.send();
    }
</script>