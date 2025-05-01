<div class="collapse in" id="collapseFilter">
	<table class="table">
		<tbody id="relatorioConteudo">
			<?php
			$sql = "SELECT * FROM SAC_ANEXO WHERE COD_REFDOWN = $conta AND COD_EMPRESA = $cod_empresa ORDER BY DAT_CADASTR DESC";
			//fnEscreve($sql);

			$arrayquery = mysqli_query($connAdmSAC->connAdm(), $sql);
			while ($qrAnexo = mysqli_fetch_assoc($arrayquery)) {

				$urlAnexo = "./media/clientes/3/helpdesk/" . $cod_empresa . "/" . $qrAnexo['NOM_ARQUIVO'];
				$diretorioDestino = "./media/clientes/3/helpdesk/" . $cod_empresa . "/";

				$file_parts = explode('.', @$qrAnexo['NOM_ARQUIVO']); // Armazena o resultado de explode em uma variável
				$file_ext = strtolower(end($file_parts)); // Passa a variável para a função end()


				if ($qrAnexo['DAT_CADASTR'] < "2019-08-29 11:00:00") {

					if (!file_exists($diretorioDestino)) {
						mkdir($diretorioDestino, 0777);
					}

					if (!file_exists($urlAnexo)) {
						$arquivo = "./media/clientes/0/helpdesk/" . $qrAnexo['NOM_ARQUIVO'];
						rename($arquivo, $urlAnexo);
					}
				} else {

					if (!file_exists($urlAnexo)) {

						if (!file_exists($diretorioDestino)) {
							mkdir($diretorioDestino, 0777);
						}

						$arquivo = "./media/clientes/3/helpdesk/0/" . $qrAnexo['NOM_ARQUIVO'];
						@$sucesso = rename(@$arquivo, @$urlAnexo);

						// if($sucesso){
						//     fnConsole("moveu");
						// }else{
						//     fnConsole("não moveu");
						// }

					}
				}

			?>

				<tr>
					<td>

						<?php if ($file_ext == "jpeg" || $file_ext == "jpg" || $file_ext == "png") { ?>
							<a href="<?= $urlAnexo ?>" class="download" target="files" onclick="openNav()"><span class="fal fa-file-search"></span>
							</a>
						<?php } else { ?>
							<a href="https://docs.google.com/a/192.99.240.249/viewer?url=https://adm.bunker.mk/media/clientes/3/helpdesk/<?php echo $cod_empresa . '/' . $qrAnexo['NOM_ARQUIVO']; ?>&pid=explorer&efh=false&a=v&chrome=false&embedded=true" class="download" target="files" onclick="openNav()"><span class="fal fa-file-search"></span></a>
						<?php } ?>
					</td>
					<td><a href="<?= $urlAnexo ?>" download><span class="fa fa-download"></span></a></td>
					<td><?php echo $qrAnexo['NOM_ARQUIVO']; ?></td>
					<td><small><?php echo date("d/m/Y", strtotime($qrAnexo['DAT_CADASTR'])) ?></small>&nbsp;<small><?php echo date("H:i:s", strtotime($qrAnexo['DAT_CADASTR'])) ?></small></td>
				</tr>

			<?php
			}
			?>
		</tbody>
	</table>
</div>

<!--<div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
				</div>		
			</div>
		</div>
	</div>-->

<!-- <script src="js/plugins/hummingbird-treeview.js"></script> -->

<style type="text/css">
	/* The Overlay (background) */
	.overlay {
		/* Height & width depends on how you want to reveal the overlay (see JS below) */
		height: 100%;
		width: 100%;
		position: fixed;
		/* Stay in place */
		left: 0;
		top: 0;
		background-color: rgba(0, 0, 0, 0.9);
		/* Black w/opacity */
		overflow-x: hidden;
		/* Disable horizontal scroll */
		transition: 0.5s;
		/* 0.5 second transition effect to slide in or slide down the overlay (height or width, depending on reveal) */
		display: none;
		z-index: 9999;
	}

	/* Position the content inside the overlay */
	.overlay-content {
		position: relative;
		top: 0;
		/* 5% from the top */
		width: 80%;
		/* 100% width */
		text-align: center;
		/* Centered text/links */
		margin-left: auto;
		margin-right: auto;
	}

	/* Position the close button (top right corner) */
	.overlay .closebtn {
		position: absolute;
		top: 60px;
		right: 45px;
		font-size: 60px;
	}

	.modal-dialog2 {
		width: 100vw;
		height: 100vh;

	}

	.modal-content2 {
		width: 100vw;
		height: 100vh;
		border-radius: 0;
	}
</style>

<!-- The overlay -->
<div id="myNav" class="overlay">

	<!-- Button to close the overlay navigation -->
	<div class="push50"></div>
	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

	<!-- Overlay content -->
	<div class="overlay-content">
		<iframe name="files" id="files" src='' width='100%' height='100%' frameborder='0'></iframe>
	</div>

</div>

<script>
	function openNav() {
		$('#myNav').show();
		try {
			$('.modal-dialog').attr("class", 'modal-dialog2');
			$('.modal-content').attr("class", 'modal-content2');
		} catch (err) {}
	}

	/* Close */
	function closeNav() {
		$('#myNav').hide();
		try {
			$('.modal-dialog2').attr("class", 'modal-dialog');
			$('.modal-content2').attr("class", 'modal-content');
		} catch (err) {}
		$('#files').attr('src', '');
	}
</script>