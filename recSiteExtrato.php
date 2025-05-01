<?php 

include "_system/_functionsMain.php"; 

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_extrato = fnLimpaCampoZero($_REQUEST['COD_EXTRATO']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $des_dominio = fnLimpaCampo($_REQUEST['DES_DOMINIO']);
        $des_logo = fnLimpaCampo($_REQUEST['DES_LOGO']);
        $des_banner = fnLimpaCampo($_REQUEST['DES_BANNER']);
        $des_email = fnLimpaCampo($_REQUEST['DES_EMAIL']);
        //$log_vantagem = fnLimpaCampo($_REQUEST['LOG_VANTAGEM']);
        if (empty($_REQUEST['LOG_VANTAGEM'])) {
            $log_vantagem = 'N';
        } else {
            $log_vantagem = $_REQUEST['LOG_VANTAGEM'];
        }
        if (empty($_REQUEST['LOG_VANTAGEM'])) {
            $check_vantagem = '';
        } else {
            $check_vantagem = "checked";
        }
        $txt_vantagem = fnLimpaCampo($_REQUEST['TXT_VANTAGEM']);
        //$log_regula = fnLimpaCampo($_REQUEST['LOG_REGULA']);
        if (empty($_REQUEST['LOG_REGULA'])) {
            $log_regula = 'N';
        } else {
            $log_regula = $_REQUEST['LOG_REGULA'];
        }
        if (empty($_REQUEST['LOG_REGULA'])) {
            $check_regula = '';
        } else {
            $check_regula = "checked";
        }
        $txt_regula = fnLimpaCampo($_REQUEST['TXT_REGULA']);
        //$log_lojas = fnLimpaCampo($_REQUEST['LOG_LOJAS']);
        if (empty($_REQUEST['LOG_LOJAS'])) {
            $log_lojas = 'N';
        } else {
            $log_lojas = $_REQUEST['LOG_LOJAS'];
        }
        if (empty($_REQUEST['LOG_LOJAS'])) {
            $check_lojas = '';
        } else {
            $check_lojas = "checked";
        }
        $txt_lojas = fnLimpaCampo($_REQUEST['TXT_LOJAS']);
        //$log_faq = fnLimpaCampo($_REQUEST['LOG_FAQ']);
        if (empty($_REQUEST['LOG_FAQ'])) {
            $log_faq = 'N';
        } else {
            $log_faq = $_REQUEST['LOG_FAQ'];
        }
        if (empty($_REQUEST['LOG_FAQ'])) {
            $check_faq = '';
        } else {
            $check_faq = "checked";
        }
        $txt_faq = fnLimpaCampo($_REQUEST['TXT_FAQ']);
        //$log_extrato = fnLimpaCampo($_REQUEST['LOG_EXTRATO']);
        if (empty($_REQUEST['LOG_EXTRATO'])) {
            $log_extrato = 'N';
        } else {
            $log_extrato = $_REQUEST['LOG_EXTRATO'];
        }
        if (empty($_REQUEST['LOG_EXTRATO'])) {
            $check_extrato = '';
        } else {
            $check_extrato = "checked";
        }
        $txt_extrato = fnLimpaCampo($_REQUEST['TXT_EXTRATO']);
        //$log_contato = fnLimpaCampo($_REQUEST['LOG_CONTATO']);
        if (empty($_REQUEST['LOG_CONTATO'])) {
            $log_contato = 'N';
        } else {
            $log_contato = $_REQUEST['LOG_CONTATO'];
        }
        if (empty($_REQUEST['LOG_CONTATO'])) {
            $check_contato = '';
        } else {
            $check_contato = "checked";
        }
        $txt_contato = fnLimpaCampo($_REQUEST['TXT_CONTATO']);
        $cor_titulos = fnLimpaCampo($_REQUEST['COR_TITULOS']);
        $cor_textos = fnLimpaCampo($_REQUEST['COR_TEXTOS']);
        $cor_rodapebg = fnLimpaCampo($_REQUEST['COR_RODAPEBG']);
        $cor_rodape = fnLimpaCampo($_REQUEST['COR_RODAPE']);
        $cor_botao = fnLimpaCampo($_REQUEST['COR_BOTAO']);
        $cor_botaoon = fnLimpaCampo($_REQUEST['COR_BOTAOON']);
        $des_vantagem = fnLimpaCampo($_REQUEST['DES_VANTAGEM']);
        $ico_bloco1 = fnLimpaCampo($_REQUEST['ICO_BLOCO1']);
        $ico_bloco2 = fnLimpaCampo($_REQUEST['ICO_BLOCO2']);
        $ico_bloco3 = fnLimpaCampo($_REQUEST['ICO_BLOCO3']);
        $tit_bloco1 = fnLimpaCampo($_REQUEST['TIT_BLOCO1']);
        $tit_bloco2 = fnLimpaCampo($_REQUEST['TIT_BLOCO2']);
        $tit_bloco3 = fnLimpaCampo($_REQUEST['TIT_BLOCO3']);
        $des_bloco1 = fnLimpaCampo($_REQUEST['DES_BLOCO1']);
        $des_bloco2 = fnLimpaCampo($_REQUEST['DES_BLOCO2']);
        $des_bloco3 = fnLimpaCampo($_REQUEST['DES_BLOCO3']);
        $des_regras = addslashes(htmlentities($_REQUEST['DES_REGRAS']));
        $des_programa = fnLimpaCampo($_REQUEST['DES_PROGRAMA']);
		
        if (empty($_REQUEST['LOG_CADASTRO'])) {
            $log_cadastro = 'N';
        } else {
            $log_cadastro = $_REQUEST['LOG_CADASTRO'];
        }
        if ($log_cadastro == "N") {
            $check_cadastro = '';
        } else {
            $check_cadastro = "checked";
        }		

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {

            $sql = "CALL SP_ALTERA_SITE_EXTRATO (         
				 '" . $cod_extrato . "', 
				 '" . $cod_empresa . "', 
				 '" . $des_dominio . "', 
				 '" . $des_logo . "', 
				 '" . $des_banner . "', 
				 '" . $des_email . "', 
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
				 '" . $cor_titulos . "', 
				 '" . $cor_textos . "', 
				 '" . $cor_rodapebg . "', 
				 '" . $cor_rodape . "', 
				 '" . $cor_botao . "', 
				 '" . $cor_botaoon . "', 
				 '" . $des_vantagem . "', 
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
                 '" . $des_programa . "',
				 '" . $log_cadastro . "' 
				) ";

            //echo $sql;
			//fnTestesql(connTemp($cod_empresa, ''), $sql);
            mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

            //grava o noem do site no adm 
            $sql1 = "CALL SP_ALTERA_DOMINIO (
			 '" . $des_dominio . "', 
			 '" . $cod_empresa . "'   
			) ";

            //echo $sql1;			
            mysqli_query($connAdm->connAdm(), trim($sql1)) or die(mysqli_error());

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                    $msgRetorno = "CAD";
                    break;
                case 'ALT':
                    $msgRetorno = "ALT";
                    break;
                case 'EXC':
                    $msgRetorno = "EXC";
                    break;
				break;
            }
            $msgTipo = 'alert-success';
        }
    }
}

header("Location:http://adm.bunker.mk/action.do?mod=V6eFJquayU4%C2%A2&id=".fnEncode($cod_empresa)."&msg=".$msgRetorno);


//fnEscreve($qrBuscaSiteExtrato['LOG_CADASTRO']);
//fnEscreve($check_cadastro);

//fnMostraForm();
?>