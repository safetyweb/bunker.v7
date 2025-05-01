<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$hoje = "";
$dias30 = "";
$log_univend = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_cliente = "";
$nom_cliente = "";
$des_placa = "";
$num_cartao = "";
$num_cgcecpf = "";
$dat_ini = "";
$dat_fim = "";
$log_funcionario = "";
$log_inativos = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$check_funcionario = "";
$checkInativos = "";
$andInativos = "";
$checkUnivend = "";
$cod_persona = "";
$lojasSelecionadas = "";
$selectPlaca = "";
$andNome = "";
$andUnidades = "";
$andPlaca = "";
$andCartao = "";
$andCpf = "";
$andLojas = "";
$andFuncionarios = "";
$andDatIni = "";
$andDatFim = "";
$andCodigo = "";
$retorno = "";
$inicio = "";
$qrListaEmpresas = "";
$sexo = "";
$dat_niver = "";
$log_funciona = "";
$mostraCracha = "";
$mostraPlaca = "";
$email = "";
$unidade = "";
$content = "";


$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));
$log_univend = 'N';

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = "1";

$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode(@$_GET['id']);

$sql = "SELECT NOM_FANTASI
	FROM empresas where COD_EMPRESA = $cod_empresa ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
        $cod_univend = @$_POST['COD_UNIVEND'];

        $cod_cliente = fnLimpacampo(@$_REQUEST['COD_CLIENTE']);
        $nom_cliente = fnLimpacampo(@$_REQUEST['NOM_CLIENTE']);
        $des_placa = fnLimpacampo(@$_REQUEST['DES_PLACA']);
        $num_cartao = fnLimpacampo(@$_REQUEST['NUM_CARTAO']);
        $num_cgcecpf = fnLimpaDoc(fnLimpacampo(@$_REQUEST['NUM_CGCECPF']));
        if (empty(@$_REQUEST['LOG_UNIVEND'])) {
            $log_univend = 'N';
        } else {
            $log_univend = @$_REQUEST['LOG_UNIVEND'];
        }

        $dat_ini = fnDataSql(@$_POST['DAT_INI']);
        $dat_fim = fnDataSql(@$_POST['DAT_FIM']);

        if (empty(@$_REQUEST['LOG_FUNCIONARIO'])) {
            $log_funcionario = 'N';
        } else {
            $log_funcionario = @$_REQUEST['LOG_FUNCIONARIO'];
        }
        if (empty(@$_REQUEST['LOG_INATIVOS'])) {
            $log_inativos = 'N';
        } else {
            $log_inativos = @$_REQUEST['LOG_INATIVOS'];
        }


        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '' && $opcao != 0) {
        }
    }
}

//busca dados da url    
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode(@$_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
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

//inicialização das variáveis - default
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

if ($log_funcionario == 'S') {
    $check_funcionario = "checked";
} else {
    $check_funcionario = "";
}

if ($log_inativos == "S") {
    $checkInativos = "checked";
    $andInativos = "AND CL.LOG_ESTATUS = 'N'";
} else {
    $checkInativos = "";
    $andInativos = "";
}

if ($log_univend == "S") {
    $checkUnivend = "checked";
} else {
    $checkUnivend = "";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnEscreve($cod_empresa); 	
//fnEscreve($cod_persona); 	
//fnMostraForm();
?>


<style>
    input[type="search"]::-webkit-search-cancel-button {
        height: 16px;
        width: 16px;
        background: url(images/close-filter.png) no-repeat right center;
        position: relative;
        cursor: pointer;
    }

    input.tableFilter {
        border: 0px;
        background-color: #fff;
    }

    table a:not(.btn),
    .table a:not(.btn) {
        text-decoration: none;
    }

    table a:not(.btn):hover,
    .table a:not(.btn):hover {
        text-decoration: underline;
    }
</style>

<div class="push30"></div>

<div class="row">

    <div class="col-md-12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?> </span>
                </div>
                <?php include "atalhosPortlet.php"; ?>
            </div>
            <div class="portlet-body">

                <?php if ($msgRetorno <> '') { ?>
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>

                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Filtros</legend>

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Unidade de Atendimento</label>
                                        <?php include "unidadesAutorizadasComboMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CPF/CNPJ</label>
                                        <input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo $num_cgcecpf; ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Nome do Cliente</label>
                                        <input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="40" value="<?php echo $nom_cliente; ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Data Inicial Alteração</label>

                                            <div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
                                                <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Data Final Alteração</label>

                                            <div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
                                                <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="push20"></div>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                </div>

                                <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
                                <input type="hidden" name="opcao" id="opcao" value="">
                                <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                                <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                                <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

                            </div>

                        </fieldset>

                    </form>
                </div>

            </div>
        </div>

        <div class="push20"></div>

        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">

                    <div class="push20"></div>

                    <div class="row">

                        <div class="col-lg-12">

                            <div class="no-more-tables">

                                <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
                                    <!--
                                    <thead> 
                                          <tr>
                                            <td class="bg-primary" colspan="100"><input type="search" name="filter" id="filter" class="input-sm tableFilter text-primary pull-right" style="height: 25px;" value=""></td>
                                          </tr>
                                    </thead>
                                    -->
                                    <thead>
                                        <tr>
                                            <th class="{sorter:false}" width="3.05%"></th>
                                            <th width="6.01%">Código</th>
                                            <th width="14.20%">Nome do Cliente</th>
                                            <th width="6.87%">Data de Cadastro</th>
                                            <th width="7.08%">Data de Alteração</th>
                                            <th width="7.08%">Usu. Alteração</th>
                                            <th width="8.37%">Loja</th>
                                            <th width="7.22%">CPF</th>
                                            <th width="7.22%">Celular</th>
                                            <th width="6.11%">CEP</th>
                                            <th width="19.41%">e-Mail</th>
                                            <th width="26.05%">Senha</th>
                                            <th width="5.45%">Sexo</th>
                                            <th width="5.45%">Dat. Niver</th>
                                        </tr>
                                    </thead>
                                    <tbody id="relatorioConteudo">

                                        <?php
                                        //============================
                                        /*$ARRAY_UNIDADE1 = array(
                                            'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
                                            'cod_empresa' => $cod_empresa,
                                            'conntadm' => $connAdm->connAdm(),
                                            'IN' => 'N',
                                            'nomecampo' => '',
                                            'conntemp' => '',
                                            'SQLIN' => ""
                                        );
                                        $ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);
                                        */


                                        if ($cod_empresa != 0 && $cod_empresa != '') {

                                            if ($cod_empresa == 19) {
                                                $selectPlaca = "(SELECT MAX(DES_PLACA) FROM VEICULOS WHERE COD_CLIENTE = CL.COD_CLIENTE) AS DES_PLACA,";
                                            } else {
                                                $selectPlaca = "";
                                            }

                                            if ($nom_cliente != '' && $nom_cliente != 0) {
                                                $andNome = 'AND CL.NOM_CLIENTE LIKE "' . $nom_cliente . '%"';
                                            } else {
                                                $andNome = ' ';
                                            }

                                            if ($cod_univend == 9999) {
                                                if ($log_univend == "N") {
                                                    $andUnidades = "AND (CL.COD_UNIVEND IN (" . $lojasSelecionadas . ") OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)";
                                                } else {
                                                    // $andUnidades = "AND (CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)";
                                                    $andUnidades = "
                                                                    -- AND CL.COD_UNIVEND IN (" . $lojasSelecionadas . ")
                                                                    AND 
                                                                      case when CL.COD_UNIVEND IS NULL then '1'
                                                                           when CL.COD_UNIVEND = '0' then '1'
                                                                           when CL.COD_UNIVEND > '0' then '1'
                                                                      ELSE '0' END IN (1)";
                                                }
                                            } else {
                                                if ($log_univend == "N") {
                                                    $andUnidades = "AND CL.COD_UNIVEND IN (" . $lojasSelecionadas . ")";
                                                } else {
                                                    // $andUnidades = "AND (CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)";
                                                    $andUnidades = "
                                                                    -- AND CL.COD_UNIVEND IN (" . $lojasSelecionadas . ")
                                                                    AND 
                                                                      case when CL.COD_UNIVEND IS NULL then '1'
                                                                           when CL.COD_UNIVEND = '0' then '1'
                                                                           when CL.COD_UNIVEND > '0' then '1'
                                                                      ELSE '0' END IN (1)";
                                                }
                                            }

                                            // echo $cod_univend."_<br>";
                                            // echo $log_univend."_<br>";
                                            // echo $andUnidades."_<br>";

                                            if ($des_placa != '' && $des_placa != 0) {
                                                $andPlaca = 'AND CL.COD_CLIENTE = (SELECT COD_CLIENTE FROM VEICULOS WHERE DES_PLACA = "' . $des_placa . '")';
                                            } else {
                                                $andPlaca = ' ';
                                            }

                                            if ($num_cartao != '' && $num_cartao != 0) {
                                                $andCartao = 'AND CL.NUM_CARTAO=' . $num_cartao;
                                            } else {
                                                $andCartao = ' ';
                                            }

                                            if ($num_cgcecpf != '' && $num_cgcecpf != 0) {
                                                $andCpf = 'AND CL.NUM_CGCECPF =' . $num_cgcecpf;
                                            } else {
                                                $andCpf = ' ';
                                            }

                                            if ($cod_univend != '' && $cod_univend != 0) {
                                                $andLojas = 'AND CL.COD_UNIVEND  IN  (0,' . $lojasSelecionadas . ')';
                                            } else {
                                                $andLojas = ' ';
                                            }

                                            if ($log_funcionario == 'S') {
                                                $andFuncionarios = " AND CL.LOG_FUNCIONA = 'S' ";
                                            } else {
                                                $andFuncionarios = "";
                                            }

                                            if ($dat_ini == "" || $dat_fim == "") {
                                                $andDatIni = " ";
                                                $andDatFim = " ";
                                            } else {
                                                $andDatIni = "AND DATE(CL.DAT_ALTERAC) BETWEEN '$dat_ini' AND '$dat_fim' ";
                                            }

                                            // if ($dat_fim == "") {
                                            //     $andDatFim = " ";
                                            // } else {
                                            //     $andDatFim = "AND DATE_FORMAT(CL.DAT_ALTERAC, '%Y-%m-%d') <= '$dat_fim' ";
                                            // }


                                            //paginação
                                            $sql = "SELECT COUNT(CL.COD_CLIENTE) AS CONTADOR FROM  " . connTemp($cod_empresa, 'true') . ".CLIENTES CL
                                                    WHERE CL.COD_EMPRESA = " . $cod_empresa . "
                                                    AND CL.COD_CLIENTE IN (SELECT COD_CLIENTE FROM log_alter_clientes WHERE COD_EMPRESA = $cod_empresa)
													" . $andCodigo . "
                                                    " . $andUnidades . "
													" . $andNome . "
                                                    " . $andPlaca . "
                                                    " . $andCartao . "
                                                    " . $andCpf . "
                                                    " . $andFuncionarios . "
                                                    " . $andInativos . "
                                                    " . $andDatIni . "
                                                    ORDER BY NOM_CLIENTE ";

                                            // fnEscreve($sql);

                                            $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

                                            $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

                                            //variavel para calcular o início da visualização com base na página atual
                                            $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                            //lista de clientes
                                            $sql = "SELECT CL.*,
                                                            USU.NOM_USUARIO,
                                                            USU2.NOM_USUARIO AS USU_ALTERAC,
                                                            uni.NOM_FANTASI,
                                                            CASE WHEN CL.COD_SEXOPES = 1 THEN 'Masculino'
                                                            WHEN CL.COD_SEXOPES = 2 THEN 'Feminino'
                                                            ELSE 'Indefinido' END DES_SEXOPES

                                                    FROM CLIENTES CL
                                                    LEFT JOIN USUARIOS USU ON USU.COD_USUARIO = CL.COD_ATENDENTE
                                                    LEFT JOIN USUARIOS USU2 ON USU2.COD_USUARIO = CL.COD_ALTERAC
                                                    LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=CL.COD_UNIVEND
                                                    WHERE CL.COD_EMPRESA = $cod_empresa
                                                    AND CL.COD_CLIENTE IN (SELECT COD_CLIENTE FROM log_alter_clientes WHERE COD_EMPRESA = $cod_empresa)
                                                        $andUnidades
                                                        $andNome
                                                        $andPlaca
                                                        $andCartao
                                                        $andCpf
                                                        $andFuncionarios
                                                        $andInativos
                                                        $andDatIni
                                                    ORDER BY CL.NOM_CLIENTE LIMIT $inicio,$itens_por_pagina";

                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            // fnEscreve($sql);
                                            //  echo "___".$sql."___";
                                            $count = 0;
                                            while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {
                                                //print_r($qrListaEmpresas);

                                                $sexo = $qrListaEmpresas['DES_SEXOPES'];
                                                $dat_niver = $qrListaEmpresas['DAT_NASCIME'];

                                                // fnEscreve($qrListaEmpresas['COD_SEXOPES']);

                                                $log_funciona = $qrListaEmpresas['LOG_FUNCIONA'];
                                                if ($log_funciona == "S") {
                                                    $mostraCracha = '<i class="fa fa-address-card" aria-hidden="true"></i>';
                                                } else {
                                                    $mostraCracha = "";
                                                }

                                                // if ($cod_empresa == 19) {
                                                //     $mostraPlaca = "<td class='text-center'><small>" . $qrListaEmpresas['DES_PLACA'] . "</small></td>";
                                                // } else {
                                                //     $mostraPlaca = "";
                                                // }
                                                if ($qrListaEmpresas['DES_EMAILUS'] == "") {
                                                    $email = "e-mail não cadastrado!";
                                                } else {
                                                    $email = $qrListaEmpresas['DES_EMAILUS'];
                                                }

                                                if ($qrListaEmpresas['COD_UNIVEND'] != 0) {
                                                    $unidade = $qrListaEmpresas['NOM_FANTASI'];
                                                } else {
                                                    $unidade = "Sem unidade";
                                                }
                                                $count++;

                                        ?>

                                                <tr id="bloco_<?php echo $qrListaEmpresas['COD_CLIENTE']; ?>">
                                                    <td class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaEmpresas['COD_CLIENTE']; ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></td>
                                                    <td><small><?php echo $qrListaEmpresas['COD_CLIENTE']; ?></small></td>
                                                    <td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?= fnEncode($qrListaEmpresas['COD_CLIENTE']); ?>" target="_blank"><?= $qrListaEmpresas['NOM_CLIENTE']; ?></a></td>
                                                    <td><small><?php echo fnDataFull($qrListaEmpresas['DAT_CADASTR']); ?></small></td>
                                                    <td><small><?php echo fnDataFull($qrListaEmpresas['DAT_ALTERAC']); ?></small></td>
                                                    <td><small><?php echo $qrListaEmpresas['USU_ALTERAC']; ?></small></td>
                                                    <td><small><?php echo $unidade; ?></small></td>
                                                    <td><small><?php echo $qrListaEmpresas['NUM_CGCECPF']; ?></small></td>
                                                    <td><small><?php echo $qrListaEmpresas['NUM_CELULAR']; ?></small></td>
                                                    <td><small><?php echo $qrListaEmpresas['NUM_CEPOZOF']; ?></small></td>
                                                    <td><small><?php echo $email; ?></small></td>
                                                    <td><small><?php echo $qrListaEmpresas['DES_SENHAUS']; ?></small></td>
                                                    <td><small><?php echo $sexo; ?></small></td>
                                                    <td><small><?php echo $dat_niver; ?></small></td>
                                                </tr>

                                        <?php
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>

                                <table class="table table-bordered table-striped table-hover tablesorter">
                                    <?php if ($cod_empresa != 0 && $cod_empresa != '') { ?>
                                        <tfoot>
                                            <tr class="text-left">
                                                <th colspan="100">
                                                    <a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="" colspan="100">
                                                    <center>
                                                        <ul id="paginacao" class="pagination-sm"></ul>
                                                    </center>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    <?php }  //fnEscreve($cod_empresa);    
                                    ?>
                                </table>

                                <div class="push"></div>

                            </div>

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

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
    $(document).ready(function() {

        var dataInicial = $('#DAT_INI').val();
        var dataFinal = $('#DAT_FIM').val();

        var numPaginas = <?php echo $numPaginas; ?>;
        if (numPaginas != 0) {
            carregarPaginacao(numPaginas);
        }

        $('.datePicker').datetimepicker({
            format: 'DD/MM/YYYY',
            maxDate: 'now',
        }).on('changeDate', function(e) {
            $(this).datetimepicker('hide');
        });

        $("#DAT_INI_GRP").on("dp.change", function(e) {
            $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
        });

        $("#DAT_FIM_GRP").on("dp.change", function(e) {
            $('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
        });

        //chosen obrigatório
        $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
        $('#formulario').validator();

        //table sorter
        $(function() {
            var tabelaFiltro = $('table.tablesorter')
            tabelaFiltro.find("tbody > tr").find("td:eq(1)").mousedown(function() {
                $(this).prev().find(":checkbox").click()
            });
            $("#filter").keyup(function() {
                $.uiTableFilter(tabelaFiltro, this.value);
            })
            $('#formLista').submit(function() {
                tabelaFiltro.find("tbody > tr:visible > td:eq(1)").mousedown();
                return false;
            }).focus();
        });

        //pesquisa table sorter
        $('.filter-all').on('input', function(e) {
            if ('' == this.value) {
                var lista = $("#filter").find("ul").find("li");
                filtrar(lista, "");
            }
        });

        $("body").delegate('input.placa', 'paste', function(e) {
            $(this).unmask();
        });
        $("body").delegate('input.placa', 'input', function(e) {
            $('input.placa').mask(MercoSulMaskBehavior, mercoSulOptions);
        });

    });

    var MercoSulMaskBehavior = function(val) {
            var myMask = 'SSS0A00';
            var mercosul = /([A-Za-z]{3}[0-9]{1}[A-Za-z]{1})/;
            var normal = /([A-Za-z]{3}[0-9]{2})/;
            var replaced = val.replace(/[^\w]/g, '');
            if (normal.exec(replaced)) {
                myMask = 'SSS-0000';
            } else if (mercosul.exec(replaced)) {
                myMask = 'SSS0A00';
            }
            return myMask;
        },
        mercoSulOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(MercoSulMaskBehavior.apply({}, arguments), options);
            }
        };

    $(document).on('change', '#COD_EMPRESA', function() {
        $("#dKey").val($("#COD_EMPRESA").val());
    });


    $(".exportarCSV").click(function() {
        $.confirm({
            title: 'Exportação',
            content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Insira o nome do arquivo:</label>' +
                '<input type="text" placeholder="Nome" class="nome form-control" required />' +
                '</div>' +
                '</form>',
            buttons: {
                formSubmit: {
                    text: 'Gerar',
                    btnClass: 'btn-blue',
                    action: function() {
                        var nome = this.$content.find('.nome').val();
                        if (!nome) {
                            $.alert('Por favor, insira um nome');
                            return false;
                        }

                        $.confirm({
                            title: 'Mensagem',
                            type: 'green',
                            icon: 'fa fa-check-square',
                            content: function() {
                                var self = this;
                                return $.ajax({
                                    url: "relatorios/ajxRelClientesGeralDetalhado.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                    data: $('#formulario').serialize(),
                                    method: 'POST'
                                }).done(function(response) {
                                    self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                    var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                    SaveToDisk('media/excel/' + fileName, fileName);
                                    // console.log(response);
                                }).fail(function(response) {
                                    self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                                    // console.log(response.responseText);
                                });
                            },
                            buttons: {
                                fechar: function() {
                                    //close
                                }
                            }
                        });
                    }
                },
                cancelar: function() {
                    //close
                },
            }
        });
    });

    function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "relatorios/ajxRelClientesGeralDetalhado.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
            data: $('#formulario').serialize(),
            beforeSend: function() {
                $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                $("#relatorioConteudo").html(data);
                $(".tablesorter").trigger("updateCache");
                // console.log(data);
            },
            error: function() {
                $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });
    }

    function page(index) {

        $("#pagina").val(index);
        $("#formulario")[0].submit();
        //alert(index);	

    }

    function abreDetail(idBloco) {
        var idItem = $('#detail_' + idBloco);

        // console.log(idItem);

        if (!idItem.is(':visible')) {
            var pDataInicial = $('#DAT_INI').val();
            var pDataFinal = $('#DAT_FIM').val();

            console.log(pDataInicial);
            console.log(pDataFinal);

            $.ajax({
                type: "GET",
                url: "relatorios/ajxRelClientesGeralDetalhado.do",
                data: {
                    DAT_INI: pDataInicial,
                    DAT_FIM: pDataFinal,
                    id: "<?php echo fnEncode($cod_empresa); ?>",
                    cliente: idBloco,
                    opcao: "abreDetail"
                },
                beforeSend: function() {
                    $('#bloco_' + idBloco).after('<tr id="loadDetail"><th colspan = "6"><div class="loading" style="width: 100%;"></div></tr></th>');
                },
                success: function(data) {
                    $('#loadDetail').remove();
                    $('#bloco_' + idBloco).after(data);
                    $('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');

                },
                error: function() {
                    idItem.html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
                }
            });
        } else {
            idItem.hide();
            $('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
            $('#detail_' + idBloco).destroy();
        }
    }
</script>