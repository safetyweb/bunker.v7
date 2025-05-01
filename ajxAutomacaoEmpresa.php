<?php

	include '_system/_functionsMain.php';
	

	$cod_empresa = fnLimpaCampoZero($_GET['id']);
	$acao = fnLimpaCampo(@$_GET['acao']);

	switch($acao){

		case "1": 


?>

			<div class="row">

				<div class="push50"></div>

				<div class="col-md-6 col-md-offset-3">

					

				</div>

			</div>

			<div class="push100"></div>

			<hr>

			<div class="col-md-2">
				<button type="submit" class="col-md-12 btn btn-primary prev1"><i class="fas fa-arrow-left pull-left"></i>Anterior</button>
			</div>

			<div class="col-md-8"></div>

			<div class="col-md-2">
				<button type="submit" class="col-md-12 btn btn-primary next2">Próximo<i class="fas fa-arrow-right pull-right"></i></button>
			</div>
				

			<div class="push10"></div>

			<script>

				$.ajax({
					type: "GET",
					url: "../uploads/uploadImport.php?acao=confirmar&id=<?php echo $cod_empresa; ?>",
					success:function(data){
						$("#passo3").html(data);
					},
					error:function(){
						alert('Erro ao carregar...');
					}
				});

				var cont = 0;
				$('#loadMore').click(function(){
					
					cont +=20;

					if(cont >= "<?php echo $qrLinhas['LINHAS']; ?>"){
						$('#loadMore').addClass('disabled');
						$('#loadMore').text('Todos os Itens Já se Encontam na Lista');
					}

					$.ajax({
						type: "GET",
						url: "../uploads/uploadImport.php?acao=loadMore&itens="+cont+"&id=<?php echo $cod_empresa; ?>",
						beforeSend:function(){	
							$('#loadMore').text('Carregando...');
						},
						success:function(data){
							$('#loadMore').text('Carregar Mais Produtos Da Lista');
							$('#relConteudo').append(data);
						},
						error:function(){
							alert('Erro ao carregar...');
						}
					});
				});

				$('.prev1').click(function(){
					$('#passo2').hide();
					$('#passo1').show();
					$("#step2 div.fundo, #step2 a.btn").removeClass('fundoAtivo');
				});

				$('.next2').click(function(){
					$('#passo2').hide();
					$('#passo3').show();
					$("#step3 div.fundo, #step3 a.btn").addClass('fundoAtivo');
				});
				
			</script>

		<?php 

		break;

		case "2": //Rotina de confirmação dos dados enviados

		?>

			<div class="push50"></div>

			<div class="row">

				<?php

					$msgNovaBlk = "Voce já possui uma blacklist ativa.";
					$sqlIn = "";
					$sqlBuscaBlk = "SELECT MAX(COD_BLKLIST) AS COD_BLKLIST FROM BLACKLISTTKT WHERE COD_EMPRESA = $cod_empresa and TIP_BLKLIST = 'PRD' and COD_EXCLUSA = 0";
					// fnEscreve($sqlBuscaBlk);
					$qrCodBlk = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,""),trim($sqlBuscaBlk)));

					if(!isset($qrCodBlk['COD_BLKLIST'])){

						$sqlIn = "CALL SP_ALTERA_BLACKLISTTKT (
									 0, 
									 'PRD', 
									 'Import(".date('d/m/Y H:i:s').")', 
									 'IMP', 
									 '".$_SESSION["SYS_COD_USUARIO"]."', 
									 '".$cod_empresa."', 
									 'CAD' 
									) ";
									//fnEscreve($sqlIn);
									$msgNovaBlk = "Você não possuia nenhuma blacklist ativa. Uma blacklist foi gerada automaticamente.";

					}

					if($sqlIn != ""){
						mysqli_query(connTemp($cod_empresa,""),trim($sqlIn));
					}
					
					$qrCodBlk = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,""),trim($sqlBuscaBlk)));

					$cod_blklist = fnLimpaCampoZero($qrCodBlk['COD_BLKLIST']);
					//fnEscreve($cod_blklist);
				?>
					

				<div class="col-md-4"></div>

				<div class="col-md-4 text-center">
					<h4><?=$msgNovaBlk?>&nbsp;<b>O que deseja fazer?</b></h4>
				</div>

			</div>

			<div class="row text-center">
				
				<div class="col-md-4"></div>

				<div class="col-md-2">
					<input type="radio" id="ATUALIZAR" name="RADIO" checked value="ATUALIZAR">
					<label for="ATUALIZAR">Inserir produtos não-existentes na lista de produtos e na blacklist </label>
				</div>

				<div class="col-md-2">
					<input type="radio" id="SUBSTITUIR" name="RADIO" value="SUBSTITUIR">
					<label for="SUBSTITUIR">Inserir produtos não-existentes e substituir produtos com Códigos Externos iguais na lista de produtos e na blacklist </label>
				</div>

				<input type="hidden" name="COD_BLKLIST" value="<?=$cod_blklist?>">

			</div>



		<div class="push100"></div>

		<hr>

		<div class="col-md-2">
			<button class="col-md-12 btn btn-primary prev2"><i class="fas fa-arrow-left pull-left"></i>Anterior</button>
		</div>

		<div class="col-md-8"></div>

		<div class="col-md-2">
			<button class="col-md-12 btn btn-primary next3">Confirmar<i class="fas fa-check pull-right"></i></button>
		</div>
			

		<div class="push10"></div>

		<script>

			$('.next3').click(function(){
				$.ajax({
					type: "POST",
					url: "../uploads/uploadImport.php?id=<?php echo $cod_empresa; ?>",
					data: $('#formulario').serialize(),
					beforeSend:function(){
						$('#passo3').hide();
						$('#passo4').show();
						$("#passo4").html('<div class="loading" style="width: 100%;"></div>');
					},
					success:function(data){
						$("#passo4").html(data);
						$("#step4 div.fundo, #step4 a.btn").addClass('fundoAtivo');
					},
					error:function(){
						alert('Erro ao carregar...');
					}
				});
			});

			$('.prev2').click(function(){
				$('#passo3').hide();
				$('#passo2').show();
				$("#step3 div.fundo, #step3 a.btn").removeClass('fundoAtivo');
			});


		</script>

		<?php

		break;

		case "loadMore":

		?>
		
		<?php

		$limite = $_GET['itens'];

				//fnEscreve($limite);

				$sqlBlk = "
						  	 SELECT  IB.*,
							(SELECT  COUNT(1) FROM BLACKLISTTKTPROD BTP LEFT JOIN BLACKLISTTKT BTK ON BTK.COD_BLKLIST = BTP.COD_BLKLIST
																			WHERE BTP.COD_PRODUTO = PC.COD_PRODUTO and 
							                                                 BTP.cod_empresa=IB.cod_empresa 
							                                                 AND BTK.COD_EXCLUSA = 0) AS TEMBLACKLIST,
							(SELECT COUNT(1) FROM PRODUTOCLIENTE PC WHERE PC.COD_EXTERNO = IB.COD_EXTERNO AND 
																		   PC.COD_EMPRESA = IB.COD_EMPRESA)
							AS TEMPRODUTO
							FROM IMPORT_BLACKLIST IB
							left join PRODUTOCLIENTE PC on PC.COD_EXTERNO = IB.COD_EXTERNO AND 
														    PC.COD_EMPRESA = IB.COD_EMPRESA
							WHERE IB.COD_EMPRESA = $cod_empresa
							ORDER BY NOM_PRODUTO
						    LIMIT $limite,20
						 ";

				$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlBlk)) or die(mysqli_error());

				while($qrBlk = mysqli_fetch_assoc($result)){

					if($qrBlk['TEMBLACKLIST'] == 1) $iconeBlk = '<span class="fas fa-check" style="color: #7cfc00;"></span>'; 
					else $iconeBlk = '<span class="fas fa-times" style="color: #e32636";></span>';

					if($qrBlk['TEMPRODUTO'] == 1) $icone = '<span class="fas fa-check" style="color: #7cfc00;"></span>'; 
					else $icone = '<span class="fas fa-times" style="color: #e32636";></span>';

					?>
					<tr>
						<td class="text-center"><?php echo $icone; ?></td>
						<td class="text-center"><?php echo $iconeBlk; ?></td>
						<td><?php echo $qrBlk['COD_EXTERNO']; ?></td>
						<td><?php echo $qrBlk['NOM_PRODUTO']; ?></td>
					</tr>
					<?php
					}
					?>
			

<?php 

		break;

		default:



			if(isset($_POST['RADIO'])){

				//fnEscreve('setado');

				$escolha = $_POST['RADIO'];
				$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
				$jaexiste=0;
				$altera=0;
				$cod_blklist = $_POST['COD_BLKLIST'];

				if($escolha != "ATUALIZAR" && $escolha != ""){

					$sql = "SELECT IB.* FROM IMPORT_BLACKLIST IB
							WHERE (SELECT COUNT(1) FROM PRODUTOCLIENTE PC WHERE PC.COD_EXTERNO = IB.COD_EXTERNO AND PC.COD_EMPRESA = IB.COD_EMPRESA) = 1 
							AND (SELECT COUNT(1) FROM BLACKLISTTKTPROD BTP WHERE 
						    BTP.COD_PRODUTO = (SELECT COD_PRODUTO FROM PRODUTOCLIENTE PC WHERE PC.COD_EXTERNO = IB.COD_EXTERNO AND PC.COD_EMPRESA = IB.COD_EMPRESA)) = 1 
						    AND IB.COD_EMPRESA = $cod_empresa";

					//fnEscreve($sql);

					$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());
					while($qrBlk = mysqli_fetch_assoc($arrayQuery)){

						$blk_externo = $qrBlk['COD_EXTERNO'];
						$dat_cadastr = $qrBlk['DAT_CADASTR'];

						//busca cod_produto
						$buscaProd = "SELECT COD_PRODUTO FROM PRODUTOCLIENTE WHERE
									COD_EXTERNO = '$blk_externo' AND COD_EMPRESA = $cod_empresa";

						$qrBuscaProd = mysqli_query(connTemp($cod_empresa,""),trim($buscaProd));

						$qrCod = mysqli_fetch_assoc($qrBuscaProd);

						$cod_produto = $qrCod['COD_PRODUTO'];

						//busca cod_prodhab
						$buscaProdHab = "SELECT COD_PRODHAB FROM BLACKLISTTKTPROD WHERE
									COD_PRODUTO = $cod_produto AND COD_EMPRESA = $cod_empresa";

						$qrBuscaProdHab = mysqli_query(connTemp($cod_empresa,""),trim($buscaProdHab));

						$qrProdHab = mysqli_fetch_assoc($qrBuscaProdHab);

						$cod_prodhab = $qrProdHab['COD_PRODHAB'];


						$sql1 = "UPDATE BLACKLISTTKTPROD SET
								COD_PRODUTO=$cod_produto,
								COD_BLKLIST=$cod_blklist,
								COD_USUCADA=$cod_usucada,
								COD_EMPRESA=$cod_empresa,
								DAT_CADASTR='$dat_cadastr'
								WHERE COD_PRODUTO = $cod_produto
								AND COD_EMPRESA = $cod_empresa
								";

						//mysqli_query(connTemp($cod_empresa,""),trim($sql1)) or die(mysqli_error());
						mysqli_query(connTemp($cod_empresa,""),trim($sql1)) or die(mysqli_error());

						$altera = 1;

					}

				}

				$sqlBlk = "
						  	 SELECT  IB.*,
							(SELECT  COUNT(1) FROM BLACKLISTTKTPROD BTP LEFT JOIN BLACKLISTTKT BTK ON BTK.COD_BLKLIST = BTP.COD_BLKLIST
																			WHERE BTP.COD_PRODUTO = PC.COD_PRODUTO and 
							                                                 BTP.cod_empresa=IB.cod_empresa 
							                                                 AND BTK.COD_EXCLUSA = 0) AS TEMBLACKLIST,
							(SELECT COUNT(1) FROM PRODUTOCLIENTE PC WHERE PC.COD_EXTERNO = IB.COD_EXTERNO AND 
																		   PC.COD_EMPRESA = IB.COD_EMPRESA)
							AS TEMPRODUTO
							FROM IMPORT_BLACKLIST IB
							LEFT JOIN PRODUTOCLIENTE PC on PC.COD_EXTERNO = IB.COD_EXTERNO AND 
														    PC.COD_EMPRESA = IB.COD_EMPRESA
							WHERE IB.COD_EMPRESA = $cod_empresa
						";


				$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sqlBlk)) or die(mysqli_error());

				while($qrBlk = mysqli_fetch_assoc($arrayQuery)){

					$blk_externo = $qrBlk['COD_EXTERNO'];
					$blk_ean = $qrBlk['EAN'];
					$blk_produto = $qrBlk['NOM_PRODUTO'];
					$blk_data = $qrBlk['DAT_CADASTR'];

					if($qrBlk['TEMPRODUTO'] == 0 && $qrBlk['TEMBLACKLIST'] == 0){

						$sql = "INSERT INTO PRODUTOCLIENTE(
								COD_EXTERNO,
								EAN,
								DES_PRODUTO,
								DAT_CADASTR,
								COD_EMPRESA,
								LOG_HABITEXC,
								LOG_IMPORT
								) VALUES(
								'$blk_externo',
								'$blk_ean',
								'$blk_produto',
								'$blk_data',
								$cod_empresa,
								'S',
								'S'
								)";

						// fnEscreve($sql);

						mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());						

						//busca cod_produto
						$buscaProd = "SELECT COD_PRODUTO FROM PRODUTOCLIENTE WHERE
									COD_EXTERNO = '$blk_externo' AND COD_EMPRESA = $cod_empresa";

						$qrBuscaProd = mysqli_query(connTemp($cod_empresa,""),trim($buscaProd));

						$qrCod = mysqli_fetch_assoc($qrBuscaProd);

						$cod_produto = $qrCod['COD_PRODUTO'];

						if(trim($cod_produto) != ""){

							$sql1 = "INSERT INTO BLACKLISTTKTPROD(
									COD_PRODUTO,
									COD_BLKLIST,
									COD_USUCADA,
									COD_EMPRESA,
									DAT_CADASTR
									) VALUES(
									$cod_produto,
									$cod_blklist,
									$cod_usucada,
									$cod_empresa,
									'$blk_data'
									); ";


							//fnEscreve($cod_produto);
							fnEscreve($sql1);
							mysqli_query(connTemp($cod_empresa,""),trim($sql1)) or die(mysqli_error());

						}
						//fnTestesql(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());
					
					}else if($qrBlk['TEMPRODUTO'] == 1 && $qrBlk['TEMBLACKLIST'] == 0){

						//busca cod_produto
						$buscaProd = "SELECT COD_PRODUTO FROM PRODUTOCLIENTE WHERE
									COD_EXTERNO = '$blk_externo' AND COD_EMPRESA = $cod_empresa";

						// fnEscreve($buscaProd);

						$qrBuscaProd = mysqli_query(connTemp($cod_empresa,""),trim($buscaProd));

						$qrCod = mysqli_fetch_assoc($qrBuscaProd);

						$cod_produto = $qrCod['COD_PRODUTO'];

						if(trim($cod_produto) != ""){

							$sql1 = "INSERT INTO BLACKLISTTKTPROD(
									COD_PRODUTO,
									COD_BLKLIST,
									COD_USUCADA,
									COD_EMPRESA,
									DAT_CADASTR
									) VALUES(
									$cod_produto,
									$cod_blklist,
									$cod_usucada,
									$cod_empresa,
									'$blk_data'
									); ";

							// fnEscreve($sql1);

							mysqli_query(connTemp($cod_empresa,""),trim($sql1)) or die(mysqli_error());

						}
						//fnTestesql(connTemp($cod_empresa,""),trim($sql1)) or die(mysqli_error());

					}else{
						// incrementando contador caso produto exista na lista de produtos e na blacklist simultaneamente
						$jaexiste++;
					}
				}
				

				?>

				<div class="push100"></div>

				<div class="row">

					<div class="col-md-4"></div>

					<?php 

					$sqlLinhas = "SELECT COUNT(*) AS LINHAS FROM IMPORT_BLACKLIST WHERE COD_EMPRESA = $cod_empresa";
								$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlLinhas)) or die(mysqli_error());
								$qrLinhas = mysqli_fetch_assoc($result);

					// comparando nro de linhas da planilha com nro de produtos existentes na lista e na blacklist
					//$jaexiste=0;
					if($jaexiste != $qrLinhas['LINHAS']){
					?>

						<div class="col-md-4 text-center">
							<h4>Lista Blacklist importada com <b>sucesso</b>!</h4>
							<h4>Registros importados: <?=($qrLinhas['LINHAS']-$jaexiste)?></h4>
						</div>

					<?php }else if($jaexiste == $qrLinhas['LINHAS'] && $altera == 0){ ?>

						<div class="col-md-4 text-center">
							<h4>Lista Blacklist já existe. <b>Nenhum dado</b> foi alterado.</h4>
						</div>

					<?php }else{ 

						$sqlIn = "CALL SP_ALTERA_BLACKLISTTKT (
									 $cod_blklist, 
									 'PRD', 
									 'Import(".date('d/m/Y H:i:s').")', 
									 'IMP', 
									 '".$_SESSION["SYS_COD_USUARIO"]."', 
									 '".$cod_empresa."', 
									 'ALT' 
									) ";
						mysqli_query(connTemp($cod_empresa,""),trim($sqlIn));

					?>

						<div class="col-md-4 text-center">
							<h4>Lista Blacklist atualizada com <b>sucesso</b>!</h4>
						</div>

					<?php } ?>

				</div>

				<div class="push100"></div>

				<hr>

				<div class="col-md-10"></div>

				<div class="col-md-2">
					<a href="action.do?mod=<?php echo fnEncode(1306)."&id=".fnEncode($cod_empresa); ?>" class="col-md-12 btn btn-success concluir">Concluir</a>
				</div>
					

				<div class="push10"></div>

				<script>

				</script>

			<?php

			}

			 

		break;
		
	}
?>
