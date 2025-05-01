<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_totem = "";
$des_logo = "";
$des_alinham = "";
$des_imgback = "";
$des_imgback_mob = "";
$cod_layout = "";
$log_corpers = "";
$cor_backbar = "";
$cor_backpag = "";
$cor_titulos = "";
$cor_textos = "";
$cor_botao = "";
$cor_botaoon = "";
$log_ticket = "";
$des_paghome = "";
$val_inativo = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaSiteTotem = "";
$check_CORPERS = "";
$check_TICKET = "";
$destinoHome = "";
$qrBuscaUsuTeste = "";
$log_usuario = "";
$des_senhaus = "";
$sql2 = "";
$qrPlayer = "";
$numPlayer = "";
$formBack = "";
$abaEmpresa = "";
$qrLayout = "";
$tip_consulta = "";


$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_totem = fnLimpaCampoZero(@$_REQUEST['COD_TOTEM']);
        $cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
        $des_logo = fnLimpaCampo(@$_REQUEST['DES_LOGO']);
        $des_alinham = fnLimpaCampo(@$_REQUEST['DES_ALINHAM']);
        $des_imgback = fnLimpaCampo(@$_REQUEST['DES_IMGBACK']);
        $des_imgback_mob = fnLimpaCampo(@$_REQUEST['DES_IMGBACK_MOB']);
        $cod_layout = fnLimpaCampo(@$_REQUEST['COD_LAYOUT']);

        if (empty(@$_REQUEST['LOG_CORPERS'])) {
            $log_corpers = 'N';
        } else {
            $log_corpers = @$_REQUEST['LOG_CORPERS'];
        }

        $cor_backbar = fnLimpaCampo(@$_REQUEST['COR_BACKBAR']);
        $cor_backpag = fnLimpaCampo(@$_REQUEST['COR_BACKPAG']);
        $cor_titulos = fnLimpaCampo(@$_REQUEST['COR_TITULOS']);
        $cor_textos = fnLimpaCampo(@$_REQUEST['COR_TEXTOS']);
        $cor_botao = fnLimpaCampo(@$_REQUEST['COR_BOTAO']);
        $cor_botaoon = fnLimpaCampo(@$_REQUEST['COR_BOTAOON']);

        if (empty(@$_REQUEST['LOG_TICKET'])) {
            $log_ticket = 'N';
        } else {
            $log_ticket = @$_REQUEST['LOG_TICKET'];
        }

        $des_paghome = fnLimpaCampo(@$_REQUEST['DES_PAGHOME']);
        $val_inativo = fnLimpaCampoZero(@$_REQUEST['VAL_INATIVO']);

        $nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $MODULO = @$_GET['mod'];
        $COD_MODULO = fndecode(@$_GET['mod']);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '') {

            $sql = "CALL SP_ALTERA_TOTEM (         
				 '" . $cod_totem . "', 
				 '" . $cod_empresa . "', 
				 '" . $des_logo . "', 
				 '" . $des_alinham . "', 
                 '" . $des_imgback . "', 
				 '" . $des_imgback_mob . "', 
				 '" . $cod_layout . "', 
				 '" . $log_corpers . "', 
				 '" . $cor_backbar . "', 
				 '" . $cor_backpag . "', 
				 '" . $cor_titulos . "', 
				 '" . $cor_textos . "', 
				 '" . $cor_botao . "', 
                 '" . $cor_botaoon . "', 
                 '" . $log_ticket . "', 
                 '" . $des_paghome . "', 
                 '" . $val_inativo . "' 
				) ";

            // fnEscreve($sql);
            $arrayProc = mysqli_query($conn, $sql);

            if (!$arrayProc) {

                $cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
            }

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
                    }
                    break;
                case 'ALT':
                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível alterar o registro : $cod_erro";
                    }
                    break;
                case 'EXC':
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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode(@$_GET['id']);
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

//busca dados da tabela
$sql = "SELECT * FROM TOTEM WHERE COD_EMPRESA = $cod_empresa";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaSiteTotem = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotem)) {
    //fnEscreve("entrou if");

    $cod_totem = $qrBuscaSiteTotem['COD_TOTEM'];
    $des_alinham = $qrBuscaSiteTotem['DES_ALINHAM'];
    $des_logo = $qrBuscaSiteTotem['DES_LOGO'];
    $des_imgback = $qrBuscaSiteTotem['DES_IMGBACK'];
    $des_imgback_mob = $qrBuscaSiteTotem['DES_IMGBACK_MOB'];
    $cod_layout = $qrBuscaSiteTotem['COD_LAYOUT'];

    if ($qrBuscaSiteTotem['LOG_CORPERS'] == "N") {
        $check_CORPERS = '';
    } else {
        $check_CORPERS = "checked";
    }

    if ($qrBuscaSiteTotem['LOG_TICKET'] == "N") {
        $check_TICKET = '';
    } else {
        $check_TICKET = "checked";
    }

    $cor_backbar = $qrBuscaSiteTotem['COR_BACKBAR'];
    $cor_backpag = $qrBuscaSiteTotem['COR_BACKPAG'];
    $cor_titulos = $qrBuscaSiteTotem['COR_TITULOS'];
    $cor_textos = $qrBuscaSiteTotem['COR_TEXTOS'];
    $cor_botao = $qrBuscaSiteTotem['COR_BOTAO'];
    $cor_botaoon = $qrBuscaSiteTotem['COR_BOTAOON'];

    $des_paghome = $qrBuscaSiteTotem['DES_PAGHOME'];
    if ($des_paghome == "index") {
        $destinoHome = "";
    } else {
        $destinoHome = "banner.do";
    }
    $val_inativo = $qrBuscaSiteTotem['VAL_INATIVO'];
} else {
    //default se vazio
    //fnEscreve("entrou else");

    $cod_totem = 0;
    $des_logo = "";
    $des_alinham = "left";
    $des_imgback = "";
    $cod_layout = 4;
    $check_CORPERS = '';
    $check_TICKET = '';

    $cor_backbar = "34495e";
    $cor_backpag = "f2f3f4";
    $cor_titulos = "#34495e";
    $cor_textos = "#34495e";
    $cor_botao = "#0092d8";
    $cor_botaoon = "#48c9b0";
    $des_paghome = "";
}
//busca usuário modelo	
$sql = "SELECT * FROM  USUARIOS
			WHERE LOG_ESTATUS='S' AND
				  COD_EMPRESA = $cod_empresa AND
				  COD_TPUSUARIO=10  limit 1 ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($adm, $sql);
$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
    $log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
    $des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
}

$sql2 = "select count(1) as numPlayer from totem_players where cod_empresa=$cod_empresa";

//fnEscreve($sql2);
$arrayQuery = mysqli_query($conn, $sql2);
$qrPlayer = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
    $numPlayer = $qrPlayer['numPlayer'];
}



//fnEscreve($log_usuario);
//fnEscreve($numPlayer);

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
                $abaEmpresa = 1188;

                switch ($_SESSION["SYS_COD_SISTEMA"]) {
                    case 14: //rede duque
                        include "abasEmpresaDuque.php";
                        break;
                    case 15: //quiz
                        include "abasEmpresaQuiz.php";
                        break;
                    case 16: //gabinete
                        include "abasGabinete.php";
                        break;
                    case 18: //mais cash
                        include "abasMaisCash.php";
                        break;
                    default;
                        include "abasEmpresaConfig.php";
                        break;
                }

                ?>

                <div class="push30"></div>

                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">&nbsp;</label>
                                        <h4>totem.bunker.mk</h4>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="push15"></div>
                                    <a href="#" class="btn btn-default btn-sm btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1316) ?>&id=<?php echo fnEncode($cod_empresa); ?>&pop=true" data-title="Templates do Totem / <?php echo $nom_empresa; ?>"><i class="far fa-images" aria-hidden="true"></i>&nbsp; Templates </a>
                                </div>

                                <div class="col-md-2">
                                    <div class="push15"></div>
                                    <a href="#" class="btn btn-info btn-sm btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1263) ?>&id=<?php echo fnEncode($cod_empresa); ?>&pop=true" data-title="Totem Players / <?php echo $nom_empresa; ?>"><i class="fa fa-play-circle" aria-hidden="true"></i>&nbsp; Players &nbsp;(<?php echo $numPlayer; ?>) </a>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Layout</label>
                                        <select data-placeholder="Selecione uma skin" name="COD_LAYOUT" id="COD_LAYOUT" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <?php

                                            $sql = "select COD_LAYOUT, DES_LAYOUT from LAYOUTS order by DES_LAYOUT";
                                            $arrayQuery = mysqli_query($adm, $sql);

                                            while ($qrLayout = mysqli_fetch_assoc($arrayQuery)) {
                                                echo "
                                                    <option value='" . $qrLayout['COD_LAYOUT'] . "'>" . $qrLayout['DES_LAYOUT'] . "</option> 
                                                ";
                                            }
                                            ?>
                                        </select>
                                        <script>
                                            $("#formulario #COD_LAYOUT").val("<?php echo $cod_layout; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Alinhamento do Logo</label>
                                        <select data-placeholder="Selecione um alinhamento" name="DES_ALINHAM" id="DES_ALINHAM" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <option value="left">Esquerda</option>
                                            <option value="center">Centro</option>
                                            <option value="right">Direita</option>
                                        </select>
                                        <script>
                                            $("#formulario #DES_ALINHAM").val("<?php echo $des_alinham; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label for="inputName" class="control-label required">Logotipo</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_LOGO" extensao="img"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="hidden" name="DES_LOGO" id="DES_LOGO" value="<?php echo $des_logo; ?>">
                                        <input type="text" name="LOGO" id="LOGO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_logo); ?>">
                                    </div>
                                    <span class="help-block">(.png 585px X 125px)</span>
                                </div>

                                <div class="col-md-2">
                                    <label for="inputName" class="control-label">Img. de Fundo (Horizontal)</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMGBACK" extensao="img"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="hidden" name="DES_IMGBACK" id="DES_IMGBACK" value="<?php echo $des_imgback; ?>">
                                        <input type="text" name="IMGBACK" id="IMGBACK" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_imgback); ?>">
                                    </div>
                                    <span class="help-block">(.jpg 1920px X 1080px)</span>
                                </div>

                                <div class="col-md-2">
                                    <label for="inputName" class="control-label">Img. de Fundo (Vertical, Retrato)</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMGBACK_MOB" extensao="img"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="hidden" name="DES_IMGBACK_MOB" id="DES_IMGBACK_MOB" value="<?php echo $des_imgback_mob; ?>">
                                        <input type="text" name="IMGBACK_MOB" id="IMGBACK_MOB" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_imgback_mob); ?>">
                                    </div>
                                    <span class="help-block">(.jpg 1080px X 1920px)</span>
                                </div>


                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Configuração</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Utilizar Cores Personalizadas</label>
                                        <div class="push5"></div>
                                        <label class="switch">
                                            <input type="checkbox" name="LOG_CORPERS" id="LOG_CORPERS" class="switch" value="S" <?php echo $check_CORPERS; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <!-- <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Gerar Ticket de Ofertas</label> 
                                        <div class="push5"></div>
                                        <label class="switch">
                                            <input type="checkbox" name="LOG_TICKET" id="LOG_TICKET" class="switch" value="S" <?php echo $check_TICKET; ?> >
                                            <span></span>
                                        </label>
                                    </div>
                                </div> -->

                                <!-- <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Página Inicial</label>
                                            <select data-placeholder="Selecione uma página inicial" name="DES_PAGHOME" id="DES_PAGHOME" class="chosen-select-deselect" required>
                                                <option value=""></option>
                                                <option value="index">Pesquisa de CPF/CNPJ</option>
                                                <option value="banner">Banner Rotativo</option>
                                            </select>
                                            <script>$("#formulario #DES_PAGHOME").val("<?php echo $des_paghome; ?>").trigger("chosen:updated"); </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div> -->

                                <!--  <div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo de Consulta</label>
											<select data-placeholder="Selecione um tipo" name="TIP_CONSULTA" id="TIP_CONSULTA" class="chosen-select-deselect" required>
												<option value=""></option>
                                                <option value="cpf">CPF/CNPJ</option>
												<option value="cpf_cartao">CPF/CNPJ + Cartão</option>
											</select>
											<script>$("#formulario #TIP_CONSULTA").val("<?php echo $tip_consulta; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
								</div> -->

                                <!-- <div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tempo de Inatividade</label>
											<select data-placeholder="Selecione uma inatividade" name="VAL_INATIVO" id="VAL_INATIVO" class="chosen-select-deselect" required>
												<option value=""></option>
												<option value="0">Nenhum</option>
												<option value="5">5 segundos</option>
												<option value="15">15 segundos</option>
												<option value="30">30 segundos</option>
												<option value="60">60 segundos</option>
											</select>
											<script>$("#formulario #VAL_INATIVO").val("<?php echo $val_inativo; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
								</div> -->

                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Cores Personalizadas</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cor da Barra Superior</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_BACKBAR" id="COR_BACKBAR" maxlength="100" value="<?php echo $cor_backbar; ?>">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Cor do Fundo da Página</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_BACKPAG" id="COR_BACKPAG" maxlength="100" value="<?php echo $cor_backpag; ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Títulos</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_TITULOS" id="COR_TITULOS" maxlength="100" value="<?php echo $cor_titulos; ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Textos</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_TEXTOS" id="COR_TEXTOS" maxlength="100" value="<?php echo $cor_textos; ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Cor Botão</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_BOTAO" id="COR_BOTAO" value="<?php echo $cor_botao; ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Cor Botão Hover</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_BOTAOON" id="COR_BOTAOON" value="<?php echo $cor_botaoon; ?>">
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                            <?php if ($cod_totem == 0) { ?>
                                <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <?php } else { ?>
                                <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <?php } ?>

                        </div>

                        <input type="hidden" name="COD_TOTEM" id="COD_TOTEM" value="<?php echo $cod_totem; ?>">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

                        <div class="push5"></div>

                    </form>

                    <div class="push50"></div>

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

<link rel="stylesheet" href="fonts/font-awesome-4.2.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css" />

<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.2.0.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script type="text/javascript">
    $(document).ready(function() {

        //chosen
        $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
        $('#formulario').validator();

        //color picker
        $('.pickColor').minicolors({
            control: $(this).attr('data-control') || 'hue',
            theme: 'bootstrap'
        });

    });

    function retornaForm(index) {
        $("#formulario #COD_MAQUINA").val($("#ret_COD_MAQUINA_" + index).val());
        $("#formulario #DES_MAQUINA").val($("#ret_DES_MAQUINA_" + index).val());
        $("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }

    $('.upload').on('click', function(e) {
        var idField = 'arqUpload_' + $(this).attr('idinput');
        var typeFile = $(this).attr('extensao');

        $.dialog({
            title: 'Arquivo',
            content: '' +
                '<form method = "POST" enctype = "multipart/form-data">' +
                '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
                '<div class="progress" style="display: none">' +
                '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
                '   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
                '</div>' +
                '<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
                '</form>'
        });
    });

    function uploadFile(idField, typeFile) {
        var formData = new FormData();
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

        $('.progress').show();
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                $('#btnUploadFile').addClass('disabled');
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        if (percentComplete !== 100) {
                            $('.progress-bar').css('width', percentComplete + "%");
                            $('.progress-bar > span').html(percentComplete + "%");
                        }
                    }
                }, false);
                return xhr;
            },
            url: '../uploads/uploaddoc.php',
            type: 'POST',
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function(data) {

                var data = JSON.parse(data);

                $('.jconfirm-open').fadeOut(300, function() {
                    $(this).remove();
                });
                if (data.success) {
                    $('#' + idField.replace("arqUpload_DES_", "")).val(nomeArquivo);
                    $('#' + idField.replace("arqUpload_", "")).val(data.nome_arquivo);
                    $.alert({
                        title: "Mensagem",
                        content: "Upload feito com sucesso",
                        type: 'green'
                    });

                } else {
                    $.alert({
                        title: "Erro ao efetuar o upload",
                        content: data,
                        type: 'red'
                    });
                }
            }
        });
    }
</script>