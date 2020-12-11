<?php 

if ($_SESSION == null) {
  echo "<h1>You have not login </h1>";
  echo "<a href=index.php?page=login>Click here to login</a>";
}
else {
  echo "<form class='search'>
  <input type='text' class='search-bar' placeholder='Search patient' id='search_value' onkeyup='showLiveResult(this.value)'>
  <input type='button' class='btn' value='Search' id='search_all' onclick='showAllResult()' />
  <div id='livesearch'></div>
  <div id='allsearch'></div>
  </form>";

}
?>
  <!-- <form class='search'>
  <input type="text" class='search-bar' placeholder="Search patient" id="search_value" onkeyup="showLiveResult(this.value)">
  <input type="button" class='btn' value="Search" id="search_all" onclick="showAllResult() " />
  <div id="livesearch"></div>
  <div id="allsearch"></div>
  </form> -->


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
  xmlhttp.open("GET","allsearch.php?search="+str,true);
  xmlhttp.send();
}

function showAllResult() {
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("allsearch").innerHTML=this.responseText;
      document.getElementById("allsearch").style.border="1px solid #A5ACB2";
    }
  }
  str = document.getElementById("search_value").value;
  xmlhttp.open("GET","show_patient_info.php?allsearch="+str,true);
  xmlhttp.send();
  document.getElementById("search_value").value = '';
  document.getElementById("livesearch").innerHTML = "";
}

var input = document.getElementById("search_value");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keydown", function(event) {
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    document.getElementById("search_all").click();
  }
});
</script>

