<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
  header("Location: login");
  exit();
} else {

  require 'header.php';

  if ($_SESSION['dashboard'] == 1) {

    require_once "../models/Consultas.php";
    $consulta = new Consultas();

    //consultar cantidad de grupos de medicamentos
    $rspta = $consulta->totalGrupos();
    $reg = $rspta->fetch_object();
    $adultos = $reg->cant_adultos;
    $pediatrias = $reg->cant_pediatria;
    $neonatos = $reg->cant_neonato;
    ?>

    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h1 class="box-title"><strong>Sistema Anexos Medicamentos Enfermería - SAME</strong></h1>
                <div class="box-tools pull-right">

                </div>
              </div>
              <!--box-header-->
              <!--centro-->
              <div class="panel-body">
                <!-- panel #1 -->
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-aqua"> <!--green blue aqua purple olive lime navy teal maroon-->
                    <div class="inner">
                      <h3><?php echo $adultos; ?></h3>
                      <p>Adultos</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-android-contact"></i>
                    </div>
                    <a href="dashadulto" class="small-box-footer">Ingresar <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <!-- panel #2 -->
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-blue">
                    <div class="inner">
                      <h3><?php echo $pediatrias; ?></h3>
                      <p>Pediatría</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-happy-outline"></i>
                    </div>
                    <a href="dashpediatria" class="small-box-footer">Ingresar <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <!-- panel #3 -->
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <div class="small-box bg-olive">
                    <div class="inner">
                      <h3><?php echo $neonatos; ?></h3>
                      <p>Neonatos</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-social-reddit-outline"></i>
                    </div>
                    <a href="dashneonato" class="small-box-footer">Ingresar <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
              </div>
              <!--fin centro-->
            </div>
          </div>
        </div>
        <!-- /.box -->

      </section>
      <!-- /.content -->
    </div>
    <?php
  } else {
    require 'noacceso.php';
  }

  require 'footer.php';
  ?>
  <script src="../public/js/Chart.bundle.min.js"></script>
  <script src="../public/js/Chart.min.js"></script>
  <script>
    //primer grafico de barra
    var ctx = document.getElementById("compras").getContext('2d');
    var compras = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [<?php echo $fechasc ?>],
        datasets: [{
          label: 'Total',
          data: [<?php echo $totalesc ?>],
          backgroundColor: [
            'rgba(54, 162, 235, 0.2)', //azul
            'rgba(255, 206, 86, 0.2)', //amarillo
            'rgba(153, 102, 255, 0.2)', //morado
            'rgba(75, 192, 192, 0.2)' //azul verdoso
          ],
          borderColor: [
            'rgba(54, 162, 235, 1)', //azul
            'rgba(255, 206, 86, 1)', //amarillo
            'rgba(153, 102, 255, 1)', //morado
            'rgba(75, 192, 192, 1)' //azul verdoso
          ],
          borderWidth: 1
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

    //segundo grafico de barra
    var ctx = document.getElementById("ventas").getContext('2d');
    var ventas = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [<?php echo $fechasv ?>],
        datasets: [{
          label: 'Realizados',
          data: [<?php echo $totalesv ?>],
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)', //rojo
            'rgba(54, 162, 235, 0.2)', //azul
            'rgba(255, 206, 86, 0.2)', //amarillo
            'rgba(75, 192, 192, 0.2)', //azul verdoso
            'rgba(153, 102, 255, 0.2)', //Morado
            'rgba(255, 159, 64, 0.2)', //amarillo azul
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
          ],
          borderColor: [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1
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
  <?php
}

ob_end_flush();
?>