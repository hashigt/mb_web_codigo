<?php
require_once("../comum.php");
require_once(BASE_DIR . "/somenteAutenticado.php");

use Mbelo\Bazar\Banco;

$id_categoria = filter_input(INPUT_POST, "id_categoria", FILTER_VALIDATE_INT);

if ($id_categoria) {
    $pdo = Banco::obterConexao();
    
    try {
        $ps = $pdo->prepare("delete from categoria where id_categoria = :id_categoria");
        $ps->bindParam(":id_categoria", $id_categoria, PDO::PARAM_INT);
        $sucesso = $ps->execute();
        
        if ($sucesso) {
            http_response_code(200);
            echo json_encode([
                "id_categoria" => $id_categoria,
                "mensagem" => "Categoria excluída com sucesso!"
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Não foi possível excluir a categoria."]);
        }
    } catch (PDOException $e) {
        // Trata erro caso haja produtos vinculados (chave estrangeira)
        http_response_code(400);
        echo json_encode(["erro" => "Erro ao excluir: existem registros vinculados a esta categoria."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["erro" => "ID de categoria inválido."]);
}
