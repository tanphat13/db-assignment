<?php
    if ($_SESSION == null) {
        echo "<h1>You have not login </h1>";
        echo "<a href=index.php?page=login>Click here to login</a>";
      }
    else {
    echo'<form class="search">
        <input type="text" class="search-bar" placeholder="Search Patient" id="search_value" onkeyup="showLiveResult(this.value)">
        <input type="button" class="btn" value="Search" id="search_all" onclick="showAllResult() " />
        <div id="livesearch"></div>
    </form>';
    } 



?>
<script>
    function showLiveResult(str) {
    let username = "<?php echo $_SESSION["username"] ?>";
    let password = "<?php echo $_SESSION["username"] ?>"; 
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
    xmlhttp.open("GET","report_livesearch.php?username="+username+"&password="+password+"&search="+str,true);
    xmlhttp.send();
    }
</script>