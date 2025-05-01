<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$andCodPDV = "";
$NUM_CGCECPF = "";
$CUPOM = "";
$dias30 = "";
$hoje = "";
$temUnivend = "";
$NUM_CGCECPF1 = "";
$CUPOM1 = "";
$retorno = "";
$totalitens_por_pagina = 0;
$inicio = "";
$sqlcomand = "";
$countLinha = "";
$sqldadosr = "";

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);
$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$andCodPDV = @$_GET['andCodPDV'];
$NUM_CGCECPF = @$_POST['NUM_CGCECPF'];
$CUPOM = @$_REQUEST['CUPOM'];

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if (is_string($cod_univend) && strlen($cod_univend) == 0) {
	$cod_univend = "9999";
}

//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

switch ($opcao) {
	case 'exportar':

		break;
	case 'paginar':


		if ($NUM_CGCECPF != '' && $NUM_CGCECPF != 0) {
			$NUM_CGCECPF1 = "and NUM_CGCECPF='" . $NUM_CGCECPF . "'";
		} else {
			$NUM_CGCECPF1 = '';
		}

		if ($CUPOM != '' && $CUPOM != 0) {
			$CUPOM1 = "and CUPOM in($CUPOM)";
		} else {
			$CUPOM1 = '';
		}

		// $sql="SHOW TABLE STATUS LIKE 'origemvenda';";

		$sql = "SELECT  DAT_CADASTR
					from origemvenda
					inner join msg_venda on origemvenda.COD_ORIGEM=msg_venda.ID
					where 
					$andCodPDV
					COD_EMPRESA = $cod_empresa
					AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'	
                    $NUM_CGCECPF1 
                    $CUPOM1    
					AND COD_UNIVEND IN($lojasSelecionadas)
			";

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_num_rows($retorno);
		// fnEscreve($totalitens_por_pagina);
		$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;



		//,MSG,DES_VENDA 
		//select dinâmico do relatório
		$sql = "SELECT DAT_CADASTR, NOM_USUARIO, ID_MAQUINA ,NUM_CGCECPF, COD_PDV, MSG, COD_ORIGEM,COD_UNIVEND,origem_retorno, IP
								from origemvenda
								inner join msg_venda on origemvenda.COD_ORIGEM=msg_venda.ID
								where 
								$andCodPDV
								COD_EMPRESA = $cod_empresa
								AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'		
                                                            $NUM_CGCECPF1 
                                                            $CUPOM1     
								AND COD_UNIVEND IN($lojasSelecionadas)
                                                                AND  case when origem_retorno !='' then '1'
                                                                          when origem_retorno IS NOT NULL then '2'
                                                                          ELSE '0' END IN ('1','2','0')

								order by origemvenda.COD_ORIGEM desc limit $inicio,$itens_por_pagina
							  ";
		$sqlcomand = mysqli_query(connTemp($cod_empresa, ''), $sql);
		// fnEscreve($sql);

		$countLinha = 1;
		while ($sqldadosr = mysqli_fetch_assoc($sqlcomand)) {

			echo '<tr>';
			echo '<td><small> ' . fnFormatDateTime($sqldadosr['DAT_CADASTR']) . '</small></td>';
			echo '<td><small> ' . $sqldadosr['NOM_USUARIO'] . '</small></td>';
			echo '<td><small> ' . $sqldadosr['ID_MAQUINA'] . '</small></td>';
			echo '<td><small> ' . $sqldadosr['NUM_CGCECPF'] . '</small></td>';
			echo '<td><small> ' . $sqldadosr['COD_PDV'] . '</small></td>';
			echo '<td><small> ' . $sqldadosr['IP'] . '</small></td>';
			echo '<td><small> ' . $sqldadosr['COD_UNIVEND'] . '</small></td>';
			echo '<td><small> ' . $sqldadosr['MSG'] . '</small></td>';
			echo '<td class="text-center">';
?>
			<a class="btn btn-xs btn-default addBox" data-url="action.php?mod=<?php echo fnEncode(1203); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idR=<?php echo fnEncode($sqldadosr['COD_ORIGEM']); ?>&pop=true" data-title="XML Recebido"><small><i class="fa fa-code"></i></small></a>
			<?php
			echo '</td>';
			echo '<td class="text-center">';
			?>
			<a class="btn btn-xs btn-default addBox" data-url="action.php?mod=<?php echo fnEncode(1259); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idR=<?php echo fnEncode($sqldadosr['COD_ORIGEM']); ?>&pop=true" data-title="XML Enviado"><small><i class="fa fa-code"></i></small></a>
<?php
			echo '</tr>';
			$countLinha++;
		}

		break;
}
?>