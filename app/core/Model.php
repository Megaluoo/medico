<?php
abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected int $tenantId;

    public function __construct(int $tenantId)
    {
        $this->tenantId = $tenantId;
        $this->db = Database::getInstance()->getConnection();
    }

    public function getTenantId(): int
    {
        return $this->tenantId;
    }
}
