<?php

include "_system/_functionsMain.php";
include "_system/func_dinamiza/Function_dinamiza.php";


$opcao = $_REQUEST['tipo'];

$html = stripslashes(trim($_REQUEST['html']));

$html = str_replace('class="preview"', 'style="display:none;"', $html);

$des_template = addslashes($html);

// echo "<pre>";
// print_r($des_template);
// echo "</pre>";

$cod_modelo = fnLimpaCampoZero(@$_REQUEST['COD_MODELO']);
$dir_arq = "emailComponenteTeste/EmailMkt/{$cod_empresa}";
$nom_pagina = "email" . time() . ".html";

$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
// $des_assunto = remove_emoji($_REQUEST['DES_ASSUNTO']);
$des_assunto = addslashes($_REQUEST['DES_ASSUNTO']);
$des_remet = fnLimpaCampo($_REQUEST['DES_REMET']);
$end_remet = fnAcentos(fnLimpaCampo($_REQUEST['END_REMET']));
$email_resposta = fnAcentos(fnLimpaCampo($_REQUEST['EMAIL_RESPOSTA']));
$txt_linkopt = fnLimpaCampo($_REQUEST['TXT_LINKOPT']);
$tag_linkopt = $_REQUEST['TAG_LINKOPT'];
$txt_opt = fnLimpaCampo($_REQUEST['TXT_OPT']);
$tag_opt = $_REQUEST['TAG_OPT'];
if (empty($_REQUEST['LOG_OPT'])) {$log_opt='N';}else{$log_opt=$_REQUEST['LOG_OPT'];}
$cod_usucada = @$_SESSION['SYS_COD_USUARIO'];

$connTemp = connTemp($cod_empresa, "");

mysqli_query ($connTemp,"set character_set_client='utf8mb4'"); 
mysqli_query ($connTemp,"set character_set_results='utf8mb4'");
mysqli_query ($connTemp,"set collation_connection='utf8mb4_unicode_ci'");

try {
  //// EXCLUI ARQUIVO ANTIGO DO DIRETORIO VINCULADO AO MODELO SALVO
	if ($opcao == "ALT" || $opcao == "EXC") {
		$sql = "SELECT NOM_PAGINA FROM MODELO_EMAIL WHERE COD_TEMPLATE = $cod_template";
		$arrayQuery = mysqli_query($connTemp, trim($sql));
		$campoQuery = mysqli_fetch_assoc($arrayQuery);
		unlink($dir_arq . "/" . $campoQuery["NOM_PAGINA"]);
	}

} finally {


	try {

		if ($opcao != '') {

			switch ($opcao) {

				case 'CAD':

					$sql = "INSERT INTO MODELO_EMAIL (
							                COD_EMPRESA,
							                COD_TEMPLATE,
							                DES_TEMPLATE,
							                NOM_PAGINA,
							                COD_BANCOVAR,
							                COD_USUCADA
						              ) VALUES(
							                $cod_empresa,
							                $cod_template,
							                '$des_template',
							                '$nom_pagina',
							                '$cod_bancovar',
							                $cod_usucada
						              ); ";
					mysqli_query($connTemp, trim($sql));

				break;

				case 'ALT':

					$sql = "UPDATE MODELO_EMAIL SET
					                DES_ASSUNTO='$des_assunto',
					                DES_REMET='$des_remet',
					                DES_TEMPLATE='$des_template',
					                NOM_PAGINA='$nom_pagina',
					                COD_BANCOVAR='$cod_bancovar'
			                WHERE COD_TEMPLATE = $cod_template; ";
			        // fnEscreve($sql);
			        mysqli_query($connTemp, trim($sql));

				break;

				case 'EXC':

		            //$sql = "DELETE FROM MODELO_EMAIL WHERE COD_MODELO = $cod_modelo";

					// $sql = "UPDATE MODELO_EMAIL SET DES_TEMPLATE='' WHERE COD_TEMPLATE = $cod_template ";
					// mysqli_query($connTemp, trim($sql));


					// $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

				break;

			}


			$sql = "UPDATE TEMPLATE_EMAIL SET
			                DES_ASSUNTO='$des_assunto',
			                DES_REMET='$des_remet',
			                END_REMET='$end_remet',
			                EMAIL_RESPOSTA='$email_resposta',
			                LOG_OPT='$log_opt',
			                TXT_LINKOPT='$txt_linkopt',
			                TAG_LINKOPT='$tag_linkopt',
			                TXT_OPT='$txt_opt',
			                TAG_OPT='$tag_opt'
	                WHERE COD_TEMPLATE = $cod_template
	                AND COD_EMPRESA = $cod_empresa";

	    mysqli_query($connTemp, trim($sql));

			
			

			$sqlMsg = "SELECT TE.*, MDE.DES_TEMPLATE AS HTML FROM TEMPLATE_EMAIL TE
						LEFT JOIN MENSAGEM_EMAIL ME ON ME.COD_TEMPLATE_EMAIL = TE.COD_TEMPLATE
						LEFT JOIN MODELO_EMAIL MDE ON MDE.COD_TEMPLATE = TE.COD_TEMPLATE
						WHERE TE.COD_EMPRESA = $cod_empresa 
						AND TE.COD_TEMPLATE = $cod_template";
				      // fnEscreve($sqlMsg);

			$qrMsg = mysqli_fetch_assoc(mysqli_query($connTemp,$sqlMsg));

			if($qrMsg['LOG_OPT'] == 'S'){
				$log_opt = 1;
			}else{
				$log_opt = 0;
			}

			if($qrMsg['TAG_LINKOPT'] != ""){
				$tag_linkopt = $qrMsg['TAG_LINKOPT'];
			}else{
				$tag_linkopt = "";
			}

			if($qrMsg['TAG_OPT'] != ""){
				$tag_opt = $qrMsg['TAG_OPT'];
			}else{
				$tag_opt = "";
			}

			include "autenticaDinamize.php";

			$html = "<html><head></head><body>".$qrMsg['HTML']."</body></html>";

			$processo = 'sucesso';

			if($qrMsg['COD_EXT_TEMPLATE'] == ''){

				$retornoAdd = AddHtml($_SESSION['AUTH_DINAMIZE'] ,$html, $qrMsg['NOM_TEMPLATE']);

				$processo = $retornoAdd['code_detail'];

				// echo '<pre>';
				// print_r($retornoAdd);
				// echo '/<pre>';

				$cod_ext_retorno = $retornoAdd['body']['code'];
				// fnescreve($cod_ext_retorno);

				if($cod_ext_retorno != ""){
					$sql = "UPDATE TEMPLATE_EMAIL SET COD_EXT_TEMPLATE = $cod_ext_retorno WHERE COD_TEMPLATE = $cod_template";
				}else{
					$sql = "UPDATE TEMPLATE_EMAIL SET COD_EXT_TEMPLATE = NULL WHERE COD_TEMPLATE = $cod_template";
					$processo = "Retorno: ".$retornoAdd['code_detail'];
				}

		    		// fnEscreve($sql);
					mysqli_query($connTemp,$sql);

			}else{

				$retornoAtt = AtualizaHtml($_SESSION['AUTH_DINAMIZE'], $html, $qrMsg['NOM_TEMPLATE'], $qrMsg['COD_EXT_TEMPLATE']);

				$processo = $retornoAtt['code_detail'];				

				// echo '<pre>';
				// print_r($retornoAtt);
				// echo '/<pre>';
				// fnescreve('else');

			}

			// fnEscreve($processo);
			// fnEscreve($retornoAtt['code_detail']);

			if($processo == "Sucesso"){
				echo "https://adm.bunker.mk/action.do?mod=".fnEncode(1644)."&id=".fnEncode($cod_empresa)."&idT=".fnEncode($cod_template)."&idc=".fnEncode($cod_campanha)."&pop=true&msg=success";
			}else{
				echo "https://adm.bunker.mk/action.do?mod=".fnEncode(1644)."&id=".fnEncode($cod_empresa)."&idT=".fnEncode($cod_template)."&idc=".fnEncode($cod_campanha)."&pop=true&msg=".fnEncode($processo);
			}

		}

	} catch (Exception $e) {

		echo 'erro_tmplt';
		
	}

}

mysqli_close($connTemp);

?>