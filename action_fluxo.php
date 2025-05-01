<?php
$fluxo = "";
$mod = fnDecode($_GET["mod"]);
$cod_empresa = fnDecode(@$_GET["id"]);

if (isset($_GET["fluxo"]) && $_GET["fluxo"] !== "") {
	$fluxo = fnDecode($_GET["fluxo"]);
	$_SESSION[$cod_empresa]["fluxo"][$mod] = $fluxo;
} elseif (isset($_SESSION[$cod_empresa]["fluxo"][$mod]) && $_SESSION[$cod_empresa]["fluxo"][$mod] !== "") {
	$fluxo = $_SESSION[$cod_empresa]["fluxo"][$mod];
}
//print_r($_SESSION);

function monta_url($params = [])
{
	$get = $_GET;
	$params = array_merge($get, $params);
	return http_build_query($params);
}

if ($fluxo <> "") {

	$sql = "SELECT
				FD.COD_FLUXO,FO.COD_FLUXO_OPER,FO.COD_FLUXO_OPER,FD.COD_EMPRESA,FD.DES_FLUXO,FD.JSN_FLUXO_EXPORT,FD.DES_ITENS,FD.DES_FLUXO_MODULOS,
				FO.COD_MODULOS,FO.COD_NODE,FO.PARAMS,FO.DES_PASSO_ANTERIOR
			FROM fluxo_operacional FO
			LEFT JOIN fluxo_dados FD ON FD.COD_FLUXO=FO.COD_FLUXO
			WHERE FO.COD_FLUXO_OPER=0$fluxo
			AND FO.COD_EMPRESA='$cod_empresa'";

	$rs = mysqli_query(conntemp($cod_empresa, ""), $sql);
	$ln = mysqli_fetch_assoc($rs);
	if (isset($ln)) {
		$json = json_decode($ln["DES_FLUXO_MODULOS"], true);
		$passos = json_decode($ln["DES_ITENS"], true);
		$passo = $ln["COD_NODE"];
		$passo_atual = $passos[$passo];

		if ($ln["COD_MODULOS"] <> $mod) {
			$params = array_merge(
				['mod' => fnEncode($ln["COD_MODULOS"]), 'id' => fnEncode($ln["COD_EMPRESA"]), 'fluxo' => fnEncode($ln["COD_FLUXO_OPER"])],
				json_decode($ln["PARAMS"], true)
			);

			$url = "/action.do?" . http_build_query($params);
?>
			<script>
				$("body").html("<div style='padding:20px;'>Redirecionando...</div>");
				window.location.href = "<?= $url ?>";
			</script>
		<?php
			exit;
		}

		$ant = json_decode($ln["DES_PASSO_ANTERIOR"], true);
		$passo_anterior = end($ant);
		?>

		<script>
			$(document).ready(function() {

				let html = "";
				html = "";

				<?php
				$p = $passo_anterior;
				if ($p <> "") {
					$ppasso = $json[$p];

					if ($ppasso["type"] == "modulo" || $ppasso["type"] == "home") {
						$url = "/action.do?" . monta_url(["mod" => fnEncode(1985), "id" => $_GET["id"], "fluxo" => fnEncode($fluxo), "passo" => $p, "passo_acao" => "prev"]);
				?>
						html += `<a class='btn btn-primary text-left' style='margin-right:2px;float:left;text-align: left;' href='javascript:' onclick="passo_fluxo('ant','<?= $url ?>')">`;
						html += "Passo Anterior";
						html += "<br><small style='font-size:10px;'><?= $ppasso["desc"] ?>&nbsp;</small>";
						html += "</a>";
					<?php
					}
				}
				foreach ($json[$passo]['next'] as $p) {
					$ppasso = $json[$p];

					$url = "/action.do?" . monta_url(["mod" => fnEncode(1985), "id" => $_GET["id"], "fluxo" => fnEncode($fluxo), "passo" => $p, "passo_acao" => "next", "passo_atual" => $passo]);
					?>
					html += `<a class='btn btn-primary text-left' style='margin-right:2px;float:left;text-align: left;' href='javascript:' onclick="passo_fluxo('prox','<?= $url ?>')">`;
					html += "Próximo Passo";
					html += "<br><small style='font-size:10px;'><?= $ppasso["desc"] ?>&nbsp;</small>";
					html += "</a>";
				<?php
				} ?>

				html += "<div class='push30'></div>";


				if ($(".portlet-body").length > 0) {
					$(".portlet-body:last").append(html);
				} else if ($(".form-group .getBtn").parent().length > 0) {
					$(".form-group .getBtn:last").parent().prepend(html)
				} else if ($(".containerfluid:last").length > 0) {
					$(".containerfluid:last").append(html);
				} else {
					$("body").append(html);
				}
			});

			function passo_fluxo(acao, url) {
				if (acao == "prox") {
					ret = true;
					<?php
					if (isset($passo_atual["regras"])) {
						if (is_array($passo_atual["regras"])) {
							foreach ($passo_atual["regras"] as $item) {
								foreach ($item as $key => $valor) {
									$item[$key] = str_replace("'", "&apos;", $item[$key]);
									$item[$key] = str_replace("\"", "&quot;", $item[$key]);
									$item[$key] = str_replace(">", "&gt;", $item[$key]);
									$item[$key] = str_replace("<", "&lt;", $item[$key]);
								}
								echo "ret = passo_critica('" . $item["elemento"] . "', '" . $item["operador"] . "', '" . $item["valor"] . "', '" . $item["msg"] . "');";
								echo "if (ret == false){return false;}";
							}
						}
					}
					?>
					console.log("URL", acao, url)
				}

				window.location.href = url;
			}

			function passo_critica(elemento, operador, valor, msg) {
				let el = $(elemento);
				let val = "";

				if (el.length <= 0) {
					val = "";
				} else {
					val = el.val();
					if (val == "") {
						val = el.html();
					}
				}

				if (operador == "=") {
					operador = "==";
				}

				console.log(val, operador, valor, ' | ', elemento)
				let critica = eval(`'${val}' ${operador} '${valor}'`);
				if (critica) {
					passo_msg_erro(msg);
					return false;
				}

				return true;
			}

			function passo_msg_erro(msg) {
				let html = `<div class="alert alert-danger alert-dismissible top30 bottom30" role="alert" id="msgRetorno">` +
					`<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>` +
					msg +
					`</div>`;

				if ($(".portlet-body").length > 0) {
					$(".portlet-body").prepend(html)
				} else {
					alert(msg);
				}
			}
		</script>

<?php
	}
}
