<?php
echo fnDebug('true');
$uid = $_GET["uid"];
if ($uid != "") {
?>

    <div class="row">

        <div class="col-md12 margin-bottom-30">
            <!-- Portlet -->
            <div class="portlet portlet-bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="far fa-file-alt"></i>
                        <span class="text-primary"><?php echo $NomePg; ?> </span>
                    </div>
                    <?php include "atalhosPortlet.php"; ?>
                </div>
                <div class="portlet-body">

                    <div class="login-form">


                        <fieldset>
                            <legend>Dados do Gatilho</legend>

                            <div class="row" id="gatilho"></div>

                        </fieldset>

                        <div>
                            <div class="col-md-10"></div>
                            <div class="col-md-2">
                                <div class="push20"></div>
                                <a href="<?= "?mod=" . $_REQUEST["mod"] ?>" class="btn btn-primary btn-block btn-sm btn-block getBtn">
                                    Voltar
                                </a>
                            </div>
                            <div>

                                <form id="formulario">
                                    <input type='hidden' name="mod" value="<?= $_GET["mod"] ?>" />
                                    <input type='hidden' name="UID" value="<?= $_GET["uid"] ?>" />

                                    <fieldset>
                                        <legend>Filtros</legend>

                                        <div class="row">

                                            <div class="col-md-10">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Empresa</label>

                                                        <select data-placeholder="Selecione uma empresa" name="EMPRESA" class="chosen-select-deselect">
                                                            <option value=""></option>
                                                            <?php
                                                            $sql = "select * FROM empresas order by NOM_FANTASI";
                                                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
                                                            while ($qrListaEmpresa = mysqli_fetch_assoc($arrayQuery)) {
                                                                echo "<option value='" . $qrListaEmpresa['COD_EMPRESA'] . "'>" . $qrListaEmpresa['NOM_FANTASI'] . " (" . $qrListaEmpresa['COD_EMPRESA'] . ")" . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Tipo de Gatilho</label>

                                                        <select data-placeholder="Selecione um tipo" name="TIP_GATILHO" class="chosen-select-deselect">
                                                            <option value=""></option>
                                                            <option value="aniv">aniv</option>
                                                            <option value="anivCad">anivCad</option>
                                                            <option value="anivDia">anivDia</option>
                                                            <option value="anivMes">anivMes</option>
                                                            <option value="anivQuinz">anivQuinz</option>
                                                            <option value="anivSem">anivSem</option>
                                                            <option value="cadastro">cadastro</option>
                                                            <option value="credExp">credExp</option>
                                                            <option value="credVen">credVen</option>
                                                            <option value="inativos">inativos</option>
                                                            <option value="individual">individual</option>
                                                            <option value="individualB">individualB</option>
                                                            <option value="resgate">resgate</option>
                                                            <option value="venda">venda</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Cód. Campanha</label>

                                                        <input type="number" name="COD_CAMPANHA" class="form-control input-sm">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Cód. Gatilho</label>

                                                        <input type="number" name="COD_GATILHO" class="form-control input-sm">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Inconsistências</label>

                                                        <select data-placeholder="Selecione um tipo" name="INCONSISTENCIAS" class="chosen-select-deselect">
                                                            <option value=""></option>
                                                            <option value="ERROS">Erros</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-2">
                                                <div class="push20"></div>
                                                <button class="btn btn-primary btn-block btn-sm btn-block getBtn" onclick="filter_items();">
                                                    <i class="fa fa-filter" aria-hidden="true"></i>&nbsp;
                                                    Filtrar
                                                </button>
                                            </div>

                                        </div>

                                    </fieldset>
                                </form>

                                <div class="push20"></div>

                                <div>
                                    <div class="row">
                                        <div class="col-lg-12">

                                            <div class="no-more-tables">


                                                <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
                                                    <thead>
                                                        <tr>
                                                            <th class="{sorter:false}">Data/Hora</th>
                                                            <th class="{sorter:false}">Tipo</th>
                                                            <th class="{sorter:false}">Empresa</th>
                                                            <th class="{sorter:false}">Cód. Campanha</th>
                                                            <th class="{sorter:false}">Cód. Gatilho</th>
                                                            <th class="{sorter:false}">Tipo Gatilho</th>
                                                            <th class="{sorter:false}">Descrição</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>


                                                <div class="push"></div>

                                            </div>

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


            <script>
                let filter = "";
                $(document).ready(function() {
                    filter_items();
                });

                function filter_items() {
                    filter = $('#formulario').serialize();
                    load_items();
                }

                function load_items(loading) {

                    console.log("load", filter);

                    if (loading == undefined) {
                        loading = true;
                    }

                    $.ajax({
                        url: "ajxgatilhos_logs.do?opcao=item",
                        data: {
                            uid: "<?= $_GET["uid"] ?>"
                        },
                        method: 'POST',
                        beforeSend: function() {
                            if ($.trim($('#gatilho').html()) == "") {
                                $('#gatilho').html('<div class="loading" style="width: 100%;"></div>');
                            }
                        },
                        success: function(data) {
                            $("#gatilho").html(data);
                        },
                        error: function() {
                            $('#gatilho').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> erro ao carregar lista...</p>');
                        }
                    });

                    $.ajax({
                        url: "ajxgatilhos_logs.do?opcao=subitems",
                        data: filter,
                        method: 'POST',
                        beforeSend: function() {
                            if (loading) {
                                $('#tablista tbody').html('<td colspan=100><div class="loading" style="width: 100%;"></div></td>');
                            }
                        },
                        success: function(data) {
                            $("#tablista tbody").html(data);

                            <?php if (@$_GET["autorefresh"] == "true") { ?>
                                /*setTimeout(() => {
                                    load_items(false);
                                }, 6000);*/
                            <?php } ?>
                        },
                        error: function() {
                            $('#tablista tbody').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> erro ao carregar lista...</p>');
                        }
                    });
                }
            </script>

        <?php
    } else {
        ?>

            <div class="row">

                <div class="col-md12 margin-bottom-30">
                    <!-- Portlet -->
                    <div class="portlet portlet-bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="far fa-file-alt"></i>
                                <span class="text-primary"><?php echo $NomePg; ?> </span>
                            </div>
                            <?php include "atalhosPortlet.php"; ?>
                        </div>
                        <div class="portlet-body">

                            <div class="login-form">

                                <form id="formulario">
                                    <input type='hidden' name="mod" value="<?= $_GET["mod"] ?>" />

                                    <fieldset>
                                        <legend>Filtros</legend>

                                        <div class="row">

                                            <div class="col-md-10">

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Data</label>

                                                        <div class="input-group date datePicker">
                                                            <input type='text' class="form-control input-sm data" name="DATA" />
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Empresa</label>

                                                        <select data-placeholder="Selecione uma empresa" name="EMPRESA" class="chosen-select-deselect">
                                                            <option value=""></option>
                                                            <?php
                                                            $sql = "select * FROM empresas order by NOM_FANTASI";
                                                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
                                                            while ($qrListaEmpresa = mysqli_fetch_assoc($arrayQuery)) {
                                                                echo "<option value='" . $qrListaEmpresa['COD_EMPRESA'] . "'>" . $qrListaEmpresa['NOM_FANTASI'] . " (" . $qrListaEmpresa['COD_EMPRESA'] . ")" . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Tipo</label>

                                                        <select data-placeholder="Selecione um tipo" name="TIPO" class="chosen-select-deselect">
                                                            <option value=""></option>
                                                            <option value="PUSH">Push</option>
                                                            <option value="PUSH_LOTE">Push Lote</option>
                                                            <option value="PUSH_GENERICO">Push Genérico</option>
                                                            <option value="SMS">SMS</option>
                                                            <option value="EMAIL">E-mail</option>
                                                            <option value="WHATSAPP">Whatsapp</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Inconsistências</label>

                                                        <select data-placeholder="Selecione um tipo" name="INCONSISTENCIAS" class="chosen-select-deselect">
                                                            <option value=""></option>
                                                            <option value="ERROS">Erros</option>
                                                            <option value="DIVERGENCIAS">Divergências</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-md-2">
                                                <div class="push20"></div>
                                                <button class="btn btn-primary btn-block btn-sm btn-block getBtn" onclick="filter_items();">
                                                    <i class="fa fa-filter" aria-hidden="true"></i>&nbsp;
                                                    Filtrar
                                                </button>
                                            </div>

                                        </div>

                                    </fieldset>
                                </form>

                                <div class="push20"></div>

                                <div>
                                    <div class="row">
                                        <div class="col-lg-12">

                                            <div class="no-more-tables">


                                                <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
                                                    <thead>
                                                        <tr>
                                                            <th class="{sorter:false}">Cód. Execução</th>
                                                            <th class="{sorter:false}">Início</th>
                                                            <th class="{sorter:false}">Término</th>
                                                            <th class="{sorter:false}">Duração</th>
                                                            <th class="{sorter:false}">Tipo</th>
                                                            <th class="{sorter:false}">Empresas</th>
                                                            <th class="{sorter:false}">Qtd. Envios</th>
                                                            <th class="{sorter:false}">Qtd. Erros</th>
                                                            <th class="{sorter:false}">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>


                                                <div class="push"></div>


                                            </div>

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


            <script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
            <script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
            <script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
            <link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

            <script>
                let filter = "";
                let date;
                $(document).ready(function() {
                    //filter_items();

                    $('.datePicker').datetimepicker({
                        format: 'DD/MM/YYYY',
                        maxDate: 'now',
                    }).on('changeDate', function(e) {
                        $(this).datetimepicker('hide');
                    });
                });

                function filter_items() {
                    if ($("[name=DATA]").val() == "") {
                        $("[name=DATA]").val('<?= date("d/m/Y") ?>')
                    }

                    date = $("[name=DATA]").val();
                    filter = $('#formulario').serialize();
                    load_items();
                }

                function load_items(loading) {

                    console.log("load", filter);

                    if (loading == undefined) {
                        loading = true;
                    }

                    $.ajax({
                        url: "ajxgatilhos_logs.do?opcao=items",
                        data: filter,
                        method: 'POST',
                        beforeSend: function() {
                            if (loading) {
                                $('#tablista tbody').html('<td colspan=100><div class="loading" style="width: 100%;"></div></td>');
                            }
                        },
                        success: function(data) {
                            $("#tablista tbody").html(data);

                            /*setTimeout(() => {
                                if (date == '<?= date("d/m/Y") ?>') {
                                    load_items(false);
                                }
                            }, 10000);*/
                        },
                        error: function() {
                            $('#tablista tbody').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> erro ao carregar lista...</p>');
                        }
                    });
                }
            </script>
        <?php
    }
        ?>