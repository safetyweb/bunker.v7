<?php
include '../../_system/_functionsMain.php';
include './func_wscadastro.php';
include './int_marka.php';
echo 'Inicio cadastro<br>';
//fnDebug('true');
$connadmin=$connAdm->connAdm();
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
                                              'nome'=> fnAcentos($ARRAY['Nome']),
                                              'cpf'=>fnLimpaDoc($CPFCNPJ),
                                              'sexo'=>@$TipoSexoId,
                                              'email'=>@$ARRAY['Email'],
                                              'rg'=>@$ARRAY['RG'],
                                              'telresidencial'=>@$ARRAY['TelefoneResidencial'],
                                              'telcelular'=>@$ARRAY['TelefoneCelular'],
                                              'cnpj'=>fnLimpaDoc($CPFCNPJ),
                                              'endereco'=> limitarTexto(fnAcentos($ARRAY['Endereco']),99),
                                              'numero'=> @limitarTexto($ARRAY['Numero'],10),
                                              'complemento'=>fnAcentos($ARRAY['Complemento']),
                                              'bairro'=>fnAcentos($ARRAY['Bairro']),
                                              'cidade'=>fnAcentos($ARRAY['Cidade']),
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
echo 'Cadastro OK...<br>';
echo 'inicio venda OK...<br>';
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
                   if(mysqli_num_rows($dadosclientebase)>=0)
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
                            if ($ARRAY['ValorCreditoFidelidade'] == '0.0000') {
                                $resgate = '';
                            } else {
                                $resgate = number_format($ARRAY['ValorCreditoFidelidade'], 2, ',', '.');
                            }
                            if(@$ARRAY['FormasPagamento']['IntegracaoPedidoPagamentoInfo'][0]['FormaPagamentoId']=='')
                            {@$formapg='cartao';}else
                            {@$formapg=$ARRAY['FormasPagamento']['IntegracaoPedidoPagamentoInfo'][0]['FormaPagamentoId'];} 
                            $id_vendapdv=$ARRAY['PedidoId'].'-'.$ARRAY['UsuarioId'].'-'.date('ymdHis');
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

                            $dadoslogin=array( 'login'=>$userwsmarka,
                                                'senha'=>$passwsmarka,
                                                'idmaquina'=>$cod_univend,
                                                'idloja'=>$cod_univend,
                                                'idcliente'=>$cod_empresa);
                            $arrvendamarka=inserevendaMK($dados,$dadoslogin); 
                            if($arrvendamarka['msgerro']=='OK' || $arrvendamarka['msgerro']=';o A soma dos itens não correspode ao valor total!')
                            {
                                //UPdate no log 
                                $updatelog="UPDATE log_integration_venda set COD_INSERT='1' 
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
                    echo "<pre>";
                    print_r($ARRAY);
                    echo "</pre><br><br>";
                    echo $arrvendamarka;
                }    
                  
                  //=========================================
/*                
//inerir credito para a conta do cliente
$buscadados="SELECT * FROM SENHAS_ECOMMERCE where log_ativo='S' and TIPO_URL='1' and cod_parcomu=9";
$rsconfig =mysqli_query($connadmin, $buscadados);   
while ($row = mysqli_fetch_assoc($rsconfig)) {}

//$ARRAY['Usuario']['Email']*/
//======================================================================================================

            }
            
mysqli_close($conntemp);             
}
Echo 'Venda OK...';
mysqli_close($connadmin);