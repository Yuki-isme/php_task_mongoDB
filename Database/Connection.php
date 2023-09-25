<?php
require_once '../vendor/autoload.php';
class Connection
{
    protected $client;
    protected $database;
    //khởi tạo kết nối mongodb
    public function __construct()
    {
        $this->client = new MongoDB\Client("mongodb://localhost:27017");
        $this->database = $this->client->selectDatabase('manage_bill');
    }

}
?>

