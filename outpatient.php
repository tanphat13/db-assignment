<?php
     $servername = "localhost";
     $username = "root";
     $password = "Tanphat123";
     $dbname = "hospital";

     $conn = new mysqli($servername, $username, $password, $dbname);

     $sql =  $conn->query("SELECT employee_id, CONCAT(Fname, ' ', Lname) as fullname FROM doctor;");
     $records = $sql->fetch_all();
?>

<div class="form-item">
    <label for="examination-date">Examination Date</label>
    <input type="date" id="examination-date" name="examination-date" required>
</div>

<div class="form-item">
    <label for="examination-2nd-date">Examination Date</label>
    <input type="date" id="examination-2nd-date" name="examination-2nd-date" required>
</div>

<div class="form-item">
    <label for="diagnosis">Diagnosis</label>
    <input type="text" id="diagnosis" name="diagnosis" placeholder="Patient's Diagnosis" required>
</div>

<div class="form-item">
    <label for="doctor">Doctor</label>
    <select id="doctor" name="doctor" required>
        <?php
            $list_doctor = "";
            foreach($records as $record) {
                $list_doctor .= "<option value='$record[0]'>$record[0] - $record[1]</option>";
            }
            echo $list_doctor;
        ?>
    </select>
</div>

<div class="form-item">
    <label for="fee">Fee</label>
    <input type="text" id="fee" name="fee" placeholder="Fee" required>
</div>
