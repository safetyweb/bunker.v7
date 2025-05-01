<?php 

	include './_system/_functionsMain.php';
	require_once './js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type; 

	$opcao = fnLimpaCampo($_GET['opcao']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['COD_EMPRESA']));
	$cod_campanha = fnLimpaCampoZero(fnDecode($_REQUEST['COD_CAMPANHA']));
	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	// fnEscreve($opcao);

	switch($opcao){

		case 'CRIAR_CAMP':

			$sqlCamp = "SELECT DES_CAMPANHA,
							   DES_OBSERVA,
							   DAT_INI,
							   HOR_INI,
							   DAT_FIM,
							   HOR_FIM,
							   TIP_CAMPANHA
						FROM CAMPANHA 
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_CAMPANHA = $cod_campanha
						AND LOG_ATIVO = 'S'";
			// fnEscreve($sqlCamp);

			$qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCamp));

			$dat_ini = date("d/m/Y H:i",strtotime($qrCamp['DAT_INI']." ".$qrCamp['HOR_INI']));
			$dat_fim = date("d/m/Y H:i",strtotime($qrCamp['DAT_FIM']." ".$qrCamp['HOR_FIM']));
			$des_observa = fnAcentos($qrCamp['DES_OBSERVA']);
			$des_campanha = fnAcentos($qrCamp['DES_CAMPANHA']);

			include './_system/ibope/BuscarCampanha.php';
			include './_system/ibope/FnIbotpe.php';

			$cadastrocampanha = array(
								    'nome'=> $des_campanha,
								    'dataInicio'=> $dat_ini,
								    'dataVencimento'=> $dat_fim,
								    'tipoCampanha'=> 1,
								    'objetivo'=> $des_observa,
								    'ativacao'=>'true'
							   	);

			$retorno = cadastraCampanha ($User,$cadastrocampanha);

			$cod_ext_campanha = $retorno['body']['envelope']['body']['cadastracampanharesponse']['cadastracampanharesult'];

			$sql = "UPDATE CAMPANHA SET COD_EXT_CAMPANHA = $cod_ext_campanha WHERE COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa";
			// fnEscreve($sql);
			mysqli_query(connTemp($cod_empresa,''),$sql);

			// echo '<pre>';
			// print_r($retorno);
			// echo '</pre>';

			// fnEscreve($retorno['body']['envelope']['body']['cadastracampanharesponse']['cadastracampanharesult']);

		break;

		case 'ENVIAR_MODEL':


			$sqlMsg = "SELECT TE.*, MDE.DES_TEMPLATE AS HTML FROM MENSAGEM_EMAIL ME
						LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_EMAIL
						LEFT JOIN MODELO_EMAIL MDE ON MDE.COD_TEMPLATE = TE.COD_TEMPLATE
						WHERE ME.COD_EMPRESA = $cod_empresa 
						AND ME.COD_CAMPANHA = $cod_campanha
						AND ME.LOG_PRINCIPAL = 'S'";
			// fnEscreve($sqlMsg);

			$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlMsg));

			if($qrMsg['LOG_OPT'] == 'S'){
				$log_opt = 1;
			}else{
				$log_opt = 0;
			}

			if($qrMsg['TAG_LINKOPT'] != ""){
				$tag_linkopt = $qrMsg['TAG_LINKOPT'];
			}else{
				$tag_linkopt = "";
			}

			if($qrMsg['TAG_OPT'] != ""){
				$tag_opt = $qrMsg['TAG_OPT'];
			}else{
				$tag_opt = "";
			}

			$html = "<html><head></head><body>".trim(htmlentities($qrMsg['HTML']))."</body></html>";

			include './_system/ibope/BuscarCampanha.php';
			include './_system/ibope/FnIbotpe.php';

			$cadastraConfiguracaoEmail=array(
											'enderecoRemetente'=> $qrMsg['END_REMET'],
			                                'nomeRemetente'=> $qrMsg['DES_REMET'],
		                                    'emailDeResposta'=> $qrMsg['EMAIL_RESPOSTA'],
		                                    'assunto'=> $qrMsg['DES_ASSUNTO'],
		                                    'conteudoHtml'=> $html,
		                                    'flagOptOut'=> $log_opt,
		                                    'textoLinkOptOut'=> $qrMsg['TXT_LINKOPT'],
		                                    'textoOptOut'=> $qrMsg['TXT_OPT'],
		                                    'tagLinkOptOut'=> $tag_linkopt,
		                                    'tagOptOut'=> $tag_opt
		                               );

			// echo '<pre>';
			// print_r($cadastraConfiguracaoEmail);
			// echo '</pre>';

			$retorno = cadastraConfiguracaoEmail ($User,$cadastraConfiguracaoEmail);

			$cod_ext_template = $retorno['body']['envelope']['body']['cadastraconfiguracaoemailresponse']['cadastraconfiguracaoemailresult'];

			// fnEscreve($cod_ext_template);

			$sql = "UPDATE TEMPLATE_EMAIL SET COD_EXT_TEMPLATE = $cod_ext_template WHERE COD_TEMPLATE = (
																											SELECT COD_TEMPLATE_EMAIL FROM MENSAGEM_EMAIL 
																											WHERE COD_EMPRESA = $cod_empresa 
																											AND COD_CAMPANHA = $cod_campanha
																											AND LOG_PRINCIPAL = 'S'
																										)";
			// fnEscreve($sql);
			mysqli_query(connTemp($cod_empresa,''),$sql);

		break;

		case 'ENVIAR_LISTA':

			$sqlLote = "SELECT MAX(COD_LOTE) AS NRO_LOTES FROM EMAIL_LISTA 
						 WHERE COD_EMPRESA = $cod_empresa 
						 AND COD_CAMPANHA = $cod_campanha";
			$qrLote = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlLote));
			$nro_lotes = $qrLote['NRO_LOTES'];

			fnEscreve($nro_lotes);

			include './_system/ibope/BuscarCampanha.php';
			include '_system/ftpIbope.php';

			for ($i=1; $i <= $nro_lotes ; $i++) { 

				$nomeRel = $_GET['nomeRel']."_".$i;
				$arquivo = '_system/ibope/listas_envio/'.$cod_empresa.'_'.$nomeRel.'.csv';
				$caminhoRelat = './ibope/listas_envio/'.$cod_empresa.'_'.$nomeRel.'.csv';

				// fnEscreve($arquivo);
			
				$writer = WriterFactory::create(Type::CSV);
				$writer->setFieldDelimiter(';');
				$writer->openToFile($arquivo); 
	                               
				$sql = "SELECT DES_EMAILUS,
							   COD_CLIENTE,
							   NOM_CLIENTE,
							   CASE 
							   	   WHEN COD_SEXOPES = 1 THEN 'MASCULINO' 
							   	   WHEN COD_SEXOPES = 2 THEN 'FEMININO' 
							   	   ELSE 'INDEFINIDO' 
							   END AS SEXO,
							   DAT_NASCIME
						FROM EMAIL_LISTA
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_CAMPANHA = $cod_campanha
						AND COD_LOTE = $i
						ORDER BY NOM_CLIENTE";
						
				// fnEscreve($sql);
				// fnEscreve($arquivo);
						
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

				$array = array();
				while($row = mysqli_fetch_assoc($arrayQuery)){
					  $newRow = array();
					  
					  $cont = 0;
					  foreach ($row as $objeto) {
						  
						
						array_push($newRow, $objeto);
						
						  
						$cont++;
					  }
						
					$array[] = $newRow;
				}
				
				$arrayColumnsNames = array();
				$count = 0;
				while($row = mysqli_fetch_field($arrayQuery))
				{
					
					array_push($arrayColumnsNames, $row->name);
					
					$count++;
				}			

				$writer->addRow($arrayColumnsNames);
				$writer->addRows($array);

				$writer->close();


			$nomeRel = $cod_empresa.'_'.$nomeRel.'.csv';

			$dadosarquivo = array(
									'arqlocal'=> $arquivo,
			                        'nomearq'=> $nomeRel
			                	); 

			// echo '<pre>';
			// print_r($dadosarquivo);
			// echo '</pre>';

			$retorno = ibopeftp($dadosarquivo); 

			$sql = "SELECT CP.DES_CAMPANHA, 
						   CP.DAT_INI, 
						   CP.HOR_INI,
						   CP.COD_EXT_CAMPANHA, 
						   TE.COD_EXT_TEMPLATE 
					FROM MENSAGEM_EMAIL ME
					LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_EMAIL
					LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = ME.COD_CAMPANHA
					WHERE ME.COD_EMPRESA = $cod_empresa 
					AND ME.COD_CAMPANHA = $cod_campanha
					AND ME.LOG_PRINCIPAL = 'S'";
			$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

			$sqlControle = "INSERT INTO EMAIL_LOTE(
												COD_CAMPANHA,
												COD_EMPRESA,
												COD_LOTE,
												COD_STATUSUP,
												NOM_ARQUIVO,
												DES_PATHARQ,
												COD_USUCADA
											) VALUES(
												$cod_campanha,
												$cod_empresa,
												$i,
												'$retorno[uploadcod]',
												'$nomeRel',
												'$arquivo',
												$cod_usucada
											)";
			// fnEscreve($sqlControle);

			mysqli_query(connTemp($cod_empresa,''),$sqlControle);

			if($qrMsg['COD_EXT_CAMPANHA'] && $qrMsg['COD_EXT_TEMPLATE'] && $retorno['uploadcod'] == 3){

				$dat_ini = date("d/m/Y H:i",strtotime($qrCamp['DAT_INI']." ".$qrCamp['HOR_INI']));

				$dadosProcessaMailing=array(
                                    'txtNome'=>$qrMsg['DES_CAMPANHA'],
                                    'flgUploadArquivo'=> 1,
                                    'txtSeparadorDadoArquivo'=>';',
                                    'flgAtivo'=>1,      
                                    'txtPersonalizacao'=>htmlentities('<#nome>,<#cupom>'),    
                                    'txtNomeArquivo'=>$nomeRel,
                                    'idConfig'=>$qrMsg['COD_EXT_TEMPLATE'],
                                    'idCampanha'=>$qrMsg['COD_EXT_CAMPANHA'],   
                                    'datAgendamento'=>$dat_ini
                                    );

				echo '<pre>';
				print_r($dadosProcessaMailing);
				echo '</pre>';

				// $retornoCad = cadastraEProcessaMailing ($User,$dadosProcessaMailing);

				// echo '<pre>';
				// print_r($retornoCad);
				// echo '</pre>';

			}else{
				echo"falta coisa";
			}

		}


		break;

	}
?>
