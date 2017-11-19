<?php
    // chart 1
    $query = "SELECT MONTHNAME(Payment_Date) AS 'Month', Payment_Amount FROM Payment";
    $res = $dbhandle->query($query);

    $single_branch_query = "SELECT * FROM Branch WHERE Branch_Id = 1";
    $single_branch_query_result = $dbhandle->query($branch_query);
    $single_branch = mysqli_fetch_array($single_branch_query_result, MYSQLI_ASSOC);

//    todo
    $branch1_totalRev = 100000;
//?>

<script>
    // Load Charts and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Draw Chart 1
    google.charts.setOnLoadCallback(drawChart);

    /**Chart 1**/
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'Payment Amount'],


            <?php
            while($row=$res->fetch_assoc()){

                echo "['".$row['Month']."',".$row['Payment_Amount']."],";
            }
            ?>
        ]);

        var options = {
            title: 'Total Revenue Over Time (Months)',
            is3D: true,
            width:400,
            height:300,
            legend: { position: "none" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('drawChart'));
        chart.draw(data, options);
    }
</script>

<div class="branch-container">
    <h1 class="branch-id">Branch ID: <?php echo $single_branch['Branch_Id'] ?></h1>
    <p class="branch-location small"><?php echo $single_branch['Branch_City'] . ", " . $single_branch['State_Id'] ?></p>
    <p class="branch-phone small"><?php echo $single_branch['Branch_Tel_Num'] ?></p>
    <div class="clearfix"></div>

    <div class="branch-performance"></div>
    <p class="branch-total-revenue">$100,000</p>

    <div class="infographics">
        <div class="rows">
            <div id="row1">
                <div id="drawChart" style="border: 1px solid #ccc; display: inline-block"></div>
<!--                <div id="drawChart2" style="border: 1px solid #ccc; display: inline-block"></div>-->
            </div>
            <div id="row2">
<!--                <div id="drawChart3" style="border: 1px solid #ccc; display: inline-block"></div>-->
<!--                <div id="drawChart4" style="border: 1px solid #ccc; display: inline-block"></div>-->
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * sets the status of associated tab
     */
    $(document).ready(function() {
        var totalRev = <?php echo $branch1_totalRev ?>

        if (totalRev >= 300000) {
            $('.nav-view-1 .nav-tab-status').addClass('good');
            $('.branch-performance').addClass('good');
        } else if (totalRev >= 100000 && totalRev < 300000) {
            $('.nav-view-1 .nav-tab-status').addClass('fair');
            $('.branch-performance').addClass('fair');
        } else {
            $('.nav-view-1 .nav-tab-status').addClass('bad');
            $('.branch-performance').addClass('bad');
        }
    });
</script>