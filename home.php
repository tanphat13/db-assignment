

<?php 
    // if (isset( $_SESSION["user"] )) {
    //     echo "Session Value: " .$_SESSION["user"] .'<<<<<<<<<';
    // }
    // if(!isset($_COOKIE['admin'])) {
    //     echo "Cookie named '" . $_POST['username'] . "' is not set!";
    // } else {
    //     echo "Welcome " .$_COOKIE['admin'];
    // }
    $servername = "localhost";
    $username = "manager";
    $password = "manager";
    $dbname = "hospital";

    // Create connection

    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $sql = "SELECT * FROM products";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        
        echo "<div class='homepage'> <table border='1'>
          <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Price</th>
          <th>Product Color</th>
          </tr>";
        while($row = $result->fetch_assoc()) {
          
          echo "
          <tr>
              <td>  ".$row["product_id"]." </td>
              <td>  ".$row["product_name"]." </td>
              <td>  ".$row["product_price"]." </td>
              <td>  ".$row["product_color"]." </td>        
          </tr>
          ";
          }
          echo "</table>";
      } 
      else {
        echo "0 results";
      }
      echo $_SESSION["permission"]; 
    if ($_SESSION["permission"] == "full") {  
      echo '<div class="add_new_product"> 
      <form class="new_singer" action="index.php?page=access_db_processing" method="post">
      <fieldset> 
          <legend> Add new Product </legend> <br> 
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" required><br>
          <label for="price">Price:</label>
          <input type="text" id="price" name="price" required><br>
          <label for="color">Color:</label>
          <input type="text" id="color" name="color" required><br>
          <input type="submit">
      </fieldset>
      </form></div>';
       
      echo '<div class="edit_product"> 
      <form class="new_singer" action="index.php?page=edit_data" method="post">
      <fieldset> 
          <legend> Edit Product </legend> <br> 
          <label for="ID">ID:</label>
          <input type="text" id="ID" name="ID" required><br>
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" required><br>
          <label for="price">Price:</label>
          <input type="text" id="price" name="price" required><br>
          <label for="color">Color:</label>
          <input type="text" id="color" name="color" required><br>
          <input type="submit">
      </fieldset>
      </form>
      </div>';
      echo '<div class="remove_product"> 
      <form class="new_singer" action="index.php?page=remove_data" method="post">
      <fieldset> 
          <legend> Remove Product </legend> <br> 
          <label for="ID">ID to remove:</label>
          <input type="text" id="ID" name="ID" required><br>
          <input type="submit">
      </fieldset>
      </form>
      </div></div>'  ;
    }


      
      
      
      
      $conn->close();

?>