
<?php 

include '_system/_functionsMain.php'; 

    //echo fnDebug('true');

$opcao = $_GET['opcao'];
$cod_empresa = fnDecode($_GET['id']);
$cod_campanha = fnDecode($_GET['idC']);
$cod_cupom = fnDecode($_GET['idCp']);     

switch ($opcao) {
    case 'exportar':
        
        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = 'media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
    

                       
        $sql = "SELECT 
                    B.COD_CLIENTE,
                    B.NOM_CLIENTE,
                    NUM_CUPOM 
                FROM geracupom A, clientes B
                WHERE A.COD_EMPRESA = $cod_empresa AND
                A.COD_CAMPANHA = $cod_campanha AND
                A.cod_cupom= $cod_cupom AND 
                A.COD_CLIENTE=B.COD_CLIENTE AND 
                A.log_sorteado='S'";
                  
                
        // fnEscreve($sql);
                
        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);         
            
        $arquivo = fopen($arquivoCaminho, 'w',0);
                
        while($headers=mysqli_fetch_field($arrayQuery)){
             $CABECHALHO[]=$headers->name;
        }
        fputcsv ($arquivo,$CABECHALHO,';','"','\n');
      
        while ($row=mysqli_fetch_assoc($arrayQuery)){   
            
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"', '\n');  
        }
        fclose($arquivo);
       
    break;
}
?>