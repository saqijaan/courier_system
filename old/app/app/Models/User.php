<?php 
namespace App\Models;

use App\Helpers\Db;

class User extends Db{
    protected $table= 'users';

    public $id;
    public $name;
    public $phone;
    public $email;
    public $address;
    public $city_id;
    public $password;
    public $token;
    public $created_at;
    public $updated_at;

    public function __construct()
    {
        parent::__construct();
        $this->created_at=date('Y-m-d H:i:s');
        $this->updated_at=date('Y-m-d H:i:s');
    }
    public function save(){
        if ($this->id ==null )
        {
            $query ="INSERT INTO $this->table (";
            $query.="name,phone,email,address,city_id,password,token,created_at,updated_at";
            $query.=")";
            $query.=" VALUES(";
            $query.=" '$this->name','$this->phone','$this->email','$this->address','$this->city_id','$this->password','$this->token','$this->created_at','$this->updated_at'";
            $query.=")";
            $this->con->query($query);
        }else{
            $query ="UPDATE $this->table SET ";
            $query.="name       =   '$this->name'       ,";
            $query.="phone      =   '$this->phone'      ,";
            $query.="email      =   '$this->email'      ,";
            $query.="address    =   '$this->address'    ,";
            $query.="city_id    =   '$this->city_id'    ,";
            $query.="password   =   '$this->password'   ,";
            $query.="token      =   '$this->token'      ,";
            $query.="WHERE id   =    $this->id";
            $this->con->query($query);
        }
        return $this->con->affected_rows > 0 ? true : false;
    }
    public static function find($id){
        self::$con->query("SELECT * FROM ");
    }

}