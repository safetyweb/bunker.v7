<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
	$opcao = fnLimpaCampo($_GET['opcao']);

	// fnEscreve($opcao);

	$ARRAY_UNIDADE1=array(
				   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
				   'cod_empresa'=>$cod_empresa,
				   'conntadm'=>$connAdm->connAdm(),
				   'IN'=>'N',
				   'nomecampo'=>'',
				   'conntemp'=>'',
				   'SQLIN'=> ""   
				   );
	$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);

	switch($opcao){
		case 'loadMore':

			$inicio = fnLimpaCampoZero($_GET['itens']);
		
			$sql = "SELECT EC.COD_CONTROLE,
						   EC.NOM_CLIENTE, 
						   EC.DES_EMAILUS,
						   EC.LOG_OK,
						   EC.DAT_OK,
						   EC.DAT_ENVIO,
						   CL.COD_UNIVEND, 
						   CL.COD_CLIENTE
					FROM EMAIL_CONTROLE EC 
					INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = EC.COD_CLIENTE
					WHERE EC.COD_EMPRESA = $cod_empresa
					AND EC.COD_CAMPANHA = $cod_campanha
					ORDER BY EC.NOM_CLIENTE
					LIMIT $inicio, 50";

			//fnEscreve($sql);

			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			
			$count=0;
			while ($qrLista = mysqli_fetch_assoc($arrayQuery)){

				$count++;
				$NOM_ARRAY_UNIDADE=(array_search($qrLista['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));

				if($qrLista['LOG_OK'] == 'S'){
					$log_ok = "<a href='javascript:void(0);' class='btn btn-xs btn-success transparency'>Ok <span class='fa fa-check'></span></a>";
				}else{
					$log_ok = "<a href='javascript:void(0);' class='btn btn-xs btn-danger transparency' onclick='okCliente(\"".fnEncode($qrLista['COD_CONTROLE'])."\",".$count.")'>Ok <span class='fa fa-times'></span></a>";
				}

				if($qrLista['DAT_OK'] != ''){
					$dat_ok = fnDataFull($qrLista['DAT_OK']);
				}else{
					$dat_ok = "";
				}

				if($qrLista['DAT_ENVIO'] != ''){
					$dat_envio = fnDataFull($qrLista['DAT_ENVIO']);
				}else{
					$dat_envio = "";
				}

				echo"
					<tr id='".fnEncode($qrLista['COD_CONTROLE'])."'>
					  <!-- <td class='text-center'><input type='checkbox' id='check_$count' name='check_$count' onclick='retornaFormPersonas(".$count.")' checked value='".$qrLista['COD_CLIENTE']."'>&nbsp;</td> -->
					  <td><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=". fnEncode($qrLista['COD_CLIENTE'])."' class='f14' target='_blank'>".ucwords(strtolower(($qrLista['NOM_CLIENTE'])))."</a></td>
					  <td><small>".strtolower($qrLista['DES_EMAILUS'])."</td>
					  <td><small>".$ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']."</small></td>
						  <td class='data-envio'><small>".$dat_envio."</small></td>
						  <td><small></small>".$dat_ok."</small></td>
					  <td class='text-center'>".$log_ok."</small></td>
					</tr>
					<input type='hidden' id='ret_COD_CLIENTE_".$count."' value='".$qrLista['COD_CLIENTE']."'>
				";  
			}

		break;

		case 'okCliente':

		// fnEscreve('entrou');

			$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
			$cod_controle = fnLimpaCampoZero(fnDecode($_POST['COD_CONTROLE']));
			$count = fnLimpaCampoZero(fnDecode($_POST['COUNT']));
			$now = date("Y-m-d H:i:s");

			$sql = "UPDATE EMAIL_CONTROLE SET
								LOG_OK = 'S',
								DAT_OK = '$now',
								COD_USUCADA_OK = $cod_usucada
					WHERE COD_CONTROLE = $cod_controle";

			// fnEscreve($sql);

			mysqli_query(connTemp($cod_empresa,''),$sql);
		
			$sql = "SELECT EC.COD_CONTROLE,
						   EC.NOM_CLIENTE, 
						   EC.DES_EMAILUS,
						   EC.LOG_OK,
						   EC.DAT_OK,
						   EC.DAT_ENVIO,
						   CL.COD_UNIVEND, 
						   CL.COD_CLIENTE
					FROM EMAIL_CONTROLE EC 
					INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = EC.COD_CLIENTE
					WHERE EC.COD_EMPRESA = $cod_empresa
					AND EC.COD_CAMPANHA = $cod_campanha
					AND EC.COD_CONTROLE = $cod_controle";

			//fnEscreve($sql);

			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			
			$qrLista = mysqli_fetch_assoc($arrayQuery);

			$NOM_ARRAY_UNIDADE=(array_search($qrLista['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));

			if($qrLista['LOG_OK'] == 'S'){
				$log_ok = "<a href='javascript:void(0);' class='btn btn-xs btn-success transparency'>Ok <span class='fa fa-check'></span></a>";
			}else{
				$log_ok = "<a href='javascript:void(0);' class='btn btn-xs btn-danger transparency' onclick='okCliente(".fnEncode($qrLista['COD_CONTROLE']).",".$count.")'>Ok <span class='fa fa-times'></span></a>";
			}

			if($qrLista['DAT_OK'] != ''){
				$dat_ok = fnDataFull($qrLista['DAT_OK']);
			}else{
				$dat_ok = "";
			}

			if($qrLista['DAT_ENVIO'] != ''){
				$dat_envio = fnDataFull($qrLista['DAT_ENVIO']);
			}else{
				$dat_envio = "";
			}

			echo"
			  <td><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=". fnEncode($qrLista['COD_CLIENTE'])."' class='f14' target='_blank'>".ucwords(strtolower(($qrLista['NOM_CLIENTE'])))."</a></td>
			  <td><small>".strtolower($qrLista['DES_EMAILUS'])."</td>
			  <td>".$ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']."</td>
			  <td class='data-envio'><small>".$dat_envio."</small></td>
			  <td>".$dat_ok."</td>
			  <td class='text-center'>".$log_ok."</td>
			"; 
			

		break;
	}											


?>