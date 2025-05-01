<?php

//echo fnDebug('true');

$cod_conface = 0;
$des_emailus = "";
$des_senhaus = "";
$itens_por_pagina = 20;
$pagina = 1;
$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_senhaparc = fnLimpaCampoZero($_REQUEST['COD_SENHAPARC']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
        $des_authkey = fnLimpaCampo($_REQUEST['DES_AUTHKEY']);
        $des_token = fnLimpaCampo($_REQUEST['DES_TOKEN']);
        $num_celular = fnLimpaCampo($_REQUEST['NUM_CELULAR']);
        // $des_base64 = fnLimpaCampo($_REQUEST['DES_BASE64']);
        $cod_parcomu = fnLimpaCampoZero($_REQUEST['COD_PARCOMU']);

        if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
        $array = array("");

        if ($opcao != '') {

            if ($opcao != 'EXC') {

                include "_system/whatsapp/wsp.php";

                $session = $cod_empresa;

                if($cod_univend != 0){
                    $session = $cod_empresa."_".$cod_univend;
                }

                $connect=FnIniciaSessao($session,"$des_authkey");
                $des_token = $connect[token];

                // echo '<pre>';
                // print_r($connect);
                // echo '</pre>';

                $connectQR=FnGeraQRCODESessao($session,$des_token);

                // echo '<pre>';
                // print_r($connectQR);
                // echo '</pre>'; 

                // echo '<pre>';
                // fnEscreve($session);
                // fnEscreve($des_token);
                // echo '</pre>';

                $des_base64 = $connectQR[qrcode];

            }else{
                // $connectQR=FnCloseSessao($cod_empresa,$des_token);
            }

            $log_login = 'N';

            if($des_base64 != ""){
                $log_login = 'S';
            }

                //mensagem de retorno
            switch ($opcao) {
                case 'CAD':

                $sql = "INSERT INTO SENHAS_WHATSAPP(
                    COD_EMPRESA,
                    COD_UNIVEND,
                    DES_AUTHKEY,
                    NUM_CELULAR,
                    COD_PARCOMU,
                    LOG_ATIVO,
                    DES_TOKEN,
                    DES_BASE64,
                    LOG_LOGIN,
                    DAT_LOGIN,
                    COD_USUCADA
                    ) VALUES(
                    $cod_empresa,
                    $cod_univend,
                    '$des_authkey',
                    '$num_celular',
                    $cod_parcomu,
                    '$log_ativo',
                    '$des_token',
                    '$des_base64',
                    '$log_login',
                    NOW(),
                    $cod_usucada
                )";

                    //fnEscreve($sql);
                    mysqli_query($connAdm->connAdm(), $sql);
                        // fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

                    break;
                    case 'ALT':

                    $sql = "UPDATE SENHAS_WHATSAPP SET
                    COD_UNIVEND=$cod_univend,
                    DES_AUTHKEY='$des_authkey',
                    NUM_CELULAR='$num_celular',
                    COD_PARCOMU=$cod_parcomu,
                    LOG_ATIVO='$log_ativo',
                    DES_BASE64='$des_base64',
                    COD_USUALT=$cod_usucada,
                    DAT_ALTERAC=NOW()
                    WHERE COD_SENHAPARC = $cod_senhaparc";

                        //fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());
                        // fnEscreve($sql);
                    mysqli_query($connAdm->connAdm(), $sql);

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

                    break;
                    case 'EXC':

                    $sql = "DELETE FROM SENHAS_WHATSAPP WHERE COD_SENHAPARC = $cod_senhaparc";
                        // fnEscreve($sql);
                    mysqli_query($connAdm->connAdm(), $sql);

                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

                    break;
                }
                $msgTipo = 'alert-success';
            }
        }
    }

    ?>

    <style>
   /* body #popModal .modal-dialog { 
        margin-top: 50px;
    }*/
</style>

<div class="push30"></div> 

<div class="row">				

    <div class="col-md-12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="glyphicon glyphicon-calendar"></i>
                    <span class="text-primary"><?php echo $NomePg; ?></span>
                </div>
                <?php

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
                //menu senhas comunicação
                $abaComunica = 1948;
                include "abasSenhasComunicacao.php";
                ?>

                <div class="push30"></div> 			

                <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                    <fieldset>
                        <legend>Dados Gerais</legend> 

                        <div class="row">

                           <div class="col-md-2">
                            <div class="form-group">
                             <label for="inputName" class="control-label">Comunicação Ativa</label> 
                             <div class="push5"></div>
                             <label class="switch">
                              <input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?php echo $checkLOG_ATIVO; ?> >
                              <span></span>
                          </label>
                      </div>
                  </div>						

              </div>

              <div class="push10"></div>

              <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputName" class="control-label required">Empresa</label>
                        <select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect" required>
                            <option value=""></option>
                            <?php
                            $sql = "select COD_EMPRESA, NOM_EMPRESA from EMPRESAS order by NOM_EMPRESA ";
                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

                            while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)) {
                                echo"<option value='" . $qrListaTipoEntidade['COD_EMPRESA'] . "'>" . $qrListaTipoEntidade['NOM_EMPRESA'] . "</option>";
                            }
                            ?>  
                        </select>

                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputName" class="control-label required">Unidade</label>
                        <div id="relUnivend">
                            <select data-placeholder="Selecione uma unidade" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect">
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputName" class="control-label required">Parceiro Comunicação</label>
                        <select data-placeholder="Selecione um parceiro" name="COD_PARCOMU" id="COD_PARCOMU" class="chosen-select-deselect" required>
                            <option value=""></option>
                            <?php
                            $sql = "SELECT COD_PARCOMU, DES_PARCOMU FROM PARCEIRO_COMUNICACAO WHERE COD_TPCOM = 6 ORDER BY DES_PARCOMU ";
                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

                            while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)) {
                                echo"<option value='" . $qrListaTipoEntidade['COD_PARCOMU'] . "'>" . $qrListaTipoEntidade['DES_PARCOMU'] . "</option>";
                            }
                            ?>	
                        </select>	
                        <div class="help-block with-errors"></div>
                    </div>
                </div>  

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputName" class="control-label">Celular</label>
                        <input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" id="NUM_CELULAR" value="">
                    </div>
                    <div class="help-block with-errors"></div>
                </div> 	

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputName" class="control-label">Autkey</label>
                        <input type="text" class="form-control input-sm" name="DES_AUTHKEY" id="DES_AUTHKEY" maxlength="100" value="">
                    </div>
                    <div class="help-block with-errors"></div>
                </div>

            </div>

                        <!-- <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Base 64</label>
                                    <textarea name="DES_BASE64" id="DES_BASE64" class="form-control" rows="4"></textarea>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                        </div> -->

                        <div class="push10"></div>

                    </fieldset>


                    <div class="push10"></div>
                    <hr>	
                    <div class="form-group text-right col-lg-12">

                        <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                        <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
                        <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

                    </div>

                    <input type="hidden" name="opcao" id="opcao" value="">
                    <input type="hidden" name="COD_SENHAPARC" id="COD_SENHAPARC" value="<?php echo $cod_senhaparc; ?>" /> 
                    <input type="hidden" name="DES_TOKEN" id="DES_TOKEN" value="<?php echo $des_token; ?>" /> 
                    <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" /> 
                    <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		

                    <div class="push5"></div> 

                </form>

                <!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->
                <div class="push30"></div>

                <div class="row">
                    <form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

                        <div class="col-md-offset-4 col-md-4 col-xs-12">
                            <div class="push20"></div>

                            <div class="input-group activeItem">
                                <div class="input-group-btn search-panel">
                                    <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
                                        <span id="search_concept">Sem filtro</span>&nbsp;
                                        <span class="far fa-angle-down"></span>                                                             
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li class="divisor"><a href="#">Sem filtro</a></li>
                                        <!-- <li class="divider"></li> -->
                                        <li><a href="#NOM_EMPRESA">Razão social</a></li>
                                        <li><a href="#NOM_FANTASI">Nome fantasia</a></li>
                                        <li><a href="#NUM_CGCECPF">CNPJ</a></li>
                                        <li><a href="#NOM_SEGMENT">Segmento</a></li>
                                        <li><a href="#DES_SISTEMA">Sistema</a></li>
                                    </ul>
                                </div>
                                <input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">         
                                <input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?=$val_pesquisa?>" onkeyup="buscaRegistro(this)">
                                <div class="input-group-btn"id="CLEARDIV" style="<?=$esconde?>">
                                    <button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
                                </div>
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
                                </div>
                            </div>

                        </div>
                            
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" /> 
                        <!-- <input type="hidden" name="COD_SISTEMAS" id="COD_SISTEMAS" value="" /> -->
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                    </form>
                    
                </div>

                <div class="push30"></div>

                <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

                <div class="col-lg-12">

                    <div class="no-more-tables">

                        <table class="table table-bordered table-striped table-hover tablesorter buscavel">

                            <thead>
                                <tr>
                                    <th class="{sorter:false}" width="40"></th>
                                    <th>Código</th>
                                    <th>Empresa</th>
                                    <th>Unidade</th>
                                    <th>Celular</th>
                                    <th class="{sorter:false}">Ativo</th>
                                </tr>
                            </thead>
                            <tbody id="relatorioConteudo">
                                <?php
                                $sql = "SELECT 1 from SENHAS_WHATSAPP WHERE 1=1
                                $andFiltro
                                ";

                                $retorno = mysqli_query($connAdm->connAdm(), $sql);
                                $totalitens_por_pagina = mysqli_num_rows($retorno);
                                $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                                // fnEscreve($numPaginas);

                                $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
                                //echo $inicio;


                                $sql = "SELECT SENHAS_WHATSAPP.*,
                                EMPRESAS.NOM_FANTASI NOM_EMPRESA,
                                UNIDADEVENDA.NOM_FANTASI NOM_UNIVEND 
                                from SENHAS_WHATSAPP
                                left join EMPRESAS ON SENHAS_WHATSAPP.COD_EMPRESA = EMPRESAS.COD_EMPRESA
                                left join UNIDADEVENDA ON SENHAS_WHATSAPP.COD_UNIVEND = UNIDADEVENDA.COD_UNIVEND
                                WHERE 1=1
                                $andFiltro";

                                // fnEscreve($sql);
                                $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                $count = 0;
                                while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

                                    $count++;
                                    $tipo = "";
                                    // fnEscreve($sessionLoop);
                                    ?>
                                    <tr>
                                        <td><input type='radio' name='radio1' onclick='retornaForm("<?=$count?>")'></td>
                                        <td><?=$qrBuscaModulos['COD_SENHAPARC']?></td>
                                        <td><?=$qrBuscaModulos['NOM_EMPRESA']?></td>
                                        <td><?=$qrBuscaModulos['NOM_UNIVEND']?></td>
                                        <td><?=$qrBuscaModulos['NUM_CELULAR']?></td>

                                        <?php
                                        if ($qrBuscaModulos['LOG_ATIVO'] == 'S') {
                                            echo "<td class='fal fa-check' aria-hidden='true'></td>";
                                        } else {
                                            echo "<td> </td>";
                                        }
                                        ?>
                                        <td class="text-center">
                                            <small>
                                                <div class="btn-group dropdown dropleft">
                                                    <button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        ações &nbsp;
                                                        <span class="fas fa-caret-down"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                                        <li><a href="javascript:void(0)" onclick='sessao("<?=$qrBuscaModulos[COD_SENHAPARC]?>","status")'>Status </a></li>
                                                        <li><a href="javascript:void(0)" onclick='sessao("<?=$qrBuscaModulos[COD_SENHAPARC]?>","qrcode")'>QrCode </a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="javascript:void(0)" onclick='sessao("<?=$qrBuscaModulos[COD_SENHAPARC]?>","encerrar")'>Encerrar Sessão </a></li>
                                                        <!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
                                                    </ul>
                                                </div>
                                            </small>
                                        </td>
                                    </tr>

                                    <input type='hidden' id='ret_COD_SENHAPARC_<?=$count?>' value="<?=$qrBuscaModulos['COD_SENHAPARC']?>">
                                    <input type='hidden' id='ret_LOG_ATIVO_<?=$count?>' value="<?=$qrBuscaModulos['LOG_ATIVO']?>">
                                    <input type='hidden' id='ret_COD_UNIVEND_<?=$count?>' value="<?=$qrBuscaModulos['COD_UNIVEND']?>">
                                    <input type='hidden' id='ret_COD_PARCOMU_<?=$count?>' value="<?=$qrBuscaModulos['COD_PARCOMU']?>">
                                    <input type='hidden' id='ret_COD_EMPRESA_<?=$count?>' value="<?=$qrBuscaModulos['COD_EMPRESA']?>">
                                    <input type='hidden' id='ret_NUM_CELULAR_<?=$count?>' value="<?=$qrBuscaModulos['NUM_CELULAR']?>">
                                    <input type='hidden' id='ret_DES_AUTHKEY_<?=$count?>' value="<?=$qrBuscaModulos['DES_AUTHKEY']?>">
                                    <input type='hidden' id='ret_DES_BASE64_<?=$count?>' value="<?=$qrBuscaModulos['DES_BASE64']?>">
                                    <input type='hidden' id='ret_DES_TOKEN_<?=$count?>' value="<?=$qrBuscaModulos['DES_TOKEN']?>">
                                    <?php 
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

        <!-- modal -->
        <div class="modal fade" id="popModal" tabindex='-1'>
            <div class="modal-dialog" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="push100"></div>
                        <div class="push100"></div>
                        <center><img class="img-responsive" id="IMG_CODE" src="<?=$des_base64?>" width="40%" /></center>
                        <div class="push100"></div>
                        <div class="push100"></div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- fim Portlet -->
</div>				

<div class="push20"></div>


<script type="text/javascript">

    //Barra de pesquisa essentials ------------------------------------------------------
        $(document).ready(function(e){
            var value = $('#INPUT').val().toLowerCase().trim();
            if(value){
                $('#CLEARDIV').show();
            }else{
                $('#CLEARDIV').hide();
            }
            $('.search-panel .dropdown-menu').find('a').click(function(e) {
                e.preventDefault();
                var param = $(this).attr("href").replace("#","");
                var concept = $(this).text();
                $('.search-panel span#search_concept').text(concept);
                $('.input-group #VAL_PESQUISA').val(param);
                $('#INPUT').focus();
            });

            $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function(){
                $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
            });

            $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function(){
                $("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
            });

            $('#CLEAR').click(function(){
                $('#INPUT').val('');
                $('#INPUT').focus();
                $('#CLEARDIV').hide();
                if("<?=$filtro?>" != ""){
                    location.reload();
                }else{
                    var value = $('#INPUT').val().toLowerCase().trim();
                    if(value){
                        $('#CLEARDIV').show();
                    }else{
                        $('#CLEARDIV').hide();
                    }
                    $(".buscavel tr").each(function (index) {
                        if (!index) return;
                        $(this).find("td").each(function () {
                            var id = $(this).text().toLowerCase().trim();
                            var sem_registro = (id.indexOf(value) == -1);
                            $(this).closest('tr').toggle(!sem_registro);
                            return sem_registro;
                        });
                    });
                }
            });

            // $('#SEARCH').click(function(){
            //  $('#formulario').submit();
            // });
                
            
        });

        function buscaRegistro(el){
            var filtro = $('#search_concept').text().toLowerCase();

            if(filtro == "sem filtro"){
                var value = $(el).val().toLowerCase().trim();
                if(value){
                    $('#CLEARDIV').show();
                }else{
                    $('#CLEARDIV').hide();
                }
                $(".buscavel tr").each(function (index) {
                    if (!index) return;
                    $(this).find("td").each(function () {
                        var id = $(this).text().toLowerCase().trim();
                        var sem_registro = (id.indexOf(value) == -1);
                        $(this).closest('tr').toggle(!sem_registro);
                        return sem_registro;
                    });
                });
            }
        }

    //-----------------------------------------------------------------------------------

    $(document).ready(function(){
        var idEmp = '<?php echo $cod_empresa; ?>';
        buscaCombo(idEmp,$('#COUNT').val());
    });

    $("#COD_EMPRESA").change(function() {

        var idEmp = $('#COD_EMPRESA').val();
        buscaCombo(idEmp,0);
        
    });

    function buscaCombo(idEmp,index){
        $.ajax({
            type: "POST",
            url: "ajxGerenciadorSenha.php",
            data: { ajxEmp:idEmp},
            beforeSend:function(){
                $('#relUsuarios').html('<div class="loading" style="width: 100%;"></div>');
                $('#relUnivend').html('<div class="loading" style="width: 100%;"></div>');
            },
            success:function(data){
                console.log(data);
                $('#relUsuarios').html($('#relatorioUsu',data));
                $('#relScripts').html($('#relatorioScripts',data));
                $('#relUnivend').html($('#relatorioUni',data));
                if(index != 0){
                    retornaForm(index);
                }
                $(".chosen-select-deselect").chosen({allow_single_deselect:true});
            }
            // error:function(){
            //     $('#relatorioUsu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Empresa não encontrada...</p>');
            // }
        });
    }

    $(function(){


        <?php if($des_base64 != ""){ ?>
            $('#popModal .modal-title').text('QrCode');
            // $('#popModal').modal();
            $('.modal').not('#popModalNotifica').appendTo("body").modal('show');
        <?php } ?>
        

        var SPMaskBehavior = function(val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };

        $('.sp_celphones').mask(SPMaskBehavior, spOptions);

    });

    function retornaForm(index) {
        $("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_PARCOMU").val($("#ret_COD_PARCOMU_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_SENHAPARC").val($("#ret_COD_SENHAPARC_" + index).val());
        $("#formulario #DES_TOKEN").val($("#ret_DES_TOKEN_" + index).val());
        $("#formulario #NUM_CELULAR").val($("#ret_NUM_CELULAR_" + index).val());
        $("#formulario #DES_AUTHKEY").val($("#ret_DES_AUTHKEY_" + index).val());
        $("#formulario #DES_BASE64").val($("#ret_DES_BASE64_" + index).val());

        if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);} 
        else {$('#formulario #LOG_ATIVO').prop('checked', false);}			

        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }

    function sessao(cod_senhaparc,tipo){

        let msg1="Encerrar sessão?",
        msg2="Sessão encerrada.";

        if(tipo == "status"){
            msg1 = "Verificar status?";
        }else if(tipo == "qrcode"){
            msg1 = "Gerar QrCode?";
        }

        $.alert({
            title: "Aviso",
            type: 'orange',
            content: msg1,
            buttons: {
                "Sim": {
                   btnClass: 'btn-danger',
                   action: function(){
                    $.ajax({
                        type: "POST",
                        url: "ajxGerenciadorSenhaWhatsApp.do?id=<?=fnEncode($cod_empresa)?>&opcao="+tipo,
                        data: {COD_SENHAPARC:cod_senhaparc},
                        success:function(data){

                            console.log(data);

                            if(tipo == "status"){
                                if(data){
                                    msg2 = "Status: Conectado.";
                                }else{
                                    msg2 = "Status: Desconectado.";
                                }
                            }else if(tipo == "qrcode"){
                                if(data == "0"){
                                    msg2 = "QrCode não disponível";
                                }else{
                                    msg2 = "";
                                }
                            }else{
                                if(data){
                                    msg2 = "Sessão encerrada.";
                                }else{
                                    msg2 = "Erro ao encerrar sessão.";
                                }
                            }

                            if(msg2 != ""){
                                $.alert({
                                    title: "Aviso",
                                    content: msg2
                                });
                            }else{
                                $('#IMG_CODE').attr('src', data);
                                $('#popModal .modal-title').text('QrCode');
                                $('.modal').not('#popModalNotifica').appendTo("body").modal('show');
                            }

                        },
                        error:function(){
                         $.alert({
                            title: "Aviso",
                            type: 'red',
                            content: "Erro na solicitação."
                        });
                     }
                 });
                }
            },
            "Não": {
               action: function(){

               }
           }
       }
   });
    }

</script>	