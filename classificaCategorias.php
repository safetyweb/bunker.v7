<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_classifica = fnLimpaCampoZero($_REQUEST['COD_CLASSIFICA']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $qtd_diashist = fnLimpaCampoZero($_REQUEST['QTD_DIASHIST']);
        $qtd_mesclass = fnLimpaCampoZero($_REQUEST['QTD_MESCLASS']);
        $qtd_mreclass = fnLimpaCampo($_REQUEST['QTD_MRECLASS']);

        $nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {


            if ($opcao == 'CAD') {
                $sql = "INSERT INTO EMPRESA_CLASSIFICA(
								COD_EMPRESA, 
								QTD_DIASHIST, 
								QTD_MESCLASS,
                                QTD_MRECLASS) 
								VALUES (
								'$cod_empresa', 
								'$qtd_diashist', 
								'$qtd_mesclass',
								'$qtd_mreclass'
								)";
                $arrayInsert = mysqli_query($conn, $sql);

                if (!$arrayInsert) {

                    $cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
                }
            }

            if ($opcao == 'ALT') {
                $sqlUpdate = "UPDATE EMPRESA_CLASSIFICA SET 
								QTD_DIASHIST = '$qtd_diashist', 
								QTD_MESCLASS = '$qtd_mesclass',
								QTD_MRECLASS = '$qtd_mreclass'
								WHERE COD_CLASSIFICA = $cod_classifica and COD_EMPRESA = $cod_empresa ";
                $arrayUpdate = mysqli_query($conn, $sqlUpdate);

                if (!$arrayUpdate) {
                    $cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate,$nom_usuario);
                }
            }

            //fnEscreve($cod_empresa);			
            //echo $sql;

            //mensagem de retorno
            switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
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
$sql = "SELECT * FROM EMPRESA_CLASSIFICA WHERE COD_EMPRESA = $cod_empresa ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaClassifica = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaClassifica)) {
    //fnEscreve("entrou if");

    $cod_classifica = $qrBuscaClassifica['COD_CLASSIFICA'];
    $qtd_diashist = $qrBuscaClassifica['QTD_DIASHIST'];
    $qtd_mesclass = $qrBuscaClassifica['QTD_MESCLASS'];
    $qtd_mreclass = $qrBuscaClassifica['QTD_MRECLASS'];
} else {
    //default se vazio
    //fnEscreve("entrou else");

    $cod_categoria = 0;
    $qtd_diashist = "";
    $qtd_mesclass = "";
    $qtd_mreclass = "";
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
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
                                        <label for="inputName" class="control-label required">Histórico para Classificação <small>(em dias)</small></label>
                                        <input type="text" class="form-control text-center input-sm int" name="QTD_DIASHIST" id="QTD_DIASHIST" maxlength="3" value="<?php echo $qtd_diashist; ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Classificação Automática <small>(em meses)</small></label>
                                        <select data-placeholder="Selecione a periodicidade de reclassificação" name="QTD_MESCLASS" id="QTD_MESCLASS" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <option value="12" disabled>Anual</option>
                                            <option value="6" disabled>Semestral</option>
                                            <option value="4" disabled>Quadrimestral</option>
                                            <option value="3" disabled>Trimestral</option>
                                            <option value="2" disabled>Bimestral</option>
                                            <option value="1" disabled>Mensal</option>
                                            <option value="0">Online (a cada venda)</option>
                                        </select>
                                        <script>
                                            $("#formulario #QTD_MESCLASS").val("<?php echo $qtd_mesclass; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors">início em 01/jan</div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Reclassificação Automática <small>(inatividade)</small></label>
                                        <select data-placeholder="Selecione a periodicidade de reclassificação" name="QTD_MRECLASS" id="QTD_MRECLASS" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <option value="M">Mensal</option>
                                            <option value="S">Semanal</option>
                                        </select>
                                        <script>
                                            $("#formulario #QTD_MRECLASS").val("<?php echo $qtd_mreclass; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors">início em 01/jan</div>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                            <?php if ($cod_classifica == 0) { ?>
                                <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <?php } else { ?>
                                <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <?php } ?>

                        </div>

                        <input type="hidden" name="COD_CLASSIFICA" id="COD_CLASSIFICA" value="<?php echo $cod_classifica; ?>">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

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

<link rel="stylesheet" href="fonts/font-awesome-4.2.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css" />

<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.2.0.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script type="text/javascript">
    $(document).ready(function() {

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