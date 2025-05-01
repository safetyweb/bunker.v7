<?php

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set("america/sao_paulo");

echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$hashLocal = mt_rand();

$hoje = '';
$dias30 = '';
$dat_ini = '';


//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));
$dat_fim = date("Y-m");

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
        $cod_univend = @$_POST['COD_UNIVEND'];
        $cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
        $cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
        $dat_ini = fnmesanosql("01/" . @$_POST['DAT_INI']);
        $dat_ini_campo = @$_POST['DAT_INI'];
        $dat_campo = @$_POST['DAT_FIM'];
        $dat_fim = fnmesanosql("01/" . @$_POST['DAT_FIM']);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '') {
        }
    }
}

$ano = date('Y', strtotime('-1 year', strtotime($dat_fim)));
//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI, TIP_RETORNO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($adm, $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
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
// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//fnMostraForm();
//fnEscreve($cod_cliente);



$dat_ini = date('Y-m-d', strtotime($dat_ini));
$dat_fim = date('Y-m-t', strtotime($dat_fim));

$start    = new DateTime($dat_ini);
$start->modify('first day of this month');
$end      = new DateTime($dat_fim);
$end->modify('first day of next month');
$interval = DateInterval::createFromDateString('1 month');
$period   = new DatePeriod($start, $interval, $end);
$mesesIntervalo = "";

foreach ($period as $dt) {
    $mesesIntervalo .= $dt->format("Y-m") . ",";
}

$mesesIntervalo = rtrim($mesesIntervalo, ",");
$mesesIntervalo = explode(",", $mesesIntervalo);

$selectValues = "";
$caseWhen = "";
$meses = array();

foreach ($mesesIntervalo as $mes) {

    $dataLoop = explode("-", $mes);

    $anoLoop = $dataLoop[0];
    $mesLoop = $dataLoop[1];

    $indice = substr(ucfirst(strftime("%B", strtotime($mes . '-01'))), 0, 3) . "/" . date("Y", strtotime($mes . '-01'));

    $selectValues .= "SUM(PCT_DIARIO$anoLoop$mesLoop) '" . $indice . "',";
    $caseWhen .= "CASE WHEN DATE_FORMAT(DAT_MOVIMENTO, \"%Y-%m\") = '" . $mes . "' THEN ROUND(((SUM(QTD_TOTFIDELIZ)/ SUM(QTD_TOTVENDA))*100),2) ELSE 0 END AS PCT_DIARIO$anoLoop$mesLoop,";
    array_push($meses, $indice);
}


$sql = "SELECT  COD_UNIVEND, 
                    NOM_FANTASI,

                    $selectValues

                    DAT_MOVIMENTO, 
                    MES
    FROM(
    SELECT vendas_diarias.COD_UNIVEND, uni.NOM_FANTASI, 
    $caseWhen
    DAT_MOVIMENTO, 
    MONTH(DAT_MOVIMENTO) MES
    FROM vendas_diarias
    INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=vendas_diarias.COD_UNIVEND
    WHERE DAT_MOVIMENTO BETWEEN '$dat_ini' AND '$dat_fim' AND uni.COD_UNIVEND IN($lojasSelecionadas)
    GROUP BY COD_UNIVEND,DATE_FORMAT(DAT_MOVIMENTO, \"%Y-%m\"))tmpvendasmovi
    GROUP BY COD_UNIVEND";

//fnEscreve($sql);

$arrQuery = mysqli_query($conn, $sql);

$arrResult = array();

while ($qrMes = mysqli_fetch_assoc($arrQuery)) {

    $arrResult[$qrMes['COD_UNIVEND']]['NOM_FANTASI'] = $qrMes["NOM_FANTASI"];

    foreach ($mesesIntervalo as $mes) {

        $indice = substr(ucfirst(strftime("%B", strtotime($mes . '-01'))), 0, 3) . "/" . date("Y", strtotime($mes . '-01'));

        $arrResult[$qrMes['COD_UNIVEND']]["MESES"][$indice] = fnValor($qrMes[$indice], 2);
    }
}

// echo "<pre>";
// print_r($arrResult);
// echo "</pre>";

// exit();

?>

<div class="push30"></div>

<div class="row">

    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

        <div class="col-md12 margin-bottom-30">
            <!-- Portlet -->
            <div class="portlet portlet-bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fal fa-terminal"></i>
                        <span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
                    </div>

                    <?php
                    $formBack = "1015";
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

                    <div class="push30"></div>

                    <div class="login-form">

                        <fieldset>
                            <legend>Filtros</legend>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Unidade de Atendimento</label>
                                        <?php include "unidadesAutorizadasComboMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Grupo de Lojas</label>
                                        <?php include "grupoLojasComboMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Região</label>
                                        <?php include "grupoRegiaoMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="push10"></div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data Inicial</label>
                                        <div class="input-group date datePicker" id="DAT_FIM_GRP">
                                            <input type='text' class="form-control input-sm" name="DAT_INI" id="DAT_INI" value="<?= $dat_ini_campo ?>" required />
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
                                            <input type='text' class="form-control input-sm" name="DAT_FIM" id="DAT_FIM" value="<?= $dat_campo ?>" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="push20"></div>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
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

                        <div class="row">

                            <div class="col-md-12 wrapper" id="div_Produtos">

                                <div class="push20"></div>

                                <table class="table table-bordered table-hover tablesorter">


                                    <thead>
                                        <tr>
                                            <th>Lojas</th>
                                            <?php
                                            foreach ($mesesIntervalo as $mes) {

                                                $indice = substr(ucfirst(strftime("%B", strtotime($mes . '-01'))), 0, 3) . "/" . date("Y", strtotime($mes . '-01'));



                                            ?>
                                                <th><?= $indice ?></th>
                                            <?php
                                            }
                                            ?>
                                        </tr>

                                    </thead>

                                    <tbody>
                                        <?php

                                        foreach ($arrResult as $unidade) {

                                            //echo "<pre>";
                                            //print_r($unidade);
                                            //echo "</pre>";
                                        ?>
                                            <tr>
                                                <td><?= $unidade['NOM_FANTASI'] ?></td>
                                                <?php
                                                foreach ($unidade['MESES'] as $meses) {
                                                ?>
                                                    <td><?= $meses ?>%</td>
                                                <?php
                                                }
                                                ?>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>

                                    <div class="push10"></div>

                                    <tfoot>
                                        <tr>
                                            <th colspan="100">
                                                <a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp;Exportar </a>
                                            </th>
                                        </tr>
                                    </tfoot>

                                </table>

                            </div>

                        </div>

                        <input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
                        <input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
                        <input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                        <input type="hidden" name="DATA_INI" id="DATA_INI" value="<?= $dat_ini ?>">
                        <input type="hidden" name="DATA_FIM" id="DATA_FIM" value="<?= $dat_fim ?>">
                        <input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas; ?>">
                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                        <div class="push5"></div>



                        <div class="push50"></div>

                        <div class="push"></div>

                    </div>

                </div>
            </div>
            <!-- fim Portlet -->
        </div>
    </form>
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

<script>
    //datas
    $(function() {

        $('.datePicker').datetimepicker({
            format: 'MM/YYYY',
            maxDate: 'now'
        }).on('changeDate', function(e) {
            $(this).datetimepicker('hide');
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
                                        url: "relatorios/ajxRelEvolucaoInd.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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