<?php
include "../conexao.php";
$id = $_GET['id'];

$sql="SELECT * FROM tbl_livro
WHERE id = $id";
$result = $conn->query($sql);
$livro = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Formulário de Alteração de Aluno</title>

<style>
body{
    font-family:Arial;
    background-color:#f2f2f2;
    text-align:center;
}
.container{
    background:white;
    width:600px;
    margin:auto;
    margin-top:30px;
    padding:20px;
    border-radius:10px;
}
.caixa{
    width:80%;
    padding:5px;
    margin:5px;
}
img{
    width:100px;
    margin-bottom:10px;
}
.grupo{
    text-align:left;
    width:80%;
    margin:auto;
}
.grupo label{
    display:block;
    margin:5px 0;
}
</style>

</head>
<body>

<div class="container">

<img src="img/logo.png">    

<h2>Edição de Aluno</h2>

<form method="post" action="updateAluno.php" enctype="multipart/form-data">

    <input type="hidden" name="id" class="caixa" value="<?= $livro['id']?>"><br>

    Titulo:<br>
    <input type="text" name="titulo" class="caixa" value="<?= $livro['titulo']?>"><br>
    
    Autor:<br>
    <input type="text" name="autor" class="caixa" value="<?= $livro['autor']?>"><br>
    
    Data de publicação:<br>
    <input type="number" name="ano_publicacao" class="caixa" value="<?= $livro['ano_publicacao']?>"><br>
    
    <br>

    Descrição:<br>
    <input type="text" name="descricao" class="caixa" value="<?= $livro['descricao']?>"><br>

    <br>

    Imagem: <br>
        <img src="<?=$livro['imagem']?>"
        width="120" height="100"> <br>

        <input type="file" name="imagem">
    

<!--Botões de Enviar e Limpar-->
<input type="submit" value="EDITAR" class="caixa">


</form>