<?php
require 'connect.php';
$erro = '';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = $_GET['id'];


$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $categoria = trim($_POST['categoria']);
    $descricao = trim($_POST['descricao']);
    $preco = filter_var($_POST['preco'], FILTER_VALIDATE_FLOAT);
    $quantidade = filter_var($_POST['quantidade'], FILTER_VALIDATE_INT);
    $data_validade = trim($_POST['data_validade']);

    if (empty($nome) || $preco === false || $quantidade === false) {
        $erro = "Preencha os campos corretamente.";
    } else {
        try {
 
            $sql = "UPDATE produtos SET nome = :nome, categoria = :categoria, descricao = :descricao, 
                    preco = :preco, quantidade = :quantidade, data_validade = :data_validade WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':categoria' => $categoria,
                ':descricao' => $descricao,
                ':preco' => $preco,
                ':quantidade' => $quantidade,
                ':data_validade' => $data_validade,
                ':id' => $id
            ]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $erro = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Editar Produto</h2>
    <?php if($erro): ?> <div class="alert alert-danger"><?= $erro ?></div> <?php endif; ?>
    
    <form method="POST">
        <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Categoria:</label>
            <input type="text" name="categoria" value="<?= htmlspecialchars($produto['categoria']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descrição:</label>
            <textarea name="descricao" class="form-control"><?= htmlspecialchars($produto['descricao']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Preço:</label>
            <input type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Quantidade:</label>
            <input type="number" name="quantidade" value="<?= $produto['quantidade'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Data de Validade:</label>
            <input type="date" name="data_validade" value="<?= $produto['data_validade'] ?>" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    </form>
</body>
</html>