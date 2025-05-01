<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_saldo = fnLimpaCampoZero($_REQUEST['COD_SALDO']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $des_logo = fnLimpaCampo($_REQUEST['DES_LOGO']);
        $des_alinham = fnLimpaCampo($_REQUEST['DES_ALINHAM']);
        $des_imgback = fnLimpaCampo($_REQUEST['DES_IMGBACK']);       
        $cor_backbar = fnLimpaCampo($_REQUEST['COR_BACKBAR']);
        $cor_backpag = fnLimpaCampo($_REQUEST['COR_BACKPAG']);
        $cor_textos = fnLimpaCampo($_REQUEST['COR_TEXTOS']);
		
        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao == 999) {
        //if ($opcao != '') {
			
            $sql = "CALL SP_ALTERA_SITE_SALDO (         
				 '" . $cod_saldo . "', 
				 '" . $cod_empresa . "', 
				 '" . $des_logo . "', 
				 '" . $des_alinham . "', 
				 '" . $des_imgback . "', 
				 '" . $cor_backbar . "', 
				 '" . $cor_backpag . "', 
				 '" . $cor_textos . "', 
				 '" . $log_totganho . "', 
				 '" . $cor_totganho . "', 
				 '" . $log_totresga . "', 
				 '" . $cor_totresga . "', 
				 '" . $log_liberar . "', 
				 '" . $cor_liberar . "', 
				 '" . $log_expirar . "', 
                 '" . $cor_expirar . "' 
				) ";
			
			//fnEscreve($cod_empresa);			
            //echo $sql;
            mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
			
            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    break;
                case 'ALT':
                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    break;
                case 'EXC':
                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
                    break;
                    break;
            }
            $msgTipo = 'alert-success';
        }
    }
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
    }
} else {
    $cod_empresa = 0;
    //fnEscreve('entrou else');
}

//busca dados da tabela
$sql = "SELECT * FROM SITE_SALDO WHERE COD_EMPRESA = $cod_empresa ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
$qrBuscaSiteTotem = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotem)) {
    //fnEscreve("entrou if");

    $cod_saldo = $qrBuscaSiteTotem['COD_SALDO'];
    $des_logo = $qrBuscaSiteTotem['DES_LOGO'];
    $des_alinham = $qrBuscaSiteTotem['DES_ALINHAM'];
    $des_imgback = $qrBuscaSiteTotem['DES_IMGBACK'];
    $cor_backbar = $qrBuscaSiteTotem['COR_BACKBAR'];
    $cor_backpag = $qrBuscaSiteTotem['COR_BACKPAG'];
    $cor_textos = $qrBuscaSiteTotem['COR_TEXTOS'];

    if ($qrBuscaSiteTotem['LOG_TOTGANHO'] == "N") {
        $check_TOTGANHO = '';
    } else {
        $check_TOTGANHO = "checked";
    }
    $cor_totganho = $qrBuscaSiteTotem['COR_TOTGANHO'];
		
    if ($qrBuscaSiteTotem['LOG_TOTRESGA'] == "N") {
        $check_TOTRESGA = '';
    } else {
        $check_TOTRESGA = "checked";
    }
    $cor_totresga = $qrBuscaSiteTotem['COR_TOTRESGA'];
			
    if ($qrBuscaSiteTotem['LOG_LIBERAR'] == "N") {
        $check_LIBERAR = '';
    } else {
        $check_LIBERAR = "checked";
    }
    $cor_liberar = $qrBuscaSiteTotem['COR_LIBERAR'];
					
    if ($qrBuscaSiteTotem['LOG_EXPIRAR'] == "N") {
        $check_EXPIRAR = '';
    } else {
        $check_EXPIRAR = "checked";
    }
    $cor_liberar = $qrBuscaSiteTotem['COR_LIBERAR'];
		
	
} else {
    //default se vazio
    //fnEscreve("entrou else");
    
	$cod_saldo = 0;
	$des_logo = "";
	$des_alinham = "left";
	$des_imgback = "";

    $cor_backbar = "";
    $cor_backpag = "#f2f3f4";
    $cor_textos = "#34495e";
	
	$check_TOTGANHO = "checked";
	$cor_totganho = "#1a4e95";

	$check_TOTRESGA = "checked";
	$cor_totresga = "#35aadc";

	$check_LIBERAR = "checked";
	$cor_liberar = "#cc324b";

	$check_EXPIRAR = "checked";
	$cor_expirar = "#193042";

}

	//fnEscreve($log_usuario);
	//fnEscreve($des_senhaus);
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
                $formBack = "1019";
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
				
                <div class="push20"></div> 

				<?php
				$abaEmpresa = 1264;
				include "abasEmpresaConfig.php";
				?>
				
				<div class="push20"></div> 
				
				<?php
				$abaCategoria = 1265;
				include "abasCategoriaEmpresa.php"; 
				?>

                <div class="push30"></div> 

                <div class="login-form"> 

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Parâmetros de Classificação Automática</legend> 

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Empresa</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
                                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                                    </div>														
                                </div>
								
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Qtd. </label>
										<input type="text" class="form-control text-center input-sm money" name="VAL_FAIXAINI" id="VAL_FAIXAINI" maxlength="10" required>
										<div class="help-block with-errors">volume de compras</div>
									</div>
								</div>	

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Alinhamento do Logo</label>
											<select data-placeholder="Selecione um alinhamento" name="DES_ALINHAM" id="DES_ALINHAM" class="chosen-select-deselect" required>
												<option value=""></option>
												<option value="left">Esquerda</option>
												<option value="center">Centro</option>
												<option value="right">Direita</option>
											</select>
											<script>$("#formulario #DES_ALINHAM").val("<?php echo $des_alinham; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
								</div>
													
                            </div>

                        </fieldset>	
						
                        <div class="push10"></div>
                        <hr>	
                        <div class="form-group text-right col-lg-12">

                            <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
                            <?php if ($cod_saldo == 0) { ?>	
                                <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <?php } else { ?>
                                <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <?php } ?>	

                        </div>

                        <input type="hidden" name="COD_SALDO" id="COD_SALDO" value="<?php echo $cod_saldo; ?>">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		

                        <div class="push5"></div> 

                    </form>

                    <div class="push50"></div>										

                </div>								

            </div>
        </div>
        <!-- fim Portlet -->
    </div>

</div>	

<!-- modal -->									
<div class="modal fade" id="popModal" tabindex='-1'>
    <div class="modal-dialog" style="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
            </div>		
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<div class="push20"></div> 

<link rel="stylesheet" href="fonts/font-awesome-4.2.0/css/font-awesome.min.css"/>
<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>

<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.2.0.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script type="text/javascript">

    $(document).ready(function () {

		//chosen
        $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
        $('#formulario').validator();
    });

    function retornaForm(index) {
        $("#formulario #COD_MAQUINA").val($("#ret_COD_MAQUINA_" + index).val());
        $("#formulario #DES_MAQUINA").val($("#ret_DES_MAQUINA_" + index).val());
        $("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }
	
</script>	

