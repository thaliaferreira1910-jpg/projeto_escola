<?php
// ==========================================================
// ETAPA 1 — INCLUSÃO DOS ARQUIVOS NECESSÁRIOS
// ==========================================================
// Incluímos o arquivo que realiza a conexão com o banco de dados.
// A conexão será utilizada por meio da variável $conn.
include "../conexao.php";

// ==========================================================
// ETAPA 2 — VERIFICAÇÃO DO MÉTODO DE ENVIO
// ==========================================================
// Verificamos se os dados chegaram utilizando o método POST.
// O formulário de cadastro deve estar configurado com method="POST".
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

// Se alguém tentar acessar este arquivo diretamente pelo navegador,
// o sistema redirecionará o usuário para o formulário de cadastro.
header('Location: cadastroGaleria.php');
// O comando exit encerra imediatamente a execução do código.
exit;
}

// ==========================================================
// ETAPA 3 — RECEBIMENTO DOS DADOS DO FORMULÁRIO
// ==========================================================
// Recebemos o título da galeria enviado pelo formulário.
//
// O operador ?? define um valor padrão vazio caso o campo não exista.
// A função trim() remove espaços desnecessários no início e no final.
$tituloGaleria = trim($_POST['titulo_galeria'] ?? '');
// Recebemos a descrição da galeria.
$descricaoGaleria = trim($_POST['descricao_galeria'] ?? '');
// Recebemos a legenda que será cadastrada para as imagens.
$legenda = trim($_POST['legenda'] ?? '');
// Os arquivos enviados por campos do tipo file ficam armazenados
// no vetor especial $_FILES.
//
// O nome "imagens" deve ser o mesmo utilizado no formulário:
// <input type="file" name="imagens[]" multiple>
$arquivos = $_FILES['imagens'] ?? null;

// ==========================================================
// ETAPA 4 — VALIDAÇÃO DOS CAMPOS OBRIGATÓRIOS
// ==========================================================
// Nesta condição, verificamos três situações:
//
// 1. Se o título da galeria está vazio;
// 2. Se nenhuma imagem foi enviada;
// 3. Se os nomes das imagens não estão organizados em um vetor.
//
// A função is_array() confirma se várias imagens foram enviadas

// por meio de um campo com o atributo multiple.
if (
$tituloGaleria === '' ||
!$arquivos ||
!is_array($arquivos['name'] ?? null)
) {
// Se algum dado obrigatório estiver incorreto,
// voltamos ao formulário informando que ocorreu um erro.
header('Location: cadastroGaleria.php?galeria=erro');
exit;
}

// ==========================================================
// ETAPA 5 — CONFIGURAÇÃO DO UPLOAD DAS IMAGENS
// ==========================================================
// Definimos o caminho físico da pasta onde as imagens serão salvas.
//
// A constante __DIR__ representa a pasta em que este arquivo PHP
// está localizado.
$pastaFisica = __DIR__ . '/../uploads/galeria/';
// Definimos o caminho que será gravado no banco de dados.
//
// Não armazenamos a imagem diretamente no banco.
// Armazenamos somente o endereço do arquivo.
$caminhoBanco = '../uploads/galeria/';
// Criamos uma lista com os tipos de imagens permitidos.
//
// À esquerda está o tipo MIME real do arquivo.
// À direita está a extensão que será utilizada no nome da imagem.
$tiposPermitidos = [
'image/jpeg' => 'jpg',
'image/png' => 'png',
'image/webp' => 'webp'
];
// Definimos o tamanho máximo permitido para cada imagem.
//
// O cálculo abaixo corresponde a 5 megabytes:

// 5 × 1024 kilobytes × 1024 bytes.
$limiteBytes = 5 * 1024 * 1024;
// Criamos um objeto finfo para descobrir o tipo real de cada arquivo.
// Essa verificação é mais segura do que analisar somente a extensão.
$finfo = new finfo(FILEINFO_MIME_TYPE);
// Criamos um vetor vazio para guardar apenas as imagens
// que passarem por todas as validações.
$imagensValidas = [];

// ==========================================================
// ETAPA 6 — VALIDAÇÃO INDIVIDUAL DAS IMAGENS
// ==========================================================
// Percorremos todos os arquivos enviados pelo formulário.
//
// A variável $indice representa a posição da imagem no vetor.
// A variável $nomeOriginal contém o nome original do arquivo.
foreach ($arquivos['name'] as $indice => $nomeOriginal) {
// Recuperamos o código de erro relacionado ao upload.
$erro = $arquivos['error'][$indice];
// Recuperamos o tamanho do arquivo em bytes.
$tamanho = $arquivos['size'][$indice];
// Recuperamos o endereço temporário criado pelo servidor.
$temporario = $arquivos['tmp_name'][$indice];
// Verificamos se:
//
// 1. O upload apresentou algum erro;
// 2. O arquivo está vazio;
// 3. O arquivo ultrapassa o limite de 5 MB.
if (
$erro !== UPLOAD_ERR_OK ||
$tamanho <= 0 ||
$tamanho > $limiteBytes
) {
// O comando continue ignora o arquivo inválido

// e passa para a próxima imagem.
continue;
}
// Identificamos o tipo MIME verdadeiro do arquivo temporário.
$mime = $finfo->file($temporario);
// Verificamos se o tipo encontrado está na lista permitida.
if (isset($tiposPermitidos[$mime])) {
// Se a imagem for válida, guardamos seu endereço temporário
// e a extensão correspondente.
$imagensValidas[] = [
'temporario' => $temporario,
'extensao' => $tiposPermitidos[$mime]
];
}
}

// ==========================================================
// ETAPA 7 — VERIFICAÇÃO DA PASTA E DAS IMAGENS VÁLIDAS
// ==========================================================
// Verificamos se pelo menos uma imagem válida foi encontrada.
//
// Também verificamos se a pasta de destino existe.
// Caso não exista, tentamos criá-la com mkdir().
//
// O parâmetro 0755 define as permissões da pasta.
// O valor true permite criar também as pastas anteriores,
// caso elas ainda não existam.
if (
count($imagensValidas) === 0 ||
(!is_dir($pastaFisica) && !mkdir($pastaFisica, 0755, true))
) {
// Se nenhuma imagem for válida ou se a pasta não puder
// ser criada, retornamos ao formulário com uma mensagem de erro.
header('Location: cadastroGaleria.php?galeria=erro');
exit;
}

// ==========================================================
// ETAPA 8 — PREPARAÇÃO DA TRANSAÇÃO
// ==========================================================
// Este vetor guardará os caminhos das imagens que forem movidas.
//
// Caso aconteça algum erro durante o cadastro, utilizaremos este
// vetor para excluir os arquivos já enviados.
$arquivosMovidos = [];
// Iniciamos uma transação no banco de dados.
//
// A transação garante que todos os registros sejam cadastrados
// corretamente. Se ocorrer um erro, todas as alterações serão desfeitas.
$conn->begin_transaction();

// ==========================================================
// ETAPA 9 — CADASTRO DA GALERIA
// ==========================================================
try {
// Preparamos o comando SQL que cadastrará o título
// e a descrição na tabela galeria.
//
// Os sinais de interrogação são parâmetros que receberão
// os valores posteriormente.
$stmtGaleria = $conn->prepare(
"INSERT INTO galeria (titulo, descricao) VALUES (?, ?)"
);
// Relacionamos os valores às interrogações da consulta.
//
// A letra "s" significa string.
// Como temos dois textos, utilizamos "ss".
$stmtGaleria->bind_param(
"ss",
$tituloGaleria,
$descricaoGaleria
);
// Executamos o comando de cadastro da galeria.

$stmtGaleria->execute();
// Recuperamos o ID gerado automaticamente para a nova galeria.
//
// Esse ID será utilizado para relacionar as imagens
// com a galeria que acabou de ser cadastrada.
$galeriaId = $conn->insert_id;
// Encerramos o primeiro comando preparado.
$stmtGaleria->close();

// ======================================================
// ETAPA 10 — PREPARAÇÃO DO CADASTRO DAS FOTOS
// ======================================================
// Preparamos o comando SQL para cadastrar as imagens.
//
// Cada registro receberá:
// - O ID da galeria;
// - O caminho da imagem;
// - A legenda.
$stmtFoto = $conn->prepare(
"INSERT INTO fotos_livros
(galeria_id, caminho_imagem, legenda)
VALUES (?, ?, ?)"
);

// ======================================================
// ETAPA 11 — SALVAMENTO DE CADA IMAGEM
// ======================================================
// Percorremos todas as imagens que passaram pela validação.
foreach ($imagensValidas as $imagem) {
// Criamos um nome aleatório e seguro para a imagem.

//
// random_bytes(16) cria uma sequência aleatória.
// bin2hex() transforma essa sequência em caracteres.
//
// Isso evita arquivos com nomes repetidos e reduz problemas
// com espaços ou caracteres especiais.

$nomeSeguro =

bin2hex(random_bytes(16)) .
'.' .
$imagem['extensao'];
// Montamos o caminho físico completo onde a imagem será salva.
$destino = $pastaFisica . $nomeSeguro;
// Montamos o caminho que será armazenado no banco de dados.
$caminhoImagem = $caminhoBanco . $nomeSeguro;
// Movemos a imagem da pasta temporária do servidor
// para a pasta definitiva da galeria.
if (!move_uploaded_file($imagem['temporario'], $destino)) {
// Se a imagem não puder ser movida, lançamos uma exceção.

//
// A execução será direcionada para o bloco catch.

throw new Exception(
'Falha ao mover uma das imagens.'
);
}
// Guardamos o caminho do arquivo movido.

//
// Se ocorrer um erro posteriormente, poderemos excluir
// esta imagem para evitar arquivos sem registro no banco.

$arquivosMovidos[] = $destino;
// Relacionamos os valores aos parâmetros da consulta.

//
// "iss" significa:
// i = número inteiro, correspondente ao ID da galeria;
// s = texto, correspondente ao caminho da imagem;
// s = texto, correspondente à legenda.

$stmtFoto->bind_param(
"iss",
$galeriaId,
$caminhoImagem,
$legenda
);
// Executamos o cadastro da imagem no banco de dados.
$stmtFoto->execute();

}
// Encerramos o comando preparado das fotos.
$stmtFoto->close();

// ======================================================
// ETAPA 12 — CONFIRMAÇÃO DA TRANSAÇÃO
// ======================================================
// Se todos os comandos foram executados sem erros,
// confirmamos definitivamente as alterações no banco.
$conn->commit();
// Definimos o status de sucesso.
$status = 'sucesso';
} catch (Throwable $erro) {
// ======================================================
// ETAPA 13 — TRATAMENTO DE ERROS
// ======================================================
// Se qualquer erro acontecer dentro do bloco try,
// desfazemos todas as alterações realizadas no banco.
$conn->rollback();
// Percorremos os arquivos que já haviam sido movidos.
foreach ($arquivosMovidos as $arquivo) {
// Verificamos se o arquivo realmente existe.
if (is_file($arquivo)) {
// Excluímos o arquivo para que não fique uma imagem
// sem registro correspondente no banco de dados.
unlink($arquivo);
}
}
// Definimos o status de erro.
$status = 'erro';
}

// ==========================================================
// ETAPA 14 — FINALIZAÇÃO
// ==========================================================
// Encerramos a conexão com o banco de dados.
$conn->close();
// Redirecionamos o usuário para o formulário.
//
// O resultado será enviado pela URL:
// cadastroGaleria.php?galeria=sucesso
// ou
// cadastroGaleria.php?galeria=erro
header("Location: cadastroGaleria.php?galeria=$status");
// Encerramos definitivamente a execução do programa.
exit;