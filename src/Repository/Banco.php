<?php
namespace App\Repository;

use App\Entity\Produto;
use App\Entity\Cliente;

class Banco {
	private function conectaBD() {
		$host = 'localhost';
		$user = 'root';
		$pass = '';
		$base = 'verao-2019';
		$banco = new \mysqli($host, $user, $pass, $base);
		if (\mysqli_connect_errno()) {
			exit("Não foi possível conectar no banco de dados!");
		}

		return $banco;
	}

	private function getResultsBD($strsql) {
		$banco = $this->conectaBD();

		$statement = $banco->prepare($strsql);
		if (!$statement) {
			exit($banco->error);
		}

		if (!$statement->execute()) {
			exit($banco->error);
		}

		$resultados = $statement->get_result();

		return $resultados;
	}

	private function fetchProduto($linha) {
		$produto = new Produto();
		$produto->setId($linha->id);
		$produto->setNome($linha->nome);
		$produto->setDescricao($linha->descricao);
		$produto->setPreco($linha->preco);
		$produto->setEstoque($linha->estoque);
		$produto->setImagem($linha->imagem);

		return $produto;
	}

	private function fetchCliente($linha) {
		$cliente = new Cliente();
		$cliente->setId($linha->id);
		$cliente->setNome($linha->nome);
		$cliente->setEmail($linha->email);
		$cliente->setSenha($linha->senha);

		return $cliente;
	}

	public function getProduto($id) {
		$strsql = "select * from produtos where id = " . (int) $id;
		
		$resultados = $this->getResultsBD($strsql);

		$linha = $resultados->fetch_object();
	
		$produto = $this->fetchProduto($linha);

		return $produto;
	}

	public function getProdutosByCategoria($nome) {
		$nome = trim($nome);
		$strsql = "select p.* from produtos as p
		inner join categorias as c on c.id = p.categoria_id
		where c.nome = '$nome'";

		$resultados = $this->getResultsBD($strsql);
	
		$produtos = array();
		while ($linha = $resultados->fetch_object()) {
			$produtos[] = $this->fetchProduto($linha);
		}

		return $produtos;
	}

	public function getProdutosAleatoriamente() {
		$strsql = "select * from produtos order by rand() limit 2";

		$resultados = $this->getResultsBD($strsql);
	
		$produtos = array();
		while ($linha = $resultados->fetch_object()) {
			$produtos[] = $this->fetchProduto($linha);
		}

		return $produtos;
	}

	public function login($email, $senha) {
		$senha = md5($senha);
		$strsql = "select * from clientes where email = '$email' and senha = '$senha'";

		$resultados = $this->getResultsBD($strsql);

		$linha = $resultados->fetch_object();
		
		if (!$linha) {
			return false;
		}

		$cliente = $this->fetchCliente($linha);

		return $cliente;
	}
        
        public function CadastrarCliente ($strsql) {
            $banco = $this->conectaBD();
            
            $statement = $banco->prepare($strsql);
            if (!$statement) {
		exit($banco->error);
            }
            if (!$statement->execute()) {
		exit($banco->error);
            }
            /*
            Parece que esta linha:
            $banco->execute();
            1) está errada, e deveria ser $statement->execute();
            2) é desnecessária, pois a execução já ocorre no 2º if.            
            */
        }
        
        public function buscarProdutos ($buscado) {
            $strsql = "SELECT * FROM produtos WHERE nome OR descricao LIKE '%" . $buscado . "%'";
            
            $resultados = $this->getResultsBD($strsql);
            
            $encontrado = array();
            
            if($resultados->num_rows > 0) {
                $encontrado[0] = " ";
                while ($linha = $resultados->fetch_object()) {
                    $encontrado[] = $this->fetchProduto($linha);
                }
            } else {
                $encontrado[0] = 'Lamentamos, mas nenhum produto foi encontrado. Por favor, tente novamente no futuro.';
            }
            
            return $encontrado;
        }

}
