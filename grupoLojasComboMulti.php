	<select data-placeholder="Selecione um grupo de lojas" name="COD_GRUPOTR[]" id="COD_GRUPOTR" multiple="multiple" class="chosen-select-deselect">
		<option value=""></option>
		<?php
		$sql = "select * from grupotrabalho where cod_empresa = $cod_empresa order by DES_GRUPOTR";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
		while ($qrListaGrupoWork = mysqli_fetch_assoc($arrayQuery)) {

			if (!empty($_REQUEST['COD_GRUPOTR']) && is_array($_REQUEST['COD_GRUPOTR'])) {
				if (recursive_array_search($qrListaGrupoWork['COD_GRUPOTR'], array_filter($_REQUEST['COD_GRUPOTR'])) !== false) {
					$selecionado = "selected";
				} else {
					$selecionado = "";
				}
			} else {
				$selecionado = "";
			}

			echo "<option value='" . $qrListaGrupoWork['COD_GRUPOTR'] . "' " . $selecionado . " >" . $qrListaGrupoWork['DES_GRUPOTR'] . "</option>";
		}
		?>
	</select>
	<div class="help-block with-errors"></div>

	<a class="btn btn-default btn-sm" id="iAll_COD_GRUPOTR" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square-o" aria-hidden="true"></i> selecionar todos</a>&nbsp;
	<a class="btn btn-default btn-sm" id="iNone_COD_GRUPOTR" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>

	<script type="text/javascript">
		$('#iAll_COD_GRUPOTR').on('click', function(e) {
			e.preventDefault();
			$('#COD_GRUPOTR option').prop('selected', true).trigger('chosen:updated');
		});

		$('#iNone_COD_GRUPOTR').on('click', function(e) {
			e.preventDefault();
			$("#COD_GRUPOTR option:selected").removeAttr("selected").trigger('chosen:updated');
		});
	</script>