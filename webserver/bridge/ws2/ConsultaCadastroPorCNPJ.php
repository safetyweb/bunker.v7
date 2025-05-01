<?php
function ConsultaCadastroPorCNPJ($DADOSLOGIN) {       
  
    
    
   // return  array('ConsultaCadastroPorCNPJResult'=>array('msgerro'=> file_get_contents("php://input")));
   //return array('ConsultaCadastroPorCNPJResult'=>array('msgerro' => var_export(json_encode($DADOSLOGIN->dadoslogin,true), true))); 
    
    rtrim(trim(require_once('../../../_system/Class_conn.php')));
    rtrim(trim(require_once('../../func/function.php')));
    include './functionbridge/functionbridge.php';
  $CNPJ=fnlimpaCPF($DADOSLOGIN->CNPJ);

    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$DADOSLOGIN->dadoslogin->login."', '".fnEncode($DADOSLOGIN->dadoslogin->senha)."','','','".$DADOSLOGIN->dadoslogin->idcliente."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
   
/*   $dec=$row['NUM_DECIMAIS'];
    if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;} */
    
    //verifica se a loja foi delabilitada
       $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND='.$DADOSLOGIN->dadoslogin->idloja.' AND cod_empresa='.$DADOSLOGIN->dadoslogin->idcliente;
        $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
                
        if($lojars['LOG_ESTATUS']!='S')
        {
           return  array('ConsultaCadastroPorCNPJResult'=>array('msgerro'=>'LOJA DESABILITADA'));
           exit();   
        }  
    
    if ($row['COD_EMPRESA'] != $DADOSLOGIN->dadoslogin->idcliente)
        {
          $passou=1;
        } else {}
    //conn user
      $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);  
    //94858993000
    //EMPRESAS.LOG_CONSEXT => S OU N  ATIVA A CONSULTA,
   // EMPRESAS.LOG_ATIVO = > S OU N ATIVA FULL WEBSERVICES,
        $CONFIGUNI="SELECT * FROM unidades_parametro WHERE 
														  COD_EMPRESA=".$DADOSLOGIN->dadoslogin->idcliente." AND 
														  COD_UNIVENDA=".$DADOSLOGIN->dadoslogin->idloja." AND LOG_STATUS='S'";
		$RSCONFIGUNI=mysqli_query($connUser->connUser(), $CONFIGUNI);
		if(!$RSCONFIGUNI)
		{		  
			// fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda','erro no novo parametro da unidade'.$sql,$row['LOG_WS']); 
		 
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
  
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
        $arraydados1= array('CONN'=>$connUser->connUser(),
                            'DATA_HORA'=>date("Y-m-d H:i:s"),
                            'IP'=>$_SERVER['REMOTE_ADDR'],
                            'PORT'=>$_SERVER['REMOTE_PORT'],
                            'COD_USUARIO'=>$row['COD_USUARIO'],
                            'LOGIN'=>$DADOSLOGIN->dadoslogin->login,
                            'COD_EMPRESA'=>$row['COD_EMPRESA'],
                            'IDLOJA'=>$DADOSLOGIN->dadoslogin->idloja,
                            'IDMAQUINA'=>$DADOSLOGIN->dadoslogin->idmaquina,
                            'CPF'=>$CNPJ,
                            'URL'=>$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                            'XML'=>file_get_contents("php://input")
                           );
    $LOG=fngravaxmlbusca($arraydados1);
    Grava_log_consulta($connUser->connUser(),$LOG,'Consulta CNPJ');   
  
        if($row['LOG_ATIVO']=='S')
        {  
                if( $passou!=1)
                { 
                                //valida campo
                               $cod_men= fnmemoria($connUser->connUser(),'true',$DADOSLOGIN->dadosLogin->login,'Consulta Cadastro',$row['COD_EMPRESA']);

                                   $dadosbase=fn_consultaBase($connUser->connUser(),'',trim($CNPJ),'','','',$row['COD_EMPRESA']);   
                                     //busca retorno profissão
                                   $bus_PROFISS = "select * from profissoes where COD_PROFISS=".$dadosbase[0]['profissao'];
                                   $profiss_ret = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$bus_PROFISS)); 

                                    if($dadosbase[0]['COD_CLIENTE'] != 0){   
                                      //consulta creditos
                                              $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$dadosbase[0]['COD_CLIENTE'].")";
                                              $retSaldo = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$consultasaldo));
                                              if($dadosbase[0]['sexo']=='1'){$sexo='M';}else{$sexo='F';}

                                              fnmemoriafinal($connUser->connUser(),$cod_men);
                                                mysqli_close($connAdm->connAdm());   
                                                mysqli_close($connUser->connUser()); 
                                                //url extrato
                                                 $urlextrato=fnEncode($DADOSLOGIN->dadoslogin->login.';'
                                                                        .$DADOSLOGIN->dadoslogin->senha.';'
                                                                        .$DADOSLOGIN->dadoslogin->idloja.';'
                                                                        .$DADOSLOGIN->dadoslogin->idmaquina.';'
                                                                        .$row['COD_EMPRESA'].';'
                                                                        .$DADOSLOGIN->dadoslogin->codvendedor.';'
                                                                        .$DADOSLOGIN->dadoslogin->nomevendedor.';'
                                                                        .$CNPJ
                                                                         );

                                                      //Carrega dados da base de dados 
                                                      return array('ConsultaCadastroPorCNPJResult'=> array(
                                                                                    'nome' => $dadosbase[0]['nome'],
                                                                                    'cartao' =>$dadosbase[0]['cartao'] ,
                                                                                    'cpf' =>$dadosbase[0]['cpf'],
                                                                                    'rg' => $dadosbase[0]['rg'],
                                                                                    'cnpj'=>$dadosbase[0]['cnpj'],
                                                                                    'nomeportador'=>'',
                                                                                    'grupo' => $dadosbase[0]['grupo'],
                                                                                    'sexo' =>$sexo,
                                                                                    'datanascimento' => $dadosbase[0]['datanascimento'],
                                                                                    'estadocivil' => $dadosbase[0]['estadocivil'],
                                                                                    'telresidencial' =>$dadosbase[0]['telresidencial'],
                                                                                    'telcelular' =>$dadosbase[0]['telcelular'],
                                                                                    'telcomercial' =>$dadosbase[0]['telcomercial'],
                                                                                    'email' => $dadosbase[0]['email'],
                                                                                    'profissao' =>$profiss_ret['DES_PROFISS'],
                                                                                    'clientedesde' => $dadosbase[0]['clientedesde'],
                                                                                    'tipocliente' =>$dadosbase[0]['tipocliente'],                                                                                    
                                                                                    'endereco' => $dadosbase[0]['endereco'],
                                                                                    'numero' => $dadosbase[0]['numero'],
                                                                                    'complemento' =>$dadosbase[0]['complemento'],
                                                                                    'bairro' =>$dadosbase[0]['bairro'],
                                                                                    'cidade' =>$dadosbase[0]['cidade'],
                                                                                    'estado' =>$dadosbase[0]['estado'],
                                                                                    'cep' => $dadosbase[0]['cep'],
                                                                                    'cartaotitular' =>$dadosbase[0]['cartaotitular'],
                                                                                    'bloqueado'=>'So',    
                                                                                    'motivo'=>'S0',                                                                                   
                                                                                    'dataalteracao' =>$dadosbase[0]['dataalteracao'] ,
                                                                                    'adesao'=>'',
                                                                                    'saldo' =>fnformatavalorretorno($retSaldo['TOTAL_CREDITO'],$decimal),
                                                                                    'saldoresgate' =>fnformatavalorretorno($retSaldo['CREDITO_DISPONIVEL'],$decimal),
                                                                                    'msgerro' => 'OK',
                                                                                    'urlextrato' =>"http://extrato.bunker.mk?key=$urlextrato",
                                                                                    'msgcampanha' =>'',                                                                                    
                                                                                    'retornodnamais' => '0'
                                                       ));
                                    }else{return array('ConsultaCadastroPorCNPJResult'=>array('msgerro'=>';-O Oh não! Empresa não cadastrada!'));}        
                }      
                else
                {
                return array('return'=>array('ConsultaCadastroPorCNPJResult' =>"Id_cliente não confere com o cadastro!"));
                } 
        }else{
        return array('return'=>array('ConsultaCadastroPorCNPJResult'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));  
        }        
    }else{return array('return'=>array('ConsultaCadastroPorCNPJResult'=>'Erro Na autenticação'));}
   
}

