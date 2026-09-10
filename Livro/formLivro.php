<?php
/*Aqui virá o código de busca utilizando o comando SQL - Nesta Mudança faremos apenas a conexão aqui, o código ficará embaixo*/
include "../conexao.php"; 

?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário Aluno</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<style>
    body{
        font-family:Arial;
        text-align:center;
        background-color:#f2f2f2;
    }
    .container{
        background:white;
        width:80%;
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

    <a href="../menu.php">
    <img src="https://cdn-icons-png.flaticon.com/128/1144/1144760.png">   
</a>

    <h2>Cadastro de Livro</h2>

<form method="post" action="insertLivro.php" enctype="multipart/form-data">

    Titulo:<br>
    <input type="text" name="titulo" class="caixa"><br>

    Autor:<br>
    <input type="text" name="autor" class="caixa"><br>

    Ano da Publicação:<br>
    <input type="date" name="ano_publicacao" class="caixa"><br>

    Descrição:<br>
    <input type="obs" name="descricao" class="caixa"><br>

<br>
<br>
    Imagem:<br>
    <input type="file" name="imagem" accept="image/*"><br>

<!--Botõs de Enviar e Limpar-->
    <input type="submit" value="CADASTRAR" class="caixa">
    <input type="reset" value="CANCELAR" class="caixa">

</form>

<h3>Livros Cadastrados</h3>
<div class="row">

<?php

$sql = "SELECT * FROM tbl_livro";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
    $id = $row['id'];
    $img = !empty($row['imagem'])?
    $row['imagem'] : "../icones/semfoto.png";

    echo "
        <div class='col-md-3'>
        <div class='card mb-3 shadow-sm'>

        <img src= '$img' class='card-img-top' height='350' style='object-fit:cover;'>

        <div class='card-body'>
            <h5 class='card-title'>
    {$row['titulo']}
    </h5>
    
    <p class='card-text'><small>
    {$row['autor']} - {$row['ano_publicacao']}
    </small></p>

    <a href='editarLivro.php?id=$id' class='btn btn-sm btn-warning'>Editar</a>

    <a href='deleteLivro.php?id=$id'
    class='btn btn-sm btn-danger'
    onclick=\"return confirm('Deseja excluir o livro {$row['titulo']}?');\">Excluir</a>
    </div>
    </div>
    </div>
    ";


}


?>

</div>

</div>

</body>
</html>