<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$dat_ini = "";
$dat_fim = "";
$hashLocal = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$cod_propriedade = "";
$cod_chale = "";
$cod_statuspag = "";
$cod_tipo = "";
$filtro_data = "";
$log_statusreserva = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$sqlHotel = "";
$arrayHotel = [];
$qrHotel = "";
$qrStatuspag = "";
$selecionado = "";
$andProp = "";
$andChale = "";
$andStatusReserva = "";
$andStatus = "";
$andStatusCancel = "";
$andDat = "";
$andTipCredito = "";
$sqlContador = "";
$queryCont = "";
$result = "";
$total_hospedagem = 0;
$total_desconto = 0;
$total_pagoPropriedade = 0;
$sqlChale = "";
$queryChale = "";
$qrResultChale = "";
$sqlOpci = "";
$array = [];
$qrOpcionais = "";
$total_opcionais = 0;
$total_liquido = 0;
$total_margem = 0;
$retorno = "";
$totalitens_por_pagina = 0;
$inicio = "";
$tot_hosp = "";
$tot_marg = "";
$tot_descCupom = "";
$val_liquido = "";
$tot_liquido = "";
$tot_cafe = "";
$tot_pet = "";
$tot_fond = "";
$tot_ref = "";
$tot_dec = "";
$tot_pago = "";
$qrListaVendas = "";
$temCafe = "";
$temPet = "";
$temFound = "";
$temRefeicao = "";
$temDecoracao = "";
$tot_opcionais = "";
$qrLista = "";
$valEfetivo = "";
$val_margem = "";
$pct_margem = "";
$sqlCancela = "";
$arrayQueryCancela = [];
$qrBuscaCancela = "";
$cancelada = "";
$cupom = "";
$content = "";


//echo fnDebug('true');

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = "1";

$dat_ini = "";
$dat_fim = "";

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));
//$cod_univend = "9999"; //todas revendas - default

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
        $cod_propriedade = fnLimpaCampoZero(@$_POST['COD_PROPRIEDADE']);
        $cod_chale = fnLimpaCampoZero(@$_POST['COD_CHALE']);
        $cod_statuspag = fnLimpaCampoArray(@$_POST['COD_STATUSPAG']);
        $cod_tipo = fnLimpaCampoArray(@$_POST['COD_TIPO']);
        $dat_ini = fnDataSql(@$_POST['DAT_INI']);
        $dat_fim = fnDataSql(@$_POST['DAT_FIM']);
        $filtro_data = fnLimpaCampo(@$_REQUEST['FILTRO_DATA']);
        $log_statusreserva = fnLimpaCampo(@$_REQUEST['LOG_STATUSRESERVA']);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '' && $opcao != 0) {
        }
    }
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode(@$_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV, TIP_RETORNO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 274;
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

if ($filtro_data == "") {
    $filtro_data = "RESERVA";
}


?>
<style>
    .shortCut {
        color: #2c3e50;
        margin: 0 3px 0 3px;
    }
</style>
<div class="push30"></div>

<div class="row" id="div_Report">

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label ">Propriedades</label>
                                        <select data-placeholder="Selecione os hotéis" name="COD_PROPRIEDADE" id="COD_PROPRIEDADE" class="chosen-select-deselect">
                                            <option value="9999" selected>Todas</option>
                                            <?php
                                            $sqlHotel = "SELECT COD_EXTERNO, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S'";
                                            $arrayHotel = mysqli_query(connTemp($cod_empresa, ''), $sqlHotel);

                                            while ($qrHotel = mysqli_fetch_assoc($arrayHotel)) {
                                            ?>
                                                <option value="<?= $qrHotel['COD_EXTERNO'] ?>"><?= $qrHotel['NOM_FANTASI'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <script>
                                            $("#COD_PROPRIEDADE").val("<?php echo $cod_propriedade; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Chalés</label>
                                        <div id="divId_sub">
                                            <select data-placeholder="Selecione o sub grupo" name="COD_CHALE" id="COD_CHALE" class="chosen-select-deselect">
                                                <option value="">&nbsp;</option>
                                            </select>
                                        </div>
                                        <script>

                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Filtro de data</label>
                                        <select data-placeholder="Selecione o Tipo" name="FILTRO_DATA" id="FILTRO_DATA" class="chosen-select-deselect" required>
                                            <option value="RESERVA">Data do pedido</option>
                                            <option value="DEFAULT">Checkin - Checkout</option>
                                            <option value="CHECKIN">Checkin</option>
                                            <option value="CHECKOUT">Checkout</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                        <script>
                                            $("#FILTRO_DATA").val("<?php echo $filtro_data; ?>").trigger("chosen:updated");
                                        </script>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data Inicial</label>

                                        <div class="input-group date datePicker" id="DAT_INI_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data Final</label>

                                        <div class="input-group date datePicker" id="DAT_FIM_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label ">Status de Pagamento</label>
                                        <select data-placeholder="Selecione o status" name="COD_STATUSPAG[]" id="COD_STATUSPAG" class="chosen-select-deselect" multiple>
                                            <option value=""></option>
                                            <?php
                                            $sql = "SELECT * FROM ADORAI_STATUSPAG WHERE COD_EXCLUSA IS NULL";
                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                            while ($qrStatuspag = mysqli_fetch_assoc($arrayQuery)) {
                                                if (recursive_array_search($qrStatuspag['COD_STATUSPAG'], array_filter(@$_REQUEST['COD_STATUSPAG'])) !== false) {
                                                    $selecionado = "selected";
                                                } else {
                                                    $selecionado = "";
                                                }
                                            ?>
                                                <option value="<?= $qrStatuspag['COD_STATUSPAG'] ?>" <?= $selecionado ?>><?= $qrStatuspag['ABV_STATUSPAG'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label ">Forma de Pagamento</label>
                                        <select data-placeholder="Selecione o status" name="COD_TIPO[]" id="COD_TIPO" class="chosen-select-deselect" multiple>
                                            <option value=""></option>
                                            <?php
                                            $sql = "SELECT * FROM TIP_CREDITO WHERE COD_EXCLUSA = 0";
                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                            while ($qrStatuspag = mysqli_fetch_assoc($arrayQuery)) {
                                                if (recursive_array_search($qrStatuspag['COD_TIPO'], array_filter(@$_REQUEST['COD_TIPO'])) !== false) {
                                                    $selecionado = "selected";
                                                } else {
                                                    $selecionado = "";
                                                }
                                            ?>
                                                <option value="<?= $qrStatuspag['COD_TIPO'] ?>" <?= $selecionado ?>><?= $qrStatuspag['ABV_TIPO'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Status Reserva</label>
                                        <select data-placeholder="Selecione o Tipo" name="LOG_STATUSRESERVA" id="LOG_STATUSRESERVA" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="Cancelado">Cancelado</option>
                                            <option value="Reservado">Reservado</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                        <script>
                                            $("#LOG_STATUSRESERVA").val("<?php echo $log_statusreserva; ?>").trigger("chosen:updated");
                                        </script>
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
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                        <div class="push5"></div>


                        <div class="push5"></div>

                    </form>

                </div>
            </div>
        </div>

        <div class="push30"></div>

        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">
                    <div class="row">
                        <div class="col-md-12">

                            <table class="table table-bordered table-hover tablesorter buscavel" style="font-size: 14px;">

                                <thead>
                                    <tr>
                                        <th>Cód. Pedido</th>
                                        <th>Status</th>
                                        <th>Hospede</th>
                                        <th>Check-In</th>
                                        <th>Check-Out</th>
                                        <th>Propriedade</th>
                                        <th>Chalé</th>
                                        <th>Total Hospedagem</th>
                                        <th>Desc. Cupom</th>
                                        <th>Total Opcionais</th>
                                        <th>Val. Liquido</th>
                                        <th>Val. Pago Propriedade</th>
                                        <th class="text-center" width="15%">Opcionais</th>
                                        <th>Margem</th>
                                        <th>%</th>
                                    </tr>
                                </thead>

                                <tbody id="relatorioConteudo">

                                    <?php

                                    if ($cod_propriedade != "" && $cod_propriedade != '9999') {
                                        $andProp = "AND API.COD_PROPRIEDADE = $cod_propriedade";
                                    } else {
                                        $andProp = "";
                                    }

                                    if ($cod_chale != '' && $cod_chale != 0) {
                                        $andChale = "AND API.COD_CHALE = $cod_chale";
                                    } else {
                                        $andChale = "";
                                    }

                                    $andStatusReserva = "";
                                    if ($log_statusreserva != '' && $log_statusreserva != 0) {
                                        $andStatusReserva = "AND AP.LOG_STATUSRESERVA = '$log_statusreserva'";
                                    }

                                    if ($cod_statuspag != '' && $cod_statuspag != 0) {
                                        if ($cod_statuspag != 4 && $cod_statuspag != 8) {
                                            $andStatus = "AND AP.COD_STATUSPAG in ($cod_statuspag)";
                                        } else {
                                            $andStatusCancel = "INNER JOIN ADORAI_CANCELAMENTOS AS ACL ON ACL.COD_PEDIDO = AP.COD_PEDIDO";
                                        }
                                    } else {
                                        $andStatus = "";
                                        $andStatusCancel = "";
                                    }

                                    switch ($filtro_data) {
                                        case 'DEFAULT':
                                            $andDat = "API.DAT_INICIAL >= '$dat_ini 00:00:00'
                                                AND API.DAT_FINAL <= '$dat_fim 23:59:59'";
                                            break;

                                        case 'RESERVA':
                                            $andDat = "API.DAT_CADASTR >= '$dat_ini 00:00:00'
                                                AND API.DAT_CADASTR <= '$dat_fim 23:59:59'";
                                            break;

                                        case 'CHECKIN':
                                            $andDat = "API.DAT_INICIAL >= '$dat_ini 00:00:00'
                                                AND API.DAT_INICIAL <= '$dat_fim 23:59:59'";
                                            break;

                                        case 'CHECKOUT':
                                            $andDat = "API.DAT_FINAL >= '$dat_ini 00:00:00'
                                                AND API.DAT_FINAL <= '$dat_fim 23:59:59'";
                                            break;
                                    }

                                    if ($cod_tipo != '' && $cod_tipo != 0) {
                                        $andTipCredito = "INNER JOIN CAIXA AS CX ON AP.COD_PEDIDO = CX.COD_CONTRAT AND CX.COD_TIPO IN ($cod_tipo)";
                                    } else {
                                        $andTipCredito = "";
                                    }

                                    //SQL PARA CONTADORES
                                    $sqlContador = "SELECT 
                                        AP.COD_PEDIDO,
                                        AP.VALOR_PEDIDO,
                                        AP.COD_CUPOM,
                                        AP.VAL_CUPOM,
                                        AP.VAL_REFERENCIA_CHALE,
                                        API.COD_CHALE
                                        FROM adorai_pedido AP
                                        INNER JOIN adorai_pedido_items API ON API.COD_PEDIDO = AP.COD_PEDIDO
                                        $andTipCredito
                                        $andStatusCancel
                                        WHERE AP.ID_RESERVA != 0 AND
                                        $andDat
                                        $andStatusReserva
                                        $andProp
                                        $andChale
                                        $andStatus";

                                    $queryCont = mysqli_query(connTemp($cod_empresa, ''), $sqlContador);
                                    // fnEscreve($sqlContador);
                                    while ($result = mysqli_fetch_assoc($queryCont)) {
                                        //total da hospedagem
                                        $total_hospedagem += $result['VALOR_PEDIDO'];

                                        //total desconto cupom
                                        if ($result['COD_CUPOM'] != "") {
                                            $total_desconto += $result['VAL_CUPOM'];
                                        }

                                        //valor pago propriedade
                                        if ($result['VAL_REFERENCIA_CHALE'] > 0) {
                                            $total_pagoPropriedade += $result['VAL_REFERENCIA_CHALE'];
                                        } else {
                                            $sqlChale = "SELECT VAL_EFETIVO FROM ADORAI_CHALES WHERE COD_EXTERNO = " . $result['COD_CHALE'];
                                            $queryChale = mysqli_query(connTemp($cod_empresa, ''), $sqlChale);

                                            if ($qrResultChale = mysqli_fetch_assoc($queryChale)) {
                                                $total_pagoPropriedade += $qrResultChale['VAL_EFETIVO'];
                                            }
                                        }

                                        // opcionais sem cortesia
                                        $sqlOpci = "SELECT 
                                        APO.VALOR
                                        FROM adorai_pedido_opcionais AS APO
                                        INNER JOIN opcionais_adorai AS OA ON APO.COD_OPCIONAL = OA.COD_OPCIONAL 
                                        WHERE COD_PEDIDO = " . $result['COD_PEDIDO'] . " AND APO.COD_EXCLUSA IS NULL
                                        AND OA.LOG_CORTESIA = 'N' ";

                                        $array = mysqli_query(connTemp($cod_empresa, ''), $sqlOpci);
                                        if ($qrOpcionais = mysqli_fetch_assoc($array)) {
                                            $total_opcionais += $qrOpcionais['VALOR'];
                                        }
                                    }
                                    //total liquido
                                    $total_liquido = ($total_hospedagem + $total_opcionais) - $total_desconto;

                                    //total margem
                                    $total_margem = $total_liquido - $total_pagoPropriedade;

                                    $sql = "SELECT
                                    1
                                    FROM adorai_pedido AS AP
                                    INNER JOIN adorai_pedido_items AS API ON AP.COD_PEDIDO = API.COD_PEDIDO
                                    LEFT JOIN adorai_chales AS AC ON API.COD_CHALE = AC.COD_EXTERNO
                                    LEFT JOIN adorai_propriedades AS APP ON API.COD_PROPRIEDADE = APP.COD_HOTEL
                                    LEFT JOIN adorai_statuspag AS ASP ON AP.COD_STATUSPAG = ASP.COD_STATUSPAG
                                    $andTipCredito
                                    $andStatusCancel
                                    WHERE AP.ID_RESERVA != 0 AND
                                    $andDat
                                    $andStatusReserva
                                    $andProp
                                    $andChale
                                    $andStatus
                                    GROUP BY AP.COD_PEDIDO";

                                    // fnEscreve($sql);
                                    $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                    $totalitens_por_pagina = mysqli_num_rows($retorno);

                                    // fnescreve($sql);
                                    $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                                    // fnEscreve($numPaginas);
                                    //variavel para calcular o início da visualização com base na página atual
                                    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                    //totalizadores

                                    $tot_hosp = 0;
                                    $tot_marg = 0;
                                    $tot_descCupom = 0;
                                    $val_liquido = 0;
                                    $tot_liquido = 0;
                                    $tot_cafe = 0;
                                    $tot_pet = 0;
                                    $tot_fond = 0;
                                    $tot_ref = 0;
                                    $tot_dec = 0;
                                    $tot_pago = 0;

                                    // ================================================================================
                                    $sql = "SELECT
                                    AP.COD_PEDIDO,
                                    AP.ID_RESERVA,
                                    ASP.DES_STATUSPAG,
                                    AP.NOME,
                                    AP.SOBRENOME,
                                    API.DAT_INICIAL,
                                    API.DAT_FINAL,
                                    APP.NOM_PROPRIEDADE,
                                    AP.COD_CUPOM,
                                    AC.NOM_QUARTO,
                                    AC.VAL_EFETIVO,
                                    AP.VALOR,
                                    AP.VAL_CUPOM,
                                    AP.VAL_REFERENCIA_CHALE,
                                    AP.VALOR_PEDIDO
                                    FROM adorai_pedido AS AP
                                    INNER JOIN adorai_pedido_items AS API ON AP.COD_PEDIDO = API.COD_PEDIDO
                                    LEFT JOIN adorai_chales AS AC ON API.COD_CHALE = AC.COD_EXTERNO
                                    LEFT JOIN adorai_propriedades AS APP ON API.COD_PROPRIEDADE = APP.COD_HOTEL
                                    LEFT JOIN adorai_statuspag AS ASP ON AP.COD_STATUSPAG = ASP.COD_STATUSPAG
                                    $andTipCredito
                                    $andStatusCancel
                                    WHERE
                                    AP.ID_RESERVA != 0 AND
                                    $andDat
                                    $andStatusReserva
                                    $andProp
                                    $andChale
                                    $andStatus
                                    GROUP BY AP.COD_PEDIDO
                                    LIMIT $inicio, $itens_por_pagina
                                    ";
                                    // fnEscreve($sql);
                                    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                    $count = 0;

                                    while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

                                        $sqlOpci = "SELECT 
                                        APO.VALOR, 
                                        OA.ABV_OPCIONAL,
                                        OA.LOG_CORTESIA
                                        FROM adorai_pedido_opcionais AS APO
                                        INNER JOIN opcionais_adorai AS OA ON APO.COD_OPCIONAL = OA.COD_OPCIONAL 
                                        WHERE COD_PEDIDO = " . $qrListaVendas['COD_PEDIDO'] . " AND APO.COD_EXCLUSA IS NULL ";

                                        $array = mysqli_query(connTemp($cod_empresa, ''), $sqlOpci);

                                        $temCafe = $temPet = $temFound = $temRefeicao = $temDecoracao = '';
                                        $tot_opcionais = 0;
                                        while ($qrLista = mysqli_fetch_assoc($array)) {

                                            if ($qrLista['ABV_OPCIONAL'] == 'Café') {
                                                $tot_cafe = $tot_cafe + $qrLista['VALOR'];
                                            }

                                            if ($qrLista['ABV_OPCIONAL'] == 'Pets') {
                                                $tot_pet = $tot_pet + $qrLista['VALOR'];
                                            }

                                            if ($qrLista['ABV_OPCIONAL'] == 'Fondue') {
                                                $tot_fond = $tot_fond + $qrLista['VALOR'];
                                            }

                                            if ($qrLista['ABV_OPCIONAL'] == 'Refeição') {
                                                $tot_ref = $tot_ref + $qrLista['VALOR'];
                                            }

                                            if ($qrLista['ABV_OPCIONAL'] == 'Decoração') {
                                                $tot_dec = $tot_dec + $qrLista['VALOR'];
                                            }

                                            if ($qrLista['LOG_CORTESIA'] == 'N') {
                                                $tot_opcionais = $tot_opcionais + $qrLista['VALOR'];
                                            }

                                            switch ($qrLista['ABV_OPCIONAL']) {
                                                case 'Café':
                                                    $temCafe .= "<i class='fal fa-coffee' class='shortCut' data-toggle='tooltip' data-placement='top' data-original-title='Café da Manhã - " . fnValor($qrLista['VALOR'], 2) . "'></i>&nbsp";
                                                    break;
                                                case 'Pets':
                                                    $temPet .= "<i class='fal fa-paw-alt' class='shortCut' data-toggle='tooltip' data-placement='top' data-original-title='Pet - " . fnValor($qrLista['VALOR'], 2) . "'></i>&nbsp";
                                                    break;
                                                case 'Fondue':
                                                    $temFound .= "<i class='fal fa-chess-queen' class='shortCut' data-toggle='tooltip' data-placement='top' data-original-title='Found - " . fnValor($qrLista['VALOR'], 2) . "'></i>&nbsp";
                                                    break;
                                                case 'Refeição':
                                                    $temRefeicao .= "<i class='fal fa-utensils' class='shortCut' data-toggle='tooltip' data-placement='top' data-original-title='Refeição - " . fnValor($qrLista['VALOR'], 2) . "'></i>&nbsp";
                                                    break;
                                                case 'Decoração':
                                                    $temDecoracao .= "<i class='fal fa-holly-berry' class='shortCut' data-toggle='tooltip' data-placement='top' data-original-title='Decoração - " . fnValor($qrLista['VALOR'], 2) . "'></i>&nbsp";
                                                    break;
                                            }
                                        }

                                        if ($qrListaVendas['VAL_REFERENCIA_CHALE'] > 0) {
                                            $valEfetivo = $qrListaVendas['VAL_REFERENCIA_CHALE'];
                                        } else {
                                            $valEfetivo = $qrListaVendas['VAL_EFETIVO'];
                                        }


                                        $val_liquido = ($qrListaVendas['VALOR_PEDIDO'] + $tot_opcionais) - $qrListaVendas['VAL_CUPOM'];
                                        $val_margem = $val_liquido - $valEfetivo;
                                        $pct_margem = $qrListaVendas['VALOR_PEDIDO'] != 0 ? (($val_margem) / $qrListaVendas['VALOR_PEDIDO']) * 100 : 0;


                                        //CONTADORES
                                        // $tot_liquido += $val_liquido;
                                        // $tot_marg += ($val_liquido - $valEfetivo);
                                        // $tot_hosp += $qrListaVendas['VALOR_PEDIDO'];
                                        // if ($qrListaVendas['COD_CUPOM'] != '') {
                                        //     $tot_descCupom += $qrListaVendas['VAL_CUPOM'];
                                        // }

                                        // fnEscreve($qrListaVendas['COD_CUPOM']);
                                        // $tot_pago += $valEfetivo;
                                        //SQL PARA VERIFICAR SE A RESERVA FOI CANCELADA
                                        $sqlCancela = "SELECT * FROM ADORAI_CANCELAMENTOS WHERE COD_PEDIDO = " . $qrListaVendas['COD_PEDIDO'];
                                        $arrayQueryCancela = mysqli_query(connTemp($cod_empresa, ''), $sqlCancela);

                                        if ($qrBuscaCancela = mysqli_fetch_assoc($arrayQueryCancela)) {
                                            $cancelada = "background-color: #fdedec;";
                                        } else {
                                            $cancelada = "";
                                        }

                                        $cupom = fnValor(0, 2);
                                        if ($qrListaVendas['COD_CUPOM'] != "") {
                                            $cupom = fnValor($qrListaVendas['VAL_CUPOM'], 2);
                                        }

                                    ?>
                                        <tr class="addBox" style="cursor: pointer; <?= $cancelada ?>" data-url='action.do?mod=<?php echo fnEncode(2012) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idp=<?php echo fnEncode($qrListaVendas['COD_PEDIDO']) ?>&pop=true' data-title='Detalhes da Reserva'>
                                            <td><?= $qrListaVendas['COD_PEDIDO']; ?> - <br> <?= $qrListaVendas['ID_RESERVA']; ?></td>
                                            <td><?= $qrListaVendas['DES_STATUSPAG']; ?></td>
                                            <td><?= $qrListaVendas['NOME'] . " " . $qrListaVendas['SOBRENOME'] ?></td>
                                            <td><?= fnDataShort($qrListaVendas['DAT_INICIAL']) ?></td>
                                            <td><?= fnDataShort($qrListaVendas['DAT_FINAL']) ?></td>
                                            <td><?= $qrListaVendas['NOM_PROPRIEDADE']; ?></td>
                                            <td><?= $qrListaVendas['NOM_QUARTO']; ?></td>
                                            <td class="text-right"><?= fnValor($qrListaVendas['VALOR_PEDIDO'], 2); ?></td>
                                            <td class="text-right"><?= $cupom; ?></td>
                                            <td class="text-right"><?= fnValor($tot_opcionais, 2); ?></td>
                                            <td class="text-right"><?= fnValor($val_liquido, 2); ?></td>
                                            <td class="text-right"><?= fnValor($valEfetivo, 2); ?></td>
                                            <td class="text-center" width="15%">
                                                <?php
                                                echo $temCafe;
                                                echo $temPet;
                                                echo $temFound;
                                                echo $temRefeicao;
                                                echo $temDecoracao;
                                                ?>
                                            </td>
                                            <td class="text-right"><?= fnValor($val_liquido - $valEfetivo, 2); ?></td>
                                            <td><?= fnValor($pct_margem, 0); ?> %</td>
                                        </tr>
                                    <?php

                                        $count++;
                                    }

                                    ?>

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>Totalizadores</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right"><?= fnValor($total_hospedagem, 2) ?></th>
                                        <th class="text-right"><?= fnValor($total_desconto, 2) ?></th>
                                        <th class="text-right"><?= fnValor($total_opcionais, 2) ?></th>
                                        <th class="text-right"><?= fnValor($total_liquido, 2) ?></th>
                                        <th class="text-right"><?= fnValor($total_pagoPropriedade, 2) ?></th>
                                        <th></th>
                                        <th class="text-right"><?= fnValor($total_margem, 2) ?></th>
                                        <th></th>

                                    </tr>

                                    <tr>
                                        <th colspan="100">
                                            <a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
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

                            </table>

                        </div>


                    </div>

                </div>

            </div>
        </div>
        <!-- fim Portlet -->

        <div class="modal fade" id="popModal" tabindex='-1'>
            <div class="modal-dialog " style="max-width: 93% !important;">
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

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
    $(document).ready(function() {

        var numPaginas = <?php echo $numPaginas; ?>;
        if (numPaginas != 0) {
            carregarPaginacao(numPaginas);
        }

        $('#DAT_INI_GRP, #DAT_FIM_GRP').datetimepicker({
            format: 'DD/MM/YYYY'
        }).on('changeDate', function(e) {
            $(this).datetimepicker('hide');
        });

        $("#DAT_INI_GRP").on("dp.change", function(e) {
            $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
        });

        $("#DAT_FIM_GRP").on("dp.change", function(e) {
            $('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
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
                                icon: 'fal fa-check-square-o',
                                content: function() {
                                    var self = this;
                                    return $.ajax({
                                        url: "relatorios/ajxRelPagHospAdorai.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        // console.log(response);
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        //console.log(response);
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

    });

    // ajax
    $("#COD_PROPRIEDADE").change(function() {
        var codBusca = $("#COD_PROPRIEDADE").val();
        var codBusca3 = $("#COD_EMPRESA").val();
        buscaSubCat(codBusca, codBusca3);
    });

    function buscaSubCat(codprop, idEmp) {
        $.ajax({
            type: "GET",
            url: "ajxCheckoutAdorai.do?opcao=SubBusca",
            data: {
                COD_PROPRIEDADE: codprop,
                COD_EMPRESA: idEmp
            },

            beforeSend: function() {
                $('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                $("#divId_sub").html(data);
            },
            error: function() {
                $('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
            }
        });
    }

    function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "relatorios/ajxRelPagHospAdorai.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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
</script>