<?php
session_start();
require "conexaoMysql.php";
require "Anuncio.php";
require "Foto.php";
require "Interesse.php";

if (!isset($_SESSION['anunciante_id'])) {
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(["error" => "Acesso não autorizado"]);
  exit;
}

$acao = $_GET['acao'] ?? '';
$pdo = mysqlConnect();

function sanitize($input)
{
  return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

header('Content-Type: application/json; charset=utf-8');

switch ($acao) {
  case "criarAnuncio":
    $marca = sanitize($_POST["marca"] ?? "");
    $modelo = sanitize($_POST["modelo"] ?? "");
    $ano = sanitize($_POST["ano"] ?? "");
    $cor = sanitize($_POST["cor"] ?? "");
    $quilometragem = sanitize($_POST["quilometragem"] ?? "");
    $descricao = sanitize($_POST["descricao"] ?? "");
    $valor = sanitize($_POST["valor"] ?? "");
    $estado = sanitize($_POST["estado"] ?? "");
    $cidade = sanitize($_POST["cidade"] ?? "");

    if (!$marca || !$modelo || !$ano || !$cor || !$quilometragem || !$descricao || !$valor || !$estado || !$cidade) {
      echo json_encode(["error" => "Todos os campos são obrigatórios"]);
      exit;
    }

    try {
      $pdo->beginTransaction();
      $anuncio_id = Anuncio::Create($pdo, $_SESSION['anunciante_id'], $marca, $modelo, $ano, $cor, $quilometragem, $descricao, $valor, $estado, $cidade);

      $uploadDir = "uploads/";
      if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

      $fotos = $_FILES["fotos"] ?? [];
      if (count($fotos['name']) < 3) {
        $pdo->rollBack();
        echo json_encode(["error" => "Mínimo de 3 fotos requerido"]);
        exit;
      }

      foreach ($fotos['name'] as $index => $name) {
        if ($fotos['error'][$index] === UPLOAD_ERR_OK) {
          $ext = pathinfo($name, PATHINFO_EXTENSION);
          if (!in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
            $pdo->rollBack();
            echo json_encode(["error" => "Apenas imagens JPG/PNG são permitidas"]);
            exit;
          }
          $newName = uniqid() . "." . $ext;
          $destPath = $uploadDir . $newName;
          if (move_uploaded_file($fotos['tmp_name'][$index], $destPath)) {
            Foto::Create($pdo, $anuncio_id, $newName);
          } else {
            $pdo->rollBack();
            echo json_encode(["error" => "Erro ao salvar foto"]);
            exit;
          }
        }
      }

      $pdo->commit();
      echo json_encode(["success" => "Anúncio criado com sucesso"]);
    } catch (Exception $e) {
      $pdo->rollBack();
      echo json_encode(["error" => "Erro ao criar anúncio: " . $e->getMessage()]);
    }
    break;

  case "listarMeusAnuncios":
    try {
      $anuncios = Anuncio::GetByAnunciante($pdo, $_SESSION['anunciante_id']);
      $result = [];
      foreach ($anuncios as $anuncio) {
        $fotos = Foto::GetByAnuncio($pdo, $anuncio->id);
        $result[] = [
          "id" => $anuncio->id,
          "marca" => $anuncio->marca,
          "modelo" => $anuncio->modelo,
          "ano" => $anuncio->ano,
          "foto" => $fotos[0]->nome_arquivo ?? "assets/img/default.png"
        ];
      }
      echo json_encode($result);
    } catch (Exception $e) {
      echo json_encode(["error" => "Erro ao listar anúncios: " . $e->getMessage()]);
    }
    break;

  case "excluirAnuncio":
    $id = sanitize($_POST["id"] ?? "");
    try {
      Anuncio::Remove($pdo, $id);
      echo json_encode(["success" => "Anúncio excluído com sucesso"]);
    } catch (Exception $e) {
      echo json_encode(["error" => "Erro ao excluir anúncio: " . $e->getMessage()]);
    }
    break;

  case "detalhesAnuncio":
    $id = sanitize($_POST["id"] ?? "");
    try {
      $anuncio = Anuncio::GetById($pdo, $id);
      $fotos = Foto::GetByAnuncio($pdo, $id);
      if ($anuncio) {
        echo json_encode([
          "id" => $anuncio->id,
          "marca" => $anuncio->marca,
          "modelo" => $anuncio->modelo,
          "ano" => $anuncio->ano,
          "cor" => $anuncio->cor,
          "quilometragem" => $anuncio->quilometragem,
          "descricao" => $anuncio->descricao,
          "valor" => $anuncio->valor,
          "estado" => $anuncio->estado,
          "cidade" => $anuncio->cidade,
          "fotos" => array_map(fn($foto) => $foto->nome_arquivo, $fotos)
        ]);
      } else {
        echo json_encode(["error" => "Anúncio não encontrado"]);
      }
    } catch (Exception $e) {
      echo json_encode(["error" => "Erro ao obter detalhes: " . $e->getMessage()]);
    }
    break;

  case "listarInteresses":
    $anuncio_id = sanitize($_POST["anuncio_id"] ?? "");
    try {
      $interesses = Interesse::GetByAnuncio($pdo, $anuncio_id);
      echo json_encode($interesses);
    } catch (Exception $e) {
      echo json_encode(["error" => "Erro ao listar interesses: " . $e->getMessage()]);
    }
    break;

  case "excluirInteresse":
    $id = sanitize($_POST["id"] ?? "");
    try {
      Interesse::Remove($pdo, $id);
      echo json_encode(["success" => "Interesse excluído com sucesso"]);
    } catch (Exception $e) {
      echo json_encode(["error" => "Erro ao excluir interesse: " . $e->getMessage()]);
    }
    break;

  case "logoff":
    session_destroy();
    echo json_encode(["success" => "Logoff bem-sucedido"]);
    break;

  default:
    echo json_encode(["error" => "Ação não disponível"]);
}
?>