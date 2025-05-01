	<div class="push10"></div>
	<div class="col-md-12">
		<h4>Projeção de parcelas</h4>
	</div>


<?php 

	include '_system/_functionsMain.php';

	$num_parcelas = fnLimpaCampo($_POST['NUM_PARCELAS']);
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$val_a_pagar = fnLimpaCampo($_POST['VAL_A_PAGAR']);
	$val_a_pagar_mask = fnValor($val_a_pagar,2);
	$dat_vencimen = $dat_ini;
	$val_parcela = 0;
	$val_multiplicado = 0;
	$diferenca = 0;

	for ($i=1; $i <= $num_parcelas; $i++) { 

		$val_parcela = ($val_a_pagar/$num_parcelas);
		$val_parcela_mask = fnValor($val_parcela,2);

		if($i == $num_parcelas){

			$val_multiplicado = fnValorSql($val_parcela_mask) * $num_parcelas;
			if($val_multiplicado != $val_a_pagar){
				$diferenca = $val_a_pagar - $val_multiplicado;
				$val_parcela += $diferenca;
				$val_parcela_mask = fnValor($val_parcela,2);
			}
		}

?>

	<div class="col-md-12">

		<div class="row" style="display: inline-flex; align-content: space-between;">
		
			<div class="col-md-4 text-left" style="align-self: center;">
				<div class="form-group">
					<span><?=$i?>ª parcela <small>R$</small> <b><?=$val_parcela_mask?></b></span>
				</div>
			</div>
			
			<div class="col-md-3" style="align-self: center; text-align: center;">
				<span>vencimento em</span>
			</div>

			<div class="col-md-5 pull-right">
				
				<div class="form-group">
					<div class="input-group date datePicker" id="DAT_INI_GRP_<?=$i?>">
						<input type='text' class="form-control input-sm data text-center" name="DAT_INI_<?=$i?>" id="DAT_INI_<?=$i?>" value="" required />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
					<div class="help-block with-errors"></div>
				</div>
				
			</div>

		</div>

		<input type="hidden" name="VAL_PARCELA_<?=$i?>" val="VAL_PARCELA_<?=$i?>" value="<?=$val_parcela_mask?>">
		
	</div>

	<script type="text/javascript">
		$(function(){
			$("#DAT_INI_<?=$i?>").val("<?=fnDataShort($dat_vencimen)?>");
		});
	</script>

<?php 
		$dat_vencimen = date('Y-m-d',strtotime($dat_vencimen." + 1 month"));

	}

?> 


<div class="col-md-6 text-right" style="padding-right: 30px;">
	<div class="push20"></div>
	<!-- <a href="javascript:void(0)" class='btn btn-primary' onclick='document.getElementById("parcelamentoFinal").submit();'>Salvar parcelamento</a> -->
	<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn">Salvar Parcelamento</button>
</div>
<input type="hidden" name="QTD_PARCELA" val="QTD_PARCELA" value="<?=$num_parcelas?>">
<input type="hidden" name="opcao" val="opcao" value="CAD">

<script type="text/javascript">
	$(function(){
		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});
		// $("#CAD").click(function(e){
		// 	// alert();
		// 	e.preventDefault();
		// 	document.getElementById("parcelamentoFinal").submit();
		// });
	});
</script>