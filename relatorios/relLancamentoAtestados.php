<?php

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
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
        $dat_ini = fnDataSql($_POST['DAT_INI']);
        $dat_fim = fnDataSql($_POST['DAT_FIM']);

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {
        }
    }
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV, TIP_RETORNO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
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

        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">
                    <div class="row">
                        <div class="col-md-12">

                            <table class="table table-bordered table-hover tablesorter buscavel">

                                <thead>
                                    <tr>
                                        <th class="{ sorter: false }" width="40"></th>
                                        <th>Cód. Atestado</th>
                                        <th>Colaborador</th>
                                        <th>Data Inicial</th>
                                        <th>Data Final</th>
                                        <th>Qtd. Dias</th>
                                        <th>Arquivo</th>
                                        <th class="tab { sorter: false }" width="40"></th>
                                    </tr>
                                </thead>

                                <tbody id="relatorioConteudo">

                                    <?php
                                   $sql = "SELECT * FROM ATESTADOS_COLABORADOR AS AC
                                                                INNER JOIN CLIENTES AS CL ON AC.COD_CLIENTE = CL.COD_CLIENTE
                                                                WHERE AC.COD_EMPRESA = $cod_empresa
                                                                AND AC.COD_EXCLUSA IS NULL";

                                    // fnEscreve($sql);
                                    $retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
                                    $totalitens_por_pagina = mysqli_num_rows($retorno);

                                    // fnescreve($sql);
                                    $numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);

                                    // fnEscreve($numPaginas);
                                    //variavel para calcular o início da visualização com base na página atual
                                    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                    // ================================================================================
                                    $sql = "SELECT AC.*, CL.NOM_CLIENTE FROM ATESTADOS_COLABORADOR AS AC
                                                                INNER JOIN CLIENTES AS CL ON AC.COD_CLIENTE = CL.COD_CLIENTE
                                                                WHERE AC.COD_EMPRESA = $cod_empresa
                                                                AND AC.COD_EXCLUSA IS NULL
                                            LIMIT $inicio, $itens_por_pagina";

                                    //fnEscreve($sql);
                                    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                    $count = 0;
                                    while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

                                        ?>  
                                        <tr>
                                            <td></td>
                                            <td><?= $qrListaVendas['COD_ATESTADO']; ?></td>
                                            <td><a href="action.do?mod=<?php echo fnEncode(1688); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?=fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?=$qrListaVendas['NOM_CLIENTE']; ?></a></td>
                                            <td><?= fnDataShort($qrListaVendas['DAT_INI']); ?></td>
                                            <td><?= fnDataShort($qrListaVendas['DAT_FIM']); ?></td>
                                            <td><?= fnDateDif($qrListaVendas['DAT_INI'],$qrListaVendas['DAT_FIM']); ?></td>
                                            <td><?= fnBase64DecodeImg($qrListaVendas['DES_IMG_ATESTADO']); ?></td>
                                            <td class='text-center'><a href="media/clientes/<?=$cod_empresa?>/<?=$qrListaVendas['DES_IMG_ATESTADO']?>" target='_blank'><span class='fas fa-download'></span></a></td>
                                        </tr>
                                        <?php

                                        $count++;    
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

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
    $(document).ready(function() {

        var numPaginas = <?php echo $numPaginas; ?>;
        if(numPaginas != 0){
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
                         action: function () {
                            var nome = this.$content.find('.nome').val();
                            if(!nome){
                               $.alert('Por favor, insira um nome');
                               return false;
                           }

                           $.confirm({
                               title: 'Mensagem',
                               type: 'green',
                               icon: 'fal fa-check-square-o',
                               content: function(){
                                  var self = this;
                                  return $.ajax({
                                     url: "relatorios/ajxRelLancamentoAtestado.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>", 
                                     data: $('#formulario').serialize(),
                                     method: 'POST'
                                 }).done(function (response) {
                                    console.log(response);
                                    self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                    var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                    SaveToDisk('media/excel/' + fileName, fileName);
    											//console.log(response);
                                }).fail(function(){
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
            url: "relatorios/ajxRelLancamentoAtestado.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
            data: $('#formulario').serialize(),
            beforeSend:function(){
                $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
            },
            success:function(data){
                $("#relatorioConteudo").html(data);                                     
                console.log(data);                                 
            },
            error:function(){
                $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });     
    }
</script>