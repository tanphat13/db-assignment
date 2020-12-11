<link rel="stylesheet" href="hospital.css">
<?php

    $servername = "localhost";
    $username = "root";
    $password = "Tanphat123";
    $dbname = "hospital";
    $ID = $_GET["ID"];
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $sql = "SELECT CONCAT(fname, ' ', lname) as Fullname FROM patient WHERE patient_id = '$ID'";
    $result = $conn->query($sql);
    $data = $result->fetch_row();
    echo "<h2> Patient Name: $data[0] </h2>"; 
    
    $sql = $conn->query("SELECT total_price_medication('$ID') as total_price;");
    $result = $sql->fetch_object();
    $records =  json_decode($result->total_price);
    
    $service_list = '';
        foreach ($records as $record) {
            $service_list .= "<div class='print'><link rel='stylesheet' href='hospital.css'>";
            $service = explode('-', $record->service);
            $service[0][0] = strtoupper($service[0][0]);
            $service_list .= "<div class='report_container' id='report_container'>
            <div class='report_row'><h3 class='report_item strong service-heading'>$service[0] $service[1]</h3></div>
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
            $service_list .= "</div><a href='#' onclick='printreport(this)'>Print</a>";
        }
    echo $service_list;

?>
<script>
    function printreport(ele) {
        var openWindow = window.open("", "report", "attributes");
        openWindow.document.write(ele.previousSibling.innerHTML);
        openWindow.document.close();
        openWindow.focus();
        openWindow.print();
        // openWindow.close();
        // document.getElementById('report_container').classList.remove('print-container');
    }
</script>