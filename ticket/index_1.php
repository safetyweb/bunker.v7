<!DOCTYPE html>
<html>
<head>
	
	<meta name="viewport" content="width=device-width" />
    <title>Ticket de Ofertas</title>
	
</head>

<body onload="JavaScript:window.print();">

<link href='http://fonts.googleapis.com/css?family=Lato:700,900' rel='stylesheet' type='text/css'>

<style type="text/css">
	body {
		font-family: 'Lato', sans-serif;
		font-weight: 700;
		color: #000;
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
	
	.push {clear:both;} 
	.push1 {height: 1px; clear:both;} 
	.push5 {height: 5px; clear:both;} 
	.push10 {height: 10px; clear:both;} 
	.push20 {height: 20px; clear:both;} 
	.borda {border:1px solid #000;}
	
	@page  
	{ 
		size: auto;   /* auto is the initial value */ 

		/* this affects the margin in the printer settings */ 
		margin: 0mm 0mm 0mm 0mm;  
	} 
	
</style>

<?php
include "../_system/_functionsMain.php";
      
//$_SESSION["tkt"]=2;	

//echo fnDebug('true');
	//TESTE URL - 0dZNjqJqwg740eZxaPjrBP9sAIdp£Kcp£h
	
	$parametros = fnDecode($_GET['tkt']);
	
	if (isset($_GET['nome'])){ 
		$nomeSimul = $_GET['nome'];
		$arrayNomeSimul = explode(" ", $nomeSimul);
		$nomeSimulLimpo = $arrayNomeSimul[0];
	}
	
	$arrayCampos = explode(";", $parametros);

	$cod_empresa = $arrayCampos[0];
	$num_cartao = $arrayCampos[1];
	$cod_loja = $arrayCampos[2];
	
	//fnEscreve($cod_loja);

	//busca dados da configuração	
	if (is_numeric($cod_empresa)){
		//busca dados da empresa
		$sql = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA = '".$cod_empresa."'  and LOG_ATIVO_TKT = 'S' ";
		
		//fnTesteSql(connTemptkt($connAdm->connAdm(),$cod_empresa,""),$sql);
		$arrayQuery = mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),trim($sql)) or die(mysqli_error());
		$qrBuscaConfiguracao = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaConfiguracao)){
			$cod_configu = $qrBuscaConfiguracao['COD_CONFIGU'];
			$log_ativo_tkt = $qrBuscaConfiguracao['LOG_ATIVO_TKT'];
			$cod_template_tkt = $qrBuscaConfiguracao['COD_TEMPLATE_TKT'];
			$qtd_compras_tkt = $qrBuscaConfiguracao['QTD_COMPRAS_TKT'];
			$qtd_ofertas_tkt = $qrBuscaConfiguracao['QTD_OFERTAS_TKT'];
			$qtd_produtos_tkt = $qrBuscaConfiguracao['QTD_PRODUTOS_TKT'];
			$qtd_categor_tkt = $qrBuscaConfiguracao['QTD_CATEGOR_TKT'];
			$num_historico_tkt = $qrBuscaConfiguracao['NUM_HISTORICO_TKT'];
			$min_historico_tkt = $qrBuscaConfiguracao['MIN_HISTORICO_TKT'];
			$max_historico_tkt = $qrBuscaConfiguracao['MAX_HISTORICO_TKT'];
			$cod_blklist = $qrBuscaConfiguracao['COD_BLKLIST'];
			$log_emisdia = $qrBuscaConfiguracao['LOG_EMISDIA'];
			
		}else {			
			echo(";| Ticket desabilitado");
			}
												
	}else {
			
			echo(";( Ticket inválido");
	}	

	//busca nome do cliente
	$sql1 = "SELECT NOM_CLIENTE, COD_CLIENTE FROM CLIENTES where NUM_CARTAO = '".$num_cartao."'  AND COD_EMPRESA = $cod_empresa ";
	
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

	if (!empty($nomeSimul)){
		$nomeLimpo = $nomeSimulLimpo;
		$nom_cliente = $nomeSimul;
	}
	
	//select cod_persona from personaclientes where cod_cliente = 777751 and cod_empresa = 3
	
	//monta ticket 
	//fnEscreve($cod_empresa);

?>

<div style="width: 382px;">
	<?php

		//montagem do array - código da template
		//fnEscreve($cod_template_tkt);
		
		$sql = "SELECT MODELOTEMPLATETKT.COD_REGISTR,
					   MODELOTEMPLATETKT.COD_EMPRESA,
					   MODELOTEMPLATETKT.COD_TEMPLATE,
					   MODELOTEMPLATETKT.COD_BLTEMPL,
					   MODELOTEMPLATETKT.DES_IMAGEM
				FROM   MODELOTEMPLATETKT
				WHERE  MODELOTEMPLATETKT.COD_EMPRESA = $cod_empresa 
				AND    MODELOTEMPLATETKT.COD_TEMPLATE = $cod_template_tkt
				AND    MODELOTEMPLATETKT.COD_EXCLUSA is null
				ORDER BY NUM_ORDENAC";
		
		//fnEscreve($sql);
		
		$arrayQuery = mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,''),$sql) or die(mysqli_error());
	
		while ($qrListaModelos = mysqli_fetch_assoc($arrayQuery))
		  {
			  ?>
			  <?php
				
				switch ($qrListaModelos['COD_BLTEMPL']) {	
					case 1://nome do cliente		
					?>
						<div class="bloco">
							<h3 style="margin: 5px; font-size:26px;"><b> <?php echo $nomeLimpo; ?></b></h3>
							<h5 style="margin: 5px; font-size:15px;"><b>LEVE TAMBÉM...</b></h5>
						</div>
					<?php
					break;     
					case 2://lista de produtos
					?>
						<div class="bloco">
							<center>
								<h5 style="margin: 0; font-size:18px">Veja <strong><?php echo $qtd_produtos_tkt; ?> ofertas personalizadas</strong> para você!</h5>
							</center>
							<div style="display: flow-root;">
								<?php
                                                                $sql2="SELECT C.COD_EXTERNO,C.DES_IMAGEM, A.* FROM PRODUTOTKT A,CATEGORIATKT B, PRODUTOCLIENTE C
                                                                        where  A.COD_EMPRESA = $cod_empresa AND
                                                                           A.COD_CATEGORTKT = B.COD_CATEGORTKT AND
                                                                           A.COD_PRODUTO = C.COD_PRODUTO AND										   
                                                                           B.LOG_DESTAK <> 'S' AND 
                                                                           A.LOG_PRODTKT = 'S' AND 
                                                                           ((A.COD_UNIVEND_AUT = '0') OR (FIND_IN_SET('$cod_loja',A.COD_UNIVEND_AUT))) AND
                                                                           ((A.COD_UNIVEND_BLK = '0') OR (!FIND_IN_SET('$cod_loja',A.COD_UNIVEND_BLK))) AND
                                                                           ((A.DAT_INIPTKT <= NOW()) OR ( A.DAT_INIPTKT IS NULL) AND 
                                                                           (A.DAT_FIMPTKT >= NOW()) OR ( A.DAT_FIMPTKT IS NULL))   
                                                                           ORDER BY B.NUM_ORDENAC, rand() LIMIT $qtd_produtos_tkt";                                                              
							
								//fnTesteSql(connTemptkt($connAdm->connAdm(),$cod_empresa,""),$sql);
								$arrayQuery2 = mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),trim($sql2)) or die(mysqli_error());
								//fnEscreve($sql2);
								?>						
								<div class="bloco" style="text-align: left; font-size: 14px;">							
									<?php
                                                                                         //depois da validade terminar busco de novo     
                                                $sql="SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_cliente and COD_EMPRESA=".$cod_empresa." and LOG_EMISDIA='S' and DAT_VALIDADE >= '".date('Y-m-d')."' ORDER by COD_GERAL DESC limit 1;";
                                                $misdiatkt=mysqli_fetch_assoc(mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),$sql)); 
                                                $OFERTASTKT=unserialize($misdiatkt['DES_TICKET']);
                                                
                                                                       
									$BLTEMPL=$qrListaModelos['COD_BLTEMPL'];
									$ArrayProdutos = array($BLTEMPL=>''); 
                                                                                
									while ($qrBuscaProdTkt = mysqli_fetch_assoc($arrayQuery2))
									  {	
										echo"
											<div class='' style='width: 55%; float: left; text-align: left;'>
												<span style='font-weight: 700;'>".$qrBuscaProdTkt['NOM_PRODTKT']."</span>
												<div class='push1'></div>
												<span style='font-weight: 700; font-size: 13px;'>Código: ".$qrBuscaProdTkt['COD_EXTERNO']."</span>
												
											</div>
											<div class='' style='text-align: right;'>
												<span style='font-weight: 700; margin: 0;'>de: R$ ".fnValor($qrBuscaProdTkt['VAL_PRODTKT'],2)."</span><br/>
												<span style='font-weight: 900; font-size: 16px; margin: 0;'>por: R$ ".fnValor($qrBuscaProdTkt['VAL_PROMTKT'],2)."</span>
											</div>
											<div class='push'></div>
											<hr style='border: none; height: 1px; color: #000; background-color: #000; '/>
											<div class='push5'></div>
											";
											
											//montagem do array
											$produtoLista = $qrBuscaProdTkt['COD_PRODUTO'].",".$produtoLista;
                                                                                        
											$produtoListaCod= $qrBuscaProdTkt['COD_PRODUTO'];
											$produtoListaVAL= fnValor($qrBuscaProdTkt['VAL_PRODTKT'],2);
											$produtoListaPROM= fnValor($qrBuscaProdTkt['VAL_PROMTKT'],2);
																		
                                        }
  									?>								
								</div>							

							</div>
												
						</div>
					<?php
					break; 		
					case 3://lista de promoções black
					case 10://lista de promoções black com imagem
					
					case 7://lista de promoções white
					case 9://lista de promoções white com imagem
					
					//bloco black
					if ($qrListaModelos['COD_BLTEMPL'] == 3 || $qrListaModelos['COD_BLTEMPL'] == 10){
						$cor_fundo = "#161616";
						$cor_texto = "#fff";
					} else{
						$cor_fundo = "#fff";
						$cor_texto = "#000";
					}
					
					//fnEscreve($qrListaModelos['COD_BLTEMPL']);
									
					?>
					
						<div class="bloco">
							<center style="margin-bottom: 20px; padding: 5px; background-color: <?php echo $cor_fundo; ?>; color: <?php echo $cor_texto; ?>; ">
								<?php
                                                
								$sql10="SELECT C.COD_EXTERNO,C.DES_IMAGEM, A.* FROM PRODUTOTKT A,CATEGORIATKT B, PRODUTOCLIENTE C
                                                                        where  A.COD_EMPRESA = $cod_empresa AND
                                                                           A.COD_CATEGORTKT = B.COD_CATEGORTKT AND
                                                                           A.COD_PRODUTO = C.COD_PRODUTO AND										   
                                                                           A.LOG_OFERTAS = 'S' AND 
                                                                           ((A.COD_UNIVEND_AUT = '0') OR (FIND_IN_SET('$cod_loja',A.COD_UNIVEND_AUT))) AND
                                                                           ((A.COD_UNIVEND_BLK = '0') OR (!FIND_IN_SET('$cod_loja',A.COD_UNIVEND_BLK))) AND
                                                                           ((A.DAT_INIPTKT <= NOW()) OR ( A.DAT_INIPTKT IS NULL) AND 
                                                                           (A.DAT_FIMPTKT >= NOW()) OR ( A.DAT_FIMPTKT IS NULL))   
                                                                           ORDER BY B.NUM_ORDENAC, rand() LIMIT $qtd_ofertas_tkt";

								//fnEscreve($sql10);
								
								$arrayQuery10 = mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),trim($sql10)) or die(mysqli_error());
								if ($qtd_ofertas_tkt > 1){$txtOferta = "OFERTAS";} else {$txtOferta = "OFERTA";}
								?>						
								<div class="bloco" style="text-align: left; font-size: 13px">
									<center>
									<h5 style="font-weight: 900; margin-bottom: 2px; font-size: 17px;"><?php echo $txtOferta; ?> EM DESTAQUE</h5>
									<div class="push20"></div>
									
									<?php
									//inicializa array geral 
									$BLTEMPL=$qrListaModelos['COD_BLTEMPL'];
									$ArrayOferta=array($BLTEMPL=>''); 
                                                                         
									while ($qrBuscaOferta10 = mysqli_fetch_assoc($arrayQuery10))
									  {	
										//se é bloco com imagem
										if ($qrListaModelos['COD_BLTEMPL'] != 3 && $qrListaModelos['COD_BLTEMPL'] != 7 ){
										echo"
											<img style='cursor: pointer; max-width:100%; max-height: 100%'>
												<img src='http://img.bunker.mk/media/clientes/$cod_empresa/produtos/".$qrBuscaOferta10['DES_IMAGEM']."' style='max-width:100%; max-height: 100%'>
											</img>
										"; 	
										}
								  
										echo"
											<h4 style='font-size: 21px; margin-top: 2px; margin-bottom: 2px'>".$qrBuscaOferta10['NOM_PRODTKT']."</h4>
											<h4 style='font-weight: 900; font-size: 23px; margin-top: 2px'>De R$ ".fnValor($qrBuscaOferta10['VAL_PRODTKT'],2)." Por R$ ".fnValor($qrBuscaOferta10['VAL_PROMTKT'],2)."</h4>
											<div class='push15'></div>
											"; 
										
										$produtoOferta = $qrBuscaOferta10['COD_PRODUTO'].",".$produtoOferta; 	
                                                                                
										      
										}
									?>		
									<h4 style="margin-top: 20px; font-size: 21px">Aproveite!</h4>
									</center>
								</div>	
							</center>			
						</div>
					<?php
					break;		
					case 4://destaque
                                                
                                               
                                                
						$procsaldo="CALL SP_CONSULTA_SALDO_CLIENTE ($cod_cliente)";
						$SALDO_CLIENTE = mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),trim($procsaldo)) or die(mysqli_error());
						$rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);					
						?>
							<div class="bloco">
								<center>
									<div style="font-size: 16px; line-height: 22px;"><span style="font-weight:900;"><?php echo $nom_cliente; ?></span> <br/>
									Seu saldo é: R$ <?php echo fnValor($rowSALDO_CLIENTE['TOTAL_CREDITO'],2); ?> <br/>
									<?php echo date("d/m/Y"); ?></div>
								</center>
							</div>
						<?php
                                                                      
						$ArraySaldo=array($qrListaModelos['COD_BLTEMPL']=> array("TOTAL_CREDITO" =>fnValor($rowSALDO_CLIENTE['TOTAL_CREDITO'],2)));
                                                                        
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
												<img src='http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $qrListaModelos['DES_IMAGEM'];?>' style='max-width:100%; max-height: 100%'>
											</div>
											<?php
                                                                                       
                                                                                             
										} 
                                                                              
										$ArrayRodaPe=array($qrListaModelos['COD_BLTEMPL']=> array("DES_IMAGEM" =>$qrListaModelos['DES_IMAGEM']));
                                                                            
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
								
								$ArrayImagem=array($qrListaModelos['COD_BLTEMPL']=> array("DES_IMAGEM" =>$qrListaModelos['DES_IMAGEM']));
								                                
							?>																	
						</div>
					<?php
					break;		
					case 8://habito de compras
					
						//busca hábito de compra					
						//se tem lista de exclusão de hábito
						if ($cod_blklist != 0){
							//busca categorias excluidas
							$sql8_2 = "select COD_CATEGOR from blacklisttkt where cod_empresa = $cod_empresa AND TIP_BLKLIST='CAT' ";							
							//fnTesteSql(connTemptkt($connAdm->connAdm(),$cod_empresa,""),$sql8_2);
							$arrayQuery8_2 = mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),trim($sql8_2)) or die(mysqli_error());
							$qrBuscaCatBlkList = mysqli_fetch_assoc($arrayQuery8_2);								
							if (isset($qrBuscaCatBlkList)){
								$cod_categorBlk = $qrBuscaCatBlkList['COD_CATEGOR'];
								$sqlExclusao = "AND C.COD_CATEGOR NOT IN ($cod_categorBlk)";
							} else {
								$cod_categorBlk = "0";
								$sqlExclusao = "";
								}	
						}
						
						//se tem categoria de exclusão
						$sql8="SELECT  DISTINCT C.DES_PRODUTO, C.COD_PRODUTO,C.COD_EXTERNO 
									FROM VENDAS A,ITEMVENDA B, PRODUTOCLIENTE C
									WHERE A.COD_CLIENTE = $cod_cliente AND
									A.COD_VENDA=B.COD_VENDA AND
									B.COD_PRODUTO=C.COD_PRODUTO AND
									C.COD_EMPRESA=$cod_empresa  AND
									A.DAT_CADASTR >= ADDDATE( NOW(), INTERVAL - $max_historico_tkt DAY) AND
									A.DAT_CADASTR <= ADDDATE( NOW(), INTERVAL - $min_historico_tkt DAY) 
									$sqlExclusao
									ORDER BY rand(DES_PRODUTO) LIMIT $qtd_compras_tkt ";
						
						//fnTesteSql(connTemptkt($connAdm->connAdm(),$cod_empresa,""),$sql);
						$arrayQuery8 = mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),trim($sql8)) or die(mysqli_error());
						//fnEscreve($sql8);
						?>						
						<div class="bloco" style="text-align: left; font-size: 14px">							
							<?php
                                                        $BLTEMPL=$qrListaModelos['COD_BLTEMPL'];
                                                        $ArrayHabitos=array($BLTEMPL=>''); 
							while ($qrBuscaHabitoCli = mysqli_fetch_assoc($arrayQuery8))
							  {	
								echo"
									  <div style='margin-bottom: 3px;'>&emsp; &bull; &nbsp; ".$qrBuscaHabitoCli['DES_PRODUTO']."  
									  <div class='push1'></div>
									  <span style='font-weight: 700; margin: 0 0 0 34px; font-size: 13px;'>Código: ".$qrBuscaHabitoCli['COD_EXTERNO']."</span></div>
									  
									";
								$produtoHabito = $qrBuscaHabitoCli['COD_PRODUTO'].",".$produtoHabito; 
								
								$ArrayHabitos[$BLTEMPL][] =   array("COD_PRODUTO"=>$qrBuscaHabitoCli['COD_PRODUTO'],    
																	"DES_PRODUTO" =>$qrBuscaHabitoCli['DES_PRODUTO']
																	);

								}
                                                                
							?>								
						</div>
					<?php
					break;
					case 11://saldo com cartão
					
						$procsaldo="CALL SP_CONSULTA_SALDO_CLIENTE ($cod_cliente)";
						$SALDO_CLIENTE = mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),trim($procsaldo)) or die(mysqli_error());
						$rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);					
						?>
							<div class="bloco">
								<center>
									<div style="font-size: 16px; line-height: 22px;"><span style="font-size: 21px; font-weight:900;"><?php echo $nom_cliente; ?></span> <br/>
									<small>cartão: <?php echo $num_cartao; ?></small> <br/>
									Seu saldo é: R$ <?php echo fnValor($rowSALDO_CLIENTE['TOTAL_CREDITO'],2); ?> <br/>
									<?php echo date("d/m/Y"); ?></div>
								</center>
							</div>
		
						<?php
											
						$ArraySaldoCartao=array($qrListaModelos['COD_BLTEMPL']=> array("NOME"=>$nom_cliente,
																					"CARTAO"=>$num_cartao,
																					"TOTAL_CREDITO" =>fnValor($rowSALDO_CLIENTE['TOTAL_CREDITO'],2)));
										  
					break; 		
					case 15://cod. de barra com cpf
					case 16://cod. de barra sem cpf
					
						?>
							<div class="bloco">
								<center>

									<?php

									include '../_system/codebar/BarcodeGenerator.php';
									include('../_system/codebar/BarcodeGeneratorPNG.php');

									 $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
									echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($num_cartao, $generator::TYPE_CODE_39,1.7,40)) . '">';   
									if ($qrListaModelos['COD_BLTEMPL']==15){echo "<br/><span style='font-size: 12px;'>".$num_cartao."</span>";}
									?>								

								</center>
							</div>
		
						<?php
											
										  
					break; 		

				}
				
				//variaveis de controle
				//fnEscreve($qrListaModelos['COD_TEMPLATE']." - ".$qrListaModelos['COD_BLTEMPL']."_".$qrListaModelos['COD_REGISTR']);
				
			
				
				?>
					
				<?php
		  }											
		  
				//Grava ticket
				//fnEscreve("TODOS - ".substr($produtoHabito.$produtoOferta.$produtoLista,0,-1));
			  
				$todosProdutos = substr($produtoHabito.$produtoOferta.$produtoLista,0,-1);	
				$opcao = "CAD";
				$cod_ticket = 0;
				$cod_maquina = 0;
				//$cod_cadastr = $_SESSION["SYS_COD_USUARIO"];
				$cod_cadastr = 4;

				$sql = "CALL SP_ALTERA_TICKET (
				'".$cod_ticket."', 
				'".$cod_cliente."', 
				'".$cod_empresa."', 
				'".$cod_loja."', 
				'".$cod_maquina."', 
				'".$cod_cadastr."', 
				'".$todosProdutos."', 
				'".$opcao."'    
				) ";

			      $ROWsql= mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),trim($sql)) or die(mysqli_error());
                              $arrayretorno= mysqli_fetch_assoc($ROWsql);
		 
		  
		?>
	</ul>
</div>


<?php
/*
 $tudo=array('COD_TEMPLATE'=> array($cod_template_tkt=> array("ArrayProdutos"=>$ArrayProdutos,    
                                                                "ArrayOferta" =>$ArrayOferta,
                                                                "ArraySaldo" =>$ArraySaldo,
                                                                "ArrayRodaPe"=>$ArrayRodaPe, 
                                                                "ArrayImagem"=>$ArrayImagem,
                                                                "ArrayHabitos"=>$ArrayHabitos,
                                                                "ArraySaldoCartao"=>$ArraySaldoCartao
      )));
     
  $xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($tudo,true)));
  //$trimmed_array=array_map('trim',$xamls);	  
  $arraynormal = str_replace(" ","",$xamls);
  $insert="INSERT INTO TICKET_DADOS(COD_TICKET,DES_TICKET)VALUES(".$arrayretorno['COD_TICKET'].",'".$arraynormal."')";
  mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""), rtrim(ltrim(trim($insert))));
 

 
LOG_DB(connTemptkt($connAdm->connAdm(),$cod_empresa,""),connTemptkt($connAdm->connAdm(),$cod_empresa,""));
process_kill(connTemptkt($connAdm->connAdm(),$cod_empresa,""));
cache_query (connTemptkt($connAdm->connAdm(),$cod_empresa,""),1);
 * 
 */
?>

</body>
</html>

