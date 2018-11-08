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

    public function Signin($json_data = array())
    {
        if(isset($_POST['username']) && isset($_POST['password']))
        {
            $username = $_POST['username'];
            $password = sha1($_POST['password']);

            $stmt = $this->model->AuthenticateUser($username,$password);
            if(count($stmt) > 0)
            {
                if(($stmt[0]->position == "Psychologist") && ($stmt[0]->is_approved == 0))
                {
                    $json_data['success'] = FALSE;
                    $json_data['message'] = 'Your application is still waiting for approval.';
                }
                else if(($stmt[0]->position == "Psychologist") && ($stmt[0]->is_approved == 2))
                {
                    $json_data['success'] = FALSE;
                    $json_data['message'] = 'Sorry, your application has been denied.';
                }
                else
                {
                    $json_data['usertype'] = $stmt[0]->position;
                    $json_data['userid'] = $stmt[0]->id;
                    $json_data['username'] = $stmt[0]->username;
                    $json_data['useremail'] = $stmt[0]->email;
                    $json_data['userfirstname'] = $stmt[0]->firstname;
                    $json_data['usermiddlename'] = $stmt[0]->middlename;
                    $json_data['userlastname'] = $stmt[0]->lastname;
                    $json_data['userage'] = $stmt[0]->age;
                    $json_data['usergender'] = $stmt[0]->gender;
                    $json_data['userautoresponse'] = $stmt[0]->autoresponse;
                    $json_data['isguest'] = $stmt[0]->is_guest;
                    $json_data['userskipchatbot'] = $stmt[0]->skipchatbot;
                    $json_data['token'] = $this->generateAppToken($stmt[0]->id);
                    $json_data['success'] = TRUE;
                }
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
                if($_POST['usertype'] == "Psychologist")
                {
                    $json_data['message'] = "Application submitted! Please visit this app time to time to check the status of your application.";
                }
                else
                {
                    $json_data['message'] = "Success! You may now login.";
                }
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
            $json_data['message'] = "Email is already in used.";
        }
        echo json_encode($json_data);
        exit;
    }
    
    public function UpdateAccount()
    {
        $json_data = array();
        if($this->isUsernameAvailable($_POST['username'],$_POST['id']))
        {
            $updated = $this->model->UpdateAccount($_POST);
            if($updated)
            {
                $json_data['success'] = TRUE;
                $json_data['message'] = "Account successfully updated";
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
    
    public function UploadImage()
    {
        $json_data = array();
        $json_data['success'] = FALSE;
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $uploads_dir = FCPATH.'images/uploads/';
            $tmp_name = $_FILES['image']['tmp_name'];
            $pic_name = $_FILES['image']['name'];
            move_uploaded_file($tmp_name, $uploads_dir.$pic_name);
            $json_data['success'] = TRUE;
        }
        else{
            echo "File not uploaded successfully.";
        }
        echo json_encode($json_data);
    }
    
    public function AddChatroom()
    {
        $json_data = array();
        $json_data['errorcode'] = "";
        $json_data['message'] = "";
        if(isset($_POST['username']) && isset($_POST['token']))
        {
            $username = $_POST['username'];
            $token = $_POST['token'];
            
            $stmt = $this->model->AuthenticateUserToken($username,$token);
            if(count($stmt) > 0)
            {
                $this->model->AddChatroom($_POST);
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
    
    public function GetChatroom()
    {
        $json_data = array();
        $json_data['errorcode'] = "";
        $json_data['message'] = "";
        $json_data['chatroom_data'] = array();
        if(isset($_POST['username']) && isset($_POST['token']))
        {
            $username = $_POST['username'];
            $token = $_POST['token'];
            
            $stmt = $this->model->AuthenticateUserToken($username,$token);
            if(count($stmt) > 0)
            {
                $json_data['success'] = TRUE;
                $stmt = $this->model->GetChatrooms($_POST['userid'], $_POST['usertype']);
                foreach($stmt->result() as $row)
                {
                    $row->chatdate =  date("M d o", strtotime($row->chatdate)) . " " . date("h:i a", strtotime($row->chatdate));
                    array_push($json_data['chatroom_data'], $row);
                }
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
    
    public function GuestLogin()
    {
        $data = $this->model->GuestLogin();
        $_POST['username'] = $data[0];
        $_POST['password'] = $data[1];
        $json_data = array();
        $json_data['message_to_guest'] = DEFAULT_MESSAGE_TO_GUEST;
        $this->Signin($json_data);
    }
    
    public function SetSkipChatBot()
    {
        $json_data = array();
        extract($_POST);
        $stmt = $this->model->AuthenticateUserToken($username,$token);
        if(count($stmt) > 0)
        {
            $this->model->SetSkipChatBot($userid);
            $json_data['success'] = TRUE;
        }
        else
        {
            $json_data['success'] = FALSE;
        }
        echo json_encode($json_data);
        exit;
    }
}
