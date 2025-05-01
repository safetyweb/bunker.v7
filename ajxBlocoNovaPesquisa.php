<?php include "_system/_functionsMain.php";

//echo fnDebug('true');

$cod_empresa = fnLimpacampoZero(fnDecode($_REQUEST['COD_EMPRESA']));
if (isset($_REQUEST['DES_DOMINIO'])) {
	$des_dominio = fnLimpacampo($_REQUEST['DES_DOMINIO']);
} else {
	$des_dominio = "";
}

$opcao = fnLimpacampo($_REQUEST['opcao']);
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

//fnescreve($opcao);

//tabela do update
switch ($opcao) {

	case 'shortenUrl':

		$cod_cliente = fnEncode(fnLimpaCampoZero($_POST['COD_CLIENTE']));
		$cod_pesquisa = fnEncode(fnLimpaCampoZero($_POST['COD_PESQUISA']));

		echo file_get_contents("http://tinyurl.com/api-create.php?url=" . "https://" . $des_dominio . ".fidelidade.mk/pesquisa?idP=" . $cod_pesquisa . "&idc=" . $cod_cliente);

		break;

	case 'exc':

		$cod_registr = fnLimpacampoZero(fnDecode($_REQUEST['COD_REGISTR']));
		$sql = "UPDATE MODELOPESQUISA SET COD_EXCLUSA = $cod_usucada, DAT_EXCLUSA = Now() WHERE COD_REGISTR = $cod_registr; ";

		$sql .= "DELETE FROM CONDICAO_PESQUISA WHERE COD_EMPRESA = $cod_empresa AND COD_REGISTR = $cod_registr; ";
		// fnEscreve($sql);
		mysqli_multi_query(connTemp($cod_empresa, ""), trim($sql));

		break;

	case 'img':

		$cod_registr = fnLimpacampoZero(fnDecode($_REQUEST['COD_REGISTR']));
		$des_imagem = fnLimpacampo($_REQUEST['DES_IMAGEM']);

		$sql = "UPDATE MODELOPESQUISA SET 
					DES_IMAGEM = '$des_imagem' 
					WHERE COD_REGISTR = $cod_registr;";
		//fnEscreve($sql);
		mysqli_query(connTemp($cod_empresa, ''), $sql);

		break;

	case 'rating':

		$cod_registr = fnLimpacampoZero(fnDecode($_REQUEST['COD_REGISTR']));
		$sql = "SELECT * FROM MODELOPESQUISA
					WHERE COD_REGISTR = $cod_registr";

		//fnEscreve($sql);

		$qrListaModelos = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

?>
		<li class="ui-state-default movable" id="BLOCO_<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>" cod-registr="<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>">
			<?php
			switch ($qrListaModelos['COD_BLPESQU']) {
				case 5: // AVALIAÇÃO
			?>
					<center class="bloco">
						<div class="row">
							<div class="col-md-2 col-sm-2 col-xs-2 col-xs-offset-10">
								<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12 blocoAvaliacaoComentado">
								<a href="javascript:void(0)" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1509) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idr=<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>&pop=true" data-title="Bloco de Avaliação">
									<?php

									if ($qrListaModelos['DES_PERGUNTA'] != '') {
									?>
										<h5><?php echo $qrListaModelos['DES_PERGUNTA']; ?></h5>
										<?php
										$contador = 0;
										if ($qrListaModelos['TIP_BLOCO'] != "estrela") {
										?>
											<div class="chart-scale">
												<?php
												while ($contador <= $qrListaModelos['NUM_QUANTID']) {
												?>
													<!-- <input type="radio" name="rating" value="5" /><label class= "star<?php echo $contador; ?> <?php echo $qrListaModelos['TIP_BLOCO']; ?>Type full" for="star"></label> -->
													<div class="btn-scale btn-scale-desc-<?= $contador ?>"><?= $contador ?></div>
												<?php
													$contador++;
												}
												?>
											</div>
										<?php
											$sql = "SELECT * FROM TIPO_ROTULO_AVALIACAO_PESQUISA WHERE COD_ROTULO=0" . $qrListaModelos['COD_ROTULO'];
											$rotulo = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));
											echo "<table style='width:100%'>";
											echo "<td style='font-size:11px;text-align:left'>" . $rotulo["DES_ROTULO_MIN"] . "</td>";
											echo "<td style='font-size:11px;text-align:right'>" . $rotulo["DES_ROTULO_MAX"] . "</td>";
											echo "</table>";
										} else {
										?>
											<div class="row">
												<div id="rateYo_<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>" style="margin-left: auto; margin-right: auto;"></div>
											</div>
											<script>
												$(function() {
													$("#rateYo_<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>").rateYo({
														numStars: "<?= $qrListaModelos['NUM_QUANTID'] ?>",
														rating: "70%",
														starWidth: "17px",
														spacing: "4px",
														halfStar: false,
														fullStar: true
													});
												});
											</script>
										<?php
										}

										?>


									<?php
									} else {
									?>
										<h5><span class="fas fa-star-half-alt"></span> Clique para configurar a <b>avaliação</b> <span class="fas fa-star-half-alt"></span></h5>
									<?php
									}
									?>
								</a>
							</div>
						</div>
					</center>
					<hr class="divisao" />
			<?php
					break;
			}

			?>
		</li>
	<?php

		break;

	case 'alterarBloco':
		$pergunta = fnLimpacampo($_GET['pergunta']);
		$des_imagem = fnLimpacampo($_GET['des_imagem']);
		$cod_registr = fnLimpacampo($_GET['cod_registr']);

		$sql = "UPDATE MODELOPESQUISA SET 
					DES_IMAGEM = '$des_imagem', 
					DES_PERGUNTA = '$pergunta' 
					WHERE COD_REGISTR = $cod_registr;";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
		break;

	case 'texto':

		$cod_registr = fnLimpacampoZero(fnDecode($_REQUEST['COD_REGISTR']));
		$des_pergunta = fnLimpacampo($_REQUEST['DES_PERGUNTA']);
		$des_tipo_resposta = fnLimpacampo($_REQUEST['DES_TIPO_RESPOSTA']);
		$num_opcoes = fnLimpaCampoZero($_REQUEST['NUM_OPCOES']);
		$des_opcoes = fnLimpacampo($_REQUEST['DES_OPCOES']);
		$des_imagem = fnLimpacampo($_REQUEST['DES_IMAGEM']);

		$sql = "UPDATE MODELOPESQUISA SET 
					DES_PERGUNTA = '$des_pergunta',
					DES_TIPO_RESPOSTA = '$des_tipo_resposta',
					NUM_OPCOES = '$num_opcoes',
					DES_OPCOES = '$des_opcoes',
					DES_IMAGEM = '$des_imagem',
					COD_USUCALT = $cod_usucada,
					DAT_ALTERAC = Now() 
					WHERE COD_REGISTR = $cod_registr";
		// fnEscreve($sql);
		mysqli_query(connTemp($cod_empresa, ""), trim($sql));

		$sql = "SELECT * FROM MODELOPESQUISA
					WHERE COD_REGISTR = $cod_registr";

		//fnEscreve($sql);

		$qrListaModelos = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

	?>
		<li class="ui-state-default movable" id="BLOCO_<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>" cod-registr="<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>">
			<?php
			switch ($qrListaModelos['COD_BLPESQU']) {
				case 1: // TEXTO INFORMATIVO
			?>
					<center class="bloco">
						<div class="row">
							<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2" onclick='alteraTexto(this, "<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>","Texto")'>
								<label for="inputName" class="control-label"><?php echo $qrListaModelos['DES_PERGUNTA']; ?></label>
								<input type="hidden" class="des_pergunta" value="<?php echo $qrListaModelos['DES_PERGUNTA']; ?>">
							</div>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
							</div>
						</div>
					</center>
					<hr class="divisao" />
				<?php
					break;
				case 2: // PERGUNTA
				?>
					<center class="bloco">
						<div class="row">
							<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2 blocoPergunta" onclick='alteraTexto(this, "<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>","Pergunta")'>
								<label for="inputName" class="control-label"><?php echo $qrListaModelos['DES_PERGUNTA']; ?></label>
								<?php
								if ($qrListaModelos['DES_OPCOES'] <> "") {
									$opcoes = json_decode($qrListaModelos['DES_OPCOES'], true);
								} else {
									$opcoes = array();
								}
								if ($qrListaModelos['DES_TIPO_RESPOSTA'] == "R") {
									echo "<div style='text-align:left'>";
									foreach ($opcoes as $k =>  $v) {
										echo "<input name='opc_" . $qrListaModelos["COD_REGISTR"] . "' type='radio'> $v<br>";
									}
									echo "</div>";
								} elseif ($qrListaModelos['DES_TIPO_RESPOSTA'] == "C") {
									echo "<div style='text-align:left'>";
									foreach ($opcoes as $k =>  $v) {
										echo "<input name='opc_" . $qrListaModelos["COD_REGISTR"] . "' type='checkbox'> $v<br>";
									}
									echo "</div>";
								} elseif (
									$qrListaModelos['DES_TIPO_RESPOSTA'] == "RB" ||
									$qrListaModelos['DES_TIPO_RESPOSTA'] == "CB"
								) {
									echo "<div style='line-height:36px;'>";
									foreach ($opcoes as $k =>  $v) {
										echo "<a style='border:2px solid #CCC;border-radius:6px;padding:5px;white-space:nowrap;' href='javascript:'>$v</a> &nbsp;";
									}
									echo "</div>";
								} elseif ($qrListaModelos['DES_TIPO_RESPOSTA'] == "A") {
									echo "<div class='push10'></div>";
									echo "<div style='line-height:36px;'>";
									foreach ($opcoes as $k =>  $v) {
										echo "<div style='display:flex;flex-wrap: nowrap;'>";
										echo "<div style='flex-basis: 100%;text-align:left;'>$v</div>";
										echo "<div style='text-align:right;'><a class='icon_negativo'><i class='far fa-thumbs-down'></i></a></div>";
										echo "<div style='text-align:left;'><a class='icon_positivo'><i class='far fa-thumbs-up'></i></a></div>";
										echo "</div>";
										echo "<div class='push1'></div>";
									}
									echo "</div>";
								} else {
								?>
									<input type="text" class="form-control input-sm" value="">
								<?php }
								if ($qrListaModelos['DES_IMAGEM'] <> "") {
									echo "<div class='push30'></div>";
									echo "<img style='width:100%' src='media/clientes/" . $cod_empresa . "/pesquisa/" . $qrListaModelos['DES_IMAGEM'] . "'>";
								}
								?>
								<input type="hidden" class="des_pergunta" value="<?php echo $qrListaModelos['DES_PERGUNTA']; ?>">
								<input type="hidden" class="des_tipo_resposta" value="<?php echo $qrListaModelos['DES_TIPO_RESPOSTA']; ?>">
								<input type="hidden" class="num_opcoes" value="<?php echo $qrListaModelos['NUM_OPCOES']; ?>">
								<input type="hidden" class="des_imagem" value="<?php echo $qrListaModelos['DES_IMAGEM']; ?>">
								<textarea style="display:none;" class="des_opcoes"><?php echo $qrListaModelos['DES_OPCOES']; ?></textarea>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
							</div>
						</div>
					</center>
					<hr class="divisao" />
			<?php
					break;
			}

			?>
		</li>
	<?php

		break;

	case 'finalizacao':

		$cod_pesquisa = fnLimpacampoZero(fnDecode($_REQUEST['COD_PESQUISA']));
		$des_pergunta = fnLimpacampo($_REQUEST['DES_PERGUNTA']);

		$sql = "UPDATE PESQUISA SET 
					DES_FINALIZA = '$des_pergunta' 
					WHERE COD_EMPRESA = $cod_empresa
					AND COD_PESQUISA = $cod_pesquisa";
		// fnEscreve($sql);
		mysqli_query(connTemp($cod_empresa, ""), trim($sql));

	?>

		<center class="bloco">
			<div class="row">
				<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2" onclick='alteraFinalizacao(this, "Texto")'>
					<label for="inputName" class="control-label"><?php echo $des_pergunta; ?></label>
					<input type="hidden" class="des_pergunta" value="<?php echo $des_pergunta; ?>">
				</div>
			</div>
		</center>

	<?php

		break;

	case "addBloco": //Inclui modelo Template

		$cod_blpesqu = fnLimpacampoZero($_REQUEST['COD_BLPESQU']);
		$cod_pesquisa = fnLimpacampoZero(fnDecode($_REQUEST['COD_PESQUISA']));

		$tipoBloco = '';
		if ($cod_blpesqu == 1) {
			$des_pergunta = 'Digite aqui seu texto';
		} else if ($cod_blpesqu == 2) {
			$des_pergunta = 'Digite aqui sua pergunta';
		} else if ($cod_blpesqu == 3) {
			$des_pergunta = 'Saldo de Pontos';
		} else if ($cod_blpesqu == 4) {
			$des_pergunta = 'Imagem';
		} else {
			$des_pergunta = "";
		}

		$sql = "INSERT INTO MODELOPESQUISA(
								COD_EMPRESA,
								COD_TEMPLATE,
								COD_BLPESQU,
								DES_PERGUNTA,
								COD_USUCADA,
								DAT_CADASTR
								) VALUES(
								$cod_empresa,
								$cod_pesquisa,
								$cod_blpesqu,
								'$des_pergunta',
								$cod_usucada,
								Now()
								)";
		//fnEscreve($sql);
		mysqli_query(connTemp($cod_empresa, ""), trim($sql));

		$sql = "SELECT * FROM MODELOPESQUISA
					WHERE COD_REGISTR = (
											SELECT MAX(COD_REGISTR) FROM MODELOPESQUISA
											WHERE COD_EMPRESA = $cod_empresa 
											AND COD_TEMPLATE = $cod_pesquisa
											AND COD_USUCADA = $cod_usucada
											AND COD_EXCLUSA is null
										)";

		//fnEscreve($sql);
		$qrListaModelos = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));


	?>
		<li class="ui-state-default movable" id="BLOCO_<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>" cod-bloco="<?php echo $qrListaModelos['COD_BLPESQU'] ?>">
			<?php
			switch ($qrListaModelos['COD_BLPESQU']) {
				case 1: // TEXTO INFORMATIVO
			?>
					<center class="bloco">
						<div class="row">
							<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2" onclick='alteraTexto(this, "<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>","Texto")'>
								<label for="inputName" class="control-label"><?php echo $qrListaModelos['DES_PERGUNTA']; ?></label>
								<input type="hidden" class="des_pergunta" value="<?php echo $qrListaModelos['DES_PERGUNTA']; ?>">
							</div>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
							</div>
						</div>
					</center>
					<hr class="divisao" />
				<?php
					break;
				case 2: // PERGUNTA
				?>
					<center class="bloco">
						<div class="row">
							<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2 blocoPergunta" onclick='alteraTexto(this, "<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>","Pergunta")'>
								<label for="inputName" class="control-label"><?php echo $qrListaModelos['DES_PERGUNTA']; ?></label>
								<input type="text" class="form-control input-sm" value="">
								<input type="hidden" class="des_pergunta" value="<?php echo $qrListaModelos['DES_PERGUNTA']; ?>">
							</div>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
							</div>
						</div>
					</center>
					<hr class="divisao" />
				<?php
					break;
				case 3: // SALDO DE PONTOS
				?>
					<center class="bloco">
						<div class="row">
							<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2">
								<h6>ISABEL DE ANDRADE MARTINEZ SALES BR</h6>
								<h6>Número Cartão: 1234 5678 9012 3456</h6>
								<h6>Saldo: R$ 0,18 31/05/2017</h6>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
							</div>
						</div>
					</center>
					<hr class="divisao" />
				<?php
					break;
				case 4: // IMAGEM
				?>
					<center class="bloco">
						<div class="row">
							<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2">
								<div class="div-imagem">
									<?php
									if (empty(trim($qrListaModelos['DES_IMAGEM']))) {
									?>
										<div class="imagemTicket">
											<button class="btn btn-block btn-success upload-image"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
											<input type="file" cod_registr='<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
										</div>
									<?php
									} else {
									?>
										<div class="imagemTicket">
											<img src='../media/clientes/<?php echo $cod_empresa ?>/<?php echo $qrListaModelos['DES_IMAGEM']; ?>' class='upload-image' style='cursor: pointer; max-width:100%; max-height: 100%'>
											<input type="file" cod_registr='<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
										</div>
									<?php
									}
									?>
								</div>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
							</div>
						</div>
					</center>
					<hr class="divisao" />
				<?php
					break;
				case 5: // AVALIAÇÃO
				?>
					<center class="bloco">
						<div class="row">
							<div class="col-md-2 col-sm-2 col-xs-2  col-xs-offset-10">
								<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
							</div>
							<div class="col-md-12 col-sm-12 col-xs-12 blocoAvaliacaoComentado">
								<a href="javascript:void(0)" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1509) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idr=<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>&pop=true" data-title="Bloco de Avaliação">
									<?php

									if ($qrListaModelos['DES_PERGUNTA'] != '') {
									?>
										<h5><?php echo $qrListaModelos['DES_PERGUNTA']; ?></h5>
										<?php
										$contador = 0;
										if ($qrListaModelos['TIP_BLOCO'] != "estrela") {
										?>
											<div class="chart-scale">
												<?php
												while ($contador <= $qrListaModelos['NUM_QUANTID']) {
												?>
													<!-- <input type="radio" name="rating" value="5" /><label class= "star<?php echo $contador; ?> <?php echo $qrListaModelos['TIP_BLOCO']; ?>Type full" for="star"></label> -->
													<div class="btn-scale btn-scale-desc-<?= $contador ?>"><?= $contador ?></div>
												<?php
													$contador++;
												}
												?>
											</div>
										<?php
											$sql = "SELECT * FROM TIPO_ROTULO_AVALIACAO_PESQUISA WHERE COD_ROTULO=0" . $qrListaModelos['COD_ROTULO'];
											$rotulo = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));
											echo "<table style='width:100%'>";
											echo "<td style='font-size:11px;text-align:left'>" . $rotulo["DES_ROTULO_MIN"] . "</td>";
											echo "<td style='font-size:11px;text-align:right'>" . $rotulo["DES_ROTULO_MAX"] . "</td>";
											echo "</table>";
										} else {
										?>
											<div class="row">
												<div id="rateYo_<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>" style="margin-left: auto; margin-right: auto;"></div>
											</div>
											<script>
												$(function() {
													$("#rateYo_<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>").rateYo({
														numStars: "<?= $qrListaModelos['NUM_QUANTID'] ?>",
														rating: "70%",
														starWidth: "17px",
														spacing: "4px",
														halfStar: false,
														fullStar: true
													});
												});
											</script>
										<?php
										}

										?>


									<?php
									} else {
									?>
										<h5><span class="fas fa-star-half-alt"></span> Clique para configurar a <b>avaliação</b> <span class="fas fa-star-half-alt"></span></h5>
									<?php
									}
									?>
								</a>
							</div>
							<div class="push10"></div>
						</div>
					</center>
					<hr class="divisao" />
				<?php
					break;
				case 6: // LOGIN
				?>
					<center class="bloco">
						<div class="row">
							<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2">
								<header>
									<p class="lead">Faça seu login para responder nossas pesquisas!</p>
								</header>
								<div class="row">
									<div class="col-md-12 col-sm-12 col-md-xs">
										<input type="text" id="cpf" name="cpf" class="form-control input-hg" placeholder="Seu CPF" maxlength="14">
										<div class="push10"></div>
										<button type="button" class="btn btn-primary btn-hg btn-block" name="btLogin" id="btLogin">Fazer login</button>
										<div class="push10"></div>
										<div class="errorLogin" style="color: red; text-align: center; display: none">Usuário/senha inválidos.</div>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
							</div>
						</div>
					</center>
					<hr class="divisao" />
				<?php
					break;
				case 7: // LOGIN COM SENHA
				?>
					<center class="bloco">
						<div class="row">
							<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2">
								<header>
									<p class="lead">Faça seu login para responder nossas pesquisas!</p>
								</header>
								<div class="row">
									<div class="col-md-12 col-sm-12 col-md-xs">
										<input type="text" id="cpf" name="cpf" class="form-control input-hg" placeholder="Seu CPF" maxlength="14">
										<div class="push10"></div>
										<input type="password" id="senha" name="senha" class="form-control input-hg" placeholder="Sua Senha">
										<div class="push10"></div>
										<button type="button" class="btn btn-primary btn-hg btn-block" name="btLogin" id="btLogin">Fazer login</button>
										<div class="push10"></div>
										<div class="errorLogin" style="color: red; text-align: center; display: none">Usuário/senha inválidos.</div>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
							</div>
						</div>
					</center>
					<hr class="divisao" />
				<?php
					break;
				case 8: // SMART LOGIN
				?>
					<center class="bloco">
						<div class="row">
							<div class="col-md-10">
								<div class="col-md-4 text-center"><span class="fa fa-envelope"></span></div>
								<div class="col-md-4 text-center"><span class="fa fa-phone"></span></div>
								<div class="col-md-4 text-center"><span class="fa fa-user"></span></div>
							</div>
							<div class="col-md-2">
								<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
							</div>
						</div>
					</center>
					<hr class="divisao" />
			<?php
					break;
			}

			?>
		</li>
		<script type="text/javascript">
			var Ids = "";
			jQuery('#drop-target .movable').each(function(index) {
				Ids += jQuery(this).attr('id').substring(6) + ",";
			});

			var arrayOrdem = Ids.substring(0, (Ids.length - 1));

			execOrdenacao(arrayOrdem, 6, "<?= $cod_empresa ?>");
		</script>
<?php

		break;
}


?>