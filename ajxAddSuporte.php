<?php
include './_system/_functionsMain.php';

$cod_empresa = $_GET['ajxEmp'];

if (!isset($cod_empresa)) {
	$cod_empresa = 0;
}

?>

<div>

	<div id="relatorioUsuario">
		<select data-placeholder="Selecione um usuário" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect" style="width:100%;" required>
			<option value="">&nbsp;</option>
			<?php if ($cod_empresa != 0) { ?>
				<optgroup label="Usuários">
					<?php

					$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios 
										where usuarios.COD_EMPRESA = $cod_empresa
										and usuarios.DAT_EXCLUSA is null
										AND COD_TPUSUARIO IN(9,6,1,3) 
										AND LOG_ESTATUS = 'S' order by  usuarios.NOM_USUARIO ";
					$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

					while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
						echo "
												  <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
												";
					}
					?>
				</optgroup>
			<?php } ?>

			<optgroup label="Usuários Marka">
				<?php

				$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios 
										where (usuarios.COD_EMPRESA = 2 OR usuarios.COD_EMPRESA = 3)
										and usuarios.DAT_EXCLUSA is null
										AND COD_TPUSUARIO IN(9,6,1,3) 
										AND LOG_ESTATUS = 'S' order by  usuarios.NOM_USUARIO ";
				$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

				while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
					echo "
											  <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
											";
				}
				?>
			</optgroup>

		</select>
	</div>



	<div id="relatorioUnidades">
		<?php if ($cod_empresa != 0) { ?>
			<select data-placeholder="Selecione uma unidade para acesso" name="COD_UNIVEND[]" id="COD_UNIVEND" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
				<?php
				$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND DAT_EXCLUSA IS NULL ORDER BY NOM_FANTASI ";
				$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
				while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {
					echo "
					<option value='" . $qrListaUnive['COD_UNIVEND'] . "'>" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
				";
				}
				?>
			</select>
		<?php } ?>
	</div>

	<div id="relatorioUsuariosEnv">
		<?php if ($cod_empresa != 0) { ?>
			<select data-placeholder="Selecione os usuários" name="COD_USUARIOS_ENV[]" id="COD_USUARIOS_ENV" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
				<?php

				$sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
								where (COD_EMPRESA = $cod_empresa OR COD_EMPRESA = 2 OR COD_EMPRESA = 3) AND usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";
				$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

				while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
					echo "
										  <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
										";
				}
				?>
			</select>
		<?php } ?>
	</div>

	<div id="scripts">
		<script>
			$(".chosen-select-deselect").chosen({
				allow_single_deselect: true
			});
			retornaForm(0);
		</script>
	</div>

	<div id="relatorioSistema">

		<?php if ($cod_empresa != 0) { ?>
			<select data-placeholder="Selecione um sistema" name="COD_SISTEMAS" id="COD_SISTEMAS" class="chosen-select-deselect" style="width:100%;" required>
				<?php

				$sql = "SELECT COD_SISTEMAS FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa ";
				$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
				$qrBuscaSistema = mysqli_fetch_assoc($arrayQuery);
				$sistemasMarka = $qrBuscaSistema['COD_SISTEMAS'];

				$sql = "SELECT COD_SISTEMA, DES_SISTEMA FROM SISTEMAS WHERE COD_SISTEMA IN (" . $sistemasMarka . ") order by DES_SISTEMA ";
				$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

				while ($qrListaSistemas = mysqli_fetch_assoc($arrayQuery)) {
					echo "
										  <option value='" . $qrListaSistemas['COD_SISTEMA'] . "'>" . $qrListaSistemas['DES_SISTEMA'] . "</option> 
										";
				}
				?>
			</select>
		<?php } ?>
	</div>

	<div>
		<div id="relatorioPlataforma">
			<?php if ($cod_empresa != 0) { ?>
				<select class="chosen-select-deselect" data-placeholder="Selecione a plataforma" name="COD_PLATAFORMA" id="COD_PLATAFORMA" required>
					<?php

					$sql = "SELECT EM.COD_PLATAFORMA, SP.DES_PLATAFORMA FROM EMPRESAS EM 
									INNER JOIN SAC_PLATAFORMA SP ON EM.COD_PLATAFORMA = SP.COD_PLATAFORMA
									WHERE EM.COD_EMPRESA = $cod_empresa";
					$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

					while ($qrPlataforma = mysqli_fetch_assoc($arrayQuery)) {
					?>
						<option value="<?php echo $qrPlataforma['COD_PLATAFORMA']; ?>"><?php echo $qrPlataforma['DES_PLATAFORMA']; ?></option>
					<?php } ?>
				</select>
			<?php } ?>
		</div>
	</div>

	<div>
		<div id="relatorioIntegracao">
			<?php if ($cod_empresa != 0) { ?>
				<select class="chosen-select-deselect" data-placeholder="Selecione a integradora" name="COD_INTEGRADORA" id="COD_INTEGRADORA" required>
					<?php
					$sql = "SELECT 
                            EM.COD_INTEGRADORA, 
                            (SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = EM.COD_INTEGRADORA) AS NOM_FANTASI 
                        FROM EMPRESAS EM 
                        WHERE EM.COD_EMPRESA = $cod_empresa";

					$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

					while ($qrIntegra = mysqli_fetch_assoc($arrayQuery)) {
					?>
						<option value="<?php echo $qrIntegra['COD_INTEGRADORA']; ?>">
							<?php echo $qrIntegra['NOM_FANTASI']; ?>
						</option>
					<?php } ?>
				</select>
			<?php } ?>
		</div>
	</div>


	<div>
		<div id="relatorioVersaoIntegra">
			<?php if ($cod_empresa != 0) { ?>
				<select class="chosen-select-deselect" data-placeholder="Selecione a versão" name="COD_VERSAOINTEGRA" id="COD_VERSAOINTEGRA" required>
					<?php

					$sql = "SELECT EM.COD_VERSAOINTEGRA, SV.DES_VERSAOINTEGRA FROM EMPRESAS EM 
									INNER JOIN SAC_VERSAOINTEGRA SV ON EM.COD_VERSAOINTEGRA = SV.COD_VERSAOINTEGRA 
									WHERE EM.COD_EMPRESA = $cod_empresa";
					$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

					while ($qrIntegracao = mysqli_fetch_assoc($arrayQuery)) {
					?>
						<option value="<?php echo $qrIntegracao['COD_VERSAOINTEGRA']; ?>"><?php echo $qrIntegracao['DES_VERSAOINTEGRA']; ?></option>
					<?php } ?>
				</select>
			<?php } ?>
		</div>
	</div>

</div>