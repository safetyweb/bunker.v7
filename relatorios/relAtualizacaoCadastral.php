<?php
//echo fnDebug('true');
//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 15 days')));

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = "1";

$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode($_GET['id']);

$sql = "SELECT NOM_FANTASI
	FROM empresas where COD_EMPRESA = $cod_empresa ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
        $cod_univend = $_POST['COD_UNIVEND'];

        $cod_cliente = fnLimpacampo($_REQUEST['COD_CLIENTE']);
        $nom_cliente = fnLimpacampo($_REQUEST['NOM_CLIENTE']);
        $des_placa = fnLimpacampo($_REQUEST['DES_PLACA']);
        $num_cartao = fnLimpacampo($_REQUEST['NUM_CARTAO']);
        $num_cgcecpf = fnLimpaDoc(fnLimpacampo($_REQUEST['NUM_CGCECPF']));

        $dat_ini = fnDataSql($_POST['DAT_INI']);
        $dat_fim = fnDataSql($_POST['DAT_FIM']);

        if (empty($_REQUEST['LOG_ALTERAC'])) {
            $log_alterac = 'N';
        } else {
            $log_alterac = $_REQUEST['LOG_ALTERAC'];
        }
        if (empty($_REQUEST['LOG_INATIVOS'])) {
            $log_inativos = 'N';
        } else {
            $log_inativos = $_REQUEST['LOG_INATIVOS'];
        }


        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {
        }
    }
}

//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

//inicialização das variáveis - default	

if ($log_alterac == 'S') {
    $check_alterac = "checked";
} else {
    $check_alterac = "";
}

if ($log_inativos == "S") {
    $checkInativos = "checked";
    $andInativos = "AND CL.LOG_ESTATUS = 'N'";
} else {
    $checkInativos = "";
    $andInativos = "";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//inicialização das variáveis - default 
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

//rotina de controle de acessos por módulo
include "moduloControlaAcesso.php";

if (fnControlaAcesso("1024", $arrayParamAutorizacao) === true) {
    $autoriza = 1;
} else {
    $autoriza = 0;
}

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

    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

        <div class="col-md12 margin-bottom-30">
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

                        <fieldset>
                            <legend>Filtros</legend>

                            <div class="row">

                                <div class="col-md-3">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Todas as alterações</label>
                                            <div class="push5"></div>
                                            <label class="switch switch-small">
                                                <input type="checkbox" name="LOG_ALTERAC" id="LOG_ALTERAC" class="switch" value="S" <?= $check_alterac ?>>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-6">   
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Só Inativos</label> 
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_INATIVOS" id="LOG_INATIVOS" class="switch" value="S" <?= $checkInativos ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div> -->

                                </div>

                                <div class="col-md-4">
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

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Data Inicial de <span class="lbl-data">Ativação</span></label>

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
                                            <label for="inputName" class="control-label">Data Final de <span class="lbl-data">Ativação</span></label>

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

                                <?php if ($cod_empresa == 19) { ?>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Placa</label>
                                            <input type="text" id="DES_PLACA" name="DES_PLACA" class="form-control input-sm text-center placa" data-minlength="7" data-minlength-error="Formato inválido">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                <?php } ?>

                                <div class="col-md-2">
                                    <div class="push20"></div>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                </div>

                            </div>

                        </fieldset>
                    </div>
                </div>
            </div>

            <div class="push20"></div>

            <div class="portlet portlet-bordered">

                <div class="portlet-body">

                    <div class="login-form">

                        <div class="push20"></div>

                        <div>
                            <div class="row">
                                <div class="col-lg-12">

                                    <div class="no-more-tables table-responsive">



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
                                                    <th class="{sorter:false}" width="40"></th>
                                                    <th>Código</th>
                                                    <th>Nome do Cliente</th>
                                                    <th>Data de Cadastro</th>
                                                    <?php if ($log_alterac == 'S') { ?>
                                                        <th> Data de Alteração</th>
                                                    <?php } else { ?>
                                                        <th> Data de Ativação</th>
                                                    <?php } ?>
                                                    <th>Última Compra</th>
                                                    <th>Loja</th>
                                                    <th>Vendedor</th>
                                                    <th>Cartão</th>
                                                    <th>CPF</th>
                                                    <th>Telefone</th>
                                                    <th>Celular</th>
                                                    <th>e-Mail</th>
                                                    <?php if ($cod_empresa == 19) { ?>
                                                        <th width="150">Placa</th>
                                                    <?php } ?>
                                                    <th width="100">Saldo Disponível</th>
                                                    <th width="100">Saldo Bloqueado</th>
                                                    <th class="text-center">Termos</th>
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
                                                 * 
                                                 */



                                                if ($cod_empresa == 19) {
                                                    $selectPlaca = "(SELECT MAX(DES_PLACA) FROM VEICULOS WHERE COD_CLIENTE = CL.COD_CLIENTE) AS DES_PLACA,";
                                                } else {
                                                    $selectPlaca = "";
                                                }

                                                if ($nom_cliente != '') {
                                                    $andNome = 'AND CL.NOM_CLIENTE LIKE "' . $nom_cliente . '%"';
                                                } else {
                                                    $andNome = ' ';
                                                }

                                                if ($des_placa != '') {
                                                    $andPlaca = 'AND CL.COD_CLIENTE = (SELECT COD_CLIENTE FROM VEICULOS WHERE DES_PLACA = "' . $des_placa . '")';
                                                } else {
                                                    $andPlaca = ' ';
                                                }

                                                if ($num_cartao != '') {
                                                    $andCartao = 'AND CL.NUM_CARTAO=' . $num_cartao;
                                                } else {
                                                    $andCartao = ' ';
                                                }

                                                if ($num_cgcecpf != '') {
                                                    $andCpf = 'AND CL.NUM_CGCECPF =' . $num_cgcecpf;
                                                } else {
                                                    $andCpf = ' ';
                                                }

                                                if ($cod_univend != '') {
                                                    $andLojas = 'AND CL.COD_UNIVEND  IN  (0,' . $lojasSelecionadas . ')';
                                                } else {
                                                    $andLojas = ' ';
                                                }

                                                if ($log_alterac == 'S') {

                                                    $andAlterac = "AND CL.DAT_ALTERAC BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";
                                                    $andDatIni = "";
                                                    $andDatFim = "";
                                                } else {

                                                    $andAlterac = "AND LOG_TERMO = 'S'";

                                                    if ($dat_ini == "") {
                                                        $andDatIni = "";
                                                    } else {
                                                        $andDatIni = "AND DATE_FORMAT(LC.DAT_ATIV, '%Y-%m-%d') >= '$dat_ini' ";
                                                    }

                                                    if ($dat_fim == "") {
                                                        $andDatFim = "";
                                                    } else {
                                                        $andDatFim = "AND DATE_FORMAT(LC.DAT_ATIV, '%Y-%m-%d') <= '$dat_fim' ";
                                                    }
                                                }

                                                // if ($dat_ini == "") {
                                                //     $andDatIni = "AND CL.DAT_ALTERAC IS NOT NULL";
                                                // } else {
                                                //     $andDatIni = "AND DATE_FORMAT(CL.DAT_ALTERAC, '%Y-%m-%d') >= '$dat_ini' ";
                                                // }

                                                // if ($dat_fim == "") {
                                                //     $andDatFim = "AND CL.DAT_ALTERAC IS NOT NULL";
                                                // } else {
                                                //     $andDatFim = "AND DATE_FORMAT(CL.DAT_ALTERAC, '%Y-%m-%d') <= '$dat_fim' ";
                                                // }


                                                //paginação
                                                $sql = "SELECT COUNT(CL.COD_CLIENTE) AS CONTADOR FROM  " . connTemp($cod_empresa, 'true') . ".CLIENTES CL
                                                          LEFT JOIN LOG_CANAL LC ON LC.COD_CLIENTE = CL.COD_CLIENTE AND CL.COD_EMPRESA=LC.COD_EMPRESA AND CL.COD_UNIVEND=LC.COD_UNIVEND
                                                          WHERE CL.COD_EMPRESA = " . $cod_empresa . "
                                                          AND CL.COD_UNIVEND  IN  ($lojasSelecionadas)
                                                            " . $andCodigo . "
                                                            " . $andNome . "
                                                            " . $andPlaca . "
                                                            " . $andCartao . "
                                                            " . $andCpf . "
                                                            " . $andAlterac . "
                                                            " . $andInativos . "
                                                            ORDER BY NOM_CLIENTE ";
                                                $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                                $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

                                                $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

                                                //variavel para calcular o início da visualização com base na página atual
                                                $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                                //lista de clientes
                                                $sql = "SELECT CL.*,uni.NOM_FANTASI,USU.NOM_USUARIO, $selectPlaca
                                                                (SELECT ifnull(SUM(VAL_SALDO),0)
                                                                   FROM CREDITOSDEBITOS CDB
                                                                  WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE 
                                                                    AND TIP_CREDITO='C' 
                                                                    AND COD_STATUSCRED=1 
                                                                    AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                                                                    AND COD_EMPRESA = $cod_empresa ) AS VAL_SALDO,
                                                                (SELECT ifnull(SUM(VAL_SALDO),0)
                                                                   FROM CREDITOSDEBITOS CDB
                                                                  WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE 
                                                                    AND TIP_CREDITO='C' 
                                                                    AND COD_STATUSCRED IN (3,7)
                                                                    AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                                                                    AND COD_EMPRESA = $cod_empresa ) AS SALDO_BLOQUEADO,
                                                                CL.DAT_ALTERAC,
                                                                LC.DAT_ATIV
                                                        FROM CLIENTES CL
                                                        LEFT JOIN USUARIOS USU ON USU.COD_USUARIO = CL.COD_VENDEDOR
                                                        LEFT JOIN LOG_CANAL LC ON LC.COD_CLIENTE = CL.COD_CLIENTE
                                                        LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND = CL.COD_UNIVEND
                                                        WHERE CL.COD_EMPRESA = $cod_empresa
                                                        AND (CL.COD_UNIVEND IN ($lojasSelecionadas) OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)
                                                            $andNome
                                                            $andPlaca
                                                            $andCartao
                                                            $andCpf
                                                            $andAlterac
                                                            $andInativos
                                                            $andDatIni
                                                            $andDatFim
                                                        ORDER BY CL.NOM_CLIENTE LIMIT $inicio,$itens_por_pagina";
                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                                //fnEscreve($sql);
                                                //  echo "___".$sql."___";
                                                $count = 0;
                                                while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

                                                    $log_funciona = $qrListaEmpresas['LOG_FUNCIONA'];
                                                    if ($log_funciona == "S") {
                                                        $mostraCracha = '<i class="fa fa-address-card" aria-hidden="true"></i>';
                                                    } else {
                                                        $mostraCracha = "";
                                                    }

                                                    if ($cod_empresa == 19) {
                                                        $mostraPlaca = "<td class='text-center'><small>" . $qrListaEmpresas['DES_PLACA'] . "</small></td>";
                                                    } else {
                                                        $mostraPlaca = "";
                                                    }

                                                    if ($qrListaEmpresas['COD_UNIVEND'] != 0) {
                                                        $unidade = $qrListaEmpresas['NOM_FANTASI'];
                                                    } else {
                                                        $unidade = "Sem unidade";
                                                    }

                                                    if ($log_alterac == 'S') {
                                                        $dataFiltro = $qrListaEmpresas['DAT_ALTERAC'];
                                                    } else {
                                                        $dataFiltro = $qrListaEmpresas['DAT_ATIV'];
                                                    }

                                                    if ($qrListaEmpresas['DAT_ATIV'] != "") {
                                                        $termos = "<span class='fal fa-check text-success'></span>";
                                                    } else {
                                                        $termos = "";
                                                    }

                                                    $count++;

                                                    if ($autoriza == 1) {
                                                        $colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</a></small></td>";
                                                    } else {
                                                        $colCliente = "<td><small>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</small></td>";
                                                    }

                                                    echo "
                                                        <tr>
                                                          <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                                                          <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
                                                          " . $colCliente . "
                                                          <td><small>" . fnDataFull($qrListaEmpresas['DAT_CADASTR']) . "</small></td>
                                                          <td><small>" . fnDataFull($dataFiltro) . "</small></td>
                                                          <td><small>" . fnDataFull($qrListaEmpresas['DAT_ULTCOMPR']) . "</small></td>
                                                          <td><small>" . $unidade . "</small></td>
                                                          <td> <small>" . $qrListaEmpresas['NOM_USUARIO'] . "</small></td>
                                                          <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CARTAO']) . "</small></td>
                                                          <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CGCECPF']) . "</small></td>
                                                          <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_TELEFON']) . "</small></td>
                                                          <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CELULAR']) . "</small></td>
                                                          <td><small>" . fnMascaraCampo(strtolower($qrListaEmpresas['DES_EMAILUS'])) . "</small></td>
                                                          $mostraPlaca
                                                          <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['VAL_SALDO'], 2) . "</small></td>
                                                          <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['SALDO_BLOQUEADO'], 2) . "</small></td>
                                                          <td class='text-center'><small>" . $termos . "</small></td>
                                                        </tr>
                                                        <input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
                                                        <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
                                                        ";
                                                }

                                                ?>

                                            </tbody>
                                            <?php if ($cod_empresa != 0) { ?>
                                                <tfoot>
                                                    <tr>
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

                                        <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
                                        <input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
                                        <input type="hidden" name="opcao" id="opcao" value="">
                                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">



                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="push"></div>

                    </div>

                </div>
            </div>
            <!-- fim Portlet -->
        </div>
    </form>
</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
    $(document).ready(function() {

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
                                        url: "relatorios/ajxRelAtualizacaoCadastral.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        console.log(response);
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


    function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "relatorios/ajxRelAtualizacaoCadastral.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
            data: $('#formulario').serialize(),
            beforeSend: function() {
                $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                $("#relatorioConteudo").html(data);
                console.log(data);
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
</script>