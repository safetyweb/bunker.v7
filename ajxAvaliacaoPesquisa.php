<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
	$opcao = fnLimpaCampo($_REQUEST['opcao']);

	switch ($opcao) {
		case 'addRedir':
		$cod_registr = fnLimpaCampoZero($_REQUEST['COD_REGISTR']);
		$cod_pesquisa = fnLimpaCampoZero($_REQUEST['COD_PESQUISA']);
		$cod_condicao = fnLimpaCampoZero($_REQUEST['COD_CONDICAO']);

		$sql = "SELECT NUM_REDIRECT FROM CONDICAO_PESQUISA WHERE COD_EMPRESA = $cod_empresa AND COD_CONDICAO = $cod_condicao";

		// fnEscreve($sql);

		$qrCond = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

		$qtdCondicoes = explode(',', $qrCond['NUM_REDIRECT']);

		$qtdCondicoes = count($qtdCondicoes);

		$redirects = $qrCond['NUM_REDIRECT'].',0';

		// fnEscreve($redirects);

		$redirects = ltrim(rtrim($redirects,','),',');						

		$sqlUpdt = "UPDATE CONDICAO_PESQUISA SET NUM_REDIRECT = '$redirects'
					 WHERE COD_EMPRESA = $cod_empresa 
		 			AND COD_CONDICAO = $cod_condicao";

		// fnEscreve($sqlUpdt);

		mysqli_query(connTemp($cod_empresa,''),$sqlUpdt);

		?>
			<div class="push10"></div>
			<label class="control-label">Bloco seguinte</label>
			<div class="row">
				<div class="col-xs-11">
					<select data-placeholder="Selecione" name="NUM_REDIRECT_<?=$cod_condicao.'_'.$qtdCondicoes?>" id="NUM_REDIRECT_<?=$cod_condicao.'_'.$qtdCondicoes?>" class="blocoIrAvaliacao chosen-select-deselect requiredChk" required>
						<option value=""></option>
						<?php 
							$sql = "SELECT COD_REGISTR, DES_PERGUNTA FROM MODELOPESQUISA 
									WHERE COD_EXCLUSA IS NULL 
									AND COD_REGISTR != $cod_registr 
									AND COD_TEMPLATE = $cod_pesquisa";

							$arrayQueryRed = mysqli_query(connTemp($cod_empresa,''),$sql);		
							while ($qrLista = mysqli_fetch_assoc($arrayQueryRed)) {																			
						?>
								<option value="<?=$qrLista[COD_REGISTR]?>"><?=$qrLista['DES_PERGUNTA']?></option>
						<?php 
							}
						?>
					</select>
				</div>
				<div class="col-xs-1 text-right">
					<div class="push10"></div>
					<a href="javascript:void(0)" onclick='delRedirect("<?=$cod_condicao?>","0")'><span class="fal fa-times text-danger"></span></a>
				</div>
			</div>
			<div class="help-block with-errors"></div>

		<?php
		break;
		case 'addCond':

			$cod_registr = fnLimpaCampoZero($_REQUEST['COD_REGISTR']);
			$cod_pesquisa = fnLimpaCampoZero($_REQUEST['COD_PESQUISA']);

			$sql = "INSERT INTO CONDICAO_PESQUISA(
								COD_EMPRESA,
								COD_REGISTR,
								NUM_REDIRECT
								) VALUES(
								$cod_empresa,
								$cod_registr,
								'0'
								)";
			//fnEscreve($sql);

			mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			
														
			$count=0;
				$sql = "SELECT * FROM CONDICAO_PESQUISA 
						WHERE COD_CONDICAO = (
											SELECT MAX(COD_CONDICAO) FROM CONDICAO_PESQUISA 
											WHERE COD_EMPRESA = $cod_empresa 
											AND COD_REGISTR = $cod_registr
										   )";
				//fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

				$qrCond = mysqli_fetch_assoc($arrayQuery);

?>

				<div id="BLOCO_<?=$qrCond[COD_CONDICAO]?>">

					<hr>

					<div class="row">

						<div class="col-md-4 col-sm-4 col-xs-4">
							<div class="col-xs-3" style="padding-left: 0; padding-right: 0;">
								<a href="javascript:void(0)" title="Excluir Condição" onclick='delCondicao("<?=$qrCond[COD_CONDICAO]?>")'>
									<i class="far fa-trash-alt text-danger" aria-hidden="true"></i>
								</a>
							</div>
							<div class="col-xs-9 text-right" style="padding-left: 0;">
								<div class="push20"></div>
								<div class="push5"></div>
								<p>Se resposta é:</p>
							</div>							
						</div>

						<div class="col-md-5 col-sm-5 col-xs-5">
							<div class="form-group">
								<label class="control-label">Condição</label>
									<select data-placeholder="Selecione" name="TIP_CONDICAO_<?=$qrCond[COD_CONDICAO]?>" id="TIP_CONDICAO_<?=$qrCond[COD_CONDICAO]?>" class="condicaoAvalicao chosen-select-deselect requiredChk" required>
										<option value="">&nbsp;</option>
										<option value="<?=fnEncode('=')?>">Igual a</option>
										<option value="<?=fnEncode('>=')?>">Maior ou igual a</option>
										<option value="<?=fnEncode('<=')?>">Menor ou igual a</option>
									</select>
									<script>$("#TIP_CONDICAO_<?=$qrCond[COD_CONDICAO]?>").val("<?=fnEncode($qrCond[TIP_CONDICAO])?>").trigger("chosen:updated");</script>
								<div class="help-block with-errors"></div>
							</div>
						</div>

						<div class="col-md-3 col-sm-3 col-xs-3">
							<div class="form-group">
								<label class="control-label">Resultado</label>
									<select data-placeholder="Selecione" name="NUM_RESULTADO_<?=$qrCond[COD_CONDICAO]?>" id="NUM_RESULTADO_<?=$qrCond[COD_CONDICAO]?>" class="chosen-select-deselect requiredChk" required>
										<option value=""></option>
										<?php  

											for ($i=1; $i <= 10 ; $i++) { 
										?>
											<option value="<?=$i?>"><?=$i?></option>
										<?php
											}

										?>
									</select>
									<script>$("#NUM_RESULTADO_<?=$qrCond[COD_CONDICAO]?>").val("<?=$qrCond[NUM_RESULTADO]?>").trigger("chosen:updated");</script>
								<div class="help-block with-errors"></div>
							</div>
							<!-- <div class="form-group">
								<label class="control-label">Resultado</label>
								<input type="text" class="resultado form-control input-sm" name="NUM_RESULTADO_<?=$qrCond[COD_CONDICAO]?>" id="NUM_RESULTADO_<?=$qrCond[COD_CONDICAO]?>" value="<?=$qrCond[NUM_RESULTADO]?>" placeholder="" required />
							</div> -->
						</div>
					</div>

					<div class="row">

						<div class="col-md-12 col-sm-12 col-md-xs">
							<div class="form-group" id="condicoesConteudo_<?=$qrCond[COD_CONDICAO]?>">
								<label class="control-label">Bloco para qual será redirecionado</label>
									<select data-placeholder="Selecione" name="NUM_REDIRECT_<?=$qrCond[COD_CONDICAO]?>_0" id="NUM_REDIRECT_<?=$qrCond[COD_CONDICAO]?>_0" class="blocoIrAvaliacao chosen-select-deselect requiredChk" required>
										<option value=""></option>
<?php 
											$sql = "SELECT COD_REGISTR, DES_PERGUNTA FROM MODELOPESQUISA 
													WHERE COD_EXCLUSA IS NULL 
													AND COD_REGISTR != $cod_registr 
													AND COD_TEMPLATE = $cod_pesquisa";

											$arrayQueryRed = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());		
											while ($qrLista = mysqli_fetch_assoc($arrayQueryRed)) {																			
?>
												<option value="<?=$qrLista[COD_REGISTR]?>"><?=$qrLista['DES_PERGUNTA']?></option>
<?php 
											}
?>
									</select>
									<script>$("#NUM_REDIRECT_<?=$qrCond[COD_CONDICAO]?>_0").val("<?=$qrCond[NUM_REDIRECT]?>").trigger("chosen:updated");</script>
								<div class="help-block with-errors"></div>
							</div>
							<div class="push10"></div>
							<a href="javascript:void(0)" onclick='addRedirect("<?=$qrCond[COD_CONDICAO]?>")'><span class="fal fa-plus-circle"></span> Adicionar redirecionamento</a>
						</div>

					</div>

				</div>

<?php 

		break;
		
		case 'excRedirect':

			$cod_condicao = fnLimpaCampoZero($_REQUEST['COD_CONDICAO']);
			$num_redirect = fnLimpaCampoZero($_REQUEST['NUM_REDIRECT']);
			$cod_registr = fnLimpaCampoZero($_REQUEST['COD_REGISTR']);
			$cod_pesquisa = fnLimpaCampoZero($_REQUEST['COD_PESQUISA']);
			$num_redirects = "0";

			$sqlRedirect = "SELECT NUM_REDIRECT FROM CONDICAO_PESQUISA 
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_CONDICAO = $cod_condicao";

			$qrCond = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlRedirect));
			
			$redirects = explode(',', $qrCond['NUM_REDIRECT']);

			if (($key = array_search($num_redirect, $redirects)) !== false) {
			    unset($redirects[$key]);
			}
		
			$num_redirects = implode(",", $redirects);

			$sql = "UPDATE CONDICAO_PESQUISA 
					SET NUM_REDIRECT = '$num_redirects'
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_CONDICAO = $cod_condicao";
			// echo $sql;
			mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			for ($i=1; $i < count($redirects); $i++) { 
			?>

					<div class="push10"></div>
					<label class="control-label">Bloco seguinte</label>
					<div class="row">
						<div class="col-xs-11">
							<select data-placeholder="Selecione" name="NUM_REDIRECT_<?=$cod_condicao.'_'.$i?>" id="NUM_REDIRECT_<?=$cod_condicao.'_'.$i?>" class="blocoIrAvaliacao chosen-select-deselect requiredChk" required>
								<option value=""></option>
								<?php 
									$sql = "SELECT COD_REGISTR, DES_PERGUNTA FROM MODELOPESQUISA 
											WHERE COD_EXCLUSA IS NULL 
											AND COD_REGISTR != $cod_registr 
											AND COD_TEMPLATE = $cod_pesquisa";

									$arrayQueryRed = mysqli_query(connTemp($cod_empresa,''),$sql);		
									while ($qrLista = mysqli_fetch_assoc($arrayQueryRed)) {																			
								?>
										<option value="<?=$qrLista[COD_REGISTR]?>"><?=$qrLista['DES_PERGUNTA']?></option>
								<?php 
									}
								?>
							</select>
						</div>
						<div class="col-xs-1 text-right">
							<div class="push10"></div>
							<a href="javascript:void(0)" onclick='delRedirect("<?=$cod_condicao?>","<?=$redirects[$i]?>")'><span class="fal fa-times text-danger"></span></a>
						</div>
					</div>
					<script>
						$("#NUM_REDIRECT_<?=$cod_condicao.'_'.$i?>").chosen({allow_single_deselect: true});
						$("#NUM_REDIRECT_<?=$cod_condicao.'_'.$i?>").val("<?=$redirects[$i]?>").trigger("chosen:updated");
					</script>
					<div class="help-block with-errors"></div>

			<?php
			}

		break;

		default:

			$cod_condicao = fnLimpaCampoZero($_REQUEST['COD_CONDICAO']);

			$sql = "DELETE FROM CONDICAO_PESQUISA 
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_CONDICAO = $cod_condicao";
			mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

		break;
	}

?>
