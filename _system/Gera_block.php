<?php
class Block {
    public $index;
    public $timestamp;
    public $data;
    public $previousHash;
    public $hash;
    public $nonce;

    public function __construct($index, $timestamp, $data, $previousHash) {
        $this->index = $index;
        $this->timestamp = $timestamp;
        $this->data = $data;
        $this->previousHash = $previousHash;
        $this->nonce = 0;
        $this->hash = $this->mineBlock(2); // Dificuldade de mineração (exemplo: 2)
    }

    public function calculateHash($nonce) {
        return hash('sha256', $this->index . $this->timestamp . $this->data . $this->previousHash . $nonce);
    }

    public function mineBlock($difficulty) {
        $target = str_repeat('0', $difficulty);
        while (substr($this->calculateHash($this->nonce), 0, $difficulty) !== $target) {
            $this->nonce++;
        }
        return $this->calculateHash($this->nonce);
    }
}

class Blockchain {
    public $chain;
    public $difficulty;

    public function __construct() {
        $this->chain = [$this->createGenesisBlock()];
        $this->difficulty = 2; // Dificuldade da mineração
    }

    public function createGenesisBlock() {
        return new Block(0, time(), "Genesis Block", "0");
    }

    public function addBlock($data) {
        $previousBlock = $this->chain[count($this->chain) - 1];
        $newBlock = new Block(count($this->chain), time(), $data, $previousBlock->hash);
        $this->chain[] = $newBlock;
    }

    public function isChainValid() {
        for ($i = 1; $i < count($this->chain); $i++) {
            $currentBlock = $this->chain[$i];
            $previousBlock = $this->chain[$i - 1];

            if ($currentBlock->hash !== $currentBlock->calculateHash($currentBlock->nonce) ||
                $currentBlock->previousHash !== $previousBlock->hash) {
                return false;
            }
        }
        return true;
    }
}

class MarketCounter {
    private $blockchain;

    public function __construct() {
        $this->blockchain = new Blockchain();
    }

    public function sellProduct($productName, $price, $seller, $buyer) {
        $transactionData = "Venda de $productName por $price de $seller para $buyer";
        $this->blockchain->addBlock($transactionData);
    }

    public function viewTransactions() {
        foreach ($this->blockchain->chain as $block) {
            echo "Index: " . $block->index . "<br>";
            echo "Timestamp: " . date('Y-m-d H:i:s', $block->timestamp) . "<br>";
            echo "Data: " . $block->data . "<br>";
            echo "Previous Hash: " . $block->previousHash . "<br>";
            echo "Hash: " . $block->hash . "<br>";
        }
    }
}

// Exemplo de uso
/*$marketCounter = new MarketCounter();
$marketCounter->sellProduct("Maçã", 1.5, "Fornecedor A", "Cliente B");
$marketCounter->sellProduct("Laranja", 2.0, "Fornecedor C", "Cliente D");

$marketCounter->viewTransactions();*/
?>