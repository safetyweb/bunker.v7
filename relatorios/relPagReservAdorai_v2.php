<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$hashLocal = "";
$hoje = "";
$dias30 = "";
$des_owner = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$cod_propriedade = "";
$tip_credito = "";
$cod_chale = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$lojasSelecionadas = "";
$sqlPropriedades = "";
$query = "";
$vlPropriedades = "";
$qrResult = "";
$sqlHotel = "";
$arrayHotel = [];
$qrHotel = "";
$sqlTip = "";
$arrayTip = [];
$qrTip = "";
$and_propriedade = "";
$and_chale = "";
$andTip = "";
$andTipCred = "";
$andowner = "";
$andOwn = "";
$val_creditos = "";
$val_debitos = "";
$qrListaVendas = "";
$exibe_valdebitos = "";
$exibe_valcreditos = "";
$tdDropMenu = "";
$chkConciliado = "";
$qrLista = "";
$nomBanco = "";
$propriedade = "";
$vl = "";
$countFooter = "";
$tipo = "";
$vl2 = "";
$content = "";


//echo fnDebug('true');

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = "1";

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-01'));

$des_owner = "9999";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
        $dat_ini = fnDataSql(@$_POST['DAT_INI']);
        $dat_fim = fnDataSql(@$_POST['DAT_FIM']);
        $cod_propriedade = fnLimpaCampo(@$_POST['COD_PROPRIEDADE']);
        $tip_credito = fnLimpaCampo(@$_POST['TIP_CREDITO']);
        $cod_chale = fnLimpaCampo(@$_POST['COD_CHALE']);
        $des_owner = fnLimpaCampo(@$_REQUEST['DES_OWNER']);

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

//fnMostraForm();	
//fnEscreve($lojasSelecionadas);

$sqlPropriedades = "SELECT COD_PROPRIEDADE, COD_HOTEL, NOM_PROPRIEDADE FROM adorai_propriedades WHERE COD_EXCLUSA = 0";
$query = mysqli_query(connTemp($cod_empresa, ''), $sqlPropriedades);

$vlPropriedades = array();
while ($qrResult = mysqli_fetch_assoc($query)) {
    $vlPropriedades['PROPRIEDADES'][$qrResult['COD_HOTEL']] = array(
        'NOM_PROPRIEDADE' => $qrResult['NOM_PROPRIEDADE'],
        'TOT_DEBITO' => 0,
        'TOT_CREDITO' => 0
    );
}


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

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label ">Propriedades</label>
                                        <select data-placeholder="Selecione os hotéis" name="COD_PROPRIEDADE" id="COD_PROPRIEDADE" class="chosen-select-deselect">
                                            <option value=""></option>
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
                                        <label for="inputName" class="control-label required">Data Inicial</label>

                                        <div class="input-group date datePicker" id="DAT_INI_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?= fnFormatDate($dat_ini) ?>" required />
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
                                            <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?= fnFormatDate($dat_fim) ?>" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label ">Tipo Lançamento</label>
                                        <select data-placeholder="Selecione os hotéis" name="TIP_CREDITO" id="TIP_CREDITO" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <?php
                                            $sqlTip = "SELECT * FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa AND TIP_OPERACAO = 'C'";
                                            $arrayTip = mysqli_query(connTemp($cod_empresa, ''), $sqlTip);

                                            while ($qrTip = mysqli_fetch_assoc($arrayTip)) {
                                            ?>
                                                <option value="<?= $qrTip['COD_TIPO'] ?>"><?= $qrTip['ABV_TIPO'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <script>
                                            $("#TIP_CREDITO").val("<?php echo $tip_credito; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Origem</label>
                                        <select data-placeholder="Selecione o Tipo" name="DES_OWNER" id="DES_OWNER" class="chosen-select-deselect" required>
                                            <option value="9999">Todos</option>
                                            <option value="A">Adorai</option>
                                            <option value="F">Foco</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                        <script>
                                            $("#DES_OWNER").val("<?php echo $des_owner; ?>").trigger("chosen:updated");
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
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">


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

                            <table class="table table-bordered table-hover tablesorter buscavel">

                                <thead>
                                    <tr>
                                        <th class="{sorter:false}"></th>
                                        <th>Cód. Reserva</th>
                                        <th>Chalé</th>
                                        <th>Propriedade</th>
                                        <th>Origem</th>
                                        <th>Data da Reserva</th>
                                        <th class="text-center">Data do Lançamento</th>
                                        <th class="text-left">Lançamento</th>
                                        <th class="text-left">Conta Bancária</th>
                                        <th class="text-right">Créditos</th>
                                        <th class="text-right">Débitos</th>
                                        <th>Conciliado</th>
                                        <th class="{sorter:false}" width='40'></th>
                                    </tr>
                                </thead>

                                <tbody id="relatorioConteudo">

                                    <?php

                                    if ($cod_propriedade == "") {
                                        $and_propriedade = " ";
                                    } else {
                                        $and_propriedade = "AND UNV.COD_EXTERNO = $cod_propriedade";
                                    }
                                    if ($cod_chale != '' && $cod_chale != 0) {
                                        $and_chale = "AND AC.COD_EXTERNO = $cod_chale";
                                    } else {
                                        $and_chale = " ";
                                    }

                                    if ($tip_credito != '' && $tip_credito != 0) {
                                        $andTip = "AND cx.COD_TIPO = $tip_credito";
                                        $andTipCred = "AND TC.COD_TIPO = $tip_credito";
                                    } else {
                                        $andTip = " ";
                                        $andTipCred = " ";
                                    }

                                    if ($des_owner != "9999") {
                                        $andowner = "AND CX.DES_OWNER = '$des_owner'";
                                        $andOwn = "AND C.DES_OWNER = '$des_owner'";
                                    } else {
                                        $andowner = "";
                                        $andOwn = "";
                                    }

                                    $sql = "SELECT
                                                (SELECT SUM(C.val_credito)
                                                FROM caixa AS c
                                                INNER JOIN adorai_pedido AS p ON c.cod_contrat = p.COD_PEDIDO
                                                INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = c.COD_TIPO
                                                WHERE p.COD_EMPRESA = 274
                                                    AND p.COD_PEDIDO = AP.COD_PEDIDO
                                                    AND c.cod_contrat = AP.COD_PEDIDO
                                                    AND TC.TIP_OPERACAO = 'C'
                                                    $andTipCred
                                                    $andOwn
                                                    AND C.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' ) AS VAL_CREDITOS,

                                                (SELECT SUM(val_credito)
                                                FROM caixa AS c
                                                INNER JOIN adorai_pedido AS p ON c.cod_contrat = p.COD_PEDIDO
                                                INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = c.COD_TIPO
                                                WHERE p.COD_EMPRESA = 274
                                                    AND p.COD_PEDIDO = AP.COD_PEDIDO
                                                    AND c.cod_contrat = AP.COD_PEDIDO
                                                    AND TC.TIP_OPERACAO = 'D'
                                                    $andTipCred
                                                    $andOwn
                                                    AND C.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' ) AS VAL_DEBITOS,
                                                    AC.NOM_QUARTO,
                                                    UNV.NOM_UNIVEND,
                                                    UNV.NOM_FANTASI,
                                                    tp.ABV_TIPO,
                                                    CX.DAT_CADASTR AS DAT_LANCAMENTO,
                                                    AP.DAT_CADASTR AS DAT_RESERVA,
                                                    CX.COD_CONTRAT,
                                                    CX.DES_OWNER,
                                                    A.LOG_CONCILIADO,
                                                    CB.NOM_BANCO
                                                FROM caixa AS CX
                                                INNER JOIN tip_credito AS tp ON tp.COD_TIPO = cx.COD_TIPO
                                                INNER JOIN adorai_pedido AS A ON A.COD_PEDIDO = CX.COD_CONTRAT
                                                INNER JOIN adorai_pedido_items AS AP ON AP.COD_PEDIDO = CX.COD_CONTRAT
                                                INNER JOIN adorai_chales AS AC ON AC.COD_EXTERNO = AP.COD_CHALE
                                                INNER JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = AP.COD_PROPRIEDADE
                                                LEFT JOIN CONTABANCARIA AS CB ON CB.COD_CONTA = CX.COD_CONTA
                                                WHERE AP.COD_EMPRESA = 274
                                                AND CX.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                                                $and_propriedade
                                                $and_chale
                                                $andTip
                                                $andowner
                                                GROUP BY CX.COD_CONTRAT
                                                ORDER BY cx.DAT_CADASTR, AC.COD_EXTERNO, UNV.COD_EXTERNO";
                                    //fnEscreve($sql);
                                    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                    $count = 0;
                                    $val_creditos = 0;
                                    $val_debitos = 0;

                                    while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
                                        //fnEscreveArray($qrListaVendas);
                                        if ($qrListaVendas['VAL_DEBITOS'] != "" && $qrListaVendas['VAL_DEBITOS'] != 0) {
                                            $exibe_valdebitos = "- R$ " .  fnValor($qrListaVendas['VAL_DEBITOS'], 2);
                                        } else {
                                            $exibe_valdebitos = "";
                                        }

                                        if ($qrListaVendas['VAL_CREDITOS'] != "" && $qrListaVendas['VAL_CREDITOS'] != 0) {
                                            $exibe_valcreditos = "R$ " .  fnValor($qrListaVendas['VAL_CREDITOS'], 2);
                                        } else {
                                            $exibe_valcreditos = "";
                                        }

                                        if ($qrListaVendas['LOG_CONCILIADO'] == 'N') {
                                            $tdDropMenu = "
												<td width='40' class='text-center'>
													<div class='btn-group dropdown dropleft'>
                                                        <a href='javascript:void(0)' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                        <span style='opacity: 0.4;' class='fal fa-ellipsis-v fa-2x'></span>
                                                        </a>
                                                        <ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu'>
                                                        <li><a href='javascript:void(0)' onclick='concilia(" . $qrListaVendas['COD_CONTRAT'] . ")' >Conciliar</a></li>
                                                        </ul>
													</div>
												</td>";

                                            $chkConciliado = "";
                                        } else {
                                            $tdDropMenu = "<td width='40'></td>";
                                            $chkConciliado = "<span class='fal fa-check'></span>";
                                        }
                                    ?>
                                        <tr>
                                            <td style='cursor:pointer' data-url='action.do?mod=<?= fnEncode(2012) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($qrListaVendas['COD_CONTRAT']) ?>&pop=true' class='addBox' data-title='Detalhes da Reserva'></td>
                                            <td style='cursor:pointer' data-url='action.do?mod=<?= fnEncode(2012) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($qrListaVendas['COD_CONTRAT']) ?>&pop=true' class='addBox' data-title='Detalhes da Reserva'><?= $qrListaVendas['COD_CONTRAT']; ?></td>
                                            <td style='cursor:pointer' data-url='action.do?mod=<?= fnEncode(2012) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($qrListaVendas['COD_CONTRAT']) ?>&pop=true' class='addBox' data-title='Detalhes da Reserva'><?= $qrListaVendas['NOM_QUARTO']; ?></td>
                                            <td style='cursor:pointer' data-url='action.do?mod=<?= fnEncode(2012) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($qrListaVendas['COD_CONTRAT']) ?>&pop=true' class='addBox' data-title='Detalhes da Reserva'><?= $qrListaVendas['NOM_FANTASI']; ?></td>
                                            <td style='cursor:pointer' data-url='action.do?mod=<?= fnEncode(2012) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($qrListaVendas['COD_CONTRAT']) ?>&pop=true' class='addBox text-center' data-title='Detalhes da Reserva'><?= $qrListaVendas['DES_OWNER']; ?></td>
                                            <td style='cursor:pointer' data-url='action.do?mod=<?= fnEncode(2012) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($qrListaVendas['COD_CONTRAT']) ?>&pop=true' class='addBox' data-title='Detalhes da Reserva'><?= fnDataShort($qrListaVendas['DAT_RESERVA']); ?></td>
                                            <td style='cursor:pointer' data-url='action.do?mod=<?= fnEncode(2012) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($qrListaVendas['COD_CONTRAT']) ?>&pop=true' class='addBox text-center' data-title='Detalhes da Reserva'><?= fnDataShort($qrListaVendas['DAT_LANCAMENTO']); ?></td>
                                            <td style='cursor:pointer' data-url='action.do?mod=<?= fnEncode(2012) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($qrListaVendas['COD_CONTRAT']) ?>&pop=true' class='addBox text-left' data-title='Detalhes da Reserva'><?= $qrListaVendas['ABV_TIPO']; ?></td>
                                            <td style='cursor:pointer' data-url='action.do?mod=<?= fnEncode(2012) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($qrListaVendas['COD_CONTRAT']) ?>&pop=true' class='addBox text-left' data-title='Detalhes da Reserva'><?= $qrListaVendas['NOM_BANCO']; ?></td>
                                            <td style='cursor:pointer' data-url='action.do?mod=<?= fnEncode(2012) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($qrListaVendas['COD_CONTRAT']) ?>&pop=true' class='addBox text-right' data-title='Detalhes da Reserva'><?= $exibe_valcreditos; ?></td>
                                            <td style='cursor:pointer' data-url='action.do?mod=<?= fnEncode(2012) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($qrListaVendas['COD_CONTRAT']) ?>&pop=true' class='addBox text-right' data-title='Detalhes da Reserva' style="color: red;"><?= $exibe_valdebitos; ?></td>
                                            <td class="text-center"><?= $chkConciliado ?></td>
                                            <?= $tdDropMenu; ?>
                                        </tr>
                                    <?php
                                        $val_creditos += $qrListaVendas['VAL_CREDITOS'];
                                        $val_debitos += $qrListaVendas['VAL_DEBITOS'];
                                        $count++;
                                    }

                                    $sql = "SELECT DISTINCT
                                                (SELECT SUM(C.val_credito)
                                                FROM caixa AS c
                                                INNER JOIN adorai_pedido AS p ON c.cod_contrat = p.COD_PEDIDO
                                                INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = c.COD_TIPO
                                                WHERE p.COD_EMPRESA = 274
                                                    AND p.COD_PEDIDO = AP.COD_PEDIDO
                                                    AND c.cod_contrat = AP.COD_PEDIDO
                                                    AND TC.TIP_OPERACAO = 'C'
                                                    $andTipCred
                                                    $andOwn
                                                    AND C.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' ) AS VAL_CREDITOS,

                                                (SELECT SUM(val_credito)
                                                FROM caixa AS c
                                                INNER JOIN adorai_pedido AS p ON c.cod_contrat = p.COD_PEDIDO
                                                INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = c.COD_TIPO
                                                WHERE p.COD_EMPRESA = 274
                                                    AND p.COD_PEDIDO = AP.COD_PEDIDO
                                                    AND c.cod_contrat = AP.COD_PEDIDO
                                                    AND TC.TIP_OPERACAO = 'D'
                                                    $andTipCred
                                                    $andOwn
                                                    AND C.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' ) AS VAL_DEBITOS,
                                                    AC.COD_HOTEL,
                                                    tp.ABV_TIPO,
                                                    CB.NOM_BANCO
                                                FROM caixa AS CX
                                                INNER JOIN tip_credito AS tp ON tp.COD_TIPO = cx.COD_TIPO
                                                INNER JOIN adorai_pedido_items AS AP ON AP.COD_PEDIDO = CX.COD_CONTRAT
                                                INNER JOIN adorai_chales AS AC ON AC.COD_EXTERNO = AP.COD_CHALE
                                                INNER JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = AP.COD_PROPRIEDADE
                                                LEFT JOIN CONTABANCARIA AS CB ON CB.COD_CONTA = CX.COD_CONTA
                                                WHERE AP.COD_EMPRESA = 274
                                                AND CX.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
                                                $andTip
                                                $andowner";
                                    //fnescreve($sql);
                                    $query = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                    // $vlPropriedades[$qrResult['COD_HOTEL']] = array(
                                    //     'NOM_PROPRIEDADE' => $qrResult['NOM_PROPRIEDADE'],
                                    //     'TOT_LANCAME' => 0,
                                    //     'TOT_DEBITO'=> 0,
                                    //     'TOT_CREDITO' => 0,
                                    //     'DES_CONTAB' => ''
                                    // );

                                    while ($qrLista = mysqli_fetch_assoc($query)) {

                                        $vlPropriedades['PROPRIEDADES'][$qrLista['COD_HOTEL']]['TOT_CREDITO'] += $qrLista['VAL_CREDITOS'];
                                        $vlPropriedades['PROPRIEDADES'][$qrLista['COD_HOTEL']]['TOT_DEBITO'] += $qrLista['VAL_DEBITOS'];
                                        $vlPropriedades['PROPRIEDADES'][$qrLista['COD_HOTEL']]['TIPOS'][$qrLista['ABV_TIPO']]['TOT_CREDITO'] += $qrLista['VAL_CREDITOS'];
                                        $vlPropriedades['PROPRIEDADES'][$qrLista['COD_HOTEL']]['TIPOS'][$qrLista['ABV_TIPO']]['TOT_DEBITO'] += $qrLista['VAL_DEBITOS'];
                                        if ($qrLista['NOM_BANCO'] != '') {
                                            $nomBanco = $qrLista['NOM_BANCO'];
                                        } else {
                                            $nomBanco = 'NÃO VINCULADO';
                                        }
                                        $vlPropriedades['CONTAS'][$nomBanco]['TOT_CREDITO'] += $qrLista['VAL_CREDITOS'];
                                        $vlPropriedades['CONTAS'][$nomBanco]['TOT_DEBITO'] += $qrLista['VAL_DEBITOS'];
                                    }

                                    //fnEscreveArray($vlPropriedades);



                                    ?>

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">Total Créditos: R$ <?= fnValor($val_creditos, 2); ?></td>
                                        <td class="text-right">Total Débitos: R$ <?= fnValor($val_debitos, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="10"></td>
                                    </tr>
                                    <!-- 

                                    Array
                                        (
                                            [PROPRIEDADES] => Array
                                                (
                                                    [2957] => Array
                (
                    [NOM_PROPRIEDADE] => Piedade/SP - Campo
                    [TOT_DEBITO] => 0
                    [TOT_CREDITO] => 7238
                    [TIPOS] => Array
                        (
                            [Cartão Adorai] => Array
                                (
                                    [TOT_CREDITO] => 3730
                                    [TOT_DEBITO] => 0
                                )

                            [Cartão Crédito Site] => Array
                                (
                                    [TOT_CREDITO] => 1050
                                    [TOT_DEBITO] => 0
                                )

                            [Pix Adorai] => Array
                                (
                                    [TOT_CREDITO] => 660
                                    [TOT_DEBITO] => 0
                                )

                            [Asaas] => Array
                                (
                                    [TOT_CREDITO] => 1798
                                    [TOT_DEBITO] => 0
                                )

                        )
                                        )

                                    <tr>
                                        <th colspan="100">
                                            <a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                                        </th>
                                    </tr> -->

                                    <?php

                                    foreach ($vlPropriedades['PROPRIEDADES'] as $propriedade => $vl) {
                                        if ($vl['TOT_CREDITO'] > 0 || $vl['TOT_DEBITO'] > 0) {

                                    ?>

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><?= $vl['NOM_PROPRIEDADE']; ?></td>
                                                <td colspan="3"></td>
                                            </tr>
                                            <?php
                                            $countFooter = 0;
                                            foreach ($vl['TIPOS'] as $tipo => $vl2) {
                                            ?>
                                                <tr>
                                                    <td colspan="7"></td>
                                                    <td><?= $tipo; ?></td>
                                                    <td class="text-right">Total Créditos: R$ <?= fnValor($vl2['TOT_CREDITO'], 2); ?></td>
                                                    <td class="text-right">Total Débitos: R$ <?= fnValor($vl2['TOT_DEBITO'], 2); ?></td>

                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            </tr>

                                    <?php
                                        }
                                    }
                                    ?>


                                    <tr>
                                        <th class="" colspan="100">
                                            <center>
                                                <ul id="paginacao" class="pagination-sm"></ul>
                                            </center>
                                            <a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this, <?= $dat_ini ?>, <?= $dat_ini ?>)"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a> &nbsp;&nbsp;

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

    });


    function exportarCSV(btn, dat_ini, dat_fim) {
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
                                    url: "relatorios/ajxRelPagAdorai.do?opcao=exportar2&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
    }

    function concilia(codPedido) {
        let cod_pedido = codPedido;

        $.confirm({
            title: 'Conciliação',
            content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<p>Deseja conciliar os valores recebidos para o pedido <b>' + cod_pedido + '</b>?</p>' +
                '</div>' +
                '</form>',
            buttons: {
                formSubmit: {
                    text: 'Conciliar',
                    btnClass: 'btn-blue',
                    action: function() {
                        $.confirm({
                            title: 'Mensagem',
                            type: 'green',
                            icon: 'fa fa-check-square',
                            content: function() {
                                var self = this;
                                return $.ajax({
                                    url: "relatorios/ajxRelPagAdorai.do?opcao=conciliar&id=<?php echo fnEncode($cod_empresa); ?>",
                                    data: {
                                        COD_PEDIDO: cod_pedido
                                    }, // Correção aqui: uso de um objeto para passar os dados
                                    method: 'POST',
                                    dataType: 'json' // Certifique-se de que a resposta seja em JSON
                                }).done(function(response) {
                                    // Supondo que a resposta seja um JSON com um campo 'success'
                                    if (response.success) {
                                        self.setContentAppend('<div>Conciliação feita com sucesso.</div>');
                                        setTimeout(function() {
                                            location.reload();
                                        }), 1000;
                                    } else {
                                        self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                                    }
                                }).fail(function() {
                                    self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                                });
                            },
                            buttons: {
                                fechar: function() {
                                    // Fecha o diálogo
                                }
                            }
                        });
                    }
                },
                cancelar: function() {
                    // Fecha o diálogo
                }
            }
        });
    }
</script>