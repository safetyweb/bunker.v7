<?php
include '../../_system/_functionsMain.php';
include './C5F.php';
fndebug('true');
$cod_empresa=$_GET['id'];
$cod_univend=$_GET['id2'];
$connadmin=$connAdm->connAdm();
function newvalues ($get_valor)
{
	$valor = str_replace('.', '', $get_valor);
	return $valor;
}

//verificar se tem webhooks ativo na empresa C5
$C5atvio="SELECT * from WEBHOOK WHERE TIP_WEBHOOK=6 AND LOG_ESTATUS='S' AND COD_EMPRESA=$cod_empresa GROUP BY cod_empresa";
$rwativo= mysqli_fetch_assoc(mysqli_query($connadmin, $C5atvio));
if($rwativo['LOG_ESTATUS']!='')
{     
    //verificar se o dataqualit esta ativo.
    $consultaEXT="SELECT * FROM empresas WHERE cod_empresa=$cod_empresa AND LOG_CONSEXT='S'";
    $consultaRW= mysqli_fetch_assoc(mysqli_query($connadmin, $consultaEXT));
    if($consultaRW['LOG_CONSEXT']!=''){$temdataqualit='S';}else{$temdataqualit='N';}
    //==================================================================================
    //pegando usuario para webservice
    $sqlacessos="SELECT LOG_USUARIO,DES_SENHAUS FROM usuarios WHERE cod_empresa=$cod_empresa AND LOG_ESTATUS='S' AND COD_TPUSUARIO='10'";
    $rwacessos= mysqli_fetch_assoc(mysqli_query($connadmin, $sqlacessos));
    $usuario=$rwacessos['LOG_USUARIO'];
    $senha= fnDecode($rwacessos['DES_SENHAUS']);        
    //=====================================================================
    //dados enviados via post
    $Capturajson=file_get_contents("http://externo.bunker.mk/C5/simulador.json");
    //$Capturajson=file_get_contents("php://input");
    $arrayjson=json_decode($Capturajson,true);
    $conn= connTemp($cod_empresa, '');
	//nome do programa
	$nome_programa="SELECT DES_PROGRAMA FROM SITE_EXTRATO WHERE COD_EMPRESA=$cod_empresa";
	$rsnom_programa=mysqli_fetch_assoc(mysqli_query($conn,$nome_programa));
	$nom_programa=$rsnom_programa[DES_PROGRAMA];
	/*if($cod_empresa=='173')
	{	
		$ins='INSERT INTO log_c5 (COD_EMPRESA, TIP_CAHAMDA, LOG_JSON,COD_VENDAPDV,CPF) VALUES ("'.$cod_empresa.'", "Inserirvenda", "'.addslashes($Capturajson).'","12","'.$arrayjson['Sale']['Header']['Identification']['0']['Document'].'")';
		mysqli_query($conn, $ins);
	}*/
  /*  echo '<pre>';
    print_r($arrayjson);
    echo '</pre>';
    echo '<br><br> aqui pra baixo <br><br>';
    */
    foreach ($arrayjson['Sale']['Header']['Identification'] as $key => $dadosCLIENTE)
    {
        if($dadosCLIENTE['PartnerCode'] == '35')
        {                   
            if($dadosCLIENTE['DocumentType']!="cdtCNPJ")
            {    
                $cpf="<cpf>$dadosCLIENTE[Document]</cpf>";
                $cpfpuro=$dadosCLIENTE['Document'];
            }else{
                $cpf="<cnpj>$dadosCLIENTE[Document]</cnpj>";
                $cpfpuro=$dadosCLIENTE['Document'];
            }        
        }
    }
    $NOM_MAQUINA=$arrayjson['Sale']['Header']['IdTerminal'];
    $cod_pdv=$arrayjson['ID'];
    $date_time= str_replace('T',' ',$arrayjson['Sale']['Header']['DateTimeIssue']);
    $valor_total=$arrayjson['Sale']['Total'];
    $cupomc5=$arrayjson['Sale']['Header']['IdDocument'];
    $forma_pagamento=$arrayjson['Sale']['Payments']['0']['PaymentType'];
    $vendedor=$arrayjson['Sale']['Header']['IdUser'];
    $vl_resgate=$arrayjson['Sale']['Discount'];
    $complete=$arrayjson['Response'];
    
    //================================================================================
    //verificar se o cliente é fidelidade
     $xmlbusca='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                                    <soapenv:Header/>
                                    <soapenv:Body>
                                      <fid:BuscaConsumidor>
                                        <fase>fase1</fase>
                                        <opcoesbuscaconsumidor>
                                          '.$cpf.'
                                         </opcoesbuscaconsumidor>
                                                <dadosLogin> 
                                                  <login>'.$usuario.'</login>
                                                  <senha>'.$senha.'</senha>
                                                  <idloja>'.$cod_univend.'</idloja>
                                                  <idmaquina>'.$NOM_MAQUINA.'</idmaquina>
                                                  <idcliente>'.$cod_empresa.'</idcliente>
                                                  <codvendedor>'.$vendedor.'</codvendedor>
                                                  <nomevendedor>'.$vendedor.'</nomevendedor>
                                               </dadosLogin>
                                     </fid:BuscaConsumidor>
                                    </soapenv:Body>
                                  </soapenv:Envelope>';
//echo $xmlbusca;     
$cod_retorno_busca=buscasaldo($xmlbusca);  
$cartao=$cod_retorno_busca[body][envelope][body][buscaconsumidorresponse][buscaconsumidorresponse][acao_a_cadastro][cartao];
    //==================================================
    //cadastrar o cliente que nao existe
        if($cartao!='0')
        {         
            if($cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_a_cadastro']['coderro']=='14')
            {
                $clientefildelida=$cpfpuro;
                $fideliz='1';
            }
        }else{
         $clientefildelida='0';
        }    
    //complete


if($complete=='2')
{   
    //deletear registro processado
      $delregidtro="DELETE FROM log_c5 WHERE  COD_EMPRESA=$cod_empresa AND COD_VENDAPDV='".$cod_pdv."'";
      mysqli_query($conn, $delregidtro);
    echo   '{
                "version": 1,
                "sale": null,
                "return": {
                    "code": 0,
                    "messageText": "0"
                },
                "execution": "cetCompleted",
                "interpret": null,
                "vouchersPrint": [
                ]
            }';
    exit();
} 

if($complete=='1')
{
	sleep(3);
    $log_c5="SELECT * FROM log_c5 WHERE COD_EMPRESA=$cod_empresa AND COD_VENDAPDV='".$cod_pdv."'";
    $rwlog_c5= mysqli_fetch_assoc(mysqli_query($conn, $log_c5));
    $SALDO_COMPRA=$rwlog_c5['SALDO_COMPRA'];    
       $xmlbusca='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                                    <soapenv:Header/>
                                    <soapenv:Body>
                                      <fid:BuscaConsumidor>
                                        <fase>fase1</fase>
                                        <opcoesbuscaconsumidor>
                                          '.$cpf.'
                                         </opcoesbuscaconsumidor>
                                                <dadosLogin> 
                                                  <login>'.$usuario.'</login>
                                                  <senha>'.$senha.'</senha>
                                                  <idloja>'.$cod_univend.'</idloja>
                                                  <idmaquina>'.$NOM_MAQUINA.'</idmaquina>
                                                  <idcliente>'.$cod_empresa.'</idcliente>
                                                  <codvendedor>'.$vendedor.'</codvendedor>
                                                  <nomevendedor>'.$vendedor.'</nomevendedor>
                                               </dadosLogin>
                                     </fid:BuscaConsumidor>
                                    </soapenv:Body>
                                  </soapenv:Envelope>';
   // echo $xmlbusca;   
    $cod_retorno_busca=buscasaldo($xmlbusca);
    
    /*echo '<pre>';
    print_r($cod_retorno_busca);
    echo '</pre>';*/    
    $nome=$cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_a_cadastro']['nome'];
    $primeironome=explode(' ', $nome);
    $nome=$primeironome[0];
    //habito
	$to=$cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_b_ticket_de_ofertas']['ofertashabito']['produtohabito']['coderro'];
	if (array_key_exists("coderro", $to)) 
	{
		echo 'aqui 12121212';
		$texto='Hábito de Compras';
		foreach($cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_b_ticket_de_ofertas']['ofertashabito']['produtohabito'] as $key => $dadosto)
		{		
	      $habito.= '#variavelnome\r\n                     '.$dadosto['descricao'].'\r\n                     Codigo: '.$dadosto['codigoexterno'].'';
	    } 
		$habito=str_replace('#variavelnome',$texto,$habito); 
	}else{
		unset($habito);
	}
	
    //oferta
    $chackarray=$cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_b_ticket_de_ofertas']['ofertasticket']['produtoticket'];
if (array_key_exists("0", $chackarray)) {
   foreach($cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_b_ticket_de_ofertas']['ofertasticket']['produtoticket'] as $key1 => $dadostoof)
    {
        if($dadostoof['msgerro']!='Não há Produtos no ticket!')
        {    
            $Oferta.='\r\n                   +'.$dadostoof['descricao'].' de: R$ '.$dadostoof['preco'].' POR: R$ '.$dadostoof['valorcomdesconto'].'\r\n     Codigo: '.$dadostoof['codigoexterno'].'';
        }
    }
}else{
    foreach($cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_b_ticket_de_ofertas']['ofertasticket'] as $key1 => $dadostoof)
    {
            if($dadostoof['msgerro']!='Não há Produtos no ticket!')
            {    
               $Oferta='\r\n                  +'.$dadostoof['descricao'].' de: R$ '.$dadostoof['preco'].' POR: R$ '.$dadostoof['valorcomdesconto'].'\r\n     Codigo: '.$dadostoof['codigoexterno'].'';
            }
    }
}
//OFERTA DESTAQUE
$codigoexterno=$cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_b_ticket_de_ofertas']['ofertaspromocao']['produtopromocao']['codigoexterno'];
$descricao=$cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_b_ticket_de_ofertas']['ofertaspromocao']['produtopromocao']['descricao'];
$preco=$cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_b_ticket_de_ofertas']['ofertaspromocao']['produtopromocao']['preco'];
$valorcomdesconto=$cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_b_ticket_de_ofertas']['ofertaspromocao']['produtopromocao']['valorcomdesconto'];
$destaque= '\r\n                 '.$descricao.' \r\n                 De: R$ '.$preco.'     Por: R$ '.$valorcomdesconto.'\r\n';

//saldo cliente
$saldodisponivel=$cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_h_saldo']['saldodisponivel'];
//deletear registro processado
 $delregidtro="DELETE FROM log_c5 WHERE  COD_EMPRESA=$cod_empresa AND COD_VENDAPDV='".$cod_pdv."'";
 mysqli_query($conn, $delregidtro);

echo   '{
	"version": 1,
	"sale": {
		"increase": 0,
		"discount": 0,
		"total": 0,
		"header": null,
		"payments": [],
		"paymentChange": null,
		"items": [
			{
				"itemNumber": 1,
				"unitPrice": 0,
				"increasePrice": 0,
				"packingQuantity": 0,
				"quantity": 0,
				"totalPrice": 0,
				"discountPrice": 0.05,
				"discountAmount": 1,
				"status": "sttValid"
			}
		],
		"discountCodes": [],
		"messages": {
			"customer": [],
			"user": []
		},
		"vouchersPrint": [
                        {
"text": "                        Ticket De Ofertas\n\n                            '.$nome.'\n\n                   Está esquecendo de algo?\n                      \r\n'.$habito.'\r\n\r\n              Veja ofertas personalizadas para você! \r\n        '.$Oferta.'\r\n\r\n                    OFERTA EM DESTAQUE \r\n '.$destaque.'\r\n\r\n                 Saldo Total: R$ '.$saldodisponivel.'\r\n                 Acumulado na compra: R$ '.$SALDO_COMPRA.'\r\n                 Validade das Ofertas: '.date('d/m/Y').'\r\n                 Programa de Fidelidade '.$nom_programa.'\r\n"
			}                          
                        ]
	},
	"return": {
		"code": 0,
		"messageText": "0"
	},
	"execution": "cetCompleted",
	"interpret": null,
	"vouchersPrint": []
}';
exit();
}    
    //===============================================================
    $contador='1';
    $testec='1';
    //VALOR _RESGATE
    foreach ($arrayjson['Sale']['PartitionDiscount'] as $descmarka => $valor_reg)
    {
        if($valor_reg['PartnerCode'] == 35)
        {
            $resgate=$valor_reg['Price'];
        } else {
            $DESCONTODOC=$valor_reg['Price'];
            $var='1';
        }
    }    
    
    //==FIM RESGATE==
    foreach ($arrayjson['Sale']['Items'] as $chave => $dadositem )
    {
       // echo '<pre>';
       // print_r($dadositem);
       // echo '</pre>';
        //verificar se tem desconto no item
        if($dadositem['Status']=='sttValid')
        {
            if (array_key_exists("0", $dadositem['PartitionDiscount'])) {
                foreach ($dadositem['PartitionDiscount'] as $descontoItens => $dadosdesc)
                {
                   $valorliquidoitem=$dadosdesc['Price'];
                }
            }else{
                unset($valorliquidoitem);
            }
             
            $VALUESINSERT.="(
			                 NOW(), 
							 '".$cod_empresa."', 
							 '".$cod_pdv."', 
							 '".$cpf."', 
							 '".$dadositem['UnitPrice']."', 
							 '".$dadositem['Quantity']."', 
							 '".$dadositem['SellerCode']."'
							 ),";   
           //valor liquido do item
           $valorliquidoitemdiff=$dadositem['UnitPrice']-$valorliquidoitem;
            $itemformato.='<vendaitem>
                            <id_item>'.$contador.'</id_item>
                            <produto>'.$dadositem['Description'].'</produto>
                            <codigoproduto>'.$dadositem['InternalCode'].'</codigoproduto>
                            <quantidade>'.$dadositem['Quantity'].'</quantidade>
                            <valorbruto>'.newvalues (fnValor($dadositem['UnitPrice'],2)).'</valorbruto>
                            <descontovalor>'.newvalues (fnValor($valorliquidoitem,2)).'</descontovalor>
                            <valorliquido>'.newvalues (fnValor($valorliquidoitemdiff,2)).'</valorliquido>
                            <ean>'.$dadositem['BarCode'].'</ean>
                         </vendaitem>';
            $vltotvendaBRUTO+=$valorliquidoitemdiff*fnValor($dadositem['Quantity'],0);
            $contador++;
        }    
    }
	//inserir itens para calculos
	$VALUESINSERT=RTRIM($VALUESINSERT,',');
	$INSERTBULKINGITEM="INSERT INTO log_c5_item (DAT_CADASTR, 
                         COD_EMPRESA, 
								 COD_VENDAPDV, 
								 CPF, 
								 VALOR_ITEM, 
								 QTD_ITEM, 
								 COD_VENDEDOR) 
								 VALUES 
								 $VALUESINSERT";
	mysqli_query($conn, $INSERTBULKINGITEM);
	//Buscar cod_vendedor com maior venda
	$buscavendedor="SELECT  
					SUM(VALOR_ITEM)* SUM(QTD_ITEM) AS TOTAL,
					COD_VENDEDOR
			FROM log_c5_item
			WHERE 
			       COD_VENDAPDV='".$cod_pdv."'
				AND COD_EMPRESA='".$cod_empresa."'
				GROUP BY COD_VENDEDOR ORDER BY  TOTAL DESC";
	$rwVendedor=mysqli_fetch_assoc(mysqli_query($conn, $buscavendedor));
    //echo'<pre>';
    //print_r($arraysaller);
    //echo'</pre>';
    //valor liquido venda
    if($var == '1')
    {
       $vl_liquidoDOC= $vltotvendaBRUTO-$DESCONTODOC;
    } else {
        $vl_liquidoDOC=$vltotvendaBRUTO;
    }    
    //metodo de consulta webservice
       $xmlvenda='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                                <soapenv:Header/>
                                <soapenv:Body>
                                   <fid:InsereVenda>
                                      <fase>fase1</fase>
                                      <venda>
                                         <id_vendapdv>'.$cod_pdv.'</id_vendapdv>
                                         <datahora>'.$date_time.'</datahora>
                                         <cartao>'.$clientefildelida.'</cartao>
                                         <valortotalbruto>'.newvalues (fnValor($vltotvendaBRUTO,2)).'</valortotalbruto>
                                         <descontototalvalor>'.newvalues (fnValor($DESCONTODOC,2)).'</descontototalvalor>
                                         <valortotalliquido>'.newvalues (fnValor($vl_liquidoDOC,2)).'</valortotalliquido>
                                         <valor_resgate>'.newvalues (fnValor($resgate,2)).'</valor_resgate>
                                         <cupomfiscal>'.$cupomc5.'</cupomfiscal>
                                         <formapagamento>'.$forma_pagamento.'</formapagamento>
                                         <codvendedor>'.$rwVendedor['COD_VENDEDOR'].'</codvendedor>
										  <codatendente>'.$vendedor.'</codatendente>
                                         <itens>
                                            '.$itemformato.'
                                         </itens>
                                      </venda>
                                      <dadosLogin> 
                                         <login>'.$usuario.'</login>
                                         <senha>'.$senha.'</senha>
                                         <idloja>'.$cod_univend.'</idloja>
                                         <idmaquina>'.$NOM_MAQUINA.'</idmaquina>
                                         <idcliente>'.$cod_empresa.'</idcliente>
                                         <codvendedor>'.$vendedor.'</codvendedor>
                                         <nomevendedor>'.$vendedor.'</nomevendedor>
                                      </dadosLogin>
                                   </fid:InsereVenda>
                                </soapenv:Body>
                             </soapenv:Envelope>';
//     echo $xmlvenda;
//       exit();
$vendareturnmarka=vendasC5($xmlvenda);

//saldo
$saldodisponivel=$vendareturnmarka[body][envelope][body][inserevendaresponse][inserevendaresponse][acao_h_saldo][saldodisponivel]; 
$creditovenda=$vendareturnmarka[body][envelope][body][inserevendaresponse][inserevendaresponse][acao_h_saldo][creditovenda];
//criando arquivo para salvar saldo
//inserir registro de saldo

    if($fideliz=='1')
    { 
      $butonn='{
                    "caption": "Sim",
                    "response": "1"
                },
                {
                    "caption": "Não",
                    "response": "2"
                }';
         $defaultButton= "Sim";
         $caption='\r\n Saldo Total : R$ '.$saldodisponivel.' \r\nSaldo Acumulado na venda : R$ '.$creditovenda.' \r\nDeseja Imprimir O Cupom de Descontos '.$nom_programa.'?';     
         
         $log_c5="SELECT * FROM log_c5 WHERE COD_EMPRESA=$cod_empresa AND COD_VENDAPDV='".$cod_pdv."'";
         $rwlog_c5= mysqli_fetch_assoc(mysqli_query($conn, $log_c5));
        if($rwlog_c5['COD_VENDAPDV']=='')
        {    
            $ins='INSERT INTO log_c5 (COD_EMPRESA, TIP_CAHAMDA, LOG_JSON,SALDO_COMPRA,COD_VENDAPDV) VALUES ("'.$cod_empresa.'", "Inserirvenda", "'.addslashes($arrayjson).'","'.$creditovenda.'","'.$cod_pdv.'")';
            mysqli_query($conn, $ins);
        }
    }else{
      $butonn='{
                    "caption": "Finalizar",
                    "response": "2"
                }';
      $defaultButton= "Finalizar";
      $caption="Que pena que voce não aderiu nosso Programa $nom_programa";     
         
    }
//montar os produtos do ticket de Habito
//verificar se o cliente é fidelidade
echo '{
    "version": 1,
    "sale": null,
    "return": {
        "code": 0
    },
    "execution": "cetContinue",
    "interpret": {
        "commandType": "cctMessage",
        "messageCommand": {
            "title": "'.$nom_programa.'",
            "text":"'.$caption.'",
            "defaultButton": "'.$defaultButton.'",
            "messageType": "cmtConfirmation",
            "buttons": [
                 '.$butonn.'
            ]
        },
        "options": null,
        "value": null
    },
    "vouchersPrint": [
    ]
}';    
}
	$RWdelte="DELETE FROM log_c5_item WHERE  COD_EMPRESA='".$cod_empresa."' AND COD_VENDAPDV='".$cod_pdv."'";
	mysqli_query($conn, $RWdelte);						 
