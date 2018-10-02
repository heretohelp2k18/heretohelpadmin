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
                $json_data['id'] = $stmt[0]->id;
                $json_data['firstname'] = $stmt[0]->firstname;
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
    
    public function generateAppToken($userID)
    {
        $timestamp = time();
        $token = sha1($timestamp);
        $this->model->UpdateAppToken($userID, $token);
        return $token;
    }


    public function GetStations()
    {
        $json_data = array();
        $json_data['list'] = array();
        $stmt = $this->model->GetStations();
        foreach($stmt->result() as $row)
        {
            array_push($json_data['list'], $row);
        }
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function GetBlotters()
    {
        $json_data = array();
        $json_data['list'] = array();
        $stmt = $this->model->GetBlotters();
        foreach($stmt->result() as $row)
        {
            $row->date_of_incident = date("M d o", strtotime($row->date_of_incident));
            $row->time_of_incident = date("h:i a", strtotime($row->time_of_incident));
            
            array_push($json_data['list'], $row);
        }
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function GetWanteds()
    {
        $json_data = array();
        $json_data['list'] = array();
        $stmt = $this->model->GetWantedList();
        foreach($stmt->result() as $row)
        {
            $img = FCPATH."images/uploads/".$row->image;
            if(!file_exists($img) || (trim($row->image) == ''))
            {
                $row->image = 'img/wanted_default.png';
            }
            else
            {
                $row->image = 'http://'.$_SERVER['HTTP_HOST'].'/images/uploads/'.$row->image;
            }
            
            array_push($json_data['list'], $row);
        }
        
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function UploadFromCam()
    {
        $file = $_GET['filename'].'.jpg';
        move_uploaded_file($_FILES["file"]["tmp_name"], FCPATH.'images/reports/'.$file);
    }
    
    public function SubmitReport()
    {
        $json_data = array();
        if(trim($_POST['image']) != '')
        {
            $_POST['image'] = $_POST['image'].'.jpg';
        }
        
        $json_data['success'] = $this->model->SubmitReport($_POST);
        echo json_encode($json_data);
        exit;
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
                $json_data['message'] = "Your account has been successfully created! You may now login.";
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
    
    public function GetReportHistory()
    {
        $id = $_POST['id'];
        $json_data = array();
        $json_data['list'] = array();
        $stmt = $this->model->GetReportHistoryByUser($id);
        foreach ($stmt->result() as $row)
        {
            $row->date_reported = date("M d o", strtotime($row->date_reported));
            
            $img = FCPATH."images/reports/".$row->image;
            if(!file_exists($img) || (trim($row->image) == ''))
            {
                $row->image = '';
            }
            else
            {
                $row->image = 'http://'.$_SERVER['HTTP_HOST'].'/images/reports/'.$row->image;
            }
            
            array_push($json_data['list'], $row);
        }
        if(!empty($json_data['list']))
        {
            $json_data['success'] = TRUE;
        }
        else
        {
            $json_data['success'] = FALSE;
            $json_data['message'] = "No history found.";
        }
        echo json_encode($json_data);
        exit;
    }
    
    public function GetReportComments()
    {
        $id = $_POST['id'];
        $json_data = array();
        $json_data['success'] = TRUE;
        $json_data['comments'] = array();
        $stmt = $this->model->GetCommentsById($id);
        foreach($stmt->result() as $row)
        {
            $row->date_added = date("M d o", strtotime($row->date_added));
            array_push($json_data['comments'],$row);
        }
        echo json_encode($json_data);
        exit;
    }
    
    public function PostComment()
    {
        $json_data = array();
        $json_data['success'] = $this->model->AddComment($_POST);
        echo json_encode($json_data);
        exit;
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
    
    public function GetBlotterYears()
    {
        $json_data = array();
        $json_data['current_month'] = date('m');
        $json_data['list'] = '<option value="">Select Year</option>';
        $current_year = date('Y');
        $years = $this->model->GetBlotterYears();
        foreach ($years->result() as $y)
        {
            if($current_year == $y->years)
            {
                $json_data['list'] .= '<option value="'.$y->years.'" selected>'.$y->years.'</option>';
            }
            else
            {
                $json_data['list'] .= '<option value="'.$y->years.'">'.$y->years.'</option>';
            }
        }
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function GetCrimeAnalysisByMonth()
    {
        $date_from = date('Y-m-d',strtotime($_POST['year'].'-'.$_POST['month'].'-1'));
        $date_to = date("Y-m-t", strtotime($date_from));
        $json_data = array();
        $content = $this->model->GetCrimeAnalysisByDateRange($date_from,$date_to);
        $json_data['content'] = $content->result();
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function GetCrimeAnalysisByYear()
    {
        $date_from = date('Y-m-d',strtotime($_POST['year'].'-01-1'));
        $date_to = date('Y-m-d',strtotime($_POST['year'].'-12-31'));
        $json_data = array();
        $content = $this->model->GetCrimeAnalysisByDateRange($date_from,$date_to);
        $json_data['content'] = $content->result();
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function GetCrimeAnalysisByWeek()
    {
        $week_data = explode('-', $_POST['week']);
        $year = $week_data[0];
        $week = $week_data[1];
        $week = ltrim($week, 'W');

        $date_from = date('Y-m-d', strtotime($year."W".str_pad($week,2,"0",STR_PAD_LEFT)));
        $date_to =  date('Y-m-d', strtotime("+6 days", strtotime($date_from)));
        
        $json_data = array();
        $content = $this->model->GetCrimeAnalysisByDateRange($date_from,$date_to);
        $json_data['content'] = $content->result();
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function GetCrimeAnalysisByDay()
    {
        $day = $_POST['day'];
        $json_data = array();
        $content = $this->model->GetCrimeAnalysisByDay($day);
        $json_data['content'] = $content->result();
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function SubmitBlotter()
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
        
        $blotter_id = $this->model->AddBlotter($blotter);
        $this->model->UpdateBlotterEntryNumber($blotter_id,($blotter_id + BLOTTER_ENTRY_NUMBER_START));
        $this->model->AddReporting($blotter_id,$reporting);
        $this->model->AddSuspect($blotter_id,$suspect);
        $this->model->AddChildInConflict($blotter_id,$child_in_conflict);
        $this->model->AddVictim($blotter_id,$victim);
        
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
    
    public function GetCrimes()
    {
        $json_data = array();
        $json_data['list'] = array();
        $stmt = $this->model->GetCrimes();
        foreach ($stmt->result() as $row)
        {
            switch ($row->type)
            {
                case 1:
                    $row->type_caption = 'Major';
                    break;
                case 2:
                    $row->type_caption = 'Minor';
                    break;
            }
            array_push($json_data['list'], $row);
        }
        $json_data['success'] = TRUE;
        echo json_encode($json_data);
        exit;
    }
}
