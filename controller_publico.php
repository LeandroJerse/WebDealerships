<?php
session_start();
require "conexaoMysql.php";
require "Anunciante.php";
require "Anuncio.php";
require "Interesse.php";

$acao = $_GET['acao'] ?? '';
$pdo = mysqlConnect();

function sanitize($input)
{
  return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

header('Content-Type: application/json; charset=utf-8');

switch ($acao) {
  case "registrarAnunciante":
    $nome = sanitize($_POST["nome"] ?? "");
    $cpf = sanitize($_POST["cpf"] ?? "");
    $email = sanitize($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $telefone = sanitize($_POST["telefone"] ?? "");

    if (!$nome || !$cpf || !$email || !$senha || !$telefone) {
      echo json_encode(["error" => "Todos os campos são obrigatórios"]);
      exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    try {
      $id = Anunciante::Create($pdo, $nome, $cpf, $email, $senhaHash, $telefone);
      echo json_encode(["success" => "Anunciante registrado com sucesso"]);
    } catch (Exception $e) {
      echo json_encode(["error" => "Erro ao registrar: " . $e->getMessage()]);
    }
    break;

  case "login":
    $email = sanitize($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    try {
      $anunciante = Anunciante::GetByEmail($pdo, $email);
      if ($anunciante && password_verify($senha, $anunciante->senhahash)) {
        $_SESSION['anunciante_id'] = $anunciante->id;
        echo json_encode(["success" => "Login bem-sucedido"]);
      } else {
        echo json_encode(["error" => "Email ou senha incorretos"]);
      }
    } catch (Exception $e) {
      echo json_encode(["error" => "Erro ao fazer login: " . $e->getMessage()]);
    }
    break;

  case "listarAnuncios":
    $marca = sanitize($_POST["marca"] ?? "");
    $modelo = sanitize($_POST["modelo"] ?? "");
    $cidade = sanitize($_POST["cidade"] ?? "");

    try {
      $anuncios = Anuncio::GetLast20($pdo, $marca ?: null, $modelo ?: null, $cidade ?: null);
      $result = [];
      foreach ($anuncios as $anuncio) {
        $fotos = Foto::GetByAnuncio($pdo, $anuncio->id);
        $result[] = [
          "id" => $anuncio->id,
          "marca" => $anuncio->marca,
          "modelo" => $anuncio->modelo,
          "ano" => $anuncio->ano,
          "cidade" => $anuncio->cidade,
          "valor" => $anuncio->valor,
          "foto" => $fotos[0]->nome_arquivo ?? "assets/img/default.png"
        ];
      }
      echo json_encode($result);
    } catch (Exception $e) {
      echo json_encode(["error" => "Erro ao listar anúncios: " . $e->getMessage()]);
    }
    break;

  case "listarMarcas":
    try {
      $marcas = Anuncio::GetMarcas($pdo);
      echo json_encode($marcas);
    } catch (Exception $e) {
      echo json_encode(["error" => "Erro ao listar marcas: " . $e->getMessage()]);
    }
    break;

  case "listarModelos":
    $marca = sanitize($_POST["marca"] ?? "");
    try {
      $modelos = Anuncio::GetModelos($pdo, $marca);
      echo json_encode($modelos);
    } catch (Exception $e) {
      echo json_encode(["error" => "Erro ao listar modelos: " . $e->getMessage()]);
    }
    break;

  case "listarCidades":
    $marca = sanitize($_POST["marca"] ?? "");
    $modelo = sanitize($_POST["modelo"] ?? "");
    try {
      $cidades = Anuncio::GetCidades($pdo, $marca, $modelo);
      echo json_encode($cidades);
    } catch (Exception $e) {
      echo json_encode(["error" => "Erro ao listar cidades: " . $e->getMessage()]);
    }
    break;

  case "registrarInteresse":
    $anuncio_id = sanitize($_POST["anuncio_id"] ?? "");
    $nome = sanitize($_POST["nome"] ?? "");
    $telefone = sanitize($_POST["telefone"] ?? "");
    $mensagem = sanitize($_POST["mensagem"] ?? "");

    if (!$anuncio_id || !$nome || !$telefone || !$mensagem) {
      echo json_encode(["error" => "Todos os campos são obrigatórios"]);
      exit;
    }

    try {
      Interesse::Create($pdo, $anuncio_id, $nome, $telefone, $mensagem);
      echo json_encode(["success" => "Interesse registrado com sucesso"]);
    } catch (Exception $e) {
      echo json_encode(["error" => "Erro ao registrar interesse: " . $e->getMessage()]);
    }
    break;

  default:
    echo json_encode(["error" => "Ação não disponível"]);
}
?>