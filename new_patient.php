<form class="new_patient" action="index.php?page=new_patient_processing" method="post">
    <div class="form-container"> 
        <legend> Add New Patient </legend>

        <div class="form-item patient_type">
            <label for="patient_type">Type of Patient</label>
            <div class="option">
                <input type="radio" id="in_patient" name="patient_type" value="IP" onchange="addFormField('inpatient')">
                <label for="male">Inpatient </label>
                <input type="radio" id="out_patient" name="patient_type" value="OP" onchange="addFormField('outpatient')">
                <label for="female">Outpatient </label>
            </div>
        </div>
        
        <div class="form-item">
            <label for="fname">First Name</label>
            <input type="text" id="fname" name="fname" placeholder="Patient's First Name" required>
        </div>
        
        <div class="form-item">
            <label for="lname">Last Name</label>
            <input type="text" id="lname" name="lname" placeholder="Patient's Last Name" required>
        </div>
        
        <div class="form-item">
            <label for="date_of_birth">Birthday:</label>
            <input type="date" id="date_of_birth" name="date_of_birth">
        </div>
        
        <div class="form-item patient_type">
            <label for="gender">Gender</label>
            <div class="option">
                <input type="radio" id="male" name="gender" value="Male" checked>
                <label for="male">Male </label>
                <input type="radio" id="female" name="gender" value="Female">
                <label for="female">Female </label>
            </div>
        </div>

        <div class="form-item">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="Patient's Phone Number" required pattern="[0-9]{10}">
        </div>

        <div class="form-item">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" placeholder="Patient's Address" required>
        </div>

        <div id="additional-info"></div>

        <input type="submit" value="Submit" class="form-item btn">
    </div>
</form>

<script>
    function addFormField(type) {
        console.log(type);
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                document.getElementById('additional-info').innerHTML = this.response;
            }
        }
        xhttp.open("GET", type+".php", true);
        xhttp.send();
    }
</script>