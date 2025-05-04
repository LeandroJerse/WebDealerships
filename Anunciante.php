<?php

class Anunciante
{
  static function Create($pdo, $nome, $cpf, $email, $senhaHash, $telefone)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      INSERT INTO anunciante (nome, cpf, email, senhahash, telefone)
      VALUES (?, ?, ?, ?, ?)
      SQL
    );
    $stmt->execute([$nome, $cpf, $email, $senhaHash, $telefone]);
    return $pdo->lastInsertId();
  }

  static function GetByEmail($pdo, $email)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT id, nome, cpf, email, senhahash, telefone
      FROM anunciante
      WHERE email = ?
      SQL
    );
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_OBJ);
  }
}

?>