<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Formulário Completo</title>

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

<img src="https://cdn-icons-png.flaticon.com/128/13671/13671693.png">    

<h2>Cadastro de Aluno</h2>

<form method="post" action="insertAluno.php" enctype="multipart/form-data">
    CPF:<br>
    <input type="number" name="cpfAluno" class="caixa"><br>
      
    Nome:<br>
    <input type="text" name="nomeAluno" class="caixa"><br>
    
    Data de Nascimento:<br>
    <input type="date" name="dataAluno" class="caixa"><br>
    
    Email:<br>
    <input type="email" name="emailAluno" class="caixa"><br>
    
    <br>  
    Estado:<br>
    <select name="estadoAluno" class="caixa">
        <option>São Paulo</option>
        <option>Rio de Janeiro</option>
        <option>Minas Gerais</option>
        <option>Espírito Santo</option>
    </select>
    <br>
    <br>


<!--Botões de Enviar e Limpar-->
<input type="submit" value="CADASTRAR" class="caixa">
<input type="reset" value="CANCELAR" class="caixa">

</form>
</div>

</body>
</html>