<?php
        include '../_system/_functionsMain.php'; 
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//fnDebug('true');
        
    $opcao = $_GET['opcao'];
    $itens_por_pagina = $_GET['itens_por_pagina'];
    $pagina = $_GET['idPage'];
    $cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
    $cod_univend = fnLimpaCampo($_REQUEST['COD_UNIVEND']);
    $dat_ini = fnDataSql($_POST['DAT_INI']);
    $hor_ini = fnLimpaCampo($_POST['HOR_INI']);
    $dat_fim = fnDataSql($_POST['DAT_FIM']);
    $hor_fim = fnLimpaCampo($_POST['HOR_FIM']);

    $num_celular = preg_replace("/[^0-9]/", "",fnLimpaCampo($_POST['NUM_CELULAR']));
    $lojasSelecionadas = $_REQUEST['LOJAS'];
    // fnEscreve($lojasSelecionadas);
    
    $hoje = fnFormatDate(date("Y-m-d"));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	$dias30 = fnFormatDate(date("Y-m-d"));
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30);
    $hor_ini = "00:00:00";
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje);
    $hor_fim = "23:59:00";
	}
    
    switch ($opcao) {
        case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $writer = WriterFactory::create(Type::CSV);
        $writer->setFieldDelimiter(';');
        $writer->openToFile($arquivo);

        if ($cod_univend != "" && $cod_univend != "9999") {
            //$andUnidade = "AND gt.COD_UNIVEND IN($lojasSelecionadas)";
        }
        if($num_celular != ""){
            $andCelular = "AND gt.NUM_CELULAR = $num_celular"; 
        }

        if ($dat_ini != ""){
          $data_ini = $dat_ini . ' ' . $hor_ini . ":00";
          $data_fim = $dat_fim . ' ' . $hor_fim . ":00";
        }

        $sql = "SELECT
                    gt.COD_EMPRESA, 
                    gt.COD_USUCADA, 
                    gt.NOM_CLIENTE, 
                    gt.NUM_CGCECPF, 
                    gt.NUM_CELULAR, 
                    gt.DES_EMAIL,
                    gt.LOG_USADO ATUALIZADO, 
                    gt.COD_TOKEN, 
                    rt.DAT_CADAST NOVO_CLIENTE, 
                    rt.DAT_CADAST DAT_CADASTR, 
                    rt.DES_MSG, 
                    UNI.NOM_FANTASI, 
                    CASE WHEN gt.COD_EXCLUSA=1 AND LOG_USADO=1 THEN '1' ELSE '0' END DES_STATUS_TOKEN,
                    ret.BOUNCE,
                    ret.COD_CCONFIRMACAO,
                    rt.CHAVE_CLIENTE
                    
                  FROM geratoken gt
                  INNER JOIN rel_geratoken rt ON gt.DES_TOKEN=rt.TOKEN AND rt.COD_GERATOKEN=gt.COD_TOKEN
                  left JOIN sms_lista_ret ret ON ret.COD_CLIENTE=gt.COD_CLIENTE AND ret.CHAVE_CLIENTE=rt.CHAVE_CLIENTE
                  LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=gt.COD_UNIVEND
                  WHERE gt.COD_EMPRESA = $cod_empresa
                  AND gt.DAT_CADASTR BETWEEN '$data_ini' AND '$data_fim' 
                  AND gt.COD_UNIVEND IN($lojasSelecionadas)
                  $andCelular
                  ORDER BY gt.DAT_CADASTR DESC";

                  fnEscreve($sql);

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        // fnEscreve($sql);

        $array = array();

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $newRow = array();
            $cont = 0;

            foreach ($row as $objeto) {

                if($cont == 8){

                  if($row[DAT_CADASTR_CLIENTE] == ""){

                    $cliNovo = "";

                  }else if($row[DAT_CADASTR_CLIENTE] >= $row[DAT_CADASTR_TOKEN]){

                    $cliNovo = "S";

                  }else{

                    $cliNovo = "N";

                  }

                  array_push($newRow, $cliNovo);

                }else if($cont == 9){

                  array_push($newRow, fnDataFull($objeto));

                }else if($cont == 6){

                  if($objeto == 2){

                    $cadastrado = 'S';

                  }else{

                    $cadastrado = '';

                  }

                  array_push($newRow, $cadastrado);

                }else if($cont == 12){

                  if($objeto == 1){

                    $excluido = 'S';

                  }else{

                    $excluido = '';

                  }

                  array_push($newRow, $excluido);

                }else{

                  array_push($newRow, $objeto);

                }

                $cont++;
            }
            $array[] = $newRow;
        }



        $arrayColumnsNames = array();
        while ($row = mysqli_fetch_field($arrayQuery)) {
            array_push($arrayColumnsNames, $row->name);
        }

        $writer->addRow($arrayColumnsNames);
        $writer->addRows($array);

        $writer->close();

        break;            
                case 'paginar':
                    

                if($cod_univend != "" && $cod_univend != "9999"){
                 // $andUnidade = "AND gt.COD_UNIVEND = $cod_univend";
                }
                if($num_celular != ""){
                  $andCelular = "AND gt.NUM_CELULAR = $num_celular"; 
                }

                $sqlCount = "SELECT 1
                FROM geratoken gt
                INNER JOIN rel_geratoken rt ON gt.DES_TOKEN=rt.TOKEN
                WHERE gt.COD_EMPRESA = $cod_empresa
                AND gt.DAT_CADASTR BETWEEN '$data_ini'AND '$data_fim'   
                AND gt.COD_UNIVEND IN($lojasSelecionadas)";

                $retorno = mysqli_query(connTemp($cod_empresa,''),$sqlCount);
                $totalitens_por_pagina = mysqli_num_rows($retorno);
                $numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);

                //variavel para calcular o início da visualização com base na página atual
                $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                 $sql = "SELECT
                            gt.COD_EMPRESA, 
                            gt.COD_USUCADA, 
                            gt.NOM_CLIENTE, 
                            gt.NUM_CGCECPF, 
                            gt.NUM_CELULAR, 
                            gt.DES_EMAIL,
                            gt.LOG_USADO, 
                            gt.COD_TOKEN, 
                            rt.DAT_CADAST DAT_CADASTR, 
                            rt.DES_MSG, 
                            UNI.NOM_FANTASI, 
                            CASE WHEN gt.COD_EXCLUSA=1 AND LOG_USADO=1 THEN '1' ELSE '0' END DES_STATUS_TOKEN,
                            ret.BOUNCE,
                            ret.COD_CCONFIRMACAO,
                            rt.CHAVE_CLIENTE
                            
                          FROM geratoken gt
                          INNER JOIN rel_geratoken rt ON gt.DES_TOKEN=rt.TOKEN AND rt.COD_GERATOKEN=gt.COD_TOKEN
                          left JOIN sms_lista_ret ret ON ret.COD_CLIENTE=gt.COD_CLIENTE AND ret.CHAVE_CLIENTE=rt.CHAVE_CLIENTE
                          LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=gt.COD_UNIVEND
                          WHERE gt.COD_EMPRESA = $cod_empresa
                          AND DATE(gt.DAT_CADASTR) BETWEEN '$data_ini' AND '$data_fim' 
                          AND gt.COD_UNIVEND IN($lojasSelecionadas)
                          $andCelular
                          ORDER BY gt.DAT_CADASTR DESC
                          LIMIT $inicio,$itens_por_pagina";

                  $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);


                 //  fnEscreve($sql);
                  $count=0;
                  while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
                    {                             
                          $count++;

                          //fnEscreve()

                          if ($qrBuscaModulos['LOG_USADO'] == 2){
                                  $cliCadastrado = "<i class='fal fa-check text-success' aria-hidden='true'></i>";
                                  $reenvioTkn = "";
                          }else {
                                  $cliCadastrado = "<i class='fal fa-times text-danger' aria-hidden='true'></i>";
                                  $reenvioTkn = "<a class='btn btn-xs btn-info' onclick='reenvioTkn(".$qrBuscaModulos['COD_TOKEN'].")'><span class='fal fa-repeat'></span> Reenviar</a>";
                          }

                          if ($qrBuscaModulos['DES_STATUS_TOKEN'] == 1){
                                  $statusToken = "<i class='fal fa-times text-danger' aria-hidden='true'></i>";
                                  $reenvioTkn = "";
                          }else {
                                  $statusToken = "";
                          }

                          if ($qrBuscaModulos['BOUNCE'] == 1){
                                  $cliCadastrado = "<b class='text-danger'>e</b>";
                                  $reenvioTkn = "";
                          }


                          echo"
                                  <tr>
                                    <td>".$qrBuscaModulos['NOM_CLIENTE']."</td>
                                    <td>".$qrBuscaModulos['NUM_CGCECPF']."</td>
                                    <td>".$qrBuscaModulos['NUM_CELULAR']."</td>
                                    <td>".$qrBuscaModulos['NOM_FANTASI']."</td>
                                    <td>".fnDataFull($qrBuscaModulos['DAT_CADASTR'])."</td>
                                    <td>".$qrBuscaModulos['DES_MSG']."</td>
                                    <td>".$qrBuscaModulos['CHAVE_CLIENTE']."</td>    
                                    <td>".$cliCadastrado."</td>
                                    <td>".$statusToken."</td>
                                    <td>".$reenvioTkn."</td>
                                  </tr>

                                  "; 
                            } 
        
                break;
                  }
?>

