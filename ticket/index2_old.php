<?php
include "../_system/_functionsMain.php";

$_SESSION["tkt"]=2;	

	//echo fnDebug('true');

	$parametros = fnDecode($_GET['tkt']);
	$arrayCampos = explode(";", $parametros);;
	
	$cod_empresa = $arrayCampos[0];
	$num_cartao = $arrayCampos[1];
	
	//fnEscreve(fnEncode("7;527000000006"));
	//fnEscreve(fnDecode("0dZNjqJqwg4GZIfw3PyRzg¢¢"));
	//fnEscreve($cod_empresa);
	//fnEscreve($NUM_CARTAO);

	//busca dados da configuração	
	if (is_numeric($cod_empresa)){
		//busca dados da empresa
		$sql = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA = '".$cod_empresa."' ";
		
		//fnTesteSql(connTemptkt($connAdm->connAdm(),$cod_empresa,""),$sql);
		$arrayQuery = mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),trim($sql)) or die(mysqli_error());
		$qrBuscaConfiguracao = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaConfiguracao)){
			$cod_configu = $qrBuscaConfiguracao['COD_CONFIGU'];
			$log_ativo_tkt = $qrBuscaConfiguracao['LOG_ATIVO_TKT'];
			$cod_template_tkt = $qrBuscaConfiguracao['COD_TEMPLATE_TKT'];
			$qtd_compras_tkt = $qrBuscaConfiguracao['QTD_COMPRAS_TKT'];
			$qtd_ofertas_tkt = $qrBuscaConfiguracao['QTD_OFERTAS_TKT'];
			$qtd_categor_tkt = $qrBuscaConfiguracao['QTD_CATEGOR_TKT'];
			$num_historico_tkt = $qrBuscaConfiguracao['NUM_HISTORICO_TKT'];
			$min_historico_tkt = $qrBuscaConfiguracao['MIN_HISTORICO_TKT'];
			$max_historico_tkt = $qrBuscaConfiguracao['MAX_HISTORICO_TKT'];
			$cod_blklist = $qrBuscaConfiguracao['cod_blklist'];
		}
												
	}else {
			
			fnEscreve(";( Ticket inválido. ");
	}	

	//busca nome do cliene
	$sql1 = "SELECT NOM_CLIENTE, COD_CLIENTE FROM CLIENTES where NUM_CARTAO = '".$num_cartao."' ";
	
	//fnTesteSql(connTemptkt($connAdm->connAdm(),$cod_empresa,""),$sql);
	$arrayQuery1 = mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),trim($sql1)) or die(mysqli_error());
	$qrBuscaNomeCli = mysqli_fetch_assoc($arrayQuery1);
		
	if (isset($qrBuscaNomeCli)){
		$nom_cliente = $qrBuscaNomeCli['NOM_CLIENTE'];
		$cod_cliente = $qrBuscaNomeCli['COD_CLIENTE'];
	}
	//verifica se nome cliente está preenchido
	if (!empty($nom_cliente)){
		$arrayNome = explode(" ", $nom_cliente);
		$nomeLimpo = $arrayNome[0];
	}else {
		$nomeLimpo = "Cliente";
	}
	
//monta ticket 
//fnEscreve($cod_empresa);
//fnEscreve($log_ativo_tkt);
//fnEscreve($qtd_compras_tkt);
//fnEscreve($cod_template_tkt);

?>

<style type="text/css">
	body {
		/*font-family: Tahoma,Arial,sans-serif;
		font-family: Trebuchet MS,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Tahoma,sans-serif;*/
		font-family: Arial, Lucida Grande,Lucida Sans Unicode,Lucida Sans,Tahoma,sans-serif;
		color: #454545;
		-webkit-print-color-adjust: exact;
	}
	
	.bloco{
		position: relative; 
		margin-top: 25px;
		text-align:center;
	}
	
	.lista{
		list-style-type: circle;
		text-align: left;
		font-size: 13px;
		font-weight: 400;
	}
	
	.upload-image{
		max-width:100%;
		max-height: 100%;
	}
	
	.image-container{ 
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
	}
</style>

<div style="width: 400px;">
	<?php																	
		$sql = "SELECT MODELOTEMPLATETKT.COD_REGISTR,
					   MODELOTEMPLATETKT.COD_EMPRESA,
					   MODELOTEMPLATETKT.COD_TEMPLATE,
					   MODELOTEMPLATETKT.COD_BLTEMPL,
					   MODELOTEMPLATETKT.DES_IMAGEM
				FROM   MODELOTEMPLATETKT
				WHERE  MODELOTEMPLATETKT.COD_EMPRESA = $cod_empresa 
				AND    MODELOTEMPLATETKT.COD_TEMPLATE = 2
				ORDER BY NUM_ORDENAC";
		
		//fnEscreve($sql);
			
		$arrayQuery = mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,''),$sql) or die(mysqli_error());

	
		while ($qrListaModelos = mysqli_fetch_assoc($arrayQuery))
		  {
			  ?>
			  <?php
				//fnEscreve($qrListaModelos['COD_BLTEMPL']);
				switch ($qrListaModelos['COD_BLTEMPL']) {	
					case 1://nome do cliente
		
					?>
						<div class="bloco">
							<h3 style="margin: 5px; font-size:26px"><b> <?php echo $nomeLimpo; ?></b></h3>
							<h5 style="margin: 5px; font-size:15px"><b>LEVE TAMBÉM...</b></h5>
						</div>
					<?php
					break;     
					case 2://Pontos modelo
					?>
						<div class="bloco">
							<center>
								<h5 style="margin-top: 5px">Veja <b>5 ofertas personalizadas</b> para você!</h5>
							</center>
							<div style="display: flow-root">			
								<div style="width: 55%; float: left; text-align: left;">
									<h5 style="font-weight: 900">[obs1]</h5>
									<h5>[imagem1]</h5>
								</div>
								<div>
									<h5 style="font-weight: 900">de: R$ [de1]</h5>
									<h5 style="font-weight: 900">por: R$ [por1]</h5>
								</div>
								<hr/>
							</div>
							<div style="display: flow-root">			
								<div style="width: 55%; float: left; text-align: left;">
									<h5 style="font-weight: 900">[obs2]</h5>
									<h5>[imagem2]</h5>
								</div>
								<div>
									<h5 style="font-weight: 900">de: R$ [de2]</h5>
									<h5 style="font-weight: 900">por: R$ [por2]</h5>
								</div>
								<hr/>
							</div>
							<div style="display: flow-root">			
								<div style="width: 55%; float: left; text-align: left;">
									<h5 style="font-weight: 900">[obs3]</h5>
									<h5>[imagem3]</h5>
								</div>
								<div>
									<h5 style="font-weight: 900">de: R$ [de3]</h5>
									<h5 style="font-weight: 900">por: R$ [por3]</h5>
								</div>
								<hr/>
							</div>							
						</div>
					<?php
					break; 		
					case 3://lista de promoções black
					?>
						<div class="bloco">
							<center style="margin-bottom: 20px; padding: 5px; background-color: #161616; color: #fff">
								<h5 style="font-weight: 900; margin-bottom: 2px; font-size: 17px;">OFERTA EM DESTAQUE</h5>
								<h3 style="font-weight: 900; margin-top: 2px; margin-bottom: 2px; font-size: 26px;">LEVE 3 PAGUE 2</h3>
								<h4 style="font-size: 21px; margin-top: 2px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
								<h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
								<h4 style="margin-top: 20px; font-size: 19px">Aproveite!</h4>
							</center>			
						</div>
					<?php
					break;		
					case 4://destaque
					?>
						<div class="bloco">
							<center>
								<h6>ISABEL DE ANDRADE MARTINEZ SALES BR</h6>
								<h6>Saldo: R$ 0,18</h6>
								<h6>31/05/2017</h6>
							</center>
						</div>
					<?php
					break; 		
					case 5://rodape
					?>
						<div class="bloco">
							<center>
								<h6 style="margin-right: 20px; margin-bottom:10px; font-size: 13px;font-weight: 400">Ofertas válidas até o término da campanha ou enquanto durar o estoque.</h6>
								<div class="div-imagem">
									<?php
										if (empty(trim($qrListaModelos['DES_IMAGEM']))) {
											?>
											<div class="image-container">
												sem imagem cadastrada
											</div>
											<?php
										}else{
											?>
											<div class="image-container">
												<img src='http://img.bunker.mk/media/clientes/<?php echo $cod_empresa ?>/<?php echo $qrListaModelos['DES_IMAGEM'];?>' style='max-width:100%; max-height: 100%'>
											</div>
											<?php
										}
									?>
								</div>																		
								<h6 style="font-size: 11px;font-weight: 400;margin-top:10px;">Ticket de Ofertas | Marka Fidelização</h6>
							</center>
						</div>
					<?php
					break; 		
					case 6://imagem
					?>
						<div class="bloco">
							<?php
								if (empty(trim($qrListaModelos['DES_IMAGEM']))) {
									?>
									<div class="image-container">
										sem imagem cadastrada
									</div>
									<?php
								}else{
									?>
									<div class="image-container">
										<img src='http://img.bunker.mk/media/clientes/<?php echo $cod_empresa ?>/<?php echo $qrListaModelos['DES_IMAGEM'];?>' style='max-width:100%; max-height: 100%'>
									</div>
									<?php
								}
							?>																	
						</div>
					<?php
					break;		
					case 7://lista de promoções white
					?>
						<div class="bloco">
							<center style="margin-bottom: 15px; padding: 5px; background-color: #fff; color: #000">
								<h5 style="font-weight: 900; margin-bottom: 2px; font-size: 17px;">OFERTA EM DESTAQUE</h5>
								<h3 style="font-weight: 900; margin-top: 2px; margin-bottom: 2px; font-size: 26px;">LEVE 3 PAGUE 2</h3>
								<h4 style="font-size: 21px; margin-top: 2px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
								<h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
								<h4 style="margin-top: 20px;margin-bottom: 0">Aproveite!</h4>
							</center>
						</div>
					<?php
					break;	
					case 8://habito de compras
					
						//busca hábito de compra 
						//fnEscreve($qtd_compras_tkt);
						//fnEscreve($min_historico_tkt);
						//fnEscreve($max_historico_tkt);
						fnEscreve($cod_blklist);
						
						$sql8 = "SELECT  DISTINCT C.DES_PRODUTO FROM VENDAS A,ITEMVENDA B, PRODUTOCLIENTE C
								 WHERE COD_CLIENTE = $cod_cliente AND
								 B.COD_PRODUTO=C.COD_PRODUTO AND
								 A.DAT_CADASTR >= ADDDATE( NOW(), INTERVAL -$max_historico_tkt DAY) AND
								 A.DAT_CADASTR <= ADDDATE( NOW(), INTERVAL -$min_historico_tkt DAY)
								 ORDER BY rand(DES_PRODUTO) LIMIT $qtd_compras_tkt ";
						
						//fnTesteSql(connTemptkt($connAdm->connAdm(),$cod_empresa,""),$sql);
						$arrayQuery8 = mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),trim($sql8)) or die(mysqli_error());
						//fnEscreve($sql8);
						?>
						<div class="bloco">
							<ul class="lista">
							<?php
							while ($qrBuscaHabitoCli = mysqli_fetch_assoc($arrayQuery8))
							  {	
								echo"
									  <li>".$qrBuscaHabitoCli['DES_PRODUTO']."</li> 
									"; 
								}											
							?>
							</ul>		
						</div>
					<?php
					break;

				}
				
				?>
					
				<?php
		  }											
		?>
	</ul>
</div>











<?php
LOG_DB(connTemptkt($connAdm->connAdm(),$cod_empresa,""),connTemptkt($connAdm->connAdm(),$cod_empresa,""));
process_kill(connTemptkt($connAdm->connAdm(),$cod_empresa,""));
cache_query (connTemptkt($connAdm->connAdm(),$cod_empresa,""),1);
?>