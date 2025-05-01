<?php
//echo fnDebug('true');

$itens_por_pagina = 50;
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hashLocal = mt_rand();
$log_unifica = "";
$cod_mes = "";

$cod_modulo = fnDecode($_GET['mod']);
$cod_tipo = $_REQUEST['COD_TIPO'];
$cod_tipo_exc = $_REQUEST['COD_TIPO_EXC'];
$andCodTipo = " ";
$Arr_COD_TIPO = $cod_tipo;
$Arr_COD_TIPO_EXC = $cod_tipo_exc;

if (isset($Arr_COD_TIPO)){
        //array das unidades de venda
        $countUnive = 0;
        if (isset($Arr_COD_TIPO)){
         for ($i=0;$i<count($Arr_COD_TIPO);$i++) 
         { 
            $str_univend.=$Arr_COD_TIPO[$i].',';
            $countUnive ++; 
        } 
        $str_univend = substr($str_univend,0,-1);
    }       
    $cod_tipo = $str_univend;
}else{
    $cod_tipo = "0";
}

$str_univend = "";

if (isset($cod_tipo_exc)){
        //array das unidades de venda
        $countUnive = 0;
        if (isset($Arr_COD_TIPO_EXC)){
         for ($i=0;$i<count($Arr_COD_TIPO_EXC);$i++) 
         { 
            $str_univend.=$Arr_COD_TIPO_EXC[$i].',';
            $countUnive ++; 
        } 
        $str_univend = substr($str_univend,0,-1);
    }       
    $cod_tipo_exc = $str_univend;
}else{
    $cod_tipo_exc = "0";
}

// FNeSCREVE($cod_tipo_exc);
// FNeSCREVE($_REQUEST['COD_TIPO']);
//tipo de lançamento	
switch ($cod_modulo) {
    case 1713: //folha de pagamento
    $tip_lancame = "F";
    $andBonifica = "";
    $lancame = "AND LOG_LANCAME = '$tip_lancame'";
    if ($cod_tipo != 0) {
        $andCodTipo = "AND COD_TIPO IN($cod_tipo) ";
    }
    if($cod_tipo_exc != 0){
        $andCodTipoExc = "AND COD_TIPO NOT IN($cod_tipo_exc) ";
    }
    break;

    case 1722: //bonificação
    $tip_lancame = "B";
    $andBonifica = "AND LOG_BONIFICA = 'S' ";
    $lancame = "";
    if ($cod_tipo != 0) {
        $andCodTipo = "AND COD_TIPO IN($cod_tipo) ";
    }

    if($cod_tipo_exc != 0){
        $andCodTipoExc = "AND COD_TIPO NOT IN($cod_tipo_exc) ";
    }
    break;
}

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_mes = fnLimpaCampoZero(fnDecode($_REQUEST['COD_MES']));
        $cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);


        // fnEscreve($cod_mes);

    $opcao = $_REQUEST['opcao'];
    $hHabilitado = $_REQUEST['hHabilitado'];
    $hashForm = $_REQUEST['hashForm'];

    if ($opcao != '') {

    }
}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
        $disableEmpresa = "disabled";
    }
} else {
    $cod_empresa = 0;
    $disableEmpresa = "";
    //fnEscreve('entrou else');
}

if ($cod_mes == "" || $cod_mes == 0) {

    $sqlUltMes = "SELECT COD_MES FROM MES_CAIXA WHERE COD_EMPRESA = $cod_empresa ORDER BY DAT_FIM DESC LIMIT 1";

    $arrayUltMes = mysqli_query(connTemp($cod_empresa, ''), $sqlUltMes);
    $qrUltMes = mysqli_fetch_assoc($arrayUltMes);

    $cod_mes = $qrUltMes[COD_MES];
}

// fnEscreve($cod_mes);
//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($dat_fim);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($lojasReportAdm);
?>

<style>
    table a:not(.btn), .table a:not(.btn) {
        text-decoration: none;
    }
    table a:not(.btn):hover, .table a:not(.btn):hover {
        text-decoration: underline;
    }
    /* try removing the "hack" below to see how the table overflows the .body */
    .hack1 {
        display: table;
        table-layout: fixed;
        width: 100%;
    }

    .hack2 {
        display: table-cell;
        overflow-x: auto;
        width: 100%;
    }
</style>

<div class="push30"></div> 

<div class="row" id="div_Report">				

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="glyphicon glyphicon-calendar"></i>
                    <span class="text-primary"> <?php echo $NomePg; ?> <?php echo $nom_empresa; ?></span>
                </div>

                <?php
//$formBack = "1015";
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Mês</label>
                                        <select data-placeholder="Selecione o mês" name="COD_MES" id="COD_MES" class="chosen-select-deselect" style="width:100%;">																     
                                            <?php
                                            $sqlMes = "SELECT COD_MES, MESANO FROM MES_CAIXA
                                            WHERE COD_EMPRESA = $cod_empresa
                                            ORDER BY DAT_FIM DESC";
                                            $arrayMes = mysqli_query(connTemp($cod_empresa, ''), $sqlMes);

                                            while ($qrMes = mysqli_fetch_assoc($arrayMes)) {
                                                ?>

                                                <option value="<?= fnEncode($qrMes[COD_MES]) ?>"><?= $qrMes[MESANO] ?></option>

                                                <?php
                                            }
                                            ?>																	
                                        </select>
                                        <script type="text/javascript">$("#COD_MES").val("<?= fnEncode($cod_mes) ?>").trigger("chosen:updated");</script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Incluir Tipo de Lançamento</label>
                                        <select data-placeholder="Selecione o tipo de lançamento" name="COD_TIPO[]" id="COD_TIPO" multiple="multiple" class="chosen-select-deselect" style="width:100%;">																     
                                            <option value="0"></option>
                                            <?php

                                            $sqlTipo = "SELECT * FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0  $lancame ORDER BY COD_TIPO ASC";
                                            $arrayTipo = mysqli_query(connTemp($cod_empresa, ''), $sqlTipo);

                                            while ($qrTipo = mysqli_fetch_assoc($arrayTipo)) {
                                                ?>

                                                <option value="<?= $qrTipo[COD_TIPO] ?>"><?= $qrTipo[DES_TIPO] ?></option>

                                                <?php

                                            }

                                            ?>													
                                        </select>
                                        <script type="text/javascript">
                                            $("#COD_TIPO").val("").trigger("chosen:updated");

                                            if ("<?= $cod_tipo ?>" != "0" ){
                                                //alert("entrou...");
                                                var sistemasMst = "<?= $cod_tipo ?>";               
                                                var sistemasMstArr = sistemasMst.split(',');                
                                                //opções multiplas
                                                for (var i = 0; i < sistemasMstArr.length; i++) {
                                                  $("#formulario #COD_TIPO option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");                 
                                                }
                                                $("#formulario #COD_TIPO").trigger("chosen:updated");    
                                            } else {$("#formulario #COD_TIPO").val('').trigger("chosen:updated");}

                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Excluir Tipo de Lançamento</label>
                                        <select data-placeholder="Selecione o tipo de lançamento" name="COD_TIPO_EXC[]" id="COD_TIPO_EXC" multiple="multiple" class="chosen-select-deselect" style="width:100%;">                                                                    
                                            <option value="0"></option>
                                            <?php

                                            $sqlTipo = "SELECT * FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0  $lancame ORDER BY COD_TIPO ASC";
                                            $arrayTipo = mysqli_query(connTemp($cod_empresa, ''), $sqlTipo);

                                            while ($qrTipo = mysqli_fetch_assoc($arrayTipo)) {
                                                ?>

                                                <option value="<?= $qrTipo[COD_TIPO] ?>"><?= $qrTipo[DES_TIPO] ?></option>

                                                <?php

                                            }

                                            ?>                                                  
                                        </select>
                                        <script type="text/javascript">
                                            $("#COD_TIPO_EXC").val("").trigger("chosen:updated");

                                            if ("<?= $cod_tipo_exc ?>" != "0" ){
                                                //alert("entrou...");
                                                var sistemasMst = "<?= $cod_tipo_exc ?>";               
                                                var sistemasMstArr = sistemasMst.split(',');                
                                                //opções multiplas
                                                for (var i = 0; i < sistemasMstArr.length; i++) {
                                                  $("#formulario #COD_TIPO_EXC option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");                 
                                                }
                                                $("#formulario #COD_TIPO_EXC").trigger("chosen:updated");    
                                            } else {$("#formulario #COD_TIPO_EXC").val('').trigger("chosen:updated");}

                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="push20"></div>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                </div>			

                            </div>

                        </fieldset>	

                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
                        <input type="hidden" name="TIP_LANCAME" id="TIP_LANCAME" value="<?= $tip_lancame ?>">
                        <input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas ?>">					
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		

                    </form>


                </div>	

            </div>	

        </div>	

        <div class="push20"></div>

        <!-- Portlet -->
        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="hack1">
                    <div class="hack2">

                        <div class="col-md-12">

                            <div class="push20"></div>

                            <table class="table">
                                <tbody>
                                    <tr>
                                        <?php
                                        $sqlCol = "SELECT * FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0 $lancame $andCodTipo $andCodTipoExc ORDER BY COD_TIPO ASC";
                                        $arrayCol = mysqli_query(connTemp($cod_empresa, ''), $sqlCol);
                                        // fnEscreve($sqlCol);											  

                                        while ($qrCol = mysqli_fetch_assoc($arrayCol)) {


                                            $sqlTotal = "SELECT IfNull(sum(CAIXA.VAL_CREDITO),0) VAL_TOTSALDO
                                            FROM CAIXA
                                            LEFT JOIN TIP_CREDITO ON caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
                                            WHERE  CAIXA.COD_EMPRESA = $cod_empresa AND 
                                            CAIXA.COD_MES = $cod_mes AND 
                                            CAIXA.DAT_EXCLUSA IS NULL AND 
                                            CAIXA.COD_EXCLUSA = 0 AND 
                                            CAIXA.TIP_LANCAME = '$tip_lancame' AND 
                                            TIP_CREDITO.COD_TIPO = $qrCol[COD_TIPO]
                                            ";

                                            $arrayTotal = mysqli_query(connTemp($cod_empresa, ''), $sqlTotal);
                                            //fnEscreve($sqlTotal);
                                            //echo($sqlTotal);

                                            while ($qrColTotal = mysqli_fetch_assoc($arrayTotal)) {

                                                $saldoTotal . $qrCol[COD_TIPO] = $qrColTotal[VAL_TOTSALDO];
                                            }
                                            ?>	

                                            <td class="text-center"><b><?= ucfirst(mb_strtolower($qrCol[DES_TIPO], "utf-8")) ?></b> <div class="push5"></div> <small>R$</small> <?= fnValor($saldoTotal . $qrCol[COD_TIPO], 2) ?> </td>								  

                                            <?php
                                        }

                                        if ($cod_tipo == 0) {

                                            $sqlTotalLiq = "SELECT  
                                            sum(case when TIP_CREDITO.tip_operacao='C' then
                                            CAIXA.VAL_CREDITO
                                            END)-
                                            sum(case when TIP_CREDITO.tip_operacao='D' then
                                            CAIXA.VAL_CREDITO
                                            END) VAL_LIQUIDO
                                            FROM CAIXA
                                            LEFT JOIN TIP_CREDITO ON caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
                                            WHERE  CAIXA.COD_EMPRESA = $cod_empresa AND 
                                            CAIXA.COD_MES = $cod_mes AND 
                                            CAIXA.DAT_EXCLUSA IS NULL AND 
                                            CAIXA.COD_EXCLUSA = 0 AND 
                                            CAIXA.TIP_LANCAME = '$tip_lancame'";

                                            $arrayTotalLiq = mysqli_query(connTemp($cod_empresa, ''), $sqlTotalLiq);
                                            $qrTotalLiq = mysqli_fetch_assoc($arrayTotalLiq);

                                            $val_total_liquido = $qrTotalLiq[VAL_LIQUIDO];
                                            ?>	
                                            <td class="text-center"><b>Vl. Líquido </b> <div class="push5"></div> <small>R$</small> <?= fnValor($val_total_liquido, 2) ?></td>
                                            <?php
                                        }
                                        ?>	
                                    </tr>
                                </tbody>

                            </table>

                        </div>

                    </div>
                </div>

                <div class="push"></div>

            </div>	

        </div>				

        <div class="push20"></div>

        <!-- Portlet -->
        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="hack1">
                    <div class="hack2">

                        <div class="row">
                            <div class="col-md-12">

                                <div class="push20"></div>

                                <table class="table table-bordered table-striped table-hover tableSorter table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Nome</th>
                                            <!-- <th>PIX</th> -->
                                            <th>Contas</th>
                                            <?php
                                            $sqlCol = "SELECT * FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0  $lancame $andCodTipo $andCodTipoExc ORDER BY COD_TIPO ASC";
                                            $arrayCol = mysqli_query(connTemp($cod_empresa, ''), $sqlCol);

                                            while ($qrCol = mysqli_fetch_assoc($arrayCol)) {
                                                ?>

                                                <th><?= ucfirst(mb_strtolower($qrCol[DES_TIPO], "utf-8")) ?></th>

                                                <?php
                                            }

                                            if ($cod_tipo == 0) {
                                                ?>	
                                                <th class="text-right">Vl. Líquido</th>
                                                <?php
                                            }
                                            ?>	
                                        </tr>
                                    </thead>
                                    <tbody id="relatorioConteudo">

                                        <?php
                                        $sql = "SELECT DISTINCT COD_CLIENTE
                                        FROM CLIENTES CL 
                                        INNER JOIN CAIXA CX ON CX.COD_CONTRAT = CL.COD_CLIENTE AND CX.COD_MES = $cod_mes AND CX.TIP_LANCAME = '$tip_lancame' $andCodTipo $andCodTipoExc
                                        WHERE CL.COD_EMPRESA = $cod_empresa
                                        $andBonifica
                                        AND CL.LOG_TITULAR = 'S'";

                                        // fnEscreve($sql);

                                        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                        $totalitens_por_pagina = mysqli_num_rows($retorno);
                                        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                                        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                        $sql = "SELECT DISTINCT CL.COD_CLIENTE, CL.NOM_CLIENTE, CL.VAL_SALBASE, CL.PCT_JURIDICO, 
                                                CASE WHEN BC.NUM_AGENCIA>0 THEN
                                                CONCAT(BC.NUM_AGENCIA,' / ',NUM_CONTACO)
                                                ELSE 
                                                CASE WHEN NUM_PIX > 0 THEN
                                                CONCAT( 
                                                CASE WHEN TIP_PIX =3 THEN
                                                'PIX'
                                                WHEN TIP_PIX =2 THEN
                                                'PIX'
                                                WHEN TIP_PIX =1 THEN
                                                'PIX'

                                                END,' - ',NUM_PIX)
                                                END
                                                END AS CONTAS
                                                FROM CLIENTES CL 
                                                LEFT JOIN dados_bancarios bc ON bc.COD_CLIENTE=CL.COD_CLIENTE
                                                INNER JOIN CAIXA CX ON CX.COD_CONTRAT = CL.COD_CLIENTE AND CX.COD_MES = $cod_mes AND CX.TIP_LANCAME = '$tip_lancame' $andCodTipo $andCodTipoExc
                                                WHERE CL.COD_EMPRESA = $cod_empresa AND 
                                                CL.LOG_TITULAR = 'S'
                                                $andBonifica  
                                                ORDER BY CL.NOM_CLIENTE ASC LIMIT $inicio,$itens_por_pagina";

                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            // fnEscreve($sql);
                                        $count = 0;
                                        while ($qrFunc = mysqli_fetch_assoc($arrayQuery)) {
                                            $count++;
                                            $val_liquido = 0;
                                            ?>

                                            <tr>
                                                <td><small><?= $qrFunc['COD_CLIENTE'] ?></small></td>
                                                <td><small><?= $qrFunc['NOM_CLIENTE'] ?></small></td>
                                                <td><small><?= $qrFunc['CONTAS'] ?></small></td>

                                                <?php
                                                $sqlCol2 = "SELECT * FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa $lancame $andCodTipo $andCodTipoExc ORDER BY COD_TIPO ASC";
                                                $arrayCol2 = mysqli_query(connTemp($cod_empresa, ''), $sqlCol2);

                                                while ($qrCol2 = mysqli_fetch_assoc($arrayCol2)) {

                                                    $sqlCaixa2 = "SELECT CAIXA.VAL_CREDITO,
                                                    CAIXA.COD_CAIXA,
                                                    TIP_CREDITO.COD_TIPO,
                                                    TIP_CREDITO.DES_TIPO,
                                                    TIP_CREDITO.TIP_OPERACAO,
                                                    CAIXA.DAT_LANCAME
                                                    FROM CAIXA
                                                    left join TIP_CREDITO on caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
                                                    where CAIXA.COD_CONTRAT=$qrFunc[COD_CLIENTE]
                                                    AND CAIXA.COD_EMPRESA=$cod_empresa 
                                                    AND CAIXA.COD_MES = $cod_mes
                                                    AND CAIXA.DAT_EXCLUSA IS NULL
                                                    AND CAIXA.COD_EXCLUSA = 0
                                                    AND CAIXA.TIP_LANCAME = '$tip_lancame'
                                                    AND TIP_CREDITO.COD_TIPO = $qrCol2[COD_TIPO]
                                                    GROUP BY TIP_CREDITO.COD_TIPO
                                                    ORDER BY TIP_CREDITO.COD_TIPO DESC
                                                    ";

                                                // fnEscreve($sqlCaixa2);
                                                    $arrayCaixa2 = mysqli_query(connTemp($cod_empresa, ''), $sqlCaixa2);

                                                    $qrVal = mysqli_fetch_assoc($arrayCaixa2);

                                                    if (mysqli_num_rows($arrayCaixa2) > 0) {

                                                        $tip_operacao = $qrVal['TIP_OPERACAO'];

                                                        if ($tip_operacao == "D") {
                                                            $corTexto = "text-danger";
                                                            $val_liquido -= $qrVal['VAL_CREDITO'];
                                                        } else {
                                                            $corTexto = "";
                                                            $val_liquido += $qrVal['VAL_CREDITO'];
                                                        }
                                                        ?>
                                                        <td><small><?= fnValor($qrVal['VAL_CREDITO'], 2) ?></small></td>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td></td>	
                                                        <?php
                                                    }
                                                }

                                                if ($cod_tipo == 0) {
                                                    ?>	
                                                    <td><small><?= fnValor($val_liquido, 2) ?></small></td>
                                                    <?php
                                                }
                                                ?>	
                                            </tr>	
                                            <?php
                                        }
                                        ?>

                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th colspan="100">
                                                <a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                                            </th>
                                        </tr>														
                                        <tr>
                                            <th class="" colspan="100">
                                                <center><ul id="paginacao" class="pagination-sm"></ul></center>
                                            </th>
                                        </tr>
                                    </tfoot>

                                </table>

                            </div>


                        </div>

                    </div>
                </div>


                <div class="push50"></div>									

                <div class="push"></div>

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

<script>

//datas
$(function () {

    var numPaginas = <?php echo $numPaginas; ?>;
    if (numPaginas != 0) {
        carregarPaginacao(numPaginas);
    }

    $('.datePicker').datetimepicker({
        format: 'DD/MM/YYYY',
        maxDate: 'now',
    }).on('changeDate', function (e) {
        $(this).datetimepicker('hide');
    });

    $("#DAT_INI_GRP").on("dp.change", function (e) {
        $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
    });

    $("#DAT_FIM_GRP").on("dp.change", function (e) {
        $('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
    });

    $(".exportarCSV").click(function () {
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
                    action: function () {
                        var nome = this.$content.find('.nome').val();
                        if (!nome) {
                            $.alert('Por favor, insira um nome');
                            return false;
                        }

                        $.confirm({
                            title: 'Mensagem',
                            type: 'green',
                            icon: 'fa fa-check-square-o',
                            content: function () {
                                var self = this;
                                return $.ajax({
                                    url: "relatorios/ajxRelLancamentoRH.do?opcao=exportar&nomeRel=" + nome,
                                    data: $('#formulario').serialize(),
                                    method: 'POST'
                                }).done(function (response) {
                                    self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                    var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                    SaveToDisk('media/excel/' + fileName, fileName);
                                    console.log(response);
                                }).fail(function () {
                                    self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                                });
                            },
                            buttons: {
                                fechar: function () {
                                    //close
                                }
                            }
                        });
                    }
                },
                cancelar: function () {
                    //close

                },
            }
        });
    });

});

function reloadPage(idPage) {
    $.ajax({
        type: "POST",
        url: "relatorios/ajxRelLancamentoRH.do?opcao=paginar&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
        data: $('#formulario').serialize(),
        beforeSend: function () {
            $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
        },
        success: function (data) {
            $("#relatorioConteudo").html(data);
        },
        error: function (data) {
            console.log(data);
            $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
        }
    });
}

function abreDetail(idBloco) {
    var idItem = $('.abreDetail_' + idBloco)
    if (!idItem.is(':visible')) {
        idItem.show();
        $('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
    } else {
        idItem.hide();
        $('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
    }
}

</script>	
