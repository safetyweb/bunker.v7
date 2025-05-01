<?php
$server->wsdl->addComplexType(
    'itensoferta',
    'complexType',
    'struct',
    'sequence',
    '',
    array('vendaitemoferta' =>array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'vendaitemoferta', 'type' => 'tns:vendaitemoferta'),
         ));
 //'codigoTKT' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigoTKT', 'type' => 'xsd:integer'),
        
$server->wsdl->addComplexType(
    'vendaitemoferta',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'codigoproduto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigoproduto', 'type' => 'xsd:integer'),
        'quantidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'quantidade', 'type' => 'xsd:string'),
        'valor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valor', 'type' => 'xsd:string')
));


$server->register('OfertaProduto',
			array('fase'=>'xsd:string',
                              'cpf'=>'xsd:string',
                              'cartao'=>'xsd:string',
                              'itensoferta'=>'tns:itensoferta',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('OfertaProdutoResponse' => 'tns:acao'),  //output
			 $ns,         						// namespace
                        "$ns/OfertaProduto",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'OfertaProduto'         		// documentation
                    );


function OfertaProduto ($fase,$cpf,$cartao,$OfertaProduto,$dadosLogin) {
     include '../_system/Class_conn.php';
     include 'func/function.php'; 
     
     ob_start();
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
     $row = mysqli_fetch_assoc($buscauser);
     //compara os id_cliente com o cod_empresa
	 
     /*$dec=$row['NUM_DECIMAIS'];
    if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;}*/

     //verifica se a loja foi delabilitada
       $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND='.$dadosLogin['idloja'].' AND cod_empresa='.$dadosLogin['idcliente'];
        $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
        if($lojars['LOG_ESTATUS']!='S')
        {
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',' Loja desabilidata',$row['LOG_WS']); 
           return  array('OfertaProdutoResponse'=>array('msgerro'=>'LOJA DESABILITADA',
                                                           'coderro'=>'80'));
           exit();   
        }  
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])){
        $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'OfertaProduto',$dadosLogin['idcliente']);
        
		
		//nova regra de casas decimais 
	   $CONFIGUNI="SELECT * FROM unidades_parametro WHERE 
														  COD_EMPRESA=".$dadosLogin['idcliente']." AND 
														  COD_UNIVENDA=".$dadosLogin['idloja']." AND LOG_STATUS='S'";
		$RSCONFIGUNI=mysqli_query($connUser->connUser(), $CONFIGUNI);
		if(!$RSCONFIGUNI)
		{		  
			fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'BuscaConsumidor','erro na pre-venda dados unidade',$row['LOG_WS']); 
		}else{	
			if($RCCONFIGUNI=mysqli_num_rows($RSCONFIGUNI) > 0)
			{
				//aqui pega da unidade
				$RWCONFIGUNI=mysqli_fetch_assoc($RSCONFIGUNI);
				$dec=$RWCONFIGUNI['NUM_DECIMAIS']; 
				if ($RWCONFIGUNI['TIP_RETORNO']== 2){$decimal = '2';}else {$decimal = '0';}
				
				 $LOG_CADVENDEDOR=$RWCONFIGUNI['LOG_CADVENDEDOR'];
			}else{
				//aqui pega da controle de licença
				$dec=$row['NUM_DECIMAIS'];			
				if ($row['TIP_RETORNO']== 2){$decimal = '2';}else {$decimal = '0';}			
				$LOG_CADVENDEDOR=$row['LOG_CADVENDEDOR'];
			}
		}
		
        $xmlteste=addslashes(file_get_contents("php://input"));
        $inserarray='INSERT INTO origemOfertaProduto (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,NUM_CGCECPF,DES_VENDA,DES_LOGIN)values
                    ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
                     "'.$row['COD_USUARIO'].'","'.$dadosLogin['login'].'","'.$row['COD_EMPRESA'].'","'.$dadosLogin['idloja'].'","'.$dadosLogin['idmaquina'].'","'.$cpf.$cartao.'","'.$xmlteste.'","'.$dadosLogin.'")';
        $arraP=mysqli_query($connUser->connUser(),$inserarray);
        Grava_log_cad($connUser->connUser(),$COD_ORIGEM,'OfertaProdutoResponse');
 
        
        
        
        
        
        
       //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
        {
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],'',$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'OfertaProduto','Id_cliente não confere com o cadastro!',$row['LOG_WS']);
           return  array('OfertaProdutoResponse'=>array('msgerro'=>'Id_cliente não confere com o cadastro!',
                                                        'coderro'=>'4')); 
           exit();
        } 
       //VERIFICA SE A EMPRESA FOI DESABILITADA
        if($row['LOG_ATIVO']=='N'){
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],'',$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'OfertaProduto','A empresa foi desabilitada por algum motivo',$row['LOG_WS']);
           return  array('OfertaProdutoResponse'=>array('msgerro'=>'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
                                                        'coderro'=>'6'));
           exit();
        }
                 //VERIFICA SE O USUARIO FOI DESABILITADA
        if($row['LOG_ESTATUS']=='N'){
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',' A empresa foi desabilitada por algum motivo',$row['LOG_WS']); 
           return  array('OfertaProdutoResponse'=>array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!',
                                                         'coderro'=>'44'));
           exit();
        }
    //////////////////////=================================================================================================================
    
   }else{
       fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],'',$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'OfertaProduto','Usuario ou senha Inválido!',$row['LOG_WS']);
       return  array('OfertaProdutoResponse'=>array('msgerro'=>'Usuario ou senha Inválido!',
                                                     'coderro'=>'5'));
       exit();
   }

 
 $arrayconsulta=array(   'conn'=>$connAdm->connAdm(),
                         'ConnB'=>$connUser->connUser(),
                         'database'=>$row['NOM_DATABASE'],
                         'cod_cliente'=> $COD_CLIENTE,
                         'empresa'=>$row['COD_EMPRESA'],
                         'fase'=> $fase,
                         'cpf'=> $cpf,
                         'cnpj'=> $cpf,
                         'cartao'=>  $cartao,
                         'email'=>  $cliente['email'],
                         'telefone'=>  $cliente['telefone'],
                         'consultaativa'=>$row['LOG_CONSEXT'],
                         'login'=>$dadosLogin['login'],
                         'senha'=>$dadosLogin['senha'],
                         'idloja'=>$dadosLogin['idloja'],
                         'idmaquina'=>$dadosLogin['idmaquina'],
                         'codvendedor'=>$dadosLogin['codvendedor'],
                         'nomevendedor'=>$dadosLogin['nomevendedor'],
                         'COD_USUARIO'=>$row['COD_USUARIO'],
                         'pagina'=>'ofertaProduto',
                         'menssagem'=>$cad_venda,
                         'ArrayOfertaProduto'=>$OfertaProduto,
                         'venda'=>'nao',
						 'LOG_WS'=>$row['LOG_WS'],
                         'dec'=>$dec,
                         'decimal'=>$decimal
                         );
    ob_end_flush();
    ob_flush();
    fnmemoriafinal($connUser->connUser(),$cod_men);
    return  array('OfertaProdutoResponse'=>fnreturn($arrayconsulta));
}
