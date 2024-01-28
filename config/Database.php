<?php
class Database
{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '12344321';
    private $database = 'ems';

    protected $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Public method to get the database connection
    public function getConnection()
    {
        return $this->conn;
    }
}
