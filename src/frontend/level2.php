<?php
$host = "localhost";
$user = "root";
$pass = "";
$database = "Aadhar database";
$gotp = $_POST['otp'];
$phone = $_POST['phone'];
$phone ='91'.$phone;
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
$query3 = "SELECT Public_key,Private_key FROM Aadhar_Details where Postal=$postal";
$result3 = $mysqli->query($query3);
$row2 = $result2->fetch_assoc();
$cons = $row2['Constituency'];
$row3 = $result3->fetch_assoc();
$public = $row3['Public_key'];
$private = $row3['Private_key'];
?>
  <html>
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
  /* The container */

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

  <form name="myform" method="post" action="thankyou.php" onsubmit="return request();">
  <div class="imgcontainer">
    <img src="Vote.jpg" alt="Avatar" class="avatar">
  </div>

  <center><div class="container">
    <label for="uname"><b>Select the Candidate of your choice:  <?php echo "[Constituency: ".$cons."]"?></b></label><br><br>

      <?php
      while($row = $result->fetch_assoc()) {
?>




     <input type="radio"  name="vote" value="<?php echo $row['Candidate_Name']." ".$row['Party'] ?>" ><?php echo $row['Candidate_Name']." ".$row['Party'] ?><br>
     <input type="hidden" id="cons" name="cons" value="<?php echo $row['Constituency'] ?>">


<?php
}
?>
  <!--    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>
    -->
    <button type="submit">Vote</button><br>

  </div><center>


  </form>
  <script language="JavaScript" type="text/javascript" src="jsrsasign-all-min.js"></script>
  <script language="JavaScript" type="text/javascript">
  function request(){
    var msg = document.querySelector('input[name="vote"]:checked').value;
    var cons = document.querySelector('input[name="cons"]').value;


  	var x=securegenkey(msg,cons);
      var sen = "bnm="+x;
       var xmlhttp = new XMLHttpRequest();


      	xmlhttp.open("POST","http://172.20.10.4:23451/voting",true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(sen);
            return true


  }


  //Generating Keys Using ECDSA

  //Curve secp256r1 , secp256k1 , secp384r1
  function securegenkey(msg,cons)
  {
    var curve = "secp256k1";
    var ec = new KJUR.crypto.ECDSA({"curve": curve});

    //Generating Keypair
    var keypair = ec.generateKeyPairHex();

    //Generating Private Key
    var private_key = "<?php echo $private?>";

    //Generating Public Key
    var public_key = "<?php echo $public?>";
    console.log(private_key);

    //Printing Result in Console



    //Signature Algorithm SHA256withECDSA, SHA1withECDSA
    var signature_algo = "SHA256withECDSA";

    //Message for encrypting

    var party= msg.split(" ").splice(-1);
    var lastIndex = msg.lastIndexOf(" ");
    var cand= msg.substring(0, lastIndex);
    var message = 'Candidate:'+cand+',Party:'+party+',Constituency:'+cons;
    console.log(message);

    //Generating Signature
    var sig = new KJUR.crypto.Signature({"alg": signature_algo});
    sig.init({d: private_key, curve: curve});
    sig.updateString(message);
    var sigValueHex = sig.sign();

    sigValueHex=sigValueHex.slice(6);
    //document.write("Signature : "+sigValueHex+"<br>");
    var bytesforR=sigValueHex.slice(0,2);
    //document.write("bytesforR"+bytesforR);
    var yourNumber = parseInt(bytesforR,16);
    //document.write("converted"+yourNumber);
    var x=2+(yourNumber*2);
    var check1 =sigValueHex.slice(2,4);
    var r;
    //document.write(check1);
    if(check1=="00")
    {
    	//document.write("hi i am in the if");
    	 r=sigValueHex.slice(4,x);
    }
    else
    {
    	//document.write("hi i am in the if");
    	 r =sigValueHex.slice(2,x);
    }


    var bytesforS=sigValueHex.slice(x+2,x+4);
    //document.write("\nvalue of second"+bytesforS);
    var yourNumber1 = parseInt(bytesforS,16);
    //document.write("yourNumber1"+yourNumber1);
    var y=2+(yourNumber1*2);
    //var s=sigValueHex.slice(x+4,x+y+5);
    var check2 =sigValueHex.slice(x+4,x+6);
    if(check2=="00")
    {
    	var s =sigValueHex.slice(x+6,x+y+5);
    }
    else
    {
    	var s =sigValueHex.slice(x+4,x+y+6);
    }

    var finalsign=r+s;
    var json='{"public_key":"'+public_key+'","signature":"'+finalsign+'", "Candidate":"'+cand+'","Party":"'+party+'","Constituency":"'+cons+'","Message":"'+message+'"}';
    console.log(json);
    return json
  }

  </script>
  </body>

  </html>

<?php
}
else{
  echo "ERROR IN OTP";
}
?>
