<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
   
class AuthLogin {
    
    private $mode = 'admin';

    public function checkIfLogin($mode) 
    {
        $this->mode = $mode;
        
        if($mode == 'admin')
        {
            if(isset($_SESSION['admin']))
            {
                if($_SESSION['admin']['login'])
                {
                    $user_id = $_SESSION['admin']['user_id'];
                    $session_id = $_SESSION['admin']['session_id'];
                    $CI =& get_instance();
                    $CI->pdo = $CI->load->database('pdo', true);
                    try
                    {
                        $sql = "SELECT * FROM app_users 
                                WHERE id = ? 
                                AND session_id = ?
                                AND enabled = 1";
                        $stmt = $CI->pdo->query($sql,array($user_id,$session_id));
                        $result = $stmt->result();
                        if(count($result) > 0)
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
                        return FALSE;
                    }

                }
                else
                {
                    return FALSE;
                }
            }
            else
            {
                return FALSE;
            }
        }
        else if ($mode == 'app') 
        {
        
        }
    }
    
    public function login($username,$password) {
        try
        {
            $CI =& get_instance();
            $CI->pdo = $CI->load->database('pdo', true);
            
            $password = sha1($password);
            $sql = "SELECT * FROM app_users 
                    WHERE username = ?
                    AND password = ?
                    AND ((position = 'Web Administrator') OR (position = 'Psychologist'))
                    AND enabled = 1";
            //      AND is_admin = 1
            $stmt = $CI->pdo->query($sql,array($username,$password));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function SetSessionId($id,$session_id) 
    {
        if($this->mode == 'admin')
        {
            try
            {
                $CI =& get_instance();
                $CI->pdo = $CI->load->database('pdo', true);
                
                $sql = "UPDATE app_users
                        SET session_id = ?
                        WHERE id = ?";
                $stmt = $CI->pdo->query($sql,array($session_id,$id));
                return $stmt;
            } 
            catch (Exception $ex) 
            {
                echo $ex;
                exit;
            }
        }
    }
}	