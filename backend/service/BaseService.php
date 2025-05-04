<?php
require_once __DIR__ . '/../dao/BaseDao.php';

class BaseService {
    protected $dao;

    public function __construct(BaseDao $dao) {
        $this->dao = $dao;
    }

    // Basic CRUD
    public function getAll(): mixed {
        return $this->dao->getAll();
    }

    public function getById($id): mixed {
        return $this->dao->getById($id);
    }

    public function create($data): mixed {
        return $this->dao->insert($data);
    }

    public function update($id, $data): mixed {
        return $this->dao->update($id, $data);
    }

    public function delete($id): mixed {
        return $this->dao->delete($id);
    }

    // Optional: Validate required fields
    protected function validateRequiredFields(array $data, array $fields): void {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                throw new Exception("Field '$field' is required.");
            }
        }
    }
}
