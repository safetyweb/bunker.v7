<?php

echo fnDebug('true');

?>

<script src="js/gridstack/gridstack-all.js"></script>
<link href="js/gridstack/gridstack.min.css" rel="stylesheet" />

<link rel="stylesheet" href="js/plugins/menu-dropdown/menu.min.css" />
<script type="text/javascript" src="js/plugins/menu-dropdown/menu.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>
<script src="js/pie-chart.js"></script>
<script src="js/gauge.coffee.js" type="text/javascript"></script>

<style type="text/css">
	.grid-stack {
		background: #E8EAED;
	}

	.grid-stack-item-content {
		background: #FFF;
		border-radius: 15px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-weight: bold;
	}

	.grid-stack-item-removing {
		opacity: 0.8;
		filter: blur(5px);
	}

	.sidepanel-item {
		background: #ced6ed;
		border-radius: 15px;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 30px 12px;
		font-size: 12px;
		margin-bottom: 15px;
	}

	.sidepanel-item#trash {
		background-color: #FF9999;
	}

	.sidepanel-item#drag {
		cursor: all-scroll;
	}


	/*Menu DropDown*/
	.menu_card {
		position: absolute;
		right: 30px;
		top: 13px;
	}

	.menu {
		top: 0 !important;
		left: -112px !important;
		width: 100px !important;
		z-index: 9999999;
		font-size: 13px !important;
	}

	.menu li a {
		color: #3c3c3c !important;
	}

	.menu-down-right,
	.menu-down-left,
	.menu.menu--right {
		transform-origin: top left !important;
	}

	.menu_card .fal {
		font-size: 20px !important;
	}
</style>


<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
				</div>
				<?php include "atalhosPortlet.php"; ?>
			</div>
			<div class="portlet-body">

				<div class="login-form">

					<div class="row">
						<div class="sidepanel col-md-2 d-none d-md-block">
							<div id="trash" class="sidepanel-item">
								<div>Arraste aqui para remover!</div>
							</div>
							<div id="drag" class="grid-stack-item sidepanel-item">
								<div>Me arraste para o dashboard!</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-10">
							<div class="grid-stack"></div>
						</div>
					</div>

					<div class="grid-stack"></div>

					<div class="push30"></div>

					<textarea id="exportGrid" class="form-control" style="height: 208px;width: 100%;"></textarea>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>



<script type="text/javascript">
	GridStack.renderCB = function(el, w) {
		el.innerHTML = w.content;
	};

	let grid = GridStack.init({
		cellHeight: 70,
		acceptWidgets: true,
		removable: '#trash',
		children: [{
				x: 0,
				y: 0,
				w: 4,
				h: 2,
				content: '001'
			},
			{
				x: 4,
				y: 0,
				w: 4,
				h: 4,
				content: '<canvas id="mybarChart"></canvas>'
			},
			{
				x: 8,
				y: 0,
				w: 2,
				h: 2,
				minW: 2,
				noResize: true,
				content: '<p class="card-text text-center" style="margin-bottom: 0">Drag me!<p class="card-text text-center"style="margin-bottom: 0"><ion-icon name="hand"></ion-icon><p class="card-text text-center" style="margin-bottom: 0">...but don\'t resize me!'
			},
			{
				x: 10,
				y: 0,
				w: 2,
				h: 2,
				content: '4'
			},
			{
				x: 0,
				y: 2,
				w: 2,
				h: 2,
				content: '5'
			},
			{
				x: 2,
				y: 2,
				w: 2,
				h: 4,
				content: '6'
			},
			{
				x: 8,
				y: 2,
				w: 4,
				h: 2,
				content: '7'
			},
			{
				x: 0,
				y: 4,
				w: 2,
				h: 2,
				content: '8'
			},
			{
				x: 4,
				y: 4,
				w: 4,
				h: 2,
				content: '9'
			},
			{
				x: 8,
				y: 4,
				w: 2,
				h: 2,
				content: '10'
			},
			{
				x: 10,
				y: 4,
				w: 2,
				h: 2,
				content: '11'
			},
		]
	});

	GridStack.setupDragIn('.sidepanel>.grid-stack-item', undefined, [{
		h: 2,
		content: 'novo item'
	}]);

	grid.on('added removed change', function(e, items) {
		let str = '';
		items.forEach(function(item) {
			str += ' (x,y)=' + item.x + ',' + item.y;
		});
		console.log(e.type + ' ' + items.length + ' items:' + str, items);

		gera_menus();
		grid_refresh();
		grid_save();
	});

	function gera_menus() {
		document.querySelectorAll('.grid-stack').forEach(function(stack) {
			stack.querySelectorAll('.grid-stack-item').forEach(function(item) {
				if (!item.querySelector('.menu_card')) {
					let id = "el_" + Math.random().toString(36).substr(2, 9);
					var menuDiv = document.createElement('div');
					menuDiv.className = 'menu_card';
					menuDiv.innerHTML = `
								<ul class="menu menu-down-left" id="${id}" data-menu data-menu-toggle="#menu-toggle_${id}">
									<li class="text-info">
										<a href="javascript:" onclick="remove_item(this)">Excluir</a>
									</li>
									<li class="text-info">
										<a href="javascript:">Opção B</a>
									</li>
									<hr>
									<li class="text-info">
										<a href="javascript:">Opção C</a>
									</li>
								</ul>
								<div class="row set1 dropleft">
									<a href="javascript:void(0)" class="col-xs-2" id="menu-toggle_${id}">
										<span class="fal fa-ellipsis-v fa-2x"></span>
									</a>
								</div>`;
					item.appendChild(menuDiv);

					$(`#${id}`).menu();
				}
			});
		});
	}

	function remove_item(item) {
		grid.removeWidget(item.parentNode.parentNode.parentNode.parentNode);
	}

	function grid_refresh() {

		var ctx = document.getElementById("mybarChart");
		if (ctx) {
			var mybarChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: ["18 a 20", "21 a 30", "31 a 40", "41 a 50", "51 a 60"],
					datasets: [{
						label: 'Média de idade',
						backgroundColor: "#85C1E9",
						data: [1, 2, 3, 4, 5]
					}]
				},

				options: {
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: true
							}
						}]
					},
					animation: {
						onComplete: function(animation) {

						}
					}
				}
			});
		}
	}

	function grid_save() {
		const gridJSON = grid.save();
		console.log(gridJSON);
		$("#exportGrid").val(JSON.stringify(gridJSON))
	}

	gera_menus();
	grid_refresh();
	grid_save();
</script>