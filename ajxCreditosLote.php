<?php
include "_system/_functionsMain.php";
//echo fnDebug('true');

$passo = fnLimpacampoZero($_GET['passo']);
$cod_persona = "";

//fnMostraForm();

//controel dos steps 
switch ($passo) {

		//bloco 2 - confirmação
	case "1":

		$cod_empresa = fnDecode($_GET['id']);

		if (isset($_POST['cod_persona'])) {
			$Arr_COD_PERSONA = $_POST['cod_persona'];
			for ($i = 0; $i < count($Arr_COD_PERSONA); $i++) {
				$cod_persona = $cod_persona . $Arr_COD_PERSONA[$i] . ",";
			}
			$cod_persona = substr($cod_persona, 0, -1);
		} else {
			$cod_persona = "0";
		}

		//fnEscreve($cod_persona);

		//busca total de personas

		$sql = "select count(cod_cliente) as totPersona from personaclassifica
			where cod_persona in($cod_persona) and 
				  cod_empresa=$cod_empresa ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
		$qrBuscaTotalGeral = mysqli_fetch_assoc($arrayQuery);

		if (isset($qrBuscaTotalGeral)) {
			$totPersona = $qrBuscaTotalGeral['totPersona'];
		}
		//fnEscreve($totPersona);

		//busca total unico
		$sql = "select count(distinct cod_cliente) as totUnico from personaclassifica
			where cod_persona in($cod_persona) and 
				  cod_empresa=$cod_empresa ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
		$qrBuscaTotalUnico = mysqli_fetch_assoc($arrayQuery);

		if (isset($qrBuscaTotalUnico)) {
			$totUnico = $qrBuscaTotalUnico['totUnico'];
		}
		//fnEscreve($totUnico);

?>

		<style>
			.tag {
				padding: 1px 5px 3px 5px;
				border-radius: 3px;
				color: #777;
				background: #E5E7E9;
				margin: 0 10px 10px 0;
			}
		</style>

		<div class="row">

			<div class="push30"></div>

			<div class="col-md-3"></div>

			<div class="col-md-6">

				<div class="col-md-6">
					<div class="form-group">
						<label for="inputName" class="control-label required">Personas Selecionadas</label>
						<input type="text" class="form-control input-sm btn-lg leitura" readonly="readonly" name="TOTPERSONA" id="TOTPERSONA" value="<?php echo fnValor($totPersona, 0); ?>">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="inputName" class="control-label required">Clientes Únicos</label>
						<input type="text" class="form-control input-sm btn-lg leitura" readonly="readonly" name="TOTUNICO" id="TOTUNICO" value="<?php echo fnValor($totUnico, 0); ?>">
					</div>
				</div>

				<div class="push20"></div>

				<div class="col-md-12">
					<div class="form-group">
						<label for="inputName" class="control-label required">Personas Envolvidas</label>
						<div class="push10"></div>

						<?php
						$sql = "SELECT * FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND COD_PERSONA in($cod_persona) ORDER BY DES_PERSONA";

						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

						while ($qrPersona = mysqli_fetch_assoc($arrayQuery)) {

						?>
							<span class="tag f14"><?php echo ucfirst($qrPersona['DES_PERSONA']); ?> &nbsp;<i class="far fa-check f14" aria-hidden="true"></i></span>

						<?php
						}
						?>
						<input type="hidden" name="COD_PERSONA" id="COD_PERSONA" value="<?php echo $cod_persona; ?>">
					</div>
				</div>

				<div class="push30"></div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="inputName" class="control-label required">Valor Individual do Crédito</label>
						<input type="text" class="form-control input-lg text-center money" name="VAL_CREDITO" id="VAL_CREDITO" maxlength="20" data-error="Campo obrigatório" required>
						<div class="help-block with-errors"></div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="inputName" class="control-label required">Data de Validade do Crédito</label>
						<input type="text" class="form-control input-lg text-center data" name="DAT_VALIDADE" id="DAT_VALIDADE" maxlength="20" data-error="Campo obrigatório" required>
						<div class="help-block with-errors"></div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="inputName" class="control-label required">Total de Créditos a Ser Gerado </label>
						<input type="text" class="form-control leituraOff text-center input-lg money" name="VAL_TOTAL" id="VAL_TOTAL" maxlength="20" data-error="Campo obrigatório" required>
						<div class="help-block with-errors"></div>
					</div>
				</div>

			</div>

			<div class="col-md-3"></div>

			<div class="push20"></div>

			<hr>

			<div class="col-md-2">
				<button class="col-md-12 btn btn-primary prev1"><i class="fas fa-arrow-left pull-left"></i>Anterior</button>
			</div>

			<div class="col-md-8"></div>
			<div class="col-md-2 pull-right">
				<button class="btn btn-primary btn-block next next2" disabled name="next">Próximo &nbsp; <i class="fas fa-arrow-right"></i></button>
			</div>

			<div class="push10"></div>
		</div>

		<script>
			$("#step2 div.fundo, #step2 a.btn").addClass('fundoAtivo');

			$('.next2').click(function() {

				$.ajax({
					type: "POST",
					url: "ajxCreditosLote.php?passo=2&id=<?php echo fnEncode($cod_empresa); ?>",
					data: $('#formulario').serialize(),
					method: 'POST',
					success: function(data) {
						$("#passo3").html(data);

						$('#passo2').hide();
						$('#passo3').show();

					},
					beforeSend: function() {
						$("#passo2").html('<div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)</div>');
					},
					error: function() {
						$.alert({
							title: "Erro ao processar",
							content: "Algo saiu errado. Por favor, tente novamente.",
							type: 'red'
						});
						$("#step3 div.fundo, #step3 a.btn").css('background', 'red');
					}
				});

			});

			$('.prev1').click(function() {
				$('#passo2').hide();
				$('#passo1').show();
				$("#step2 div.fundo, #step2 a.btn").removeClass('fundoAtivo');
			});

			var valCredito;
			var valUnico;
			var valTotal;
			$('#VAL_CREDITO').change(function() {
				valCredito = limpaValor($('#VAL_CREDITO').val());
				valUnico = limpaValor($('#TOTUNICO').val());
				valTotal = valCredito * limpaValor($('#TOTUNICO').val());

				$('#VAL_TOTAL').val();
				$('#VAL_TOTAL').unmask();
				$('#VAL_TOTAL').val(valTotal.toFixed(2));
				$('#VAL_TOTAL').mask("#.##0,00", {
					reverse: true
				});

				//alert(valTotal);

			});

			//validando inputs
			$('input[type=text],input[type=password]').keyup(function() {

				if ($('#VAL_CREDITO').val() != '' &&
					$('#DAT_VALIDADE').val() != '' &&
					$('#VAL_TOTAL').val() != '') {

					$('.next2').removeAttr('disabled');
				} else {
					$('.next2').attr('disabled', 'disabled');
				}
			});
		</script>


	<?php
		break;

	case "2":

		$cod_empresa = fnDecode($_GET['id']);

		$cod_persona = $_POST['COD_PERSONA'];
		$val_credito = $_POST['VAL_CREDITO'];
		$val_total  = $_POST['VAL_TOTAL'];
		$totpersona = $_POST['TOTPERSONA'];
		$totunico = $_POST['TOTUNICO'];

		$dat_validade  = fnDataSql($_POST['DAT_VALIDADE']);
		if ($dat_validade != "") {
			$dat_validade = $dat_validade . " 23:59:59";
		}

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		$sql = "CALL SP_CADASTR_CREDITOS_LOT (
	'" . $cod_persona . "', 
	'" . $cod_empresa . "',				 
	'" . fnValorSql($totpersona) . "', 
	'" . fnValorSql($totunico) . "', 
	'" . fnValorSql($val_credito) . "',
	'" . $cod_usucada . "',
	" . fnDateSql($dat_validade) . ",
	'CAD'
	) ";

		//echo $sql;
		mysqli_query(connTemp($cod_empresa, ""), trim($sql));


	?>

		<div class="row">

			<div class="col-md-3"></div>

			<div class="col-md-6">

				<div class="alert alert-success alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Créditos processados com <strong>sucesso!</strong>
				</div>

				<div class="push20"></div>

				<h4>Foram gerados <b>R$ <?php echo $val_total; ?></b> para <b><?php echo $totunico; ?></b> pessoas</h4>

				<div class="push50"></div>

			</div>

			<div class="col-md-3"></div>

			<div class="push20"></div>

			<hr>

			<div class="col-md-8"></div>
			<div class="col-md-2 pull-right">
				<a href="action.do?mod=<?php echo fnEncode(1311); ?>&id=<?php echo fnEncode($cod_empresa); ?>" class="btn btn-primary btn-block" name="next"><i class="fal fa-home"></i> &nbsp; Início </a>
			</div>

			<div class="push10"></div>
		</div>

		<script>
			$("#step3 div.fundo, #step3 a.btn").addClass('fundoAtivo');
		</script>


		<?php
		break;

	case 'ativo':

		$cod_empresa = fnDecode($_GET['id']);

		$log_ativo = $_POST['LOG_ATIVO'];

		$andAtivo = "AND B.LOG_ATIVO = 'S'";

		if ($log_ativo == "N") {
			$andAtivo = "";
		}

		$sql = "SELECT DISTINCT B.DES_PERSONA,
					   B.COD_PERSONA,
					   A.QTD_PESCLASS,
					   A.VAL_CREDITO,
					   (A.QTD_PESCLASS*A.VAL_CREDITO) AS TOT_CREDITO,
					   A.DAT_CADASTR,
					   A.DAT_VALIDADE,
					   B.LOG_ATIVO 
					   FROM PERSONA B
				LEFT JOIN CREDITOS_LOT A ON A.COD_PERSONAS=B.COD_PERSONA AND A.cod_empresa=$cod_empresa
				WHERE B.COD_EMPRESA = $cod_empresa
				$andAtivo
				ORDER BY A.DAT_CADASTR DESC";

		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$count = 0;
		while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {
			$count++;
			/*		
				echo "<pre>";
				print_r($qrListaPersonas);
				echo "</pre>";
				//exit();
				*/

			// $sqlPersonas = "SELECT COUNT(B.COD_CLIENTE) as TOTAL_PERSONA FROM PERSONACLASSIFICA B WHERE B.COD_PERSONA = ".$qrListaPersonas['COD_PERSONA']." AND B.COD_EMPRESA = $cod_empresa ";
			// //fnEscreve($sqlPersonas);
			// $ListaTotal = mysqli_query(connTemp($cod_empresa,''),$sqlPersonas);
			// $ListaTotal = mysqli_fetch_assoc($ListaTotal);

			if ($qrListaPersonas['LOG_ATIVO'] == "S") {
				$personaAtivo = "<i class='fal fa-check' aria-hidden='true'></i>";
			} else {
				$personaAtivo = "";
			}

		?>

			<tr>
				<td class="text-center"><input type="checkbox" class="bigCheck" name="cod_persona[]" id="cod_persona_<?php echo $count; ?>" value="<?php echo $qrListaPersonas['COD_PERSONA']; ?>" onclick='liberabtn("#cod_persona_")'>&nbsp;</td>
				<td><small><?php echo $qrListaPersonas['DES_PERSONA']; ?></small></td>
				<td class="text-center"><small><?php echo $qrListaPersonas['QTD_PESCLASS'] ?></small></td>
				<td class="text-right"><small><?php echo fnValor($qrListaPersonas['VAL_CREDITO'], 0); ?></small></td>
				<td class="text-right"><small><?php echo fnValor($qrListaPersonas['TOT_CREDITO'], 0); ?></small></td>
				<td><small><?php echo fnDataFull($qrListaPersonas['DAT_CADASTR']); ?></small></td>
				<td><small><?php echo fnDataFull($qrListaPersonas['DAT_VALIDADE']); ?></small></td>
				<td class='text-center'><?php echo $personaAtivo; ?></td>
			</tr>

<?php
		}

		break;

	default:
		return false;
		break;
}
?>