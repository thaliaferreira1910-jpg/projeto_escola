<?php
include "conexao.php";

$id=isset($_GET['id']) ? (int) $_GET['id']:0;

$sql="SELECT * FROM tbl_livro WHERE id=$id";
$result=$conn->query($sql);
$livro=$result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
<body class="container mt-4">
    <div class="card shadow-sm">
        <div class="col-md-4">
        <?php $img = !empty($livro['imagem'] ? $livro['imagem']) : "../icones/semfoto.png"; ?>



    
</body>
</html>