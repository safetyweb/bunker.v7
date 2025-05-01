<?php

$server->wsdl->addComplexType(
    'vendageracontrole',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'id_vendapdv' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'id_vendapdv', 'type' => 'xsd:string'),
        'datahora' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'datahora', 'type' => 'xsd:string'),
        'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:string'),
        'valortotalbruto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valortotalbruto', 'type' => 'xsd:string'),
        'descontototalvalor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descontototalvalor', 'type' => 'xsd:string'),
        'valortotalliquido' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valortotalliquido', 'type' => 'xsd:string'),
        'valor_resgate' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valor_resgate', 'type' => 'xsd:string'),
        'cupomfiscal' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cupomfiscal', 'type' => 'xsd:string'),
        'cupomdesconto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cupomdesconto', 'type' => 'xsd:string'),
        'formapagamento' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'formapagamento', 'type' => 'xsd:string'),
        'indicador' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'indicador', 'type' => 'xsd:string'),
        'codatendente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codatendente', 'type' => 'xsd:string'),
        'codvendedor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codvendedor', 'type' => 'xsd:string'),
        'idcliente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'idcliente', 'type' => 'xsd:string'),
        'saldodisponivel' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldodisponivel', 'type' => 'xsd:string'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
        )
);
//inserir venda
//=================================================================== EstornaVenda ==================================================================================
//soap enc array java  import wsld netbea
$server->wsdl->addComplexType(
    'retornogeracontrole',
    'complexType',
    'struct',
    'sequence',
    '',
        array('msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
              'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:string')
            )
);
$server->register('GeraControle',
                       array('geracontrole'=>'tns:vendageracontrole',
                             'dadosLogin'=>'tns:LoginInfo' ),  //parameters
			array('retornogeracontrole' => 'tns:retornogeracontrole'),  //output
			 $ns,         						// namespace
                        "$ns/geracontrole",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'geracontrole'         		// documentation
                    );
function GeraControle ($geracontrole,$dadosLogin) {
    gc_enable();
     include '../_system/Class_conn.php';
     include './func/function.php'; 
     ob_start();
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
    //
    //verifica se a loja foi delabilitada
       $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND='.$dadosLogin['idloja'].' AND cod_empresa='.$dadosLogin['idcliente'];
        $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
        if($lojars['LOG_ESTATUS']!='S')
        {
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',' Loja desabilidata',$row['LOG_WS']); 
           return  array('retornogeracontrole'=>array('msgerro'=>'LOJA DESABILITADA',
                                                   'coderro'=>'80'));
           exit();   
        }  
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    
   
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
           $cod_men= fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'listaEstadoCivil',$row['COD_EMPRESA']);

              //verifica id_empresa
              if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
                {
                     fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$geracontrole['cartao'],$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','Id_cliente não confere com o cadastro!',$row['LOG_WS']); 
                      return array('retornogeracontrole'=>array('msgerro'=>'Id_cliente não confere com o cadastro!'));
                       exit();
                }

                //VERIFICA SE O USUARIO FOI DESABILITADA
                if($row['LOG_ESTATUS']=='N'){
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$geracontrole['cartao'],$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda',' A empresa foi desabilitada por algum motivo',$row['LOG_WS']); 
                   return  array('retornogeracontrole'=>array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!'));
                   exit();
                }
            //////////////////////=================================================================================================================
               //empresa desabilidatada

                if($row['LOG_ATIVO']!='S')
                { 
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$geracontrole['cartao'],$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[',$row['LOG_WS']); 
                     return array('retornogeracontrole'=>array('msgerro'=>'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));
                    exit();
                }

        }else{
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$geracontrole['cartao'],$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','Erro Na autenticação!',$row['LOG_WS']); 
                    return array('retornogeracontrole'=>array('msgerro'=>'Erro Na autenticação!'));
        } 
      $lojas=fnconsultaLoja($connAdm->connAdm(),$connUser->connUser(),$dadosLogin['idloja'],$dadosLogin['idmaquina'],$row['COD_EMPRESA']);
      
      $formapag=fnFormaPAG($connUser->connUser(),$geracontrole['formapagamento'],$row['COD_EMPRESA']);
      
      if($dadosLogin['nomevendedor']=="")
       {    
            if($dadosLogin['codvendedor']!=''){$codvendedor="Vendedor:".$dadosLogin['codvendedor'];}else{$codvendedor="Vendedor:".$venda['codvendedor'];}    
       }else{
             $codvendedor=$dadosLogin['nomevendedor'] ; 
       }
        $cod_vendedor=fnVendedor ($connAdm->connAdm(),$codvendedor,$row['COD_EMPRESA'],$dadosLogin['idloja']); 
           
     $insertsql='INSERT INTO gera_controle (IP, 
                                            DAT_CADASTR, 
                                            COD_EMPRESA, 
                                            NUM_CARTAO, 
                                            NUM_CGCECPF, 
                                            COD_UNIVEND, 
                                            COD_FORMAPA, 
                                            VAL_RESGATE, 
                                            VAL_TOTDESCONTO, 
                                            VAL_TOTBRUTO, 
                                            VAL_TOTLIQUIDO, 
                                            COD_VENDAPDV, 
                                            COD_USUCADA, 
                                            COD_MAQUINA, 
                                            COD_CUPOM, 
                                            COD_VENDEDOR, 
                                            DAT_CADASTR_WS, 
                                            DESCRICAO) 
                                            VALUES 
                                            ("'.$_SERVER['REMOTE_ADDR'].'", 
                                             "'.DATE("Y-m-d H:i:s").'", 
                                             "'.$dadosLogin['idcliente'].'", 
                                             "'.$geracontrole['cartao'].'", 
                                             "'.$geracontrole['cartao'].'", 
                                             "'.$dadosLogin['idloja'].'", 
                                             "'.$formapag.'", 
                                             "'.fnFormatvalor($geracontrole['valor_resgate']).'", 
                                             "'.fnFormatvalor($geracontrole['descontototalvalor']).'",
                                             "'.fnFormatvalor($geracontrole['valortotalbruto']).'", 
                                             "'.fnFormatvalor($geracontrole['valortotalliquido']).'", 
                                             "'.$geracontrole['id_vendapdv'].'", 
                                             "'.$row['COD_USUARIO'].'", 
                                             "'.$lojas['COD_MAQUINA'].'", 
                                             "'.$geracontrole['cupomfiscal'].'", 
                                             "'.$cod_vendedor.'", 
                                             "'.DATE("Y-m-d H:i:s").'", 
                                             "'.$geracontrole['msgerro'].'"
                                             );
                ';
     mysqli_query($connUser->connUser(), $insertsql);
       
     
ob_end_flush();
ob_flush();
fnmemoriafinal($connUser->connUser(),$cod_men);
mysqli_close($connAdm->connAdm());   
mysqli_close($connUser->connUser()); 
gc_collect_cycles();
// print_r($itn);                                         
return array('retornogeracontrole'=>array('msgerro'=>'OK',
                                           'coderro'=>39));
}
?>   