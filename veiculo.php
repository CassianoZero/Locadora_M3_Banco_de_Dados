<?php
include "conexao.php";

$id_editar = "";
$placa_editar = "";
$modelo_editar = "";
$marca_editar = "";
$ano_editar = "";
$categoria_editar = "";
$status_editar = "";

if (isset($_GET["editar"])) {
    $id = $_GET["editar"];

    $sql = "SELECT * FROM veiculo WHERE id_veiculo = $id";
    $resultado_editar = $conexao->query($sql);
    $veiculo_editar = $resultado_editar->fetch_assoc();

    $id_editar = $veiculo_editar["id_veiculo"];
    $placa_editar = $veiculo_editar["placa"];
    $modelo_editar = $veiculo_editar["modelo"];
    $marca_editar = $veiculo_editar["marca"];
    $ano_editar = $veiculo_editar["ano"];
    $categoria_editar = $veiculo_editar["categoria"];
    $status_editar = $veiculo_editar["status"];
}

if (isset($_POST["salvar"])) {
    $id_veiculo = $_POST["id_veiculo"];
    $placa = $_POST["placa"];
    $modelo = $_POST["modelo"];
    $marca = $_POST["marca"];
    $ano = $_POST["ano"];
    $categoria = $_POST["categoria"];
    $status = $_POST["status"];

    if ($id_veiculo == "") {
        $sql = "INSERT INTO veiculo (placa, modelo, marca, ano, categoria, status)
                VALUES ('$placa', '$modelo', '$marca', '$ano', '$categoria', '$status')";
    } else {
        $sql = "UPDATE veiculo 
                SET placa = '$placa',
                    modelo = '$modelo',
                    marca = '$marca',
                    ano = '$ano',
                    categoria = '$categoria',
                    status = '$status'
                WHERE id_veiculo = $id_veiculo";
    }

    $conexao->query($sql);

    header("Location: veiculo.php");
    exit;
}

if (isset($_GET["excluir"])) {
    $id = $_GET["excluir"];

    $verifica_reserva = $conexao->query("SELECT * FROM reserva WHERE id_veiculo = $id");
    $verifica_locacao = $conexao->query("SELECT * FROM locacao WHERE id_veiculo = $id");

    if ($verifica_reserva->num_rows > 0 || $verifica_locacao->num_rows > 0) {
        echo "<script>
                alert('Não é possível excluir este veículo, pois ele possui reserva ou locação cadastrada.');
                window.location.href = 'veiculo.php';
              </script>";
        exit;
    }

    $conexao->query("DELETE FROM veiculo WHERE id_veiculo = $id");

    header("Location: veiculo.php");
    exit;
}

$resultado = $conexao->query("SELECT * FROM veiculo");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Veículos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Cadastro de Veículos</h1>

<div class="voltar">
    <a href="index.html">← Voltar ao menu</a>
</div>

<form method="POST">
    <input type="hidden" name="id_veiculo" value="<?php echo $id_editar; ?>">

    <input type="text" name="placa" placeholder="Placa" required value="<?php echo $placa_editar; ?>">
    <input type="text" name="modelo" placeholder="Modelo" required value="<?php echo $modelo_editar; ?>">
    <input type="text" name="marca" placeholder="Marca" required value="<?php echo $marca_editar; ?>">
    <input type="number" name="ano" placeholder="Ano" value="<?php echo $ano_editar; ?>">
    <input type="text" name="categoria" placeholder="Categoria" value="<?php echo $categoria_editar; ?>">

    <select name="status">
        <option value="Disponível" <?php if($status_editar == "Disponível") echo "selected"; ?>>Disponível</option>
        <option value="Reservado" <?php if($status_editar == "Reservado") echo "selected"; ?>>Reservado</option>
        <option value="Alugado" <?php if($status_editar == "Alugado") echo "selected"; ?>>Alugado</option>
        <option value="Manutenção" <?php if($status_editar == "Manutenção") echo "selected"; ?>>Manutenção</option>
    </select>

    <button type="submit" name="salvar">
        <?php echo ($id_editar == "") ? "Salvar" : "Atualizar"; ?>
    </button>
</form>

<h2>Veículos cadastrados</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Placa</th>
        <th>Modelo</th>
        <th>Marca</th>
        <th>Ano</th>
        <th>Categoria</th>
        <th>Status</th>
        <th>Ação</th>
    </tr>

    <?php while ($veiculo = $resultado->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $veiculo["id_veiculo"]; ?></td>
        <td><?php echo $veiculo["placa"]; ?></td>
        <td><?php echo $veiculo["modelo"]; ?></td>
        <td><?php echo $veiculo["marca"]; ?></td>
        <td><?php echo $veiculo["ano"]; ?></td>
        <td><?php echo $veiculo["categoria"]; ?></td>
        <td><?php echo $veiculo["status"]; ?></td>
        <td>
            <a class="editar" href="veiculo.php?editar=<?php echo $veiculo['id_veiculo']; ?>">Editar</a>
            <a class="excluir" href="veiculo.php?excluir=<?php echo $veiculo['id_veiculo']; ?>">Excluir</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>