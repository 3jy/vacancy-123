<?php

namespace application;

/**
 * Class Adapter
 * @package application
 */
class Adapter
{
    /**
     * @var self
     */
    private static $inst;

    /**
     * @var \PDO
     */
    private $connection;

    /**
     * @return Adapter
     */
    public static function getInstance()
    {
        if (!isset(self::$inst)) {
            self::$inst = new self();
        }

        return self::$inst;
    }

    /**
     */
    public function getConnection()
    {
        $conf = Config::getInstance()->getConfig();
        $this->connection = new \PDO("mysql:host={$conf->db->host};dbname={$conf->db->name}", $conf->db->user, $conf->db->password);
    }

    /**
     */
    public function dropConnection()
    {
        if (isset($this->connection)) {
            $this->connection = null;
        }
    }

    /**
     * @param string $query
     * @param array $args
     */
    public function exec($query, $args = [])
    {
        try {
            $this->getConnection();
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $stmt = $this->connection->prepare($query);
            if (!is_array($args)) {
                $args = [$args];
            }

            $stmt->execute($args);
            $this->dropConnection();
        } catch (\PDOException $e) {
            Logger::getInstance()->debug("Error is thrown with message - " . $e->getMessage());
        }
    }
}
