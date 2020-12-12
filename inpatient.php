<?php
     $servername = "localhost";
     $username = $_GET["username"];
     $password = $_GET["password"];
     $dbname = "hospital";

     $conn = new mysqli($servername, $username, $password, $dbname);

     $sql =  $conn->query("SELECT employee_id, CONCAT(Fname, ' ', Lname) as fullname FROM nurse;");
     $records = $sql->fetch_all();
?>

<div class="form-item">
    <label for="diagnosis">Diagnosis</label>
    <input type="text" id="diagnosis" name="diagnosis" placeholder="Patient's Diagnosis" required>
</div>

<div class="form-item">
    <label for="admission-date">Date Of Admission</label>
    <input type="datetime-local" id="admission-date" name="admission-date" required>
</div>

<div class="form-item">
    <label for="sickroom">Room</label>
    <input type="text" id="sickroom" name="sickroom" placeholder="Patient's Room" required>
</div>

<div class="form-item">
    <label for="nurse">Nurse</label>
    <select id="nurse" name="nurse" required>
        <?php
            $list_nurse = "";
            foreach($records as $record) {
                $list_nurse .= "<option value='$record[0]'>$record[0] - $record[1]</option>";
            }
            echo $list_nurse;
        ?>
    </select>
</div>