<?php
function TrocadeCartao ($dados) {
 
    require_once('../../../_system/Class_conn.php');
    include '../../../wsmarka/func/function.php';
/*
         <lin:cartaoantigo>?</lin:cartaoantigo>
         <lin:cartaonovo>?</lin:cartaonovo>
        <lin:motivo>?</lin:motivo>

 */

    // limpa doc
    @$cartaoantigo=$dados->cartaoantigo;
    @$cartaonovo=$dados->cartaonovo;
    @$motivo= fnAcentos($dados->motivo);    
  // return array('TrocadeCartaoResult'=>'cartaoantigo-'.@$cartaoantigo.'cartaonovo-'.$cartaonovo.'motivo-'.$motivo);  
    //=========================================================================
    $connAdmvar=$connAdm->connAdm();
   
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','".$dados->dadoslogin->idcliente."','','')";
    $buscauser=mysqli_query($connAdmvar,$sql);
    $row = mysqli_fetch_assoc($buscauser);
    mysqli_next_result($connAdmvar);
   
        
    if(!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS']))
    {
        return array('TrocadeCartaoResult'=>'Usuario ou senha invalido!');  
    }else{
        $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
        $connUservar=$connUser->connUser();
    } 
      $arrylog=array(  'cod_usuario'=>$row['COD_USUARIO'],
                     'login'=>$dados->dadoslogin->login,
                     'cod_empresa'=>$row['COD_EMPRESA'],
                     'pdv'=>'0',
                     'cupom'=>'0',
                     'idloja'=>$dados->dadoslogin->idloja,
                     'idmaquina'=>$dados->dadoslogin->idmaquina,
                     'cpf'=>$cartaonovo,     
                     'xml'=>addslashes(file_get_contents("php://input")),
                     'tables'=>'origemtrocacartao',
                     'conn'=>$connUservar
                 );
    $cod_log=fngravalogxml($arrylog);      
                
    //se o cartão ja for utilizado exibe a messagem
    //Cartão XXXXX ja utilizado pelo cliente XXXXX
    
    //Cartão fora da faixa cadastrada
    //Cartão XXX não encontrado na faixa de cartão.
    
    //traca ok
    //Cartão 15 já foi objeto de troca em 22/07/2019 11:10:35
    
    $arrayconsulta=array('ConnB'=>$connUservar,
                        'conn'=>$connAdmvar,
                        'database'=>$row['NOM_DATABASE'],
                        'empresa'=>$row['COD_EMPRESA'],
                        'fase'=> '',
                        'cartao'=>$cartaoantigo,
                        'cpf'=>'',                      
                        'login'=>$dados->dadoslogin->login,
                        'senha'=>$dados->dadoslogin->senha,
                        'idloja'=>$dados->dadoslogin->idloja,
                        'idmaquina'=>$dados->dadoslogin->idmaquina,
                        'codvendedor'=>'',
                        'nomevendedor'=>'',
                        'COD_USUARIO'=>$row['COD_USUARIO'],
                        'pagina'=>'BuscaConsumidor',
                        'COD_UNIVEND'=>$dados->dadoslogin->idloja,
                        'venda'=>'nao',
                        'generico'=>'',
                        'LOG_WS'=>$row['LOG_WS']
                        );  
            $dadosbase[]=fn_consultaBase($arrayconsulta); 
            
           if($dadosbase[0]['COD_CLIENTE']<=0)
            {
              Grava_log_cad($connUservar,$LOG,'Cliente nao cadastrado');
              return array('TrocadeCartaoResult' =>'Cliente nao cadastrado');   
            } 
            //busca de cartão ja usado
            $arrayconsulta=array('ConnB'=>$connUservar,
                        'conn'=>$connAdmvar,
                        'database'=>$row['NOM_DATABASE'],
                        'empresa'=>$row['COD_EMPRESA'],
                        'fase'=> '',
                        'cartao'=>$cartaonovo,
                        'cpf'=>'',                      
                        'login'=>$dados->dadoslogin->login,
                        'senha'=>$dados->dadoslogin->senha,
                        'idloja'=>$dados->dadoslogin->idloja,
                        'idmaquina'=>$dados->dadoslogin->idmaquina,
                        'codvendedor'=>'',
                        'nomevendedor'=>'',
                        'COD_USUARIO'=>$row['COD_USUARIO'],
                        'pagina'=>'BuscaConsumidor',
                        'COD_UNIVEND'=>$dados->dadoslogin->idloja,
                        'venda'=>'nao',
                        'generico'=>'',
                        'LOG_WS'=>$row['LOG_WS']
                        );  
            $dadosbaseJa[]=fn_consultaBase($arrayconsulta);   
            
 
        //atualiza por cpf
    if($row['COD_CHAVECO']==1 || $row['COD_CHAVECO']=='5'){
 
                $geracartao="select  
                                   (SELECT NUM_TAMANHO FROM LOTECARTAO A WHERE A.COD_EMPRESA=geracartao.COD_EMPRESA AND A.COD_LOTCARTAO=geracartao.COD_LOTCARTAO) AS NUM_TAMANHO,
                                    cod_cartao,log_usado,num_cartao,count(*) contador  from geracartao where num_cartao='$cartaonovo'  and cod_empresa=".$row['COD_EMPRESA'];
                $rsgeracartao=mysqli_fetch_assoc(mysqli_query($connUservar,$geracartao));

               if(($rsgeracartao['contador']==0) && 
                  ($row['COD_CHAVECO']=='5') && 
                  (strlen($cartaonovo)!=11)&&
                  (strlen($cartaonovo)!=14))
               {
                   Grava_log_msgxml($connUservar,'msg_trocacartao',$cod_log,'Cartão '.$cartaonovo.' não encontrado na faixa de cartão.');
                   return array('TrocadeCartaoResult' => 'Cartão '.$cartaonovo.' não encontrado na faixa de cartão.');    
               }   
                          
               //====================================================================================
               if($row['COD_CHAVECO']=='5' && strlen($cartaonovo) == $rsgeracartao['NUM_TAMANHO'] )
               {
             
       
                   if($dadosbase[0]['cpf'] != '' || $dadosbase[0]['cartao'] !='')
                   {  
 
                       if($cartaonovo==$dadosbase[0]['cartao'])
                        {
                          Grava_log_msgxml($connUservar,'msg_trocacartao',$cod_log,'Cartão '.$cartaonovo.' ja utilizado pelo cliente '.$dadosbaseJa[0]['nome']);
                          return array('TrocadeCartaoResult' => 'Cartão '.$cartaonovo.' ja utilizado pelo cliente '.$dadosbaseJa[0]['nome']);   
                        }
                     
     
                       if($rsgeracartao['contador'] > 0 && $rsgeracartao['log_usado']=='N') 
                       {                           
                           //novo cartao - insere
                           //update na tabela de cartoes
                           $updatecartao="update  geracartao set log_usado='S',cod_USUALTE=".$row['COD_USUARIO']." where cod_empresa='".$row['COD_EMPRESA']."' and  num_cartao=".$cartaonovo; 
                         mysqli_fetch_assoc(mysqli_query($connUservar,$updatecartao));
                            Grava_log_msgxml($connUservar,'msg_trocacartao',$cod_log,'OK');
                         
                       }elseif ($rsgeracartao['contador']==0) 
                       { 
                           //cartao inválido - não existe na base
                       Grava_log_msgxml($connUservar,'msg_trocacartao',$cod_log,'Cartão '.$cartaonovo.' não encontrado na faixa de cartão.');  
                         return array('TrocadeCartaoResult' => 'Cartão '.$cartaonovo.' não encontrado na faixa de cartão.');                           
                       }elseif ($rsgeracartao['contador'] > 0 && $rsgeracartao['log_usado']=='S' ){
                         //cartao válido - mas já utilizado                           
                         Grava_log_msgxml($connUservar,'msg_trocacartao',$cod_log,'Cartão '.$cartaonovo.' ja utilizado pelo cliente '.$dadosbaseJa[0]['nome']);  
                         return array('TrocadeCartaoResult'=>'Cartão '.$cartaonovo.' ja utilizado pelo cliente '.$dadosbaseJa[0]['nome']);                          
                       }
                   }
                  
                }elseif (strlen($cartaonovo) != $rsgeracartao['NUM_TAMANHO']) {
                    
                     Grava_log_msgxml($connUservar,'msg_trocacartao',$cod_log,'Cartão '.$cartaonovo.' não encontrado na faixa de cartão.');      
                     return array('TrocadeCartaoResult' => 'Cartão '.$cartaonovo.' não encontrado na faixa de cartão.');                           
                            
               }    

                                        $sql1 = " update clientes  
                                                                   set 
                                                                       NUM_CARTAO='".$cartaonovo."'
                                                                       where  COD_EMPRESA=".$row['COD_EMPRESA']." and COD_CLIENTE=".$dadosbase[0]['COD_CLIENTE'];



                                       $arraP1=mysqli_query($connUservar,$sql1);
                                       if (!$arraP1)
                                       {
                                           mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                                           try {mysqli_query($connUservar,$sql1);} 
                                           catch (mysqli_sql_exception $e) {$msgsql = $e;} 

                                           $msg="Erro ao atualizar cadastro $msgsql";
                                           $xamls= addslashes($msg);
                                            Grava_log_msgxml($connUservar,'msg_trocacartao',$cod_log,$xamls);

                                       } else {
                                           //inserindo o motivo                                          
                                          
                                    $insertomotivo= "insert into  historico_cartao (
                                                                            NUM_CARTAO_ANT,
                                                                            NUM_CARTAO_NOV,
                                                                            COD_EMPRESA,
                                                                            COD_CLIENTE,
                                                                            DAT_CADASTR,
                                                                            MOTIVO)VALUES(
                                                                            '".$cartaoantigo."',
                                                                            '".$cartaonovo."',
                                                                            '".$row['COD_EMPRESA']."',
                                                                            '".$dadosbase[0]['COD_CLIENTE']."',
                                                                            '".date('Y-m-d H:i:s')."',
                                                                            '". addslashes($motivo)."'
                                                                            )";
                                               mysqli_query($connUservar, $insertomotivo);
                                               return array('TrocadeCartaoResult'=> 'OK');
                                       }

                //fim da atualiza por cpf                                                    
    }

}