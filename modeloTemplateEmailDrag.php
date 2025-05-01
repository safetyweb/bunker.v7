<?php
if (isset($_GET['idc'])) {
  $cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
}else{
  $cod_campanha = "";
}

if (isset($_GET['pop'])) {
  $popUp = fnLimpaCampo($_GET['pop']);
} else {
  $popUp = '';
}

//fnEscreve($cod_campanha);

?>

<!-- <div class="push30"></div> --> 

<div class="row">				

  <div class="col-md12 margin-bottom-30">
    <!-- Portlet -->
    <?php if ($popUp != "true") { ?>              
      <div class="portlet portlet-bordered">
      <?php } else { ?>
        <div class="portlet" style="padding: 0 20px 20px 20px;" >
        <?php } ?>

        <?php if ($popUp != "true") { ?>
          <div class="portlet-title">
            <div class="caption">
              <i class="glyphicon glyphicon-calendar"></i>
              <span class="text-primary"><?php echo $NomePg; ?></span>
            </div>
            <?php include "atalhosPortlet.php"; ?>
          </div>
        <?php } ?>
        <div class="portlet-body">

          <?php if ($msgRetorno <> '') { ?> 
            <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $msgRetorno; ?>
            </div>
          <?php } ?>

          <!-- <div class="push30"></div>  -->

          <div class="login-form">

            <?php
			
		  
			     // fnEscreve($cod_campanha);
			
			
            $cod_empresa = 0;
            $cod_template = 0;
            if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
			  //busca dados da empresa
              $cod_empresa = fnDecode($_GET['id']);
              $cod_template = fnDecode($_GET['idT']);
            }

            $cod_modelo = "";
            $des_template = "";
            $des_assunto = "";
            $des_remet = "";
            $nom_pagina = "";
            $isCad = true;

            if ($cod_template != "" && $cod_template != 0) {
              $sqlModelo = "SELECT COD_MODELO, DES_TEMPLATE, DES_ASSUNTO, DES_REMET FROM MODELO_EMAIL WHERE COD_TEMPLATE = $cod_template";

              $arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sqlModelo));

              while ($qrModelo = mysqli_fetch_assoc($arrayQuery)) {
                $isCad = false;

                $cod_modelo = $qrModelo['COD_MODELO'];
                $des_template = $qrModelo['DES_TEMPLATE'];
                $des_assunto = $qrModelo['DES_ASSUNTO'];
                $des_remet = $qrModelo['DES_REMET'];
                $nom_pagina = $qrModelo['NOM_PAGINA'];
              }
            }

            $path = "emailComponenteTeste/";

            include "abasTemplateEmailDrag.php";

            echo '<div class="push20"></div>';

            include "emailComponenteTeste/index.php";
            ?>

          </div>
        </div>
      </div>
      <!-- fim Portlet -->
    </div>

  </div>					

  <div class="push20"></div> 