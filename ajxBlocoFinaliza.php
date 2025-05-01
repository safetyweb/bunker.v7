<?php include "_system/_functionsMain.php"; 
include './totem/funWS/atualizacadastro.php'; 
include './totem/funWS/inserirvenda.php'; 
include './totem/funWS/buscaConsumidor.php';

//echo fnDebug('true');
//fnMostraForm();
@$opcao=$_GET['opcao'];
@$cpf=$_GET['c1'];
@$cod_empresa=$_GET['COD_EMPRESA'];
@$cod_orcamento=$_REQUEST['COD_ORCAMENTO'];
@$valorTotal=$_REQUEST['total_de_produtos'];
@$VAL_DESCONTO=$_REQUEST['VAL_DESCONTO'];
@$VAL_LIQUIDO=$_REQUEST['total_da_venda'];
@$VAL_RESGATE=$_REQUEST['VAL_RESGATE'];
@$COD_USUARIO=$_REQUEST['COD_USUARIO']; 
@$DES_CUPOM=$_REQUEST['DES_CUPOM']; 
@$COD_UNIVEND=$_REQUEST['COD_LOJA'];
@$VAL_CREDITO=fnValorSql($_REQUEST['VAL_CREDITO']);
@$DAT_EXPIRA=$_REQUEST['DAT_EXPIRA'];
if($VAL_RESGATE!='0,00')
{
 $VAL_LIQUIDO=$valorTotal;   
}    

$sql = "select LOG_USUARIO, DES_SENHAUS,COD_UNIVEND from usuarios where cod_empresa = ".$cod_empresa." AND COD_TPUSUARIO=10 and DAT_EXCLUSA is null ";
//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());	
$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
$log_usuario = $qrBuscaUsuario['LOG_USUARIO'];								
$des_senhaus = $qrBuscaUsuario['DES_SENHAUS'];	

/*
<dadosLogin>
<login>".$dadoslogin['0']."</login>
<senha>".$dadoslogin['1']."</senha>
<idloja>".$dadoslogin['2']."</idloja>
<idmaquina>".$dadoslogin['3']."</idmaquina>
<idcliente>".$dadoslogin['4']."</idcliente>
<codvendedor>".$dadoslogin['5']."</codvendedor>
<nomevendedor>".$dadoslogin['6']."</nomevendedor>
</dadosLogin>
 */

$sql = "SELECT NOM_USUARIO, COD_EXTERNO FROM USUARIOS WHERE COD_USUARIO = $COD_USUARIO";
$qrVend = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));

$arrayCampos=array( '0'=>$log_usuario,
                    '1'=> fnDecode($des_senhaus),
                    '2'=>$COD_UNIVEND,
                    '3'=>'999000',
                    '4'=>$cod_empresa,
                    '5'=>$qrVend['COD_EXTERNO'],
                    '6'=> fnAcentos($qrVend['NOM_USUARIO'])
                    );             
$buscaconsumidor = fnconsulta(fnLimpaDoc($cpf), $arrayCampos);
//echo '<pre>';
//print_r($buscaconsumidor);
//echo '</pre>';

if($buscaconsumidor['msg']=='CPF digitado é inválido!')
{

  // $modDestino = 1240;
  $modDestino = 1758;

  if($opcao != ""){
    $modDestino = 1758;
  }
//  header("Refresh:0;url=http://adm.bunker.mk/action.do?mod=apC2A333ahM1VYcC2A2&id=QunXraEOVrgC2A2&erro=1");   
echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.fnurl ().'/action.do?mod='. fnEncode($modDestino).'&id='.fnEncode($cod_empresa).'&erro=1">';
//echo $cod_empresa;
}
?>


<div class="push"></div>

<!-- -------------- bloco saldo  --------------- -->
<?php
        //busca saldos de resgate
        $cod_clientesql="select COD_CLIENTE from clientes where cod_empresa=$cod_empresa and NUM_CGCECPF='".fnLimpaDoc($cpf)."'";
       // fnEscreve($cod_clientesql);
        $cod_cliereturn=mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''), $cod_clientesql));
	
        //busca dados da empresa
        $sql = "SELECT TIP_RETORNO,LOG_CREDAVULSO FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
        //fnEscreve($sql);
        $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
        $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

        if (isset($arrayQuery)){
                $tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
                if ($tip_retorno == 2){
                        $casasDec = 2;
                }else { $casasDec = 0; }
                
}
        
   
?>
<input type="hidden" name="c1" id="c1" value="<?php echo $cpf; ?>" >
<input type="hidden" name="c10" id="c10" value="<?php echo $cpf; ?>" >
<?php
//verifica a resgate  antes de enviar
$arraydescontos=array(  'cartao'=>fnLimpaDoc($buscaconsumidor['cartao']),
                        'valortotalliquido'=>$valorTotal,
                        'valor_resgate'=>$_REQUEST['VAL_RESGATE']
                     );

if($arraydescontos['valor_resgate'] > '0,00')
{    
    $vlaarraudesc=validadescontos($arraydescontos,$arrayCampos);
    
   // echo '<pre>';
   // print_r($vlaarraudesc);
   // echo '</pre>';
    //if($vlaarraudesc['coderro']==52)
    //{
   //     $print=$vlaarraudesc['msgerro'][0];
   //     echo $print;
   // }    
}
if ($vlaarraudesc['coderro'][0]=='50' || $vlaarraudesc['coderro'][0]=='49')
{

        $print=$vlaarraudesc['msgerro'][0];   
        
    
} else {
   
//////////////////////////////////////////////
//inserir venda 
    $sqlitemvenda="select B.COD_EXTERNO,B.DES_PRODUTO,A.* from AUXVENDA A
                    inner join  PRODUTOCLIENTE B on 	A.COD_PRODUTO=B.COD_PRODUTO	
                    where A.COD_ORCAMENTO = '$cod_orcamento' and A.COD_ORCAMENTO <> ''  order by A.COD_VENDA";	
   
   $queryexec= mysqli_query(connTemp($cod_empresa,''), $sqlitemvenda);
  
        if(mysqli_num_rows($queryexec) > 0)
        {
           
            while ($row = mysqli_fetch_assoc($queryexec)) {
               // matriz de entrada
                $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç');

                // matriz de saída
                $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C');

                // devolver a string
                $nom_prod=str_replace($what, $by, $row['DES_PRODUTO']); 
             $vendaitem.="<vendaitem>
                            <id_item>".$row['COD_VENDA']."</id_item>
                            <produto>".$nom_prod."</produto>
                            <codigoproduto>".$row['COD_EXTERNO']."</codigoproduto>
                            <quantidade>".str_replace(".",",",$row['QTD_PRODUTO'])."</quantidade>
                            <valorbruto>".str_replace(".","",fnValor($row['VAL_UNITARIO'],2))."</valorbruto>
                            <descontovalor>0,00</descontovalor>
                            <valorliquido>".str_replace(".","",fnValor($row['VAL_UNITARIO'],2))."</valorliquido>
                        </vendaitem>"; 
            
            }
            
			//print_r($vendaitem);

            $sqlCanal = "SELECT COD_EXTERNO FROM EMPRESA_CANAIS WHERE COD_EMPRESA = $cod_empresa AND COD_EXTERNO = '99999' ORDER BY 1 LIMIT 1";

            $qrCanal = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,""),$sqlCanal));

            $canalVenda = "0";

            if($qrCanal[COD_EXTERNO] != ""){
              $canalVenda = $qrCanal[COD_EXTERNO];
            }
           
            $arraydadoscad=array('id_vendapdv'=>$cod_orcamento,
                                 'datahora'=>date("Y-m-d H:i:s"),
                                 'cartao'=>fnLimpaDoc($cpf),
                                 'valortotalbruto'=>str_replace(".","",$valorTotal),
                                 'descontototalvalor'=>str_replace(".","",$VAL_DESCONTO),
                                 'valortotalliquido'=>str_replace(".","",$VAL_LIQUIDO),
                                 'valor_resgate'=>str_replace(".","",$VAL_RESGATE),
                                 'cupomfiscal'=>$DES_CUPOM,
                                 'formapagamento'=>$_REQUEST['COD_FORMAPA'],
                                 'pontostotal'=>$VAL_CREDITO,
                                 'canalvendas'=>"$canalVenda",
                                 'codatendente'=>$qrVend['COD_EXTERNO'],
                                 'codvendedor'=>$qrVend['COD_EXTERNO']
                                  );
            
           $print=inserirvenda($arraydadoscad, $arrayCampos, $vendaitem);
        
           //insere CREDITO
         
            if($cod_empresa!='124')
            {   
                if($VAL_CREDITO!='' || $VAL_CREDITO!='0,00' || $VAL_CREDITO!= '0.00')
                {    
                     if($VAL_CREDITO > (float)'0.00')
                    {   
                         $CREDITO = "CALL SP_CADASTRA_CREDITO(
                                                          '".$cod_cliereturn['COD_CLIENTE']."',
                                                          '".$VAL_CREDITO."',
                                                          '".fnDataSql(@$DAT_EXPIRA)."',
                                                          '".$COD_USUARIO."',   
                                                          'Crédito Avulso',   
                                                          '13',   
                                                          '".$COD_UNIVEND."',   
                                                          '".$cod_empresa."', 
                                                          '".$cod_orcamento."',    
                                                          'CAD'   
                                                          ) ";

                        mysqli_query(connTemp($cod_empresa),$CREDITO);


                          //=================================================
                    } 
                 }
            }   
        }
		
}
	$sql = "CALL SP_CONSULTA_SALDO_CLIENTE('".$cod_cliereturn['COD_CLIENTE']."') ";
	//fnEscreve($sql);
	$qrBuscaSaldoResgate = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
        
        
	if (isset($qrBuscaSaldoResgate)){		
		$credito_disponivel = $qrBuscaSaldoResgate['CREDITO_DISPONIVEL'];
                $credito_aliberar = $qrBuscaSaldoResgate['TOTAL_CREDITO']-$qrBuscaSaldoResgate['CREDITO_DISPONIVEL'];
                $saldototal =$qrBuscaSaldoResgate['TOTAL_CREDITO'];  
	}			

  $msgVenda = json_decode(json_encode($print), TRUE)
?>

<div class="blkSaldo row">
	<div class="col-md-3 "></div>

	<div class="col-md-6" style="padding: 0 25px;">
			<?php 
				if ( !empty($buscaconsumidor['datanascimento'])){
					$niver = $buscaconsumidor['datanascimento'];
					$arrayNiver = explode('/', $niver);    
					$mes_atual = date("m");
					$mes_niver = $arrayNiver[1];
					//fnEscreve($mes_niver);
					//fnEscreve($mes_atual);
				}
				
				if ($mes_atual == $mes_niver ){
			?>
			
			<div class="alert alert-warning top30" role="alert" id="msgRetorno">
				<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<i class="fa fa-gift fa-2x" aria-hidden="true"></i> &nbsp; <span class="f18">Mês de aniversário do cliente </span>
			</div>
			
			<?php 
				}	
			?>
			
			<div class="push"></div>
	
			<div class="col-md-4 blkSaldo-left">
				<h3 style="color: white; margin: auto;" class=""><?php echo fnValor($credito_disponivel,$casasDec);?></h3>						
				<span>Saldo Disponível</span>
			</div>
			
			<div class="col-md-4 blkSaldo-left blkSaldo-middle">
				<h3 style="color: white; margin: auto;"><?php echo fnValor($credito_aliberar,$casasDec); ?></h3> 						
				<span  class="resgatado">Saldo a Liberar</span>
			</div>
			
			<div class="col-md-4 blkSaldo-left blkSaldo-lost">
				<h3 style="color: white; margin: auto;"><?php echo fnValor($saldototal,$casasDec); ?></h3> 			   
			   <span class="liberar">Saldo Total</span>
			</div>						
			
	</div>
</div>	

<div class="push20"></div>

<div class="col-md-3"></div>	

<div class="col-md-6">
	<!--<h4>Parabéns <b><a href="action.php?mod=<?php echo fnEncode(1024);?>&id=<?php echo fnEncode($cod_empresa);?>&idC=<?php echo $buscaconsumidor['nome'];?>"><?php echo $buscaconsumidor['nome'];?></b>. <br/>-->
	<h4>Parabéns <b><?php echo $buscaconsumidor['nome'];?></b>. <br/>
	<?php echo $msgVenda[0]; ?></h4>
</div>

<div class="col-md-3"></div>	

<div class="push100"></div> 				

<div class="col-md-3"></div>	

<div class="col-md-6">
  <?php 
    // $modDestino = 1240;
    $modDestino = 1758;

    if($opcao != ""){
      $modDestino = 1758;
    }
  ?>
  <a class="btn btn-success btn-lg btn-block addBox" tabindex="5" data-title='Recibo Avulso' data-url='action.php?mod=<?=fnEncode(1771)?>&id=<?=fnEncode($cod_empresa)?>&idpdv=<?=fnEncode($cod_orcamento)?>&pop=true'><i class="fa fa-1x fa-receipt" aria-hidden="true"></i>&nbsp; Recibo </a>
  <div class="push10"></div>
	<a href="action.do?mod=<?php echo fnEncode($modDestino); ?>&id=<?php echo fnEncode($cod_empresa); ?>" name="HOME3" id="HOME3" class="btn btn-info btn-lg btn-block" tabindex="5"><i class="fa fa-1x fa-home" aria-hidden="true"></i>&nbsp; Voltar Menu Principal </a>
</div>

<div class="col-md-3"></div>
		