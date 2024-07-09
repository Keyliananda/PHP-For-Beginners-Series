<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    public $connection;
    public $statement;

    // Konstruktor mit Fehlerbehandlung hinzugefügt
    public function __construct($config, $username = 'root', $password = '5115')
    {
        // DSN-Zeichenkette erstellen
        $dsn = 'mysql:' . http_build_query($config, '', ';');

        try {
            // Verbindung zur Datenbank herstellen
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Fehlerbehandlung aktivieren
            ]);
        } catch (PDOException $e) {
            // Falls die Verbindung fehlschlägt, eine Fehlermeldung ausgeben und das Skript beenden
            die('Datenbankverbindung fehlgeschlagen: ' . $e->getMessage());
        }
    }

    // Methode, um eine SQL-Abfrage vorzubereiten und auszuführen
    public function query($query, $params = [])
    {
        $this->statement = $this->connection->prepare($query);

        $this->statement->execute($params);

        return $this;
    }

    // Methode, um alle Ergebnisse der Abfrage abzurufen
    public function get()
    {
        return $this->statement->fetchAll();
    }

    // Methode, um das erste Ergebnis der Abfrage abzurufen
    public function find()
    {
        return $this->statement->fetch();
    }

    // Methode, um das erste Ergebnis abzurufen oder eine Ausnahme auszulösen, wenn kein Ergebnis gefunden wurde
    public function findOrFail()
    {
        $result = $this->find();

        if (! $result) {
            abort();
        }

        return $result;
    }
}