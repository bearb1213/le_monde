<?php
require_once __DIR__ . '/../utils/db.php';
require_once __DIR__ . '/../models/User.php';

class UserDAO
{
    protected $pdo;

    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?: getPDO();
    }

    public function create($user)
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
            $stmt->execute([':username' => $user->username, ':password' => $user->password]);
            $user->id = (int)$this->pdo->lastInsertId();
            return $user;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to create user: ' . $e->getMessage());
        }
    }

    public function findById($id)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            return $row ? User::fromArray($row) : null;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to fetch user: ' . $e->getMessage());
        }
    }

    public function findByUsername($username)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->execute([':username' => $username]);
            $row = $stmt->fetch();
            return $row ? User::fromArray($row) : null;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to fetch user: ' . $e->getMessage());
        }
    }

    
    public function login($username, $password)
    {
        try {
            $user = $this->findByUsername($username);
            if (!$user) {
                return null;
            }
            // if (function_exists('password_verify') && password_verify($password, $user->password)) {
            //     return $user;
            // }

            if ($user->password === $password) {
                return $user;
            }

            return null;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to authenticate user: ' . $e->getMessage());
        }
    }

    public function update($user)
    {
        try {
            $stmt = $this->pdo->prepare('UPDATE users SET username = :username, password = :password WHERE id = :id');
            $stmt->execute([':username' => $user->username, ':password' => $user->password, ':id' => $user->id]);
            return true;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to update user: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to delete user: ' . $e->getMessage());
        }
    }
}
