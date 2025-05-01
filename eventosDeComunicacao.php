<?php

	//echo "<h5>_".$opcao."</h5>";                  

$hashLocal = mt_rand();	

$modulo = fnDecode($_GET['mod']);
$cod_modulo = fnDecode($_GET['mod']);
$adm = $connAdm->connAdm();
if($_SERVER['REQUEST_METHOD']=='POST')
{
	$request = md5(implode( $_POST ));

	if(isset($_SESSION['last_request']) && $_SESSION['last_request']== $request)
	{
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	}
	else
	{
		$_SESSION['last_request']  = $request;

		$cod_evento = fnLimpaCampoZero($_REQUEST['cod_evento']);
		$cod_comunicacao = fnLimpaCampoZero($_REQUEST['cod_comunicacao']);
		$des_evento = fnLimpaCampo($_REQUEST['des_evento']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {			

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

				$sql = "select max(num_ordenac) num_ordenac from tip_evento ";
				$arrayQuery = mysqli_query($adm,$sql);
				$qrModulo = mysqli_fetch_assoc($arrayQuery);
				$num_ordenac = $qrModulo["num_ordenac"] + 1;


				$sql = "insert into tip_evento(
				cod_comunicacao,
				des_evento,
				num_ordenac
				)
				values(
				$cod_comunicacao,
				'$des_evento',
				$num_ordenac
			)";

			$arrayProc = mysqli_query($adm,$sql);

			if (!$arrayProc){
				$cod_error = Log_error_comand($connAdm->connAdm(),$connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
			}
			if ($cod_erro == 0 || $cod_erro ==  "") {
				$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
			} else {
				$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
			}
			break;



			case 'ALT':

			$sql = "update tip_evento set 
			des_evento = '$des_evento',
			cod_comunicacao = '$cod_comunicacao'
			where cod_evento = $cod_evento
			";

			$arrayProc = mysqli_query($adm,$sql);
			if ($cod_erro == 0 || $cod_erro ==  "") {
				$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
			} else {
				$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
			}
			break;


			case 'EXC':
			$sql = "delete from tip_evento where cod_evento = $cod_evento";

			$arrayProc = mysqli_query($adm,$sql);
			if ($cod_erro == 0 || $cod_erro ==  "") {
				$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
			} else {
				$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
			}
			break;

		}
		if ($cod_erro == 0 || $cod_erro == "") {
			$msgTipo = 'alert-success';
		} else {
			$msgTipo = 'alert-danger';
		}
	}
}
}


	//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);	
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)){
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}

}else {
	$cod_empresa = 0;		
		//fnEscreve('entrou else');
}

	//fnMostraForm(); 

?>			
<div class="push30"></div> 

<div class="row">				

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>

				<?php 
									//$formBack = "1019";
				include "atalhosPortlet.php"; 
				?>	

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>	
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>


				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend> 

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="cod_evento" id="cod_evento" value="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo</label>
										<select data-placeholder="Selecione o tipo" name="cod_comunicacao" id="cod_comunicacao" class="chosen-select-deselect" style="width:100%;">
											<option value=""></option>																     

											<?php
											$sql = "select * from cat_comunicacao";
											$arrayProc = mysqli_query($adm,$sql);

											while($qrBusca = mysqli_fetch_assoc($arrayProc)) {

												echo "<option value=".$qrBusca['cod_comunicacao'].">".$qrBusca['des_comunicacao']."</option>";
												
											}
											?>																
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição</label>
										<input type="text" class="form-control input-sm" name="des_evento" id="des_evento" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">	

					</form>

					<div class="push5"></div>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th width="40"></th>
											<th>Código</th>
											<th>Descrição</th>
											<th>Tipo Comunicação</th>
										</tr>
									</thead>

									<tbody>
										<?php 

										$sql = "select 
										te.*, 
										cc.des_comunicacao 
										from tip_evento as te 
										inner join cat_comunicacao as cc on te.cod_comunicacao = cc.cod_comunicacao order by des_comunicacao,des_evento";

										$arrayQuery = mysqli_query($adm,$sql);

										$count=0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
										{
											$count++;

											?>
											<tr>
												<td><input type='radio' name='radio1' onclick='retornaForm(<?php echo $count;?>)'></td>
												<td><?php echo $qrBuscaModulos['cod_evento']; ?></td>
												<td><?php echo $qrBuscaModulos['des_evento']; ?></td>
												<td><?php echo $qrBuscaModulos['des_comunicacao']; ?></td>
											</tr>

											<input type='hidden' id='ret_cod_evento_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['cod_evento']; ?>'>
											<input type='hidden' id='ret_cod_comunicacao_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['cod_comunicacao']; ?>'>
											<input type='hidden' id='ret_des_evento_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['des_evento']; ?>'>
											<input type='hidden' id='ret_num_ordenac_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['num_ordenac']; ?>'>
											<?php 
										}
										?>

									</tbody>
								</table>

							</form>

						</div>

					</div>										

					<div class="push"></div>

				</div>								

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>					

<div class="push20"></div> 
<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>
<script type="text/javascript" src="js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>

	$(document).ready( function() {


	});

	function retornaForm(index){
		$("#formulario #cod_evento").val($("#ret_cod_evento_"+index).val());
		$("#formulario #des_evento").val($("#ret_des_evento_"+index).val());
		$("#formulario #cod_comunicacao").val($("#ret_cod_comunicacao_"+index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');						
	}

</script>	