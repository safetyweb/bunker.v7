	<select data-placeholder="Selecione uma região" name="COD_TIPOREG[]" id="COD_TIPOREG" multiple="multiple" class="chosen-select-deselect">
		<option value=""></option>
		<?php
		$sql = "select * from regiao_grupo where cod_empresa = $cod_empresa order by des_tiporeg";
		//fnEscreve($sql);
		@$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);
		while (@$qrListaRegiao = mysqli_fetch_assoc($arrayQuery)) {


			if (!empty($_REQUEST['COD_TIPOREG']) && is_array($_REQUEST['COD_TIPOREG'])) {
				if (recursive_array_search($qrListaRegiao['COD_TIPOREG'], array_filter($_REQUEST['COD_TIPOREG'])) !== false) {
					$selecionado = "selected";
				} else {
					$selecionado = "";
				}
			} else {
				$selecionado = "";
			}


			echo "<option value='" . $qrListaRegiao['COD_TIPOREG'] . "' " . $selecionado . " >" . $qrListaRegiao['DES_TIPOREG'] . "</option>";
		}
		?>
	</select>
	<div class="help-block with-errors"></div>

	<a class="btn btn-default btn-sm" id="iAll_COD_TIPOREG" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square-o" aria-hidden="true"></i> selecionar todas</a>&nbsp;
	<a class="btn btn-default btn-sm" id="iNone_COD_TIPOREG" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todas</a>

	<script type="text/javascript">
		$('#iAll_COD_TIPOREG').on('click', function(e) {
			e.preventDefault();
			$('#COD_TIPOREG option').prop('selected', true).trigger('chosen:updated');
		});

		$('#iNone_COD_TIPOREG').on('click', function(e) {
			e.preventDefault();
			$("#COD_TIPOREG option:selected").removeAttr("selected").trigger('chosen:updated');
		});
	</script>