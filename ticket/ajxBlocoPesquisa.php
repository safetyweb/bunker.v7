<?php
include "../_system/_functionsMain.php";

//echo fnDebug('true');

//habilitando o cors
//header("Access-Control-Allow-Origin: *");

$cod_empresa = fnLimpacampoZero($_GET['cod_empresa']);
$cod_pesquisa = fnLimpacampoZero($_GET['cod_pesquisa']);
$cod_players = fnLimpacampoZero($_GET['COD_PLAYERS']);
$log_hotsite = fnLimpacampoZero($_GET['LOG_HOTSITE']);
$opcao = fnLimpacampo($_GET['opcao']);
// echo '_'.$opcao;
// exit();
// echo '_'.$_GET['LOG_HOTSITE'];
// echo '_'.$log_hotsite;
$opcaoIr = 'btnContinuar';
$cod_cliente_totem = fnLimpaCampoZero(fnDecode($_GET['cod_cliente_totem']));

if($cod_players != 0){

	$sql = "SELECT * FROM  USUARIOS
			WHERE LOG_ESTATUS='S' AND
				  COD_EMPRESA = $cod_empresa AND
				  COD_TPUSUARIO=10  limit 1  ";
	// echo($sql);
	// exit();
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
					
	// echo('chegou');
	// exit();
	if (isset($arrayQuery)) {
		$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
		$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
	}

}


// echo "_".$cod_pesquisa."<br>";
// echo "_".$_GET['cod_pesquisa']."<br>";
// exit();
        
switch ($opcao) {
	case 'salvar':

		$cod_registro = fnLimpacampoZero($_GET['cod_registro']);
		$cod_modpesquisa = fnLimpacampoZero($_GET['cod_modpesquisa']);
		
		if($_GET['cod_cliente'] != 'preview'){
			$cod_cliente = fnLimpacampoZero($_GET['cod_cliente']);
			$defineSalvo = 1;
		}else{
			$cod_cliente = $_GET['cod_cliente'];
			$defineSalvo = 0;
		}

		// echo $defineSalvo;
		// exit();

		// echo $cod_players;
		
		$resposta_numero = fnLimpacampoZero($_GET['resposta_numero']);
		$nps = fnLimpacampoZero($_GET['nps']);
		$resposta_texto = fnLimpacampo($_GET['resposta_texto']);

		


		if($defineSalvo == 1){
	
			$sql = "INSERT INTO DADOS_PESQUISA_ITENS 
					(COD_REGISTRO, COD_PESQUISA, COD_PERGUNTA, COD_EMPRESA, RESPOSTA_NUMERO, RESPOSTA_TEXTO, COD_NPSTIPO) 
			 VALUES ($cod_registro, $cod_pesquisa, $cod_modpesquisa, $cod_empresa, $resposta_numero, '$resposta_texto', $nps
					);";
			// echo $sql;
			// exit();
			mysqli_query(connTemp($cod_empresa,''),$sql);
		}

	break;

	case 'login':
		$cod_registro = fnLimpacampoZero($_GET['cod_registro']);
		$cpf = fnLimpacampo(fnLimpaDoc($_GET['cpf']));
		$email = fnLimpacampo($_GET['email']);
		$celular = fnLimpacampo(fnLimpaDoc($_GET['celular']));
		$senha = fnLimpacampo($_GET['senha']);
		

		if($cod_players != 0){
			$unidade_resposta = "(SELECT COD_UNIVEND FROM TOTEM_PLAYERS
									WHERE COD_PLAYERS = $cod_players 
									AND COD_EMPRESA = $cod_empresa)";
		}else{
			$unidade_resposta = "CASE 
									WHEN (SELECT COD_UNIVEND FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa) IS NOT NULL  
										THEN (SELECT COD_UNIVEND FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa)
									WHEN (SELECT MAX(COD_UNIVEND) FROM VENDAS WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa) IS NOT NULL  
										THEN (SELECT MAX(COD_UNIVEND) FROM VENDAS WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa)
									WHEN (SELECT COD_UNIVEND FROM unidadevenda WHERE LOG_ESTATUS = 'S' AND LOG_ESTATUS = 'S' AND LOG_UNIPREF = 'S' AND COD_EMPRESA = $cod_empresa LIMIT 1 ) > 0 
										THEN  (SELECT COD_UNIVEND FROM unidadevenda WHERE LOG_ESTATUS = 'S' AND LOG_ESTATUS = 'S' AND LOG_UNIPREF = 'S' AND COD_EMPRESA = $cod_empresa LIMIT 1 )
									ELSE (SELECT COD_UNIVEND FROM unidadevenda WHERE LOG_ESTATUS = 'S' and COD_EMPRESA = $cod_empresa LIMIT 1 )
								END"; 
		}

		$sql = "SELECT MAX(COD_REGISTRO) AS CODIGO FROM DADOS_PESQUISA";
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);		
		$qrBusca = mysqli_fetch_assoc($arrayQuery);
		$codigo = $qrBusca['CODIGO'];
		
		if(trim($senha) == ""){

			$sqlCli = "SELECT NOM_CLIENTE, COD_CLIENTE FROM CLIENTES 
					WHERE NUM_CGCECPF = '$cpf' AND NUM_CGCECPF != '' AND COD_EMPRESA = $cod_empresa
					OR DES_EMAILUS = '$email' AND DES_EMAILUS != '' AND COD_EMPRESA = $cod_empresa
					OR NUM_CELULAR = '$celular' AND NUM_CELULAR != '' AND COD_EMPRESA = $cod_empresa";

			$arrayQueryCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);
			
			if(mysqli_num_rows($arrayQueryCli) == 1){
				$qrCli = mysqli_fetch_assoc($arrayQueryCli);

				$cod_cliente = $qrCli['COD_CLIENTE'];
				$nom_cliente = $qrCli['NOM_CLIENTE'];
				$ja_fez = 'N';

				$sqlPesq = "SELECT LOG_PERMITE FROM PESQUISA WHERE COD_PESQUISA = $cod_pesquisa";
				$qrPesq = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlPesq));


				if($qrPesq['LOG_PERMITE'] == 'N' && $cod_cliente != 0){

					$sql = "SELECT DISTINCT COD_CLIENTE FROM DADOS_PESQUISA WHERE COD_CLIENTE = $cod_cliente AND COD_PESQUISA = $cod_pesquisa AND COD_EMPRESA = $cod_empresa";
					$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sql);
					if(mysqli_num_rows($arrayCli) != 0){
						$ja_fez = 'S';
					}

				}


				if($ja_fez == 'N'){
				
					$sql = "UPDATE DADOS_PESQUISA SET COD_CLIENTE = $cod_cliente, COD_UNIVEND =  
								$unidade_resposta 
							WHERE COD_REGISTRO = $cod_registro";
					mysqli_query(connTemp($cod_empresa,''),$sql);

					$sql = "SELECT DES_PESQUISA FROM PESQUISA WHERE COD_PESQUISA = $cod_pesquisa";
					//fnEscreve($sql);
					$qrPesq = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

					$sql = "SELECT * FROM MODELOPESQUISA WHERE COD_TEMPLATE = $cod_pesquisa AND NUM_ORDENAC = 1 AND DAT_EXCLUSA IS NULL LIMIT 1";
					$qrBusca = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

					
					$qrCli = mysqli_fetch_assoc($arrayCli);

					// echo($sql);
					// exit();

					$qrBusca += ['NOM_CLIENTE' => $nom_cliente];
					$qrBusca += ['COD_CLIENTE' => $cod_cliente];
					$qrBusca += ['DES_PESQUISA' => $qrPesq['DES_PESQUISA']];
					addBloco($qrBusca, $codigo, "btnContinuar");

				}else{
					$nome = explode(' ', $nom_cliente);
					$nome = ucfirst(strtolower($nome[0]));
					echo "<div class='push20'></div> <center><b>".$nome."</b>, você já respondeu à essa pesquisa. Obrigado!</center>";
				}
				
			}else{
				echo 0;
				// echo("01 - ");
				// echo(mysqli_num_rows($arrayQuery));
			}
		}else{
			$sql = "SELECT DES_SENHAUS, COD_CLIENTE FROM CLIENTES WHERE NUM_CGCECPF = $cpf AND COD_EMPRESA = $cod_empresa";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			
			if(mysqli_num_rows($arrayQuery) == 1){
				$qrBusca = mysqli_fetch_assoc($arrayQuery);
				$senhaBanco = fnDecode($qrBusca['DES_SENHAUS']);
				$cod_cliente = $qrBusca['COD_CLIENTE'];
				
				if($senhaBanco == $senha){
					$sql = "UPDATE DADOS_PESQUISA SET COD_CLIENTE = $cod_cliente WHERE COD_REGISTRO = $cod_registro";
					mysqli_query(connTemp($cod_empresa,''),$sql);
				
					echo $cod_cliente;
				}else{
					echo 0;
					// echo("02");
				}				
			}else{
				echo 0;
				// echo("03");
			}			
		}
		
	break;	
	
	case 'listarPesquisas':	
		$sql = "SELECT PS.*, 
					   CP.DAT_FIM AS FIM_CAMPANHA,
					   CP.HOR_FIM
		FROM PESQUISA PS
		LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = PS.COD_CAMPANHA 
		WHERE PS.COD_EMPRESA = $cod_empresa 
		AND PS.LOG_ATIVO = 'S' 
		AND PS.DAT_FIM > CURDATE() 
		ORDER BY PS.DES_PESQUISA";

		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
		$qrBusca = mysqli_fetch_assoc($arrayQuery);
		$cod_pesquisa = $qrBusca['COD_PESQUISA'];		
		$cod_players = fnLimpaCampoZero($_GET['COD_PLAYERS']);		
		if($_GET['cod_cliente'] != 'preview'){
			$cod_cliente = fnLimpacampoZero($_GET['cod_cliente']);
		}else{
			$cod_cliente = $_GET['cod_cliente'];
		}
		$fim_campanha = fnDataShort($qrBusca['FIM_CAMPANHA'])." ".$qrBusca['HOR_FIM'];
		// fnEscreve($fim_campanha);
		
		if(mysqli_num_rows($arrayQuery) == 1){

			$sqlEmp = "SELECT DES_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
			$arrayDom = mysqli_query(connTemp($cod_empresa,''),$sqlEmp);
			$qrDom = mysqli_fetch_assoc($arrayDom);

			if($cod_players != 0){

				header("Location:https://".$qrDom['DES_DOMINIO'].".fidelidade.mk/pesquisa.do?idP=".fnEncode($qrBusca['COD_PESQUISA'])."&idc=".fnEncode($cod_cliente)."&cod_players=".$cod_players."&hs=".$log_hotsite);

			}else{

				header("Location:https://".$qrDom['DES_DOMINIO'].".fidelidade.mk/pesquisa.do?idP=".fnEncode($qrBusca['COD_PESQUISA'])."&idc=".fnEncode($cod_cliente)."&hs=".$log_hotsite);

			}


		}else{

			$sql2 = "SELECT * FROM PESQUISA WHERE COD_EMPRESA = $cod_empresa 
					AND LOG_ATIVO = 'S' 
					AND LOG_PRINCIPAL = 'S' 
					AND DAT_FIM > CURDATE() order by DES_PESQUISA";
			//fnEscreve($sql);
			$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);

			if(mysqli_num_rows($arrayQuery2) == 1){

				$sqlEmp = "SELECT DES_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
				$arrayDom = mysqli_query(connTemp($cod_empresa,''),$sqlEmp);
				$qrDom = mysqli_fetch_assoc($arrayDom);

				$qrBusca2 = mysqli_fetch_assoc($arrayQuery2);

				if($cod_players != 0){

					header("Location:https://".$qrDom['DES_DOMINIO'].".fidelidade.mk/pesquisa.do?idP=".fnEncode($qrBusca2['COD_PESQUISA'])."&idc=".fnEncode($cod_cliente)."&cod_players=".$cod_players);

				}else{

					header("Location:https://".$qrDom['DES_DOMINIO'].".fidelidade.mk/pesquisa.do?idP=".fnEncode($qrBusca2['COD_PESQUISA'])."&idc=".fnEncode($cod_cliente));

				}

			}else{

					$sql3 = "SELECT * FROM PESQUISA WHERE COD_EMPRESA = $cod_empresa 
							AND LOG_ATIVO = 'S' 
							AND DAT_FIM > CURDATE() order by DES_PESQUISA";
					// echo($sql3);
					$arrayQuery3 = mysqli_query(connTemp($cod_empresa,''),$sql3);

					$qtd_pesquisa = mysqli_num_rows($arrayQuery3);

					if($qtd_pesquisa > 0){

				?>	
						<div class="push20"></div>				
						<p class="lead">
							Escolha a pesquisa abaixo para iniciar
						</p>
						<div class="push20"></div>
						
				<?php

					while ($qrBusca3 = mysqli_fetch_assoc($arrayQuery3)) {
						?>			
							<p class="lead">
								<a href="#" style="font-size: 16px;" class="iniciarPesquisa" cod-pesquisa="<?php echo $qrBusca3['COD_PESQUISA']; ?>"><b><i class="fa fa-chevron-right" aria-hidden="true" style="font-size: 12px;">&nbsp; </i><?php echo $qrBusca3['DES_PESQUISA']; ?></b></a>
							</p>
							
						<?php
					}

				}else{

				?>

					<div class="push20"></div>				
						<div class="col-md-12 text-center">
							<p class="lead">
								Não há pesquisas de avaliação ativas no momento.
							</p>
						</div>
					<div class="push20"></div>

				<?php

					$sql = "SELECT T.COD_PLAYERS,
								   T.COD_EMPRESA,
								   T.COD_UNIVEND,
								   U.NOM_FANTASI,
								   T.COD_USUARIO,
								   S.NOM_USUARIO, 
								   T.VAL_INATIVO, 
								   T.LOG_TICKET, 
								   T.DES_PAGHOME, 
								   T.LOG_NPS 
							FROM TOTEM_PLAYERS T 
							LEFT JOIN WEBTOOLS.UNIDADEVENDA U ON U.COD_UNIVEND=T.COD_UNIVEND
							LEFT JOIN WEBTOOLS.USUARIOS S ON S.COD_USUARIO=T.COD_USUARIO
							WHERE T.COD_EMPRESA = $cod_empresa
							AND T.COD_PLAYERS = $cod_players
							AND U.LOG_ESTATUS != 'N'";

					// echo($sql);
					// exit();

					$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);

					$qrLista = mysqli_fetch_assoc($arrayQuery);

					$log_ticket = $qrLista['LOG_TICKET'];

					if($log_ticket == 'S'){

						include_once '../totem/funWS/TKT.php';

						$cod_cliente = fnDecode($_GET['cod_cliente']);

						// echo "_".$cod_cliente;
						// exit();
						
						$idlojaKey = $qrLista['COD_UNIVEND'];
						$idmaquinaKey = 0;
						$codvendedorKey = 0;
						$nomevendedorKey = 0;

						$urltotem = fnEncode(
									$log_usuario.';'
									.$des_senhaus.';'
									.$idlojaKey.';'
									.$idmaquinaKey.';'
									.$cod_empresa.';'
									.$codvendedorKey.';'
									.$nomevendedorKey.';'
									.$cod_players
						);

						$arrayCampos = fnDecode($urltotem);

						$arrayCampos = explode(";", $arrayCampos);

						$sqlCli = "SELECT NUM_CGCECPF FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";

						$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

						$qrCli = mysqli_fetch_assoc($arrayCli);

						//dados atualiza cadastro
						$dadosatualiza=Array('cpf'=>$qrCli['NUM_CGCECPF']);

						// echo "<pre>";
						// print_r($dadosatualiza);
						// echo "</pre>";

						// echo "<pre>";
						// print_r($arrayCampos);
						// echo "</pre>";

						$urlTKT = geratkt($dadosatualiza,$arrayCampos);

						// print_r($urlTKT);

						if($urlTKT[url][coderro][0] == 16){
							?>
								<div class="row">
									<div class="col-md-12 text-center">
										<h4>Ticket de ofertas indisponível. Configuração de fases necessária.</h4>
									</div>
								</div>
							<?php
						}else{

						?>
							<div class="row">
								<div class="col-md-12 text-center">
									<a href="javascript:void(0)" onclick="window.top.location.href = 'http://totem.bunker.mk/ticket_V2.do?key=<?php echo $urltotem; ?>&url=<?php echo $urlTKT['url']; ?>&ch=3'" class="btn btn-block btn-primary">Ver ofertas</a>
								</div>
							</div>
						<?php

						}
						
					}

				}
			}			
		}
		
	break;	
	
	case 'iniciarPesquisa':	
		$plataforma = fnLimpacampoZero($_GET['plataforma']);
		$totem = fnLimpacampo($_GET['log_totem']);
		$cod_players = fnLimpaCampoZero($_GET['COD_PLAYERS']);
		if(fnDecode($_GET['cod_cliente']) != "preview"){
			$cod_cliente = fnLimpaCampoZero(fnDecode($_GET['cod_cliente']));
		}else{
			$cod_cliente = '000999000';
		}

		$sql = "SELECT PS.LOG_ATIVO,
					   PS.LOG_PERMITE, 
					   PS.DES_PESQUISA, 
					   PS.DAT_FIM,
					   CP.DAT_FIM AS FIM_CAMPANHA,
					   CP.HOR_FIM
				FROM PESQUISA PS
				LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = PS.COD_CAMPANHA
				WHERE PS.COD_PESQUISA = $cod_pesquisa";
		// echo($sql);
		$qrPesq = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
		$fim_campanha = $qrPesq['FIM_CAMPANHA']." ".$qrPesq['HOR_FIM'];
		$fim_pesquisa = $qrPesq['DAT_FIM'];
		$nom_cliente = "";
		// fnEscreve($cod_cliente);
		// fnEscreve($qrPesq['LOG_PERMITE']);

		if($qrPesq['LOG_PERMITE'] == 'N' && $cod_cliente != 0 && $cod_cliente != "000999000"){

			$sql = "SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE = (SELECT DISTINCT COD_CLIENTE FROM DADOS_PESQUISA WHERE COD_CLIENTE = $cod_cliente AND COD_PESQUISA = $cod_pesquisa AND COD_EMPRESA = $cod_empresa) AND COD_EMPRESA = $cod_empresa";
			$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sql);
			if(mysqli_num_rows($arrayCli) != 0){
				$qrCli = mysqli_fetch_assoc($arrayCli);
				$nom_cliente = $qrCli['NOM_CLIENTE'];
			}

		}else if($cod_cliente == '000999000'){
			$nom_cliente = "preview";
		}else{
			$nom_cliente = "";
		}

		if($qrPesq['LOG_ATIVO'] == 'S' && $fim_campanha >= date("Y-m-d H:i:s") && $fim_pesquisa >= date("Y-m-d") && $nom_cliente == ""){		
			
			if($cod_players != 0){
				$unidade_resposta = "(SELECT COD_UNIVEND FROM TOTEM_PLAYERS
										WHERE COD_PLAYERS = $cod_players 
										AND COD_EMPRESA = $cod_empresa)";
			}else{
				$unidade_resposta = "CASE 
										WHEN (SELECT COD_UNIVEND FROM VENDAS WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa ORDER BY COD_VENDA DESC LIMIT 1) IS NOT NULL  
											THEN (SELECT COD_UNIVEND FROM VENDAS WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa ORDER BY COD_VENDA DESC LIMIT 1)
										WHEN (SELECT COD_UNIVEND FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa) IS NOT NULL  
											THEN (SELECT COD_UNIVEND FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa)
										WHEN (SELECT COD_UNIVEND FROM unidadevenda WHERE LOG_ESTATUS = 'S' AND LOG_UNIPREF = 'S' AND COD_EMPRESA = $cod_empresa LIMIT 1 ) > 0 
											THEN  (SELECT COD_UNIVEND FROM unidadevenda WHERE LOG_ESTATUS = 'S' AND LOG_ESTATUS = 'S' AND LOG_UNIPREF = 'S' AND COD_EMPRESA = $cod_empresa LIMIT 1 )
										ELSE (SELECT COD_UNIVEND FROM unidadevenda WHERE LOG_ESTATUS = 'S' and COD_EMPRESA = $cod_empresa LIMIT 1 )
									END"; 
			}
		
			$sql = "INSERT INTO DADOS_PESQUISA (COD_PESQUISA, COD_EMPRESA, COD_CLIENTE, COD_UNIVEND, COD_NPSPLATAFO, DT_HORAINICIAL) VALUES ($cod_pesquisa, $cod_empresa, $cod_cliente, $unidade_resposta, $plataforma, now());";
			// echo $sql;
			mysqli_query(connTemp($cod_empresa,''),$sql);	

			$sql = "SELECT MAX(COD_REGISTRO) AS CODIGO FROM DADOS_PESQUISA";
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);		
			$qrBusca = mysqli_fetch_assoc($arrayQuery);
			$codigo = $qrBusca['CODIGO'];
			
			$sql = "SELECT * FROM MODELOPESQUISA WHERE COD_TEMPLATE = $cod_pesquisa AND NUM_ORDENAC = 1 AND DAT_EXCLUSA IS NULL LIMIT 1";
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);	
			
			while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
				if($qrBusca['COD_BLPESQU'] == 8){
					$sqlCli = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa";
					$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);
					$qtd_cli = mysqli_num_rows($arrayCli);
					if($qtd_cli == 0){
						$qrBusca['COD_BLPESQU'] = 6;

						if($totem == 'S'){
							$sql = "SELECT * FROM  USUARIOS
									WHERE LOG_ESTATUS='S' AND
										  COD_EMPRESA = $cod_empresa AND
										  COD_TPUSUARIO=10  limit 1  ";
							// echo($sql);
						    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
							$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
											
							if (isset($arrayQuery)) {
								$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
								$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
							}

							$sql = "SELECT T.COD_PLAYERS,
										   T.COD_EMPRESA,
										   T.COD_UNIVEND,
										   U.NOM_FANTASI,
										   T.COD_USUARIO, 
										   T.VAL_INATIVO, 
										   T.LOG_TICKET, 
										   T.DES_PAGHOME, 
										   T.LOG_NPS 
									FROM TOTEM_PLAYERS T 
									LEFT JOIN UNIDADEVENDA U ON U.COD_UNIVEND=T.COD_UNIVEND
									WHERE T.COD_EMPRESA = $cod_empresa
									AND T.COD_PLAYERS = $cod_players
									AND U.LOG_ESTATUS != 'N'";

							// echo($sql);
							// exit();

							$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);

							$qrLista = mysqli_fetch_assoc($arrayQuery);

							$idlojaKey = $qrLista['COD_UNIVEND'];
							$idmaquinaKey = 0;
							$codvendedorKey = 0;
							$nomevendedorKey = 0;

							$urltotem = fnEncode(
										$log_usuario.';'
										.$des_senhaus.';'
										.$idlojaKey.';'
										.$idmaquinaKey.';'
										.$cod_empresa.';'
										.$codvendedorKey.';'
										.$nomevendedorKey.';'
										.$cod_players.';'
										.$cod_pesquisa
							);

							// echo fnDecode($urltotem);
							// exit();
						}

						$qrBusca += ['LOG_TOTEM' => $totem];
						$qrBusca += ['URL_TOTEM' => $urltotem];
						$qrBusca += ['COD_PLAYERS' => $cod_players];
						addBloco($qrBusca, $codigo, "btnContinuar");
					}else{
						$qrCli = mysqli_fetch_assoc($arrayCli);
						$qrBusca += ['NOM_CLIENTE' => $qrCli['NOM_CLIENTE']];
						$qrBusca += ['COD_CLIENTE' => $qrCli['COD_CLIENTE']];
						$qrBusca += ['DES_PESQUISA' => $qrPesq['DES_PESQUISA']];
						$qrBusca += ['COD_PLAYERS' => $cod_players];
						addBloco($qrBusca, $codigo, "btnContinuar");
					}
				}else{
					addBloco($qrBusca, $codigo, "btnContinuar");
				}
			}

		}else if($cod_cliente == "000999000"){
			$sql = "SELECT MAX(COD_REGISTRO) AS CODIGO FROM DADOS_PESQUISA";
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);		
			$qrBusca = mysqli_fetch_assoc($arrayQuery);
			$codigo = $qrBusca['CODIGO'];
			
			$sql = "SELECT * FROM MODELOPESQUISA WHERE COD_TEMPLATE = $cod_pesquisa AND NUM_ORDENAC = 1 AND DAT_EXCLUSA IS NULL LIMIT 1";
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);	
			
			while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
				$qrBusca += ['NOM_CLIENTE' => "preview"];
				$qrBusca += ['DES_PESQUISA' => $qrPesq['DES_PESQUISA']];
				addBloco($qrBusca, $codigo, "btnContinuar");
			}
		}else if($qrPesq['LOG_ATIVO'] == 'S' && $fim_campanha < date("Y-m-d H:i:s") && $fim_pesquisa >= date("Y-m-d")){
			echo "<div class='push20'></div> <center>A campanha dessa pesquisa expirou.</center>";
		}else if($qrPesq['LOG_ATIVO'] == 'S' && $fim_campanha >= date("Y-m-d H:i:s") && $fim_pesquisa < date("Y-m-d")){
			echo "<div class='push20'></div> <center>Essa pesquisa expirou.</center>";
		}else if($qrPesq['LOG_ATIVO'] == 'S' && $fim_campanha >= date("Y-m-d H:i:s") && $fim_pesquisa >= date("Y-m-d") && $nom_cliente != ""){
			$nome = explode(' ', $nom_cliente);
			$nome = ucfirst(strtolower($nome[0]));
			echo "<div class='push20'></div> <center><b>".$nome."</b>, você já respondeu à essa pesquisa. Obrigado!</center>";
		}else{
			echo "<div class='push20'></div> <center>Esta pesquisa não está mais disponível.</center>";
		}
		
	break;

	case 'iniciarPesquisaVisualizacao':	
		
		$sql = "SELECT MAX(COD_REGISTRO) AS CODIGO FROM DADOS_PESQUISA";
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);		
		$qrBusca = mysqli_fetch_assoc($arrayQuery);
		$codigo = $qrBusca['CODIGO'];
		
		$sql = "SELECT * FROM MODELOPESQUISA WHERE COD_TEMPLATE = $cod_pesquisa AND NUM_ORDENAC = 1 AND DAT_EXCLUSA IS NULL LIMIT 1";
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);	
		
		while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
			addBloco($qrBusca, $codigo, "btnContinuar");
		}
		
	break;
	
	case 'proximoBlocoPesquisa':			
		$cod_registro = fnLimpacampoZero($_GET['cod_registro']);
		$cod_ordenacao = fnLimpacampoZero($_GET['cod_ordenacao']);

		$sqlOrdenac = "SELECT NUM_REDIRECT FROM CONDICAO_PESQUISA WHERE COD_EMPRESA = $cod_empresa";

		$arrayOrdem = mysqli_query(connTemp($cod_empresa,''),$sqlOrdenac);
		$blocosExclusa = "";

		while ($qrOrdem = mysqli_fetch_assoc($arrayOrdem)){
			if($qrOrdem[NUM_REDIRECT] != "" && $qrOrdem[NUM_REDIRECT] != 0){
				$blocosExclusa .= $qrOrdem[NUM_REDIRECT].",";
			}
		}

		if($blocosExclusa != ""){
			$blocosExclusa = ltrim(rtrim($blocosExclusa,','),',');
			$andBlocosNot = "AND COD_REGISTR NOT IN($blocosExclusa)";
		}else{
			$andBlocosNot = "";
		}
		
		$sql = "SELECT * FROM MODELOPESQUISA WHERE COD_TEMPLATE = $cod_pesquisa AND NUM_ORDENAC > $cod_ordenacao AND DAT_EXCLUSA IS NULL $andBlocosNot ORDER BY NUM_ORDENAC ASC LIMIT 1";
		// echo($sql);
		// exit();
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);	
		
		if(mysqli_num_rows($arrayQuery) == 0){			
			$sql = "UPDATE DADOS_PESQUISA SET DT_HORAFINAL = NOW() WHERE COD_REGISTRO = $cod_registro";
			mysqli_query(connTemp($cod_empresa,''),$sql);		

			$sql = "SELECT (TIME_TO_SEC(dt_horafinal) - TIME_TO_SEC(dt_horainicial)) AS DIF from dados_pesquisa where COD_REGISTRO = $cod_registro";
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			$qrBusca = mysqli_fetch_assoc($arrayQuery);
			$dif = $qrBusca['DIF'];	

			$sql = "UPDATE DADOS_PESQUISA SET DIFERENCA = $dif WHERE COD_REGISTRO = $cod_registro";
			mysqli_query(connTemp($cod_empresa,''),$sql);
			
			$qrBusca['COD_BLPESQU'] = 0;
			$qrBusca['COD_PESQUISA'] = $cod_pesquisa;
			$qrBusca['LOG_HOTSITE'] = $log_hotsite;
			$qrBusca['COD_EMPRESA'] = $cod_empresa;
			$qrBusca['COD_PLAYERS'] = $cod_players;
			$qrBusca['COD_CLIENTE'] = $cod_cliente_totem;
			if($cod_players != 0){
				$qrBusca['LOG_USUARIO'] = $log_usuario;
				$qrBusca['DES_SENHAUS'] = $des_senhaus;

			}
			addBloco($qrBusca, $cod_registro, "btnContinuar");
		}else{
			while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
				$qrBusca['COD_PESQUISA'] = $cod_pesquisa;
				$qrBusca['LOG_HOTSITE'] = $log_hotsite;
				$qrBusca['COD_EMPRESA'] = $cod_empresa;
				$qrBusca['COD_PLAYERS'] = $cod_players;
				$qrBusca['COD_CLIENTE'] = $cod_cliente_totem;
				if($cod_players != 0){
					$qrBusca['LOG_USUARIO'] = $log_usuario;
					$qrBusca['DES_SENHAUS'] = $des_senhaus;

				}
				addBloco($qrBusca, $cod_registro, "btnContinuar");
			}			
		}
	
	break;

	case 'proximoBlocoAvaliacao':			
		$cod_modpesquisa = fnLimpacampoZero($_GET['cod_modpesquisa']);
		$cod_condicao = fnLimpacampoZero($_GET['codCondicao']);
		$cod_ordenacao = fnLimpacampoZero($_GET['cod_ordenacao']);
		$cod_registro = fnLimpacampoZero($_GET['cod_registro']);
		$bloco_ir = fnLimpacampo($_GET['bloco_ir']);
		$blocosCodigo = $_GET['blocosCodigo'];
		$resposta_numero = $_GET['resposta_numero'];

		// echo($cod_registro);
		// echo($cod_modpesquisa);
		// exit();
		
		if($bloco_ir != 0){

			$bloco_ir = explode(',', $bloco_ir);

			if(count($bloco_ir) > 1){
				$opcaoIr = 'btnContinuarCondicao';
			}

			// echo($opcaoIr);
			// exit();

			$sql = "
					SELECT 
						(SELECT 
								MAX(NUM_ORDENAC)
							FROM
								MODELOPESQUISA
							WHERE
								COD_REGISTR IN ($blocosCodigo)) AS NUM_ORDENAC,
								MODELOPESQUISA.COD_TEMPLATE,
								MODELOPESQUISA.COD_BLPESQU,
								MODELOPESQUISA.COD_REGISTR,
								MODELOPESQUISA.DES_PERGUNTA,
								MODELOPESQUISA.TIP_BLOCO,
								MODELOPESQUISA.NUM_QUANTID,
								MODELOPESQUISA.LOG_CONDICOES,
								MODELOPESQUISA.DES_TIPO_RESPOSTA,
								MODELOPESQUISA.NUM_OPCOES,
								MODELOPESQUISA.DES_OPCOES,
								MODELOPESQUISA.DES_IMAGEM,
								MODELOPESQUISA.COD_ROTULO,
						'".$cod_condicao."' AS COD_AVALIACAO
					FROM
						MODELOPESQUISA
					WHERE
						COD_REGISTR = $bloco_ir[0]";		
			
			// echo($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);	
			
			while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
				$qrBusca['COD_PESQUISA'] = $cod_pesquisa;
				$qrBusca['LOG_HOTSITE'] = $log_hotsite;
				$qrBusca['COD_EMPRESA'] = $cod_empresa;
				$qrBusca['COD_PLAYERS'] = $cod_players;
				$qrBusca['COD_CLIENTE'] = $cod_cliente_totem;
				if($cod_players != 0){
					$qrBusca['LOG_USUARIO'] = $log_usuario;
					$qrBusca['DES_SENHAUS'] = $des_senhaus;

				}
				addBloco($qrBusca, $cod_registro, $opcaoIr);
			}			
		}else{
			$sql = "SELECT MAX(NUM_ORDENAC) AS NUM_ORDENAC FROM MODELOPESQUISA WHERE COD_REGISTR IN ($blocosCodigo)";
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);	
			$qrBusca = mysqli_fetch_assoc($arrayQuery);
			$num_ordenac = $qrBusca['NUM_ORDENAC'] + 1;
			
			$sql = "SELECT * FROM MODELOPESQUISA WHERE COD_TEMPLATE = $cod_pesquisa AND NUM_ORDENAC > $cod_ordenacao AND DAT_EXCLUSA IS NULL $andBlocosNot ORDER BY NUM_ORDENAC ASC LIMIT 1";
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);	
			
			if(mysqli_num_rows($arrayQuery) == 0){			
				$sql = "UPDATE DADOS_PESQUISA SET DT_HORAFINAL = NOW() WHERE COD_REGISTRO = $cod_registro";
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);			
				
				$qrBusca['COD_BLPESQU'] = 0;
				$qrBusca['COD_PESQUISA'] = $cod_pesquisa;
				$qrBusca['LOG_HOTSITE'] = $log_hotsite;
				$qrBusca['COD_EMPRESA'] = $cod_empresa;
				$qrBusca['COD_PLAYERS'] = $cod_players;
				$qrBusca['COD_CLIENTE'] = $cod_cliente_totem;
				if($cod_players != 0){
					$qrBusca['LOG_USUARIO'] = $log_usuario;
					$qrBusca['DES_SENHAUS'] = $des_senhaus;

				}
				addBloco($qrBusca, $cod_registro, $opcaoIr);
			}else{
				while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
					$qrBusca['COD_PESQUISA'] = $cod_pesquisa;
					$qrBusca['LOG_HOTSITE'] = $log_hotsite;
					$qrBusca['COD_EMPRESA'] = $cod_empresa;
					$qrBusca['COD_PLAYERS'] = $cod_players;
					$qrBusca['COD_CLIENTE'] = $cod_cliente_totem;
					if($cod_players != 0){
						$qrBusca['LOG_USUARIO'] = $log_usuario;
						$qrBusca['DES_SENHAUS'] = $des_senhaus;

					}
					addBloco($qrBusca, $cod_registro, $opcaoIr);
				}			
			}			
		}
	break;

	case 'proximoBlocoCondicao':			
		$cod_registro = fnLimpacampoZero($_GET['cod_registro']);
		$cod_ordenacao = fnLimpacampoZero($_GET['cod_ordenacao']);
		$cod_condicao = fnLimpacampoZero($_GET['cod_avaliacao']);
		$cod_modpesquisa = fnLimpacampoZero($_GET['cod_modpesquisa']);

		$sqlOrdenac = "SELECT NUM_REDIRECT FROM CONDICAO_PESQUISA WHERE COD_EMPRESA = $cod_empresa AND COD_CONDICAO = $cod_condicao";

		// echo $sqlOrdenac;

		$qrBlocos = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlOrdenac));

		$blocosCondicao = explode(',', $qrBlocos['NUM_REDIRECT']);

		$bloco_ir = 0;

		$index = array_search($cod_modpesquisa, $blocosCondicao);
		if($index !== false && $index < count($blocosCondicao)-1) $bloco_ir = $blocosCondicao[$index+1];

		if($bloco_ir != 0){

			$opcaoIr = 'btnContinuarCondicao';

			$sql = "
					SELECT 
						MODELOPESQUISA.NUM_ORDENAC,
						MODELOPESQUISA.COD_TEMPLATE,
						MODELOPESQUISA.COD_BLPESQU,
						MODELOPESQUISA.COD_REGISTR,
						MODELOPESQUISA.DES_PERGUNTA,
						MODELOPESQUISA.TIP_BLOCO,
						MODELOPESQUISA.NUM_QUANTID,
						MODELOPESQUISA.LOG_CONDICOES,
						MODELOPESQUISA.DES_TIPO_RESPOSTA,
						MODELOPESQUISA.NUM_OPCOES,
						MODELOPESQUISA.DES_OPCOES,
						MODELOPESQUISA.DES_IMAGEM,
						MODELOPESQUISA.COD_ROTULO,
						'".$cod_condicao."' AS COD_AVALIACAO
					FROM
						MODELOPESQUISA
					WHERE
						COD_REGISTR = $bloco_ir";		
			
			// echo($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);	
			
			while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
				$qrBusca['COD_PESQUISA'] = $cod_pesquisa;
				$qrBusca['LOG_HOTSITE'] = $log_hotsite;
				$qrBusca['COD_EMPRESA'] = $cod_empresa;
				$qrBusca['COD_PLAYERS'] = $cod_players;
				$qrBusca['COD_CLIENTE'] = $cod_cliente_totem;
				if($cod_players != 0){
					$qrBusca['LOG_USUARIO'] = $log_usuario;
					$qrBusca['DES_SENHAUS'] = $des_senhaus;

				}
				addBloco($qrBusca, $cod_registro, $opcaoIr);
			}

		}else{

			$opcaoIr = 'btnContinuar';

			$cod_ordenacao = fnLimpacampoZero($_GET['cod_ordenacao']);

			$sqlOrdenac = "SELECT NUM_REDIRECT FROM CONDICAO_PESQUISA WHERE COD_EMPRESA = $cod_empresa";

			$arrayOrdem = mysqli_query(connTemp($cod_empresa,''),$sqlOrdenac);
			$blocosExclusa = "";

			while ($qrOrdem = mysqli_fetch_assoc($arrayOrdem)){
				$blocosExclusa .= $qrOrdem[NUM_REDIRECT].",";
			}

			if($blocosExclusa != ""){
				$blocosExclusa = explode(',', $blocosExclusa);
				$blocosExclusa = array_filter($blocosExclusa, function($value) { return !is_null($value) && $value !== ''; });
				$blocosExclusa = implode(',', $blocosExclusa);
				// $blocosExclusa = ltrim(rtrim($blocosExclusa,','),',');
				$andBlocosNot = "AND COD_REGISTR NOT IN($blocosExclusa)";
			}else{
				$andBlocosNot = "";
			}
			
			$sql = "SELECT * FROM MODELOPESQUISA WHERE COD_TEMPLATE = $cod_pesquisa AND NUM_ORDENAC > $cod_ordenacao AND DAT_EXCLUSA IS NULL $andBlocosNot ORDER BY NUM_ORDENAC ASC LIMIT 1";
			// echo($sql);
			// exit();
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);	
			
			if(mysqli_num_rows($arrayQuery) == 0){			
				$sql = "UPDATE DADOS_PESQUISA SET DT_HORAFINAL = NOW() WHERE COD_REGISTRO = $cod_registro";
				mysqli_query(connTemp($cod_empresa,''),$sql);		

				$sql = "SELECT (TIME_TO_SEC(dt_horafinal) - TIME_TO_SEC(dt_horainicial)) AS DIF from dados_pesquisa where COD_REGISTRO = $cod_registro";
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
				$qrBusca = mysqli_fetch_assoc($arrayQuery);
				$dif = $qrBusca['DIF'];	

				$sql = "UPDATE DADOS_PESQUISA SET DIFERENCA = $dif WHERE COD_REGISTRO = $cod_registro";
				mysqli_query(connTemp($cod_empresa,''),$sql);
				
				$qrBusca['COD_BLPESQU'] = 0;
				$qrBusca['COD_PESQUISA'] = $cod_pesquisa;
				$qrBusca['LOG_HOTSITE'] = $log_hotsite;
				$qrBusca['COD_EMPRESA'] = $cod_empresa;
				$qrBusca['COD_PLAYERS'] = $cod_players;
				$qrBusca['COD_CLIENTE'] = $cod_cliente_totem;
				if($cod_players != 0){
					$qrBusca['LOG_USUARIO'] = $log_usuario;
					$qrBusca['DES_SENHAUS'] = $des_senhaus;

				}
				addBloco($qrBusca, $cod_registro, "btnContinuar");
			}else{
				while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
					$qrBusca['COD_PESQUISA'] = $cod_pesquisa;
					$qrBusca['LOG_HOTSITE'] = $log_hotsite;
					$qrBusca['COD_EMPRESA'] = $cod_empresa;
					$qrBusca['COD_PLAYERS'] = $cod_players;
					$qrBusca['COD_CLIENTE'] = $cod_cliente_totem;
					if($cod_players != 0){
						$qrBusca['LOG_USUARIO'] = $log_usuario;
						$qrBusca['DES_SENHAUS'] = $des_senhaus;

					}
					addBloco($qrBusca, $cod_registro, "btnContinuar");
				}			
			}

		}
	
	break;	
	
	case 'proximoBlocoPesquisaVisualizacao':			
		$cod_registro = fnLimpacampoZero($_GET['cod_registro']);

		$cod_ordenacao = fnLimpacampoZero($_GET['cod_ordenacao']) + 1;
		
		$sql = "SELECT * FROM MODELOPESQUISA WHERE COD_TEMPLATE = $cod_pesquisa AND NUM_ORDENAC = $cod_ordenacao AND DAT_EXCLUSA IS NULL LIMIT 1";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);	
		
		if(mysqli_num_rows($arrayQuery) == 0){			
			$qrBusca['COD_BLPESQU'] = 0;
			$qrBusca['COD_PESQUISA'] = $cod_pesquisa;
			$qrBusca['LOG_HOTSITE'] = $log_hotsite;
			addBloco($qrBusca, $cod_registro, "btnContinuar");
		}else{
			while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
				$qrBusca['COD_PESQUISA'] = $cod_pesquisa;
				$qrBusca['LOG_HOTSITE'] = $log_hotsite;
				addBloco($qrBusca, $cod_registro, "btnContinuar");
			}			
		}
	
	break;		
}

function addBloco($objeto, $codigo, $opcaoIr) {
	switch ($objeto['COD_BLPESQU']) {	
		case 0:// AGRADECIMENTO PESQUISA

		if($objeto['COD_PLAYERS'] != 0){

			$cod_empresa = $objeto['COD_EMPRESA'];
			$cod_players = $objeto['COD_PLAYERS'];
			$cod_cliente = $objeto['COD_CLIENTE'];
			$log_usuario = $objeto['LOG_USUARIO'];
			$des_senhaus = $objeto['DES_SENHAUS'];
			$log_hotsite = $objeto['LOG_HOTSITE'];

			// echo($cod_cliente);
			// exit();

			$sql = "SELECT T.COD_PLAYERS,
						   T.COD_EMPRESA,
						   T.COD_UNIVEND,
						   U.NOM_FANTASI,
						   T.COD_USUARIO,
						   S.NOM_USUARIO, 
						   T.VAL_INATIVO, 
						   T.LOG_TICKET, 
						   T.DES_PAGHOME, 
						   T.LOG_NPS 
					FROM TOTEM_PLAYERS T 
					LEFT JOIN WEBTOOLS.UNIDADEVENDA U ON U.COD_UNIVEND=T.COD_UNIVEND
					LEFT JOIN WEBTOOLS.USUARIOS S ON S.COD_USUARIO=T.COD_USUARIO
					WHERE T.COD_EMPRESA = $cod_empresa
					AND T.COD_PLAYERS = $cod_players
					AND U.LOG_ESTATUS != 'N'";

			// echo($sql);
			// exit();

			$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);

			$qrLista = mysqli_fetch_assoc($arrayQuery);

			$log_ticket = $qrLista['LOG_TICKET'];

			$sqlCli = "SELECT NUM_CGCECPF, LOG_OFERTAS FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";

			$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

			$qrCli = mysqli_fetch_assoc($arrayCli);

			//dados atualiza cadastro
			$dadosatualiza=Array('cpf'=>$qrCli['NUM_CGCECPF']);

			$log_ofertas = $qrCli['LOG_OFERTAS'];

			if($log_ticket == 'S' && $log_ofertas == 'S'){

				include_once '../totem/funWS/TKT.php';

				$idlojaKey = $qrLista['COD_UNIVEND'];
				$idmaquinaKey = 0;
				$codvendedorKey = 0;
				$nomevendedorKey = 0;

				$urltotem = fnEncode(
							$log_usuario.';'
							.$des_senhaus.';'
							.$idlojaKey.';'
							.$idmaquinaKey.';'
							.$cod_empresa.';'
							.$codvendedorKey.';'
							.$nomevendedorKey.';'
							.$cod_players
				);

				$arrayCampos = fnDecode($urltotem);

				$arrayCampos = explode(";", $arrayCampos);

				// echo "<pre>";
				// print_r($dadosatualiza);
				// echo "</pre>";

				// echo "<pre>";
				// print_r($arrayCampos);
				// echo "</pre>";

				$urlTKT = geratkt($dadosatualiza,$arrayCampos);


				// print_r($urlTKT);

				if($urlTKT[url][coderro][0] == 16){
					?>
						<div class="row">
							<div class="col-md-12 text-center">
								<h4>Ticket de ofertas indisponível. Configuração de fases necessária.</h4>
							</div>
						</div>
					<?php
				}else{

					if($log_hotsite == 1){

						// $arraygeratkt=array('cpf'=>fnLimpaDoc($qrCli['NUM_CGCECPF']),
						//                     'cod_empresa'=>rtrim(trim($cod_empresa)),
						//                     'login'=>rtrim(trim($log_usuario)),
						//                     'senha'=>rtrim(trim($des_senhaus)),
						//                     'loja'=>rtrim(trim($idlojaKey))
						//                     );


						// GetURLTktMania ($arraygeratkt);

						$id=fnEncode($cod_empresa.';'.fnLimpaDoc($qrCli['NUM_CGCECPF']).';'.$idlojaKey);

						?>
						<script type="text/javascript">
							let a = document.createElement('a');
							a.target= '_blank';
							a.href= 'http://adm.bunker.mk/ticket/?tkt=<?=$id?>&print=no';
							a.click();
						</script>
						<?php 

					}else{

						$sqlEmpresa = "SELECT DES_DOMINIO, COD_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa ORDER BY 1 DESC LIMIT 1";
						$arrayEmpresa = mysqli_query(connTemp($cod_empresa,''),$sqlEmpresa);

						if(mysqli_num_rows($arrayEmpresa)>0){

							$qrEmpresa = mysqli_fetch_assoc($arrayEmpresa);

							// ?idc=".fnEncode($cod_cliente)."&t=".fnEncode('S')

							if($qrEmpresa[COD_DOMINIO] == 2){
								$extensaoDominio = ".fidelidade.mk";
							}else{
								$extensaoDominio = ".mais.cash";
							}

						?>

							<form  method="POST" id="formCliente" action="https://<?=$qrEmpresa[DES_DOMINIO].$extensaoDominio?>" target="_blank">
								<input type="hidden" name="idc" value="<?=fnEncode($cod_cliente)?>">
								<input type="hidden" name="t" value="<?=fnEncode('S')?>">
							</form>

							<script type="text/javascript">
								window.top.location.href = 'http://totem.bunker.mk/ticket_V2.do?key=<?php echo $urltotem; ?>&url=<?php echo $urlTKT['url']; ?>&ch=3'
								$("#formCliente").submit();
							</script>

						<?php 
						// exit();

						}

				?>
						<!-- <script type="text/javascript"></script> -->
					
				<?php

					}

				}
				
			}
			
		}

			$sqlFinal = "SELECT DES_FINALIZA FROM PESQUISA WHERE COD_EMPRESA = $objeto[COD_EMPRESA] AND COD_PESQUISA = $objeto[COD_PESQUISA]";
			// echo($sqlFinal);
			$qrFinal = mysqli_fetch_assoc(mysqli_query(connTemp($objeto[COD_EMPRESA],''),$sqlFinal));
		?>
			<div class="row">
				<div class="col-md-12">
					<div class="push20"></div>
					<h5 class="lead text-center"><?=$qrFinal['DES_FINALIZA']?></h5>
				</div>						
			</div>
			<script>
				let timeout = setTimeout(timeOutApp, 5000);
		

				function timeOutApp() {
					// window.location.replace("https://<?=$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]?>");
					location.reload();
				}
			</script>				
		<?php
		
		break;	
		case 1:// TEXTO INFORMATIVO
		?>
			<div class="row">
				<div class="col-md-12">
				<div class="push20"></div>
					<h5 class="lead"><?php echo $objeto['DES_PERGUNTA'];?></h5>
				</div>						
			</div>	
			<div class="row">
				<div class="col-md-12">
					<button type="button" class="btn btn-primary btn-hg btn-block <?=$opcaoIr?>" cod-avaliacao="<?=fnLimpaCampoZero($objeto['COD_AVALIACAO'])?>" cod-modpesquisa="<?php echo $objeto['COD_REGISTR'];?>" cod-registro="<?php echo $codigo;?>" cod-blpesqu="<?php echo $objeto['COD_BLPESQU'];?>" cod-ordenacao="<?php echo $objeto['NUM_ORDENAC'];?>" cod-pesquisa="<?php echo $objeto['COD_TEMPLATE']; ?>">Continuar</button>
				</div>
			</div>				
		<?php
		break;     
		case 2:// PERGUNTA
		?>
			<div class="row">
				<div class="col-md-12">
				<div class="push20"></div>
					<h5 class="lead"><?php echo $objeto['DES_PERGUNTA'];?></h5>

					<?php
					if ($objeto['DES_OPCOES'] <> ""){
						$opcoes = json_decode($objeto['DES_OPCOES'],true);
					}else{
						$opcoes = array();
					}
					if ($objeto['DES_TIPO_RESPOSTA'] == "R"){

						echo "<div style='text-align:left'>";
						foreach($opcoes as $k =>  $v){
							echo "<input value='$k' text='$v' name='opc_".$objeto["COD_REGISTR"]."' type='radio' style='width: 20px;margin: 7px 10px 0 0;' onClick=\"gravaOpcao('opc_".$objeto["COD_REGISTR"]."','#resposta_".$objeto["COD_REGISTR"]."');\"> $v<br>";
						}
						echo "</div>";
						echo "<input type='hidden' class='form-control input-hg respostaPergunta' id='resposta_".$objeto["COD_REGISTR"]."'>";

					}elseif ($objeto['DES_TIPO_RESPOSTA'] == "C"){

						echo "<div style='text-align:left'>";
						foreach($opcoes as $k =>  $v){
							echo "<input value='$k' text='$v' name='opc_".$objeto["COD_REGISTR"]."' type='checkbox' style='width: 20px;margin: 7px 10px 0 0;' onClick=\"gravaOpcao('opc_".$objeto["COD_REGISTR"]."','#resposta_".$objeto["COD_REGISTR"]."');\"> $v<br>";
						}
						echo "</div>";
						echo "<input type='hidden' class='form-control input-hg respostaPergunta' id='resposta_".$objeto["COD_REGISTR"]."'>";

					}elseif ($objeto['DES_TIPO_RESPOSTA'] == "RB" ||
							 $objeto['DES_TIPO_RESPOSTA'] == "CB"){

						echo "<div style='line-height:36px;'>";
						foreach($opcoes as $k =>  $v){
							echo "<input value='$k' text='$v' name='opc_".$objeto["COD_REGISTR"]."' type='".($objeto['DES_TIPO_RESPOSTA'] == "RB"?"radio":"checkbox")."' style='display:none;' onClick=\"gravaOpcao('opc_".$objeto["COD_REGISTR"]."','#resposta_".$objeto["COD_REGISTR"]."');\">";
							echo "<a data-value='$k' data-name='opc_".$objeto["COD_REGISTR"]."' onClick=\"clicaBloco('opc_".$objeto["COD_REGISTR"]."','#resposta_".$objeto["COD_REGISTR"]."','$k');\" style='border:2px solid #CCC;border-radius:6px;padding:5px;white-space:nowrap;' href='javascript:'>$v</a> &nbsp;";
						}
						echo "</div>";
						echo "<div class='push30'></div>";
						echo "<input type='hidden' class='form-control input-hg respostaPergunta' id='resposta_".$objeto["COD_REGISTR"]."'>";

					}elseif ($objeto['DES_TIPO_RESPOSTA'] == "A"){

						echo "<div style='line-height:36px;'>";
						foreach($opcoes as $k =>  $v){
							echo "<div style='display:flex;flex-wrap: nowrap;'>";
							echo "<div style='flex-basis: 100%;text-align:left;'>$v</div>";
							echo "<div style='text-align:right;'><a class='icon_negativo' name='opc_".$objeto["COD_REGISTR"]."' data-id='$k' data-text='$v' data-tp='N' href='javascript:' onClick=\"clicaBlocoAvaliacao('opc_".$objeto["COD_REGISTR"]."','#resposta_".$objeto["COD_REGISTR"]."',this);\" ><i class='far fa-thumbs-down'></i></a></div>";
							echo "<div style='text-align:left;'><a class='icon_positivo' name='opc_".$objeto["COD_REGISTR"]."' data-id='$k' data-text='$v' data-tp='S' href='javascript:' onClick=\"clicaBlocoAvaliacao('opc_".$objeto["COD_REGISTR"]."','#resposta_".$objeto["COD_REGISTR"]."',this);\" ><i class='far fa-thumbs-up'></i></a></div>";
							echo "</div>";
							echo "<div class='push1'></div>";
						}
						echo "</div>";
						echo "<input type='hidden' class='form-control input-hg respostaPergunta' id='resposta_".$objeto["COD_REGISTR"]."'>";

					}else{

						echo "<input tp-resp='".$objeto['DES_TIPO_RESPOSTA']."' type='text' class='form-control input-hg respostaPergunta' placeholder='Digite aqui'>";

					}
					?>
				</div>																	
			</div>

			<?php
			if (trim(@$objeto['DES_IMAGEM']) <> "") {
			?>
				<div class='push30'></div>
				<div class="row">
					<div class="div-imagem">
						<div  class="imagemTicket">
							<img src='https://img.bunker.mk/media/clientes/<?php echo $objeto['COD_EMPRESA'] ?>/pesquisa/<?php echo $objeto['DES_IMAGEM'];?>' class='upload-image img-responsive' style='cursor: pointer; max-width:100%; max-height: 100%; margin-left: auto; margin-right: auto;'>
						</div>
					</div>
				</div>
			<?php
			}
			?>

			<div class="row">
				<div class="col-md-12">
					<button type="button" class="btn btn-primary btn-hg btn-block <?=$opcaoIr?>" cod-avaliacao="<?=fnLimpacampoZero($objeto['COD_AVALIACAO'])?>" cod-modpesquisa="<?php echo $objeto['COD_REGISTR'];?>" cod-registro="<?php echo $codigo;?>" cod-blpesqu="<?php echo $objeto['COD_BLPESQU'];?>" cod-ordenacao="<?php echo $objeto['NUM_ORDENAC'];?>" cod-pesquisa="<?php echo $objeto['COD_TEMPLATE']; ?>">Continuar</button>
				</div>
			</div>				
		<?php
		break; 				
		case 3:// SALDO DE PONTOS
		?>
			<center class="bloco">
				<div class="row">
					<div class="col-md-10">
						<div class="push20"></div>
						<!--<h6>ISABEL DE ANDRADE MARTINEZ SALES BR</h6>
						<h6>Número Cartão: 1234 5678 9012 3456</h6>-->
						<h6>Saldo: R$ 0,18  31/05/2017</h6>
					</div>
					<div class="push50"></div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<button type="button" class="btn btn-primary btn-hg btn-block btnContinuar" cod-modpesquisa="<?php echo $objeto['COD_REGISTR'];?>" cod-registro="<?php echo $codigo;?>" cod-blpesqu="<?php echo $objeto['COD_BLPESQU'];?>" cod-ordenacao="<?php echo $objeto['NUM_ORDENAC'];?>" cod-pesquisa="<?php echo $objeto['COD_TEMPLATE']; ?>">Continuar</button>
					</div>
				</div>				
			</center>
		<?php
		break; 				
		case 4: // IMAGEM
		?>
			<div class="row">
				<div class="col-md-12">
					<div class="div-imagem">
						<?php
							if (empty(trim($objeto['DES_IMAGEM']))) {
								?>
								<div class="imagemTicket">
									<button class="btn btn-block btn-success upload-image"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
									<input type="file" cod_registr='<?php echo $objeto['COD_REGISTR'];?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;"/>
								</div>
								<?php
							}else{
								?>
								<div  class="imagemTicket">
									<img src='https://img.bunker.mk/media/clientes/<?php echo $objeto['COD_EMPRESA'] ?>/<?php echo $objeto['DES_IMAGEM'];?>' class='upload-image img-responsive' style='cursor: pointer; max-width:100%; max-height: 100%; margin-left: auto; margin-right: auto;'>
									<input type="file" cod_registr='<?php echo $objeto['COD_REGISTR'];?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;"/>
								</div>
								<?php
							}
						?>
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-md-12">
					<button type="button" class="btn btn-primary btn-hg btn-block <?=$opcaoIr?>" cod-avaliacao="<?=fnLimpacampoZero($objeto['COD_AVALIACAO'])?>" cod-modpesquisa="<?php echo $objeto['COD_REGISTR'];?>" cod-registro="<?php echo $codigo;?>" cod-blpesqu="<?php echo $objeto['COD_BLPESQU'];?>" cod-ordenacao="<?php echo $objeto['NUM_ORDENAC'];?>" cod-pesquisa="<?php echo $objeto['COD_TEMPLATE']; ?>">Continuar</button>
				</div>
			</div>			
		<?php
		break;		
		case 5:// AVALIAÇÃO


			$sql = "SELECT * FROM CONDICAO_PESQUISA WHERE COD_REGISTR = $objeto[COD_REGISTR]";
			// fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($objeto['COD_EMPRESA'],''),$sql);
			$condicoes = [];

			// fnEscreve("sem erro");
		
			while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {

				$condicao = [
								'codCondicao' => $qrBusca['COD_CONDICAO'],
								'condicaoAvalicao' => $qrBusca['TIP_CONDICAO'],
								'resultado' => $qrBusca['NUM_RESULTADO'],
								'blocoIrAvaliacao' => $qrBusca['NUM_REDIRECT']
							];

				array_push($condicoes, $condicao);

			}

			// echo "<pre>";
			// print_r($condicoes);
			// echo "</pre>";
		?>

			<center>
				<div class="row">
					<div class="col-md-12">
						<div class="push20"></div>
						<h5 class="lead"><?php echo $objeto['DES_PERGUNTA'];?></h5>
						<input type="hidden" class="des_pergunta" value="<?php echo $objeto['DES_PERGUNTA'];?>">												
						<input type="hidden" class="tip_bloco" value="<?php echo $objeto['TIP_BLOCO'];?>">												
						<input type="hidden" class="num_quantid" value="<?php echo $objeto['NUM_QUANTID'];?>">												
						<input type="hidden" class="log_condicoes" value='<?php echo json_encode($condicoes);?>'>												
						<div class="chart-scale">
						<?php
							$contador = 0;
							$contadorNovo = $objeto['NUM_QUANTID'];
							while ($contador <= $objeto['NUM_QUANTID']) {
								?>
									<!-- <input type="radio" id="star<?php echo $contadorNovo; ?>" name="rating" />
									<label valor="<?php echo $contadorNovo; ?>" class="totem <?php echo $objeto['TIP_BLOCO'];?>Type full" for="star<?php echo $contadorNovo; ?>">
										<div class="numero"><?php echo $contadorNovo; ?></div>
									</label> -->

									<button class="btn btn-scale btn-scale-desc-<?=$contador?> btnNota" valor="<?=$contador?>"><?=$contador?></button>
								<?php
								$contador++;
								$contadorNovo--;
							}																		
						
						?>
						</div>
						<?php
						global $connAdm;
						$sql = "SELECT * FROM TIPO_ROTULO_AVALIACAO_PESQUISA WHERE COD_ROTULO=0".$objeto['COD_ROTULO'];					
						$rotulo = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));
						echo "<table style='width:100%'>";
						echo "<td style='font-size:13px;text-align:left'>".$rotulo["DES_ROTULO_MIN"]."</td>";
						echo "<td style='font-size:13px;text-align:right'>".$rotulo["DES_ROTULO_MAX"]."</td>";
						echo "</table>";
						?>
						<div class="push20"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<button type="button" class="btn btn-primary btn-hg btn-block btnContinuar" cod-modpesquisa="<?php echo $objeto['COD_REGISTR'];?>" cod-registro="<?php echo $codigo;?>" cod-blpesqu="<?php echo $objeto['COD_BLPESQU'];?>" cod-ordenacao="<?php echo $objeto['NUM_ORDENAC'];?>" cod-pesquisa="<?php echo $objeto['COD_TEMPLATE']; ?>">Continuar</button>
					</div>
				</div>				
			</center>
		<?php
		break;	
		case 6:// LOGIN
		?>
		<style type="text/css">
			.center-block {
			    float: none;
			    margin-left: auto;
			    margin-right: auto;
			}

			.input-group .icon-addon .form-control {
			    border-radius: 0;
			}

			.icon-addon {
			    position: relative;
			    color: #555;
			    display: block;
			}

			.icon-addon:after,
			.icon-addon:before {
			    display: table;
			    content: " ";
			}

			.icon-addon:after {
			    clear: both;
			}

			.icon-addon.addon-md .glyphicon,
			.icon-addon .glyphicon, 
			.icon-addon.addon-md .fa,
			.icon-addon .fa {
			    position: absolute;
			    z-index: 2;
			    left: 10px;
			    font-size: 14px;
			    width: 20px;
			    margin-left: -2.5px;
			    text-align: center;
			    padding: 10px 0;
			    top: 1px
			}

			.icon-addon.addon-lg .form-control {
			    line-height: 1.33;
			    height: 46px;
			    font-size: 18px;
			    padding: 10px 16px 10px 40px;
			}

			.icon-addon.addon-sm .form-control {
			    height: 30px;
			    padding: 5px 10px 5px 28px;
			    font-size: 12px;
			    line-height: 1.5;
			}

			.icon-addon.addon-lg .fa,
			.icon-addon.addon-lg .glyphicon {
			    font-size: 18px;
			    margin-left: 0;
			    left: 11px;
			    top: 4px;
			}

			.icon-addon.addon-md .form-control,
			.icon-addon .form-control {
			    padding-left: 30px;
			    float: left;
			    font-weight: normal;
			}

			.icon-addon.addon-sm .fa,
			.icon-addon.addon-sm .glyphicon {
			    margin-left: 0;
			    font-size: 12px;
			    left: 5px;
			    top: -1px
			}

			.icon-addon .form-control:focus + .glyphicon,
			.icon-addon:hover .glyphicon,
			.icon-addon .form-control:focus + .fa,
			.icon-addon:hover .fa {
			    color: #2580db;
			}
		</style>
			<center class="bloco">
				<div class="row">
					<div class="col-md-12">
						<header>
							<p class="lead">Não conseguimos identificar seu cadastro. Por favor, faça o login para continuar a pesquisa.</p>
						</header>
						<div class="row">
							<div class="col-md-12">
								<div class="push20"></div>
								<!-- <input type="text" id="cpf" name="cpf" class="form-control input-hg cpf text-center" placeholder="Seu CPF" maxlength="14"> -->
								<div class="form-group">
					                <div class="icon-addon addon-lg">
					                    <input type="text" placeholder="CPF ou CNPJ" class="form-control" id="cpf" name="cpf" maxlength="18" onkeydown="mascaraCpfCnpj($(this))">
					                    <label for="CPF" class="fa fa-search" rel="tooltip" title="CPF"></label>
					                </div>
					                <div class="text-muted text-center"> OU </div>
					                <div class="push20"></div>
					                <div class="icon-addon addon-lg">
					                    <input type="text" placeholder="Email" class="form-control" id="email" name="email" maxlength="60">
					                    <label for="email" class="fa fa-envelope" rel="tooltip" title="Email"></label>
					                </div>
					                <div class="text-muted text-center"> OU </div>
					                <div class="push20"></div>
					                <div class="icon-addon addon-lg">
					                    <input type="text" placeholder="Celular" class="form-control sp_celphones" id="celular" name="celular" maxlength="14">
					                    <label for="celular" class="fa fa-phone" rel="tooltip" title="Celular"></label>
					                </div>
					            </div>
								<div class="push20"></div>
								<div class="loading" style="width: 100%; display: none;"></div>
								<button type="button" class="btn btn-primary btn-hg btn-block btnContinuar" cod-modpesquisa="<?php echo $objeto['COD_REGISTR'];?>" cod-registro="<?php echo $codigo;?>" cod-blpesqu="<?php echo $objeto['COD_BLPESQU'];?>" cod-ordenacao="<?php echo $objeto['NUM_ORDENAC'];?>" cod-pesquisa="<?php echo $objeto['COD_TEMPLATE']; ?>">Continuar Pesquisa</button>
								<?php 
									if($objeto['LOG_TOTEM'] == 'S'){ 

								?>
									<div class="push15"></div>
									<a type="button" class="btn btn-default btn-hg btn-block" onclick="window.top.location.href = 'http://totem.bunker.mk/index.do?key=<?php echo $objeto[URL_TOTEM]; ?>'" style="border-radius: 30px;">Não possui cadastro? Cadastrar-se</a>
								<?php } ?>
								<div class="errorLogin" style="color: red; text-align: center; display: none">Usuário inválido. Tente novamente!<br/>Utilize o mesmo dado informado no seu cadastro.</div>
							</div>
						</div>
					</div>																
				</div>															
			</center>
			<hr class="divisao"/>
			<script>
				var SPMaskBehavior = function (val) {
				  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
				},
				spOptions = {
				  onKeyPress: function(val, e, field, options) {
					  field.mask(SPMaskBehavior.apply({}, arguments), options);
					}
				};			
				
				$('.sp_celphones').mask(SPMaskBehavior, spOptions);	
			</script>
		<?php
		break;		
		case 7:// LOGIN COM SENHA
		?>
			<center class="bloco">
				<div class="row">
					<div class="col-md-12">
						<header>
							<p class="lead">Faça seu login para responder nossas pesquisas!</p>
						</header>
						<div class="row">
							<div class="col-md-12">
								<input type="text" id="cpf" name="cpf" class="form-control input-hg cpf text-center" placeholder="Seu CPF" maxlength="14">
								<input type="password" id="senha" name="senha" class="form-control input-hg" placeholder="Sua Senha">
								<div class="push20"></div>
								<div class="loading" style="width: 100%; display: none;"></div>
								<button type="button" class="btn btn-primary btn-hg btn-block btnContinuar" cod-modpesquisa="<?php echo $objeto['COD_REGISTR'];?>" cod-registro="<?php echo $codigo;?>" cod-blpesqu="<?php echo $objeto['COD_BLPESQU'];?>" cod-ordenacao="<?php echo $objeto['NUM_ORDENAC'];?>" cod-pesquisa="<?php echo $objeto['COD_TEMPLATE']; ?>">Continuar Pesquisa</button>
								<div class="errorLogin" style="color: red; text-align: center; display: none">Usuário inválido. Tente novamente!</div>
							</div>
						</div>
					</div>																
				</div>															
			</center>
			<hr class="divisao"/>
		<?php
		break;
		case 8:// SMART LOGIN

		$nome = explode(' ', $objeto['NOM_CLIENTE']);
		$nome = ucfirst(strtolower($nome[0]));

		// echo $objeto['COD_PLAYERS'];
		// exit();

		if($objeto['COD_PLAYERS'] != 0){

			$cod_empresa = $objeto['COD_EMPRESA'];
			$cod_players = $objeto['COD_PLAYERS'];

			$sql = "SELECT T.LOG_TICKET
					FROM TOTEM_PLAYERS T 
					LEFT JOIN WEBTOOLS.UNIDADEVENDA U ON U.COD_UNIVEND=T.COD_UNIVEND
					WHERE T.COD_EMPRESA = $cod_empresa
					AND T.COD_PLAYERS = $cod_players
					AND U.LOG_ESTATUS != 'N'";

			// echo($sql);
			// exit();

			$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);

			$qrLista = mysqli_fetch_assoc($arrayQuery);

			$log_ticket = $qrLista['LOG_TICKET'];

			if($log_ticket == 'S'){
				// $txt = "Avaliar e Ver Ofertas";
				$txt = "Avaliar";
			}else{
				$txt = "Avaliar";
				// $txt = "Responder Avaliação";
			}
		}else{
		?>

			<div class="row">
				<div class="col-md-12">
					<div class="push20"></div>
					<h5 class="lead text-center">Olá <b><?=$nome?></b>, bem vindo a nossa pesquisa: <?=$objeto['DES_PESQUISA']?></h5>
					<div class="push20"></div>
				</div>						
			</div>

		<?php 
			$txt = "Continuar";
		}

		?>	
			<div class="row">
				<div class="col-md-12">
					<button type="button" class="btn btn-primary btn-hg btn-block btnContinuar" cod-modpesquisa="<?php echo $objeto['COD_REGISTR'];?>" cod-registro="<?php echo $codigo;?>" cod-blpesqu="<?php echo $objeto['COD_BLPESQU'];?>" cod-ordenacao="<?php echo $objeto['NUM_ORDENAC'];?>" cod-pesquisa="<?php echo $objeto['COD_TEMPLATE']; ?>"><?=$txt?></button>
				</div>
			</div>
			<script type="text/javascript">$("#COD_CLIENTE").val("<?=$objeto[COD_CLIENTE]?>");</script>				
		<?php
		break;		
	}
}
?>