<?php

Class WebserviceModel extends CI_Model {

    Public function __construct() {
        parent::__construct();
        $this->pdo = $this->load->database('pdo', true);
    }

    public function GetUsers() {
        try
        {
            $sql = "SELECT * FROM app_users";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function AuthenticateUser($username,$password) 
    {
        try
        {
            $sql = "SELECT id,firstname,middlename,lastname,age,username,email,position,gender,autoresponse,is_approved FROM app_users where username = ? and password = ?";
            $stmt = $this->pdo->query($sql,array($username,$password));
            return $stmt->result();
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function AuthenticateUserToken($username,$token) 
    {
        try
        {
            $sql = "SELECT id FROM app_users where username = ? and session_app_id = ?";
            $stmt = $this->pdo->query($sql,array($username,$token));
            return $stmt->result();
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function UpdateAppToken($userId, $token)
    {
        try
        {
            $sql = "UPDATE app_users SET session_app_id = ? where id = ?";
            $stmt = $this->pdo->query($sql,array($token, $userId));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetPreferences()
    {
        try
        {
            $sql = "SELECT * FROM preferences";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetChatBotSequence()
    {
        try
        {
            $sql = "SELECT * FROM chatbot";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function Register($data)
    {
        try
        {
            extract($data);
            $password = sha1($password);
            
            if($usertype == "Psychologist")
            {
                $sql = "INSERT INTO app_users
                        SET firstname = ?,
                        middlename = ?,
                        lastname = ?,
                        gender = ?,
                        age = ?,
                        username = ?,
                        email = ?,
                        password = ?,
                        position = ?,
                        enabled = 1,
                        is_approved = 0,
                        idimage = ?
                        ";
                $stmt = $this->pdo->query($sql,array($firstname,$middlename,$lastname,$gender,$age,$username,$email,$password,$usertype,$idimage));
            }
            else
            {
                $sql = "INSERT INTO app_users
                        SET firstname = ?,
                        middlename = ?,
                        lastname = ?,
                        gender = ?,
                        age = ?,
                        username = ?,
                        email = ?,
                        password = ?,
                        position = ?,
                        enabled = 1
                        ";
                $stmt = $this->pdo->query($sql,array($firstname,$middlename,$lastname,$gender,$age,$username,$email,$password,$usertype));
            }
            $id = $this->pdo->insert_id();
            return $id;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function UpdateAccount($data)
    {
        try
        {
            extract($data);
            if(trim($password) != '')
            {
                $password = sha1($password);
                $sql = "UPDATE app_users
                        SET firstname = ?,
                        middlename = ?,
                        lastname = ?,
                        gender = ?,
                        age = ?,
                        username = ?,
                        autoresponse = ?,
                        email = ?,
                        password = ?
                        WHERE id = ?
                        ";
                $stmt = $this->pdo->query($sql,array($firstname,$middlename,$lastname,$gender,$age,$email,$autoresponse,$email,$password,$id));
                return $stmt;
            }
            else
            {
                $sql = "UPDATE app_users
                        SET firstname = ?,
                        middlename = ?,
                        lastname = ?,
                        gender = ?,
                        age = ?,
                        username = ?,
                        email = ?,
                        autoresponse = ?
                        WHERE id = ?
                        ";
                $stmt = $this->pdo->query($sql,array($firstname,$middlename,$lastname,$gender,$age,$email,$email,$autoresponse,$id));
                return $stmt;
            }
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function CheckIfUsernameExist($username,$id)
    {
        try
        {
            $additional_query = '';
            if($id != null)
            {
                $additional_query = "AND id != $id AND enabled = 1";
            }
            $sql = "SELECT id from app_users 
                    where username = ?
                    $additional_query";
            $stmt = $this->pdo->query($sql,array($username));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function CheckIfNumberExist($number,$id)
    {
        try
        {
            $additional_query = '';
            if($id != null)
            {
                $additional_query = "AND id != $id AND enabled = 1";
            }
            $sql = "SELECT id from app_users 
                    where mobile = ?
                    $additional_query";
            $stmt = $this->pdo->query($sql,array($number));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetUserById($id)
    {
        try
        {
            $sql = "SELECT id,firstname,lastname,mobile,address,username FROM app_users WHERE id = ?";
            $stmt = $this->pdo->query($sql,array($id));
            $result = $stmt->result();
            return (array) $result[0];
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function AddChatroom($data)
    {
        try
        {
            extract($data);
            $chatdate = date("Y-m-d H:i:s");
            $sql = "INSERT INTO chatroom
                    SET userid = ?,
                    psychoid = ?,
                    chatroom = ?,
                    chatdate = ?
                    ";
            $stmt = $this->pdo->query($sql,array($userid, $psychoid, $chatroom, $chatdate));
            $id = $this->pdo->insert_id();
            return $id;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetChatrooms($userid, $usertype)
    {
        try
        {
            if($usertype == "User")
            {
                $sql = "SELECT c.chatroom, c.chatdate, CONCAT(u.firstname, ' ', u.lastname) AS chatmate, u.gender FROM chatroom AS c
                    INNER JOIN app_users AS u
                    ON u.id = c.psychoid
                    WHERE userid = ?
                    ORDER BY c.id DESC";
            }
            else if($usertype == "Psychologist")
            {
                $sql = "SELECT c.chatroom, c.chatdate, CONCAT(u.firstname, ' ', u.lastname) AS chatmate, u.gender FROM chatroom AS c
                    INNER JOIN app_users AS u
                    ON u.id = c.userid
                    WHERE psychoid = ?
                    ORDER BY c.id DESC";
            }
            
            $stmt = $this->pdo->query($sql, array($userid));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
}

?> 