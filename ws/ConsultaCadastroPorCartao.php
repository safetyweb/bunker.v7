<?php
//função que captura os dados da pagina "soap"
//=================================================================== ConsultaCadastroPorCPF ====================================================================

$server->register('ConsultaCadastroPorCartao',
			array(
                              'cartao'=>'xsd:string',
                               'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('return' => 'tns:FichadeCadastroRetorno'),  //output
			'urn:server',   //namespace
			'urn:server#ConsultaCadastroPorCartao',  //soapaction
			'rpc', //document
			'literal', // literal
			'ConsultaCadastroPorCartao');  //description

 function ConsultaCadastroPorCartao($cartao,$dadoslogin) {
    include '../_system/Class_conn.php';
    include './func/function.php';  
     
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
  
        if ($row['COD_EMPRESA'] != $dadoslogin['idcliente'])
        {
          $passou=1;
        } else {}
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
        {
            if($row['LOG_ATIVO']=='S')
            {
                    if( $passou!=1)
                    {  
                        $dadosbase=fn_consultaBase($connUser->connUser(),'','',trim($cartao),'','',$row['COD_EMPRESA']);   
                           if ($dadosbase[0]['cartao']==''){$msg='Cartão não cadastrado';}else{$msg='ok';}
                           $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$dadosbase[0]['COD_CLIENTE'].")";
                           $retSaldo = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$consultasaldo));
                            //busca retorno profissão
                               $bus_PROFISS = "select * from profissoes where COD_PROFISS=".$dadosbase[0]['profissao'];
                               $profiss_ret = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$bus_PROFISS));  


                           if($dadosbase[0]['sexo']=='1'){$sexo='M';}else{$sexo='F';} 
                                   return array(
                                       'cartao' =>$dadosbase[0]['cartao'] ,
                                       'tipocliente' =>$dadosbase[0]['tipocliente'],
                                       'nome' => $dadosbase[0]['nome'],
                                       'cpf' =>$dadosbase[0]['cpf'],
                                       'cnpj'=>$dadosbase[0]['cnpj'],
                                       'rg' => $dadosbase[0]['rg'],
                                       'sexo' =>$sexo,
                                       'datanascimento' => $dadosbase[0]['datanascimento'],
                                       'estadocivil' => $dadosbase[0]['estadocivil'],
                                       'email' => $dadosbase[0]['email'],
                                       'dataalteracao' =>$dadosbase[0]['dataalteracao'] ,
                                       'cartaotitular' =>$dadosbase[0]['cartaotitular'],
                                       'nomeportador'=>$dadosbase[0]['nomeportador'],
                                       'grupo' => $dadosbase[0]['grupo'],
                                       'profissao' =>$profiss_ret['DES_PROFISS'],
                                       'clientedesde' => $dadosbase[0]['clientedesde'],
                                       'endereco' => $dadosbase[0]['endereco'],
                                       'numero' => $dadosbase[0]['numero'],
                                       'complemento' =>$dadosbase[0]['complemento'],
                                       'bairro' =>$dadosbase[0]['bairro'],
                                       'cidade' =>$dadosbase[0]['cidade'],
                                       'estado' =>$dadosbase[0]['estado'],
                                       'cep' => $dadosbase[0]['cep'],
                                       'telresidencial' =>$dadosbase[0]['telresidencial'],
                                       'telcelular' =>$dadosbase[0]['telcelular'],
                                       'telcomercial' =>$dadosbase[0]['telcomercial'],
                                       'saldo' =>fnformatavalorretorno($retSaldo['TOTAL_CREDITO']),
                                       'saldoresgate' =>fnformatavalorretorno($retSaldo['CREDITO_DISPONIVEL']),
                                       'msgerro' => $msg,
                                       'msgcampanha' =>'',
                                       'url' =>'',
                                       'ativacampanha' => '',
                                       'dadosextras' => '',
                                       'bloqueado' => '',
                                       'motivo' =>'',
                                       'adesao' =>'',
                                       'codatendente' =>'',
                                       'senha' =>'',
                                       'urlextrato' =>'',
                                       'retornodnamais' => '0'
                                       );
                    }      
                    else{
                        return array('msgerro' =>"Id_cliente não confere com o cadastro!");

                    }               
            }else{
            return array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[');  
            }             

                         
        }else{
            return array('msgerro'=>'Erro Na autenticação');

        }   
      
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}
//=================================================================== Fim ConsultaCadastroPorCPF ====================================================================

?>
