<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Fb extends REST_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        // Construct the parent class
        parent::__construct();
        //$this->load->library('facebook');
        $this->load->library('session');
        $this->load->model('artists_model','',TRUE);
    }

    public function fb_register_post() 
    {
        $facebook_id      = $this->post('facebook_id');
        $username         = $this->post('first_name').$this->post('last_name');
        $first_name       = $this->post('first_name');
        $last_name        = $this->post('last_name');
        $email            = $this->post('email');
        $gender           = $this->post('gender');
        $user_type        = 1;//Artist user
        $status           = 1;//Active
        $added_date       = time();
        
        //Generate a random string.
        $rand_token = openssl_random_pseudo_bytes(16);
        //Convert the binary data into hexadecimal representation.
        $token = bin2hex($rand_token);
        
        
        $record = $this->artists_model->check_user_record($email);
        
        if($record){
            //if record is present maintain session 
            

            $this->session->set_userdata(array(
                'user_id'        => $record[0]['id'],
                'username'       => $record[0]['username'],
                'fname'          => $record[0]['fname'],
                'lname'          => $record[0]['lname'],
                'email'          => $record[0]['email'],
                'profile_picture'=> $record[0]['profile_picture'],
                'dob'            => $record[0]['dob'],
                'gender'         => $record[0]['gender'],
                'address'        => $record[0]['address'],
                'contact_no'     => $record[0]['contact_no'],
                'auth_token'     => $record[0]['auth_token'],
            ));

            //Set the response and exit
            $this->response([
                'status'         => TRUE,
                'message'        => 'Login successfully',
                'username'       => $username,
                'data'           => $this->session->userdata(),
                'status_code'    => 200
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

            
            

        }else{
            
            //else register first and then maintain session 
            
            if($facebook_id != '' && $first_name != '' && $last_name != '' && $email != '' && $gender != '')
            {
                $userData   = array('fname'=>$first_name,'lname'=>$last_name,'username'=>$username,'status'=>$status,'fb_id'=>$facebook_id,
                                    'email'=>$email,'user_type'=>$user_type,'gender'=>$gender,'added_date'=>$added_date,'auth_token'=>$token);
                
                $register   = $this->artists_model->register_member($userData);
                $record     = $this->artists_model->check_user_record($email);
                $this->session->set_userdata(array(
                    'user_id'  => $record[0]['id'],
                    'username' => $record[0]['username'],
                    'fname'    => $record[0]['fname'],
                    'lname'    => $record[0]['lname'],
                    'email'    => $record[0]['email'],
                    'profile_picture' => $record[0]['profile_picture'],
                    'dob'      => $record[0]['dob'],
                    'gender'   => $record[0]['gender'],
                    'address'   => $record[0]['address'],
                    'contact_no' => $record[0]['contact_no'],
                    'auth_token' => $record[0]['auth_token'],
                ));

                //Set the response and exit
                $this->response([
                    'status' => TRUE,
                    'message' => 'Login successfully',
                    'username' => $username,
                    'data' => $this->session->userdata(),
                    'status_code' => 200
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $message = [
                'status'     => FALSE,
                'message'    => 'Not register,please enter required fields properly',
                'status_code'=> 400
                ];
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 

            }
        }
            
            
        }
    

}
