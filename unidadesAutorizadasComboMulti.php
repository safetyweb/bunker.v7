	<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND[]" id="COD_UNIVEND" multiple="multiple" class="chosen-select-deselect requiredChk" required>
		<?php

		// alterado dia 07/12/2023 o esquema de verifica��o de unidades autorizadas por Ricardinho
		$arrUnidadesUsu = explode(",", $cod_univendUsu);

		//n�o mostra todas em telas que n�o s�o relat�rios'
		$naomostra = fnDecode($_GET['mod']);
		switch ($naomostra) {
			case 1406: //tela de cupom
				$mostraTodas = "N";
				break;
			default;
				$mostraTodas = "S";
				break;
		}

		if ($mostraTodas == "S") {
			if ($cod_univend == "9999") {
				echo "<option value='9999' selected>Todas Unidades</option>";
			} else {
				echo "<option value='9999'>Todas Unidades</option>";
			}
		}

		$sql = "select COD_UNIVEND, NOM_FANTASI, NOM_UNIVEND from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' AND LOG_ESTATUS = 'S' order by trim(NOM_FANTASI) ";
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
		//fnEscreve($sql);

		while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery)) {

			//if ($cod_univend == $qrListaUnidades['COD_UNIVEND']){ $selecionado = "selected";}else{$selecionado = "";}	

			if (isset($_REQUEST['COD_UNIVEND']) && is_array($_REQUEST['COD_UNIVEND'])) {
				if (recursive_array_search($qrListaUnidades['COD_UNIVEND'], array_filter($_REQUEST['COD_UNIVEND'])) !== false) {
					$selecionado = "selected";
				} else {
					$selecionado = "";
				}
			} else {
				$selecionado = "";
			}

			//verifica acesso master
			if ($usuReportAdm == "N") {
				// alterado dia 07/12/2023 o esquema de verifica��o de unidades autorizadas por Ricardinho
				// if (strlen(strstr($cod_univendUsu,$qrListaUnidades['COD_UNIVEND']))>0){ $lojaAtiva = "";}else{$lojaAtiva = "disabled";}
				if (in_array($qrListaUnidades['COD_UNIVEND'], $arrUnidadesUsu)) {
					$lojaAtiva = "";
				} else {
					$lojaAtiva = "disabled";
				}
			} else {
				$lojaAtiva = " ";
			}

			if ($lojaAtiva != "disabled") {
				echo "
					  <option value='" . $qrListaUnidades['COD_UNIVEND'] . "' " . $selecionado . " " . $lojaAtiva . ">" . $qrListaUnidades['NOM_FANTASI'] . "</option> 
					";
			}
		}
		?>
	</select>
	<?php  //echo "_".$cod_univendUsu;
	// echo "_".$sql; 
	?>
	<div class="help-block with-errors"></div>
	<a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square-o" aria-hidden="true"></i> selecionar todos</a>&nbsp;
	<a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>

	<script type="text/javascript">
		$('#iAll').on('click', function(e) {
			e.preventDefault();
			$('#COD_UNIVEND option').prop('selected', true).trigger('chosen:updated');
		});

		$('#iNone').on('click', function(e) {
			e.preventDefault();
			$("#COD_UNIVEND option:selected").removeAttr("selected").trigger('chosen:updated');
		});
	</script>