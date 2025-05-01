<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$des_emailus = "";
$des_senhaus = "";
$hashLocal = "";
$val_pesquisa = "";
$filtro = "";
$msgRetorno = "";
$msgTipo = "";
$cod_senhaparc = "";
$des_authkey = "";
$des_authkey2 = "";
$des_usuario = "";
$des_cliext = "";
$cod_parcomu = "";
$msg_retorno = "";
$cod_usucada = "";
$cod_listaext = "";
$log_ativo = "";
$hHabilitado = "";
$hashForm = "";
$array = [];
$msg = "";
$retorno = "";
$esconde = "";
$andFiltro = "";
$abaComunica = "";
$arrayQuery = [];
$qrListaTipoEntidade = "";
$countForm = "";
$totalitens_por_pagina = 0;
$inicio = "";
$qrBuscaModulos = "";
$tipo = "";
$cod_conface = 0;
$cod_empresa = 0;
$hashLocal = mt_rand();

$itens_por_pagina = 50;
$pagina = "1";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_senhaparc = fnLimpaCampoZero(@$_REQUEST['COD_SENHAPARC']);
        $cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
        $des_authkey = fnLimpaCampo(@$_REQUEST['DES_AUTHKEY']);
        $des_authkey2 = fnLimpaCampo(@$_REQUEST['DES_AUTHKEY2']);
        $des_usuario = fnLimpaCampo(@$_REQUEST['DES_USUARIO']);
        $des_cliext = fnLimpaCampo(@$_REQUEST['DES_CLIEXT']);
        $cod_parcomu = fnLimpaCampoZero(@$_REQUEST['COD_PARCOMU']);
        $msg_retorno = fnLimpaCampo(@$_REQUEST['MSG_RETORNO']);
        $cod_usucada = fnLimpaCampo(@$_REQUEST['COD_USUCADA']);
        $des_usuario = fnLimpaCampo(@$_REQUEST['DES_USUARIO']);
        $cod_listaext = fnLimpaCampo(@$_REQUEST['COD_LISTAEXT']);

        //$log_ativo = fnLimpaCampo(@$_REQUEST['LOG_ATIVO']);
        if (empty(@$_REQUEST['LOG_ATIVO'])) {
            $log_ativo = 'N';
        } else {
            $log_ativo = @$_REQUEST['LOG_ATIVO'];
        }

        $filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
        $val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
        $array = array("");

        if ($opcao != '') {

            $msg = "Sucesso";

            if ($cod_parcomu == 15) {

                include '_system/func_dinamiza/Function_dinamiza.php';

                $retorno = autenticacao_dinamiza($des_usuario, $des_authkey, $des_cliext);

                $msg = $retorno['code_detail'];

                if ($msg != "Sucesso") {
                    $log_ativo = 'N';
                }
            }

            // fnEscreve($msg);

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':

                    $sql = "INSERT INTO SENHAS_PARCEIRO(
                                            COD_EMPRESA,
                                            DES_AUTHKEY,
                                            DES_AUTHKEY2,
                                            DES_USUARIO,
                                            DES_CLIEXT,
                                            COD_LISTAEXT,
                                            COD_LISTA,
                                            COD_PARCOMU,
                                            MSG_RETORNO,
                                            LOG_ATIVO,
                                            COD_USUCADA
                                        ) VALUES(
                                            $cod_empresa,
                                            '$des_authkey',
                                            '$des_authkey2',
                                            '$des_usuario',
                                            '$des_cliext',
                                            '$cod_listaext',
                                            '$cod_listaext',
                                            $cod_parcomu,
                                            '$msg',
                                            '$log_ativo',
                                            $cod_usucada
                                        )";

                    // fnEscreve($sql);
                    mysqli_query($connAdm->connAdm(), $sql);
                    // fnTestesql($connAdm->connAdm(),$sql);

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

                    break;
                case 'ALT':

                    $sql = "UPDATE SENHAS_PARCEIRO SET
                                        COD_EMPRESA=$cod_empresa,
                                        DES_AUTHKEY='$des_authkey',
                                        DES_AUTHKEY2='$des_authkey2',
                                        DES_USUARIO='$des_usuario',
                                        DES_CLIEXT='$des_cliext',
                                        COD_LISTAEXT='$cod_listaext',
                                        COD_LISTA='$cod_listaext',
                                        COD_PARCOMU=$cod_parcomu,
                                        MSG_RETORNO='$msg',
                                        LOG_ATIVO='$log_ativo',
                                        COD_USUALT=$cod_usucada,
                                        DAT_ALTERAC=NOW()
                                WHERE COD_SENHAPARC = $cod_senhaparc";

                    //fnTestesql($connAdm->connAdm(),$sql);
                    //fnEscreve($sql);
                    mysqli_query($connAdm->connAdm(), $sql);

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

                    break;
                case 'EXC':

                    $sql = "DELETE FROM SENHAS_PARCEIRO WHERE COD_SENHAPARC = $cod_senhaparc";
                    mysqli_query($connAdm->connAdm(), $sql);

                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

                    break;
            }
            $msgTipo = 'alert-success';
        }
    }
}

if ($val_pesquisa != "") {
    $esconde = " ";
} else {
    $esconde = "display: none;";
}
if ($filtro != "") {
    $andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
} else {
    $andFiltro = " ";
}

//fnMostraForm();
//fnEscreve($cod_empresa);
?>

<div class="push30"></div>

<div class="row">

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"><?php echo $NomePg; ?></span>
                </div>
                <?php include "atalhosPortlet.php"; ?>
            </div>
            <div class="portlet-body">

                <?php if ($msgRetorno <> '') { ?>
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>

                <?php
                //menu senhas comunicação
                $abaComunica = 1623;
                include "abasSenhasComunicacao.php";
                ?>

                <div class="push30"></div>
                <a href="action.do?mod=<?php echo fnEncode(1243) ?>">Senhas e-mail (old)</a>
                <div class="login-form">
                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Dados Gerais</legend>

                            <div class="row">

                                <input type="hidden" class="form-control input-sm" name="COD_SENHAPARC" id="COD_SENHAPARC" value="">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Ativo</label>
                                        <div class="push5"></div>
                                        <label class="switch">
                                            <input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" checked>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Empresa</label>
                                        <select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <?php
                                            $sql = "select COD_EMPRESA, NOM_FANTASI from EMPRESAS order by NOM_FANTASI ";
                                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                            while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)) {
                                                echo "<option value='" . $qrListaTipoEntidade['COD_EMPRESA'] . "'>" . $qrListaTipoEntidade['COD_EMPRESA'] . "&nbsp-&nbsp" . $qrListaTipoEntidade['NOM_FANTASI'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <script>
                                            $("#formulario #COD_EMPRESA").val("<?php echo $cod_empresa; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Parceiro Comunicação</label>
                                        <select data-placeholder="Selecione um parceiro" name="COD_PARCOMU" id="COD_PARCOMU" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <?php
                                            $sql = "SELECT COD_PARCOMU, DES_PARCOMU FROM PARCEIRO_COMUNICACAO ORDER BY DES_PARCOMU ";
                                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                            while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)) {
                                                echo "<option value='" . $qrListaTipoEntidade['COD_PARCOMU'] . "'>" . $qrListaTipoEntidade['DES_PARCOMU'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <script>
                                            $("#formulario #COD_PARCOMU").val("<?php echo $cod_parcomu; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required LBL_USUARIO">Usuário</label>
                                        <input type="text" class="form-control input-sm" name="DES_USUARIO" id="DES_USUARIO" maxlength="100" value="<?php echo $des_usuario; ?>" required>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required LBL_AUTHKEY">Chave 1</label>
                                        <input type="text" class="form-control input-sm" name="DES_AUTHKEY" id="DES_AUTHKEY" maxlength="250" value="<?php echo $des_authkey; ?>" required>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-2" id="chave2" style="display: none;">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label LBL_AUTHKEY2">Chave 2</label>
                                        <input type="text" class="form-control input-sm" name="DES_AUTHKEY2" id="DES_AUTHKEY2" maxlength="250" value="<?php echo $des_authkey2; ?>">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Usuário</label>
                                        <div id="relUsuarios">
                                            <select data-placeholder="Selecione um usuário" name="COD_USUINTEGRA" id="COD_USUINTEGRA" class="chosen-select-deselect">
                                            </select>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required LBL_CLIEXT">Cliente Externo</label>
                                        <input type="text" class="form-control input-sm" name="DES_CLIEXT" id="DES_CLIEXT" maxlength="250" value="<?php echo $des_cliext; ?>" required>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required LBL_LISTAEXT">Cod. Externo Lista </label>
                                        <input type="text" class="form-control input-sm" name="COD_LISTAEXT" id="COD_LISTAEXT" value="<?php echo $cod_listaext; ?>">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                            </div>

                            <div class="push10"></div>

                        </fieldset>


                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Excluir</button>

                        </div>

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="COUNT" id="COUNT" value="<?php echo $countForm; ?>" />
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
                        <input type="hidden" name="AND_FILTRO" id="AND_FILTRO" value="<?= $andFiltro ?>">
                        <div class="push20"></div>
                    </form>
                    <form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">
                        <div class="row">
                            <div class="col-xs-4 col-xs-offset-4">
                                <div class="input-group activeItem">
                                    <div class="input-group-btn search-panel">
                                        <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
                                            <span id="search_concept">Sem filtro</span>&nbsp;
                                            <span class="fal fa-angle-down"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li class="divisor"><a href="#">Sem filtro</a></li>
                                            <!-- <li class="divider"></li> -->
                                            <li><a href="#EMPRESAS.NOM_FANTASI">Empresa</a></li>
                                            <li><a href="#EMPRESAS.COD_EMPRESA">Código</a></li>
                                        </ul>
                                    </div>
                                    <input type="hidden" name="VAL_PESQUISA" value="<?= $filtro ?>" id="VAL_PESQUISA">
                                    <input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
                                    <div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
                                        <button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
                                    </div>
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="push20"></div>
        <div class="portlet portlet-bordered">
            <div class="portlet-body">
                <div class="login-form">
                    <div class="push50"></div>

                    <div class="col-lg-12">

                        <div class="no-more-tables">

                            <table class="table table-bordered table-striped table-hover tablesorter buscavel">

                                <thead>
                                    <tr>
                                        <th class="{sorter:false}" width="40"></th>
                                        <th>Código</th>
                                        <th>Empresa</th>
                                        <th>Cód. Empresa</th>
                                        <th>Usuário</th>
                                        <th>MSG</th>
                                    </tr>
                                </thead>
                                <tbody id="relatorioConteudo">
                                    <?php
                                    $sql = "SELECT 1 from SENHAS_PARCEIRO WHERE 1=1
                                    $andFiltro
                                    ";

                                    $retorno = mysqli_query($connAdm->connAdm(), $sql);
                                    $totalitens_por_pagina = mysqli_num_rows($retorno);
                                    $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                                    // fnEscreve($numPaginas);

                                    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
                                    //echo $inicio;


                                    $sql = "SELECT SENHAS_PARCEIRO.*,
                                                EMPRESAS.NOM_FANTASI 
                                            from SENHAS_PARCEIRO
                                            left join empresas ON SENHAS_PARCEIRO.COD_EMPRESA = empresas.COD_EMPRESA
                                            WHERE 1=1
                                            $andFiltro
                                            LIMIT $inicio,$itens_por_pagina";

                                    //fnEscreve($sql);
                                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                    $count = 0;
                                    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

                                        $count++;
                                        $tipo = "";

                                        echo "
                                        <tr>
                                            <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                                            <td>" . $qrBuscaModulos['COD_SENHAPARC'] . "</td>
                                            <td>" . $qrBuscaModulos['NOM_FANTASI'] . "</td>
                                            <td>" . $qrBuscaModulos['COD_EMPRESA'] . "</td>
                                            <td>" . $qrBuscaModulos['DES_USUARIO'] . "</td>
                                            <td>" . $qrBuscaModulos['MSG_RETORNO'] . "</td>
                                        </tr>

                                        <input type='hidden' id='ret_COD_SENHAPARC_" . $count . "' value='" . $qrBuscaModulos['COD_SENHAPARC'] . "'>
                                        <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrBuscaModulos['COD_EMPRESA'] . "'>
                                        <input type='hidden' id='ret_COD_PARCOMU_" . $count . "' value='" . $qrBuscaModulos['COD_PARCOMU'] . "'>
                                        <input type='hidden' id='ret_DES_USUARIO_" . $count . "' value='" . $qrBuscaModulos['DES_USUARIO'] . "'>
                                        <input type='hidden' id='ret_DES_AUTHKEY_" . $count . "' value='" . $qrBuscaModulos['DES_AUTHKEY'] . "'>
                                        <input type='hidden' id='ret_DES_AUTHKEY2_" . $count . "' value='" . $qrBuscaModulos['DES_AUTHKEY2'] . "'>
                                        <input type='hidden' id='ret_DES_CLIEXT_" . $count . "' value='" . $qrBuscaModulos['DES_CLIEXT'] . "'>
                                        <input type='hidden' id='ret_COD_LISTAEXT_" . $count . "' value='" . $qrBuscaModulos['COD_LISTAEXT'] . "'>
                                        <input type='hidden' id='ret_LOG_ATIVO_" . $count . "' value='" . $qrBuscaModulos['LOG_ATIVO'] . "'>
                                        ";
                                    }
                                    ?>

                                </tbody>
                                <tfoot>
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

                    <div class="push"></div>
                </div>
            </div>
        </div>
        <!-- fim Portlet -->
    </div>
</div>

<div class="push20"></div>

<div id="relScripts"></div>

<script type="text/javascript">
    //Barra de pesquisa essentials ------------------------------------------------------
    $(document).ready(function(e) {
        var value = $('#INPUT').val().toLowerCase().trim();
        if (value) {
            $('#CLEARDIV').show();
        } else {
            $('#CLEARDIV').hide();
        }
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#", "");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #VAL_PESQUISA').val(param);
            $('#INPUT').focus();
        });

        $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function() {
            $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
        });

        $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function() {
            $("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
        });

        $('#CLEAR').click(function() {
            $('#INPUT').val('');
            $('#INPUT').focus();
            $('#CLEARDIV').hide();
            if ("<?= $filtro ?>" != "") {
                location.reload();
            } else {
                var value = $('#INPUT').val().toLowerCase().trim();
                if (value) {
                    $('#CLEARDIV').show();
                } else {
                    $('#CLEARDIV').hide();
                }
                $(".buscavel tr").each(function(index) {
                    if (!index) return;
                    $(this).find("td").each(function() {
                        var id = $(this).text().toLowerCase().trim();
                        var sem_registro = (id.indexOf(value) == -1);
                        $(this).closest('tr').toggle(!sem_registro);
                        return sem_registro;
                    });
                });
            }
        });

        // $('#SEARCH').click(function(){
        // 	$('#formulario').submit();
        // });


    });

    function buscaRegistro(el) {
        var filtro = $('#search_concept').text().toLowerCase();

        if (filtro == "sem filtro") {
            var value = $(el).val().toLowerCase().trim();
            if (value) {
                $('#CLEARDIV').show();
            } else {
                $('#CLEARDIV').hide();
            }
            $(".buscavel tr").each(function(index) {
                if (!index) return;
                $(this).find("td").each(function() {
                    var id = $(this).text().toLowerCase().trim();
                    var sem_registro = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!sem_registro);
                    return sem_registro;
                });
            });
        }
    }

    //-----------------------------------------------------------------------------------
    $(document).ready(function() {
        var numPaginas = <?php echo $numPaginas; ?>;
        if (numPaginas != 0) {
            carregarPaginacao(numPaginas);
        }

        $("#COD_PARCOMU").change(function() {

            var parceiro = $("#COD_PARCOMU").val();

            $("#DES_USUARIO").removeAttr("required");
            $(".LBL_USUARIO").removeClass("required");
            $(".LBL_AUTHKEY").removeClass("required");
            $("#DES_AUTHKEY").removeAttr("required");
            $(".LBL_AUTHKEY2").removeClass("required");
            $("#DES_AUTHKEY2").removeAttr("required");
            $("#DES_CLIEXT").removeAttr("required");
            $(".LBL_CLIEXT").removeClass("required");
            $("#COD_LISTAEXT").removeAttr("required").fadeOut(1);
            $(".LBL_LISTAEXT").removeClass("required").fadeOut(1);
            $("#DES_AUTHKEY2").removeAttr("required");
            $(".LBL_AUTHKEY2").removeClass("required");
            $(".LBL_CLIEXT").addClass("required").text("Cliente Externo");
            $(".LBL_LISTAEXT").addClass("required").text("Cod. Externo Lista");
            $("#chave2").fadeOut(1);
            // alert(parceiro);

            if (parceiro == 15) {

                $("#DES_USUARIO").prop("required", true);
                $(".LBL_USUARIO").addClass("required");
                $("#DES_AUTHKEY").prop("required", true);
                $(".LBL_AUTHKEY").addClass("required");
                $("#DES_AUTHKEY2").prop("required", true);
                $(".LBL_AUTHKEY2").addClass("required");
                $("#DES_CLIEXT").prop("required", true);
                $(".LBL_CLIEXT").addClass("required");
                $("#COD_LISTAEXT").fadeIn(1).prop("required", true);
                $(".LBL_LISTAEXT").fadeIn(1).addClass("required");

            } else if (parceiro == 16 || parceiro == 17) {
                $("#DES_AUTHKEY2").prop("required", true);
                $(".LBL_AUTHKEY2").addClass("required");
                $("#chave2").fadeIn(1);
            } else if (parceiro == 22) {
                $("#DES_CLIEXT").prop("required", true).val("");
                $("#COD_LISTAEXT").prop("required", true).fadeIn(1).val("");
                $(".LBL_CLIEXT").addClass("required").text("Telefone Token");
                $(".LBL_LISTAEXT").addClass("required").text("Telefone Lote").fadeIn(1);
            }

            // $('#formulario').validator('validate');
            $("#formulario #hHabilitado").val('S');

        });
    });

    function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "ajxsenhaParceiros.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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


    // $("#COD_EMPRESA").change(function() {

    //     var idEmp = $('#COD_EMPRESA').val();
    //     buscaCombo(idEmp,0);

    // });

    // function buscaCombo(idEmp,index){
    //     $.ajax({
    //         type: "POST",
    //         url: "ajxGerenciadorSenha.php",
    //         data: { ajxEmp:idEmp},
    //         beforeSend:function(){
    //             $('#relUsuarios').html('<div class="loading" style="width: 100%;"></div>');
    //             $('#relUnivend').html('<div class="loading" style="width: 100%;"></div>');
    //         },
    //         success:function(data){
    //             console.log(data);  
    //             $('#relUsuarios').html($('#relatorioUsu',data));
    //             $('#relUnivend').html($('#relatorioUni',data));
    //             if(index != 0){
    //                 retornaForm(index);
    //             }
    //             $(".chosen-select-deselect").chosen({allow_single_deselect:true});
    //         }
    //         // error:function(){
    //         //     $('#relatorioUsu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Empresa não encontrada...</p>');
    //         // }
    //     });
    // }

    function retornaForm(index) {

        $("#formulario #COD_SENHAPARC").val($("#ret_COD_SENHAPARC_" + index).val());
        $("#formulario #COD_PARCOMU").val($("#ret_COD_PARCOMU_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val()).trigger("chosen:updated");
        $("#formulario #DES_CLIEXT").val($("#ret_DES_CLIEXT_" + index).val()).trigger("chosen:updated");
        $("#formulario #DES_AUTHKEY").val($("#ret_DES_AUTHKEY_" + index).val());
        $("#formulario #DES_USUARIO").val($("#ret_DES_USUARIO_" + index).val());
        $("#formulario #COD_LISTAEXT").val($("#ret_COD_LISTAEXT_" + index).val());


        if ($("#ret_LOG_ATIVO_" + index).val() == 'S') {
            $('#formulario #LOG_ATIVO').prop('checked', true);
        } else {
            $('#formulario #LOG_ATIVO').prop('checked', false);
        }

        if ($("#ret_COD_PARCOMU_" + index).val() == 16 || $("#ret_COD_PARCOMU_" + index).val() == 17) {
            $("#DES_AUTHKEY2").prop("required", true);
            $(".LBL_AUTHKEY2").addClass("required");
            $("#chave2").fadeIn(1);
            $("#formulario #DES_AUTHKEY2").val($("#ret_DES_AUTHKEY2_" + index).val());
        } else {
            $("#DES_AUTHKEY2").removeAttr("required");
            $(".LBL_AUTHKEY2").removeClass("required");
            $("#chave2").fadeOut(1);
        }

        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
        //alert($('#COD_ECOMMERCE').val());
    }
</script>