<?php
include "conexao.php";

$id_editar = "";
$id_cliente_editar = "";
$id_veiculo_editar = "";
$data_inicio_editar = "";
$data_fim_editar = "";
$valor_total_editar = "";

if (isset($_GET["editar"])) {
    $id = $_GET["editar"];

    $sql = "SELECT * FROM locacao WHERE id_locacao = $id";
    $resultado_editar = $conexao->query($sql);
    $locacao_editar = $resultado_editar->fetch_assoc();

    $id_editar = $locacao_editar["id_locacao"];
    $id_cliente_editar = $locacao_editar["id_cliente"];
    $id_veiculo_editar = $locacao_editar["id_veiculo"];
    $data_inicio_editar = $locacao_editar["data_inicio"];
    $data_fim_editar = $locacao_editar["data_fim"];
    $valor_total_editar = $locacao_editar["valor_total"];
}

if (isset($_POST["salvar"])) {
    $id_locacao = $_POST["id_locacao"];
    $data_inicio = $_POST["data_inicio"];
    $data_fim = $_POST["data_fim"];
    $valor_total = $_POST["valor_total"];
    $id_cliente = $_POST["id_cliente"];
    $id_veiculo = $_POST["id_veiculo"];

    if ($id_locacao == "") {
        $sql = "INSERT INTO locacao
                (data_inicio, data_fim, valor_total, id_cliente, id_veiculo)
                VALUES
                ('$data_inicio', '$data_fim', '$valor_total', '$id_cliente', '$id_veiculo')";
    } else {
        $sql = "UPDATE locacao
                SET data_inicio = '$data_inicio',
                    data_fim = '$data_fim',
                    valor_total = '$valor_total',
                    id_cliente = '$id_cliente',
                    id_veiculo = '$id_veiculo'
                WHERE id_locacao = $id_locacao";
    }

    $conexao->query($sql);

    header("Location: locacao.php");
    exit;
}

if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];
    $conexao->query("DELETE FROM locacao WHERE id_locacao = $id");

    header("Location: locacao.php");
    exit;
}

$clientes = $conexao->query("SELECT * FROM cliente");

$veiculos = $conexao->query("
    SELECT veiculo.id_veiculo, veiculo.placa, modelo.nome_modelo, marca.nome_marca
    FROM veiculo
    JOIN modelo ON veiculo.id_modelo = modelo.id_modelo
    JOIN marca ON modelo.id_marca = marca.id_marca
");

$resultado = $conexao->query("
    SELECT locacao.*, cliente.nome, veiculo.placa, modelo.nome_modelo, marca.nome_marca
    FROM locacao
    JOIN cliente ON locacao.id_cliente = cliente.id_cliente
    JOIN veiculo ON locacao.id_veiculo = veiculo.id_veiculo
    JOIN modelo ON veiculo.id_modelo = modelo.id_modelo
    JOIN marca ON modelo.id_marca = marca.id_marca
");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Locações</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="topo">
    <div class="logo">Locadora M3</div>

    <nav class="menu">
        <a href="cliente.php">Clientes</a>
        <a href="veiculo.php">Veículos</a>
        <a href="reserva.php">Reservas</a>
        <a href="locacao.php">Locações</a>
    </nav>
</header>

<h1>Cadastro de Locações</h1>

<div class="voltar">
    <a href="index.html">← Voltar ao menu</a>
</div>

<form method="POST">
    <input type="hidden" name="id_locacao" value="<?php echo $id_editar; ?>">

    <label>Cliente:</label>
    <select name="id_cliente" required>
        <option value="">Selecione o cliente</option>
        <?php while ($cliente = $clientes->fetch_assoc()) { ?>
            <option value="<?php echo $cliente['id_cliente']; ?>"
                <?php if ($cliente['id_cliente'] == $id_cliente_editar) echo "selected"; ?>>
                <?php echo $cliente['nome']; ?>
            </option>
        <?php } ?>
    </select>

    <label>Veículo:</label>
    <select name="id_veiculo" required>
        <option value="">Selecione o veículo</option>
        <?php while ($veiculo = $veiculos->fetch_assoc()) { ?>
            <option value="<?php echo $veiculo['id_veiculo']; ?>"
                <?php if ($veiculo['id_veiculo'] == $id_veiculo_editar) echo "selected"; ?>>
                <?php echo $veiculo['nome_modelo'] . " - " . $veiculo['nome_marca'] . " - " . $veiculo['placa']; ?>
            </option>
        <?php } ?>
    </select>

    <label>Data de início:</label>
    <input type="date" name="data_inicio" required value="<?php echo $data_inicio_editar; ?>">

    <label>Data de fim:</label>
    <input type="date" name="data_fim" required value="<?php echo $data_fim_editar; ?>">

    <input type="number" name="valor_total" placeholder="Valor total" step="0.01" required value="<?php echo $valor_total_editar; ?>">

    <button type="submit" name="salvar">
        <?php echo ($id_editar == "") ? "Salvar" : "Atualizar"; ?>
    </button>
</form>

<h2>Locações cadastradas</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Veículo</th>
        <th>Início</th>
        <th>Fim</th>
        <th>Valor</th>
        <th>Ação</th>
    </tr>

    <?php while ($locacao = $resultado->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $locacao["id_locacao"]; ?></td>
        <td><?php echo $locacao["nome"]; ?></td>
        <td><?php echo $locacao["nome_modelo"] . " - " . $locacao["nome_marca"] . " - " . $locacao["placa"]; ?></td>
        <td><?php echo $locacao["data_inicio"]; ?></td>
        <td><?php echo $locacao["data_fim"]; ?></td>
        <td>R$ <?php echo $locacao["valor_total"]; ?></td>
        <td>
            <a class="editar" href="locacao.php?editar=<?php echo $locacao['id_locacao']; ?>">Editar</a>
            <a class="excluir" href="locacao.php?excluir=<?php echo $locacao['id_locacao']; ?>">Excluir</a>
        </td>
    </tr>
    <?php } ?>
</table>

<footer>
    <p>Trabalho M3 - Banco de Dados - Tema: Aluguel de Carros</p>
</footer>

</body>
</html>
