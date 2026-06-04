<?php
include "conexao.php";

$id_editar = "";
$nome_editar = "";
$cpf_editar = "";
$telefone_editar = "";
$email_editar = "";

if (isset($_GET["editar"])) {
    $id = $_GET["editar"];

    $sql = "SELECT * FROM cliente WHERE id_cliente = $id";
    $resultado_editar = $conexao->query($sql);
    $cliente_editar = $resultado_editar->fetch_assoc();

    $id_editar = $cliente_editar["id_cliente"];
    $nome_editar = $cliente_editar["nome"];
    $cpf_editar = $cliente_editar["cpf"];
    $telefone_editar = $cliente_editar["telefone"];
    $email_editar = $cliente_editar["email"];
}

if (isset($_POST["salvar"])) {
    $id_cliente = $_POST["id_cliente"];
    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];

    if ($id_cliente == "") {
        $sql = "INSERT INTO cliente (nome, cpf, telefone, email)
                VALUES ('$nome', '$cpf', '$telefone', '$email')";
    } else {
        $sql = "UPDATE cliente 
                SET nome = '$nome',
                    cpf = '$cpf',
                    telefone = '$telefone',
                    email = '$email'
                WHERE id_cliente = $id_cliente";
    }

    $conexao->query($sql);

    header("Location: cliente.php");
    exit;
}

if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];
    $conexao->query("DELETE FROM cliente WHERE id_cliente = $id");

    header("Location: cliente.php");
    exit;
}

$resultado = $conexao->query("SELECT * FROM cliente");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Cadastro de Clientes</h1>

<div class="voltar">
    <a href="index.html">← Voltar ao menu</a>
</div>

<form method="POST">
    <input type="hidden" name="id_cliente" value="<?php echo $id_editar; ?>">

    <input type="text" name="nome" placeholder="Nome" required value="<?php echo $nome_editar; ?>">
    <input type="text" name="cpf" placeholder="CPF" required value="<?php echo $cpf_editar; ?>">
    <input type="text" name="telefone" placeholder="Telefone" value="<?php echo $telefone_editar; ?>">
    <input type="email" name="email" placeholder="E-mail" value="<?php echo $email_editar; ?>">

    <button type="submit" name="salvar">
        <?php echo ($id_editar == "") ? "Salvar" : "Atualizar"; ?>
    </button>
</form>

<h2>Clientes cadastrados</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>CPF</th>
        <th>Telefone</th>
        <th>E-mail</th>
        <th>Ação</th>
    </tr>

    <?php while ($cliente = $resultado->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $cliente["id_cliente"]; ?></td>
        <td><?php echo $cliente["nome"]; ?></td>
        <td><?php echo $cliente["cpf"]; ?></td>
        <td><?php echo $cliente["telefone"]; ?></td>
        <td><?php echo $cliente["email"]; ?></td>
        <td>
            <a class="editar" href="cliente.php?editar=<?php echo $cliente['id_cliente']; ?>">
                Editar
            </a>

            <a class="excluir" href="cliente.php?excluir=<?php echo $cliente['id_cliente']; ?>">
                Excluir
            </a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>