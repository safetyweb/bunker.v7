<?php include "_system/_functionsMain.php";

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$cod_empresa = $buscaAjx1;
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
$buscaAjx4 = fnLimpacampo($_GET['ajx4']);

//tabela do update
switch ($buscaAjx2) {
	case 0: //Inclui modelo Template
		$sql = "CALL SP_ALTERA_MODELOTEMPLATETKT(0, '" . $buscaAjx3 . "', '" . $buscaAjx1 . "', '" . $buscaAjx4 . "', ' ', '" . $_SESSION["SYS_COD_USUARIO"] . "', 'CAD' )";

		//fnEscreve($sql);

		$retorno = mysqli_query(connTemp($buscaAjx1, ""), trim($sql));
		$row = mysqli_fetch_row($retorno);
		echo $row[0];
		break;
	case 1: //nome do cliente
?>
		<div style="position: relative">
			<center>
				<h3 style="margin: 5px; font-weight: 900">ISABEL,</h3>
				<!--<h5 style="margin: 5px; font-weight: 900"><b>LEVE TAMBÉM...</b></h5>-->
				<h5 style="margin-top: 5px"><b>Esquecendo</b> de algo?</h5>
				<a class="excluirBloco">
					<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
				</a>
				<hr class="divisao" />
				<center>

		</div>
	<?php
		break;
	case 2: //Produtos modelo 1
	?>
		<div style="position: relative">
			<center>
				<h5 style="margin-top: 5px">Veja <b>ofertas personalizadas</b> para você!</h5>
			</center>
			<div style="width: 100%;">
				<div style="width: 55%; float: left; text-align: left;">
					<h5 style="font-weight: 900">[Nome do Produto]</h5>
					<h5>[código]</h5>
				</div>
			</div>

			<div style="width: 100%;">
				<div style="width: 40%; float: right; text-align: left;">
					<h5 style="font-weight: 900">de: R$ [de1]</h5>
					<h5 style="font-weight: 900">por: R$ [por1]</h5>
				</div>
			</div>
			<hr />
			<div style="width: 100%;">
				<div style="width: 55%; float: left; text-align: left;">
					<h5 style="font-weight: 900">[Nome do Produto]</h5>
					<h5>[código]</h5>
				</div>
			</div>

			<div style="width: 100%;">
				<div style="width: 40%; float: right; text-align: left;">
					<h5 style="font-weight: 900">de: R$ [de2]</h5>
					<h5 style="font-weight: 900">por: R$ [por2]</h5>
				</div>
			</div>
			<hr />
			<div style="width: 100%;">
				<div style="width: 55%; float: left; text-align: left;">
					<h5 style="font-weight: 900">[Nome do Produto]</h5>
					<h5>[código]</h5>
				</div>
			</div>

			<div style="width: 100%;">
				<div style="width: 40%; float: right; text-align: left;">
					<h5 style="font-weight: 900">de: R$ [de3]</h5>
					<h5 style="font-weight: 900">por: R$ [por3]</h5>
				</div>
			</div>
			<hr />
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 3: //lista de promoções black
	?>
		<div style="position: relative">
			<center style="margin-bottom: 20px; padding: 5px; background-color: #161616; color: #fff">
				<h5 style="font-weight: 900; margin-bottom: 20px; font-size: 17px;">OFERTA EM DESTAQUE</h5>
				<h4 style="font-size: 21px; margin-top: 2px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
				<h5 style="font-weight: 500; font-size:12px;">998765</h5>
				<h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
				<h4 style="margin-top: 20px;">Aproveite!</h4>
			</center>
			<a class="excluirBloco black">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 4: //destaque
	?>
		<div style="position: relative">
			<center>
				<h6>ISABEL DE ANDRADE MARTINEZ SALES BR</h6>
				<h6>Saldo: R$ 0,18</h6>
				<h6>31/05/2017</h6>
			</center>
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 5: //rodapé
	?>
		<div style="position: relative">
			<h6 style="margin-right: 20px">Ofertas válidas até o término da campanha ou enquanto durar o estoque.</h6>
			<div class="div-imagem">
				<div style="height:auto; width: 100%;  display: flex; align-items: center; justify-content: center; padding: 10px; padding-right: 20px;">
					<button class="btn btn-block btn-success upload-image"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
					<input type="file" cod_registr='<?php echo $buscaAjx4; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
				</div>
			</div>
			<h6 style="font-size: 11px">Ticket de Ofertas | Marka Sistemas</h6>
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 6: //imagem
	?>
		<div style="position: relative">
			<div class="div-imagem">
				<div style="height:auto; width: 100%;  display: flex; align-items: center; justify-content: center; padding: 10px; padding-right: 20px;">
					<button class="btn btn-block btn-success upload-image"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
					<input type="file" cod_registr='<?php echo $buscaAjx4; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
				</div>
			</div>
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 7: //lista de promoções white
	?>
		<div style="position: relative">
			<center style="margin-bottom: 15px; padding: 5px; background-color: #fff; color: #000">
				<h5 style="font-weight: 900; margin-bottom: 20px; font-size: 17px;">OFERTA EM DESTAQUE</h5>
				<h4 style="font-size: 21px; margin-top: 2px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
				<h5 style="font-weight: 500; font-size:12px;">998765</h5>
				<h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
				<h4 style="margin-top: 20px;margin-bottom: 0">Aproveite!</h4>
			</center>
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 8: //habito de compras
	?>
		<div style="position: relative; text-align: left; font-size: 13px">
			<div>&emsp;•&emsp; LISADOR GTS 15ML</div>
			<div>&emsp;•&emsp; VOLTAREN 100MG RET 10'S</div>
			<div>&emsp;•&emsp; TORAGESIC 10MG 10'S</div>

			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 9: //promoções logo white
	case 19: //promoções logo white com percentual
	?>
		<div style="position: relative">
			<center style="margin-bottom: 15px; padding: 5px; background-color: #fff; color: #000">
				<h5 style="font-weight: 900; font-size: 17px;">OFERTA EM DESTAQUE</h5>
				<img style='cursor: pointer; max-width:100%; max-height: 100%;'>
				<i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
				</img>
				<h4 style="font-size: 21px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
				<h5 style="font-weight: 500; font-size:12px;">998765</h5>
				<h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
				<?php if ($buscaAjx2 == 19) { ?>
					<h4 style="font-weight: 900; font-size: 40px; margin-top: 2px"><strong>35%</strong></h4>
				<?php } ?>
				<h4 style="margin-top: 20px;margin-bottom: 0">Aproveite!</h4>
			</center>
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 10: //promoções logo black
	?>
		<div style="position: relative">
			<center style="margin-bottom: 15px; padding: 5px; background-color: #161616; color: #fff">
				<h5 style="font-weight: 900; font-size: 17px;">OFERTA EM DESTAQUE</h5>
				<img style='cursor: pointer; max-width:100%; max-height: 100%;'>
				<i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
				</img>
				<h4 style="font-size: 21px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
				<h5 style="font-weight: 500; font-size:12px;">998765</h5>
				<h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
				<h4 style="margin-top: 20px;margin-bottom: 0">Aproveite!</h4>
			</center>
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 11: // saldo cartão
	?>
		<div style="position: relative">
			<center>
				<h6>ISABEL DE ANDRADE MARTINEZ SALES BR</h6>
				<h6>Número Cartão: 1234 5678 9012 3456</h6>
				<h6>Saldo: R$ 0,18</h6>
				<h6>31/05/2017</h6>
			</center>
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 12: // grupo de desconto - 2 colunas
	?>
		<div style="position: relative;">
			<div class="row">
				<div class="col-md-6" style="text-align: left; border-right: 1px dashed gray; padding-bottom: 20px;">
					<div class="row">
						<div class="col-md-12">
							<h5 style="font-weight: 900; font-size: 50px; letter-spacing: -3px;">15%
								<br /><span style='background-color: #454545; color: #fff; font-weight: 900; padding: 2px 4px 2px 4px; font-size: 11px; letter-spacing: 1px; margin: 0;'>DE DESCONTO</span>
								<div class="push10"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div style="font-size: 11px; text-transform: uppercase; margin-top: -10px; margin-left: 5px"><b>[Nome da Categoria]</b></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 1</b><br /><small>[código]</small></div>
							<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 2</b><br /><small>[código]</small></div>
							<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 3</b><br /><small>[código]</small></div>
							<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 4</b><br /><small>[código]</small></div>
						</div>
					</div>
				</div>

				<div class="col-md-6" style="text-align: left; border-right: 1px dashed gray; padding-bottom: 20px;">
					<div class="row">
						<div class="col-md-12">
							<h5 style="font-weight: 900; font-size: 50px; letter-spacing: -3px;">25%
								<br /><span style='background-color: #454545; color: #fff; font-weight: 900; padding: 2px 4px 2px 4px; font-size: 11px; letter-spacing: 1px; margin: 0;'>DE DESCONTO</span>
								<div class="push10"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div style="font-size: 11px; text-transform: uppercase; margin-top: -10px; margin-left: 5px"><b>[Nome da Categoria]</b></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 1</b><br /><small>[código]</small></div>
							<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 2</b><br /><small>[código]</small></div>
							<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 3</b><br /><small>[código]</small></div>
							<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 4</b><br /><small>[código]</small></div>
						</div>
					</div>
				</div>
			</div>

			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;

	case 21: //grupo de desconto - 1 coluna
	?>
		<div style="position: relative">
			<center>
				<h5 style="margin-top: 5px">Veja <b>ofertas personalizadas </b> para você!</h5>
			</center>

			<div style="width: 70%;  float:left;">

				<div style="width: 100%; height: auto; text-align: left; margin: 0 0 20px 0;">
					<h5 style="font-weight: 900">[Nome da Categoria]</h5>
					<h5 style="font-weight: 900">[Nome do produto]</h5>
					<h5>[código]</h5>
				</div>

				<div style="width: 100%; height: auto; text-align: left; margin: 0 0 20px 0;">
					<h5 style="font-weight: 900">[Nome do produto]</h5>
					<h5>[código]</h5>
				</div>

				<div style="width: 100%; height: auto; text-align: left; margin: 0 0 20px 0;">
					<h5 style="font-weight: 900">[Nome do produto]</h5>
					<h5>[código]</h5>
				</div>

			</div>

			<div style="width: 29%; float:right;">
				<div style="width: 100%; text-align: center;">
					<h5 style="font-weight: 900; font-size: 50px; letter-spacing: -3px;">15%
						<br /><span style='background-color: #454545; color: #fff; font-weight: 900; padding: 2px 4px 2px 4px; font-size: 11px; letter-spacing: 1px; margin: 0;'>DE DESCONTO</span>
				</div>
			</div>


			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;

	case 15: //Código com número
	?>
		<div style="position: relative">
			<center style="margin-bottom: 15px; padding: 5px;">
				<img style='cursor: pointer; max-width:100%; max-height: 100%' src="/images/codigo-barras.png" width="320" height="70"></img>
				<h5 style="margin-bottom: 20px;">1234567890123</h5>
			</center>
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 16: //Código sem número
	?>
		<div style="position: relative">
			<center style="margin-bottom: 15px; padding: 5px;">
				<img style='cursor: pointer; max-width:100%; max-height: 100%' src="/images/codigo-barras.png" width="320" height="70"></img>
			</center>
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 17: //Última Compra
	?>
		<div style="position: relative">
			<center><small>Dat. Referência: 01/01/2017</small></center>
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin-top: -10px;" aria-hidden="true"></i>
			</a>
			<hr class="divisao" style="margin-top: 5px" />
		</div>
	<?php
		break;
	case 18: //Produtos modelo 2 (desconto white)
	case 20: //Produtos modelo 3 (desconto black)
	?>
		<div style="position: relative">
			<center>
				<h5 style="margin-top: 5px">Veja <b>ofertas personalizadas </b> para você!</h5>
			</center>

			<?php if ($buscaAjx2 == 20) { ?>
				<div style="background: #000; display: flex; color: #fff; padding: 0 0 0 5px; margin: 0 0 10px 0;">
				<?php } ?>

				<div style="width: 70%;  float:left;">
					<div style="width: 100%; height: auto; text-align: left;">
						<h5 style="font-weight: 900">[Nome da Categoria 3]</h5>
						<h5 style="font-weight: 900">[Nome do produto]</h5>
						<h5 style="font-weight: 900">de: R$ [de1] por: R$ [por1]</h5>
						<h5>[código]</h5>
					</div>
				</div>

				<div style="width: 29%; float:right;">
					<div style="width: 100%; text-align: center;">
						<h5 style="font-weight: 900; font-size: 45px; letter-spacing: -3px;">35%</h5>
					</div>
				</div>

				<?php if ($buscaAjx2 == 20) { ?>
				</div>
			<?php } else {
			?>
				<hr />
			<?php } ?>

			<?php if ($buscaAjx2 == 20) { ?>
				<div style="background: #000; display: flex; color: #fff; padding: 0 0 0 5px; margin: 0 0 10px 0;">
				<?php } ?>

				<div style="width: 70%;  float:left;">
					<div style="width: 100%; height: auto; text-align: left;">
						<h5 style="font-weight: 900">[Nome da Categoria 3]</h5>
						<h5 style="font-weight: 900">[Nome do produto]</h5>
						<h5 style="font-weight: 900">de: R$ [de1] por: R$ [por1]</h5>
						<h5>[código]</h5>
					</div>
				</div>

				<div style="width: 29%; float:right;">
					<div style="width: 100%; text-align: center;">
						<h5 style="font-weight: 900; font-size: 45px; letter-spacing: -3px;">35%</h5>
					</div>
				</div>

				<?php if ($buscaAjx2 == 20) { ?>
				</div>
			<?php } else {
			?>
				<hr />
			<?php } ?>

			<?php if ($buscaAjx2 == 20) { ?>
				<div style="background: #000; display: flex; color: #fff; padding: 0 0 0 5px; margin: 0 0 10px 0;">
				<?php } ?>

				<div style="width: 70%;  float:left;">
					<div style="width: 100%; height: auto; text-align: left;">
						<h5 style="font-weight: 900">[Nome da Categoria 3]</h5>
						<h5 style="font-weight: 900">[Nome do produto]</h5>
						<h5 style="font-weight: 900">de: R$ [de1] por: R$ [por1]</h5>
						<h5>[código]</h5>
					</div>
				</div>

				<div style="width: 29%; float:right;">
					<div style="width: 100%; text-align: center;">
						<h5 style="font-weight: 900; font-size: 45px; letter-spacing: -3px;">35%</h5>
					</div>
				</div>

				<?php if ($buscaAjx2 == 20) { ?>
				</div>
			<?php } else {
			?>
				<hr />
			<?php } ?>

			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 22: //texto livre
	?>
		<div style="position: relative">
			<div class="div-texto">
				<div style="height:auto; width: 100%;  display: flex; align-items: center; justify-content: center; padding: 10px; padding-right: 20px;">
					<a href="javascript:void(0)" class="btn btn-block btn-success addBox" data-url="action.do?mod=<?php echo fnEncode(1597) ?>&id=<?php echo fnEncode($buscaAjx1) ?>&idr=<?php echo fnEncode($buscaAjx4) ?>&pop=true" data-title="Editar Texto" style="color: white; text-decoration: none;"><span class="far fa-text-height"></span>&nbsp;&nbsp; Insira aqui seu texto</a>
					<input type="file" cod_registr='<?php echo $buscaAjx4; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
				</div>
			</div>
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>
	<?php
		break;
	case 23: //Aniversariante

		$sqlMsg = "SELECT * FROM COMUNICACAO_MODELO_TKT WHERE COD_EMPRESA = $cod_empresa LIMIT 1";
		// echo($sql);
		$arrayMsg = mysqli_query(connTemp($cod_empresa, ""), $sqlMsg);

		$qrBuscaComunicacao = mysqli_fetch_assoc($arrayMsg);

		$temMsg = mysqli_num_rows($arrayMsg);

		if (isset($qrBuscaComunicacao['DES_TEXTO_SMS'])) {
			$msg = $qrBuscaComunicacao['DES_TEXTO_SMS'];
		} else {
			$msg = "";
		}

		$dia_hoje = date('d');
		$mes_hoje = date('m');
		$ano_hoje = date('Y');
		$dia_nascime = $dia_hoje;
		$mes_nascime = $mes_hoje;
		$ano_nascime = '2000';

		$NOM_CLIENTE = explode(" ", ucfirst(strtolower("Fulano")));
		$TEXTOENVIO = str_replace('<#NOME>', $NOM_CLIENTE[0], $msg);
		$TEXTOENVIO = str_replace('<#SALDO>', fnValor('9.99', 2), $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#NOMELOJA>',  'Unidade Tal', $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#ANIVERSARIO>', '01/01/2000', $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#DATAEXPIRA>', '01/01/2999', $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#EMAIL>', 'exemplo@email.com', $TEXTOENVIO);
		$msgsbtr = nl2br($TEXTOENVIO, true);
		$msgsbtr = str_replace('<br />', ' \n ', $msgsbtr);
		$msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);


	?>
		<div style="position: relative">
			<div class="div-aniv">
				<?php
				if ($msgsbtr != "") {
				?>
					<div class="imagemTicket text-center f18">
						<a href="javascript:void(0)" class="addBox" data-url="action.do?mod=<?php echo fnEncode(1916) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?= fnEncode($qrBuscaComunicacao['COD_COMUNIC']) ?>&pop=true" data-title="Editar Texto"><?= $msgsbtr ?></a>
					</div>
				<?php
				} else {
				?>
					<div class="imagemTicket">
						<a href="javascript:void(0)" class="btn btn-block btn-xs btn-info addBox" data-url="action.do?mod=<?php echo fnEncode(1916) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Editar Texto"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp; Configurar</a>
						<input type="file" cod_registr='<?php echo $buscaAjx4; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
					</div>
				<?php
				}
				?>
			</div>
			<a class="excluirBloco">
				<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
			</a>
			<hr class="divisao" />
		</div>

		<!-- <div style="position: relative">
				<div class="div-imagem">
					<div  style="height:auto; width: 100%;  display: flex; align-items: center; justify-content: center; padding: 10px; padding-right: 20px;">
						<a href="javascript:void(0)" class="btn btn-block btn-xs btn-info addBox" data-url="action.do?mod=<?php echo fnEncode(1916) ?>&id=<?php echo fnEncode($buscaAjx1) ?>&pop=true" data-title="Editar Texto"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp; Configurar</a>
						<input type="file" cod_registr='<?php echo $buscaAjx4; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;"/>
					</div>
				</div>
				<a class="excluirBloco">
					<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
				</a>					
				<hr class="divisao"/>
			</div> -->
<?php
		break;

	case 99:
		$sql = "CALL SP_ALTERA_MODELOTEMPLATETKT ('" . $buscaAjx3 . "', 0, 0, 0, ' ', '" . $_SESSION["SYS_COD_USUARIO"] . "', 'EXC' )";
		mysqli_query(connTemp($buscaAjx1, ""), trim($sql));
		break;
}


//fnEscreve("aee....");
//fnEscreve($txtBloco);
//fnEscreve($buscaAjx1);
//fnEscreve($buscaAjx2);
//fnMostraForm();

//update da ordenação
//$sql2 = $montaUpdate;		
//$arrayQuery2 = mysqli_multi_query($connAdm->connAdm(),$sql2);
//$qrOrdena = mysqli_fetch_assoc($arrayQuery2);
//fnEscreve($sql2);		

?>