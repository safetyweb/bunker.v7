<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$opcao = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$hor_ini = "";
$hor_fim = "";
$num_celular = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = '';
$qrBuscaEmpresa = "";
$nom_empresa = "";
$hoje = "";
$dias30 = "";
$formBack = "";
$andCelular = "";
$andUnidade = "";
$lojasSelecionadas = "";
$data_ini = "";
$data_fim = "";
$retorno = "";
$totalitens_por_pagina = 0;
$qtd_token_enviado = 0;
$qtd_numeros_envios = 0;
$qtd_tokens_unicos = 0;
$qtd_total_aceite = 0;
$pct_qtd_total_aceite = 0;
$qtd_total_nao_aceite = 0;
$qtd_total_reenvio = 0;
$qtd_antigos_atualizados = 0;
$qtd_novos_atualizados = 0;
$pct_qtd_total_nao_aceite = 0;
$pct_qtd_novos = 0;
$pct_qtd_antigos = 0;
$sqlCount = "";
$inicio = "";
$qrBuscaModulos = "";
$cliCadastrado = "";
$reenvioTkn = "";
$statusToken = "";
$content = "";
$condicaoCartao = "";
$andCreditos = "";
$condicaoVendaPDV = "";
$andNome = "";


//echo "<h5>_".$opcao."</h5>";


$hashLocal = mt_rand();
$itens_por_pagina = 50;
$pagina = 1;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
        $cod_univend = fnLimpaCampo(@$_REQUEST['COD_UNIVEND']);
        $dat_ini = fnDataSql(@$_POST['DAT_INI']);
        $dat_fim = fnDataSql(@$_POST['DAT_FIM']);
        $hor_ini = fnLimpaCampo(@$_REQUEST['HOR_INI']);
        $hor_fim = fnLimpaCampo(@$_REQUEST['HOR_FIM']);
        $num_celular = preg_replace("/[^0-9]/", "", fnLimpaCampo(@$_POST['NUM_CELULAR']));

        // fnEscreve($cod_univend);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];
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

$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));
//inicializaÃ§Ã£o das variÃ¡veis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
    $hor_ini = "00:00:00";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
    $hor_fim = "23:59:00";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnMostraForm();

?>
<style>
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

                <div class="push10"></div>

                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Filtros</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data Inicial</label>

                                        <div class="input-group date datePicker" id="DAT_INI_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors">Envio do Token</div>
                                    </div>
                                </div>

                                <div class="col-md-2" style="width: 150px;">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Hora Início</label>

                                        <div class='input-group date clockPicker'>
                                            <input type='text' class="form-control input-sm hora-obrigatoria" name="HOR_INI" id="HOR_INI" value="<?php echo $hor_ini; ?>" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>
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
                                        <div class="help-block with-errors">Envio do Token</div>
                                    </div>
                                </div>

                                <div class="col-md-2" style="width: 150px;">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Hora Final</label>

                                        <div class='input-group date clockPicker'>
                                            <input type='text' class="form-control input-sm hora-obrigatoria" name="HOR_FIM" id="HOR_FIM" value="<?php echo $hor_fim; ?>" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Unidade de Atendimento</label>
                                        <?php include "unidadesAutorizadasComboMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Celular</label>
                                        <input type="text" class="form-control input-sm" name="NUM_CELULAR" id="NUM_CELULAR" data-mask="(00) 00000-0000" data-mask-selectonfocus="true" value="<?php echo $num_celular ?>">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="push20"></div>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                </div>
                            </div>

                        </fieldset>

                        <div class=" push20"></div>

                        <div class="col-md-2">
                            <a href="javascript:void(0)" class="btn btn-info btn-sm btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1732) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Gerar Token">Gerar Novo Token</a>
                        </div>

                        <?php
                        $andCelular = "";
                        $andUnidade = "";

                        if ($cod_univend != "" && $cod_univend != "9999") {
                            $andUnidade = "AND gt.COD_UNIVEND IN($lojasSelecionadas)";
                        }
                        if ($num_celular != '' && $num_celular != 0) {
                            $andCelular = "AND gt.NUM_CELULAR = $num_celular";
                        }

                        if ($dat_ini != '' && $dat_ini != 0) {
                            $data_ini = $dat_ini . ' ' . $hor_ini . ":00";
                            $data_fim = $dat_fim . ' ' . $hor_fim . ":00";
                        }

                        $sql = "SELECT 
                                                    SUM(QTD_NUMEROS_ENVIOS) QTD_NUMEROS_ENVIOS, 
                                                    SUM(QTD_TOKEN_ENVIADO) QTD_TOKEN_ENVIADO, 
                                                    SUM(QTD_TOKENS_UNICOS) QTD_TOKENS_UNICOS, 
                                                    SUM(NAO_USADO) NAO_USADO, 
                                                    SUM(USADO) USADO, 
                                                    SUM(excluidos) excluidos, 
                                                    SUM(QTD_REENVIO_MESMO_NUMERO) QTD_REENVIO_MESMO_NUMERO, 
                                                    SUM(QTD_ANTIGOS_ATUALIZADOS) QTD_ANTIGOS_ATUALIZADOS, 
                                                    SUM(QTD_NOVOS_ATUALIZADOS) QTD_NOVOS_ATUALIZADOS
                                                    FROM(
                                                            SELECT 
                                                                 COUNT(gt.DES_TOKEN) QTD_NUMEROS_ENVIOS ,
                                                                 COUNT(DISTINCT gt.DES_TOKEN) QTD_TOKENS_UNICOS ,
                                                                    SUM(gt.QTD_REENVIO_CONTROLE) QTD_TOKEN_ENVIADO, 
                                                                   CASE WHEN gt.LOG_USADO=1 AND gt.COD_EXCLUSA IN (0,1) THEN 1 ELSE 0 END NAO_USADO,                    
                                                                    CASE WHEN gt.LOG_USADO=2 AND gt.COD_EXCLUSA=0 THEN 1 ELSE NULL END USADO, 
                                                                    CASE WHEN gt.COD_EXCLUSA=1 THEN 1 ELSE 0 END excluidos, 
                                                                    SUM(CASE WHEN gt.QTD_REENVIO_CONTROLE > 1 THEN gt.QTD_REENVIO_CONTROLE ELSE 0 END) QTD_REENVIO_MESMO_NUMERO,                    
                                                                    CASE WHEN DATE(CLI.DAT_CADASTR) < DATE ('$data_ini') AND gt.LOG_USADO=2 AND CLI.COD_CLIENTE IS NOT NULL THEN 1 ELSE NULL END QTD_ANTIGOS_ATUALIZADOS,                  
                                                                    CASE WHEN DATE(CLI.DAT_CADASTR) >= DATE ('$data_ini') AND gt.LOG_USADO=2 AND CLI.COD_CLIENTE IS NOT NULL THEN 1 ELSE NULL END QTD_NOVOS_ATUALIZADOS
                                                            FROM geratoken gt
                                                            LEFT JOIN clientes CLI ON CLI.COD_CLIENTE=gt.COD_CLIENTE AND CLI.COD_EMPRESA=gt.COD_EMPRESA
                                                            WHERE gt.COD_EMPRESA = $cod_empresa 
                                                            AND gt.DAT_CADASTR BETWEEN '$data_ini' AND '$data_fim'
                                                            AND gt.COD_UNIVEND IN($lojasSelecionadas)
                                                            GROUP BY gt.DES_TOKEN
                                                    )TMP_TOTAL_CLIENTES";
                        //fnEscreve($sql);
                        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                        $totalitens_por_pagina = mysqli_fetch_assoc($retorno);
                        $numPaginas = ceil(@$totalitens_por_pagina['nao_usado'] / $itens_por_pagina);

                        //fnEscreve($sql);


                        $qtd_token_enviado = $totalitens_por_pagina['QTD_TOKEN_ENVIADO'];
                        $qtd_numeros_envios = $totalitens_por_pagina['QTD_NUMEROS_ENVIOS'];
                        $qtd_tokens_unicos = $totalitens_por_pagina['QTD_TOKENS_UNICOS'];
                        $qtd_total_aceite = $totalitens_por_pagina['USADO'];
                        $pct_qtd_total_aceite = $qtd_token_enviado != 0 ?  ($qtd_total_aceite * 100) / $qtd_token_enviado : 0;
                        $qtd_total_nao_aceite = $totalitens_por_pagina['NAO_USADO'];
                        $qtd_total_reenvio = $totalitens_por_pagina['QTD_REENVIO_MESMO_NUMERO'];
                        $qtd_antigos_atualizados = $totalitens_por_pagina['QTD_ANTIGOS_ATUALIZADOS'];
                        $qtd_novos_atualizados = $totalitens_por_pagina['QTD_NOVOS_ATUALIZADOS'];
                        $pct_qtd_total_nao_aceite = $qtd_token_enviado != 0 ? ($qtd_total_nao_aceite * 100) / $qtd_token_enviado : 0;
                        $pct_qtd_novos =  $qtd_total_aceite != 0 ? ($qtd_novos_atualizados * 100) / $qtd_total_aceite : 0;
                        $pct_qtd_antigos =  $qtd_total_aceite != 0 ? ($qtd_antigos_atualizados * 100) / $qtd_total_aceite : 0;
                        // fnEscreve($sql);

                        ?>

                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                        <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                        <div class="push5"></div>

                    </form>

                </div>

            </div>

        </div>

        <div class="push20"></div>

        <style>
            .shadow2 {
                padding: 15px 0 10px 0;
            }
        </style>

        <div class="row">

            <div class="col-md-12 col-lg-12 margin-bottom-30">
                <!-- Portlet -->
                <div class="portlet portlet-bordered">

                    <div class="portlet-body">

                        <div class="row text-center">

                            <div class="col-md-3">
                                <div class="shadow2">
                                    <div class="col-md-12 top-content">
                                        <p>Tokens Gerados</p>
                                        <label><?php echo fnValor($qtd_tokens_unicos, 0); ?></label>
                                    </div>
                                    <div class="clearfix"> </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="shadow2">
                                    <div class="col-md-8 top-content">
                                        <p>Envios</p>
                                        <label><?php echo fnValor($qtd_token_enviado, 0); ?></label>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="main-pie" class="pie-title-center" data-percent="100">
                                            <span class="pie-value">100%</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"> </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="shadow2">
                                    <div class="col-md-12 top-content">
                                        <p>Números que Enviaram</p>
                                        <label><?php echo fnValor($qtd_numeros_envios, 0); ?></label>
                                    </div>
                                    <!-- <div class="col-md-4">    
                                                <div id="main-pie" class="pie-title-center" data-percent="100">
                                                    <span class="pie-value">100%</span>
                                                </div>
                                            </div> -->
                                    <div class="clearfix"> </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="shadow2">
                                    <div class="col-md-12 top-content">
                                        <p>Tokens Iguais Reenviados</p>
                                        <label><?php echo fnValor($qtd_total_reenvio, 0); ?></label>
                                    </div>
                                    <!-- <div class="col-md-4">    
                                                <div id="main-pie" class="pie-title-center" data-percent="100">
                                                    <span class="pie-value">100%</span>
                                                </div>
                                            </div> -->
                                    <div class="clearfix"> </div>
                                </div>
                            </div>

                        </div>

                        <div class="row text-center">

                            <div class="col-md-3">
                                <div class="shadow2">
                                    <div class="col-md-8 top-content">
                                        <p>Tokens Expirados</p>
                                        <label><?php echo fnValor($qtd_total_nao_aceite, 0); ?></label>
                                        <p style="font-size: 14px;">&nbsp;</p>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="main-pie3" class="pie-title-center" data-percent="<?php echo fnValor($pct_qtd_total_nao_aceite, 2); ?>">
                                            <span class="pie-value"><?php echo fnValor($pct_qtd_total_nao_aceite, 2); ?>%</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"> </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="shadow2">
                                    <div class="col-md-8 top-content">
                                        <p>Tokens Utilizados</p>
                                        <label><?php echo fnValor($qtd_total_aceite, 0); ?></label>
                                        <p style="font-size: 14px;">&nbsp;</p>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="main-pie2" class="pie-title-center" data-percent="<?php echo fnValor($pct_qtd_total_aceite, 2); ?>">
                                            <span class="pie-value"><?php echo fnValor($pct_qtd_total_aceite, 2); ?>%</span>
                                        </div>
                                    </div>
                                    <div class="clearfix"> </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="shadow2">
                                    <div class="col-md-12 top-content">
                                        <p>Cadastros Novos</p>
                                        <label><?php echo fnValor($qtd_novos_atualizados, 0); ?></label>
                                        <p style="font-size: 14px;">&nbsp;</p>

                                    </div>
                                    <div class="clearfix"> </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="shadow2">
                                    <div class="col-md-12 top-content">
                                        <p>Cadastros Antigos</p>
                                        <label><?php echo fnValor($qtd_antigos_atualizados, 0); ?></label>
                                        <p style="font-size: 14px;">&nbsp;</p>

                                    </div>
                                    <div class="clearfix"> </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">

                    <div class="row">

                        <div class="col-lg-12">

                            <div class="no-more-tables">

                                <form name="formLista">

                                    <table class="table table-bordered table-striped table-hover tableSorter">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>CPF/CNPJ</th>
                                                <th>Celular</th>
                                                <!-- <th>Email</th>
                                                                                  <th>Envios</th> -->
                                                <th>Unidade</th>
                                                <th>Dt. Cadastro</th>
                                                <th>Retorno API</th>
                                                <th>Chave Envio</th>
                                                <th>Cadastro Concluído</th>
                                                <th>Token Excluído</th>
                                                <th class="{sorter:false}"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="relatorioConteudo">

                                            <?php
                                            $andCelular = "";
                                            $andUnidade = "";

                                            if ($cod_univend != "" && $cod_univend != "9999") {
                                                // $andUnidade = "AND gt.COD_UNIVEND = $cod_univend";
                                            }
                                            if ($num_celular != '' && $num_celular != 0) {
                                                $andCelular = "AND gt.NUM_CELULAR = $num_celular";
                                            }

                                            $sqlCount = "SELECT 1
                                                                                                FROM geratoken gt
                                                                                                INNER JOIN rel_geratoken rt ON gt.DES_TOKEN=rt.TOKEN AND rt.COD_GERATOKEN=gt.COD_TOKEN
                                                                                                WHERE gt.COD_EMPRESA = $cod_empresa
                                                                                                AND gt.DAT_CADASTR BETWEEN '$data_ini' AND '$data_fim'
                                                                                                AND gt.COD_UNIVEND IN($lojasSelecionadas)  
                                                                                                $andCelular
                                                                                                ";

                                            $retorno = mysqli_query(connTemp($cod_empresa, ''), $sqlCount);
                                            $totalitens_por_pagina = mysqli_num_rows($retorno);
                                            $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                                            //variavel para calcular o inÃ­cio da visualizaÃ§Ã£o com base na pÃ¡gina atual
                                            $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                            // $sql = "SELECT gt.COD_EMPRESA,
                                            //            gt.COD_USUCADA,
                                            //            gt.NOM_CLIENTE,
                                            //            gt.NUM_CGCECPF,
                                            //            gt.NUM_CELULAR,
                                            //            gt.DES_EMAIL,
                                            //            gt.LOG_USADO,
                                            //            gt.COD_TOKEN,
                                            //            rt.DAT_CADAST DAT_CADASTR,
                                            //            rt.DES_MSG,
                                            //             UNI.NOM_FANTASI,
                                            //                  case 
                                            //                      when  gt.COD_EXCLUSA=1 AND LOG_USADO=1 
                                            //                           then '1' ELSE '0' END DES_STATUS_TOKEN
                                            // FROM geratoken gt
                                            // INNER JOIN rel_geratoken rt ON gt.DES_TOKEN=rt.TOKEN AND rt.COD_GERATOKEN=gt.COD_TOKEN
                                            // LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=gt.COD_UNIVEND
                                            // WHERE gt.COD_EMPRESA = $cod_empresa
                                            // AND date(gt.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim'   
                                            // AND gt.COD_UNIVEND IN($lojasSelecionadas)
                                            // $andCelular
                                            // ORDER BY gt.DAT_CADASTR DESC LIMIT $inicio,$itens_por_pagina  ";

                                            $sql = "SELECT
                                                                                          gt.COD_EMPRESA, 
                                                                                          gt.COD_USUCADA, 
                                                                                          gt.NOM_CLIENTE, 
                                                                                          gt.NUM_CGCECPF, 
                                                                                          gt.NUM_CELULAR, 
                                                                                          gt.DES_EMAIL,
                                                                                          gt.LOG_USADO, 
                                                                                          gt.COD_TOKEN, 
                                                                                          rt.DAT_CADAST DAT_CADASTR, 
                                                                                          rt.DES_MSG, 
                                                                                          UNI.NOM_FANTASI, 
                                                                                          CASE WHEN gt.COD_EXCLUSA=1 AND LOG_USADO=1 THEN '1' ELSE '0' END DES_STATUS_TOKEN,
                                                                                          ret.BOUNCE,
                                                                                          ret.COD_CCONFIRMACAO,
                                                                                          rt.CHAVE_CLIENTE
                                                                                          
                                                                                        FROM geratoken gt
                                                                                        INNER JOIN rel_geratoken rt ON gt.DES_TOKEN=rt.TOKEN AND rt.COD_GERATOKEN=gt.COD_TOKEN
                                                                                        left JOIN sms_lista_ret ret ON ret.COD_CLIENTE=gt.COD_CLIENTE AND ret.CHAVE_CLIENTE=rt.CHAVE_CLIENTE
                                                                                        LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=gt.COD_UNIVEND
                                                                                        WHERE gt.COD_EMPRESA = $cod_empresa
                                                                                        AND gt.DAT_CADASTR BETWEEN '$data_ini' AND '$data_fim' 
                                                                                        AND gt.COD_UNIVEND IN($lojasSelecionadas)
                                                                                        $andCelular
                                                                                        ORDER BY gt.DAT_CADASTR DESC
                                                                                        LIMIT $inicio,$itens_por_pagina";

                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


                                            //fnEscreve($sql);
                                            $count = 0;
                                            while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                                $count++;

                                                //fnEscreve()

                                                if ($qrBuscaModulos['LOG_USADO'] == 2) {
                                                    $cliCadastrado = "<i class='fal fa-check text-success' aria-hidden='true'></i>";
                                                    $reenvioTkn = "";
                                                } else {
                                                    $cliCadastrado = "<i class='fal fa-times text-danger' aria-hidden='true'></i>";
                                                    $reenvioTkn = "<a class='btn btn-xs btn-info' onclick='reenvioTkn(" . $qrBuscaModulos['COD_TOKEN'] . ")'><span class='fal fa-repeat'></span> Reenviar</a>";
                                                }

                                                if ($qrBuscaModulos['DES_STATUS_TOKEN'] == 1) {
                                                    $statusToken = "<i class='fal fa-times text-danger' aria-hidden='true'></i>";
                                                    $reenvioTkn = "";
                                                } else {
                                                    $statusToken = "";
                                                }

                                                if ($qrBuscaModulos['BOUNCE'] == 1) {
                                                    $cliCadastrado = "<b class='text-danger'>e</b>";
                                                    $reenvioTkn = "";
                                                }


                                                echo "
                                                                                                <tr>
                                                                                                  <td>" . $qrBuscaModulos['NOM_CLIENTE'] . "</td>
                                                                                                  <td>" . $qrBuscaModulos['NUM_CGCECPF'] . "</td>
                                                                                                  <td>" . $qrBuscaModulos['NUM_CELULAR'] . "</td>
                                                                                                  <td>" . $qrBuscaModulos['NOM_FANTASI'] . "</td>
                                                                                                  <td>" . fnDataFull($qrBuscaModulos['DAT_CADASTR']) . "</td>
                                                                                                  <td>" . $qrBuscaModulos['DES_MSG'] . "</td>
                                                                                                  <td>" . $qrBuscaModulos['CHAVE_CLIENTE'] . "</td>    
                                                                                                  <td>" . $cliCadastrado . "</td>
                                                                                                  <td>" . $statusToken . "</td>
                                                                                                  <td>" . $reenvioTkn . "</td>
                                                                                                </tr>

                                                                                                ";
                                            }



                                            ?>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="100">
                                                    <a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                                                </th>
                                            </tr>
                                            <th class="" colspan="100">
                                                <center>
                                                    <ul id="paginacao" class="pagination-sm"></ul>
                                                </center>
                                            </th>
                                        </tfoot>
                                    </table>



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

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<script src="js/pie-chart.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
    $('.clockPicker').datetimepicker({
        format: 'LT',
    }).on('changeDate', function(e) {
        $(this).datetimepicker('hide');
    });

    $(function() {

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
                                icon: 'fa fa-check-square-o',
                                content: function() {
                                    var self = this;
                                    return $.ajax({
                                        url: "maiscash/ajxRelTokenCad.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&condicaoCartao=<?php echo $condicaoCartao; ?>&andCreditos=<?php echo $andCreditos; ?>&condicaoVendaPDV=<?php echo $condicaoVendaPDV; ?>&andNome=<?php echo $andNome; ?>",
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

    function reenvioTkn(idTkn) {
        $.ajax({
            method: 'POST',
            url: './maiscash/ajxRetaguardaToken.do',
            data: {
                COD_TOKEN: idTkn,
                COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>"
            },
            success: function(data) {
                if (data == 39) {
                    $.alert({
                        title: "Sucesso",
                        content: "Token Enviado",
                        type: 'green'
                    });
                    location.reload();
                } else if (data == 93) {
                    $.alert({
                        title: "Aviso",
                        content: "Por favor, aguarde 5 minutos e tente novamente, ou tente outro número. Tentativas excedidas.",
                        type: 'orange'
                    });
                } else {
                    $.alert({
                        title: "Falha",
                        content: "Token não enviado. Limite alcançado.",
                        type: 'orange'
                    });
                }
                console.log(data);
            }
        });
    }

    function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "maiscash/ajxRelTokenCad.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
            data: $('#formulario').serialize(),
            beforeSend: function() {
                $('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
            },
            success: function(data) {
                $("#relatorioConteudo").html(data);
                $(".tablesorter").trigger("updateAll");
            },
            error: function() {
                $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });
    }
    //graficos
    $(document).ready(function() {

        $('#main-pie').pieChart({
            barColor: '#2c3e50',
            trackColor: '#eee',
            lineCap: 'round',
            lineWidth: 8,
            onStep: function(from, to, percent) {
                $(this.element).find('.pie-value').text(percent.toFixed(2) + '%');
            }
        });

        $('#main-pie2').pieChart({
            barColor: '#3bb2d0',
            trackColor: '#eee',
            lineCap: 'round',
            lineWidth: 8,
            onStep: function(from, to, percent) {
                $(this.element).find('.pie-value').text(percent.toFixed(2) + '%');
            }
        });

        $('#main-pie3').pieChart({
            barColor: '#E74C3C',
            trackColor: '#eee',
            lineCap: 'round',
            lineWidth: 8,
            onStep: function(from, to, percent) {
                $(this.element).find('.pie-value').text(percent.toFixed(2) + '%');
            }
        });


    });
</script>