<?php 

	include '../_system/_functionsMain.php';


	$opcao = $_GET['opcao'];

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$andAno = preg_replace('/\\\\/', '',fnLimpaCampo($_REQUEST['FILTRO_ANO']));
	$andTurno = preg_replace('/\\\\/', '',fnLimpaCampo($_REQUEST['FILTRO_TURNO']));
	$andEstado = preg_replace('/\\\\/', '',fnLimpaCampo($_REQUEST['FILTRO_ESTADO']));
	$andCidade = preg_replace('/\\\\/', '',fnLimpaCampo($_REQUEST['FILTRO_CIDADE']));
	$andCargo = preg_replace('/\\\\/', '',fnLimpaCampo($_REQUEST['FILTRO_CARGO']));
	$nm_candidato = fnLimpaCampo($_REQUEST['NM_CANDIDATO']);

	$inicializador = 0;

	if($andAno != ""){
		$classeAno = "filtrado";
		$inicializador = 1;
	}else{
		$classeAno = "";
	}
	if($andTurno != ""){
		$classeTurno = "filtrado";
		$inicializador = 1;
	}else{
		$classeTurno = "";
	}
	if($andEstado != ""){
		$classeEstado = "filtrado";
		$inicializador = 1;
	}else{
		$classeEstado = "";
	}
	if($andCidade != ""){
		$classeCidade = "filtrado";
		$inicializador = 1;
	}else{
		$classeCidade = "";
	}
	if($andCargo != ""){
		$classeCargo = "filtrado";
		$inicializador = 1;
	}else{
		$classeCargo = "";
	}

	if($nm_candidato != ""){
		$andCandidato = "AND NM_URNA_CANDIDATO LIKE '%$nm_candidato%'";
	}else{
		$andCandidato = "";
	}

	switch ($opcao) {

		case 'loadMore':
		
			$limite = $_GET['itens'];

			$sql = "SELECT NM_URNA_CANDIDATO,
						   SG_PARTIDO,
						   DS_CARGO,
						   DS_SIT_TOT_TURNO,
						   SUM(QT_VOTOS_NOMINAIS) AS VOTOS_NOMINAIS
						   FROM ELEICOES
					WHERE $inicializador = 1
					$andAno
					$andTurno
					$andEstado
					$andCidade
					$andCargo
					$andCandidato
					GROUP BY NR_CANDIDATO
					ORDER BY SUM(QT_VOTOS_NOMINAIS) DESC
					LIMIT $limite,50
					";
			
			// fnEscreve($cod_empresa);
			//fnTestesql(connTemp($cod_empresa,''),$sql);											
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$count=0;
			while ($qrApoia = mysqli_fetch_assoc($arrayQuery)){			

				$count++;

				switch ($qrApoia['DS_SIT_TOT_TURNO']) {

					case 'NÃO ELEITO':
						$cor = "#D98880";
					break;

					case 'SUPLENTE':
						$cor = "#F7DC6F";
					break;
					
					default:
						$cor = "#58D68D";
					break;

				}

	?>

					<tr>
					  <td><small><?=$qrApoia['NM_URNA_CANDIDATO']?></small></td>
					  <td><small><?=$qrApoia['SG_PARTIDO']?></small></td>
					  <td><small><?=$qrApoia['DS_CARGO']?></small></td>
					  <td>
					  	<small>
							<p class="label" style="background-color: <?=$cor?>"> 
								<?=$qrApoia['DS_SIT_TOT_TURNO']?>
							</p>
						</small>
					  </td>
					  <td class='text-center'><small><?=fnValor($qrApoia['VOTOS_NOMINAIS'],0)?></small></td>
					</tr>

	<?php 

			}

		break;
		
		default:
		

			$sql = "SELECT NM_URNA_CANDIDATO
						   FROM ELEICOES
					WHERE $inicializador = 1
					$andAno
					$andTurno
					$andEstado
					$andCidade
					$andCargo
					$andCandidato
					GROUP BY NR_CANDIDATO
					";
			//fnTestesql(connTemp($cod_empresa,''),$sql);		
			//fnEscreve($sql);

			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
			$totalitens_por_pagina = mysqli_num_rows($retorno);

			$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			// Filtro por Grupo de Lojas
			// include "filtroGrupoLojas.php";

			$sql = "SELECT NM_URNA_CANDIDATO,
						   SG_PARTIDO,
						   DS_CARGO,
						   DS_SIT_TOT_TURNO,
						   SUM(QT_VOTOS_NOMINAIS) AS VOTOS_NOMINAIS
						   FROM ELEICOES
					WHERE $inicializador = 1
					$andAno
					$andTurno
					$andEstado
					$andCidade
					$andCargo
					$andCandidato
					GROUP BY NR_CANDIDATO
					ORDER BY SUM(QT_VOTOS_NOMINAIS) DESC
					LIMIT 50
					";
			
			// fnEscreve($sql);
			//fnTestesql(connTemp($cod_empresa,''),$sql);											
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			// fnEscreve($totalitens_por_pagina);

			if(mysqli_num_rows($arrayQuery) > 0){


		?>

			<table class="table table-bordered table-hover tablesorter">
											
				<thead>
					<tr>
						<th>Candidato</th>
						<th>Partido</th>
						<th>Cargo</th>
						<th>Resultado</th>
						<th>Votos Nominais</th>
					</tr>
				</thead>

				<tbody id="relConteudo">

		<?php
								  
				$count=0;
				while ($qrApoia = mysqli_fetch_assoc($arrayQuery)){			

					$count++;

					switch ($qrApoia['DS_SIT_TOT_TURNO']) {

						case 'NÃO ELEITO':
							$cor = "#D98880";
						break;

						case 'SUPLENTE':
							$cor = "#F7DC6F";
						break;
						
						default:
							$cor = "#58D68D";
						break;

					}

		?>

						<tr>
						  <td><small><?=$qrApoia['NM_URNA_CANDIDATO']?></small></td>
						  <td><small><?=$qrApoia['SG_PARTIDO']?></small></td>
						  <td><small><?=$qrApoia['DS_CARGO']?></small></td>
						  <td>
						  	<small>
								<p class="label" style="background-color: <?=$cor?>"> 
									<?=$qrApoia['DS_SIT_TOT_TURNO']?>
								</p>
							</small>
						  </td>
						  <td class='text-center'><small><?=fnValor($qrApoia['VOTOS_NOMINAIS'],0)?></small></td>
						</tr>

		<?php 

				}

		?>

				</tbody>
				
			</table>

			<script>
				var cont = 0;
				$('#loadMore').click(function(){
					
					cont +=50;

					if(cont >= "<?=$totalitens_por_pagina?>"){
						$('#loadMore').addClass('disabled');
						$('#loadMore').text('Todos os itens iá se encontam na lista');
					}

					$.ajax({
						type: "POST",
						url: "relatorios/ajxRelEleicoes.do?id=<?=fnEncode($cod_empresa)?>&opcao=loadMore&itens="+cont,
						data: $("#formulario").serialize(),
						beforeSend:function(){	
							$('#loadMore').text('Carregando...');
						},
						success:function(data){
							$('#loadMore').text('Carregar mais...');
							$('#relConteudo').append(data);
							console.log(data);
						},
						error:function(data){
							alert('Erro ao carregar...');
							console.log(data);
						}
					});
				});
			</script>

		<?php 

				if($totalitens_por_pagina > 50){
					echo "<center><a href='javascript:void(0)' class='btn btn-primary' id='loadMore'>Carregar mais...</a></center>";
				}

			}else{
				echo "<tr></tr><tr><td colspan='5' class='text-center'><h4>Não há registros para pesquisa com estes parâmetros.</h4></td></tr>";
			}

		break;

	}

	

?>