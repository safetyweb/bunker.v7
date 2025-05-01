<?php
function InserirVenda ($dados) {
    require_once('../../../_system/Class_conn.php');
    include '../../../wsmarka/func/function.php';
    
       
          /* $dataformat1=date('Y-m-d', strtotime(str_replace('/','-',$dados->venda->data))); 
           $datahora1=DateTime::createFromFormat('Y-m-d', $dataformat1);
           $datahora1=$datahora1->format('Y-m-d');
           $date1 = new DateTime($datahora1);
           $date2 = new DateTime(date('Y-m-d'));
           $interval = $date1->diff($date2);               
            if($interval->days >= '2')
            {    
                return    array('InserirVendaResult'=>array(
                                                            'nome'=> 'OK',                                          
                                                            'saldocreditos'=>'0,00',
                                                            'saldoresgate'=>'0,00',
                                                            'comprovante'=>'0',
                                                            'comprovante_resgate'=>'0',
                                                            'url'=>'OK',
                                                            'exibesaldopontos'=>1,
                                                            'msgerro'=>'OK'
                                                            )
                    );
            }    
    */
    
       $connAdmVar=$connAdm->connAdm();
       $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','".$dados->dadoslogin->idcliente."','','')";
       $buscauser=mysqli_query($connAdmVar,$sql);
       $row = mysqli_fetch_assoc($buscauser);
       
       mysqli_next_result($connAdmVar);
       
       $cartao= fnlimpaCPF($dados->venda->cartao);
        $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><InserirVendaResult></InserirVendaResult>");
   
       //verifica se o usuario esta ativo.
        if($row['LOG_ESTATUS']=='N'){
            return array('InserirVendaResult'=>array('msgerro' => 'Usuario foi desabilitado!'));  
        }
        //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
        if ($row['COD_EMPRESA'] != $dados->dadoslogin->idcliente)
        {
           return  array('InserirVendaResult'=>array('msgerro'=>'Id_cliente não confere com o cadastro!')); 

        }   
        //verifica se a empresa esta ativa
        if($row['LOG_ATIVO']!='S'){  
             return array('InserirVendaResult'=>array('msgerro' => 'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[')); 
        } 
       //Autenticação
        if(!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS'])){
            return array('InserirVendaResult'=>array('msgerro'=>'Usuario ou senha invalido!'));  
        } 
        
        if ($row['COD_EMPRESA'] != $dados->dadoslogin->idcliente){
             return array('InserirVendaResult'=>array('msgerro' => 'Empresa nao confere!'));
        }
        //Numero de decimal da integradora
        $dec=$row['NUM_DECIMAIS'];
        if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;}
        //Url Extrato
        $urlextrato=fnEncode($dados->dadoslogin->login.';'
                            .$dados->dadoslogin->senha.';'
                            .$dados->dadoslogin->idloja.';'
                            .$dados->dadoslogin->idmaquina.';'
                            .$row['COD_EMPRESA'].';'
                            .'0;'
                            .'0;'    
                            .$cartao
                             );
       
         $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
         $cod_men=fnmemoria($connUser->connUser(),'true',$dados->dadoslogin->login,'insere venda',$row['COD_EMPRESA']);  

         
         //Grava Log
         //Grava Log de envio do xml
         
        if($cartao=='' || $cartao=='?')
        {                   
             $cpf_vazio='0';
             $cartao='0';
        } 
        
        
         
        $arrylog=array(  'cod_usuario'=>$row['COD_USUARIO'],
                         'login'=>$dados->dadoslogin->login,
                         'cod_empresa'=>$row['COD_EMPRESA'],
                         'pdv'=>$dados->venda->id_venda,
                         'idloja'=>$dados->dadoslogin->idloja,
                         'idmaquina'=>$dados->dadoslogin->idmaquina,
                         'cpf'=>$cartao,     
                         'xml'=>addslashes(file_get_contents("php://input")),
                         'tables'=>'origemvenda',
                         'cupom'=>$dados->venda->cupom,   
                         'conn'=>$connUser->connUser()
                     );
        $cod_log=fngravalogxml($arrylog); 
    
       
       /* if($cartao=='01734200014')
        {                   
            Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'CPF VAZIO','OK'); 
            return array('InserirVendaResult'=>array('msgerro'=> $cod_log));    
        } */
        
            mysqli_next_result($connAdmVar); 
            $NOM_atendente= addslashes($dados->venda->atendente);
            $NOM_atendente=str_replace("'","",$NOM_atendente);
             $cod_atendente=fnatendente($connAdmVar,
                                        $NOM_atendente,
                                         $dados->dadoslogin->idcliente,
                                        $dados->dadoslogin->idloja,
                                        $NOM_atendente);
            mysqli_next_result($connAdmVar);             
            $NOM_USUARIO= addslashes(fnAcentos($dados->venda->vendedor));
            $NOM_USUARIO=str_replace("'","",$NOM_USUARIO);
            $cod_vendedor=fnVendedor($connAdmVar,
                                      $NOM_USUARIO,
                                      $dados->dadoslogin->idcliente,
                                      $dados->dadoslogin->idloja,
                                      $NOM_USUARIO);
              mysqli_next_result($connAdmVar);
                //verifica se o profissão existe
                        $formap=utf8_encode(fnAcentos(addslashes($dados->venda->formapagamento)));
                        $form= "call SP_VERIFICA_FORMAPAGAMENTO(".$row['COD_EMPRESA'].",'$formap');";
                        $formaret=mysqli_query($connUser->connUser(),$form);
                        $formapretorno=mysqli_fetch_assoc($formaret);  
                       
                        
                        mysqli_next_result($connUser->connUser());  
         //===============================================================          
            //data hoa
            $dataformat=date('Y-m-d', strtotime(str_replace('/','-',$dados->venda->data))); 
            $datahora=DateTime::createFromFormat('Y-m-d', $dataformat);
            if($datahora===false)
            {
             Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Data deve ser ANO-MES-DIA','OK');
             return array('InserirVendaResult'=>array('msgerro'=>'Data deve ser ANO-MES-DIA')); 
            } else {$datahora=$datahora->format('Y-m-d');} 
            
            //verifica se  a data e maior que esta sendo enviada 
            /*if( fndate($datahora) > date("Y-m-d"))
            { 
             Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Data da venda maior que a data atual!','OK');
             return array('InserirVendaResult'=>array('msgerro' =>'Data da venda maior que a data atual!')); 
            } */   
            //Consulta de cliente   
               //busca cliente  na base de dados    
           $arraydadosbusca=array('empresa'=>$row['COD_EMPRESA'],
                                  'cartao'=>$cartao,
                                  'cpf'=>$cartao,
                                  'venda'=>'venda',
                                  'ConnB'=>$connUser->connUser());
           $cliente_cod= fn_consultaBase($arraydadosbusca);        
    // return array('InserirVendaResult'=>array('msgerro' => var_export($cliente_cod,TRUE)));
           //verifica se o cliente retornou 

           if($cliente_cod['COD_CLIENTE'] == 0 ||$cliente_cod['COD_CLIENTE']=='' )
           {
               Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Cliente não cadastrado','OK');        
               return array('InserirVendaResult'=>array('msgerro' => "Cartão  $cartao não encontrado"));
           } 
           //======================================
            ////Loja não cadastrada 
           
            $lojas=fnconsultaLoja($connAdmVar,
                                  $connUser->connUser(),
                                    $dados->dadoslogin->idloja,
                                    $dados->dadoslogin->idmaquina,
                                    $row['COD_EMPRESA']);
           
              
                
            if($lojas['msg']!=1)
            {   //$lojas['COD_UNIVEND']
                Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Loja não cadastrada!','OK');        
                return array('InserirVendaResult'=>array('msgerro' => 'Loja não cadastrada!'));
             
            }
			//verifica se a loja foi delabilitada
       $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND='.$dados->dadoslogin->idloja.' AND cod_empresa='.$row['COD_EMPRESA'];
        $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
        if($lojars['LOG_ESTATUS']!='S' || $lojars['LOG_ESTATUS']=='')
        {
           return  array('InserirVendaResult'=>array('msgerro'=>'Loja não cadastrada!'));
           exit();   
        }   
			
        //Pré-venda inicio
if(trim(rtrim($dados->venda->operacao=='pre-venda')))
{  

        if($lojas['COD_MAQUINA']!='')
        {
          $COD_MAQUINA=$lojas['COD_MAQUINA'];
        }else{
            $COD_MAQUINA=0;
        }        
         //Inicio da venda
            if($row['TIP_CONTABIL']==''){$TIP_CONTABIL=0;}else{$TIP_CONTABIL=$row['TIP_CONTABIL'];}
              mysqli_next_result($connUser->connUser());
                $cad_venda = "CALL SP_INSERE_PRE_VENDA_WS( 0,
                                                            '".$row['COD_EMPRESA']."', 
                                                            '".$cliente_cod['COD_CLIENTE']."',
                                                            '1',
                                                            '3',
                                                            '".$lojas['COD_UNIVEND']."',
                                                            '".$formapretorno['COD_FORMAPA']."',
                                                            '".fnFormatvalor($dados->venda->valortotal,$dec)."',
                                                            0,
                                                            '".fnFormatvalor($dados->venda->valor_resgate,$dec)."',
                                                            0,
                                                            '".$dados->venda->id_venda."',
                                                           '".$row['COD_USUARIO']."',
                                                            '".$TIP_CONTABIL."',
                                                            '".$COD_MAQUINA."',
                                                            '".$dados->venda->cupom."',
                                                            '".$cod_vendedor."',
                                                            '".$datahora."',
                                                             '".$cod_atendente."'
                                                             );";
       /* if($cliente_cod['COD_CLIENTE']=='118035')
        {
                return array('InserirVendaResult'=>array('msgerro' => $cad_venda)); 
        }*/ 
              $rewsinsert=mysqli_query($connUser->connUser(),$cad_venda);
                if (!$rewsinsert){
                   
                    Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Ops  problemas venda_pre-venda', 'OK'); 
                    return array('InserirVendaResult'=>array('msgerro' => 'Ops temos problemas com os dados da venda_pre-venda'));
                } else {
                $row_venda = mysqli_fetch_assoc($rewsinsert);
              
               // echo $cad_venda;
                $msg="Processo de venda concluido!";  
                $xamls= addslashes($msg);
                //Grava_log($connUser->connUser(),$LOG,$xamls);
                }
            
              
              mysqli_next_result($connUser->connUser());
                //
     if (count($dados->venda->items->vendaitem->codigoproduto)==1){ 
                                                     
        $VAL_TOTITEM=fnFormatvalor($dados->venda->items->vendaitem->quantidade,$dec)* fnFormatvalor($dados->venda->items->vendaitem->valor,$dec);
        $produto=addslashes(utf8_encode(fnAcentos($dados->venda->items->vendaitem->produto)));
        $produto=limitarTexto($produto,150);
        if($dados->venda->items->vendaitem->estoque=='' ||
           $dados->venda->items->vendaitem->estoque=='?'){$estoque=0;}
           else{$estoque=$dados->venda->items->vendaitem->estoque;}
           
            $categorias="CALL SP_ATUALIZA_PRODUTOS('".$row['COD_EMPRESA']."',
                                                    '".$dados->venda->items->vendaitem->codigoproduto."',
                                                    '".$produto."',
                                                    '".addslashes(fnAcentos($dados->venda->items->vendaitem->grupoproduto))."', 
                                                    '".addslashes(fnAcentos($dados->venda->items->vendaitem->subgrupoproduto))."',
                                                    '".addslashes(fnAcentos($dados->venda->items->vendaitem->laboratorio))."');";
            $catrow= mysqli_query($connUser->connUser(),$categorias);
     
             if (!$catrow)
             {                               
                          Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Ops nas categorizacao','OK'); 
                          return array('InserirVendaResult'=>array('msgerro' => 'Ops nas categorizacao'));
                          exit();
             } 
           mysqli_next_result($connUser->connUser());
        $itemvendainsert="call SP_INSERE_PRE_ITENS_SOAP(".$cliente_cod['COD_CLIENTE'].",
                                                        '".$row['COD_EMPRESA']."',
                                                        '".$dados->venda->items->vendaitem->id_item."',
                                                         ".$row_venda['COD_VENDA'].",
                                                         '".addslashes($dados->venda->items->vendaitem->codigoproduto)."',
                                                         '".$produto."',    
                                                         0,
                                                         '".fnFormatvalor($dados->venda->items->vendaitem->quantidade,$dec)."',
                                                         '".fnFormatvalor($dados->venda->items->vendaitem->valor,$dec)."',
                                                         '".$VAL_TOTITEM."',
                                                         '0.00',
                                                        '".fnFormatvalor($dados->venda->items->vendaitem->valor,$dec)."',
                                                        '".$dados->venda->items->vendaitem->parametro1."',
                                                        '".$dados->venda->items->vendaitem->parametro2."', 
                                                        '".$dados->venda->items->vendaitem->parametro3."', 
                                                        '".$dados->venda->items->vendaitem->parametro4."', 
                                                        '".$dados->venda->items->vendaitem->parametro5."', 
                                                        '', 
                                                        '', 
                                                        '', 
                                                        '', 
                                                        '', 
                                                        '', 
                                                        '',
                                                        '',    
                                                         '".$dados->dadoslogin->idloja."',
                                                         '".$estoque."'  
                                                          )";
        $itemteste=mysqli_query($connUser->connUser(),$itemvendainsert);
         
         if (!$itemteste){
                  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                try {mysqli_query($connUser->connUser(),$itemvendainsert);} 
                catch (mysqli_sql_exception $e) {$msgsql = $e;} 
                 Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$e,'OK'); 
                 return array('InserirVendaResult'=>array('msgerro' => 'OPS PROBLEMAS NOS ITENS'));
                 exit();
         }   

        }else{

            foreach ($dados->venda->items->vendaitem as $key => $chave)
            {


                $VAL_TOTITEM=fnFormatvalor($chave->valor,$dec)*fnFormatvalor($chave->quantidade,$dec);
                $NOM_PROD="";
                $NOM_PROD= addslashes(utf8_encode(fnAcentos($chave->produto))); 
                $NOM_PROD=limitarTexto($NOM_PROD,150);
                 if($chave->estoque=='' ||
                   $chave->estoque=='?'){$estoque=0;}
           else{$estoque=$chave->estoque;}
            mysqli_next_result($connUser->connUser());  
           //cadastro de categorias
           $categorias="CALL SP_ATUALIZA_PRODUTOS('".$row['COD_EMPRESA']."',  
                                                  '".$chave->codigoproduto."', 
                                                  '".$NOM_PROD."', 
                                                  '".addslashes(fnAcentos($chave->grupoproduto))."', 
                                                  '".addslashes(fnAcentos($chave->subgrupoproduto))."', 
                                                  '".addslashes(fnAcentos($chave->laboratorio))."')";
           
                            $catrow= mysqli_query($connUser->connUser(), $categorias);
                              if (!$catrow)
                              {
                                           Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Ops nas categorizacao','OK'); 
                                           return array('InserirVendaResult'=>array('msgerro' => 'Ops nas categorizacao'));
                                           exit();
                              }
             mysqli_next_result($connUser->connUser());    
        $itemvendainsert.="call SP_INSERE_PRE_ITENS_SOAP(".$cliente_cod['COD_CLIENTE'].",
                                                        '".$row['COD_EMPRESA']."',
                                                        '".$chave->id_item."',
                                                         ".$row_venda['COD_VENDA'].",
                                                         '".$chave->codigoproduto."',
                                                         '".$produto."',    
                                                         0,
                                                         '".fnFormatvalor($chave->quantidade,$dec)."',
                                                         '".fnFormatvalor($chave->valor,$dec)."',
                                                         '".$VAL_TOTITEM."',
                                                         '0.00',
                                                        '".fnFormatvalor($chave->valor,$dec)."',
                                                        '".$chave->parametro1."',
                                                        '".$chave->parametro2."', 
                                                        '".$chave->parametro3."', 
                                                        '".$chave->parametro4."', 
                                                        '".$chave->parametro5."', 
                                                        '', 
                                                        '', 
                                                        '', 
                                                        '', 
                                                        '', 
                                                        '', 
                                                        '',
                                                        '',    
                                                         '".$dados->dadoslogin->idloja."',
                                                         '".$estoque."'  
                                                          );";                   
            } 
            $itemteste= mysqli_multi_query($connUser->connUser(),$itemvendainsert);            
            
           
       }
      mysqli_next_result($connUser->connUser());                                                  
  /*if($cliente_cod['COD_CLIENTE']=='131110')
  {
          return array('InserirVendaResult'=>array('nome'=>$itemvendainsert));
  } */   
        
    //Calcula creditos e pontos extras
    $sql_credito = "CALL SP_INSERE_PRE_CREDITOS_WS('".$row_venda['COD_VENDA']."',
                                                    0,      
                                                    '".$row['COD_EMPRESA']."',
                                                    '".$cliente_cod['COD_CLIENTE']."',    
                                                    1,    
                                                    1,
                                                    '".$lojas['COD_UNIVEND']."',
                                                    '".$formapretorno['COD_FORMAPA']."',
                                                    '".fnFormatvalor($dados->venda->valortotal,$dec)."',
                                                    '".fnFormatvalor($dados->venda->valor_resgate,$dec)."',
                                                    0,
                                                    '".$dados->venda->id_venda."',
                                                    '".$row['COD_USUARIO']."',
                                                    '".$cod_vendedor."'    
                                                    );";

               //exibir saldo cliente
            $SALDO_CLIENTE= mysqli_query($connUser->connUser(),$sql_credito);        
            $rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);
            mysqli_next_result($connUser->connUser());
            $msg="OK";
            $xamls= addslashes($msg);
            Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'OK','OK'); 
        
            $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$cliente_cod['COD_CLIENTE'].");";
            $sld=mysqli_query($connUser->connUser(),$consultasaldo);
            $retSaldo = mysqli_fetch_assoc($sld);
          fnmemoriafinal($connUser->connUser(),$cod_men);
    
               //===================msg de resgate=================
           return array('InserirVendaResult'=>array('nome'=>$cliente_cod['nome'], //saldo todal do cliente
                                                    'saldopontos'=>fnformatavalorretorno((float)$retSaldo['CREDITO_DISPONIVEL'],$decimal), //saldo que ele terá da venda
                                                    'prevendapontos'=>fnformatavalorretorno((float)$rowSALDO_CLIENTE['CREDITO_VENDA'],$decimal),
                                                    'proxpremio'=>'0',
                                                    'pontosparaproxpremio'=>'0,00',
                                                    'msgerro' => 'OK')
                        );


           
            
        //FIM Pre venda
        //inicio da venda
}else{
           
     //verifica se data não é maior que atual      
    /*if( $datahora > date("Y-m-d"))
    {         
     Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Data da venda maior que a data atual!','OK');  
     return array('InserirVendaResult'=>array('msgerro' =>'Data da venda maior que a data atual!')); 
    }*/
    include './FN.php';
    $retornodocalc=fncalculaValor($dados,$dec);
    if($retornodocalc!='1'){
      
         //$retorno = 1 Valor da soma dos itens igual ao total
                                  $msg='valor dos itens nao corresponde ao valor total';
                                  $xamls= addslashes($msg);
                                 $vendaerrovalor="INSERT INTO venda_divergente 
                                                            (
                                                            COD_EMPRESA,
                                                            COD_INTERNO_XML, 
                                                            USUARIO, 
                                                            PDV,
                                                            COD_VENDEDOR, 
                                                            NOM_VENDEDOR, 
                                                            COD_UNIVEND,
                                                            MSG
                                                            ) 
                                                            VALUES 
                                                            ('".$row['COD_EMPRESA']."',
                                                             '$cod_log', 
                                                             '".$row['COD_USUARIO']."',
                                                             '".$dados->venda->id_vendapdv."', 
                                                             '".$dados->venda->codvendedor."', 
                                                             '".$dados->venda->codvendedor."', 
                                                             '".$lojas[0]['COD_UNIVEND']."',
                                                              '$msg');";
                                  mysqli_query($connUser->connUser(), $vendaerrovalor);
                                  mysqli_next_result($connUser->connUser());                    
           Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$msg,'OK');
    } 
    //verifica o pdv
     mysqli_next_result($connUser->connUser()); 
     if($row['TIP_REGVENDA']=='4')
    { 
        //verifica se o PDV ja foi inserido
         $CODPDV="SELECT COUNT(*) as venda,
                         DAT_CADASTR_WS,
                         cod_vendapdv,
                         cod_univend,
                         cod_cliente FROM VENDAS 
                WHERE COD_EMPRESA='".$dados->dadoslogin->idcliente."' AND 
                      cod_cliente = '".$cliente_cod['COD_CLIENTE']."' and
                      cod_univend = '".$dados->dadoslogin->idloja."' and
                COD_VENDAPDV='".$dados->venda->id_venda."'";
         $row_CODPDV = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$CODPDV));
          mysqli_next_result($connUser->connUser()); 
    }elseif($row['TIP_REGVENDA']=='3'){ 
        $row_CODPDV['venda']='1';
        
    }elseif($row['TIP_REGVENDA']=='1'){
        //verifica se o PDV ja foi inserido
         $CODPDV="SELECT COUNT(*) as venda,DAT_CADASTR_WS FROM VENDAS WHERE COD_EMPRESA='".$dados->dadoslogin->idcliente."' and COD_VENDAPDV='".$dados->venda->id_venda."'";
         $row_CODPDV = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$CODPDV));
         mysqli_next_result($connUser->connUser()); 
    }else{
        //verifica se o PDV ja foi inserido
         $CODPDV="SELECT COUNT(*) as venda,DAT_CADASTR_WS FROM VENDAS WHERE COD_EMPRESA='".$dados->dadoslogin->idcliente."' and COD_VENDAPDV='".$dados->venda->id_venda."'";
         $row_CODPDV = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$CODPDV));
         mysqli_next_result($connUser->connUser()); 
    }
   
    
    if($row_CODPDV['venda'] != 0) {
            $msg="Venda de número '".$dados->venda->id_venda."' já foi inserida para esse cliente em ".$row_CODPDV['DAT_CADASTR_WS'].".";
            $xamls= addslashes($msg);          
            Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls,'OK');
            return array('InserirVendaResult'=>array('msgerro' => $msg));
    }
     // return array('InserirVendaResult'=>array('msgerro' => $row_CODPDV['venda']));        
     //verifica valor de resgate
    if((float)fnFormatvalor($dados->venda->valor_resgate,$dec) > '0.00')
    {

        if($cartao > 0)
        {   

            //=====busca saldo do clientes 
            $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$cliente_cod['COD_CLIENTE'].")";
            $rsrown=mysqli_query($connUser->connUser(),$consultasaldo);
            $retSaldo = mysqli_fetch_assoc($rsrown);           
            mysqli_next_result($connUser->connUser());

           //============================================================================
            //busca valor de configurados para resgates
            $regraresgate='SELECT round(min(CR.NUM_MINRESG),2) as NUM_MINRESG,MAX(CR.PCT_MAXRESG) as PCT_MAXRESG,C.LOG_ATIVO FROM campanha C
                            INNER JOIN CAMPANHARESGATE CR ON CR.COD_CAMPANHA=C.COD_CAMPANHA
                            WHERE LOG_ATIVO="S" AND C.cod_empresa='.$dados->dadoslogin->idcliente;
            $resgresult=mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$regraresgate));
            mysqli_next_result($connUser->connUser());
           
            //==========================================================================
            $arrayvalorres=array('vl_venda'=> fnFormatvalor($dados->venda->valortotal,$dec),
                                 'PCT_MAXRESG'=> $resgresult['PCT_MAXRESG']);
              
          //calcula porcentagem de resgate
          $percentual=($arrayvalorres['vl_venda']*$arrayvalorres['PCT_MAXRESG'])/100;
          
            if(fnFormatvalor($dados->venda->valor_resgate,$dec) > $percentual)
            {
                $return=array('InserirVendaResult'=>array('msgerro' => 'Valor do resgate excede o máximo permitido para essa venda (R$ '.fnformatavalorretorno($percentual,$dec).')'));
                Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Valor Resgate maior que o permitido','OK');
                return $return;
            }
                
            if(fnFormatvalor($dados->venda->valor_resgate,$dec) < $resgresult['NUM_MINRESG'])
            {
                $return=array('InserirVendaResult'=>array('msgerro' => 'Só é possível realizar resgates a partir de R$ '.fnformatavalorretorno($resgresult['NUM_MINRESG'],$dec)));
                Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Valor Resgate não pode ser menor que o permitido','OK');
                return $return;
            }
             //saldo menor que o disponivel 
            if( fnFormatvalor($dados->venda->valor_resgate,$dec) > fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'],$dec))
            {

                $return=array('InserirVendaResult'=>array('msgerro' =>'Saldo insuficiente para resgate. Saldo para resgate é de R$ '.fnformatavalorretorno($retSaldo['CREDITO_DISPONIVEL'],$dec)));
                Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Valor Resgate maior que o disponivel','ok');
                return $return;
            }

              //====================================================================================
        }      
    } 
    //critica hora
    if($cartao != 0)
    {  
                              
        //não critica data hora se for igual a 2 
        if($row['TIP_REGVENDA']=='1')
        {  //verifica se a data/hora ja foi cadastrada
            $dataH='SELECT count(*) as DAT_HORA from vendas where  COD_EMPRESA="'.$dados->dadoslogin->idcliente.'" and
                     COD_CLIENTE='.$cliente_cod['COD_CLIENTE'].' and 
                     cast(DAT_CADASTR_WS as datetime)="'.$datahora.' '.$dados->venda->hora.'"';
            $row_DATAH= mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$dataH));
            mysqli_next_result($connUser->connUser()); 
            
                if($row_DATAH['DAT_HORA'] != 0){
                    
                      $msg="Venda de número '".$dados->venda->id_venda."' já foi inserida para esse cliente em ".$datahora."".$dados->venda->hora.".";
                      $xamls= addslashes($msg);                    
                      $return=array('InserirVendaResult'=>array('msgerro' => $msg));                   
                      Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls,'OK');
                     return $return; 
                }
        }
    }
     
     //================================        
    //Inicio da venda
    if($lojas['COD_MAQUINA']!='')
        {
          $COD_MAQUINA=$lojas['COD_MAQUINA'];
        }else{
            $COD_MAQUINA=0;
        }        
            if($row['TIP_CONTABIL']==''){$TIP_CONTABIL=0;}else{$TIP_CONTABIL=$row['TIP_CONTABIL'];}
              mysqli_next_result($connUser->connUser());
                $cad_venda = "CALL SP_INSERE_VENDA_WS( 0,
                                                            '".$row['COD_EMPRESA']."', 
                                                            '".$cliente_cod['COD_CLIENTE']."',
                                                            '1',
                                                            '3',
                                                            '".$lojas['COD_UNIVEND']."',
                                                            '".$formapretorno['COD_FORMAPA']."',
                                                            '".fnFormatvalor($dados->venda->valortotal,$dec)."',
                                                            0,
                                                            '".fnFormatvalor($dados->venda->valor_resgate,$dec)."',
                                                            0,
                                                            '".$dados->venda->id_venda."',
                                                           '".$row['COD_USUARIO']."',
                                                            '".$TIP_CONTABIL."',
                                                            '".$COD_MAQUINA."',
                                                            '".$dados->venda->cupom."',
                                                            '".$cod_vendedor."',
                                                            '".$datahora.' '.$dados->venda->hora."',
                                                             '".$cod_atendente."'
                                                             );";
                   
             
              $rewsinsert=mysqli_query($connUser->connUser(),$cad_venda);
                if (!$rewsinsert){
                    Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Ops temos problemas com os dados da venda','OK'); 
                    return array('InserirVendaResult'=>array('msgerro' => 'Ops temos problemas com os dados da venda'));
                    
                } else {
                $row_venda = mysqli_fetch_assoc($rewsinsert);
              
               // echo $cad_venda;
                $msg="Processo de venda concluido!";  
                $xamls= addslashes($msg);
                //Grava_log($connUser->connUser(),$LOG,$xamls);
                
                }
            
              mysqli_next_result($connUser->connUser());
            if (count($dados->venda->items->vendaitem->codigoproduto)==1){ 
                $vl_unitario_item=fnFormatvalor($dados->venda->items->vendaitem->valor,$dec)/fnFormatvalor($dados->venda->items->vendaitem->quantidade,$dec);
                $VAL_TOTITEM=fnFormatvalor($dados->venda->items->vendaitem->quantidade,$dec)* fnFormatvalor($vl_unitario_item,$dec);
                $produto=addslashes(fnAcentos($dados->venda->items->vendaitem->produto));
                $produto=limitarTexto($produto,150);
                if($dados->venda->items->vendaitem->estoque=='' ||
                   $dados->venda->items->vendaitem->estoque=='?'){$estoque=0;}
                   else{$estoque=$dados->venda->items->vendaitem->estoque;}
                     
                   //cadastro de categorias
                   $categorias="CALL SP_ATUALIZA_PRODUTOS('".$row['COD_EMPRESA']."',
                                                        '".$dados->venda->items->vendaitem->codigoproduto."',
                                                        '".$produto."','".addslashes(fnAcentos($dados->venda->items->vendaitem->grupoproduto))."', 
                                                        '".addslashes(fnAcentos($dados->venda->items->vendaitem->subgrupoproduto))."',
                                                        '".addslashes(fnAcentos($dados->venda->items->vendaitem->laboratorio))."');";
                   $catrow= mysqli_query($connUser->connUser(),$categorias);
                 
                    if (!$catrow)
                    {                               
                                 Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Ops nas categorizacao','OK'); 
                                 return array('InserirVendaResult'=>array('msgerro' => 'Ops nas categorizacao'));
                                 exit();
                    }       
                mysqli_next_result($connUser->connUser());
                if($dados->venda->items->vendaitem->parametro1=='') {$parametro1=0;}   
                if($dados->venda->items->vendaitem->parametro2=='') {$parametro2=0;}  
                if($dados->venda->items->vendaitem->parametro3=='') {$parametro3=0;}  
                if($dados->venda->items->vendaitem->parametro4=='') {$parametro4=0;}
                if($dados->venda->items->vendaitem->parametro5=='') {$parametro5=0;}   
                $itemvendainsert="call SP_INSERE_ITENS_SOAP(".$cliente_cod['COD_CLIENTE'].",
                                                                '".$row['COD_EMPRESA']."',
                                                                '".$dados->venda->items->vendaitem->id_item."',
                                                                 ".$row_venda['COD_VENDA'].",
                                                                 '".$dados->venda->items->vendaitem->codigoproduto."',
                                                                 '".$produto."',    
                                                                 0,
                                                                 '".fnFormatvalor($dados->venda->items->vendaitem->quantidade,$dec)."',
                                                                 '".fnFormatvalor($vl_unitario_item,$dec)."',
                                                                 '".fnFormatvalor($VAL_TOTITEM,$dec)."',
                                                                 '0.00',
                                                                '".fnFormatvalor($vl_unitario_item,$dec)."',
                                                                '".$parametro1."',
                                                                '".$parametro2."', 
                                                                '".$parametro3."', 
                                                                '".$parametro4."', 
                                                                '".$parametro5."', 
                                                                '0', 
                                                                '0', 
                                                                '0', 
                                                                '0', 
                                                                '0', 
                                                                '0', 
                                                                '0',
                                                                '0',    
                                                                 '".$dados->dadoslogin->idloja."',
                                                                 '".$estoque."'  
                                                                  )";

                $itemteste=mysqli_query($connUser->connUser(),$itemvendainsert);
                 if (!$itemteste){
                         Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Ops temos problemas com os dados do item','OK'); 
                         return array('InserirVendaResult'=>array('msgerro' => 'Ops temos problemas com os dados do item'));
                         exit();
                 }  
                  mysqli_next_result($connUser->connUser());

        }else{

            foreach ($dados->venda->items->vendaitem as $key => $chave)
            {

                $vl_unitario_item=fnFormatvalor($chave->valor,$dec) / fnFormatvalor($chave->quantidade,$dec);
                $VAL_TOTITEM=fnFormatvalor($vl_unitario_item,$dec)* fnFormatvalor($chave->quantidade,$dec);
                $NOM_PROD="";
                $NOM_PROD= addslashes(fnAcentos($chave->produto));               
                $NOM_PROD=limitarTexto($NOM_PROD,150);
             
                 if($chave->estoque=='' ||
                   $chave->estoque=='?'){$estoque=0;}
           else{$estoque=$chave->estoque;}
           mysqli_next_result($connUser->connUser());  
           //cadastro de categorias
           $categorias="CALL SP_ATUALIZA_PRODUTOS('".$row['COD_EMPRESA']."',  
                                                  '".$chave->codigoproduto."', 
                                                  '".$NOM_PROD."', 
                                                  '".addslashes(fnAcentos($chave->grupoproduto))."', 
                                                  '".addslashes(fnAcentos($chave->subgrupoproduto))."', 
                                                  '".addslashes(fnAcentos($chave->laboratorio))."')";
           
          $catrow= mysqli_query($connUser->connUser(), $categorias);
            if (!$catrow)
            {
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                       try {mysqli_query($connUser->connUser(),$categorias);} 
                       catch (mysqli_sql_exception $e) {$msgsql = $e;} 
                        Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$e,'OK'); 
                         return array('InserirVendaResult'=>array('msgerro' => 'Ops nas categorizacao'));
                         exit();
            }
           //===============
             mysqli_next_result($connUser->connUser());   
                if($chave->parametro1=='') {$parametro1=0;}   
                if($chave->parametro2=='') {$parametro2=0;}  
                if($chave->parametro3=='') {$parametro3=0;}  
                if($chave->parametro4=='') {$parametro4=0;}
                if($chave->parametro5=='') {$parametro5=0;}   
                     
        $itemvendainsert="call SP_INSERE_ITENS_SOAP(".$cliente_cod['COD_CLIENTE'].",
                                                        '".$row['COD_EMPRESA']."',
                                                        '".$chave->id_item."',
                                                         ".$row_venda['COD_VENDA'].",
                                                         '".$chave->codigoproduto."',
                                                         '".$NOM_PROD."',    
                                                         0,
                                                         '".fnFormatvalor($chave->quantidade,$dec)."',
                                                         '".fnFormatvalor($vl_unitario_item,$dec)."',
                                                         '".fnFormatvalor($VAL_TOTITEM,$dec)."',
                                                         '0.00',
                                                        '".fnFormatvalor($vl_unitario_item,$dec)."',
                                                        '".$parametro1."',
                                                        '".$parametro2."', 
                                                        '".$parametro3."', 
                                                        '".$parametro4."', 
                                                        '".$parametro5."', 
                                                        '0', 
                                                        '0', 
                                                        '0', 
                                                        '0', 
                                                        '0', 
                                                        '0', 
                                                        '0',
                                                        '0',    
                                                         '".$dados->dadoslogin->idloja."',
                                                         '".$estoque."'  
                                                          );";
             $itemteste=mysqli_query($connUser->connUser(),$itemvendainsert);                  
            } 
            
            if (!$itemteste)
                 {
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                       try {mysqli_query($connUser->connUser(),$itemvendainsert);} 
                       catch (mysqli_sql_exception $e) {$msgsql = $e;}
                        Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$e,'OK'); 
                         return array('InserirVendaResult'=>array('msgerro' => 'Ops temos problemas com os dados do items'));
                         exit();
                 }
       }
        mysqli_next_result($connUser->connUser());   
            
        //Calcula creditos e pontos extras
    if($cartao!=0)
    {
        if($row['COD_EMPRESA']==142 || $row['LOG_CREDAVULSO']=='S'){
            
            $sql_credito1 = "CALL SP_INSERE_CREDITOS_WS('".$row_venda['COD_VENDA']."',
                                                        0,      
                                                        '".$row['COD_EMPRESA']."',
                                                        '".$cliente_cod['COD_CLIENTE']."',    
                                                        1,    
                                                        1,
                                                        '".$lojas['COD_UNIVEND']."',
                                                        '".$formapretorno['COD_FORMAPA']."',
                                                        '".fnFormatvalor($dados->venda->valortotal,$dec)."',
                                                        '".fnFormatvalor('0,00',$dec)."',
                                                        0,
                                                        '".$dados->venda->id_venda."',
                                                        '".$row['COD_USUARIO']."',
                                                        '".$cod_vendedor."'    
                                                        )";
              mysqli_query($connUser->connUser(),$sql_credito1);
              
                        $sql_credito=" CALL SP_CREDITO_FIXO(    '".$cliente_cod['COD_CLIENTE']."', 
                                                                '".fnFormatvalor($dados->venda->pontostotal,$dec)."', 
                                                                '$datahora', 
                                                                '".$row['COD_USUARIO']."', 
                                                                'Venda OK', 
                                                                '1', 
                                                                '".$lojas['COD_UNIVEND']."', 
                                                                '".$row['COD_EMPRESA']."',
                                                                'VEN',
                                                               '".$row_venda['COD_VENDA']."',
                                                               '".$cod_vendedor."',
                                                                '".fnFormatvalor($dados->venda->valor_resgate,$dec)."', 
                                                                'CAD' )";  

                        // mysqli_query($connUser->connUser(),$pontostotal);
        }else{
        $sql_credito = "CALL SP_INSERE_CREDITOS_WS('".$row_venda['COD_VENDA']."',
                                                        0,      
                                                        '".$row['COD_EMPRESA']."',
                                                        '".$cliente_cod['COD_CLIENTE']."',    
                                                        1,    
                                                        1,
                                                        '".$lojas['COD_UNIVEND']."',
                                                        '".$formapretorno['COD_FORMAPA']."',
                                                        '".fnFormatvalor($dados->venda->valortotal,$dec)."',
                                                        '".fnFormatvalor($dados->venda->valor_resgate,$dec)."',
                                                        0,
                                                        '".$dados->venda->id_venda."',
                                                        '".$row['COD_USUARIO']."',
                                                        '".$cod_vendedor."'    
                                                        )";
        }
                   //exibir saldo cliente
           $SALDO_CLIENTE= mysqli_query($connUser->connUser(),$sql_credito);     
             mysqli_next_result($connUser->connUser());
           if (!$SALDO_CLIENTE)
           {
			   /*if($cartao=='01734200014')
			   {
				return array('InserirVendaResult'=>array('msgerro' =>$sql_credito));
			   }*/				   
                   Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'ERRO AO INSERIR CREDITO!','OK');
                   return array('InserirVendaResult'=>array('msgerro' => 'ERRO AO INSERIR CREDITO!'));
                   exit(); 
           } else {
                  $rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);
                   $msg="OK";
                   $xamls= addslashes($msg);
                  //Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'OK','OK'); 

                   //===================msg de resgate=================

           }  
           
    }else{
        //retorno cliente avulso
      $return=array('InserirVendaResult'=>array(
                                    'nome'=> $cliente_cod['nome'],                                    
                                    'saldopontos'=>'0',
                                    'saldocreditos'=>'0',
                                    'comprovante'=>'',                                          
                                    'url'=>htmlspecialchars("http://extrato.bunker.mk?key=$urlextrato", ENT_COMPAT,'UTF-8', true),
                                    'exibesaldopontos'=>0,
                                    'msgerro'=>'OK'
                                        )
            ); 
    array_to_xml($return,$xml_user_info);   
    Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'OK',addslashes($xml_user_info->asXML()));
    fnmemoriafinal($connUser->connUser(),$cod_men);
    return $return;    
    }        
if($msg=='OK')
{  
//atualizar informação de recebinento de sms/email 
//================================================================
/*
if($cliente_cod['COD_CLIENTE'] > 0 && $cartao !=0)
{
    
     $array=ARRAY('WHERE'=>"WHERE TIP_GATILHO in ('venda','resgate') AND cod_empresa=$row[COD_EMPRESA] AND LOG_STATUS='S'",
                  'TABLE'=> array('gatilho_EMAIL',
                                  'gatilho_sms'));
    foreach ($array['TABLE'] as $KEY => $dadostable)
    {    
       
        $sqlgatilho_email="SELECT * FROM $dadostable $array[WHERE]";
         
        $rwgatilho_email=mysqli_query($connUser->connUser(), $sqlgatilho_email);        
        $rsgatilho_email= mysqli_fetch_assoc($rwgatilho_email);
        if($rsgatilho_email['TIP_GATILHO']!='')
        {    
            if($rsgatilho_email['TIP_GATILHO']=='resgate'){$gatilho='5';}
            if($rsgatilho_email['TIP_GATILHO']=='venda'){$gatilho='6';}
           
               $cod_campanha=$rsgatilho_email['COD_CAMPANHA'];
               $TIP_MOMENTO=$rsgatilho_email['TIP_MOMENTO'];
               $TIP_GATILHO=$rsgatilho_email['TIP_GATILHO'];
               if((float)fnFormatvalor($dados->venda->valor_resgate,$dec) <= '0.00')
               {$valorresgate='0.00';}else{$valorresgate=fnFormatvalor($dados->venda->valor_resgate,$dec);}      
                
                       $sqlfila= "INSERT INTO email_fila ( COD_EMPRESA, 
                                                           COD_UNIVEND, 
                                                           COD_CLIENTE, 
                                                           NUM_CGCECPF,
                                                           NOM_CLIENTE, 
                                                           DT_NASCIME, 
                                                           DES_EMAILUS,
                                                           NUM_CELULAR,
                                                           COD_SEXOPES, 
                                                           COD_CAMPANHA,
                                                           TIP_MOMENTO,
                                                           TIP_FILA,
                                                           TIP_GATILHO,                                                           
                                                           VAL_CRED_ACUMULADO,
                                                           VAL_RESGATE,
                                                           SEMANA
                                                           ) VALUES 
                                                           ('".$row['COD_EMPRESA']."', 
                                                           '".$dados->dadoslogin->idloja."', 
                                                           '".$cliente_cod['COD_CLIENTE']."', 
                                                           '".$cliente_cod['cpf']."', 
                                                           '".addslashes(fnAcentos($cliente_cod['nome']))."', 
                                                           '".$cliente_cod['datanascimento']."', 
                                                           '".trim($cliente_cod['email'])."',
                                                           '".$cliente_cod['telcelular']."',    
                                                           '".$cliente_cod['sexo']."', 
                                                           '".$cod_campanha."', 
                                                           '".$TIP_MOMENTO."',
                                                           '$gatilho',
                                                           '$TIP_GATILHO',
                                                           '".fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$decimal)."',
                                                           '".$valorresgate."',
                                                           '".date("W", strtotime("-2 day",strtotime(date('Y-m-d H:i:s'))))."'    
                                                           );";
                 if($rsgatilho_email['TIP_GATILHO']=='resgate'){ 
                     if((float)fnFormatvalor($dados->venda->valor_resgate,$dec) > '0.00')
                     {        
                         mysqli_query($connUser->connUser(), $sqlfila);
                     }
                     
                 }
                 if($rsgatilho_email['TIP_GATILHO']=='venda'){ mysqli_query($connUser->connUser(), $sqlfila);}       
                unset($sqlfila);     
        }               
    }
 } */ 
//atualizar informação de recebinento de sms/email 
//================================================================
if($cliente_cod['COD_CLIENTE'] > 0 && $cartao !=0)
{
   
    $array=ARRAY('WHERE'=>"WHERE g.TIP_GATILHO in ('venda','resgate') AND g.cod_empresa=$row[COD_EMPRESA] AND g.LOG_STATUS='S'",
                 'TABLE'=> array('gatilho_EMAIL g INNER  JOIN email_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha',
                                 'gatilho_sms g INNER  JOIN sms_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha'
                                ));
    foreach ($array['TABLE'] as $KEY => $dadostable)
    {  
        unset($sqlgatilho_email);
        $sqlgatilho_email.="SELECT * FROM $dadostable $array[WHERE]";
        $rwgatilho_email=mysqli_query($connUser->connUser(), $sqlgatilho_email); 
        while($rsgatilho_email= mysqli_fetch_assoc($rwgatilho_email))
        {        
            if($rsgatilho_email['TIP_GATILHO']!='')
            {    
                if($KEY=='0')
                { 
                    if($rsgatilho_email['TIP_GATILHO']=='resgate'){$gatilho='5';}
                    if($rsgatilho_email['TIP_GATILHO']=='venda'){$gatilho='6';}
                }else{
                    if($rsgatilho_email['TIP_GATILHO']=='resgate'){$gatilho='7';}
                    if($rsgatilho_email['TIP_GATILHO']=='venda'){$gatilho='8';}
                }    
                       $cod_campanha=$rsgatilho_email['COD_CAMPANHA'];
                       $TIP_MOMENTO=$rsgatilho_email['TIP_MOMENTO'];
                       $TIP_GATILHO=$rsgatilho_email['TIP_GATILHO'];
                       $COD_PERSONAS=$rsgatilho_email['COD_PERSONAS'];
                       if((float)fnFormatvalor($dados->venda->valor_resgate,$dec) <= '0.00')
                       {$valorresgate='0.00';}
                       else{$valorresgate=fnFormatvalor($dados->venda->valor_resgate,$dec);}      

                               $sqlfila= "INSERT INTO email_fila ( COD_EMPRESA, 
                                                                   COD_UNIVEND, 
                                                                   COD_CLIENTE, 
                                                                   NUM_CGCECPF,
                                                                   NOM_CLIENTE, 
                                                                   DT_NASCIME, 
                                                                   DES_EMAILUS,
                                                                   NUM_CELULAR,
                                                                   COD_SEXOPES, 
                                                                   COD_CAMPANHA,
                                                                   TIP_MOMENTO,
                                                                   TIP_FILA,
                                                                   TIP_GATILHO,                                                           
                                                                   VAL_CRED_ACUMULADO,
                                                                   VAL_RESGATE,
                                                                   SEMANA,
                                                                   TIP_CONTROLE,
                                                                   MES
                                                                   ) VALUES 
                                                                   ('".$row['COD_EMPRESA']."', 
                                                                    '".$dados->dadoslogin->idloja."', 
                                                                    '".$cliente_cod['COD_CLIENTE']."', 
                                                                    '".$cliente_cod['cpf']."',                                                                  
                                                                    '".addslashes(fnAcentos($cliente_cod['nome']))."', 
                                                                    '".$cliente_cod['datanascimento']."', 
                                                                    '".trim($cliente_cod['email'])."',
                                                                    '".$cliente_cod['telcelular']."',    
                                                                    '".$cliente_cod['sexo']."', 
                                                                    '".$cod_campanha."', 
                                                                    '".$TIP_MOMENTO."',
                                                                    '$gatilho',
                                                                    '$TIP_GATILHO',
                                                                    '".fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$decimal)."',
                                                                    '".$valorresgate."',
                                                                    '".date("W", strtotime("-2 day",strtotime(date('Y-m-d H:i:s'))))."',
                                                                    $rsgatilho_email[TIP_CONTROLE],
                                                                    '".DATE('m')."'    
                                                                   );";
                       // if($cliente_cod['telcelular']!='')
                       // {    
                            if($rsgatilho_email['TIP_GATILHO']=='resgate'){ 
                                if((float)fnFormatvalor($dados->venda->valor_resgate,$dec) > '0.00')
                                {        
                                    mysqli_query($connUser->connUser(), $sqlfila);
                                }
                            }
                            if($rsgatilho_email['TIP_GATILHO']=='venda')
                            { mysqli_query($connUser->connUser(), $sqlfila);}  
                        //}    
                        unset($sqlfila);     
                }
           $clas="CALL SP_PERSONA_CLASSIFICA_CADASTRO($cliente_cod[COD_CLIENTE], ".$row['COD_EMPRESA'].", $cod_campanha, '".$COD_PERSONAS."',0)";
         $testesql=mysqli_query($connUser->connUser(), $clas);     
		 
        }
    }
}
//==================================================================
$comprovante='
    CLIENTE: '.$cliente_cod['nome'].'
    Cartao: '.$cartao.'
    DATA: '.date("Y-m-d H:i:s").'
    SALDO ACUMULADO: '. fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$decimal).'

   *COMPROVANTE NAO FISCAL.*';
   if((float)fnFormatvalor($dados->venda->valor_resgate,$dec) > '0.00')
   {   

$comprovante_resgate='                 
    Cliente: '.$cliente_cod['nome'].'
    Cartao: '.$cartao.'
    Valor debitado: R$ '.fnformatavalorretorno($dados->venda->valor_resgate,$decimal).'
    Data:'.date("Y-m-d H:i:s").'
    Reconheco e autorizo o debito


     _____________________________
     ASSINATURA DO CLIENTE
     SALDO ACUMULADO: R$ '.fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$decimal).'

     COMPROVANTE NAO FISCAL.
  ';
   }
}   
 
$return=array('InserirVendaResult'=>array(
                                        'nome'=> $cliente_cod['nome'],                                          
                                        'saldocreditos'=>fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$decimal),
                                        'saldoresgate'=>fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_DISPONIVEL'],$decimal),
                                        'comprovante'=>$comprovante,
                                        'comprovante_resgate'=>$comprovante_resgate,
                                        'url'=>"http://extrato.bunker.mk?key=".rawurlencode($urlextrato),
                                        'exibesaldopontos'=>1,
                                        'msgerro'=>$msg
                                        )
            );
            
    array_to_xml($return,$xml_user_info);   
    Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'OK',addslashes($xml_user_info->asXML()));
    fnmemoriafinal($connUser->connUser(),$cod_men);
    mysqli_close($connUser->connUser());   
    mysqli_close($connUser->connUser());  
    return $return;      
    }        

}