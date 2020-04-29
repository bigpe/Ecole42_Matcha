<?php
    class database
    {
        private $db;

        public function __construct()
        {
            $ini = include('config.php');

            $DB_DSN = "mysql:dbname=" . $ini['db']['dbname'] . ";host=" . $ini['db']['host'] . ";port=" . $ini['db']['port'] . ";charset=UTF8";
            $DB_USER = $ini['db']['username'];
            $DB_PASSWORD = $ini['db']['password'];
            $this->db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }
        public function db_read($query)
        {
            $data = $this->db->query($query)->fetch()[0];
            return($data);
        }
        public function db_read_multiple($query)
        {
            $data = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
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
        public function db_import($file_path){
            $file = file_get_contents("./database_dump/$file_path");
            $this->db->exec($file);
        }
    }
?>