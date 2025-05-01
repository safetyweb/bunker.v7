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

        $cod_totem = fnLimpaCampoZero($_REQUEST['COD_TOTEM']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $des_logo = fnLimpaCampo($_REQUEST['DES_LOGO']);
        $des_alinham = fnLimpaCampo($_REQUEST['DES_ALINHAM']);
        $des_imgback = fnLimpaCampo($_REQUEST['DES_IMGBACK']);
        $cod_layout = fnLimpaCampo($_REQUEST['COD_LAYOUT']);
		
        if (empty($_REQUEST['LOG_CORPERS'])) {
            $log_corpers = 'N';
        } else {
            $log_corpers = $_REQUEST['LOG_CORPERS'];
        }
       
        $cor_backbar = fnLimpaCampo($_REQUEST['COR_BACKBAR']);
        $cor_backpag = fnLimpaCampo($_REQUEST['COR_BACKPAG']);
        $cor_titulos = fnLimpaCampo($_REQUEST['COR_TITULOS']);
        $cor_textos = fnLimpaCampo($_REQUEST['COR_TEXTOS']);
        $cor_botao = fnLimpaCampo($_REQUEST['COR_BOTAO']);
        $cor_botaoon = fnLimpaCampo($_REQUEST['COR_BOTAOON']);

        if (empty($_REQUEST['LOG_TICKET'])) {
            $log_ticket = 'N';
        } else {
            $log_ticket = $_REQUEST['LOG_TICKET'];
        }
       						
        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {
			
			
			
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
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    //fnEscreve('entrou else');
}

//busca dados da tabela
$sql = "SELECT * FROM TOTEM WHERE COD_EMPRESA = $cod_empresa";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
$qrBuscaSiteTotem = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotem)) {
    //fnEscreve("entrou if");

    $cod_totem = $qrBuscaSiteTotem['COD_TOTEM'];
    $des_logo = $qrBuscaSiteTotem['DES_LOGO'];
    $des_alinham = $qrBuscaSiteTotem['DES_ALINHAM'];
    $des_imgback = $qrBuscaSiteTotem['DES_IMGBACK'];
    $cod_layout = $qrBuscaSiteTotem['COD_LAYOUT'];

    if ($qrBuscaSiteTotem['LOG_CORPERS'] == "N") {
        $check_CORPERS = '';
    } else {
        $check_CORPERS = "checked";
    }
	
    if ($qrBuscaSiteTotem['LOG_TICKET'] == "N") {
        $check_TICKET = '';
    } else {
        $check_TICKET = "checked";
    }

    $cor_backbar = $qrBuscaSiteTotem['COR_BACKBAR'];
    $cor_backpag = $qrBuscaSiteTotem['COR_BACKPAG'];
    $cor_titulos = $qrBuscaSiteTotem['COR_TITULOS'];
    $cor_textos = $qrBuscaSiteTotem['COR_TEXTOS'];
    $cor_botao = $qrBuscaSiteTotem['COR_BOTAO'];
    $cor_botaoon = $qrBuscaSiteTotem['COR_BOTAOON'];
	
} else {
    //default se vazio
    //fnEscreve("entrou else");
    
	$cod_totem = 0;
	$des_logo = "";
	$des_alinham = "left";
	$des_imgback = "";
	$cod_layout = 4;
	$check_CORPERS = '';    
	$check_TICKET = '';    

    $cor_backbar = "34495e";
    $cor_backpag = "f2f3f4";
    $cor_titulos = "#34495e";
    $cor_textos = "#34495e";
    $cor_botao = "#0092d8";
    $cor_botaoon = "#48c9b0";

}

	//fnEscreve($log_usuario);
	//fnEscreve($des_senhaus);

	//fnMostraForm();

?>

<style>
.bold {font-weight: bold;}
.center {text-align:center;}

.chosen-container {
font-size: 16px;
}

.chosen-container-single .chosen-single {
height: 45px;
}

.chosen-container-single .chosen-single span {
margin-top: 5px;
}
</style>


                <div class="login-form" style="padding: 0 10px 30px 10px;"> 
					<div class="row">

						<div class="col-md-12 text-center" style="height: 80px; background: #34495e;">
						<div class="push15"></div>
						<img src="images/logo_exemplo.png" width="70%">
						</div>
						
					</div>

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

					<div class="row">

						<div class="col-md-12">
						<!-- bloco 1 -->

							<div class="row">
							
								<div class="push20"></div>
								
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" class="form-control input-lg f21 bold center" name="NOM_EMPRESA" id="NOM_EMPRESA" value="Roberto">
									</div>														
								</div>
								
								<div class="push20"></div>
								
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" class="form-control center"name="NOM_EMPRESA" id="NOM_EMPRESA" value="Carlos da Silva Teixeira">
									</div>														
								</div>	
								
								<div class="push20"></div>
								
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" class="form-control center"name="NOM_EMPRESA" id="NOM_EMPRESA" value="11/22/3333">
									</div>														
								</div>
								
								<div class="push20"></div>
									
								<div class="col-md-2">
									<div class="form-group">
										<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect">
											<option value="">&nbsp;</option>					
											<?php 																	
												$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
												$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
											
												while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery))
												  {														
													echo"
														  <option value='".$qrListaSexo['COD_SEXOPES']."'>".$qrListaSexo['DES_SEXOPES']."</option> 
														"; 
													  }											
											?>	
										</select>	
										<div class="help-block with-errors"></div>
									</div>
								</div>
								
								<div class="push20"></div>
								
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" class="form-control center"name="NOM_EMPRESA" id="NOM_EMPRESA" value="(12) 98877-6655">
									</div>														
								</div>
								
							</div>

							<div class="push30"></div>
							<div class="form-group text-center col-lg-12">

								<button type="button" name="CAD" id="CAD" class="btn btn-success btn-lg btn-block getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>

							</div>
						
						</div>
						
					</div>


                        <input type="hidden" name="COD_TOTEM" id="COD_TOTEM" value="<?php echo $cod_totem; ?>">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		

                        <div class="push5"></div> 

                    </form>

                    <div class="push50"></div>										

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

		//color picker
        $('.pickColor').minicolors({
            control: $(this).attr('data-control') || 'hue',
            theme: 'bootstrap'
        });

    });

    function retornaForm(index) {
        $("#formulario #COD_MAQUINA").val($("#ret_COD_MAQUINA_" + index).val());
        $("#formulario #DES_MAQUINA").val($("#ret_DES_MAQUINA_" + index).val());
        $("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }

    $('.upload').on('click', function (e) {
        var idField = 'arqUpload_' + $(this).attr('idinput');
        var typeFile = $(this).attr('extensao');

        $.dialog({
            title: 'Arquivo',
            content: '' +
                    '<form method = "POST" enctype = "multipart/form-data">' +
                    '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
                    '<div class="progress" style="display: none">' +
                    '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">'+
                    '   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
                    '</div>' +
                    '<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
                    '</form>'
        });
    });

    function uploadFile(idField, typeFile) {
        var formData = new FormData();
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

        $('.progress').show();
        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                $('#btnUploadFile').addClass('disabled');
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        if (percentComplete !== 100) {
                            $('.progress-bar').css('width', percentComplete + "%");
                            $('.progress-bar > span').html(percentComplete + "%");
                        }
                    }
                }, false);
                return xhr;
            },
            url: '../uploads/uploaddoc.php',
            type: 'POST',
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function (data) {
                $('.jconfirm-open').fadeOut(300, function () {
                    $(this).remove();
                });
                if (!data.trim()) {
                    $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
                    $.alert({
                        title: "Mensagem",
                        content: "Upload feito com sucesso",
                        type: 'green'
                    });

                } else {
                    $.alert({
                        title: "Erro ao efetuar o upload",
                        content: data,
                        type: 'red'
                    });
                }
            }
        });
    }
</script>	

