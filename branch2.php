<?php

    //Branch1 1st Query
    $branch2_query = "SELECT MONTHNAME(a.Payment_Date) AS 'Month', SUM(a.Payment_Amount) AS 'Total'
    FROM Payment AS a
    INNER JOIN invoice AS b 
    ON a.Invoice_Id = b.Invoice_Id
    INNER JOIN rental AS c 
    ON c.Rental_Id = b.Rental_Id
    INNER JOIN car AS d 
    ON d.Car_Id = c.Car_Id
    INNER JOIN branch AS e 
    ON e.Branch_Id = d.Branch_Id
    WHERE e.Branch_Id = 2
    GROUP BY MONTHNAME(Payment_Date)";
    $branch2_res = $dbhandle->query($branch2_query);

    //Chart 2 Query
    $branch2_query2 = "SELECT branch_city AS 'City', SUM(Payment_Amount) AS 'Revenue' 
    FROM branch AS a INNER JOIN car AS b 
    ON a.Branch_Id = b.Branch_Id INNER JOIN rental AS c ON b.Car_Id = c.Car_Id 
    INNER JOIN invoice AS d ON c.Rental_Id = d.Rental_Id 
    INNER JOIN payment AS e ON d.Invoice_Id = e.Invoice_Id 
    WHERE State_Id = 1
    OR a.Branch_Id = 2
    GROUP BY Branch_City";
    $branch2_res2 = $dbhandle->query($branch2_query2);

    //Chart 3 Query
    $branch2_query3 = "SELECT MONTHNAME(Rental_start_date_time) AS 'Month', COUNT(DISTINCT(f.Cus_Id)) AS 'Unique Visitors' 
    FROM branch AS a INNER JOIN car AS b ON a.Branch_Id = b.Branch_Id 
    INNER JOIN rental AS c ON b.Car_Id = c.Car_Id 
    INNER JOIN invoice AS d ON c.Rental_Id = d.Rental_Id 
    INNER JOIN payment AS e ON d.Invoice_Id = e.Invoice_Id 
    INNER JOIN customer AS f ON f.Cus_Id = d.Cus_Id 
    WHERE a.Branch_Id = 2
    GROUP BY Month";
    $branch2_res3 = $dbhandle->query($branch2_query3);

    //Chart 4 Query
    $branch2_query4 = "SELECT MONTHNAME(Rental_Actual_End_Date_Time) AS 'Month', Count(d.Car_Id) AS 'Count' 
    FROM Payment AS a
    INNER JOIN invoice AS b 
    ON a.Invoice_Id = b.Invoice_Id
    INNER JOIN rental AS c 
    ON c.Rental_Id = b.Rental_Id
    INNER JOIN car AS d 
    ON d.Car_Id = c.Car_Id
    INNER JOIN branch AS e 
    ON e.Branch_Id = d.Branch_Id
    WHERE e.Branch_Id = 2
    GROUP BY Month";
    $branch2_res4 = $dbhandle->query($branch2_query4);

    $branch2_info_query = "SELECT * FROM Branch JOIN State ON State.State_Id = Branch.State_Id HAVING Branch.Branch_Id = 2";
    $branch2_info_query_result = $dbhandle->query($branch2_info_query);
    $branch2 = mysqli_fetch_array($branch2_info_query_result, MYSQLI_ASSOC);

    $branch2_totalRev_query = "
            SELECT SUM(i.Invoice_Total_Cost) AS 'TotalRev'
           FROM Invoice AS i
           JOIN Rental AS r ON i.Rental_ID = r.Rental_Id
           JOIN Car AS c on r.Car_Id = c.Car_Id
           JOIN Branch AS b on c.Branch_Id = b.Branch_Id
           GROUP BY b.Branch_Id
           HAVING b.Branch_Id = 2
            ";
    $branch2_totalRev_query_result = $dbhandle->query($branch2_totalRev_query);
    $branch2_totalRev = mysqli_fetch_array($branch2_totalRev_query_result, MYSQLI_ASSOC)

?>

<script type="text/javascript">

    // Load Charts and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Draw Chart 1
    google.charts.setOnLoadCallback(branchChart21);

    // Draw Chart 2
    google.charts.setOnLoadCallback(branchChart22);

    // Draw Chart 3
    google.charts.setOnLoadCallback(branchChart23);

    // Draw Chart 4
    google.charts.setOnLoadCallback(branchChart24);

    /**Chart 1**/

    // Callback that draws the pie chart for Sarah's pizza.
    function branchChart21() {

        // Create the data table for Sarah's pizza.

        var data = google.visualization.arrayToDataTable([
            ['Month', 'Total'],


            <?php
            while($row=$branch2_res->fetch_assoc()){

                echo "['".$row['Month']."',".$row['Total']."],";
            }
            ?>
        ]);

        var options = {
            title: 'Total earning Over Time (Months)',
            is3D: true,
            width:543,
            height:298,
            legend: { position: "none" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('branchChart21'));
        chart.draw(data, options);
    }

    /**Chart 2**/

    // Callback that draws the pie chart for Anthony's pizza.
    function branchChart22() {

        // Create the data table for Anthony's pizza.
        var data = google.visualization.arrayToDataTable([
            ['City', 'Revenue'],

            <?php
            while($row=$branch2_res2->fetch_assoc()){

                echo "['".$row['City']."',".$row['Revenue']."],";
            }
            ?>
        ]);

        // Set options for Anthony's pie chart.
        var options = {
            title:'Total earning compared to nearby branches (same city or state)',
            is3D: true,
            width:543,
            height:298,
            legend: { position: "none" },
        };

        // Instantiate and draw the chart for Anthony's pizza.
        var chart = new google.visualization.ColumnChart(document.getElementById('branchChart22'));
        chart.draw(data, options);
    }

    /**Chart 3 **/

    function branchChart23() {

        // Create the data table for Sarah's pizza.

        var data = google.visualization.arrayToDataTable([
            ['Month', 'Unique Visitors'],

            <?php
            while($row=$branch2_res3->fetch_assoc()){

                echo "['".$row['Month']."',".$row['Unique Visitors']."],";
            }
            ?>
        ]);

        var options = {
            title: 'Number of unique customers over time',
            is3D: true,
            width:543,
            height:298,
            legend: { position: "none" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('branchChart23'));
        chart.draw(data, options);
    }

    /**Chart 4 **/

    function branchChart24() {

        // Create the data table for Sarah's pizza.

        var data = google.visualization.arrayToDataTable([
            ['Month', 'Count'],

            <?php
            while($row=$branch2_res4->fetch_assoc()){

                echo "['".$row['Month']."',".$row['Count']."],";
            }
            ?>
        ]);

        var options = {
            title: 'Car rentals per month',
            is3D: true,
            width:543,
            height:298,
            legend: { position: "none" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('branchChart24'));
        chart.draw(data, options);
    }

</script>

<div class="branch-container">
    <h1 class="branch-id">Branch ID: <?php echo $branch2['Branch_Id'] ?></h1>
    <p class="branch-location small"><?php echo $branch2['Branch_City'] . ", " . $branch2['State_Name'] ?></p>
    <p class="branch-phone small">
        <?php echo "(" . substr($branch2['Branch_Tel_Num'], 0, 3) . ") " .
            substr($branch2['Branch_Tel_Num'], 3, 3) . " - " .
            substr($branch2['Branch_Tel_Num'], 6) ?>
    </p>
    <div class="clearfix"></div>

    <div class="branch-2 branch-performance"></div>
    <p class="branch-total-revenue">Total Revenue: $<?php echo $branch2_totalRev['TotalRev'] ?></p>

    <div class="infographics">
        <div id="branchChart21" style="border: 1px solid #ccc; display: inline-block"></div>
        <div id="branchChart22" style="border: 1px solid #ccc; display: inline-block"></div>
        <div id="branchChart23" style="border: 1px solid #ccc; display: inline-block"></div>
        <div id="branchChart24" style="border: 1px solid #ccc; display: inline-block"></div>
    </div>
</div>

<script>
    /**
     * sets the status of associated tab
     */
    $(document).ready(function() {
        var totalRev = <?php echo $branch2_totalRev['TotalRev'] ?>;

        if (totalRev >= 1900) {
            $('.nav-view-2 .nav-tab-status').addClass('good');
            $('.branch-2.branch-performance').addClass('good');
        } else if (totalRev >= 500 && totalRev < 1900) {
            $('.nav-view-2 .nav-tab-status').addClass('fair');
            $('.branch-2.branch-performance').addClass('fair');
        } else {
            $('.nav-view-2 .nav-tab-status').addClass('bad');
            $('.branch-2.branch-performance').addClass('bad');
        }
    });
</script>


