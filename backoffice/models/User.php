<?php

class User
{
    public $id;
    public $username;
    public $password;

    public function __construct($id = null, $username = null, $password = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public static function fromArray($row)
    {
        $u = new self();
        $u->id = isset($row['id']) ? (int)$row['id'] : null;
        $u->username = isset($row['username']) ? $row['username'] : null;
        $u->password = isset($row['password']) ? $row['password'] : null;
        return $u;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
