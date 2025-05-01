<?php

//include '../_functionsMain.php';
//include './func_transacional.php';
function envio_fast_sms($array)
{

    //verificar se o cliente esta em blacklist e ou optout de sms
  /*  $optoutcliente="SELECT LOG_SMS FROM clientes WHERE cod_empresa='".$array[COD_EMPRESA]."' AND COD_CLIENTE=$array[COD_CLIENTE] AND LOG_SMS='S';";
    $rwoptoutcliente=mysqli_query($array[CONNTMP],$optoutcliente);
   if($rwoptoutcliente->num_rows <= '0')
   {			

        return array('msgerro'=>'Cliente não aceita receber sms!',
                     'coderro'=>'03');
        exit();

    }*/
    
		$campanhaSMS="SELECT 	G.COD_EMPRESA,
                                        G.COD_CAMPANHA,
                                        G.LOG_STATUS,
                                        G.LOG_PROCESS,
                                        G.HOR_ESPECIF,
                                        T.DES_TEMPLATE,
                                        T.COD_TEMPLATE,
                                        C.DES_CAMPANHA,
                                        C.LOG_ATIVO,
                                        C.DAT_INI,
                                        C.DAT_FIM,
                                        C.LOG_PROCESSA_SMS,
                                        G.TIP_GATILHO,
                                        G.TIP_CONTROLE
					FROM gatilho_sms G
					INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
					INNER JOIN mensagem_sms M ON M.COD_CAMPANHA=G.COD_CAMPANHA
					INNER JOIN template_sms T ON T.COD_TEMPLATE=M.COD_TEMPLATE_SMS
					WHERE
                                         C.LOG_ATIVO='S' and
                                         CURDATE() between C.DAT_INI and C.DAT_FIM and 
                                        G.cod_empresa='".$array[COD_EMPRESA]."' AND 
                                        G.TIP_GATILHO='".$array[TIP_OPERACAO]."';";
    
	   //cadFast
                            $rwcampanhaSMS=mysqli_query($array[CONNTMP],$campanhaSMS);
                            if($rwcampanhaSMS->num_rows <= '0')
                            {			

                                return array('msgerro'=>'Campanha Inativa ou fora do periodo de validade!',
                                              'coderro'=>'06');
                                exit();

                            };
                            
                            
                            $rscampanhaSMS=mysqli_fetch_assoc($rwcampanhaSMS);
                
                            $COD_CAMPANHA=$rscampanhaSMS['COD_CAMPANHA'];

                                    if($rscampanhaSMS[LOG_PROCESSA_SMS]=='N' || $rscampanhaSMS[LOG_ATIVO]=='')
                                    {			

                                        return array('msgerro'=>'Campanha Inativa',
                                                                  'coderro'=>'01');
                                        exit();

                                    };

                            if($rscampanhaSMS[TIP_GATILHO]=='vendaFast')
                            {

                                if($rscampanhaSMS[TIP_CONTROLE]!='99')
                                { 
                                       //verificar se o cliente tem vendo dentro do gatilho
                                    $countdata=$rscampanhaSMS[TIP_CONTROLE]-1;

                                    $dataop= date('Y-m-d', strtotime('- '.$countdata.' days'));

                                     //vrificar se ja foi disparado
                                    $clienteverifica="SELECT * FROM sms_lista_ret 
                                                                    WHERE cod_cliente=$array[COD_CLIENTE] AND
                                                                    cod_empresa=$array[COD_EMPRESA] and 
                                                                    date(DAT_CADASTR) >='".$dataop."' and   
                                                                   cod_campanha=".$rscampanhaSMS['COD_CAMPANHA'];
                                    $rwverificavenda= mysqli_query($array[CONNTMP], $clienteverifica);
                                    if($rwverificavenda->num_rows > '0')
                                    {
                                         return array('msgerro'=>'SMS já enviado!',
                                                       'coderro'=>'02');
                                                        exit(); 
                                    }     
                                }
                            }
                            if($rscampanhaSMS[TIP_GATILHO]=='cadFast')
                            {
                                $verificarclienteenvio="
                                    SELECT COD_CLIENTE from sms_lista_ret  
                                    WHERE Cod_empresa=$array[COD_EMPRESA] AND COD_CLIENTE=$array[COD_CLIENTE] and COD_CAMPANHA=$rscampanhaSMS[COD_CAMPANHA]";
                                if($rwverificavenda->num_rows > '0')
                                {
                                    return array('msgerro'=>'SMS já enviado!',
                                                  'coderro'=>'02');
                                                   exit(); 
                                }     
						       
                            }

                            //capturando dominio inicial
                            $sqldominio="SELECT DES_DOMINIO,COD_DOMINIO from site_extrato WHERE cod_empresa='".$array[COD_EMPRESA]."'";
                            $rsdominio=mysqli_fetch_assoc(mysqli_query($array[CONNTMP],$sqldominio));
                            $DES_DOMINIO=$rsdominio['DES_DOMINIO'];
                            $COD_DOMINIO=$rsdominio['COD_DOMINIO'];
                            //completar  as variaveis					
                                    $tagsPersonaliza=procpalavras($rscampanhaSMS[DES_TEMPLATE],$array[CONNADM]);

                                    $tags = explode(',',$tagsPersonaliza);

                                    $selectCliente = "";	

                                    for ($i=0; $i < count($tags) ; $i++) {
                                            switch($tags[$i]){

                                                    case '<#NOME>';
                                                            $selectCliente .= "C.NOM_CLIENTE,";
                                                    break;
                                                    case '<#CARTAO>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#ESTADOCIVIL>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#SEXO>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#PROFISSAO>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#NASCIMENTO>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#ENDERECO>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#NUMERO>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#BAIRRO>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#CIDADE>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#ESTADO>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#CEP>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#COMPLEMENTO>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#TELEFONE>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#CELULAR>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#SALDO>';
                                                            $selectCliente .= "FORMAT(TRUNCATE((SELECT 
                                                                                                    IFNULL((SELECT Sum(val_saldo) FROM creditosdebitos f 
                                                                                                    WHERE f.cod_cliente = cred.cod_cliente AND 
                                                                                                                    f.tip_credito = 'C' AND 
                                                                                                                    f.cod_statuscred = 1 AND 
                                                                                                                    f.tip_campanha = cred.tip_campanha AND 
                                                                                                                    (( f.log_expira = 'S' AND DATE_FORMAT(f.dat_expira, '%Y-%m-%d') >= Date_format(Now(), '%Y-%m-%d') ) OR ( f.log_expira = 'N' ) )),0)+
                                                                                                    IFNULL((SELECT Sum(val_saldo) FROM creditosdebitos_bkp g
                                                                                                    WHERE g.cod_cliente = cred.cod_cliente AND 
                                                                                                                    g.tip_credito = 'C' AND 
                                                                                                                    g.cod_statuscred = 1 AND 
                                                                                                                    g.tip_campanha = cred.tip_campanha AND 
                                                                                                                    ((g.log_expira = 'S' AND DATE_FORMAT(g.dat_expira, '%Y-%m-%d') >= Date_format(Now(), '%Y-%m-%d') ) OR (g.log_expira = 'N' ) )),0) AS CREDITO_DISPONIVEL
                                                                                                                    FROM creditosdebitos cred 
                                                                                                                    WHERE cred.cod_cliente=C.cod_CLIENTE
                                                                                                                    GROUP BY cred.cod_cliente ),$array[CASAS_DEC]),$array[CASAS_DEC],'pt_BR') AS CREDITO_DISPONIVEL,";
                                                    break;
                                                    case '<#PRIMEIRACOMPRA>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#ULTIMACOMPRA>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#TOTALCOMPRAS>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#CODIGO>';
                                                            $selectCliente .= "C.COD_CLIENTE,";
                                                    break;
                                                    case '<#CUPOMSORTEIO>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#CUPOM_INDICACAO>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#NUMEROLOJA>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#BAIRROLOJA>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#NOMELOJA>';
                                                            $selectCliente .= "(SELECT NOM_FANTASI 
                                                                                                      FROM unidadevenda UNI 
                                                                                                            WHERE UNI.COD_UNIVEND=C.COD_UNIVEND 
                                                                                                            AND UNI.COD_EMPRESA=C.COD_EMPRESA) AS NOM_FANTASI,";
                                                    break;
                                                    case '<#ENDERECOLOJA>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#TELEFONELOJA>';
                                                            $selectCliente .= "";
                                                    break;
                                                    case '<#ANIVERSARIO>';
                                                            $selectCliente .= "C.DAT_NASCIME,";
                                                    break;
                                                    case '<#DATAEXPIRA>';
                                                            $selectCliente .= "(SELECT 
                                                                                                    MIN(DAT_EXPIRA) AS DAT_EXPIRA
                                                                                                    FROM creditosdebitos 
                                                                                                        WHERE DAT_EXPIRA >= NOW() AND  cod_CLIENTE=C.cod_CLIENTE) AS DAT_EXPIRA,";
                                                    break;
                                                    case '<#DATAEXPIRAMAX>';
                                                    $selectCliente .= "(SELECT 
										        MAX(DAT_EXPIRA) AS DAT_EXPIRAMAX
									        	FROM creditosdebitos 
											    WHERE DAT_EXPIRA >= NOW() AND  cod_CLIENTE=C.cod_CLIENTE) AS DAT_EXPIRAMAX,";
                                                    break;
                                                    default:
                                                            $selectCliente .= "C.NUM_CELULAR,C.NOM_CLIENTE as cli_name,";
                                                    break;

                                            }
                                    }
									$selectCliente .= "C.NUM_CELULAR,C.NOM_CLIENTE as cli_name,";

                                    $selectCliente = rtrim($selectCliente,',');

                                    $sqlEnvio = "SELECT $selectCliente FROM CLIENTES C
                                                                    WHERE 
                                                                    C.COD_CLIENTE=$array[COD_CLIENTE]";
									
                                    $rsEnvio=mysqli_fetch_assoc(mysqli_query($array[CONNTMP],$sqlEnvio));	
                                   		

                            $NOM_CLIENTE=explode(" ", ucfirst(strtolower(fnAcentos($rsEnvio['NOM_CLIENTE']))));
                            $celular = fnLimpaDoc($rsEnvio['NUM_CELULAR']);

                            //==========================================			
                            //alterar o variavel peolo texto
                            $TEXTOENVIO=str_replace('<#TOKEN>', $senha, $rscampanhaSMS[DES_TEMPLATE]);
                            if($COD_DOMINIO=='1')
                            {	 
                            $TEXTOENVIO=str_replace('<#LINKTOKEN>', 'https://'.$DES_DOMINIO.'.mais.cash/ativacao.do', $TEXTOENVIO);
                            $TEXTOENVIO=str_replace('<#LINKATIVACAO>', 'https://'.$DES_DOMINIO.'.mais.cash/ativacao.do', $TEXTOENVIO);
                            }
                            if($COD_DOMINIO=='2')
                            {	 
                            $TEXTOENVIO=str_replace('<#LINKTOKEN>', 'https://'.$DES_DOMINIO.'.fidelidade.mk/ativacao.do', $TEXTOENVIO);
                            $TEXTOENVIO=str_replace('<#LINKATIVACAO>', 'https://'.$DES_DOMINIO.'.fidelidade.mk/ativacao.do', $TEXTOENVIO);
                            }
                            $TEXTOENVIO=str_replace('<#NOMELOJA>', fnAcentos($rsEnvio[NOM_FANTASI]), $TEXTOENVIO);                    
                            $TEXTOENVIO=str_replace('<#NOME>', $NOM_CLIENTE[0], $TEXTOENVIO);
                            $TEXTOENVIO=str_replace('<#DATAEXPIRA>',fnDataShort($rsEnvio[DAT_EXPIRA]), $TEXTOENVIO);
                            $TEXTOENVIO=str_replace('<#SALDO>', $rsEnvio[CREDITO_DISPONIVEL], $TEXTOENVIO);
                            $TEXTOENVIO=str_replace('<#ANIVERSARIO>', fnDataShort($rsEnvio[DAT_NASCIME]), $TEXTOENVIO);
                            $TEXTOENVIO=str_replace('<#CREDITOVENDA>', $array[CRED_VENDA], $TEXTOENVIO);
                            $TEXTOENVIO=str_replace('<#DATAEXPIRAMAX>', fnDataShort($rsEnvio[DAT_EXPIRAMAX]), $TEXTOENVIO);
                            
                    //senha para autenticar o envio
                            $sqlsenhasms = "SELECT apar.DES_AUTHKEY,
                                                    apar.DES_USUARIO,
                                                    apar.COD_LISTAEXT,
                                                    apar.COD_PARCOMU FROM senhas_parceiro apar
                            INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                            WHERE apar.COD_EMPRESA = $array[COD_EMPRESA]
                            AND par.COD_TPCOM='2'
                            AND apar.COD_PARCOMU IN(17,22)
                            AND apar.LOG_ATIVO = 'S'
                            ORDER BY apar.COD_PARCOMU DESC 
                            LIMIT 1";
                            $rssenhasms=mysqli_fetch_assoc(mysqli_query($array[CONNADM],$sqlsenhasms));
                    //verificar o saldo antes do envio
                          /*  $sqlcomdebt="SELECT                
                                                            pedido.TIP_LANCAMENTO 
                                                            ,pedido.COD_VENDA
                                                            ,pedido.COD_PRODUTO 
                                                            ,emp.NOM_EMPRESA
                                                            ,pedido.DAT_CADASTR 
                                                            , pedido.COD_ORCAMENTO
                                                            , canal.DES_CANALCOM
                                                            , canal.COD_CANALCOM
                                                            ,SUM(round(pedido.QTD_PRODUTO,0)) AS QTD_PRODUTO
                                                            , pedido.VAL_UNITARIO
                                                            , pedido.VAL_UNITARIO * pedido.QTD_PRODUTO AS VAL_TOTAL 
                                                            , if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
                                                            FROM pedido_marka pedido 
                                                            INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                                                            INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                                                            INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                                                            WHERE pedido.COD_ORCAMENTO > 0 AND 
                                                            pedido.COD_EMPRESA =".$array[COD_EMPRESA]." AND
                                                            PAG_CONFIRMACAO='S' and
                                                            canal.COD_TPCOM=2
                                                            GROUP BY  pedido.TIP_LANCAMENTO	            
                                                            ORDER BY pedido.TIP_LANCAMENTO desc ";
                            $rwarraysql=mysqli_query($array[CONNADM], $sqlcomdebt);
                            while($rssaldo= mysqli_fetch_assoc($rwarraysql)) 
                            {
                                    if($rssaldo['TIP_LANCAMENTO']=='D'){$DebSaldo=  $rssaldo['QTD_PRODUTO'];}
                                    if($rssaldo['TIP_LANCAMENTO']=='C') {$CredSaldo= $rssaldo['QTD_PRODUTO'];}
                            } 

                    $saldorestante=bcsub($CredSaldo, $DebSaldo);
                    $saldoDiferenca=abs(bcsub('1', $CredSaldo));	
                    */
                        $sqlcomdebt="SELECT 
                                             pedido.TIP_LANCAMENTO 
                                            ,pedido.COD_VENDA
                                            ,pedido.COD_PRODUTO 
                                            ,emp.NOM_EMPRESA
                                            ,pedido.DAT_CADASTR 
                                            , pedido.COD_ORCAMENTO
                                            , canal.DES_CANALCOM
                                            , canal.COD_CANALCOM
                                            ,SUM(round(pedido.QTD_PRODUTO,0)) AS QTD_PRODUTO
                                            ,SUM(round(pedido.QTD_SALDO_ATUAL,0)) QTD_SALDO_ATUAL
                                            , pedido.VAL_UNITARIO
                                            , pedido.VAL_UNITARIO * pedido.QTD_PRODUTO AS VAL_TOTAL 
                                            , if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
                            FROM pedido_marka pedido 
                            INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                            INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                            INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                            WHERE pedido.COD_ORCAMENTO > 0 AND 
                                   pedido.COD_EMPRESA =".$array[COD_EMPRESA]." AND
                                    PAG_CONFIRMACAO='S' and
                                    canal.COD_TPCOM=2
                                    AND pedido.QTD_SALDO_ATUAL > 0  AND 
	                            pedido.DAT_VALIDADE IS NOT NULL and
                                    pedido.TIP_LANCAMENTO ='C' 
                            	GROUP BY  pedido.TIP_LANCAMENTO	            
                           ORDER BY pedido.TIP_LANCAMENTO desc ";
        $rwarraysql=mysqli_query($array['CONNADM'], $sqlcomdebt);
        if($rwarraysql->num_rows <= 0)
        {
            $DebSaldo = '0';  
            return array('msgerro'=>'Saldo insuficiente',
                         'coderro'=>"2");
            exit();
        }else{    
            while($rssaldo= mysqli_fetch_assoc($rwarraysql)) 
            {
                 $DebSaldo = $rssaldo['QTD_SALDO_ATUAL'];

            } 
        }    
          /*  if($DebSaldo <= '1')
            {   
                return array('msgerro'=>'Saldo insuficiente',
                             'coderro'=>'02');

            }*/	
                    //==========================================envio			
               $nom_camp_msg=$rscampanhaSMS[COD_CAMPANHA].'||'.$rscampanhaSMS[COD_EMPRESA].'||'.$array[COD_CLIENTE].'||'.$rscampanhaSMS[COD_TEMPLATE];
                $msgsbtr = $TEXTOENVIO;
                $cod_parcomu_auth = $rssenhasms["COD_PARCOMU"];
                $cliente_externo = $rssenhasms["COD_LISTAEXT"];

                if($cod_parcomu_auth == 17){

                    $CLIE_SMS_L[]=array("numero"=> $celular,
                                        "mensagem"=>$msgsbtr,                   
                                        "DataAgendamento"=> "$dat_envio",
                                        "Codigo_cliente"=>"$nom_camp_msg"
                                         );

                }else{

                    $CLIE_SMS_L[]=array("from"=>$cliente_externo,
                                        "to" =>'+55'.$celular, 
                                        "mensagem"=>$msgsbtr,                   
                                        "DataAgendamento"=> "",
                                        "Codigointerno"=> base64_encode($nom_camp_msg),
                                        "codCliente"=>$array[COD_CLIENTE],
                                        "numCelular"=>$celular
                                       );  

                }

                // $CLIE_SMS_L[]=array("numero"=>$celular,
                //                     "mensagem"=>$TEXTOENVIO,                   
                //                     "DataAgendamento"=>''.date('Y-m-d H:i:s').'',
                //                     "Codigo_cliente"=>"$nom_camp_msg"
                //                      );
                                 
               $nom_camp_envio=$rscampanhaSMS[DES_CAMPANHA].'||'.$rscampanhaSMS[COD_CAMPANHA].'||'.$rscampanhaSMS[COD_EMPRESA];

               if($cod_parcomu_auth == 17){

                    $senha = $rssenhasms["DES_AUTHKEY"];

                    $testefast=EnvioSms_fast($senha,$des_campanha,json_encode($CLIE_SMS_L),'short');

                    $cod_erro_nexux=$testefast[Resultado][CodigoResultado];

                    $msgenvio=$testefast[Resultado][Mensagem];
                    $jsonputo=json_encode($testefast);

                }else{

                    $usuario = $rssenhasms['DES_USUARIO'];
                    $senha = $rssenhasms['DES_AUTHKEY'];

                    $base64= base64_encode($usuario.':'.$senha);
                    $testefast=sms_twilo($base64,$CLIE_SMS_L,$usuario,$senha);
                    $cod_erro_nexux='0';

                }

               // $testefast=EnvioSms_fast($rssenhasms["DES_AUTHKEY"],$nom_camp_envio,json_encode($CLIE_SMS_L));
              // return $testefast;
               
               // $cod_erro_nexux=$testefast[Resultado][CodigoResultado];

               if($cod_erro_nexux=='0' && $cod_parcomu_auth == 17){

                    $CHAVE_GERAL=$testefast[Resultado][Chave];
                    $CHAVE_CLIENTE=$testefast[Mensagens][0][UniqueID];
                    $msgenvio=$testefast[Resultado][Mensagem];

                }else if($cod_erro_nexux == '0' && $cod_parcomu_auth != 17){

                    $CHAVE_GERAL=$testefast[0]['account_sid'];
                    $CHAVE_CLIENTE=$testefast[0]['sid'];
                    $msgenvio = "";
                }

                if($cod_erro_nexux=='0')
                {	
                        //==========envio de debitos				
                        $arraydebitos=array('quantidadeEmailenvio'=>'1',
                                            'COD_EMPRESA'=>$rscampanhaSMS['COD_EMPRESA'],
                                            'PERMITENEGATIVO'=>'N',
                                            'COD_CANALCOM'=>'2',
                                            'CONFIRMACAO'=>'S',
                                            'COD_CAMPANHA'=>$rscampanhaSMS['COD_CAMPANHA'],    
                                            'LOG_TESTE'=> 'N',
                                            'DAT_CADASTR'=> date('Y-m-d H:i:s'),
                                            'CONNADM'=>$array[CONNADM]
                                            ); 
                         $retornoDeb=FnDebitos($arraydebitos);
                }


                            //inserindo log de registro 
                            //TIP_ENVIO = 1 SMS
                            //TIP_ENVIO = 2 email


                            $sqlInsertRel= "INSERT INTO SMS_LISTA_RET(
                                                            COD_EMPRESA,
                                                            COD_CAMPANHA,                                                                               
                                                            NOM_CLIENTE,
                                                            COD_UNIVEND,
                                                            COD_CLIENTE,
                                                            NUM_CELULAR,                                                                               
                                                            STATUS_ENVIO,
                                                            ID_DISPARO,
                                                            DES_MSG_ENVIADA	,
                                                            CHAVE_GERAL,
                                                            CHAVE_CLIENTE,
                                                            idContatosMailing,
                                                            DAT_CADASTR,
                                                            DES_STATUS
                                                            )values
                                                            ('".$rscampanhaSMS[COD_EMPRESA]."',
                                                             '".$rscampanhaSMS[COD_CAMPANHA]."',       
                                                             '".$rsEnvio['cli_name']."',
                                                             '".$array[COD_UNIVEND]."',
                                                             '".$array[COD_CLIENTE]."',
                                                             '".$celular."',
                                                             'S',
                                                             '".date('Ymd')."',
                                                             '".$TEXTOENVIO."',
                                                             '".$CHAVE_GERAL."',
                                                             '".$CHAVE_CLIENTE."',
                                                             '22',
                                                             '".date('Y-m-d H:i:s')."',
                                                             '".$msgenvio."'    
                                                            ) ; ";
                     mysqli_query($array[CONNTMP], $sqlInsertRel);
                   // return $sqlInsertRel;	


                            return array('msgerro'=>$retornoDeb,
                                         'coderro'=>"5");
                     // return $testefast;
							

};
/*
$array=array('CONNADM'=>$connAdm->connAdm(),
								 'CONNTMP'=>connTemp('238',''),
								 'COD_EMPRESA'=>'238',
								 'COD_UNIVEND'=>'97350',
								 'NOMECLIENTE'=>'Marcio Teste',
								 'COD_CLIENTE'=>'798',
								 'TELEFONE'=>'11969000158',
								 'CASAS_DEC'=>'2',
								 'TIP_OPERACAO'=>'cadFast'
					);

$result = envio_fast_sms($array);
echo "<pre>";
print_r($result);
echo "</pre>";*/
?>