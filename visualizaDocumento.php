<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
		$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '" . $cod_grupotr . "', 
				 '" . $des_grupotr . "', 
				 '" . $cod_empresa . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;

			$arrayProc = mysqli_query($adm, $sql);

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
					}
					break;					
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_documento = fnDecode($_GET['idD']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

if($cod_documento != 0){

	$sqlDoc = "SELECT * FROM DOCUMENTOS 
			   WHERE COD_EMPRESA = $cod_empresa
			   AND COD_DOCUMEN = $cod_documento";

	//fnEscreve($sql);
	$arrayDoc = mysqli_query(connTemp($cod_empresa,""),$sqlDoc);
	$qrDoc = mysqli_fetch_assoc($arrayDoc);

	if(isset($qrDoc)){

		$font_family = trim($qrDoc[FONT_FAMILY]);
		$fsize_cabecalho = $qrDoc[FSIZE_CABECALHO];
		$fsize_rodape = $qrDoc[FSIZE_RODAPE];
		$fsize_texto = $qrDoc[FSIZE_TEXTO];
		$fsize_titulo = $qrDoc[FSIZE_TITULO];
		$fsize_bloco = $qrDoc[FSIZE_BLOCO];
		$fsize_looping = $qrDoc[FSIZE_LOOPING];

	}else{

		$font_family = "Arial";
		$fsize_cabecalho = "18";
		$fsize_rodape = "18";
		$fsize_texto = "16";
		$fsize_titulo = "24";
		$fsize_bloco = "14";
		$fsize_looping = "14";

	}

}

//fnMostraForm();

?>


<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true"){  ?>							
		<div class="portlet portlet-bordered">
		<?php } else { ?>
		<div class="portlet" style="padding: 0 20px 20px 20px;" >
		<?php } ?>
		
			<?php if ($popUp != "true"){  ?>
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

				<button type="button" class="btn btn-info btn-sm addBox pull-right" onclick="imprimir()" ><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Imprimir </button>

				<div class="push30"></div>

				<div class="login-form">

					<div id="impressao">

						<style type="text/css">
							.documento {
								max-width: 700px;
								margin-left: auto;
								margin-right: auto;
							}
							.cabecalho, .cabecalho > *,
							.rodape, .rodape > *,
							.texto, .texto > *,
							.titulo, .titulo > *,
							.bloco, .bloco > *{
								font-family: "<?=$font_family?>";
							}
							.cabecalho, .cabecalho > *{font-size: <?=$fsize_cabecalho?>!important}
							.rodape, .rodape > *{font-size: <?=$fsize_rodape?>!important}
							.texto, .texto > *{font-size: <?=$fsize_texto?>!important}
							.titulo, .titulo > *{font-size: <?=$fsize_titulo?>!important}
							.bloco, .bloco > *{font-size: <?=$fsize_bloco?>!important}

							@media print {
								@page {
									margin: 0; 
								}
							    .documento {
									max-width: 700px;
									margin-left: auto;
									margin-right: auto;
								}
								.cabecalho, .cabecalho > *,
								.rodape, .rodape > *,
								.texto, .texto > *,
								.titulo, .titulo > *,
								.bloco, .bloco > *{
									font-family: "<?=$font_family?>";
								}
								.cabecalho, .cabecalho > *{font-size: <?=$fsize_cabecalho?>!important}
								.rodape, .rodape > *{font-size: <?=$fsize_rodape?>!important}
								.texto, .texto > *{font-size: <?=$fsize_texto?>!important}
								.titulo, .titulo > *{font-size: <?=$fsize_titulo?>!important}
								.bloco, .bloco > *{font-size: <?=$fsize_bloco?>!important}
							}
						</style>

						<div class="documento" id="impressao1">

							<?php

								$sql = "SELECT * FROM TEMPLATE_DOCUMENTO 
										WHERE COD_DOCUMENTO = $cod_documento
										ORDER BY NUM_ORDENAC";
								// fnEscreve($sql);

								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
								
								while($qrTempl = mysqli_fetch_assoc($arrayQuery)){

									$chave = $qrTempl[COD_TEMPLATE];

									$conteudo = "";

									$imagem = $qrTempl[DES_IMAGEM];

									if($qrTempl[DES_TEMPLATE] != ""){

										$conteudo = base64_decode($qrTempl[DES_TEMPLATE]);

									}

									$cod_bltempl = $qrTempl[COD_BLTEMPL];

									switch($cod_bltempl){

										case 1:
										case 2:
										case 3:
										case 4:
										case 5:
										case 7:

											if($cod_bltempl == 1){
												$classeFonte = "cabecalho";
											}else if($cod_bltempl == 2){
												$classeFonte = "rodape";
											}else if($cod_bltempl == 3){
												$classeFonte = "texto";
											}else if($cod_bltempl == 4){
												$classeFonte = "titulo";
											}else{
												$classeFonte = "bloco";
											}
												

											if($qrTempl[DES_TEMPLATE] != ""){
												$conteudoMovable = "<div class='$classeFonte'>".html_entity_decode($conteudo)."</div>";
											}	
											
										break;

										case 6:

											if($imagem == ""){

												$conteudoMovable = '<img src="" alt="Imagem não encontrada">';
											}else{

												$conteudoMovable = "<div style='height:auto; width: 100%;  display: flex; align-items: center; justify-content: center;'>
													                <img src='media/clientes/$cod_empresa/$imagem'  style='max-width:100%; max-height: 100%'>
													    			</div>";

											}

											
											
										break;

									}

							?>
									

									<?=$conteudoMovable?>
									<div class="push20"></div>
									<div class="push10"></div>
									

							<?php
								}
							?>

						</div>

					</div>

				</div>

				<div class="push20"></div>
				<div class="push10"></div>

				<button type="button" class="btn btn-info btn-sm addBox pull-right" onclick="imprimir()" ><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Imprimir </button>


				<div class="push50"></div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<script src='js/printThis.js'></script>

<div class="push20"></div>

<script type="text/javascript">
	function imprimir(){
		$("#impressao").printThis({
			// debug: true,
			copyTagClasses: true
		});
	}
</script>