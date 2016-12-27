<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Blog extends REST_Controller {

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
            $this->load->model('artists_model','',TRUE);
            
        }
    
        //Blog
        
        //Listing of all blog [GET]
        public function index_get()
        {
            $get_arr = $this->query();
            
            $auth_token = $get_arr['auth_token'];
            $user_id    = $get_arr['user_id'];
            $pagination_data = array();
            
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
            
            //for specific blog
            if(isset($get_arr['id']) && $get_arr['id'] != ''){
                $blog_arr  = $this->artists_model->get_blog_details($get_arr['id']);
                $msg       = 'Blog Details';
            }
            else //list of all blogs
            {
                //set filter data
                if(isset($get_arr['filter']) && $get_arr['filter'] != ''){
                    $filter_data = $get_arr['filter'];
                }else{
                    $filter_data = '';
                }
                
                //set user_id
                if(isset($get_arr['user_id']) && $get_arr['user_id'] != ''){
                    $user_id = $get_arr['user_id'];
                }else{
                    $user_id = '';
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
                
                $condition_arr = array('user_id'=>$user_id,'filter'=>$filter_data);//blog list by specific user_id / filter
                $blog_arr      = $this->artists_model->get_all_blog($pagination_data['page'],$pagination_data['limit'],$condition_arr);
                $msg           = 'List of all blogs';
            }
            
            $condition_arr = array('user_id'=>$user_id);
            $blog_count    = $this->artists_model->get_blog_count($condition_arr);
            
            if(isset($blog_arr) && $blog_count > 0)
            {
                $message = [
                'status'         => TRUE,
                'message'        => $msg,
                'data'           => $blog_arr,
                'data_count'     => $blog_count,
                'pagination_data'=> $pagination_data,
                'status_code'    => 200
                ];
                
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            else
            {
                $message = [
                'status'     => TRUE,
                'message'    => 'No blog present',
                'status_code'=> 200
                ];
                
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            
        }
        
        //Add Blog [POST]
        public function index_post()
        {
            $auth_token                   = $this->post('auth_token');
            $blogData['user_id']          = $this->post('user_id');
            $blogData['blog_title']       = $this->post('blog_title');
            $blogData['blog_picture']     = $this->post('blog_picture');
            $blogData['blog_description'] = $this->post('blog_description');
            $blogData['blog_status']      = '1'; //status=>active
            $blogData['created_date']     = time();
            
            //checking auth token
            $record = $this->artists_model->is_valid_token($blogData['user_id'],$auth_token);
            if($record == 0){
                //redirect to login
                $this->response([
                    'status'      => FALSE,
                    'message'     => 'Unauthorize user,please login again',
                    'status_code' => 401
                ], REST_Controller::HTTP_UNAUTHORIZED); // UNAUTHORIZED (401) being the HTTP response code
            }
            
            if($blogData['user_id'] != '' && $blogData['blog_title'] != '' && $blogData['blog_picture'] != '' && $blogData['blog_description'] != '')
            {
                    $record  = $this->artists_model->add_blog($blogData);
                    $message = [
                    'status'      => TRUE,
                    'message'     => 'Blog added successfully',
                    'status_code' => 200
                    ];
                    
                    $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $message = [
                'status'      => FALSE,
                'message'     => 'Blog not added,Please enter required fields properly',
                'status_code' => 400
                ];
                
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
            }
            
        }
        
        //Updating blog by artist [POST]
        public function edit_post($blog_id)
        {
            $auth_token                   = $this->post('auth_token');
            $user_id                      = $this->post('user_id');
            //$blog_id                      = $this->post('blog_id');
            $blogData['blog_title']       = $this->post('blog_title');
            $blogData['blog_picture']     = $this->post('blog_picture');
            $blogData['blog_description'] = $this->post('blog_description');
            $blogData['blog_status']      = '1';//0=>Inactive,1=>Active
            $blogData['updated_date']     = time();
            
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
            
            if($blog_id != '' && $blogData['blog_title'] != '' && $blogData['blog_picture'] != '' && $blogData['blog_description'] != '' && $blogData['blog_status'] != '')
            {
                $record     = $this->artists_model->update_blog($blog_id,$blogData);
                $blog_data  = $this->artists_model->get_blog_details($blog_id);
                $message = [
                'status'     => TRUE,
                'message'    => 'Blog updated successfully',
                'blog_data'  => $blog_data,
                'status_code'=> 200
                ];
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            else
            {
                $message = [
                'status'     => FALSE,
                'message'    => 'Blog not updated,please enter required fields properly',
                'status_code'=> 400
                ];
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
            }
        }
        
        //Deleting blog by blog id [GET]
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

            $record = $this->artists_model->delete_blog($id);
            if($record) {
                $message = [
                    'status'     => TRUE,
                    'id'         => $id,
                    'message'    => 'Blog deleted successfully',
                    'status_code'=> 200
                ];

                $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $message = [
                    'status'     => FALSE,
                    'id'         => $id,
                    'message'    => 'Invalid blog id',
                    'status_code'=> 400
                ];
                
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
        }
}
