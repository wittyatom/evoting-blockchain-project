<?php
include("fusioncharts/fusioncharts.php");
$hostdb = "localhost";  // MySQl host
$userdb = "root";  // MySQL username
$passdb = "";  // MySQL password
$namedb = "Aadhar database";  // MySQL database name
$dbhandle = new mysqli($hostdb, $userdb, $passdb, $namedb);
$myString = $_POST['res'];

if ($dbhandle->connect_error) {
  exit("There was an error with your connection: ".$dbhandle->connect_error);
}
?>

<html>
   <head>
      <title>E-Voting Application using Blockchain</title>
        <script src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>
        <script src="http://static.fusioncharts.com/code/latest/fusioncharts.charts.js"></script>
        <script src="http://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.fint.js"></script>
   </head>
   <body>

<?php

  $strQuery = "SELECT DISTINCT Candidate, Party, Votes FROM Central_Ledger WHERE Constituency='$myString' ";
 	$result = $dbhandle->query($strQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
  if ($result) {

    $arrData = array(
                  "chart" => array(
                    "caption" => "Voting Scenario at the Moment",
                    "showValues" => "0",
                    "theme" => "zune"
                    )
                 );
        	// creating array for categories object

          $arrData["data"] = array();

  // Push the data into the array
          while($row = mysqli_fetch_array($result)) {
             array_push($arrData["data"], array(
                "label" => $row["Party"]."\n".$row["Candidate"],
                "value" => $row["Votes"]
                )
             );
          }

    	 $jsonEncodedData = json_encode($arrData);


			// chart object
      $columnChart = new FusionCharts("column2D", "myFirstChart" , 900, 500, "chart-1", "json", $jsonEncodedData);

            // Render the chart
            $columnChart->render();

            // Close the database connection
            $dbhandle->close();


   }

?>
<center>
<h1>E-Voting Results</h1>

 <div id="chart-1">Loading.....</div></center>
   </body>
</html>
