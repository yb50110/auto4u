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

    $branch_query = "SELECT * FROM Branch";
    $branch_query_result = $dbhandle->query($branch_query);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auto4You</title>

    <link rel="stylesheet" href="stylesheets/main.css">

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Scripts for pie chart-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        // Load Charts and the corechart package.
        google.charts.load('current', {'packages':['corechart']});
    </script>
</head>
<body>

    <div class="wrapper">
        <div class="sidebar">
            <div class="user-image" style="background-image: url('img/yunha.jpg');"></div>
        </div>

        <div class="main">
            <div class="body">
                <div class="branch-view-1 active">
                    <?php include 'branch1.php'; ?>
                </div>
                <div class="branch-view-2">
                    <?php include 'branch2.php'; ?>
                </div>
                <div class="branch-view-3">
                    <?php include 'branch3.php'; ?>
                </div>
                <div class="branch-view-4">
                    <?php include 'branch4.php'; ?>
                </div>
                <div class="branch-view-5">
                    <?php include 'branch5.php'; ?>
                </div>
                <div class="branch-view-6">
                    <?php include 'branch6.php'; ?>
                </div>
            </div>
            <div class="right-nav">
                <?php
                    if ($branch_query_result->num_rows > 0) {
                        while($row = $branch_query_result->fetch_assoc()) {
                            // create a tab and bind an onclick function that opens associated branch view
                            echo "<div class='nav-tab nav-view-" . $row["Branch_Id"] . "' onclick='showBranch(" . $row["Branch_Id"] . ")'>Branch ID: " . $row["Branch_Id"] . "<div class='nav-tab-status'></div></div>";
                        }
                    }
                ?>
                <div class="nav-tab new">+ Add another branch</div>
            </div>
        </div>
    </div>

</body>

<script>
    $(document).ready(function() {
        // on document load, add class active to the first active tab
        if (!$('.nav-view-1').hasClass('active')) {
            $('.nav-view-1').addClass('active')
        }

        // for demo purposes, remove some branch tabs
        $('.nav-view-3').hide();
        $('.nav-view-6').hide();
    });

    function showBranch(branchId) {
        // remove active from all branch views
        $('[class*="nav-view-').removeClass('active');
        $('[class*="branch-view-"]').removeClass('active');
        // add active only to the associated branch view
        $('.nav-view-' + branchId).addClass('active');
        $('.branch-view-' + branchId).addClass('active');
    }
</script>
</html>
