<?php
include '../../_system/_functionsMain.php';

function  FnAtualizaGEO($dados_array)
{
    if($dados_array['COD_EMPRESA']=='all')
    {
        $where='';
        $cod_empresa='';
    } 
    else
    {
        $where='WHERE and LOG_ATIVO="S" and cod_empresa='.$dados_array['COD_EMPRESA'];
        $cod_empresa="COD_EMPRESA=$dados_array[COD_EMPRESA] and";
    }
    
    if($dados_array['CEP']=='all'){$whereCEP="WHERE  $cod_empresa REPLACE(NUM_CEPOZOF,'-','') > 0  and  LAT ='0.0000000' AND LNG ='0.0000000'";}else{$whereCEP="WHERE $cod_empresa REPLACE(NUM_CEPOZOF,'-','') in('".$dados_array['CEP']."')";}
    if($dados_array['LIMITCONSULTA']=='all'){$limit="";}else{$limit="LIMIT $dados_array[LIMITCONSULTA]";}
   
        //empresa que iram fazer o atualização 
        $sqlempresa="SELECT * FROM empresas $where";       
       // $sqlempresa="SELECT * FROM empresas";
        $rwemprea= mysqli_query($dados_array['CONadm'],$sqlempresa);
        while ($rsemopresa= mysqli_fetch_assoc($rwemprea)){
            $contempmysql=connTemp($rsemopresa['COD_EMPRESA'],''); 
            
            
            $sqlclienteend="SELECT  COD_CLIENTE,Ltrim(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(DES_ENDEREC,'RUA',''),'AV.',''),
                                 'AVENIDA',''),'SITIO',''),'ALAMEDA',''),'AV',''),'PRAÇA','')
				,'Av.',''),'ESTRADA',''),'TRAVESSA',''),'Rua',''),'ROD.',''),'RODOVIA',''))  DES_ENDEREC, 
                              COD_ESTADOF, NOM_CIDADEC FROM clientes WHERE num_cepozof ='' AND des_enderec !=''";
           
            $rwcliend=mysqli_query($contempmysql, $sqlclienteend);
            while ($rscliend = mysqli_fetch_assoc($rwcliend))
            { 
                
                $SQlatualizaCEp="SELECT * FROM cepbr_cidade C
                                INNER JOIN cepbr_bairro B  ON C.id_cidade=B.id_cidade
                                INNER JOIN cepbr_estado S ON C.uf=S.uf
                                INNER JOIN cepbr_endereco E ON E.id_cidade=C.id_cidade AND E.id_bairro=B.id_bairro
                                INNER JOIN cepbr_geo G ON G.cep=E.cep 
                                WHERE E.logradouro='".$rscliend['DES_ENDEREC']."' "
                        . "AND C.id_cidade!='' and S.uf='".$rscliend['COD_ESTADOF']."' AND C.cidade ='".$rscliend['NOM_CIDADEC']."' ";
              echo '<br>BUSCACEP:  '.$SQlatualizaCEp.'<br>';
              
                $rscliendatualizacep=mysqli_fetch_assoc(mysqli_query($dados_array['connCEP'], $SQlatualizaCEp));
                if($rscliendatualizacep['id_cidade']!='')
                {
                    $sqlupcep="UPDATE clientes SET NUM_CEPOZOF='".$rscliendatualizacep['cep']."'
                            WHERE COD_CLIENTE=$rscliend[COD_CLIENTE]";
                    mysqli_query($contempmysql, $sqlupcep);     
                }    
            }
       
            
             //capturar dados do cliente
            $sqlclintes="SELECT COD_EMPRESA,REPLACE(NUM_CEPOZOF,'-','') as NUM_CEPOZOF,LAT,LNG FROM clientes  $whereCEP group by  REPLACE(NUM_CEPOZOF,'-','') $limit ";
            echo '<br>'.$sqlclintes.'<br>';
            $rwclientes=mysqli_query($contempmysql, $sqlclintes);
            while ($rsclientes= mysqli_fetch_assoc($rwclientes)){
                //capturar dados de cep
               $sqlCEP= "SELECT * FROM cepbr_cidade C
                INNER JOIN cepbr_bairro B  ON C.id_cidade=B.id_cidade
                INNER JOIN cepbr_estado S ON C.uf=S.uf
                INNER JOIN cepbr_endereco E ON E.id_cidade=C.id_cidade AND E.id_bairro=B.id_bairro
                INNER JOIN cepbr_geo G ON G.cep=E.cep
                WHERE E.cep=$rsclientes[NUM_CEPOZOF] OR C.cep=$rsclientes[NUM_CEPOZOF]  OR G.cep=$rsclientes[NUM_CEPOZOF]";
                $rwcepbase= mysqli_fetch_assoc(mysqli_query($dados_array['connCEP'], $sqlCEP));
                $dadoscep[]=$rwcepbase;
                
                if($rwcepbase['cep']=='')
                {
                    
                    //verificar se ja existe o cep na base de dados
                    $sqlblacklist="select COUNT(CEP) AS contador from cep_invalidos where CEP=$rsclientes[NUM_CEPOZOF]";
                    $rwblacklist= mysqli_fetch_assoc(mysqli_query($dados_array['connCEP'], $sqlblacklist));
                    if($rwblacklist['contador'] <= '0')
                    {  
                        $sqlinsert="insert into cep_invalidos (CEP)values('".$rsclientes[NUM_CEPOZOF]."')";
                        mysqli_query($dados_array['connCEP'], $sqlinsert);
                    }else{
                         $sqlblacklist1[]=$rwblacklist['contador'];
                    }
                } else {
                    //UPDATE
                    $sqlupdate="UPDATE clientes SET LAT='".$rwcepbase['latitude']."', 
                                                    LNG='".$rwcepbase['longitude']."' 
                            WHERE  REPLACE(NUM_CEPOZOF,'-','')=$rsclientes[NUM_CEPOZOF] and cod_empresa=".$rsemopresa['COD_EMPRESA'];
                    echo '<br>'.$sqlupdate.'<br>';
                    mysqli_query($contempmysql, $sqlupdate);  
                    
                }
            }
        }
        
    return array( 'SQL'=>$sqlclintes1);        
}

$dados_array=array(
                  'COD_EMPRESA'=>'136', 
                  'CEP'=>'all',
                  'LIMITCONSULTA'=>'all',
                  'connCEP'=>$DADOS_CEP->connUser(),
                  'CONadm'=>$connAdm->connAdm()
                    );


$teste=FnAtualizaGEO($dados_array);
echo '<pre>';
print_r($teste);
echo '</pre>';
/*
 SELECT * FROM cepbr_cidade C
INNER JOIN cepbr_bairro B  ON C.id_cidade=B.id_cidade
INNER JOIN cepbr_estado S ON C.uf=S.uf
INNER JOIN cepbr_endereco E ON E.id_cidade=C.id_cidade AND E.id_bairro=B.id_bairro
INNER JOIN cepbr_geo G ON G.cep=E.cep
WHERE E.cep='04144020' 
 */