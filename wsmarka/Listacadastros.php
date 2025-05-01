<?php
$server->wsdl->addComplexType(
    'DadosBuscaClientes',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'datahoraInicial' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'datahoraInicial', 'type' => 'xsd:string'),
        'datahoraFinal' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'datahoraFinal', 'type' => 'xsd:string'),
		'quantidadeLista' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'quantidadeLista', 'type' => 'xsd:string'),
        'proximaPagina'=> array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'proximaPagina', 'type' => 'xsd:string')
		)
);
$server->wsdl->addComplexType(
            'dadosclientes',
            'complexType',
            'struct',
            'sequence',
            '',
             array(
                 'informacoesdocliente' =>  array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'acao_A_cadastro', 'type' => 'tns:acao_A_cadastro')
                  
                 )
  );
$server->register('ListaCadastros',
				array(                     
						  'opcoesbuscaClientes'=>'tns:DadosBuscaClientes',
						  'dadosLogin'=>'tns:LoginInfo'
				    ),  //parameters
			array('ListacadastrosResponse' =>'tns:dadosclientes',
											 'quantidaregistrototal'=>'xsd:string',
										     'quantidaderegistrolista'=>'xsd:string',
											 'paginaatual'=>'xsd:string',
											 'paginacao'=>'xsd:string',
                                             'msgerro' =>'xsd:string',
											 'coderro' =>'xsd:string'), 
						$ns,         						
						"$ns/ListaCadastros",     			
						'document',                        
						'literal',                        
						'ListaCadastros'         		
                    );


function ListaCadastros($opcoesbuscaClientes,$dadosLogin) {
     include '../_system/Class_conn.php';
     include 'func/function.php'; 
 
     $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
     $buscauser=mysqli_query($connAdm->connAdm(),$sql);
     $row = mysqli_fetch_assoc($buscauser);
	 if(!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS'])){
            return  array('msgerro'=>'Usuario ou senha Inválido!',
                          'coderro'=>'5');
            exit();
	 }
	   
    if($dadosLogin['idloja']!='')
    {    

        if($dadosLogin['idloja']!='0')
        {    
            $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                         WHERE COD_UNIVEND='.$dadosLogin['idloja'].' AND cod_empresa='.$dadosLogin['idcliente'];
                $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
                if($lojars['LOG_ESTATUS']!='S')
                {
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',' Loja desabilidata',$row['LOG_WS']); 
                   return  array('msgerro'=>'LOJA DESABILITADA',
                                 'coderro'=>'80');
                   exit();   
                }
        }        
    }        
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    
       //VERIFICA SE A EMPRESA FOI DESABILITADA
        if($row['LOG_ATIVO']=='N')
        {
            fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$logcpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'BuscaConsumidor','A empresa foi desabilitada por algum motivo',$row['LOG_WS']);
            
            $return=array('msgerro'=>'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
                          'coderro'=>'37' );
            return $return;
            exit();
            
        }
         //VERIFICA SE O USUARIO FOI DESABILITADA
        if($row['LOG_ESTATUS']=='N'){
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',' A empresa foi desabilitada por algum motivo',$row['LOG_WS']); 
          
            $return=array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!',
                          'coderro'=>'44');          
            return $return;
           exit();
        }
    //////////////////////=================================================================================================================
//validar formato data
        $datahoraInicial=validateDate($opcoesbuscaClientes['datahoraInicial']);
	$datahoraFinal=validateDate($opcoesbuscaClientes['datahoraFinal']);		
        if($datahoraInicial!=1 && $datahoraFinal!=1){
              fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'InsereVenda','Formato date/time inválido! AAAA-MM-DD HH:i:s',$row['LOG_WS']);
              return array('msgerro' => 'Formato date/time inválido! AAAA-MM-DD HH:i:s',
                           'coderro'=>'100' );
		} 
//Quantidade maxima da lista   		
		if($opcoesbuscaClientes['quantidadeLista']>'100'){
				  fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'InsereVenda','Formato date/time inválido! AAAA-MM-DD HH:i:s',$row['LOG_WS']);
				  return array('msgerro' => 'Limite maximo da lista é 100',
							  'coderro'=>'101' );
			}  	
//Dafault paginação   		
		if($opcoesbuscaClientes['proximaPagina']=='' && $opcoesbuscaClientes['proximaPagina']=='0'){
		    $proximaPagina='1';	
		}else{
			$proximaPagina=$opcoesbuscaClientes['proximaPagina'];
		}
// limitar a consulta 30 dias
               
		$entrada = new DateTime($opcoesbuscaClientes['datahoraInicial']);
		$saida = new DateTime($opcoesbuscaClientes['datahoraFinal']);
                $intervalo = $entrada->diff($saida);
                if($intervalo->days > '30')
		{
			  return array('msgerro' => 'Intervalo de consulta nao pode ultrapassar o periodo de 30 dias',
						    'coderro'=>'102' ); 
		}		  
//quantidade default da lista
		if($opcoesbuscaClientes['quantidadeLista']<='0' || $opcoesbuscaClientes['quantidadeLista']=='')
		{
			$quantidadeLista='50';
		}else{
			$quantidadeLista=$opcoesbuscaClientes['quantidadeLista'];
		}			
//carregar o contador de cliente  na lista total

	$SQLQTDCLIENTE="SELECT COUNT(*) QTD_LISTA FROM CLIENTES
                        WHERE date(DAT_CADASTR) BETWEEN '".$opcoesbuscaClientes['datahoraInicial']."' AND '".$opcoesbuscaClientes['datahoraFinal']."'
                        and COD_EMPRESA=".$dadosLogin['idcliente']."
                        AND CASE
                                        WHEN num_cartao > '0'
                                        THEN '1'
                                          WHEN num_CGCECPF>'0'
                                        THEN '1'
                                          ELSE '0' end IN ( 1, 1 )";
	$rwQTDCLIENTE=mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$SQLQTDCLIENTE));	
	//verificando a quantidade da consulta
	$inicio = ($proximaPagina * $quantidadeLista) - $quantidadeLista;	
	
//dados de clientes
			$SQLCLIENTE="SELECT *  FROM CLIENTES
                                    WHERE date(DAT_CADASTR) BETWEEN '".$opcoesbuscaClientes['datahoraInicial']."' AND '".$opcoesbuscaClientes['datahoraFinal']."'
                                    and COD_EMPRESA=".$dadosLogin['idcliente']."

                                    AND CASE
                                                    WHEN num_cartao > '0'
                                                    THEN '1'
                                                      WHEN num_CGCECPF>'0'
                                                    THEN '1'
                                                      ELSE '0' end IN ( 1, 1 ) 
                                                      order by DAT_CADASTR desc
                                                      limit $inicio ,$quantidadeLista";
	$rsCLIENTE=mysqli_query($connUser->connUser(),$SQLCLIENTE);											  
	while($rwCLIENTE=mysqli_fetch_assoc($rsCLIENTE))						
	{
		$dadoscliente[]=array(
								'nome'=>$rwCLIENTE['NOM_CLIENTE'],
								'cartao'=>$rwCLIENTE['NUM_CARTAO'],
								'cpf'=> fncompletadoc($rwCLIENTE['NUM_CGCECPF'],$rwCLIENTE['TIP_CLIENTE']),
								'sexo'=>$rwCLIENTE['COD_SEXOPES'],
								'rg'=>$rwCLIENTE['NUM_RGPESSO'],
								'cnpj'=>fncompletadoc($rwCLIENTE['NUM_CGCECPF'],$rwCLIENTE['TIP_CLIENTE']),
								'grupo'=> $grupo,
								'datanascimento'=>$rwCLIENTE['DAT_NASCIME'],
								'estadocivil'=>$rwCLIENTE['COD_ESTACIV'], 
								'telresidencial'=>$rwCLIENTE['NUM_TELEFON'], 
								'telcomercial'=>$rwCLIENTE['NUM_COMERCI'],
								'telcelular' => $rwCLIENTE['NUM_CELULAR'],
								'email'=>$rwCLIENTE['DES_EMAILUS'], 
								'profissao'=>$rwCLIENTE['COD_PROFISS'], 
								'clientedesde'=>$rwCLIENTE['DAT_CADASTR'],
								'tipocliente'=>$rwCLIENTE['TIP_CLIENTE'], 
								'endereco'=>$rwCLIENTE['DES_ENDEREC'],
								'numero'=>$rwCLIENTE['NUM_ENDEREC'],
								'bairro'=>$rwCLIENTE['DES_BAIRROC'],
								'complemento'=>$rwCLIENTE['DES_COMPLEM'], 
								'cidade'=>$rwCLIENTE['NOM_CIDADEC'],
								'estado'=>$rwCLIENTE['COD_ESTADOF'],
								'cep'=>$rwCLIENTE['NUM_CEPOZOF'],													  
								'dataalteracao'=>$rwCLIENTE['DAT_ALTERAC'],
								'participafidelidade'=>$rwCLIENTE['LOG_FIDELIZADO']
								);
		
	}
	$total_paginas = Ceil($rwQTDCLIENTE['QTD_LISTA'] / $quantidadeLista);
	if($total_paginas > $proximaPagina)
	{		
		$msg="TRUE";
	}else{
	    $msg="FALSE";
	}				  
    $return =array(
					'msgerro' => 'OK',
					'coderro'=>'39',
					'quantidaregistrototal'=>$rwQTDCLIENTE['QTD_LISTA'],
					'quantidaderegistrolista'=>$quantidadeLista,
					'paginaatual'=>$proximaPagina,
					'paginacao'=>$msg,				
	               'ListacadastrosResponse'=>array('informacoesdocliente'=>$dadoscliente));
														   
    return $return;       
}




