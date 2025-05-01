<?php
//retorno geral
$server->wsdl->addComplexType(
    'ListaCupomRetorno',
    'complexType',
    'struct',
    'sequence',
    '',
    array('listaSaldos' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'listasaldoarray', 'type' => 'tns:listasaldoarray'),
          'listaCupons' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'Cupomarray', 'type' => 'tns:Cupomarray'),    
          'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:integer'),
          'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
        )
);

//Total ganho
$server->wsdl->addComplexType(
    'listasaldoarray',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'TotalGanho'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'TotalGanho', 'type' => 'xsd:decimal'),
        'totalResgatado'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'totalResgatado', 'type' => 'xsd:decimal'),
        'saldoDisponivel'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldoDisponivel', 'type' => 'xsd:decimal'),
        'saldoLiberar'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldoLiberar', 'type' => 'xsd:decimal'),
        'saldoBloqueado'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldoBloqueado', 'type' => 'xsd:decimal'),
        'expirados'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'expirados', 'type' => 'xsd:decimal')
         )
);

//========================
//lista cupom
$server->wsdl->addComplexType(
    'Cupomarray',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'Cupom'=>array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'ItensCupom', 'type' => 'tns:ItensCupom'),
         )
);

$server->wsdl->addComplexType(
    'ItensCupom',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'dataLancamento'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'dataLancamento', 'type' => 'xsd:string'),
        'idVenda'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'idVenda', 'type' => 'xsd:string'),
        'status'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'status', 'type' => 'xsd:string'),
        'valorCredito'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valorCredito', 'type' => 'xsd:decimal'),
        'valorResgate'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valorResgate', 'type' => 'xsd:decimal'),
        'dataExpiracao'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'dataExpiracao', 'type' => 'xsd:string'),
        'origem'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'origem', 'type' => 'xsd:string'),
        'loja'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'loja', 'type' => 'xsd:string'),
        'codigoExternoLoja'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigoExternoLoja', 'type' => 'xsd:string'),
        'campanha'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'campanha', 'type' => 'xsd:string')
         )
);
//========================

$server->register('ListaCupom',
			array(
                              'CARTAO'=>'xsd:integer',
                              'CPF'=>'xsd:integer',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('ListaCupom' => 'tns:ListaCupomRetorno'),  //output
			 $ns,         						// namespace
                        "$ns/ListaCupom",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'ListaCupom'         		// documentation
                    );


function ListaCupom ($CARTAO,$CPF,$dadosLogin) {
     include '../_system/Class_conn.php';
     include 'func/function.php'; 
     
   //  ob_start();
     $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
     $buscauser=mysqli_query($connAdm->connAdm(),$sql);
     $row = mysqli_fetch_assoc($buscauser);
     //compara os id_cliente com o cod_empresa
    
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    
    
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])){
       $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'ListaCupom',$row['COD_EMPRESA']);
       

       //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
        {
           return  array('ListaCupom'=>array('msgerro'=>'Id_cliente não confere com o cadastro!')); 
           exit();
        } 
       //VERIFICA SE A EMPRESA FOI DESABILITADA
        if($row['LOG_ATIVO']=='N'){
           return  array('ListaCupom'=>array('msgerro'=>'Oh não! A empresa foi desabilitada por algum motivo ;-[!'));
           exit();
        }
                 //VERIFICA SE O USUARIO FOI DESABILITADA
        if($row['LOG_ESTATUS']=='N'){
           return  array('ListaCupom'=>array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!'));
           exit();
        }
    //////////////////////=================================================================================================================
    
   }else{
       return  array('ListaCupom'=>array('msgerro'=>'Usuario ou senha Inválido!'));
       exit();
   }
   
 
   //limpa campo cpfcartao
   $cartao=fnlimpaCPF($CARTAO);
   $CPF=fnlimpaCPF($CPF);
   if($cartao!=""){$cartao=$cartao;}else{$cartao=$CPF;}   
   //busca de cliente
   $dadosbase=fn_consultaBase($connUser->connUser(),trim($cartao),'',trim($cartao),'','',$row['COD_EMPRESA']);   
   //=========================
 $cod_cliente=$dadosbase[0]['COD_CLIENTE'];
 $cod_empresa=$row['COD_EMPRESA'];
 
 // listaSaldos select 
 $listasadosql="SELECT (SELECT Sum(val_credito) 
        FROM   creditosdebitos 
        WHERE  cod_cliente = A.cod_cliente
            AND cod_statuscred <> 6            
            AND tip_credito = 'C')  AS TOTAL_CREDITOS,
            
        (SELECT Sum(val_credito) 
        FROM   creditosdebitos 
        WHERE  cod_cliente = A.cod_cliente 
            AND tip_credito = 'D')  AS TOTAL_DEBITOS,
            
        (SELECT Sum(val_saldo) 
        FROM   creditosdebitos 
        WHERE  cod_cliente = A.cod_cliente 
            AND tip_credito = 'C' 
            AND COD_STATUSCRED = 1 
            AND dat_expira > Now()) AS CREDITO_DISPONIVEL, 
            
        (SELECT Sum(val_credito) 
        FROM   creditosdebitos 
        WHERE  cod_cliente = A.cod_cliente 
            AND tip_credito = 'C' 
            AND COD_STATUSCRED = 2 
            AND dat_expira > Now()) AS CREDITO_ALIBERAR,
            
        (SELECT Sum(val_credito) 
        FROM   creditosdebitos 
        WHERE  cod_cliente = A.cod_cliente 
            AND tip_credito = 'C' 
            AND COD_STATUSCRED = 3 
            AND dat_expira > Now()) AS CREDITO_BLOQUEADO,
            
        (SELECT Sum(val_saldo) 
        FROM   creditosdebitos 
        WHERE  cod_cliente = A.cod_cliente 
            AND tip_credito = 'C' 
            AND COD_STATUSCRED = 4) AS CREDITO_EXPIRADOS 
      
      FROM CREDITOSDEBITOS A
      WHERE COD_CLIENTE=$cod_cliente
      AND COD_EMPRESA = $cod_empresa
      GROUP BY COD_CLIENTE";
 $resultlista=mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $listasadosql));
    if($resultlista['TOTAL_CREDITOS']==""){$TotalGanho="0.00";}else{$TotalGanho=$resultlista['TOTAL_CREDITOS'];}
    if($resultlista['TOTAL_DEBITOS']==""){$totalResgatado='0.00';}else{$totalResgatado=$resultlista['TOTAL_DEBITOS'];}
    if($resultlista['CREDITO_DISPONIVEL']==""){$saldoDisponivel='0.00';}else{$saldoDisponivel=$resultlista['CREDITO_DISPONIVEL'];}
    if($resultlista['CREDITO_ALIBERAR']==''){$saldoLiberar='0.00';}else{$saldoLiberar=$resultlista['CREDITO_ALIBERAR'];}
    if($resultlista['CREDITO_BLOQUEADO']==''){$saldoBloqueado='0.00';}else{$saldoBloqueado=$resultlista['CREDITO_BLOQUEADO'];}
    if($resultlista['CREDITO_EXPIRADOS']==''){$expirados='0.00';}else{$expirados=$resultlista['CREDITO_EXPIRADOS'];}
//==================================================================================== 
//===listaCupons
    
 $sqlcupom = "SELECT 
           A.COD_CREDITO, 
           A.COD_CAMPAPROD,
           A.COD_ITEMVEN, 
           A.COD_CLIENTE,
           A.COD_VENDA,
           A.TIP_CREDITO, 
           A.DAT_CADASTR, 
           A.DAT_LIBERA,
           A.LOG_EXPIRA,
           A.DAT_EXPIRA,
           A.TIP_PONTUACAO,
           A.VAL_PONTUACAO,
           A.VAL_CREDITO,
           A.VAL_SALDO, 
           A.COD_STATUSCRED,
           H.DES_STATUSCRED,
           A.COD_CAMPANHA,
           A.TIP_CAMPANHA,
           A.COD_PERSONA, 
           A.DES_OPERACA ,
           B.ABV_TPCAMPA,
           C.DES_CAMPANHA,
           
           D.DES_PERSONA,
           E.DES_ABREVIA,
           G.NOM_UNIVEND,
           G.COD_EXTERNO,
           F.COD_VENDAPDV

           FROM CREDITOSDEBITOS A
           LEFT JOIN WEBTOOLS.TIPOCAMPANHA B ON A.TIP_CAMPANHA=B.COD_TPCAMPA 
           LEFT JOIN CAMPANHA C ON C.COD_CAMPANHA=A.COD_CAMPANHA
           LEFT JOIN PERSONA  D  ON  D.COD_PERSONA=A.COD_PERSONA
           LEFT JOIN STATUSMARKA E ON E.COD_STATUS=A.COD_STATUS
           LEFT JOIN VENDAS F ON F.COD_VENDA=A.COD_VENDA
           LEFT JOIN WEBTOOLS.UNIDADEVENDA G ON G.COD_UNIVEND=F.COD_UNIVEND
           LEFT JOIN STATUSCREDITO H ON H.COD_STATUSCRED=A.COD_STATUSCRED

           WHERE A.COD_CLIENTE = $cod_cliente
           AND A.COD_STATUSCRED <> 6
           AND A.COD_STATUS <> 15  
           AND A.COD_EMPRESA = $cod_empresa            
           ORDER  BY A.DAT_CADASTR DESC 
           ";
         $arrayQuery = mysqli_query($connUser->connUser(),$sqlcupom) or die(mysqli_error());
         
         
         while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery))
           {    
                    if ($qrBuscaProdutos['TIP_CREDITO'] == "D"){
                     $valorCred = 0;
                     $valorDeb = $qrBuscaProdutos['VAL_CREDITO'];
                    }else{
                        $valorCred = $qrBuscaProdutos['VAL_CREDITO'];
                     $valorDeb = 0;
                    }
           $cupom[]=array(
                            'dataLancamento'=>fnDataFull($qrBuscaProdutos['DAT_CADASTR']),
                            'idVenda'=>$qrBuscaProdutos['COD_VENDAPDV'],
                            'status'=>$qrBuscaProdutos['DES_STATUSCRED'],
                            'valorCredito'=>$valorCred,
                            'valorResgate'=>$valorDeb,
                            'dataExpiracao'=>$qrBuscaProdutos['DAT_EXPIRA'],
                            'loja'=>$qrBuscaProdutos['NOM_UNIVEND'],
                            'codigoExternoLoja'=>$qrBuscaProdutos['COD_EXTERNO'],
                            'campanha'=>$qrBuscaProdutos['DES_CAMPANHA']
                            );
           }   
             
//================================================================                
ob_end_flush();
ob_flush();
fnmemoriafinal($connUser->connUser(),$cod_men);
mysqli_close($connAdm->connAdm());   
mysqli_close($connUser->connUser()); 
    return  array('ListaCupom'=>array('listaSaldos'=>array('TotalGanho'=>$TotalGanho,
                                                           'totalResgatado'=>$totalResgatado,
                                                           'saldoDisponivel'=>$saldoDisponivel,
                                                           'saldoLiberar'=>$saldoLiberar,
                                                           'saldoBloqueado'=>$saldoBloqueado,
                                                           'expirados'=>$expirados
                                                                ),
                                       'listaCupons'=>array('Cupom'=>$cupom)),
                 'msgerro'=>'TESTE',
                 'coderro'=>'1' 
       
               );
}
 
        