<?php
Class AdminModel extends CI_Model {

    Public function __construct() {
        parent::__construct();
        $this->pdo = $this->load->database('pdo', true);
    }
    
    public function GetAppUsers() {
        try
        {
            $sql = "SELECT * FROM app_users 
                    WHERE enabled = 1
                    AND position != ''
                    ORDER BY lastname,firstname,username";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetAppUserById($id)
    {
        try
        {
            $sql = "SELECT id,firstname,lastname,position,username,is_admin FROM app_users WHERE id = ?";
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

    public function AddAppUser($data) {
        try
        {
            extract($data);
            if(isset($is_admin) && $is_admin == 'on')
            {
                $is_admin = 1;
            }
            else
            {
                $is_admin = 0;
            }
            
            $password = sha1($password);
            $sql = "INSERT INTO app_users
                    SET username = ?,
                    password = ?,
                    lastname = ?,
                    firstname = ?,
                    position = ?,
                    is_admin = ?
                    ";
            $stmt = $this->pdo->query($sql,array($username,$password,$lastname,$firstname,$position,$is_admin));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function DeleteAppUser($id)
    {
        try
        {
            $sql = "UPDATE app_users
                    SET enabled = 0
                    WHERE id = ?
                    ";
            $stmt = $this->pdo->query($sql,array($id));

            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function UpdateAppUser($data) {
        try
        {
            extract($data);
            if(isset($is_admin) && $is_admin == 'on')
            {
                $is_admin = 1;
            }
            else
            {
                $is_admin = 0;
            }
            
            if(trim($password) == '')
            {
                $sql = "UPDATE app_users
                        SET username = ?,
                        lastname = ?,
                        firstname = ?,
                        position = ?,
                        is_admin = ?
                        WHERE id = ?
                        ";
                $stmt = $this->pdo->query($sql,array($username,$lastname,$firstname,$position,$is_admin,$edit_id));
            }
            else 
            {
                $password = sha1($password);
                $sql = "UPDATE app_users
                        SET username = ?,
                        password = ?,
                        lastname = ?,
                        firstname = ?,
                        position = ?
                        WHERE id = ?
                        ";
                $stmt = $this->pdo->query($sql,array($username,$password,$lastname,$firstname,$position,$edit_id));
            }
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
            $sql = "SELECT id FROM app_users where username = ? and password = ?";
            $stmt = $this->pdo->query($sql,array($username,$password));
            return $stmt->result();
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function AddPreference($data) {
        try
        {
            extract($data);
            $sql = "INSERT INTO preferences
                    SET title = ?,
                    tag = ?,
                    content = ?
                    ";
            $stmt = $this->pdo->query($sql,array(
                    $title,
                    "",
                    ""
            ));
            $id = $this->pdo->insert_id();
            return $id;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetPreferences() {
        try
        {
            $sql = "SELECT * FROM preferences
                    ORDER BY title";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function UpdatePreference($data)
    {
        try
        {
            extract($data);
            $sql = "UPDATE preferences
                    SET title = ?,
                    tag = ?,
                    content = ?
                    WHERE id = ?
                    ";
            $stmt = $this->pdo->query($sql,array($title,$tag,$content,$id));
            
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }

    public function DeletePreference($id)
    {
        try
        {
            $sql = "DELETE FROM preferences
                    WHERE id = ?
                    ";
            $stmt = $this->pdo->query($sql,array($id));

            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetAnswers() {
        try
        {
            $sql = "SELECT * FROM preferences
                    WHERE tag like 'UA%'
                    OR tag like 'US%'
                    ORDER BY tag";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetChatBotSequences() {
        try
        {
            $sql = "SELECT p.title, p.content, c.* FROM preferences as p
                    INNER JOIN chatbot as c
                    ON p.tag = c.tag
                    ORDER BY p.title";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function AddChatBotSequence($data) {
        try
        {
            extract($data);
            $sql = "INSERT INTO chatbot
                    SET tag = ?,
                    sequence = ?,
                    follow = ?
                    ";
            $stmt = $this->pdo->query($sql,array(
                    $tag,
                    "",
                    ""
            ));
            $id = $this->pdo->insert_id();
            return $id;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function updateChatBotSequence($data)
    {
        try
        {
            extract($data);
            $sql = "UPDATE chatbot
                    SET sequence = ?,
                    follow = ?
                    WHERE id = ?
                    ";
            $stmt = $this->pdo->query($sql,array($sequence,$follow,$id));
            
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function DeleteChatBotSequence($id)
    {
        try
        {
            $sql = "DELETE FROM chatbot
                    WHERE id = ?
                    ";
            $stmt = $this->pdo->query($sql,array($id));

            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GenericDelete($id,$table)
    {
        try
        {
            $sql = "DELETE FROM $table
                    WHERE id = ?
                    ";
            $stmt = $this->pdo->query($sql,array($id));

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