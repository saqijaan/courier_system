<?php

namespace App\Helpers;

class Db{
    private $HOST       =   'localhost';
    private $DB_NAME    =   'courier_system';
    private $DB_USERNAME=   'root';
    private $DB_PASSWORD=   '';

    protected $con      =   null;
    protected $table    =   "sample";

    public function __construct(){
        $this->con=new \MySQLi(
                                $this->HOST,
                                $this->DB_USERNAME,
                                $this->DB_PASSWORD,
                                $this->DB_NAME
                            );

        if ($this->con->connect_errno){
            die("Failed to connect to MySQL: " . $mysqli->connect_error);
        }
    }
    public function get(){
        $res = $this->con->query("SELECT * FROM $table");
        $table = $res->fetch_all('MYSQLI_ASSOC');
        return json_encode($table);
    }
    public function delete($id){
        $this->con->query("DELETED FROM $table WHERE id=$id");
        return $this->con->affected_rows > 0?true :false;
    }

}