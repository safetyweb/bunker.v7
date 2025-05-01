<?php

include "../_system/_functionsMain.php";
include "../_system/_FUNCTION_WS.php";

//habilitando o cors
header("Access-Control-Allow-Origin: *");

$cod_tiporeg = str_replace(".","",fnLimpacampo($_POST['GRUPO']));
$cod_empresa = fnLimpacampoZero($_POST['COD_EMPRESA']);

//fnEscreve($cod_empresa);
if($cod_tiporeg == "") $cod_tiporeg = "*";
if($cod_tiporeg == "*") {$andTipoReg = " "; $cod_tiporeg = "all";} else $andTipoReg = "AND COD_TIPOREG = $cod_tiporeg";

//fnEscreve($cod_tiporeg);




?>



				
				<option value=".<?php echo $cod_tiporeg; ?>">Todas as Lojas</option>
				<?php
				$safe_word = array("-", " ");

				$sql1 = "SELECT DISTINCT(NOM_CIDADEC) FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S' AND LOG_ATIVOHS = 'S' $andTipoReg ORDER BY NOM_CIDADEC ";
				$arrayQuery1 = mysqli_query($connAdm->connAdm(),$sql1) or die(mysqli_error());

				$count=0;
				while ($qrListaUniBairros = mysqli_fetch_assoc($arrayQuery1))
				{
				if($qrListaUniBairros['NOM_CIDADEC'] == "Centro" && $cod_tiporeg != "all"){
				?>
				<option value=".<?php echo $cod_tiporeg; ?><?php echo str_replace($safe_word, "_", $qrListaUniBairros['NOM_CIDADEC']); ?>"><?php echo $qrListaUniBairros['NOM_CIDADEC']; ?></option>
				<?php
				}else{
				?>														  
				<option value=".<?php echo str_replace($safe_word, "_", $qrListaUniBairros['NOM_CIDADEC']); ?>"><?php echo $qrListaUniBairros['NOM_CIDADEC']; ?></option>
				<?php
				}
					}	 
				?>
