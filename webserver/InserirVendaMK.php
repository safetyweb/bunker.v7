<?php
//inserir venda
//=================================================================== InserirVenda ==================================================================================
//retorno dados venda
$server->wsdl->addComplexType(
    'dadosdavendaMK',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'nome' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nome', 'type' => 'xsd:string'),
        'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:int'),
        'saldo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldo', 'type' => 'xsd:string'),
        'saldoresgate' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldoresgate', 'type' => 'xsd:string'),
        'comprovante' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'comprovante', 'type' => 'xsd:string'),
        'comprovante_resgate' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'comprovante_resgate', 'type' => 'xsd:string'),
        'url' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'url', 'type' => 'xsd:string'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
    )
);

// venda
$server->wsdl->addComplexType(
    'vendaMK',
    'complexType',
    'struct',
    'sequence',
    '',
         array('id_vendapdv' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'id_vendapdv', 'type' => 'xsd:string'),
               'datahora' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'datahora', 'type' => 'xsd:string'),
               'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:string'),
               'valorLiquido' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valorLiquido', 'type' => 'xsd:string'),
               'valorFrete' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valorFrete', 'type' => 'xsd:string'),
               'valortotal' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valortotal', 'type' => 'xsd:string'),
               'descontoComercial' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descontoComercial', 'type' => 'xsd:string'),
               'resgateFidelidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'resgateFidelidade', 'type' => 'xsd:string'),
               'descontoFidelidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descontoFidelidade', 'type' => 'xsd:string'),
               'cupom' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cupom', 'type' => 'xsd:string'),
               'formapagamento' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'formapagamento', 'type' => 'xsd:string'),
               'cartaoamigo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartaoamigo', 'type' => 'xsd:string'),
               'pontosextras' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'pontosextras', 'type' => 'xsd:string'),
               'naopontuar' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'naopontuar', 'type' => 'xsd:string'),
               'codatendente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codatendente', 'type' => 'xsd:string'),
               'codvendedor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codvendedor', 'type' => 'xsd:string'),
               'pontostotal' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'pontostotal', 'type' => 'xsd:string'),
               'itemsMK' => array('minOccurs'=>'0', 'maxOccurs'=>'20','name' => 'items', 'type' => 'tns:itemsMK')
            )
);

//array de itens
$server->wsdl->addComplexType(
    'itemsMK',
    'complexType',
    'struct',
    'sequence',
    '',
        array('vendaitemMK' =>array('minOccurs'=>'0', 'maxOccurs'=>'20','name' => 'vendaitemMK', 'type' => 'tns:vendaitemMK'))
         
);




// itens da venda array ^

$server->wsdl->addComplexType(
    'vendaitemMK',
    'complexType',
    'struct',
    'sequence',
    '',
         array('id_item' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'id_item', 'type' => 'xsd:string'),
               'produto' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'produto', 'type' => 'xsd:string'),
               'codigoproduto' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'codigoproduto', 'type' => 'xsd:string'),
               'quantidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'quantidade', 'type' => 'xsd:string'),
               'valorUnitario' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'valorUnitario', 'type' => 'xsd:string'),
               'descontoComercial' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'descontoComercial', 'type' => 'xsd:string'),
               'valorTotal' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'valorTotal', 'type' => 'xsd:string'),
               'naopontuar' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'naopontuar', 'type' => 'xsd:string')
             )
);



 //Registro para parassar os dados pra a função inserir venda
$server->register('InserirVendaMK',
			array(
                              'vendaMK'=>'tns:vendaMK',
                               'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('return' => 'tns:dadosdavendaMK'),  //output
			'urn:fidelidade',   //namespace
			'urn:fidelidade#InserirVendaMK',  //soapaction
			'rpc', //document
			'literal', // literal
			'InserirVenda');  //description


function InserirVendaMK ($InserirVendaMK,$dadoslogin) {
 
    include '../_system/Class_conn.php';
    include './func/function.php';
    
  //valida campos
        $msg=valida_campo_vazio($InserirVendaMK['formapagamento'],'formapagamento','numeric');
        if(!empty($msg)||!empty($msg1)){return array('msgerro' => $msg);}
        $msg=valida_campo_vazio($dadoslogin['login'],'login','string');
        if(!empty($msg)){return array('msgerro' => $msg);}
        $msg=valida_campo_vazio($dadoslogin['senha'],'senha','string');
        if(!empty($msg)){return array('msgerro' => $msg);}  
        $msg=valida_campo_vazio($InserirVendaMK['id_vendapdv'],'id_vendapdv','string');
        if(!empty($msg)){return array('msgerro' => $msg);}
        
    $Cartaows=$InserirVendaMK['cartao'];
  // echo count($InserirVenda['items']['vendaitem']);
    
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    //memoria log
    
   
   
   // EMPRESAS.LOG_ATIVO = > S OU N ATIVA FULL WEBSERVICES,
if($row['LOG_ATIVO']=='S')
{  
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
        
      fnmemoria($connUser->connUser(),'true',$dadoslogin['login'],'Venda');
        //inserir venda inteira na base de daods 
       $xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($InserirVendaMK,true)));
       $inserarray='INSERT INTO ORIGEMVENDA (DAT_CADASTR,IP,PORTA,COD_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,COD_PDV,NUM_CGCECPF,DES_VENDA)values
                   ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
                    "'.$row['COD_USUARIO'].'","'.$row['COD_EMPRESA'].'","'.$dadoslogin['idloja'].'","'.$dadoslogin['idmaquina'].'","'.$InserirVendaMK['id_vendapdv'].'","'.$InserirVendaMK['cartao'].'","'.$xamls.'")';
        mysqli_query($connUser->connUser(),$inserarray);
    
        $ID_LOG="SELECT last_insert_id(COD_ORIGEM) as ID_LOG from ORIGEMVENDA ORDER by COD_ORIGEM DESC limit 1;";
        $LOG = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_LOG));
     
       //CODIGO PDV igual não passa
       $CODPDV="SELECT COUNT(*) as venda FROM VENDAS WHERE COD_VENDAPDV='".$InserirVendaMK['id_vendapdv']."'";
       $row_CODPDV = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$CODPDV));
       

        //calcula valor do itens + quantida e verifica se o valor total dos itens e igual  
        $retorno=fn_calValor($InserirVendaMK);

        //Menssagem de erro do sistema criticas de campos
       
            if($retorno!=1)
            { 
                    //$retorno = 1 Valor da soma dos itens igual ao total
                    $msg=';o A soma dos itens não correspode ao valor total!';
                   
                    $xamls= addslashes($msg);
                    Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                    if(!empty($msg)){return array('msgerro' => $msg);}
            }elseif($row_CODPDV['venda'] != 0) {
                    $msg='Oh não! Seu codigo PDV ja existe, tente com outro codigo por favor! :(  ';
                    $xamls= addslashes($msg);
                    Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                    
            }elseif($InserirVendaMK['datahora']==date("Y-m-d H:i:s", time())){
                    $msg='Oh não! Ja existe um cadastro nesse mesmo periodo, tente periodo por favor! :(  ';
                    $xamls= addslashes($msg);
                    Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);
            }

            //$retorno = 1 Valor da soma dos itens igual ao total
            //$row_CODPDV['venda']== 0 não existe essa venda no banco de dados
            if($row_CODPDV['venda'] == 0 && $retorno == 1)
            {
                // verifica se o dados cpf,cartão,emaile celular existe na base de dados
                $dadosbase=fn_consultaBase($connUser->connUser(),$Cartaows,'',$Cartaows,'','');
                //Carregar os dados do cliente
                if($dadosbase[0]['COD_CLIENTE'] != 0){  
                                $nome=$dadosbase[0]['nome'];
                                $COD_CLIENTE=$dadosbase[0]['COD_CLIENTE'];
                                $datanascimento=$dadosbase[0]['datanascimento'];
                                $sexo=$dadosbase[0]['sexo'];
                                $cpf=$dadosbase[0]['cpf'];
                                $cartao=$dadosbase[0]['cartao'];
                                $telefone=$dadosbase[0]['telcelular'];
                                
                }else{
                 
                        //se o cadastro automatico for ativo 
                       if($row['LOG_AUTOCAD']=='S')
                        {    
                           //busca de cpf se tiver auto cad com cpf
                            if($row['LOG_CONSEXT'] == 'S'){
                                if ( valida_cpf($Cartaows) ) {
                                    //FUNÇÃO QUE BUSCA DA IFARO OS DADOS DO CPF
                                    include './func/func_ifaro.php';  
                                    $resultIfaro=ifaro($Cartaows);
                                    $nome = $resultIfaro[0]['nome'][0];
                                    $cartao=$resultIfaro[0]['cpf'][0];
                                    if($resultIfaro[0]['sexo'][0]=='M'){$sexo=1;}else{$sexo=2;}
                                    $datanascimento = $resultIfaro[0]['datanascimento'][0]; 
                                     $sql="insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA) value
                                    ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."','".$resultIfaro[0]['cpf'][0]."','".$resultIfaro[0]['nome'][0]."','".$row['COD_EMPRESA']."','".$dadoslogin['login']."','".$dadoslogin['idloja']."','".$dadoslogin['idmaquina']."')";
                                    mysqli_query($connAdm->connAdm(),$sql);
                                }else{
                                    $msg='CPF digitado e invalido!';  
                                    $xamls= addslashes($msg);
                                    Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                                }                    
                            }else{
                                $cartao=$InserirVendaMK['cartao'];
                                $datanascimento=is_Date(date('d/m/Y'));
                                $sexo=1; 
                                $nome="cliente ".$cartao;
                                
                            }
                               //cadastrastro de cliente que nao existe
                               $cad_cliente = "CALL SP_ALTERA_CLIENTES_WS('".$row['COD_EMPRESA']."',
                                                                           '".$nome."',
                                                                           '".$row['COD_USUARIO']."',
                                                                           '".$cartao."',
                                                                           '".$datanascimento."',
                                                                           '".$sexo."',
                                                                           '".$cartao."',
                                                                           'F',
                                                                           'CAD'
                                                                        )";
                               $row_cliente = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$cad_cliente));
                               $COD_CLIENTE=$row_cliente['COD_CLIENTE'];
                               $msg='Cliente inserido ';
                               $xamls= addslashes($msg);
                               Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                               $updatecartao="update  geracartao set log_usado='S',cod_USUALTE=".$row['COD_USUARIO']." where num_cartao=".$cartao; 
                               mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$updatecartao));
                               
                               $msg='cartao alterado';
                               $xamls= addslashes($msg);
                               Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                        //se o cadastro automatico for inativo      
                        }elseif($row['LOG_AUTOCAD']=='N'){
                            $COD_CLIENTE=$dadosbase[0]['COD_CLIENTE'];
                            
                        }  
                }
              
                //Fim da carga do cliente
                //inicio do inserir venda
                if($InserirVendaMK['cartao']==0)
                {
                  $cad_venda = "CALL SP_INSERE_VENDA_WS( 0,
                                                           0,
                                                           '".$row['COD_EMPRESA']."', 
                                                           '".$row['COD_CLIENTE_AV']."',
                                                           '1',
                                                           '3',
                                                           '".$row['COD_UNIVEND']."',
                                                           '".$InserirVendaMK['formapagamento']."',
                                                           '".fnFormatvalor($InserirVendaMK['valortotal'])."',
                                                           0,
                                                           '".fnFormatvalor($InserirVendaMK['valor_resgate'])."',
                                                           0,
                                                           '".$InserirVendaMK['id_vendapdv']."',
                                                          '".$row['COD_USUARIO']."',
							   '".$row['TIP_CONTABIL']."'
                                                            );";
                    $rewsinsert=mysqli_query($connUser->connUser(),$cad_venda);
                    $row_venda = mysqli_fetch_assoc($rewsinsert);
                   // echo $cad_venda;
                    $msg="Processo de venda avulso concluido!";  
                    $xamls= addslashes($msg);
                    Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);                    
                }else{
                    $cad_venda = "CALL SP_INSERE_VENDA_WS( 0,
                                                           0,
                                                           '".$row['COD_EMPRESA']."', 
                                                           '".$COD_CLIENTE."',
                                                           '1',
                                                           '3',
                                                           '".$row['COD_UNIVEND']."',
                                                           '".$InserirVendaMK['formapagamento']."',
                                                           '".fnFormatvalor($InserirVendaMK['valortotal'])."',
                                                           0,
                                                           '".fnFormatvalor($InserirVendaMK['valor_resgate'])."',
                                                           0,
                                                           '".$InserirVendaMK['id_vendapdv']."',
                                                          '".$row['COD_USUARIO']."',
                                                          '".$row['TIP_CONTABIL']."'    
                                                            );";
                    $rewsinsert=mysqli_query($connUser->connUser(),$cad_venda);
                    $row_venda = mysqli_fetch_assoc($rewsinsert);
                   // echo $cad_venda;
                    $msg="Processo de venda concluido!";  
                    $xamls= addslashes($msg);
                    Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                    //
                }
                    //fim do inserir venda
                                         
                                     //se item venda for menor que um.      
                                    if (count($InserirVendaMK['items']['vendaitem']['codigoproduto'])==1){ 

                                         $VAL_TOTITEM=fnFormatvalor($InserirVendaMK['items']['vendaitem']['quantidade'])* fnFormatvalor($InserirVendaMK['items']['vendaitem']['valor']);
                                         $itemvendainsert="INSERT INTO ITEMVENDA (COD_VENDA,COD_PRODUTO,COD_ORCAMENTO,QTD_PRODUTO,VAL_UNITARIO,VAL_TOTITEM)
                                                           Values('".$row_venda['COD_VENDA']."',
                                                                  '".$InserirVendaMK['items']['vendaitem']['codigoproduto']."',
                                                                  0,    
                                                                  '".fnFormatvalor($InserirVendaMK['items']['vendaitem']['quantidade'])."',
                                                                  '".fnFormatvalor($InserirVendaMK['items']['vendaitem']['valor'])."',    
                                                                  '".fnFormatvalor($VAL_TOTITEM)."'    
                                                                  );";    

                                         mysqli_query($connUser->connUser(),$itemvendainsert);
                                        
                                        
                                     }else{
                                         
                                         for($i=0;$i < count($InserirVendaMK['items']['vendaitem']);$i++){
                                              $VAL_TOTITEM=fnFormatvalor($InserirVendaMK['items']['vendaitem'][$i]['quantidade'])*fnFormatvalor($InserirVendaMK['items']['vendaitem'][$i]['valor']);
                                              
                                               $itemvendainsert.="INSERT INTO ITEMVENDA (COD_VENDA,COD_PRODUTO,COD_ORCAMENTO,QTD_PRODUTO,VAL_UNITARIO,VAL_TOTITEM)
                                                               Values('".$row_venda['COD_VENDA']."',
                                                                      '".$InserirVendaMK['items']['vendaitem'][$i]['codigoproduto']."',
                                                                      0,    
                                                                      '".fnFormatvalor($InserirVendaMK['items']['vendaitem'][$i]['quantidade'])."',
                                                                      '".fnFormatvalor($InserirVendaMK['items']['vendaitem'][$i]['valor'])."',    
                                                                      '".$VAL_TOTITEM."'    
                                                                      );";    
                                                 
                                             
                                          } 
                                         
                                               mysqli_multi_query($connUser->connUser(),$itemvendainsert);
                                              
                                    }
                                               $msg='Processo de itens concluido!';
                                               $xamls= addslashes($msg);
                                               Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                        if($InserirVendaMK['cartao']==0)
                        {
                        $msg='Venda avulso nao gerar credito!';
                        $xamls= addslashes($msg);
                        Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                        $msg='ok!';
                        $xamls= addslashes($msg);
                        Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                        }else{                   
                           //Calcula creditos e pontos extras
                           $sql_credito = "CALL SP_INSERE_CREDITOS_WS('".$row_venda['COD_VENDA']."',
                                                                     0,      
                                                                     '".$row['COD_EMPRESA']."',
                                                                     '".$COD_CLIENTE."',    
                                                                     1,    
                                                                     1,
                                                                     '".$row['COD_UNIVEND']."',
                                                                     '".$InserirVendaMK['formapagamento']."',
                                                                     '".fnFormatvalor($InserirVendaMK['valortotal'])."',
                                                                     '".fnFormatvalor($InserirVendaMK['valor_resgate'])."',
                                                                     0,
                                                                     '".$InserirVendaMK['id_vendapdv']."',
                                                                     '".$row['COD_USUARIO']."'  
                                                                     )";
                           //exibir saldo cliente
                            mysqli_query($connUser->connUser(),$sql_credito);
                            $procsaldo='CALL SP_CONSULTA_SALDO_CLIENTE ('.$COD_CLIENTE.')';
                            $SALDO_CLIENTE=mysqli_query($connUser->connUser(),$procsaldo);
                            $rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);
                          
                            $msg='Processo de credito concluido!';
                            $xamls= addslashes($msg);
                            Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                            $msg="OK!";
                            $xamls= addslashes($msg);
                            Grava_log($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                        }                
            } else {}
              //memoria log
              fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
              
                 //RETORNO DA WEB SERVICE    
                return array(
                               'nome'=> $nome,
                               'cartao'=>$COD_CLIENTE,
                               'saldo'=>$rowSALDO_CLIENTE['TOTAL_CREDITO'],
                               'saldoresgate'=>$rowSALDO_CLIENTE['CREDITO_DISPONIVEL'],
                               'comprovante'=>'',
                               'comprovante_resgate'=>'',
                               'url'=>'',
                               'msgerro'=>$msg
                           );




    }else{ 
        $msg='Oh Não! Seu Usuario ou senha está errado. Se tiver a necessidade entre  em contato com o Administrador do sistema.';
        return array('msgerro'=>$msg);}  
}else{return array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[');}    
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
            
     
}

//=================================================================== Fim InserirVenda =================================================================================

?>
