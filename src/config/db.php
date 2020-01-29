<?php
class db{
    private $server="localhost";
    private $db="educdb";
    private $user="root";
    private $pswd="";
    //Conección
    public function conectDB(){
        $mySqlConnect="mysql:host=$this->server;dbname=$this->db;charset=utf8";
        $dbConnection=new PDO($mySqlConnect,$this->user,$this->pswd);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }
}
