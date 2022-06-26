<?php
     include "includes/header.php";
     include "includes/sidebar.php";
     include "includes/footer.php";
?>
<html>
    <body style="background-color: white;" onload="setDataOnSelection();">
            <!--========== CONTENTS ==========-->
    <main>
        <section>
                <div class="mis-logo">
                    <img src="./img/misoutlogo.png" alt="">
                    <h3 class="mis-title" style="color:white;">ALWAYS THE FASTEST</h3>
                </div>
                <div class="title-wrapper">
                  <br>
                <h1 style="text-align:center; color:black;">Annual Report</h1>
                <br>
            </div>
            
            <div class="dropdown">
                <form action="../includes/dbprocess.php" Method="POST">
                    <div id="Quarterly" class="filter-wrapper">
                        <span class="custom-dropdown big">
                        <?php
                    $query = "SELECT DISTINCT YEAR(cbe_date) as year FROM `tblcashbookentry`";    
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->get_result();
                ?>   
                            <select id="year_run" name="year" style="background-color:#1B191B;">    
                               <option value="" disabled selected>YEAR</option>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <option value="<?= $row['year']; ?>"><?= $row['year']; ?></option>
                    <?php } ?>  
                            </select>
                        </span>            
                        
                        <div class="input-container cta" >
                            <button class="signup-btn continue" type="submit" name="run_dashboard_report" style="background-color:#1B191B;">RUN REPORT</button>
                        </div>
                      
                    </div>

                </form>
            </div>
            <br><br>

            <script>
             function setDataOnSelection(){
                            $('#year_run').val("<?php echo $_SESSION['year_is']?>");
                        }

                    </script>

            <!---GRAPH-->
         
            
                <div class="card1">
                  <div id="piechart" class="" style="width: 600px; height: 400px;"></div>
                </div>
                <br>
                  <h3 style="text-align: center">INCOME STATEMENT FOR THE YEAR <?php echo $_SESSION['year_is']?>  </h3>
                <br><br> 
               
                <div class="card1">   
                  <div id="donutchart" class="" style="width: 600px; height: 400px;"></div>
                </div>
                <br>
                <h3 style="text-align: center">CASH FLOW FOR THE YEAR <?php echo $_SESSION['year_is']?>  </h3>
                <br><br> 
                                  
                <div class="card1">  
                  <div id="columnchart_material" class="" style="width: 500px; height: 300px;"></div>
                </div>
                <br>
                <h3 style="text-align: center">INCOME STATEMENT FOR THE YEAR <?php echo $_SESSION['year_is']?>  </h3>
                <br><br> 

                <div class="card1">
                  <div id="columnchart_values" class="" style="width: 600px; height: 300px;"></div>
                </div>
                <br>
                <h3 style="text-align: center">CASH FLOW FOR THE YEAR <?php echo $_SESSION['year_is']?>  </h3>
                <br><br> 
           


            <!----------------->

            <?php

    $year =  $_SESSION ['year_is'];
    $income = array();
    $expense = array();
    $monthavailable = array();
    $total_income = 0;
    $total_expenses = 0;

          for ($i=0; $i < 13; $i++) { 
            # code...
            $income[$i] = 0;
            $expense[$i] = 0;
          }



          $query = "SELECT DISTINCT is_month FROM `tblincomestatement` WHERE `is_type` = 'Monthly' AND `is_year` = '$year' AND (`is_category` = 'INCOME' OR `is_category` = 'EXPENSES')";    
          $stmt = $conn->prepare($query);
          $stmt-> execute();
          $result = $stmt->get_result();  
          
          $a=0;
          while ($row = $result->fetch_assoc()) {
              $monthavailable[$a] = $row['is_month'];
              $a++;
          }

          $total_month =  count($monthavailable);


    for ($i=0; $i < $total_month ; $i++) {  
        # code...

        $query = "SELECT  SUM(is_amount) AS total_amount FROM `tblincomestatement` WHERE `is_month` = '$monthavailable[$i]' AND `is_year` = '$year' AND `is_category` = 'INCOME'";    
        $stmt = $conn->prepare($query);
        $stmt-> execute();
        $result = $stmt->get_result();  
    
        while ($row = $result->fetch_assoc()) {
            $income[$monthavailable[$i]] = $row['total_amount'];
        }


        $query = "SELECT  SUM(is_amount) AS total_amount FROM `tblincomestatement` WHERE `is_month` = '$monthavailable[$i]' AND `is_year` = '$year' AND `is_category` = 'EXPENSES'";    
        $stmt = $conn->prepare($query);
        $stmt-> execute();
        $result = $stmt->get_result();  
    
        while ($row = $result->fetch_assoc()) {
            $expense[$monthavailable[$i]] = $row['total_amount'];
        }
    }

    for ($i=1; $i < 13 ; $i++) { 
      # code...
      $total_income = $total_income + $income[$i];
      $total_expenses = $total_expenses + $expense[$i];
    }




          $lastmoney = array ();         
          $lastcolor = array ();     
          
          for ($i=0; $i < 13; $i++) { 
            # code...
            $lastmoney[$i] = 0;
            $lastcolor[$i] = "";
          }


          $f_neg = 0;
          $f_pos = 0;

          $o_neg = 0;
          $o_pos = 0;

          $i_neg = 0;
          $i_pos = 0;


    for ($i=1; $i <= 12 ; $i++) { 
      # code...
                    //financing
                    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$i' AND `cf_date_year` = '$year' AND `cf_category` = 'FINANCING' AND `cf_sign` = 'negative'";    
                    $stmt = $conn->prepare($query);
                    $stmt-> execute();
                    $result = $stmt->get_result();  

                  
                    while ($row = $result->fetch_assoc()) {
                        $f_neg = $row['total_amount'];
                    }

                    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$i' AND `cf_date_year` = '$year' AND `cf_category` = 'FINANCING' AND `cf_sign` = 'positive'";    
                    $stmt = $conn->prepare($query);
                    $stmt-> execute();
                    $result = $stmt->get_result();  

                    while ($row = $result->fetch_assoc()) {
                      $f_pos = $row['total_amount'];
                    }

                    $totalFIO = $f_pos - $f_neg;

                    //operating
                    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$i' AND `cf_date_year` = '$year' AND `cf_category` = 'OPERATING' AND `cf_sign` = 'negative'";    
                    $stmt = $conn->prepare($query);
                    $stmt-> execute();
                    $result = $stmt->get_result();  

                  
                    while ($row = $result->fetch_assoc()) {
                        $o_neg = $row['total_amount'];
                    }

                    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$i' AND `cf_date_year` = '$year' AND `cf_category` = 'OPERATING' AND `cf_sign` = 'positive'";    
                    $stmt = $conn->prepare($query);
                    $stmt-> execute();
                    $result = $stmt->get_result();  

                    while ($row = $result->fetch_assoc()) {
                      $o_pos = $row['total_amount'];
                    }

                    $totalOPO = $o_pos - $o_neg;


                    //investing
                    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$i' AND `cf_date_year` = '$year' AND `cf_category` = 'INVESTING' AND `cf_sign` = 'negative'";    
                    $stmt = $conn->prepare($query);
                    $stmt-> execute();
                    $result = $stmt->get_result();  

                  
                    while ($row = $result->fetch_assoc()) {
                        $i_neg = $row['total_amount'];
                    }

                    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$i' AND `cf_date_year` = '$year' AND `cf_category` = 'INVESTING' AND `cf_sign` = 'positive'";    
                    $stmt = $conn->prepare($query);
                    $stmt-> execute();
                    $result = $stmt->get_result();  

                    while ($row = $result->fetch_assoc()) {
                      $i_pos = $row['total_amount'];
                    }


                    $totalINO = $i_pos - $i_neg;

                    $lastmoney[$i] = $totalFIO + $totalINO + $totalOPO;

                    if($lastmoney[$i] > 0){
                      $lastcolor[$i] = "#17b978";
                    }else{
                      $lastcolor[$i] = "#F25E5E"; 
                    }


    }

    $financing = 0;
    $investing = 0;
    $operating = 0;


    //financing
    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$year' AND `cf_date_year` = '$year' AND `cf_category` = 'FINANCING' AND `cf_sign` = 'negative'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

  
    while ($row = $result->fetch_assoc()) {
        $f_neg = $row['total_amount'];
    }

    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$year' AND `cf_date_year` = '$year'  AND `cf_category` = 'FINANCING' AND `cf_sign` = 'positive'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

    while ($row = $result->fetch_assoc()) {
      $f_pos = $row['total_amount'];
    }

    $financing = $f_pos - $f_neg;
    if($financing < 0){
      $financing = substr($financing,1);
    }

    //operating
    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$year' AND `cf_date_year` = '$year' AND `cf_category` = 'OPERATING' AND `cf_sign` = 'negative'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

  
    while ($row = $result->fetch_assoc()) {
        $o_neg = $row['total_amount'];
    }

    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$year' AND `cf_date_year` = '$year' AND `cf_category` = 'OPERATING' AND `cf_sign` = 'positive'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

    while ($row = $result->fetch_assoc()) {
      $o_pos = $row['total_amount'];
    }

    $operating = $o_pos - $o_neg;
    if($operating < 0){
      $operating = substr($operating,1);
    }


    //investing
    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$year' AND `cf_date_year` = '$year'  AND `cf_category` = 'INVESTING' AND `cf_sign` = 'negative'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

  
    while ($row = $result->fetch_assoc()) {
        $i_neg = $row['total_amount'];
    }

    $query = "SELECT  SUM(cf_amount) AS total_amount FROM `tblcashflow` WHERE `cf_date_month` = '$year' AND `cf_date_year` = '$year' AND  `cf_category` = 'INVESTING' AND `cf_sign` = 'positive'";    
    $stmt = $conn->prepare($query);
    $stmt-> execute();
    $result = $stmt->get_result();  

    while ($row = $result->fetch_assoc()) {
      $i_pos = $row['total_amount'];
    }


    $investing = $i_pos - $i_neg;
    if($investing < 0){
      $investing = substr($investing,1);
    }


?>
          
         
    </section>
    </main>

    <script type="text/javascript">

      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'INCOME', 'EXPENSES'],
        
          ['JAN', <?php echo $income[1]?>, <?php echo $expense[1]?>],
          ['FEB', <?php echo $income[2]?>, <?php echo $expense[2]?>],
          ['MAR', <?php echo $income[3]?>, <?php echo $expense[3]?>],
          ['APR', <?php echo $income[4]?>, <?php echo $expense[4]?>],
          ['MAY', <?php echo $income[5]?>, <?php echo $expense[5]?>],
          ['JUN', <?php echo $income[6]?>, <?php echo $expense[6]?>],
          ['JUL', <?php echo $income[7]?>, <?php echo $expense[7]?>],
          ['AUG', <?php echo $income[8]?>, <?php echo $expense[8]?>],
          ['SEP', <?php echo $income[9]?>, <?php echo $expense[9]?>],
          ['OCT', <?php echo $income[10]?>, <?php echo $expense[10]?>],
          ['NOV', <?php echo $income[11]?>, <?php echo $expense[11]?>],
          ['DEC', <?php echo $income[12]?>, <?php echo $expense[12]?>]
        ]);

        var options = {
          chart: {
            title: '',
            subtitle: '',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }

      </script>

      <script type="text/javascript">
    


    // DONUT CHART

google.charts.load("current", {packages:["corechart"]});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
  var data = google.visualization.arrayToDataTable([
    ['INCOME', 'EXPENSES'],
    ['OPERATING',      <?php echo $operating?>],
    ['FINANCING',      <?php echo $financing?>],
    ['INVESTING',      <?php echo $investing?>]
    
  ]);

  var options = {
    title: '',
    pieHole: 0.4,
  };

  var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
  chart.draw(data, options);
}





</script>




//
<script>

  google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["cash", "amount", { role: "style" } ],
        ["JAN", <?php echo $lastmoney[1]?>, "<?php echo $lastcolor[1]?>"],
        ["FEB", <?php echo $lastmoney[2]?>, "<?php echo $lastcolor[2]?>"],
        ["MAR", <?php echo $lastmoney[3]?>, "<?php echo $lastcolor[3]?>"],
        ["APR", <?php echo $lastmoney[4]?>, "<?php echo $lastcolor[4]?>"],
        ["MAY", <?php echo $lastmoney[5]?>, "<?php echo $lastcolor[5]?>"],
        ["JUN", <?php echo $lastmoney[6]?>, "<?php echo $lastcolor[6]?>"],
        ["JUL", <?php echo $lastmoney[7]?>, "<?php echo $lastcolor[7]?>"],
        ["AUG", <?php echo $lastmoney[8]?>, "<?php echo $lastcolor[8]?>"],
        ["SEP", <?php echo $lastmoney[9]?>, "<?php echo $lastcolor[9]?>"],
        ["OCT", <?php echo $lastmoney[10]?>, "<?php echo $lastcolor[10]?>"],
        ["NOV", <?php echo $lastmoney[11]?>, "<?php echo $lastcolor[11]?>"],
        ["DEC", <?php echo $lastmoney[12]?>, "<?php echo $lastcolor[12]?>"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "",
        width: 900,
        height: 300,
        bar: {groupWidth: "80%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
      chart.draw(view, options);
  }
  
      </script>

    

      <script>

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

  var data = google.visualization.arrayToDataTable([
    ['INCOME', 'AMOUNT'],
    ['INCOME',   <?php echo $total_income?>],
    ['EXPENSES', <?php echo $total_expenses?>],
    
  ]);

  var options = {
    title:''
  };

  var chart = new google.visualization.PieChart(document.getElementById('piechart'));

  chart.draw(data, options);
}
      </script>

<?php
           if (isset($_SESSION['response']) && $_SESSION['response'] !='') { ?>

<script>
swal({
    title: "<?php echo $_SESSION['response']?>",
    icon: "<?php echo $_SESSION['res_type']?>",
    button: "Done",
});
</script>

<?php
     unset($_SESSION['res_type']); 
    unset($_SESSION['response']); }
?>

    </body>
</html>


