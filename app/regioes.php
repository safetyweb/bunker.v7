	<?php
  include_once 'header.php';
  $tituloPagina = "Regiões";
  include_once "navegacao.php";

  //  if(!isset($_SESSION["usuario"])){

  //    header('Location:app.do?key='.$_GET[key]);

  // }

  $sqlCli = "SELECT COD_CLIENTE FROM CLIENTES WHERE NUM_CGCECPF ='" . $usuario . "' AND COD_EMPRESA = $cod_empresa";
  $qrCli = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlCli));
  $cod_cliente = $qrCli['COD_CLIENTE'];

  // fnescreve($cod_cliente);


  ?>

	<div class="container">

	  <div class="push10"></div>

	  <h4>Escolha abaixo a região mais próxima de você.</h4>
	  <h4 id="demo"></h4>

	  <div class="push10"></div>
	  <a href='enderecos.do?key=<?= $_GET[key] ?>&id=<?= fnEncode(9999) ?>&secur=<?= fnEncode($cod_cliente) ?>&idU=<?= $usuEncrypt ?>&t=<?= $rand ?>' class='btn btn-primary btn-block'>TODOS</a>



	  <div class="push10"></div>
	  <?php
    // enderecos.php?id=10

    $sql = "SELECT * FROM REGIAO_GRUPO WHERE COD_EMPRESA = $cod_empresa ORDER BY DES_TIPOREG";

    // fnEscreve($sql);
    $arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

    while ($qrListaRegiao = mysqli_fetch_assoc($arrayQuery)) {
    ?>

	    <a href='enderecos.do?key=<?= $_GET['key'] ?>&id=<?= fnEncode($qrListaRegiao['COD_TIPOREG']) ?>&secur=<?= fnEncode($cod_cliente) ?>&idU=<?= $usuEncrypt ?>&t=<?= $rand ?>' class='btn btn-primary btn-block'><?= $qrListaRegiao['DES_TIPOREG'] ?></a>
	  <?php
    }
    ?>
	  <!-- <div class="push10"></div> 
                       <a href="enderecos.php?id=9999&secur=<?php echo fnEncode($cod_cliente); ?>" class="btn btn-primary btn-block">TODOS</a> -->

	  <div class="push50"></div>

	</div> <!-- /container -->

	<?php
  function get_browser_name($user_agent)
  {
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'iPhone')) return 'iPhone';
    elseif (strpos($user_agent, 'iPad')) return 'iPad';
    elseif (strpos($user_agent, 'iPod')) return 'iPod';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';

    return 'Other';
  }

  // Usage:

  $navegador = get_browser_name($_SERVER['HTTP_USER_AGENT']);

  //if($navegador!= 'Safari' || $navegador!= 'iPhone'||$navegador!= 'iPad'||$navegador!= 'iPod'||$navegador!= 'Other' )
  //{    
  echo '        
        <script type="text/javascript">
        var x = document.getElementById("demo");
          
                    function getLocation()
                      {
                                
                      if (navigator.geolocation)
                        {
                        navigator.geolocation.getCurrentPosition(showPosition);
                                      
                        }
                      else{x.innerHTML="O seu navegador não suporta Geolocalização.";}
                      }
                    function showPosition(position)
                      {
                        document.cookie="RD_localAtual="+position.coords.latitude + "," + position.coords.longitude;
                                   
                      }

                    getLocation()   
        </script>';

  //}
  ?>

	<?php include_once 'footer.php'; ?>