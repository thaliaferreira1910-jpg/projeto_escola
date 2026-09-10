<?php
include "../conexao.php";
$id = $_GET['id'];

$sql ="DELETE FROM tbl_livro WHERE
id=$id";
$conn->query($sql);
header("Location:formLivro.php");

?>