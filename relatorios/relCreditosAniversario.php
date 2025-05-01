<?php

//fnMostraForm();

//definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = "1";
$dat_ini = '';
$dat_fim = '';
$cod_credlot = '';

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
        $cod_univend = @$_POST['COD_UNIVEND'];
        $cod_credlot = @$_POST['COD_CREDLOT'];
        $cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
        $cod_tiporeg = @$_REQUEST['COD_TIPOREG'];

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '') {
        }
    }
}

$modulo = fnDecode($_GET['mod']);

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
        $cod_cliente_av = $qrBuscaEmpresa['COD_CLIENTE_AV'];
        $tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];

        if ($tip_retorno == 1) {
            $casasDec = 0;
        } else {
            $casasDec = 2;
        }
    }
} else {
    $cod_empresa = 0;
    $nom_empresa = "";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}


//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnMostraForm();	
//fnEscreve($lojasSelecionadas);


?>

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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Unidade de Atendimento</label>
                                        <?php include "unidadesAutorizadasComboMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Grupo de Lojas</label>
                                        <?php include "grupoLojasComboMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Região</label>
                                        <?php include "grupoRegiaoMulti.php"; ?>
                                    </div>
                                </div>

                                <?php if ($modulo == 1790) { ?>
                                    <div class="col-md-2">
                                        <div class="push20"></div>
                                        <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                    </div>
                                <?php } ?>

                            </div>

                            <?php if ($modulo == 2004) {; ?>

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label required">Persona</label>

                                            <select data-placeholder="Selecione uma ou mais unidades" multiple="multiple" name="COD_CREDLOT[]" id="COD_CREDLOT" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
                                                <?php

                                                $ARRAY_VENDEDOR1 = array(
                                                    'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa in($cod_empresa,3)",
                                                    'cod_empresa' => $cod_empresa,
                                                    'conntadm' => $connAdm->connAdm(),
                                                    'IN' => 'N',
                                                    'nomecampo' => '',
                                                    'conntemp' => '',
                                                    'SQLIN' => ""
                                                );
                                                $ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);
                                                $arrayAutorizado = explode(",", $_SESSION["SYS_COD_UNIVEND"]);

                                                $sql = "SELECT B.DES_PERSONA,
                                            B.COD_PERSONA,
                                            A.QTD_PESCLASS,
                                            A.VAL_CREDITO,
                                            B.LOG_PUBLICO,
                                            (A.QTD_PESCLASS*A.VAL_CREDITO) AS TOT_CREDITO,
                                            A.DAT_CADASTR,
                                            B.COD_UNIVEND,
                                            A.DAT_VALIDADE,
                                            B.LOG_ATIVO,
                                            A.COD_CREDLOT
                                            FROM PERSONA B
                                            INNER JOIN CREDITOS_LOT A ON A.COD_PERSONAS=B.COD_PERSONA AND A.cod_empresa=$cod_empresa
                                            WHERE B.COD_EMPRESA = $cod_empresa
                                            AND B.LOG_ATIVO = 'S'
                                            ORDER BY A.DAT_CADASTR DESC";

                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                                while ($qrListaCamp = mysqli_fetch_assoc($arrayQuery)) {
                                                    $autorizado = "N";
                                                    if (recursive_array_search($qrListaCamp['COD_UNIVEND'], $arrayAutorizado) !== false) {
                                                        $autorizado = "S";
                                                    }

                                                    if ($_SESSION["SYS_COD_EMPRESA"] == 2 || $_SESSION["SYS_COD_EMPRESA"] == 3) {
                                                        $autorizado = "S";
                                                    }

                                                    if ($autorizado == "S" || $qrListaCamp['LOG_PUBLICO'] == 'S') {
                                                        $selected = '';
                                                        if (is_array($cod_credlot) && in_array($qrListaCamp['COD_CREDLOT'], $cod_credlot)) {
                                                            $selected = 'selected';
                                                        }
                                                        echo "
                                                        <option value='" . $qrListaCamp['COD_CREDLOT'] . "'" . $selected . ">" . ucfirst($qrListaCamp['DES_PERSONA']) . " " . fnDataFull($qrListaCamp['DAT_CADASTR']) . "</option> 
                                                         ";
                                                    }
                                                }

                                                ?>
                                            </select>

                                            <div class="help-block with-errors"></div>
                                            <a class="btn btn-default btn-sm" id="iAlll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square-o" aria-hidden="true"></i> selecionar todos</a>&nbsp;
                                            <a class="btn btn-default btn-sm" id="iNonee" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>

                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="push20"></div>
                                        <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                    </div>

                                </div>

                            <?php } ?>


                        </fieldset>

                        <div class="push20"></div>

                        <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
                        <input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                        <input type="hidden" name="mod" id="mod" value="<?php echo $modulo; ?>">

                        <div class="push5"></div>

                    </form>

                </div>
            </div>
        </div>

        <div class="push30"></div>



        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="row text-center">

                    <?php
                    // Filtro por Grupo de Lojas
                    include "filtroGrupoLojas.php";

                    if (is_array($cod_credlot)) {
                        $creditoSelecionados = implode(",", $cod_credlot);
                    } else {

                        $creditoSelecionados = '';
                    }

                    if ($modulo == 2004) {

                        if ($cod_credlot != '' && $cod_credlot != 0) {
                            $sql = "SELECT 
                    COUNT(distinct b.COD_CLIENTE) QTD_CLIENTE, 
                    SUM(val_credito) AS TOT_CREDCAMPANHA, 
                    SUM(val_saldo)TOT_CAMPANHA, 
                    SUM((SELECT ifnull(SUM(AA.VAL_SALDO),0) 
                        FROM CREDITOSDEBITOS AA,empresas c 
                        WHERE AA.COD_CLIENTE=A.COD_CLIENTE AND
                        C.COD_EMPRESA=AA.COD_EMPRESA AND 
                        AA.TIP_CREDITO='C' AND 
                        AA.COD_STATUSCRED=1 AND 
                        AA.tip_campanha = c.TIP_CAMPANHA 
                        AND (DATE_FORMAT(AA.DAT_EXPIRA, '%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(AA.LOG_EXPIRA='N')) ))AS TOT_CREDITO_DISPONIVEL_GERAL 
                    FROM creditosdebitos a 
                    INNER JOIN clientes b ON b.COD_CLIENTE = a.COD_CLIENTE 
                    INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=b.COD_UNIVEND 
                    WHERE a.cod_cliente=b.cod_cliente AND 
                    a.cod_empresa=b.cod_empresa AND 
                    a.cod_empresa=$cod_empresa AND 
                    a.cod_credlot IN($creditoSelecionados) AND
                    a.COD_UNIVEND IN($lojasSelecionadas)";
                        } else {
                            $sql == '';
                        }
                    } else {
                        $sql = "SELECT count(distinct COD_CLIENTE) QTD_CLIENTE, 
                        sum(val_credito) AS TOT_CREDCAMPANHA, 
                        sum(SALDO_CAMPANHA) AS TOT_CAMPANHA, 
                        sum(CREDITO_DISPONIVEL_GERAL) AS TOT_CREDITO_DISPONIVEL_GERAL 
                        FROM (
                            SELECT b.COD_CLIENTE, 
                            b.NOM_CLIENTE, 
                            a.COD_UNIVEND, 
                            uni.NOM_FANTASI, 
                            b.NUM_CELULAR, 
                            b.DAT_NASCIME, 
                            DATE_FORMAT (a.dat_expira, '%d/%m/%Y') DATA_VENCIMENTO, 
                            SUM(a.val_credito) AS val_credito, 
                            SUM(a.val_saldo) as SALDO_CAMPANHA, 
                            (SELECT ifnull(SUM(AA.VAL_SALDO),0) FROM CREDITOSDEBITOS AA,empresas c WHERE AA.COD_CLIENTE=A.COD_CLIENTE AND C.COD_EMPRESA=AA.COD_EMPRESA AND AA.TIP_CREDITO='C' AND AA.COD_STATUSCRED=1 AND AA.tip_campanha = c.TIP_CAMPANHA AND (DATE_FORMAT(AA.DAT_EXPIRA, '%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(AA.LOG_EXPIRA='N')) )AS CREDITO_DISPONIVEL_GERAL 
                            FROM creditosdebitos a INNER JOIN clientes b ON b.COD_CLIENTE = a.COD_CLIENTE 
                            INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=b.COD_UNIVEND 
                            INNER JOIN creditos_lot d ON d.cod_credlot = a.cod_credlot AND ((d.cod_empresa=219 AND d.cod_personas IN(104,105,106)) OR (d.cod_empresa=306 AND d.cod_personas IN(282)))
                            WHERE a.cod_cliente=b.cod_cliente AND a.cod_empresa=b.cod_empresa AND a.cod_empresa=$cod_empresa AND a.cod_credlot>0 AND a.COD_UNIVEND IN($lojasSelecionadas) AND 
                            DATE(a.dat_expira) > DATE(NOW())
                            GROUP BY b.COD_CLIENTE
                            ) totalizador";
                    }

                    if ($sql != '') {
                        $query = mysqli_query(connTemp($cod_empresa, ''), $sql);
                        $qrBusca = mysqli_fetch_assoc($query);
                    }

                    ?>
                    <div class="form-group text-center col-md-1 col-lg-1"></div>

                    <div class="form-group text-center col-md-2 col-lg-2">

                        <div class="push20"></div>

                        <div class="form-group">
                            <input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="QTD_CLIENTES" id="QTD_CLIENTES" maxlength="100" value="<?= fnValor(@$qrBusca['QTD_CLIENTE'], 0) ?>">
                            <label for="inputName" class="control-label"><b>Qtd. Clientes</b></label>
                            <div class="help-block with-errors"></div>
                        </div>


                        <div class="push20"></div>

                    </div>

                    <div class="form-group text-center col-md-2 col-lg-2">

                        <div class="push20"></div>

                        <div class="form-group">
                            <input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="TOT_BONUS_CONCEDIDO" id="TOT_BONUS_CONCEDIDO" maxlength="100" value="R$ <?= fnValor(@$qrBusca['TOT_CREDCAMPANHA'], 2) ?>">
                            <label for="inputName" class="control-label"><b>Tot. Bônus Concedido</b></label>
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="push20"></div>

                    </div>

                    <div class="form-group text-center col-md-2 col-lg-2">

                        <div class="push20"></div>

                        <div class="form-group">
                            <input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="TOT_BONUS_SEM_RESGATE" id="TOT_BONUS_SEM_RESGATE" maxlength="100" value="R$ <?= fnValor(@$qrBusca['TOT_CAMPANHA'], 2) ?>">
                            <label for="inputName" class="control-label"><b>Tot. Bônus Sem Resgate</b></label>
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="push20"></div>

                    </div>

                    <div class="form-group text-center col-md-2 col-lg-2">

                        <div class="push20"></div>

                        <div class="form-group">
                            <input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="TOT_RESGATADO" id="TOT_RESGATADO" maxlength="100" value="R$ <?= fnValor(@$qrBusca['TOT_CREDCAMPANHA'] - @$qrBusca['TOT_CAMPANHA'], 2) ?>">
                            <label for="inputName" class="control-label"><b>Tot. Resgatado</b></label>
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="push20"></div>

                    </div>

                    <div class="form-group text-center col-md-2 col-lg-2">

                        <div class="push20"></div>

                        <div class="form-group">
                            <input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="TOT_DISPONIVEL_PARA_RESGATE" id="TOT_DISPONIVEL_PARA_RESGATE" maxlength="100" value="R$ <?= fnValor(@$qrBusca['TOT_CREDITO_DISPONIVEL_GERAL'], 2) ?>">
                            <label for="inputName" class="control-label"><b>Tot. Disponível para Resgate</b></label>
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="push20"></div>

                    </div>

                </div>

            </div>

        </div>

        <div class="push30"></div>

        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">
                    <div class="row">
                        <div class="col-md-12">

                            <table class="table table-bordered table-hover tablesorter buscavel">

                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-group">
                                                <label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Cliente</b></small></label>
                                                <input type="hidden" class="form-control input-sm" name="CLIENTE" id="CLIENTE" maxlength="100" value="">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </th>

                                        <th>
                                            <div class="form-group">
                                                <label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Data Niver</b></small></label>
                                                <input type="hidden" class="form-control input-sm" name="DATA_NIVER" id="DATA_NIVER" maxlength="100" value="">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </th>

                                        <th width="10%">
                                            <div class="form-group">
                                                <label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Telefone</b></small></label>
                                                <input type="hidden" class="form-control input-sm" name="TELEFONE" id="TELEFONE" maxlength="100" value="">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </th>

                                        <th>
                                            <div class="form-group">
                                                <label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Unidade</b></small></label>
                                                <input type="hidden" class="form-control input-sm" name="UNIDADE" id="UNIDADE" maxlength="100" value="">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </th>

                                        <th>
                                            <div class="form-group">
                                                <label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Vencimento</b></small></label>
                                                <input type="hidden" class="form-control input-sm" name="VENCIMENTO" id="VENCIMENTO" maxlength="100" value="">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </th>

                                        <th class="text-right">
                                            <div class="form-group">
                                                <label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Bônus</b></small></label>
                                                <input type="hidden" class="form-control input-sm" name="BONUS" id="BONUS" maxlength="100" value="">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </th>

                                        <th class="text-right">
                                            <div class="form-group">
                                                <label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Saldo Bônus</b></small></label>
                                                <input type="hidden" class="form-control input-sm" name="SALDO_BONUS" id="SALDO_BONUS" maxlength="100" value="">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </th>

                                        <th class="text-right">
                                            <div class="form-group">
                                                <label for="inputName" style="font-size: 16px;" class="control-label"><small><b>Saldo Total</b></small></label>
                                                <input type="hidden" class="form-control input-sm" name="SALDO_TOTAL" id="SALDO_TOTAL" maxlength="100" value="">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody id="relatorioConteudo">

                                    <?php

                                    $andCredlot = "AND a.COD_CREDLOT IN ($creditoSelecionados)";

                                    // Filtro por Grupo de Lojas
                                    include "filtroGrupoLojas.php";


                                    // contador ===================
                                    if ($modulo == 2004) {

                                        if ($cod_credlot != '' && $cod_credlot != 0) {
                                            # code...

                                            $sql = "SELECT 1
                                                    FROM creditosdebitos a
                                                    INNER JOIN clientes b ON b.COD_CLIENTE = a.COD_CLIENTE
                                                    INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=b.COD_UNIVEND
                                                    WHERE a.cod_cliente=b.cod_cliente 
                                                    AND a.cod_empresa=b.cod_empresa 
                                                    AND a.cod_empresa=$cod_empresa 
                                                    AND a.cod_credlot>0 
                                                    AND a.val_saldo>0
                                                    $andCredlot
                                                    AND a.COD_UNIVEND IN($lojasSelecionadas)
                                                    GROUP BY a.cod_cliente
                                                    ORDER BY b.NOM_CLIENTE";
                                        } else {
                                            $sql = '';
                                        }
                                    } else {
                                        $sql = "SELECT 1
                                                    FROM creditosdebitos a
                                                    INNER JOIN clientes b ON b.COD_CLIENTE = a.COD_CLIENTE
                                                    INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=b.COD_UNIVEND
                                                    INNER JOIN creditos_lot d ON d.cod_credlot = a.cod_credlot AND ((d.cod_empresa=219 AND d.cod_personas IN(104,105,106)) OR (d.cod_empresa=306 AND d.cod_personas IN(282)))
                                                    WHERE a.cod_cliente=b.cod_cliente 
                                                    AND a.cod_empresa=b.cod_empresa 
                                                    AND a.cod_empresa=$cod_empresa 
                                                    AND a.cod_credlot>0 
                                                    AND a.val_saldo>0
                                                    AND a.COD_UNIVEND IN($lojasSelecionadas) 
                                                    AND DATE(a.dat_expira) > DATE(NOW())
                                                    GROUP BY a.cod_cliente
                                                    ORDER BY b.NOM_CLIENTE";
                                    }

                                    // fnEscreve($sql);

                                    if ($sql != '') {
                                        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                        $totalitens_por_pagina = mysqli_num_rows($retorno);
                                    }

                                    // fnescreve($sql);

                                    @$numPaginas = ceil($totalitens_por_pagina / @$itens_por_pagina);

                                    // fnEscreve($numPaginas);
                                    //variavel para calcular o início da visualização com base na página atual
                                    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                    // fnEscreve($inicio);
                                    // fnEscreve($totalitens_por_pagina);
                                    // ================================================================================

                                    if ($modulo == 2004) {

                                        if ($cod_credlot != '' && $cod_credlot != 0) {

                                            $sql = "SELECT b.COD_CLIENTE, 
                                                        b.NOM_CLIENTE, 
                                                        a.COD_UNIVEND, 
                                                        uni.NOM_FANTASI, 
                                                        b.NUM_CELULAR,
                                                        b.DAT_NASCIME,
                                                        DATE_FORMAT (a.dat_expira, '%d/%m/%Y') DATA_VENCIMENTO, 
                                                        SUM(val_credito) AS val_credito, 
                                                        SUM(val_saldo)SALDO_CAMPANHA,
                                                        (SELECT ifnull(SUM(AA.VAL_SALDO),0)
                                                        FROM CREDITOSDEBITOS AA,empresas c
                                                        WHERE AA.COD_CLIENTE=A.COD_CLIENTE 
                                                        AND C.COD_EMPRESA=AA.COD_EMPRESA 
                                                        AND AA.TIP_CREDITO='C' 
                                                        AND AA.COD_STATUSCRED=1
                                                        AND AA.tip_campanha = c.TIP_CAMPANHA 
                                                        AND (DATE_FORMAT(AA.DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(AA.LOG_EXPIRA='N'))
                                                        )AS CREDITO_DISPONIVEL_GERAL
                                                        FROM creditosdebitos a
                                                        INNER JOIN clientes b ON b.COD_CLIENTE = a.COD_CLIENTE
                                                        INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=b.COD_UNIVEND
                                                        WHERE a.cod_cliente=b.cod_cliente 
                                                        AND a.cod_empresa=b.cod_empresa 
                                                        AND a.cod_empresa=$cod_empresa 
                                                        $andCredlot
                                                        AND a.COD_UNIVEND IN($lojasSelecionadas)
                                                        GROUP BY a.cod_cliente
                                                        ORDER BY b.NOM_CLIENTE
                                                        LIMIT $inicio, $itens_por_pagina";
                                        } else {
                                            $sql = '';
                                        }
                                    } else {

                                        $sql = "SELECT b.COD_CLIENTE, 
                                                    b.NOM_CLIENTE, 
                                                    a.COD_UNIVEND, 
                                                    uni.NOM_FANTASI, 
                                                    b.NUM_CELULAR,
                                                    b.DAT_NASCIME, 
                                                    DATE_FORMAT (a.dat_expira, '%d/%m/%Y') DATA_VENCIMENTO, 
                                                    SUM(a.val_credito) AS val_credito, 
                                                    SUM(a.val_saldo)SALDO_CAMPANHA,
                                                    (SELECT ifnull(SUM(AA.VAL_SALDO),0)
                                                    FROM CREDITOSDEBITOS AA,empresas c
                                                    WHERE AA.COD_CLIENTE=A.COD_CLIENTE 
                                                    AND C.COD_EMPRESA=AA.COD_EMPRESA 
                                                    AND AA.TIP_CREDITO='C' 
                                                    AND AA.COD_STATUSCRED=1
                                                    AND AA.tip_campanha = c.TIP_CAMPANHA 
                                                    AND (DATE_FORMAT(AA.DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(AA.LOG_EXPIRA='N'))
                                                    )AS CREDITO_DISPONIVEL_GERAL
                                                    FROM creditosdebitos a
                                                    INNER JOIN clientes b ON b.COD_CLIENTE = a.COD_CLIENTE
                                                    INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=b.COD_UNIVEND
                                                    INNER JOIN creditos_lot d ON d.cod_credlot = a.cod_credlot AND ((d.cod_empresa=219 AND d.cod_personas IN(104,105,106)) OR (d.cod_empresa=306 AND d.cod_personas IN(282)))
                                                    WHERE a.cod_cliente=b.cod_cliente 
                                                    AND a.cod_empresa=b.cod_empresa 
                                                    AND a.cod_empresa=$cod_empresa 
                                                    AND a.cod_credlot>0
                                                    AND a.COD_UNIVEND IN($lojasSelecionadas) 
                                                    AND DATE(a.dat_expira) > DATE(NOW())
                                                    GROUP BY a.cod_cliente
                                                    ORDER BY b.NOM_CLIENTE
                                                    LIMIT $inicio, $itens_por_pagina";
                                    }

                                    //fnEscreve($sql);

                                    if ($sql != '') {
                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                    }


                                    $countLinha = 0;
                                    while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

                                    ?>
                                        <tr>
                                            <td><a href="action.do?mod=<?php echo fnEncode(1081); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?= fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?= $qrListaVendas['NOM_CLIENTE']; ?></a></td>
                                            <td><?= $qrListaVendas['DAT_NASCIME'] ?></td>
                                            <td><?= fnmasktelefone($qrListaVendas['NUM_CELULAR']) ?></td>
                                            <td><?= $qrListaVendas['NOM_FANTASI'] ?></td>
                                            <td><?= $qrListaVendas['DATA_VENCIMENTO'] ?></td>
                                            <td class="text-right"><b><?= fnValor($qrListaVendas['val_credito'], 2) ?></b></td>
                                            <td class="text-right"><b><?= fnValor($qrListaVendas['SALDO_CAMPANHA'], 2) ?></b></td>
                                            <td class="text-right"><b><?= fnValor($qrListaVendas['CREDITO_DISPONIVEL_GERAL'], 2) ?></b></td>
                                        </tr>
                                    <?php
                                        $countLinha++;
                                    }

                                    ?>

                                </tbody>

                                <tfoot>

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
    </div>

</div>

<div class="push20"></div>

<script>
    $(document).ready(function() {

        $.tablesorter.addParser({
            id: "moeda",
            is: function(s) {
                return true;
            },
            format: function(s) {
                return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9,]/g), ""));
            },
            type: "numeric"
        });

        var numPaginas = <?php echo $numPaginas; ?>;
        if (numPaginas != 0) {
            carregarPaginacao(numPaginas);
        }

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
                                        url: "relatorios/ajxCreditosAniversario.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&credlot=<?= $creditoSelecionados ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
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

    });

    function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "relatorios/ajxCreditosAniversario.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&credlot=<?= $creditoSelecionados ?>",
            data: $('#formulario').serialize(),
            beforeSend: function() {
                $('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
            },
            success: function(data) {
                $("#relatorioConteudo").html(data);
                // $(".tablesorter").trigger("updateAll");
            },
            error: function() {
                $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });
    }
</script>