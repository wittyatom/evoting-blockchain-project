<?php

$myString = $_POST['aadhar'];

include 'otppage.html';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Aadhar database";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT Phone FROM Aadhar_Details where Aadhar_Number=$myString";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Phone Number" . $row["Phone"];
    }
} else {
    echo "No details";
}
$conn->close();



?>
