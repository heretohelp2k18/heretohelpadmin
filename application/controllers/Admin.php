<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session'); 
        $this->load->model('AdminModel','model');
        $this->load->helper('url');
        if(!session_id())
        {
            session_start();
        }
        
        $this->load->library('AuthLogin');
        if($GLOBALS['method'] != 'login')
        {
            if(!$this->authlogin->checkIfLogin('admin'))
            {
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
                {
                    $json_data = array();
                    $json_data['success'] = FALSE;
                    $json_data['message'] = "You are not logged in";
                    echo json_encode($json_data);
                    exit;
                }
                else
                {
                    redirect('/admin/login');
                }
            }
        }
    }
    
    public function Login()
    {
        if(isset($_POST['username']) && isset($_POST['password']))
        {
            $user_data = null;
            $stmt = $this->authlogin->login($_POST['username'],$_POST['password']);
            foreach($stmt->result() as $row)
            {
                $user_data = (array) $row;
            }

            if($user_data != null)
            {
                $_SESSION['admin']['login'] = TRUE;
                $_SESSION['admin']['user_id'] = $user_data['id'];
                $_SESSION['admin']['session_id'] = session_id();
                $this->authlogin->SetSessionId($user_data['id'],session_id());
                redirect("/admin");
            }
            else
            {
                $_SESSION['login_message']['status'] = FALSE;
                $_SESSION['login_message']['message'] = 'Login Failed';
                redirect("/admin/login");
            }
        }
        else
        {
            $data = array();
            $data['show_message'] = 'false';
            $data['message'] = ''; 
            if(isset($_SESSION['login_message']))
            {
                if(!$_SESSION['login_message']['status'])
                {
                    $data['showMessage'] = 'true';
                    $data['message'] = $_SESSION['login_message']['message'];
                    unset($_SESSION['login_message']);
                }
            }
            
            $this->load->view('Admin/Login',$data);
        }
    }
    
    public function Logout()
    {
        unset($_SESSION['admin']);
        redirect("/admin/login");
    }

    public function index()
    {
        $this->Dashboard();
    }

    public function AppUsers()
    {
        $data = array();
        $data['list'] = '';
        $stmt = $this->model->GetAppUsers();
        foreach($stmt->result() as $row)
        {
            if(isset($_GET['qtype']) && ($_GET['qtype'] == "Pending"))
            {
                $data['list'] .= $this->load->view('Admin/AppUsersListReview',$row,TRUE);
            }
            else
            {
                $data['list'] .= $this->load->view('Admin/AppUsersList',$row,TRUE);
            }
        }

        $this->load->view('Admin/AdminHeader');
        $this->load->view('Admin/AppUsers',$data);
        $this->load->view('Admin/AdminFooter');
    }

    public function AppUsersAjax()
    {
        $data = array();
        $data['list'] = '';
        $stmt = $this->model->GetAppUsers();
        foreach($stmt->result() as $row)
        {
            $data['list'] .= $this->load->view('Admin/AppUsersList',$row,TRUE);
        }

        $data['success'] = TRUE;
        echo json_encode($data);
        exit;
    }

    public function AddAppUser()
    {
        $json_data = array();
        $json_data['success'] = $this->model->AddAppUser($_POST);;
        echo json_encode($json_data);
        exit;
    }

    public function UpdateAppUser()
    {
        $json_data = array();
        $json_data['success'] = $this->model->UpdateAppUser($_POST);;
        echo json_encode($json_data);
        exit;
    }

    public function DeleteAppUser()
    {
        $json_data = array();
        $json_data['success'] = $this->model->DeleteAppUser($_POST['id']);
        echo json_encode($json_data);
        exit;
    }

    public function GetAppUserById()
    {
        $json_data = array();
        $json_data['info'] = $this->model->GetAppUserById($_POST['id']);
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function SetApproveUser()
    {
        $json_data = array();
        $json_data['success'] = $this->model->SetApproveUser($_POST);
        $json_data['message'] = "Error updating to the database.";
        if($json_data['success'])
        {
            if($_POST['action'] == "1")
            {
                $json_data['message'] = "Application successfully approved.";
            }
            else
            {
                $json_data['message'] = "Application has been denied.";
            }
        }
        
        echo json_encode($json_data);
        exit;
    }

    public function Dashboard()
    {
        $data = array();
        // User Count
        $stmt = $this->model->GetActiveUserCount();
        foreach($stmt->result() as $row)
        {
            $data['usercount'] = $row->count;
        }
        
        // Psychologist Count
        $stmt = $this->model->GetActivePsychologist();
        foreach($stmt->result() as $row)
        {
            $data['psychcount'] = $row->count;
        }
        
        // Pending Psychologist Count
        $stmt = $this->model->GetPendingPsychologist();
        foreach($stmt->result() as $row)
        {
            $data['pendpsychcount'] = $row->count;
        }
        
        $this->load->view('Admin/AdminHeader');
        $this->load->view('Admin/Dashboard/DashboardIndex',$data);
        $this->load->view('Admin/AdminFooter');
    }
    
    public function ChatBot()
    {
        $selected = 0;
        if(isset($GLOBALS['params'][0]))
        {
            $selected = $GLOBALS['params'][0];
        }
        
        $data = array();
        $data['botId'] = "";
        $data['botTag'] = "";
        $data['title'] = "";
        $data['sequence'] = "";
        $data['follow'] = "";
        $data['content'] = "";
        $data['list'] = '';
        $stmt = $this->model->GetChatBotSequences();
        foreach($stmt->result() as $row)
        {
            if($selected == 0)
            {
                $selected = $row->id;
            }
            
            if($row->id == $selected)
            {
                $data['botId'] = $row->id;
                $data['title'] = $row->title;
                $data['botTag'] = $row->tag;
                $data['sequence'] = $row->sequence;
                $data['follow'] = $row->follow;
                $data['content'] = $row->content;
                $row->active = "active"; 
            }
            else
            {
                $row->active = "";
            }
            
            $data['list'] .= $this->load->view('Admin/ChatBot/TitleItem',$row,TRUE);
        }
        
        $data['options'] = '';
        $stmt = $this->model->GetPreferences();
        foreach($stmt->result() as $row)
        {
            $data['options'] .= $this->load->view('Admin/ChatBot/SelectItem',$row,TRUE);
        }
        
        $data['answers'] = $this->BuildAnswers();
        
        $data['sequenceList'] = '';
        if($data['sequence'] != "")
        {
            $sequenceData = json_decode($data['sequence'], TRUE);
            foreach ($sequenceData['data'] as $sequenceItem)
            {
                $sequenceItem['answers'] = $this->BuildAnswers($sequenceItem['tag']);
                $data['sequenceList'] .= $this->load->view('Admin/ChatBot/SequenceList',$sequenceItem,TRUE);
            }
        }
        
        $this->load->view('Admin/AdminHeader');
        $this->load->view('Admin/ChatBot/ChatBotIndex',$data);
        $this->load->view('Admin/AdminFooter');
    }
    
    public function BuildAnswers($selected = "")
    {
        $answers = "";
        $stmt = $this->model->GetAnswers();
        foreach ($stmt->result() as $row)
        {
            if($row->tag == $selected)
            {
               $row->selected = "selected"; 
            }
            else
            {
                $row->selected = ""; 
            }
            $answers .= $this->load->view('Admin/ChatBot/SelectAnswer',$row,TRUE);
        }
        return $answers;
    }
    
    public function addChatBotSequence()
    {
        $json_data = array();
        $json_data['success'] = FALSE;
        $json_data['id'] = $this->model->AddChatBotSequence($_POST);
        if($json_data['id'] > 0)
        {
            $json_data['success'] = TRUE;
            $json_data['message'] = "Sequence successfully added.";
        }
        echo json_encode($json_data);
        exit;
    }
    
    public function updateChatBotSequence()
    {
        $json_data = array();
        $json_data['success'] = $this->model->updateChatBotSequence($_POST);
        if($json_data['success'])
        {
            $json_data['message'] = "Sequence successfully updated";
        }
        echo json_encode($json_data);
        exit;
    }

    public function deleteChatBotSequence()
    {
        $json_data = array();
        $json_data['success'] = $this->model->DeleteChatBotSequence($_POST['id']);
        if($json_data['success'])
        {
            $json_data['message'] = "Sequence successfully deleted.";
        }
        echo json_encode($json_data);
        exit;
    }
    
    public function Preferences()
    {
        $selected = 0;
        if(isset($GLOBALS['params'][0]))
        {
            $selected = $GLOBALS['params'][0];
        }
        
        $data = array();
        $data['prefid'] = "";
        $data['title'] = "";
        $data['tag'] = "";
        $data['content'] = ""; 
        $data['list'] = '';
        $stmt = $this->model->GetPreferences();
        foreach($stmt->result() as $row)
        {
            if($selected == 0)
            {
                $selected = $row->id;
            }
            
            if($row->id == $selected)
            {
                $data['prefid'] = $row->id;
                $data['title'] = $row->title;
                $data['tag'] = $row->tag;
                $data['content'] = $row->content;
                $row->active = "active"; 
            }
            else
            {
                $row->active = "";
            }
            
            $data['list'] .= $this->load->view('Admin/Preferences/TitleItem',$row,TRUE);
        }
        
        $this->load->view('Admin/AdminHeader');
        $this->load->view('Admin/Preferences/PreferencesIndex',$data);
        $this->load->view('Admin/AdminFooter');
    }
    
    public function addPreference()
    {
        $json_data = array();
        $json_data['success'] = FALSE;
        $json_data['id'] = $this->model->AddPreference($_POST);
        if($json_data['id'] > 0)
        {
            $json_data['success'] = TRUE;
            $json_data['message'] = "Preference successfully added.";
        }
        echo json_encode($json_data);
        exit;
    }
    
    public function updatePreference()
    {
        $json_data = array();
        $json_data['success'] = $this->model->UpdatePreference($_POST);
        if($json_data['success'])
        {
            $json_data['message'] = "Preference successfully updated";
        }
        echo json_encode($json_data);
        exit;
    }

    public function deletePreference()
    {
        $json_data = array();
        $json_data['success'] = $this->model->DeletePreference($_POST['id']);
        if($json_data['success'])
        {
            $json_data['message'] = "Preference successfully deleted.";
        }
        echo json_encode($json_data);
        exit;
    }
    
    public function redirect($url)
    {
        echo '<meta http-equiv="refresh" content="0; URL='.$url.'">';
        exit;
    }
    
    public function GenericDelete()
    {
        $json_data = array();
        $json_data['success'] = $this->model->GenericDelete($_POST['id'],$_POST['table']);
        echo json_encode($json_data);
        exit;
    }
    
    public function BuildPagination($active,$total,$max,$link)
    {
        $data = array();
        $data['list'] = '';
        $rem = $total % $max;
        $total_page = ($total - $rem) / $max;
        if($rem > 0)
        {
            $total_page += 1;
        }
        
        for($i = 1; $i <= $total_page; $i++)
        {
            $page = array();
            $page['page'] = $i;
            $page['link'] = $link;
            if($active == $i)
            {
                $page['class'] = 'active';
            }
            else
            {
                $page['class'] = '';
            }
            $data['list'] .= $this->load->view('Pagination/ListItem',$page,TRUE);
        }
        return $this->load->view('Pagination/Index',$data,TRUE);
    }
}

?>