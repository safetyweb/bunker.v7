<?php
include '../../_system/_functionsMain.php';


function  FnAtualizaGEO($dados_array)
{
    $contador=0;
    if($dados_array['COD_EMPRESA']=='all')
    {
        $where='';
        $cod_empresa='';
    } 
    else
    {
        $where='WHERE  LOG_ATIVO="S" and cod_empresa='.$dados_array['COD_EMPRESA'];
        $cod_empresa="COD_EMPRESA=$dados_array[COD_EMPRESA] and";
    }
    
    if($dados_array['CEP']=='all'){$whereCEP="WHERE  $cod_empresa REPLACE(REPLACE(REPLACE(NUM_CEPOZOF, '-', ''), ' ', ''), '.', '')  > 0 ";}else{$whereCEP="WHERE $cod_empresa REPLACE(REPLACE(REPLACE(NUM_CEPOZOF, '-', ''), ' ', ''), '.', '')  in('".$dados_array['CEP']."')";}
    if($dados_array['LIMITCONSULTA']=='all'){$limit="";}else{$limit="LIMIT $batch_size OFFSET $offset ";}
   
        //empresa que iram fazer o atualização 
        $sqlempresa="SELECT * FROM empresas $where";  
        // $sqlempresa="SELECT * FROM empresas";
        $rwemprea= mysqli_query($dados_array['CONadm'],$sqlempresa);
        while ($rsemopresa= mysqli_fetch_assoc($rwemprea)){
            $contempmysql=connTemp($rsemopresa['COD_EMPRESA'],''); 
           
                 ob_start();
                    //capturar dados do cliente group by  REPLACE(NUM_CEPOZOF,'-','')
                    $sqlclintes="SELECT COD_EMPRESA,REPLACE(REPLACE(REPLACE(NUM_CEPOZOF, '-', ''), ' ', ''), '.', '')  as NUM_CEPOZOF,LAT,LNG,DES_ENDEREC,DES_BAIRROC,COD_ESTADOF FROM clientes  $whereCEP  $limit ";
                    echo $sqlclintes.'<br>';
                    $rwclientes=mysqli_query($contempmysql, $sqlclintes);
                  
                    while ($rsclientes= mysqli_fetch_assoc($rwclientes)){
                        //capturar dados de cep
                       $sqlCEP= "SELECT * FROM cepbr_cidade C
                        INNER JOIN cepbr_bairro B  ON C.id_cidade=B.id_cidade
                        INNER JOIN cepbr_estado S ON C.uf=S.uf
                        INNER JOIN cepbr_endereco E ON E.id_cidade=C.id_cidade AND E.id_bairro=B.id_bairro
                        INNER JOIN cepbr_geo G ON G.cep=E.cep
                        WHERE E.cep=$rsclientes[NUM_CEPOZOF] OR C.cep=$rsclientes[NUM_CEPOZOF]  OR G.cep=$rsclientes[NUM_CEPOZOF]";
                        $rs_controle=mysqli_query($dados_array['connCEP'], $sqlCEP);
                        $rwcepbase= mysqli_fetch_assoc($rs_controle);
                        //UPDATE
                        //  DES_BAIRROC=CASE WHEN  DES_BAIRROC = '' OR  DES_BAIRROC IS NULL THEN '".$rwcepbase['bairro']."' ELSE DES_BAIRROC END,
                        $sqlupdate="UPDATE clientes SET LAT='".$rwcepbase['latitude']."', 
                                                    LNG='".$rwcepbase['longitude']."',
                                                    DES_ENDEREC=CASE WHEN  DES_ENDEREC = '' OR  DES_ENDEREC IS NULL THEN '". addslashes($rwcepbase['logradouro'])."' ELSE DES_ENDEREC END, 
                                                    DES_BAIRROC='".addslashes($rwcepbase['bairro'])."',
                                                    COD_ESTADOF=CASE WHEN  COD_ESTADOF = '' OR  COD_ESTADOF IS NULL THEN '".$rwcepbase['uf']."' ELSE COD_ESTADOF END,
                                                    NOM_CIDADEC = CASE WHEN  NOM_CIDADEC = '' OR  NOM_CIDADEC IS NULL THEN '".addslashes($rwcepbase['cidade'])."' ELSE NOM_CIDADEC END   
                                      WHERE  REPLACE(REPLACE(REPLACE(NUM_CEPOZOF, '-', ''), ' ', ''), '.', '')='".$rsclientes[NUM_CEPOZOF]."'" ;

                        mysqli_query($contempmysql, $sqlupdate);  
                        
                        $contador++;
                    }
                    
                    
              // Incrementa o offset para pegar o próximo lote
                ob_end_flush();
                ob_flush();
                flush();
         
            
            
        }
        
    return array( 'SQL'=>$contador);        
}

$dados_array=array(
                  'COD_EMPRESA'=>'502', 
                  'CEP'=>'all',
                  'LIMITCONSULTA'=>'all',
                  'connCEP'=>$DADOS_CEP->connUser(),
                  'CONadm'=>$connAdm->connAdm()
                    );


$teste=FnAtualizaGEO($dados_array);
echo '<pre>';
print_r($teste);
echo '</pre>';

echo $teste;
