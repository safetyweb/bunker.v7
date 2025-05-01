<?php
include '../../_system/_functionsMain.php';
include './C5F.php';
$cod_empresa=$_GET['id'];
$cod_univend=$_GET['id2'];
$connadmin=$connAdm->connAdm();

//verificar se tem webhooks ativo na empresa C5
$C5atvio="SELECT * from WEBHOOK WHERE TIP_WEBHOOK=6 AND LOG_ESTATUS='S' AND COD_EMPRESA=$cod_empresa GROUP BY cod_empresa";
$rwativo= mysqli_fetch_assoc(mysqli_query($connadmin, $C5atvio));
if($rwativo['LOG_ESTATUS']!='')
{     
    //verificar se o dataqualit esta ativo.
  /*  $consultaEXT="SELECT * FROM empresas WHERE cod_empresa=$cod_empresa AND LOG_CONSEXT='S'";
    $consultaRW= mysqli_fetch_assoc(mysqli_query($connadmin, $consultaEXT));
    if($consultaRW['LOG_CONSEXT']!=''){$temdataqualit='S';}else{$temdataqualit='N';}*/
    //==================================================================================
    //pegando usuario para webservice
    $sqlacessos="SELECT LOG_USUARIO,DES_SENHAUS FROM usuarios WHERE cod_empresa=$cod_empresa AND LOG_ESTATUS='S' AND COD_TPUSUARIO='10'";
    $rwacessos= mysqli_fetch_assoc(mysqli_query($connadmin, $sqlacessos));
    $usuario=$rwacessos['LOG_USUARIO'];
    $senha= fnDecode($rwacessos['DES_SENHAUS']);
    //=====================================================================
    //dados enviados via post
   // $Capturajson=file_get_contents("http://externo.bunker.mk/C5/simulador.json");
    $Capturajson=file_get_contents("php://input");
    $arrayjson=json_decode($Capturajson,true);
    $conn= connTemp($cod_empresa, '');

   /*if($cod_empresa=='173')
	{	
		$ins='INSERT INTO log_c5 (COD_EMPRESA, TIP_CAHAMDA, LOG_JSON,COD_VENDAPDV,CPF) VALUES ("'.$cod_empresa.'", "CONSULTA", "'.addslashes($Capturajson).'","12","'.$arrayjson['Sale']['Header']['Identification']['0']['Document'].'")';
		mysqli_query($conn, $ins);
	}*/
   
    $NOM_MAQUINA=$arrayjson['Sale']['Header']['IdTerminal'];
    $cod_pdv=$arrayjson['ID'];
    $date_time= str_replace('T',' ',$arrayjson['Sale']['Header']['DateTimeIssue']);
    $valor_total=$arrayjson['Sale']['Total'];
    $cupomc5=$arrayjson['Sale']['Header']['IdDocument'];
    $forma_pagamento=$arrayjson['Sale']['Payments']['0']['PaymentType'];
    $vendedor=$arrayjson['Sale']['Header']['IdUser'];
    $vl_resgate=$arrayjson['Sale']['Discount'];
    $complete=$arrayjson['Response'];
    if (array_key_exists("0", $arrayjson['Sale']['Header']['Identification'])) {
    
        foreach ($arrayjson['Sale']['Header']['Identification'] as $key => $dadosCLIENTE)
        {
            //echo '<pre>';
            //print_r($dadosCLIENTE);
            //echo '</pre>';
            
            if($dadosCLIENTE['PartnerCode'] == '35')
            {  
                if($dadosCLIENTE['DocumentType']!="cdtCNPJ")
                {    
                    $cpf="<cpf>$dadosCLIENTE[Document]</cpf>";
                }else{
                    $cpf="<cnpj>$dadosCLIENTE[Document]</cnpj>";
                }
               $fideliz='1';
            }  
        }
    } else {
        echo '{
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
if($fideliz!='1')
{
    echo '{
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
$cod_retorno_busca=buscasaldo($xmlbusca);     
$saldo=$cod_retorno_busca['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_h_saldo']['saldodisponivel'];
//===============================================================
if($complete=='1') 
{
    
 echo '{
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
            "title": "Parabéns você é cliente fidelidade",
            "text": "\r\nSeu Saldo Atual é : R$ '.$saldo.'\r\n",
            "defaultButton": "PROXIMO",
            "messageType": "cmtConfirmation",
            "buttons": [
                {
                    "caption": "PROXIMO",
                    "response": "1"
                }
            ]
        },
        "options": null,
        "value": null
    },
    "vouchersPrint": [
    ]
}'; 
}