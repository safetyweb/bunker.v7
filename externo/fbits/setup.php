<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include '../../_system/_functionsMain.php';
include './func_wscadastro.php';
include './int_marka.php';
printf(nl2br('Inicio cadastro\n \r'));

//fnDebug('true');
$connadmin=$connAdm->connAdm();
printf(nl2br('CONEXAO COM BASE OK\n \r'));
$buscadados="SELECT * FROM SENHAS_ECOMMERCE where log_ativo='S' and TIPO_URL='2' and cod_parcomu=9;";
$rsconfig =mysqli_query($connadmin, $buscadados);   
while ($row = mysqli_fetch_assoc($rsconfig)) {
   
        @$cod_empresa=$row['COD_EMPRESA'];
        $conntemp=connTemp($cod_empresa,'');
        
            $buscaorigemlog="select * from log_integration_user where cod_empresa=$cod_empresa and COD_INSERT=0";
            $rsBUsca=mysqli_query($conntemp , $buscaorigemlog);
            
            $dados=array(   'URLWSDL'=>$row['URL_WSDL'],
                            'URL'=>$row['URL'],
                            'SENHA'=>$row['DES_AUTHKEY'],
                            'DEBUG'=>$row['DEBUG_ATIVO'],
                            'FUNCTION'=>'GetItems',
                            'cod_empresa'=>$cod_empresa,     
                            'conntemp'=>$conntemp  
                        );
            $usuarioID=SyncUsuario($dados);
            if(mysqli_num_rows($rsBUsca)>=0)
            { 
                while ($rowresult=mysqli_fetch_assoc($rsBUsca))
                {       $ARRAYbase = REPLACE_STD_SET($rowresult['DES_VENDA']);   
                        $usuarioID[]=array('UsuarioId'=>rtrim(trim($ARRAYbase['UsuarioId'])));   
                }        
            }    
                   //busca de usuarios para envia ws_marka
                   $buscausuariows="select  des_senhaus,
                                            log_usuario,
                                            cod_univend
                                    from usuarios 
                                                where   cod_empresa=$cod_empresa and 
                                                        cod_usuario='".$row['COD_USUINTEGRA']."'"; 
                   $usuarioconn=mysqli_fetch_assoc(mysqli_query($connadmin, $buscausuariows));
                   $passwsmarka=fnDecode($usuarioconn['des_senhaus']);
                   $userwsmarka=$usuarioconn['log_usuario'];
                   $cod_univend=$usuarioconn['cod_univend'];
                 //=============================================================================================

            //buscar na base de log é inserir nos clientes via atualizacadastro     
            foreach ($usuarioID as $key => $value) {

                    $buscaorigemlog="select * from log_integration_user where cod_empresa=$cod_empresa and COD_INSERT=0 and COD_EXT_USER='".$value['UsuarioId']."'";  
                    $rowresult=mysqli_fetch_assoc(mysqli_query($conntemp , $buscaorigemlog));
                     //array do cliente na base de dados;
                    $ARRAY = REPLACE_STD_SET($rowresult['DES_VENDA']);
                    //=========================================


                    if($ARRAY['TipoPessoaId']==1){@$TipoPessoaId='PF';}else{@$TipoPessoaId='PJ';};
                    if($ARRAY['TipoSexoId']==1){@$TipoSexoId='M';}else{@$TipoSexoId='F';};
                    if(fnLimpaDoc($ARRAY['CPF']=='')){@$CPFCNPJ=$ARRAY['CNPJ'];}else{@$CPFCNPJ=$ARRAY['CPF'];};
                        if(fnLimpaDoc($ARRAY['CPF'])!='' || fnLimpaDoc($ARRAY['CNPJ'])){
                            
                    
                                $dados=array( 'cartao'=> fnLimpaDoc($CPFCNPJ),
                                              'tipocliente'=>@$TipoPessoaId,
                                              'nome'=>fnAcentos(utf8_decode($ARRAY['Nome'])),
                                              'cpf'=>fnLimpaDoc($CPFCNPJ),
                                              'sexo'=>@$TipoSexoId,
                                              'email'=>@$ARRAY['Email'],
                                              'rg'=>@$ARRAY['RG'],
                                              'telresidencial'=>@$ARRAY['TelefoneResidencial'],
                                              'telcelular'=>@$ARRAY['TelefoneCelular'],
                                              'cnpj'=>fnLimpaDoc($CPFCNPJ),
                                              'endereco'=> limitarTexto(fnAcentos(utf8_decode(fnAcentos($ARRAY['Endereco']))),99),
                                              'numero'=> @limitarTexto(fnAcentos(utf8_decode($ARRAY['Numero'])),10),
                                              'complemento'=>fnAcentos(utf8_decode($ARRAY['Complemento'])),
                                              'bairro'=>fnAcentos(utf8_decode(fnAcentos($ARRAY['Bairro']))),
                                              'cidade'=>fnAcentos(utf8_decode($ARRAY['Cidade'])),
                                              'estado'=>fnAcentos($ARRAY['Estado']),
                                              'cep'=>@$ARRAY['CEP'],
                                              'datanascimento'=>date('Y-m-d', strtotime($ARRAY['DataNascimento'])));
                               
                                $dadoslogin=array( 'login'=>$userwsmarka,
                                                    'senha'=>$passwsmarka,
                                                    'idmaquina'=>$cod_univend,
                                                    'idloja'=>$cod_univend,
                                                    'idcliente'=>$cod_empresa);
                            //insere cadastro na base de dados.
                            $retorno=atualiazacadastroMK($dados,$dadoslogin);
                            //se receber o OK do atualiza cadastro dar o complete no usuario
                            if($retorno=='OK')
                            {
                                        //verifica se é cpf mesmo
                                        if(strlen(fnLimpaDoc($CPFCNPJ))<=11)
                                        {   
                                            //inserindo dados do cliente na base intermediaria só cpf 
                                            $log_cpf="insert into log_cpf (data_hora,IP,CPF,NOME,SEXO,DT_NASCIMENTO,COD_EMPRESA)
                                                      value
                                                      ('". date('Y-m-d H:i:s')."',
                                                       '".$_SERVER['REMOTE_ADDR']."',
                                                       '".fnLimpaDoc($CPFCNPJ)."',
                                                       '".fnAcentos($ARRAY['Nome'])."',
                                                       '".$TipoSexoId."',
                                                       '".date('Y-m-d', strtotime($ARRAY['DataNascimento']))."',
                                                       '".$cod_empresa."'    
                                                      );
                                                     " ; 
                                            mysqli_query($connadmin, $log_cpf);
                                            //==============================================================
                                        }
                                    //==========================================================
                                    //update no log para 1
                                    $updatelog="UPDATE log_integration_user set COD_INSERT='1' 
                                                 WHERE COD_EXT_USER=".rtrim(trim($ARRAY['UsuarioId']))." and
                                                       COD_EMPRESA='".$cod_empresa."';";

                                    mysqli_query($conntemp, $updatelog);
                                      //dar o complete no usuario
                                    $dadoscomplete=array(   'URLWSDL'=>$row['URL_WSDL'],
                                                            'URL'=>$row['URL'],
                                                            'SENHA'=>$row['DES_AUTHKEY'],
                                                            'DEBUG'=>$row['DEBUG_ATIVO'],
                                                            'FUNCTION'=>'Complete',
                                                            'usuarioId'=>rtrim(trim($ARRAY['UsuarioId'])),
                                                            'cod_empresa'=>$cod_empresa,     
                                                            'conntemp'=>$conntemp  
                                           );
                                    SyncUsuario_complete($dadoscomplete);
                            }    
               }    
                //===========================================================

            }
mysqli_close($conntemp);  
        
}
    
unset($dados);
mysqli_close($connadmin); 
printf( '\n Cadastro OK... \n \r');
printf( '\n inicio venda OK...\n \r');
$connadmin=$connAdm->connAdm();
$buscadados="SELECT * FROM SENHAS_ECOMMERCE where log_ativo='S' and TIPO_URL='1' and cod_parcomu=9";        
$rsconfig =mysqli_query($connadmin, $buscadados);   
while ($row = mysqli_fetch_assoc($rsconfig)) {
        @$cod_empresa=$row['COD_EMPRESA'];
        $conntemp=connTemp($cod_empresa,'');
         $buscaorigemlog="select * from log_integration_venda where cod_empresa=$cod_empresa and COD_INSERT=0";
         $rsBUsca=mysqli_query($conntemp , $buscaorigemlog);
         
          
            $dados=array(   'URLWSDL'=>$row['URL_WSDL'],
                            'URL'=>$row['URL'],
                            'SENHA'=>$row['DES_AUTHKEY'],
                            'DEBUG'=>$row['DEBUG_ATIVO'],
                            'FUNCTION'=>'GetItems2',
                            'cod_empresa'=>$cod_empresa,     
                            'conntemp'=>$conntemp  
                        );
            $PedidoId=SyncPedidoVenda_GetItems2($dados);
            if(mysqli_num_rows($rsBUsca)>=0)
            { 
                while ($rowresult=mysqli_fetch_assoc($rsBUsca))
                {       $ARRAYbase = REPLACE_STD_SET($rowresult['DES_VENDA']);   
                        $PedidoId[]=array('PedidoId'=>rtrim(trim($ARRAYbase['PedidoId'])));   
                }        
            }   
            
                   //busca de usuarios para envia ws_marka
                   $buscausuariows="select  des_senhaus,
                                            log_usuario,
                                            cod_univend
                                    from usuarios 
                                                where   cod_empresa=$cod_empresa and 
                                                        cod_usuario='".$row['COD_USUINTEGRA']."'"; 
                   $usuarioconn=mysqli_fetch_assoc(mysqli_query($connadmin, $buscausuariows));
                   $passwsmarka=fnDecode($usuarioconn['des_senhaus']);
                   $userwsmarka=$usuarioconn['log_usuario'];
                   $cod_univend=$usuarioconn['cod_univend'];
                 //=============================================================================================

            //buscar na base de log é inserir nos clientes via atualizacadastro     
            foreach ($PedidoId as $key => $value) {

                    $buscaorigemlog="select * from log_integration_venda where cod_empresa=$cod_empresa and COD_INSERT=0 and COD_EXT_VEN='".$value['PedidoId']."'";  
                    $rowresult=mysqli_fetch_assoc(mysqli_query($conntemp , $buscaorigemlog));
                     //array do cliente na base de dados;
                    $ARRAY = REPLACE_STD_SET($rowresult['DES_VENDA']);
                   /* echo "<pre>";
                    print_r($ARRAY);
                    echo "</pre>";*/
                   $verifica_cadastro="select cod_cliente FROM clientes  WHERE num_cgcecpf='".fnLimpaDoc($ARRAY['Usuario']['CPF'])."' AND cod_empresa=$cod_empresa"; 
                   $dadosclientebase=mysqli_query($conntemp, $verifica_cadastro);
                   if(mysqli_num_rows($dadosclientebase)<=0)
                   {  
                        //retransmitir o cliente
                         if($ARRAY['Usuario']['TipoPessoaId']==1){@$TipoPessoaId='PF';}else{@$TipoPessoaId='PJ';};
                         if($ARRAY['Usuario']['TipoSexoId']==1){@$TipoSexoId='M';}else{@$TipoSexoId='F';};
                         if(fnLimpaDoc($ARRAY['Usuario']['CPF']=='')){@$CPFCNPJ=$ARRAY['Usuario']['CNPJ'];}else{@$CPFCNPJ=$ARRAY['Usuario']['CPF'];};
                                 if(fnLimpaDoc($ARRAY['Usuario']['CPF'])!='' || fnLimpaDoc($ARRAY['Usuario']['CNPJ'])){   
                                     $dados=array( 'cartao'=> fnLimpaDoc($CPFCNPJ),
                                                   'tipocliente'=>@$TipoPessoaId,
                                                   'nome'=> fnAcentos($ARRAY['Usuario']['Nome']),
                                                   'cpf'=>fnLimpaDoc($CPFCNPJ),
                                                   'sexo'=>@$TipoSexoId,
                                                   'email'=>@$ARRAY['Usuario']['Email'],
                                                   'rg'=>@$ARRAY['Usuario']['RG'],
                                                   'telresidencial'=>@$ARRAY['Usuario']['TelefoneResidencial'],
                                                   'telcelular'=>@$ARRAY['Usuario']['TelefoneCelular'],
                                                   'cnpj'=>fnLimpaDoc($CPFCNPJ),
                                                   'endereco'=> limitarTexto(fnAcentos($ARRAY['Usuario']['Endereco']),99),
                                                   'numero'=> @limitarTexto($ARRAY['Usuario']['Numero'],10),
                                                   'complemento'=>fnAcentos($ARRAY['Usuario']['Complemento']),
                                                   'bairro'=>fnAcentos($ARRAY['Usuario']['Bairro']),
                                                   'cidade'=>fnAcentos($ARRAY['Usuario']['Cidade']),
                                                   'estado'=>fnAcentos($ARRAY['Usuario']['Estado']),
                                                   'cep'=>@$ARRAY['Usuario']['CEP'],
                                                   'datanascimento'=>date('Y-m-d', strtotime($ARRAY['DataNascimento'])));
                                     $dadoslogin=array(  'login'=>$userwsmarka,
                                                         'senha'=>$passwsmarka,
                                                         'idmaquina'=>$cod_univend,
                                                         'idloja'=>$cod_univend,
                                                         'idcliente'=>$cod_empresa);
                                     //insere cadastro na base de dados.
                                     
                                     $retorno=atualiazacadastroMK($dados,$dadoslogin);
                                }
                    }
                      //  echo 'retono do cadastro na vrnda =>>>> '.$retorno.'========cadastro na venda';
                    //===================================================================
                    $count=count($ARRAY['Itens']['IntegracaoPedidoProdutoInfo'])-1;
                    if($count>=0){
                            if($count<=0){

                                $item=array('vendaitem'=>array( 'id_item'=>rtrim(trim($ARRAY['Itens']['IntegracaoPedidoProdutoInfo'][0]['ProdutoVarianteId'])),
                                                                'produto'=>fnAcentos($ARRAY['Itens']['IntegracaoPedidoProdutoInfo'][0]['Nome']),
                                                                'codigoproduto'=>rtrim(trim($ARRAY['Itens']['IntegracaoPedidoProdutoInfo'][0]['SKU'])),
                                                                'quantidade'=>$ARRAY['Itens']['IntegracaoPedidoProdutoInfo'][0]['ProdutoQuantidade'],
                                                                'valor'=>str_replace('.', ',', $ARRAY['Itens']['IntegracaoPedidoProdutoInfo'][0]['PrecoUnitario']))
                                            ); 
                            }else{
                                foreach ($ARRAY['Itens']['IntegracaoPedidoProdutoInfo'] as $key => $value) {
                                  $item[]=array(    'id_item'=>$value['ProdutoVarianteId'],
                                                     'produto'=>$value['Nome'],
                                                     'codigoproduto'=>$value['SKU'],
                                                     'quantidade'=>$value['ProdutoQuantidade'],
                                                     'valor'=>str_replace('.', ',',$value['PrecoUnitario'])
                                                );  
                                }
                            }
                            if ($ARRAY['ValorDebitoCC'] == '0.0000') {
                                $resgate = '';
                            } else {
                                $resgate = number_format($ARRAY['ValorDebitoCC'], 2, ',', '.');
                            }
                            if(@$ARRAY['FormasPagamento']['IntegracaoPedidoPagamentoInfo'][0]['FormaPagamentoId']=='')
                            {@$formapg='cartao';}else
                            {@$formapg=$ARRAY['FormasPagamento']['IntegracaoPedidoPagamentoInfo'][0]['FormaPagamentoId'];} 
                            $id_vendapdv=$ARRAY['PedidoId'];
                            $dados=array( 'id_vendapdv'=> $id_vendapdv,
                                          'datahora'=>date('Y-m-d H:i:s', strtotime($ARRAY['DataPedido'])),
                                          'cartao'=> fnLimpaDoc($ARRAY['Usuario']['CPF']),
                                          'valortotal'=>str_replace('.', ',', $ARRAY['Subtotal']),
                                          'cupom'=>rtrim(trim($ARRAY['PedidoId'])),
                                          '<valor_resgate>'=>str_replace('.', ',', $ARRAY['ValorDebitoCC']),
                                          'formapagamento'=>$formapg,
                                          'codatendente'=>'',
                                          'codvendedor'=>'',
                                          'valor_resgate'=>$resgate,
                                          'items'=>$item                                   
                                        );
echo'<pre>';
print_r($dados);
echo'</pre>';
                            $dadoslogin=array( 'login'=>$userwsmarka,
                                                'senha'=>$passwsmarka,
                                                'idmaquina'=>$cod_univend,
                                                'idloja'=>$cod_univend,
                                                'idcliente'=>$cod_empresa);
                            $arrvendamarka=inserevendaMK($dados,$dadoslogin); 
                            if($arrvendamarka['msgerro']=='OK')
                            {
                              $cod='1';  
                            }elseif ($arrvendamarka['msgerro']==';o A soma dos itens não correspode ao valor total!') 
                            {
                              $cod='2';  
                            } elseif ($arrvendamarka['msgerro']=='Oh não! Ja existe uma venda na mesma data e Horas! :(') 
                            {
                               $cod='3';   
                            }else{
                                $cod='4';   
                            }
                              
                            
                            
                             if($cod==4 || $cod=='1' || $cod='3' || $cod='2' ){
                                    //inerir credito para a conta do cliente
                                    printf('\n inicio.......venda OK inicio do insere credito\n');
                                    if($cod=='1'){ 
                                        printf('\n entrou na rotina de credito\n \r');
                                        $buscadadoscredito="SELECT * FROM SENHAS_ECOMMERCE where log_ativo='S' and TIPO_URL='4' and cod_parcomu=9";
                                        $rsconfigcredit =mysqli_query($connadmin, $buscadadoscredito);
                                        if(mysqli_num_rows($rsconfigcredit)>0)
                                        {
                                             echo '\r\n verifica se a rotina de credito esta ativa \r\n';
                                            if($arrvendamarka['creditovenda']=='' || $arrvendamarka['creditovenda']=='null')
                                            {$vl='0,00';}else{$vl=$arrvendamarka['creditovenda'];}
                                                $source = array('.', ',');
                                                $replace = array('', '.');
                                                $valor = str_replace($source, $replace, $vl);
                                                $array=array(
                                                              'email'=>$ARRAY['Usuario']['Email'],
                                                              'val_credito'=>$valor,
                                                              'senha'=>$row['DES_AUTHKEY']
                                                            );
                                             inserecredito($array);
                                             printf('\n inseriu credito de : '.$valor.'\n \r');
                                              printf('\n fim \n \r');
                                         }
                                    }
                                    printf('\n venda OK FIM do insere credito\n');
                 //======================================================================================================
                                //UPdate no log 
                                    //COD_INSERT='2' aguarando atulização
                                     //COD_INSERT='8' pedido cancelado
                                    
                                    $startDate = time();
                                    $datverifi=date('Y-m-d H:i:s', strtotime('+3 day', $startDate));
                                    $updatelog="UPDATE log_integration_venda set COD_INSERT='2',
                                                                                 DATA_VERIFICA='$datverifi'
                                                     WHERE COD_EXT_VEN=".rtrim(trim($ARRAY['PedidoId']))." and
                                                           COD_EMPRESA='".$cod_empresa."';";
                                    mysqli_query($conntemp, $updatelog);  
                                
                                $dados= ['URLWSDL'=>$row['URL_WSDL'],
                                                'URL'=>$row['URL'],
                                                'SENHA'=>$row['DES_AUTHKEY'],
                                                'DEBUG'=>$row['DEBUG_ATIVO'],
                                                'FUNCTION'=>'Complete',
                                                'pedidoId'=>rtrim(trim($ARRAY['PedidoId'])),
                                                'cod_empresa'=>$cod_empresa,     
                                                'conntemp'=>$conntemp  
                                                ];
                                $PedidoId=SyncPedidoVenda_complete($dados);
                            }
                   // echo "<pre>";
                  //  print_r($ARRAY);
                   // echo "</pre><br><br>";
                   // echo $arrvendamarka;
                }    
                  
                  //=========================================
            }
            //estorno de venda;
                echo 'INICIO DO ESTORNO VENDA';
                 $verificastatus="select * from log_integration_venda 
                 WHERE COD_INSERT in ('1','2') and   COD_EMPRESA='".$cod_empresa."';";                 
                 $statupdido=mysqli_query($conntemp, $verificastatus);   
                 while($satuspg=mysqli_fetch_assoc($statupdido))
                 {
                      
                     $ARRAYbase = REPLACE_STD_SET($satuspg['DES_VENDA']); 
                      
                        $dados= ['URLWSDL'=>$row['URL_WSDL'],
                                'URL'=>$row['URL'],
                                'SENHA'=>$row['DES_AUTHKEY'],
                                'DEBUG'=>$row['DEBUG_ATIVO'],
                                'FUNCTION'=>'Select',
                                'pedidoId'=>rtrim(trim($satuspg['COD_EXT_VEN']))
                                ];
                       $PedidoId= SyncPedidoVenda_select($dados);
                      
                        //verificar a DATA_VERIFICA 
                       $datanow=date('Y-m-d H:i:s');
                        if($satuspg['DATA_VERIFICA']>=$datanow)
                        {    
                            
                                if($PedidoId['Status']=='8' || $PedidoId['Status']=='3' || $PedidoId['Status']=='12')
                                {
                                  
                                   //pedido cancelado 
                                   //STATUS_PEDIDO=8 
                                    $PDV=array( 'id_vendapdv'=> $satuspg['COD_EXT_VEN']);
                                    $dadoslogin=array(  'login'=>$userwsmarka,
                                                         'senha'=>$passwsmarka,
                                                         'idmaquina'=>$cod_univend,
                                                         'idloja'=>$cod_univend,
                                                         'idcliente'=>$cod_empresa);
                                    $estorno=Estorno_venda($PDV,$dadoslogin);
                                       if($estorno['msgerro']=='OK')
                                        {  
                                              //fazer estorno da conta corrente do cliente
                                              $arraysaldo=array(
                                                                'email'=>$ARRAYbase['Usuario']['Email'],
                                                                'senha'=>$row['DES_AUTHKEY']
                                                              );
                                             $saldocontaatual=saldoatual($arraysaldo);

                                             if($saldocontaatual==''){$vl='0.00';}else{$vl=$saldocontaatual;}
                                                 $vl= number_format($vl, 2, '.', ',');
                                                 $array=array(
                                                                'email'=>$ARRAYbase['Usuario']['Email'],
                                                                'val_credito'=>$vl,
                                                                'senha'=>$row['DES_AUTHKEY']
                                                              );
                                                Estornocredito($array);
                                                
                                                //envia credito atualizado
                                                if($estorno['saldo']==''){$vl='0.00';}else{$vl=$estorno['saldo'];}
                                                $vl= number_format($vl, 2, '.', ',');
                                                
                                                 $arrayatualiza=array(
                                                               'email'=>$ARRAYbase['Usuario']['Email'],
                                                               'val_credito'=>$vl,
                                                               'senha'=>$row['DES_AUTHKEY']
                                                             );
                                                 inserecredito($arrayatualiza);

                                            $updatelog="UPDATE log_integration_venda set COD_INSERT='8',
                                                                                         STATUS_PEDIDO=8,
                                                                                         DATA_VERIFICA='".data('Y-m-d H:i:s')."'
                                                                          WHERE COD_EXT_VEN=".$satuspg['COD_EXT_VEN']." and
                                                                                COD_EMPRESA='".$cod_empresa."';";
                                             mysqli_query($conntemp, $updatelog);   
                                        }else{     
                                            $updatelog="UPDATE log_integration_venda set COD_INSERT='8',
                                                                                         STATUS_PEDIDO=8
                                                                          WHERE COD_EXT_VEN=".$satuspg['COD_EXT_VEN']." and
                                                                                COD_EMPRESA='".$cod_empresa."';";
                                             mysqli_query($conntemp, $updatelog);
                                            echo 'Venda ja excluida';
                                        }         
                                }else
                                {
                                    $updatelog="UPDATE log_integration_venda set COD_INSERT='2',
                                                                                 STATUS_PEDIDO='2'
                                            WHERE COD_EXT_VEN=".$satuspg['COD_EXT_VEN']." and
                                                  COD_EMPRESA='".$cod_empresa."';";
                                     mysqli_query($conntemp, $updatelog);
                                }    
                        }else{
                           echo 'Data ja verificadas'; 
                            $updatelog="UPDATE log_integration_venda set COD_INSERT='200',
                                                                                 STATUS_PEDIDO='200'
                                            WHERE COD_EXT_VEN=".$satuspg['COD_EXT_VEN']." and
                                                  COD_EMPRESA='".$cod_empresa."';";
                                     mysqli_query($conntemp, $updatelog);
                        }
                 echo 'FIM DO ESTORNO VENDA';
                 }
            
mysqli_close($conntemp);             
}
printf('\n Venda OK...\n \r');
mysqli_close($connadmin);
 