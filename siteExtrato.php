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
$cod_extrato = "";
$cod_dominio = "";
$des_dominio = "";
$des_logo = "";
$des_icolog = "";
$des_banner = "";
$des_email = "";
$des_urlios = "";
$des_urlandro = "";
$log_vantagem = "";
$txt_vantagem = "";
$log_home = "";
$check_home = "";
$check_vantagem = "";
$txt_cadastro = "";
$log_regula = "";
$check_regula = "";
$txt_regula = "";
$log_lojas = "";
$check_lojas = "";
$txt_lojas = "";
$log_faq = "";
$check_faq = "";
$txt_faq = "";
$log_extrato = "";
$check_extrato = "";
$txt_extrato = "";
$log_contato = "";
$check_contato = "";
$txt_contato = "";
$log_premios = "";
$txt_premios = "";
$log_sobre = "";
$log_contraste = "";
$txt_sobre = "";
$cor_barra = "";
$cor_txtbarra = "";
$cor_site = "";
$cor_titulos = "";
$cor_textos = "";
$cor_rodapebg = "";
$cor_rodape = "";
$cor_botao = "";
$cor_botaoon = "";
$cor_txtbotao = "";
$des_vantagem = "";
$ico_bloco1 = "";
$ico_bloco2 = "";
$ico_bloco3 = "";
$tit_bloco1 = "";
$tit_bloco2 = "";
$tit_bloco3 = "";
$des_bloco1 = "";
$des_bloco2 = "";
$des_bloco3 = "";
$des_regras = "";
$des_sobre = "";
$des_programa = "";
$destino_home = "";
$tp_ordenac = "";
$tam_texto = "";
$log_cadastro = "";
$log_termos = "";
$check_cadastro = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$sql1 = "";
$sqlTxt = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$sqlControle = "";
$arrayControle = [];
$qrControle = "";
$des_img_g = "";
$des_img = "";
$des_imgmob = "";
$qrBuscaSiteExtrato = "";
$disable_home = "";
$check_termos = "";
$check_premios = "";
$check_sobre = "";
$check_contraste = "";
$formBack = "";
$abaEmpresa = "";
$msgDominioTipo = "";
$msgDominio = "";


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

        $cod_extrato = fnLimpaCampoZero(@$_REQUEST['COD_EXTRATO']);
        $cod_dominio = fnLimpaCampoZero(@$_REQUEST['COD_DOMINIO']);
        $cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
        $des_dominio = fnLimpaCampo(@$_REQUEST['DES_DOMINIO']);
        $des_logo = fnLimpaCampo(@$_REQUEST['DES_LOGO']);
        $des_icolog = fnLimpaCampo(@$_REQUEST['DES_ICOLOG']);
        $des_banner = fnLimpaCampo(@$_REQUEST['DES_BANNER']);
        $des_email = fnLimpaCampo(@$_REQUEST['DES_EMAIL']);
        $des_urlios = fnLimpaCampo(@$_REQUEST['DES_URLIOS']);
        $des_urlandro = fnLimpaCampo(@$_REQUEST['DES_URLANDRO']);

        //$log_vantagem = fnLimpaCampo(@$_REQUEST['LOG_VANTAGEM']);
        $txt_vantagem = fnLimpaCampo(@$_REQUEST['TXT_CADASTRO']);

        if (empty(@$_REQUEST['LOG_HOME'])) {
            $log_home = 'N';
            $check_home = '';
        } else {
            $log_home = @$_REQUEST['LOG_HOME'];
            $check_home = "checked";
        }
        if (empty(@$_REQUEST['LOG_VANTAGEM'])) {
            $log_vantagem = 'N';
            $check_vantagem = '';
        } else {
            $log_vantagem = @$_REQUEST['LOG_VANTAGEM'];
            $check_vantagem = "checked";
        }
        $txt_vantagem = fnLimpaCampo(@$_REQUEST['TXT_VANTAGEM']);
        $txt_cadastro = fnLimpaCampo(@$_REQUEST['TXT_CADASTRO']);

        //$log_regula = fnLimpaCampo(@$_REQUEST['LOG_REGULA']);
        if (empty(@$_REQUEST['LOG_REGULA'])) {
            $log_regula = 'N';
            $check_regula = '';
        } else {
            $log_regula = @$_REQUEST['LOG_REGULA'];
            $check_regula = "checked";
        }
        $txt_regula = fnLimpaCampo(@$_REQUEST['TXT_REGULA']);

        //$log_lojas = fnLimpaCampo(@$_REQUEST['LOG_LOJAS']);
        if (empty(@$_REQUEST['LOG_LOJAS'])) {
            $log_lojas = 'N';
            $check_lojas = '';
        } else {
            $log_lojas = @$_REQUEST['LOG_LOJAS'];
            $check_lojas = "checked";
        }
        $txt_lojas = fnLimpaCampo(@$_REQUEST['TXT_LOJAS']);

        //$log_faq = fnLimpaCampo(@$_REQUEST['LOG_FAQ']);
        if (empty(@$_REQUEST['LOG_FAQ'])) {
            $log_faq = 'N';
            $check_faq = '';
        } else {
            $log_faq = @$_REQUEST['LOG_FAQ'];
            $check_faq = "checked";
        }
        $txt_faq = fnLimpaCampo(@$_REQUEST['TXT_FAQ']);

        //$log_extrato = fnLimpaCampo(@$_REQUEST['LOG_EXTRATO']);
        if (empty(@$_REQUEST['LOG_EXTRATO'])) {
            $log_extrato = 'N';
            $check_extrato = '';
        } else {
            $log_extrato = @$_REQUEST['LOG_EXTRATO'];
            $check_extrato = "checked";
        }
        $txt_extrato = fnLimpaCampo(@$_REQUEST['TXT_EXTRATO']);

        //$log_contato = fnLimpaCampo(@$_REQUEST['LOG_CONTATO']);
        if (empty(@$_REQUEST['LOG_CONTATO'])) {
            $log_contato = 'N';
            $check_contato = '';
        } else {
            $log_contato = @$_REQUEST['LOG_CONTATO'];
            $check_contato = "checked";
        }
        $txt_contato = fnLimpaCampo(@$_REQUEST['TXT_CONTATO']);

        //$log_contato = fnLimpaCampo(@$_REQUEST['LOG_PREMIOS']);
        if (empty(@$_REQUEST['LOG_PREMIOS'])) {
            $log_premios = 'N';
        } else {
            $log_premios = @$_REQUEST['LOG_PREMIOS'];
        }
        $txt_premios = fnLimpaCampo(@$_REQUEST['TXT_PREMIOS']);

        if (empty(@$_REQUEST['LOG_SOBRE'])) {
            $log_sobre = 'N';
        } else {
            $log_sobre = @$_REQUEST['LOG_SOBRE'];
        }

        if (empty(@$_REQUEST['LOG_CONTRASTE'])) {
            $log_contraste = 'N';
        } else {
            $log_contraste = @$_REQUEST['LOG_CONTRASTE'];
        }
        $txt_sobre = fnLimpaCampo(@$_REQUEST['TXT_SOBRE']);

        $cor_barra = fnLimpaCampo(@$_REQUEST['COR_BARRA']);
        $cor_txtbarra = fnLimpaCampo(@$_REQUEST['COR_TXTBARRA']);
        $cor_site = fnLimpaCampo(@$_REQUEST['COR_SITE']);
        $cor_titulos = fnLimpaCampo(@$_REQUEST['COR_TITULOS']);
        $cor_textos = fnLimpaCampo(@$_REQUEST['COR_TEXTOS']);
        $cor_rodapebg = fnLimpaCampo(@$_REQUEST['COR_RODAPEBG']);
        $cor_rodape = fnLimpaCampo(@$_REQUEST['COR_RODAPE']);
        $cor_botao = fnLimpaCampo(@$_REQUEST['COR_BOTAO']);
        $cor_botaoon = fnLimpaCampo(@$_REQUEST['COR_BOTAOON']);
        $cor_txtbotao = fnLimpaCampo(@$_REQUEST['COR_TXTBOTAO']);
        $des_vantagem = fnLimpaCampo(@$_REQUEST['DES_VANTAGEM']);
        $ico_bloco1 = fnLimpaCampo(@$_REQUEST['ICO_BLOCO1']);
        $ico_bloco2 = fnLimpaCampo(@$_REQUEST['ICO_BLOCO2']);
        $ico_bloco3 = fnLimpaCampo(@$_REQUEST['ICO_BLOCO3']);
        $tit_bloco1 = fnLimpaCampo(@$_REQUEST['TIT_BLOCO1']);
        $tit_bloco2 = fnLimpaCampo(@$_REQUEST['TIT_BLOCO2']);
        $tit_bloco3 = fnLimpaCampo(@$_REQUEST['TIT_BLOCO3']);
        $des_bloco1 = fnLimpaCampo(@$_REQUEST['DES_BLOCO1']);
        $des_bloco2 = fnLimpaCampo(@$_REQUEST['DES_BLOCO2']);
        $des_bloco3 = fnLimpaCampo(@$_REQUEST['DES_BLOCO3']);
        $des_regras = addslashes(htmlentities(@$_REQUEST['DES_REGRAS']));
        $des_sobre = addslashes(htmlentities(@$_REQUEST['DES_SOBRE']));
        $des_programa = fnLimpaCampo(@$_REQUEST['DES_PROGRAMA']);
        $destino_home = fnLimpaCampo(@$_REQUEST['DESTINO_HOME']);
        $tp_ordenac = fnLimpaCampo(@$_REQUEST['TP_ORDENAC']);
        $tam_texto = fnLimpaCampo(@$_REQUEST['TAM_TEXTO']);

        if (empty(@$_REQUEST['LOG_CADASTRO'])) {
            $log_cadastro = 'N';
        } else {
            $log_cadastro = @$_REQUEST['LOG_CADASTRO'];
        }

        if (empty(@$_REQUEST['LOG_TERMOS'])) {
            $log_termos = 'N';
        } else {
            $log_termos = @$_REQUEST['LOG_TERMOS'];
        }

        if ($log_cadastro == "N") {
            $check_cadastro = '';
        } else {
            $check_cadastro = "checked";
        }

        $nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $MODULO = @$_GET['mod'];
        $COD_MODULO = fndecode(@$_GET['mod']);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '') {

            //Verifica se existe o dominio, solicitado diogo Whatsapp
            if ($opcao == 'CAD') {
                $sql = "select COD_EMPRESA from DOMINIO WHERE DES_DOMINIO = '$des_dominio' ";

                $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
                if ($qrBuscaCodEmpresa = mysqli_fetch_assoc($arrayQuery)) {
                    $msgDominio = "O dominio <strong>$des_dominio</strong> já esta sendo usado";
                    $msgDominioTipo = 'alert-danger';
                    $des_dominio = "";
                }
            }

            $sql = "CALL SP_ALTERA_SITE_EXTRATO (         
            '" . $cod_extrato . "', 
            '" . $cod_empresa . "', 
            '" . $cod_dominio . "', 
            '" . $des_dominio . "', 
            '" . $des_logo . "', 
            '" . $des_icolog . "', 
            '" . $des_banner . "', 
            '" . $des_email . "', 
            '" . $log_home . "', 
            '" . $destino_home . "', 
            '" . $log_vantagem . "', 
            '" . $txt_vantagem . "', 
            '" . $log_regula . "', 
            '" . $txt_regula . "', 
            '" . $log_lojas . "', 
            '" . $txt_lojas . "', 
            '" . $log_faq . "', 
            '" . $txt_faq . "', 
            '" . $log_extrato . "', 
            '" . $txt_extrato . "', 
            '" . $log_contato . "', 
            '" . $txt_contato . "', 
            '" . $cor_barra . "', 
            '" . $cor_txtbarra . "', 
            '" . $cor_site . "', 
            '" . $cor_titulos . "', 
            '" . $cor_textos . "', 
            '" . $cor_rodapebg . "', 
            '" . $cor_rodape . "', 
            '" . $cor_botao . "', 
            '" . $cor_botaoon . "', 
            '" . $cor_txtbotao . "', 
            '" . $des_vantagem . "', 
            '" . $des_urlios . "', 
            '" . $des_urlandro . "', 
            '" . $ico_bloco1 . "', 
            '" . $ico_bloco2 . "', 
            '" . $ico_bloco3 . "', 
            '" . $tit_bloco1 . "', 
            '" . $tit_bloco2 . "', 
            '" . $tit_bloco3 . "', 
            '" . $des_bloco1 . "', 
            '" . $des_bloco2 . "', 
            '" . $des_bloco3 . "', 
            '" . $des_regras . "',    
            '" . $des_sobre . "',    
            '" . $des_programa . "',
            '" . $log_cadastro . "', 
            '" . $log_termos . "', 
            '" . $log_premios . "', 
            '" . $txt_premios . "',
            '" . $log_sobre . "', 
            '" . $txt_sobre . "',
            '" . $tam_texto . "', 
            '" . $log_contraste . "', 
            '" . $tp_ordenac . "' 
        ) ";

            // fnEscreve($sql);
            //fnTestesql(connTemp($cod_empresa, ''), $sql);
            $arrayProc = mysqli_query($conn, $sql);

            if (!$arrayProc) {

                $cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
            }

            //fnEscreve($cod_erro);
            //grava o noem do site no adm 
            $sql1 = "CALL SP_ALTERA_DOMINIO (
                '" . $des_dominio . "', 
                '" . $cod_empresa . "'   
            ) ";

            //echo $sql1;			
            mysqli_query($adm, trim($sql1));

            $sqlTxt = "UPDATE SITE_EXTRATO SET
            TXT_CADASTRO = '$txt_cadastro'
            WHERE COD_EMPRESA = $cod_empresa";

            // fnEscreve($sqlTxt);
            mysqli_query($conn, $sqlTxt);

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

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

$arrayControle = mysqli_query(connTemp($cod_empresa, ''), $sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

if (isset($qrControle['DES_IMG_G'])) {
    $des_img_g = $qrControle['DES_IMG_G'];
} else {
    $des_img_g = "";
}
if (isset($qrControle['DES_IMG'])) {
    $des_img = $qrControle['DES_IMG'];
} else {
    $des_img = "";
}
if (isset($qrControle['DES_IMGMOB'])) {
    $des_imgmob = $qrControle['DES_IMGMOB'];
} else {
    $des_imgmob = "";
}

//busca dados da tabela
$sql = "SELECT * FROM SITE_EXTRATO WHERE COD_EMPRESA = '" . $cod_empresa . "' ";
//fnEscreve($sql);
// fnEscreve('chegou');
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaSiteExtrato = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteExtrato)) {
    //fnEscreve("entrou if");
    $cod_extrato = $qrBuscaSiteExtrato['COD_EXTRATO'];
    $cod_dominio = $qrBuscaSiteExtrato['COD_DOMINIO'];
    $des_dominio = $qrBuscaSiteExtrato['DES_DOMINIO'];
    $des_logo = $qrBuscaSiteExtrato['DES_LOGO'];
    $des_icolog = $qrBuscaSiteExtrato['DES_ICOLOG'];
    $des_banner = $qrBuscaSiteExtrato['DES_BANNER'];
    $des_email = $qrBuscaSiteExtrato['DES_EMAIL'];
    $destino_home = $qrBuscaSiteExtrato['DESTINO_HOME'];
    //$log_vantagem = "checked";
    if ($qrBuscaSiteExtrato['LOG_HOME'] == "N") {
        $check_home = '';
        $disable_home = 'disabled';
    } else {
        $check_home = "checked";
    }
    if ($qrBuscaSiteExtrato['LOG_VANTAGEM'] == "N") {
        $check_vantagem = '';
    } else {
        $check_vantagem = "checked";
    }
    $txt_vantagem = $qrBuscaSiteExtrato['TXT_VANTAGEM'];
    //$log_regula = "checked";
    if ($qrBuscaSiteExtrato['LOG_REGULA'] == "N") {
        $check_regula = '';
    } else {
        $check_regula = "checked";
    }
    $txt_regula = $qrBuscaSiteExtrato['TXT_REGULA'];
    //$log_lojas = "checked";
    if ($qrBuscaSiteExtrato['LOG_LOJAS'] == "N") {
        $check_lojas = '';
    } else {
        $check_lojas = "checked";
    }
    $txt_lojas = $qrBuscaSiteExtrato['TXT_LOJAS'];
    //$log_faq = "checked";
    if ($qrBuscaSiteExtrato['LOG_FAQ'] == "N") {
        $check_faq = '';
    } else {
        $check_faq = "checked";
    }
    $txt_faq = $qrBuscaSiteExtrato['TXT_FAQ'];
    //$log_extrato = "checked";
    if ($qrBuscaSiteExtrato['LOG_EXTRATO'] == "N") {
        $check_extrato = '';
    } else {
        $check_extrato = "checked";
    }
    $txt_extrato = $qrBuscaSiteExtrato['TXT_EXTRATO'];
    //$log_contato = "checked";
    if ($qrBuscaSiteExtrato['LOG_CONTATO'] == "N") {
        $check_contato = '';
    } else {
        $check_contato = "checked";
    }
    $txt_contato = $qrBuscaSiteExtrato['TXT_CONTATO'];
    $txt_cadastro = $qrBuscaSiteExtrato['TXT_CADASTRO'];
    if ($qrBuscaSiteExtrato['LOG_CADASTRO'] == "N") {
        $check_cadastro = '';
    } else {
        $check_cadastro = "checked";
    }
    if ($qrBuscaSiteExtrato['LOG_TERMOS'] == "N") {
        $check_termos = '';
    } else {
        $check_termos = "checked";
    }
    if ($qrBuscaSiteExtrato['LOG_PREMIOS'] == "N") {
        $check_premios = '';
    } else {
        $check_premios = "checked";
    }
    $txt_premios = $qrBuscaSiteExtrato['TXT_PREMIOS'];

    if ($qrBuscaSiteExtrato['LOG_SOBRE'] == "N") {
        $check_sobre = '';
    } else {
        $check_sobre = "checked";
    }

    if ($qrBuscaSiteExtrato['LOG_CONTRASTE'] == "N") {
        $check_contraste = '';
    } else {
        $check_contraste = "checked";
    }
    $txt_sobre = $qrBuscaSiteExtrato['TXT_SOBRE'];

    $cor_titulos = $qrBuscaSiteExtrato['COR_TITULOS'];
    $cor_barra = $qrBuscaSiteExtrato['COR_BARRA'];
    $cor_txtbarra = $qrBuscaSiteExtrato['COR_TXTBARRA'];
    $cor_site = $qrBuscaSiteExtrato['COR_SITE'];
    $cor_textos = $qrBuscaSiteExtrato['COR_TEXTOS'];
    $cor_rodapebg = $qrBuscaSiteExtrato['COR_RODAPEBG'];
    $cor_rodape = $qrBuscaSiteExtrato['COR_RODAPE'];
    $cor_botao = $qrBuscaSiteExtrato['COR_BOTAO'];
    $cor_botaoon = $qrBuscaSiteExtrato['COR_BOTAOON'];
    $cor_txtbotao = $qrBuscaSiteExtrato['COR_TXTBOTAO'];
    $des_vantagem = $qrBuscaSiteExtrato['DES_VANTAGEM'];
    $des_urlios = $qrBuscaSiteExtrato['DES_URLIOS'];
    $des_urlandro = $qrBuscaSiteExtrato['DES_URLANDRO'];
    $ico_bloco1 = $qrBuscaSiteExtrato['ICO_BLOCO1'];
    $ico_bloco2 = $qrBuscaSiteExtrato['ICO_BLOCO2'];
    $ico_bloco3 = $qrBuscaSiteExtrato['ICO_BLOCO3'];
    $tit_bloco1 = $qrBuscaSiteExtrato['TIT_BLOCO1'];
    $des_bloco1 = $qrBuscaSiteExtrato['DES_BLOCO1'];
    $tit_bloco2 = $qrBuscaSiteExtrato['TIT_BLOCO2'];
    $des_bloco2 = $qrBuscaSiteExtrato['DES_BLOCO2'];
    $tit_bloco3 = $qrBuscaSiteExtrato['TIT_BLOCO3'];
    $des_bloco3 = $qrBuscaSiteExtrato['DES_BLOCO3'];
    $des_regras = $qrBuscaSiteExtrato['DES_REGRAS'];
    $des_sobre = $qrBuscaSiteExtrato['DES_SOBRE'];
    $des_programa = $qrBuscaSiteExtrato['DES_PROGRAMA'];
    $log_sobre = $qrBuscaSiteExtrato['LOG_SOBRE'];
    $txt_sobre = $qrBuscaSiteExtrato['TXT_SOBRE'];
    $tam_texto = $qrBuscaSiteExtrato['TAM_TEXTO'];
    $tp_ordenac = $qrBuscaSiteExtrato['TP_ORDENAC'];
} else {
    //default se vazio
    //fnEscreve("entrou else");

    $cod_extrato = 0;
    $cod_dominio = 2;
    $des_dominio = "";
    $des_logo = "";
    $des_icolog = "";
    $des_banner = "";
    $des_email = "";
    $check_vantagem = "checked";
    $check_cadastro = "checked";
    $check_termos = "checked";
    $check_premios = "";
    $check_sobre = "";
    $check_contraste = "";
    $txt_sobre = "O Programa";
    $txt_cadastro = "Cadastre-se";
    $txt_vantagem = "Vantagens";
    $check_regula = "checked";
    $txt_regula = "Regulamento";
    $check_lojas = "checked";
    $txt_lojas = "Lojas";
    $check_faq = "";
    $txt_faq = "FAQ";
    $check_extrato = "checked";
    $txt_extrato = "Extrato";
    $check_contato = "checked";
    $txt_contato = "Contato";
    $txt_premios = "Prêmios";
    $cor_titulos = "#34495e";
    $cor_barra = "#fff";
    $cor_txtbarra = "#34495e";
    $cor_site = "#fff";
    $cor_textos = "#34495e";
    $cor_rodapebg = "#34495e";
    $cor_rodape = "#fff";
    $cor_botao = "#0092d8";
    $cor_botaoon = "#48c9b0";
    $cor_txtbotao = "#fff";
    $des_vantagem = "Participe agora e comece a ganhar.";
    $ico_bloco1 = "icons/money.svg";
    $ico_bloco2 = "icons/pig.svg";
    $ico_bloco3 = "icons/iphone.svg";
    $tit_bloco1 = "Ganhe dinheiro para suas próximas compras";
    $des_bloco1 = "Você acumula dinheiro e pode trocar por descontos ou prêmios.";
    $tit_bloco2 = "Alcance outros Níveis";
    $des_bloco2 = "Quanto mais você participa, mais dinheiro você acumula.";
    $tit_bloco3 = "Acesso Total";
    $des_bloco3 = "Transparência: Veja seu histórico de compras, ganhos e resgates.";
    $des_regras = "";
    $des_sobre = "";
    $des_programa = "";
    $tp_ordenac = "DESC";
}
// fnEscreve($log_contraste);

//fnMostraForm();
?>

<script type="text/javascript" src="js/plugins/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        // General options
        mode: "textareas",
        setup: function(ed) {
            // set the editor font size
            ed.onInit.add(function(ed) {
                ed.getBody().style.fontSize = '13px';
            });
        },
        language: "pt",
        theme: "advanced",
        plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1: "undo,redo,|,bold,italic,underline,strikethrough,nonbreaking,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,forecolor,backcolor,|,copy,paste,cut,|,pastetext,pasteword,|,search,replace,|,link,unlink,anchor,image,|,hr,removeformat,visualaid,|,cleanup,preview,print,code,fullscreen",
        theme_advanced_buttons2: "",
        theme_advanced_buttons3: "",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,

        // Example content CSS (should be your site CSS)
        //content_css : "css/content.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url: "lists/template_list.js",
        external_link_list_url: "lists/link_list.js",
        external_image_list_url: "lists/image_list.js",
        media_external_list_url: "lists/media_list.js",

        // Replace values for the template plugin
        template_replace_values: {
            username: "Some User",
            staffid: "991234"
        }
    });
</script>

<style>
    .modal-dialog,
    .modal-content {
        width: 98vw;
        height: 97vh;
    }

    .modal-dialog {
        position: absolute;
        left: 0.5%;
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
                    <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
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

                <?php if ($msgDominio <> '') { ?>
                    <div class="alert <?php echo $msgDominioTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgDominio; ?>
                    </div>
                <?php } ?>

                <?php
                $abaEmpresa = 1165;

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
                            <legend>Dados Gerais</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Nome do Programa</label>
                                        <input type="text" class="form-control input-sm" name="DES_PROGRAMA" id="DES_PROGRAMA" maxlength="30" value="<?php echo $des_programa; ?>" required>
                                        <div class="help-block with-errors">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Nome do Hot Site</label>
                                        <input type="text" class="form-control input-sm text-center" name="DES_DOMINIO" id="DES_DOMINIO" maxlength="20" value="<?php echo $des_dominio; ?>" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Domínio Padrão</label>
                                        <select data-placeholder="Selecione a ordem" name="COD_DOMINIO" id="COD_DOMINIO" class="chosen-select-deselect">
                                            <option value="2">.fidelidade.mk</option>
                                            <option value="1">.mais.cash</option>
                                        </select>
                                        <script>
                                            $("#formulario #COD_DOMINIO").val('<?= $cod_dominio ?>').trigger("chosen:updated");
                                        </script>
                                    </div>
                                </div>

                                <!-- <div class="col-md-2">						
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">&nbsp;</label>
                                        <h5><b>.fidelidade.mk</b></h5>
                                        <h5><b>.mais.cash</b></h5>
                                    </div>
                                </div> -->
                                <?php if ($des_dominio != '') { ?>

                                    <!-- <div class="col-md-2">
                                    <div class="push15"></div>
                                    <a href="javascript:void(0)" data-title="Preview Hotsite" class="btn btn-default btn-sm btn-block addBox previewSite"><i class="fas fa-eye" aria-hidden="true"></i>&nbsp; Preview Site</a>
                                </div> -->
                                    <script>
                                        $('.previewSite').click(function() {

                                            $.ajax({
                                                method: 'POST',
                                                url: 'ajxPreviewHotsite.php',
                                                data: $('#formulario').serialize(),
                                                success: function(data) {
                                                    $('#conteudoFrame').attr('src', 'https://<?= $des_dominio ?>.fidelidade.mk?preview=true');
                                                    console.log(data);
                                                },
                                                error: function(data) {
                                                    // alert(data);
                                                }
                                            });
                                            // $('.previewSite').attr('data-url','https://<?php echo $des_dominio; ?>.fidelidade.mk?form='+form);
                                        });
                                    </script>

                                    <div class="col-md-2">
                                        <div class="push15"></div>
                                        <div class="btn-group dropdown dropleft btn-block">
                                            <button type="button" class="btn btn-info btn-sm btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fal fa-share" aria-hidden="true"></i>
                                                &nbsp; Visitar Site &nbsp;
                                                <span class="fal fa-caret-down"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                                <li><a href="https://<?php echo $des_dominio; ?>.fidelidade.mk" target="_blank"><?= $des_dominio ?>.fidelidade.mk</a></li>
                                                <li><a href="https://<?php echo $des_dominio; ?>.mais.cash" target="_blank"><?= $des_dominio ?>.mais.cash</a></li>
                                            </ul>
                                        </div>
                                        <!-- <a href="https://<?php echo $des_dominio; ?>.fidelidade.mk" target="_blank" class="btn btn-info btn-sm btn-block"><i class="fal fa-share" aria-hidden="true"></i>&nbsp; Visitar Site</a> -->
                                    </div>

                                <?php } else {  ?>
                                    <div class="col-md-2">
                                        <div class="push15"></div>
                                        <a href="#" target="_blank" class="btn btn-info btn-sm btn-block disabled" disabled><i class="fal fa-share" aria-hidden="true"></i>&nbsp; Visualizar Site</a>
                                    </div>
                                <?php } ?>

                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <label for="inputName" class="control-label required">Logotipo</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_LOGO" extensao="img"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="hidden" name="DES_LOGO" id="DES_LOGO" value="<?php echo $des_logo; ?>">
                                        <input type="text" name="LOGO" id="LOGO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_logo); ?>">
                                    </div>
                                    <span class="help-block">(.png 120px X 35px)</span>
                                </div>

                                <div class="col-md-3">
                                    <label for="inputName" class="control-label required">Banner</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_BANNER" extensao="img"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="hidden" name="DES_BANNER" id="DES_BANNER" value="<?php echo $des_banner; ?>">
                                        <input type="text" name="BANNER" id="BANNER" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_banner); ?>">
                                    </div>
                                    <span class="help-block">(.jpg 1400px X 600px)</span>
                                </div>

                                <div class="col-md-3">
                                    <label for="inputName" class="control-label">Avatar de Login</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_ICOLOG" extensao="img"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="hidden" name="DES_ICOLOG" id="DES_ICOLOG" value="<?php echo $des_icolog; ?>">
                                        <input type="text" name="ICOLOG" id="ICOLOG" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_icolog); ?>">
                                    </div>
                                    <span class="help-block">(.png 390px X 350px)</span>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">e-Mail de Contato</label>
                                        <input type="text" class="form-control input-sm" name="DES_EMAIL" id="DES_EMAIL" maxlength="100" value="<?php echo $des_email; ?>" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>


                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Navegação</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Home</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_HOME" id="LOG_HOME" class="switch switch-small" value="S" <?php echo $check_home; ?>>
                                            <span style="padding-right: 20px;"> <!-- Home --></span>
                                        </label>
                                    </div>
                                    <div class="push5"></div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" name="DESTINO_HOME" id="DESTINO_HOME" maxlength="250" value="<?php echo $destino_home; ?>" <?= $disable_home ?>>
                                        <div class="help-block with-errors">URL Externa</div>
                                    </div>
                                    <script>
                                        $('#LOG_HOME').change(function() {
                                            if ($('#LOG_HOME').is(':checked')) {
                                                $('#DESTINO_HOME').prop('disabled', false);
                                            } else {
                                                $('#DESTINO_HOME').prop('disabled', true);
                                            }
                                        });
                                    </script>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cadastro</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_CADASTRO" id="LOG_CADASTRO" class="switch switch-small" value="S" <?php echo $check_cadastro; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="push5"></div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" name="TXT_CADASTRO" id="TXT_CADASTRO" maxlength="20" value="<?php echo $txt_cadastro; ?>">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Programa</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_SOBRE" id="LOG_SOBRE" class="switch switch-small" value="S" <?php echo $check_sobre; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="push5"></div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" name="TXT_SOBRE" id="TXT_SOBRE" maxlength="20" value="<?php echo $txt_sobre; ?>">
                                        <input type="checkbox" name="LOG_CONTRASTE" id="LOG_CONTRASTE" value="S" <?= $check_contraste ?>>
                                        <label>Contraste</label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Vantagens</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_VANTAGEM" id="LOG_VANTAGEM" class="switch switch-small" value="S" <?php echo $check_vantagem; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="push5"></div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" name="TXT_VANTAGEM" id="TXT_VANTAGEM" maxlength="20" value="<?php echo $txt_vantagem; ?>">
                                        <span class="help-block">Bloco associado ao Programa</span>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Extrato</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_EXTRATO" id="LOG_EXTRATO" class="switch switch-small" value="S" <?php echo $check_extrato; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="push5"></div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" name="TXT_EXTRATO" id="TXT_EXTRATO" maxlength="20" value="<?php echo $txt_extrato; ?>">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Termos/Regulamentos</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_REGULA" id="LOG_REGULA" class="switch switch-small" value="S" <?php echo $check_regula; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="push5"></div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" name="TXT_REGULA" id="TXT_REGULA" maxlength="20" value="<?php echo $txt_regula; ?>">
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Lojas</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_LOJAS" id="LOG_LOJAS" class="switch switch-small" value="S" <?php echo $check_lojas; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="push5"></div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" name="TXT_LOJAS" id="TXT_LOJAS" maxlength="20" value="<?php echo $txt_lojas; ?>">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">FAQ</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_FAQ" id="LOG_FAQ" class="switch switch-small" value="S" <?php echo $check_faq; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="push5"></div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" name="TXT_FAQ" id="TXT_FAQ" maxlength="20" value="<?php echo $txt_faq; ?>">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Contato</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_CONTATO" id="LOG_CONTATO" class="switch switch-small" value="S" <?php echo $check_contato; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="push5"></div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" name="TXT_CONTATO" id="TXT_CONTATO" maxlength="20" value="<?php echo $txt_contato; ?>">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Prêmios</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_PREMIOS" id="LOG_PREMIOS" class="switch switch-small" value="S" <?php echo $check_premios; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="push5"></div>
                                    <div class="form-group">
                                        <input type="text" class="form-control input-sm" name="TXT_PREMIOS" id="TXT_PREMIOS" maxlength="20" value="<?php echo $txt_premios; ?>">
                                    </div>
                                    <div class="help-block">catálogo de prêmios próprios / markapontos</div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="push30"></div>
                                        <div class="push10"></div>
                                        <label for="inputName" class="control-label">Ordem de Exibição dos Prêmios</label>
                                        <select data-placeholder="Selecione a ordem" name="TP_ORDENAC" id="TP_ORDENAC" class="chosen-select-deselect">
                                            <option value="ASC">Crescente</option>
                                            <option value="DESC">Decrescente</option>
                                        </select>
                                        <div class="help-block with-errors">ordem dos pontos</div>
                                        <script>
                                            $("#formulario #TP_ORDENAC").val('<?= $tp_ordenac ?>').trigger("chosen:updated");
                                        </script>
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Aceite dos Termos Obrigatório</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_TERMOS" id="LOG_TERMOS" class="switch switch-small" value="S" <?php echo $check_termos; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="push5"></div>
                                    <div class="form-group">
                                        <!-- <input type="text" class="form-control input-sm" name="TXT_PREMIOS" id="TXT_PREMIOS" maxlength="20" value="<?php echo $txt_premios; ?>" required>                                                            -->
                                    </div>
                                    <!-- <div class="help-block">catálogo de prêmios próprios / markapontos</div>                                     -->
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="push30"></div>
                                        <div class="push10"></div>
                                        <label for="inputName" class="control-label">Url App Android</label>
                                        <input type="text" class="form-control input-sm" name="DES_URLANDRO" id="DES_URLANDRO" maxlength="200" value="<?php echo $des_urlandro; ?>">
                                        <div class="help-block with-errors">bloco vantagens</div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="push30"></div>
                                        <div class="push10"></div>
                                        <label for="inputName" class="control-label">Url App IOS</label>
                                        <input type="text" class="form-control input-sm" name="DES_URLIOS" id="DES_URLIOS" maxlength="200" value="<?php echo $des_urlios; ?>">
                                        <div class="help-block with-errors">bloco vantagens</div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="push30"></div>
                                        <div class="push10"></div>
                                        <label for="inputName" class="control-label">Tamanho do texto</label>
                                        <select data-placeholder="Selecione o tamanho" name="TAM_TEXTO" id="TAM_TEXTO" class="chosen-select-deselect">
                                            <option value="P">Pequeno</option>
                                            <option value="M">Médio</option>
                                            <option value="G">Grande</option>
                                        </select>
                                        <div class="help-block with-errors">Programa/Vantagens</div>
                                        <script>
                                            $("#formulario #TAM_TEXTO").val('<?= $tam_texto ?>').trigger("chosen:updated");
                                        </script>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <!-- <fieldset>

                            <legend>Imagens Área de cadastro (Totem/Hotsite)</legend>

                            <div class="row">


                                <div class="col-md-3">
                                    <label for="inputName" class="control-label required">Imagem Desktop (G)</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG_G" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="hidden" name="DES_IMG_G" id="DES_IMG_G" value="<?php echo $des_img_g; ?>">
                                        <input type="text" name="IMG_G" id="IMG_G" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_img_g); ?>">
                                    </div>
                                    <span class="help-block">(.jpg 940px X 845px)</span>
                                </div>

                                <div class="col-md-3">
                                    <label for="inputName" class="control-label required">Imagem Tablet (M)</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="hidden" name="DES_IMG" id="DES_IMG" value="<?php echo $des_img; ?>">
                                        <input type="text" name="IMG" id="IMG" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_img); ?>">
                                    </div>
                                    <span class="help-block">(.jpg 680px X 675px)</span>
                                </div>

                                <div class="col-md-3">
                                    <label for="inputName" class="control-label required">Imagem Mobile (P)</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMGMOB" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="hidden" name="DES_IMGMOB" id="DES_IMGMOB" value="<?php echo $des_imgmob; ?>">
                                        <input type="text" name="IMGMOB" id="IMGMOB" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_imgmob); ?>">
                                    </div>
                                    <span class="help-block">(.jpg 360px X 360px)</span>
                                </div>

                            </div>

                        </fieldset> -->

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Cores</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Barra Superior(Menu)</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_BARRA" id="COR_BARRA" maxlength="100" value="<?php echo $cor_barra ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Links da Barra Superior</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_TXTBARRA" id="COR_TXTBARRA" maxlength="100" value="<?php echo $cor_txtbarra ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Fundo do Site</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_SITE" id="COR_SITE" maxlength="100" value="<?php echo $cor_site ?>" required>
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
                                        <label for="inputName" class="control-label required">Rodapé</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_RODAPEBG" id="COR_RODAPEBG" value="<?php echo $cor_rodapebg; ?>" required>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Texto do Rodapé</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_RODAPE" id="COR_RODAPE" value="<?php echo $cor_rodape; ?>" required>
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

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Cor Texto do Botão</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_TXTBOTAO" id="COR_TXTBOTAO" value="<?php echo $cor_txtbotao ?>">
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Vantagens</legend>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Chamada Vantagens</label>
                                        <input type="text" class="form-control input-sm" name="DES_VANTAGEM" id="DES_VANTAGEM" value="<?php echo $des_vantagem; ?>">
                                    </div>
                                </div>

                            </div class="row">

                            <div class="row">

                                <div class="col-md-4">
                                    <label for="inputName" class="control-label required">Ícone Bloco #1 </label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="ICO_BLOCO1"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="hidden" name="ICO_BLOCO1" id="ICO_BLOCO1" value="<?php echo $ico_bloco1; ?>">
                                        <input type="text" name="BLOCO1" id="BLOCO1" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($ico_bloco1); ?>">
                                        <div class="help-block with-errors">(.png 336px X 336px)</div>
                                    </div>
                                    <span class="help-block"><a href="javascript:void(0);" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1166); ?>&field=ICO_BLOCO1&pop=true" data-title="Banco de ícones">Banco de ícones</a></span>
                                </div>

                                <div class="col-md-4">
                                    <label for="inputName" class="control-label required">Ícone Bloco #2 </label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="ICO_BLOCO2"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="hidden" name="ICO_BLOCO2" id="ICO_BLOCO2" value="<?php echo $ico_bloco2; ?>">
                                        <input type="text" name="BLOCO2" id="BLOCO2" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($ico_bloco2); ?>">
                                        <div class="help-block with-errors">(.png 336px X 336px)</div>
                                    </div>
                                    <span class="help-block"><a href="javascript:void(0);" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1166); ?>&field=ICO_BLOCO2&pop=true" data-title="Banco de ícones">Banco de ícones</a></span>
                                </div>

                                <div class="col-md-4">
                                    <label for="inputName" class="control-label required">Ícone Bloco #3 </label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="ICO_BLOCO3"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="hidden" name="ICO_BLOCO3" id="ICO_BLOCO3" value="<?php echo $ico_bloco3; ?>">
                                        <input type="text" name="BLOCO3" id="BLOCO3" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($ico_bloco3); ?>">
                                        <div class="help-block with-errors">(.png 336px X 336px)</div>
                                    </div>
                                    <span class="help-block"><a href="#;" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1166); ?>&field=ICO_BLOCO3&pop=true" data-title="Banco de ícones">Banco de ícones</a></span>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Título Bloco #1</label>
                                        <input type="text" class="form-control input-sm" name="TIT_BLOCO1" id="TIT_BLOCO1" maxlength="150" value="<?php echo $tit_bloco1; ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Título Bloco #2</label>
                                        <input type="text" class="form-control input-sm" name="TIT_BLOCO2" id="TIT_BLOCO2" maxlength="150" value="<?php echo $tit_bloco2; ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Título Bloco #3</label>
                                        <input type="text" class="form-control input-sm" name="TIT_BLOCO3" id="TIT_BLOCO3" maxlength="150" value="<?php echo $tit_bloco3 ?>">
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Descrição Bloco #1</label>
                                        <input type="text" class="form-control input-sm" name="DES_BLOCO1" id="DES_BLOCO1" maxlength="200" value="<?php echo $des_bloco1; ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Descrição Bloco #2</label>
                                        <input type="text" class="form-control input-sm" name="DES_BLOCO2" id="DES_BLOCO2" maxlength="200" value="<?php echo $des_bloco2; ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Descrição Bloco #3</label>
                                        <input type="text" class="form-control input-sm" name="DES_BLOCO3" id="DES_BLOCO3" maxlength="200" value="<?php echo $des_bloco3; ?>">
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Sobre o Programa</legend>

                            <div class="row">

                                <div class="col-md-12">

                                    <textarea name="DES_SOBRE" id="DES_SOBRE" style="width: 100%; height: 240px;"><?php echo $des_sobre; ?></textarea>

                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Termos/Regulamentos <small>(caso termos LGPD não estejam habilitados)</small></legend>

                            <div class="row">

                                <div class="col-md-12">

                                    <textarea name="DES_REGRAS" id="DES_REGRAS" style="width: 100%; height: 240px;"><?php echo $des_regras; ?></textarea>

                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                            <?php if ($cod_extrato == 0) { ?>
                                <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <?php } else { ?>
                                <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <?php } ?>

                        </div>

                        <input type="hidden" name="COD_EXTRATO" id="COD_EXTRATO" value="<?php echo $cod_extrato; ?>">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

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
                <iframe id="conteudoFrame" frameborder="0" style="width: 100%; height: 80%"></iframe>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="push20"></div>

<!-- <link rel="stylesheet" href="fonts/font-awesome-4.2.0/css/font-awesome.min.css"/>
<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>

<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.2.0.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script> -->

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

        //bloqueio de caracteres especiais no dominio
        $('#DES_DOMINIO').on('input', function() {

            var c = this.selectionStart,
                r = /[^a-z]/gi,
                v = $(this).val();
            if (r.test(v)) {
                $(this).val(v.replace(r, ''));
                c--;
            }
            this.setSelectionRange(c, c);
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

        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        if (nomeArquivo.indexOf(' ') > 0) {
            $.alert({
                title: "Erro ao efetuar o upload",
                content: "O nome do arquivo não pode conter espaços, renomeie o arquivo e faça o upload novamente",
                type: 'red'
            });
        } else {

            var formData = new FormData();

            formData.append('arquivo', $('#' + idField)[0].files[0]);
            formData.append('diretorio', '../media/clientes');
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
                        var tipo = "arqUpload_" + idField.split('_')[1] + "_";
                        $('#' + idField.replace(tipo, "")).val(nomeArquivo);
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
    }
</script>