<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_univend = fnLimpaCampoZero($_POST['COD_UNIVEND']);
	$cod_profiss = fnLimpaCampoZero($_POST['COD_PROFISS']);

?>

<fieldset>
	<legend>Dados do Contrato</legend>

	<div class="row">

		<div class="col-sm-2">
			<div class="form-group">
				<label for="inputName" class="control-label">Contrato</label>

				<select data-placeholder="Selecione um contrato" name="COD_TPCONTRAT" id="COD_TPCONTRAT" class="chosen-select-deselect" tabindex="1" onchange="mostraVeiculos(this)" data-element="#dados">
					<option value="1">Genérico</option>
					<option value="2">Cabo Eleitoral</option>
					<option value="3">Coordenador Cabo Eleitoral</option>
					<option value="4">Cessão Serviços</option>
					<option value="5">Cessão Gratuita de Veículos</option>

				</select>

			</div>

		</div>

		<div class="col-md-2">
			<div class="form-group">
				<label for="inputName" class="control-label required">Data Inicial Vigência</label>

				<div class="input-group date datePicker" id="DAT_INI_GRP">
					<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="" required />
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
				<div class="help-block with-errors"></div>
			</div>
		</div>

		<div class="col-md-2">
			<div class="form-group">
				<label for="inputName" class="control-label required">Data Final Vigência</label>

				<div class="input-group date datePicker" id="DAT_FIM_GRP">
					<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="" required />
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>

					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>

		<div class="col-md-2">
			<div class="form-group">
				<label for="inputName" class="control-label required">Valor Total do Contrato</label>
				<input type="tel" class="form-control input-sm money" name="VAL_CONTRAT" id="VAL_CONTRAT" value="" required>
				<div class="help-block with-errors">Em Reais pelo período(R$)</div>
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<label for="inputName" class="control-label required">Forma de Pagamento</label>

				<select data-placeholder="Selecione uma forma de pagamento" name="COD_FORMAPA" id="COD_FORMAPA" class="chosen-select-deselect" tabindex="1" required>
					<option value="1">Dinheiro</option>
					<option value="2">Pix</option>
					<option value="3">TED/DOC</option>
					<option value="4">Cheque</option>

				</select>

			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<label for="inputName" class="control-label">Periodicidade de Pagamento</label>

				<select data-placeholder="Selecione um prazo" name="TIP_PAGAMEN" id="TIP_PAGAMEN" class="chosen-select-deselect" tabindex="1" required>
					<option value="0">Pagamento Único</option>
					<option value="1">Diário</option>
					<option value="7">Semanal</option>
					<option value="15">Quinzenal</option>
					<option value="30">Mensal</option>

				</select>

			</div>

		</div>

	</div>

	<div class="row">

		<div class="col-md-3" id="div_veiculos" style="display: none;">
			<div class="form-group">
				<label for="inputName" class="control-label required">Veículo</label>

				<select data-placeholder="Selecione um veículo" name="COD_VEICULO" id="COD_VEICULO" class="chosen-select-deselect" tabindex="1">
					<option value=""></option>
					<?php
                      
                        $sql = "SELECT * FROM VEICULO_CLIENTE 
								WHERE COD_CLIENTE = $cod_cliente
								AND COD_EMPRESA = $cod_empresa
								AND COD_EXCLUSA = 0
								ORDER BY DES_MARCA";

                        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
                        //fnEscreve($sql);
                        
                        while ($qrVeic = mysqli_fetch_assoc($arrayQuery))
                          {
                            
                            echo"
                                  <option value='".$qrVeic['COD_VEICULO']."'>".$qrVeic['DES_MARCA']." ".$qrVeic['DES_MODELO']." ".($qrVeic['DES_PLACA'])."</option> 
                                "; 
                              }                                         
                    ?>
				</select>

			</div>
		</div>

	</div>

</fieldset>

<div class="push30"></div>

<div class="col-lg-12">

	<table class="table table-bordered table-striped table-hover tableSorter">
		<thead>
			<tr>
				<th class="text-center {sorter:false}" width="40"><small>Todos</small><br><input type='checkbox' id="selectAll"></th>
				<th>Código</th>
				<th>Nome</th>
				<th>Cargo</th>
			</tr>
		</thead>
		<tbody>

			<?php

			$sql = "SELECT COD_CLIENTE,NOM_CLIENTE,b.NOM_FANTASI,c.DES_PROFISS FROM clientes a,unidadevenda b, profissoes_pref c 
					WHERE a.COD_UNIVEND=b.cod_univend AND 
					      a.cod_profiss=c.cod_profiss AND 
					      a.cod_empresa=$cod_empresa AND 
					      a.cod_profiss=$cod_profiss AND 
					      a.cod_univend=$cod_univend AND 
					      a.cod_indicad!=29007 AND 
					      NOT EXISTS (SELECT 1 FROM contrato_eleitoral WHERE contrato_eleitoral.cod_cliente=a.COD_CLIENTE)";

			// fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sql);

			$count = 0;
			while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
				$count++;

			?>

				<tr>
					<td class='text-center'><input type='checkbox' name='radio_<?=$count?>' onclick='attListaClientes(<?=$count?>)'>&nbsp;</td>
					<td><?=$qrBuscaModulos['COD_CLIENTE']?></td>
					<td><?=$qrBuscaModulos['NOM_CLIENTE']?></td>
					<td><?=$qrBuscaModulos['DES_PROFISS']?></td>
				</tr>

				<input type="hidden" id="ret_COD_CLIENTE_<?=$count?>" value="<?=$qrBuscaModulos['COD_CLIENTE']?>">

			<?php 
			}

			?>

		</tbody>
	</table>

	<hr>
	<div class="form-group text-right col-lg-12">

		<!-- <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
		<button type="submit" name="CAD" id="CAD" class="btn btn-success getBtn"><i class="fal fa-check" aria-hidden="true"></i>&nbsp; Gerar contratos em lote</button>
		<!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
		<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

	</div>

</div>

<script>

	$(function(){

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$('.chosen-select-deselect').chosen({allow_single_deselect:true});

		$('#selectAll').click(function () {
		    $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
		    attListaClientes();
		});
		
	});

	function attListaClientes(index){

		listaClientes = [];

		$("table tr").each(function(index) {

			if($(this).find("input[type='checkbox']:not('#selectAll')").is(':checked')){

				var codigo = $(this).find("input[type='checkbox']").attr('name').replace('radio_', '');
				listaClientes.push($("#ret_COD_CLIENTE_"+index).val());

			}

		});

		console.log(listaClientes);

		$("#CLIENTES_CONTRATO").val(listaClientes);

	}

</script>