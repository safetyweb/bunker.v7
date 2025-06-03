<?php
if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$filtro = "";
$data_final = "";
$data_inicial = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$val_pesquisa = "";
$hHabilitado = "";
$hashForm = "";
$esconde = "";
$DestinoPg = "";
$andAtivo = "";
$query = "";
$cod_consultores = "";
$qrCon = "";
$andFiltro = "";
$arrayQuery = [];
$qtd_vendas = 0;
$qrListaEmpresas = "";
$sqlVendas = "";
$queryVendas = "";
$qrVendas = "";
$mostraAtivo = "";
$radioAcesso = "";
$tem_sistema = "";
$mostraEmpresa = "";
$corLojaAtv = "";
$sqlUnv = "";
$qtd_vendUnv = 0;
$qrUnv = "";
$sqlVendUni = "";
$queryVendUnv = "";
$qrVendUnv = "";
$ativo = "";
$cobranca = "";
$nomSh = "";
$RedirectPg = "";
$content = "";
$filtro = '';
$data_final = date('d/m/Y');
$data_inicial = date('d/m/Y', strtotime("-2 days"));
$hashLocal = mt_rand();
$num_cgcecpf = "";
$cod_univend = 0;
$tip_filtroreg = "";
$log_todas = "";
$nom_cidadec  = "";
$cod_estadof = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        // - variáveis da barra de pesquisa -------------
        $filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
        $val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);
        // ----------------------------------------------
        if (isset($_POST['NUM_CGCECPF'])) {
            $num_cgcecpf = fnLimpaCampo(fnLimpaDoc(@$_POST['NUM_CGCECPF']));
        }

        if (isset($_REQUEST['COD_EMPRESA']) && !empty($_REQUEST['COD_EMPRESA'])) {
            $cod_empresa = fnLimpaArray($_REQUEST['COD_EMPRESA']);
        }

        if (isset($_REQUEST['COD_UNIVEND']) && !empty($_REQUEST['COD_UNIVEND'])) {
            $cod_univend = fnLimpaArray($_REQUEST['COD_UNIVEND']);
        }

        if (isset($_POST['TIP_FILTROREG'])) {
            $tip_filtroreg = fnLimpaCampo($_POST['TIP_FILTROREG']);
        }

        if (isset($_POST['COD_ESTADOF'])) {
            $cod_estadof = fnLimpaCampo($_POST['COD_ESTADOF']);
        }

        if (isset($_POST['NOM_CIDADEC'])) {
            $nom_cidadec = fnLimpaCampo($_POST['NOM_CIDADEC']);
        }


        if (isset($_REQUEST['LOG_TODAS'])) {
            $log_todas = 'S';
        } else {
            $log_todas = 'N';
        }

        // fnEscreveArray($_REQUEST);
        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        // if ($opcao != ''){


        // }  

    }
}

// esquema do X da barra - (recarregar pesquisa)
if ($val_pesquisa != "") {
    $esconde = " ";
} else {
    $esconde = "display: none;";
}

$checked = "";
if (isset($log_todas) && $log_todas == 'S') {
    $checked = "checked";
}
?>

<div class="push30"></div>

<div class="row">

    <div class="col-md12 margin-bottom-30">

        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"> <?php echo $NomePg; ?></span>
                </div>

                <?php
                include "backReport.php";
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


                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Filtros</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Somente Inativas</label>
                                        <div class="push5"></div>
                                        <label class="switch">
                                            <input type="checkbox" name="LOG_TODAS" id="LOG_TODAS" class="switch" value="S" <?= $checked; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Empresa</label>
                                        <select data-placeholder="Selecione uma empresa" name="COD_EMPRESA[]" id="COD_EMPRESA" multiple="multiple" class="chosen-select-deselect">
                                            <option value="">Todas Empresas</option>
                                            <?php
                                            $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM EMPRESAS WHERE LOG_ATIVO = 'S' AND COD_MASTER = 3 AND LOG_INTEGRADORA = 'N' AND COD_EMPRESA NOT IN (2,3)";
                                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                            while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

                                                if (isset($_REQUEST['COD_EMPRESA']) && is_array($_REQUEST['COD_EMPRESA'])) {
                                                    if (recursive_array_search($qrListaEmpresas['COD_EMPRESA'], array_filter($_REQUEST['COD_EMPRESA'])) !== false) {
                                                        $selecionado = "selected";
                                                    } else {
                                                        $selecionado = "";
                                                    }
                                                } else {
                                                    $selecionado = "";
                                                }

                                                echo "
                                                    <option value='" . $qrListaEmpresas['COD_EMPRESA'] . "' " . $selecionado . ">" . $qrListaEmpresas['NOM_FANTASI'] . "</option> 
                                                    ";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                        <a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square-o" aria-hidden="true"></i> selecionar todos</a>&nbsp;
                                        <a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>

                                        <script type="text/javascript">
                                            $('#iAll').on('click', function(e) {
                                                e.preventDefault();
                                                $('#COD_EMPRESA option').prop('selected', true).trigger('chosen:updated');
                                            });

                                            $('#iNone').on('click', function(e) {
                                                e.preventDefault();
                                                $("#COD_EMPRESA option:selected").removeAttr("selected").trigger('chosen:updated');
                                            });
                                        </script>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Unidade</label>
                                        <div id="divId_sub">
                                            <select data-placeholder="" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect">
                                                <option value="">&nbsp;</option>
                                            </select>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CNPJ</label>
                                        <input type="text" class="form-control input-sm" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo $num_cgcecpf; ?>">
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Filtro de Região por</label>
                                        <select data-placeholder="Selecione uma empresa" name="TIP_FILTROREG" id="TIP_FILTROREG" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="EMP">Empresa</option>
                                            <option value="UNV">Unidade</option>
                                        </select>
                                        <script>
                                            $("#formulario #TIP_FILTROREG").val("<?php echo $tip_filtroreg; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Estado</label>
                                        <select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="AC">AC</option>
                                            <option value="AL">AL</option>
                                            <option value="AM">AM</option>
                                            <option value="AP">AP</option>
                                            <option value="BA">BA</option>
                                            <option value="CE">CE</option>
                                            <option value="DF">DF</option>
                                            <option value="ES">ES</option>
                                            <option value="GO">GO</option>
                                            <option value="MA">MA</option>
                                            <option value="MG">MG</option>
                                            <option value="MS">MS</option>
                                            <option value="MT">MT</option>
                                            <option value="PA">PA</option>
                                            <option value="PB">PB</option>
                                            <option value="PE">PE</option>
                                            <option value="PI">PI</option>
                                            <option value="PR">PR</option>
                                            <option value="RJ">RJ</option>
                                            <option value="RN">RN</option>
                                            <option value="RO">RO</option>
                                            <option value="RR">RR</option>
                                            <option value="RS">RS</option>
                                            <option value="SC">SC</option>
                                            <option value="SE">SE</option>
                                            <option value="SP">SP</option>
                                            <option value="TO">TO</option>
                                        </select>
                                        <script>
                                            $("#formulario #COD_ESTADOF").val("<?php echo $cod_estadof; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cidade</label>
                                        <input type="text" class="form-control input-sm" name="NOM_CIDADEC" id="NOM_CIDADEC" value="<?php echo $nom_cidadec; ?>">
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="push20"></div>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push20"></div>

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                        <div class="push5"></div>

                    </form>
                </div>
            </div>
        </div>

        <div class="push20"></div>

        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"><?php echo $NomePg ?></span>
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
                <!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->
                <div class="push30"></div>

                <div class="row">
                    <form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

                        <div class="col-md-2"></div>

                        <div class="col-md-4 col-xs-12">
                            <div class="push20"></div>

                            <div class="input-group activeItem">
                                <div class="input-group-btn search-panel">
                                    <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
                                        <span id="search_concept">Sem filtro</span>&nbsp;
                                        <span class="far fa-angle-down"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li class="divisor"><a href="#">Sem filtro</a></li>
                                        <!-- <li class="divider"></li> -->
                                        <li><a href="#NOM_EMPRESA">Razão social</a></li>
                                        <li><a href="#NOM_FANTASI">Nome fantasia</a></li>
                                        <li><a href="#NUM_CGCECPF">CNPJ</a></li>
                                        <li><a href="#NOM_CONSULTOR">Coordenador</a></li>
                                    </ul>
                                </div>
                                <input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">
                                <input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
                                <div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
                                    <button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
                                </div>
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
                                </div>
                            </div>

                        </div>

                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                    </form>

                </div>

                <div class="push30"></div>

                <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

                <div class="col-lg-12">

                    <div class="no-more-tables">

                        <form name="formLista" id="formLista" method="post" action="action.php?mod=<?php echo $DestinoPg; ?>&id=0">

                            <!-- usar classe "buscavel" nos elementos-pai a serem filtrados -->
                            <table class="table table-bordered table-striped table-hover tablesorter buscavel">
                                <thead>
                                    <tr>
                                        <th class="{sorter:false}" width="40"></th>
                                        <th class="text-center">Código</th>
                                        <th>Nome Fantasia</th>
                                        <th class="text-center">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label"><b>Qtd. Vendas</b></label>
                                                <input type="hidden" class="form-control input-sm" name="TOUR_QTD_VENDAS" id="TOUR_QTD_VENDAS">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </th>
                                        <th style='display:none;'>Telefones</th>
                                        <th>Coordenador</th>
                                        <th>Integradora</th>
                                        <th>Lojas</th>
                                        <th>Ativas</th>
                                        <th class="{sorter:false}">Cobrança</th>
                                        <th class="{sorter:false}">Ativo</th>
                                        <th class="text-center">Status</th>
                                        <th>Produção</th>
                                        <th class="text-center">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label"><b>Alteração</b></label>
                                                <input type="hidden" class="form-control input-sm" name="TOUR_ALTERACAO" id="TOUR_ALTERACAO">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="relatorioEmpresas">

                                    <?php

                                    $andAtivo = "AND LOG_ATIVO = 'S'";


                                    // filtro do banco de dados (precisa existir antes do sql)-------------------------------------------------------------------------------------------------
                                    if ($filtro != "") {
                                        if ($filtro == 'NOM_CONSULTOR') {
                                            $sql = "SELECT COD_USUARIO FROM USUARIOS WHERE COD_EMPRESA = 3 AND NOM_USUARIO LIKE '%$val_pesquisa%'";
                                            $query = mysqli_query($connAdm->connAdm(), $sql);

                                            $cod_consultores = "";
                                            while ($qrCon = mysqli_fetch_assoc($query)) {
                                                $cod_consultores .= $qrCon['COD_USUARIO'] . ',';
                                            }
                                            $cod_consultores = rtrim($cod_consultores, ',');

                                            $andFiltro = " AND COD_CONSULTOR IN ($cod_consultores)";
                                        } else {
                                            $andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
                                        }
                                    } else {
                                        $andFiltro = " ";
                                    }
                                    // --------------------------------------------------------------------------------------------------------------------------------------------------------

                                    $andCnpj = "";
                                    if ($num_cgcecpf != "") {
                                        $andCnpj .= " AND E.NUM_CGCECPF = '$num_cgcecpf' ";
                                    }

                                    $andEmp = "";
                                    if ($cod_empresa != "") {
                                        $andEmp = "AND E.COD_EMPRESA IN (" . $cod_empresa . ") ";
                                    }

                                    $andUnv = "";
                                    if ($cod_univend != "" && $cod_univend != 0) {
                                        $andUnv = "AND UNV.COD_UNIVEND IN ($cod_univend)";
                                    }

                                    $andCidade = "";
                                    $andEstado = "";
                                    if ($tip_filtroreg != "" && $tip_filtroreg == 'EMP') {
                                        if ($nom_cidadec != "") {
                                            $andCidade = "AND E.NOM_CIDADEC = '$nom_cidadec'";
                                        }

                                        if ($cod_estadof != "") {
                                            $andEstado = "AND E.COD_ESTADOF = '$cod_estadof'";
                                        }
                                    }

                                    $andCidadeUnv = "";
                                    $andEstadoUnv = "";
                                    $andEstadoUnvEmp = "";
                                    if ($tip_filtroreg != "" && $tip_filtroreg == 'UNV') {
                                        if ($nom_cidadec != "") {
                                            $andCidadeUnv = "AND UNV.NOM_CIDADEC = '$nom_cidadec'";
                                        }

                                        if ($cod_estadof != "") {
                                            $andEstadoUnv = "AND UNV.COD_ESTADOF = '$cod_estadof'";
                                            $andEstadoUnvEmp = "
                                            AND E.COD_EMPRESA IN (
                                                SELECT DISTINCT UNI.COD_EMPRESA
                                                FROM unidadevenda AS UNI
                                                WHERE UNI.COD_ESTADOF = '$cod_estadof'
                                            )";
                                        }
                                    }

                                    $andAtivo = "";
                                    if ($log_todas == 'S') {
                                        $andAtivo = "AND LOG_ATIVO = 'N'";
                                    } else {
                                        $andAtivo = "AND LOG_ATIVO = 'S'";
                                    }

                                    if ($_SESSION["SYS_COD_MASTER"] == "2") {
                                        $sql = "SELECT STATUSSISTEMA.DES_STATUS, E.*,
																(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = E.COD_EMPRESA) as COD_DATABASE,
																(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=E.cod_consultor) as NOM_CONSULTOR, 
																(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = E.COD_EMPRESA) AS LOJAS,	
																(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = E.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,	
																(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = E.COD_EMPRESA AND UV.LOG_COBRANCA = 'S') AS COBRANCA_ATIVA,
																(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=E.COD_INTEGRADORA  ) NOM_INTEGRADORA,
																B.COD_DATABASE, 
																B.NOM_DATABASE,
                                                                E.DAT_ALTERAC 
																FROM empresas  E 
																LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=E.COD_STATUS
																INNER JOIN tab_database B ON B.cod_empresa=E.COD_EMPRESA 
																WHERE E.COD_EMPRESA <> 1 
																$andFiltro
																$andAtivo
                                                                $andCnpj
                                                                $andEmp
                                                                $andCidade
                                                                $andEstado
                                                                $andEstadoUnvEmp
                                                                $andAtivo
																ORDER by NOM_FANTASI";
                                    } else {
                                        $sql = "SELECT STATUSSISTEMA.DES_STATUS,E.*,
																(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = E.COD_EMPRESA) as COD_DATABASE, 
																(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=E.cod_consultor) as NOM_CONSULTOR, 
																(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = E.COD_EMPRESA) AS LOJAS,	
																(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = E.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,
																(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = E.COD_EMPRESA AND UV.LOG_COBRANCA = 'S') AS COBRANCA_ATIVA,	
																(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=E.COD_INTEGRADORA  ) NOM_INTEGRADORA,
																B.COD_DATABASE, 
																B.NOM_DATABASE,
                                                                E.DAT_ALTERAC 
																FROM empresas  E
																LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=E.COD_STATUS
																INNER JOIN tab_database B ON B.cod_empresa=E.COD_EMPRESA 
																WHERE E.COD_EMPRESA IN (" . $_SESSION["SYS_COD_MULTEMP"] . ")
																$andFiltro
																$andAtivo
                                                                $andCnpj
                                                                $andEmp
                                                                $andCidade
                                                                $andEstado
                                                                $andEstadoUnvEmp
                                                                $andAtivo
																ORDER by NOM_FANTASI";
                                    }
                                    // echo $sql;
                                    // fnEscreve($sql);
                                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                    $count = 0;

                                    while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {
                                        $count++;
                                        $qtd_vendas = 0;

                                        $sqlVendas = "SELECT count(*) as QTD_VENDAS FROM VENDAS WHERE COD_EMPRESA = " . $qrListaEmpresas['COD_EMPRESA'] . " AND DAT_CADASTR >= '" . fnDataSql($data_inicial) . "' AND DAT_CADASTR <= '" . fnDataSql($data_final) . " '";
                                        $queryVendas = mysqli_query(connTemp($qrListaEmpresas['COD_EMPRESA'], ''), $sqlVendas);

                                        if ($queryVendas === false) {
                                        }

                                        if (mysqli_num_rows($queryVendas) > 0) {
                                            $qrVendas = mysqli_fetch_assoc($queryVendas);
                                            $qtd_vendas = $qrVendas['QTD_VENDAS'];
                                        }

                                        if ($qrListaEmpresas['LOG_ATIVO'] == 'S') {
                                            // $mostraAtivo = '<i class="fal fa-check" aria-hidden="true"></i>';	
                                            $mostraAtivo = '<i class="fal fa-check" aria-hidden="true"></i>';
                                            $radioAcesso = "<input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'>";
                                        } else {
                                            $mostraAtivo = '';
                                            $radioAcesso = "";
                                        }

                                        if (!empty($qrListaEmpresas['COD_SISTEMAS'])) {
                                            $tem_sistema = "tem";
                                        } else {
                                            $tem_sistema = "nao";
                                        }

                                        $mostraEmpresa = "<a href='action.do?mod=" . fnEncode(1020) . "&id=" . fnEncode($qrListaEmpresas['COD_EMPRESA']) . "'>" . $qrListaEmpresas['NOM_FANTASI'] . "</a>";

                                        echo "
                                            <tr id='bloco_" . $qrListaEmpresas['COD_EMPRESA'] . "'>
                                                <th width='3%' class='{sorter:false} text-center'><a href='javascript:void(0);' onclick='abreDetail(" . $qrListaEmpresas['COD_EMPRESA'] . ")' style='padding:10px;'><i class='fa fa-angle-right' aria-hidden='true'></i></a></th>
                                                <td class='text-center'>" . $qrListaEmpresas['COD_EMPRESA'] . "</td>
                                                <td>" . $mostraEmpresa . "</td>
                                                <td class='text-center'>" . $qtd_vendas . "</td>
                                                <td style='display:none;'>" . $qrListaEmpresas['NUM_TELEFON'] . " / " . $qrListaEmpresas['NUM_CELULAR'] . "</td>
                                                <td>" . $qrListaEmpresas['NOM_CONSULTOR'] . "</td>
                                                <td>" . $qrListaEmpresas['NOM_INTEGRADORA'] . "</td>
                                                <td align='center'>" . $qrListaEmpresas['LOJAS'] . "</td>
                                                <td align='center'><span class='" . $corLojaAtv . "'>" . $qrListaEmpresas['LOJAS_ATIVAS'] . "</td>
                                                <td align='center'>" . fnValor($qrListaEmpresas['COBRANCA_ATIVA'], 0) . "</td>
                                                <td align='center'>" . $mostraAtivo . "</td>
                                                <td align='center'>" . $qrListaEmpresas['DES_STATUS'] . "</td>
                                                <td><small>" . fnDateRetorno($qrListaEmpresas['DAT_PRODUCAO']) . "</small></td>
                                                <td><small>" . fnDateRetorno($qrListaEmpresas['DAT_ALTERAC']) . "</small></td>
                                            </tr>
                                            <input type='hidden' id='ret_IDC_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_EMPRESA']) . "'>
                                            <input type='hidden' id='ret_ID_" . $count . "' value='" . $qrListaEmpresas['COD_EMPRESA'] . "'>
                                            <input type='hidden' id='ret_NOM_EMPRESA_" . $count . "' value='" . $qrListaEmpresas['NOM_EMPRESA'] . "'>
                                            ";

                                        $sqlUnv = "SELECT UNV.*, EMP.NOM_FANTASI as NOM_INTEGRADORA, UP.COD_INTEGRADORA
                                        FROM UNIDADEVENDA AS UNV 
                                        LEFT JOIN UNIDADES_PARAMETRO AS UP ON UNV.COD_UNIVEND = UP.COD_UNIVENDA AND UP.COD_EMPRESA = UNV.COD_EMPRESA
                                        LEFT JOIN EMPRESAS AS EMP ON UP.COD_INTEGRADORA = EMP.COD_EMPRESA AND EMP.LOG_INTEGRADORA = 'S'
                                        WHERE UNV.COD_EMPRESA = '" . $qrListaEmpresas['COD_EMPRESA'] . "' 
                                        $andUnv 
                                        $andCidadeUnv
                                        $andEstadoUnv
                                        ORDER BY UNV.NOM_FANTASI";

                                        $query = mysqli_query(connTemp($qrListaEmpresas['COD_EMPRESA'], ''), $sqlUnv);

                                        $qtd_vendUnv = 0;
                                        while ($qrUnv = mysqli_fetch_assoc($query)) {

                                            $sqlVendUni = "SELECT count(*) as QTD_VENDAS FROM VENDAS WHERE COD_EMPRESA = " . $qrListaEmpresas['COD_EMPRESA'] . " AND COD_UNIVEND = " . $qrUnv['COD_UNIVEND'] . " AND DAT_CADASTR >= '" . fnDataSql($data_inicial) . "' AND DAT_CADASTR <= '" . fnDataSql($data_final) . " '";
                                            $queryVendUnv = mysqli_query(connTemp($qrListaEmpresas['COD_EMPRESA'], ''), $sqlVendUni);
                                            if ($qrVendUnv = mysqli_fetch_assoc($queryVendUnv)) {
                                                $qtd_vendUnv = $qrVendUnv['QTD_VENDAS'];
                                            }

                                            if ($qrUnv['LOG_ESTATUS'] == 'S') {
                                                $ativo = '<i class="fal fa-check" aria-hidden="true"></i>';
                                            } else {
                                                $ativo = '';
                                            }

                                            if ($qrUnv['LOG_COBRANCA'] == 'S') {
                                                $cobranca = '<i class="fal fa-check" aria-hidden="true"></i>';
                                            } else {
                                                $cobranca = '';
                                            }

                                            if ($qrUnv['COD_INTEGRADORA'] != '' && $qrUnv['COD_INTEGRADORA'] == '0') {
                                                $nomSh = $qrUnv['NOM_INTEGRADORA'];
                                            } else {
                                                $nomSh = "";
                                            }


                                            echo "
                                            <tr style='background-color: #fff; display: none;' class='abreDetail_" . $qrListaEmpresas['COD_EMPRESA'] . "'>
                                                <td width='40'></td>
                                                <td class='text-center'>
                                                " . $qrUnv['COD_UNIVEND'] . "
                                                    
                                                </td>
                                                <td>
                                                " . $qrUnv['NOM_FANTASI'] . "
                                                </td>
                                                <td class='text-center'>" . $qtd_vendUnv . "</td>
                                                <td></td>
                                                <td>" . $qrUnv['NOM_INTEGRADORA'] . "</td>
                                                <td></td>
                                                <td></td>
                                                <td align='center'>" . $cobranca . "</td>
                                                <td align='center'>" . $ativo . "</td>
                                                <td align='center'>Produção</td>
                                                <td>" . fnDateRetorno($qrListaEmpresas['DAT_ALTERAC']) . "</td>
                                            </tr>";
                                        }
                                    }

                                    ?>

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="100">
                                            <a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="push50"></div>

                            <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                            <input type="hidden" name="codBusca" id="codBusca" value="">
                            <input type="hidden" name="nomBusca" id="nomBusca" value="">

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

<?php
if (!is_null($RedirectPg)) {
    $DestinoPg = fnEncode($RedirectPg);
} else {
    $DestinoPg = "";
}
?>

<script type="text/javascript">
    //Barra de pesquisa essentials ------------------------------------------------------
    $(document).ready(function(e) {

        var value = $('#INPUT').val().toLowerCase().trim();
        if (value) {
            $('#CLEARDIV').show();
        } else {
            $('#CLEARDIV').hide();
        }
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#", "");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #VAL_PESQUISA').val(param);
            $('#INPUT').focus();
        });

        $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function() {
            $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
        });

        $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function() {
            $("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
        });

        $('#CLEAR').click(function() {
            $('#INPUT').val('');
            $('#INPUT').focus();
            $('#CLEARDIV').hide();
            if ("<?= $filtro ?>" != "") {
                location.reload();
            } else {
                var value = $('#INPUT').val().toLowerCase().trim();
                if (value) {
                    $('#CLEARDIV').show();
                } else {
                    $('#CLEARDIV').hide();
                }
                $(".buscavel tr").each(function(index) {
                    if (!index) return;
                    $(this).find("td").each(function() {
                        var id = $(this).text().toLowerCase().trim();
                        var sem_registro = (id.indexOf(value) == -1);
                        $(this).closest('tr').toggle(!sem_registro);
                        return sem_registro;
                    });
                });
            }
        });

        // $('#SEARCH').click(function(){
        // 	$('#formulario').submit();
        // });

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
                                icon: 'fal fa-check-square-o',
                                content: function() {
                                    var self = this;
                                    return $.ajax({
                                        url: "ajxLinkControleLicencas.do?opcao=exportar&nomeRel=" + nome,
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '3_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        console.log(response);
                                    }).fail(function() {
                                        self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
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

        let univend = <?= $cod_univend ?>;
        if (univend != "") {
            var codBusca = $("#COD_EMPRESA").val();
            buscaSubCat(codBusca, <?= $cod_univend ?>);
        }

        $("#COD_EMPRESA").change(function() {
            var codBusca = $("#COD_EMPRESA").val();
            buscaSubCat(codBusca, 0);
        });
    });


    function buscaRegistro(el) {
        var filtro = $('#search_concept').text().toLowerCase();

        if (filtro == "sem filtro") {
            var value = $(el).val().toLowerCase().trim();
            if (value) {
                $('#CLEARDIV').show();
            } else {
                $('#CLEARDIV').hide();
            }
            $(".buscavel tr").each(function(index) {
                if (!index) return;
                $(this).find("td").each(function() {
                    var id = $(this).text().toLowerCase().trim();
                    var sem_registro = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!sem_registro);
                    return sem_registro;
                });
            });
        }
    }

    function abreDetail(idBloco) {
        var linhaPrincipal = $('#bloco_' + idBloco);
        var linhaDetalhe = $('.abreDetail_' + idBloco);

        // Move a linha de detalhes para logo após a linha principal
        linhaPrincipal.after(linhaDetalhe);

        // Alterna a visibilidade da linha de detalhes
        if (!linhaDetalhe.is(':visible')) {
            linhaDetalhe.show(); // Mostra a linha de detalhes
            linhaPrincipal.find(".fa").removeClass('fa-angle-right').addClass('fa-angle-down');
        } else {
            linhaDetalhe.hide(); // Oculta a linha de detalhes
            linhaPrincipal.find(".fa").removeClass('fa-angle-down').addClass('fa-angle-right');
        }
    }

    //-----------------------------------------------------------------------------------

    // function filtraEmpresaAtiva(el) {

    //     $.ajax({
    //         method: 'POST',
    //         url: 'ajxControleProjetos.do?opcao=ativa',
    //         data: $("#formulario").serialize(),
    //         beforeSend: function() {
    //             $('#relatorioEmpresas').html('<div class="loading" style="width: 100%;"></div>');
    //         },
    //         success: function(data) {
    //             console.log(data);
    //             $('#relatorioEmpresas').html(data);
    //             $(".tablesorter").trigger("updateAll");
    //         },
    //         error: function() {
    //             $('#relatorioEmpresas').html("Ops... Empresas não encontradas!");
    //         }
    //     });

    // }

    function buscaSubCat(idEmp, codUnv) {
        $.ajax({
            type: "GET",
            url: "ajxControleProjetos.do?opcao=univend",
            data: {
                COD_EMPRESA: idEmp,
                COD_UNIVEND: codUnv
            },

            beforeSend: function() {
                $('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                console.log(data);
                $("#divId_sub").html(data);
            },
            error: function() {
                $('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
            }
        });
    }


    function retornaForm(index) {

        $("#codBusca").val($("#ret_ID_" + index).val());
        $("#codBusca").val($("#ret_IDC_" + index).val());
        $("#nomBusca").val($("#ret_NOM_EMPRESA_" + index).val());
        $('#formLista').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id=' + $("#ret_IDC_" + index).val());
        $('#formLista').submit();
    }
</script>