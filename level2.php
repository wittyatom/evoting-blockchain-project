<?php
$host = "localhost";
$user = "root";
$pass = "";
$database = "Aadhar database";
$gotp = $_POST['otp'];
$phone = $_POST['phone'];
$postal = $_POST['postal'];

$mysqli = new mysqli($host, $user, $pass, $database);

$query = "SELECT Code FROM Otp_Data where Phone_Number=$phone";
$result = $mysqli->query($query);
while($row = $result->fetch_assoc()) {

    $otp = $row['Code'];

  }

$mysqli->close();
if($gotp==$otp){
$mysqli = new mysqli($host, $user, $pass, $database);
$query2 = "SELECT * FROM Candidate_Database where Postal_Code=$postal";
$result = $mysqli->query($query2);
$query1 = "SELECT Constituency FROM Candidate_Database where Postal_Code=$postal";
$result2 = $mysqli->query($query1);
$row2 = $result2->fetch_assoc();
$cons = $row2['Constituency'];
?>
  <html>
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
  /* The container */
.container {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 22px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Hide the browser's default radio button */
.container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Create a custom radio button */
.checkmark {
    position: absolute;
    top: 0;
    left: 415px;
    height: 25px;
    width: 25px;
    background-color: #eee;
    border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
    background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.container input:checked ~ .checkmark {
    background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the indicator (dot/circle) when checked */
.container input:checked ~ .checkmark:after {
    display: block;
}

/* Style the indicator (dot/circle) */
.container .checkmark:after {
 	top: 9px;
	left: 9px;
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background: white;
}
  body {font-family: Arial, Helvetica, sans-serif;}
  form {border: 3px solid #f1f1f1;}

  input[type=text], input[type=password]{
      width: 40%;
      padding: 12px 20px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #ccc;
      box-sizing: border-box;

  }

  button {
      background-color: #438AE1;
      color: white;
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      cursor: pointer;
      width: 30%;
  }

  button:hover {
      opacity: 0.8;
  }

  .cancelbtn {
      width: auto;
      padding: 10px 18px;
      background-color: #f44336;
  }

  .imgcontainer {
      text-align: center;
      margin: 24px 0 12px 0;
  }

  img.avatar {
      width: 20%;
      border-radius: 40%;
  }

  .container {
      padding: 10px;
  }

  span.psw {
      float: right;
      padding-top: 16px;
  }

  /* Change styles for span and cancel button on extra small screens */
  @media screen and (max-width: 300px) {
      span.psw {
         display: block;
         float: none;
      }
      .cancelbtn {
         width: 100%;
      }
  }
  </style>
  </head>
  <body>




  <center><h2>Select Your Candidate:</h2></center>

  <form name="myform" method="post" >
  <div class="imgcontainer">
    <img src="Vote.jpg" alt="Avatar" class="avatar">
  </div>

  <center><div class="container">
    <label for="uname"><b>Select the Candidate of your choice:  <?php echo "[Constituency: ".$cons."]"?></b></label><br><br>

      <?php
      while($row = $result->fetch_assoc()) {
?>


<label class="container"><?php echo "Candidate: ".$row['Candidate_Name']." (".$row['Party'].")" ?>

     <input type="radio" id="vote"  name="vote" value="<?php echo $row['Candidate_Name']." ".$row['Party'] ?>" ><br>
     <span class="checkmark"></span>
</label>
<?php
}
?>
  <!--    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>
    -->
    <button type="submit" onclick="checkval()">Vote</button><br>

  </div><center>


  </form>

  </body>

  </html>

<?php
}
else{
  echo "ERROR IN OTP";
}
?>
