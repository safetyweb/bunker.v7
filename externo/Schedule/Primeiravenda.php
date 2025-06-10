<?php
require '../../_system/_functionsMain.php';
include_once '../email/envio_sac.php';
$conadmmysql = $connAdm->connAdm();

$to['email6'] = 'margareth@markafidelizacao.com.br;coordenacaoti@markafidelizacao.com.br;marcio@markafidelizacao.com.br';
$subject = 'Aviso de primeira venda';
$headers = "From: comunicacao@bunker.mk\r\n" .
	"Reply-To: \r\n" .
	"Content-Type: text/html; charset=UTF-8\r\n";

$sql = "SELECT * FROM empresas WHERE LOG_ATIVO='S' AND LOG_INTEGRADORA = 'N' AND COD_SISTEMAS NOT IN (2,12,19,16,21,13,15) AND DAT_EXCLUSA IS NULL";
$query = mysqli_query($conadmmysql, $sql);

while ($result = mysqli_fetch_assoc($query)) {

	$cod_empresa = $result['COD_EMPRESA'];
	$nom_fantasi = $result['NOM_FANTASI'];

	$sqlUnivend = "SELECT * FROM unidadevenda WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS ='S' AND LOG_COBRANCA ='N' AND DAT_EXCLUSA IS NULL";
	$queryUnivend = mysqli_query($conadmmysql, $sqlUnivend);

	while ($resultUnivend = mysqli_fetch_assoc($queryUnivend)) {
		$dat_ini = date('Y-m-d', strtotime('-5 days'));
		$dat_fim = date('Y-m-d');

		$nom_unid = $resultUnivend['NOM_FANTASI'];

		$sqlVend = "SELECT DAT_CADASTR AS data, COUNT(*) AS totalVendas, cod_empresa, cod_univend
		FROM vendas 
		WHERE COD_UNIVEND = " . $resultUnivend['COD_UNIVEND'] . "
		AND COD_EMPRESA = $cod_empresa
		AND COD_AVULSO = 2
		AND DATE(DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
		GROUP BY DATE(DAT_CADASTR)
		ORDER BY cod_empresa";

		$queryVend = mysqli_query(connTemp($cod_empresa, ''), $sqlVend);

		$dias = 0;
		while ($resu = mysqli_fetch_assoc($queryVend)) {

			$cod_univend = $resu['cod_univend'];

			$dias++;
		}

		if ($dias >= 5) {

			//fnEscreve("entrou");

			$cod_emp = fnEncode($cod_empresa);
			$cod_univ = fnEncode($cod_univend);


			$mensagem = "	<table class='nl-container' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;  background-image: none; background-position: top left; background-repeat: no-repeat;'>
			<tbody>
			<tr>
			<td>

			<table class='row row-6' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;'>
			<tbody>

			<tr> 
			<td class='row-content' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 600.00px;' width='600.00'>

			<table class='row-content' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 600.00px;' width='600.00'>
			<tbody>
			<tr>
			<td class='column column-1' width='33.33333%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
			<table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
			<tbody>
			<tr>
			<td style='width:100%; padding-right: 0; padding-left:0;'>
			<div class='alignment' align='center'>
			<img src='https://img.bunker.mk/media/mkt/headMarka4.jpg' width='100%' alt='logo' title='logo'/>
			</div>
			</td>
			</tr>
			</tbody>
			</table>
			</td>
			</tr>
			</tbody>
			</table>                                	
			</td>
			</tr>

			</tbody>
			</table>

			<table class='row row-7' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
			<tbody>
			<tr>
			<td>
			<table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 600.00px;' width='600.00'>
			<tbody>
			<tr>
			<td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 0px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
			<table class='text_block block-1' width='100%' border='0' cellpadding='15' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
			<tbody><tr>
			<td class='pad'>
			<div style='font-family: &#39;Trebuchet MS&#39;, Tahoma, sans-serif'>
			<div class='' style='font-size: 12px; font-family: &#39;Oxygen&#39;, &#39;Trebuchet MS&#39;, &#39;Lucida Grande&#39;, &#39;Lucida Sans Unicode&#39;, &#39;Lucida Sans&#39;, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #595959; line-height: 1.2;'>
			<p style='margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px; letter-spacing: normal; line-height: 1.2;'><span style='font-size:22px;'>Tivemos movimentação de vendas na seguinte empresa e unidade!</span></p>
			</div>
			</div>
			</td>
			</tr>
			</tbody></table>
			</td>
			</tr>
			</tbody>
			</table>
			</td>
			</tr>
			</tbody>
			</table>

			<br>

			<table class='row row-7' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
			<tbody>
			<tr>
			<td>
			<table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 600.00px;' width='600.00'>
			<tbody>
			<tr>
			<td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
			<table class='text_block block-1' width='100%' border='0' cellpadding='15' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
			<tbody><tr>
			<td class='pad'>
			<div style='font-family: &#39;Trebuchet MS&#39;, Tahoma, sans-serif'>
			<div class='' style='font-size: 12px; font-family: &#39;Oxygen&#39;, &#39;Trebuchet MS&#39;, &#39;Lucida Grande&#39;, &#39;Lucida Sans Unicode&#39;, &#39;Lucida Sans&#39;, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #595959; line-height: 1.2;'>
			<p style='margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px; letter-spacing: normal;'>
			<span style='font-size:18px;'>
			Empresa: $cod_empresa - $nom_fantasi <br>
			Unidade: $cod_univend - $nom_unid
			</span>
			</p>
			</div>
			</div>
			</td>
			</tr>
			</tbody></table>
			</td>
			</tr>
			</tbody>
			</table>
			</td>
			</tr>
			</tbody>
			</table>

			<table class='row row-7' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
			<tbody>
			<tr>
			<td>
			<table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 600.00px;' width='600.00'>
			<tbody>
			<tr>
			<td class='column column-1' width='100%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
			<table class='text_block block-1' width='100%' border='0' cellpadding='15' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
			<tbody><tr>
			<td class='column column-1' width='20%'></td>
			<td class='column column-2' width='60%'>
			<div style='font-family: &#39;Trebuchet MS&#39;, Tahoma, sans-serif'>
			<div class='' style='width: 100%; text-align: center; background-color: #1A4A90; border-radius: 5px; padding: 10px; font-size: 12px; font-family: &#39;Oxygen&#39;, &#39;Trebuchet MS&#39;, &#39;Lucida Grande&#39;, &#39;Lucida Sans Unicode&#39;, &#39;Lucida Sans&#39;, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #595959; line-height: 1.2;'>
			<a style='font-size: 15px; font-weight: 500; color: #fff; font-family: sans-serif; text-decoration: none; color: #FFD54F; ' href='https://adm.bunker.mk/action.do?mod=CB3XI3i3644¢&id=$cod_emp&idU=$cod_univ' target='_blank'><strong>CLIQUE AQUI PARA VER MAIS</strong></a>
			</div>
			</div>
			</td>
			<td class='column column-3' width='20%'></td>
			</tr>
			</tbody></table>
			</td>
			</tr>
			</tbody>
			</table>
			</td>
			</tr>
			</tbody>
			</table>


			<br>

			<table class='row row-6' align='center' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;'>
			<tbody>

			<tr> 
			<td class='row-content' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 600.00px;' width='600.00'>

			<table class='row-content' align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 600.00px;' width='600.00'>
			<tbody>
			<tr>
			<td class='column column-1' width='33.33333%' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
			<table class='image_block block-1' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
			<tbody>
			<tr>
			<td style='width:100%; padding-right: 0; padding-left:0;'>
			<div class='alignment' align='center'>
			<img src='https://img.bunker.mk/media/mkt/headMarka4.jpg' width='100%' alt='logo' title='logo'/>
			</div>
			</td>
			</tr>
			</tbody>
			</table>
			</td>
			</tr>
			</tbody>
			</table>                                	
			</td>
			</tr>
			</tbody>
			</table>

			</td>
			</tr>
			</tbody>
			</table>
			";

			$retorno = fnsacmail(
				$to,
				'Marka Fidelização',
				$mensagem,
				$subject,
				'Marka Fidelização',
				$connAdm->connAdm(),
				connTemp(3, ""),
				3
			);

			/*echo "<pre>";
			print_r($retorno);
			echo "</pre>";*/


			//mail($to, $subject, $mensagem, $headers);
		}
	}
}
