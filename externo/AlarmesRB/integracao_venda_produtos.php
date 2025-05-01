<?php

include_once 'BunkerApi/config.php';
include_once 'BunkerApi/BunkerMK.php';

class Integracao {
     private $bunker;
     private $conexao;
     private $ultimo_id_venda;
     
     public function __construct() {
         ini_set('display_errors',0);
        ini_set('display_startup_erros',0);
        error_reporting(0);
        $this->bunker = new BunkerMK();
        $this->ultimo_id_venda =  $this->bunker->getUltimoIDVenda2();
        //$this->bunker->debug($this->ultimo_id_venda);
        $this->conexao = new PDO("mysql:host=".DB_CLIETE_HOST.";dbname=san", DB_CLIETE_USUARIO, DB_CLIETE_SENHA, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
                
        foreach ($this->pegarDadosVenda() as $row) {
            $DadosVenda = (object) $row;
            $this->atualizaCadastro($DadosVenda);     
            $DadosVendaItens = $this->pegarDadosVendaItens($DadosVenda->id_vendapdv);
            $retorno = $this->inserirVenda($DadosVenda, $DadosVendaItens);
      
            if($retorno->InsereVendaResponse->msgerro){
                echo '<br>id_vendapdv: '.$DadosVenda->id_vendapdv.'   Erro: '.$retorno->InsereVendaResponse->msgerro;
            }else{
                echo '<br>id_vendapdv: '.$DadosVenda->id_vendapdv;
            }
        }
    }
    
    public function inserirVenda ($DadosVenda, $DadosVendaItens){
        //$this->bunker->debug($DadosVenda);
        
        $venda['id_vendapdv'] = 'p_'.$DadosVenda->id_vendapdv;
        $venda['datahora'] = $DadosVenda->datahora;
        $venda['cartao'] = $DadosVenda->cartao;
        $venda['valortotal'] = $DadosVenda->valortotal;
        $venda['cupom'] = '';
        $venda['formapagamento'] = 'dinheiro';
        $venda['cartaoamigo'] = '';
        $venda['pontosextras'] = '';
        $venda['naopontuar'] = '';
        $venda['codvendedor'] = '';
        $venda['pontostotal'] = '';

        // Inicio do loopping de produtos
        $count_item = 1;
        foreach($DadosVendaItens as $item){
            $DadosItem = (object) $item;
            //$this->bunker->debug($DadosItem);
            $item['id_item'] = $count_item;
            $item['produto'] = $DadosItem->produto;
            $item['codigoproduto'] = (!empty($DadosItem->codigoproduto))? $DadosItem->codigoproduto: '0';
            //$this->bunker->debug($item['codigoproduto']);
            $item['quantidade'] = $DadosItem->quantidade;
            $item['valor'] =  $DadosItem->valor;
            $item['naopontuar'] = '';

            $items['vendaitem'][] = $item;
            $count_item ++;
        }
        // Fim do looping
        //$this->bunker->debug($items);
        $venda['items']= $items;
        //$this->bunker->debug($venda);
        $dados['venda'] = $venda;
        $retorno = $this->bunker->enviar($dados, 'InserirVenda');
        $this->bunker->updateUltimoIDVenda2($DadosVenda->id_vendapdv);
        
        //$this->bunker->getXmlEnvio();
        //$this->bunker->getXmlRetorno();
        
        return $retorno;
    }
        
    public function atualizaCadastro ($Dados){
       $cliente['cartao'] = $Dados->cartao;
       $cliente['tipocliente'] = 'PF';
       $cliente['nome'] = $Dados->nome;
       $cliente['cpf'] = $Dados->cpf;
       $cliente['cnpj'] = $Dados->cnpj;
       $cliente['rg']= '';
       $cliente['sexo']= $Dados->sexo;
       $cliente['datanascimento']=$Dados->datanascimento;
       $cliente['estadocivil']='';
       $cliente['email']=$Dados->I;
       $cliente['dataalteracao']='';
       $cliente['cartaotitular']='';
       $cliente['dataalteracao']='';
       $cliente['nomeportador']='';
       $cliente['grupo']='';
       $cliente['profissao']='';	
       $cliente['clientedesde']=$Dados->clientedesde;
       $cliente['endereco']=$Dados->endereco;
       $cliente['numero']=$Dados->numero;	
       $cliente['complemento']=$Dados->complemento;
       $cliente['bairro']=$Dados->bairro;
       $cliente['cidade']=$Dados->cidade;	
       $cliente['estado']=$Dados->estado;
       $cliente['cep']=$Dados->cep;
       $cliente['telresidencial']='';	
       $cliente['telcelular']='';
       $cliente['telcomercial']='';
       $cliente['saldo']='';
       $cliente['saldoresgate']='';
       $cliente['msgerro']='';
       $cliente['msgcampanha']='';
       $cliente['url']='';		
       $cliente['ativacampanha']='';
       $cliente['dadosextras']='';
       $cliente['bloqueado']='';
       $cliente['motivo']='';	
       $cliente['adesao']='';
       $cliente['codatendente']='';
       $cliente['senha']='';
       $cliente['urlextrato']='';
       $cliente['retornodnamais']='';

       $dados['cliente'] = $cliente;
       //$this->bunker->debug($dados);
       $retorno = $this->bunker->enviar($dados, 'AtualizaCadastro');
       //$this->bunker->debug($retorno);
       if($retorno->AtualizaCadastroResult->msgerro <> 'OK'){
           echo '<br>cartao: '.$dados['cliente']['cartao'].'   Erro: '.$retorno->AtualizaCadastroResult->msgerro;
       }

        //$bunker->getXmlEnvio();
        //$bunker->getXmlRetorno();
    }   
    
    public function pegarDadosVenda (){
        $sql = "select 
		v.ID_VendaProduto  as id_vendapdv
                , DATE_FORMAT(v.DT_Pedido,'%Y-%m-%d %h:%i:%s') as datahora
                , c.NU_CNPJ_CPF as cartao
                , SUM(VL_TotalFaturado) as valortotal
                , c.TP_Situacao as tipocliente
               , c.NM_Cliente as nome
               , c.NU_CNPJ_CPF as cpf
               , c.NU_CNPJ_CPF as cnpj
               , 'M' as sexo
               , '2000-01-01' as datanascimento
               , '' as email
               , c.NM_Endereco as endereco
               , c.NU_Endereco as numero
               , '' as complemento
               , b.NM_Bairro as bairro
               , m.NM_Municipio as cidade
               , e.NM_Estado as estado
               , c.NU_CEP as cep
               , '' as telresidencial
               , '' as telcelular
               , '' as telcomercial
               , c.DT_Cadastro as clientedesde
             from vendasprodutos as v
             inner join produtosvendidos as vp on vp.ID_VendaProduto = v.ID_VendaProduto
             inner JOIN clientes as c on c.ID_Cliente = v.ID_Cliente
             inner join bairros as b on b.ID_Bairro = c.ID_Bairro
             inner join municipios as m on m.ID_Municipio = b.ID_Municipio
             inner join estados as e on e.ID_Estado = m.ID_Estado
             where v.ID_VendaProduto > {$this->ultimo_id_venda}
             GROUP BY v.ID_VendaProduto";
        //$this->bunker->debug($sql);         
        $resultado = $this->conexao->query($sql);
        return $resultado;
    }
    
    public function pegarDadosVendaItens ($id_vendapdv){
        $sql = "select pv.ID_Produto as codigoproduto
                , pv.NU_QuantidadeFaturada as quantidade
                , p.NM_Produto as produto
                , pv.VL_FinalFaturado as valor
                 from produtosvendidos as pv inner join produtos as p on p.ID_Produto = pv.ID_Produto
                where pv.ID_VendaProduto = {$id_vendapdv}";
        $resultado = $this->conexao->query($sql);
        return $resultado;
    }
} 
    
new Integracao();
echo '<br><br>--------------------->Finalizado<-----------------------';
exit();


