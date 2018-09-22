<?php
$host = "localhost";
$user = "root";
$pass = "";
$database = "Aadhar database";
$myString = $_POST['uname'];
$mysqli = new mysqli($host, $user, $pass, $database);

$query = "SELECT Phone,Postal FROM Aadhar_Details where Aadhar_Number=$myString";
$result = $mysqli->query($query);
while($row = $result->fetch_assoc()) {

    $phone = $row['Phone'];
    $postal= $row['Postal'];
  }

$mysqli->close();
$otp=rand(1000,9999);
$mysqli = new mysqli($host, $user, $pass, $database);

$query = "INSERT INTO Otp_Data VALUES ($phone,$otp)";
$query2 = "UPDATE Otp_Data SET Code=$otp WHERE Phone_Number=$phone";
$query3 = "SELECT * FROM Otp_Data where Phone_Number=$phone";
$result = $mysqli->query($query3);
$row = $result->fetch_assoc();
if (count($row)==0){
$mysqli->query($query);

}
else{
  $mysqli->query($query2);
}
//require_once "vendor/autoload.php";
//use Twilio\Rest\Client;
$phone = substr($phone, 3);
include('way2sms-api.php');
sendWay2SMS ( '9597632707' , 'harry123' , $phone , 'The otp is: '.$otp);

/*$account_sid = "ACc932f8fc2a4a12ae17c807b9052bc107";
$auth_token = "fe7d2517c176b14708268cff66b61665";
$twilio_phone_number = "+1 205-351-0645 ";

 $client = new Client($account_sid, $auth_token);
$client->messages->create(
   $phone,
    array(
        "from" => $twilio_phone_number,
        "body" => "the otp is ".$otp,
    )
 );*/


?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
    width: 40%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
    al
}

button {
    background-color: #438AE1;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 40%;
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


<center><h2>OTP AUTHENTICATION:</h2></center>

<form name="myform" method="post" action="level2.php">
  <div class="imgcontainer">
    <img src="otp.png" alt="Avatar" class="avatar">
  </div>

  <center><div class="container">
    <label for="uname"><b>Enter the OTP sent to registered mobile number: <?php echo $phone?></b></label><br>
    <input type="text" id="otp" placeholder="Enter OTP" name="otp" required><br>
    <input type="hidden" id="phone" value="<?php echo $phone?>" name="phone"><br>
    <input type="hidden" id="postal" value="<?php echo $postal?>" name="postal"><br>
<!--    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>
    -->
    <button type="submit" onclick="checkval()">Authenticate</button><br>

  </div><center>


</form>

</body>

</html>
