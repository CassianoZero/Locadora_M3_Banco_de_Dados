<?php
include "conexao.php";

$id_editar = "";
$id_cliente_editar = "";
$id_veiculo_editar = "";
$data_reserva_editar = "";
$data_retirada_editar = "";
$data_devolucao_editar = "";

if (isset($_GET["editar"])) {
    $id = $_GET["editar"];

    $sql = "SELECT * FROM reserva WHERE id_reserva = $id";
    $resultado_editar = $conexao->query($sql);
    $reserva_editar = $resultado_editar->fetch_assoc();

    $id_editar = $reserva_editar["id_reserva"];
    $id_cliente_editar = $reserva_editar["id_cliente"];
    $id_veiculo_editar = $reserva_editar["id_veiculo"];
    $data_reserva_editar = $reserva_editar["data_reserva"];
    $data_retirada_editar = $reserva_editar["data_retirada"];
    $data_devolucao_editar = $reserva_editar["data_devolucao"];
}

if (isset($_POST["salvar"])) {
    $id_reserva = $_POST["id_reserva"];
    $data_reserva = $_POST["data_reserva"];
    $data_retirada = $_POST["data_retirada"];
    $data_devolucao = $_POST["data_devolucao"];
    $id_cliente = $_POST["id_cliente"];
    $id_veiculo = $_POST["id_veiculo"];

    if ($id_reserva == "") {
        $sql = "INSERT INTO reserva 
                (data_reserva, data_retirada, data_devolucao, id_cliente, id_veiculo)
                VALUES 
                ('$data_reserva', '$data_retirada', '$data_devolucao', '$id_cliente', '$id_veiculo')";
    } else {
        $sql = "UPDATE reserva 
                SET data_reserva = '$data_reserva',
                    data_retirada = '$data_retirada',
                    data_devolucao = '$data_devolucao',
                    id_cliente = '$id_cliente',
                    id_veiculo = '$id_veiculo'
                WHERE id_reserva = $id_reserva";
    }

    $conexao->query($sql);

    header("Location: reserva.php");
    exit;
}

if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];
    $conexao->query("DELETE FROM reserva WHERE id_reserva = $id");

    header("Location: reserva.php");
    exit;
}

$clientes = $conexao->query("SELECT * FROM cliente");
$veiculos = $conexao->query("SELECT * FROM veiculo");

$resultado = $conexao->query("
    SELECT reserva.*, cliente.nome, veiculo.modelo, veiculo.placa
    FROM reserva
    JOIN cliente ON reserva.id_cliente = cliente.id_cliente
    JOIN veiculo ON reserva.id_veiculo = veiculo.id_veiculo
");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Reservas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Cadastro de Reservas</h1>

<div class="voltar">
    <a href="index.html">← Voltar ao menu</a>
</div>

<form method="POST">
    <input type="hidden" name="id_reserva" value="<?php echo $id_editar; ?>">

    <label>Cliente:</label>
    <select name="id_cliente" required>
        <?php while ($cliente = $clientes->fetch_assoc()) { ?>
            <option value="<?php echo $cliente['id_cliente']; ?>"
                <?php if ($cliente['id_cliente'] == $id_cliente_editar) echo "selected"; ?>>
                <?php echo $cliente['nome']; ?>
            </option>
        <?php } ?>
    </select>

    <label>Veículo:</label>
    <select name="id_veiculo" required>
        <?php while ($veiculo = $veiculos->fetch_assoc()) { ?>
            <option value="<?php echo $veiculo['id_veiculo']; ?>"
                <?php if ($veiculo['id_veiculo'] == $id_veiculo_editar) echo "selected"; ?>>
                <?php echo $veiculo['modelo'] . " - " . $veiculo['placa']; ?>
            </option>
        <?php } ?>
    </select>

    <label>Data da reserva:</label>
    <input type="date" name="data_reserva" required value="<?php echo $data_reserva_editar; ?>">

    <label>Data de retirada:</label>
    <input type="date" name="data_retirada" required value="<?php echo $data_retirada_editar; ?>">

    <label>Data de devolução:</label>
    <input type="date" name="data_devolucao" required value="<?php echo $data_devolucao_editar; ?>">

    <button type="submit" name="salvar">
        <?php echo ($id_editar == "") ? "Salvar" : "Atualizar"; ?>
    </button>
</form>

<h2>Reservas cadastradas</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>Veículo</th>
        <th>Reserva</th>
        <th>Retirada</th>
        <th>Devolução</th>
        <th>Ação</th>
    </tr>

    <?php while ($reserva = $resultado->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $reserva["id_reserva"]; ?></td>
        <td><?php echo $reserva["nome"]; ?></td>
        <td><?php echo $reserva["modelo"] . " - " . $reserva["placa"]; ?></td>
        <td><?php echo $reserva["data_reserva"]; ?></td>
        <td><?php echo $reserva["data_retirada"]; ?></td>
        <td><?php echo $reserva["data_devolucao"]; ?></td>
        <td>
            <a class="editar" href="reserva.php?editar=<?php echo $reserva['id_reserva']; ?>">Editar</a>
            <a class="excluir" href="reserva.php?excluir=<?php echo $reserva['id_reserva']; ?>">Excluir</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>