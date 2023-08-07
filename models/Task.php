<?php
class Task {
    public $id;
    private $userId;
    public $description;
    private $createdAt;
    public $status;

    /**
     * Конструктор класса Task
     * @param int $id Идентификатор задачи
     * @param int $userId Идентификатор пользователя
     * @param string $description Описание задачи
     * @param string $createdAt Дата и время создания задачи
     * @param string $status Статус задачи
     */
    public function __construct(int $id, int $userId, string $description, string $createdAt, string $status) {
        $this->id = $id;
        $this->userId = $userId;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->status = $status;
    }

    /**
     * Получить идентификатор задачи
     * @return int Идентификатор задачи
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Получить идентификатор пользователя
     * @return int Идентификатор пользователя
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Получить описание задачи
     * @return string Описание задачи
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Получить дату и время создания задачи
     * @return string Дата и время создания задачи
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Получить статус задачи
     * @return string Статус задачи
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Установить описание задачи
     * @param string $description Описание задачи
     */
    public function setDescription(string $description) {
        $this->description = $description;
    }

    /**
     * Установить дату и время создания задачи
     * @param string $createdAt Дата и время создания задачи
     */
    public function setCreatedAt(string $createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * Установить статус задачи
     * @param string $status Статус задачи
     */
    public function setStatus(string $status) {
        $this->status = $status;
    }
}
?>
