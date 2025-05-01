<?php
$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_pedido = fnLimpaCampoZero(fnDecode($_GET['idp']));
$cod_hospede = fnLimpaCampoZero(fnDecode($_GET['idhp']));
$cod_propriedade = fnLimpaCampo($_POST['COD_PROPRIEDADE']);


$sqlHospedes = "SELECT * FROM HOSPEDES_ADORAI WHERE COD_PEDIDO =  $cod_pedido  AND COD_EMPRESA =  $cod_empresa AND COD_HOSPEDE = $cod_hospede";
$queryHospedes = mysqli_query(connTemp($cod_empresa, ''), $sqlHospedes);


$hospedesResult = mysqli_fetch_assoc($queryHospedes);
$nome = $hospedesResult['NOM_HOSP'];
$sobrenome = $hospedesResult['SOBRENOM_HOSP'];
$cpf = $hospedesResult['NUM_CGCECPF'];
$email = $hospedesResult['DES_EMAILUS'];
$sexo = $hospedesResult['DES_SEXOPES'];
$telefone = $hospedesResult['NUM_TELEFONE'];
$nascimento = $hospedesResult['DAT_NASCIME'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));
	
    $opcao = $_REQUEST['opcao'];
    $hHabilitado = $_REQUEST['hHabilitado'];
    $hashForm = $_REQUEST['hashForm'];

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		if ($opcao != '') {		
			//mensagem de retorno
            $nomePost = fnLimpaCampo($_REQUEST['NOM_HOSP']);
            $sobrenomePost = fnLimpaCampo($_REQUEST['SOBRENOM_HOSP']);
            $cpfPost = fnLimpaCampo($_REQUEST['NUM_CGCECPF']);
            $emailPost = fnLimpaCampo($_REQUEST['DES_EMAILUS']);
            $sexoPost = fnLimpaCampoZero($_REQUEST['DES_SEXOPES']);
            $telefonePost = fnLimpaCampo($_REQUEST['NUM_TELEFONE']);
            $nascimentoPost = fnDataSql($_REQUEST['DAT_NASCIME']);
            
			switch ($opcao) {
				case 'ALT':
                    $sqlUpdate = "UPDATE HOSPEDES_ADORAI SET
                    NOM_HOSP = '$nomePost',
                    SOBRENOM_HOSP = '$sobrenomePost',
                    NUM_CGCECPF = '$cpfPost',
                    DES_EMAILUS = '$emailPost',
                    DES_SEXOPES = $sexoPost,
                    DAT_NASCIME = '$nascimentoPost',
                    NUM_TELEFONE = '$telefonePost',
                    DAT_ALTERAC = NOW()
                    WHERE COD_HOSPEDE = $cod_hospede AND COD_PEDIDO = $cod_pedido AND COD_EMPRESA = $cod_empresa
                	";
                mysqli_query(connTemp($cod_empresa,''),$sqlUpdate);
					$sqlUpdatePedidos = "UPDATE ADORAI_PEDIDO SET
					NOME = '$nomePost',
					SOBRENOME = '$sobrenomePost',
					CPF = '$cpfPost',
					EMAIL = '$emailPost',
					TELEFONE = '$telefonePost',
					DAT_ALTERAC = NOW()
					WHERE COD_PEDIDO = $cod_pedido AND COD_EMPRESA = $cod_empresa AND NOME = '$nome' AND SOBRENOME = '$sobrenome'
					AND EMAIL = '$email' 
					";
                // fnTestesql(connTemp($cod_empresa, ''), $sqlUpdate);
                mysqli_query(connTemp($cod_empresa,''),$sqlUpdatePedidos);
                break;
                }
            }
		// fnEscreve($cod_hotel);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

	}
}




?>

<style>
    html{
        overflow-x: hidden;
    }
    body{
        width:100%;
        height:100%;
    }

    .portlet{
        padding:0;
    }
	.table-container td {
		padding: 8px;
	}

	.table-container tbody tr:last-child td {
		border-bottom: 1px solid #dddddd;
	}
	
	ul.summary-list {
		display: inline-block;
		padding-left:0 ;
		width: 100%;
		margin-bottom: 0;
	}

	ul.summary-list > li {
		display: inline-block;
		width: 19.5%;
		text-align: center;
	}

	ul.summary-list > li > a > i {
		display:block;
		font-size: 18px;
		padding-bottom: 5px;
	}

	ul.summary-list > li > a {
		padding: 10px 0;
		display: inline-block;
		color: #818181;
	}

	ul.summary-list > li  {
		border-right: 1px solid #eaeaea;
	}

	ul.summary-list > li:last-child  {
		border-right: none;
	}

    .form-group{
        margin-top:10px;
    }
</style>
<div class="row">				
	<div class="col-md12 margin-bottom-30">
		<div class="portlet">
			<div class="portlet-body">
				
				<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
					<div class="push5"></div>

					<fieldset>
						<legend>Dados do Hospede</legend>
						<div class="push10"></div>
						<div class="row">

							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label for="inputName" class="control-label">Nome</label>
									<input type="text" class="form-control input-sm"  name="NOM_HOSP" id="NOM_HOSP" value="<?= strtoupper($nome)?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>
			                <div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label for="inputName" class="control-label">Sobrenome</label>
									<input type="text" class="form-control input-sm"  name="SOBRENOM_HOSP" id="SOBRENOM_HOSP" value="<?= strtoupper($sobrenome)?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>

				
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label for="inputName" class="control-label">CPF</label>
									<input type="text" class="form-control input-sm"  name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?=$cpf?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>
                        </div>
                        <div class="row">
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label for="inputName" class="control-label">E-mail</label>
									<input type="text" class="form-control input-sm"  name="DES_EMAILUS" id="DES_EMAILUS" value="<?=$email?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>
                            <div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label for="inputName" class="control-label">Telefone</label>
									<input type="text" class="form-control input-sm"  name="NUM_TELEFONE" id="NUM_TELEFONE" maxlength='11' value="<?=$telefone?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="inputName" class="control-label ">Sexo do Hospede</label>
                                    <select data-placeholder="Selecione os hotéis" name="DES_SEXOPES" id="DES_SEXOPES" class="chosen-select-deselect" >
                                        <option value="" disabled selected hidden>Selecione seu Sexo</option>
                                        <option value="1">Masculino</option>
                                        <option value="2">Feminino</option>
                                        <option value="3">Prefiro não Enformar</option>
                                    </select>
                                    <script>
                                        $("#DES_SEXOPES").val("<?php echo $sexo?>").trigger("chosen:updated");
                                    </script>
                                    <div class="help-block with-errors"></div>
                                </div>
							</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">Nascimento</label>

                                    <div class="input-group date datePicker" id="DAT_FIM_GRP">
                                        <input type='text' class="form-control input-sm data" name="DAT_NASCIME" id="DAT_NASCIME" value="<?=fnDataShort($nascimento)?>" required/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>
					</fieldset>
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
					</div>
                    <div>
						<button type="button" class="btn close" data-dismiss="modal">Fechar</button>
                        <button type="submit" name="ALT" id="ALT" value="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript" src="js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>

<script>
	
	function retornaForm(index) {
		$("#formulario #COD_STATUSPAG").val($("#ret_COD_STATUSPAG_" + index).val());
		$("#formulario #DES_STATUSPAG").val($("#ret_DES_STATUSPAG_" + index).val());
		$("#formulario #ABV_STATUSPAG").val($("#ret_ABV_STATUSPAG_" + index).val());
		$("#formulario #TIP_ACAORESERVA").val($("#ret_TIP_ACAORESERVA_" + index).val());
		$("#formulario #DES_COR").val($("#ret_DES_COR_"+index).val());
		$('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONE_"+index).val());
		$("#formulario #DES_ICONE").val($("#ret_DES_ICONE_"+index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
	$('.datePicker').datetimepicker({
		format: 'DD/MM/YYYY'
	}).on('changeDate', function(e) {
		$(this).datetimepicker('hide');
	});
    $('#NUM_CGCECPF').mask('000.000.000-00');

	$("#DAT_INI_GRP").on("dp.change", function(e) {
		$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
	});

	$("#DAT_FIM_GRP").on("dp.change", function(e) {
		$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
	});


	// ajax
	$("#COD_PROPRIEDADE").ready(function() {
		var codBusca = $("#COD_PROPRIEDADE").val();
		var codBusca3 = $("#COD_EMPRESA").val();
		buscaSubCat(codBusca, codBusca3);
	});
	

	function buscaSubCat(codprop, idEmp) 
	{
		$.ajax({
			type: "GET",
			url: "ajxCheckoutAdorai.do?opcao=SubBusca",
			data: {
				COD_PROPRIEDADE: codprop,
				COD_EMPRESA: idEmp
			},

			beforeSend: function() {
				$('.divId_sub').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$(".divId_sub").html(data);
			},
			error: function() {
				$('.divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}
</script>