<?php
$dbhandle = new mysqli('localhost','root','root','auto4you');
$dbhandle->connect_error;

$query = "SELECT MONTHNAME(Payment_Date) AS 'Month', Payment_Amount
FROM Payment";
$res = $dbhandle->query($query);

?>

<html lang="en">
<head>
    <!--
    IT 440/540 Group Assignment
    Members:
        Yun Ha Seo
        Gabriel Smith
        Miles Winston
        Sushil Manandhar
    -->

    <meta charset="UTF-8">
    <title>Auto4You</title>

    <link rel="stylesheet" href="stylesheets/main.css">

    <!-- Scripts for pie chart-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Month', 'Payment_Amount'],

                <?php
                while($row=$res->fetch_assoc()){

                    echo "['".$row['Month']."',".$row['Payment_Amount']."],";
                }
                ?>
            ]);

            var options = {
                title: 'Total Revenue Over Time',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>

    <div class="wrapper">
        <div class="sidebar">
            <div class="user-image" style="background-image: url('img/yunha.jpg');"></div>
        </div>

        <div class="main">


            <div class="body">
                <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
            </div>
            <div class="right-nav">

            </div>
        </div>
    </div>

</body>
</html>
