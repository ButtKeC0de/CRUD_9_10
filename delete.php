<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Erro ao excluir: " . $e->getMessage());
    }
}
header("Location: index.php");
exit;
?>