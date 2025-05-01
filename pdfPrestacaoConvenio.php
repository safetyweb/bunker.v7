<?php
ini_set('memory_limit', '-1');

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

if (@$_REQUEST["data"] == ""){
	echo "Sem dados para gerar!";
	exit;
}
$print = json_decode(@$_REQUEST["data"]);

if (@$_GET['acao'] == "merge"){

	require_once('pdfComponente/fpdf/fpdf.php');
	require_once('pdfComponente/FPDI-master/src/autoload.php');
	require_once('pdfComponente/FPDI-master/src/Fpdi.php');
	require_once('pdfComponente/PDFMerger/PDFMerger.php');
	$pdf = new PDFMerger();

	foreach($print as $file){
		$dir = __DIR__ . "/temp_pdf/" . $file . ".pdf";
		if (file_exists($dir)){
			//echo $dir."<p>";
			$pdf->addPDF($dir, 'all');
		}
	}

	if (@$_GET['filename'] <> ""){
		try {
			$pdf->merge('file', __DIR__.'/temp_pdf/'.@$_GET['filename'].'.pdf', 'P');
		}catch(Exception $e){
			echo $e->getTraceAsString();
			throw $e;
		}
	}else{
		//$merge->output();
	}
	exit;
/*	require_once("pdfComponente/fpdf_merge.php");
	$merge = new FPDF_Merge();
	try {
		foreach($print as $file){
			$dir = __DIR__ . "/temp_pdf/" . $file . ".pdf";
			if (file_exists($dir)){
				//echo $dir."<p>";
				$merge->add($dir);
			}
		}
	}catch(Exception $e){
		echo $e->getTraceAsString();
		throw $e;
	}
	if ($_GET['filename'] <> ""){
		$merge->output(__DIR__.'/temp_pdf/'.@$_GET['filename'].'.pdf');
	}else{
		$merge->output();
	}
	//print_r($print);exit;
	exit;*/
}


if (count($print) <= 1){
	$data = json_decode($print[0],true);
	if (@$data["url"] <> ""){
		$ext = strtolower(pathinfo($data["url"], PATHINFO_EXTENSION));
		if ($ext == "pdf"){
			$c = copy($data["dir"],__DIR__.'/temp_pdf/'.@$_GET['filename'].'.pdf');
			echo "pdf copiado";
			exit;
		}
	}
}

include "_system/_functionsMain.php";

require_once("pdfComponente/autoload.inc.php");
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new DOMPDF($options);

$mod = fnDecode($_GET['mod']);

$cod_empresa = fnDecode($_GET['id']);
$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
if (isset($qrBuscaEmpresa)){
	$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
}

$cod_conveni = fnLimpaCampoZero(fnDecode($_GET['idC']));
$sql = "SELECT * FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	
$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
if (isset($qrBuscaTemplate)){
	$cod_conveni = $qrBuscaTemplate['COD_CONVENI'];
	$cod_entidad = $qrBuscaTemplate['COD_ENTIDAD'];
	$num_process = $qrBuscaTemplate['NUM_PROCESS'];
	$num_conveni = $qrBuscaTemplate['NUM_CONVENI'];
	$cod_tpconveni = $qrBuscaTemplate['COD_TPCONVENI'];
	$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
	$nom_abrevia = $qrBuscaTemplate['NOM_ABREVIA'];
	$des_descric = $qrBuscaTemplate['DES_DESCRIC'];
	$val_valor = fnValor($qrBuscaTemplate['VAL_VALOR'],2);
	$val_conced = fnValor($qrBuscaTemplate['VAL_CONCED'],2);
	$val_contpar = fnValor($qrBuscaTemplate['VAL_CONTPAR'],2);
	$dat_inicinv = fnDataShort($qrBuscaTemplate['DAT_INICINV']);
	$dat_fimconv = fnDataShort($qrBuscaTemplate['DAT_FIMCONV']);
	$dat_assinat = fnDataShort($qrBuscaTemplate['DAT_ASSINAT']);
	$log_licitacao = $qrBuscaTemplate['LOG_LICITACAO'];
}

$html = "<html>";


$html .= "<head>";

/*****SCRIPTS************************************************************************************/
$html .= <<<HTML
	<style>
	.quebra{ page-break-before: always; }

	body {
		font-family: "Lato","Helvetica Neue",Helvetica,Arial,sans-serif;
		font-size: 15px;
		line-height: 1.42857143;
		color: #2c3e50;
		background-color: #ffffff;
	}

	h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
		font-family: "Lato","Helvetica Neue",Helvetica,Arial,sans-serif;
		font-weight: 400;
		line-height: 1.1;
		color: inherit;
	}
	h1, .h1, h2, .h2, h3, .h3 {
		margin-top: 21px;
		margin-bottom: 10.5px;
	}
	h1, .h1 {
		font-size: 39px;
	}
	h2, .h2 {
		font-size: 32px;
	}

	table{
		width: 100%;
		margin-bottom: 5px;
	}
	table td{
		vertical-align: top;
		font-size:12px;
	}
	label{
		font-weight:bold;
	}
	small{
		font-size: 9px;
	}

	.text-right {
		text-align: right;
	}
	.text-center {
		text-align: center;
	}
	.text-danger, .text-danger:hover {
		color: #e74c3c;
	}
	</style>
HTML;

$html .= "</head>";

$html .= "<body>";

$tit = false;
foreach($print as $k=>$v){
	$data = json_decode($v,true);

	if (@$data["titulo"] <> ""){
		$html .= ($k > 0?"<div class='quebra'></div>":"");
		$tit = true;
	}
	if (@$data["subtitulo"] <> ""){
		if (!$tit){
			$html .= ($k > 0?"<div class='quebra'></div>":"");
		}else{
			$tit = false;
		}
	}


	if (@$data["titulo"] <> ""){
		$html .= "<h1>".@$data["titulo"]."</h1>";
	}

	if (@$data["subtitulo"] <> ""){
		$html .= "<h2>".@$data["subtitulo"]."</h2>";
	}

	if (@$data["tela"] <> ""){
		$html .= "<div style='height:10px;'></div>";
		if (@$data["tela"] == "CONVENIO"){

			$sql = "SELECT * FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$linha = mysqli_fetch_assoc($rs);
			
			$html .= "<table><tr>";
			$html .= "<td>";
			$html .= "	<label>Código</label><br>";
			$html .= "	".$cod_conveni;
			$html .= "</td>";
			$html .= "<td>";

			$sql = "select NOM_ENTIDAD from ENTIDADE WHERE COD_ENTIDAD=".$linha["COD_ENTIDAD"];
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$ret = mysqli_fetch_assoc($rs);
			$html .= "	<label>Entidade</label><br>";
			$html .= "	".$ret["NOM_ENTIDAD"];
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Número do Processo</label><br>";
			$html .= "	".$linha["NUM_PROCESS"];
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Número do Convênio</label><br>";
			$html .= "	".$linha["NUM_CONVENI"];
			$html .= "</td>";
			$html .= "<td>";
				$sql = "select DES_TPCONVENI from TIPO_CONVENIO WHERE COD_TPCONVENI=".$linha["COD_TPCONVENI"];
				$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				$ret = mysqli_fetch_assoc($rs);
			$html .= "	<label>Tipo de Convênio</label><br>";
			$html .= "	".$ret["DES_TPCONVENI"];
			$html .= "</td>";
			$html .= "</tr></table>";

			$html .= "<table><tr>";
			$html .= "<td>";
			$html .= "	<label>Nome</label><br>";
			$html .= "	".$linha["NOM_CONVENI"];
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Abreviação</label><br>";
			$html .= "	".$linha["NOM_ABREVIA"];
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Licitação Opcional?</label><br>";
			$html .= "	".$linha["LOG_LICITACAO"];
			$html .= "</td>";
			$html .= "</tr></table>";

			$html .= "<table><tr>";
			$html .= "<td>";
			$html .= "	<label>Descrição</label><br>";
			$html .= "	".$linha["DES_DESCRIC"];
			$html .= "</td>";
			$html .= "</tr></table>";

			$html .= "<table><tr>";
			$html .= "<td>";
			$html .= "	<label>Valor do Concedente</label><br>";
			$html .= "	".number_format($linha["VAL_CONCED"],2,",",".");
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	+  ";
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Valor de Contrapartida</label><br>";
			$html .= "	".number_format($linha["VAL_CONTPAR"],2,",",".");
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	=  ";
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Valor Global</label><br>";
			$html .= "	".number_format($linha["VAL_VALOR"],2,",",".");
			$html .= "</td>";
			$html .= "</tr></table>";

			$html .= "<table><tr>";
			$html .= "<td>";
			$html .= "	<label>Data Inicial</label><br>";
			$html .= "	".fnDataShort($linha["DAT_INICINV"]);
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Data Final</label><br>";
			$html .= "	".fnDataShort($linha["DAT_FIMCONV"]);
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Data de Assinatura</label><br>";
			$html .= "	".fnDataShort($linha["DAT_ASSINAT"]);
			$html .= "</td>";
			$html .= "</tr></table>";

		}elseif (@$data["tela"] == "ADITIVOS"){

			$sql = "select TA.COD_ADITIVO, 
						TA.TIP_ADITIVO, 
						TA.TIP_TIPADIT, 
						TA.COD_TIPMOTI,
						TA.DES_OBSERVA, 
						TA.DAT_INICIAL, 
						TA.DAT_FINAL,  
						TA.VAL_CONVENI, 
						TA.VAL_CONTRAP, 
						TA.VAL_TOTALGL, 
						TA.COD_USUCADA, 
						TM.DES_TPMOTIV
				from TERMOADITIVO TA  
				left join $connAdm->DB.TIPOMOTIVO TM ON TM.COD_TIPMOTI = TA.COD_TIPMOTI 
				where TA.TIP_TIPADIT = 'CON' 
				and TA.COD_EMPRESA = $cod_empresa
				and TA.COD_CONVENI = $cod_conveni
				order by TA.COD_ADITIVO ";
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($linha = mysqli_fetch_assoc($rs)){
				$count++;
				if ($count > 1){
					$html .= "<hr/>";
				}
				
				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$linha["COD_ADITIVO"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Convênio</label><br>";
				$html .= "	".$nom_conveni;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Termo Aditivo</label><br>";
				$item = ["P"=>"A Prazo","V"=>"Valor"];
				$html .= "	".@$item[$linha["TIP_ADITIVO"]];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Tipo do Motivo</label><br>";
				$html .= "	".$linha["DES_TPMOTIV"];
				$html .= "</td>";
				$html .= "</tr></table>";
				
				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Data Inicial</label><br>";
				$html .= "	".fnDataShort($linha["DAT_INICIAL"]);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Final</label><br>";
				$html .= "	".fnDataShort($linha["DAT_FINAL"]);
				$html .= "</td>";
				$html .= "</tr></table>";


				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Valor do Concedente</label><br>";
				$html .= "	".number_format($linha["VAL_CONVENI"],2,",",".");
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Valor de Contrapartida</label><br>";
				$html .= "	".number_format($linha["VAL_CONTRAP"],2,",",".");
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Valor Global</label><br>";
				$html .= "	".number_format($linha["VAL_TOTALGL"],2,",",".");
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Observação</label><br>";
				$html .= "	".$linha["DES_OBSERVA"];
				$html .= "</td>";
				$html .= "</tr></table>";
			}

		}elseif (@$data["tela"] == "CONTRATO"){

			$sql = "SELECT * FROM CONTRATO 
					WHERE DES_TPCONTRAT = 'CON' AND COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($linha = mysqli_fetch_assoc($rs)){
				$count++;
				if ($count > 1){
					$html .= "<hr/>";
				}
				
				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$linha["COD_CONTRAT"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Convênio</label><br>";
				$html .= "	".$nom_conveni;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Convênio / Aditivos</label><br>";
				if ($linha["COD_ADITIVO"] == 0){
					$html .= "	".$nom_conveni;
				}else{
					$sql = "SELECT 
									a.COD_ADITIVO,
								CONCAT(B.DES_TPMOTIV,' / ', case when a.tip_aditivo='P' then
									'Prazo'
										when a.tip_aditivo='V' then
									'Valor'
									END)  TERMO    

							FROM termoaditivo a,webtools.TIPOMOTIVO b
							WHERE a.COD_TIPMOTI=b.COD_TIPMOTI AND 
									a.cod_empresa = $cod_empresa AND 
									a.cod_conveni = $cod_conveni AND
									a.COD_ADITIVO = ".$linha["COD_ADITIVO"];
					$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);	
					$qrBusca = mysqli_fetch_assoc($arrayQuery);
					$html .= "	".$qrBusca["TERMO"];
				}
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Ano</label><br>";
				$html .= "	".$linha["DES_ANO"];
				$html .= "</td>";
				$html .= "</tr></table>";
				
				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Número Contrato</label><br>";
				$html .= "	".$linha["NRO_CONTRAT"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Inicio Vigência</label><br>";
				$html .= "	".fnDataShort($linha["DAT_INI"]);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Término Vigência</label><br>";
				$html .= "	".fnDataShort($linha["DAT_FIM"]);
				$html .= "</td>";
				$html .= "</tr></table>";
				
				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Data de Assinatura</label><br>";
				$html .= "	".fnDataShort($linha["DAT_ASSINAT"]);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Ordem de Serviço</label><br>";
				$html .= "	".fnDataShort($linha["DAT_ORDEM"]);
				$html .= "</td>";
				$html .= "</tr></table>";


				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Valor do Concedente</label><br>";
				$html .= "	".number_format($linha["VAL_CONVENI"],2,",",".");
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	+  ";
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Valor de Contrapartida</label><br>";
				$html .= "	".number_format($linha["VAL_CONTPAR"],2,",",".");
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	=  ";
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Valor Global</label><br>";
				$html .= "	".number_format($linha["VAL_VALOR"],2,",",".");
				$html .= "</td>";
				$html .= "</tr></table>";
			}
		}elseif (@$data["tela"] == "ENTIDADE"){

			$sql = "SELECT EN.COD_ENTIDAD,
					EC.COD_REGISTRO,
					EN.COD_GRUPOENT,
					EN.COD_TPENTID,
					EN.COD_EXTERNO,
					EN.COD_EMPRESA,
					EN.COD_MUNICIPIO,
					EN.COD_ESTADO,
					EN.NOM_ENTIDAD,
					EN.NUM_CGCECPF,
					EN.DES_ENDERC,
					EN.NUM_ENDEREC,
					EN.DES_BAIRROC,
					EN.NUM_CEPOZOF,
					EN.NOM_CIDADES,
					EN.NOM_ESTADOS,
					EN.NUM_TELEFONE,
					EN.NUM_CELULAR,
					EN.EMAIL,
					EN.NOM_RESPON,
					EN.QTD_MEMBROS,
					TIPOENTIDADE.DES_TPENTID,
					EMPRESAS.NOM_EMPRESA,
					A.DES_GRUPOENT
			from ENTIDADE EN
			inner join ENTIDADE_CONVENIO EC ON EC.COD_ENTIDAD = EN.COD_ENTIDAD 
			left join webtools.empresas ON EN.COD_EMPRESA = webtools.empresas.COD_EMPRESA 
			left join webtools.tipoentidade ON EN.COD_TPENTID = webtools.tipoentidade.COD_TPENTID
			left join entidade_grupo A ON A.COD_GRUPOENT = EN.COD_GRUPOENT 
			where webtools.empresas.COD_EMPRESA = $cod_empresa 
			AND (EC.COD_EXCLUSA IS NULL OR EC.COD_EXCLUSA = 0)
			order by COD_ENTIDAD";
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($linha = mysqli_fetch_assoc($rs)){
				$count++;
				if ($count > 1){
					$html .= "<hr/>";
				}
				
				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$linha["COD_REGISTRO"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Convênio</label><br>";
				$html .= "	".$nom_conveni;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Nome da Entidade</label><br>";
				$html .= "	".$linha["NOM_ENTIDAD"];
				$html .= "</td>";
				$html .= "</tr></table>";
			}

		}elseif (@$data["tela"] == "ENTIDADE_BANC"){

			$sql = "SELECT CONTABANCARIA.COD_CONTA,
							CONTABANCARIA.COD_EMPRESA,
							CONTABANCARIA.COD_ENTIDAD,
							CONTABANCARIA.COD_CONVENI,
							CONTABANCARIA.COD_CLIENTE,
							CONTABANCARIA.NUM_BANCO,
							CONTABANCARIA.NUM_AGENCIA,
							CONTABANCARIA.NUM_CONTACO,
							CONTABANCARIA.NUM_PIX,
							CONTABANCARIA.TIP_PIX,
							EMPRESAS.NOM_EMPRESA,
							ENTIDADE.NOM_ENTIDAD, 
							CONVENIO.DES_DESCRIC 
					from CONTABANCARIA  
						left join webtools.empresas ON CONTABANCARIA.COD_EMPRESA = empresas.COD_EMPRESA 
						left join ENTIDADE ON CONTABANCARIA.COD_ENTIDAD = ENTIDADE.COD_ENTIDAD 
						left join CONVENIO ON CONTABANCARIA.COD_CONVENI = CONVENIO.COD_CONVENI 
					where empresas.COD_EMPRESA =  $cod_empresa
					AND CONTABANCARIA.COD_CONVENI = $cod_conveni";
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($linha = mysqli_fetch_assoc($rs)){
				$count++;
				if ($count > 1){
					$html .= "<hr/>";
				}
				
				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$linha["COD_CONTA"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Convênio</label><br>";
				$html .= "	".$nom_conveni;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Entidade</label><br>";
				$html .= "	".$linha["NOM_ENTIDAD"];
				$html .= "</td>";
				$html .= "</tr></table>";
				
				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Número Banco</label><br>";
				$html .= "	".$linha["NUM_BANCO"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Agência</label><br>";
				$html .= "	".$linha["NUM_AGENCIA"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Conta Corrente</label><br>";
				$html .= "	".$linha["NUM_CONTACO"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>PIX</label><br>";
				$html .= "	".$linha["NUM_PIX"];
				$html .= "</td>";
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Tipo de Pix</label><br>";
				$item = [1=>"Celular",2=>"Email",3=>"CPF/CNPJ"];
				$html .= "	".@$item[$linha["TIP_PIX"]];
				$html .= "</td>";
				$html .= "</tr></table>";
			}

		}elseif (@$data["tela"] == "LICITACAO"){

			$sql = "SELECT
						LICITACAO.*,
						(select DES_TPMODAL from TIPOMODALIDADE WHERE COD_TPMODAL=LICITACAO.COD_TPMODAL) DES_TPMODAL
					FROM LICITACAO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($linha = mysqli_fetch_assoc($rs)){
				$count++;
				if ($count > 1){
					$html .= "<hr/>";
				}

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$linha["COD_LICITAC"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Convênio</label><br>";
				$html .= "	".$nom_conveni;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Núm. Processo Administrativo</label><br>";
				$html .= "	".$linha["NUM_ADMINIS"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data de Publicação do Edital</label><br>";
				$html .= "	".fnDataShort($linha["DAT_EDITAL"]);
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Objeto da Licitação</label><br>";
				$html .= "	".$linha["NOM_LICITAC"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Núm. da Modalidade</label><br>";
				$html .= "	".$linha["NUM_LICITAC"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Modalidade</label><br>";
				$html .= "	".$linha["DES_TPMODAL"];
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Descrição</label><br>";
				$html .= "	".$linha["DES_LICITAC"];
				$html .= "</td>";
				$html .= "</tr></table>";

			}

		}elseif (@$data["tela"] == "ITENS_OBJETO"){

			$sql = "SELECT LCO.*, LCT.NOM_LICITAC FROM LICITACAO_OBJETO LCO
			LEFT JOIN LICITACAO LCT ON LCT.COD_LICITAC = LCO.COD_LICITAC
			WHERE LCO.COD_EMPRESA = $cod_empresa AND LCO.COD_CONVENI = $cod_conveni";
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($linha = mysqli_fetch_assoc($rs)){
				$count++;
				if ($count > 1){
					$html .= "<hr/>";
				}

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$linha["COD_OBJETO"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Convênio</label><br>";
				$html .= "	".$nom_conveni;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Título do Bloco</label><br>";
				$html .= "	".$linha["NOM_LICITAC"];
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Descrição do Bloco</label><br>";
				$html .= "	".$linha["DES_OBJETO"];
				$html .= "</td>";
				$html .= "</tr></table>";

			}

		}elseif (@$data["tela"] == "PROPOSTAS"){

			$sql = "SELECT PPT.*, LCT.NOM_LICITAC, CL.NOM_CLIENTE, LCO.NOM_OBJETO FROM PROPOSTA PPT 
					LEFT JOIN LICITACAO LCT ON LCT.COD_LICITAC = PPT.COD_LICITAC
					LEFT JOIN LICITACAO_OBJETO LCO ON LCO.COD_OBJETO = PPT.COD_OBJETO
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = PPT.COD_CLIENTE
					WHERE PPT.COD_EMPRESA = $cod_empresa AND PPT.COD_CONVENI = $cod_conveni";
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($linha = mysqli_fetch_assoc($rs)){
				$count++;
				if ($count > 1){
					$html .= "<hr/>";
				}

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$linha["COD_PROPOSTA"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Convênio</label><br>";
				$html .= "	".$nom_conveni;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Objeto da Licitação</label><br>";
				$html .= "	".$linha["NOM_OBJETO"];
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Nome do Fornecedor/Responsável</label><br>";
				$html .= "	".@$linha["NOM_CLIENTE"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Valor da Proposta</label><br>";
				$html .= "	".fnValor(@$linha['VAL_VALOR'],2);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Vencedora?</label><br>";
				$html .= "	".@$linha['LOG_STATUS'];
				$html .= "</td>";
				$html .= "</tr></table>";

			}
		}elseif (@$data["tela"] == "ATA_PROPOSTA"){

			$sql = "SELECT * FROM PUBLICACAO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($linha = mysqli_fetch_assoc($rs)){
				$count++;
				if ($count > 1){
					$html .= "<hr/>";
				}

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$linha["COD_PUBLICA"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Empresa</label><br>";
				$html .= "	".$nom_empresa;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Licitação</label><br>";
				$html .= "	".$nom_empresa;
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Data Ajudicação</label><br>";
				$html .= "	".fnDataShort($linha['DAT_ADJUDICA']);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Homologação</label><br>";
				$html .= "	".fnDataShort($linha['DAT_HOMOLOGA']);
				$html .= "</td>";
				$html .= "</tr></table>";

			}

		}elseif (@$data["tela"] == "TAREFAS"){

			$sql = "SELECT * FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
			$cod_entidad = $qrBuscaTemplate['COD_ENTIDAD'];
			$dat_inicinv = fnDataShort($qrBuscaTemplate['DAT_INICINV']);
			$dat_fimconv = fnDataShort($qrBuscaTemplate['DAT_FIMCONV']);

			$sqlAd = "SELECT * FROM TERMOADITIVO 
						WHERE COD_EMPRESA = $cod_empresa
						AND TIP_ADITIVO = 'P'
						AND COD_CONVENI = $cod_conveni
						ORDER BY 1 DESC
						LIMIT 1";
			$arrayAd = mysqli_query(connTemp($cod_empresa,''),$sqlAd);
			$qrAditivo = mysqli_fetch_assoc($arrayAd);
			if($qrAditivo["DAT_FINAL"] != ""){
				$dat_aditivo = fnDataShort($qrAditivo["DAT_FINAL"]);
			}else{
				$dat_aditivo = "";
			}

			$sql = "SELECT * FROM ENTIDADE WHERE COD_ENTIDAD = $cod_entidad";
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			$nom_entidad = "";
			while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)){
				$nom_entidad .= $qrListaTipoEntidade['NOM_ENTIDAD'];
			}	
		
			$sql = "SELECT 
						TF1.*, 
						TF2.NOM_TAREFA AS TAREFA_PRINCIPAL 
					FROM TAREFA TF1
					LEFT JOIN TAREFA TF2 ON TF2.COD_SUBTAREFA = TF1.COD_TAREFA
					WHERE TF1.COD_EMPRESA = $cod_empresa
					AND TF1.COD_CONVENIO = $cod_conveni
					AND TF1.LOG_ATIVO = 'S'
					AND TF1.COD_SUBTAREFA = 0
					GROUP BY TF1.COD_TAREFA";
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($linha = mysqli_fetch_assoc($rs)){
				$count++;
				if ($count > 1){
					$html .= "<hr/>";
				}

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Convênio</label><br>";
				$html .= "	".$nom_conveni;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Inicial</label><br>";
				$html .= "	".$dat_inicinv;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Final</label><br>";
				$html .= "	".$dat_fimconv;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Aditivo</label><br>";
				$html .= "	".$dat_aditivo;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Entidade</label><br>";
				$html .= "	".$nom_entidad;
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Tarefa Ativa</label><br>";
				$html .= "	".$linha["LOG_ATIVO"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Nome da Tarefa</label><br>";
				$html .= "	".$linha["NOM_TAREFA"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Inicial</label><br>";
				$html .= "	".fnDataShort($linha['DAT_INI'],2);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Final</label><br>";
				$html .= "	".fnDataShort($linha['DAT_FIM'],2);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Percentual de Conclusão</label><br>";
				$html .= "	".$linha['PCT_TAREFA'];
				$html .= "</td>";
				$html .= "</tr></table>";

			}

		}elseif (@$data["tela"] == "LICITACAO_CTR"){
			$sql = "SELECT CTT.*, LCO.NOM_OBJETO, CL.NOM_CLIENTE, PPT.VAL_VALOR AS VAL_PROPOSTA FROM CONTRATO CTT
					LEFT JOIN PROPOSTA PPT ON PPT.COD_OBJETO = CTT.COD_OBJETO 
					AND CTT.COD_PROPOSTA=PPT.COD_PROPOSTA
					LEFT JOIN LICITACAO_OBJETO LCO ON LCO.COD_OBJETO = CTT.COD_OBJETO
					LEFT JOIN CLIENTES CL ON CTT.COD_CLIENTE = CL.COD_CLIENTE
					WHERE CTT.DES_TPCONTRAT = 'LIC' AND CTT.COD_EMPRESA = $cod_empresa AND CTT.COD_CONVENI = $cod_conveni";
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($linha = mysqli_fetch_assoc($rs)){
				$count++;
				if ($count > 1){
					$html .= "<hr/>";
				}

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$linha["COD_CONTRAT"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Convênio</label><br>";
				$html .= "	".$nom_conveni;
				$html .= "</td>";
				$html .= "<td>";
				$sql = "SELECT PPT.COD_OBJETO, PPT.COD_PROPOSTA, PPT.VAL_VALOR AS VAL_PROPOSTA, CL.NOM_CLIENTE, LCO.NOM_OBJETO 
				FROM PROPOSTA PPT
				LEFT JOIN LICITACAO_OBJETO LCO ON LCO.COD_OBJETO = PPT.COD_OBJETO
				LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = PPT.COD_CLIENTE
				WHERE PPT.COD_EMPRESA = $cod_empresa 
				AND PPT.COD_CONVENI = $cod_conveni 
				AND PPT.LOG_STATUS = 'S'
				AND COD_PROPOSTA = ".$linha["COD_PROPOSTA"];
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
				$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
				$html .= "	<label>Proposta/Objeto</label><br>";
				$html .= "	".$qrBuscaTemplate["NOM_CLIENTE"]." / ".$qrBuscaTemplate["NOM_OBJETO"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Ano</label><br>";
				$html .= "	".$linha["DES_ANO"];
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Número Contrato</label><br>";
				$html .= "	".$linha["NRO_CONTRAT"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Inicio Vigência</label><br>";
				$html .= "	".fnDataShort($linha['DAT_INI']);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Término Vigência</label><br>";
				$html .= "	".fnDataShort($linha['DAT_FIM']);
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Data de Assinatura</label><br>";
				$html .= "	".fnDataShort($linha["DAT_ASSINAT"]);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Ordem de Serviço</label><br>";
				$html .= "	".fnDataShort($linha['DAT_ORDEM']);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Valor da Proposta</label><br>";
				$html .= "	".fnValor($linha['VAL_PROPOSTA'],2);
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Valor Efetivo (Participação Global)</label><br>";
				$html .= "	".number_format($linha["VAL_CONVENI"],2,",",".");
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	+  ";
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Valor de Contrapartida</label><br>";
				$html .= "	".number_format($linha["VAL_CONTPAR"],2,",",".");
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	=  ";
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Valor do Convênio</label><br>";
				$html .= "	".number_format($linha["VAL_VALOR"],2,",",".");
				$html .= "</td>";
				$html .= "</tr></table>";
			}

		}elseif (@$data["tela"] == "CREDITOS"){
			$sql = "SELECT SUM(A.VAL_VALOR) AS VAL_VALOR,SUM(A.VAL_CONVENI)AS VAL_CONCED,SUM(A.VAL_CONTPAR)AS VAL_CONTPAR,B.NOM_CONVENI,B.NUM_CONVENI
			FROM CONTRATO A,CONVENIO B 
			WHERE 
			A.COD_CONVENI=B.COD_CONVENI AND 
			A.COD_CONVENI = $cod_conveni AND 
			A.DES_TPCONTRAT='CON'";	
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$qrConveni = mysqli_fetch_assoc($arrayQuery);
			
			$sql = "SELECT CX.*, TC.DES_TIPO FROM CAIXA CX
					LEFT JOIN TIP_CREDITO TC ON TC.COD_TIPO = CX.COD_TIPO
					WHERE CX.COD_CONVENI = $cod_conveni AND CX.COD_EMPRESA = $cod_empresa
					AND TC.TIP_OPERACAO ='C'";
			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($linha = mysqli_fetch_assoc($rs)){
				$count++;
				if ($count > 1){
					$html .= "<hr/>";
				}

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$linha["COD_CAIXA"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Convênio</label><br>";
				$html .= "	".$nom_conveni;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Nro. do Convênio</label><br>";
				$html .= "	".$num_conveni;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Valor Contrapartida</label><br>";
				$html .= "	".fnValor($qrConveni['VAL_CONTPAR'],2);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Valor Concedente</label><br>";
				$html .= "	".fnValor($qrConveni['VAL_CONCED'],2);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Valor do Convênio</label><br>";
				$html .= "	".fnValor($qrConveni['VAL_VALOR'],2);
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Valor do Crédito</label><br>";
				$html .= "	".fnValor($linha['VAL_CREDITO'],2);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Tipo de Crédito</label><br>";
				$html .= "	".$linha['DES_TIPO'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Nro. Ordem Bancária</label><br>";
				$html .= "	".$linha['NUM_ORDEM'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data do Crédito</label><br>";
				$html .= "	".fnDataShort($linha['DAT_CREDITO']);
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Observação</label><br>";
				$html .= "	".$linha["DES_COMENT"];
				$html .= "</td>";
				$html .= "</tr></table>";
			}

		}elseif (@$data["tela"] == "SUBTAREFAS"){
			$sqlAd = "SELECT * FROM TERMOADITIVO 
						WHERE COD_EMPRESA = $cod_empresa
						AND TIP_ADITIVO = 'P'
						AND COD_CONVENI = $cod_conveni
						ORDER BY 1 DESC
						LIMIT 1";
			$arrayAd = mysqli_query(connTemp($cod_empresa,''),$sqlAd);
			$qrAditivo = mysqli_fetch_assoc($arrayAd);
			if($qrAditivo["DAT_FINAL"] != ""){
				$dat_aditivo = fnDataShort($qrAditivo["DAT_FINAL"]);
			}else{
				$dat_aditivo = "";
			}

			$sql = "SELECT * FROM ENTIDADE WHERE COD_ENTIDAD = $cod_entidad";
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			$nom_entidad = "";
			while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)){
				$nom_entidad .= $qrListaTipoEntidade['NOM_ENTIDAD'];
			}	

			$sql = "SELECT 
						TF1.*, 
						TF2.NOM_TAREFA AS TAREFA_PRINCIPAL 
					FROM TAREFA TF1
					LEFT JOIN TAREFA TF2 ON TF2.COD_TAREFA = TF1.COD_SUBTAREFA
					WHERE TF1.COD_EMPRESA = $cod_empresa
					AND TF1.COD_CONVENIO = $cod_conveni
					AND TF1.LOG_ATIVO = 'S'
					AND TF1.COD_SUBTAREFA != 0
					GROUP BY TF1.COD_TAREFA";

			$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($linha = mysqli_fetch_assoc($rs)){
				$count++;
				if ($count > 1){
					$html .= "<hr/>";
				}

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Nome</label><br>";
				$html .= "	".$nom_conveni;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Inicial</label><br>";
				$html .= "	".$dat_inicinv;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Final</label><br>";
				$html .= "	".$dat_fimconv;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Aditivo</label><br>";
				$html .= "	".$dat_aditivo;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Entidade</label><br>";
				$html .= "	".$nom_entidad;
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Tarefa Ativa</label><br>";
				$html .= "	".$linha["LOG_ATIVO"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Nome da Tarefa</label><br>";
				$html .= "	".$linha["NOM_TAREFA"];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Inicial</label><br>";
				$html .= "	".fnDataShort($linha['DAT_INI'],2);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Final</label><br>";
				$html .= "	".fnDataShort($linha['DAT_FIM'],2);
				$html .= "</td>";
				$html .= "</tr></table>";

				$html .= "<table><tr>";
				$html .= "<td>";
				$html .= "	<label>Tarefa Principal</label><br>";
				$html .= "	".$linha['TAREFA_PRINCIPAL'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Percentual de Conclusão</label><br>";
				$html .= "	".$linha['PCT_TAREFA'];
				$html .= "</td>";
				$html .= "</tr></table>";
			}
		}elseif (@$data["tela"] == "MEDICAO"){

			$sql = "SELECT CTT.*, CL.NOM_CLIENTE, CR.TIP_CONTROLE FROM CONTRATO CTT 
			LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
			LEFT JOIN CONTROLE_RECEBIMENTO CR ON CR.COD_CONTRAT = CTT.COD_CONTRAT 
												AND CR.COD_CONVENI = CTT.COD_CONVENI
												AND CR.COD_EMPRESA = CTT.COD_EMPRESA
			WHERE CTT.COD_EMPRESA = $cod_empresa 
			AND CTT.DES_TPCONTRAT = 'LIC' 
			AND CTT.COD_CONVENI = $cod_conveni
			GROUP BY CTT.COD_CONTRAT";
			$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
			while ($qrContrat = mysqli_fetch_assoc($arrayQuery)){

				$html .= "<table style='background:#DDD;'><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$qrContrat['COD_CONTRAT'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Contrato</label><br>";
				$html .= "	".$qrContrat['NRO_CONTRAT'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Favorecido</label><br>";
				$html .= "	".$qrContrat['NOM_CLIENTE'];;
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Início</label><br>";
				$html .= "	".fnDataShort($qrContrat['DAT_INI']);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Fim</label><br>";
				$html .= "	".fnDataShort($qrContrat['DAT_FIM']);
				$html .= "</td>";
				$html .= "</tr></table>";

				$cod_contrat = $qrContrat['COD_CONTRAT'];

				$sqlAcumula = "SELECT SUM(VAL_MEDICAO) AS VAL_MEDAC, SUM(VAL_EVOLUCAO) AS VAL_EVOFIS 
				FROM CONTROLE_RECEBIMENTO WHERE COD_CONTRAT = $cod_contrat AND COD_EMPRESA = $cod_empresa";
				$arrayAcumula =  mysqli_query(connTemp($cod_empresa,''),$sqlAcumula);
				$qrAcumula = mysqli_fetch_assoc($arrayAcumula);
				if(isset($qrAcumula)){
					$val_medac = $qrAcumula['VAL_MEDAC'];
					$val_evofis = $qrAcumula['VAL_EVOFIS'];
				}else{
					$val_medac=0;
					$val_evofis=0;
				}

				$sql = "SELECT * FROM CONTROLE_RECEBIMENTO WHERE COD_EMPRESA = $cod_empresa AND TIP_CONTROLE = 'BLM' AND COD_CONTRAT = $cod_contrat";

				$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

				$count=0;
				while ($linha = mysqli_fetch_assoc($rs)){
					$count++;
					if ($count > 1){
						$html .= "<hr/>";
					}

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Código</label><br>";
					$html .= "	".$linha['COD_RECEBIM'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Empresa</label><br>";
					$html .= "	".$nom_empresa;
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Empresa Contratada</label><br>";
					$html .= "	".$qrContrat['NOM_CLIENTE'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Contrato</label><br>";
					$html .= "	".$qrContrat['NRO_CONTRAT'];
					$html .= "</td>";
					$html .= "</tr></table>";

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Valor do Contrato</label><br>";
					$html .= "	".fnValor($qrContrat['VAL_VALOR'],2);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Evolução Física Acumulada</label><br>";
					$html .= "	".fnValor($val_evofis,2);
					$html .= "  <br><small>PERCENTUAL (%)</small>";
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor da Medição Acumulada</label><br>";
					$html .= "	".fnValor($val_medac,2);
					$html .= "  <br><small>REAIS (R$)</small>";
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Código Externo</label><br>";
					$html .= "	".$linha['COD_MEDICAO'];
					$html .= "</td>";
					$html .= "</tr></table>";

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Número do Medição</label><br>";
					$html .= "	".$linha["NUM_MEDICAO"];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Data do Medição</label><br>";
					$html .= "	".fnDataShort($linha['DAT_MEDICAO']);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Evolução Física</label><br>";
					$html .= "	".fnValor($linha['VAL_EVOLUCAO'],2);
					$html .= "  <br><small>PORCENTAGEM (%)</small>";
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor da Medição</label><br>";
					$html .= "	".fnValor($linha['VAL_MEDICAO'],2);
					$html .= "  <br><small>REAIS (R$)</small>";
					$html .= "</td>";
					$html .= "</tr></table>";

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Comentário</label><br>";
					$html .= "	".$linha['DES_COMENT'];
					$html .= "</td>";
					$html .= "</tr></table>";
				}
			}
		}elseif (@$data["tela"] == "MOVIMENTACAO"){

			$sql = "SELECT CR.COD_RECEBIM,
								CR.NUM_MEDICAO,
								CR.DAT_MEDICAO,
								CR.VAL_MEDICAO,
								CR.VAL_TOTAL,
								CL.NOM_CLIENTE, 
								CTT.NRO_CONTRAT 
					FROM CONTROLE_RECEBIMENTO CR
					LEFT JOIN CONTRATO CTT ON CTT.COD_CONTRAT = CR.COD_CONTRAT
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
					WHERE CR.COD_EMPRESA = $cod_empresa AND CR.COD_CONVENI = $cod_conveni";	
			$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
			while ($qrMedicao = mysqli_fetch_assoc($arrayQuery)){
				$sqlSaldo = "SELECT SUM(EM.VAL_VALOR) AS VAL_VALOR FROM EMPENHO EM WHERE EM.COD_EMPRESA = $cod_empresa AND EM.COD_RECEBIM =".$qrMedicao['COD_RECEBIM'];
				$arrayQuerySaldo = mysqli_query(connTemp($cod_empresa,''),$sqlSaldo) or die(mysqli_error());
				$qrSaldo = mysqli_fetch_assoc($arrayQuerySaldo);
				if(isset($qrSaldo)){
					$val_saldo = $qrMedicao['VAL_TOTAL'] - $qrSaldo['VAL_VALOR'];
				}else{
					$val_saldo = $qrMedicao['VAL_TOTAL'];
				}

				$html .= "<table style='background:#DDD;'><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$qrMedicao['COD_RECEBIM'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Nro. do Contrato</label><br>";
				$html .= "	".$qrMedicao['NRO_CONTRAT'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Favorecido</label><br>";
				$html .= "	".$qrMedicao['NOM_CLIENTE'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Nro. da Nota</label><br>";
				$html .= "	".$qrMedicao['NUM_MEDICAO'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Execução</label><br>";
				$html .= "	".fnDataShort($qrMedicao['DAT_MEDICAO']);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Valor da Execução</label><br>";
				$html .= "	".fnValor($qrMedicao['VAL_TOTAL'],2);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Saldo Comprovação</label><br>";
				$html .= "	".fnValor($val_saldo,2);
				$html .= "</td>";
				$html .= "</tr></table>";

				$sql = "SELECT * FROM EMPENHO WHERE COD_EMPRESA = $cod_empresa AND COD_RECEBIM = ".$qrMedicao['COD_RECEBIM'];
				$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

				$count=0;
				while ($linha = mysqli_fetch_assoc($rs)){
					$count++;
					if ($count > 1){
						$html .= "<hr/>";
					}

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Código</label><br>";
					$html .= "	".$linha['COD_EMPENHO'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Empresa</label><br>";
					$html .= "	".$nom_empresa;
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Favorecido</label><br>";
					$html .= "	".$qrMedicao['NOM_CLIENTE'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Data de Execução</label><br>";
					$html .= "	".fnDataShort($qrMedicao['DAT_MEDICAO']);
					$html .= "</td>";
					$html .= "</tr></table>";

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Código do Contrato</label><br>";
					$html .= "	".$qrMedicao['NRO_CONTRAT'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Nro. da Nota</label><br>";
					$html .= "	".$qrMedicao['NUM_MEDICAO'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor da Execução</label><br>";
					$html .= "	".fnValor($qrMedicao['VAL_TOTAL'],2);
					$html .= "</td>";
					$html .= "</tr></table>";

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Saldo da Execução</label><br>";
					$html .= "	".fnValor($val_saldo,2);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor do Recurso do Convênio</label><br>";
					$html .= "	".fnValor($linha['VAL_CONVENI'],2);
					$html .= "	<br><small>ACUMULADO</small>";
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor da Contrapartida</label><br>";
					$html .= "	".fnValor($linha['VAL_CONTPAR'],2);
					$html .= "  <br><small>ACUMULADA</small>";
					$html .= "</td>";
					$html .= "</tr></table>";

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Data Inicial</label><br>";
					$html .= "	".fnDataShort($linha['DAT_NOTA']);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Número da Nota Fiscal</label><br>";
					$html .= "	".$linha['NUM_NOTA'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Data Inicial Empenho</label><br>";
					$html .= "	".fnDataShort($linha['DAT_EMPENHO']);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Número do Empenho</label><br>";
					$html .= "	".$linha['NUM_EMPENHO'];
					$html .= "</td>";
					$html .= "</tr></table>";

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Valor de Recurso do Convênio</label><br>";
					$html .= "	".fnValor($linha['VAL_CONVENI'],2);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	+  ";
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor da Contrapartida</label><br>";
					$html .= "	".fnValor($linha['VAL_CONTPAR'],2);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	=  ";
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor Efetivo da Nota Fiscal</label><br>";
					$html .= "	".fnValor($linha['VAL_VALOR'],2);
					$html .= "</td>";
					$html .= "</tr></table>";


				}
			}
		}elseif (@$data["tela"] == "PAGAMENTO"){
			$sql = "SELECT EM.*,CL.NOM_CLIENTE, CTT.NRO_CONTRAT FROM EMPENHO EM
			LEFT JOIN CONTRATO CTT ON CTT.COD_CONTRAT = EM.COD_CONTRAT
			LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
			WHERE EM.COD_EMPRESA = $cod_empresa AND EM.COD_CONVENI = $cod_conveni AND EM.VAL_VALOR <> 0.00";														
			$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
			
			$count=0;
			while ($qrMedicao = mysqli_fetch_assoc($arrayQuery))
			{		
				$cod_empenho = $qrMedicao['COD_EMPENHO'];
				$cod_recebim = $qrMedicao['COD_RECEBIM'];

				$html .= "<table style='background:#DDD;'><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$qrMedicao['COD_EMPENHO'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Nro. do Contrato</label><br>";
				$html .= "	".$qrMedicao['NRO_CONTRAT'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Favorecido</label><br>";
				$html .= "	".$qrMedicao['NOM_CLIENTE'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Nro. da Nota</label><br>";
				$html .= "	".$qrMedicao['NUM_NOTA'];
				$html .= "</td>";
				$html .= "</table>";

				$sql = "SELECT SUM(VAL_CREDITO) VAL_CREDITO FROM CAIXA CX
				LEFT JOIN TIP_CREDITO TC ON TC.COD_TIPO = CX.COD_TIPO
				WHERE CX.COD_EMPRESA = $cod_empresa
				AND TC.TIP_OPERACAO ='D' AND CX.COD_EMPENHO = $cod_empenho";
				$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
				$qrTot = mysqli_fetch_assoc($arrayQuery);

				$sql = "SELECT CX.*, TC.DES_TIPO FROM CAIXA CX
				LEFT JOIN TIP_CREDITO TC ON TC.COD_TIPO = CX.COD_TIPO
				WHERE CX.COD_EMPRESA = $cod_empresa
				AND TC.TIP_OPERACAO ='D' AND CX.COD_EMPENHO = $cod_empenho";
						
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error()); 
				
				$count=0;
				$val_total = 0;
				while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
				{		
					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Código</label><br>";
					$html .= "	".$qrBuscaModulos['COD_CAIXA'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Empresa</label><br>";
					$html .= "	".$nom_empresa;
					$html .= "</td>";


					$sql = "SELECT CR.*,
							(SELECT ifnull(SUM( case when B.TIP_OPERACAO = 'C' then
								A.VAL_CREDITO
								END),0) -
							ifnull(SUM( case when B.TIP_OPERACAO = 'D' then
								A.VAL_CREDITO
								END),0) saldo_em_conta

							FROM caixa a,tip_credito b
							WHERE 
							a.cod_tipo=b.COD_TIPO AND 
							a.cod_empresa=b.cod_empresa AND 
							a.COD_EMPRESA = CR.COD_EMPRESA AND 
							a.cod_conveni=CR.COD_CONVENI) VAL_SALDO_CONTA ,
								CL.NOM_CLIENTE, 
								CTT.NRO_CONTRAT, 
								EM.VAL_VALOR AS VAL_DEBITO, 
								CV.NUM_CONVENI AS NUM_CONVENI_CV, 
								CV.VAL_VALOR AS VAL_VALOR_CV, 
								CV.VAL_CONCED AS VAL_CONCED_CV, 
								CV.VAL_CONTPAR AS VAL_CONTPAR_CV
						FROM CONTROLE_RECEBIMENTO CR
						LEFT JOIN CONTRATO CTT ON CTT.COD_CONTRAT = CR.COD_CONTRAT
						LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
						LEFT JOIN EMPENHO EM ON EM.COD_CONTRAT = CR.COD_CONTRAT
						LEFT JOIN CONVENIO CV ON CV.COD_CONVENI = CR.COD_CONVENI
						WHERE EM.COD_EMPRESA = $cod_empresa 
						AND EM.COD_EMPENHO = $cod_empenho
						AND CR.COD_RECEBIM = $cod_recebim";

					$arrayQuery2 =  mysqli_query(connTemp($cod_empresa,''),$sql);
					$qrContrat = mysqli_fetch_assoc($arrayQuery2);

					$html .= "<td>";
					$html .= "	<label>Favorecido</label><br>";
					$html .= "	".$qrContrat['NOM_CLIENTE'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Favorecido</label><br>";
					$html .= "	".fnDataShort($qrContrat['DAT_MEDICAO']);
					$html .= "</td>";
					$html .= "</tr></table>";

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Nro. do Convênio</label><br>";
					$html .= "	".$qrContrat['NUM_CONVENI_CV'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor Contrapartida</label><br>";
					$html .= "	".fnValor($qrContrat['VAL_CONTPAR_CV'],2);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor Concedente</label><br>";
					$html .= "	".fnValor($qrContrat['VAL_CONCED_CV'],2);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor do Convênio</label><br>";
					$html .= "	".fnValor($qrContrat['VAL_VALOR_CV'],2);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Saldo em Conta</label><br>";
					$html .= "	".fnValor($qrContrat['VAL_SALDO_CONTA'],2);
					$html .= "</td>";
					$html .= "</tr></table>";

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Valor do Débito</label><br>";
					$html .= "	".fnValor($qrContrat['VAL_DEBITO'],2);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor Pago</label><br>";
					$html .= "	".fnValor($qrTot['VAL_CREDITO'],2);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor à Pagar</label><br>";
					$html .= "	".fnValor($qrContrat['VAL_DEBITO']-$qrTot['VAL_CREDITO'],2);
					$html .= "</td>";
					$html .= "</tr></table>";

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Valor do Débito</label><br>";
					$html .= "	".fnValor($qrBuscaModulos['VAL_CREDITO'],2);
					$html .= "</td>";

					$sql = "SELECT * FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa AND TIP_OPERACAO = 'D' AND COD_TIPO=".$qrBuscaModulos['COD_TIPO'];
					$arrayQuery3 =  mysqli_query(connTemp($cod_empresa,''),$sql);
					$qrTp = mysqli_fetch_assoc($arrayQuery3);
					$html .= "<td>";
					$html .= "	<label>Tipo de Débito</label><br>";
					$html .= "	".$qrTp['DES_TIPO'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Nro. do Comprovante</label><br>";
					$html .= "	".$qrBuscaModulos['NUM_ORDEM'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Data do Débito</label><br>";
					$html .= "	".fnDataShort($qrBuscaModulos['DAT_CREDITO']);
					$html .= "</td>";
					$html .= "</tr></table>";

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Observação</label><br>";
					$html .= "	".$qrBuscaModulos['DES_COMENT'];
					$html .= "</td>";
					$html .= "</tr></table>";
				}
			}
/*
			$sql = "SELECT EM.*,CL.NOM_CLIENTE, CTT.NRO_CONTRAT FROM EMPENHO EM
					LEFT JOIN CONTRATO CTT ON CTT.COD_CONTRAT = EM.COD_CONTRAT
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
					WHERE EM.COD_EMPRESA = $cod_empresa AND EM.COD_CONVENI = $cod_conveni AND EM.VAL_VALOR <> 0.00";
			$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
			while ($qrMedicao = mysqli_fetch_assoc($arrayQuery)){
				$sqlSaldo = "SELECT SUM(EM.VAL_VALOR) AS VAL_VALOR FROM EMPENHO EM WHERE EM.COD_EMPRESA = $cod_empresa AND EM.COD_RECEBIM =".$qrMedicao['COD_RECEBIM'];
				$arrayQuerySaldo = mysqli_query(connTemp($cod_empresa,''),$sqlSaldo) or die(mysqli_error());
				$qrSaldo = mysqli_fetch_assoc($arrayQuerySaldo);
				if(isset($qrSaldo)){
					$val_saldo = @$qrMedicao['VAL_TOTAL'] - @$qrSaldo['VAL_VALOR'];
				}else{
					$val_saldo = @$qrMedicao['VAL_TOTAL'];
				}

				$sqlPago = "SELECT SUM(CX.VAL_CREDITO) AS VAL_PAGO FROM CAIXA CX 
				WHERE CX.COD_EMPRESA = $cod_empresa AND CX.COD_EMPENHO =".$qrMedicao['COD_EMPENHO'];
				$qrPago = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlPago));
				// fnEscreve($sqlPago);

				if($qrPago['VAL_PAGO'] != $qrMedicao['VAL_VALOR']){
					$html .= "<table style='background:#DDD;'><tr>";
					$html .= "<td>";
					$html .= "	<label>Código</label><br>";
					$html .= "	".$qrMedicao['COD_EMPENHO'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Nro. do Contrato</label><br>";
					$html .= "	".$qrMedicao['NRO_CONTRAT'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Favorecido</label><br>";
					$html .= "	".$qrMedicao['NOM_CLIENTE'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Nro. da Nota</label><br>";
					$html .= "	".$qrMedicao['NUM_NOTA'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Data Execução</label><br>";
					$html .= "	".fnDataShort($qrMedicao['DAT_NOTA']);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor da Comprovação</label><br>";
					$html .= "	".fnValor($qrMedicao['VAL_VALOR'],2);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Valor Pago</label><br>";
					$html .= "	".fnValor($qrPago['VAL_PAGO'],2);
					$html .= "</td>";
					$html .= "</tr></table>";

					$sql = "SELECT CR.*,
								(SELECT ifnull(SUM( case when B.TIP_OPERACAO = 'C' then
									A.VAL_CREDITO
									END),0) -
								ifnull(SUM( case when B.TIP_OPERACAO = 'D' then
									A.VAL_CREDITO
									END),0) saldo_em_conta

								FROM caixa a,tip_credito b
								WHERE 
								a.cod_tipo=b.COD_TIPO AND 
								a.cod_empresa=b.cod_empresa AND 
								a.COD_EMPRESA = CR.COD_EMPRESA AND 
								a.cod_conveni=CR.COD_CONVENI) VAL_SALDO_CONTA ,
									CL.NOM_CLIENTE, 
									CTT.NRO_CONTRAT, 
									EM.VAL_VALOR AS VAL_DEBITO, 
									CV.NUM_CONVENI AS NUM_CONVENI_CV, 
									CV.VAL_VALOR AS VAL_VALOR_CV, 
									CV.VAL_CONCED AS VAL_CONCED_CV, 
									CV.VAL_CONTPAR AS VAL_CONTPAR_CV
							FROM CONTROLE_RECEBIMENTO CR
							LEFT JOIN CONTRATO CTT ON CTT.COD_CONTRAT = CR.COD_CONTRAT
							LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
							LEFT JOIN EMPENHO EM ON EM.COD_CONTRAT = CR.COD_CONTRAT
							LEFT JOIN CONVENIO CV ON CV.COD_CONVENI = CR.COD_CONVENI
							WHERE EM.COD_EMPRESA = $cod_empresa 
							AND EM.COD_EMPENHO = ".$qrMedicao['COD_EMPENHO']."
							AND CR.COD_RECEBIM = ".$qrMedicao['COD_RECEBIM']."
							";

					$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
					$qrContrat = mysqli_fetch_assoc($arrayQuery);

					if (isset($qrContrat)){
						$cod_conveni = $qrContrat['COD_CONVENI'];
						$cod_cliente = $qrContrat['COD_CLIENTE'];
						$nom_cliente = $qrContrat['NOM_CLIENTE'];
						$nro_contrat = $qrContrat['NRO_CONTRAT'];
						$cod_contrat = $qrContrat['COD_CONTRAT'];
						$dat_medicao = $qrContrat['DAT_MEDICAO'];
						$val_valor = $qrContrat['VAL_VALOR'];
						$val_debito = $qrContrat['VAL_DEBITO'];
						$nom_empContrat = $qrContrat['NOM_CLIENTE'];
						$num_conveni = $qrContrat['NUM_CONVENI_CV'];
						$val_valor = $qrContrat['VAL_VALOR_CV'];
						$val_conced = $qrContrat['VAL_CONCED_CV'];
						$val_contpar = $qrContrat['VAL_CONTPAR_CV'];
						$val_saldo_conta = $qrContrat['VAL_SALDO_CONTA'];
					}

					$sql = "SELECT CX.*, TC.DES_TIPO FROM CAIXA CX
					LEFT JOIN TIP_CREDITO TC ON TC.COD_TIPO = CX.COD_TIPO
					WHERE CX.COD_EMPRESA = $cod_empresa
					AND TC.TIP_OPERACAO ='D' AND CX.COD_EMPENHO = ".$qrMedicao['COD_EMPENHO'];
					$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

					$count=0;
					$val_total = 0;
					while ($linha = mysqli_fetch_assoc($rs)){
						$count++;
						$val_total+=$linha['VAL_CREDITO'];
						if ($count > 1){
							$html .= "<hr/>";
						}

						$html .= "<table><tr>";
						$html .= "<td>";
						$html .= "	<label>Código</label><br>";
						$html .= "	".$linha['COD_EMPENHO'];
						$html .= "</td>";
						$html .= "<td>";
						$html .= "	<label>Empresa</label><br>";
						$html .= "	".$nom_empresa;
						$html .= "</td>";
						$html .= "<td>";
						$html .= "	<label>Favorecido</label><br>";
						$html .= "	".$qrMedicao['NOM_CLIENTE'];
						$html .= "</td>";
						$html .= "<td>";
						$html .= "	<label>Data de Execução</label><br>";
						$html .= "	".fnDataShort($qrMedicao['DAT_NOTA']);
						$html .= "</td>";
						$html .= "</tr></table>";

						$html .= "<table><tr>";
						$html .= "<td>";
						$html .= "	<label>Nro. do Convênio</label><br>";
						$html .= "	".$num_conveni;
						$html .= "</td>";
						$html .= "<td>";
						$html .= "	<label>Valor Contrapartida</label><br>";
						$html .= "	".fnValor($val_contpar,2);
						$html .= "</td>";
						$html .= "<td>";
						$html .= "	<label>Valor Concedente</label><br>";
						$html .= "	".fnValor($val_conced,2);
						$html .= "</td>";
						$html .= "<td>";
						$html .= "	<label>Valor do Convênio</label><br>";
						$html .= "	".fnValor($val_valor,2);
						$html .= "</td>";
						$html .= "<td>";
						$html .= "	<label>Saldo em Conta</label><br>";
						$html .= "	".fnValor($val_saldo_conta,2);
						$html .= "</td>";
						$html .= "</tr></table>";

						$html .= "<table><tr>";
						$html .= "<td>";
						$html .= "	<label>Valor do Débito</label><br>";
						$html .= "	".fnValor($val_debito,2);
						$html .= "</td>";
						$html .= "<td>";
						$html .= "	<label>Valor Pago</label><br>";
						$html .= "	".fnValor($val_total,2);
						$html .= "	<br><small>REAIS (R$)</small>";
						$html .= "</td>";
						$html .= "<td>";
						$html .= "	<label>Valor à Pagar</label><br>";
						$html .= "	".fnValor(($val_debito-$val_total),2);
						$html .= "  <br><small>REAIS (R$)</small>";
						$html .= "</td>";
						$html .= "</tr></table>";

						$html .= "<table><tr>";
						$html .= "<td>";
						$html .= "	<label>Valor do Débito</label><br>";
						$html .= "	".fnValor($linha['VAL_CREDITO'],2);
						$html .= "</td>";
						$html .= "<td>";
						$html .= "	<label>Tipo de Débito</label><br>";
						$html .= "	".$linha['DES_TIPO'];
						$html .= "</td>";
						$html .= "<td>";
						$html .= "	<label>Nro. do Comprovante</label><br>";
						$html .= "	".$linha['NUM_ORDEM'];
						$html .= "</td>";
						$html .= "<td>";
						$html .= "	<label>Data do Débito</label><br>";
						$html .= "	".fnDataShort($linha['DAT_CREDITO']);
						$html .= "</td>";
						$html .= "</tr></table>";

						$html .= "<table><tr>";
						$html .= "<td>";
						$html .= "	<label>Observação</label><br>";
						$html .= "	".$linha['DES_COMENT'];
						$html .= "</td>";
						$html .= "</tr></table>";


					}
				}
			}*/
		}elseif (@$data["tela"] == "DESPESAS"){

			$sql = "SELECT CTT.*, CL.NOM_CLIENTE, CR.TIP_CONTROLE FROM CONTRATO CTT 
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
					LEFT JOIN CONTROLE_RECEBIMENTO CR ON CR.COD_CONTRAT = CTT.COD_CONTRAT 
														AND CR.COD_CONVENI = CTT.COD_CONVENI
														AND CR.COD_EMPRESA = CTT.COD_EMPRESA
					WHERE CTT.COD_EMPRESA = $cod_empresa 
					AND CTT.DES_TPCONTRAT = 'LIC' 
					AND CTT.COD_CONVENI = $cod_conveni
					GROUP BY CTT.COD_CONTRAT";	
			$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
			while ($qrContrat = mysqli_fetch_assoc($arrayQuery)){
				$html .= "<table style='background:#DDD;'><tr>";
				$html .= "<td>";
				$html .= "	<label>Código</label><br>";
				$html .= "	".$qrContrat['COD_CONTRAT'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Contrato</label><br>";
				$html .= "	".$qrContrat['NRO_CONTRAT'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Favorecido</label><br>";
				$html .= "	".$qrContrat['NOM_CLIENTE'];
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Início</label><br>";
				$html .= "	".fnDataShort($qrContrat['DAT_INI']);
				$html .= "</td>";
				$html .= "<td>";
				$html .= "	<label>Data Fim</label><br>";
				$html .= "	".fnDataShort($qrContrat['DAT_FIM']);
				$html .= "</td>";
				$html .= "</tr></table>";

				$sqlAcumula = "SELECT SUM(VAL_MEDICAO) AS VAL_MEDAC, SUM(VAL_EVOLUCAO) AS VAL_EVOFIS 
				FROM CONTROLE_RECEBIMENTO WHERE COD_CONTRAT = ".$qrContrat['COD_CONTRAT']." AND COD_EMPRESA = $cod_empresa";
				$arrayAcumula =  mysqli_query(connTemp($cod_empresa,''),$sqlAcumula);
				$qrAcumula = mysqli_fetch_assoc($arrayAcumula);
				if(isset($qrAcumula)){
					$val_medac = $qrAcumula['VAL_MEDAC'];
					$val_evofis = $qrAcumula['VAL_EVOFIS'];
				}else{
					$val_medac=0;
					$val_evofis=0;
				}
				if($qrContrat['TIP_CONTROLE'] == "RCB"){
					$label1 = "Quantidade Itens";
					$label2 = "Valor Comprado";
					$valor2 = "";
					$txtCuringa = "Recebimento";
				}else{
					$label1 = "Evolução Física Acumulada";
					$label2 = "Valor da Medição Acumulada";
					$valor2 = fnValor($val_medac,2);
					$txtCuringa = "Medição";
				}

				$sql = "SELECT * FROM CONTROLE_RECEBIMENTO 
				WHERE COD_EMPRESA = $cod_empresa
				AND COD_CONVENI = $cod_conveni
				AND COD_CONTRAT = ".$qrContrat['COD_CONTRAT'];
				$rs = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

				$count=0;
				while ($linha = mysqli_fetch_assoc($rs)){
					$count++;
					if ($count > 1){
						$html .= "<hr/>";
					}

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Código</label><br>";
					$html .= "	".$linha['COD_RECEBIM'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Empresa</label><br>";
					$html .= "	".$nom_empresa;
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Empresa Contratada</label><br>";
					$html .= "	".$qrContrat['NOM_CLIENTE'];
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>Contrato</label><br>";
					$html .= "	".$qrContrat['NRO_CONTRAT'];
					$html .= "</td>";
					$html .= "</tr></table>";

					$html .= "<table><tr>";
					$html .= "<td>";
					$html .= "	<label>Valor do Contrato</label><br>";
					$html .= "	".fnValor($qrContrat['VAL_VALOR'],2);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>$label1</label><br>";
					$html .= "	".fnValor($val_evofis,2);
					$html .= "</td>";
					$html .= "<td>";
					$html .= "	<label>$label2</label><br>";
					$html .= "	".$valor2;
					$html .= "</td>";
					$html .= "</tr></table>";


				}
			}
		}elseif (@$data["tela"] == "CUMPRIMENTO"){

			$sql = "SELECT DISTINCT c.NOM_CLIENTE,a.val_valor AS VALOR_CONVENIO,
						a.val_conced AS VALOR_CONCEDENTE,
						a.val_contpar AS VAL_CONTRAPARTIDA,
						a.DAT_INICINV,
						ifnull((SELECT SUM(val_credito) FROM caixa
									WHERE COD_EMPRESA = a.COD_EMPRESA AND 
										COD_CONVENI = a.COD_CONVENI AND 
										cod_tipo=1),0) AS CREDITOS_CONCEDENTE,
					IFNULL((SELECT SUM(val_credito) FROM caixa
									WHERE COD_EMPRESA = a.COD_EMPRESA AND 
										COD_CONVENI = a.COD_CONVENI AND 
										cod_tipo=2),0) AS CREDITOS_CONVENENTE,
						ifnull((SELECT SUM(val_credito) FROM caixa
									WHERE COD_EMPRESA = a.COD_EMPRESA AND 
										COD_CONVENI = a.COD_CONVENI AND 
										cod_tipo=3),0) AS CREDITOS_APLICACAO,
						IFNULL((SELECT SUM(val_credito) FROM caixa
						WHERE COD_EMPRESA = a.COD_EMPRESA AND 
								COD_CONVENI = a.COD_CONVENI AND 
								cod_tipo not IN(1,2,3)),0) AS DEBITOS_CONVENIO
						
				from CONVENIO a
				LEFT JOIN CONTROLE_RECEBIMENTO b ON a.cod_empresa=b.cod_empresa AND a.cod_conveni=b.cod_conveni  
				LEFT JOIN CLIENTES C ON C.COD_CLIENTE = b.COD_CLIENTE
				WHERE a.COD_EMPRESA = $cod_empresa AND 
					a.COD_CONVENI = $cod_conveni
				";
			$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
			$qrContrat = mysqli_fetch_assoc($arrayQuery);

			$nom_cliente = @$qrContrat['NOM_CLIENTE'];
			$valor_convenio = @$qrContrat['VALOR_CONVENIO'];
			$valor_concedente = @$qrContrat['VALOR_CONCEDENTE'];
			$val_contrapartida = @$qrContrat['VAL_CONTRAPARTIDA'];
			$dat_inicinv = @$qrContrat['DAT_INICINV'];
			$val_debito = @$qrContrat['VAL_DEBITO'];
			$creditos_concedente = @$qrContrat['CREDITOS_CONCEDENTE'];
			$creditos_convenente = @$qrContrat['CREDITOS_CONVENENTE'];
			$val_recebido = @$qrContrat['CREDITOS_CONCEDENTE']+@$qrContrat['CREDITOS_CONVENENTE'];
			$creditos_aplicacao = @$qrContrat['CREDITOS_APLICACAO'];
			$debitos_convenio = @$qrContrat['DEBITOS_CONVENIO'];
			$saldo_recebido = ($val_recebido + $creditos_aplicacao) - $debitos_convenio;

			$html .= "<table><tr>";
			$html .= "<td style='text-align:center'>";
			$html .= "	<label>Valor do Convênio</label><br>";
			$html .= "	<label>Concedente</label><br>";
			$html .= "	".fnValor($valor_concedente,2);
			$html .= "	<br><label>Convenente</label><br>";
			$html .= "	".fnValor($val_contrapartida,2);
			$html .= "	<br><label>Total</label><br>";
			$html .= "	".fnValor($valor_convenio,2);
			$html .= "</td>";
			$html .= "<td style='text-align:center'>";
			$html .= "	<label>Valor Recebido</label><br>";
			$html .= "	<label>Concedente</label><br>";
			$html .= "	".fnValor($creditos_concedente,2);
			$html .= "	<br><label>Convenente</label><br>";
			$html .= "	".fnValor($creditos_convenente,2);
			$html .= "	<br><label>Total</label><br>";
			$html .= "	".fnValor($val_recebido,2);
			$html .= "</td>";
			$html .= "<td style='text-align:center'>";
			$html .= "	<label>Saldo</label><br>";
			$html .= "	<label>Receita</label><br>";
			$html .= "	".fnValor($val_recebido,2);
			$html .= "	<br><label>Despesa</label><br>";
			$html .= "	".fnValor($debitos_convenio,2);
			$html .= "	<br><label>Total</label><br>";
			$html .= "	".fnValor($saldo_recebido,2);
			$html .= "</td>";
			$html .= "</tr></table>";

		}elseif (@$data["tela"] == "FINANCEIRO"){

	
			$sql = "SELECT DISTINCT c.NOM_CLIENTE,a.val_valor AS VALOR_CONVENIO,
						a.val_conced AS VALOR_CONCEDENTE,
						a.val_contpar AS VAL_CONTRAPARTIDA,
						a.DAT_INICINV,
						ifnull((SELECT SUM(val_credito) FROM caixa
									WHERE COD_EMPRESA = a.COD_EMPRESA AND 
										COD_CONVENI = a.COD_CONVENI AND 
										cod_tipo=1),0) AS CREDITOS_CONCEDENTE,
					IFNULL((SELECT SUM(val_credito) FROM caixa
									WHERE COD_EMPRESA = a.COD_EMPRESA AND 
										COD_CONVENI = a.COD_CONVENI AND 
										cod_tipo=2),0) AS CREDITOS_CONVENENTE,
						ifnull((SELECT SUM(val_credito) FROM caixa
									WHERE COD_EMPRESA = a.COD_EMPRESA AND 
										COD_CONVENI = a.COD_CONVENI AND 
										cod_tipo=3),0) AS CREDITOS_APLICACAO,
						IFNULL((SELECT SUM(val_credito) FROM caixa
						WHERE COD_EMPRESA = a.COD_EMPRESA AND 
								COD_CONVENI = a.COD_CONVENI AND 
								cod_tipo not IN(1,2,3)),0) AS DEBITOS_CONVENIO
						
				from CONVENIO a
				LEFT JOIN CONTROLE_RECEBIMENTO b ON a.cod_empresa=b.cod_empresa AND a.cod_conveni=b.cod_conveni  
				LEFT JOIN CLIENTES C ON C.COD_CLIENTE = b.COD_CLIENTE
				WHERE a.COD_EMPRESA = $cod_empresa AND 
					a.COD_CONVENI = $cod_conveni
				";

			//fnEscreve($sql);
			//echo($sql);
			$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
			$qrContrat = mysqli_fetch_assoc($arrayQuery);

			if (isset($qrContrat)){
				$nom_cliente = @$qrContrat['NOM_CLIENTE'];
				$valor_convenio = @$qrContrat['VALOR_CONVENIO'];
				$valor_concedente = @$qrContrat['VALOR_CONCEDENTE'];
				$val_contrapartida = @$qrContrat['VAL_CONTRAPARTIDA'];
				$dat_inicinv = @$qrContrat['DAT_INICINV'];
				$val_debito = @$qrContrat['VAL_DEBITO'];
				$creditos_concedente = @$qrContrat['CREDITOS_CONCEDENTE'];
				$creditos_convenente = @$qrContrat['CREDITOS_CONVENENTE'];
				$val_recebido = @$qrContrat['CREDITOS_CONCEDENTE']+@$qrContrat['CREDITOS_CONVENENTE'];
				$creditos_aplicacao = @$qrContrat['CREDITOS_APLICACAO'];
				$debitos_convenio = @$qrContrat['DEBITOS_CONVENIO'];
				$saldo_recebido = ($val_recebido + $creditos_aplicacao) - $debitos_convenio;
			}

			$html .= "<table><tr>";
			$html .= "<td>";
			$html .= "	<label>Empresa</label><br>";
			$html .= "	".$nom_empresa;
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Convênio</label><br>";
			$html .= "	".$nom_cliente;
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Data de Início</label><br>";
			$html .= "	".fnDataShort($dat_inicinv);
			$html .= "</td>";
			$html .= "</tr></table>";
			
			$html .= "<table><tr>";
			$html .= "<td>";
			$html .= "	<label>Valor do Convênio</label><br>";
			$html .= "	".fnValor($valor_convenio,2);
			$html .= "	<table>";
			$html .= "	<td><small>".fnValor($valor_concedente,2)."<br>concedente</small></td>";
			$html .= "	<td><small>".fnValor($val_contrapartida,2)."<br>convenente</small></td>";
			$html .= "	</table>";
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Valor Recebido</label><br>";
			$html .= "	".fnValor($val_recebido,2);
			$html .= "	<table>";
			$html .= "	<td><small>".fnValor($creditos_concedente,2)."<br>concedente</small></td>";
			$html .= "	<td><small>".fnValor($creditos_convenente,2)."<br>convenente</small></td>";
			$html .= "	</table>";
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Valor de Aplicação</label><br>";
			$html .= "	".fnValor($creditos_aplicacao,2);
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Total de Débitos</label><br>";
			$html .= "	".fnValor($debitos_convenio,2);
			$html .= "</td>";
			$html .= "<td>";
			$html .= "	<label>Saldo do Convênio</label><br>";
			$html .= "	".fnValor($saldo_recebido,2);
			$html .= "</td>";
			$html .= "</tr></table>";

			$html .= "<table class='table table-hover'>";
			$html .= "<thead>";
			$html .= "  <tr>";
			$html .= "	<th width='150'><small>Data</small></th>";
			$html .= "	<th><small>Favorecido</small></th>";
			$html .= "	<th><small>Comentário</small></th>";
			$html .= "	<th><small>Operação</small></th>";
			$html .= "	<th width='80'><small>Tipo</small></th>";
			$html .= "	<th colspan='2' class='text-center'><small>Valor</small></th>";
			$html .= "  </tr>";
			$html .= "</thead>";
			$html .= "  <tbody>";

			$sql = "SELECT  c.NOM_CLIENTE,
							d.DES_TIPO,
							d.ABV_TIPO,
							d.TIP_OPERACAO, 
							a.VAL_CREDITO,
							a.DAT_CREDITO,
							e.DES_COMENT
					FROM caixa a 
					LEFT JOIN contrato b ON b.cod_empresa=a.cod_empresa AND b.cod_conveni=a.cod_conveni AND b.COD_CONTRAT=a.cod_contrat
					LEFT JOIN clientes c ON c.cod_cliente=b.COD_CLIENTE AND c.COD_EMPRESA=a.cod_empresa 
					INNER JOIN tip_credito d ON d.COD_EMPRESA=a.cod_empresa AND d.COD_TIPO=a.COD_TIPO
					LEFT JOIN empenho f ON f.cod_empenho=a.cod_empenho AND f.COD_EMPRESA=a.cod_empresa 
					LEFT JOIN controle_recebimento e ON e.cod_recebim=f.cod_recebim AND e.COD_EMPRESA=a.cod_empresa 

					WHERE a.cod_empresa = $cod_empresa
					AND a.cod_conveni = $cod_conveni 
					GROUP BY a.COD_CAIXA
					ORDER BY a.DAT_CREDITO ";

			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			
			$count=0;
			$val_totalCred = 0;
			$val_totalDeb = 0;
			$val_totCont = 0;
			$val_totConv = 0;
			while ($qrListaCaixa = mysqli_fetch_assoc($arrayQuery))
			{														  
				$count++;
				if (@$dat_lancame ==  @$qrListaCaixa['DAT_CREDITO']){
					$dat_lancame = "";	
				} else {
					$dat_lancame = @$qrListaCaixa['DAT_CREDITO'];		
					//$dat_lancame = fnDataShort($qrListaCaixa['DAT_CREDITO']);		
				}
				
				$tip_operacao = $qrListaCaixa['TIP_OPERACAO'];
				
				if ($tip_operacao == "D") {
					$corTexto = "text-danger";
					$val_debito = fnValor($qrListaCaixa['VAL_CREDITO'],2);
					$val_totalDeb = $val_totalDeb + $qrListaCaixa['VAL_CREDITO'];
					$val_credito = "";
				} else { 
					$corTexto = ""; 
					$val_credito = fnValor($qrListaCaixa['VAL_CREDITO'],2);
					$val_totalCred = $val_totalCred + $qrListaCaixa['VAL_CREDITO'];
					$val_debito = "";
				} 
					

				$html .= "  <tr>";
				$html .= "  <td><b>".fnDataShort($dat_lancame)."</b></td>";
				$html .= "  <td>".$qrListaCaixa['NOM_CLIENTE']."</td>";
				$html .= "  <td>".$qrListaCaixa['DES_COMENT']."</td>";
				$html .= "  <td>".$qrListaCaixa['DES_TIPO']."</td>";
				$html .= "  <td class='text-center ".$corTexto."'>".$qrListaCaixa['TIP_OPERACAO']."</td>";
				$html .= "  <td class='text-right ".$corTexto."'>".$val_credito."</td>";
				$html .= "  <td class='text-right ".$corTexto."'>".$val_debito."</td>";
				$html .= "  </tr>";	

			}
			  
			$html .= "  </tbody>";

			$html .= "<tfoot>";
			$html .= "  <tr>";
			$html .= "	  <td></td>";
			$html .= "	  <td></td>";
			$html .= "	  <td></td>";
			$html .= "	  <td></td>";
			$html .= "	  <td></td>";
			$html .= "	  <td class='text-right'><b>".fnValor($val_totalCred,2)."</b></td>";
			$html .= "	  <td class='text-right'><b>".fnValor($val_totalDeb,2)."</b></td>";
			$html .= "  </tr>";
			$html .= "</tfoot>";

		  	$html .= "</table>";
		
		}else{
			$html .= "[Dados da Tela: ".@$data["tela"]."]";
		}
		$html .= "<div style='height:10px;'></div>";
	}

	if (@$data["url"] <> ""){
		$ext = strtolower(pathinfo($data["url"], PATHINFO_EXTENSION));

		$html .= "<div style='height:10px;'></div>";
		if ($ext == "png" || $ext == "jpg" || $ext == "jpeg" || $ext == "gif"
		|| $ext == "bmp" || $ext == "svg"){
			$html .= "<img src='".$data["url"]."' style='max-width:100%'>";
		}else{
			$html .= "<b>Documento:</b> <a href='".$data["url"]."' target='_blank'>".$data["url"]."</a>";
		}
	}

}
													

$html .= "</body>";
$html .= "</html>";

//echo $html;exit;

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portait');

$dompdf->render();
$font = $dompdf->getFontMetrics()->get_font("helvetica", "");
//$dompdf->getCanvas()->page_text(35, 810, utf8_encode("Emissão: ").date("d/m/Y H:i:s").str_repeat(" ", 160).utf8_encode("P�gina")." {PAGE_NUM} de {PAGE_COUNT}", $font, 8, array(0,0,0));


if ($_GET['filename'] <> ""){
	$output = $dompdf->output();
	file_put_contents(__DIR__.'/temp_pdf/'.@$_GET['filename'].'.pdf', $output);
}else{
	$dompdf->stream("pdf.pdf", array("Attachment" => false));	
}