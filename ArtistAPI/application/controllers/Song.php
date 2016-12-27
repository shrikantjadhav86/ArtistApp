<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Song extends REST_Controller {

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
    
        //Songs
        
        //Listing of all song [GET]
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
            
            //for specific song
            if(isset($get_arr['id']) && $get_arr['id'] != ''){
                $song_arr  = $this->artists_model->get_song_details($get_arr['id']);//remaining
                $msg       = 'Song Details';
            }
            else //list of all songs
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
                
                $condition_arr = array('user_id'=>$user_id,'filter'=>$filter_data);//song list by specific user_id / filter
                $song_arr      = $this->artists_model->get_all_song($pagination_data['page'],$pagination_data['limit'],$condition_arr);
                $msg           = 'List of all songs';
            }
            
            $condition_arr = array('user_id'=>$user_id);
            $song_count    = $this->artists_model->get_song_count($condition_arr);
            
            if(isset($song_arr) && $song_count > 0)
            {
                $message = [
                'status'         => TRUE,
                'message'        => $msg,
                'data'           => $song_arr,
                'data_count'     => $song_count,
                'pagination_data'=> $pagination_data,
                'status_code'    => 200
                ];
                
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            else
            {
                $message = [
                'status'      => TRUE,
                'message'     => 'No song present',
                'status_code' => 200
                ];
                
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            
        }
        
        //Add song [POST]
        public function index_post()
        {
            $auth_token                   = $this->post('auth_token');
            $songData['user_id']          = $this->post('user_id');
            $songData['media_title']      = $this->post('song_title');
            $songData['link']             = $this->post('link');
            $songData['media_type']       = 'song';
            $songData['created_date']     = time();
            
            //checking auth token
            $record = $this->artists_model->is_valid_token($songData['user_id'],$auth_token);
            if($record == 0){
                //redirect to login
                $this->response([
                    'status'      => FALSE,
                    'message'     => 'Unauthorize user,please login again',
                    'status_code' => 401
                ], REST_Controller::HTTP_UNAUTHORIZED); // UNAUTHORIZED (401) being the HTTP response code
            }
            
            if($songData['user_id'] != '' && $songData['link'] != '')
            {
                    $record  = $this->artists_model->add_song($songData);
                    $message = [
                    'status'     => TRUE, 
                    'message'    => 'Song added successfully',
                    'status_code'=> 200
                    ];
                    $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $message = [
                'status'     => FALSE,
                'message'    => 'Song not added,Please enter required fields properly',
                'status_code'=> 400
                ];
                
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
            }
            
        }
        
        //Updating song by media_id [POST]
        public function edit_post($media_id)
        {
            $user_id                  = $this->post('user_id');
            $auth_token               = $this->post('auth_token');
            
            //$media_id                 = $this->post('song_id');
            $songData['media_title']  = $this->post('song_title');
            $songData['link']         = $this->post('link');
            $songData['updated_date'] = time();
            
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
            
            
            if($media_id != '' && $songData['media_title'] !='' && $songData['link'] != '')
            {
                $record    = $this->artists_model->update_song($media_id,$songData);
                $song_data = $this->artists_model->get_song_details($media_id);
                
                $message = [
                'status'     => TRUE,
                'message'    => 'Song updated successfully',
                'song_data'  => $song_data,
                'status_code'=> 200
                ];
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            else
            {
                $message = [
                'status'     => FALSE,
                'message'    => 'Songs not updated,please enter required fields properly',
                'status_code'=> 400
                ];
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
            }
        }
        
        //Deleting song by media id [GET]
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

            $record = $this->artists_model->delete_song($id);
            if($record) {
                $message = [
                    'status'     => TRUE,
                    'id'         => $id,
                    'message'    => 'Song deleted successfully',
                    'status_code'=> 200
                ];

                $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $message = [
                    'status'     => FALSE,
                    'id'         => $id,
                    'message'    => 'Invalid song id',
                    'status_code'=> 400
                ];
                
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
}
