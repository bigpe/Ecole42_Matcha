<?php
    class database
    {
        private $db;

        public function __construct()
        {
            $ini = include('config.php');

            $DB_DSN = "mysql:dbname=" . $ini['dbname'] . ";host=" . $ini['host'] . ";port=" . $ini['port'];
            $DB_USER = $ini['username'];
            $DB_PASSWORD = $ini['password'];
            $this->db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }
        public function db_read($query)
        {
            $data = $this->db->query($query)->fetch()[0];
            return($data);
        }
        public function db_read_multiple($query)
        {
            $data = $this->db->query($query)->fetchAll();
            return($data);
        }
        public function db_change($query)
        {
            $this->db->exec($query);
        }
        public function db_check($query)
        {
            $data = $this->db->query($query)->fetch();
            if(is_array($data))
                return (1);
            return (0);
        }
    }
?>