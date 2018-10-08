<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webservice extends CI_Controller {
    
    public function __construct() 
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        $this->load->model('WebserviceModel','model');
        
        $_POST = $_GET; // Android setup
    }
    
    public function index()
    {
        echo "Entering Here To Help Web Service...";
    }

    public function Signin()
    {
        $json_data = array();
        if(isset($_POST['username']) && isset($_POST['password']))
        {
            $username = $_POST['username'];
            $password = sha1($_POST['password']);

            $stmt = $this->model->AuthenticateUser($username,$password);
            if(count($stmt) > 0)
            {
                $json_data['usertype'] = $stmt[0]->position;
                $json_data['userid'] = $stmt[0]->id;
                $json_data['username'] = $stmt[0]->username;
                $json_data['useremail'] = $stmt[0]->email;
                $json_data['userfirstname'] = $stmt[0]->firstname;
                $json_data['usergender'] = $stmt[0]->gender;
                $json_data['token'] = $this->generateAppToken($stmt[0]->id);
                $json_data['success'] = TRUE;
            }
            else
            {
                $json_data['success'] = FALSE;
                $json_data['message'] = 'Login failed';
            }
        }
        else
        {
            $json_data['success'] = FALSE;
            $json_data['message'] = 'Invalid Action';
        }

        echo json_encode($json_data);
        exit;
    }
    
    public function ChatBotData()
    {
        $json_data = array();
        $json_data['errorcode'] = "";
        $json_data['message'] = "";
        $json_data['preferences'] = array();
        $json_data['chatbot'] = array();
        if(isset($_POST['username']) && isset($_POST['token']))
        {
            $username = $_POST['username'];
            $token = $_POST['token'];
            
            $stmt = $this->model->AuthenticateUserToken($username,$token);
            if(count($stmt) > 0)
            {
                $stmt = $this->model->GetPreferences();
                foreach($stmt->result() as $row)
                {
                    array_push($json_data['preferences'], $row);
                }
                
                $stmt = $this->model->GetChatBotSequence();
                foreach($stmt->result() as $row)
                {
                    array_push($json_data['chatbot'], $row);
                }
                
                $json_data['success'] = TRUE;
            }
            else
            {
                $json_data['success'] = FALSE;
                $json_data['message'] = 'Session Expired!';
                $json_data['errorcode'] = "143"; //Session Expired
            }
        }
        else
        {
            $json_data['success'] = FALSE;
            $json_data['message'] = 'Invalid Action';
        }

        echo json_encode($json_data);
        exit;
    }
    
    public function generateAppToken($userID)
    {
        $timestamp = time();
        $token = sha1($timestamp);
        $this->model->UpdateAppToken($userID, $token);
        return $token;
    }
    
    public function UploadFromCam()
    {
        $file = $_GET['filename'].'.jpg';
        move_uploaded_file($_FILES["file"]["tmp_name"], FCPATH.'images/reports/'.$file);
    }
    
    public function Register()
    {
        $json_data = array();
        if($this->isUsernameAvailable($_POST['username']))
        {
            $id = $this->model->Register($_POST);
            $json_data['id'] = $id;
            if($id > 0)
            {
                $json_data['success'] = TRUE;
                $json_data['message'] = "Success! You may now login.";
            }
            else
            {
                $json_data['success'] = FALSE;
                $json_data['message'] = "Error in inserting to the database";
            } 
        }
        else
        {
            $json_data['success'] = FALSE;
            $json_data['message'] = "Username is already in used.";
        }
        echo json_encode($json_data);
        exit;
    }
    
    public function UpdateAccount()
    {
        $json_data = array();
        if($this->isUsernameAvailable($_POST['username'],$_POST['id']))
        {
            if($this->isNumberAvailable($_POST['mobile'],$_POST['id']))
            {
                $updated = $this->model->UpdateAccount($_POST);
                if($updated)
                {
                    $json_data['success'] = TRUE;
                }
                else
                {
                    $json_data['success'] = FALSE;
                    $json_data['message'] = "Error in inserting to the database";
                }
            }
            else
            {
                $json_data['success'] = FALSE;
                $json_data['message'] = "Mobile number is already in used.";
            }
        }
        else
        {
            $json_data['success'] = FALSE;
            $json_data['message'] = "Username is already in used.";
        }
        echo json_encode($json_data);
        exit;
    }
    
    public function isUsernameAvailable($username,$id = null)
    {
        $is_exist = $this->model->CheckIfUsernameExist($username,$id);
        if(!empty($is_exist->result()))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    public function isNumberAvailable($mobile,$id = null)
    {
        $is_exist = $this->model->CheckIfNumberExist($mobile, $id);
        if(!empty($is_exist->result()))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    
    public function GetUserById()
    {
        $params = json_decode(file_get_contents('php://input'),true);
        $_POST = $params;
        $id = $_POST['id'];
        $json_data = array();
        $json_data['info'] = $this->model->GetUserById($id);
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
}
