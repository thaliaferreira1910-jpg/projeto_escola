<?php
//Importar o arquivo de conexão, fora da pasta
include "../conexao.php";
/*Neste trecho de código está sendo criado uma variável
em PHP $ para receber através do método POST
o name do HTML*/
$cpfAluno = $_POST['cpfAluno'];
$nomeAluno = $_POST['nomeAluno'];
$dataAluno = $_POST['dataAluno'];
$emailAluno = $_POST['emailAluno'];
$estadoAluno = $_POST['estadoAluno'];

$sql = "INSERT INTO tbl_aluno(cpfAluno,nomeAluno,dataNasc,
emailAluno,estadoAluno) VALUES 
('$cpfAluno','$nomeAluno','$dataAluno',
'$emailAluno','$estadoAluno')";

if($conn->query($sql) === TRUE){
    echo 
    "<script>
    alert('Dados cadastrados com sucesso!');
    window.location.href='formAluno.php';
    </script>";
}else{
    echo 'Erro ao inserir:'.$conn->error;
}

?>