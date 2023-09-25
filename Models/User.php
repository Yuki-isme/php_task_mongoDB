<?php

require_once 'Model.php';

class User extends Model
{
    private $collectionName = 'users';

    public function getAllUser(){
        return $this->find($this->collectionName, []);
    }

    public function check($param = [])
    {
        return $this->findOne($this->collectionName, $param);
    }
}