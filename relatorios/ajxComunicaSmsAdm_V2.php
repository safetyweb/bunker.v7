<?php 

	include '../_system/_functionsMain.php';
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;

	$opcao = $_GET['opcao'];
	$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA_COMBO']);
  $dat_ini = fnDataSql($_POST['DAT_INI']);
  $dat_fim = fnDataSql($_POST['DAT_FIM']);
  $cod_segment = fnLimpaCampoZero($_REQUEST['COD_SEGMENT']);
  $cod_sistemas = fnLimpaCampoArray($_POST['COD_SISTEMAS']);

  $andData = "AND case when EL.DAT_AGENDAMENTO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' then '1'
	            when EL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND EL.LOG_TESTE = 'S' then '2'
	            ELSE '0' END IN (1,2)";

	if($cod_empresa != 0){
      $andEmpresa = "AND apar.COD_EMPRESA = $cod_empresa";
    }else{
      $andEmpresa = "";
    }

  if($cod_segment != 0){
      $andSegment = "AND EMP.COD_SEGMENT = $cod_segment";
  }else{
      $andSegment = "";
  }

  if($cod_sistemas != 0){
      $andSistemas = "AND EMP.COD_SISTEMAS IN($cod_sistemas)";
  }else{
      $andSistemas = "";
  }

    // fnEscreve('chega aqui');
    // fnEscreve($opcao);

    // exit();

	switch ($opcao) {

		case 'exportar':

			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/3_'.$nomeRel.'.csv';

			// fnEscreve($arquivo);
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo);

			$sqlEmp = "SELECT EMP.COD_EMPRESA, EMP.NOM_FANTASI, EMP.COD_SISTEMAS FROM senhas_parceiro apar
                        INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU 
                        INNER JOIN webtools.EMPRESAS EMP  ON EMP.COD_EMPRESA=apar.COD_EMPRESA
                        WHERE par.COD_TPCOM='2'
                        AND  apar.LOG_ATIVO='S'
                        $andEmpresa
                        $andSistemas
												$andSegment";

            $arrayEmp = mysqli_query($connAdm->connAdm(),$sqlEmp);
	        // exit();
	        
	        $count=0;
	        $array = array();
	        while ($qrEmp = mysqli_fetch_assoc($arrayEmp)){


				$sql = "SELECT
                        EL.COD_EMPRESA,
                        '' AS EMPRESA,
                        '$qrEmp[COD_SISTEMAS]' AS SISTEMAS,
                        MAX(EL.DAT_AGENDAMENTO) AS DAT_ENVIO,
                        SUM(EL.QTD_LISTA) AS QTD_LISTA,
                        SUM(CEM.QTD_SUCESSO) AS QTD_SUCESSO,
                        SUM(CEM.QTD_NRECEBIDO) AS QTD_NRECEBIDO,
                        SUM(CEM.QTD_EXCLUSAO) AS QTD_OPTOUT,
                        SUM(CEM.QTD_FALHA) AS QTD_FALHA,
                        SUM(CEM.QTD_AGUARADANDO) AS QTD_AGUARDANDO
                      FROM SMS_LOTE EL
                      LEFT JOIN CONTROLE_ENTREGA_SMS CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO AND CEM.cod_empresa=EL.COD_EMPRESA AND CEM.cod_campanha=EL.COD_CAMPANHA AND CEM.LOG_TESTE=EL.LOG_TESTE
											LEFT JOIN TEMPLATE_SMS TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE
											LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = EL.COD_CAMPANHA
                      WHERE EL.LOG_ENVIO = 'S'
                      AND EL.LOG_TESTE = 'N'
                      AND EL.COD_EMPRESA = $qrEmp[COD_EMPRESA]
                      $andData
                      GROUP BY EL.COD_EMPRESA
                      ORDER BY EL.COD_CONTROLE DESC
                        ";

        // fnEscreve($sql);
					
				$arrayQuery = mysqli_query(connTemp($qrEmp[COD_EMPRESA],''),$sql);

				$newRow = array();

				while($row = mysqli_fetch_assoc($arrayQuery)){
					  
					  $cont = 0;
					  foreach ($row as $objeto) {
						  
						if($cont == 1){

							array_push($newRow, $qrEmp[NOM_FANTASI]);

						}else if($cont == 2){

							$sqlSis = "SELECT DES_SISTEMA FROM SISTEMAS WHERE COD_SISTEMA IN($objeto)";
							$arraySis = mysqli_query($connAdm->connAdm(),$sqlSis);

							$sistemas = "";

							while($qrSis = mysqli_fetch_assoc($arraySis)){
								$sistemas .= $qrSis[DES_SISTEMA].", ";
							}

							$sistemas = ltrim(rtrim(trim($sistemas),","),",");


							array_push($newRow, $sistemas);

						}else{

							array_push($newRow, $objeto);

						}
						
						$cont++;
					  }
				}

				$array[] = $newRow;

			}
			
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				array_push($arrayColumnsNames, $row->name);
			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();
			
		break;
		
		default:

		break;

	}

?>