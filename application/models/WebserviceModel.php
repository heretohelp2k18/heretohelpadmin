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
            $sql = "SELECT id,firstname,username,email,position FROM app_users where username = ? and password = ?";
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
    
    public function GetStations()
    {
        try
        {
            $sql = "SELECT * FROM stations 
                    WHERE enabled = 1
                    ORDER BY station";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetBlotters()
    {
        try
        {
            $sql = "SELECT * FROM blotter 
                    WHERE enabled = 1";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetWantedList()
    {
        try
        {
            $sql = "SELECT w.id,w.lastname,w.firstname,w.middlename,w.alias,r.region,w.offenses,w.reward,w.image 
                    FROM wanted as w
                    LEFT JOIN region as r
                    ON w.region = r.id
                    WHERE enabled = 1
                    ORDER BY sort,lastname,firstname";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function SubmitReport($data)
    {
        try
        {
            extract($data);
            $date_reported = date("Y-m-d H:i:s");
            $sql = "INSERT INTO crime_reports
                    SET crime = ?,
                    user_id = ?,
                    details = ?,
                    g_lat = ?,
                    g_long = ?,
                    address = ?,
                    reporter_gps_address = ?,
                    image = ?,
                    date_reported = ?,
                    is_flag = 1";
            $stmt = $this->pdo->query($sql,array($crime,$user_id,$details,$g_lat,$g_long,$address,$reporter_gps_address,$image,$date_reported));
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
                        SET lastname = ?,
                        firstname = ?,
                        address = ?,
                        mobile = ?,
                        username = ?,
                        password = ?
                        WHERE id = ?
                        ";
                $stmt = $this->pdo->query($sql,array($lastname,$firstname,$address,$mobile,$username,$password,$id));
                return $stmt;
            }
            else
            {
                $sql = "UPDATE app_users
                        SET lastname = ?,
                        firstname = ?,
                        address = ?,
                        mobile = ?,
                        username = ?
                        WHERE id = ?
                        ";
                $stmt = $this->pdo->query($sql,array($lastname,$firstname,$address,$mobile,$username,$id));
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
    
    public function GetReportHistoryByUser($id)
    {
        try
        {
            $sql = "SELECT cr.*, count(crc.crime_report_id) as comment_count 
                    FROM crime_reports as cr 
                    LEFT JOIN crime_report_comments as crc 
                    ON cr.id = crc.crime_report_id 
                    WHERE cr.user_id = ?
                    GROUP BY cr.id
                    ORDER BY cr.id DESC";
            $stmt = $this->pdo->query($sql,array($id));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetCommentsById($id) {
        try
        {
            $sql = "SELECT crc.*, CONCAT(au.firstname,' ',au.lastname) as user_name FROM crime_report_comments as crc
                    INNER JOIN app_users as au
                    ON au.id = crc.user_id
                    WHERE crc.crime_report_id = ?
                    ORDER BY crc.id";
            $stmt = $this->pdo->query($sql,array($id));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function AddComment($data) {
        try
        {
            extract($data);
            $date_added = date('Y-m-d H:i:s');
            $sql = "INSERT INTO crime_report_comments
                    SET crime_report_id = ?,
                    user_id = ?,
                    comment = ?,
                    date_added = ?";
            $stmt = $this->pdo->query($sql,array($id,$user_id,$comment,$date_added));
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
    
    public function GetBlotterYears() {
        try
        {
            $sql = "SELECT DISTINCT(YEAR(date_of_incident)) as years 
                    FROM blotter 
                    WHERE enabled = 1 
                    ORDER BY years DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetCrimeAnalysisByDateRange($date_from,$date_to) {
        try
        {
            $sql = "SELECT c.crime as label,(SELECT COUNT(id) FROM blotter WHERE incident = c.crime
                    AND (date_of_incident >= ? AND date_of_incident <= ?)) as value 
                    FROM crimes as c
                    ORDER BY c.crime";
            $stmt = $this->pdo->query($sql,array($date_from,$date_to));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetCrimeAnalysisByDay($day) {
        try
        {
            $sql = "SELECT c.crime as label,(SELECT COUNT(id) FROM blotter WHERE incident = c.crime
                    AND date_of_incident = ?) as value 
                    FROM crimes as c
                    ORDER BY c.crime";
            $stmt = $this->pdo->query($sql,array($day));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function AddBlotter($data) {
        try
        {
            extract($data);
            $sql = "INSERT INTO blotter
                    SET incident = ?,
                    date_reported = ?,
                    time_reported = ?,
                    date_of_incident = ?,
                    time_of_incident = ?,
                    narrative = ?,
                    place_of_incident = ?,
                    g_lat = ?,
                    g_long = ?,
                    enabled = 1
                    ";
            $stmt = $this->pdo->query($sql,array(
                    $incident,
                    $date_reported,
                    $time_reported,
                    $date_of_incident,
                    $time_of_incident,
                    $narrative,
                    $place_of_incident,
                    $g_lat,
                    $g_lng
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
    
    public function UpdateBlotterEntryNumber($id,$number) {
        try
        {
            $sql = "UPDATE blotter
                    SET entry_number = ?
                    WHERE id = ?
                    ";
            $stmt = $this->pdo->query($sql,array($number,$id));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function GetCrimes() {
        try
        {
            $sql = "SELECT * FROM crimes 
                WHERE enabled = 1
                ORDER BY crime";
            $stmt = $this->pdo->query($sql);
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function AddReporting($blotter_id,$data) {
        try
        {
            extract($data);
            if(isset($r_is_victim))
            {
                $r_is_victim = 1;
            }
            else
            {
                $r_is_victim = 0;
            }
            
            $sql = "INSERT INTO reporting
                    SET blotter_id = ?,
                    lname = ?,
                    fname = ?,
                    mname = ?,
                    qualifier = ?,
                    nickname = ?,
                    citizenship = ?,
                    sex = ?,
                    status = ?,
                    birth_date = ?,
                    age = ?,
                    birth_place = ?,
                    phone = ?,
                    mobile = ?,
                    c_address = ?,
                    c_village = ?,
                    c_brgy = ?,
                    c_city = ?,
                    c_province = ?,
                    o_address = ?,
                    o_village = ?,
                    o_brgy = ?,
                    o_city = ?,
                    o_province = ?,
                    hea = ?,
                    occupation = ?,
                    id_presented = ?,
                    email = ?,
                    is_victim = ?
                    ";
            $stmt = $this->pdo->query($sql,array(
                    $blotter_id,
                    $r_lname,
                    $r_fname,
                    '',
                    '',
                    '',
                    '',
                    $r_sex,
                    $r_status,
                    '',
                    $r_age,
                    '',
                    '',
                    $r_mobile,
                    $r_address,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $r_is_victim
            ));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function AddSuspect($blotter_id,$data) {
        try
        {
            extract($data);
            if(isset($s_is_officer))
            {
                $s_is_officer = 1;
            }
            else
            {
                $s_is_officer = 0;
            }
            
            if(isset($s_is_wpcr))
            {
                $s_is_wpcr = 1;
            }
            else
            {
                $s_is_wpcr = 0;
            }
            
            if(isset($s_is_under))
            {
                $s_is_uti = 1;
            }
            else
            {
                $s_is_uti = 0;
            }
            
            $sql = "INSERT INTO suspect
                    SET blotter_id = ?,
                    lname = ?,
                    fname = ?,
                    mname = ?,
                    qualifier = ?,
                    nickname = ?,
                    citizenship = ?,
                    sex = ?,
                    status = ?,
                    birth_date = ?,
                    age = ?,
                    birth_place = ?,
                    phone = ?,
                    mobile = ?,
                    c_address = ?,
                    c_village = ?,
                    c_brgy = ?,
                    c_city = ?,
                    c_province = ?,
                    o_address = ?,
                    o_village = ?,
                    o_brgy = ?,
                    o_city = ?,
                    o_province = ?,
                    hea = ?,
                    occupation = ?,
                    work_address = ?,
                    rtv = ?,
                    email = ?,
                    is_officer = ?,
                    rank = ?,
                    unit_assigned = ?,
                    group_affiliation = ?,
                    is_wpcr = ?,
                    criminal_records = ?,
                    sopc = ?,
                    height = ?,
                    weight = ?,
                    eye_color = ?,
                    eye_desc = ?,
                    hair_color = ?,
                    hair_desc = ?,
                    is_uti = ?,
                    influence = ?
                    ";
            $stmt = $this->pdo->query($sql,array(
                    $blotter_id,
                    $s_lname,
                    $s_fname,
                    '',
                    '',
                    '',
                    '',
                    $s_sex,
                    $s_status,
                    '',
                    $s_age,
                    '',
                    '',
                    $s_mobile,
                    $s_address,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $s_is_officer,
                    '',
                    '',
                    '',
                    $s_is_wpcr,
                    '',
                    '',
                    '',
                    '',
                    $s_eye_color,
                    $s_eye_desc,
                    $s_hair_color,
                    $s_hair_desc,
                    $s_is_uti,
                    $s_under_of
            ));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function AddChildInConflict($blotter_id,$data) {
        try
        {
            extract($data);
            $sql = "INSERT INTO child_in_conflict
                    SET blotter_id = ?,
                    g_name = ?,
                    g_address = ?,
                    g_phone = ?,
                    g_mobile = ?,
                    diversion_mechanism = ?,
                    distinguishing_features = ?
                    ";
            $stmt = $this->pdo->query($sql,array(
                    $blotter_id,
                    $c_name_of_guardian,
                    $c_guardian_address,
                    '',
                    $c_mobile,
                    $c_diversion,
                    $c_dist_features
            ));
            return $stmt;
        } 
        catch (Exception $ex) 
        {
            echo $ex;
            exit;
        }
    }
    
    public function AddVictim($blotter_id,$data) {
        try
        {
            extract($data);
            $sql = "INSERT INTO victim
                    SET blotter_id = ?,
                    lname = ?,
                    fname = ?,
                    mname = ?,
                    qualifier = ?,
                    nickname = ?,
                    citizenship = ?,
                    sex = ?,
                    status = ?,
                    birth_date = ?,
                    age = ?,
                    birth_place = ?,
                    phone = ?,
                    mobile = ?,
                    c_address = ?,
                    c_village = ?,
                    c_brgy = ?,
                    c_city = ?,
                    c_province = ?,
                    o_address = ?,
                    o_village = ?,
                    o_brgy = ?,
                    o_city = ?,
                    o_province = ?,
                    hea = ?,
                    occupation = ?,
                    work_address = ?,
                    email = ?
                    ";
            $stmt = $this->pdo->query($sql,array(
                    $blotter_id,
                    $v_lname,
                    $v_fname,
                    '',
                    '',
                    '',
                    '',
                    $v_sex,
                    $v_status,
                    '',
                    $v_age,
                    '',
                    '',
                    $v_mobile,
                    $v_address,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    ''
            ));
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