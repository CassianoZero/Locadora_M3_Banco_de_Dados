<?php
include "conexao.php";

$id_editar = "";
$placa_editar = "";
$ano_editar = "";
$id_modelo_editar = "";
$id_categoria_editar = "";
$id_status_editar = "";

if (isset($_GET["editar"])) {
    $id = $_GET["editar"];

    $sql = "SELECT * FROM veiculo WHERE id_veiculo = $id";
    $resultado_editar = $conexao->query($sql);
    $veiculo_editar = $resultado_editar->fetch_assoc();

    $id_editar = $veiculo_editar["id_veiculo"];
    $placa_editar = $veiculo_editar["placa"];
    $ano_editar = $veiculo_editar["ano"];
    $id_modelo_editar = $veiculo_editar["id_modelo"];
    $id_categoria_editar = $veiculo_editar["id_categoria"];
    $id_status_editar = $veiculo_editar["id_status"];
}

if (isset($_POST["salvar"])) {
    $id_veiculo = $_POST["id_veiculo"];
    $placa = $_POST["placa"];
    $ano = $_POST["ano"];
    $id_modelo = $_POST["id_modelo"];
    $id_categoria = $_POST["id_categoria"];
    $id_status = $_POST["id_status"];

    if ($id_veiculo == "") {
        $sql = "INSERT INTO veiculo (placa, ano, id_modelo, id_categoria, id_status)
                VALUES ('$placa', '$ano', '$id_modelo', '$id_categoria', '$id_status')";
    } else {
        $sql = "UPDATE veiculo
                SET placa = '$placa',
                    ano = '$ano',
                    id_modelo = '$id_modelo',
                    id_categoria = '$id_categoria',
                    id_status = '$id_status'
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

$modelos = $conexao->query("
    SELECT modelo.id_modelo, modelo.nome_modelo, marca.nome_marca
    FROM modelo
    JOIN marca ON modelo.id_marca = marca.id_marca
");

$categorias = $conexao->query("SELECT * FROM categoria");
$status = $conexao->query("SELECT * FROM status_veiculo");

$resultado = $conexao->query("
    SELECT veiculo.*, modelo.nome_modelo, marca.nome_marca,
           categoria.nome_categoria, status_veiculo.descricao_status
    FROM veiculo
    JOIN modelo ON veiculo.id_modelo = modelo.id_modelo
    JOIN marca ON modelo.id_marca = marca.id_marca
    JOIN categoria ON veiculo.id_categoria = categoria.id_categoria
    JOIN status_veiculo ON veiculo.id_status = status_veiculo.id_status
");
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
    <input type="number" name="ano" placeholder="Ano" value="<?php echo $ano_editar; ?>">

    <select name="id_modelo" required>
        <option value="">Selecione o modelo</option>
        <?php while ($modelo = $modelos->fetch_assoc()) { ?>
            <option value="<?php echo $modelo['id_modelo']; ?>"
                <?php if ($modelo['id_modelo'] == $id_modelo_editar) echo "selected"; ?>>
                <?php echo $modelo['nome_modelo'] . " - " . $modelo['nome_marca']; ?>
            </option>
        <?php } ?>
    </select>

    <select name="id_categoria" required>
        <option value="">Selecione a categoria</option>
        <?php while ($categoria = $categorias->fetch_assoc()) { ?>
            <option value="<?php echo $categoria['id_categoria']; ?>"
                <?php if ($categoria['id_categoria'] == $id_categoria_editar) echo "selected"; ?>>
                <?php echo $categoria['nome_categoria']; ?>
            </option>
        <?php } ?>
    </select>

    <select name="id_status" required>
        <option value="">Selecione o status</option>
        <?php while ($st = $status->fetch_assoc()) { ?>
            <option value="<?php echo $st['id_status']; ?>"
                <?php if ($st['id_status'] == $id_status_editar) echo "selected"; ?>>
                <?php echo $st['descricao_status']; ?>
            </option>
        <?php } ?>
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
        <td><?php echo $veiculo["nome_modelo"]; ?></td>
        <td><?php echo $veiculo["nome_marca"]; ?></td>
        <td><?php echo $veiculo["ano"]; ?></td>
        <td><?php echo $veiculo["nome_categoria"]; ?></td>
        <td><?php echo $veiculo["descricao_status"]; ?></td>
        <td>
            <a class="editar" href="veiculo.php?editar=<?php echo $veiculo['id_veiculo']; ?>">Editar</a>
            <a class="excluir" href="veiculo.php?excluir=<?php echo $veiculo['id_veiculo']; ?>">Excluir</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
