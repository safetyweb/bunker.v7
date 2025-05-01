<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$dias30 = "";
$hoje = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = [];
$array = [];
$qrListaUniVendas = "";
$grupoTrabalho = "";
$retorno = "";
$inicio = "";
$countLinha = "";
$qrListaVendas = "";
$atualizado = "";
$loja = "";




$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

switch ($opcao) {
	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "select  
                                        UNI.NOM_FANTASI as Loja,
                                        US.NOM_USUARIO as VENDEDOR,
                                        C.NOM_CLIENTE as Cliente,
                                        A.NUM_CGCECPF as Cpf,
                                        A.CAMPOS_ATUALIZ as CAMPO,
                                        A.DADOS_ATUALIZADOS as Dado_Atualizado,
                                        C.DAT_CADASTR as Data_Cadastro,
                                        A.data_hora as Data_Controle,
                                       CASE WHEN A.COD_ATUALIZADO = 0 THEN 'histórico'
                                             WHEN A.COD_ATUALIZADO = 1  THEN 'atual.'
                                             ELSE 'novo' END AS Tipo
                                        
						 from historico_atualizacao A
                                        inner JOIN webtools.usuarios US ON US.COD_EXTERNO=A.VENDEDOR AND US.cod_empresa=$cod_empresa        
                                        left JOIN unidadevenda UNI ON UNI.COD_UNIVEND= A.cod_univend	         
					left join  clientes C on A.NUM_CGCECPF=C.NUM_CGCECPF
					where A.COD_EMPRESA = $cod_empresa and 
						  A.COD_UNIVEND in ($lojasSelecionadas) and
						  A.COD_ATUALIZADO in (0,1,2) and
						  A.data_hora between '$dat_ini 00:00:00' and '$dat_fim 23:59:59' 
						  ORDER BY A.data_hora DESC";

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			//$limpandostring= fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo=json_decode($limpandostring,true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $textolimpo, ';', '"');
			//echo "<pre>";
			//print_r($row	);
			//echo "</pre>";
		}
		fclose($arquivo);

		break;
	case 'paginar':

		/*$ARRAY_UNIDADE1=array(
						   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
						   'cod_empresa'=>$cod_empresa,
						   'conntadm'=>$connAdm->connAdm(),
						   'IN'=>'N',
						   'nomecampo'=>'',
						   'conntemp'=>'',
						   'SQLIN'=> ""   
						   );
			$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);
                         * 
                         */
		$ARRAY_VENDEDOR1 = array(
			'sql' => "select COD_EXTERNO,COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
			'cod_empresa' => $cod_empresa,
			'conntadm' => $connAdm->connAdm(),
			'IN' => 'N',
			'nomecampo' => '',
			'conntemp' => '',
			'SQLIN' => ""
		);
		$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

		// echo '<pre>';
		//  print_r($ARRAY_VENDEDOR);
		//	echo '</pre>'; 
		//      exit();

		//busca por grupo de trabalho
		if (!empty($cod_grupotr)) {
			$sql = "select COD_UNIVEND from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' and COD_GRUPOTR = '" . $cod_grupotr . "' and cod_exclusa =0 order by trim(NOM_FANTASI)";
			$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
			while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery)) {
				$grupoTrabalho .= $qrListaUniVendas['COD_UNIVEND'] . ",";
			}
			//substitui lojas selecionadas
			$lojasSelecionadas = substr($grupoTrabalho, 0, -1);
		}

		$sql = "select COUNT(*) CONTADOR
						 from historico_atualizacao A
					left join  clientes C on A.NUM_CGCECPF=C.NUM_CGCECPF
                                        LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
					where A.COD_EMPRESA = $cod_empresa and 
						  A.COD_UNIVEND in ($lojasSelecionadas) and
						  A.COD_ATUALIZADO in (1,2) and
						  A.data_hora between '$dat_ini 00:00:00' and '$dat_fim 23:59:59' 
						  ORDER BY A.data_hora DESC ";

		//fnEscreve($sql);
		//fnTestesql(connTemp($cod_empresa,''), $sql);
		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

		$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "select A.data_hora,
							A.CAMPOS_ATUALIZ,
							A.COD_EMPRESA,
							A.DADOS_ATUALIZADOS,
							A.NUM_CGCECPF,
							A.VENDEDOR,
							A.COD_UNIVEND,
                                                        uni.NOM_FANTASI,
							C.NOM_CLIENTE,
							A.COD_ATUALIZADO,
							C.DAT_CADASTR
						 from historico_atualizacao A
					left join  clientes C on A.NUM_CGCECPF=C.NUM_CGCECPF
                                        LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
					where A.COD_EMPRESA = $cod_empresa and 
						  A.COD_UNIVEND in ($lojasSelecionadas) and
						  A.COD_ATUALIZADO in (1,2) and
						  A.data_hora between '$dat_ini 00:00:00' and '$dat_fim 23:59:59' 
						  ORDER BY A.data_hora DESC limit $inicio,$itens_por_pagina ";

		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$countLinha = 1;
		while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
			//echo $qrListaVendas['VENDEDOR']; 
			/*$NOM_ARRAY_UNIDADE=(array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
                         * 
                         */
			$NOM_ARRAY_NON_VENDEDOR = (array_search($qrListaVendas['VENDEDOR'], array_column($ARRAY_VENDEDOR, 'COD_EXTERNO')));

			switch ($qrListaVendas['COD_ATUALIZADO']) {
				case 0: //cadastro histórico
					$atualizado = "histórico";
					break;
				case 1: //quando ja existe cadastro, mas o cep válido é adicionado
					$atualizado = "atual.";
					break;
				case 2: //campo novo (novo cadastro ou já existente)
					$atualizado = "novo";
					break;
			}

			//fnEscreve($loja);
?>
			<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
				<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></small></td>
				<td><small><?php echo $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO']; ?></small></small></td>
				<td><small><?php echo $qrListaVendas['NOM_CLIENTE']; ?></small></td>
				<td><small><?php echo $qrListaVendas['NUM_CGCECPF']; ?></small></td>
				<td><small><?php echo $qrListaVendas['CAMPOS_ATUALIZ']; ?></small></td>
				<td><small><?php echo $qrListaVendas['DADOS_ATUALIZADOS']; ?></small></td>
				<td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
				<td><small><?php echo fnDataFull($qrListaVendas['data_hora']); ?></small></td>
				<td><small><?php echo $atualizado; ?></small></td>
			</tr>
<?php

			$countLinha++;
		}
		break;
}
?>