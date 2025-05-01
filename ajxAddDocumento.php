<?php 
include './_system/_functionsMain.php';
$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));

?>
	<select data-placeholder="Selecione o tipo de documento" name="COD_TIPODOC" id="COD_TIPODOC" class="chosen-select-deselect requiredChk" required>
		<option value=""></option>
		<?php
		$sql = "select COD_TIPODOC, NOM_TIPODOC from tipo_documento WHERE cod_empresa IN ($cod_empresa) AND COD_EXCLUSA = 0 order by nom_tipodoc ";
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

		while ($qrListaTipoDoc = mysqli_fetch_assoc($arrayQuery)) {
			echo "
				<option value='" . $qrListaTipoDoc['COD_TIPODOC'] . "'>" . $qrListaTipoDoc['NOM_TIPODOC'] . "</option> 
			";

		}
		?>
		<option class="fas fa-plus" value="add">&nbsp;ADICIONAR NOVO</option>
	</select>
	<script type="text/javascript">
		$('#COD_TIPODOC').change(function(){
			valor = $(this).val();
			if(valor=="add"){
				$(this).val('').trigger("chosen:updated");
				$('#btnCad_COD_TIPODOC').click();
			}
		});
	</script>
</div>

<script language=javascript> 
		$(".chosen-select-deselect").chosen({allow_single_deselect:true});
</script>