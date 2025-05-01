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
$msgRetorno = "";
$msgTipo = "";
$cod_campanha = "";
$tip_lancame = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$andDatIni = "";
$andDatFim = "";
$otp = "";
$usuario = "";
$senha = "";
$cliente_externo = "";
$parc_cadastrado = "";
$saldoNexux = "";
$formBack = "";
$abaEmpresa = "";
$qrCanal = "";
$sqlCamp = "";
$arrayData = [];
$cod_campanhas = "";
$qrCamp = "";
$qrLista = "";
$qtd_sms = 0;
$qtd_wpp = 0;
$qtd_email = 0;
$andCampanha = "";
$andCanal = "";
$andLancamento = "";
$sqlcontador = "";
$retorno = "";
$inicio = "";
$sqlCount = "";
$arrayQueryCount = [];
$qtd_contrato = 0;
$qtd_envio = 0;
$qtd_cred = 0;
$qtd_deb = 0;
$credSms = "";
$liberaSms = "";
$credEmail = "";
$debSms = "";
$debEmail = "";
$qrListaCount = "";
$qtd_produto = 0;
$val_unitario = "";
$val_total = 0;
$msg = "";
$dat_validade = "";
$id = "";
$content = "";



$itens_por_pagina = 50;
$pagina = 1;
$cod_canalcom = 0;

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-01'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
        $cod_campanha = fnLimpaCampo(@$_REQUEST['COD_CAMPANHA']);
        $cod_canalcom = fnLimpaCampo(@$_REQUEST['COD_CANALCOM']);
        $tip_lancame = fnLimpaCampo(@$_REQUEST['TIP_LANCAME']);
        $dat_ini = fnDataSql(@$_POST['DAT_INI']);
        $dat_fim = fnDataSql(@$_POST['DAT_FIM']);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '' && $opcao != 0) {


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
            }
            $msgTipo = 'alert-success';
        }
    }
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode(@$_GET['id']);
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

//inicialização das variáveis - default 
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
    $dat_ini = fnmesanosql($dat_ini) . "-01";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

$andDatIni = "";
$andDatFim = "";

if ($dat_ini != '' && $dat_ini != 0) {
    $andDatIni = "AND DAT_INI >= '$dat_ini'";
}

if ($dat_fim != '' && $dat_fim != 0) {
    $andDatFim = "AND DAT_FIM <= '$dat_fim'";
}

$otp = 'desativado';
include "autenticaNexux.php";
// retorna: $usuario, $senha, $cliente_externo e $parc_cadastrado(0/1)
// if($parc_cadastrado == 0){
//   fnEscreve("Parceiro não cadastrado na empresa");
// }

// VERIFICAÇÃO DE SALDO NEXUX ----------------------------------
$saldoNexux = SaldoNexux($senha);

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

                <?php
                $abaEmpresa = 1503;
                include "abasEmpresaConfig.php";
                ?>

                <div class="push30"></div>

                <div class="login-form">

                    <?php

                    if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) {

                    ?>

                        <div class="form-group col-lg-12">

                            <button type="button" name="CAD" id="CAD" class="btn btn-info getBtn addBox" data-url="action.php?mod=<?php echo fnEncode(1564) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Adicionar Créditos - <?= $nom_empresa ?>"><i class="fal fa-usd-circle" aria-hidden="true"></i>&nbsp; Adicionar Crédito Avulso</button>

                        </div>

                        <div class="push50"></div>

                    <?php

                    }

                    ?>

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Dados Gerais</legend>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">Data Inicial</label>

                                    <div class="input-group date datePicker" id="DAT_INI_GRP">
                                        <input type='text' class="form-control input-sm" name="DAT_INI" id="DAT_INI" value="<?= fnDataShort($dat_ini) ?>" required />
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
                                        <input type='text' class="form-control input-sm" name="DAT_FIM" id="DAT_FIM" value="<?= fnDataShort($dat_fim) ?>" required />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">

                                    <label for="inputName" class="control-label">Tipo do Canal</label>
                                    <select data-placeholder="Selecione o canal" name="COD_CANALCOM" id="COD_CANALCOM" class="chosen-select-deselect">
                                        <option value="0">Todos</option>
                                        <?php

                                        $sql = "SELECT COD_CANALCOM, DES_CANALCOM FROM CANAL_COMUNICACAO";
                                        $arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));
                                        while ($qrCanal = mysqli_fetch_assoc($arrayQuery)) {
                                        ?>

                                            <option value="<?= $qrCanal['COD_CANALCOM'] ?>"><?= $qrCanal['DES_CANALCOM'] ?></option>

                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <div class="help-block with-errors"></div>
                                    <script type="text/javascript">
                                        $("#formulario #COD_CANALCOM").val('<?= $cod_canalcom ?>').trigger("chosen:updated");
                                    </script>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">

                                    <label for="inputName" class="control-label">Campanha</label>
                                    <div id="divCampanhas">

                                        <select data-placeholder="Selecione a campanha" name="COD_CAMPANHA" id="COD_CAMPANHA" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <?php

                                            $sqlCamp = "SELECT DISTINCT COD_CAMPANHA FROM PEDIDO_MARKA
                                        WHERE COD_EMPRESA = $cod_empresa
                                        AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";

                                            $arrayData = mysqli_query($connAdm->connAdm(), trim($sqlCamp));
                                            $cod_campanhas = "";

                                            while ($qrCamp = mysqli_fetch_assoc($arrayData)) {
                                                $cod_campanhas .= $qrCamp['COD_CAMPANHA'] . ",";
                                            }

                                            $cod_campanhas = rtrim(trim($cod_campanhas), ',');

                                            $sql = "SELECT COD_CAMPANHA, DES_CAMPANHA FROM CAMPANHA 
                                    WHERE COD_EMPRESA = $cod_empresa 
                                  -- AND COD_EXT_CAMPANHA IS NOT NULL
                                    AND COD_CAMPANHA IN($cod_campanhas)
                                    AND (LOG_PROCESSA = 'S' OR LOG_PROCESSA_SMS = 'S')
                                    AND LOG_ATIVO = 'S'";

                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            while ($qrCamp = mysqli_fetch_assoc($arrayQuery)) {
                                            ?>

                                                <option value="<?= $qrCamp['COD_CAMPANHA'] ?>"><?= $qrCamp['DES_CAMPANHA'] ?></option>

                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <?php // fnEscreve($sql); 
                                        ?>
                                    </div>

                                    <div class="help-block with-errors"></div>
                                    <script type="text/javascript">
                                        $("#formulario #COD_CAMPANHA").val('<?= $cod_campanha ?>').trigger("chosen:updated");
                                    </script>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Tipo de Lançamento</label>
                                    <select data-placeholder="Selecione o canal" name="TIP_LANCAME" id="TIP_LANCAME" class="chosen-select-deselect">
                                        <option value="">Todos</option>
                                        <option value="D">Débito</option>
                                        <option value="C">Crédito</option>
                                    </select>
                                    <div class="help-block with-errors"></div>
                                    <script type="text/javascript">
                                        $("#formulario #TIP_LANCAME").val('<?= $tip_lancame ?>').trigger("chosen:updated");
                                    </script>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="push20"></div>
                                <button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
                        <input type="hidden" name="FEZ_AVULSO" id="FEZ_AVULSO" value="N">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                        <div class="push5"></div>

                    </form>

                    <div class="push20"></div>

                    <?php

                    $sql = "SELECT case 
                   when   SUM(PM.QTD_SALDO_ATUAL) <=   SUM(PM.QTD_PRODUTO)
                      then 
                         SUM(PM.QTD_SALDO_ATUAL) 
                      ELSE 
                       SUM(PM.QTD_PRODUTO) - SUM(PM.QTD_SALDO_ATUAL) end QTD_PRODUTO ,
                           PM.TIP_LANCAMENTO,
                           CC.DES_CANALCOM 
                    FROM PEDIDO_MARKA PM
                    INNER JOIN PRODUTO_MARKA PRM ON PRM.COD_PRODUTO = PM.COD_PRODUTO
                    INNER JOIN CANAL_COMUNICACAO CC ON CC.COD_CANALCOM = PRM.COD_CANALCOM 
                    WHERE PM.COD_ORCAMENTO > 0 
                    AND PM.PAG_CONFIRMACAO='S'
                    AND  PM.TIP_LANCAMENTO='C'
                    AND PM.COD_EMPRESA = $cod_empresa
                    AND  PM.QTD_SALDO_ATUAL > 0
                    GROUP BY CC.COD_TPCOM";

                    // fnEscreve($sql);

                    $arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

                    while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

                        // fnEscreve($qrLista['QTD_PRODUTO']);

                        $count++;

                        switch ($qrLista['DES_CANALCOM']) {

                            case 'SMS':
                                if ($qrLista['TIP_LANCAMENTO'] == 'D') {
                                    $qtd_sms = $qtd_sms - $qrLista['QTD_PRODUTO'];
                                } else {
                                    $qtd_sms = $qtd_sms + $qrLista['QTD_PRODUTO'];
                                }
                                break;

                            case 'WhatsApp':
                                if ($qrLista['TIP_LANCAMENTO'] == 'D') {
                                    $qtd_wpp = $qtd_wpp - $qrLista['QTD_PRODUTO'];
                                } else {
                                    $qtd_wpp = $qtd_wpp + $qrLista['QTD_PRODUTO'];
                                }
                                break;

                            default:
                                if ($qrLista['TIP_LANCAMENTO'] == 'D') {
                                    $qtd_email = $qtd_email - $qrLista['QTD_PRODUTO'];
                                } else {
                                    $qtd_email = $qtd_email + $qrLista['QTD_PRODUTO'];
                                }
                                break;
                        }
                    }

                    ?>

                    <div class="row text-center">

                        <div class="form-group text-center col-md-4 col-lg-4">

                            <div class="push20"></div>

                            <p><span id="QTD_SALDO_EMAIL"><?= fnValor($qtd_email, 0) ?></span></p>
                            <p><b>Saldo Email</b></p>

                            <div class="push20"></div>

                        </div>

                        <div class="form-group text-center col-md-4 col-lg-4">

                            <div class="push20"></div>

                            <p><span id="QTD_SALDO_SMS"><?= fnValor($qtd_sms, 0) ?></span></p>
                            <p><b>Saldo SMS</b></p>

                            <div class="push20"></div>

                        </div>

                        <!-- <div class="form-group text-center col-md-3 col-lg-3">

              <div class="push20"></div>
                
              <p><span id="QTD_SALDO_NXX"><?= fnValor($saldoNexux, 0) ?></span></p>
              <p><b>Saldo MKT</b></p>
            
              <div class="push20"></div>

            </div> -->

                        <div class="form-group text-center col-md-4 col-lg-4">

                            <div class="push20"></div>

                            <p><span id="QTD_SALDO_WPP"><?= fnValor($qtd_wpp, 0) ?></span></p>
                            <p><b>Saldo WhatsApp</b></p>

                            <div class="push20"></div>

                        </div>

                    </div>

                    <div class="push50"></div>

                    <div class="col-lg-12">

                        <div class="no-more-tables">

                            <form name="formLista">

                                <table class="table table-bordered table-striped table-hover tableSorter">
                                    <thead>
                                        <tr>
                                            <!-- <th>Cod</th> -->
                                            <th>Data</th>
                                            <th>ID</th>
                                            <th>Descrição</th>
                                            <th>Vl. Unitário</th>
                                            <th>Quantidade</th>
                                            <th>Total</th>
                                            <th>Situação</th>
                                            <th>Validade</th>
                                        </tr>
                                    </thead>
                                    <tbody id="relatorioConteudo">

                                        <?php

                                        if ($cod_campanha != 0 && $cod_campanha != '') {
                                            $andCampanha = "AND pedido.COD_CAMPANHA = $cod_campanha";
                                        } else {
                                            $andCampanha = "";
                                        }

                                        if ($cod_canalcom != 0 && $cod_canalcom != '') {
                                            $andCanal = "AND prod.COD_CANALCOM = $cod_canalcom";
                                        } else {
                                            $andCanal = "";
                                        }

                                        if ($tip_lancame == 'D') {
                                            $andLancamento = "AND pedido.TIP_LANCAMENTO = 'D'";
                                        } else if ($tip_lancame == 'C') {
                                            $andLancamento = "AND pedido.TIP_LANCAMENTO = 'C'";
                                        } else {
                                            $andLancamento = "";
                                        }

                                        //paginação
                                        $sqlcontador = "SELECT * FROM pedido_marka pedido
                                            INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                                            INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                                            INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA     
                                            WHERE pedido.COD_ORCAMENTO > 0
                                            AND pedido.COD_EMPRESA = $cod_empresa
                                            AND pedido.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                                                $andCampanha
                                                $andCanal
                                                $andLancamento
                                            ";

                                        $retorno = mysqli_query($connAdm->connAdm(), $sqlcontador);
                                        $total_itens_por_pagina = mysqli_num_rows($retorno);
                                        $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);
                                        //variavel para calcular o início da visualização com base na página atual
                                        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
                                        // fnEscreve($numPaginas);
                                        $sqlCount = "SELECT pedido.TIP_LANCAMENTO,
                                   pedido.COD_VENDA,
                                   pedido.COD_CAMPANHA,
                                   emp.NOM_EMPRESA,
                                   pedido.DAT_CADASTR,
                                   CONCAT(DATE_FORMAT(MID(CONVERT(pedido.COD_ORCAMENTO,CHAR),1,6), '%d/%m/%Y')
                                   ,' ' , MID(pedido.COD_ORCAMENTO,7,2),':',MID(pedido.COD_ORCAMENTO,9,2),':', MID(pedido.COD_ORCAMENTO,11,2)) AS DAT_CADASTRO,
                                   pedido.COD_ORCAMENTO,
                                   canal.DES_CANALCOM,
                                   round(pedido.QTD_PRODUTO,0) AS QTD_PRODUTO,
                                   pedido.VAL_UNITARIO,
                                   pedido.PAG_CONFIRMACAO,
                                   round(pedido.VAL_UNITARIO * pedido.QTD_PRODUTO,2) AS VAL_TOTAL ,
                                   if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
                            FROM pedido_marka pedido 
                            INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                            INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                            INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                            WHERE pedido.COD_ORCAMENTO > 0 
                            AND pedido.COD_EMPRESA = $cod_empresa
                            AND pedido.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                            $andCampanha
                            $andCanal
                            $andLancamento
                            ORDER BY pedido.DAT_CADASTR DESC, canal.DES_CANALCOM";

                                        // fnEscreve($sqlCount);

                                        $arrayQueryCount = mysqli_query($connAdm->connAdm(), trim($sqlCount));

                                        $count = 0;

                                        $qtd_contrato = 0;
                                        $qtd_envio = 0;
                                        $qtd_email = 0;
                                        $qtd_sms = 0;
                                        $qtd_wpp = 0;
                                        $qtd_cred = 0;
                                        $qtd_deb = 0;
                                        $credSms = 0;
                                        $liberaSms = 0;
                                        $credEmail = 0;
                                        $debSms = 0;
                                        $debEmail = 0;

                                        while ($qrListaCount = mysqli_fetch_assoc($arrayQueryCount)) {
                                            switch ($qrListaCount['DES_CANALCOM']) {

                                                case 'SMS':
                                                    if ($qrListaCount['TIP_LANCAMENTO'] == 'D') {
                                                        $qtd_sms = $qtd_sms - $qrListaCount['QTD_PRODUTO'];
                                                        $debSms = $debSms - $qrListaCount['QTD_PRODUTO'];
                                                    } else {
                                                        $qtd_sms = $qtd_sms + $qrListaCount['QTD_PRODUTO'];
                                                        if ($qrListaCount['PAG_CONFIRMACAO'] == 'S') {
                                                            $credSms = $credSms + $qrListaCount['QTD_PRODUTO'];
                                                        } else {
                                                            $liberaSms = $liberaSms + $qrListaCount['QTD_PRODUTO'];
                                                        }
                                                    }
                                                    break;

                                                case 'WhatsApp':
                                                    if ($qrListaCount['TIP_LANCAMENTO'] == 'D') {
                                                        $qtd_wpp = $qtd_wpp - $qrListaCount['QTD_PRODUTO'];
                                                    } else {
                                                        $qtd_wpp = $qtd_wpp + $qrListaCount['QTD_PRODUTO'];
                                                    }
                                                    break;

                                                default:
                                                    if ($qrListaCount['TIP_LANCAMENTO'] == 'D') {
                                                        $qtd_email = $qtd_email - $qrListaCount['QTD_PRODUTO'];
                                                        $debEmail = $debEmail - $qrListaCount['QTD_PRODUTO'];
                                                    } else {
                                                        $qtd_email = $qtd_email + $qrListaCount['QTD_PRODUTO'];
                                                        $credEmail = $credEmail + $qrListaCount['QTD_PRODUTO'];
                                                    }
                                                    break;
                                            }
                                        }

                                        $sql = "SELECT pedido.TIP_LANCAMENTO,
                                   pedido.COD_VENDA,
                                   pedido.COD_CAMPANHA,
                                   emp.NOM_EMPRESA,
                                   pedido.DAT_CADASTR,
                                   pedido.DAT_VALIDADE,
                                   pedido.ID_SESSION_PAGSEGURO,
                                   CONCAT(DATE_FORMAT(MID(CONVERT(pedido.COD_ORCAMENTO,CHAR),1,6), '%d/%m/%Y')
                                   ,' ' , MID(pedido.COD_ORCAMENTO,7,2),':',MID(pedido.COD_ORCAMENTO,9,2),':', MID(pedido.COD_ORCAMENTO,11,2)) AS DAT_CADASTRO,
                                   pedido.COD_ORCAMENTO,
                                   canal.DES_CANALCOM,
                                   round(pedido.QTD_PRODUTO,0) AS QTD_PRODUTO,
                                   pedido.VAL_UNITARIO,
                                   round(pedido.VAL_UNITARIO * pedido.QTD_PRODUTO,2) AS VAL_TOTAL ,
                                   if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
                            FROM pedido_marka pedido 
                            INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                            INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                            INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                            WHERE pedido.COD_ORCAMENTO > 0 
                            AND pedido.COD_EMPRESA = $cod_empresa
                            AND pedido.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                            $andCampanha
                            $andCanal
                            $andLancamento
                            ORDER BY pedido.DAT_CADASTR DESC, canal.DES_CANALCOM LIMIT $inicio,$itens_por_pagina";

                                        // fnEscreve($sql);

                                        $arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));


                                        while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

                                            $count++;

                                            if ($qrLista['TIP_LANCAMENTO'] == 'D') {

                                                $qtd_produto = "<span class='text-danger' style='font-size:14px;'><b>-</b></span>&nbsp;" . fnValor($qrLista['QTD_PRODUTO'], 0);
                                                $val_unitario = "";
                                                $val_total = "";
                                                $qtd_envio = $qtd_envio + $qrLista['QTD_PRODUTO'];
                                                $msg = ucfirst(strtolower($qrLista['ID_SESSION_PAGSEGURO']));
                                                $qtd_deb += $qrLista['QTD_PRODUTO'];
                                                $dat_validade = "";

                                                $sql = "SELECT DES_CAMPANHA FROM CAMPANHA WHERE COD_CAMPANHA = $qrLista[COD_CAMPANHA]";
                                                $qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), trim($sql)));
                                                $id = @$qrCamp['DES_CAMPANHA'];
                                            } else {

                                                $qtd_produto = "<span class='text-success' style='font-size:14px;'><b>+</b></span>&nbsp;" . fnValor($qrLista['QTD_PRODUTO'], 0);
                                                $val_unitario = fnValor($qrLista['VAL_UNITARIO'], 6);
                                                $val_total = fnValor($qrLista['VAL_TOTAL'], 2);
                                                $qtd_contrato = $qtd_contrato + $qrLista['QTD_PRODUTO'];
                                                if ($qrLista['COD_ORCAMENTO'] != "") {
                                                    $msg = $qrLista['DES_SITUACAO'];
                                                } else {
                                                    $msg = "Pagamento Confirmado";
                                                }
                                                $id = $qrLista['COD_ORCAMENTO'];
                                                $qtd_cred += $qrLista['QTD_PRODUTO'];
                                                $dat_validade = fnDataShort($qrLista['DAT_VALIDADE']);

                                                if ($id == 1) {
                                                    $id = "Crédito Avulso";
                                                    $msg = "Crédito Avulso";
                                                }
                                            }


                                            echo " <tr>                   
                              <td><small>" . fnDataFull($qrLista['DAT_CADASTR']) . "</small></td>
                              <td><small>" . $id . "</td>
                              <td><small>" . $qrLista['DES_CANALCOM'] . "</small></td>
                              <td class='text-right'><small>" . $val_unitario . "</small></td>
                              <td class='text-right'><small>" . $qtd_produto . "</small></td>
                              <td class='text-right'><small>" . $val_total . "</small></td>   
                              <td><small>" . $msg . "</small></td>
                              <td><small>" . $dat_validade . "</small></td>
                            </tr>
                            ";
                                        }
                                        ?>

                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th colspan="4"></th>
                                            <th colspan="2" class="text-right"><b>Créditos Email:</b> <?= fnValor($credEmail, 0) ?></th>
                                            <th colspan="2" class="text-right"><b>Débitos Email:</b> <?= fnValor($debEmail, 0) ?></th>
                                        </tr>
                                        <tr>
                                            <th colspan="4"></th>
                                            <th colspan="2" class="text-right"><b>Crédito Liberado:</b> <?= fnValor($credSms, 0) ?></th>
                                            <th colspan="2" class="text-right"><b>Débitos SMS:</b> <?= fnValor($debSms, 0) ?></th>
                                        </tr>
                                        <tr>
                                            <th colspan="4"></th>
                                            <th colspan="2" class="text-right"><b>Crédito á Liberar:</b> <?= fnValor($liberaSms, 0) ?></th>
                                            <!-- <th colspan="2" class="text-right"><b>Débitos SMS:</b> <?= fnValor($debSms, 0) ?></th> -->
                                        </tr>
                                        <!-- <tr>
                      <th colspan="4"></th>
                      <th colspan="2" class="text-right"><b>Créditos Totais:</b> <?= fnValor($qtd_cred, 0) ?></th>
                      <th colspan="2" class="text-right"><b>Débitos Totais:</b> <?= fnValor($qtd_deb, 0) ?></th>
                    </tr> -->
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

                                </table>

                                <script>
                                    $(function() {
                                        // $("#QTD_CONTRATO").text("<?= fnValor($qtd_contrato, 0) ?>");
                                        // $("#QTD_ENVIO").text("<?= fnValor($qtd_envio, 0) ?>");
                                        // $("#QTD_SALDO_EMAIL").text("<?= fnValor($qtd_email, 0) ?>");
                                        // $("#QTD_SALDO_SMS").text("<?= fnValor($qtd_sms, 0) ?>");
                                        // $("#QTD_SALDO_WPP").text("<?= fnValor($qtd_wpp, 0) ?>");
                                    });
                                </script>

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
        $('.modal').on('hidden.bs.modal', function() {
            // alert('fechou');
            if ($('#FEZ_AVULSO').val() == "S") {
                // alert('S');
                location.reload();
            }
        });



        $('.datePicker').datetimepicker({
            format: 'DD/MM/YYYY',
            maxDate: 'now',
        }).on('changeDate', function(e) {
            $(this).datetimepicker('hide');
        });

        $("#DAT_INI_GRP").on("dp.change", function(e) {
            $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
            atualizaCampanhas($("#DAT_INI").val(), $("#DAT_FIM").val());
        });

        $("#DAT_FIM_GRP").on("dp.change", function(e) {
            $('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
            atualizaCampanhas($("#DAT_INI").val(), $("#DAT_FIM").val());
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
                                        url: "ajxComunicacaoCompras.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
            url: "ajxComunicacaoCompras.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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

    function atualizaCampanhas(datIni, datFim) {
        $.ajax({
            type: "POST",
            url: "ajxComboCampanhasCreditos.do?id=<?php echo fnEncode($cod_empresa); ?>",
            data: {
                DAT_INI: datIni,
                DAT_FIM: datFim
            },
            beforeSend: function() {
                $('#divCampanhas').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                $("#divCampanhas").html(data);
                console.log(data);
            },
            error: function() {
                $('#divCampanhas').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });
    }

    function retornaForm(index) {
        $("#formulario #COD_TIPOCLI").val($("#ret_COD_TIPOCLI_" + index).val());
        $("#formulario #DES_TIPOCLI").val($("#ret_DES_TIPOCLI_" + index).val());
        $("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }
</script>