<?php
function InserirVenda ($dados) {
	
	/* if($dados->dadoslogin->idcliente=='209')
    {
         
		  return array('InserirVendaResult'=>array('msgerro' => 'aqui é 05397393754'));  
    } */
    rtrim(trim(require_once('../../../_system/Class_conn.php')));
    rtrim(trim(require_once('../../func/function.php'))); 
    include './functionbridge/functionbridge.php';
    
        $dataformat1=date('Y-m-d', strtotime(str_replace('/','-',$dados->venda->datahora))); 
        $datahora1=DateTime::createFromFormat('Y-m-d', $dataformat1);
        $datahora1=$datahora1->format('Y-m-d');
        $date1 = new DateTime($datahora1);
        $date2 = new DateTime(date('Y-m-d'));
        $interval = $date1->diff($date2);               
        if($interval->days >= '2')
        {    
            return    array('InserirVendaResult'=>array(
                                            'nome'=> 'OK',
                                            'saldo'=>'0,00',
                                            'saldoresgate'=>'0,00',
                                            'comprovante'=>'OK',
                                            'comprovante_resgate'=>'ok',                                         
                                            'url'=>'ok',
                                            'msgerro'=>'OK'
                                        )
                    );
        }    
    
    
     $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><InserirVendaResult></InserirVendaResult>");
     
            
       $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','".$dados->dadoslogin->idcliente."','','')";
       $buscauser=mysqli_query($connAdm->connAdm(),$sql);
       $row = mysqli_fetch_assoc($buscauser);
       //Limpa caracteres do cpf/cartao 
      $cartao= fnlimpaCPF($dados->venda->cartao);
	  
	  
	  
	  
    //verifica se o usuario esta ativo
    if($row['LOG_ESTATUS']=='N')
    {
        fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda','Usuario foi desabilitado!',$row['LOG_WS']); 
        return array('InserirVendaResult'=>array('msgerro' => 'Usuario foi desabilitado!'));  
    } 
    //===============================================      
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
        {
            
            //Numero de decimal da integradora
            
			//alterar aqui
			/*$dec=$row['NUM_DECIMAIS'];
            if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;}*/
			
			
			
            //Url Extrato
            $urlextrato=fnEncode($dados->dadoslogin->login.';'
                                .$dados->dadoslogin->senha.';'
                                .$dados->dadoslogin->idloja.';'
                                .$dados->dadoslogin->idmaquina.';'
                                .$row['COD_EMPRESA'].';'
                                .$dados->dadoslogin->codvendedor.';'
                                .$dados->dadoslogin->nomevendedor.';'
                                .$cartao
                                 );
            //if($row['COD_EMPRESA']=='60')
           // {
            //  return array('InserirVendaResult'=>array('msgerro' => $urlextrato));   
            //}    
            
            //===============================================================
           //conn user
           $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
           $cod_men=fnmemoria($connUser->connUser(),'true',$dados->dadoslogin->login,'Venda',$row['COD_EMPRESA']);
         
		    $CONFIGUNI="SELECT * FROM unidades_parametro WHERE 
														  COD_EMPRESA=".$dados->dadoslogin->idcliente." AND 
														  COD_UNIVENDA=".$dados->dadoslogin->idloja." AND LOG_STATUS='S'";
													  
			$RSCONFIGUNI=mysqli_query($connUser->connUser(), $CONFIGUNI);
			if(!$RSCONFIGUNI)
			{		  
				 fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda','erro no novo parametro da unidade'.$sql,$row['LOG_WS']); 
             
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
	  
	  
        }else{
             fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda','login e senha invalido'.$sql,$row['LOG_WS']); 
             return array('InserirVendaResult'=>array('msgerro' => 'login e senha invalido'));
        };
        //confere Id_empresa com o cadastrado na base de dados
        if ($row['COD_EMPRESA'] != $dados->dadoslogin->idcliente)
        {
             fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda','Empresa nao confere!',$row['LOG_WS']); 
             return array('InserirVendaResult'=>array('msgerro' => 'Empresa nao confere!'));
        }
        //Grava Log de envio do xml
        $arrylog=array(  'cod_usuario'=>$row['COD_USUARIO'],
                         'login'=>$dados->dadoslogin->login,
                         'cod_empresa'=>$row['COD_EMPRESA'],
                         'pdv'=>$dados->venda->id_vendapdv,
                         'idloja'=>$dados->dadoslogin->idloja,
                         'idmaquina'=>$dados->dadoslogin->idmaquina,
                         'cpf'=>$cartao,     
                         'xml'=>addslashes(file_get_contents("php://input")),
                         'tables'=>'origemvenda',
                         'cupom'=>$dados->venda->cupom,   
                         'conn'=>$connUser->connUser()
                     );
        $cod_log=fngravalogxml($arrylog);
        
      //consulta de loja
     $lojas=fnconsultaLoja($connAdm->connAdm(),$connUser->connUser(),$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$row['COD_EMPRESA']);
       
    //verifica se o valor da venda esta correto
 
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
            Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'valor dos itens nao corresponde ao valor total');
           //return array('InserirVendaResult'=>array('msgerro' => 'valor dos itens nao corresponde ao valor total'));
    }
    //==================================================
	//alterar aqui

	
                $COD_DATAWS="select FORMATO_WS from dataws  where cod_dataws=".$COD_DATAWS1;
				$COD_DATAWS=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $COD_DATAWS));
                $datahora=DateTime::createFromFormat($COD_DATAWS['FORMATO_WS'], $dados->venda->datahora);

                    if($datahora===false){
                          $return= array('InserirVendaResult'=>array('msgerro' => 'Data configurada deve ser '.$dados->venda->datahora));
                           array_to_xml($return,$xml_user_info);
                           Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Data configurada deve ser '.$dados->venda->datahora,addslashes($xml_user_info->asXML()));
                           return $return;
                    } else {
                       $datahora=$datahora->format('Y-m-d H:i:s');
                        
                    }
        //=============================================================== 
        $msg4=valida_campo_vazio($dados->venda->id_vendapdv,'id_vendapdv','string');
        if(!empty($msg4)){
            Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$msg4);
            return array('InserirVendaResult'=>array('msgerro' => $msg4));}
        
         $msg5=valida_campo_vazio($dados->dadoslogin->idloja,'idloja','numeric');
        if(!empty($msg5)){
            Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$msg5);
            return array('InserirVendaResult'=>array('msgerro' => $msg5));}
        
        $msg6=valida_campo_vazio($dados->dadoslogin->idmaquina,'idmaquina','string');
        if(!empty($msg6)){
            Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$msg6);
             return array('InserirVendaResult'=>array('msgerro' => $msg6));} 
      //===================================================================
      //valida data de entrada    
    if( fndate($datahora)>date("Y-m-d"))
    { 
        
        $return=array('InserirVendaResult'=>array('msgerro' =>'Data da venda maior que a data atual!')); 
        array_to_xml($return,$xml_user_info);
        Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Data da venda maior que a data atual!',addslashes($xml_user_info->asXML()));
        return $return;
                          
    }   
   // verifica se a empresa esta desabilitada
    if($row['LOG_ATIVO']!='S')
    {   
        $return=array('InserirVendaResult'=>array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));
        array_to_xml($return,$xml_user_info);
        Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[',addslashes($xml_user_info->asXML()));
        return $return;
    }  
    //========================================================================
    
    if($lojas[0]['msg']!=1)
    {

      $msg='loja nao cadastrada';
      $xamls= addslashes($msg);
      fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda',$xamls,$row['LOG_WS']); 
      $return=array('InserirVendaResult'=>array('msgerro' => 'Loja não cadastrada!'));
      array_to_xml($return,$xml_user_info);
      Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls,addslashes($xml_user_info->asXML()));
      return $return;
    }
    //verifica se a loja foi delabilitada
       $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND='.$dados->dadoslogin->idloja.' AND cod_empresa='.$row['COD_EMPRESA'];
        $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
        if($lojars['LOG_ESTATUS']!='S' || $lojars['LOG_ESTATUS']=='')
        {
            $return =  array('InserirVendaResult'=>array('msgerro'=>'Loja não cadastrada!'));
            array_to_xml($return,$xml_user_info);
            Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls,addslashes($xml_user_info->asXML()));
            return $return;
        }   
    //=================================================================================================
    //verifica forma de pagamento
        $formap=utf8_encode(limitarTextoLimpo(fnAcentos(addslashes($dados->venda->formapagamento)),'150'));
        $form= "call SP_VERIFICA_FORMAPAGAMENTO(".$row['COD_EMPRESA'].",'$formap');";
        $formaret=mysqli_query($connUser->connUser(),$form); 
        $formapretorno=mysqli_fetch_assoc($formaret);  
    //===========================================
    
    //se o cliente nao existir não gera a venda
    $dadosbase=fn_consultaBase($connUser->connUser(),$cartao,'',$cartao,'','',$row['COD_EMPRESA']);   
	/*if($row['COD_EMPRESA']=='209')
	{
		     return array('InserirVendaResult'=>array('msgerro' => "aqui_$cartao"));
        
	}*/	
    //=======================================================
    //Cadastro com data qualit na venda 
    
    if($row['LOG_CONSEXT'] == 'S')
    {
        
        $doc= fnCompletaDoc($cartao);       
        if($cartao!=0 || $cartao!="00000000000" || strlen($doc)>=8 && strlen($doc)<=11 )
        {
    
                             
            if($dadosbase[0]['COD_CLIENTE']<='0' || $dadosbase[0]['COD_CLIENTE']=="")
            {   
                     
                if($row['COD_CHAVECO'] == 1 || $row['COD_CHAVECO'] == 5)
                {
                    include '../../func/FunCadAuto.php';
                    $DadosBusCad=array( 'cartao'=>$doc,
                                        'login'=>$dados->dadoslogin->login,
                                        'senha'=>$dados->dadoslogin->senha,
                                        'idloja'=>$dados->dadoslogin->idloja,
                                        'idmaquina'=>$dados->dadoslogin->idmaquina,
                                        'idcliente'=>$dados->dadoslogin->idcliente,
                                        'codatendente'=>$dados->dadoslogin->codatendente,
                                        'codvendedor'=>$dados->dadoslogin->codvendedor,
                                        'nomevendedor'=>$dados->dadoslogin->nomevendedor,
                                        'connuser'=>$connUser->connUser()
                                       ); 
                    $dadosbase= FnCadAuto($DadosBusCad);   
                    //$dadosbase=fn_consultaBase($connUser->connUser(),$cartao,'',$cartao,'','',$row['COD_EMPRESA']);   
                    
                    if ( valida_cpf($doc) ) {
                        Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Cadastro inserido automaticamente na venda....','Cadastro inserido automaticamente na venda....');

                    }else{
                        Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'CPF DIGITADO É INVALIDO....','CPF DIGITADO É INVALIDO....');
                    }   
                 
                }
            } 
        }    
    }
    
    //===============================fim ===========
    if($row['LOG_AUTOCAD']=='N')
    { 
        if($dadosbase[0]['contador'] == 0)
        {
             $msg='Cartão '.$dados->venda->cartao.' não encontrado.';
             $xamls= addslashes($msg);
             fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda',$xamls,$row['LOG_WS']); 
             $return=array('InserirVendaResult'=>array('msgerro' => $msg));
             array_to_xml($return,$xml_user_info);
             Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls,addslashes($xml_user_info->asXML()));
             return $return;
			 
			 /*if($cartao=='' || $dados->dadoslogin->idcliente=='209')
			 {
				return array('InserirVendaResult'=>array('msgerro' =>  addslashes(file_get_contents("php://input"))));
			 }	*/ 
			 
        } 
    } 
    //============================================================================================
    //verifica se o saldo resgate é  maior que o disponivel
                
    if((float)fnFormatvalor($dados->venda->valor_resgate,$dec) > '0.00')
    {

        if($cartao > 0)
        {   

            //=====busca saldo do clientes 
            $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$dadosbase[0]['COD_CLIENTE'].")";
            $rsrown=mysqli_query($connUser->connUser(),$consultasaldo);
            $retSaldo = mysqli_fetch_assoc($rsrown);
            mysqli_free_result($retSaldo);
            mysqli_next_result($connUser->connUser());


           //============================================================================
            //busca valor de configurados para resgates
            $regraresgate='SELECT round(min(CR.NUM_MINRESG),2) as NUM_MINRESG,MAX(CR.PCT_MAXRESG) as PCT_MAXRESG,C.LOG_ATIVO FROM campanha C
                            INNER JOIN CAMPANHARESGATE CR ON CR.COD_CAMPANHA=C.COD_CAMPANHA
                            WHERE LOG_ATIVO="S" AND C.cod_empresa='.$dados->dadoslogin->idcliente;
        
            $resgresult=mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$regraresgate));
            //==========================================================================
            $arrayvalorres=array('vl_venda'=> fnFormatvalor($dados->venda->valortotal,$dec,$dados->dadoslogin->idcliente),
                                 'PCT_MAXRESG'=> $resgresult['PCT_MAXRESG']);
          //calcula porcentagem de resgate
          $percentual=($arrayvalorres['vl_venda'] * $arrayvalorres['PCT_MAXRESG'])/100;
           

            if($resgresult['COD_CAMPANHA'] !='' )
            {    
                    if(fnFormatvalor($dados->venda->valor_resgate,$dec) > $percentual)
                    {
                        $return=array('InserirVendaResult'=>array('msgerro' => 'Valor do resgate excede o máximo permitido para essa venda (R$ '.fnformatavalorretorno($percentual,$dec).')'));
                        array_to_xml($return,$xml_user_info);
                        Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Valor Resgate maior que o permitido',addslashes($xml_user_info->asXML()));
                        return $return;
                    }
                    if(fnFormatvalor($dados->venda->valor_resgate,$dec) < $resgresult['NUM_MINRESG'])
                    {
                        $return=array('InserirVendaResult'=>array('msgerro' => 'Só é possível realizar resgates a partir de R$ '.fnformatavalorretorno($resgresult['NUM_MINRESG'],$dec)));
                        array_to_xml($return,$xml_user_info);
                        Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Valor Resgate não pode ser menor que o permitido',addslashes($xml_user_info->asXML()));
                        return $return;
                    }
                     //saldo menor que o disponivel 
                    if( fnFormatvalor($dados->venda->valor_resgate,$dec) > fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'],$dec,$dados->dadoslogin->idcliente))
                    {

                        $return=array('InserirVendaResult'=>array('msgerro' =>'Saldo insuficiente para resgate. Saldo para resgate é de R$ '.fnformatavalorretorno($retSaldo['CREDITO_DISPONIVEL'],$dec)));
                        array_to_xml($return,$xml_user_info);
                        Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Valor Resgate maior que o disponivel',addslashes($xml_user_info->asXML()));
                        return $return;
                    }

                      //====================================================================================
            } 
        }    
    }   
  //===========================================================================  
   // 
    if($row['TIP_REGVENDA']=='4')
    { 
        //verifica se o PDV ja foi inserido
         $CODPDV="SELECT COUNT(*) as venda,
                         DAT_CADASTR_WS,
                         cod_vendapdv,
                         cod_univend,
                         cod_cliente FROM VENDAS 
                WHERE COD_EMPRESA='".$dados->dadoslogin->idcliente."' AND 
                      cod_cliente = '".$dadosbase[0]['COD_CLIENTE']."' and
                      cod_univend = '".$dados->dadoslogin->idloja."' and
                COD_VENDAPDV='".$dados->venda->id_vendapdv."'";
         $row_CODPDV = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$CODPDV));
         
    }elseif($row['TIP_REGVENDA']=='3'){ 
        $row_CODPDV['venda']='1';
        
    }elseif($row['TIP_REGVENDA']=='1'){
        //verifica se o PDV ja foi inserido
         $CODPDV="SELECT COUNT(*) as venda,DAT_CADASTR_WS FROM VENDAS WHERE COD_EMPRESA='".$dados->dadoslogin->idcliente."' and COD_VENDAPDV='".$dados->venda->id_vendapdv."'";
         $row_CODPDV = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$CODPDV));
    }else{
        //verifica se o PDV ja foi inserido
         $CODPDV="SELECT COUNT(*) as venda,DAT_CADASTR_WS FROM VENDAS WHERE COD_EMPRESA='".$dados->dadoslogin->idcliente."' and COD_VENDAPDV='".$dados->venda->id_vendapdv."'";
         $row_CODPDV = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$CODPDV));
    }
    
    
    if($row_CODPDV['venda'] != 0) {
            $msg="Venda de número '".$dados->venda->id_vendapdv."' já foi inserida para esse cliente em ".$row_CODPDV['DAT_CADASTR_WS'].".";
            $xamls= addslashes($msg);
          
            $return=array('InserirVendaResult'=>array('msgerro' => $msg));
            array_to_xml($return,$xml_user_info);
            Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls,addslashes($xml_user_info->asXML()));
            return $return;
    }
   //============================================= 
    //critica hora
    if($dados->venda->cartao != 0)
    {  
                              
        //não critica data hora se for igual a 2 
        if($row['TIP_REGVENDA']=='1')
        {  //verifica se a data/hora ja foi cadastrada
            $dataH='SELECT count(*) as DAT_HORA from vendas where  COD_EMPRESA="'.$dados->dadoslogin->idcliente.'" and
                     COD_CLIENTE='.$dadosbase[0]['COD_CLIENTE'].' and 
                     cast(DAT_CADASTR_WS as datetime)="'.$datahora.'"';
            $row_DATAH= mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$dataH));
          
                if($row_DATAH['DAT_HORA'] != 0){
                      $msg='Há uma venda para esse cliente em '.$datahora;
                      $xamls= addslashes($msg);
                      
                      $return=array('InserirVendaResult'=>array('msgerro' => $msg));
                      array_to_xml($return,$xml_user_info);
                      Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls,addslashes($xml_user_info->asXML()));
                     return $return; 
                }
        }
    }
    //================================
   
   //alterar aqui
 //verifica vendedor
 	
        if($LOG_CADVENDEDOR=='2')
        {    
            if($dados->venda->codvendedor!="")
            {   
                        $nomevendedor=$dados->venda->codvendedor;
                        $codvendedor_externo=$dados->venda->codvendedor;
            }else{
                     $nomevendedor='0';
                     $codvendedor_externo='0';

            }
        }elseif ($LOG_CADVENDEDOR=='1') {
                if($dados->dadoslogin->codvendedor!="")
                {   
                     $nomevendedor=$dados->dadoslogin->nomevendedor;
                     $codvendedor_externo=$dados->dadoslogin->codvendedor;
                }else{
                         $nomevendedor='0';
                         $codvendedor_externo='0';

                }

        }
    $cod_atendente=fnatendente($connAdm->connAdm(),$dados->venda->codatendente,$dados->dadoslogin->idcliente,$dados->dadoslogin->idloja,$dados->venda->codatendente);
   
    $NOM_USUARIO= utf8_encode(fnAcentos(rtrim(trim($nomevendedor))));
    $cod_vendedor=fnVendedor($connAdm->connAdm(),$NOM_USUARIO,$dados->dadoslogin->idcliente,$dados->dadoslogin->idloja,$codvendedor_externo);

//===========================
 
          
        if($dadosbase[0]['contador'] >= 1)
        { 
           //ajustar o resultado aqui.
            $nome=$dadosbase[0]['nome'];
            $COD_CLIENTE=$dadosbase[0]['COD_CLIENTE'];
            $datanascimento=$dadosbase[0]['datanascimento'];
            $sexo=$dadosbase[0]['sexo'];
            $cpf=$dadosbase[0]['cpf'];
            $cartao=$dadosbase[0]['cartao'];
            $telefone=$dadosbase[0]['telcelular'];
 
        }else{
            //Cadastro automatico
            if($row['LOG_AUTOCAD']=='S')
            { 
               // $cartao=$InserirVenda['cartao'];
                $datanascimento=is_Date(date('d/m/Y'));
                $sexo=3; 
                $nome="cliente ".$cartao;


               //cadastrastro de cliente que nao existe
               $cad_cliente = "CALL SP_ALTERA_CLIENTES_WS('".$row['COD_EMPRESA']."',
                                                           '".$nome."',
                                                           '".$row['COD_USUARIO']."',
                                                           '".$cartao."',
                                                           '".$datanascimento."',
                                                           '".$sexo."',
                                                           '".$cartao."',
                                                           'F',
                                                           '".$cod_vendedor."',
                                                           'CAD'
                                                        )";
               $rsCliente=mysqli_query($connUser->connUser(),$cad_cliente);
                
              //if($cartao=='01734200014'){return array('InserirVendaResult'=>array('msgerro' => var_export($cad_cliente,TRUE)));}   
              if (!$rsCliente)
              {
                  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                  try {mysqli_query($connUser->connUser(),$cad_cliente);} 
                  catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                  $msg="Error description SP_ALTERA_CLIENTES_WS: $msgsql";
                  $xamls= addslashes($msg);
                  fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda',$xamls,$row['LOG_WS']); 
                  return array('InserirVendaResult'=>array('msgerro' => 'Erro ao inserir clientes!'));
                  
                 
                } else {
                  $row_cliente = mysqli_fetch_assoc($rsCliente);
                    
                  //$COD_CLIENTE=$row_cliente['COD_CLIENTE'];
                    $dadosbase=fn_consultaBase($connUser->connUser(),$cartao,'',$cartao,'','',$row['COD_EMPRESA']); 
                    $COD_CLIENTE=$dadosbase[0]['COD_CLIENTE'];
                   
                  $msg='Cliente inserido ';
                  $xamls= addslashes($msg);
                 // Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls);
                  $updatecartao="update  geracartao set log_usado='S',cod_USUALTE=".$row['COD_USUARIO']." where num_cartao=".$cartao; 
                  mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$updatecartao));

                  $msg='cartao alterado';
                  $xamls= addslashes($msg);
                 // Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls);
                } 
           
        
     //se o cadastro automatico for inativo      
    }elseif($row['LOG_AUTOCAD']=='N'){
        $COD_CLIENTE=$dadosbase[0]['COD_CLIENTE'];

    }
}
//===============================    
  //inicio do inserir venda
    if($row['TIP_CONTABIL']==''){$TIP_CONTABIL=0;}else{$TIP_CONTABIL=$row['TIP_CONTABIL'];}
    if($dados->venda->cartao==0)
    {
        $cad_venda = "CALL SP_INSERE_VENDA_WS_AVULSO( 0,
                                               0,
                                               '".$row['COD_EMPRESA']."', 
                                               '".$row['COD_CLIENTE_AV']."',
                                               '1',
                                               '3',
                                               '".$lojas[0]['COD_UNIVEND']."',
                                               '".$formapretorno['COD_FORMAPA']."',
                                               '".fnFormatvalor($dados->venda->valortotal,$dec,$dados->dadoslogin->idcliente)."',
                                               0,
                                               '".fnFormatvalor($dados->venda->valor_resgate,$dec,$dados->dadoslogin->idcliente)."',
                                               0,
                                               '".$dados->venda->id_vendapdv."',
                                               '".$row['COD_USUARIO']."',
                                               '".$TIP_CONTABIL."',
                                               ".$lojas[0]['COD_MAQUINA'].",
                                               '".$dados->venda->cupom."',
                                               '".$cod_vendedor."',
                                               '".$datahora."',
                                               '".$cod_atendente."'    
                                               );";
        $rewsinsert=mysqli_query($connUser->connUser(),$cad_venda);
        if (!$rewsinsert)
        {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
            try {mysqli_query($connUser->connUser(),$cad_venda);} 
            catch (mysqli_sql_exception $e) {$msgsql= $e;} 

            $msg="Error description venda avulsa: $msgsql";
            $xamls= addslashes($msg);
           fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda',$msg,$row['LOG_WS']); 
                  
        } else {$row_venda = mysqli_fetch_assoc($rewsinsert);}
    }else{
        $cad_venda = "CALL SP_INSERE_VENDA_WS( 0,
                                               '".$row['COD_EMPRESA']."', 
                                               '".$COD_CLIENTE."',
                                               '1',
                                               '3',
                                               '".$lojas[0]['COD_UNIVEND']."',
                                               '".$formapretorno['COD_FORMAPA']."',
                                               '".fnFormatvalor($dados->venda->valortotal,$dec,$dados->dadoslogin->idcliente)."',
                                               0,
                                               '".fnFormatvalor($dados->venda->valor_resgate,$dec,$dados->dadoslogin->idcliente)."',
                                               0,
                                               '".$dados->venda->id_vendapdv."',
                                              '".$row['COD_USUARIO']."',
                                               '".$TIP_CONTABIL."',
                                               ".$lojas[0]['COD_MAQUINA'].",
                                               '".$dados->venda->cupom."',
                                               '".$cod_vendedor."',
                                               '".$datahora."',
                                               '".$cod_atendente."'       
                                                );";
        $rewsinsert=mysqli_query($connUser->connUser(),$cad_venda);
        if (!$rewsinsert)
        {mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
            try {mysqli_query($connUser->connUser(),$cad_venda);} 
            catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                $msg="Error description venda: $msgsql";
                $xamls= addslashes($msg);
                fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda',$xamls,$row['LOG_WS']); 
                return array('InserirVendaResult'=>array('msgerro' => 'OPS temos problemas na venda'));

        } else {$row_venda = mysqli_fetch_assoc($rewsinsert);}
    }
    //=============================================================fim do inserir venda
//inserir itens
//se item venda for menor que um.   
   /*if($cartao=='14871124835'){return array('InserirVendaResult'=>array('msgerro' => var_export($dados,TRUE)));}  
   */
    if (count($dados->venda->items->vendaitem->codigoproduto)==1)
    { 

          $VAL_TOTITEM=fnFormatvalor($dados->venda->items->vendaitem->valor,$dec)/fnFormatvalor($dados->venda->items->vendaitem->quantidade,$dec,$dados->dadoslogin->idcliente);
         $produto=addslashes(utf8_encode(fnAcentos($dados->venda->items->vendaitem->produto)));
         $produto=limitarTexto($produto,150);
         $itemvendainsert="call SP_INSERE_ITENS_WS($COD_CLIENTE,
                                                  '".$row['COD_EMPRESA']."',
                                                  '".$dados->venda->items->vendaitem->id_item."',
                                                   ".$row_venda['COD_VENDA'].",
                                                   '".$dados->venda->items->vendaitem->codigoproduto."',
                                                   '".$produto."',    
                                                   0,
                                                   '".fnFormatvalor($dados->venda->items->vendaitem->quantidade,$dec,$dados->dadoslogin->idcliente)."',
                                                   '".$VAL_TOTITEM."',
                                                   '".fnFormatvalor($dados->venda->items->vendaitem->valor,$dec,$dados->dadoslogin->idcliente)."',
                                                   '".$dados->dadoslogin->idloja."',
                                                   '0'  
                                                    )";
          
            $itemteste=mysqli_query($connUser->connUser(),$itemvendainsert);
            if (!$itemteste)
            {mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                try {
                     mysqli_query($connUser->connUser(),$itemvendainsert);
                     
                   } 
                catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                    $msg="Error SP_INSERE_ITENS_WS venda: $msgsql";
                    $xamls= addslashes($msg);
                    fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda',$xamls,$row['LOG_WS']); 
                    return array('InserirVendaResult'=>array('msgerro' => 'Ops temos problemas com os dados do item'));
            }   
    }else{

        foreach ($dados->venda->items->vendaitem as $key => $chave)
        {
           $VAL_TOTITEM= fnFormatvalor($chave->valor,$dec)/fnFormatvalor($chave->quantidade,$dec,$dados->dadoslogin->idcliente);
           $NOM_PROD="";
           $NOM_PROD= addslashes(utf8_encode(fnAcentos($chave->produto))); 
           $NOM_PROD=limitarTexto($NOM_PROD,150);
           $itemvendainsert="CALL SP_INSERE_ITENS_WS($COD_CLIENTE,
                                                        '".$row['COD_EMPRESA']."',
                                                        '".$chave->id_item."',
                                                        ".$row_venda['COD_VENDA'].",
                                                        '".$chave->codigoproduto."',
                                                        '".$NOM_PROD."',   
                                                          0,
                                                        '".fnFormatvalor($chave->quantidade,$dec,$dados->dadoslogin->idcliente)."',
                                                        '".$VAL_TOTITEM."',
                                                        '".fnFormatvalor($chave->valor,$dec,$dados->dadoslogin->idcliente)."',
                                                        '".$dados->dadoslogin->idloja."',
                                                        '0'      
                                                         );";
           	
            $itemteste=mysqli_query($connUser->connUser(),$itemvendainsert);
            if (!$itemteste)
            {mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                try {mysqli_query($connUser->connUser(),$itemvendainsert);} 
                catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                    $msg="SP_INSERE_ITENS_WS: $msgsql";
                    $xamls= addslashes($msg);
                   fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda',$xamls,$row['LOG_WS']); 
                    return array('InserirVendaResult'=>array('msgerro' => 'Ops temos problemas com os dados do item'));
            }
        }
    }
//===========================================================================    

 //inserir creditos
    if($dados->venda->cartao==0 || $dados->venda->cartao=='')
    {
        $msg='OK';
        $xamls= addslashes($msg);
        Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls);
    }else{
          if($dadosbase[0]['funcionario']=='S' && $row['LOG_PONTUAR']=='N' || $dadosbase[0]['LOG_ESTATUS']=='N'){
                //Grava_log($connUser->connUser(),$LOG,'Fucionario não Gera Creditos');
               $cod_creditou="UPDATE VENDAS SET COD_CREDITOU=4 WHERE COD_VENDA='".$row_venda['COD_VENDA']."'";     
               mysqli_query($connUser->connUser(), $cod_creditou);
               Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,'Fucionario não Gera Creditos');
               
            }else{
                    if($row['COD_EMPRESA']=='124' || $row['LOG_CREDAVULSO']=='S')
                    {   
                     
                        if($dados->venda->pontostotal !='')
                        {
                             
                           if(fnFormatvalor($dados->venda->pontostotal,$dec) >= '0'){
                            
                            $sql_credito=" CALL SP_CREDITO_FIXO(    '".$COD_CLIENTE."', 
                                                                    '".fnFormatvalor($dados->venda->pontostotal,$dec,$dados->dadoslogin->idcliente)."', 
                                                                    '$datahora', 
                                                                    '".$row['COD_USUARIO']."', 
                                                                    'Venda OK', 
                                                                    '1', 
                                                                    '".$lojas[0]['COD_UNIVEND']."', 
                                                                    '".$row['COD_EMPRESA']."',
                                                                    'VEN',
                                                                   '".$row_venda['COD_VENDA']."',
                                                                   '".$cod_vendedor."',
                                                                    '".fnFormatvalor($dados->venda->valor_resgate,$dec,$dados->dadoslogin->idcliente)."', 
                                                                    'CAD' )";  
                              
                            // mysqli_query($connUser->connUser(),$pontostotal);
                            
                            }
                        }
                    }else{    
                    //Calcula creditos e pontos extras
                    $sql_credito = "CALL SP_INSERE_CREDITOS_WS('".$row_venda['COD_VENDA']."',
                                                              0,      
                                                              '".$row['COD_EMPRESA']."',
                                                              '".$COD_CLIENTE."',    
                                                              1,    
                                                              1,
                                                              '".$lojas[0]['COD_UNIVEND']."',
                                                              '".$formapretorno['COD_FORMAPA']."',
                                                              '".fnFormatvalor($dados->venda->valortotal,$dec,$dados->dadoslogin->idcliente)."',
                                                              '".fnFormatvalor($dados->venda->valor_resgate,$dec,$dados->dadoslogin->idcliente)."',
                                                              0,
                                                              '".$dados->venda->id_vendapdv."',
                                                              '".$row['COD_USUARIO']."',
                                                              '".$cod_vendedor."'    
                                                              )";
                    }
                     
                    $SALDO_CLIENTE= mysqli_query($connUser->connUser(),$sql_credito);     
                    
                 //   return array('InserirVendaResult'=>array('msgerro' => $sql_credito));
                                                         
                    if (!$SALDO_CLIENTE)
                    {mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                       try {mysqli_query($connUser->connUser(),$sql_credito);} 
                       catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                           $msg="Error description venda: $msgsql";
                           $xamls= addslashes($msg);
                           Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls,'OK');
                           fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda',$xamls,$row['LOG_WS']); 
                          return array('InserirVendaResult'=>array('msgerro' => 'ERRO AO INSERIR CREDITO!'));
                          
                    } else {
                          $rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);
                           $msg="OK";
                           $xamls= addslashes($msg);
                           Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls,'OK');
                           
                    }
                    if($dados->venda->cartao==0)
                    {
                        Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls,'Cliente avulso entrou na creditos e debitos.');
                    }
            } 
    }
//=================================================================================    
//Gera comprovante para a impressão
//GERA COMPROVANTE
    fnmemoriafinal($connUser->connUser(),$cod_men); 
   
if($msg=='OK')
{  
      
         $comprovante='
                       CLIENTE: '. fnMascaraCampo($nome).'
                       Cartao: '.fnMascaraCampo($cartao).'
                       DATA: '.date("Y-m-d H:i:s").'
                       SALDO ACUMULADO: '. fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$decimal).'

                      *COMPROVANTE NAO FISCAL.*';
        if((float)fnFormatvalor($dados->venda->valor_resgate,$dec) > '0.00')
        {   
          
         $comprovante_resgate='                 
                               Cliente: '.fnMascaraCampo($nome).'
                               Cartao: '.fnMascaraCampo($cartao).'
                               Valor debitado: R$ '.fnValor($dados->venda->valor_resgate,$decimal).'
                               Data:'.date("Y-m-d H:i:s").'
                               Reconheco e autorizo o debito


                                _____________________________
                                ASSINATURA DO CLIENTE
                                SALDO ACUMULADO: R$ '.fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$decimal).'

                                *COMPROVANTE NAO FISCAL.*
                           ';
        }
}   
if($dados->dadoslogin->idcliente=='85')
{
    $saldo=fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_DISPONIVEL'],$decimal);
}else{
    $saldo=fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$decimal);
}    
 
$return=array('InserirVendaResult'=>array(
                                            'nome'=> fnMascaraCampo($nome),
                                            'saldo'=>fnMascaraCampo($saldo),
                                            'saldoresgate'=>fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_DISPONIVEL'],$decimal),
                                            'comprovante'=>$comprovante,
                                            'comprovante_resgate'=>$comprovante_resgate,
                                            //'url'=>htmlspecialchars("http://extrato.bunker.mk?key=".rawurlencode($urlextrato), ENT_COMPAT,'UTF-8', true),
                                            'url'=>"http://extrato.bunker.mk?key=".rawurlencode($urlextrato),
                                            'msgerro'=>$msg
                                        )
            );
//atualizar informação de recebinento de sms/email 
//================================================================
if($dadosbase[0]['COD_CLIENTE'] > 0 && $cartao !=0)
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
                       else{
                           $valorresgate=fnFormatvalor($dados->venda->valor_resgate,$dec,$dados->dadoslogin->idcliente);

                       }      

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
                                                                   '".$dadosbase[0]['COD_CLIENTE']."', 
                                                                   '".$dadosbase[0]['cpf']."', 
                                                                   '".addslashes(fnAcentos($dadosbase[0]['nome']))."', 
                                                                   '".$dadosbase[0]['datanascimento']."', 
                                                                   '".trim($dadosbase[0]['email'])."',
                                                                   '".$dadosbase[0]['telcelular']."',    
                                                                   '".$dadosbase[0]['sexo']."', 
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
                         $return1=array('InserirVendaResult'=>array('nome'=>$sqlfila ));      
                        //if($dadosbase[0]['telcelular']!='')
                        //{    
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
           $clas="CALL SP_PERSONA_CLASSIFICA_CADASTRO($dadosbase[0]['COD_CLIENTE'], ".$row['COD_EMPRESA'].", $cod_campanha, '".$COD_PERSONAS."',0)";
           $testesql=mysqli_query($connUser->connUser(), $clas);      
        }
    }
}
array_to_xml($return,$xml_user_info);
Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$msg,addslashes($xml_user_info->asXML()));
mysqli_close($connAdm->connAdm());   
mysqli_close($connUser->connUser());  
return $return; 
}        