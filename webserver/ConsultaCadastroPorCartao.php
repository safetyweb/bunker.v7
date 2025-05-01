<?php
//função que captura os dados da pagina "soap"
//=================================================================== ConsultaCadastroPorCPF ====================================================================

$server->register('ConsultaCadastroPorCartao',
			array(
                              'cartao'=>'xsd:string',
                               'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('ConsultaCadastroPorCartaoResult' => 'tns:FichadeCadastroRetorno'),  //output
			$ns,         						// namespace
                        "$ns/ConsultaCadastroPorCartao",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'ConsultaCadastroPorCartao');  //description

 function ConsultaCadastroPorCartao($cartao,$dadosLogin) {
    include_once '../_system/Class_conn.php';
    include_once './func/function.php';  
     
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
/*  $dec=$row['NUM_DECIMAIS'];
  if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;}   */ 
  
  //verifica se a loja foi delabilitada
       $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND='.$dadosLogin['idloja'].' AND cod_empresa='.$dadosLogin['idcliente'];
        $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
        if($lojars['LOG_ESTATUS']!='S')
        {
           return  array('ConsultaCadastroPorCartaoResult'=>array('msgerro'=>'LOJA DESABILITADA'));
           exit();   
        }  
  
  
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
        {
          $passou=1;
        } else {}
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    
	$CONFIGUNI="SELECT * FROM unidades_parametro WHERE 
														  COD_EMPRESA=".$dadosLogin['idcliente']." AND 
														  COD_UNIVENDA=".$dadosLogin['idloja']." AND LOG_STATUS='S'";
													  
			$RSCONFIGUNI=mysqli_query($connUser->connUser(), $CONFIGUNI);
			if(!$RSCONFIGUNI)
			{		  
	         
			}else{
					if($RCCONFIGUNI=mysqli_num_rows($RSCONFIGUNI) > 0)
					{
						//aqui pega da unidade
						$RWCONFIGUNI=mysqli_fetch_assoc($RSCONFIGUNI);
						$dec=$RWCONFIGUNI['NUM_DECIMAIS']; 
						if ($RWCONFIGUNI['TIP_RETORNO']== 2){$decimal = '2';}else {$decimal = '0';}
						$LOG_CADVENDEDOR=$RWCONFIGUNI['LOG_CADVENDEDOR'];
						$COD_DATAWS1=$RWCONFIGUNI['COD_DATAWS'];
					}else{
						//aqui pega da controle de licença
						$dec=$row['NUM_DECIMAIS'];			
						if ($row['TIP_RETORNO']== 2){$decimal = '2';}else {$decimal = '0';}			
						$LOG_CADVENDEDOR=$row['LOG_CADVENDEDOR'];
						$COD_DATAWS1=$row['COD_DATAWS'];
					}   
			}
	
    $arraydados1= array('CONN'=>$connUser->connUser(),
                       'DATA_HORA'=>date("Y-m-d H:i:s"),
                       'IP'=>$_SERVER['REMOTE_ADDR'],
                       'PORT'=>$_SERVER['REMOTE_PORT'],
                       'COD_USUARIO'=>$row['COD_USUARIO'],
                       'LOGIN'=>$dadosLogin['login'],
                       'COD_EMPRESA'=>$row['COD_EMPRESA'],
                       'IDLOJA'=>$dadosLogin['idloja'],
                       'IDMAQUINA'=>$dadosLogin['idmaquina'],
                       'CPF'=>$cartao,
                       'URL'=>'WS2-COMPATIBILIDADE',                      
                       'XML'=>file_get_contents("php://input")
                      );
    $LOG=fngravaxmlbusca($arraydados1);
      
    $urlextrato=fnEncode($dadosLogin['login'].';'
                        .$dadosLogin['senha'].';'
                        .$dadosLogin['idloja'].';'
                        .$dadosLogin['idmaquina'].';'
                        .$row['COD_EMPRESA'].';'
                        .$dadosLogin['codvendedor'].';'
                        .$dadosLogin['nomevendedor'].';'
                        .$cartao
                         );
    //'url'=>"http://extrato.bunker.mk?key=$urlextrato",
     
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
        {
            if($row['LOG_ATIVO']=='S')
            {
                    if( $passou!=1)
                    {  
                        $dadosbase=fn_consultaBase($connUser->connUser(),trim($cartao),'',trim($cartao),'','',$row['COD_EMPRESA']);   
                          if ($dadosbase[0]['cartao']==''){$msg='Cartão não cadastrado'; $cod='0';
                           return array('ConsultaCadastroPorCartaoResult'=>array('msgerro' => 'Cartão não cadastrado'));
                          }else{$msg='OK'; $cod='2';}    
                       
                          
                          
if ($dadosbase[0]['cartao']=='')
{    
    $geracartao="select   
              (SELECT NUM_TAMANHO FROM LOTECARTAO A WHERE A.COD_EMPRESA=geracartao.COD_EMPRESA AND A.COD_LOTCARTAO=geracartao.COD_LOTCARTAO) AS NUM_TAMANHO,
              cod_cartao,log_usado,num_cartao,count(*) contador  from geracartao where num_cartao='$cartao'  and cod_empresa=".$row['COD_EMPRESA'];
     $rsgeracartao=mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$geracartao));

      //====================================================================================
    if($rsgeracartao['NUM_TAMANHO']==''){$rsgeracartao=0;}else{$rsgeracartao=$rsgeracartao['NUM_TAMANHO'];}    


    if($row['COD_CHAVECO']=='5')
     {  

        if($dadosbase[0]['cartao'] !='')
        {  


            if((int) $cartao!= $dadosbase[0]['cartao'])
             {

               return array('ConsultaCadastroPorCartaoResult'=>array('msgerro' => 'Cartao Já cadastrato'));   
             } 
            if($rsgeracartao['contador'] > 0 && $rsgeracartao['log_usado']=='S') 
            {
               return array('ConsultaCadastroPorCartaoResult'=>array('msgerro' => 'Cartao Já cadastrato'));

            }
            if ($rsgeracartao['contador']==0) 
            {
              //cartao inválido - não existe na base

              return array('ConsultaCadastroPorCartaoResult'=>array('msgerro' => 'Cartão inválido!'));                           
            }
        } 
        if(strlen($cartao) != $rsgeracartao)
        {
          return array('ConsultaCadastroPorCartaoResult'=>array('msgerro' => 'Cartão inválido!'));        
        } 
     } 
}
                        
                           
                           
                           
                           
                           
                           
                           
                           
                           
                           $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$dadosbase[0]['COD_CLIENTE'].")";
                           $retSaldo = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$consultasaldo));
                            //busca retorno profissão
                               $bus_PROFISS = "select * from profissoes where COD_PROFISS=".$dadosbase[0]['profissao'];
                               $profiss_ret = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$bus_PROFISS));  

 
                           if($dadosbase[0]['sexo']=='1'){$sexo='M';}else{$sexo='F';} 
                           mysqli_close($connAdm->connAdm());   
                           mysqli_close($connUser->connUser()); 
                                   return array('ConsultaCadastroPorCartaoResult'=> array(
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
                                                                    'telcelular' =>preg_replace('/[^0-9]/', '', $dadosbase[0]['telcelular']),
                                                                    'telcomercial' =>$dadosbase[0]['telcomercial'],
                                                                    'saldo' =>fnformatavalorretorno($retSaldo['TOTAL_CREDITO'],$decimal),
                                                                    'saldoresgate' =>fnformatavalorretorno($retSaldo['CREDITO_DISPONIVEL'],$decimal),
                                                                    'msgerro' => $msg,
                                                                    'msgcampanha' =>'',
                                                                    'url' =>"http://extrato.bunker.mk?key=$urlextrato",
                                                                    'ativacampanha' => '',
                                                                    'dadosextras' => '',
                                                                    'bloqueado' => '',
                                                                    'motivo' =>'',
                                                                    'adesao' =>'',
                                                                    'codatendente' =>'',
                                                                    'senha' =>'',
                                                                    'urlextrato' =>'',
                                                                    'retornodnamais' => $cod
                                       ));
                    }      
                    else{
                        return array('ConsultaCadastroPorCartaoResult'=>array('msgerro' =>"Id_cliente não confere com o cadastro!"));

                    }               
            }else{
            return array('ConsultaCadastroPorCartaoResult'=>array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));  
            }             

                         
        }else{
            return array('ConsultaCadastroPorCartaoResult'=>array('msgerro'=>'Erro Na autenticação'));

        }   
}
//=================================================================== Fim ConsultaCadastroPorCPF ====================================================================
?>
