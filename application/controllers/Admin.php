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
        $this->AppUsers();
    }

    public function AppUsers()
    {
        $data = array();
        $data['list'] = '';
        $stmt = $this->model->GetAppUsers();
        foreach($stmt->result() as $row)
        {
            $data['list'] .= $this->load->view('Admin/AppUsersList',$row,TRUE);
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
    
    
    
    
    
    

    public function Stations()
    {
        $data = array();
        $data['list'] = '';
        
        if(isset($_GET['page']))
        {
            $from = $_GET['page'] - 1;
        }
        else
        {
            $from = 0;
        }
        
        $max = 10;
        
        $stmt = $this->model->GetStations($from,$max);
        foreach($stmt->result() as $row)
        {
            $data['list'] .= $this->load->view('Admin/Stations/StationsList',$row,TRUE);
        }

        $data['district_list'] = $this->BuildDistrictList();
        
        $active = $from + 1;
        $link = '/admin/stations/';
        $total = $this->model->GetStationsTotal();
        $data['pagination'] = $this->BuildPagination($active,$total,$max,$link);
        
        $this->load->view('Admin/AdminHeader');
        $this->load->view('Admin/Stations/Stations',$data);
        $this->load->view('Admin/AdminFooter');
    }

    public function BuildDistrictList()
    {
        $list = '';
        $stmt = $this->model->GetDistricts();
        foreach($stmt->result() as $row)
        {
            $list .= $this->load->view('Admin/Stations/DistrictList',$row,TRUE);
        }

        return $list;
    }

    public function AddStation()
    {
        $json_data = array();
        $json_data['success'] = $this->model->AddStation($_POST);
        echo json_encode($json_data);
        exit;
    }

    public function StationsAjax()
    {
        $json_data = array();
        $json_data['list'] = '';
        $stmt = $this->model->GetStations();
        foreach($stmt->result() as $row)
        {
            $json_data['list'] .= $this->load->view('Admin/Stations/StationsList',$row,TRUE);
        }
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }

    public function GetStationById()
    {
        $json_data = array();
        $json_data['info'] = $this->model->GetStationById($_POST['id']);
        $json_data['info']['edit_id'] = $json_data['info']['id'];
        $json_data['info']['submit'] = 'Update';
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }

    public function UpdateStation()
    {
        $json_data = array();
        $json_data['success'] = $this->model->UpdateStation($_POST);;
        echo json_encode($json_data);
        exit;
    }

    public function DeleteStation()
    {
        $json_data = array();
        $json_data['success'] = $this->model->DeleteStation($_POST['id']);
        echo json_encode($json_data);
        exit;
    }
    
    public function WantedList()
    {
        $data = array();
        $data['list'] = '';
        
        if(isset($_GET['page']))
        {
            $from = $_GET['page'] - 1;
        }
        else
        {
            $from = 0;
        }
        
        $max = 10;
        
        $stmt = $this->model->GetWantedList($from,$max);
        foreach($stmt->result() as $row)
        {
            $data['list'] .= $this->load->view('Admin/Wanted/WantedList',$row,TRUE);
        }
        
        if(isset($_SESSION['message']))
        {
            $data['showMessage'] = 'true';
            $data = array_merge($data,$_SESSION['message']);
            unset($_SESSION['message']);
        }
        else
        {
            $data['showMessage'] = 'false';
            $data['title'] = '';
            $data['message'] = '';
            $data['type'] = '';
        }
        
        $data['region_list'] = $this->BuildRegions();

        $active = $from + 1;
        $link = '/admin/wantedList/';
        $total = $this->model->GetWantedTotal();
        $data['pagination'] = $this->BuildPagination($active,$total,$max,$link);
        
        $this->load->view('Admin/AdminHeader');
        $this->load->view('Admin/Wanted/Wanted',$data);
        $this->load->view('Admin/AdminFooter');
    }
    
    public function BuildRegions()
    {
        $list = '';
        $stmt = $this->model->GetRegions();
        foreach ($stmt->result() as $row)
        {
            $list .= $this->load->view('Admin/Wanted/RegionList',$row,TRUE);
        }
        return $list;
    }
    
    public function ProcessWanted()
    {
        if($_POST['submit'] == 'Submit')
        {
            $this->AddWanted();
        }
        else if($_POST['submit'] == 'Update')
        {
            $this->UpdateWanted();
        }
    }
    
    public function AddWanted()
    {
        $id = $this->model->AddWanted($_POST);
        if($id > 0)
        {
            if(trim($_FILES["upload_image"]["tmp_name"]) != '')
            {
                $result = $this->SaveImage($id);
                if($result['success'])
                {
                    $this->model->SetWantedImage($id,$result['filename']);
                }
            }

            $msg = array();
            $msg['title'] = 'Good job!';
            $msg['type'] = 'success';
            $msg['message'] = 'Wanted successfully saved.';
            $msg['url'] = '/admin/wantedList';
            $this->Message($msg);
        }
        else
        {
            $msg = array();
            $msg['title'] = 'Error!';
            $msg['type'] = 'error';
            $msg['message'] = 'Failed to save to the database.';
            $msg['url'] = '/admin/wantedList';
            $this->Message($msg);
        }
    }
    
    public function UpdateWanted()
    {
        $id = $_POST['edit_id'];
        $success = $this->model->UpdateWanted($_POST);
        if($success)
        {
            if(trim($_FILES["upload_image"]["tmp_name"]) != '')
            {
                $result = $this->SaveImage($id);
                if($result['success'])
                {
                    $this->model->SetWantedImage($id,$result['filename']);
                }
                else
                {
                    $msg = array();
                    $msg['title'] = 'Can\'t update wanted photo.!';
                    $msg['type'] = 'error';
                    $msg['message'] = $result['message'];
                    $msg['url'] = '/admin/wantedList';
                    $this->Message($msg);
                    exit;
                }
            }

            $msg = array();
            $msg['title'] = 'Good job!';
            $msg['type'] = 'success';
            $msg['message'] = 'Wanted successfully updated.';
            $msg['url'] = '/admin/wantedList';
            $this->Message($msg);
        }
        else
        {
            $msg = array();
            $msg['title'] = 'Error!';
            $msg['type'] = 'error';
            $msg['message'] = 'Failed to save to the database.';
            $msg['url'] = '/admin/wantedList';
            $this->Message($msg);
        }
    }
    
    public function Message($msg)
    {
        $_SESSION['message'] = $msg;
        redirect("/admin/wantedList");
        exit;
    }
    
    public function SaveImage($id)
    {
        $status = array();
        $status['message'] = '';
        
        $target_dir = FCPATH.'images/uploads/';
        $filename = $id.'_'.basename($_FILES["upload_image"]["name"]);
        $target_file = $target_dir.$filename;
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["upload_image"]["tmp_name"]);
            if($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $status['message'] .= "File is not an image. ";
                $uploadOk = 0;
            }
        }
        
        // Check if file already exists
        if (file_exists($target_file)) {
            $status['message'] .= "Sorry, file already exists. ";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["upload_image"]["size"] > 500000) {
            $status['message'] .= "Sorry, your file is too large (maximum of 5mb). ";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $status['message'] .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $status['message'] .= "Sorry, your file was not uploaded. ";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["upload_image"]["tmp_name"], $target_file)) {
                $status['message'] = "The file ". basename( $_FILES["upload_image"]["name"]). " has been uploaded.";
            } else {
                $status['message'] .= "Sorry, there was an error uploading your file. ";
            }
        }
        
        if($uploadOk)
        {
            $status['success'] = TRUE;
        }
        else
        {
            $status['success'] = FALSE;
        }
        
        $status['filename'] = $filename;
        return $status;
    }
    
    public function GetWantedById()
    {
        $json_data = array();
        $json_data['info'] = $this->model->GetWantedById($_POST['id']);
        $json_data['info']['edit_id'] = $json_data['info']['id'];
        $json_data['info']['submit'] = 'Update';
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function DeleteWanted()
    {
        $json_data = array();
        $json_data['success'] = $this->model->DeleteWanted($_POST['id']);
        echo json_encode($json_data);
        exit;
    }
    
    public function WantedListAjax()
    {
        $json_data = array();
        $json_data['list'] = '';
        $stmt = $this->model->GetWantedList();
        foreach($stmt->result() as $row)
        {
            $json_data['list'] .= $this->load->view('Admin/Wanted/WantedList',$row,TRUE);
        }
        
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function redirect($url)
    {
        echo '<meta http-equiv="refresh" content="0; URL='.$url.'">';
        exit;
    }
    
    public function Blotters()
    {
        $data = array();
        $data['list'] = '';
        $data['extras']= '';
        
        if(isset($_GET['page']))
        {
            $from = $_GET['page'] - 1;
        }
        else
        {
            $from = 0;
        }
        
        $max = 10;
        
        $suspect = null;
        $data['suspect'] = '';
        if(isset($_GET['suspect']))
        {
            $suspect = $_GET['suspect'];
            $data['suspect'] = '('.$suspect.')';
        }
        
        $stmt = $this->model->GetBlotters($from,$max,$suspect);
        foreach($stmt->result() as $row)
        {
            $row->type = $this->model->GetOffenseByCrime($row->incident);
            switch($row->type)
            {
                case 1:
                    $row->type = "Major";
                    break;
                case 2:
                    $row->type = "Minor";
                    break;
                default:
                    $row->type = '';
            }
            $data['list'] .= $this->load->view('Admin/Blotter/BlottersList',$row,TRUE);
        }
        
        if(isset($_GET['report_id']))
        {
            $data['extras'] = $this->load->view('Admin/Blotter/ReportExtra',array("report_id" => $_GET['report_id']),TRUE);
        }
        
        $data['crimes_list'] = $this->BuildCrimesList();
        
        if($suspect == null)
        {
            $active = $from + 1;
            $link = '/admin/blotters/';
            $total = $this->model->GetBlotterTotal();
            $data['pagination'] = $this->BuildPagination($active,$total,$max,$link);
        }
        else
        {
            $data['pagination'] = '';
        }

        $this->load->view('Admin/AdminHeader');
        $this->load->view('Admin/Blotter/Blotters',$data);
        $this->load->view('Admin/AdminFooter');
    }
    
    public function BuildCrimesList()
    {
        $options = '';
        $stmt = $this->model->GetCrimes();
        foreach($stmt->result() as $row)
        {
            $options .= $this->load->view('Admin/Crime/CrimeList',$row,TRUE);
        }
        
        return $options;
    }

    public function BlottersAjax()
    {
        $data = array();
        $data['list'] = '';
        $stmt = $this->model->GetBlotters();
        foreach($stmt->result() as $row)
        {
            $data['list'] .= $this->load->view('Admin/Blotter/BlottersList',$row,TRUE);
        }

        $data['success'] = TRUE;
        echo json_encode($data);
        exit;
    }
    
    public function AddBlotter()
    {
        $json_data = array();
        $blotter = array();
        $reporting = array();
        $suspect = array();
        $child_in_conflict = array();
        $victim = array();
        $suspect_data = array();
        $child_in_conflict_data = array();
        $victim_data = array();
        foreach($_POST as $key => $value)
        {
            $prefix = substr($key,0,2);
            if($prefix == 'r_')
            {
                $reporting[$key] = $value;
            }
            else if($prefix == 's_')
            {
                $suspect[$key] = $value;
            }
            else if($prefix == 'c_')
            {
                $child_in_conflict[$key] = $value;
            }
            else if($prefix == 'v_')
            {
                $victim[$key] = $value;
            }
            else
            {
                $blotter[$key] = $value;
            }
        }
        
        foreach ($suspect as $key => $value)
        {
            foreach ($value as $index => $input)
            {
                $suspect_data[$index][$key] =  $input;
            }
        }
        
        foreach ($child_in_conflict as $key => $value)
        {
            foreach ($value as $index => $input)
            {
                $child_in_conflict_data[$index][$key] =  $input;
            }
        }
        
        foreach ($victim as $key => $value)
        {
            foreach ($value as $index => $input)
            {
                $victim_data[$index][$key] =  $input;
            }
        }
        
        $blotter_id = $this->model->AddBlotter($blotter);
        $this->model->UpdateBlotterEntryNumber($blotter_id,($blotter_id + BLOTTER_ENTRY_NUMBER_START));
        $reporting_inserted = $this->model->AddReporting($blotter_id,$reporting);
        
        foreach ($suspect_data as $suspect)
        {
            $inserted = $this->model->AddSuspect($blotter_id,$suspect);
        }
        
        foreach ($child_in_conflict_data as $child_in_conflict)
        {
            $inserted = $this->model->AddChildInConflict($blotter_id,$child_in_conflict);
        }
        
        foreach ($victim_data as $victim)
        {
            $inserted = $this->model->AddVictim($blotter_id,$victim);
        }
        
        if($blotter_id > 0)
        {
            $json_data['success'] = TRUE;
        }
        else
        {
            $json_data['success'] = FALSE;
            $json_data['message'] = "Failed to save the blotter";
        }
        
        echo json_encode($json_data);
        exit;
    }
    
    public function UpdateBlotter()
    {
        $json_data = array();
        $blotter = array();
        $reporting = array();
        $suspect = array();
        $child_in_conflict = array();
        $victim = array();
        $suspect_data = array();
        $child_in_conflict_data = array();
        $victim_data = array();
        foreach($_POST as $key => $value)
        {
            $prefix = substr($key,0,2);
            if($prefix == 'r_')
            {
                $reporting[$key] = $value;
            }
            else if($prefix == 's_')
            {
                $suspect[$key] = $value;
            }
            else if($prefix == 'c_')
            {
                $child_in_conflict[$key] = $value;
            }
            else if($prefix == 'v_')
            {
                $victim[$key] = $value;
            }
            else
            {
                $blotter[$key] = $value;
            }
        }
        
        foreach ($suspect as $key => $value)
        {
            foreach ($value as $index => $input)
            {
                $suspect_data[$index][$key] =  $input;
            }
        }
        
        foreach ($child_in_conflict as $key => $value)
        {
            foreach ($value as $index => $input)
            {
                $child_in_conflict_data[$index][$key] =  $input;
            }
        }
        
        foreach ($victim as $key => $value)
        {
            foreach ($value as $index => $input)
            {
                $victim_data[$index][$key] =  $input;
            }
        }
        $blotter_id = $_POST['edit_id'];
        $updated = $this->model->UpdateBlotter($blotter_id,$blotter);
        $reporting_updated = $this->model->UpdateReporting($blotter_id,$reporting);
        
        foreach ($suspect_data as $suspect)
        {
            if($suspect['s_edit_id'] == 0)
            {
                $inserted = $this->model->AddSuspect($blotter_id,$suspect);
            }
            else
            {
                $updated = $this->model->UpdateSuspect($suspect);
            }
        }
        
        foreach ($child_in_conflict_data as $child_in_conflict)
        {
            if($child_in_conflict['c_edit_id'] == 0)
            {
                $inserted = $this->model->AddChildInConflict($blotter_id,$child_in_conflict);
            }
            else
            {
                $updated = $this->model->UpdateChildInConflict($child_in_conflict);
            }
        }
        
        foreach ($victim_data as $victim)
        {
            if($victim['v_edit_id'] == 0)
            {
                $inserted = $this->model->AddVictim($blotter_id,$victim);
            }
            else
            {
                $updated = $this->model->UpdateVictim($victim);
            }
        }
        
        if($updated)
        {
            $json_data['success'] = TRUE;
        }
        else
        {
            $json_data['success'] = FALSE;
            $json_data['message'] = "Failed to save the blotter";
        }
        
        echo json_encode($json_data);
        exit;
    }
    
    public function DeleteBlotter()
    {
        $json_data = array();
        $json_data['success'] = $this->model->DeleteBlotter($_POST['id']);
        echo json_encode($json_data);
        exit;
    }
    
    public function GetBlotterById()
    {
        $json_data = array();
        $json_data['info'] = $this->model->GetBlotterById($_POST['id']);
        $json_data['info']['edit_id'] = $json_data['info']['id'];
        $json_data['info']['submit'] = 'Update Blotter';
        $json_data['info']['type_of_incident'] = $json_data['info']['incident'];
        
        $json_data['reporting'] = array();
        $reporting_data = $this->model->GetReporterByBlotterId($_POST['id']);
        foreach ($reporting_data as $key => $value)
        {
            $json_data['reporting']['r_'.$key] = $value;
        }
        
        //suspect data
        $suspects_data = $this->model->GetSuspectsByBlotterId($_POST['id']);
        $json_data['suspect_data_list'] = array();
        $ctr = 1;
        foreach ($suspects_data as $key => $row)
        {
            $row->ctr = $ctr;
            if($row->is_officer == 1)
            {
                $row->officer_checked = 'checked';
            }
            else
            {
                $row->officer_checked = '';
            }
            
            if($row->is_wpcr == 1)
            {
                $row->wpcr_checked = 'checked';
            }
            else
            {
                $row->wpcr_checked = '';
            }
            
            if($row->is_uti == 1)
            {
                $row->influence_checked = 'checked';
            }
            else
            {
                $row->influence_checked = '';
            }
            
            $suspectView = $this->load->view('Admin/Blotter/SuspectTemplate',$row,TRUE);
            array_push($json_data['suspect_data_list'], $suspectView);
            $ctr++;
        }
        
        //victim data
        $victim_data = $this->model->GetVictimByBlotterId($_POST['id']);
        $json_data['victim_data_list'] = array();
        $ctr = 1;
        foreach ($victim_data as $key => $row)
        {
            $row->ctr = $ctr;
            
            $victimView = $this->load->view('Admin/Blotter/VictimTemplate',$row,TRUE);
            array_push($json_data['victim_data_list'], $victimView);
            $ctr++;
        }
        
        //child in conflict with the law data
        $child_data = $this->model->GetChildByBlotterId($_POST['id']);
        $json_data['child_data_list'] = array();
        $ctr = 1;
        foreach ($child_data as $key => $row)
        {
            $row->ctr = $ctr;
            
            $childView = $this->load->view('Admin/Blotter/ChildTemplate',$row,TRUE);
            array_push($json_data['child_data_list'], $childView);
            $ctr++;
        }
        
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function PrintBlotter()
    {
        $id = $GLOBALS['params'][0];
        $json_data = array();
        $json_data['info'] = $this->model->GetBlotterById($id);
        $json_data['info']['entry_number'] = $json_data['info']['id'] + BLOTTER_ENTRY_NUMBER_START;
        $json_data['info']['type_of_incident'] = $json_data['info']['incident'];
        
        $json_data['info']['type'] = $this->model->GetOffenseByCrime($json_data['info']['incident']);
        switch($json_data['info']['type'])
        {
            case 1:
                $json_data['info']['type'] = "Major";
                break;
            case 2:
                $json_data['info']['type'] = "Minor";
                break;
            default:
                $json_data['info']['type'] = '';
        }
        
        $json_data['info']['date_reported'] = date("M d o", strtotime($json_data['info']['date_reported']));
        $json_data['info']['date_of_incident'] = date("M d o", strtotime($json_data['info']['date_of_incident']));
        
        $json_data['reporting'] = array();
        $reporting_data = $this->model->GetReporterByBlotterId($id);
        $json_data['reporting_view'] = $this->load->view('Admin/Blotter/PrintView/ReportingTemplate',$reporting_data,TRUE);
        
        //suspect data
        $suspects_data = $this->model->GetSuspectsByBlotterId($id);
        $json_data['suspect_data_list'] = '';
        $ctr = 1;
        foreach ($suspects_data as $key => $row)
        {
            $row->ctr = $ctr;
            if($row->is_officer == 1)
            {
                $row->officer_checked = 'Yes';
            }
            else
            {
                $row->officer_checked = 'No';
            }
            
            if($row->is_wpcr == 1)
            {
                $row->wpcr_checked = 'Yes';
            }
            else
            {
                $row->wpcr_checked = 'No';
            }
            
            if($row->is_uti == 1)
            {
                $row->influence_checked = 'Yes';
            }
            else
            {
                $row->influence_checked = 'No';
            }
            
            $suspectView = $this->load->view('Admin/Blotter/PrintView/SuspectTemplate',$row,TRUE);
            $json_data['suspect_data_list'] .= $suspectView;
            $ctr++;
        }
        
        //victim data
        $victim_data = $this->model->GetVictimByBlotterId($id);
        $json_data['victim_data_list'] = '';
        $ctr = 1;
        foreach ($victim_data as $key => $row)
        {
            $row->ctr = $ctr;
            
            $victimView = $this->load->view('Admin/Blotter/PrintView/VictimTemplate',$row,TRUE);
            $json_data['victim_data_list'] .= $victimView;
            $ctr++;
        }
        
        //child in conflict with the law data
        $child_data = $this->model->GetChildByBlotterId($id);
        $json_data['child_data_list'] = '';
        $ctr = 1;
        foreach ($child_data as $key => $row)
        {
            $row->ctr = $ctr;
            
            $childView = $this->load->view('Admin/Blotter/PrintView/ChildTemplate',$row,TRUE);
            $json_data['child_data_list'] .= $childView;
            $ctr++;
        }
        
        $this->load->view('Admin/Blotter/PrintView/Index',$json_data);
    }
    
    public function PrintWanted()
    {
        if(isset($GLOBALS['params'][0]))
        {
            $id = $GLOBALS['params'][0];
            $data = $this->model->GetWantedById($id);
            $data['region_caption'] = $this->model->GetRegionById($data['region']);
            $img = FCPATH."images/uploads/".$data['image'];
            if(file_exists($img) && (trim($data['image']) != ''))
            {
                $data['image'] = '<img src="/images/uploads/'.$data['image'].'">';
            }
            else
            {
                $data['image'] = '<img src="/images/wanted_default.png">';
            }
                
            if($data)
            {
                $this->load->view('Admin/Wanted/PrintView/Index',$data);
            }
        }
        else
        {
            echo "Invalid action!";
        }
    }

    public function GenericDelete()
    {
        $json_data = array();
        $json_data['success'] = $this->model->GenericDelete($_POST['id'],$_POST['table']);
        echo json_encode($json_data);
        exit;
    }
    
    public function CrimeReports()
    {
        $data = array();
        $data['list'] = '';
        
        if(isset($_GET['page']))
        {
            $from = $_GET['page'] - 1;
        }
        else
        {
            $from = 0;
        }
        
        $max = 10;
        
        $stmt = $this->model->GetCrimeReports($from,$max);
        foreach($stmt->result() as $row)
        {
            if($row->is_flag == 1)
            {
                $row->flag = '<i style="color: #e74c3c;" class="fa fa-flag" aria-hidden="true"></i>';
            }
            else
            {
                $row->flag = '';
            }
            
            $row->type = $this->model->GetOffenseByCrime($row->crime);
            switch($row->type)
            {
                case 1:
                    $row->type = "Major";
                    break;
                case 2:
                    $row->type = "Minor";
                    break;
                default:
                    $row->type = '';
            }
            
            $data['list'] .= $this->load->view('Admin/CrimeReports/CrimeReportsList',$row,TRUE);
        }
        
        $active = $from + 1;
        $link = '/admin/crimeReports/';
        $total = $this->model->GetCrimeReportTotal();
        $data['pagination'] = $this->BuildPagination($active,$total,$max,$link);

        $this->load->view('Admin/AdminHeader');
        $this->load->view('Admin/CrimeReports/CrimeReports',$data);
        $this->load->view('Admin/AdminFooter');
    }
    
    public function CrimeReportView()
    {
        if(isset($GLOBALS['params'][0]))
        {
            $id = $GLOBALS['params'][0];
            $this->model->SetFlag($id,0);
            $data = $this->model->GetCrimeReportById($id);
            $img = FCPATH."images/reports/".$data->image;
            if(file_exists($img) && (trim($data->image) != ''))
            {
                $data->image = '<img style="max-width:300px;" src="/images/reports/'.$data->image.'">';
            }
            else
            {
                $data->image = '<i>No image...</i>';
            }
            
            $data->type = $this->model->GetOffenseByCrime($data->crime);
            switch($data->type)
            {
                case 1:
                    $data->type = "Major";
                    break;
                case 2:
                    $data->type = "Minor";
                    break;
                default:
                    $data->type = '';
            }
            
            $this->load->view('Admin/AdminHeader');
            $this->load->view('Admin/CrimeReports/CrimeReportsView',$data);
            $this->load->view('Admin/AdminFooter');
        }
        else
        {
            redirect('/admin');
        }
    }
    
    public function CrimeAnalysis()
    {
        $data = array();
        $data['current_month'] = date('m');
        $data['years'] = $this->BuildBlotterYears();
        $this->load->view('Admin/AdminHeader');
        $this->load->view('Admin/CrimeAnalysis/CrimeAnalysisIndex',$data);
        $this->load->view('Admin/AdminFooter');
    }
    
    public function BuildBlotterYears()
    {
        $options = '<option value="">Select Year</option>';
        $current_year = date('Y');
        $years = $this->model->GetBlotterYears();
        foreach ($years->result() as $y)
        {
            if($current_year == $y->years)
            {
                $options .= '<option value="'.$y->years.'" selected>'.$y->years.'</option>';
            }
            else
            {
                $options .= '<option value="'.$y->years.'">'.$y->years.'</option>';
            }
        }
        return $options;
    }
    
    public function AddComment()
    {
        $_POST['user_id'] = $_SESSION['admin']['user_id'];
        $json_data = array();
        $json_data['success'] = $this->model->AddComment($_POST);
        echo json_encode($json_data);
        exit;
    }
    
    public function GetComments()
    {
        $id = $_POST['id'];
        $json_data = array();
        $json_data['success'] = TRUE;
        $json_data['comments'] = '';
        $stmt = $this->model->GetCommentsById($id);
        foreach($stmt->result() as $row)
        {
            $json_data['comments'] .= $this->load->view('Admin/CrimeReports/CommentList',$row,TRUE);
        }
        echo json_encode($json_data);
        exit;
    }
    
    public function DeleteCrimeReport()
    {
        $json_data = array();
        $json_data['success'] = $this->model->DeleteCrimeReport($_POST['id']);
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
    
    public function GetCrimeReportById()
    {
        $id = $_POST['id'];
        $json_data = array();
        $json_data['info'] = $this->model->GetCrimeReportById($id);
        if($json_data['info'])
        {
            $json_data['info']->date = date("Y-m-d",strtotime($json_data['info']->date_reported));
            $json_data['info']->time = date("h:i:s",strtotime($json_data['info']->date_reported));
        }
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function Offences()
    {
        $data = array();
        $data['list'] = '';
        
        if(isset($_GET['page']))
        {
            $from = $_GET['page'] - 1;
        }
        else
        {
            $from = 0;
        }
        
        $max = 10;
        
        $stmt = $this->model->GetCrimesRange($from,$max);
        foreach($stmt->result() as $row)
        {
            switch($row->type)
            {
                case 1:
                    $row->type = 'Major';
                    break;
                case 2:
                    $row->type = 'Minor';
                    break;
            }
            $data['list'] .= $this->load->view('Admin/Crime/CrimeRow',$row,TRUE);
        }
        
        $active = $from + 1;
        $link = '/admin/offences/';
        $total = $this->model->GetOffencesTotal();
        $data['pagination'] = $this->BuildPagination($active,$total,$max,$link);
        
        $this->load->view('Admin/AdminHeader');
        $this->load->view('Admin/Crime/CrimeIndex',$data);
        $this->load->view('Admin/AdminFooter');
    }
    
    public function SaveOffense()
    {
        $json_data = array();
        $json_data['success'] = $this->model->SaveOffense($_POST);
        echo json_encode($json_data);
        exit;
    }
    
    public function UpdateOffense()
    {
        $json_data = array();
        $json_data['success'] = $this->model->UpdateOffense($_POST);
        echo json_encode($json_data);
        exit;
    }
    
    public function GetOffenseById()
    {
        $json_data = array();
        $json_data['info'] = $this->model->GetOffenseById($_POST['id']);
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function DeleteOffense()
    {
        $json_data = array();
        $json_data['success'] = $this->model->DeleteOffense($_POST['id']);
        echo json_encode($json_data);
        exit;
    }
    
    public function SearchCrimeReport()
    {
        $json_data = array();
        $json_data['list'] = '';
        $stmt = $this->model->GetCrimeReportsSearch($_POST['key']);
        foreach($stmt->result() as $row)
        {
            if($row->is_flag == 1)
            {
                $row->flag = '<i style="color: #e74c3c;" class="fa fa-flag" aria-hidden="true"></i>';
            }
            else
            {
                $row->flag = '';
            }
            
            $row->type = $this->model->GetOffenseByCrime($row->crime);
            switch($row->type)
            {
                case 1:
                    $row->type = "Major";
                    break;
                case 2:
                    $row->type = "Minor";
                    break;
                default:
                    $row->type = '';
            }
            
            $json_data['list'] .= $this->load->view('Admin/CrimeReports/CrimeReportsList',$row,TRUE);
        }
        if(trim($json_data['list']) == '')
        {
            $json_data['list'] = "<tr><td colspan='5'>No result found...</td></tr>";
        }
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function Suspects()
    {
        $data = array();
        $data['list'] = '';
        
        if(isset($_GET['page']))
        {
            $from = $_GET['page'] - 1;
        }
        else
        {
            $from = 0;
        }
        
        $max = 10;
        
        $stmt = $this->model->GetSuspects($from,$max);
        foreach($stmt->result() as $row)
        { 
            $data['list'] .= $this->load->view('Admin/Suspects/List',$row,TRUE);
        }
        
        $active = $from + 1;
        $link = '/admin/Suspects/';
        $total = $this->model->GetSuspectsTotal();
        $data['pagination'] = $this->BuildPagination($active,$total,$max,$link);

        $this->load->view('Admin/AdminHeader');
        $this->load->view('Admin/Suspects/Index',$data);
        $this->load->view('Admin/AdminFooter');
    }
}

?>