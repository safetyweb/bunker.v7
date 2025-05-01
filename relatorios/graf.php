<?php
include '../_system/_functionsMain.php';
$sql1="SELECT COUNT(*) as OK FROM origemvenda
INNER JOIN msg_venda ON id=cod_origem
WHERE msg='OK'";

$sql2="SELECT COUNT(*)as nok FROM origemvenda
INNER JOIN msg_venda ON id=cod_origem
WHERE msg!='OK'";
$result1= mysqli_fetch_assoc(mysqli_query(connTemp(39,''), $sql1));
$result2= mysqli_fetch_assoc(mysqli_query(connTemp(39,''), $sql2));


?>
<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="widget widget-default widget-item-icon">
                    <div class="widget-item-left">
                            <span class="fa fa-bar-chart"></span>
                    </div>                             
                    <div class="widget-data">
                            <div class="widget-title">Visão Geral por Idade</div>
                            <div class="widget-subtitle" style="padding: 20px 30px 20px 10px;">
                                   
                                    <canvas id="mybarChart"></canvas>					

                            </div>
                    </div>
            </div> 												  

    </div>

    <div class="push50"></div>

</div>
<script src="../js/plugins/ion.rangeSlider.js"></script>
   
<script src="../js/plugins/Chart.min.js"></script>
<script>
      Chart.defaults.global.legend = {
        enabled: false
      };

      // Bar chart
	  // gentelella
      var ctx = document.getElementById("mybarChart");
      var mybarChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ["VENDA OK ", "VENDA NÂO OK"],
          datasets: [{
            label: 'Clientes',
            backgroundColor: "#85C1E9",
            data: [<?php echo $result1['OK'];?>, 
                   <?php echo $result2['nok'];?>, 
                   ]
          }]
        },

        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          }
        }
      });

    </script>
