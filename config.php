<?php
 class db{
    // Properties
    private $host = '127.0.0.1'; // localhost not working so using IP address
    private $user = 'root';
    private $password = '';
    private $dbname = 'webtechhactt';


    // Connect
    function connect(){
        $mysql_connect_str = "mysql:host=$this->host;dbname=$this->dbname";
        $dbConnection = new PDO($mysql_connect_str, $this->user, $this->password);
        $dbConnection->setAttribute( PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }
}
?>
