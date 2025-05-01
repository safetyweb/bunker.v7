<?php
ini_set('memory_limit', '-1');

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


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

}

if (@$_GET['file'] != ".gerais"){

	$ext = strtolower(pathinfo(@$_GET['file'], PATHINFO_EXTENSION));
	if ($ext == "pdf"){
		$c = copy(@$_GET['file'],__DIR__.'/temp_pdf/'.@$_GET['filename'].'.pdf');
		echo "pdf copiado";
		exit;
	}

}



include "_system/_functionsMain.php";

require_once("pdfComponente/autoload.inc.php");
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new DOMPDF($options);



//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
	$cod_empresa = fnDecode($_GET['id']);
}

if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
	$cod_cliente = fnDecode($_GET['idC']);
}	
	
$sql="SELECT COD_EMPRESA, NOM_FANTASI, COD_CHAVECO, LOG_CATEGORIA, LOG_AUTOCAD
		FROM empresas WHERE COD_EMPRESA=0$cod_empresa";
		
//fnEscreve($sql);		
$qrBuscaEmpresa = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),trim($sql)));
$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

//categoria de clientes		
$sql2="SELECT B.NOM_FAIXACAT,A.* 
		FROM clientes A
		left join categoria_cliente B ON B.COD_CATEGORIA=A.COD_CATEGORIA
		WHERE A.COD_CLIENTE = $cod_cliente and 
		A.COD_EMPRESA = $cod_empresa";
		
$qrBuscaCliente = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql2));		
// fnEscreve($sql2);	

if (isset($qrBuscaCliente)){

	$cod_usuario = $qrBuscaCliente['COD_CLIENTE'];
	$cod_externo = $qrBuscaCliente['COD_EXTERNO'];
	$nom_usuario = $qrBuscaCliente['NOM_CLIENTE'];
	$num_cartao =  $qrBuscaCliente['NUM_CARTAO'];
	$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
	$num_rgpesso = $qrBuscaCliente['NUM_RGPESSO'];
	$dat_nascime = $qrBuscaCliente['DAT_NASCIME'];
	$des_enderec = $qrBuscaCliente['DES_ENDEREC'];
	$num_enderec = $qrBuscaCliente['NUM_ENDEREC'];
	$des_complem = $qrBuscaCliente['DES_COMPLEM'];
	$des_bairroc = $qrBuscaCliente['DES_BAIRROC'];
	$num_cepozof = $qrBuscaCliente['NUM_CEPOZOF'];
	$nom_cidadec = $qrBuscaCliente['NOM_CIDADEC'];
	$cod_usucada = $qrBuscaCliente['COD_USUCADA'];
	$cod_estado = $qrBuscaCliente['COD_ESTADO'];
	$cod_municipio = $qrBuscaCliente['COD_MUNICIPIO'];
	$dat_admissao = fnDataShort($qrBuscaCliente['DAT_ADMISSAO']);
	$latitude = $qrBuscaCliente['LAT'];
	$longitude = $qrBuscaCliente['LNG'];

    $sqlUf = "SELECT COD_ESTADO, UF FROM ESTADO WHERE COD_ESTADO=$cod_estado";
    $arrayEstado = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlUf));		
    $nom_estado = $arrayEstado["UF"];

    $sqlMun = "SELECT COD_MUNICIPIO, NOM_MUNICIPIO FROM MUNICIPIOS WHERE COD_MUNICIPIO = $cod_municipio";
    $arrayMunic = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlMun));		
    $nom_municipio = $arrayMunic["NOM_MUNICIPIO"];
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
		font-size: 30px;
	}
	h2, .h2 {
		font-size: 20px;
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

//MOSTRA OS DADOS GERAIS
if (@$_GET['file'] == ".gerais"){
	$html .= "<h1>Prestação de Contas</h1>";

	$html .= "<h2>Dados Gerais</h2>";

	$html .= "<table><tr>";
	$html .= "<td>";
	$html .= "	<label>Código</label><br>";
	$html .= "	".$cod_usuario;
	$html .= "</td>";
	$html .= "<td>";
	$html .= "	<label>Empresa</label><br>";
	$html .= "	".$nom_empresa;
	$html .= "</td>";
	$html .= "<td>";
	$html .= "	<label>Nome do Funcionário</label><br>";
	$html .= "	".$nom_usuario;
	$html .= "</td>";
	$html .= "</tr></table>";

	$html .= "<table><tr>";
	$html .= "<td>";
	$html .= "	<label>CNPJ/CPF</label><br>";
	$html .= "	".$num_cgcecpf;
	$html .= "</td>";
	$html .= "<td>";
	$html .= "	<label>RG</label><br>";
	$html .= "	".$num_rgpesso;
	$html .= "</td>";
	$html .= "<td>";
	$html .= "	<label>Data de Nascimento</label><br>";
	$html .= "	".$dat_nascime;
	$html .= "</td>";
	$html .= "</tr></table>";

	$html .= "<h2>Localização</h2>";

	$html .= "<table><tr>";
	$html .= "<td>";
	$html .= "	<label>Endereço</label><br>";
	$html .= "	".$des_enderec;
	$html .= "</td>";
	$html .= "<td>";
	$html .= "	<label>Número</label><br>";
	$html .= "	".$num_enderec;
	$html .= "</td>";
	$html .= "<td>";
	$html .= "	<label>Complemento</label><br>";
	$html .= "	".$des_complem;
	$html .= "</td>";
	$html .= "<td>";
	$html .= "	<label>Bairro</label><br>";
	$html .= "	".$des_bairroc;
	$html .= "</td>";
	$html .= "</tr></table>";

	$html .= "<table><tr>";
	$html .= "<td>";
	$html .= "	<label>CEP</label><br>";
	$html .= "	".$num_cepozof;
	$html .= "</td>";
	$html .= "<td>";
	$html .= "	<label>Estado</label><br>";
	$html .= "	".$nom_estado;
	$html .= "</td>";
	$html .= "<td>";
	$html .= "	<label>Cidade</label><br>";
	$html .= "	".$nom_municipio;
	$html .= "</td>";
	$html .= "<td>";
	$html .= "	<label>Latitude</label><br>";
	$html .= "	".$latitude;
	$html .= "</td>";
	$html .= "<td>";
	$html .= "	<label>Longitude</label><br>";
	$html .= "	".$longitude;
	$html .= "</td>";
	$html .= "</tr></table>";

}else{
	//CONVERTE IMAGEM EM PDF
	$html .= "<img src='".@$_GET['file']."' style='max-width:90%;max-height:90%'>";
}
$html .= "</body>";
$html .= "</html>";


$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portait');

$dompdf->render();
$font = $dompdf->getFontMetrics()->get_font("helvetica", "");


if ($_GET['filename'] <> ""){
	$output = $dompdf->output();
	file_put_contents(__DIR__.'/temp_pdf/'.@$_GET['filename'].'.pdf', $output);
}else{
	$dompdf->stream("pdf.pdf", array("Attachment" => false));	
}