<?php 

	include '../_system/_functionsMain.php'; 	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$cod_status = fnLimpaCampoZero($_POST['COD_STATUS']);
    $cod_municipio = $_POST['COD_MUNICIPIO'];
    $dat_ini = fnDataSql($_POST['DAT_INI']);
    $dat_fim = fnDataSql($_POST['DAT_FIM']);

    $Arr_COD_MUNICIPIO = $cod_municipio;

    // fnEscreve($_POST['COD_MUNICIPIO']);

    if (isset($Arr_COD_MUNICIPIO)){
            //array das unidades de venda
            $countMunicipio = 0;
            if (isset($Arr_COD_MUNICIPIO)){
             for ($i=0;$i<count($Arr_COD_MUNICIPIO);$i++) 
             { 
                $str_municipio.=$Arr_COD_MUNICIPIO[$i].',';
                $countMunicipio ++; 
            } 
            $str_municipio = rtrim($str_municipio,',');
        }       
        $cod_municipio = ltrim($str_municipio,',');
    }else{
        $cod_municipio = "0";
    }

    // fnEscreve($cod_municipio);

    $cod_usucada = $_SESSION[SYS_COD_USUARIO];
	
	
	$andStatus = "";
    $andMunicipio = "";

    if($cod_status != 0){
        $andStatus = "AND EM.COD_STATUS = $cod_status";
    }

    if($cod_municipio != 0){
        $andMunicipio = "AND EM.COD_MUNICIPIO IN($cod_municipio)";
    }

    if($dat_ini != "" && $dat_fim != ""){
        $andData = "AND EM.DAT_INI BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";
    }else if($dat_ini != ""){
        $andData = "AND EM.DAT_INI >= '$dat_ini 00:00:00'";
    }else if($dat_fim != ""){
        $andData = "AND EM.DAT_INI <= '$dat_fim 23:59:59'";
    }else{
        $andData = "";
    }


	switch ($opcao) {

		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

                               
			$sql = "SELECT EM.COD_EMENDA,
                            NM.NOM_MUNICIPIO CIDADE,
                            TPE.DES_TIPO TIPO,
                            EM.DES_EMENDA DESCRICAO,
                            ORE.DES_ORGAO ORGAO,
                            STE.DES_STATUS STATUS,
                            CL2.NOM_CLIENTE AS NOM_BENEFICIARIO,
                            EM.DAT_INI,
                            EM.VAL_EMENDA
                    FROM EMENDA EM 
                    LEFT JOIN OBJETO_EMENDA OBE ON OBE.COD_OBJETO = EM.COD_OBJETO
                    LEFT JOIN ORGAO_EMENDA ORE ON ORE.COD_ORGAO = EM.COD_ORGAO
                    LEFT JOIN STATUS_EMENDA STE ON STE.COD_STATUS = EM.COD_STATUS
                    LEFT JOIN TIPO_EMENDA TPE ON TPE.COD_TIPO = EM.COD_TIPO
                    LEFT JOIN CLIENTES CL2 ON CL2.COD_CLIENTE = EM.COD_BENEFICIARIO
                    LEFT JOIN municipios NM ON NM.COD_MUNICIPIO = EM.COD_MUNICIPIO 
                    WHERE EM.COD_EMPRESA = $cod_empresa
                    AND EM.COD_EXCLUSA = 0
                    $andData
                    $andStatus
                    $andMunicipio";
					
			//fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			
			$arquivo = fopen($arquivoCaminho, 'w',0);
                
			while($headers=mysqli_fetch_field($arrayQuery)){
				$CABECHALHO[]=$headers->name;
			}
			fputcsv ($arquivo,$CABECHALHO,';','"','\n');
	
			while ($row=mysqli_fetch_assoc($arrayQuery)){  	
				$row[VAL_EMENDA] = fnValor($row['VAL_EMENDA'],2);
				//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
               // $textolimpo = json_decode($limpandostring, true);
                $array = array_map("utf8_decode", $row);
                fputcsv($arquivo, $array, ';', '"', '\n');	
			}
			fclose($arquivo);

		break;
		    
		case 'paginar':

            $sql = "SELECT * FROM EMENDA EM
                    WHERE COD_EMPRESA = $cod_empresa
                    $andData
                    $andStatus
                    $andMunicipio
                    ";

            //fnEscreve($sql);

            $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
            $totalitens_por_pagina = mysqli_num_rows($retorno);

            $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

            //variavel para calcular o início da visualização com base na página atual
            $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

            // Filtro por Grupo de Lojas
            //include "filtroGrupoLojas.php";


            $sql = "SELECT EM.COD_EMENDA,
                            NM.NOM_MUNICIPIO,
                            TPE.DES_TIPO,
                            EM.DES_EMENDA,
                            ORE.DES_ORGAO,
                            STE.DES_STATUS,
                            CL2.NOM_CLIENTE AS NOM_BENEFICIARIO,
                            EM.DAT_INI,
                            EM.VAL_EMENDA
                    FROM EMENDA EM 
                    LEFT JOIN OBJETO_EMENDA OBE ON OBE.COD_OBJETO = EM.COD_OBJETO
                    LEFT JOIN ORGAO_EMENDA ORE ON ORE.COD_ORGAO = EM.COD_ORGAO
                    LEFT JOIN STATUS_EMENDA STE ON STE.COD_STATUS = EM.COD_STATUS
                    LEFT JOIN TIPO_EMENDA TPE ON TPE.COD_TIPO = EM.COD_TIPO
                    LEFT JOIN CLIENTES CL2 ON CL2.COD_CLIENTE = EM.COD_BENEFICIARIO
                    LEFT JOIN municipios NM ON NM.COD_MUNICIPIO = EM.COD_MUNICIPIO 
                    WHERE EM.COD_EMPRESA = $cod_empresa
                    AND EM.COD_EXCLUSA = 0
                    $andData
                    $andStatus
                    $andMunicipio
                    LIMIT $inicio,$itens_por_pagina
                    ";

            // fnEscreve($sql);
            //fnTestesql(connTemp($cod_empresa,''),$sql);											
            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

            $count = 0;
            while ($qrApoia = mysqli_fetch_assoc($arrayQuery)) {

                $count++;
        ?>
                    <tr>
                        <td><small><?= $qrApoia['COD_EMENDA'] ?></small></td>
                        <td><small><?= $qrApoia['NOM_MUNICIPIO'] ?></small></td>
                        <td><small><?= $qrApoia['DES_EMENDA'] ?></small></td>
                        <td><small><?= $qrApoia['DES_TIPO'] ?></small></td>
                        <td><small><?= $qrApoia['DES_ORGAO'] ?></small></td>
                        <td><small><?= $qrApoia['DES_STATUS'] ?></small></td>
                        <td><small><?= $qrApoia['NOM_BENEFICIARIO'] ?></small></td>
                        <td><small><?= fnDataShort($qrApoia['DAT_INI']) ?></small></td>
                        <td class="text-left"><small><?= fnValor($qrApoia['VAL_EMENDA'], 2) ?></small></td>
                    </tr>
        <?php
            }									

	break; 		
	}
?>