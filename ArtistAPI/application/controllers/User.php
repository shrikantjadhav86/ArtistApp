<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller {

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
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    
        public function __construct()
        {
            // Construct the parent class
            parent::__construct();
            $this->load->library('session');
            //$this->load->library('upload');
            $this->load->model('artists_model','',TRUE);
            
        }
    
        //Member
        
        //Member Registration [POST]
        public function register_post() 
        {
            $first_name       = $this->post('fname');
            $last_name        = $this->post('lname');
            $username         = $this->post('username');
            $email            = $this->post('email');
            $password         = $this->post('password');
            $dob              = $this->post('dob');
            $contact_no       = $this->post('contact_no');
            $gender           = $this->post('gender');
            $address          = $this->post('address');
            $user_type        = 2;//Member user
            $status           = 1;//Active
            $added_date       = time();
            if($first_name != '' && $last_name != '' && $username != '' && $email != '' && $password != '' && $dob != '' && $gender != '' && $address != '')
            {
                $userData   = array('fname'=>$first_name,'lname'=>$last_name,'username'=>$username,'status'=>$status,
                                    'email'=>$email,'password'=>$password,'dob'=>$dob,'contact_no'=>$contact_no,'address'=>$address,
                                    'user_type'=>$user_type,'gender'=>$gender,'added_date'=>$added_date);
                
                $record     = $this->artists_model->register_member($userData);
                $message    =  [
                                'status'     => TRUE, 
                                'message'    => 'Registered successfully',
                                'status_code'=> 200
                                ];
                                $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
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
        
        //Lising of all member [GET]
        public function member_get()
        {
            $get_arr = $this->query();
            
            $auth_token = $get_arr['auth_token'];
            $user_id    = $get_arr['user_id'];
            $filter_data= '';
            //checking auth token
            $record = $this->artists_model->is_valid_token($user_id,$auth_token);
            if($record == 0){
                //redirect to login
                $this->response([
                    'status'      => FALSE,
                    'message'     => 'Unauthorize user,please login again',
                    'status_code' => 401
                ], REST_Controller::HTTP_UNAUTHORIZED); // UNAUTHORIZED (401) being the HTTP response code
            }
            
            //for specific user
            if(isset($get_arr['id']) && $get_arr['id'] != ''){
                $users  = $this->artists_model->get_user_details($get_arr['id']);
                $msg    = 'Artist Details';
                $pagination_data = array();
            }
            else //list of all user
            {
                //set filter data
                if(isset($get_arr['filter']) && $get_arr['filter'] != ''){
                    $filter_data = $get_arr['filter'];
                }
                
                //pagination
                
                //set page number
                if(isset($get_arr['page']) && $get_arr['page'] != ''){
                    $pagination_data['page']  = $get_arr['page'];
                }else {
                    $pagination_data['page']  = 1;
                }
                
                //set record per page
                if(isset($get_arr['limit']) && $get_arr['limit'] != ''){
                    $pagination_data['limit'] = $get_arr['limit'];
                }else {
                    $pagination_data['limit'] = 10;
                }
                
                //pagination ends
                
                $users  = $this->artists_model->get_all_users($pagination_data['page'],$pagination_data['limit'],$filter_data);
                $msg    = 'List of all member';
            }
            $data_count = count($users);
            
            // Check if the member data store contains users (in case the database result returns NULL)
            if (isset($users) && $data_count > 0)
            {
                // Set the response and exit
                $this->response([
                    'status'         => TRUE,
                    'message'        => $msg,
                    'data'           => $users,
                    'data_count'     => $data_count,
                    'pagination_data'=> $pagination_data,
                    'status_code'    => 200
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status'     => TRUE,
                    'message'    => 'No member were found',
                    'status_code'=> 200
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        }
        
        
        //Login [POST]
        public function login_post() 
        {
            
            $username   = $this->post('username');
            $password   = $this->post('password');
            $record     = $this->artists_model->check_user_record($username);
            $data_count = count($record);
            
            
            if(isset($record[0]['email']) && $record[0]['email'] != '')
            {
                //if password is correct then execute
                if($password  ==  $record[0]['password'])
                {
                    //prepare social_account array if user is artist
                    if($record[0]['user_type'] == '1'){
                        $is_artist  = TRUE;
                        $social_account['fb_url']      = $record[0]['fb_url'];
                        $social_account['twitter_url'] = $record[0]['twitter_url'];
                        $social_account['insta_url']   = $record[0]['insta_url'];
                    }
                    else{
                        $is_artist      = FALSE;
                        $social_account = array();
                    }
                    
                    
                    
                    /* genrate auth_token starts */
                    
                    //Generate a random string.
                    $token = openssl_random_pseudo_bytes(16);
                    //Convert the binary data into hexadecimal representation.
                    $token = bin2hex($token);

                    //Update auth_token 
                    $data_arr = array('auth_token'=>$token);
                    $this->artists_model->update_auth_token($record[0]['id'],$data_arr);

                    $record     = $this->artists_model->check_user_record($username);

                    $this->session->set_userdata(array(
                        'user_id'        => $record[0]['id'],
                        'username'       => $record[0]['username'],
                        'password'       => $record[0]['password'],
                        'fname'          => $record[0]['fname'],
                        'lname'          => $record[0]['lname'],
                        'email'          => $record[0]['email'],
                        'is_artist'      => $is_artist,
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
                        'password'       => $password,
                        'data'           => $this->session->userdata(),
                        'social accounts'=> $social_account,
                        'status_code'    => 200
                        //'data_count'     => $data_count
                        ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    
                    /* auth_token code ends*/
                }
                else
                {
                    $this->response([
                        'status'     => FALSE,
                        'message'    =>'Email/Username and password you entered don\'t match',
                        'status_code'=> 401
                        ], REST_Controller::HTTP_UNAUTHORIZED); // BAD_REQUEST (400) being the HTTP response code
                }
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status'     => FALSE,
                    'message'    => 'This '.$username.' [email or username] is not registered with this site',
                    'status_code'=> 404
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
        
        //Logout [POST]
        public function logout_post() 
        {
            $auth_token = $this->post('auth_token');
            $user_id    = $this->post('user_id');
            
            //checking auth token
            $record = $this->artists_model->is_valid_token($user_id,$auth_token);
            if($record == 0){
                
                $this->session->unset_userdata(
                    array('id','username','fname','lname','email','is_artist','profile_picture',
                          'dob','gender','address','contact_no','auth_token')
                    );
                echo $this->session->sess_destroy();

                // Set the response and exit
                $this->response([
                    'status'  => TRUE,
                    'message' => 'Logout Successfully'
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                
            }
            else
            {
                $data_arr = array('auth_token'=>'');
                $this->artists_model->update_auth_token($user_id,$data_arr);
                
                $this->session->unset_userdata(
                    array('id','username','fname','lname','email','is_artist','profile_picture',
                          'dob','gender','address','contact_no','auth_token')
                    );
                echo $this->session->sess_destroy();

                // Set the response and exit
                $this->response([
                    'status'  => TRUE,
                    'message' => 'Logout Successfully'
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            
            
        }

        
        //Users
        
        //Lising of all artist [GET]
        public function index_get()
        {
            $get_arr = $this->query();
            
            $auth_token = $get_arr['auth_token'];
            $user_id    = $get_arr['user_id'];
            $filter_data= '';
            //checking auth token
            $record = $this->artists_model->is_valid_token($user_id,$auth_token);
            if($record == 0){
                //redirect to login
                $this->response([
                    'status'      => FALSE,
                    'message'     => 'Unauthorize user,please login again',
                    'status_code' => 401
                ], REST_Controller::HTTP_UNAUTHORIZED); // UNAUTHORIZED (401) being the HTTP response code
            }
            
            //for specific user
            if(isset($get_arr['id']) && $get_arr['id'] != ''){
                $users  = $this->artists_model->get_user_details($get_arr['id']);
                $msg    = 'Artist Details';
                $pagination_data = array();
            }
            else //list of all user
            {
                
                //set filter data
                if(isset($get_arr['filter']) && $get_arr['filter'] != ''){
                    $filter_data = $get_arr['filter'];
                }
                
                //pagination
                
                //set page number
                if(isset($get_arr['page']) && $get_arr['page'] != ''){
                    $pagination_data['page']  = $get_arr['page'];
                }else {
                    $pagination_data['page']  = 1;
                }
                
                //set record per page
                if(isset($get_arr['limit']) && $get_arr['limit'] != ''){
                    $pagination_data['limit'] = $get_arr['limit'];
                }else {
                    $pagination_data['limit'] = 10;
                }
                
                //pagination ends
                
                $users  = $this->artists_model->get_all_users($pagination_data['page'],$pagination_data['limit'],$filter_data);
                $msg    = 'List of all artist';
            }
            $data_count = count($users);
            
            // Check if the users data store contains users (in case the database result returns NULL)
            if (isset($users) && $data_count > 0)
            {
                // Set the response and exit
                $this->response([
                    'status'         => TRUE,
                    'message'        => $msg,
                    'data'           => $users,
                    'data_count'     => $data_count,
                    'pagination_data'=> $pagination_data,
                    'status_code'    => 200
                    ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status'     => TRUE,
                    'message'    => 'No users were found',
                    'status_code'=> 200
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        }
        
        //Updating user by artist_id [POST]
        public function edit_post($user_id)
        {
            $auth_token              = $this->post('auth_token');
            //$user_id                 = $this->post('user_id');
            $userData['fname']       = $this->post('fname');
            $userData['lname']       = $this->post('lname');
            $userData['dob']         = $this->post('dob');
            $userData['gender']      = $this->post('gender'); /* Male/Female */
            $userData['address']     = $this->post('address');
            $userData['contact_no']  = $this->post('contact_no');
            $userData['about']       = $this->post('about');
            $userData['updated_date']= time();
            
            //checking auth token
            $record = $this->artists_model->is_valid_token($user_id,$auth_token);
            if($record == 0){
                //redirect to login
                $this->response([
                    'status'      => FALSE,
                    'message'     => 'Unauthorize user,please login again',
                    'status_code' => 401
                ], REST_Controller::HTTP_UNAUTHORIZED); // UNAUTHORIZED (401) being the HTTP response code
            }
            
            
            
            if( $user_id != '' && $userData['fname'] != '' && $userData['lname'] != '' && $userData['dob'] != '' && $userData['gender'] != '' 
                && $userData['address'] != '' && $userData['contact_no'] != '' && $userData['about'] != '')
            {
                $record     = $this->artists_model->update_user($user_id,$userData);
                $user_data  = $this->artists_model->get_user_details($user_id);
                $message = [
                'status'     => TRUE,
                'message'    => 'User information updated successfully',
                'user_data'  => $user_data,
                'status_code'=> 200    
                ];
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            else
            {
                $message = [
                'status'  => FALSE,
                'message' => 'User information not updated,please enter required fields properly',
                'status_code'=> 400
                ];
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
            }
        }
        
        //Delete user by artist [GET]
        public function delete_get($id)
        {
            $get_arr = $this->query();
            
            $auth_token = $get_arr['auth_token'];
            $user_id    = $get_arr['user_id'];
            
            //checking auth token
            $record = $this->artists_model->is_valid_token($user_id,$auth_token);
            if($record == 0){
                //redirect to login
                $this->response([
                    'status'      => FALSE,
                    'message'     => 'Unauthorize user,please login again',
                    'status_code' => 401
                ], REST_Controller::HTTP_UNAUTHORIZED); // UNAUTHORIZED (401) being the HTTP response code
            }
            
            // Validate the id.
            if ($id <= 0 || $id == '')
            {
                // Set the response and exit
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            $record = $this->artists_model->delete_user($id);
            if($record) {
                $message = [
                    'status'     => TRUE,
                    'user_id'    => $id,
                    'message'    => 'User deleted successfully',
                    'status_code'=> 200
                ];

                $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $message = [
                    'status'     => FALSE,
                    'id'         => $id,
                    'message'    => 'Invalid user id',
                    'status_code'=> 400
                ];
                
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        
        //Upload profile picture [POST]
        public function uploadProfilePic_post()
        {
            
            $user_id    = $this->post('user_id');
            $auth_token = $this->post('auth_token');
            
            $record = $this->artists_model->get_auth_token($user_id);
            if(isset($record[0]['auth_token']) && $record[0]['auth_token'] == $auth_token)
            {
                if($_FILES['uploadImage']['name'] != "")
                {
                    $upload_path = 'uploads/profile_picture/';
                    $data        = $this->do_upload($upload_path);

                    if(isset($data['success']) && $data['success'] == "True")
                    {
                            $image_data['file_location'] = base_url().'uploads/profile_picture/'.$data['file_name'];

                            //update profile picture url
                            $data_arr = array('profile_picture'=>$image_data['file_location']);
                            $this->artists_model->update_user($user_id,$data_arr);
                            
                            $message = [
                                'status'         => TRUE,
                                'message'        => 'Image uploaded successfully',
                                'profile_picture'=> $image_data,
                                'status_code'    => 200
                            ];

                            $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                    }
                    else
                    {
                        $message = [
                            'status'     => FALSE,
                            'message'    => $data['error'],
                            'status_code'=> 400
                        ];

                        $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
                    }		
                }
                else
                {
                    $message = [
                        'status'     => FALSE,
                        'message'    => 'File not selected',
                        'status_code'=> 400
                    ];
                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
                }
                
            }
            else
            {
                //redirect to login
                $this->response([
                    'status'     => FALSE,
                    'message'    =>'Unauthorize user,please login again',
                    'status_code'=> 401
                    ], REST_Controller::HTTP_UNAUTHORIZED); // HTTP_UNAUTHORIZED (401) being the HTTP response code
            }
        }
        
        //call by upload_profile_pic
        function do_upload($upload_path)
	{
		//server path
		$config['upload_path']   = $upload_path;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	 = '0';
		$config['max_width']     = '0';
		$config['max_height']    = '0';
		$config['encrypt_name']  = 'TRUE';
		$config['remove_spaces'] = 'TRUE';

                $this->upload->initialize($config);

		if ( ! $this->upload->do_upload('uploadImage'))
		{
                    $error = $this->upload->display_errors();

                    $data['success'] = "False";
                    $data['error']   = $error;
                    return $data;
		}
		else
		{
                    $data = $this->upload->data();
                    $data['success'] = "True";
                    return $data;
		}
	}
        
}
