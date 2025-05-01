<?php
include_once 'header.php';
$tituloPagina = "Amigos";
include_once "navegacao.php";

// if(!isset($_SESSION["usuario"])){

//    header('Location:app.do?key='.fnEncode($_SESSION["EMPRESA_COD"]));

// }

list($r_cor_backpag, $g_cor_backpag, $b_cor_backpag) = sscanf($cor_backpag, "#%02x%02x%02x");

if ($r_cor_backpag > 50) {
	$r = ($r_cor_backpag - 50);
} else {
	$r = ($r_cor_backpag + 50);
	if ($r_cor_backpag < 30) {
		$r = $r_cor_backpag;
	}
}
if ($g_cor_backpag > 50) {
	$g = ($g_cor_backpag - 50);
} else {
	$g = ($g_cor_backpag + 50);
	if ($g_cor_backpag < 30) {
		$g = $g_cor_backpag;
	}
}
if ($b_cor_backpag > 50) {
	$b = ($b_cor_backpag - 50);
} else {
	$b = ($b_cor_backpag + 50);
	if ($b_cor_backpag < 30) {
		$b = $b_cor_backpag;
	}
}

if ($r_cor_backpag <= 50 && $g_cor_backpag <= 50 && $b_cor_backpag <= 50) {
	$r = ($r_cor_backpag + 40);
	$g = ($g_cor_backpag + 40);
	$b = ($b_cor_backpag + 40);
}


$dat_ini = date("Y-m-d", strtotime("-30 days"));


$sqlCli = "SELECT COD_CLIENTE, NOM_CLIENTE
			FROM CLIENTES 
			WHERE NUM_CGCECPF = $usuario 
			AND COD_EMPRESA = $cod_empresa";

$arrayCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);

$qrCli = mysqli_fetch_assoc($arrayCli);

$cod_cliente = $qrCli[COD_CLIENTE];
$nom_cliente = $qrCli[NOM_CLIENTE];
$nom_cliente = explode(" ", $nom_cliente);
$nom_cliente = ucfirst(strtolower($nom_cliente[0]));






?>

<link href="libs/jquery-confirm.min.css" rel="stylesheet" />
<script src="libs/jquery-confirm.min.js"></script>

<style>
	body {
		background-color: <?= $cor_backpag ?>;
	}

	.shadow {
		-webkit-box-shadow: 0px 0px 18px -2px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		-moz-box-shadow: 0px 0px 18px -2px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		box-shadow: 0px 0px 18px -2px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		width: 100%;
		border-radius: 5px;
	}

	.shadow2 {
		-webkit-box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		-moz-box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		width: 100%;
		border-radius: 5px;
	}

	.reduzMargem {
		margin-bottom: 10px;
	}

	.dashed-round {
		border-radius: 5px;
		border: 2px dashed <?= $cor_textos ?>;
	}

	::selection {
		color: #fff;
		background: #7d2ae8;
	}

	.view-modal,
	.popup {
		position: absolute;
		left: 50%;
		z-index: 1;
	}

	button {
		outline: none;
		cursor: pointer;
		font-weight: 500;
		border-radius: 4px;
		border: 2px solid transparent;
		transition: background 0.1s linear, border-color 0.1s linear, color 0.1s linear;
	}

	.view-modal {
		top: 50%;
		color: #7d2ae8;
		font-size: 18px;
		padding: 10px 25px;
		background: #fff;
		transform: translate(-50%, -50%);
	}

	.popup {
		background: #fff;
		padding: 25px;
		border-radius: 15px;
		top: -150%;
		max-width: 380px;
		width: 100%;
		opacity: 0;
		pointer-events: none;
		box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
		transform: translate(-50%, -50%) scale(1.2);
		transition: top 0s 0.2s ease-in-out,
			opacity 0.2s 0s ease-in-out,
			transform 0.2s 0s ease-in-out;
	}

	.popup.show {
		top: 50%;
		opacity: 1;
		pointer-events: auto;
		transform: translate(-50%, -50%) scale(1);
		transition: top 0s 0s ease-in-out,
			opacity 0.2s 0s ease-in-out,
			transform 0.2s 0s ease-in-out;
	}

	/* .popup :is(header, .icons, .field) {
		display: flex;
		align-items: center;
		justify-content: space-between;
	} */

	.popup header {
		padding-bottom: 15px;
		border-bottom: 1px solid #ebedf9;
	}

	header span {
		font-size: 21px;
		font-weight: 600;
	}

	header .close,
	.icons a {
		display: flex;
		align-items: center;
		width: 50px;
		height: 50px;
		border-radius: 30px;
		padding: 7px;
		font-size: 14px;
		color: #fff;
		justify-content: center;
		transition: all 0.3s ease-in-out;
	}

	header .close {
		color: #878787;
		font-size: 17px;
		background: #f2f3fb;
		height: 33px;
		width: 33px;
		cursor: pointer;
	}

	header .close:hover {
		background: #ebedf9;
	}

	.popup .content {
		margin: 20px 0;
	}

	.popup .icons {
		margin: 15px 0 20px 0;
	}

	.content p {
		font-size: 16px;
	}

	.content .icons a {
		height: 50px;
		width: 50px;
		font-size: 20px;
		text-decoration: none;
		border: 1px solid transparent;
	}

	.icons a i {
		transition: transform 0.3s ease-in-out;
	}

	.icons a:hover {
		color: #fff;
		border-color: transparent;
	}

	.icons a:hover i {
		transform: scale(1.2);
	}

	.content .field {
		margin: 12px 0 -5px 0;
		height: 45px;
		border-radius: 4px;
		padding: 0 5px;
		border: 1px solid #e1e1e1;
	}

	.field.active {
		border-color: #7d2ae8;
	}

	.field i {
		width: 50px;
		font-size: 18px;
		text-align: center;
	}

	.field.active i {
		color: #7d2ae8;
	}

	.field input {
		width: 100%;
		height: 100%;
		border: none;
		outline: none;
		font-size: 15px;
	}

	.field button {
		color: #fff;
		padding: 5px 18px;
		background: #7d2ae8;
	}

	.field button:hover {
		background: #8d39fa;
	}
</style>

<div class="container">

	<div class="push30"></div>
	<div class="push30"></div>

	<div class="row">

		<div class="col-xs-12 zeraPadLateral corIcones" style="color: <?= $cor_textos ?>">
			<div class="col-xs-12 text-center">
				<h4 style="color: <?= $cor_textos ?>">Esse é o seu código de indicação:</h4>
			</div>
		</div>

	</div> <!-- /row -->

	<div class="row">

		<div class="col-xs-10 col-xs-offset-1 corIcones" style="color: <?= $cor_textos ?>">
			<div class="dashed-round">
				<div class="col-xs-12 text-center">
					<h1 style="color: <?= $cor_textos ?>;"><?= $cod_cliente ?></h1>
				</div>
				<div class="push5"></div>
			</div>
		</div>

	</div> <!-- /row -->

	<div class="push10"></div>

	<div class="row">

		<div class="col-xs-10 col-xs-offset-1 zeraPadLateral corIcones" style="color: <?= $cor_textos ?>">
			<div class="push5"></div>
			<div class="col-xs-12 text-center">
				<!-- AddToAny BEGIN -->
				<div class="text-center">
					<a href="javascript:void(0)" onclick="share()" class="btn btn-info btn-sm" style="background-color:#2C94D5!important; border-color:#2C94D5!important; border-radius: 5px; display: inline-flex; align-items: center;"><i class="fal fa-share-alt" style="color: #fff; font-size: 26px"></i>&nbsp; Compartilhar</a>
					<div class="popup" id="popup">
						<div class="close">
							<i class="fas fa-times" onclick="Open();"></i>
						</div>
						<!-- <h3>Compartilhar</h3> -->
						<!-- <div class="icons"> -->
							<!-- <a class="facebook" href="javascript:void(0)">
								<i class="fab fa-facebook"></i><br>
								<span>Facebook</span>
							</a>
							<a class="twitter" href="javascript:void(0)">
								<i class="fab fa-twitter"></i><br>
								<span>Twitter</span>
							</a> -->
							<!-- <center>
								<a class="whatsapp btn btn-xs" href="https://api.whatsapp.com/send?text=Ei! Quero te convidar para o app Duque, onde tem vantagens incríveis! Use o meu código: <?= $cod_cliente ?>, baixe o app e aproveite!" data-action="share/whatsapp/share" style="background: #25D366">
									<i class="fab fa-whatsapp fa-2x"></i>
								</a>
							</center> -->
							<!-- <a class="email" href="javascript:void(0)">
								<i class="fas fa-envelope"></i><br>
								<span>Email</span>
							</a> -->
						<!-- </div> -->
						<div class="copylink">
							<h4>Copiar código</h4>
							<input type="text" name="link" id="link" readonly value="Ei! Quero te convidar para o app Duque, onde tem vantagens incríveis! Use o meu código: <?= $cod_cliente ?>, baixe o app e aproveite!">
							<a href="javascript:void(0)" class="btn btn-xs btn-default" onclick="copyText()">
								<i class="far fa-copy" title="Copiar para área de transferência"></i>&nbsp;Copiar
							</a>
						</div>
					</div>
				</div>
				<!-- AddToAny END -->
			</div>
			<div class="push5"></div>
		</div>

	</div> <!-- /row -->

	<div class="push50"></div>

	<div class="row">
		<div class="col-xs-12">
			<h4 style="font-weight: 900!important;">MEUS AMIGOS INDICADOS</h4>
		</div>
		<div class="col-md-12">
			<hr style="margin:0; border-color: #3c3c3c; width: 100%; max-width: 100%;">
		</div>
	</div>

	<div class="push20"></div>

	<?php

	$sqlAmigos = "SELECT CI.*, UV.NOM_FANTASI FROM CLIENTES_INDICADOS CI
   					  INNER JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = CI.COD_UNIVEND
   					  WHERE CI.COD_EMPRESA = $cod_empresa
   					  AND CI.COD_INDICAD = $cod_cliente
   					  ORDER BY CI.DAT_CADASTR DESC";

	// echo($sql);

	$arrAmigos = mysqli_query(connTemp($cod_empresa, ''), $sqlAmigos);

	while ($qrAmigos = mysqli_fetch_assoc($arrAmigos)) {

	?>

		<div class="col-xs-12 zeraPadLateral corIcones" style="color: <?= $cor_textos ?>">
			<div class="shadow2">
				<div class="push10"></div>
				<div class="col-xs-8">
					<p><b><?= $qrAmigos['NOM_CLIENTE'] ?></b></p>
					<p><small><?= $qrAmigos['NOM_FANTASI'] ?></small></p>
				</div>
				<div class="col-xs-4 text-right">
					<p><small><?= fnDataShort($qrAmigos['DAT_CADASTR']) ?></small></p>
				</div>
				<div class="push5"></div>
			</div>
		</div>

	<?php
	}

	?>

	<div class="push50"></div>

</div> <!-- /container -->
<textarea name="MSG_AMIGO" id="MSG_AMIGO" style="display: none;">
Oi, tenho uma dica incrível para você: o app da Rede Duque! Juntos, temos ofertas incríveis que você vai adorar.

Basta baixar o app e usar meu código:  <?= $cod_cliente ?>  E aproveitar as vantagens! Te espero para abastecer na Duque.

Google Play:  https://bit.ly/4imeNkT

App Store:  https://bit.ly/3ZyYzh8
</textarea>

<script type="text/javascript">
	var cont = 0;
	let viewBtn = document.querySelector(".view-modal"),
				popup = document.querySelector(".popup"),
				close = popup.querySelector(".close"),
				field = popup.querySelector(".field"),
				input = field.querySelector("input"),
				copy = field.querySelector(".copyLink");

	$('#loadMore').click(function() {

		cont += 10;

		if (cont >= "<?= mysqli_num_rows($arrayQueryCount) ?>") {
			$('#loadMore').addClass('disabled');
			$('#loadMore').text('Não há mais movimentações');
		}

		$.ajax({
			type: "POST",
			url: "ajxRelGanhos.do",
			data: {
				itens: cont,
				casasDec: "<?= $casasDec ?>",
				corTextos: "<?= $cor_textos ?>",
				key: "<?= fnEncode($cod_empresa) ?>",
				TIP_CAMPANHA: "<?= $tip_campanha ?>"
			},
			beforeSend: function() {
				$('#loadMore').text('Carregando...');
			},
			success: function(data) {

				if (cont >= "<?= mysqli_num_rows($arrayQueryCount) ?>") {
					$('#loadMore').addClass('disabled');
					$('#loadMore').text('Não há mais movimentações');
				} else {
					$('#loadMore').text('Carregar Mais');
				}
				$('#relConteudo').append(data);

				// console.log(data);
			},
			error: function() {
				alert('Erro ao carregar...');
			}
		});
	});

	function share() {
		$("#MSG_AMIGO").show();
		if (navigator.share) {
			try {
				navigator.share({
				title: "Compartilhar código",
				text: $("#MSG_AMIGO").val(),
				// url: "https://www.google.com/",
				});
				// console.log("Data was shared successfully");
			} catch (err) {
				console.error("error:", err.message);
			}
		} else {
			Android.shareContent($("#MSG_AMIGO").val()); 
		}
		$("#MSG_AMIGO").hide();		
	}

	close.onclick = ()=>{
		viewBtn.click();
	}



	function copyText() {
		$("#MSG_AMIGO").show();
		/* Copy text into clipboard */
		navigator.clipboard.writeText($("#MSG_AMIGO").val());
		$("#MSG_AMIGO").hide();
		$.alert({
			title: "Sucesso",
			content: "Código de indicador copiado com sucesso.",
			columnClass: 'col-xs-12',
			backgroundDismiss: true,
			buttons: {
				"OK": {
					btnClass: 'btn-blue shadow',
					action: function() {}
				}
			}
		});
	}
</script>

<?php include 'footer.php' ?>