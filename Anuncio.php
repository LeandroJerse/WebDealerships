<?php

class Anuncio
{
  static function Create($pdo, $anunciante_id, $marca, $modelo, $ano, $cor, $quilometragem, $descricao, $valor, $estado, $cidade)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      INSERT INTO anuncio (anunciante_id, marca, modelo, ano, cor, quilometragem, descricao, valor, estado, cidade, datahora)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
      SQL
    );
    $stmt->execute([$anunciante_id, $marca, $modelo, $ano, $cor, $quilometragem, $descricao, $valor, $estado, $cidade]);
    return $pdo->lastInsertId();
  }

  static function GetLast20($pdo, $marca = null, $modelo = null, $cidade = null)
  {
    $sql = <<<SQL
    SELECT id, marca, modelo, ano, cor, quilometragem, descricao, valor, estado, cidade
    FROM anuncio
    WHERE 1=1
    SQL;
    $params = [];
    if ($marca) {
      $sql .= " AND marca = ?";
      $params[] = $marca;
    }
    if ($modelo) {
      $sql .= " AND modelo = ?";
      $params[] = $modelo;
    }
    if ($cidade) {
      $sql .= " AND cidade = ?";
      $params[] = $cidade;
    }
    $sql .= " ORDER BY datahora DESC LIMIT 20";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  static function GetById($pdo, $id)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT id, anunciante_id, marca, modelo, ano, cor, quilometragem, descricao, valor, estado, cidade
      FROM anuncio
      WHERE id = ?
      SQL
    );
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_OBJ);
  }

  static function GetByAnunciante($pdo, $anunciante_id)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT id, marca, modelo, ano, cor, quilometragem, descricao, valor, estado, cidade
      FROM anuncio
      WHERE anunciante_id = ?
      ORDER BY datahora DESC
      SQL
    );
    $stmt->execute([$anunciante_id]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  static function Remove($pdo, $id)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      DELETE FROM anuncio
      WHERE id = ?
      LIMIT 1
      SQL
    );
    $stmt->execute([$id]);
  }

  static function GetMarcas($pdo)
  {
    $stmt = $pdo->query(
      <<<SQL
      SELECT DISTINCT marca
      FROM anuncio
      ORDER BY marca
      SQL
    );
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  static function GetModelos($pdo, $marca)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT DISTINCT modelo
      FROM anuncio
      WHERE marca = ?
      ORDER BY modelo
      SQL
    );
    $stmt->execute([$marca]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  static function GetCidades($pdo, $marca, $modelo)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT DISTINCT cidade
      FROM anuncio
      WHERE marca = ? AND modelo = ?
      ORDER BY cidade
      SQL
    );
    $stmt->execute([$marca, $modelo]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }
}

?>