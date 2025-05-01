<?php 
include './_system/_functionsMain.php'; 
include_once '../totem/funWS/atualizacadastro.php';

  if( $_SERVER['REQUEST_METHOD']=='POST' )
  {

  $cpf = fnLimpaCampoZero(fnDecode($_REQUEST['CPF']));
  $cod_cliente_cad = fnLimpaCampoZero(fnDecode($_REQUEST['COD_CLIENTE']));
  $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
  $cod_sexopes = fnLimpaCampoZero($_REQUEST['COD_SEXOPES']);
  // $radio = fnLimpaCampo($_REQUEST['RADIO']);
  $nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);
  $des_senhaus = fnDecode($_REQUEST['DES_SENHAUS']);
  $dat_nascime = fnLimpaCampo($_REQUEST['DAT_NASCIME']);
  $num_celular = fnLimpaCampo($_REQUEST['NUM_CELULAR']);
  $num_cepozof = fnLimpaCampo($_REQUEST['NUM_CEPOZOF']);
  $des_emailus = fnLimpaCampo($_REQUEST['DES_EMAILUS']);
  $cod_profiss = fnLimpaCampoZero($_REQUEST['COD_PROFISS']);

  // if($radio == "MASCULINO"){
  //   $cod_sexopes = 1;
  // }else{
  //   $cod_sexopes = 2;
  // }

  $sql = "SELECT LOG_USUARIO, DES_SENHAUS FROM USUARIOS
        WHERE COD_EMPRESA = $cod_empresa AND 
        COD_TPUSUARIO = 10  AND 
        LOG_ESTATUS = 'S' LIMIT 1";

  $qrUs = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));

  $login = $qrUs['LOG_USUARIO'];
  $senha = fnDecode($qrUs['DES_SENHAUS']);

  // fnEscreve($login);
  // fnEscreve($senha);

  $dadoslogin = array(
   '0'=>$login,
   '1'=>$senha,
   '2'=>$cod_univend,
   '3'=>'maquina',
   '4'=>$cod_empresa
  );

  $dadosatualiza=Array(
            'nome'=>$nom_cliente,
            'email'=>$des_emailus,
            'telefone'=>$num_celular,
            'cpf'=>$cpf,
            'cartao'=>$cpf,
            'senha'=>$des_senhaus,
            'sexo'=>$cod_sexopes,
            'dt_nascimento'=>$dat_nascime,
            'profissao'=>$cod_profiss,
            'cep'=>$num_cepozof
          );
           

  $atualiza=atualizacadastro($dadosatualiza, $dadoslogin);

  // fnEscreve($atualiza);

  if($atualiza == "Registro inserido!" || $atualiza == "Cadastro Atualizado !"){

  }   
    
    header("Location: https://adm.bunker.mk/appduque/infoCadastro.do?secur=".fnEncode($cpf)."&idp=".fnEncode(6));
    // exit();
                
  }
  
    //Rotina de logoff
  if($_GET['logoff']=='1'){
        //session_destroy();
        //session_unset();
        unset($_SESSION["login"]);
       
        
      // header("Location:https://www.rededuque.com.br/app/");
       header("Location:https://adm.bunker.mk/appduque/"); 
        
  }

if(isset($_GET['idc'])){
  $cod_cliente_cad = fnLimpaCampoZero(fnDecode($_GET['idc']));
  $secur = fnLimpaCampo(fnDecode($_GET['secur']));

  $sql = "SELECT * FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente_cad AND COD_EMPRESA = 19";
  $qrCli = mysqli_fetch_assoc(mysqli_query(connTemp(19,''),$sql));
}

// fnEscreve($cod_cliente_cad);

// echo "<pre>";
// print_r($_SERVER);
// echo "</pre>";

?>		


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
		
        <title>Rede Duque</title>
		
		<?php include "cssLib.php"; ?>	

    </head>

    <body class="bgColor" data-gr-c-s-loaded="true">
		<?php 
		$tituloPagina = "Prêmios";
		include "menu.php"; 
		?>

    <style type="text/css">
      
      .shadow{
       -webkit-box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
       -moz-box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
       box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
       /*width: 100%;*/
       border-radius: 5px;
     }

     .shadow2{
        -webkit-box-shadow: 0px 5px 8px 0px rgba(204,200,204,0.8);
        -moz-box-shadow: 0px 5px 8px 0px rgba(204,200,204,0.8);
        box-shadow: 0px 5px 8px 0px rgba(204,200,204,0.8);
        width: 100%;
        border-radius: 5px;
    }

     .carousel{
      border-radius: 10px 10px 10px 10px;
      overflow: hidden;
    }
    .carousel-caption{
      /*background-color: rgba(0,0,0,0.2);*/
      border-radius: 30px 30px 30px 30px;
    }
    .contorno{
      color: black;
      -webkit-text-fill-color: white; /* Will override color (regardless of order) */
      /*-webkit-text-stroke-width: 0.5px;
      -webkit-text-stroke-color: white;*/
      text-shadow: 1px 1px black;
    }

    .carousel-indicators{
      z-index: 0;
    }

    .img-lista{
      height: 85px; 
      width: 85px;
      border-radius: 50px; 
    }

    .center{
      margin: auto;
      position:absolute;
      right: 0;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
    }

    </style>

    <div class="container">

        <div class="push50"></div>
      <div class="push5"></div>

      <div class="row">

        <?php 

          $sql1="SELECT A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOPROMOCAO A 
                  LEFT JOIN CAT_PROMOCAO B ON A.COD_CATEGOR = B.COD_CATEGOR 
                  LEFT JOIN SUB_PROMOCAO C ON A.COD_SUBCATE = C.COD_SUBCATE 
                  where A.COD_EMPRESA=$cod_empresa 
                  AND A.COD_EXCLUSA=0 
                  AND LOG_ATIVO = 'S' order by A.NUM_PONTOS ASC
                   ";

          // echo($sql1);
          $arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1);

          if(mysqli_num_rows($arrayQuery) > 0){
            
            $count=0;
            while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
            {                             
              $count++;
              $des_imagem = $qrListaProduto['DES_IMAGEM'];
              $des_produto = $qrListaProduto['DES_PRODUTO'];
              $num_pontos = $qrListaProduto['NUM_PONTOS'];

              // echo "<pre>";
              // echo "<pre>";
              // print_r($habito);
              // echo "</pre>";

              if($des_produto != ""){

              ?>

                <div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
                  <div class="shadow2">
                    <div class="push5"></div>
                    <div class="col-xs-5 text-center" style="height: 90px; padding: 0;">
                      <div class="img-lista center" style="background: url('../media/clientes/19/produtospromo/<?=$des_imagem?>') no-repeat center; background-size: auto 85px;"></div>
                    </div>
                    <div class="col-xs-7">
                      <h5><b><?=strtoupper($des_produto)?></b></h5>
                      <p class="f14"><b><?=$num_pontos?></b> <span class="f10">Pontos</span></p>
                    </div>
                    <div class="push5"></div>
                  </div>
                </div>

                <div class="push20"></div>

              <?php

              }

            }

          }else{

          ?>

          <div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
            <div class="shadow2">
              <div class="push5"></div>
              <div class="col-xs-12 zeraPadLateral text-center">
                <h5>Não há produtos</h5>
              </div>
              <div class="push5"></div>
            </div>
          </div>

          <?php

          }

        ?>

      </div>

    </div> <!-- /container -->

	

		<?php include 'jsLib.php';?>
    </body>
</html>
