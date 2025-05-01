	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/plugins/jquery.webui-popover.min.js" type="text/javascript"></script>
	<script src="js/chosen.jquery.min.js" type="text/javascript"></script>
	<script src="js/plugins/validator.min.js" type="text/javascript"></script>
	<script src="js/plugins/mmenu/jquery.mmenu.min.js" type="text/javascript"></script>
	<script src="js/main.js" type="text/javascript"></script>
	<script src="js/jquery.mask.min.js" type="text/javascript"></script>
	<script src="js/plugins/ie10-viewport-bug-workaround.js" type="text/javascript"></script>
	<!-- <script src="js/tablesorter/jquery.tablesorter.js" type="text/javascript"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.32.0/js/jquery.tablesorter.min.js" type="text/javascript"></script>
	<!-- <script src="js/tablesorter/jquery.tablesorter.widgets.js" type="text/javascript"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.32.0/js/jquery.tablesorter.widgets.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.32.0/js/parsers/parser-globalize.min.js" type="text/javascript"></script>
	<script src="js/plugins/jquery.metadata.js" type="text/javascript"></script>
	<script src="js/plugins/jquery.uitablefilter.js" type="text/javascript"></script>
	<script src="js/jquery-confirm.min.js"></script>
	<script src="js/jquery.twbsPagination.min.js"></script>
	<?php
	if (isset($_GET['mod'])) {
		$modEditable = fnDecode($_GET['mod']);
		$cod_modEdit = "1900,1302,1333,1371,1372,
						1458,1502,1510,1547,1589,
						1596,1666,1674,1671,1536,
						1717,1126,1168,1128,1584,
						1282,1914,1017,1185,1252,
						1945,2033,2012,1950";
		$editablePg = preg_match("~\b$modEditable\b~", $cod_modEdit);
		if ($editablePg > 0) {
	?>
			<link href="vendor/bootstrap-editable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
			<script src="vendor/bootstrap-editable/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
	<?php
		}
	}
	?>



	<?php
	/*
	****************** TOUR ******************
	DOCUMENTAÇÃO
	https://driverjs.com/docs/
	*/
	$cod_modulo = fnDecode($_GET['mod']);
	$sql = "select * from TOUR WHERE COD_MODULOS='$cod_modulo' order by NUM_ORDENAC";
	$arrTour = mysqli_query($connAdm->connAdm(), $sql);
	if (mysqli_num_rows($arrTour) > 0) {
		$steps = [];

		while ($linha = mysqli_fetch_assoc($arrTour)) {
			$description = $linha["DES_TOUR"];
			if ($linha["DES_DICA"] <> "") {
				$description .= "<hr style='margin: 10px 0 10px 0;'><i class='far fa-lightbulb-on'></i> " . $linha["DES_DICA"];
			}

			$description = str_replace("…", "...", $description);

			$steps[] = [
				"element" => $linha["DES_OBJETO"],
				"popover" => [
					"title" => $linha["NOM_TOUR"],
					"description" => $description,
					"side" => "left",
					"align" => "start"
				]
			];
		}
	?>
		<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css" />

		<script>
			function show_tour(item) {
				item = JSON.parse(atob(item));

				const driverFn = window.driver.js.driver;
				const driverObjFn = driverFn({
					animate: true,
					showProgress: false,
					showButtons: ['close'],
					doneBtnText: 'fechar',
					steps: [item]
				})

				driverObjFn.drive();
			}

			var gravou_clipe = false;
			var steps = <?= json_encode($steps) ?>;
			steps = steps.map(item => {
				if ($(`${item.element}`).parent().hasClass("form-group")) {

					//Verifica se está dentro de um "form-group", se tiver, pega todo o "form-group"
					let el = 'tour_' + Math.floor(Math.random() * 1000000000000);
					$(`${item.element}`).parent().addClass(el);
					item.element = `.${el}`;

					try {
						//Adiciona na label o botão para abrir o tour específico dele
						$(`.${el} label`).first().prepend(`<i class='fal fa-info-circle' style='cursor:pointer' onclick="show_tour('${btoa(JSON.stringify(item))}')"></i> `)
					} catch (e) {}

				} else if ($(`${item.element}`).parent().hasClass("input-group")) {

					//Verifica se está dentro de um "input-group", se tiver, pega todo o "input-group"
					let el = 'tour_' + Math.floor(Math.random() * 1000000000000);
					$(`${item.element}`).parent().parent().addClass(el);
					item.element = `.${el}`;

					try {
						//Adiciona na label o botão para abrir o tour específico dele
						$(`.${el} label`).prepend(`<i class='fal fa-info-circle' style='cursor:pointer' onclick="show_tour('${btoa(JSON.stringify(item))}')"></i> `)
					} catch (e) {}

				} else if ($(item.element).prop("tagName") == "LABEL") {

					let el_ori = item.element;

					//Verifica se está dentro de um "form-group", se tiver, pega todo o "form-group"
					let el = 'tour_' + Math.floor(Math.random() * 1000000000000);
					$(`${item.element}`).parent().addClass(el);
					item.element = `.${el}`;

					console.log(item.element, $(item.element).prop("tagName"), `${el}`);
					try {
						//Adiciona na label o botão para abrir o tour específico dele
						$(`${el_ori}`).prepend(`<i class='fal fa-info-circle' style='cursor:pointer' onclick="show_tour('${btoa(JSON.stringify(item))}')"></i> `)
					} catch (e) {}
				} else {
					//console.log(item.element, $(item.element).prop("tagName"));
				}
				return item;
			}).filter(item => {
				//O elemento tem que existir e tem que estar visível na tela para fazer parte do tour
				return $(item.element).length > 0 && $(item.element).is(":visible");
			});

			function show_tour_all() {

				console.log(steps);
				//Verifica se clipe existe
				if ($("#clippy").length > 0) {
					//Clipe existe. Verifica se a mensagem está aberta
					if ($("#msgInfo").length <= 0) {
						//Mensagem não está aberta. Aciona o clipe.
						$("#clippy").click();

					}

					if (!gravou_clipe) {
						gravou_clipe = true;
						steps.unshift({
							"element": "#msgInfo",
							"popover": {
								"title": "Visão Geral",
								"description": "",
								"side": "left",
								"align": "start"
							}
						});
					}
				}

				const driver = window.driver.js.driver;
				const driverObj = driver({
					animate: true,
					showProgress: true,
					showButtons: ['next', 'previous', 'close'],
					nextBtnText: '<i class="fas fa-chevron-right" onclick="$(this).parent().click();"></i>',
					prevBtnText: '<i class="fas fa-chevron-left" onclick="$(this).parent().click();"></i>',
					doneBtnText: '<i class="fas fa-times" onclick="$(this).parent().click();"></i>',
					progressText: '{{current}} de {{total}}',
					steps
				});
				driverObj.drive();

			}

			//Adiciona o botão no portlet
			if ($(".portlet .portlet-title").find(".actions").length <= 0) {
				$(".portlet .portlet-title").append("<div class='actions'></div>")
			}
			$(".portlet .portlet-title .actions").append("<a href='javascript:' onclick='show_tour_all();' class='shortCut' data-toggle='tooltip' data-placement='top' data-original-title='Tour' id='shortTOUR'>" +
				"<i class='fal fa-street-view' aria-hidden='true'></i>" +
				"</a>")
		</script>
		<style>
			a.shortCut {
				color: #2c3e50;
				margin: 0 3px 0 3px;
			}

			.driver-popover-title,
			.driver-popover-description {
				font-family: "Lato", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
			}

			.driver-popover .fas,
			.driver-popover .fal,
			.driver-popover .far {
				font-family: "Font Awesome 5 Pro" !important;
			}
		</style>
	<?php
	}
	?>