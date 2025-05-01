<?php
//echo fnDebug('true');

$itens_por_pagina = 50;
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {

        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {

        $_SESSION['last_request'] = $request;


        $cod_status = fnLimpaCampoZero($_POST['COD_STATUS']);
        $cod_municipio = $_POST['COD_MUNICIPIO'];
        $dat_ini = fnDataSql($_POST['DAT_INI']);
        $dat_fim = fnDataSql($_POST['DAT_FIM']);

        $Arr_COD_MUNICIPIO = $cod_municipio;

        // fnEscreve($_POST['COD_MUNICIPIO']);

        if (isset($Arr_COD_MUNICIPIO)) {
            //array das unidades de venda
            $countMunicipio = 0;
            if (isset($Arr_COD_MUNICIPIO)) {
                for ($i = 0; $i < count($Arr_COD_MUNICIPIO); $i++) {
                    $str_municipio .= $Arr_COD_MUNICIPIO[$i] . ',';
                    $countMunicipio++;
                }
                $str_municipio = rtrim($str_municipio, ',');
            }
            $cod_municipio = ltrim($str_municipio, ',');
        } else {
            $cod_municipio = "0";
        }

        // fnEscreve($cod_municipio);

        $cod_usucada = $_SESSION[SYS_COD_USUARIO];

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];
    }
}


//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $cod_campanha = fnDecode($_GET['idc']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    $nom_empresa = "";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = "";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = "";
}

if ($dat_ini != "") {
    $dat_ini_exibe = fnDataShort($dat_ini);
}

if ($dat_fim != "") {
    $dat_fim_exibe = fnDataShort($dat_fim);
}


//busca revendas do usuário
// include "unidadesAutorizadas.php";

?>

<style>
    /*table a:not(.btn), .table a:not(.btn) {
        text-decoration: none;
    }
    table a:not(.btn):hover, .table a:not(.btn):hover {
        text-decoration: underline;
    }
    #COD_OBJETO .chosen-drop .chosen-results li:last-child, 
    #COD_TIPO .chosen-drop .chosen-results li:last-child, 
    #COD_ORGAO .chosen-drop .chosen-results li:last-child, 
    #COD_STATUS .chosen-drop .chosen-results li:last-child{
        font-weight: bolder;
        font-size: 11px;
        color: #000;
    }

    #COD_OBJETO .chosen-drop .chosen-results li:last-child:before, 
    #COD_TIPO .chosen-drop .chosen-results li:last-child:before, 
    #COD_ORGAO .chosen-drop .chosen-results li:last-child:before, 
    #COD_STATUS .chosen-drop .chosen-results li:last-child:before{
        content: '\002795';
        font-weight: bolder;
        font-size: 9px;
    }*/
    td{
        width:300px;
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
                    <span class="text-primary"> <?php echo $NomePg; ?></span>
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
                            <legend>Dados Gerais</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Status</label>
                                        <div id="relatorioStatus">
                                            <select data-placeholder="Selecione um status" name="COD_STATUS" id="COD_STATUS" class="chosen-select-deselect">
                                                <option value=""></option>
                                                <?php
                                                $sql = "SELECT * FROM STATUS_EMENDA WHERE COD_EMPRESA = $cod_empresa";
                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                                while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                                ?>

                                                    <option value="<?= $qrBusca[COD_STATUS] ?>"><?= $qrBusca[DES_STATUS] ?></option>

                                                <?php
                                                }
                                                ?>
                                            </select>
                                            <script type="text/javascript">
                                                $('#COD_STATUS').val("<?= $cod_status ?>").trigger("chosen:updated");
                                            </script>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2" id="relatorioCidade">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cidade</label>
                                        <select data-placeholder="Selecione um município" name="COD_MUNICIPIO[]" id="COD_MUNICIPIO" multiple="multiple" class="chosen-select-deselect">
                                            <option value="0"></option>
                                            <?php

                                            $sql = "SELECT DISTINCT MN.COD_MUNICIPIO, MN.NOM_MUNICIPIO 
                                                        FROM MUNICIPIOS MN, EMENDA EM
                                                        WHERE MN.COD_MUNICIPIO = EM.COD_MUNICIPIO
                                                        ORDER BY NOM_MUNICIPIO";
                                            $arrayCidade = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            while ($qrCidade = mysqli_fetch_assoc($arrayCidade)) {
                                            ?>
                                                <option value="<?= $qrCidade['COD_MUNICIPIO'] ?>"><?= $qrCidade['NOM_MUNICIPIO'] ?></option>
                                            <?php
                                            }

                                            ?>
                                        </select>
                                        <script type="text/javascript">
                                            $("#COD_MUNICIPIO").val("").trigger("chosen:updated");

                                            if ("<?= $cod_municipio ?>" != "0") {
                                                //alert("entrou...");
                                                var sistemasMst = "<?= $cod_municipio ?>";
                                                var sistemasMstArr = sistemasMst.split(',');
                                                //opções multiplas
                                                for (var i = 0; i < sistemasMstArr.length; i++) {
                                                    $("#formulario #COD_MUNICIPIO option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");
                                                }
                                                $("#formulario #COD_MUNICIPIO").trigger("chosen:updated");
                                            } else {
                                                $("#formulario #COD_MUNICIPIO").val('').trigger("chosen:updated");
                                            }
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Data Inicial</label>

                                        <div class="input-group date datePicker" id="DAT_INI_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?= $dat_ini_exibe ?>" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Data Final</label>

                                        <div class="input-group date datePicker" id="DAT_FIM_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?= $dat_fim_exibe ?>" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="push20"></div>
                                    <button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                </div>

                            </div>

                        </fieldset>

                        <!-- <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />					 -->
                        <!-- <input type="hidden" name="COD_INDICAD" id="COD_INDICAD" value="<?= $cod_indicad ?>"> -->
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="COD_MUNICIPIO_AUX" id="COD_MUNICIPIO_AUX" value="">
                        <input type="hidden" name="REFRESH_COMBO" id="REFRESH_COMBO" value="N">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>" />
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                    </form>

                    <div class="push20"></div>
                    <div>
                        <div class="row">
                            <div class="col-md-12">

                                <div class="no-more-tables">

                                    <form name="formLista">

                                        <table class="table table-bordered table-striped table-hover tablesorter buscavel">

                                            <thead>
                                                <tr>
                                                    <th>Cod.</th>
                                                    <th>Cidade</th>
                                                    <th>Descrição</th>
                                                    <th>Tipo</th>
                                                    <th>Orgão</th>
                                                    <th>Status</th>
                                                    <th>Beneficiário</th>
                                                    <th>Dt. Inicial</th>
                                                    <th>Valor</th>
                                                </tr>

                                            </thead>

                                            <tbody id="relatorioConteudo">

                                                <?php

                                                $andStatus = "";
                                                $andMunicipio = "";

                                                if ($cod_status != 0) {
                                                    $andStatus = "AND EM.COD_STATUS = $cod_status";
                                                }

                                                if ($cod_municipio != 0) {
                                                    $andMunicipio = "AND EM.COD_MUNICIPIO IN($cod_municipio)";
                                                }

                                                if ($dat_ini != "" && $dat_fim != "") {
                                                    $andData = "AND EM.DAT_INI BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";
                                                } else if ($dat_ini != "") {
                                                    $andData = "AND EM.DAT_INI >= '$dat_ini 00:00:00'";
                                                } else if ($dat_fim != "") {
                                                    $andData = "AND EM.DAT_INI <= '$dat_fim 23:59:59'";
                                                } else {
                                                    $andData = "";
                                                }


                                                $sql = "SELECT * FROM EMENDA EM
                                                    WHERE COD_EMPRESA = $cod_empresa
                                                    $andData
                                                    $andStatus
                                                    $andMunicipio
                                                    ";

                                                //fnEscreve($sql);

                                                $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                                $totalitens_por_pagina = mysqli_num_rows($retorno);

                                                $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                                                //variavel para calcular o início da visualização com base na página atual
                                                $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                                // Filtro por Grupo de Lojas
                                                //include "filtroGrupoLojas.php";


                                                $sql = "SELECT  EM.COD_EMENDA,
                                                            EM.NUM_EMEDAPAL,
                                                            NM.NOM_MUNICIPIO,
                                                            TPE.DES_TIPO,
                                                            EM.DES_EMENDA,
                                                            ORE.DES_ORGAO,
                                                            STE.DES_STATUS,
                                                            CL2.NOM_CLIENTE AS NOM_BENEFICIARIO,
                                                            EM.DAT_INI,
                                                            EM.VAL_EMENDA
                                                    FROM EMENDA EM 
                                                    LEFT JOIN OBJETO_EMENDA OBE ON OBE.COD_OBJETO = EM.COD_OBJETO
                                                    LEFT JOIN ORGAO_EMENDA ORE ON ORE.COD_ORGAO = EM.COD_ORGAO
                                                    LEFT JOIN STATUS_EMENDA STE ON STE.COD_STATUS = EM.COD_STATUS
                                                    LEFT JOIN TIPO_EMENDA TPE ON TPE.COD_TIPO = EM.COD_TIPO
                                                    LEFT JOIN CLIENTES CL2 ON CL2.COD_CLIENTE = EM.COD_BENEFICIARIO
                                                    LEFT JOIN municipios NM ON NM.COD_MUNICIPIO = EM.COD_MUNICIPIO 
                                                    WHERE EM.COD_EMPRESA = $cod_empresa
                                                    AND EM.COD_EXCLUSA = 0
                                                    $andData
                                                    $andStatus
                                                    $andMunicipio
                                                    LIMIT $inicio,$itens_por_pagina
                                                    ";

                                                // fnEscreve($sql);
                                                //fnTestesql(connTemp($cod_empresa,''),$sql);											
                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                                $count = 0;
                                                while ($qrApoia = mysqli_fetch_assoc($arrayQuery)) {

                                                    $count++;
                                                ?>
                                                    <tr class="tabela">
                                                        <td><small><?= $qrApoia['NUM_EMEDAPAL'] ?></small></td>
                                                        <td><small><?= $qrApoia['NOM_MUNICIPIO'] ?></small></td>
                                                        <td><small><?= $qrApoia['DES_EMENDA'] ?></small></td>
                                                        <td><small><?= $qrApoia['DES_TIPO'] ?></small></td>
                                                        <td><small><?= $qrApoia['DES_ORGAO'] ?></small></td>
                                                        <td><small><?= $qrApoia['DES_STATUS'] ?></small></td>
                                                        <td><small><?= $qrApoia['NOM_BENEFICIARIO'] ?></small></td>
                                                        <td><small><?= fnDataShort($qrApoia['DAT_INI']) ?></small></td>
                                                        <td><small><?= fnValor($qrApoia['VAL_EMENDA'], 2) ?></small></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                        <th>
                                                            <div class="col-md-1">
                                                                <a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                                                            </div>
                                                            <div class="push10"></div>
                                                            <a href="#" class="btn btn-info addBox pull-left" id="print" data-url="action.php?mod=<?= fnEncode(1804) ?>&id=<?= fnEncode($cod_empresa); ?>&pop=true" data-title="Impressão de Cadastro"><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Impressão de Cadastro </a>
                                                        <th>
                                                </tr>
                                                <th class="" colspan="100">
                                                    <center>
                                                        <ul id="paginacao" class="pagination-sm"></ul>
                                                    </center>
                                                </th>
                                                </tr>
                                            </tfoot>

                                        </table>
                                    </form>
                                </div>


                            </div>
                        </div>



                        <div class="push5"></div>



                        <div class="push50"></div>

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
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

    <div id="retornaCombo"></div>

    <script>
        var listaClientes = [],
            current_page = 1;

        //datas
        $(function() {

            var numPaginas = <?php echo $numPaginas; ?>;
            if (numPaginas != 0) {
                carregarPaginacao(numPaginas);
            }


            jQuery('#paginacao').on('page', function(event, page) {
                current_page = page;
            });



            $('.datePicker').datetimepicker({
                format: 'DD/MM/YYYY'
            }).on('changeDate', function(e) {
                $(this).datetimepicker('hide');
            });

            // $("#DAT_INI_GRP").on("dp.change", function (e) {
            //     $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
            // });

            // $("#DAT_FIM_GRP").on("dp.change", function (e) {
            //     $('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
            // });


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
                                        url: "./relatorios/ajxRelEmendas.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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



        function reloadPage(idPage) {
            $.ajax({
                type: "POST",
                url: "./relatorios/ajxRelEmendas.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
                data: $('#formulario').serialize(),
                beforeSend: function() {
                    $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
                },
                success: function(data) {
                    $("#relatorioConteudo").html(data);
                    $(".tablesorter").trigger("updateAll");
                    // console.log(data);										
                },
                error: function() {
                    $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
                }
            });
        }
    </script>