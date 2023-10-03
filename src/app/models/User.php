<?php

require_once BASE_URL . '/src/config/database.php';

class User {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findAll($page = 1, $limit = 10) 
    {
        $query = "SELECT * FROM users WHERE  ORDER BY username LIMIT $limit OFFSET :offset";
        $this->db->query($query);

        $offset = ($page - 1) * $limit;
        $this->db->bind("offset", $offset);

        $result = $this->db->fetchAll();
        return $result;
    }

    public function findById($userId) 
    {
        $query = "SELECT * From users WHERE user_id = :userId";

        $this->db->query($query);
        $this->db->bind("userId", $userId);
        $result = $this->db->fetch();

        return $result;
    }

    public function findByUsername($username) 
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $this->db->query($query);
        $this->db->bind('username', $username);
        $result = $this->db->fetch();

        return $result;
    }

    public function findByEmail($email) 
    {
        $query = "SELECT * FROM User WHERE email = :email";

        $this->db->query($query);
        $this->db->bind('email', $email);
        $result = $this->db->fetch();

        return $result;
    }

    public function create($email, $username, $hashedPassword, $firstName, $lastName)
    {
        // Check if the email, username already exists
        if ($this->emailExists($email) && $this->usernameExists($username)) {
            throw new Exception('This email and username are already connected to an account.');
        }

        // Check if the email already exists
        if ($this->emailExists($email)) {
            throw new Exception('This email is already connected to an account.');
        }

        // Check if the username already exists
        if ($this->usernameExists($username)) {
            throw new Exception('This username is already connected to an account');
        }
        
        $query = "INSERT INTO users (email, username, password, first_name, last_name, role_id) VALUES (:email, :username, :password, :first_name, :last_name, :role_id)";

        $this->db->query($query);
        $this->db->bind('email', $email);
        $this->db->bind('username', $username);
        $this->db->bind('password', $hashedPassword);
        $this->db->bind('first_name', $firstName);
        $this->db->bind('last_name', $lastName);
        $this->db->bind('role_id', 2); // User role

        $this->db->execute();
    }

    // Check if the email already exists
    private function emailExists($email)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $this->db->query($query);
        $this->db->bind('email', $email);
        $this->db->fetch();

        return $this->db->rowCount() > 0;
    }

    // Check if the username already exists
    private function usernameExists($username)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $this->db->query($query);
        $this->db->bind('username', $username);
        $this->db->fetch();
        
        return $this->db->rowCount() > 0;
    }

    public function update($email, $username, $hashedPassword, $firstName, $lastName, $status, $avatarURL, $roleId)
    {
        $query = "INSERT INTO users (email, username, password, first_name, last_name, status, avatar_url, role_id) VALUES (:email, :username, :password, :first_name, :last_name, :status, :avatar_url, :role_id)";

        $this->db->query($query);
        $this->db->bind('email', $email);
        $this->db->bind('username', $username);
        $this->db->bind('password', $hashedPassword);
        $this->db->bind('first_name', $firstName);
        $this->db->bind('last_name', $lastName);
        $this->db->bind('status', $status);
        $this->db->bind('avatar_url', $avatarURL);
        $this->db->bind('role_id', $roleId);

        $this->db->execute();
    }

    public function activateUser($userId)
    {
        $query = "
            UPDATE users 
            SET status = 1
            WHERE user_id = :user_id
        ";

        $this->db->query($query);
        $this->db->bind('user_id', $userId);

        $this->db->execute();
    }

    public function deactivateUser($userId)
    {
        $query = "
            UPDATE users 
            SET status = 0
            WHERE user_id = :user_id
        ";

        $this->db->query($query);
        $this->db->bind('user_id', $userId);

        $this->db->execute();
    }

    public function getTotalRows()
    {
        $query = "SELECT * FROM users";
        $this->db->query($query);
        $this->db->fetch();

        return $this->db->rowCount();
    }
}