<?php
$server->wsdl->addComplexType(
    'ItemOrigem',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'codigoproduto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigoproduto', 'type' => 'xsd:integer'),
        ));



$server->wsdl->addComplexType(
    'RetornoItemsArray',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'cod_origem' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cod_origem', 'type' => 'xsd:string'),
        'nome_produto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nome_produto', 'type' => 'xsd:string'),       
        'cod_externo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cod_externo', 'type' => 'xsd:string'),
        'cod_interno' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cod_interno', 'type' => 'xsd:string'),
        'ranking'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'ranking', 'type' => 'xsd:string'),
        'pct_ranking'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'pct_ranking', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:string'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
        )
);

$server->wsdl->addComplexType(
    'RetornoItems',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'itemindicado' => array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'itemindicado', 'type' => 'tns:RetornoItemsArray'),
       
        )
);

$server->register('IndicacaoProduto',
			array('cpfCnpj'=>'xsd:string',
                              'cartao'=>'xsd:string',
                              'ProdutosOrigem'=>'tns:ItemOrigem',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('RetornoItems' => 'tns:RetornoItems'),  //output
			 $ns,         						// namespace
                        "$ns/IndicacaoProduto",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'IndicacaoProduto'         		// documentation
                    );


function IndicacaoProduto ($cpfCnpj,$cartao,$Oferta,$dadosLogin) {
     include_once '../_system/Class_conn.php';
     include_once 'func/function.php'; 
     
      
             
     $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
       $buscauser=mysqli_query($connAdm->connAdm(),$sql);
       $row = mysqli_fetch_assoc($buscauser);
       
       $cpfCnpj=fnlimpaCPF($cpfCnpj);
       $cartao=fnlimpaCPF($cartao);
       if($cpfCnpj=='')
       {
         $CPF= $cartao; 
       } else{
         $CPF=$cpfCnpj;
       }
       if($dadosLogin['idcliente']=='')
        {
         return  array('RetornoItems'=>array('itemindicado'=>array('msgerro'=>'Id_cliente não confere com o cadastro!',
                                                                             'coderro'=>'4')));   
          fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$CPF,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'IndicacaoProduto','Id_cliente não confere com o cadastro!',$row['LOG_WS']); 

        }  
       
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])){
            $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
            
        }else{
             return  array('RetornoItems'=>array('itemindicado'=>array('msgerro'=>'Usuario ou senha Inválido!',
                                                                         'coderro'=>'5')));
        }
            
        
            $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                      WHERE COD_UNIVEND='.$dadosLogin['idloja'].' AND cod_empresa='.$dadosLogin['idcliente'];
            $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
            if($lojars['LOG_ESTATUS']!='S')
            {
               fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'IndicacaoProduto',' Loja desabilidata',$row['LOG_WS']); 
               return  array('RetornoItems'=>array('itemindicado'=>array('msgerro'=>'LOJA DESABILITADA',
                                                                        'coderro'=>'80')));
               exit();   
            }
            if($Oferta['codigoproduto']=='')
            {
               fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$CPF,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'IndicacaoProduto','OPS! Necessario passar um codigo de produto!',$row['LOG_WS']); 
               return  array('RetornoItems'=>array('itemindicado'=>array('msgerro'=>'OPS! Necessario passar um codigo de produto!',
                                                                        'coderro'=>'82')));
               exit();  
            }    
            
                     //==configuração
                     $sqlconf="SELECT QTD_HISTORICO,QTD_INDICA FROM REGRAS_INDICACAO WHERE LOG_ATIVO='S' and cod_empresa=".$dadosLogin['idcliente'];
                     $rsconfi=mysqli_query($connUser->connUser(), $sqlconf);
                    if(!$rsconfi)
                    {
                         
                        fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$CPF,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'IndicacaoProduto','REGRAS INDICACAO CONFIGURADA',$row['LOG_WS']); 
                        return  array('RetornoItems'=>array('itemindicado'=>array('msgerro'=>'REGRAS INDICACAO NAO CONFIGURADA',
                                                                                'coderro'=>'83')));
                       exit();   
                    } else {
                        
                         $RSCONFIGURACAO= mysqli_fetch_assoc($rsconfi);
                         if($RSCONFIGURACAO['QTD_INDICA']=='')
                         {
                            fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$CPF,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'IndicacaoProduto','REGRAS INDICACAO CONFIGURADA',$row['LOG_WS']); 
                            return  array('RetornoItems'=>array('itemindicado'=>array('msgerro'=>'REGRAS INDICACAO NAO CONFIGURADA',
                                                                                    'coderro'=>'83')));  
                         }    
                         $prdsql="CALL SP_LISTA_INDICACAO_PRODUTO ('".$dadosLogin['idcliente']."' , '".$Oferta['codigoproduto']."' )";
                         $rwDEFINE_TOP=mysqli_query($connUser->connUser(), $prdsql);
                         if(!$rwDEFINE_TOP)
                         {
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                            try {mysqli_query($connUser->connUser(),$prdsql);} 
                            catch (mysqli_sql_exception $e) {$msgsql= $e;}                           
                            $xamls= addslashes($msgsql);
                            fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$CPF,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'IndicacaoProduto',$xamls,$row['LOG_WS']); 
                              return  array('RetornoItems'=>array('itemindicado'=>array('msgerro'=>'OPS Algo deu errado!',
                                                                                    'coderro'=>'83')));  
                         
                         }else{    
                            while($rsTOPPRODUTO=mysqli_fetch_assoc($rwDEFINE_TOP))
                            {
                                // return  array('RetornoItems'=>array('itemindicado'=>array('msgerro'=>print_r($rsTOPPRODUTO),
                                //                                                           'coderro'=>'83')));
                               $arrayitem[]=array('nome_produto'=>$rsTOPPRODUTO['NOME_AGRUPADOR'],
                                                    'cod_origem'=>$rsTOPPRODUTO['COD_PRD_EXTERNO'],   
                                                    'cod_externo'=>$rsTOPPRODUTO['COD_AGRUPADO'],
                                                    'cod_interno'=>$rsTOPPRODUTO['COD_PRODUTO_ORIGEM'],
                                                    'ranking'=>$rsTOPPRODUTO['QTD_ITEM'],
                                                    'pct_ranking'=>$rsTOPPRODUTO['PERCENTUAL_ITEM_GRUPO'].'%'
                                                    ); 
                            }
                            /*if($rsTOPPRODUTO['NOME_AGRUPADOR']=='')
                            {
                              return  array('RetornoItems'=>array('itemindicado'=>array('msgerro'=>'Sem informação sobre o produto!',
                                                                                           'coderro'=>'83')));  
                            } */   
                         }
                    }    
                     //==================
           
    
     return array('RetornoItems'=>array('itemindicado'=> $arrayitem));
     
 
}
