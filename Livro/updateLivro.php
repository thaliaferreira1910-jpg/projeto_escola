<?php
//Importar o arquivo de conexão, fora da pasta
include "../conexao.php";

/*Neste trecho do código está sendo criado uma variável em PHP $ 
para receber através do método POST o name HTML*/
$titulo = $_POST['titulo'];
$autor = $_POST['autor'];
$ano_publicacao = $_POST['ano_publicacao'];
$descricao = $_POST['descricao'];

// Upload da imagem
$imagem = "";
if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0){
    $pasta = "../uploads/";
    if(!is_dir($pasta)){
        mkdir($pasta, 0777, true);
    }
    $nomeArquivo = time() . "_" . basename($_FILES["imagem"]["name"]);
    $caminho = $pasta . $nomeArquivo;
    if(move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)){
        $imagem = $caminho;
    }
}

$sql="UPDATE tbl_livro set titulo='$titulo',
autor="$autor", ano_publicado='$ano_publicado',
descricao='$descricao', imagem='$imagem' WHERE id='$id'
";





$sql = "INSERT INTO tbl_livro(titulo,autor,ano_publicacao,descricao,imagem) 
VALUES ('$titulo','$autor','$ano_publicacao','$descricao','$imagem')";

if($conn->query($sql) === TRUE){
    echo "<script>
    alert('Dados Alterados com Sucesso!');
    window.location.href='formLivro.php';
    </script>";
}else{
    echo 'Erro ao inserir:'.$conn->error;
}

?>