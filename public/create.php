<?php
require 'connect.php';
$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $categoria = trim($_POST['categoria']);
    $descricao = trim($_POST['descricao']);
    $preco = filter_var($_POST['preco'], FILTER_VALIDATE_FLOAT);
    $quantidade = filter_var($_POST['quantidade'], FILTER_VALIDATE_INT);
    $data_validade = trim($_POST['data_validade']);


    if (empty($nome) || empty($categoria) || empty($data_validade) || $preco === false || $quantidade === false) {
        $erro = "Preencha todos os campos corretamente.";
    } else {
        try {
       
            $sql = "INSERT INTO produtos (nome, categoria, descricao, preco, quantidade, data_validade) 
                    VALUES (:nome, :categoria, :descricao, :preco, :quantidade, :data_validade)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':categoria', $categoria);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':quantidade', $quantidade);
            $stmt->bindParam(':data_validade', $data_validade);
            $stmt->execute();
            
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $erro = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Cadastrar Produto</h2>
    <?php if($erro): ?> <div class="alert alert-danger"><?= $erro ?></div> <?php endif; ?>
    
    <form method="POST">
        <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Categoria:</label>
            <input type="text" name="categoria" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descrição:</label>
            <textarea name="descricao" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Preço:</label>
            <input type="number" step="0.01" name="preco" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Quantidade:</label>
            <input type="number" name="quantidade" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Data de Validade:</label>
            <input type="date" name="data_validade" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    </form>
</body>
</html>