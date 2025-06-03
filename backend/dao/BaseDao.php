<?php
require_once __DIR__ . '/Database.php';



class BaseDao {
    protected $table;
    protected $connection;

    public function __construct($table) {
        $this->table = $table;
        $this->connection = Database::connect();
    }

    public function getAll(): mixed {
        $stmt = $this->connection->prepare("SELECT * FROM `" . $this->table . "`");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id): mixed {
        $stmt = $this->connection->prepare("SELECT * FROM `" . $this->table . "` WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function insert($data): mixed {
        $columns = implode(separator: ", ", array: array_keys($data));
        $placeholders = ":" . implode(separator: ", :", array: array_keys($data));
        $sql = "INSERT INTO `" . $this->table . "` ($columns) VALUES ($placeholders)";
        $stmt = $this->connection->prepare(query: $sql);
        return $stmt->execute(params: $data);
    }

    public function update($id, $data): mixed {
        $fields = "";
        foreach ($data as $key => $value) {
            $fields .= "$key = :$key, ";
        }
        $fields = rtrim(string: $fields, characters: ", ");
        $sql = "UPDATE `" . $this->table . "` SET $fields WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id): mixed {
        $stmt = $this->connection->prepare("DELETE FROM `" . $this->table . "` WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
