<link href="setting-loginform.css" rel="stylesheet">
<?php
$servername = "localhost";
$username = "root";
$password = "abcdef1234";
$dbname = "music";

// Create connection

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
$conn->connect($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM singer";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  
  echo " <table border='1'>
    <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Year of birth</th>
    <th>Gender</th>
    </tr>";
  while($row = $result->fetch_assoc()) {
    
    echo "
    <tr>
        <td>  ".$row["ID"]." </td>
        <td>  ".$row["singer_name"]." </td>
        <td>  ".$row["Year_of_birth"]." </td>
        <td>  ".$row["Gender"]." </td>        
    </tr>
    ";
    }
    echo "</table>";
} 
else {
  echo "0 results";
}
$conn->close();

// function add_new_singer($ID, $name, $Year_of_birth, $Gender) {
//     $servername = "localhost";
//     $username = "root";
//     $password = "abcdef1234";
//     $dbname = "music";

//     // Create connection

//     $conn = new mysqli($servername, $username, $password, $dbname);
//     // Check connection
//     $conn->connect($servername, $username, $password, $dbname);
//     if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
//     }
//     $sql = "INSERT INTO singer (ID, Name, Year_of_birth, Gender)
//     VALUES ('$ID', '$name', '$Year_of_birth', '$Gender')";
// }
// function create_new_db() {
//   $servername = "localhost";
//   $username = "root";
//   $password = "abcdef1234";
//   $dbname = "music";

//   // Create connection

//   $conn = new mysqli($servername, $username, $password, $dbname);
//   // Check connection
//   $conn->connect($servername, $username, $password, $dbname);
//   if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
//   }
//   $test = "CREATE DATABASE newDatabase; CREATE TABLE new_Table;";

// }

?>
<form class="new_singer" action="index.php?page=add_new_singer_processing" method="post">
    <fieldset> 
        <legend> Add new Singer </legend> <br> 
        <label for="Name">Name:</label>
        <input type="text" id="Name" name="Name" required><br>
        <label for="Year_of_birth">Year of birth:</label>
        <input type="text" id="Year_of_birth" name="Year_of_birth" required><br>
        <label for="Gender">Gender: </label>
        <input type="radio" name="Gender" value="Male" checked>
        <label for="male">Male </label><br>
        <label for="gender"> </label>
        <input type="radio" name="Gender" value="Female">
        <label for="female">Female </label><br>
        <input type="submit">
    </fieldset>
</form>

<form class="new_database" action="index.php?page=create_new_db" method="post">
    <fieldset> 
        <legend> Add new Database </legend> <br> 
        <label for="dbname">Database Name:</label>
        <input type="text" id="dbname" name="dbname" required><br>
        <label for="tbname">Table Name:</label>
        <input type="text" id="tbname" name="tbname" required><br>
        <input type="submit">
    </fieldset>
</form>