<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

$cod_cliente = 0;
$num_contrato = 0;
$ativo = "style='display: none'";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $num_contrato = fnLimpaCampoZero($_REQUEST['NUM_CONTRATO']);
        $cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);
        $cod_tipobem = fnLimpaCampoZero($_REQUEST['COD_TIPOBEM']);
        $tipo_finalidade = fnLimpaCampoZero($_REQUEST['TIPO_FINALIDADE']);
        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];


        if ($opcao == 'CAD') {
            $sql = "INSERT INTO CONTRATO_BLOCK (
                COD_CLIENTE,
                COD_EMPRESA,
                COD_STATUS,
                COD_TIPOBEM,
                TIPO_FINALIDADE,
                COD_USUCADA
                ) VALUES (
                '$cod_cliente',
                '$cod_empresa',
                1,
                '$cod_tipobem',
                '$tipo_finalidade',
                '$cod_usucada'
            )";

                mysqli_query(connTemp($cod_empresa, ''), $sql);

                $consultaSql = "SELECT 
                CB.NUM_CONTRATO, 
                CB.COD_STATUS, 
                CB.TIPO_FINALIDADE, 
                CL.COD_CLIENTE, 
                CL.NOM_CLIENTE, 
                TB.DES_TIPOBEM
                FROM 
                CONTRATO_BLOCK CB
                JOIN 
                CLIENTES CL ON CB.COD_CLIENTE = CL.COD_CLIENTE
                JOIN 
                TIPO_BEM TB ON CB.COD_TIPOBEM = TB.COD_TIPOBEM
                WHERE 
                CB.COD_CLIENTE = '$cod_cliente' 
                AND CB.COD_EMPRESA = '$cod_empresa'
                AND CB.DAT_CADASTR = (SELECT MAX(DAT_CADASTR) FROM CONTRATO_BLOCK WHERE COD_CLIENTE = '$cod_cliente' AND COD_EMPRESA = '$cod_empresa');
                ";

                $resultConsulta = mysqli_query(connTemp($cod_empresa, ''), $consultaSql);
                if ($resultConsulta) {
                    $qrBusca = mysqli_fetch_assoc($resultConsulta);

                    $num_contrato = $qrBusca['NUM_CONTRATO'];
                    $cod_cliente = $qrBusca['COD_CLIENTE'];
                }

                if (is_numeric(fnLimpacampo(fnDecode($_GET['fluxo'])))) {
                    $fluxo = fnDecode($_GET['fluxo']);
                    $sql = "UPDATE FLUXO_OPERACIONAL SET 
                    NUM_CONTRATO = $num_contrato,
                    COD_CLIENTE = $cod_cliente
                    WHERE COD_FLUXO_OPER = $fluxo";

                    mysqli_query(connTemp($cod_empresa, ''), $sql);
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
        }
    } else {
        $cod_empresa = 0;
    //fnEscreve('entrou else');
    }

    if ($num_contrato != 0 && $cod_cliente != 0) {

        $cod_cliente = fnEncode($cod_cliente);
        $num_contrato = fnEncode($num_contrato);
        $cod_tipobem = fnEncode($cod_tipobem);

        ?>

        <script>
            window.location.href = "/action.do?mod=<?= $_GET["mod"]?>&id=<?=$_GET["id"]?>&idC=<?=$cod_cliente?>&idCt=<?=$num_contrato?>&tpBem=<?=$cod_tipobem?>&fluxo=<?=$_GET["fluxo"]?>&passo=<?=$_GET["passo"]?>";
        </script>

        <?php
    }

    if (is_numeric(fnLimpacampo(fnDecode($_GET['idCt'])))) {

        $num_contrato = fnDecode($_GET['idCt']);
        $cod_cliente = fnDecode($_GET['idC']);

        $sql = "SELECT 
        CB.*,
        CLI.NOM_CLIENTE,
        CB.NUM_CONTRATO,
        TB.DES_TIPOBEM
        FROM 
        CONTRATO_BLOCK CB
        JOIN 
        CLIENTES CLI ON CB.COD_CLIENTE = CLI.COD_CLIENTE
        JOIN 
        TIPO_BEM TB ON CB.COD_TIPOBEM = TB.COD_TIPOBEM
        WHERE 
        CB.NUM_CONTRATO = '$num_contrato' 
        AND CB.COD_CLIENTE = '$cod_cliente' 
        AND CB.COD_EMPRESA = '$cod_empresa'
        ";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $qrBusca = mysqli_fetch_assoc($arrayQuery);

        if ($arrayQuery) {

            $num_contrato = $qrBusca['NUM_CONTRATO'];
            $des_tipobem = $qrBusca['DES_TIPOBEM'];
            $nom_cliente = $qrBusca['NOM_CLIENTE'];
            $tipo_finalidade = $qrBusca['TIPO_FINALIDADE'];

        //fnEscreve2($tipo_finalidade);
            if ($tipo_finalidade == 1) {
                $des_finalidade = "Crédito de Carbono";
                $ativo = "";
            } elseif ($tipo_finalidade == 2) {
                $des_finalidade = "Floresta";
                $ativo = "";
            } else {
                $des_finalidade = "";
            }
        }
    }
    ?>

    <link rel="stylesheet" type="text/css" href="https://adm.bunker.mk/css/jquery-confirm.min.css">

    <div class="push30"></div>

    <div class="row">

        <div class="col-md12 margin-bottom-30">
            <!-- Portlet -->
            <div class="portlet portlet-bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fal fa-terminal"></i>
                        <span class="text-primary"><?php echo $NomePg; ?></span>
                    </div>
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

                            <div class="container">
                                <div class="row form-group">
                                    <div class="col-xs-12">
                                        <ul class="nav nav-pills nav-justified thumbnail setup-panel" style="border: none;">
                                            <li class="active">
                                                <a href="#step-1">
                                                    <h4 class="list-group-item-heading">Cliente</h4>
                                                    <p class="list-group-item-text">Selecione o Cliente</p>
                                                </a>
                                            </li>
                                            <li class="disabled"><a href="#step-2">
                                                <h4 class="list-group-item-heading">Finalidade do Projeto</h4>
                                                <p class="list-group-item-text">Informe o tipo de bem e finalidade do projeto</p>
                                            </a></li>
                                            <li class="disabled"><a href="#step-3">
                                                <h4 class="list-group-item-heading">Geração da Proposta</h4>
                                                <p class="list-group-item-text">Confirme as informações e gere o proposta</p>
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row setup-content" id="step-1">
                                    <div class="col-xs-12">
                                        <div class="col-md-12 well text-center">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <label for="inputName" class="control-label required">Nome do Cliente</label>
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
                                                    </span>
                                                    <input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" value="" disabled>
                                                </div>
                                                <div class="help-block with-errors"></div>
                                                <div class="push30"></div>
                                                <div class="col-md-12 text-center">
                                                    <button id="activate-step-2" class="btn btn-primary getBtn text-center">Confirmar</button>
                                                </div>
                                            </div>
                                            <div class="col-md-3"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row setup-content" id="step-2" style="display: none">
                                    <div class="col-xs-12 well">
                                        <div class="col-md-12">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-3 text-center">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label required">Tipo de Bem</label>
                                                    <select data-placeholder="Selecione um tipo de bem" name="COD_TIPOBEM" id="COD_TIPOBEM" class="chosen-select-deselect">
                                                        <option value=""></option>
                                                        <?php
                                                        $sql = "select COD_TIPOBEM, DES_TIPOBEM from tipo_bem order by DES_TIPOBEM ";
                                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                                        while ($qrTipoBem = mysqli_fetch_assoc($arrayQuery)) {
                                                            $selected = ($qrTipoBem['COD_TIPOBEM'] == $cod_tipobem) ? 'selected' : '';
                                                            echo "<option value='" . $qrTipoBem['COD_TIPOBEM'] . "' $selected>" . $qrTipoBem['DES_TIPOBEM'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                                <div class="push30"></div>
                                            </div>
                                            <div class="col-md-3 text-center" id="div_finalidade">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label required">Finalidade</label>
                                                    <select data-placeholder="Selecione um tipo de bem" name="TIPO_FINALIDADE" id="TIPO_FINALIDADE" class="chosen-select-deselect">
                                                        <option value="0"></option>
                                                        <option value="1" <?php if ($des_finalidade === "Crédito de Carbono") echo "selected"; ?>>Crédito de Carbono</option>
                                                        <option value="2" <?php if ($des_finalidade === "Floresta") echo "selected"; ?>>Floresta</option>
                                                    </select>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <button id="activate-step-3" class="btn btn-primary getBtn text-center">Confirmar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row setup-content" id="step-3" style="display: none">
                                    <div class="col-xs-12">
                                        <div class="col-md-12 well">
                                            <h1 class="text-center"> Informações da Proposta</h1>
                                            <div class="push10"></div>
                                            <div class="row">

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label required">Número da Proposta</label>
                                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NUM_CONTRATO" id="NUM_CONTRATO" style="background-color: transparent !important;" value="<?= $num_contrato ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label ">Cód. Cliente</label>
                                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE_STEP3" id="COD_CLIENTE_STEP3" style="background-color: transparent !important;" value="<?= $cod_cliente ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label ">Nome Cliente</label>
                                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_CLIENTE_STEP3" id="NOM_CLIENTE_STEP3" style="background-color: transparent !important;" value="<?= $nom_cliente ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label ">Tipo de Propríedade</label>
                                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PROPRI_STEP3" id="COD_PROPRI_STEP3" style="background-color: transparent !important;" value="<?= $des_tipobem ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-2" id="div_oculta" <?= $ativo ?>>
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label required">Finalidade</label>
                                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="TIPO_FINALIDADE_STEP3" id="TIPO_FINALIDADE_STEP3" style="background-color: transparent !important;" value="<?= $des_finalidade ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="push30"></div>
                                            <div class="col-md-12 text-center">
                                                <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn text-center"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Gerar Proposta</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="push10"></div>

                            <input type="hidden" name="opcao" id="opcao" value="">
                            <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                            <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
                            <input type="hidden" name="last_step" id="last_step" value="<?php echo isset($_POST['last_step']) ? $_POST['last_step'] : 'step-1'; ?>">
                            <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
                            <input type="hidden" name="NUM_CONTRATO" id="NUM_CONTRATO" value="<?php echo $num_contrato; ?>">
                            <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

                            <div class="push5"></div>

                        </form>

                        <div class="push50"></div>

                        <div class="push"></div>

                    </div>

                </div>
            </div>
            <!-- fim Portlet -->
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

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://bunker.mk/js/jquery-confirm.min.js"></script>

    <div class="push20"></div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#COD_TIPOBEM').change(function() {
                if ($(this).val() == '6') {
                    $('#TIPO_FINALIDADE').attr('disabled',false).chosen('destroy').chosen({allow_single_deselect:true});
                    $('#div_oculta').show();
                } else {
                    $('#TIPO_FINALIDADE').attr('disabled',true).chosen('destroy').chosen({allow_single_deselect:true});
                    $('#TIPO_FINALIDADE').val();
                    $('#div_oculta').hide();
                }
            });

            var lastStep = $('#last_step').val();

            var navListItems = $('ul.setup-panel li a'),
            allWells = $('.setup-content');

            allWells.hide();
            $('#' + lastStep).show();

            navListItems.click(function(e) {
                e.preventDefault();
                var $target = $($(this).attr('href')),
                $item = $(this).closest('li');

                if (!$item.hasClass('disabled')) {
                    navListItems.closest('li').removeClass('active');
                    $item.addClass('active');
                    allWells.hide();
                    $target.show();
                }
            });

            $('#activate-step-2').on('click', function(e) {
                var cliente = $("#NOM_CLIENTE").val();
                if (cliente) {
                    $('ul.setup-panel li:eq(1)').removeClass('disabled');
                    $('ul.setup-panel li a[href="#step-2"]').trigger('click');
                    $('#last_step').val('step-2');
                    $(this).remove();
                } else {
                    // alert("Por favor Selecione um cliente");
                    $.alert({
                      title: "Atenção",
                      content: "Por favor Selecione um cliente."
                    });
                }

            });

            $('#activate-step-3').on('click', function(e) {
                var tipoBem = $("#COD_TIPOBEM").val();
                var tipoFinalidade = $("#TIPO_FINALIDADE").val();

                var urlParams = new URLSearchParams(window.location.search);
                var idCt = urlParams.get('idCt');

                var finalidadePreenchida = false;
                if (tipoBem == 6) {
                    if(tipoFinalidade != "" && tipoFinalidade != 0){
                        finalidadePreenchida = tipoFinalidade;
                    }
                } else {
                    finalidadePreenchida = true;
                }

                if ((tipoBem && finalidadePreenchida) || idCt) {
                    var cod_cliente = $("#COD_CLIENTE").val();
                    var nom_cliente = $("#NOM_CLIENTE").val();
                    var cod_tipo_bem = $("#COD_TIPOBEM").val();
                    var tipo_finalidade = $("#TIPO_FINALIDADE").val();
                    var des_tipo_bem = $("#COD_TIPOBEM option:selected").text();
                    var codigo_descricao = cod_tipo_bem + ' - ' + des_tipo_bem;

                    if (tipo_finalidade == 1) {
                        tipo_finalidade = "Crédito de Carbono";
                    }

                    if (tipo_finalidade == 2) {
                        tipo_finalidade = "Floresta";
                    }

                    if (cod_cliente !== "") {
                        $('#COD_CLIENTE_STEP3').val(cod_cliente);
                    }
                    if (nom_cliente !== "") {
                        $('#NOM_CLIENTE_STEP3').val(nom_cliente);
                    }
                    if (cod_tipo_bem !== "") {
                        $('#COD_PROPRI_STEP3').val(codigo_descricao);
                    }
                    if (tipo_finalidade !== "") {
                        $('#TIPO_FINALIDADE_STEP3').val(tipo_finalidade);
                    }

                    $('ul.setup-panel li:eq(2)').removeClass('disabled');
                    $('ul.setup-panel li a[href="#step-3"]').trigger('click');
                    $('#last_step').val('step-3');
                    $(this).remove();
                } else {
                    // alert("Por favor selecione os campos");
                    $.alert({
                      title: "Atenção",
                      content: "Por favor selecione os campos."
                    });
                }
            });

            var urlParams = new URLSearchParams(window.location.search);
            var idCt = urlParams.get('idCt');
            if (idCt) {
                $('#activate-step-3').click();
                $('#CAD').hide();
            }
        });
    </script>