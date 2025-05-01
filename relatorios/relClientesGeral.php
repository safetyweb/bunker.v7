<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$dias30 = '';
$log_funcionario = '';
$log_inativos = '';
$log_master = '';
$log_campanha = '';
$nom_cliente = '';
$des_placa = '';
$num_cartao = '';
$dat_fim = '';
$dat_ini = '';
$log_celular = '';
$num_celular = '';
$canal_cadastro = '';
$andCodigo = '';
$checkCelular = '';
$num_cgcecpf = '';



//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));
$log_univend = 'N';

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = "1";

$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode($_GET['id']);

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
        $num_celular = fnLimpacampo(@$_REQUEST['NUM_CELULAR']);
        $num_cgcecpf = fnLimpaDoc(fnLimpacampo(@$_REQUEST['NUM_CGCECPF']));
        $canal_cadastro = fnLimpaCampoZero(@$_POST['CANAL_CADASTRO']);
        if (empty(@$_REQUEST['LOG_UNIVEND'])) {
            $log_univend = 'N';
        } else {
            $log_univend = @$_REQUEST['LOG_UNIVEND'];
        }
        if (empty(@$_REQUEST['LOG_MASTER'])) {
            $log_master = 'N';
        } else {
            $log_master = @$_REQUEST['LOG_MASTER'];
        }

        $dat_ini = fnDataSql(@$_POST['DAT_INI']);
        $dat_fim = fnDataSql(@$_POST['DAT_FIM']);

        if (empty($_REQUEST['LOG_FUNCIONARIO'])) {
            $log_funcionario = 'N';
        } else {
            $log_funcionario = $_REQUEST['LOG_FUNCIONARIO'];
        }

        if (empty($_REQUEST['LOG_CELULAR'])) {
            $log_celular = 'N';
            $checkCelular = "";
        } else {
            $log_celular = $_REQUEST['LOG_CELULAR'];
            $checkCelular = "checked";
        }

        if (empty($_REQUEST['LOG_INATIVOS'])) {
            $log_inativos = 'N';
        } else {
            $log_inativos = $_REQUEST['LOG_INATIVOS'];
        }

        if (empty($_REQUEST['LOG_CAMPANHA'])) {
            $log_campanha = 'N';
        } else {
            $log_campanha = $_REQUEST['LOG_CAMPANHA'];
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

if ($log_master == "S") {
    $checkMaster = "checked";
} else {
    $checkMaster = "";
}

if ($log_campanha == "S") {
    $checkCampanha = "checked";
} else {
    $checkCampanha = "";
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

    <form onsubmit="return validarFormulario();" data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

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

                                <div class="col-md-6">

                                    <div class="flexrow">

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label">Só Funcionários</label>
                                                <div class="push5"></div>
                                                <label class="switch switch-small">
                                                    <input type="checkbox" name="LOG_FUNCIONARIO" id="LOG_FUNCIONARIO" class="switch" value="S" <?= $check_funcionario ?>>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label">Só Inativos</label>
                                                <div class="push5"></div>
                                                <label class="switch switch-small">
                                                    <input type="checkbox" name="LOG_INATIVOS" id="LOG_INATIVOS" class="switch" value="S" <?= $checkInativos ?>>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label">Somente sem unidade</label>
                                                <div class="push5"></div>
                                                <label class="switch switch-small">
                                                    <input type="checkbox" name="LOG_UNIVEND" id="LOG_UNIVEND" class="switch" value="S" <?= $checkUnivend ?>>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label">Não participa Antifraude</label>
                                                <div class="push5"></div>
                                                <label class="switch switch-small">
                                                    <input type="checkbox" name="LOG_MASTER" id="LOG_MASTER" class="switch" value="S" <?= $checkMaster ?>>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label">Celulares Duplicados</label>
                                                <div class="push5"></div>
                                                <input type="hidden" name="TOUR_LOG_CELULAR" id="TOUR_LOG_CELULAR">
                                                <label class="switch switch-small">
                                                    <input type="checkbox" name="LOG_CELULAR" id="LOG_CELULAR" class="switch" value="S" <?= $checkCelular ?>>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label">Cadastrados via Campanha</label>
                                                <div class="push5"></div>
                                                <input type="hidden" name="TOUR_LOG_CAMPANHA" id="TOUR_LOG_CAMPANHA">
                                                <label class="switch switch-small">
                                                    <input type="checkbox" name="LOG_CAMPANHA" id="LOG_CAMPANHA" class="switch" value="S" <?= $checkCampanha ?>>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group" id="unidadeAtendimento">
                                        <label for="inputName" class="control-label ">Unidade de Atendimento</label>
                                        <?php include "unidadesAutorizadasComboMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CPF/CNPJ</label>
                                        <input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo $num_cgcecpf; ?>">
                                    </div>
                                </div>


                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Nome do Cliente</label>
                                        <input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="40" value="<?php echo $nom_cliente; ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Numero de Celular</label>
                                        <input type="text" class="form-control input-sm" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="40" value="<?php echo $num_celular; ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Data Inicial de Cadastro</label>

                                            <div class="input-group date datePicker" id="DAT_INI_GRP">
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
                                            <label for="inputName" class="control-label">Data Final de Cadastro</label>

                                            <div class="input-group date datePicker" id="DAT_FIM_GRP">
                                                <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Canal de Cadastro</label>
                                        <select data-placeholder="Selecione um estado" name="CANAL_CADASTRO" id="CANAL_CADASTRO" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="1">PDV SH</option>
                                            <option value="2">TOTEM</option>
                                            <option value="3">HOTSITE</option>
                                            <option value="4">BUNKER</option>
                                            <option value="5">PDV VIRTUAL</option>
                                            <option value="6">MAIS CASH</option>
                                        </select>
                                        <script>
                                            $("#formulario #CANAL_CADASTRO").val("<?php echo $canal_cadastro; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
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

                                    <div class="no-more-tables">


                                        <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Nome do Cliente</th>
                                                    <th>Data de Cadastro</th>
                                                    <th>Última Compra</th>
                                                    <th>Loja</th>
                                                    <th>Canal de Cadastro</th>
                                                    <th>Vendedor</th>
                                                    <th>Cartão</th>
                                                    <th>CPF</th>
                                                    <th>Celular</th>
                                                    <th>e-Mail</th>
                                                    <?php if ($cod_empresa == 19) { ?>
                                                        <th width="150">Placa</th>
                                                    <?php } ?>
                                                    <th width="100">Saldo Disponível</th>
                                                    <th width="100">Saldo Bloqueado</th>
                                                </tr>
                                            </thead>
                                            <tbody id="relatorioConteudo">

                                                <?php

                                                if ($cod_empresa != 0) {

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

                                                    if ($log_funcionario == 'S') {
                                                        $andFuncionarios = " AND CL.LOG_FUNCIONA = 'S' ";
                                                    } else {
                                                        $andFuncionarios = "";
                                                    }

                                                    if ($log_master == 'S') {
                                                        $andMaster = " AND CL.LOG_MASTER = 'S' ";
                                                    } else {
                                                        $andMaster = "";
                                                    }

                                                    if ($log_campanha == 'S') {
                                                        $andCampanha = "AND CL.COD_CLIENTE in (SELECT DISTINCT cod_cliente  FROM CREDITOSDEBITOS    WHERE COD_CAMPANHA in   (SELECT cap.COD_CAMPANHA FROM campanha cap
                                                LEFT JOIN campanharegra reg ON reg.COD_CAMPANHA=cap.COD_CAMPANHA
                                                WHERE cap.tip_campanha IN (22,23) AND cap.COD_EMPRESA=$cod_empresa))";
                                                    } else {
                                                        $andCampanha = "";
                                                    }

                                                    if ($dat_ini == "") {
                                                        $andDatIni = " ";
                                                    } else {
                                                        $andDatIni = "AND DATE_FORMAT(CL.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' ";
                                                    }

                                                    if ($dat_fim == "") {
                                                        $andDatFim = " ";
                                                    } else {
                                                        $andDatFim = "AND DATE_FORMAT(CL.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' ";
                                                    }

                                                    if ($log_celular == "S") {
                                                        if ($dat_ini != "") {
                                                            $andDat = "AND sub_cl.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";
                                                            $andDatSub = "AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";
                                                        } else {
                                                            $andDat = "";
                                                            $andDatSub = "";
                                                        }

                                                        $andLogCel = "AND CL.num_celular IN (
                                                                SELECT num_celular
                                                                FROM clientes
                                                                WHERE cod_empresa = $cod_empresa
                                                                AND cod_univend IN (null,0," . $lojasSelecionadas . ")
                                                                $andDatSub
                                                                GROUP BY num_celular
                                                                HAVING COUNT(*) > 2
                                                            )
                                                ";



                                                        $order = "ORDER BY CL.NUM_CELULAR";
                                                    } else {
                                                        $andLogCel = "";
                                                        $order = "ORDER BY CL.NOM_CLIENTE";
                                                    }

                                                    if ($num_celular != "") {
                                                        $andCelular = "AND cl.num_celular = $num_celular";
                                                    } else {
                                                        $andCelular = "";
                                                    }

                                                    if ($canal_cadastro != "") {
                                                        $andCanal = "AND LC.COD_CANAL = $canal_cadastro";
                                                    } else {
                                                        $andCanal = "";
                                                    }


                                                    //paginação
                                                    $sql = "SELECT COUNT(CL.COD_CLIENTE) AS CONTADOR FROM  " . connTemp($cod_empresa, 'true') . ".CLIENTES CL
                                                    LEFT JOIN LOG_CANAL AS LC ON LC.COD_CLIENTE = CL.COD_CLIENTE AND CL.COD_EMPRESA=LC.COD_EMPRESA
                                            WHERE CL.COD_EMPRESA = " . $cod_empresa . "
                                            " . $andCodigo . "
                                            " . $andCampanha . "
                                            " . $andUnidades . "
                                            " . $andNome . "
                                            " . $andPlaca . "
                                            " . $andCartao . "
                                            " . $andCpf . "
                                            " . $andFuncionarios . "
                                            " . $andInativos . "
                                            " . $andMaster . "
                                            " . $andDatIni . "
                                            " . $andDatFim . "
                                            " . $andLogCel . "
                                            " . $andCanal . "
                                            " . $andCelular . "
                                            $order ";

                                                    //fnEscreve($sql);

                                                    $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                                    $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

                                                    $numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

                                                    //variavel para calcular o início da visualização com base na página atual
                                                    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                                    //lista de clientes
                                                    $sql = "SELECT CL.*, $selectPlaca
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
                                            USU.NOM_USUARIO,
                                            uni.NOM_FANTASI,
                                            LC.COD_CANAL
                                            FROM CLIENTES CL
                                            LEFT JOIN USUARIOS USU ON USU.COD_USUARIO = CL.COD_ATENDENTE
                                            LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=CL.COD_UNIVEND
                                            LEFT JOIN LOG_CANAL AS LC ON LC.COD_CLIENTE = CL.COD_CLIENTE AND CL.COD_EMPRESA=LC.COD_EMPRESA
                                            -- LEFT JOIN LOG_CANAL AS LC ON LC.COD_CLIENTE = CL.COD_CLIENTE
                                            WHERE CL.COD_EMPRESA = $cod_empresa
                                            $andUnidades
                                            $andCampanha
                                            $andNome
                                            $andPlaca
                                            $andCartao
                                            $andCpf
                                            $andFuncionarios
                                            $andInativos
                                            $andMaster
                                            $andDatIni
                                            $andDatFim
                                            $andLogCel
                                            $andCelular
                                            $andCanal
                                            $order LIMIT $inicio,$itens_por_pagina";

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
                                                        if ($qrListaEmpresas['DES_EMAILUS'] == "") {
                                                            $email = "e-mail não cadastrado!";
                                                        } else {
                                                            $email = fnmascaracampo($qrListaEmpresas['DES_EMAILUS']);
                                                        }

                                                        if ($qrListaEmpresas['COD_UNIVEND'] != 0) {
                                                            $unidade = $qrListaEmpresas['NOM_FANTASI'];
                                                        } else {
                                                            $unidade = "Sem unidade";
                                                        }

                                                        switch ($qrListaEmpresas['COD_CANAL']) {

                                                            case 2:
                                                                $canal = "TOTEM";
                                                                break;

                                                            case 3:
                                                                $canal = "HOTSITE";
                                                                break;

                                                            case 4:
                                                                $canal = "BUNKER";
                                                                break;

                                                            case 5:
                                                                $canal = "PDV VIRTUAL";
                                                                break;

                                                            case 6:
                                                                $canal = "MAIS CASH";
                                                                break;

                                                            default:
                                                                $canal = "PDV SH";
                                                                break;
                                                        }

                                                        $count++;

                                                        echo "
                                                <tr>
                                                <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
                                                <td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</a></small></td>
                                                <td><small>" . fnDataFull($qrListaEmpresas['DAT_CADASTR']) . "</small></td>
                                                <td><small>" . fnDataFull($qrListaEmpresas['DAT_ULTCOMPR']) . "</small></td>
                                                <td><small>" . $unidade . "</small></td>
                                                <td><small>" . $canal . "</small></td>
                                                <td> <small>" . $qrListaEmpresas['NOM_USUARIO'] . "</small></td>
                                                <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CARTAO']) . "</small></td>
                                                <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CGCECPF']) . "</small></td>
                                                <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CELULAR']) . "</small></td>
                                                <td><small>" . $email . "</small></td>
                                                $mostraPlaca
                                                <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['VAL_SALDO'], 2) . "</small></td>
                                                <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['SALDO_BLOQUEADO'], 2) . "</small></td>
                                                </tr>
                                                <input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
                                                <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
                                                ";
                                                    }
                                                }
                                                ?>

                                            </tbody>
                                            <?php if ($cod_empresa != 0) { ?>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="100">
                                                            <div class="btn-group dropdown left">
                                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-excel" aria-hidden="true"></i>
                                                                    &nbsp; Exportar&nbsp;
                                                                    <span class="fas fa-caret-down"></span>
                                                                </button>
                                                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                                                    <li><a class="btn btn-sm exportarCSV" data-attr="all" style="text-align: left">&nbsp; Exportar Detalhado </a></li>
                                                                    <li><a class="btn btn-sm exportSimpl" data-attr="exportSimpl" style="text-align: left">&nbsp; Exportar Simplificado Celulares Duplicados </a></li>
                                                                    <li><a class="btn btn-sm exportDupli" data-attr="univend" style="text-align: left">&nbsp; Exportar Celulares Duplicados </a></li>
                                                                    <li><a class="btn btn-sm exportUni" data-attr="univend" style="text-align: left">&nbsp; Exportar Cel. Duplicado Unidade</a></li>
                                                                </ul>
                                                            </div>
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
                                        url: "relatorios/ajxRelClientesGeral.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        //console.log(response);
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

        $(".exportSimpl").click(function() {
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
                                        url: "relatorios/ajxRelClientesGeral.do?opcao=exportSimpl&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        //console.log(response);
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

        $(".exportDupli").click(function() {
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
                                        url: "relatorios/ajxRelClientesGeral.do?opcao=expdupli&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        //console.log(response);
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

        $(".exportUni").click(function() {
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
                                        url: "relatorios/ajxRelClientesGeral.do?opcao=exportUni&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        //console.log(response);
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
            url: "relatorios/ajxRelClientesGeral.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
            data: $('#formulario').serialize(),
            beforeSend: function() {
                $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                $("#relatorioConteudo").html(data);
                //console.log(data);
            },
            error: function() {
                $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });
    }



    //adicionado por Lucas ref. chamado 6247 07/05/2024
    $('#LOG_CAMPANHA').change(function() {
        if ($(this).prop("checked") == true) {
            $.ajax({
                type: "POST",
                url: "relatorios/ajxRelClientesGeral.do?opcao=campanha22&id=<?php echo fnEncode($cod_empresa); ?>",
                data: $('#formulario').serialize(),
                beforeSend: function() {
                    $('#unidadeAtendimento').html('<div class="loading" style="width: 100%;"></div>');
                },
                success: function(data) {
                    $("#unidadeAtendimento").html(data);
                },
                error: function() {}
            });
        } else {

            $.ajax({
                type: "POST",
                url: "relatorios/ajxRelClientesGeral.do?opcao=allunid&id=<?php echo fnEncode($cod_empresa); ?>",
                data: $('#formulario').serialize(),
                beforeSend: function() {
                    $('#unidadeAtendimento').html('<div class="loading" style="width: 100%;"></div>');
                },
                success: function(data) {
                    $("#unidadeAtendimento").html(data);
                },
                error: function() {}
            });
        }
    })
    //adicionado por Lucas ref. chamado 6247 07/05/2024
    $(document).ready(function() {

        if ($('#LOG_CAMPANHA').prop("checked") == true) {
            $.ajax({
                type: "POST",
                url: "relatorios/ajxRelClientesGeral.do?opcao=campanha22&id=<?php echo fnEncode($cod_empresa); ?>&unv=<?php echo fnEncode($cod_univend); ?>",
                data: $('#formulario').serialize(),
                beforeSend: function() {
                    $('#unidadeAtendimento').html('<div class="loading" style="width: 100%;"></div>');
                },
                success: function(data) {
                    $("#unidadeAtendimento").html(data);
                    //console.log(data);
                },
                error: function() {}
            });
        }
    });

    function page(index) {

        $("#pagina").val(index);
        $("#formulario")[0].submit();
        //alert(index);	

    }
</script>