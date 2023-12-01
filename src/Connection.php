<?php

class Connection {

    /**
     * @var \PDO $pdo
     */
    private PDO $pdo;

    public function __construct()
    {
        $configs = parse_ini_file('.env');
        try {
            $dsn = "mysql:dbname={$configs['DB_DATABASE']};host={$configs['DB_HOST']};charset=utf8";
            $user = $configs['DB_USERNAME'];
            $pass = $configs['DB_PASSWORD'];
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            echo 'Ошибка подключения: ' . $e->getMessage();
        }
    }

    /**
     * @param string $tableName
     * @param array $fields
     * @param int $limit
     * @param int $offset
     * @param string $orderedField
     * @param string $order
     *
     * @return array
     */
    public function select(string $tableName, array $fields, int $limit = 0, int $offset = 0, string $orderedField = null, string $order = 'ASC'): array
    {
        $field_names = implode(', ', $fields);
        $orderStatement = $orderedField ? "ORDER BY $orderedField $order" : '';
        $limitStatement = $limit < 1 ? '' : "LIMIT {$limit}";
        $offsetStatement = $offset < 1 ? '' : "OFFSET {$offset}";
        $query = "SELECT {$field_names} FROM {$tableName} $orderStatement {$limitStatement} {$offsetStatement}";
        $statement = $this->pdo->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $tableName
     * @param array $fields
     *
     * @return int
     */
    public function insert(string $tableName, array $fields): int
    {
        $field_names = [];
        $parameters = [];
        foreach ($fields as $key => $val) {
            $field_names[] = $key;
            $parameters[] = ":{$key}";
        }

        $field_names = implode(', ', $field_names);
        $parameters = implode(', ', $parameters);
        $query = "INSERT INTO {$tableName} ({$field_names}) VALUES ({$parameters})";
        $statement = $this->pdo->prepare($query);
        $statement->execute($fields);

        return $this->pdo->lastInsertId();
    }

}
