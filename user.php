<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
    public function dashboard(){
        $data["user_info"]=$this->session_check();
        
    	$this->load->view("user/dashboard.php",$data);
    }


    public function messages($chat_id=0){
        $data["user_info"]=$this->session_check();
        if($chat_id==0){
            echo "tanimsiz";
        }else{
            $this->load->model("chat_model");
            $data["messages"]=$this->chat_model->get_all(array('chat_id' => $chat_id));
        }
        $this->load->view("user/messages.php",$data);
    }

    public function send_messages($chat_id){
        $data["user_info"]=$this->session_check();
        $this->load->model("chat_model");
        $sender=$data["user_info"]->id;
        $this->load->helper("date");
        $date=time();
        $message=$_POST["message"];
        $this->chat_model->add(array('sender' => $sender,'date' => $date,'message' => $message,'chat_id' => $chat_id));
        echo "ok";
    }

    public function get_messages($chat_id){
        $data["user_info"]=$this->session_check();
        $this->load->model("chat_model");
        $messages=$this->chat_model->get_all( array("chat_id"=>$chat_id));
        echo json_encode($messages);
    }


    public function close_introduction(){
        $data["user_info"]=$this->session_check();
        $this->load->model("user_model");
        $this->user_model->update(array("id"=>$data["user_info"]->id),array("introduction"=>"1"));
    }


    public function activate($passcode){
        $this->load->model("user_model");
        $this->user_model->update(array("passcode"=>$passcode),array("email_confirm"=>1));
        redirect(base_url("user"));
        }


    public function active_jobs(){
        $data["user_info"]=$this->session_check();
        $this->load->model("demographical_model");
        $this->load->model("relative_model");
        $this->lang->load('language', $data["user_info"]->language);
    	$this->load->model("jobs_model");
        $demographical_info=$this->demographical_model->get(array("user_id"=>$data["user_info"]->id));

        ///query cumlesi
        $query = "SELECT * FROM jobs WHERE ";
        $query .= "(status= 'open')";
        $query .= "AND (age_min<".$demographical_info->age." AND age_max>".$demographical_info->age.")";

        if($demographical_info->gender=="none"){
        $query .= "AND (gender LIKE '%"."men"."%' AND gender LIKE '%"."women"."%')";
        }else{
        $query .= "AND (gender LIKE '%".$demographical_info->gender."%')";
        }
        $query .= "AND (living_area LIKE '%".$demographical_info->living_area."%')";
        $query .= "AND (employment LIKE '%".$demographical_info->employment."%')";
        $query .= "AND (employment_type LIKE '%".$demographical_info->employment_type."%')";
        $query .= "AND (relationship LIKE '%".$demographical_info->relationship."%')";
        $query .= "AND (children_type LIKE '%".$demographical_info->children_type."%')";

        //$query .= "AND (children_ages_min<".$demographical_info->age." AND children_ages_max>".$demographical_info->age.")";
        $query .= "AND (social_media_type LIKE '%".$demographical_info->social_media_type."%')";

        $hobbies_array= json_decode($demographical_info->hobbies);
        $query .= "AND (";
        for ($i=0; $i < count($hobbies_array); $i++) { 
            if ($i!=0) {
                $query .= " OR ";
            }
            $query .= " (hobbies LIKE '%".$hobbies_array[$i]."%')";
        }
        $query .= ")";


        $professional_interests= json_decode($demographical_info->professional_interests);
        $query .= "AND (";
        for ($i=0; $i < count($professional_interests); $i++) { 
            if ($i!=0) {
                $query .= " OR ";
            }
            $query .= " (professional_interests LIKE '%".$professional_interests[$i]."%')";
        }
        $query .= ")";
        //quert cumelsi
        $relative_jobs=$this->relative_model->get_all(array("user_id"=>$data["user_info"]->id));
    	$jobs=$this->jobs_model->query($query);
//print_r($relative_jobs);
$relative_jobs_ids=array();
for ($i=0; $i < count($relative_jobs); $i++) { 
    $relative_jobs_ids[]=$relative_jobs[$i]->job_id;
}
$jobs_ids=array();
for ($i=0; $i < count($jobs); $i++) { 
    $jobs_ids[]=$jobs[$i]->id;
}

for ($b=0; $b < count($relative_jobs_ids); $b++) { 
    if (in_array($relative_jobs_ids[$b],$jobs_ids)) {
unset($jobs[array_search($relative_jobs_ids[$b], $jobs_ids)]);
    }
}


function reindex(&$the_array) {
$temp = $the_array;
$the_array = array();
foreach($temp as $value) {
$the_array[] = $value; 
} 
}


reindex($jobs);
                                echo '
                                <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">First</th>
                                                <th scope="col">Last</th>
                                                <th scope="col">Handle</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                        $a=0;
                                        while($a<=count($jobs)){
                                            if(isset($jobs[$a]->id)){
                                                echo '<tr>
                                                        <th scope="row">'.$jobs[$a]->id.'</th>
                                                        <td>'.$jobs[$a]->website.'</td>
                                                        <td>'.$jobs[$a]->work_times.'</td>
                                                        <td><a href="'.base_url("user/apply/".$jobs[$a]->id).'"<button class="btn btn-primary">'."APPLY".'</button></a></td>
                                                    </tr>';
                                                }
                                                $a++;
                                                
                                        }
                                        if(count($jobs)==0){
                                            echo '<tr>
                                                <td colspan="4"><center>AKTIF IS YOK</center></td>
                                            </tr>';
                                        }
                                        echo '
                                    </tbody>
                                </table>
';
    }


    public function apply($job_id=0){

        $data["user_info"]=$this->session_check();
        $this->lang->load('language', $data["user_info"]->language);
        $this->load->model("jobs_model");
        $this->load->model("relative_model");
        $job_info=$this->relative_model->get_all(array('id' =>  $job_id));
        $this->relative_model->add(array('job_id' => $job_id,'user_id' => $data["user_info"]->id));
        $db_error=$this->db->_error_number(); 
        
        if($db_error>0){
            echo $this->db->_error_message();
            echo $db_error;
            echo "bla";
        }else{
            redirect(base_url("user/do_job/".$job_id));
        }
    }

    public function job_history(){
        $data["user_info"]=$this->session_check();
        $this->lang->load('language',$data["user_info"]->language);
        $this->load->model("relative_model");
        $this->load->model("jobs_model");
        $apply_jobs=$this->relative_model->get_all(array("user_id"=>$data["user_info"]->id));
        for ($e=0; $e < count($apply_jobs); $e++) { 
            $job_info=$this->jobs_model->get(array("id"=>$apply_jobs[$e]->job_id));
            $apply_jobs[$e]->website=$job_info->website;
            $apply_jobs[$e]->profit=$job_info->profit;
        }
        $data["apply_jobs"]=$apply_jobs;
        $this->load->view("user/job_history.php",$data);
    }

    public function payments(){
        $data["user_info"]=$this->session_check();
        
        $this->load->model("payments_model");
        $this->load->model("user_model");
        $this->load->model("jobs_model");
        
        $this->lang->load('language', $data["user_info"]->language);
        $data["payments"]=$this->payments_model->query("SELECT * FROM payment_history WHERE (sender_id='".$data["user_info"]->id."' OR receiver_id='".$data["user_info"]->id."') ORDER BY date DESC ");

        for ($a=0; $a < count($data["payments"]); $a++) { 
            if($data["payments"][$a]->sender_id==$data["user_info"]->id){
                $data["payments"][$a]->sender="1";
                //means sender
            }else{
                $data["payments"][$a]->sender="0";
                //means receiver
            }
            $job_info=$this->jobs_model->get(array("id"=>$data["payments"][$a]->job_id));
            $data["payments"][$a]->website=@$job_info->website;
        }
        $data["account"]=$this->user_model->get(array("id"=>$data["user_info"]->id));
        $this->load->view("user/payments.php",$data);
    }

    public function demographical(){
        $data["user_info"]=$this->session_check();
        $this->lang->load('language',$data["user_info"]->language);
        $this->load->model("demographical_model");
        $this->load->model("hobbies_model");
        $this->load->model("professional_interest_model");
        $data["hobbies"]=$this->hobbies_model->get_all();
        $data["professional_interests"]=$this->professional_interest_model->get_all();
        if(count($_POST)>0){
            if(!isset($_POST["hobbies"]) or $_POST["hobbies"]==""){
                $_POST["hobbies"][0]="null";
            } 
            if(!isset($_POST["professional_interests"]) or $_POST["professional_interests"]==""){
                $_POST["professional_interests"][0]="null";
            } 
            $_POST["hobbies"]=json_encode($_POST["hobbies"]);
            $_POST["professional_interests"]=json_encode($_POST["professional_interests"]);
            $result=$this->demographical_model->update(array("user_id"=>$data["user_info"]->id),$_POST);
            $demographical_info=$this->demographical_model->get(array("user_id"=>$data["user_info"]->id));
            $data["result"]=$result;
            $data["demographical_info"]=$demographical_info;
            $this->load->view("user/demographical.php",$data);
        }else{
        $demographical_info=$this->demographical_model->get(array("user_id"=>$data["user_info"]->id));
        $data["demographical_info"]=$demographical_info;
        $this->load->view("user/demographical.php",$data);
        }
    }

    public function settings(){
        $data["user_info"]=$this->session_check();
        $this->lang->load('language',$data["user_info"]->language);
        $this->load->model("user_model");
        if(count($_POST)>0 and $_POST["password"]==$_POST["password_confirm"] and $_POST["password"]!=""){
            $_POST["password"]=md5($_POST["password"]);
            unset($_POST["password_confirm"]);
            $_POST["email_confirm"]=0;
            $result=$this->user_model->update(array("id"=>$data["user_info"]->id),$_POST);
            $user_info=$this->user_model->get(array("id"=>$data["user_info"]->id));
            $data["result"]=$result;
            $data["user_model"]=$user_info;
            $this->load->view("user/settings.php",$data);
        }else if(count($_POST)>0){
            $data["error"]=1;
            $this->load->view("user/settings.php",$data);
        }else{
        $user_info=$this->user_model->get(array("id"=>$data["user_info"]->id));
        $data["user_model"]=$user_info;
        $this->load->view("user/settings.php",$data);
        }
    }
    public function do_job($jobid=0){
        $data["user_info"]=$this->session_check();
        $this->lang->load('language',$data["user_info"]->language);
        $this->load->model("user_model");
        $this->load->model("relative_model");
        $this->load->model("jobs_model");
        $this->load->model("job_result_model");
        $job_info=$this->jobs_model->get(array('id' => $jobid ));
        if(count($job_info)){
        $status=$job_info->status;
        }
            $job_results=$this->job_result_model->query("SELECT * FROM job_result WHERE user_id=".$data["user_info"]->id." AND job_id=".$jobid." ORDER BY section DESC");
            $job_info=$this->jobs_model->get(array('id' => $jobid));
            $data["total_section"]=$job_info->section_number;

            $data["jobid"]=$jobid;
            $this->load->view("user/do_job.php",$data);
        
    }
    public function job_info($jobid){
        $data["user_info"]=$this->session_check();
        $this->load->model("jobs_model");
        $this->load->model("job_result_model");
        $query="SELECT * FROM job_result WHERE job_id='".$jobid."' AND user_id='".$data["user_info"]->id."' ORDER BY section DESC LIMIT 1";
        $job_results=$this->job_result_model->query($query);
        $job_info=$this->jobs_model->get(array('id' => $jobid));
        if(count($job_results)==0){
            $job_info->now_section=-1;
            $job_info->now_work_name="5 sn icinde hatirladiklariniz yaziniz";
            $job_info->now_work_question="5 sn icinde ne hatirladiniz";
            $result = json_encode($job_info);
        }else{
            $job_info->now_section=$job_results[0]->section;
            $job_info->now_section++;
                $job_info->now_work_name=json_decode(json_decode($job_info->work_names,true))[$job_info->now_section];

                $job_info->now_work_question=json_decode(json_decode($job_info->work_questions,true))[$job_info->now_section];
                
            if($job_info->now_work_name==""){
                $job_info->job_is_done=1;
            }else{
                $job_info->job_is_done=0;
            }
            $result= json_encode($job_info);
        }
        print_r($result);
    }
    public function upload_video($jobid=0,$sectionid=0){

        $data["user_info"]=$this->session_check();
        foreach(array('video', 'audio') as $type) {
            if (isset($_FILES["${type}-blob"])) {
            
                echo 'uploads/';
                
                $fileName = $_POST["${type}-filename"];
                $uploadDirectory = './uploads/'.$fileName;
                
                if (!move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $uploadDirectory)) {
                    echo(" problem moving uploaded file");
                }
                
                echo($fileName);
            }
        }
        $this->load->model("user_model");
        $this->load->model("relative_model");
        $this->load->model("jobs_model");
        $this->load->model("job_result_model");
        
        $job_result_info=$this->job_result_model->get(array("user_id"=>$data["user_info"]->id,"job_id"=>$jobid));

    $this->job_result_model->add(array("user_id"=>$data["user_info"]->id,"job_id"=>$jobid,'video_url' => base_url("uploads/")."/".$fileName ,'section' => $sectionid,'payment' => "not_paid"));

        
    }

    public function work_question($jobid=0,$section=0){
        $data["user_info"]=$this->session_check();
        $this->load->model("job_result_model");
        $this->load->model("jobs_model");
        $job_result_info=$this->job_result_model->get(array('user_id' => $data["user_info"]->id,'job_id' => $jobid));
        if(isset($job_result_info->id)){
            $job_update=$this->job_result_model->update(array('user_id' => $data["user_info"]->id,'job_id' => $jobid,'section' => $section),array('work_results' => $_POST["rating"]));
            if($this->db->_error_number()>0){
                ///there is a error     
            }else{
                ///there is a no error
            }
        }else{

            ////buraya gelecek

            $job_update=$this->job_result_model->add(array('user_id' => $data["user_info"]->id,'job_id' => $jobid,'section' => $section,'work_results' => $_POST["rating"],'payment' => "not_paid"));
        }
$new_section=$section+1;
            $job_info=$this->jobs_model->get(array('id' => $jobid ));
            $work_names=json_decode($job_info->work_names);
            $work_questions=json_decode($job_info->work_questions);
            $data["job_info"]=$job_info;
            $data["section"]=$new_section;
            $data["work_name"]=@$work_names[$data["section"]];
            $data["work_question"]=@$work_questions[$data["section"]];
            if($data["work_name"]==""){
                $data["job_is_done"]=1;
            }else{
                $data["job_is_done"]=0;
            }
            print_r($data);
            
    }
    function forgot_password($passcode=0){
        $this->load->library('session');
        $this->session;
        $this->load->model("user_model");
        
        if($passcode!="0"){
            $language="tr";
            $error=$this->session->flashdata("error");
            $success=$this->session->flashdata("success");
            $data["error"]=$error;
            $data["success"]=$success;
            $user_info=$this->user_model->get(array("passcode"=>$passcode));
            if(isset($user_info->id)){
                if(isset($_POST) and count($_POST)>0){
                    if($_POST["password"]==$_POST["password2"]){
                        $this->user_model->update(array("passcode"=>$passcode),array("password"=>md5($_POST['password'])));
                        if($this->db->_error_number()==0){
                            $this->session->set_flashdata('success', 'changed');
                            redirect(base_url("user"));
                        }else{
                            $this->session->set_flashdata('error', 'error');
                            $data["error"]=$this->session->flashdata("error");
                            $this->lang->load('language', $language);
                            $this->load->view("user/reset_password.php",@$data);
                        }
                    }else{
                    $data["passcode"]=$passcode;
                            $this->session->set_flashdata('error', 'error');
                            $data["error"]=$this->session->flashdata("error");
                            $this->lang->load('language', $language);
                            $this->load->view("user/reset_password.php",@$data);
                        }
                }else{
                    $data["passcode"]=$passcode;
                    $this->lang->load('language', $language);
                    $this->load->view("user/reset_password.php",@$data);
                }
            }else{
                //user is not valid
            }
        }else{
            
            if(isset($_POST) and count($_POST)>0){
                $user_info=$this->user_model->get(array("username"=>$_POST["email"]));
                if(isset($user_info->id)){
                    $email=$_POST["email"];
                    $passcode=$user_info->passcode;
                    $config['protocol'] = 'smtp';
                    $config['smtp_host'] = 'smtp.yandex.com';
                    $config['smtp_crypto'] = 'ssl';
                    $config['smtp_port'] = '465';
                    $config['smtp_user'] = 'asimyilmaz@kronasoftware.com';
                    $config['smtp_pass'] = '11murat11';
                    $config['mailtype'] = 'html';
                    $config['charset'] = 'UTF-8';
                    $config['wordwrap'] = 'TRUE';

                    $this->load->library('email');
                    $this->email->initialize($config);
                    $this->email->from('asimyilmaz@kronasoftware.com', "asimyilmaz@kronasoftware.com");
                    
                    $this->email->set_newline("\r\n");
                    $this->email->to($_POST["email"]);
                    $this->email->subject("WEBTESTET Aktivasyon");
                    $this->email->message("Linke Tiklayarak Parola Resetleme Tamamlayabilirsiniz. <a href='".base_url("user/forgot_password/".$passcode)."'>".base_url("user/forgot_password/".$passcode)."</a>");  
                    
                    $r=$this->email->send();

                    $this->session->set_flashdata('success', 'email_sent');
                    $this->load->view("static/success.php",@$data);
                }else{
                    $this->session->set_flashdata('error', 'no_username');
                    $data["error"]=$this->session->flashdata("error");
                    $data["passcode"]=$passcode;
                    $data["success"]=$this->session->flashdata("success");
                    $language="tr";
                    $this->lang->load('language', $language);
                    $this->load->view("user/forgot_password.php",@$data);
                }
            }else{
            
            $error=$this->session->flashdata("error");
            $success=$this->session->flashdata("success");
            $language="tr";
            $data["passcode"]=$passcode;
            $data["error"]=$error;
            $data["success"]=$success;
            $this->lang->load('language', $language);
            $this->load->view("user/forgot_password.php",@$data);
            }
        }
    }
    function session_check(){
        $this->load->library('session');
        $this->session;
        $username=$this->session->userdata('username');
        $password=$this->session->userdata('password');
        $this->load->model("user_model");
        $user_info=$this->user_model->get(array('email' => $username, 'password'=>md5($password)));
        if(!isset($user_info->id)){
            $this->session->set_flashdata('error', 'error');
            redirect(base_url("welcome/index"));
        }else{
            return $user_info;
        }
    }
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */