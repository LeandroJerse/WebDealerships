<?php

class Interesse
{
  static function Create($pdo, $anuncio_id, $nome, $telefone, $mensagem)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      INSERT INTO interesse (anuncio_id, nome, telefone, mensagem)
      VALUES (?, ?, ?, ?)
      SQL
    );
    $stmt->execute([$anuncio_id, $nome, $telefone, $mensagem]);
    return $pdo->lastInsertId();
  }

  static function GetByAnuncio($pdo, $anuncio_id)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT id, nome, telefone, mensagem
      FROM interesse
      WHERE anuncio_id = ?
      SQL
    );
    $stmt->execute([$anuncio_id]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  static function Remove($pdo, $id)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      DELETE FROM interesse
      WHERE id = ?
      LIMIT 1
      SQL
    );
    $stmt->execute([$id]);
  }
}

?>