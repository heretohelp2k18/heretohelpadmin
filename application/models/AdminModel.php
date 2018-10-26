<?php
Class AdminModel extends CI_Model {

    Public function __construct() {
        parent::__construct();
        $this->pdo = $this->load->database('pdo', true);
    }
    
    public function GetAppUsers() {
        try
        {
            $addtional_query = "";
            if(isset($_GET['qtype']))
            {
                $qtype = $_GET['qtype'];
                switch ($qtype)
                {
                    case "User":
                        $addtional_query = "AND position = '$qtype'";
                        break;
                    case "Psychologist":
                        $addtional_query = "AND position = '$qtype' AND is_approved = 1";
                        break;
                    case "Pending":
                        $addtional_query = "AND position = 'Psychologist' AND is_approved = 0";
                }
            }
            
            $sql = "SELECT * FROM app_users 
                    WHERE enabled = 1
                    AND is_guest = 0
                    $addtional_query
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
            $sql = "SELECT id,firstname,middlename,lastname,age,gender,position,email,username,is_admin,idimage,autoresponse FROM app_users WHERE id = ?";
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
                    middlename = ?,
                    age = ?,
                    gender = ?,
                    email = ?,
                    position = ?,
                    is_admin = ?
                    ";
            $stmt = $this->pdo->query($sql,array($username,$password,$lastname,$firstname,$middlename,$age,$gender,$email,$position,$is_admin));
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
    
    public function UpdateAppUser($data, $is_account) {
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
            
            if($is_account)
            {
                if(trim($password) == '')
                {
                    $sql = "UPDATE app_users
                            SET username = ?,
                            lastname = ?,
                            firstname = ?,
                            middlename = ?,
                            age = ?,
                            gender = ?,
                            email = ?,
                            position = ?,
                            is_admin = ?,
                            autoresponse = ?
                            WHERE id = ?
                            ";
                    $stmt = $this->pdo->query($sql,array($username,$lastname,$firstname,$middlename,$age,$gender,$email,$position,$is_admin,$autoresponse,$edit_id));
                }
                else 
                {
                    $password = sha1($password);
                    $sql = "UPDATE app_users
                            SET username = ?,
                            password = ?,
                            lastname = ?,
                            firstname = ?,
                            middlename = ?,
                            age = ?,
                            gender = ?,
                            email = ?,
                            position = ?,
                            is_admin = ?,
                            autoresponse = ?
                            WHERE id = ?
                            ";
                    $stmt = $this->pdo->query($sql,array($username,$password,$lastname,$firstname,$middlename,$age,$gender,$email,$position,$is_admin,$autoresponse,$edit_id));
                }
            }
            else
            {
                if(trim($password) == '')
                {
                    $sql = "UPDATE app_users
                            SET username = ?,
                            lastname = ?,
                            firstname = ?,
                            middlename = ?,
                            age = ?,
                            gender = ?,
                            email = ?,
                            position = ?,
                            is_admin = ?,
                            WHERE id = ?
                            ";
                    $stmt = $this->pdo->query($sql,array($username,$lastname,$firstname,$middlename,$age,$gender,$email,$position,$is_admin,$edit_id));
                }
                else 
                {
                    $password = sha1($password);
                    $sql = "UPDATE app_users
                            SET username = ?,
                            password = ?,
                            lastname = ?,
                            firstname = ?,
                            middlename = ?,
                            age = ?,
                            gender = ?,
                            email = ?,
                            position = ?,
                            is_admin = ?
                            WHERE id = ?
                            ";
                    $stmt = $this->pdo->query($sql,array($username,$password,$lastname,$firstname,$middlename,$age,$gender,$email,$position,$is_admin,$edit_id));
                }
            }
            
            
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function SetApproveUser($data) {
        try
        {
            extract($data);
            $sql = "UPDATE app_users
                    SET is_approved = ?
                    WHERE id = ?
                    ";
            $stmt = $this->pdo->query($sql,array($action,$id));
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
    
    public function GetActiveUserCount()
    {
        try
        {
            $sql = "SELECT count(*) as count FROM app_users 
                    WHERE enabled = 1
                    AND position = 'User'";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetActivePsychologist()
    {
        try
        {
            $sql = "SELECT count(*) as count FROM app_users 
                    WHERE enabled = 1
                    AND is_approved = 1
                    AND position = 'Psychologist'";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetPendingPsychologist()
    {
        try
        {
            $sql = "SELECT count(*) as count FROM app_users 
                    WHERE enabled = 1
                    AND is_approved = 0
                    AND position = 'Psychologist'";
            $stmt = $this->pdo->query($sql);
            return $stmt;
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
    
    public function GetChatrooms($userid)
    {
        try
        {
            $sql = "SELECT c.chatroom, c.chatdate, CONCAT(u.firstname, ' ', u.lastname) AS chatmate, u.gender FROM chatroom AS c
                INNER JOIN app_users AS u
                ON u.id = c.userid
                WHERE psychoid = ?
                ORDER BY c.id DESC";
            
            $stmt = $this->pdo->query($sql, array($userid));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function CheckIfUserIsAdmin($userID)
    {
        try
        {
            $sql = "SELECT id FROM app_users 
                    WHERE id = ?
                    AND is_admin = 1";
            $stmt = $this->pdo->query($sql, array($userID));
            if(count($stmt->result()) > 0 )
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
}
?>