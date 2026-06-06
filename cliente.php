<?php
include "conexao.php";

$id_editar = "";
$nome_editar = "";
$cpf_editar = "";
$email_editar = "";
$telefone_editar = "";
$cep_editar = "";
$logradouro_editar = "";
$numero_editar = "";
$bairro_editar = "";
$cidade_editar = "";
$estado_editar = "";

if (isset($_GET["editar"])) {
    $id = $_GET["editar"];

    $sql = "
        SELECT cliente.*, telefone_cliente.numero AS telefone,
               endereco_cliente.cep, endereco_cliente.logradouro,
               endereco_cliente.numero AS numero_endereco,
               endereco_cliente.bairro, endereco_cliente.cidade,
               endereco_cliente.estado
        FROM cliente
        LEFT JOIN telefone_cliente ON cliente.id_cliente = telefone_cliente.id_cliente
        LEFT JOIN endereco_cliente ON cliente.id_cliente = endereco_cliente.id_cliente
        WHERE cliente.id_cliente = $id
        LIMIT 1
    ";

    $resultado_editar = $conexao->query($sql);
    $cliente_editar = $resultado_editar->fetch_assoc();

    $id_editar = $cliente_editar["id_cliente"];
    $nome_editar = $cliente_editar["nome"];
    $cpf_editar = $cliente_editar["cpf"];
    $email_editar = $cliente_editar["email"];
    $telefone_editar = $cliente_editar["telefone"];
    $cep_editar = $cliente_editar["cep"];
    $logradouro_editar = $cliente_editar["logradouro"];
    $numero_editar = $cliente_editar["numero_endereco"];
    $bairro_editar = $cliente_editar["bairro"];
    $cidade_editar = $cliente_editar["cidade"];
    $estado_editar = $cliente_editar["estado"];
}

if (isset($_POST["salvar"])) {
    $id_cliente = $_POST["id_cliente"];
    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $cep = $_POST["cep"];
    $logradouro = $_POST["logradouro"];
    $numero = $_POST["numero"];
    $bairro = $_POST["bairro"];
    $cidade = $_POST["cidade"];
    $estado = $_POST["estado"];

    if ($id_cliente == "") {
        $conexao->query("INSERT INTO cliente (nome, cpf, email)
                         VALUES ('$nome', '$cpf', '$email')");

        $id_cliente = $conexao->insert_id;

        $conexao->query("INSERT INTO telefone_cliente (numero, tipo, id_cliente)
                         VALUES ('$telefone', 'Celular', $id_cliente)");

        $conexao->query("INSERT INTO endereco_cliente
                         (cep, logradouro, numero, bairro, cidade, estado, id_cliente)
                         VALUES
                         ('$cep', '$logradouro', '$numero', '$bairro', '$cidade', '$estado', $id_cliente)");
    } else {
        $conexao->query("UPDATE cliente
                         SET nome = '$nome',
                             cpf = '$cpf',
                             email = '$email'
                         WHERE id_cliente = $id_cliente");

        $existe_telefone = $conexao->query("SELECT * FROM telefone_cliente WHERE id_cliente = $id_cliente");

        if ($existe_telefone->num_rows > 0) {
            $conexao->query("UPDATE telefone_cliente
                             SET numero = '$telefone',
                                 tipo = 'Celular'
                             WHERE id_cliente = $id_cliente");
        } else {
            $conexao->query("INSERT INTO telefone_cliente (numero, tipo, id_cliente)
                             VALUES ('$telefone', 'Celular', $id_cliente)");
        }

        $existe_endereco = $conexao->query("SELECT * FROM endereco_cliente WHERE id_cliente = $id_cliente");

        if ($existe_endereco->num_rows > 0) {
            $conexao->query("UPDATE endereco_cliente
                             SET cep = '$cep',
                                 logradouro = '$logradouro',
                                 numero = '$numero',
                                 bairro = '$bairro',
                                 cidade = '$cidade',
                                 estado = '$estado'
                             WHERE id_cliente = $id_cliente");
        } else {
            $conexao->query("INSERT INTO endereco_cliente
                             (cep, logradouro, numero, bairro, cidade, estado, id_cliente)
                             VALUES
                             ('$cep', '$logradouro', '$numero', '$bairro', '$cidade', '$estado', $id_cliente)");
        }
    }

    header("Location: cliente.php");
    exit;
}

if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];

    $verifica_reserva = $conexao->query("SELECT * FROM reserva WHERE id_cliente = $id");
    $verifica_locacao = $conexao->query("SELECT * FROM locacao WHERE id_cliente = $id");

    if ($verifica_reserva->num_rows > 0 || $verifica_locacao->num_rows > 0) {
        echo "<script>
                alert('Não é possível excluir este cliente, pois ele possui reserva ou locação cadastrada.');
                window.location.href = 'cliente.php';
              </script>";
        exit;
    }

    $conexao->query("DELETE FROM telefone_cliente WHERE id_cliente = $id");
    $conexao->query("DELETE FROM endereco_cliente WHERE id_cliente = $id");
    $conexao->query("DELETE FROM cliente WHERE id_cliente = $id");

    header("Location: cliente.php");
    exit;
}

$resultado = $conexao->query("
    SELECT cliente.*, telefone_cliente.numero AS telefone,
           endereco_cliente.cidade, endereco_cliente.estado
    FROM cliente
    LEFT JOIN telefone_cliente ON cliente.id_cliente = telefone_cliente.id_cliente
    LEFT JOIN endereco_cliente ON cliente.id_cliente = endereco_cliente.id_cliente
");
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
    <input type="email" name="email" placeholder="E-mail" value="<?php echo $email_editar; ?>">
    <input type="text" name="telefone" placeholder="Telefone" value="<?php echo $telefone_editar; ?>">

    <input type="text" name="cep" placeholder="CEP" value="<?php echo $cep_editar; ?>">
    <input type="text" name="logradouro" placeholder="Logradouro" value="<?php echo $logradouro_editar; ?>">
    <input type="text" name="numero" placeholder="Número" value="<?php echo $numero_editar; ?>">
    <input type="text" name="bairro" placeholder="Bairro" value="<?php echo $bairro_editar; ?>">
    <input type="text" name="cidade" placeholder="Cidade" value="<?php echo $cidade_editar; ?>">
    <input type="text" name="estado" placeholder="UF" maxlength="2" value="<?php echo $estado_editar; ?>">

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
        <th>Cidade/UF</th>
        <th>Ação</th>
    </tr>

    <?php while ($cliente = $resultado->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $cliente["id_cliente"]; ?></td>
        <td><?php echo $cliente["nome"]; ?></td>
        <td><?php echo $cliente["cpf"]; ?></td>
        <td><?php echo $cliente["telefone"]; ?></td>
        <td><?php echo $cliente["email"]; ?></td>
        <td><?php echo $cliente["cidade"] . "/" . $cliente["estado"]; ?></td>
        <td>
            <a class="editar" href="cliente.php?editar=<?php echo $cliente['id_cliente']; ?>">Editar</a>
            <a class="excluir" href="cliente.php?excluir=<?php echo $cliente['id_cliente']; ?>">Excluir</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
