<?php
    // chart 1
    $branch1_query = "SELECT MONTHNAME(a.Payment_Date) AS 'Month', SUM(a.Payment_Amount) AS 'Total'
        FROM Payment AS a
        INNER JOIN invoice AS b 
        ON a.Invoice_Id = b.Invoice_Id
        INNER JOIN rental AS c 
        ON c.Rental_Id = b.Rental_Id
        INNER JOIN car AS d 
        ON d.Car_Id = c.Car_Id
        INNER JOIN branch AS e 
        ON e.Branch_Id = d.Branch_Id
        WHERE e.Branch_Id = 1
        GROUP BY MONTHNAME(Payment_Date)";
    $branch1_res = $dbhandle->query($branch1_query);

    //Chart 2 Query
    $branch1_query2 = "SELECT branch_city AS 'City', SUM(Payment_Amount) AS 'Revenue' 
        FROM branch AS a INNER JOIN car AS b 
        ON a.Branch_Id = b.Branch_Id INNER JOIN rental AS c ON b.Car_Id = c.Car_Id 
        INNER JOIN invoice AS d ON c.Rental_Id = d.Rental_Id 
        INNER JOIN payment AS e ON d.Invoice_Id = e.Invoice_Id 
        WHERE State_Id = 1
        OR a.Branch_Id = 1
        GROUP BY Branch_City";
    $branch1_res2 = $dbhandle->query($branch1_query2);

    //Chart 3 Query
    $branch1_query3 = "SELECT MONTHNAME(Rental_start_date_time) AS 'Month', COUNT(DISTINCT(f.Cus_Id)) AS 'Unique Visitors' 
        FROM branch AS a INNER JOIN car AS b ON a.Branch_Id = b.Branch_Id 
        INNER JOIN rental AS c ON b.Car_Id = c.Car_Id 
        INNER JOIN invoice AS d ON c.Rental_Id = d.Rental_Id 
        INNER JOIN payment AS e ON d.Invoice_Id = e.Invoice_Id 
        INNER JOIN customer AS f ON f.Cus_Id = d.Cus_Id 
        WHERE a.Branch_Id = 1
        GROUP BY Month";
    $branch1_res3 = $dbhandle->query($branch1_query3);

    //Chart 4 Query
    $branch1_query4 = "SELECT MONTHNAME(Rental_Actual_End_Date_Time) AS 'Month', Count(d.Car_Id) AS 'Count' 
        FROM Payment AS a
        INNER JOIN invoice AS b 
        ON a.Invoice_Id = b.Invoice_Id
        INNER JOIN rental AS c 
        ON c.Rental_Id = b.Rental_Id
        INNER JOIN car AS d 
        ON d.Car_Id = c.Car_Id
        INNER JOIN branch AS e 
        ON e.Branch_Id = d.Branch_Id
        WHERE e.Branch_Id = 1
        GROUP BY Month";
    $branch1_res4 = $dbhandle->query($branch1_query4);

    // General information
    $branch1_info_query = "SELECT * FROM Branch JOIN State ON State.State_Id = Branch.State_Id HAVING Branch.Branch_Id = 1";
    $branch1_info_query_result = $dbhandle->query($branch1_info_query);
    $branch1 = mysqli_fetch_array($branch1_info_query_result, MYSQLI_ASSOC);

    $branch1_totalRev_query = "
        SELECT SUM(i.Invoice_Total_Cost) AS 'TotalRev'
       FROM Invoice AS i
       JOIN Rental AS r ON i.Rental_ID = r.Rental_Id
       JOIN Car AS c on r.Car_Id = c.Car_Id
       JOIN Branch AS b on c.Branch_Id = b.Branch_Id
       GROUP BY b.Branch_Id
       HAVING b.Branch_Id = 1
        ";
    $branch1_totalRev_query_result = $dbhandle->query($branch1_totalRev_query);
    $branch1_totalRev = mysqli_fetch_array($branch1_totalRev_query_result, MYSQLI_ASSOC)
?>

<script>
    // Draw Chart 1
    google.charts.setOnLoadCallback(branch1_drawChart);
    // Draw Chart 2
    google.charts.setOnLoadCallback(branch1_drawChart2);
    // Draw Chart 3
    google.charts.setOnLoadCallback(branch1_drawChart3);
    // Draw Chart 4
    google.charts.setOnLoadCallback(branch1_drawChart4);

    /**Chart 1**/
    function branch1_drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'Total'],


            <?php
            while($row=$branch1_res->fetch_assoc()){

                echo "['".$row['Month']."',".$row['Total']."],";
            }
            ?>
        ]);

        var options = {
            title: 'Total earning Over Time (Months)',
            is3D: true,
            legend: { position: "none" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('branch1_drawChart'));
        chart.draw(data, options);
    }

    /**Chart 2**/
    function branch1_drawChart2() {

        // Create the data table for Anthony's pizza.
        var data = google.visualization.arrayToDataTable([
            ['City', 'Revenue'],

            <?php
            while($row=$branch1_res2->fetch_assoc()){

                echo "['".$row['City']."',".$row['Revenue']."],";
            }
            ?>
        ]);

        // Set options for Anthony's pie chart.
        var options = {
            title:'Total earning compared to nearby branches',
            is3D: true,
            legend: { position: "none" },
        };

        // Instantiate and draw the chart for Anthony's pizza.
        var chart = new google.visualization.ColumnChart(document.getElementById('branch1_drawChart2'));
        chart.draw(data, options);
    }

    /**Chart 3 **/
    function branch1_drawChart3() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'Unique Visitors'],

            <?php
            while($row=$branch1_res3->fetch_assoc()){

                echo "['".$row['Month']."',".$row['Unique Visitors']."],";
            }
            ?>
        ]);

        var options = {
            title: 'Number of unique customers over time',
            is3D: true,
            legend: { position: "none" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('branch1_drawChart3'));
        chart.draw(data, options);
    }

    /**Chart 4 **/
    function branch1_drawChart4() {
        var data = google.visualization.arrayToDataTable([
            ['Month', 'Count'],

            <?php
            while($row=$branch1_res4->fetch_assoc()){

                echo "['".$row['Month']."',".$row['Count']."],";
            }
            ?>
        ]);

        var options = {
            title: 'Car rentals per month',
            is3D: true,
            legend: { position: "none" },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('branch1_drawChart4'));
        chart.draw(data, options);
    }

</script>

<div class="branch-container">
    <h1 class="branch-id">Branch ID: <?php echo $branch1['Branch_Id'] ?></h1>
    <p class="branch-location small"><?php echo $branch1['Branch_City'] . ", " . $branch1['State_Name'] ?></p>
    <p class="branch-phone small">
        <?php echo "(" . substr($branch1['Branch_Tel_Num'], 0, 3) . ") " .
            substr($branch1['Branch_Tel_Num'], 3, 3) . " - " .
            substr($branch1['Branch_Tel_Num'], 6) ?>
    </p>
    <div class="clearfix"></div>

    <div class="branch-1 branch-performance"></div>
    <p class="branch-total-revenue">Total Revenue: $<?php echo $branch1_totalRev['TotalRev'] ?></p>

    <div class="infographics">
        <div id="branch1_drawChart" style="border: 1px solid #ccc; display: inline-block"></div>
        <div id="branch1_drawChart2" style="border: 1px solid #ccc; display: inline-block"></div>
        <div id="branch1_drawChart3" style="border: 1px solid #ccc; display: inline-block"></div>
        <div id="branch1_drawChart4" style="border: 1px solid #ccc; display: inline-block"></div>
    </div>
</div>

<script>
    /**
     * sets the status of associated tab
     */
    $(document).ready(function() {
        var totalRev = <?php echo $branch1_totalRev['TotalRev'] ?>;

        if (totalRev >= 1900) {
            $('.nav-view-1 .nav-tab-status').addClass('good');
            $('.branch-1.branch-performance').addClass('good');
        } else if (totalRev >= 500 && totalRev < 1900) {
            $('.nav-view-1 .nav-tab-status').addClass('fair');
            $('.branch-1.branch-performance').addClass('fair');
        } else {
            $('.nav-view-1 .nav-tab-status').addClass('bad');
            $('.branch-1.branch-performance').addClass('bad');
        }
    });
</script>