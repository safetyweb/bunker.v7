<?php
//https://adm.bunker.mk/_system/func_nexux/nexux_fast.php
//date_default_timezone_set('Etc/GMT+3');

include '../_functionsMain.php';
function envio_teste_sms($array)
{

		$campanhaSMS="SELECT 	G.COD_EMPRESA,
								G.COD_CAMPANHA,
								G.LOG_STATUS,
								G.LOG_PROCESS,
								G.HOR_ESPECIF,
								T.DES_TEMPLATE,
								C.LOG_ATIVO,
								C.DAT_INI,
								C.DAT_FIM,
								C.LOG_PROCESSA_SMS	
					FROM gatilho_sms G
					INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
					INNER JOIN mensagem_sms M ON M.COD_CAMPANHA=G.COD_CAMPANHA
					INNER JOIN template_sms T ON T.COD_TEMPLATE=M.COD_TEMPLATE_SMS
					WHERE G.cod_empresa='".$array[COD_EMPRESA]."' AND G.TIP_GATILHO='vendaOn';";
		$rwcampanhaSMS=mysqli_query($array[CONNTMP],$campanhaSMS);			
		$rscampanhaSMS=mysqli_fetch_assoc($rwcampanhaSMS);
		$COD_CAMPANHA=$rscampanhaSMS['COD_CAMPANHA'];
	
			if($rscampanhaSMS[LOG_PROCESSA_SMS]=='N' || $rscampanhaSMS[LOG_ATIVO]=='')
			{			
				
					return array('msgerro'=>'Campanha Inativa',
								  'coderro'=>'01');
					exit();
				
			};
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
						$selectCliente .= "C.NUM_CELULAR,";
					break;
					
				}
			}

			$selectCliente = rtrim($selectCliente,',');

			$sqlEnvio = "SELECT $selectCliente FROM CLIENTES C
							WHERE 
							C.COD_CLIENTE=$array[COD_CLIENTE]";
							
			$rsEnvio=mysqli_fetch_assoc(mysqli_query($array[CONNTMP],$sqlEnvio));				
		
		$NOM_CLIENTE=explode(" ", ucfirst(strtolower(fnAcentos($rsEnvio['NOM_CLIENTE']))));
		
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
		$sqlsenhasms = "SELECT * FROM senhas_parceiro apar
		INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
		WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU='16' AND apar.LOG_ATIVO='S'
		AND apar.COD_EMPRESA = '".$array[COD_EMPRESA]."'";
		$rssenhasms=mysqli_fetch_assoc(mysqli_query($array[CONNADM],$sqlsenhasms));
	//verificar o saldo antes do envio
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
		
        if($saldorestante <= '1')
        {   
			return array('msgerro'=>'Saldo insuficiente',
					     'coderro'=>'02');
	}	
	//==========================================envio			
	$id_campanha1=date('YmdHis');
   
    $enviosmsmsg=EnvioSms($rssenhasms["DES_USUARIO"],
	    				  $rssenhasms["DES_AUTHKEY"],
						  $rssenhasms['DES_CLIEXT'],
						  $TEXTOENVIO,
						  $array[TELEFONE],
						  $id_campanha1);
	$enviosmsmsg=json_decode($enviosmsmsg,true);
			
	if($enviosmsmsg[infomacoes][0]=='SMS enviado')
	{	
		//==========envio de debitos				
		$arraydebitos=array('quantidadeEmailenvio'=>'1',
		                    'COD_EMPRESA'=>$array[COD_EMPRESA],
		                    'PERMITENEGATIVO'=>'N',
		                    'COD_CANALCOM'=>'2',
		                    'CONFIRMACAO'=>'S',
		                    'COD_CAMPANHA'=>$COD_CAMPANHA,    
		                    'LOG_TESTE'=> 'N',
		                    'DAT_CADASTR'=> date('Y-m-d H:i:s'),
		                    'CONNADM'=>$array[CONNADM]
		                    ); 
		$retornoDeb=FnDebitos($arraydebitos);
		if($retornoDeb[cod_msg] =='2' ||  $retornoDeb[cod_msg]=='5' )
		{
			return array('msgerro'=>$retornoDeb[MSG_ATERACAO],
					     'coderro'=>'03');
							exit();	
		}
		
	}else{
			return array('msgerro'=>'Erro ao enviar',
			             'erroNEXUX'=>$enviosmsmsg[infomacoes][0],
					     'coderro'=>'04');
	}		
					 
	return array('msgerro'=>$retornoDeb,
					     'coderro'=>'05');
							

};

/*
[msgerro] => Array
        (
            [status] => 
            [infomacoes] => Array
                (
                    [0] 
$array=array('CONNADM'=>$connAdm->connAdm(),
			 'CONNTMP'=>connTemp(194,''),
			 'COD_EMPRESA'=>'194',
			 'LOJA'=>'TESTE',
			 'NOMECLIENTE'=>'RONE',
			 'COD_CLIENTE',
			 'TELEFONE'=>'15988034772',
			 'CASAS_DEC'
);

$teste1=envio_teste_sms($array);

echo '<pre>';
print_r($teste1);	
echo '</pre>';*/

/*

function EnvioSms_fast($KEY,$cod_campanha,$json)
{
  	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://sms.nexuscomunicacao.com/api/sms/send.aspx?chave=".rawurlencode($KEY),				 	
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 600,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>'{
                                    "tipo_envio": "short",
                                    "referencia": "'.$cod_campanha.'",
                                    "mensagens": '.$json.'
                                }',
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8"
	  ),
	));
			$response = curl_exec($curl);
			$err = curl_error($curl);
	             // $teste=curl_getinfo($curl, CURLINFO_HTTP_CODE);		
			curl_close($curl);
			if ($err) {
				$connect= "cURL Error #:" . $err;
			} else {
			  $connect=json_decode ($response,true); 
			}
			  return   $connect;
}
$CLIE_SMS_L[]=array("numero"=>'48996243831',
                    "mensagem"=>'teste1'.date('Y-m-d H:i:s'),                   
                    "DataAgendamento"=>''.date('Y-m-d H:i:s').'',
                    "Codigo_cliente"=>'cod_empresa,cod_campanha,cod_cliente'
                     );
$CLIE_SMS_L[]=array("numero"=>'15988034772',
                    "mensagem"=>'teste1 new envio para token'.date('Y-m-d H:i:s'),                   
                    "DataAgendamento"=>''.date('Y-m-d H:i:s').'',
                    "Codigo_cliente"=>'cod_empresa,cod_campanha,cod_cliente'
                     );


$envio=json_encode($CLIE_SMS_L); 
$teste=EnvioSms_fast('7e4c519f-9fcd-4ded-b544-15b8f9f8dd72','teste||11||12||13||14',$envio);


echo json_encode($teste,JSON_PRETTY_PRINT);
echo '<pre>';
print_r($teste);
print_r($CLIE_SMS_L);
echo '<pre>';

*/

//CODIGO 17 envio nexux fast
/*
 retorno
 Array
(
    [Resultado] => Array
        (
            [CodigoResultado] => 0
            [Mensagem] => 4 Mensagens agendadas para disparo.
            [Chave] => c354bfa5-58e6-44be-a95b-41fcf9a397c4
            [Cobrado] => 1
            [ValorCobrado] => 0.2
        )

    [Mensagens] => Array
        (
            [0] => Array
                (
                    [numero] => +5548996243831
                    [mensagem] => teste12021-10-20 16:37:26
                    [UniqueID] => 1288d4d0-c660-45bc-bbbd-871a43621c1e
                    [DataAgendamento] => 2021-10-20 16:37:26
                    [Custo] => 0.05
                    [idDisparo] => 6908
                    [Situacao] => 1
                    [MensagemSituacao] => 
                    [_data_agendamento] => 2021-10-20T16:37:26
                )

            [1] => Array
                (
                    [numero] => +5515988034772
                    [mensagem] => teste1 new envio para token2021-10-20 16:37:26
                    [UniqueID] => 52faca81-eac6-40e4-8e34-aba8705c3f16
                    [DataAgendamento] => 2021-10-20 16:37:26
                    [Custo] => 0.05
                    [idDisparo] => 6908
                    [Situacao] => 1
                    [MensagemSituacao] => 
                    [_data_agendamento] => 2021-10-20T16:37:26
                )

            [2] => Array
                (
                    [numero] => +5511971034446
                    [mensagem] => teste1 new envio para token2021-10-20 16:37:26
                    [UniqueID] => 69646bd8-3a81-419f-8f20-d63c4758c65a
                    [DataAgendamento] => 2021-10-20 16:37:26
                    [Custo] => 0.05
                    [idDisparo] => 6908
                    [Situacao] => 1
                    [MensagemSituacao] => 
                    [_data_agendamento] => 2021-10-20T16:37:26
                )

            [3] => Array
                (
                    [numero] => +5516997970129
                    [mensagem] => teste1 new envio para token2021-10-20 16:37:26
                    [UniqueID] => f2fa41ad-ae3c-4e66-bf99-8f8757cf9405
                    [DataAgendamento] => 2021-10-20 16:37:26
                    [Custo] => 0.05
                    [idDisparo] => 6908
                    [Situacao] => 1
                    [MensagemSituacao] => 
                    [_data_agendamento] => 2021-10-20T16:37:26
                )

        )

)
  
 */