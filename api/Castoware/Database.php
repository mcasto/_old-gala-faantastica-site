<?php

namespace Castoware;

use Cocur\Chain\Chain;
use Dibi\Connection;

class Database
{
  public $db;

  function __construct()
  {
    /* MySQL */
    // $username= "u466389499_legendary";
    // $password= "nH5Aze3?4[!l1NU45W(S";
    // $dbName= "u466389499_legendary";
    // $databaseConnection = $this->connectMysql($username, $password, $dbName);

    /* SQLite */
    $dbFile = dirname(__DIR__) . '/admin.db';
    $databaseConnection = $this->connectSqlite($dbFile);

    $this->db = new Connection($databaseConnection);

    $this->initDB();
  }

  function initDB()
  {
    $tableList = glob(__DIR__ . '/db-init/*.sql');

    foreach ($tableList as $tableFile) {
      $table = pathinfo($tableFile, PATHINFO_FILENAME);

      $exists = $this->db->fetch("SELECT name FROM sqlite_master WHERE type='table' AND name=?", $table);
      if (!$exists) {
        $sql = file_get_contents($tableFile);

        if (trim($sql) != '') {
          $this->db->query(trim($sql));

          $dataFile = dirname($tableFile) . "/" . $table . ".json";

          if (file_exists($dataFile)) {
            $recs = json_decode(file_get_contents($dataFile), true);
            foreach ($recs as $rec) {
              $this->db->query("INSERT INTO %n %v", $table, $rec);
            }
          }
        }
      }
    }
  }

  function connectSqlite($dbFile)
  {
    return [
      'driver' => 'sqlite',
      'database' => $dbFile
    ];
  }

  function connectMysql($username, $password, $dbName)
  {
    return [
      'driver'   => 'mysqli',
      'host'     => '127.0.0.1',
      'username' => $username,
      'password' => $password,
      'database' => $dbName,
    ];
  }
}
