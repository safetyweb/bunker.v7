<?php
//GERADOR FA TO BASE 64:  http://fa2png.io/
// normal - 30px / grande - 60px
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

include "../_system/_functionsMain.php";

require_once("../pdfComponente/autoload.inc.php");

$dt_exibe = "";

use Dompdf\Dompdf;

$dompdf = new DOMPDF();

$dados = $_POST;
foreach ($dados as $dado => $valor) {
	if (!is_array($dado)) {
		$$dado = $valor;
	}
}

$filename = "Analytics";


$h_dt_exibe = date("m/Y", strtotime($dt_exibe));
$h_qtd_clientes = fnValor($qtd_clientes, 0);
$h_qtd_cli_novos = fnValor($qtd_cli_novos, 0);
$h_pc_cli_cad = fnValor(($qtd_cli_novos * 100) / ($qtd_clientes), 1);
$h_listaFatLmp = fnValor($listaFatLmp, 2);
$h_listaFatAv = fnValor($listaFatAv, 2);
$h_listaFatTotRes = fnValor($listaFatTotRes, 2);
$h_pct_faturamento_fidelizado = fnValor($pct_faturamento_fidelizado, 2);
$h_vl_faturamento_fidelizado_mes_ant = fnValor($vl_faturamento_fidelizado_mes_ant, 2);
$h_vl_faturamento_fidelizado = fnValor($vl_faturamento_fidelizado, 2);
$h_pct_faturamento_ref = fnValor($pct_faturamento_ref, 0);
$h_qtd_transacoes = fnValor($qtd_transacoes, 0);
$h_qtd_transacoes_mes_ant = fnValor($qtd_transacoes_mes_ant, 0);
$h_qtd_transacoes_fidelizado = fnValor($qtd_transacoes_fidelizado, 0);
$h_qtd_transacoes_fidelizado_mes_ant = fnValor($qtd_transacoes_fidelizado_mes_ant, 0);
$h_qtd_transacoes_avulso = fnValor($qtd_transacoes_avulso, 0);
$h_qtd_transacoes_avulso_mes_ant = fnValor($qtd_transacoes_avulso_mes_ant, 0);
$h_vl_indice_frequencia = fnValor($vl_indice_frequencia, 2);
$h_qtd_clientes_compraram_mesm6 = fnValor($qtd_clientes_compraram_mesm6, 0);
$h_pct_fidelizado_anterior = fnValor($pct_fidelizado_anterior, 0);
$h_qtd_inativos = fnValor($qtd_inativos, 0);
$h_vl_gasto_acumulado_inativos = fnValor($vl_gasto_acumulado_inativos, 2);
$h_tm_inativos = fnValor($tm_inativos, 2);
$h_qtd_aniversariantes = fnValor($qtd_aniversariantes, 0);
$h_vl_faturamento_aniver = fnValor($vl_faturamento_aniver, 2);
$h_qtd_cli_expirar = fnValor($qtd_cli_expirar, 0);
$h_vl_faturamento_expirar = fnValor($vl_faturamento_expirar, 2);
$h_qtd_20_cli_faturamento = fnValor($qtd_20_cli_faturamento, 0);
$h_pct_20_cli_faturamento = fnValor($pct_20_cli_faturamento, 2);
$h_qtd_cli_resgate = fnValor($qtd_cli_resgate, 0);
$h_vl_total_resgate = fnValor($vl_total_resgate, 2);
$h_qtd_cli_expirado = fnValor($qtd_cli_expirado, 0);
$h_vl_faturamento_expirado = fnValor($vl_faturamento_expirado, 2);
$h_perc_vl_resgate = fnValor($perc_vl_resgate, 2);
$h_perc_vl_resgate_100 = fnValor(($perc_vl_resgate / 100), 2);
$h_pc_qtd_transacoes_fidelizado = fnValor((($qtd_transacoes_fidelizado / $qtd_transacoes) * 100), 0);
$h_pc_qtd_transacoes_avulso = fnValor((($qtd_transacoes_avulso / $qtd_transacoes) * 100), 0);
$h_pc_qtd_inativos = fnValor((($qtd_inativos / $qtd_clientes) * 100), 0);
$h_dt_filtroMenor = fnDataShort($dt_filtroMenor);
$h_dt_filtro = fnDataShort($dt_filtro);
$h_pct_tmcr = fnValor($pct_tmcr, 0);
$h_pct_tmsr = fnValor($pct_tmsr, 0);

//ICONES
$seta_info_up = fnFa64('text-info fal fa-arrow-up');

$cor_seta_cli = fnFa64($cor_seta_cli);
$cor_seta_transac = fnFa64($cor_seta_transac);
$cor_seta_total = fnFa64($cor_seta_total);
$cor_seta_fid = fnFa64($cor_seta_fid);
$cor_seta_av = fnFa64($cor_seta_av);
$cor_seta_freq = fnFa64($cor_seta_freq);
$cor_seta_uni = fnFa64($cor_seta_uni);
$fabirthdaycake = fnFa64("fal fa-birthday-cake fa-4x");
$faflag = fnFa64("fal fa-flag fa-3x");
$fachartpie = fnFa64("fal fa-chart-pie fa-3x");
$faarrowright = fnFa64("fal fa-arrow-right fa-3x");
$faarrowright_success = fnFa64("fal fa-arrow-right fa-3x text-success");
$faarrowright_danger = fnFa64("fal fa-arrow-right fa-3x text-danger");
$famalefCor1 = fnFa64("fas fa-male fa-2x fCor1");
$famalefCor2 = fnFa64("fas fa-male fa-2x fCor2");
$famalefCor3 = fnFa64("fas fa-male fa-2x fCor3");
$famalefCor4 = fnFa64("fas fa-male fa-2x fCor4");


$bar = unserialize($bar);
$cliente = unserialize($cliente);
$h_cliente[0] = fnValor($cliente[0], 0);
$h_cliente[1] = fnValor($cliente[1], 0);
$h_cliente[2] = fnValor($cliente[2], 0);
$h_cliente[3] = fnValor($cliente[3], 0);

$im = unserialize($im);
$h_im[0] = fnValor($im[0], 0);
$h_im[1] = fnValor($im[1], 0);
$h_im[2] = fnValor($im[2], 0);
$h_im[3] = fnValor($im[3], 0);

$faixa = unserialize($faixa);
$h_faixa[0] = fnValor($faixa[0], 0);
$h_faixa[1] = fnValor($faixa[1], 0);
$h_faixa[2] = fnValor($faixa[2], 0);
$h_faixa[3] = fnValor($faixa[3], 0);

$gm = unserialize($gm);
$h_gm[0] = fnValor($gm[0], 2);
$h_gm[1] = fnValor($gm[1], 2);
$h_gm[2] = fnValor($gm[2], 2);
$h_gm[3] = fnValor($gm[3], 2);


//echo "<pre>";
//print_r($dados);
//echo "</pre>";

$html = "<html>";


$html .= "<head>";

/*****SCRIPTS************************************************************************************/
$html .= <<<HTML
	<script src="../js/jquery.min.js"></script>

	<link href="../css/jquery.webui-popover.min.css" rel="stylesheet" />
	<link href="../css/chosen-bootstrap.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="../css/fa5all.css" />
	<link href="../css/bootstrap.vertical-tabs.css" rel="stylesheet" />

	<script src="../js/gauge.coffee.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script> 
	<script src="../js/pie-chart.js"></script>
    <script src="../js/plugins/Chart_Js/utils.js"></script>
	<script type="text/javascript" src="../js/plugins/jquery.sparkline.min.js"></script>
	
		
	<link href="../css/bootstrap.flatly.min.css" rel="stylesheet">
	<link href="../css/bootstrap.flatly.min.AUX.css" rel="stylesheet">

	<style>
	table td{
		vertical-align: top;
	}
	.linha{
		border-top: 1px solid #777;
		margin: 10px 0 0 10px;
		height:10px;
	}
	.quebra{ page-break-before: always; }
	.table-striped th,
	.table-striped td{
		font-size:12px !important;
	}

	.corBase {background: #F8F9F9;}
	
	.cor1 {background: #EC7063;}
	.cor2 {background: #F4D03F;}
	.cor3 {background: #58D68D;}
	.cor4 {background: #5DADE2;}
	.cor5 {background: #909497;}
	
	.fCor1 {color: #EC7063;}
	.fCor2 {color: #F4D03F;}
	.fCor3 {color: #58D68D;}
	.fCor4 {color: #5DADE2;}
	.fCor5 {color: #909497;}

	.cor1on {background: #CB4335; font-size:18px !important;}
	.cor2on {background: #D4AC0D; font-size:18px !important;}
	.cor3on {background: #239B56; font-size:18px !important;}
	.cor4on {background: #2874A6; font-size:18px !important;}

	.bar {		
		height:50px;
		border-radius: 5px;
		color: #ffffff;
		font-weight: bold;
		text-align: left;
		margin: auto;
	}

	html,body{font-size: 12px !important}
	.f30 {font-size: 30px !important;}
	.f26b{font-size: 26px !important;font-weight: bold !important;}
	.f21{font-size: 21px !important;}
	.f18{font-size: 18px !important;}
	.f17{font-size: 17px !important;}
	.f16{font-size: 16px !important;}
	.f15{font-size: 15px !important;}
	.f14{font-size: 14px !important;}
	.f13{font-size: 13px !important;}
	.f12{font-size: 12px !important;}
	.f11{font-size: 11px !important;}
	.f10{font-size: 10px !important;}

	.bar .pc{
		background: rgba(255,255,255,0.3);
		border-radius: 4px;
		margin: 10px 10px 0 15px;
		padding: 5px 10px 0 10px;
		float:left;
		height:25px;
		font-weight: bold;
	}
	.bar .vl{
		margin: 10px 0 0 0;
		padding: 5px 0 0 0;
		height:25px;
		font-weight: bold;
		float:left;
	}
	

	small{
		font-size:16px !important;
	}
	h1, .h1, h2, .h2, h3, .h3 {
    	margin-top: 21px !important;
    	margin-bottom: 10.5px !important;
	}
	h3,.h3{
    	font-size: 26px !important;
	}
	h4, .h4 {
    	font-size: 19px !important;
	}
	h5, .h5 {
    	font-size: 15px !important;
	}
	h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
		font-family: "Lato","Helvetica Neue",Helvetica,Arial,sans-serif !important;
		font-weight: 400 !important;
		line-height: 1.1 !important;
		color: inherit !important;
	}
	h1 small, h2 small, h3 small, h4 small, h5 small, h6 small, .h1 small, .h2 small, .h3 small, .h4 small, .h5 small, .h6 small, h1 .small, h2 .small, h3 .small, h4 .small, h5 .small, h6 .small, .h1 .small, .h2 .small, .h3 .small, .h4 .small, .h5 .small, .h6 .small {
		font-weight: normal !important;
		line-height: 1 !important;
		color: #b4bcc2 !important;
	}
	.bg{
		background-color:#F8F8F8 !important;
		padding:3px !important;
	}
	.tb{
		border-spacing: 10px !important;
    	border-collapse: separate !important;
	}
	small, .small {
    	font-size: 86% !important;
	}
	</style>
HTML;

$html .= "</head>";

$html .= "<body>";


/*******************************************************************************************/
$html .= "<h3 class='text-center'>Perfil do Cliente</h3>";
$html .= <<<HTML
	<table class='tb' style='width:100%'>
		<tbody>
			<tr>
				<td class='text-center bg'>
					<h5>Total de Clientes Cadastrados at&eacute; {$h_dt_exibe}</h5>
					<img src="{$chartarea}" style="max-width:300px;">
					<br><br><br>
					<h5>Base de Cadastros <b class="f21">{$h_qtd_clientes}</b> <img src="{$seta_info_up}"></h5>
					<h5>Novos Cadastros <b class="f21">{$h_qtd_cli_novos}</b> <img src="{$cor_seta_cli}">
						<br><small class="f14">Correspondem à {$h_pc_cli_cad}% da base</small></h5>
					</h5>
				</td>
				<td class='text-center bg'>
					<h5>Idade M&eacute;dia dos Clientes Cadastrados</h5>
					<img src="{$barchartgrouped}" style="max-width:300px;">
				</td>
				<td class='text-center bg'>
					<h5>Cadastros</h5>				
					<img src="{$barchartgrouped2}" style="max-width:300px;">
				</td>
			</tr>
		</tbody>
	</table>
HTML;


/*******************************************************************************************/

$html .= "<div class='quebra'></div>";

$html .= "<h3 class='text-center'>Fideliza&ccedil;&atilde;o</h3>";
$html .= <<<HTML
	<table class='tb' style='width:100%'>
		<tbody>
			<tr>
				<td class='text-center bg'>
					<h5>Faturamento em <b>{$mes_nome}</b></h5>
					<h3>{$h_pct_faturamento_fidelizado}% <img src="{$cor_seta_total}"></h3>
					<p>Clientes fidelizados geraram<br><b>R$ {$h_vl_faturamento_fidelizado}</b> de receita</p>								
					<p>Que correspondem a<br><b class="f21">{$h_pct_faturamento_ref}% </b>sobre o faturamento total</p>								
				</td>
				<td class='text-center bg'>
					<h5>Transa&ccedil;&otilde;es em <b>{$mes_nome}</b></h5>
					
					<table style='width:100%'>
						<tbody>
							<tr>
								<td class='text-center' colspan=3>
									<h3>{$h_qtd_transacoes} <img src="{$cor_seta_transac}"></h3>
									<p>Total</p>
								</td>
							</tr>
							<tr>
								<td class='text-center'>
									<h3>{$h_qtd_transacoes_fidelizado} <img src="{$cor_seta_fid}">
										<br><small>{$h_pc_qtd_transacoes_fidelizado}%</small></h3>
									</h3>
									<p>Fidelizados</p>														
								</td>
								<td class='text-center'>
									<h3>{$h_qtd_transacoes_avulso} <img src="{$cor_seta_av}">
										<br><small>{$h_pc_qtd_transacoes_avulso}%</small></h3>
									</h3>
									<p>Avulsos</p>														
								</td>
								<td class='text-center'>
									<h3>{$h_vl_indice_frequencia} <img src="{$cor_seta_av}"></h3>
									<p>Índice de Frequência</p>														
								</td>
							</tr>
						</tbody>
					</table>

				</td>
				<td class='text-center bg'>
					<h5>Clientes <b>&Uacute;nicos</b> Fidelizados que Compraram em <b>{$mes_nome}</b></h5>		
					<h3>{$h_qtd_clientes_compraram_mesm6} <img src="{$cor_seta_cli}">
					<br><small>{$h_pct_fidelizado_anterior}%</small></h3>
					<p>Clientes com compras em {$mes_nome} e compras nos meses anteriores</p>		
					<img src="{$barchartgrouped4}">
				</td>
			</tr>
			<tr>
				<td colspan=3 class='text-center bg'>
					<h5>Evolução do Engajamento Mensal</h5>
					<img src="{$lineChart2}" style="max-width:900px">
				</td>
			</tr>
			<tr>
				<td class='text-center bg'>
					<h5>Tickets e Gastos M&eacute;dios Limpos</h5>		
					<img src="{$barchartgroupedperformance}" style="max-width:300px">
					<small><p>TM com resgate <b>{$h_pct_tmcr}%</b> maior que TM sem resgate</p></small>
					<small><p>TM sem resgate <b>{$h_pct_tmsr}%</b> maior que TM avulso</p></small>
				</td>
				<td class='text-center bg'>
					<h5>Composi&ccedil;&atilde;o das Transa&ccedil;&otilde;es</h5>
					<img src="{$mydoughnut}" style="max-width:300px">
				</td>
				<td class='text-center bg'>
					<h5>Clientes Sem Compras nos <b>&Uacute;ltimos 60 dias</b></h5>
				
					<table style='width:100%'>
						<tbody>
							<tr>
								<td class='text-center' rowspan=2>
									<h3>{$h_qtd_inativos}
										<br><small>{$h_pc_qtd_inativos}%</small></h3>
									</h3>
									<p>Clientes inativos</p>
								</td>
								<td class='text-center'>
									<h4>R$ {$h_vl_gasto_acumulado_inativos}</h4>
									<p>Gasto acumulado nos &uacute;ltimos 60 dias anteriores</p>
								</td>
							</tr>
							<tr>
								<td class='text-center'>
									<h4>R$ {$h_tm_inativos}</h4>
									<p>Ticket m&eacute;dio</p>
								</td>
							</tr>
						</tbody>
					</table>

				</td>
			</tr>
		</tbody>
	</table>
HTML;


/*******************************************************************************************/
$arrayQuery2 = unserialize($top5cli);
$top5cli_tbody = "";
foreach ($arrayQuery2 as $qrAnalitics2) {
	$top5cli_tbody .= "<tr>";
	$top5cli_tbody .= "<td>" . fnMascaraCampo($qrAnalitics2['NOM_CLIENTE']) . "</td>";
	$top5cli_tbody .= "<td>" . fnMascaraCampo($qrAnalitics2['CARTAO']) . "</td>";
	$top5cli_tbody .= "<td>" . fnValor($qrAnalitics2['VALOR'], 2) . "</td>";
	$top5cli_tbody .= "<td class='text-center'>" . fnValor($qrAnalitics2['COMPRAS'], 0) . "</td>";
	$top5cli_tbody .= "</tr>";
}



$html .= "<div class='quebra'></div>";

$html .= "<h3 class='text-center'>Relacionamento</h3>";
$html .= <<<HTML
	<table class='tb' style='width:100%'>
		<tbody>
			<tr>
				<td class='text-center bg'>
					<h5>Cr&eacute;ditos a Expirar em <b>{$mesAniv}</b></h5>
				
					<table style='width:100%'>
						<tbody>
							<td class='text-center'>
								<img src="{$faflag}">
							</td>													
							<td class='text-center'>
								<h5><b>{$h_qtd_cli_expirar}</b> CLIENTES</h5>
								<h5><b>COM CR&Eacute;DITOS A EXPIRAR</b></h5>
							</td>
							<td class='text-center'>
								<img src="{$faarrowright}">
							</td>
							<td class='text-center'>
								J&aacute; compraram <br/><b>R$ {$h_vl_faturamento_expirar}</b> <br/> <small>Nos &uacute;ltimos 12 meses</small>
							</td>
						</tbody>
					</table>
				</td>
				<td class='text-center bg'>

					<h5 class='text-center'>Resgate de Cr&eacute;ditos de <b>{$mes_nome}</b></h5>
					<table style='width:100%'>
						<tbody>
							<tr>
								<td class='text-center'>
									<h4>{$h_qtd_cli_resgate}</h4>
									<p><b>Clientes que realizaram resgate</b></p>
								</td>
								<td class='text-center'>
									<img src="{$faarrowright}">
								</td>
								<td class='text-center'>
									<h4>R$ {$h_vl_total_resgate}</h4>
									<p><b>Valor total de resgate</b></p>
								</td>
							</tr>
							<tr>
								<td class='text-center'>
									<h4>{$h_qtd_cli_expirado}</h4>
									<p><b>Clientes com Cr&eacute;ditos Expirados</b></p>
								</td>
								<td class='text-center'>
									<img src="{$faarrowright}">
								</td>
								<td class='text-center'>
									<h4>R$ {$h_vl_faturamento_expirado}</h4>
									<p><b>Valor dos cr&eacute;ditos expirados</b></p>
								</td>
							</tr>
							<tr>
								<td class='text-center'>
									<h4>{$h_perc_vl_resgate}%</h4>
									<p><b>Valor vinculado ao resgate</b></p>
								</td>
								<td class='text-center'>
									<img src="{$faarrowright}">
								</td>
								<td class='text-center'>
									<p> A cada R$ 1 investido em resgate o seu cliente comprou <b>R$ {$h_perc_vl_resgate_100}</b></p>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td class='text-center bg'>
					<h5>Concentra&ccedil;&atilde;o de Faturamento</h5>												
				
					<table style='width:100%'>
						<tbody>
							<td class='text-center'>
								<img src="{$fachartpie}">
							</td>													
							<td class='text-center'>
								<p><b>20%</b> dos clientes mais rent&aacute;veis</p>
								<p>atendidos em <b>{$mes_nome}</b></p>
								<p>correspondem a <b>{$h_qtd_20_cli_faturamento}</b> clientes</p>
							</td>
							<td class='text-center'>
								<img src="{$faarrowright}">
							</td>													
							<td class='text-center'>
								<p>que correspondem a <b>{$h_pct_20_cli_faturamento}%</b> do faturamento do m&ecirc;s</p>
							</td>
						</tbody>
					</table>

				</td>
				<td class='text-center bg' rowspan=2>

					<h5 class='text-center'>Top 5 Clientes de <b>{$mes_nome}</b></h5>
					<table style='width:100%;' class="table table-striped">

						<thead>
							<tr>													      
								<th scope="col">NOME</th>
								<th scope="col">CART&Atilde;O</th>
								<th scope="col">VALOR (R$)</th>
								<th scope="col">QTD. COMPRAS</th>
							</tr>
						</thead>

						<tbody>
							{$top5cli_tbody}
						</tbody>

					</table>
				</td>
			</tr>
			<tr>
				<td class='text-center bg'>
					<h5>Aniversariantes de <b>{$mesAniv}</b></h5>
		
					<table style='width:100%'>
						<tbody>
							<td class='text-center'>
								<img src="{$fabirthdaycake}">
							</td>
							<td class='text-center'>
								<h5><b>{$h_qtd_aniversariantes}</b> clientes</h5>
								<h5><b>Aniversariantes</b></h5>
							</td>
							<td class='text-center'>
								<img src="{$faarrowright}">
							</td>
							<td class='text-center'>
								J&aacute; compraram <br/><b>R$ {$h_vl_faturamento_aniver}</b> <br/> <small>Nos &uacute;ltimos 12 meses</small>
							</td>
						</tbody>
					</table>
				</td>


			</tr>
		</tbody>
	</table>
HTML;



/*******************************************************************************************/

$html .= "<div class='quebra'></div>";

$r_freq1 = round($freq1);
$pess1 = str_repeat("<img src='{$famalefCor1}'>", $r_freq1);
$r_freq2 = round($freq2);
$pess2 = str_repeat("<img src='{$famalefCor2}'>", $r_freq2);
$r_freq3 = round($freq3);
$pess3 = str_repeat("<img src='{$famalefCor3}'>", $r_freq3);
$r_freq4 = round($freq4);
$pess4 = str_repeat("<img src='{$famalefCor4}'>", $r_freq4);

$html .= "<h3 class='text-center'>&Iacute;ndice de Rentabilidade por Perfil de Clientes</h3>";
$html .= "<div style='clear:both;height:5px;'></div>";

$facalendar = fnFa64("fal fa-calendar-alt fa-3x");
$fahistory = fnFa64("fal fa-history fa-3x");
$fasync = fnFa64("fal fa-sync fa-3x");
$fashopping = fnFa64("fal fa-shopping-cart fa-3x");

$html .= "<h5 class='text-center'>Dados do Ciclo de Recompra</h5>";
$html .= <<<HTML
	<table class='tb' style='width:100%'>
		<tbody>
			<tr>
				<td class='text-center text-info'>
					<img src='{$facalendar}'>
					<h5>{$h_dt_filtroMenor}</h5>
					<small>Clientes cadastrados anterior a esta data</small>
				</td>
				<td class='text-center text-info'>
					<img src='{$fasync}'>
					<h5>{$classifica}</h5>
					<small>Periodicidade configurada para atualização</small>
					<br/><small>(base ref. 01/jan)</small>
				</td>
				<td class='text-center text-info'>
					<img src='{$fashopping}'>
					<h5>{$h_dt_filtroMenor} a {$h_dt_filtro}</h5>
					<small>Com compras neste período</small>
				</td>
				<td class='text-center text-info'>
					<img src='{$fahistory}'>
					<h5>{$qtd_diashist}</h5>
					<small>Período previsto para retorno do cliente</small>
					<br/><small>(dias)</small>
				</td>
			</tr>
		</tbody>
	</table>
HTML;


$html .= <<<HTML
	<table class="table table-striped" style="width:100% !important;">
		<thead>
			<tr>
				<th class="text-center f18" style="width:240px;">CONCENTRA&Ccedil;&Atilde;O DE CLIENTES</th>
				<th class="text-center f18" style="width:340px;">TIPO DE CLIENTE</th>
				<th class="text-center f18">GASTO M&Eacute;DIO</th>
				<th class="text-center f18">RENTABILIDADE</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td class="text-center">
					<div class="bar cor1" style="width: 100%;">
						<div class='pc'>{$bar[0]}%</div>
						<div class='vl'>{$h_cliente[0]}</div>
					</div>
				</td>
				<td class="text-center">
					{$pess1}
					<div style='clear:both;'></div>
					    <span class="f18 fCor1"><b>{$faixa[0]}</b></span>
					<br><span class="f12 fCor1"><small>{$h_im[0]} anos </small></span>
					<br><span class="f13 fCor1"><b>{$txt_compras1}</b></span>
				</td>
				<td class="text-center">
					<span class="f26b fCor1"><b>R$ {$h_gm[0]}</b></span>
				</td>
				<td class="text-center">
					<span class="f30 fCor1"><b>{$r_freq1}x</b></span>
				</td>
			</tr>
			<tr>
				<td class="text-center">
					<div class="bar cor2" style="width: 80%;">
						<div class='pc'>{$bar[1]}%</div>
						<div class='vl'>{$h_cliente[1]}</div>
					</div>
				</td>
				<td class="text-center">
					{$pess2}
					<div style='clear:both;'></div>
					    <span class="f18 fCor2"><b>{$faixa[1]}</b></span>
					<br><span class="f12 fCor2"><small>{$h_im[1]} anos </small></span>
					<br><span class="f13 fCor2"><b>{$txt_compras2}</b></span>
				</td>
				<td class="text-center">
					<span class="f26b fCor2"><b>R$ {$h_gm[1]}</b></span>
				</td>
				<td class="text-center">
					<span class="f30 fCor2"><b>{$r_freq2}x</b></span>
				</td>
			</tr>
			<tr>
				<td class="text-center">
					<div class="bar cor3" style="width: 65%;">
						<div class='pc'>{$bar[2]}%</div>
						<div class='vl'>{$h_cliente[2]}</div>
					</div>
				</td>
				<td class="text-center">
					{$pess3}
					<div style='clear:both;'></div>
					    <span class="f18 fCor3"><b>{$faixa[2]}</b></span>
					<br><span class="f12 fCor3"><small>{$h_im[2]} anos </small></span>
					<br><span class="f13 fCor3"><b>{$txt_compras3}</b></span>
				</td>
				<td class="text-center">
					<span class="f26b fCor3"><b>R$ {$h_gm[2]}</b></span>
				</td>
				<td class="text-center">
					<span class="f30 fCor3"><b>{$r_freq3}x</b></span>
				</td>
			</tr>
			<tr>
				<td class="text-center">
					<div class="bar cor4" style="width: 50%;">
						<div class='pc'>{$bar[2]}%</div>
						<div class='vl'>{$h_cliente[2]}</div>
					</div>
				</td>
				<td class="text-center">
					{$pess4}
					<div style='clear:both;'></div>
					    <span class="f18 fCor4"><b>{$faixa[3]}</b></span>
					<br><span class="f12 fCor4"><small>{$h_im[3]} anos </small></span>
					<br><span class="f13 fCor4"><b>{$r_freq4} ou mais compras no per&iacute;odo</b></span>
				</td>
				<td class="text-center">
					<span class="f26b fCor4"><b>R$ {$h_gm[3]}</b></span>
				</td>
				<td class="text-center">
					<span class="f30 fCor4"><b>{$r_freq4}x</b></span>
				</td>
			</tr>
		</tbody>
	</table>
HTML;
/*******************************************************************************************/



$html .= "</body>";
$html .= "</html>";


//echo $html;exit;

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');

$dompdf->render();
$font = $dompdf->getFontMetrics()->get_font("helvetica", "");
$dompdf->getCanvas()->page_text(35, 810, utf8_encode("Emiss�o: ") . date("d/m/Y H:i:s") . str_repeat(" ", 160) . utf8_encode("P�gina") . " {PAGE_NUM} de {PAGE_COUNT}", $font, 8, array(0, 0, 0));

//if (@$_GET["baixa"] == "S"){
//	$pdf = $dompdf->output();
//	file_put_contents("arquivos/".$filename.".pdf", $pdf);
//}else{
$dompdf->stream($filename . ".pdf", array("Attachment" => false));
//}




function fnFa64($icon)
{
	$icon = trim($icon);
	$icon = preg_replace('/\s+/', ' ', $icon);

	$ret = $icon;
	switch ($icon) {
		case "text-danger fal fa-arrow-down":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF0WlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDUgNzkuMTYzNDk5LCAyMDE4LzA4LzEzLTE2OjQwOjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIxLTExLTE4VDEyOjAzOjI5LTAzOjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDIxLTExLTE4VDEyOjAzOjI5LTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMS0xMS0xOFQxMjowMzoyOS0wMzowMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpkNzc4OTk0Mi02YWE5LWJjNDItYmUxNy1hMjEzZjIzMjcwZWMiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDo0MTQ4YTA2NS03NTNlLWU2NDYtYTBmNy1jYTgyNTRkMTRmMDEiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDplODlhMGNlOC01YTI1LTFhNGUtYTYxMC0wZWJmZDBjY2I0ODUiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIj4gPHhtcE1NOkhpc3Rvcnk+IDxyZGY6U2VxPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0iY3JlYXRlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDplODlhMGNlOC01YTI1LTFhNGUtYTYxMC0wZWJmZDBjY2I0ODUiIHN0RXZ0OndoZW49IjIwMjEtMTEtMThUMTI6MDM6MjktMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE5IChXaW5kb3dzKSIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6ZDc3ODk5NDItNmFhOS1iYzQyLWJlMTctYTIxM2YyMzI3MGVjIiBzdEV2dDp3aGVuPSIyMDIxLTExLTE4VDEyOjAzOjI5LTAzOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+oNzZ9gAAAUdJREFUSIlj/P//PwOtAROpGt5Pd774wrfk6JtnP3mI1cNCqiUMf3+xMzAw6DH8ZWAmVgvJPiEHjFoyaslwseTD+hnZby5+VqGWwT+/PRR8O3NJxrtnP3lYGBgYGH5+OWH4a/HS3H+/D0e9rp2RKWrGe4lSCz7XJx34c4OBgVHE9gUTAwMDAzuPxXne/uIyRtZHwn+bM6a/PvVZjyoWePXMEg+W3wAPLk75gE18FFqEYUGm4VQGBrSIp8QiXBZgWEKuRfgswGoJqRYRsgCnJcRaRIwFeC0haNG/Fx8+t2W8I2QBAwMDAyMxdfz3hxv8PhX2dv3/LafOKPyI4f9bCQZGsS8M/1/9ukTIAqItQbWIQR0iwkaUBQwMDAwM////Jxp/e7De70WgzY3nPk4XX0w7l02sPpIsgVh00OLVuit+pOghOrgoAQAiM270sy0N0QAAAABJRU5ErkJggg==";
			break;
		case "text-info fal fa-arrow-up":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF0WlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDUgNzkuMTYzNDk5LCAyMDE4LzA4LzEzLTE2OjQwOjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIxLTExLTE4VDEyOjAxOjEzLTAzOjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDIxLTExLTE4VDEyOjAxOjEzLTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMS0xMS0xOFQxMjowMToxMy0wMzowMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpiMjQ2N2Y0OC0xZmY3LTNlNGYtOGMwMi0xMjkwYTY1NDkwZjQiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDoyMzM2ZDkwNi03NjE3LWJhNDMtYjA5MS1jYjIwMDEwNmI5NjkiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo0NmQ3OTM5My02ZTQwLWU2NDgtYjU1NS0wYTljNWNhYjgyYTgiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIj4gPHhtcE1NOkhpc3Rvcnk+IDxyZGY6U2VxPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0iY3JlYXRlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo0NmQ3OTM5My02ZTQwLWU2NDgtYjU1NS0wYTljNWNhYjgyYTgiIHN0RXZ0OndoZW49IjIwMjEtMTEtMThUMTI6MDE6MTMtMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE5IChXaW5kb3dzKSIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6YjI0NjdmNDgtMWZmNy0zZTRmLThjMDItMTI5MGE2NTQ5MGY0IiBzdEV2dDp3aGVuPSIyMDIxLTExLTE4VDEyOjAxOjEzLTAzOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+E50W+AAAAUpJREFUSIlj/P//PwOtARMpin9+eMO+fPmd1tC1z/4/+PFTjuqW/Pzwhn3d9g91fZ8Yqh68+cZQuvXtQ2ItYiQmuH5+eMW9cMunKbO/MiQgiyuIcDF0ewvLK3CwP8Knn6BPkC2w1ORgiGBgYGDg4WCokGVkeEakj/BagmqB0Ltuc1YemJyVs5jAJCnGAmKCDqcl6BZMshMSRlXBxmDsqzxxBhEWYbWEsAUIQIxFGJaQYgGxFqFYQo4F2CxK3PAGxSK4JZRYgG7Rl4/fUSxigljwknv2xk/zZ39lSLDUECTLAnwWMTEwMDD8evZT9eAPhlBLDcF3k+yFybYAw6LPvxiuvGUIZPj//z/R+MePF9w9M27/N1ny+P+zHz/4idVHUgFJLhi1ZNSSEWjJNlI1sJCimJ1d/KuD6s/l7FwcXyXZ2T8Sq4+ohgSlAADItTHIpjVvTQAAAABJRU5ErkJggg==";
			break;
		case "fal fa-birthday-cake fa-4x":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF0WlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDUgNzkuMTYzNDk5LCAyMDE4LzA4LzEzLTE2OjQwOjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIxLTExLTE4VDExOjU4OjQxLTAzOjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDIxLTExLTE4VDExOjU4OjQxLTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMS0xMS0xOFQxMTo1ODo0MS0wMzowMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpjMGJjYWM2Ny03YjlkLWI2NGMtYTBhMy04NDY5NjMzYjU2MWYiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDo4Y2E1ZTNkMi1kNGM2LTAxNDgtYjIx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";
			break;
		case "fal fa-flag fa-3x":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF0WlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDUgNzkuMTYzNDk5LCAyMDE4LzA4LzEzLTE2OjQwOjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIxLTExLTE4VDExOjU0OjM2LTAzOjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDIxLTExLTE4VDExOjU0OjM2LTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMS0xMS0xOFQxMTo1NDozNi0wMzowMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo4NjlkODU2Yi0xZjFmLTAwNDUtODU5YS1lMDY3MmE2YzUxMTAiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDoyMzQ1YTg1Ni0wNGM5LWM3NDYtODI4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";
			break;
		case "fal fa-chart-pie fa-3x":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF0WlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDUgNzkuMTYzNDk5LCAyMDE4LzA4LzEzLTE2OjQwOjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIxLTExLTE4VDExOjU2OjM0LTAzOjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDIxLTExLTE4VDExOjU2OjM0LTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMS0xMS0xOFQxMTo1NjozNC0wMzowMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo2M2I3Zjg3OS02NjZhLWZkNGEtYTY2NS1jYWY2Njk5Y2Y3YzQiIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDo3NDUyZjU2MC1mMzUzLWY0NDMtYWIx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";
			break;
		case "fal fa-arrow-right fa-3x":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFwmlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDUgNzkuMTYzNDk5LCAyMDE4LzA4LzEzLTE2OjQwOjIyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlRXZlbnQjIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoV2luZG93cykiIHhtcDpDcmVhdGVEYXRlPSIyMDIxLTExLTE4VDExOjUxLTAzOjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDIxLTExLTE4VDExOjUxLTAzOjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMS0xMS0xOFQxMTo1MS0wMzowMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDozOTk5MjFjYS0yMTYzLTViNGUtOWQwNC00ODIxZGI4MjE5NTciIHhtcE1NOkRvY3VtZW50SUQ9ImFkb2JlOmRvY2lkOnBob3Rvc2hvcDo3OWI1MjRjYy1lZGNkLTdhNDQtYTRjNi02ZjAyM2FjNTg4NDkiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpjMzgxNjFkMC1iZGUxLWYxNDYtYjMyZi03NDJhOGFmMjFiZWQiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIj4gPHhtcE1NOkhpc3Rvcnk+IDxyZGY6U2VxPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0iY3JlYXRlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDpjMzgxNjFkMC1iZGUxLWYxNDYtYjMyZi03NDJhOGFmMjFiZWQiIHN0RXZ0OndoZW49IjIwMjEtMTEtMThUMTE6NTEtMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE5IChXaW5kb3dzKSIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6Mzk5OTIxY2EtMjE2My01YjRlLTlkMDQtNDgyMWRiODIxOTU3IiBzdEV2dDp3aGVuPSIyMDIxLTExLTE4VDExOjUxLTAzOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+8B8xrgAAAnVJREFUWIXN2E9IFGEYx/Hv/mGWtSUqk9ZYiLoEXQzy4CXospKweNgOSV4CRRAUogiUDuLB8hSBewhRyEPmoTJK26hDeclLp/KUFRpGK+Us2fpnhnbfDuuKqKO5+8xMPxgYXuZ9+MzDM+9hPEoppPL29dfz5onDnDtW9kKsqFJK5Ho3/uli9d1pVd3/RT2cWWqSquuVeEnD+KFNzavjAOSy9L6cH3g0u9wkUVsEGAhUmA3Rsv6YxjAgihQBAgQiR/WOaFn7FuTcSklIMSBYIJOpkpCiQJBHeiSPmY0xUt9Dt5NLdx6b5GFeHx114eYLkeDgXuqId7CQQLgyc7Vu35W4Rh5UZCdtA4IM0lYglI60HQilIR0BgjVybG7l0k77bPuKrWIspEKJp5lbIyZtAHh9dNWFG2OR4PB2zzvWwUIC5eFMW32os0EjAUAuS3cydd+qk44DYW9IV4Dw70jHZ3Bz1mby94i5trBpJj2Ls6mKD7o65aIRc8V8M/TeZKqwoPnpiR6J10aCo56+/mk1lHOTZxHNT09tZdxr/I84ADPLTCZ32h+r0s4GdOpdxeSy1ye/Zfm4vuChoaYi0XIy2OX+R7Kq+ybG039u/Cw48rhrVfvbwcVjBtZxl61w4CJwA27ACgcuAY1V3ffqid66Gw5cABZw3b/o2w0HDgO3w8WrywetcOAg0ArXeeZA8077HAEWiwMHgKXgAPx248ZG9dbexeJwYGMHJXBgE1AKBzYAt8PFqg4OF4MD4Rk0Mgu+sWfpLbiumkONxdYU66AdOBAETjxPt0jjQBBoZvHl7+RwgNxf/tX0fPDeg8/q5uTCgFRNpRR/AQmYfJb2FSRyAAAAAElFTkSuQmCC";
			break;
		case "fal fa-arrow-right fa-3x text-success":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAMAAAANIilAAAAAt1BMVEUAAAAYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJwYvJxsbpthAAAAPHRSTlMAAQMEBgcJCg0QExQXGxwdHh8gIyUmNDU2OURKTlFcXWZoc3V7iIyUl5qbq62wwcjKzM7R09Xe4vP3+futJ1HGAAAA1ElEQVRIx+3V1wrCAAyF4dS6995777173v+5lCrojQVPkCr4X+e7SiAir/OHPEIWXwHomZTNw27rI2wB93Y+3hI6C9DaOIDXUYDXOSh0GBq91ujISaPjLurE2T2ddFGnflanP6srCwvv9KyHeLeHLgK8nkKhjwzG0rAxuMoaPNbgmQZ3NThm4z1lW7dVdRnbvh9J0OLt9TUdeSti5qu1VzXOztax/t/+7ZfZusJ6LN5KUmElo7ASUFiRkcKKueGtiHfD2ydN2Kue27YpVEZpMOlEHUcuQTpehmJB36EAAAAASUVORK5CYII=";
			break;
		case "fal fa-arrow-right fa-3x text-danger":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAMAAAANIilAAAAAt1BMVEUAAADnTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDznTDzAYDs9AAAAPHRSTlMAAQMEBgcJCg0QExQXGxwdHh8gIyUmNDU2OURKTlFcXWZoc3V7iIyUl5qbq62wwcjKzM7R09Xe4vP3+futJ1HGAAAA1ElEQVRIx+3V1wrCAAyF4dS6995777173v+5lCrojQVPkCr4X+e7SiAir/OHPEIWXwHomZTNw27rI2wB93Y+3hI6C9DaOIDXUYDXOSh0GBq91ujISaPjLurE2T2ddFGnflanP6srCwvv9KyHeLeHLgK8nkKhjwzG0rAxuMoaPNbgmQZ3NThm4z1lW7dVdRnbvh9J0OLt9TUdeSti5qu1VzXOztax/t/+7ZfZusJ6LN5KUmElo7ASUFiRkcKKueGtiHfD2ydN2Kue27YpVEZpMOlEHUcuQTpehmJB36EAAAAASUVORK5CYII=";
			break;
		case "fas fa-male fa-2x fCor1":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAAjCAYAAACZ6FpfAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFSSURBVEhLY/z58+d/BioAJihNMcDuon9vGX5snM/w+8w9BoY/QD6/EgOTVyIDp4EwTpsxDfr3guHb5CaGv8+gfCTA6FbHwOUsgdUwDLG/F9ZhNQQE/u9Zx/DrF5SDBjAM+n37CpSFBfy7wvD3LpSNBqgW2BgGsarqQFlYAJMOA7MylI0GMAxiNghiYJaCctAAo0sQAxsblIMGaBj9ZAIUg35fWMLwc+0xhv+gKAa5QlkYIvHrLcO/G1DXSVgxsCbEMHAIQqRgAG7Qv9tLGL7OOQYWJAi4rRjYamIY2JH8CWf+OkekISDw9RjDn5tQNhQgzAQ5mxTwF0pDAa5IIBmMGkQYjASDWM1cGBhZoBxCQNSFgUUNyoYCzNz/egfDl55NDFiLBFE/BvYSDwZsRdJorBEGowYRBpgGiRowMKvyQTlIgImPgcnGAGtiBAEq1WsMDAAya3GVL3ydPQAAAABJRU5ErkJggg==";
			break;
		case "fas fa-male fa-2x fCor2":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAiCAYAAACA5IOiAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFYSURBVEhL7ZQ/S8NAGMaf9K5JimhpcQm6OBSEfoLu3bJ3Cw5OdfdTuLs5SJbSPZsfxE0QJCpooUNs0/zxLnkjPRuxqYKg/pa873v3JHfPvRctFWBDVsXxM+Y354iCK0COsD5qrROYe21o+Yw3VHHsY3Z9hDgKqVCgQ9u5ROPAUl5Qo2dG7I9LhJIQ6XSMcEYpoYijF4+iMjwkEwoJRVwVRcwbNkVl2MI4CglFzKwBGNcpW0YaNoBuUkqoy2YWzMMR+FZfnENRE0e1O1pxWvI9TbK4c7F4cpGm4qjk1wwrm4DERzKXDSO2wx3wfQdGMx/KxOmDi+D+Iq98hnaMeteBzmjP4dTN6muRuogf8zA3TC51bcRcckl1uyL/4or8sJg3h9DeX5mP4EOwdh6qt2riIbg9KxpIhZ9C79qoUyr5s25vyq8Qt3pgRoeSZTrQtntKg0i+8N8GXgEie2yiCfaLIAAAAABJRU5ErkJggg==";
			break;
		case "fas fa-male fa-2x fCor3":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAjCAYAAABLuFAHAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFHSURBVEhLY/z58+d/BmTw7z3Dxee7GFb8fM3wDCjDyiTK4MnrxhAsIghVgAComv+9YNjzaA3D4r9QPhJQ4Q5hqJWQgPIggAlKg8Hvt0cYVmDRCAJ3vh5hOPMLyoECFM23f75g+A1lY4IXDNc/Q5lQgKKZVICiWZVdgoEVysYEEgyavFAmFKBoZhW2YYhghnLQgAq3DYMJG5QDBVSMKhIBXPPHN5sZJn16yHAHapsVKxfE//+/MVz7DXEFF7M8Q4aoL4M+N0gCpvn9doa8d3cZPkLE8ANGZYZaBU8GFWBogQPs2lciNYLA/7sMx99DmNSLZ1LBqGYSwQBrVuVWY5BnBPMJAlZmNQZLaL5GzVWfDzI0v7rMcAfKRQHMugw9CvYMolAuCIzY0CYXDAvNvFoMLqzsWGoNdgYTLi2UBAICFJXbA+VnBgYAeNRvtB1mBLsAAAAASUVORK5CYII=";
			break;
		case "fas fa-male fa-2x fCor4":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAkCAYAAACAGLraAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAFXSURBVEhLY/wPBAwUACYoTTbAdMHfLwwXT3xiWP7qL8MzoAwrKzODpyofQ4gGD1QBKkA14O9nhj27PjAs+g7lIwEVOQGGOhNeKA8BULzw+8YXhuVYNIPAnUdfGM5gkUMx4PabPwy/oWxM8Ifh2lMoEwlQHIgoBqiKsDCwQtmYgIVBSxrKRAIoBrACQzqSE8pBAypyPAwmWOSoHI1kAKgBfxg+3njDMPHmb4Y7fyG2WgkwQcLjzz+Gax8gruHiZGXINBdh0BdiAWsGAYgB914y5F74xfARKogXsLAx1HmLM6gwQ7jgQLz2mEjNIPDnF8Ox+1A2EEBigdRQAHoTBqibkMgBowYMGgNU5dgY5BnBfIKAlYONwUoOygEC1Nz46jVD05EfDHegXBTAwcHQ6yXKIArlwsBoLIwaAAKoBohxMLjyMWKpnRgZTGQ4MBIRCFBcL1DoBQYGAN5zdxCGUGGNAAAAAElFTkSuQmCC";
			break;
		case "fal fa-calendar-alt fa-3x":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC8AAAA0CAYAAAAaNmH0AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAOUSURBVGhD7Zk/SBtxFMe/ic3ljIkG/4K1ldiqOHTooIuTLS0pLQgtLtKlLq1TOznVyc0pLrVQaheHQkHoUAgVpFC6NE5GkCtiCJVUTKLGNDE5TdLf7/Iz3i+JNFHbSzQfOPLuvXB888v7vbv3TpcioEzRs8+y5IKJ3w9DWvbDvRFjjlMS3IZrOQBPOMkchVN0zrudqxjxUsuImadXcEPxnpQApt/sYIbqrrdgYagFlnSgIIpf+QT7PCPkwwXfT0BmZqFUNqxWlLX4/Bs2uQdpMYiPnjiWsoqKLKfgYXlqE3UQ0uaJ8cVSCFODLGO3oFN8GUQD7G0m3OtrRIOB+VTkio8GMTO3jekIOy8BBNGI10OkspmYg5GVNjJc86UlnCLH4nj2eTOnGvErL29i8t0uPlDbIMDx6DL666qUkCaE/JiaC2FWUW3A5HA7BlQ3An7lQwfwMRMtJm2FU+qacKuZ2eQGs5u1/yqlUivKWjy/Yf0+vJiL4hu166vxque0Vfz0SCshTG1RS4+XDzsw2KS4FY4XX3Lkiq+kzb/kZGnTZoXrfqPi1hL3J9L8rFPrnKVNRbxWnNNqc8yGDX734vnKATtLU9taB8edxnRjEg2QfiCEBXWjXmXA6IOr6LfSkyQ8X70YX+NHHbaeZkz05c4OznTD+vz7kEj3oz5cfjndDVG8e6Qf4ONSRMbiLxZHBEs/E3ycHM7VMILsG4VSyXmtuFjiu7tFDIg6dGcOPYY6zWhgcVwzY6xBr4qTwyrC3s7iqEFvp4BedVyswuhN69E1CqTyeKAVxYsPh+Be9sOlOqSguqjH4JP4uEsK8WOLLTrW5r8jbZ12xF1A2hz9jSosJjiHW9M5u7aOwXnyA5TAESMD1zHaRa3fcL7fwHhIcR9xzIj7/6ZNPJUjnCJn/pwU4vkWuTLiLiOKFm8mdTkbgdTpTMNYp8/zqkeHhsyQ9BJqRWaqEGoMRb3SoRRf55PkISzCv9sxitUQ1CPo6B7C3FOlQPa0anSYcw0djDUihDxLebYbVk+EWKq5gxNOMfFxTjgl5xr5hf+Ni5XzpURZi+c3bDKAqbc7mKU3EX0Vxm43wd6k3Yxe9m/D8SUK5z49M8DxpB39qjkYL570l9K8B4/XVK4SwdZuxayd9cmMrLQhz+FktSeURrl0sFjNcNzlhVOyVv6QBIJrASz8kBFgHm3Qw9ZVC3tH/tvXMeLLg0qp1AbgD+2wtwNFtBwqAAAAAElFTkSuQmCC";
			break;
		case "fal fa-history fa-3x":
			$ret = "data:image/png;base64,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";
			break;
		case "fal fa-sync fa-3x":
			$ret = "data:image/png;base64,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";
			break;
		case "fal fa-shopping-cart fa-3x":
			$ret = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADsAAAA0CAYAAAA0c0BmAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAVOSURBVGhD5ZpfSFtnGMafRM8xJiaV/NPGOkwsTd2mV3UXCxtTGBTKSNlod+MuZtlFr3bn1byaV151N/NiTBh0pdQx8GrCwEJLoCBsTFnbjGEiNfFvJBqT6InGfSe+J9MYE8856knwd+PzRYN5+N73/d73fNHtMXBB0NPPC8GFMns4jFPrmJkVsEXLYhgumdDZaqRVdXHI7MzEv+ifo0UJfO868cBnoVX1oCiMAy9ZBJCuJg6HcYKZmDs+jMOv1zEcE5Ue39z2wN+Ue7lqkHX0xF6EcPOv3Zzu+8CDr9+urvom69PanBxcpIOxTVLVg7ymIrGEwUcJTIhar4OXz71aUbjsJtzvaYa7yIEhzyziGPtpFcOlzqYKgHeYMfFpE8y0lpCZdEa4LpGsYIR4BlHSB5G5s0D0+Sz8L7NM6XD/43b0e/Zf15yDKdZkQeC2E4VZJrucuuwcqT1ML1dQkVoQMEmy22Y4YlRE/tnRxMFHcipeOckbXcpAIN3JdrYY8s1aa+GldwlrAnI9huYICMXE1BLhcP0yyQLkm4UJ1xtJJnYwK/0PTUkiuEbSUIu2wjJMKDBbD1ejjnQG4WJl77xZ28Z0hrSVh5tkIQrMAm5nLak9Fj5SpmjIooAASZ+jntRRFJnlbSxvSQeWk6S0I7QsbasOXc4G0kdRZBYuHt0ko3EBCdLakEZ4RWoV2CZcIVkEZWb1hv+LADMbJqkNSbyOkzRxcJfo15WZZV2nx0oyu4OIVAm1YEnADJ0IvJ3PT2XFUGiWRXJjDaldBBe1O3+ExQymSPfaTKSKo9hspcy2oeUdUqw4XT6+EosoNgsHhy6SU7GtfKt2vmyyo08qThzaSsUwQ7lZcx26DKTXM4iQPFeyaQTXSZtq0VLGjXKzsKBNKlJbOwhrcf5EhXy+litOIirM6tHSKL19B6EVkudIYmEHQdLlipOI7OH9EK/eoPvZNi20RIeBW+24U6KhEFGxs4wDs62m8Kx+lIthhrqdxSpGfohjlI5Zt0FX9AnBWWIx1eHLXhe6reX3TaXZNJ6ORTCQ66B4fP/VW+hWFytnisqPVoGzbQlU70PFzbYlUG2Wb+by414lzLalUJ9hrG2UBnntZ9vSqCxQIgmMP1rCkOhSr4e/lYNt/xdnC8eh9z0nvOaT79cpmD35jf2pY6zH+BctZdtECfVhzHY2Ij0pOG9SaQwHNmhRHtU7m5iaw80/6Gk8C+P+G1bcvVqH7egaRl+kMU6XBrYrFvz8oVlG05HE2C9xjFCBdzvqMfi+FW3cNqan1jA4l6X6oMfAJx7cOfsOahMTjxcxmBuz2D+91cb60wPBklrBd4/X8VB8+Kevw+i9VnSeNJZm5+H/fSt3G8c3WfD00EVVFtHnYbpgA7xeOx5+JD25Px6VYbx1YJ40wHfQqIjRAdbJ7ZPNYFbGXUlsQchfO969Zi+ICD1c7xjzfXkwKT2tKM0p5Gz1oNKsAV7pcjq5hcB8wYM3FsaT+RayBnYZF9k2dn5Lu/nkn9WCxz4sjP9O5W8BvCapiyuNSrMN8LVLHyqL4d/CGPkzjlgijWgwgqExylcG32xE98mrE3C1Affo74WlDfT9GsHMYhqJWByBiTD6KF9FC/5r5fNVRP05m93A+JNlDEm5Www2b45+zoqTzG8BCq/eoO/ZNkK0Loacb9upz1m9Bf7PHPjWUXyW5Q08HvhbZBsV4Tta8GOP4dixsafDjmEZXys8lQ5qnywLt3VMBjcRSrElV4Mutxk+zzGXpXLIpBAKsvCd382drXZrPW50WOGW0SqKnKLZykd9GFcRF8gs8B+ZxMWztxkCUwAAAABJRU5ErkJggg==";
			break;
	}

	return $ret;
}
