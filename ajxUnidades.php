<?php 

	include '_system/_functionsMain.php'; 
	require_once 'js/plugins/Spout/Autoloader/autoload.php';
fnDebug(true);	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$nomeArquivo = $cod_empresa.'_'.$nomeRel;
			$arquivo = 'media/excel/'.$nomeArquivo.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 
			       
			$sql = "SELECT UNV.COD_UNIVEND,
							UNV.COD_EXTERNO,
							UNV.NUM_CGCECPF AS CNPJ,
							UNV.NOM_UNIVEND,
							UNV.NOM_FANTASI,
							UNV.NOM_RESPONS,
							UNV.DES_ENDEREC,
							UNV.NUM_ENDEREC,
							UNV.DES_BAIRROC,
							UNV.NOM_CIDADEC,
							UNV.COD_ESTADOF,
							UNV.NUM_TELEFON,
							UNV.NUM_CELULAR,
							UNV.DAT_CADASTR,
							UNV.LOG_COBRANCA AS COBRANCA_ATIVA,
							UNV.LOG_ESTATUS AS ATIVA
					FROM UNIDADEVENDA AS UNV
					WHERE UNV.COD_EMPRESA = $cod_empresa
					ORDER BY UNV.NOM_FANTASI";
					
			fnEscreve($sql);
			$arrayQuery = mysqli_query($connAdm->connAdm(),trim($sql));

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				$newRow = array();
				  
				$cont = 0;
				foreach ($row as $objeto) {

					if($cont == 13){
						array_push($newRow, fnDataShort($objeto));
					}else{
						array_push($newRow, $objeto);
					}

					$cont++;

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


			//zipArquivos(array($arquivo),$nomeArquivo,"123");


		break;      
		 		
	}

	function zipArquivos($arquivos = array(),$nomeArquivo = "arquivo_zip",$pwd = ""){
		$zip = new ZipArchive();

		if($zip->open('media/zip/'.$nomeArquivo.'.zip', ZIPARCHIVE::CREATE)) {

			foreach($arquivos as $arquivo){
				$zip->addFile($arquivo);
			}
			if ($pwd != ""){
			//	$zip->setPassword($pwd);
				//$zip->setEncryptionName('test.txt', ZipArchive::EM_AES_256, 'passw0rd');
			}
			$zip->close();

		}else{
			return false;
		}
	}
?>