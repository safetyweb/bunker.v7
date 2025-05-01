<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_dobrada = fnLimpaCampoZero($_REQUEST['COD_DOBRADA']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $cod_univend = fnLimpaCampoZero(fnDecode($_REQUEST['COD_UNIVEND']));
        $cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
        $cod_estado = fnLimpaCampoZero($_REQUEST['COD_ESTADO']);
        $cod_municipio = fnLimpaCampoZero($_REQUEST['COD_MUNICIPIO']);
        $des_partido = fnLimpaCampo($_REQUEST['DES_PARTIDO']);
        $des_cargo = fnLimpaCampo($_REQUEST['DES_CARGO']);

        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
        $nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $MODULO = $_GET['mod'];
        $COD_MODULO = fndecode($_GET['mod']);

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {

                case 'CAD':

                    $sql = "INSERT INTO DOBRADA_CAMPANHA(
                                            COD_EMPRESA,
                                            COD_UNIVEND,
                                            COD_CLIENTE,
                                            COD_ESTADO,
                                            COD_MUNICIPIO,
                                            DES_PARTIDO,
                                            DES_CARGO,
                                            COD_USUCADA
                                        ) VALUES(
                                            $cod_empresa,
                                            $cod_univend,
                                            $cod_cliente,
                                            $cod_estado,
                                            $cod_municipio,
                                            '$des_partido',
                                            '$des_cargo',
                                            $cod_usucada
                                        )";

                    // echo $sql;

                    $arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

                    if (!$arrayProc) {

                        $cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
                    }

                break;

                case 'ALT':

                     $sql = "UPDATE DOBRADA_CAMPANHA SET
                                            COD_UNIVEND = $cod_univend,
                                            COD_CLIENTE = $cod_cliente,
                                            COD_ESTADO = $cod_estado,
                                            COD_MUNICIPIO = $cod_municipio,
                                            DES_PARTIDO = '$des_partido',
                                            DES_CARGO = '$des_cargo',
                                            COD_ALTERAC = $cod_usucada,
                                            DAT_ALTERAC = NOW()
                            WHERE COD_EMPRESA = $cod_empresa
                            AND COD_DOBRADA = $cod_dobrada";

                    //echo $sql;

                    $arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

                    if (!$arrayProc) {

                        $cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível alterar o registro : $cod_erro";
                    }

                break;

                case 'EXC':

                    $sql = "UPDATE DOBRADA_CAMPANHA SET
                                            COD_EXCLUSA = $cod_usucada,
                                            DAT_EXCLUSA = NOW()
                            WHERE COD_EMPRESA = $cod_empresa
                            AND COD_DOBRADA = $cod_dobrada";

                    //echo $sql;

                    $arrayProc = mysqli_query(connTemp($cod_empresa), $sql);

                    if (!$arrayProc) {

                        $cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível excluir o registro : $cod_erro";
                    }

                break;

            }

            if ($cod_erro == 0 || $cod_erro == "") {
                $msgTipo = 'alert-success';
            } else {
                $msgTipo = 'alert-danger';
            }

        }
    }
}


//busca dados da url    
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($adm, $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    //fnEscreve('entrou else');
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

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
                $abaCli = 1757;

                //menu superior - empresas
                $abaEmpresa = 1797;
			
				//menu abas
				include "abasEmpresas.php";
                // $cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idC']));

                ?>

                <div class="push30"></div>

                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Dados Gerais</legend>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Campanha</label>
                                        <select data-placeholder="Selecione a campanha" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" required>
                                            <option value=""></option>                  
                                            <?php
                                                  
                                                $sql = "select COD_UNIVEND, NOM_FANTASI, NOM_UNIVEND from unidadevenda where COD_EMPRESA = '".$cod_empresa."' AND LOG_ESTATUS = 'S' order by trim(NOM_FANTASI) "; 
                                                $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
                                                //fnEscreve($sql);
                                                
                                                while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery))
                                                  {
                                                    if ($cod_univend == $qrListaUnidades['COD_UNIVEND']){ $selecionado = "selected";}else{$selecionado = "";}   
                                                    
                                                    //verifica acesso master
                                                    if ($usuReportAdm == "N"){
                                                        if (strlen(strstr($cod_univendUsu,$qrListaUnidades['COD_UNIVEND']))>0){ $lojaAtiva = "";}else{$lojaAtiva = "disabled";}
                                                    } else  {$lojaAtiva = " ";}
                                                    echo"
                                                          <option value='".fnEncode($qrListaUnidades['COD_UNIVEND'])."' ".$selecionado." ".$lojaAtiva.">".$qrListaUnidades['NOM_FANTASI']."</option> 
                                                        "; 
                                                      }                                         
                                            ?>  
                                        </select>   
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-4" id="div_parceiro" style="display: none;">
                                    <label for="inputName" class="control-label required">Parceiro</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBuscaResp" id="btnBuscaResp" style="height:35px;" class="btn btn-primary btn-sm addBox" data-title="Busca Parceiro"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
                                        </span>
                                        <input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" value="" maxlength="50" readonly="" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
                                        <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Partido</label>
                                        <input type="tel" class="form-control input-sm" name="DES_PARTIDO" id="DES_PARTIDO" value="<?=$des_partido?>" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Cargo</label>
                                            <select data-placeholder="Selecione o cargo" name="DES_CARGO" id="DES_CARGO" class="chosen-select-deselect" required>
                                                <option value="pref">Prefeito</option>
                                                <option value="ver">Vereador</option>
                                                <option value="depE">Deputado Estadual</option>
                                                <option value="depF">Deputado Federal</option>
                                                <option value="sen">Senador</option>
                                                <option value="pres">Presidente</option>
                                            </select>
                                        <div class="help-block with-errors"></div>
                                        <script type="text/javascript">$("#formulario #DES_CARGO").val("<?=$des_cargo?>").trigger("chosen:updated");</script>
                                    </div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label <?= $endObriga ?>">Estado</label>
                                        <select data-placeholder="Selecione um estado" name="COD_ESTADO" id="COD_ESTADO" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <?php
                                            $sql = "SELECT COD_ESTADO, UF FROM ESTADO ORDER BY UF";
                                            $arrayEstado = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            while ($qrEstado = mysqli_fetch_assoc($arrayEstado)) {
                                            ?>
                                                <option value="<?= $qrEstado['COD_ESTADO'] ?>"><?= $qrEstado['UF'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <script type="text/javascript">
                                            $('#COD_ESTADO').val("<?= $cod_estado ?>").trigger("chosen:updated");
                                        </script>

                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2" id="relatorioCidade">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label <?= $endObriga ?> required">Cidade</label>
                                        <select data-placeholder="Selecione uma cidade" name="COD_MUNICIPIO" id="COD_MUNICIPIO" class="chosen-select-deselect">
                                            <option value=""></option>
                                        </select>
                                        <script type="text/javascript">
                                            $('#COD_MUNICIPIO').val("<?= $cod_municipio ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                        </fieldset>

                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                            <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

                        </div>
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="COD_MUNICIPIO_AUX" id="COD_MUNICIPIO_AUX" value="">
                        <input type="hidden" name="REFRESH_COMBO" id="REFRESH_COMBO" value="N">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa;?>">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal;?>">
                        <input type="hidden" name="AND_PARCEIRO" id="AND_PARCEIRO" value="<?=$andParceiro;?>">
                        <input type="hidden" name="AND_MUNICIPIO" id="AND_MUNICIPIO" value="<?=$andMunicipio;?>">
                        <input type="hidden" name="COD_DOBRADA" id="COD_DOBRADA" value="">
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                        <div class="push30"></div>
                    </form>

                    <div class="push50"></div>

                    <div class="col-lg-12">

                        <div class="no-more-tables">

                            <form name="formLista">

                                <table class="table table-bordered table-striped table-hover tableSorter">
                                    <thead>
                                        <tr>
                                            <th class="{ sorter: false }" width="40"></th>
                                            <th>Código</th>
                                            <th>Campanha</th>
                                            <th>Parceiro</th>
                                            <th>Partido</th>
                                            <th>Cargo</th>
                                            <th>Estado</th>
                                            <th>Município</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        $sql = "SELECT DC.*, 
                                                UV.NOM_FANTASI, 
                                                CL.NOM_CLIENTE, 
                                                ES.UF, 
                                                MU.NOM_MUNICIPIO 
                                                FROM DOBRADA_CAMPANHA DC
                                                INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = DC.COD_CLIENTE
                                                INNER JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = DC.COD_UNIVEND
                                                LEFT JOIN ESTADO ES ON ES.COD_ESTADO = DC.COD_ESTADO
                                                LEFT JOIN MUNICIPIOS MU ON MU.COD_MUNICIPIO = DC.COD_MUNICIPIO
                                                WHERE DC.COD_EMPRESA = $cod_empresa 
                                                AND DC.COD_EXCLUSA = 0";

                                        // fnEscreve($sql);

                                        $arrayQuery = mysqli_query(connTemp($cod_empresa), $sql);

                                        $count = 0;

                                        while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

                                            $count++;

                                            switch ($qrBuscaModulos[DES_CARGO]) {
                                                case 'pref':
                                                    $cargoPolitico = "PREFEITO";
                                                break;

                                                case 'ver':
                                                    $cargoPolitico = "VEREADOR";
                                                break;

                                                case 'depE':
                                                    $cargoPolitico = "DEPUTADO ESTADUAL";
                                                break;

                                                case 'depF':
                                                    $cargoPolitico = "DEPUTADO FEDERAL";
                                                break;

                                                case 'sen':
                                                    $cargoPolitico = "SENADOR";
                                                break;
                                                
                                                default:
                                                    $cargoPolitico = "PRESIDENTE";
                                                break;
                                            }

                                            echo "
                                                    <tr>
                                                        <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                                                        <td>" . $qrBuscaModulos['COD_DOBRADA'] . "</td>
                                                        <td>" . $qrBuscaModulos['NOM_FANTASI'] . "</td>
                                                        <td>" . $qrBuscaModulos['NOM_CLIENTE'] . "</td>
                                                        <td>" . $qrBuscaModulos['DES_PARTIDO'] . "</td>
                                                        <td>" . $cargoPolitico . "</td>
                                                        <td>" . $qrBuscaModulos['UF'] . "</td>
                                                        <td>" . $qrBuscaModulos['NOM_MUNICIPIO'] . "</td>
                                                    </tr>
                                                    <input type='hidden' id='ret_COD_DOBRADA_" . $count . "' value='" . $qrBuscaModulos['COD_DOBRADA'] . "'>
                                                    <input type='hidden' id='ret_COD_UNIVEND_" . $count . "' value='" . fnEncode($qrBuscaModulos['COD_UNIVEND']) . "'>
                                                    <input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . $qrBuscaModulos['COD_CLIENTE'] . "'>
                                                    <input type='hidden' id='ret_NOM_CLIENTE_" . $count . "' value='" . $qrBuscaModulos['NOM_CLIENTE'] . "'>
                                                    <input type='hidden' id='ret_DES_PARTIDO_" . $count . "' value='" . $qrBuscaModulos['DES_PARTIDO'] . "'>
                                                    <input type='hidden' id='ret_DES_CARGO_" . $count . "' value='" . $qrBuscaModulos['DES_CARGO'] . "'>
                                                    <input type='hidden' id='ret_COD_ESTADO_" . $count . "' value='" . $qrBuscaModulos['COD_ESTADO'] . "'>
                                                    <input type='hidden' id='ret_COD_MUNICIPIO_" . $count . "' value='" . $qrBuscaModulos['COD_MUNICIPIO'] . "'>
                                                    ";

                                        }

                                        ?>

                                    </tbody>
                                </table>

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

<script type="text/javascript">

    $(function(){

        var codEstado = "<?php echo $cod_estado; ?>";

        if (codEstado != 0 && codEstado != "") {
            carregaComboCidades(codEstado, "<?php echo $cod_municipio; ?>");
        }

        $("#COD_ESTADO").change(function() {
            cod_estado = $(this).val();
            carregaComboCidades(cod_estado, "");
            estado = $("#COD_ESTADO option:selected").text();
            $('#COD_ESTADOF').val(estado);
            $('#NOM_CIDADEC').val('');
        });

        $("#COD_UNIVEND").on("change", function(){
            if($(this).val() != ""){
                $("#btnBuscaResp").removeAttr("data-url").attr("data-url","action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode($cod_cliente)?>&idu="+$(this).val()+"&pop=true");
                $("#div_parceiro").fadeIn("fast");
            }else{
                $("#btnBuscaResp").removeAttr("data-url");
                $("#div_parceiro").fadeOut("fast");
            }
        });

    });

    function carregaComboCidades(cod_estado, cod_municipio) {
        $.ajax({
            method: 'POST',
            url: 'ajxComboMunicipio.php?id=<?= fnEncode($cod_empresa) ?>',
            data: {
                COD_ESTADO: cod_estado
            },
            beforeSend: function() {
                $('#relatorioCidade').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                $("#relatorioCidade").html(data);
                if(cod_municipio != "" && cod_municipio != 0){
                    $("#formulario #COD_MUNICIPIO").val(cod_municipio).trigger("chosen:updated");
                }else if ("<?php echo $cod_municipio; ?>" != 0 && "<?php echo $cod_municipio; ?>" != "" ) {
                    $("#formulario #COD_MUNICIPIO").val("<?php echo $cod_municipio; ?>").trigger("chosen:updated");
                }

                // $('#formulario').validator('validate');
                //alert(cod_municipio);
                //alert("<?php echo $cod_municipio; ?>");
            }
        });
    }

    function retornaForm(index) {
        $("#formulario #COD_DOBRADA").val($("#ret_COD_DOBRADA_" + index).val());
        $("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_" + index).val());
        $("#formulario #NOM_CLIENTE").val($("#ret_NOM_CLIENTE_" + index).val());
        $("#formulario #DES_PARTIDO").val($("#ret_DES_PARTIDO_" + index).val()).trigger("chosen:updated");
        $("#formulario #DES_CARGO").val($("#ret_DES_CARGO_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_ESTADO").val($("#ret_COD_ESTADO_" + index).val()).trigger("chosen:updated");
        carregaComboCidades($("#ret_COD_ESTADO_" + index).val(), $("#ret_COD_MUNICIPIO_" + index).val());
        $("#formulario #COD_MUNICIPIO").val($("#ret_COD_MUNICIPIO_" + index).val());
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }

</script>