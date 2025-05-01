
<header>
     <h3>Contatos</h3>
</header>
<table class="table table-dark table-striped">
<div>
<a class="btn btn-info" href="index.php?menuop=cadastrocontato" role="button">Novo Contato</a>
 </div>
 <p>
 <div>
    <form action="index.php?menuop=contatos" method="post">
    <input type="text" name="txt_pesquisa">
    <button class="btn btn-primary" type="submit">Pesquisar</button>
    <p>
    </form>
 </div>  
   <thead>   
     <tr>
          <th>ID</th>         
          <th>Nome</th>
          <th>E-mail</th>
          <th>Telefone</th>
          <th>Endereço</th>
          <th>Sexo</th>
          <th>Data de Nasc.</th>
          <th>CPF</th>   
          <th>Editar Contato</th>
          <th>Excluir</th>                
     </tr>
   </thead>
</table>
    <tbody>
    <?php
    $quantidade= 8;
    $pagina=(isset($_GET['pagina']))?(int)$_GET['pagina']:1;
    $inicio=($quantidade*$pagina)-$quantidade;

    $txt_pesquisa=(isset($_POST["txt_pesquisa"]))?$_POST["txt_pesquisa"]:"";

    $sql = "SELECT
    idContato,
    upper(nomeContato) AS nomeContato,
    lower(emailContato) AS emailContato,
    telefoneContato,
    upper(enderecoContato) AS enderecoContato,
    cpfContato,
    CASE
        WHEN sexoContato='F' THEN 'FEMININO'
        WHEN sexoContato='M' THEN 'MASCULINO'
    ELSE
        'NÃO ESPECIFICADO'
    END AS sexoContato,
    DATE_FORMAT(dataNascContato,'%d/%m/%Y') AS dataNascContato
     FROM tabeladecontatos 
     WHERE idContato='$txt_pesquisa' or nomeContato LIKE'%$txt_pesquisa%'
     ORDER BY nomeContato ASC
     LIMIT $inicio , $quantidade";
    $rs = mysqli_query($conexao,$sql) or die("Erro na consulta ao banco de dados!" .
     mysqli_connect($conexao));
     while($dados=mysqli_fetch_assoc($rs)){   
    ?>
     <div class="row">
     <table class="table table-dark table-striped table-borderless">
        <tr>
        <td><?=$dados["idContato"]?></td>
        <td><?=$dados["nomeContato"]?></td>  
        <td><?=$dados["emailContato"]?></td>  
        <td><?=$dados["telefoneContato"]?></td>
        <td><?=$dados["enderecoContato"]?></td>  
        <td><?=$dados["sexoContato"]?></td>  
        <td><?=$dados["dataNascContato"]?></td>
        <td><?=$dados["cpfContato"]?></td>
        <td><a class="btn btn-dark" href="index.php?menuop=editarcontato&idContato=<?=$dados["idContato"]?>" role="button">Editar</a></th>
        <td><a class="btn btn-danger" href="index.php?menuop=excluircontato&idContato=<?=$dados["idContato"]?>" role="button">Excluir</a></th>    
        </tr>
       </table>  
   <?php
     }
   ?>
      </div>
    </tbody>
</table>
<br>
<?php
$sqlTotal = "SELECT idContato FROM tabeladecontatos";
$qrTotal = mysqli_query($conexao,$sqlTotal) or die(mysqli_error($conexao));
$numTotal = mysqli_num_rows($qrTotal);
$totalPagina = ceil($numTotal/$quantidade);
echo '<a href="?menuop=contatos&pagina=1">Primeira Página</a>';

  if($pagina>6){
    ?>    
        <a href="?menuop=contatos&pagina=<?php echo $pagina-1?>"><<</a>
        <?php
}
for($i=1;$i<=$totalPagina;$i++){

if($i>=($pagina-5)&& $i <=($pagina+5)){
  if($i==$pagina){
    echo $i;
 }else{
    echo "<a href=\"?menuop=contatos&pagina=$i\">$i</a> ";
      }
}
  }
if($pagina<($totalPagina-5)){
  ?>
  <a href="?menuop=contatos&pagina=<?php echo $pagina+1?>">>></a>
<?php
}
echo "<a href=\"?menuop=contatos&pagina=$totalPagina\">Última Página</a>";





