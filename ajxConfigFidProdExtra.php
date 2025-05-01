<?php 

	include '_system/_functionsMain.php'; 
	require_once 'js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$cod_univend = $_POST['COD_UNIVEND'];
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$lojasSelecionadas = $_POST['LOJAS'];
	$nom_cliente = $_POST['NOM_CLIENTE'];
	$num_cartao = $_POST['NUM_CARTAO'];	
	$cod_campanha = $_POST['COD_CAMPANHA'];
    $andFiltro = fnLimpaCampo($_POST['AND_FILTRO']);
    $selecionados = fnLimpaCampo($_POST['SELECIONADOS']);

	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	
	if (strlen($cod_univend ) == 0){
		$cod_univend = "9999"; 
	}	
	//faz pesquisa por revenda (geral)
	if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}	
	
	switch ($opcao) {     
		case 'paginar':

    		$sql="select count(*) as CONTADOR from VANTAGEMEXTRAFAIXA A
                    LEFT join CAMPANHA B on A.COD_CAMPANHA= B.COD_CAMPANHA
                    LEFT join produtocliente P on A.COD_PRODUTO = P.COD_PRODUTO
                    where A.COD_CAMPANHA = '".$cod_campanha."' AND A.TIP_FAIXAS = 'PRD'
                    ".$andFiltro."
                    order by P.DES_PRODUTO ";   

            //fnEscreve($sql);
            
            $retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
            $total_itens_por_pagina = mysqli_fetch_assoc($retorno);
            
            $numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);                                                          
                    
            //variavel para calcular o início da visualização com base na página atual
            $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;                                                    
        
            $sql="SELECT A.*,B.DES_CAMPANHA as NOM_CAMPANHA,P.DES_PRODUTO,P.COD_EXTERNO, 
                    IFNULL(P.COD_PRODUTO,0) as COD_PRODUTO from VANTAGEMEXTRAFAIXA A
                    LEFT join CAMPANHA B on A.COD_CAMPANHA= B.COD_CAMPANHA
                    LEFT join produtocliente P on A.COD_PRODUTO = P.COD_PRODUTO
                    where A.COD_CAMPANHA = '".$cod_campanha."' AND A.TIP_FAIXAS = 'PRD'
                    ".$andFiltro."
                    order by P.DES_PRODUTO limit $inicio,$itens_por_pagina";
            
            //fnEscreve($sql);
            $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

            $count=0;
            $countLinha = 1;
            while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery))
            {                                                       
                $count++;
                
                if ($qrBuscaCampanhaExtra['TIP_FAIXEXT'] == "ABS") { $tipoGanho = $nom_tpcampa; }
                else { $tipoGanho = "%"; }
        
                echo"
                    <tr>
                      <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
                      <td>".$qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA']."</td>
                      <td>".$qrBuscaCampanhaExtra['COD_EXTERNO']."</td>
                      <td>".$qrBuscaCampanhaExtra['NOM_CAMPANHA']."</td>
                      <td><a href='action.do?mod=".fnEncode(1046)."&id=".fnEncode($cod_empresa)."&idP=".$qrBuscaCampanhaExtra['COD_EXTERNO']."'>".$qrBuscaCampanhaExtra['DES_PRODUTO']."</a></td>
                      <td>".number_format ($qrBuscaCampanhaExtra['QTD_FAIXEXT'],2,",",".")." ".$tipoGanho."</td>                                                            
                      <td>".$qrBuscaCampanhaExtra['QTD_FAIXLIM']."</td>
                    </tr>
                    <input type='hidden' id='ret_COD_GERAL_".$count."' value='".$qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA']."'>
                    <input type='hidden' id='ret_VAL_FAIXINI_".$count."' value='".number_format ($qrBuscaCampanhaExtra['VAL_FAIXINI'],2,",",".")."'>
                    <input type='hidden' id='ret_VAL_FAIXFIM_".$count."' value='".number_format ($qrBuscaCampanhaExtra['VAL_FAIXFIM'],2,",",".")."'>
                    <input type='hidden' id='ret_QTD_FAIXEXT_".$count."' value='".number_format ($qrBuscaCampanhaExtra['QTD_FAIXEXT'],2,",",".")."'>
                    <input type='hidden' id='ret_TIP_FAIXEXT_".$count."' value='".$qrBuscaCampanhaExtra['TIP_FAIXEXT']."'>
                    <input type='hidden' id='ret_TIP_CALCULO_".$count."' value='".$qrBuscaCampanhaExtra['TIP_CALCULO']."'>
                    <input type='hidden' id='ret_QTD_FAIXLIM_".$count."' value='".$qrBuscaCampanhaExtra['QTD_FAIXLIM']."'>
                    <input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrBuscaCampanhaExtra['COD_PRODUTO']."'>
                    <input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrBuscaCampanhaExtra['DES_PRODUTO']."'>
                    "; 
                    
                    $countLinha++;
            }											

													
            break;

        case 'exportAll':
            
            $nomeRel = $_GET['nomeRel'];
            $arquivoCaminho = 'media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

            $sql = "SELECT A.*,B.DES_CAMPANHA as NOM_CAMPANHA,P.DES_PRODUTO,P.COD_EXTERNO, 
                            IFNULL(P.COD_PRODUTO,0) as COD_PRODUTO from VANTAGEMEXTRAFAIXA A
                            LEFT join CAMPANHA B on A.COD_CAMPANHA= B.COD_CAMPANHA
                            LEFT join produtocliente P on A.COD_PRODUTO = P.COD_PRODUTO
                            where A.COD_CAMPANHA = '".$cod_campanha."' AND A.TIP_FAIXAS = 'PRD'
                            ".$andFiltro."
                            order by P.DES_PRODUTO
            ";

            $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);         
            
            $arquivo = fopen($arquivoCaminho, 'w',0);
            
            while($headers=mysqli_fetch_field($arrayQuery)){
                $CABECHALHO[]=$headers->name;
            }
            fputcsv ($arquivo,$CABECHALHO,';','"','\n');

            while ($row=mysqli_fetch_assoc($arrayQuery)){   
                
                // $row[QTD_FAIXEXT] = fnValor($row['QTD_FAIXEXT'],2);
                // $row[ULTIMA_COMPRA] = fnDataFull($row['ULTIMA_COMPRA']);

                $array = array_map("utf8_decode", $row);
                fputcsv($arquivo, $array, ';', '"', '\n');  
            }
            fclose($arquivo);

            break;

        case 'exportSele':
            
            $nomeRel = $_GET['nomeRel'];
            $arquivoCaminho = 'media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

            $sql = "SELECT A.*,B.DES_CAMPANHA as NOM_CAMPANHA,P.DES_PRODUTO,P.COD_EXTERNO, 
                            IFNULL(P.COD_PRODUTO,0) as COD_PRODUTO from VANTAGEMEXTRAFAIXA A
                            LEFT join CAMPANHA B on A.COD_CAMPANHA= B.COD_CAMPANHA
                            LEFT join produtocliente P on A.COD_PRODUTO = P.COD_PRODUTO
                            where
                            A.COD_VANTAGEMFAIXA IN ($selecionados)
                            AND A.COD_CAMPANHA = '".$cod_campanha."' AND A.TIP_FAIXAS = 'PRD'
                            ".$andFiltro."
                            order by P.DES_PRODUTO
            ";
            
            $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);         
            
            $arquivo = fopen($arquivoCaminho, 'w',0);
            
            while($headers=mysqli_fetch_field($arrayQuery)){
                $CABECHALHO[]=$headers->name;
            }
            fputcsv ($arquivo,$CABECHALHO,';','"','\n');

            while ($row=mysqli_fetch_assoc($arrayQuery)){   
                
                // $row[QTD_FAIXEXT] = fnValor($row['QTD_FAIXEXT'],2);
                // $row[ULTIMA_COMPRA] = fnDataFull($row['ULTIMA_COMPRA']);

                $array = array_map("utf8_decode", $row);
                fputcsv($arquivo, $array, ';', '"', '\n');  
            }
            fclose($arquivo);


            break;
	}
?>