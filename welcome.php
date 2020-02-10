<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	public function index()
	{	
        
    	    $language="en";
    	    $this->load->library('session');
            $this->session;
		if(!@$_POST){
            $this->lang->load('language', $language);
            $this->load->view("index.php");
        }else{
		    $newdata = array(
                    'username'  => $_POST["username"],
                    'password' => $_POST["password"],
                    'account_type' => "user",
                    );
    		$this->session->set_userdata($newdata);
    		$this->session_check();
            redirect(base_url("user/dashboard"));
    	}
    }
    public function signup()
	{	
    	    $language="en";
    	if(!@$_POST){
        	$this->load->view("user/signup.php");
        }else{
        	$this->load->model("user_model");
        	$user_exist=$this->user_model->get(array('email' => $_POST["email"]));
        	if(isset($user_exist->id)){
        		die("Bu email zaten kullanimdadir.");
        	}else{
        		$this->user_model->add(array('email' => $_POST["email"],"password"=>md5($password)));
        		redirect(base_url("welcome/index"));
			}
		}
    }

    public function category($category_name="")
	{	
		$this->session_check();
    	$language="en";
    	$this->load->model("categories_model");
    	$category_content=$this->categories_model->get_all(array('type' => $category_name),"","","");
        $a=0;
        $data["longs"] ="";
        $data["lats"] ="";
        $data["contents"] ="";
        while ($a<count($category_content)) {
            $data["longs"] .=$category_content[$a]->long;
            $data["lats"] .=$category_content[$a]->lat;
            $data["contents"] .="'".$category_content[$a]->content."'";
            if($a+1!=count($category_content)){
                $data["longs"] .= ",";
                $data["lats"] .= ",";
                $data["contents"] .=",";
            }
            $a++;    
        }
        $this->load->view("user/category.php",$data);
    }
    public function beauty()
    {   
        $this->session_check();
        $language="en";
        $this->load->model("beauty_model");
        $beauty_content=$this->beauty_model->get_all();
        $data["content"]=$beauty_content;
        $this->load->view("user/beauty.php",$data);
    }
    public function beauty_blog($index)
    {   
        $this->session_check();
        $language="en";
        $this->load->model("beauty_model");
        $beauty_content=$this->beauty_model->get(array('id' => $index));
        $data["content"]=$beauty_content;
        $this->load->view("user/beauty_blog.php",$data);
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