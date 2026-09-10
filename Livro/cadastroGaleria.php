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
body { background-color: #f8f9fa; }
.header { background-color: #0d6efd; color: white; padding: 20px 0; }
.header img { height: 50px; }
.card-formulario { padding: 25px; margin-bottom: 40px; }
footer { background-color: #0d6efd; color: white; padding: 15px 0; text-align:

center; }
</style>

</head>
<body>
<header class="header mb-4">
<div class="container d-flex justify-content-between align-items-center">
<img src="../icones/logo.png" alt="Logo">
<a href="../menu.php" class="btn btn-light">Voltar ao Menu</a>
</div>
</header>
<main class="container">
<div class="card card-formulario shadow-sm">
<h2 class="mb-3">Cadastro da Galeria de Livros</h2>

<p class="text-muted">
Cadastre um grupo de imagens para aparecer no carrossel da página da

biblioteca.
</p>
<?php if (isset($_GET['galeria']) && $_GET['galeria'] === 'sucesso'): ?>
<div class="alert alert-success">Galeria e imagens cadastradas com

sucesso!</div>

<?php elseif (isset($_GET['galeria']) && $_GET['galeria'] === 'erro'): ?>
<div class="alert alert-danger">Não foi possível cadastrar a galeria.

Confira os arquivos.</div>
<?php endif; ?>

<?php
   
?>

<form action="insertGaleria.php" method="POST" enctype="multipart/form-data">
<div class="mb-3">
<label for="titulo_galeria" class="form-label">
    Titulo da galeria:</label>
    <input type="text" name="titulo_galeria" id="titulo_galeria"
    class="form-control" maxlength="100" placeholder="Ex. : Novidades da Biblioteca"
    required>
</div>

<div class="mb-3">
    <label for="descricao_galeria" class="form-label">
        Descrição (opcional)
    </label>
    <textarea name="descricao_galeria" id="descricao_galeria"
    class="form_control" maxlength="255" rows="3"
    placeholder="Descreva brevemente esta galeria">
    </textarea>
    </div>

    <div>
        <label form="imagens" class="form-label">
            selecionar imagens:
        </label>
        <input type="file" name="imagens[]"
        class="form-control"
        accept="image/jpeg,image/png,image/webp"
        multiple required>
        <div class="form-text">
            Selecione uma ou várias imagens em JPG, PNG ou WEBP
    </div>

    <div class="mb-3">
        <label for="legenda" class="form-label">
            Legenda das imagens(opcional):
    </label>
    <input type="text" name="legenda" id="legenda"
    class="form-control" maxlength="150"
    placeholder="Ex. :Livros recém adquiridos">
    </div>

    <button type="submit" class="btn-primary">
        Cadastrar Galeria 
    </button>


 
</form>


