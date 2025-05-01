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
$cod_app = "";
$des_logo = "";
$des_imgback = "";
$cor_backbar = "";
$cor_backpag = "";
$cor_titulos = "";
$cor_textos = "";
$cor_botao = "";
$cor_botaoon = "";
$cor_fullpag = "";
$cor_textfull = "";
$log_colunas = "";
$log_ofertas = "";
$log_jornal = "";
$log_habito = "";
$log_dados = "";
$log_extrato = "";
$log_mensagem = "";
$log_premios = "";
$log_enderecos = "";
$log_parceiros = "";
$log_comunica = "";
$log_amigos = "";
$log_brindes = "";
$log_bannerhome = "";
$log_bannerlista = "";
$log_veiculo = "";
$log_token = "";
$log_sombra = "";
$log_linha = "";
$log_round = "";
$log_lgpd_lt = "";
$log_expira = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$sqlInsert = "";
$arrayInsert = [];
$cod_erro = "";
$sqlUpdate = "";
$arrayUpdate = [];
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaSiteTotemApp = "";
$chk_colunas = "";
$disp_dupla = "";
$disp_unica = "";
$chk_ofertas = "";
$disp_ofertas = "";
$chk_jornal = "";
$disp_jornal = "";
$chk_habito = "";
$disp_habito = "";
$chk_dados = "";
$disp_dados = "";
$chk_extrato = "";
$disp_extrato = "";
$chk_mensagem = "";
$disp_mensagem = "";
$chk_premios = "";
$disp_premios = "";
$chk_enderecos = "";
$disp_enderecos = "";
$chk_parceiros = "";
$disp_parceiros = "";
$chk_comunica = "";
$disp_comunica = "";
$chk_amigos = "";
$disp_amigos = "";
$chk_brindes = "";
$disp_brindes = "";
$chk_bannerhome = "";
$chk_bannerlista = "";
$chk_token = "";
$chk_veiculo = "";
$chk_sombra = "";
$chk_linha = "";
$chk_round = "";
$chk_lgpd = "";
$chk_expira = "";
$displayCad = "";
$displayLgpd = "";
$saldoParcial = "";
$saldoFull = "";
$disp_bannerhome = "";
$disp_bannerlista = "";
$qrEmail = "";
$emailEmpresa = "";
$r_cor_backpag = "";
$g_cor_backpag = "";
$b_cor_backpag = "";
$r = "";
$g = "";
$b = "";
$formBack = "";
$abaEmpresa = "";
$abaApp = "";
$qrListaSexo = "";
$cod_empresa_path = "";
$sql2 = "";
$active = "";
$qrJornal = "";
$link = "";


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

		$cod_app = fnLimpaCampoZero(@$_REQUEST['COD_APP']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$des_logo = fnLimpaCampo(@$_REQUEST['DES_LOGO']);
		$des_imgback = fnLimpaCampo(@$_REQUEST['DES_IMGBACK']);

		$cor_backbar = @$_REQUEST['COR_BACKBAR'];
		$cor_backpag = @$_REQUEST['COR_BACKPAG'];
		$cor_titulos = @$_REQUEST['COR_TITULOS'];
		$cor_textos = @$_REQUEST['COR_TEXTOS'];
		$cor_botao = @$_REQUEST['COR_BOTAO'];
		$cor_botaoon = @$_REQUEST['COR_BOTAOON'];
		$cor_fullpag = @$_REQUEST['COR_FULLPAG'];
		$cor_textfull = @$_REQUEST['COR_TEXTFULL'];

		if (empty(@$_REQUEST['LOG_COLUNAS'])) {
			$log_colunas = 'N';
		} else {
			$log_colunas = @$_REQUEST['LOG_COLUNAS'];
		}
		if (empty(@$_REQUEST['LOG_OFERTAS'])) {
			$log_ofertas = 'N';
		} else {
			$log_ofertas = @$_REQUEST['LOG_OFERTAS'];
		}
		if (empty(@$_REQUEST['LOG_JORNAL'])) {
			$log_jornal = 'N';
		} else {
			$log_jornal = @$_REQUEST['LOG_JORNAL'];
		}
		if (empty(@$_REQUEST['LOG_HABITO'])) {
			$log_habito = 'N';
		} else {
			$log_habito = @$_REQUEST['LOG_HABITO'];
		}
		if (empty(@$_REQUEST['LOG_DADOS'])) {
			$log_dados = 'N';
		} else {
			$log_dados = @$_REQUEST['LOG_DADOS'];
		}
		if (empty(@$_REQUEST['LOG_EXTRATO'])) {
			$log_extrato = 'N';
		} else {
			$log_extrato = @$_REQUEST['LOG_EXTRATO'];
		}
		if (empty(@$_REQUEST['LOG_MENSAGEM'])) {
			$log_mensagem = 'N';
		} else {
			$log_mensagem = @$_REQUEST['LOG_MENSAGEM'];
		}
		if (empty(@$_REQUEST['LOG_PREMIOS'])) {
			$log_premios = 'N';
		} else {
			$log_premios = @$_REQUEST['LOG_PREMIOS'];
		}
		if (empty(@$_REQUEST['LOG_ENDERECOS'])) {
			$log_enderecos = 'N';
		} else {
			$log_enderecos = @$_REQUEST['LOG_ENDERECOS'];
		}
		if (empty(@$_REQUEST['LOG_PARCEIROS'])) {
			$log_parceiros = 'N';
		} else {
			$log_parceiros = @$_REQUEST['LOG_PARCEIROS'];
		}
		if (empty(@$_REQUEST['LOG_COMUNICA'])) {
			$log_comunica = 'N';
		} else {
			$log_comunica = @$_REQUEST['LOG_COMUNICA'];
		}
		if (empty(@$_REQUEST['LOG_AMIGOS'])) {
			$log_amigos = 'N';
		} else {
			$log_amigos = @$_REQUEST['LOG_AMIGOS'];
		}
		if (empty(@$_REQUEST['LOG_BRINDES'])) {
			$log_brindes = 'N';
		} else {
			$log_brindes = @$_REQUEST['LOG_BRINDES'];
		}

		if (empty(@$_REQUEST['LOG_BANNERHOME'])) {
			$log_bannerhome = 'N';
		} else {
			$log_bannerhome = @$_REQUEST['LOG_BANNERHOME'];
		}
		if (empty(@$_REQUEST['LOG_BANNERLISTA'])) {
			$log_bannerlista = 'N';
		} else {
			$log_bannerlista = @$_REQUEST['LOG_BANNERLISTA'];
		}
		if (empty(@$_REQUEST['LOG_VEICULO'])) {
			$log_veiculo = 'N';
		} else {
			$log_veiculo = @$_REQUEST['LOG_VEICULO'];
		}
		if (empty(@$_REQUEST['LOG_TOKEN'])) {
			$log_token = 'N';
		} else {
			$log_token = @$_REQUEST['LOG_TOKEN'];
		}
		if (empty(@$_REQUEST['LOG_SOMBRA'])) {
			$log_sombra = 'N';
		} else {
			$log_sombra = @$_REQUEST['LOG_SOMBRA'];
		}
		if (empty(@$_REQUEST['LOG_LINHA'])) {
			$log_linha = 'N';
		} else {
			$log_linha = @$_REQUEST['LOG_LINHA'];
		}
		if (empty(@$_REQUEST['LOG_ROUND'])) {
			$log_round = 'N';
		} else {
			$log_round = @$_REQUEST['LOG_ROUND'];
		}
		if (empty(@$_REQUEST['LOG_LGPD_LT'])) {
			$log_lgpd_lt = 'N';
		} else {
			$log_lgpd_lt = @$_REQUEST['LOG_LGPD_LT'];
		}
		if (empty(@$_REQUEST['LOG_EXPIRA'])) {
			$log_expira = 'N';
		} else {
			$log_expira = @$_REQUEST['LOG_EXPIRA'];
		}


		// fnEscreve($log_ofertas);
		// fnEscreve($log_dados);
		// fnEscreve($log_extrato);
		// fnEscreve($log_premios);
		// fnEscreve($log_enderecos);
		// fnEscreve($log_comunica);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			if ($opcao == 'CAD') {

				$sqlInsert = "INSERT INTO totem_app(
								COD_EMPRESA, 
								DES_LOGO, 
								DES_IMGBACK, 
								COR_BACKBAR, 
								COR_BACKPAG, 
								COR_TITULOS, 
								COR_TEXTOS, 
								COR_BOTAO, 
								COR_BOTAOON, 
								COR_FULLPAG, 
								COR_TEXTFULL,
								LOG_COLUNAS,
								LOG_OFERTAS,
								LOG_JORNAL,
								LOG_HABITO,
								LOG_DADOS,
								LOG_EXTRATO,
								LOG_MENSAGEM,
								LOG_PREMIOS,
								LOG_ENDERECOS,
								LOG_PARCEIROS,
								LOG_COMUNICA,
								LOG_AMIGOS,
								LOG_BRINDES,
								LOG_BANNERHOME,
								LOG_TOKEN,
								LOG_VEICULO,
								LOG_SOMBRA,
								LOG_LINHA,
								LOG_ROUND,
								LOG_LGPD_LT,
								LOG_EXPIRA,
								LOG_BANNERLISTA
								) 
								VALUES (
								'$cod_empresa', 
								'$des_logo', 
								'$des_imgback', 
								'$cor_backbar', 
								'$cor_backpag', 
								'$cor_titulos', 
								'$cor_textos', 
								'$cor_botao', 
								'$cor_botaoon', 
								'$cor_fullpag', 
								'$cor_textfull',
								'$log_colunas',
								'$log_ofertas',
								'$log_jornal',
								'$log_habito',
								'$log_dados',
								'$log_extrato',
								'log_mensagem',
								'$log_premios',
								'$log_enderecos',
								'$log_parceiros',
								'$log_comunica',
								'$log_amigos',
								'$log_brindes',
								'$log_bannerhome',
								'$log_token',
								'$log_veiculo',
								'$log_sombra',
								'$log_linha',
								'$log_round',
								'$log_lgpd_lt',
								'$log_expira',
								'$log_bannerlista'
								);";

				// fnEscreve($sql);


				$arrayInsert = mysqli_query($conn, $sqlInsert);

				if (!$arrayInsert) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert, $nom_usuario);
				}
			}

			if ($opcao == 'ALT') {

				$sql = "";

				$sqlUpdate = "UPDATE TOTEM_APP SET 
								DES_LOGO = '$des_logo', 
								DES_IMGBACK = '$des_imgback', 
								COR_BACKBAR = '$cor_backbar', 
								COR_BACKPAG = '$cor_backpag', 
								COR_TITULOS = '$cor_titulos', 
								COR_TEXTOS = '$cor_textos', 
								COR_BOTAO = '$cor_botao', 
								COR_BOTAOON = '$cor_botaoon', 
								COR_FULLPAG = '$cor_fullpag', 
								COR_TEXTFULL = '$cor_textfull',
								LOG_COLUNAS = '$log_colunas',
								LOG_OFERTAS = '$log_ofertas',
								LOG_JORNAL = '$log_jornal',
								LOG_HABITO = '$log_habito',
								LOG_DADOS = '$log_dados',
								LOG_EXTRATO = '$log_extrato',
								LOG_MENSAGEM = '$log_mensagem',
								LOG_PREMIOS = '$log_premios',
								LOG_ENDERECOS = '$log_enderecos',
								LOG_PARCEIROS = '$log_parceiros',
								LOG_COMUNICA = '$log_comunica',
								LOG_AMIGOS = '$log_amigos',
								LOG_BRINDES = '$log_brindes',
								LOG_BANNERHOME = '$log_bannerhome',
								LOG_TOKEN = '$log_token',
								LOG_VEICULO = '$log_veiculo',
								LOG_SOMBRA = '$log_sombra',
								LOG_LINHA = '$log_linha',
								LOG_ROUND = '$log_round',
								LOG_LGPD_LT = '$log_lgpd_lt',
								LOG_EXPIRA = '$log_expira',
								LOG_BANNERLISTA = '$log_bannerlista'
								WHERE COD_APP = $cod_app and COD_EMPRESA = $cod_empresa;";
				// fnEscreve($sql);

				$arrayUpdate = mysqli_query($conn, $sqlUpdate);

				if (!$arrayUpdate) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
				}
				//fnEscreve($arrayUpdate);

			}

			//echo $sql;

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
$sql = "SELECT * FROM TOTEM_APP WHERE COD_EMPRESA = $cod_empresa";
//fnEscreve($sql);
$arrayQuery = mysqli_query($conn, $sql);
$qrBuscaSiteTotemApp = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotemApp)) {

	$cod_app = $qrBuscaSiteTotemApp['COD_APP'];
	$des_logo = $qrBuscaSiteTotemApp['DES_LOGO'];
	$des_imgback = $qrBuscaSiteTotemApp['DES_IMGBACK'];

	$cor_fullpag = $qrBuscaSiteTotemApp['COR_FULLPAG'];
	$cor_textfull = $qrBuscaSiteTotemApp['COR_TEXTFULL'];

	$cor_backbar = $qrBuscaSiteTotemApp['COR_BACKBAR'];
	$cor_backpag = $qrBuscaSiteTotemApp['COR_BACKPAG'];

	$cor_titulos = $qrBuscaSiteTotemApp['COR_TITULOS'];
	$cor_textos = $qrBuscaSiteTotemApp['COR_TEXTOS'];

	$cor_botao = $qrBuscaSiteTotemApp['COR_BOTAO'];
	$cor_botaoon = $qrBuscaSiteTotemApp['COR_BOTAOON'];

	$log_colunas = $qrBuscaSiteTotemApp['LOG_COLUNAS'];
	$log_ofertas = $qrBuscaSiteTotemApp['LOG_OFERTAS'];
	$log_jornal = $qrBuscaSiteTotemApp['LOG_JORNAL'];
	$log_habito = $qrBuscaSiteTotemApp['LOG_HABITO'];
	$log_dados = $qrBuscaSiteTotemApp['LOG_DADOS'];
	$log_extrato = $qrBuscaSiteTotemApp['LOG_EXTRATO'];
	$log_mensagem = $qrBuscaSiteTotemApp['LOG_MENSAGEM'];
	$log_premios = $qrBuscaSiteTotemApp['LOG_PREMIOS'];
	$log_enderecos = $qrBuscaSiteTotemApp['LOG_ENDERECOS'];
	$log_parceiros = $qrBuscaSiteTotemApp['LOG_PARCEIROS'];
	$log_comunica = $qrBuscaSiteTotemApp['LOG_COMUNICA'];
	$log_amigos = $qrBuscaSiteTotemApp['LOG_AMIGOS'];
	$log_brindes = $qrBuscaSiteTotemApp['LOG_BRINDES'];
	$log_bannerhome = $qrBuscaSiteTotemApp['LOG_BANNERHOME'];
	$log_bannerlista = $qrBuscaSiteTotemApp['LOG_BANNERLISTA'];
	$log_sombra = $qrBuscaSiteTotemApp['LOG_SOMBRA'];
	$log_linha = $qrBuscaSiteTotemApp['LOG_LINHA'];
	$log_round = $qrBuscaSiteTotemApp['LOG_ROUND'];

	if ($qrBuscaSiteTotemApp['LOG_COLUNAS'] == 'S') {
		$chk_colunas = "checked";
		$disp_dupla = "block";
		$disp_unica = "none";
	} else {
		$chk_colunas = "";
		$disp_dupla = "none";
		$disp_unica = "block";
	}

	if ($qrBuscaSiteTotemApp['LOG_OFERTAS'] == 'S') {
		$chk_ofertas = "checked";
		$disp_ofertas = "block";
	} else {
		$chk_ofertas = "";
		$disp_ofertas = "none";
	}

	if ($qrBuscaSiteTotemApp['LOG_JORNAL'] == 'S') {
		$chk_jornal = "checked";
		$disp_jornal = "block";
	} else {
		$chk_jornal = "";
		$disp_jornal = "none";
	}

	if ($qrBuscaSiteTotemApp['LOG_HABITO'] == 'S') {
		$chk_habito = "checked";
		$disp_habito = "block";
	} else {
		$chk_habito = "";
		$disp_habito = "none";
	}

	if ($qrBuscaSiteTotemApp['LOG_DADOS'] == 'S') {
		$chk_dados = "checked";
		$disp_dados = "block";
	} else {
		$chk_dados = "";
		$disp_dados = "none";
	}

	if ($qrBuscaSiteTotemApp['LOG_EXTRATO'] == 'S') {
		$chk_extrato = "checked";
		$disp_extrato = "block";
	} else {
		$chk_extrato = "";
		$disp_extrato = "none";
	}

	if ($qrBuscaSiteTotemApp['LOG_MENSAGEM'] == 'S') {
		$chk_mensagem = "checked";
		$disp_mensagem = "block";
	} else {
		$chk_mensagem = "";
		$disp_mensagem = "none";
	}

	if ($qrBuscaSiteTotemApp['LOG_PREMIOS'] == 'S') {
		$chk_premios = "checked";
		$disp_premios = "block";
	} else {
		$chk_premios = "";
		$disp_premios = "none";
	}

	if ($qrBuscaSiteTotemApp['LOG_ENDERECOS'] == 'S') {
		$chk_enderecos = "checked";
		$disp_enderecos = "block";
	} else {
		$chk_enderecos = "";
		$disp_enderecos = "none";
	}

	if ($qrBuscaSiteTotemApp['LOG_PARCEIROS'] == 'S') {
		$chk_parceiros = "checked";
		$disp_parceiros = "block";
	} else {
		$chk_parceiros = "";
		$disp_parceiros = "none";
	}

	if ($qrBuscaSiteTotemApp['LOG_COMUNICA'] == 'S') {
		$chk_comunica = "checked";
		$disp_comunica = "block";
	} else {
		$chk_comunica = "";
		$disp_comunica = "none";
	}

	if ($qrBuscaSiteTotemApp['LOG_AMIGOS'] == 'S') {
		$chk_amigos = "checked";
		$disp_amigos = "block";
	} else {
		$chk_amigos = "";
		$disp_amigos = "none";
	}

	if ($qrBuscaSiteTotemApp['LOG_BRINDES'] == 'S') {
		$chk_brindes = "checked";
		$disp_brindes = "block";
	} else {
		$chk_brindes = "";
		$disp_brindes = "none";
	}


	$chk_bannerhome = "";
	$chk_bannerlista = "";
	$chk_token = "";
	$chk_veiculo = "";
	$chk_sombra = "";
	$chk_linha = "";
	$chk_round = "";
	$chk_lgpd = "";
	$chk_expira = "";
	$displayCad = "block";
	$displayLgpd = "none";
	$saldoParcial = "block";
	$saldoFull = "none";

	if ($qrBuscaSiteTotemApp['LOG_BANNERHOME'] == 'S') {
		$chk_bannerhome = "checked";
	}

	if ($qrBuscaSiteTotemApp['LOG_BANNERLISTA'] == 'S') {
		$chk_bannerlista = "checked";
	}

	if ($qrBuscaSiteTotemApp['LOG_TOKEN'] == 'S') {
		$chk_token = "checked";
	}

	if ($qrBuscaSiteTotemApp['LOG_VEICULO'] == 'S') {
		$chk_veiculo = "checked";
	}

	if ($qrBuscaSiteTotemApp['LOG_SOMBRA'] == 'S') {
		$chk_sombra = "checked";
	}

	if ($qrBuscaSiteTotemApp['LOG_LINHA'] == 'S') {
		$chk_linha = "checked";
	}

	if ($qrBuscaSiteTotemApp['LOG_ROUND'] == 'S') {
		$chk_round = "checked";
	}

	if ($qrBuscaSiteTotemApp['LOG_EXPIRA'] == 'S') {
		$chk_expira = "checked";
		$saldoParcial = "none";
		$saldoFull = "block";
	}

	if ($qrBuscaSiteTotemApp['LOG_LGPD_LT'] == 'S') {
		$chk_lgpd = "checked";
		$displayCad = "none";
		$displayLgpd = "block";
	}



	// fnEscreve($chk_bannerlista);



} else {
	//default se vazio

	$cod_app = 0;
	$des_logo = "";
	$des_imgback = "";

	$cor_fullpag = "#34495e";
	$cor_textfull = "#fff";

	$cor_backbar = "34495e";
	$cor_backpag = "f2f3f4";

	$cor_titulos = "#34495e";
	$cor_textos = "#34495e";

	$cor_botao = "#0092d8";
	$cor_botaoon = "#48c9b0";

	$log_colunas = 'S';
	$log_ofertas = 'S';
	$log_jornal = 'S';
	$log_habito = 'S';
	$log_dados = 'S';
	$log_extrato = 'S';
	$log_mensagem = 'S';
	$log_premios = 'S';
	$log_enderecos = 'S';
	$log_parceiros = 'S';
	$log_comunica = 'S';
	$log_amigos = 'S';
	$log_brindes = 'S';

	$chk_colunas = "checked";
	$disp_dupla = "block";
	$disp_unica = "none";
	$chk_ofertas = "checked";
	$disp_ofertas = "block";
	$chk_jornal = "";
	$disp_jornal = "none";
	$chk_habito = "";
	$disp_habito = "none";
	$chk_dados = "checked";
	$disp_dados = "block";
	$chk_extrato = "checked";
	$disp_extrato = "block";
	$chk_mensagem = "checked";
	$disp_mensagem = "block";
	$chk_premios = "checked";
	$disp_premios = "block";
	$chk_enderecos = "checked";
	$disp_enderecos = "block";
	$chk_parceiros = "checked";
	$disp_parceiros = "block";
	$chk_comunica = "checked";
	$disp_comunica = "block";
	$chk_amigos = "checked";
	$disp_amigos = "block";
	$chk_brindes = "checked";
	$disp_brindes = "block";
	$chk_bannerhome = "";
	$disp_bannerhome = "none";
	$chk_bannerlista = "";
	$chk_sombra = "";
	$chk_linha = "";
	$chk_round = "";
	$chk_lgpd = "";
	$chk_expira = "checked";
	$disp_bannerlista = "none";
}

$sql = "SELECT DES_EMAIL FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
$qrEmail = mysqli_fetch_assoc(mysqli_query($conn, $sql));

if ($qrEmail['DES_EMAIL'] != "") {
	$emailEmpresa = $qrEmail['DES_EMAIL'];
} else {
	$emailEmpresa = "Email não configurado";
}

list($r_cor_backpag, $g_cor_backpag, $b_cor_backpag) = sscanf($cor_backpag, "#%02x%02x%02x");

if ($r_cor_backpag > 50) {
	$r = ($r_cor_backpag - 50);
} else {
	$r = ($r_cor_backpag + 50);
	if ($r_cor_backpag < 30) {
		$r = $r_cor_backpag;
	}
}
if ($g_cor_backpag > 50) {
	$g = ($g_cor_backpag - 50);
} else {
	$g = ($g_cor_backpag + 50);
	if ($g_cor_backpag < 30) {
		$g = $g_cor_backpag;
	}
}
if ($b_cor_backpag > 50) {
	$b = ($b_cor_backpag - 50);
} else {
	$b = ($b_cor_backpag + 50);
	if ($b_cor_backpag < 30) {
		$b = $b_cor_backpag;
	}
}

if ($r_cor_backpag <= 50 && $g_cor_backpag <= 50 && $b_cor_backpag <= 50) {
	$r = ($r_cor_backpag + 40);
	$g = ($g_cor_backpag + 40);
	$b = ($b_cor_backpag + 40);
}
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

				<?php
				$abaEmpresa = 1258;
				include "abasEmpresaConfig.php";
				?>

				<div class="push50"></div>

				<?php $abaApp = 1258;
				include "abasApp.php";  ?>

				<div class="push50"></div>
				<?php // echo $des_logo;
				?>


				<div class="login-form">

					<form data-toggle="validator" role="form2" method="POST" id="formulario" action="<?php echo $cmdPage; ?>">

						<div class="row">

							<div class="col-md-3">

								<fieldset>
									<div class="col-md-12">
										<label for="inputName" class="control-label required">Logotipo</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_LOGO" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
											</span>
											<input type="text" name="DES_LOGO" id="DES_LOGO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_logo; ?>">
										</div>
										<span class="help-block">(.png 300px X 80px)</span>
									</div>

									<div class="push10"></div>

									<div class="col-md-12">
										<label for="inputName" class="control-label">Imagem de Fundo das páginas</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMGBACK" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
											</span>
											<input type="text" name="DES_IMGBACK" id="DES_IMGBACK" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_imgback; ?>">
										</div>
										<span class="help-block">(.jpg 1400px X 600px)</span>
									</div>

								</fieldset>

							</div>

							<div class="col-md-7">

								<fieldset>
									<legend>Cores Personalizadas</legend>

									<div class="row">

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Cor da Barra Superior</label>
												<input type="text" class="form-control input-sm pickColor" name="COR_BACKBAR" id="COR_BACKBAR" maxlength="100" value="<?php echo $cor_backbar; ?>">
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Cor do Fundo da Página</label>
												<input type="text" class="form-control input-sm pickColor" name="COR_BACKPAG" id="COR_BACKPAG" maxlength="100" onchange="mudaCorSombra('.shadow, .shadow2','backgroundColor',this)" value="<?php echo $cor_backpag; ?>" required>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Cor Contraste da Página</label>
												<input type="text" class="form-control input-sm pickColor" name="COR_FULLPAG" id="COR_FULLPAG" maxlength="100" value="<?php echo $cor_fullpag; ?>" required>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Títulos</label>
												<input type="text" class="form-control input-sm pickColor" name="COR_TITULOS" id="COR_TITULOS" maxlength="100" onchange="mudaCor('.corTitulos','color',this)" value="<?php echo $cor_titulos; ?>" required>
											</div>
										</div>

										<div class="push20"></div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Textos Contraste</label>
												<input type="text" class="form-control input-sm pickColor" name="COR_TEXTFULL" id="COR_TEXTFULL" maxlength="100" value="<?php echo $cor_textfull; ?>" required>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Textos</label>
												<input type="text" class="form-control input-sm pickColor" name="COR_TEXTOS" id="COR_TEXTOS" maxlength="100" value="<?php echo $cor_textos; ?>" required>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Cor Botão</label>
												<input type="text" class="form-control input-sm pickColor" name="COR_BOTAO" id="COR_BOTAO" value="<?php echo $cor_botao; ?>" required>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Cor Botão Hover</label>
												<input type="text" class="form-control input-sm pickColor" name="COR_BOTAOON" id="COR_BOTAOON" value="<?php echo $cor_botaoon; ?>">
											</div>
										</div>

									</div>

									<div class="push13"></div>

								</fieldset>

							</div>

							<div class="col-md-2">

								<fieldset>
									<legend>Ofertas</legend>

									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Banner de Ofertas na Home</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_BANNERHOME" id="LOG_BANNERHOME" class="switch switch-small" value="S" <?= $chk_bannerhome ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="push10"></div>

									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Ofertas em Lista</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_BANNERLISTA" id="LOG_BANNERLISTA" class="switch switch-small" value="S" <?= $chk_bannerlista ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="push20"></div>
									<div class="push5" style="margin-bottom: 2px;"></div>

								</fieldset>

							</div>

						</div>

						<div class="row">

							<div class="col-md-12">

								<div class="push10"></div>
								<hr>
								<div class="form-group text-right col-lg-12">

									<?php if ($_SESSION["SYS_COD_EMPRESA"] == 2) { ?>
										<a href="https://adm.bunkerapp.com.br/app/intro.do?key=<?php echo fnEncode($cod_empresa) ?>" class="btn btn-danger pull-left" target="_blank"><i class="fal fa-link" aria-hidden="true"></i>&nbsp; Acessar App</a>
									<?php } ?>


									<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> &nbsp;

									<?php if ($cod_app == 0) { ?>
										<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
									<?php } else { ?>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
									<?php } ?>

								</div>

								<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
								<input type="hidden" name="COD_APP" id="COD_APP" value="<?php echo $cod_app; ?>">
								<input type="hidden" name="opcao" id="opcao" value="">
								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							</div>

						</div>

						<div class="push30"></div>


						<style>
							.iphone_bg {
								width: 440px;
								height: 840px;
								background: url(../images/phone_bg.png) no-repeat top;
								background-size: 430px 790px;
								margin: auto;
							}

							.mobile_wrap {
								width: 360px !important;
								height: 640px !important;
								margin: 70px 0 0 38px;
								overflow: hidden;
							}

							.container-wrap {
								overflow-y: auto;
								height: 100%;
								-ms-overflow-style: none;
								/* Internet Explorer 10+ */
								scrollbar-width: none;
								/* Firefox */
							}

							.container-wrap::-webkit-scrollbar {
								display: none;
								/* Safari and Chrome */
							}

							.bold {
								font-weight: bold;
							}

							.center {
								text-align: center;
							}

							.centerImg {
								display: block;
								margin-left: auto;
								margin-right: auto;
								width: 70%;
								height: 55px;
							}

							.chosen-container {
								font-size: 16px;
							}

							.chosen-container-single .chosen-single {
								height: 45px;
							}

							.chosen-container-single .chosen-single span {
								margin-top: 5px;
							}

							#fundoCel1Alt,
							#fundoCel1,
							.fundoCel2 {
								height: 100%;
							}

							.shadow {
								-webkit-box-shadow: 0px 0px 18px -2px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
								-moz-box-shadow: 0px 0px 18px -2px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
								box-shadow: 0px 0px 18px -2px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
								width: 100%;
								border-radius: 5px;
							}

							.shadow2 {
								-webkit-box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
								-moz-box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
								box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
								/*width: 100%;*/
								border-radius: 5px;
								margin: unset !important;
							}

							<?php if ($cod_empresa == 19) { ?>.shadow2 {

								border-radius: 30px;
							}

							<?php } ?>.LOG_COLUNAS {
								margin-bottom: 30px;
								-webkit-transition: all 0.2s ease-in-out;
								-moz-transition: all 0.2s ease-in-out;
								-o-transition: all 0.2s ease-in-out;
								transition: all 0.2s ease-in-out;
							}

							.fa-md {
								font-size: 32px;
							}

							.reduzMargem {
								margin-bottom: 10px;
							}

							.img-lista {
								height: 85px;
								width: 85px;
								border-radius: 50px;
							}

							.center {
								margin: auto;
								position: absolute;
								right: 0;
								left: 0;
								top: 50%;
								transform: translateY(-50%);
							}

							.scrolling-wrapper {
								overflow-x: scroll;
								overflow-y: hidden;
								white-space: nowrap;
							}

							.scrolling-wrapper .card {
								display: inline-block;
							}

							.separador {
								border: unset;
								max-width: unset;
								width: unset;
								border-top: 1px solid <?= $cor_textfull ?>;
								margin: 0;
								padding: 0;
							}

							.zeraPadLateral {
								padding-left: 0;
								padding-right: 0;
							}

							.carousel-control {
								background: none !important;
							}

							.carousel-indicators {
								top: 5px;
								margin-right: auto;
								bottom: unset;
							}

							.carousel-inner {
								height: auto;
							}

							.carousel-inner>.item,
							.carousel-inner>.item>img,
							.carousel-inner>.item>a>img {
								width: 100%;
								margin: auto;
								min-height: 160px;
								padding-top: 0 !important;
							}

							/*#COD_SEXOPES_chosen .chosen-single, 
							#COD_PROFISS_chosen .chosen-single {
								-webkit-box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
								-moz-box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
								box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
							}*/

							.rounded-corners {
								border-radius: 20px !important;
								border: 0px !important;
								width: 100%;
								color: <?= $cor_textos ?>;
							}

							.campo-linha {
								border-top: unset !important;
								border-left: unset !important;
								border-right: unset !important;
								border-bottom: 2px solid #eee !important;
								background-color: unset !important;
							}

							.shadow-linha {
								border-radius: unset !important;
								-webkit-box-shadow: 0 6px 2px -2px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8) !important;
								-moz-box-shadow: 0 4px 2px -2px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8) !important;
								box-shadow: 0 4px 2px -2px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8) !important;
							}

							.outline{
								border: 1px solid rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8) !important;
								background-color: rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.5) !important;
								border-radius: 5px;
							}
						</style>



						<div class="row">

							<div class="col-md-12">

								<div class="scrolling-wrapper">

									<div class="card">
										<!-- bloco 5 -->

										<div class="iphone_bg">

											<div class="row">

												<div class="col-md-12">

													<div class="mobile_wrap">

														<div class="container_wrap fundoCel1 col-md-12" style="background-color: <?= $cor_fullpag ?>; height: 100%;">

															<div class="row" style="height: 30px; background-color: #fff; margin-bottom: 0px;">
																<div class="col-md-12">
																</div>
															</div>

															<div class="row">
																<div class="col-md-12" style="height: 360px; padding: 0; margin-left: auto; margin-right: auto; margin-bottom: 20px;">
																	<img src="./images/nova_duque.png" width="100%" alt="" class="img-responsive">
																</div>
															</div>

															<div class="row" style="margin-bottom: 50px;">
																<div class="col-md-12" style="margin-bottom: 10px;">
																	<a href="javascript:void(0)" class="btn btn-primary btn-block">Entrar</a>
																</div>
																<div class="col-md-12">
																	<a href="javascript:void(0)" class="btn btn-primary btn-block">Cadastrar-se</a>
																</div>
															</div>

															<div class="row">
																<div class="col-md-12 text-center" style="margin-bottom: 20px;">
																	<a href="javascript:void(0)">Fale conosco</a>
																</div>
																<div class="col-md-12 text-center">
																	<a href="javascript:void(0)">Seja nosso parceiro</a>
																</div>
															</div>

														</div>

													</div>

												</div>

											</div>

										</div>

									</div>

									<div class="card">
										<!-- bloco 1 -->

										<div class="iphone_bg">

											<div class="row">

												<div class="col-md-12">

													<div class="mobile_wrap">

														<div class="container-wrap col-xs-12 fundoCel1" style="padding: 0; background-color: <?= $cor_fullpag ?>;">

															<div class="row" style="height: 30px; background-color: #fff;">
																<div class="col-md-12">
																</div>
															</div>

															<div class="col-xs-12 text-center fundoCel1" style=" color: <?= $cor_textfull ?>; background-color: <?= $cor_fullpag ?>;  width: 100%; border-radius: 0px; margin-bottom: 20px;">

																<div class="push"></div>

																<div class="col-md-12 textoCel1">
																	<h4 style="margin-bottom: 0;">Roberto, <span class="f12">você tem</span></h4>
																	<span class="f21">
																		<strong style="font-size: 36px;">999</strong>
																	</span>
																	<div class="push"></div>
																	<span class="f10">PONTOS ACUMULADOS</span>
																</div>

																<div class="col-md-12 texto2Cel1">

																	<div class="push10"></div>

																	<hr class="separador">

																	<div class="push10"></div>

																	<div id="saldoParcial" style="display: <?= $saldoParcial ?>">
																		<div class="col-xs-12 text-center">
																			<span class="f14">123</span><br />
																			<span class="f12">Resgatado</span>
																		</div>
																	</div>

																	<div id="saldoFull" style="display: <?= $saldoFull ?>">
																		<div class="col-xs-4 text-center">
																			<span class="f14">123</span><br />
																			<span class="f12">Resgatado</span>
																		</div>
																		<div class="col-xs-4 text-center">
																			<span class="f14">456</span><br />
																			<span class="f12">Expirado</span>
																		</div>
																		<div class="col-xs-4 text-center">
																			<span class="f14">789</span><br />
																			<span class="f12">Ganho</span>
																		</div>
																	</div>

																	<div class="push10"></div>

																</div>

															</div>

															<div class="row" style="border-radius: 30px 30px 5px 5px; width: 100%; margin-left: auto; margin-right: auto; background-color: #fff;">

																<!-- <div class="col-xs-12"> -->

																<!-- blocos coluna única -->

																<div id="colUnica" style="display: <?= $disp_unica ?>">

																	<a href="javascript:void(0)" class="LOG_OFERTAS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_ofertas ?>">
																		<div class="outline">
																			<div class="push"></div>
																			<div class="col-xs-3">
																				<div class="push10"></div>
																				<span class="fal fa-tags fa-2x"></span>
																			</div>
																			<div class="col-xs-7">
																				<div class="push10"></div>
																				<div class="push5"></div>
																				<p style="font-size: 16px;">Minhas Ofertas</p>
																			</div>
																			<div class="col-xs-2 text-right">
																				<div class="push10"></div>
																				<span class="fal fa-angle-right fa-2x"></span>
																			</div>
																			<div class="push"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_JORNAL LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_jornal ?>">
																		<div class="outline">
																			<div class="push"></div>
																			<div class="col-xs-3">
																				<div class="push10"></div>
																				<span class="fal fa-newspaper fa-2x"></span>
																			</div>
																			<div class="col-xs-7">
																				<div class="push10"></div>
																				<div class="push5"></div>
																				<p style="font-size: 16px;">Jornal de Ofertas</p>
																			</div>
																			<div class="col-xs-2 text-right">
																				<div class="push10"></div>
																				<span class="fal fa-angle-right fa-2x"></span>
																			</div>
																			<div class="push"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_HABITO LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_habito ?>">
																		<div class="outline">
																			<div class="push"></div>
																			<div class="col-xs-3">
																				<div class="push10"></div>
																				<span class="fal fa-bags-shopping fa-2x"></span>
																			</div>
																			<div class="col-xs-7">
																				<div class="push10"></div>
																				<div class="push5"></div>
																				<p style="font-size: 16px;">Minhas Compras</p>
																			</div>
																			<div class="col-xs-2 text-right">
																				<div class="push10"></div>
																				<span class="fal fa-angle-right fa-2x"></span>
																			</div>
																			<div class="push"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_DADOS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_dados ?>">
																		<div class="outline">
																			<div class="push"></div>
																			<div class="col-xs-3">
																				<div class="push10"></div>
																				<span class="fal fa-address-card fa-2x"></span>
																			</div>
																			<div class="col-xs-7">
																				<div class="push10"></div>
																				<div class="push5"></div>
																				<p style="font-size: 16px;">Meus Dados</p>
																			</div>
																			<div class="col-xs-2 text-right">
																				<div class="push10"></div>
																				<span class="fal fa-angle-right fa-2x"></span>
																			</div>
																			<div class="push"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_EXTRATO LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_extrato ?>">
																		<div class="outline">
																			<div class="push"></div>
																			<div class="col-xs-3">
																				<div class="push10"></div>
																				<span class="fal fa-file-invoice-dollar fa-2x"></span>
																			</div>
																			<div class="col-xs-7">
																				<div class="push10"></div>
																				<div class="push5"></div>
																				<p style="font-size: 16px;">Extrato</p>
																			</div>
																			<div class="col-xs-2 text-right">
																				<div class="push10"></div>
																				<span class="fal fa-angle-right fa-2x"></span>
																			</div>
																			<div class="push"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_MENSAGEM LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_mensagem ?>">
																		<div class="outline">
																			<div class="push"></div>
																			<div class="col-xs-3">
																				<div class="push10"></div>
																				<span class="fal fa-file-invoice-dollar fa-2x"></span>
																			</div>
																			<div class="col-xs-7">
																				<div class="push10"></div>
																				<div class="push5"></div>
																				<p style="font-size: 16px;">Mensagem</p>
																			</div>
																			<div class="col-xs-2 text-right">
																				<div class="push10"></div>
																				<span class="fal fa-angle-right fa-2x"></span>
																			</div>
																			<div class="push"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_PREMIOS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_premios ?>">
																		<div class="outline">
																			<div class="push"></div>
																			<div class="col-xs-3">
																				<div class="push10"></div>
																				<span class="fal fa-gifts fa-2x"></span>
																			</div>
																			<div class="col-xs-7">
																				<div class="push10"></div>
																				<div class="push5"></div>
																				<p style="font-size: 16px;">Prêmios</p>
																			</div>
																			<div class="col-xs-2 text-right">
																				<div class="push10"></div>
																				<span class="fal fa-angle-right fa-2x"></span>
																			</div>
																			<div class="push"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_PARCEIROS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_parceiros ?>">
																		<div class="outline">
																			<div class="push"></div>
																			<div class="col-xs-3">
																				<div class="push10"></div>
																				<span class="fal fa-handshake fa-2x"></span>
																			</div>
																			<div class="col-xs-7">
																				<div class="push10"></div>
																				<div class="push5"></div>
																				<p style="font-size: 16px;">Parceiros</p>
																			</div>
																			<div class="col-xs-2 text-right">
																				<div class="push10"></div>
																				<span class="fal fa-angle-right fa-2x"></span>
																			</div>
																			<div class="push"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_ENDERECOS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_enderecos ?>">
																		<div class="outline">
																			<div class="push"></div>
																			<div class="col-xs-3">
																				<div class="push10"></div>
																				<span class="fal fa-map-marker-alt fa-2x"></span>
																			</div>
																			<div class="col-xs-7">
																				<div class="push10"></div>
																				<div class="push5"></div>
																				<p style="font-size: 16px;">Endereços</p>
																			</div>
																			<div class="col-xs-2 text-right">
																				<div class="push10"></div>
																				<span class="fal fa-angle-right fa-2x"></span>
																			</div>
																			<div class="push"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_COMUNICA LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_comunica ?>">
																		<div class="outline">
																			<div class="push"></div>
																			<div class="col-xs-3">
																				<div class="push10"></div>
																				<span class="fal fa-user-headset fa-2x"></span>
																			</div>
																			<div class="col-xs-7">
																				<div class="push10"></div>
																				<div class="push5"></div>
																				<p style="font-size: 16px;">Fale Conosco</p>
																			</div>
																			<div class="col-xs-2 text-right">
																				<div class="push10"></div>
																				<span class="fal fa-angle-right fa-2x"></span>
																			</div>
																			<div class="push"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_AMIGOS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_amigos ?>">
																		<div class="outline">
																			<div class="push"></div>
																			<div class="col-xs-3">
																				<div class="push10"></div>
																				<span class="fal fa-handshake fa-2x"></span>
																			</div>
																			<div class="col-xs-7">
																				<div class="push10"></div>
																				<div class="push5"></div>
																				<p style="font-size: 16px;">Meus Amigos</p>
																			</div>
																			<div class="col-xs-2 text-right">
																				<div class="push10"></div>
																				<span class="fal fa-angle-right fa-2x"></span>
																			</div>
																			<div class="push"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_BRINDES LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_brindes ?>">
																		<div class="outline">
																			<div class="push"></div>
																			<div class="col-xs-3">
																				<div class="push10"></div>
																				<span class="fal fa-gifts fa-2x"></span>
																			</div>
																			<div class="col-xs-7">
																				<div class="push10"></div>
																				<div class="push5"></div>
																				<p style="font-size: 16px;">Meus Prêmios</p>
																			</div>
																			<div class="col-xs-2 text-right">
																				<div class="push10"></div>
																				<span class="fal fa-angle-right fa-2x"></span>
																			</div>
																			<div class="push"></div>
																		</div>
																	</a>

																</div>

																<!-- blocos coluna dupla -->

																<div id="colDupla" style="display: <?= $disp_dupla ?>">

																	<a href="javascript:void(0)" class="LOG_OFERTAS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_ofertas ?>">
																		<div class="outline">
																			<div class="push20"></div>
																			<span class="fal fa-tags fa-md"></span>
																			<div class="push10"></div>
																			<p style="font-size: 14px;">Minhas Ofertas</p>
																			<div class="push5"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_JORNAL LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_jornal ?>">
																		<div class="outline">
																			<div class="push20"></div>
																			<span class="fal fa-newspaper fa-md"></span>
																			<div class="push10"></div>
																			<p style="font-size: 14px;">Jornal de Ofertas</p>
																			<div class="push5"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_HABITO LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_habito ?>">
																		<div class="outline">
																			<div class="push20"></div>
																			<span class="fal fa-bags-shopping fa-md"></span>
																			<div class="push10"></div>
																			<p style="font-size: 14px;">Minhas Compras</p>
																			<div class="push5"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_DADOS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_dados ?>">
																		<div class="outline">
																			<div class="push20"></div>
																			<span class="fal fa-address-card fa-md"></span>
																			<div class="push10"></div>
																			<p style="font-size: 14px;">Meus Dados</p>
																			<div class="push5"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_EXTRATO LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_extrato ?>">
																		<div class="outline">
																			<div class="push20"></div>
																			<span class="fal fa-file-invoice-dollar fa-md"></span>
																			<div class="push10"></div>
																			<p style="font-size: 14px;">Extrato</p>
																			<div class="push5"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_MENSAGEM LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_mensagem ?>">
																		<div class="outline">
																			<div class="push20"></div>
																			<span class="fal fa-envelope fa-md"></span>
																			<div class="push10"></div>
																			<p style="font-size: 14px;">Mensagens</p>
																			<div class="push5"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_PREMIOS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_premios ?>">
																		<div class="outline">
																			<div class="push20"></div>
																			<span class="fal fa-gifts fa-md"></span>
																			<div class="push10"></div>
																			<p style="font-size: 14px;">Prêmios</p>
																			<div class="push5"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_PARCEIROS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_parceiros ?>">
																		<div class="outline">
																			<div class="push20"></div>
																			<span class="fal fa-handshake fa-md"></span>
																			<div class="push10"></div>
																			<p style="font-size: 14px;">Parceiros</p>
																			<div class="push5"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_ENDERECOS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_enderecos ?>">
																		<div class="outline">
																			<div class="push20"></div>
																			<span class="fal fa-map-marker-alt fa-md"></span>
																			<div class="push10"></div>
																			<p style="font-size: 14px;">Endereços</p>
																			<div class="push5"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_COMUNICA LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_comunica ?>">
																		<div class="outline">
																			<div class="push20"></div>
																			<span class="fal fa-user-headset fa-md"></span>
																			<div class="push10"></div>
																			<p style="font-size: 14px;">Fale Conosco</p>
																			<div class="push5"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_AMIGOS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_amigos ?>">
																		<div class="outline">
																			<div class="push20"></div>
																			<span class="fal fa-handshake fa-md"></span>
																			<div class="push10"></div>
																			<p style="font-size: 14px;">Meus Amigos</p>
																			<div class="push5"></div>
																		</div>
																	</a>

																	<a href="javascript:void(0)" class="LOG_BRINDES LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?= $cor_textos ?>; display: <?= $disp_brindes ?>">
																		<div class="outline">
																			<div class="push20"></div>
																			<span class="fal fa-gifts fa-md"></span>
																			<div class="push10"></div>
																			<p style="font-size: 14px;">Meus Prêmios</p>
																			<div class="push5"></div>
																		</div>
																	</a>

																</div>

																<!-- </div> -->

															</div>

														</div>

													</div>

												</div>

											</div>

										</div>

									</div>

									<div class="card" style="min-height: 840px;">

										<div class="push10"></div>

										<fieldset>
											<legend>Layout dos Campos</legend>

											<div class="push10"></div>

											<div class="col-md-12 text-center">
												<div class="form-group">
													<label for="inputName" class="control-label">Sombra nos Campos</label>
													<div class="push5"></div>
													<label class="switch switch-small">
														<input type="checkbox" name="LOG_SOMBRA" id="LOG_SOMBRA" class="switch switch-small" value="S" onchange="toggleMenu(this)" <?= $chk_sombra ?>>
														<span></span>
													</label>
												</div>
											</div>

											<div class="push10"></div>

											<div class="col-md-12 text-center">
												<div class="form-group">
													<label for="inputName" class="control-label">Campo Linha Simples</label>
													<div class="push5"></div>
													<label class="switch switch-small">
														<input type="checkbox" name="LOG_LINHA" id="LOG_LINHA" class="switch switch-small" value="S" onchange="toggleMenu(this)" <?= $chk_linha ?>>
														<span></span>
													</label>
												</div>
											</div>

											<div class="push10"></div>

											<div class="col-md-12 text-center">
												<div class="form-group">
													<label for="inputName" class="control-label">Campos Arredondados</label>
													<div class="push5"></div>
													<label class="switch switch-small">
														<input type="checkbox" name="LOG_ROUND" id="LOG_ROUND" class="switch switch-small" value="S" onchange="toggleMenu(this)" <?= $chk_round ?>>
														<span></span>
													</label>
													<!-- <input type="text" class="form-control input-sm int" name="VAL_ROUND" id="VAL_ROUND" placeholder="30" style="display: block"> -->
												</div>
											</div>

											<div class="push10"></div>

											<div class="col-md-12 text-center">
												<div class="form-group">
													<label for="inputName" class="control-label">LGPD Simplificado</label>
													<div class="push5"></div>
													<label class="switch switch-small">
														<input type="checkbox" name="LOG_LGPD_LT" id="LOG_LGPD_LT" class="switch switch-small" value="S" <?= $chk_lgpd ?>>
														<span></span>
													</label>
													<!-- <input type="text" class="form-control input-sm int" name="VAL_ROUND" id="VAL_ROUND" placeholder="30" style="display: block"> -->
												</div>
											</div>

											<div class="push10"></div>

										</fieldset>

									</div>

									<div class="card">
										<!-- bloco 4 -->

										<div class="iphone_bg">

											<div class="row">

												<div class="col-md-12">

													<div class="mobile_wrap">

														<div class="col-md-12 text-center fundoCel2">

															<div class="row fundoLogo2" style="background-color: <?= $cor_backbar ?>; color: <?= $cor_textfull ?>; min-height: 65px;">
																<div class="col-xs-12 textoCel1">
																	<div class="col-xs-3 text-center zeraPadLateral" style="min-height: 65px;">
																		<a class="center" href="javascript:void(0)" style="text-decoration: none; color: <?= $cor_textfull ?>"><i class="fal fa-home fa-2x" aria-hidden="true"></i></a>
																	</div>
																	<div class="col-xs-6 text-center zeraPadLateral" style="min-height: 65px;">
																		<div class="f21 center"><b>Cadastro</b></div>
																	</div>
																	<div class="col-xs-3 text-center zeraPadLateral" style="min-height: 65px;">
																		<?php if ($des_logo != '' && $des_logo != 0) { ?>
																			<img class="img-responsive center" alt="" width="65px" src="./media/clientes/<?php echo $cod_empresa ?>/<?php echo $des_logo ?>" style="padding-top: 5px; padding-bottom: 5px;">
																		<?php } else { ?>
																			<img class="img-responsive center" alt="" width="65px" src="./media/clientes/marka_white_small.png" style="padding-top: 5px; padding-bottom: 5px;">
																		<?php } ?>
																	</div>
																</div>
															</div>

															<div class="row" id="bloco_cadastro" style="display: <?= $displayCad ?>;">

																<div class="col-md-12" id="">

																	<div class="row" id="textoForm" style="text-align: left;">

																		<div class="push25"></div>
																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="required">Nome</label>
																				<input type="text" class="form-control input-sm" value="Roberto">
																			</div>
																		</div>
																		<div class="push20"></div>

																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="required">Email</label>
																				<input type="text" class="form-control input-sm" value="roberto@email.com">
																			</div>
																		</div>
																		<div class="push20"></div>

																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="required">Data de Nascimento</label>
																				<input type="text" class="form-control input-sm" value="01/01/2000">
																			</div>
																		</div>
																		<div class="push20"></div>

																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="required">Profissão</label>
																				<div class="push"></div>
																				<select data-placeholder="Selecione a profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect">
																					<option value=""></option>
																					<?php
																					$sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES order by DES_PROFISS ";
																					$arrayQuery = mysqli_query($adm, $sql);

																					while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery)) {
																						echo "
																						<option value='" . $qrListaSexo['COD_PROFISS'] . "'>" . $qrListaSexo['DES_PROFISS'] . "</option> 
																						";
																					}
																					?>
																				</select>
																				<script type="text/javascript">
																					$("#COD_PROFISS").val(6).trigger("chosen:updated");
																				</script>
																				<div class="help-block with-errors"></div>
																			</div>
																		</div>
																		<div class="push20"></div>

																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="required">Celular</label>
																				<input type="text" class="form-control input-sm" value="(12) 98877-6655">
																			</div>
																		</div>
																		<div class="push20"></div>

																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="required">Sexo</label>
																				<div class="push"></div>
																				<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect">
																					<option value=""></option>
																					<?php
																					$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
																					$arrayQuery = mysqli_query($adm, $sql);

																					while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery)) {
																						echo "
																						<option value='" . $qrListaSexo['COD_SEXOPES'] . "'>" . $qrListaSexo['DES_SEXOPES'] . "</option> 
																						";
																					}
																					?>
																				</select>
																				<script type="text/javascript">
																					$("#COD_SEXOPES").val(1).trigger("chosen:updated");
																				</script>
																				<div class="help-block with-errors"></div>
																			</div>
																		</div>
																		<div class="push20"></div>

																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="required">Crie sua Senha de Acesso</label>
																				<input type="password" class="form-control input-sm" value="11223344">
																				<div class="help-block with-errors">Máximo de 6 dígitos numéricos</div>
																			</div>
																		</div>
																		<div class="push20"></div>

																		<div class="col-md-12">
																			<div class="form-group">
																				<label class="required">Confirme sua Senha</label>
																				<input type="password" class="form-control input-sm" value="">
																			</div>
																		</div>
																		<div class="push20"></div>



																	</div>

																	<div class="push20"></div>

																	<button type="button" name="CAD2" id="CAD2" class="btn btn-secondary btn-lg btn-block getBtn col-md-12 shadow2" style="color: #fff; border-radius: 35px;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>

																</div>

															</div>

															<div class="row" id="bloco_lgpd" style="display: <?= $displayLgpd ?>;">

																<div class="col-md-12">

																	<div class="row" style="width: 360px;">

																		<div class="text-center">

																			<div class="push100"></div>

																			<div class="col-xs-12 text-center">

																				<h4 style="white-space: normal !important;">Atualizamos nosso regulamento, aviso de privacidade e termos de consentimento</h4>

																			</div>

																			<div class="push20"></div>

																			<div class="row">

																				<div class="col-xs-8 col-xs-offset-2 text-center">
																					<a href="javascript:void(0)" class="btn btn-success">Aceitar e ler mais tarde</a>
																				</div>

																			</div>

																			<div class="push10"></div>

																			<div class="row">

																				<div class="col-xs-8 col-xs-offset-2 text-center">
																					<a href="javascript:void(0)" class="btn btn-sm btn-info">Ler agora</a>
																				</div>

																			</div>

																		</div>

																	</div>

																</div>

															</div>

														</div>

													</div>

												</div>

											</div>

										</div>

									</div>

									<div class="card">
										<!-- bloco 2 -->

										<div class="iphone_bg">

											<div class="row">

												<div class="col-md-12">

													<div class="mobile_wrap">

														<div class="container-wrap col-xs-12 fundoCel2">

															<div class="row fundoLogo2" style="background-color: <?= $cor_backbar ?>; color: <?= $cor_textfull ?>; min-height: 65px;">
																<div class="col-xs-12 textoCel1">
																	<div class="col-xs-3 text-center zeraPadLateral" style="min-height: 65px;">
																		<a class="center" href="javascript:void(0)" style="text-decoration: none; color: <?= $cor_textfull ?>"><i class="fal fa-home fa-2x" aria-hidden="true"></i></a>
																	</div>
																	<div class="col-xs-6 text-center zeraPadLateral" style="min-height: 65px;">
																		<div class="f21 center"><b>Listas</b></div>
																	</div>
																	<div class="col-xs-3 text-center zeraPadLateral" style="min-height: 65px;">
																		<?php if ($des_logo != '' && $des_logo != 0) { ?>
																			<img class="img-responsive center" alt="" width="65px" src="./media/clientes/<?php echo $cod_empresa ?>/<?php echo $des_logo ?>" style="padding-top: 5px; padding-bottom: 5px;">
																		<?php } else { ?>
																			<img class="img-responsive center" alt="" width="65px" src="./media/clientes/marka_white_small.png" style="padding-top: 5px; padding-bottom: 5px;">
																		<?php } ?>
																	</div>
																</div>
															</div>

															<div class="push20"></div>

															<div class="row">

																<div class="col-xs-12 text-center corIcones" style="color: <?= $cor_textos ?>">
																	<p class="f12"><strong>LISTA DE OFERTAS</strong></p>
																	<div class="push5"></div>
																	<!-- <hr style="border-color: <?= $cor_textos ?>"> -->
																</div>

																<!-- blocos coluna única -->

																<div id="listaOfertas">

																	<div class="col-xs-12 reduzMargem corIcones" style="color: <?= $cor_textos ?>">
																		<div class="shadow2">
																			<div class="push5"></div>
																			<div class="col-xs-5 text-center" style="height: 90px; padding: 0;">
																				<div class="img-lista center" style="background: url('./media/clientes/3/gasolina.jpg') no-repeat center; background-size: auto 85px;"></div>
																			</div>
																			<div class="col-xs-7">
																				<h5><b>Gasolina comum</b></h5>
																				<p><small><strike>DE: R$4,297</strike></small><br />
																					POR: <b>R$3,875</b></p>
																			</div>
																			<div class="push5"></div>
																		</div>
																	</div>


																	<div class="col-xs-12 reduzMargem corIcones" style="color: <?= $cor_textos ?>">
																		<div class="shadow2">
																			<div class="push5"></div>
																			<div class="col-xs-5 text-center" style="height: 90px; padding: 0;">
																				<div class="img-lista center" style="background: url('./media/clientes/3/remedio.jpg') no-repeat center; background-size: auto 85px;"></div>
																			</div>
																			<div class="col-xs-7">
																				<h5><b>Analgésico</b></h5>
																				<p><small><strike>DE: R$8,75</strike></small><br />
																					POR: <b>R$5,99</b></p>
																			</div>
																			<div class="push5"></div>
																		</div>
																	</div>



																	<div class="col-xs-12 reduzMargem corIcones" style="color: <?= $cor_textos ?>">
																		<div class="shadow2">
																			<div class="push5"></div>
																			<div class="col-xs-5 text-center" style="height: 90px; padding: 0;">
																				<div class="img-lista center" style="background: url('./media/clientes/3/cosmetico.jpg') no-repeat center; background-size: auto 85px;"></div>
																			</div>
																			<div class="col-xs-7">
																				<h5><b>Kit de maquiagem</b></h5>
																				<p><small><strike>DE: R$69,90</strike></small><br />
																					POR: <b>R$49,90</b></p>
																			</div>
																			<div class="push5"></div>
																		</div>
																	</div>

																</div>

																<!-- blocos coluna única -->

																<div class="push10"></div>

																<div class="col-xs-12 text-center corIcones" style="color: <?= $cor_textos ?>">
																	<p class="f12"><strong>LISTA DO EXTRATO</strong></p>
																	<div class="push5"></div>
																	<!-- <hr style="border-color: <?= $cor_textos ?>"> -->
																</div>

																<div id="listaExtrato">

																	<div class="col-xs-12 reduzMargem corIcones" style="color: <?= $cor_textos ?>">
																		<div class="shadow2">
																			<div class="push5"></div>
																			<div class="col-xs-4 zeraPadLateral text-center">
																				<h5 class="f12"><b><?= Date("d/m/Y") ?></b><br /><span class="f10"><?= Date("H:i:s") ?></span></h5>
																			</div>
																			<div class="col-xs-5 zeraPadLateral text-center">
																				<h5>Matriz</h5>
																			</div>
																			<div class="col-xs-3 zeraPadLateral text-center">
																				<h5>- 2,00</h5>
																			</div>
																			<div class="push5"></div>
																		</div>
																	</div>


																	<div class="col-xs-12 reduzMargem corIcones" style="color: <?= $cor_textos ?>">
																		<div class="shadow2">
																			<div class="push5"></div>
																			<div class="col-xs-4 zeraPadLateral text-center">
																				<h5 class="f12"><b><?= Date("d/m/Y") ?></b><br /><span class="f10"><?= Date("H:i:s") ?></span></h5>
																			</div>
																			<div class="col-xs-5 zeraPadLateral text-center">
																				<h5>Filial 103</h5>
																			</div>
																			<div class="col-xs-3 zeraPadLateral text-center">
																				<h5>+ 0,23</h5>
																			</div>
																			<div class="col-xs-12">
																				<h5 class="f10 text-danger" style="margin-top: -10px; margin-bottom: 0;">Expirado em: <b>07/03/2020</b></h5>
																			</div>
																			<div class="push5"></div>
																		</div>
																	</div>

																	<div class="col-xs-12 reduzMargem corIcones" style="color: <?= $cor_textos ?>">
																		<div class="shadow2">
																			<div class="push5"></div>
																			<div class="col-xs-4 zeraPadLateral text-center">
																				<h5 class="f12"><b><?= Date("d/m/Y") ?></b><br /><span class="f10"><?= Date("H:i:s") ?></span></h5>
																			</div>
																			<div class="col-xs-5 zeraPadLateral text-center">
																				<h5>Filial 99</h5>
																			</div>
																			<div class="col-xs-3 zeraPadLateral text-center">
																				<h5>+ 0,54</h5>
																			</div>
																			<div class="col-xs-12">
																				<h5 class="f10" style="margin-top: -10px; margin-bottom: 0;">Expira em: <b><?= date("d/m/Y", strtotime('+ 30 days')) ?></b></h5>
																			</div>
																			<div class="push5"></div>
																		</div>
																	</div>

																</div>

																<!-- blocos coluna única -->

																<div class="push10"></div>

																<div class="col-xs-12 text-center corIcones" style="color: <?= $cor_textos ?>">
																	<p class="f12"><strong>LISTA DO HÁBITO</strong></p>
																	<div class="push5"></div>
																	<!-- <hr style="border-color: <?= $cor_textos ?>"> -->
																</div>

																<div id="listaHabito">

																	<div class="col-xs-12 reduzMargem corIcones" style="color: <?= $cor_textos ?>">
																		<div class="shadow2">
																			<div class="push5"></div>
																			<div class="col-xs-5 text-center" style="height: 90px; padding: 0;">
																				<div class="img-lista center" style="background: url('./media/clientes/3/depilacao.jpg') no-repeat center; background-size: auto 85px;"></div>
																			</div>
																			<div class="col-xs-7">
																				<h5><b>Depilação à cera</b></h5>
																				<p class="f14"><?= Date("d/m/Y") ?><br /><span class="f10"><?= Date("H:i:s") ?></span></p>
																			</div>
																			<div class="push5"></div>
																		</div>
																	</div>


																	<div class="col-xs-12 reduzMargem corIcones" style="color: <?= $cor_textos ?>">
																		<div class="shadow2">
																			<div class="push5"></div>
																			<div class="col-xs-5 text-center" style="height: 90px; padding: 0;">
																				<div class="img-lista center" style="background: url('./media/clientes/3/garrafa.jpg') no-repeat center; background-size: auto 85px;"></div>
																			</div>
																			<div class="col-xs-7">
																				<h5><b>Garrafa térmica</b></h5>
																				<p class="f14"><?= Date("d/m/Y") ?><br /><span class="f10"><?= Date("H:i:s") ?></span></p>
																			</div>
																			<div class="push5"></div>
																		</div>
																	</div>



																	<div class="col-xs-12 reduzMargem corIcones" style="color: <?= $cor_textos ?>">
																		<div class="shadow2">
																			<div class="push5"></div>

																			<div class="col-xs-7">
																				<h5><b>Caixa de ferramentas</b></h5>
																				<p class="f14"><?= Date("d/m/Y") ?><br /><span class="f10"><?= Date("H:i:s") ?></span></p>
																			</div>
																			<div class="push5"></div>
																		</div>
																	</div>

																</div>

																<!-- </div> -->

															</div>

														</div>

													</div>

												</div>

											</div>

										</div>

									</div>

									<div class="card">
										<!-- bloco 3 -->

										<div class="iphone_bg">

											<div class="row">

												<div class="col-md-12">

													<div class="mobile_wrap">

														<div class="container-wrap col-md-12 text-center fundoCel2">

															<div class="row fundoLogo2" style="background-color: <?= $cor_backbar ?>; color: <?= $cor_textfull ?>; min-height: 65px;">
																<div class="col-xs-12 textoCel1">
																	<div class="col-xs-3 text-center zeraPadLateral" style="min-height: 65px;">
																		<a class="center" href="javascript:void(0)" style="text-decoration: none; color: <?= $cor_textfull ?>"><i class="fal fa-home fa-2x" aria-hidden="true"></i></a>
																	</div>
																	<div class="col-xs-6 text-center zeraPadLateral" style="min-height: 65px;">
																		<div class="f21 center"><b>Jornal de Ofertas</b></div>
																	</div>
																	<div class="col-xs-3 text-center zeraPadLateral" style="min-height: 65px;">
																		<?php if ($des_logo != '' && $des_logo != 0) { ?>
																			<img class="img-responsive center" alt="" width="65px" src="./media/clientes/<?php echo $cod_empresa ?>/<?php echo $des_logo ?>" style="padding-top: 5px; padding-bottom: 5px;">
																		<?php } else { ?>
																			<img class="img-responsive center" alt="" width="65px" src="./media/clientes/marka_white_small.png" style="padding-top: 5px; padding-bottom: 5px;">
																		<?php } ?>
																	</div>
																</div>
															</div>

															<div class="row">

																<div class="push20"></div>

																<div class="col-md-12" id="">

																	<?php

																	$sql1 = "SELECT A.DES_IMAGEM, 
																					  A.DES_LINK, 
																					  A.DES_BANNER, 
																					  A.LOG_LINKWHATS FROM BANNER_APP A 
																				WHERE A.COD_EMPRESA = $cod_empresa 
																				AND A.COD_EXCLUSA = 0 
																				AND A.LOG_ATIVO = 'S' 
																				ORDER BY A.DES_BANNER";

																	// fnEscreve($sql1);
																	$arrayQuery = mysqli_query($conn, $sql1);

																	$cod_empresa_path = $cod_empresa;

																	if (mysqli_num_rows($arrayQuery) == 0) {

																		// fnEscreve('else');

																		$sql2 = "SELECT A.DES_IMAGEM, 
																						  A.DES_LINK, 
																						  A.DES_BANNER, 
																						  A.LOG_LINKWHATS FROM BANNER_APP A 
																					WHERE A.COD_EMPRESA = 7 
																					AND A.COD_EXCLUSA = 0 
																					AND A.LOG_ATIVO = 'S' 
																					ORDER BY A.DES_BANNER";

																		// fnEscreve($sql1);
																		$arrayQuery = mysqli_query(connTemp(7, ""), $sql2);
																		$cod_empresa_path = 7;
																	}

																	?>

																	<div id="carouselOfertas" class="carousel slide shadow2">

																		<ol class="carousel-indicators">
																			<?php

																			$count = 0;
																			$active = 'active';

																			while (mysqli_num_rows($arrayQuery) > $count) {

																			?>
																				<li data-target="#carouselOfertas" data-slide-to="<?= $count ?>" class="<?= $active ?>"></li>
																			<?php

																				$count++;
																				$active = '';
																			}

																			?>
																		</ol>
																		<div class="carousel-inner shadow2">

																			<?php

																			$active = 'active';

																			while ($qrJornal = mysqli_fetch_assoc($arrayQuery)) {

																			?>

																				<div class="item <?= $active ?>">
																					<?php
																					if ($qrJornal['DES_IMAGEM'] != '') {

																						if ($qrJornal['DES_LINK'] != '') {

																							if ($qrJornal['LOG_LINKWHATS'] == 'S') {
																								$link = "https://api.whatsapp.com/send?phone=" . $qrJornal['DES_LINK'] . "&text=" . urlencode($qrJornal['DES_BANNER']);
																							} else {
																								$link = $qrJornal['DES_LINK'];
																							}
																					?>
																							<a href="<?= $link ?>">
																								<img src="https://img.bunker.mk/media/clientes/<?= $cod_empresa_path ?>/banner/<?= $qrJornal['DES_IMAGEM'] ?>" width="100%">
																							</a>
																						<?php
																						} else {
																						?>
																							<img src="https://img.bunker.mk/media/clientes/<?= $cod_empresa_path ?>/banner/<?= $qrJornal['DES_IMAGEM'] ?>" width="100%">
																						<?php
																						}
																					} else {
																						?>
																						<img src="https://img.bunker.mk/media/clientes/branco.jpg" width="100%">
																					<?php
																					}
																					?>
																				</div>

																			<?php

																				$active = '';
																			}

																			?>

																		</div>

																		<!-- Carousel controls -->
																		<a class="carousel-control left" href="#carouselOfertas" data-slide="prev">
																			<div class="push20"></div>
																			<span class="fal fa-angle-left"></span>
																		</a>
																		<a class="carousel-control right" href="#carouselOfertas" data-slide="next">
																			<div class="push20"></div>
																			<span class="fal fa-angle-right"></span>
																		</a>

																	</div>

																	<div class="push20"></div>

																</div>

															</div>

														</div>

													</div>

												</div>

											</div>

										</div>

									</div>

									<div class="card">
										<!-- bloco 5 -->

										<div class="iphone_bg">

											<div class="row">

												<div class="col-md-12">

													<div class="mobile_wrap">

														<?php
														if ($des_imgback != '' && $des_imgback != 0) {
														?>
															<div class="container-wrap col-xs-12 text-center" id="fundoCel1Alt" style="background: url(media/clientes/<?php echo $cod_empresa ?>/<?php echo $des_imgback ?>);">
															<?php
														} else {
															echo "<div class='container-wrap col-xs-12 text-center fundoCel1'>";
														} ?>

															<div class="row">

																<div class="row fundoLogo2" style="background-color: <?= $cor_backbar ?>; color: <?= $cor_textfull ?>; min-height: 65px;">
																	<div class="col-xs-12 textoCel1">
																		<div class="col-xs-3 text-center zeraPadLateral" style="min-height: 65px;">
																			<a class="center" href="javascript:void(0)" style="text-decoration: none; color: <?= $cor_textfull ?>"><i class="fal fa-home fa-2x" aria-hidden="true"></i></a>
																		</div>
																		<div class="col-xs-6 text-center zeraPadLateral" style="min-height: 65px;">
																			<div class="f21 center"><b></b></div>
																		</div>
																		<div class="col-xs-3 text-center zeraPadLateral" style="min-height: 65px;">
																			<?php if ($des_logo != '' && $des_logo != 0) { ?>
																				<img class="img-responsive center" alt="" width="65px" src="./media/clientes/<?php echo $cod_empresa ?>/<?php echo $des_logo ?>" style="padding-top: 5px; padding-bottom: 5px;">
																			<?php } else { ?>
																				<img class="img-responsive center" alt="" width="65px" src="./media/clientes/marka_white_small.png" style="padding-top: 5px; padding-bottom: 5px;">
																			<?php } ?>
																		</div>
																	</div>
																</div>

																<div class="col-md-12 nomeCel1">
																	<input type="text" class="form-control input-lg f21 bold text-center" value="Roberto">
																</div>

																<div class="push20"></div>

																<div class="col-md-12 textoCel1">
																	<h3>PARABÉNS</h3>
																	<span class="f21">
																		<strong>você tem</strong><br />
																		<strong style="font-size: 60px;">999</strong>
																	</span><br />
																	<span class="f12">
																		PONTOS ACUMULADOS
																	</span>
																</div>

																<div class="push100"></div>

																<div class="col-md-12 tabelaCel1">
																	<table class="table table-bordered texto2Cel1">
																		<tbody>
																			<tr>
																				<td class="text-center">
																					<span class="f21">123</span><br />
																					<span class="f12">Saldo Anterior</span>
																				</td>
																				<td class="text-center">
																					<span class="f21">456</span><br />
																					<span class="f12">Nesta Compra</span>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</div>

																<div class="push5"></div>

															</div>

															</div>

													</div>

												</div>

											</div>

										</div>

									</div>

								</div>

							</div>

							<!-- form fechado antes para não enviar apps modelo -->
					</form>

					<div class="push50"></div>

				</div>

			</div>

		</div>
		<!-- fim Portlet -->

	</div>

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

<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script type="text/javascript">
	$(window).on('load', function() {

		let log_sombra = "<?= $log_sombra ?>",
			log_round = "<?= $log_round ?>",
			log_linha = "<?= $log_linha ?>";

		if (log_sombra == "S") {
			$("#COD_SEXOPES_chosen .chosen-single,#COD_PROFISS_chosen .chosen-single").addClass("shadow2");
			$("#bloco_cadastro").find('.input-sm').addClass("shadow2");
		}

		// else{
		// 	$("#COD_SEXOPES_chosen .chosen-single,#COD_PROFISS_chosen .chosen-single").removeClass("shadow2");
		// $("#bloco_cadastro").find('.input-sm').removeClass("shadow2");
		// }

		if (log_round == "S") {
			$("#COD_SEXOPES_chosen .chosen-single,#COD_PROFISS_chosen .chosen-single").addClass("rounded-corners").css("borderRadius", "30px");
			$("#bloco_cadastro").find('.input-sm').addClass("rounded-corners").css("borderRadius", "30px");
		}

		// else{
		// 	$("#COD_SEXOPES_chosen .chosen-single,#COD_PROFISS_chosen .chosen-single").removeClass("rounded-corners").css("borderRadius","0px");
		// $("#bloco_cadastro").find('.input-sm').removeClass("rounded-corners").css("borderRadius","0px");
		// }

		if (log_linha == "S") {
			$("#COD_SEXOPES_chosen .chosen-single,#COD_PROFISS_chosen .chosen-single").addClass("campo-linha").addClass("shadow-linha");
			$("#bloco_cadastro").find('.input-sm').addClass("campo-linha").addClass("shadow-linha");
		}

		// else{
		// 	$("#COD_SEXOPES_chosen .chosen-single,#COD_PROFISS_chosen .chosen-single").removeClass("campo-linha").removeClass("shadow-linha");
		// $("#bloco_cadastro").find('.input-sm').removeClass("campo-linha").removeClass("shadow-linha");
		// }




	});

	$(document).ready(function() {

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		//color picker
		$('.pickColor').minicolors({
			control: $(this).attr('data-control') || 'hue',
			theme: 'bootstrap'
		});

		$('#COD_SEXOPES_chosen .chosen-single').addClass('corIcones');

		// $('#LOG_BANNERLISTA,#LOG_BANNERHOME').change(function(){
		// 	var idUncheck = "#LOG_BANNERLISTA"; 
		// 	if($(this).attr("id") == "LOG_BANNERLISTA"){
		// 		idUncheck = "#LOG_BANNERHOME";
		// 	}
		// 	$(idUncheck).prop('checked', false);
		// });

		// cor fundo cel 1 e 2
		mudaCor('.fundoCel1', 'background', '#COR_FULLPAG');
		mudaCor('.fundoCel2', 'background', '#COR_BACKPAG');

		//mudando cor do fundo com imagem;
		$('#COR_FULLPAG').change(function() {
			$('#fundoCel1Alt').css('background', $('#COR_FULLPAG').val());
			$('#DES_IMGBACK').val("");
		});

		// texto contraste
		mudaCor('.textoCel1', 'color', '#COR_TEXTFULL');
		mudaCor('.texto2Cel1', 'color', '#COR_TEXTFULL');
		mudaCor('.tabelaCel1>.table-bordered>tbody>tr>td', 'border-color', '#COR_TEXTFULL');

		//cor textos
		$('#textoForm').find('input').css('color', $('#COR_TEXTOS').val());
		$('.nomeCel1').find('input').css('color', $('#COR_TEXTOS').val());
		$('#COR_TEXTOS').change(function() {
			$('#textoForm').find('input').css('color', $('#COR_TEXTOS').val());
			$('.nomeCel1').find('input').css('color', $('#COR_TEXTOS').val());
			$('.corIcones, #COD_SEXOPES_chosen .chosen-single').css('color', $('#COR_TEXTOS').val());
		});

		//cor botão
		mudaCor('#CAD2', 'background', '#COR_BOTAO');

		// cor da backbar
		mudaCor('.fundoLogo2', 'background', '#COR_BACKBAR');

		//cor botão ON
		$('#CAD2').mouseover(function() {
			$('#CAD2').css('background', $('#COR_BOTAOON').val());
		});
		$('#CAD2').mouseleave(function() {
			mudaCor('#CAD2', 'background', '#COR_BOTAO');
		});

		$("#LOG_LGPD_LT").on("change", function() {
			if ($(this).prop('checked')) {
				$("#bloco_cadastro").fadeOut("fast");
				$("#bloco_lgpd").fadeIn("fast");
			} else {
				$("#bloco_lgpd").fadeOut("fast");
				$("#bloco_cadastro").fadeIn("fast");
			}
		});

		$("#LOG_SOMBRA").on("change", function() {
			if ($(this).prop('checked')) {
				$("#COD_SEXOPES_chosen .chosen-single,#COD_PROFISS_chosen .chosen-single").addClass("shadow2");
				$("#bloco_cadastro").find('.input-sm').addClass("shadow2");
			} else {
				$("#COD_SEXOPES_chosen .chosen-single,#COD_PROFISS_chosen .chosen-single").removeClass("shadow2");
				$("#bloco_cadastro").find('.input-sm').removeClass("shadow2");
			}
		});

		$("#LOG_ROUND").on("change", function() {
			if ($(this).prop('checked')) {
				$("#COD_SEXOPES_chosen .chosen-single,#COD_PROFISS_chosen .chosen-single").addClass("rounded-corners").css("borderRadius", "30px");
				$("#bloco_cadastro").find('.input-sm').addClass("rounded-corners").css("borderRadius", "30px");
			} else {
				$("#COD_SEXOPES_chosen .chosen-single,#COD_PROFISS_chosen .chosen-single").removeClass("rounded-corners").css("borderRadius", "0px");
				$("#bloco_cadastro").find('.input-sm').removeClass("rounded-corners").css("borderRadius", "0px");
			}
		});

		$("#LOG_LINHA").on("change", function() {
			if ($(this).prop('checked')) {
				$("#COD_SEXOPES_chosen .chosen-single,#COD_PROFISS_chosen .chosen-single").addClass("campo-linha").addClass("shadow-linha");
				$("#bloco_cadastro").find('.input-sm').addClass("campo-linha").addClass("shadow-linha");
			} else {
				$("#COD_SEXOPES_chosen .chosen-single,#COD_PROFISS_chosen .chosen-single").removeClass("campo-linha").removeClass("shadow-linha");
				$("#bloco_cadastro").find('.input-sm').removeClass("campo-linha").removeClass("shadow-linha");
			}
		});

		$("#LOG_EXPIRA").on("change", function() {
			if ($(this).prop('checked')) {
				$("#saldoParcial").fadeOut("fast", function() {
					$("#saldoFull").fadeIn("fast");
				});
			} else {
				$("#saldoFull").fadeOut("fast", function() {
					$("#saldoParcial").fadeIn("fast");
				});
			}
		});

	});

	// função para mudar a cor
	function mudaCor(elemento, elementocss, colorpicker) {
		$(elemento).css(elementocss, $(colorpicker).val());
		$(colorpicker).change(function() {
			$(elemento).css(elementocss, $(colorpicker).val());
		});
	}

	// função para mudar a cor da sombra
	function mudaCorSombra(elemento, elementocss, colorpicker) {

		let cor = hexToRgb($(colorpicker).val());

		if (cor['r'] > 50) {
			$r = (cor['r'] - 50);
		} else {
			$r = (cor['r'] + 50);
			if (cor['r'] < 30) {
				$r = cor['r'];
			}
		}
		if (cor['g'] > 50) {
			$g = (cor['g'] - 50);
		} else {
			$g = (cor['g'] + 50);
			if (cor['g'] < 30) {
				$g = cor['g'];
			}
		}
		if (cor['b'] > 50) {
			$b = (cor['b'] - 50);
		} else {
			$b = (cor['b'] + 50);
			if (cor['b'] < 30) {
				$b = cor['b'];
			}
		}

		if (cor['r'] <= 50 && cor['g'] <= 50 && cor['b'] <= 50) {
			$r = (cor['r'] + 40);
			$g = (cor['g'] + 40);
			$b = (cor['b'] + 40);
		}

		$('.shadow').css({
			'box-shadow': '0px 0px 18px -2px rgba(' + $r + ',' + $g + ',' + $b + ',0.8)',
			'-webkit-box-shadow': '0px 0px 18px -2px rgba(' + $b + ',' + $g + ',' + $b + ',0.8)',
			'-moz-box-shadow': '0px 0px 18px -2px rgba(' + $b + ',' + $g + ',' + $b + ',0.8)'
		});

		$('.shadow2, .rounded-corners').css({
			'box-shadow': '0px 5px 8px 0px rgba(' + $b + ',' + $g + ',' + $b + ',0.8)',
			'-webkit-box-shadow': '0px 5px 8px 0px rgba(' + $b + ',' + $g + ',' + $b + ',0.8)',
			'-moz-box-shadow': '0px 5px 8px 0px rgba(' + $b + ',' + $g + ',' + $b + ',0.8)'
		});

	}

	function hexToRgb(hex) {
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
		hex = hex.replace(shorthandRegex, function(m, r, g, b) {
			return r + r + g + g + b + b;
		});

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
		} : null;
	}

	function retornaForm(index) {
		$("#formulario #COD_MAQUINA").val($("#ret_COD_MAQUINA_" + index).val());
		$("#formulario #DES_MAQUINA").val($("#ret_DES_MAQUINA_" + index).val());
		$("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	// função que controla os itens do menu da app
	function toggleMenu(obj) {

		let elemento = $(obj).attr('id');

		if (elemento != 'LOG_COLUNAS') {

			if ($(obj).prop('checked')) {
				$('.' + elemento).fadeIn(1);
			} else {
				$('.' + elemento).fadeOut(1);
			}

		} else {

			if ($(obj).prop('checked')) {
				$('#colUnica').fadeOut('fast', function() {
					$('#colDupla').fadeIn('fast');
				});
			} else {
				$('#colDupla').fadeOut('fast', function() {
					$('#colUnica').fadeIn('fast');
				});
			}

		}

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
				$('.jconfirm-open').fadeOut(300, function() {
					$(this).remove();
				});
				if (!data.trim()) {
					$('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);

					//guardando caminho do upload para usar depois
					var pathLogo = 'media/clientes/<?php echo $cod_empresa ?>/' + nomeArquivo;
					//--------------------------------------------------
					$.alert({
						title: "Mensagem",
						content: "Upload feito com sucesso",
						type: 'green'
					});

					//se upar a logo
					if (idField == 'arqUpload_DES_LOGO') {
						//usando o caminho do upload como parâmetro
						//exibindo imagem na logo
						$('#logoCel1').attr('src', pathLogo);
						$('#logoCel2').attr('src', pathLogo);
						//se upar o fundo
					} else {
						$('#fundoCel1').css('background', 'url(' + pathLogo + ')');
					}

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