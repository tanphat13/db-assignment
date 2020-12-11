

<div class="container">
    <nav> 
        <ul class="navbar">
            <li class="header">Hospital</li>
            <li><a href="index.php?page=new_patient"> New Patient  </a></li>
            <li><a href="index.php?page=search"> Search Patient </a></li>
            <li><a href="index.php?page=search_report"> Search for Report </a></li>
            <li><a href="index.php?page=treated_by_doctor"> Treated by doctor </a></li>
            <?php if (!isset($_SESSION["user"])) {
                    echo '<li><a href="index.php?page=login" >Log in</a></li>';
                }
                else {
                    echo '<li><a href="index.php?page=logout_processing" >Log out</a></li>';
                }
               
            ?>
            
        </ul>
    </nav>
    <div class="section-container">
        