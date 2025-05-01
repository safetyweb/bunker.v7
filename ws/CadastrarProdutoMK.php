<?php
//inserir venda
//=================================================================== EstornaVenda ==================================================================================

$server->wsdl->addComplexType(
    'CadastrarItens',
    'complexType',
    'struct',
    'all',
    '',
        array(
              'nome' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'nome', 'type' => 'xsd:string'),
              'codigo' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'codigo', 'type' => 'xsd:string'),
              'ean' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'ean', 'type' => 'xsd:string'),  
              'grupo'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'grupo', 'type' => 'xsd:string'),
              'subgrupo'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'subgrupo', 'type' => 'xsd:string'),
              'marca'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'marca', 'type' => 'xsd:string'),
              'atributo1'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo1', 'type' => 'xsd:string'),
              'atributo2'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo2', 'type' => 'xsd:string'),
              'atributo3'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo3', 'type' => 'xsd:string'),
              'atributo4'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo4', 'type' => 'xsd:string'),
              'atributo5'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo5', 'type' => 'xsd:string'),
              'atributo6'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo6', 'type' => 'xsd:string'),
              'atributo7'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo7', 'type' => 'xsd:string'),
              'atributo8'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo8', 'type' => 'xsd:string'),
              'atributo9'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo9', 'type' => 'xsd:string'),
              'atributo10'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo10', 'type' => 'xsd:string'),
              'atributo11'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo11', 'type' => 'xsd:string'),
              'atributo12'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo12', 'type' => 'xsd:string'),
              'atributo13'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo13', 'type' => 'xsd:string'),
             )
);
$server->wsdl->addComplexType(
    'CadastrarProdutoResponse',
    'complexType',
    'struct',
    'all',
    '',
        array(
            'codigo' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'codigo', 'type' => 'xsd:int'),
            'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'msgerro', 'type' => 'xsd:string'),
            'msgcampanha' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'msgcampanha', 'type' => 'xsd:string'),
            'url' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'url', 'type' => 'xsd:string'),
            'ativacampanha' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'ativacampanha', 'type' => 'xsd:string'),
            'dadosextras' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'dadosextras', 'type' => 'xsd:string'), 
            )
);

 //Registro para parassar os dados pra a função inserir venda
$server->register('CadastrarProdutoMK',
			array('Produto'=>'tns:CadastrarItens',
                              'dadosLogin'=>'tns:LoginInfo' ),  //parameters
			array('return' => 'tns:CadastrarProdutoResponse'),  //output
			'urn:fidelidade',   //namespace
			'urn:fidelidade#CadastrarProd',  //soapaction
			'rpc', //document
			'literal', // literal
			'CadastrarProduto');  //description


function CadastrarProdutoMK ($Produto,$dadoslogin) {
     include '../_system/Class_conn.php';
     include './func/function.php'; 
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
 
   
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
       fnmemoria($connUser->connUser(),'true',$dadoslogin['login'],'Cadastro de Produto');   
       $date=date("Y-m-d H:m:s");
       
       $verifica="select count(*) from produtocliente where COD_EXTERNO=".$Produto['codigo'];
       $rsverifica=mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$verifica));
       
       
       if($rsverifica!=0)
       {  
        //inserir produto
        $sql="insert into produtocliente (  COD_EXTERNO,
                                            COD_EMPRESA,
                                            EAN,
                                            DES_PRODUTO,
                                            COD_CATEGOR,
                                            COD_SUBCATE,
                                            COD_FORNECEDOR,
                                            ATRIBUTO1,
                                            ATRIBUTO2,
                                            ATRIBUTO3,
                                            ATRIBUTO4,
                                            ATRIBUTO5,
                                            ATRIBUTO6,
                                            ATRIBUTO7,
                                            ATRIBUTO8,
                                            ATRIBUTO9,
                                            ATRIBUTO10,
                                            ATRIBUTO11,
                                            ATRIBUTO12,
                                            ATRIBUTO13,
                                            COD_USUCADA,
                                            DAT_CADASTR
                                            )
                                            values
                                            (
                                            '".$Produto['codigo']."',
                                            '".$row['COD_EMPRESA']."',
                                            '".$Produto['ean']."',
                                            '".$Produto['nome']."',
                                            '".$Produto['grupo']."',
                                            '".$Produto['subgrupo']."', 
                                            '".$Produto['marca']."',
                                            '".$Produto['atributo1']."',
                                            '".$Produto['atributo2']."',
                                            '".$Produto['atributo3']."',
                                            '".$Produto['atributo4']."',
                                            '".$Produto['atributo5']."',
                                            '".$Produto['atributo6']."',
                                            '".$Produto['atributo7']."',
                                            '".$Produto['atributo8']."',
                                            '".$Produto['atributo9']."',
                                            '".$Produto['atributo10']."',
                                            '".$Produto['atributo11']."',
                                            '".$Produto['atributo12']."',
                                            '".$Produto['atributo13']."',
                                            '".$row['COD_USUARIO']."',
                                            '".$date."',
                                            )";
        mysqli_query($connUser->connUser(),$sql);
        $msg='Produto cadastrado com sucesso!';
       }else{
        //update itens
        $sql="update produtocliente set COD_EMPRESA='".$row['COD_EMPRESA']."',
                                            EAN='".$Produto['ean']."',
                                            DES_PRODUTO='".$Produto['nome']."',
                                            COD_CATEGOR='".$Produto['grupo']."',
                                            COD_SUBCATE='".$Produto['subgrupo']."',
                                            COD_FORNECEDOR='".$Produto['marca']."',
                                            ATRIBUTO1='".$Produto['atributo1']."',
                                            ATRIBUTO2='".$Produto['atributo2']."',
                                            ATRIBUTO3='".$Produto['atributo3']."',
                                            ATRIBUTO4='".$Produto['atributo4']."',
                                            ATRIBUTO5='".$Produto['atributo5']."',
                                            ATRIBUTO6='".$Produto['atributo5']."',
                                            ATRIBUTO7='".$Produto['atributo6']."',
                                            ATRIBUTO8='".$Produto['atributo7']."',
                                            ATRIBUTO9='".$Produto['atributo8']."',
                                            ATRIBUTO10='".$Produto['atributo9']."',
                                            ATRIBUTO11='".$Produto['atributo10']."',
                                            ATRIBUTO12='".$Produto['atributo11']."',
                                            ATRIBUTO13='".$Produto['atributo12']."',
                                            COD_USUCADA='".$Produto['atributo13']."',
                                            DAT_ALTERAC= '".$date."' 
                                            where COD_EXTERNO=".$Produto['codigo'];
        mysqli_query($connUser->connUser(),$sql);
        $msg='Produto alterado com muito sucesso ;-}';
           
       fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);    
       }
       return array(
                        'codigo' =>$Produto['codigo'],
                        'msgerro' => $msg,
                        'msgcampanha' => '',
                        'url' =>'',
                        'ativacampanha' => '',
                        'dadosextras' => '' 
                    );
                        
    }else{
       return array('msgerro'=>'Erro Na autenticação');
    }   
      
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}

//=================================================================== Fim InserirVenda =================================================================================

?>
