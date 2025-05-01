<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$grupoTrabalho = fnLimpaCampoZero($_GET['idg']);

?>

<div class="col-md-2">
	<div class="form-group">
		<label for="inputName" class="control-label">Agrupador da Entidade</label>
			<select data-placeholder="Selecione o agrupador da entidade" name="COD_GRUPOENT" id="COD_GRUPOENT" class="chosen-select-deselect" >
				<option value=""></option>
				<?php																	
					$sql = "SELECT COD_GRUPOENT,DES_GRUPOENT FROM Entidade_Grupo WHERE COD_EMPRESA = $cod_empresa AND COD_REGITRA = $grupoTrabalho";
					$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

					$grupos = "";
				
					while ($qrListaGrupoEntidade = mysqli_fetch_assoc($arrayQuery))
					{													
						$grupos .= "$qrListaGrupoEntidade[COD_GRUPOENT],";
					}
					$grupos = rtrim($grupos,',');

					// fnEscreve($sql2);
					$sql2 = "SELECT COD_ENTIDAD,NOM_ENTIDAD FROM ENTIDADE WHERE COD_EMPRESA = $cod_empresa AND COD_GRUPOENT IN($grupos) ORDER BY NOM_ENTIDAD";
					$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);

					// fnEscreve($sql2);
					while ($qrListaGrupoEntidade2 = mysqli_fetch_assoc($arrayQuery2))
					{													
						echo"
							  <option value='".$qrListaGrupoEntidade2['COD_ENTIDAD']."'>".$qrListaGrupoEntidade2['NOM_ENTIDAD']."</option> 
							"; 
					}											
				?>	
			</select>
		<div class="help-block with-errors"></div>
	</div>
</div>
<script type="text/javascript">$("#COD_GRUPOENT").chosen();</script>