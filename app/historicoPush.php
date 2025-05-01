<?php 
	include_once 'header.php'; 
	$tituloPagina = "Mensagens";
	include_once "navegacao.php";
	$hashLocal = mt_rand();
	$msgRetorno = "";
    $cod_cliente_url = 0;
    // echo "<br><br><br><br><br><br>";

    /* Set the default timezone */
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');                    
    date_default_timezone_set("america/sao_paulo");

    if(isset($_GET['idc'])){
        $cod_cliente_url = fnDecode(base64_decode($_GET['idc']));

        $sqlCpf = "SELECT NUM_CGCECPF 
                   FROM CLIENTES
                   WHERE COD_EMPRESA = $cod_empresa 
                   AND COD_CLIENTE = $cod_cliente_url";

        $arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCpf);
        $qrCli = mysqli_fetch_assoc($arrayCli);
        $num_cgcecpf = $qrCli[NUM_CGCECPF];

        // echo $sqlCpf."<br>";
        // echo $num_cgcecpf."<br>";

    }else{
        $num_cgcecpf = $usuario;
    }

        // echo "<br><br><br><br>";
        // echo $cod_cliente_url."_<br>";
        // exit();
	if( $_SERVER['REQUEST_METHOD']=='POST' ){

		

	}
?>	
	
	<div class="container">
		<div class="push20"></div>
        <div class="push10"></div>

        <?php if ($msgRetorno != ""){
        ?>
            <div class="alert <?=$msgTipo?>" role="alert">
            <?php echo $msgRetorno; ?>
            </div>          
        <?php   
        }else{
        ?>
            <div class="push20"></div>
            <div class="push10"></div>
            <div style="height: 3px;"></div>
        <?php
        }
        ?>

        <?php

            $sql = "SELECT ret.DAT_CADASTR,ret.DES_MSG_ENVIADA 
                    FROM push_lista_ret ret
                    INNER JOIN clientes cl ON cl.COD_CLIENTE=ret.COD_CLIENTE AND cl.NUM_CGCECPF='".$num_cgcecpf."'
                    WHERE ret.cod_empresa = $cod_empresa
                    ORDER BY ret.COD_LISTA DESC 
                    LIMIT 3";

            // echo $sql;

            $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
            $num_msgs = mysqli_num_rows($arrayQuery);
                
            $count = 0;

            $hoje = date("Y-m-d");
            $ontem = date("Y-m-d",strtotime("-1 day"));

            if($num_msgs > 0){
      
                //==fim==============
                while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)){

                    $arrData = explode(" ", $qrBuscaProdutos['DAT_CADASTR']);
                    $dataCompara = $arrData[0];

                    if($dataCompara == $hoje){
                        $data = "Hoje $arrData[1]";
                    }else if($dataCompara == $ontem){
                        $data = "Ontem $arrData[1]";
                    }else{
                        $data = utf8_encode(ucfirst(strftime("%A", strtotime($qrBuscaProdutos['DAT_CADASTR']))))." ".date("H:i:s",strtotime($qrBuscaProdutos['DAT_CADASTR']));
                        // $data = fnDataFull($qrBuscaProdutos['DAT_CADASTR']);
                    }

        ?>

                    <div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
                        <div class="shadow2">
                            <div class="push5"></div>
                            <div class="col-xs-12 text-right">
                                <h5 class="f12" style="margin-bottom: 0;"><b><?=$data?></b></h5>
                            </div>
                            <div class="col-xs-12">
                                <h5 class="f15"><?=$qrBuscaProdutos['DES_MSG_ENVIADA']?></h5>
                            </div>
                            <div class="push5"></div>
                        </div>
                    </div>

        <?php 
                } 
            }else{
        ?>
                <div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
                    <div class="shadow2">
                        <div class="push5"></div>
                        <div class="col-xs-12 text-center">
                            <h5>Ainda não há mensagens</h5>
                        </div>
                        <div class="push5"></div>
                    </div>
                </div>
        <?php
            } 
        ?>
   
   
    </div> <!-- /container -->

<?php include 'footer.php'; ?>

<script type="text/javascript">
  $("#CAD").click(function(e){
    if($("#CAD").hasClass('clicked')){
      e.preventDefault();
    }else{
      $("#NOM_CLIENTE").attr('readonly');
      $("#EMAIL").attr('readonly');
      $("#Celular").attr('readonly');
      $("#Soli").attr('readonly');
      $(this).html('<div class="loading" style="width:100%"></div>').addClass('clicked').attr('disabled',true);
      $("#formulario").submit();
    }
  });
</script>