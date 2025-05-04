<?php

class Foto
{
  static function Create($pdo, $anuncio_id, $nome_arquivo)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      INSERT INTO foto (anuncio_id, nome_arquivo)
      VALUES (?, ?)
      SQL
    );
    $stmt->execute([$anuncio_id, $nome_arquivo]);
    return $pdo->lastInsertId();
  }

  static function GetByAnuncio($pdo, $anuncio_id)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT id, nome_arquivo
      FROM foto
      WHERE anuncio_id = ?
      SQL
    );
    $stmt->execute([$anuncio_id]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}

?>