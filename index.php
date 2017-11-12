<!--
  IT 440/540 Group Assignment
  Members:
      Yun Ha Seo
      Gabriel Smith
      Miles Winston
      Sushil Manandhar
  -->

<?php
$dbhandle = new mysqli('localhost','root','root','auto4you');
$dbhandle->connect_error;

$query = "SELECT MONTHNAME(Payment_Date) AS 'Month', Payment_Amount
FROM Payment";
$res = $dbhandle->query($query);

$branch_query = "SELECT * FROM Branch";
$branch_query_result = $dbhandle->query($branch_query);
$branch = mysqli_fetch_array($branch_query_result, MYSQLI_ASSOC);

?>

<html lang="en">
<head>
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
                <h1 class="branch-id">Branch ID: <?php echo $branch['Branch_Id'] ?></h1>
                <p class="branch-location small"><?php echo $branch['Branch_City'] . ", "  ?></p>
                <p class="branch-phone small"><?php echo $branch['Branch_Tel_Num'] ?></p>

                <p class="branch-total-revenue"></p>
                <div class="branch-performance"></div>

                <div class="infographics">
                    <div id="piechart_3d"></div>
                </div>
            </div>
            <div class="right-nav">

            </div>
        </div>
    </div>

</body>
</html>
