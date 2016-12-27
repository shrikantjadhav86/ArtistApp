<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Video extends REST_Controller {

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
    
        //Video
        
        //Listing of all Video [GET]
        public function index_get()
        {
            $get_arr = $this->query();
            
            $auth_token = $get_arr['auth_token'];
            $user_id    = $get_arr['user_id'];
            $pagination_data = array();
            $filter_data = array();
            
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
            
            
            //for specific video
            if(isset($get_arr['id']) && $get_arr['id'] != ''){
                $video_arr  = $this->artists_model->get_video_details($get_arr['id']);
                $msg        = 'Video Details';
            }
            else //list of all videos
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
                
                $condition_arr = array('user_id'=>$user_id,'filter'=>$filter_data);//video list by specific user_id / filter
                $video_arr     = $this->artists_model->get_all_video($pagination_data['page'],$pagination_data['limit'],$condition_arr);
                $msg           = 'List of all videos';
            }
            
            $condition_arr = array('user_id'=>$user_id,'filter'=>$filter_data);//video list by specific user_id / filter
            $video_count   = $this->artists_model->get_video_count($condition_arr);
            
            if(isset($video_arr) && $video_count > 0)
            {
                $message = [
                'status'          => TRUE,
                'message'         => $msg,
                'data'            => $video_arr,
                'data_count'      => $video_count,
                'pagination_data' => $pagination_data,
                'status_code'     => 200
                ];
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            else
            {
                $message = [
                'status'     => TRUE,
                'message'    => 'No video present',
                'status_code'=> 200
                ];
                
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            
        }
        
        //Add video [POST]
        public function index_post()
        {
            $auth_token                    = $this->post('auth_token');
            $videoData['user_id']          = $this->post('user_id');
            $videoData['media_title']      = $this->post('video_title');
            $videoData['link']             = $this->post('link');
            $videoData['media_type']       = 'video';
            $videoData['created_date']     = time();
            
            //checking auth token
            $record = $this->artists_model->is_valid_token($videoData['user_id'],$auth_token);
            if($record == 0){
                //redirect to login
                $this->response([
                    'status'      => FALSE,
                    'message'     => 'Unauthorize user,please login again',
                    'status_code' => 401
                ], REST_Controller::HTTP_UNAUTHORIZED); // UNAUTHORIZED (401) being the HTTP response code
            }
            
            if($videoData['user_id'] != '' && $videoData['link'] != '' && $videoData['media_title'])
            {
                    $record  = $this->artists_model->add_video($videoData);
                    $message = [
                    'status'     => TRUE, 
                    'message'    => 'Video added successfully',
                    'status_code'=> 200
                    ];
                    $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $message = [
                'status'     => FALSE,
                'message'    => 'Video not added,Please enter required fields properly',
                'status_code'=> 400
                ];
                
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
            }
            
        }
        
        //Updating video by media_id [POST]
        public function edit_post($media_id)
        {
            $user_id                   = $this->post('user_id');
            $auth_token                = $this->post('auth_token');
            
            //$media_id                  = $this->post('video_id');
            $videoData['media_title']  = $this->post('video_title');
            $videoData['link']         = $this->post('link');
            $videoData['updated_date'] = time();
            
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
            
            if($media_id != '' && $videoData['media_title'] != '' && $videoData['link'] != '')
            {
                $record       = $this->artists_model->update_video($media_id,$videoData);
                $video_data   = $this->artists_model->get_video_details($media_id);
                $message = [
                'status'     => TRUE,
                'message'    => 'Video updated successfully',
                'video_data' => $video_data,
                'status_code'=> 200
                ];
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            else
            {
                $message = [
                'status'     => FALSE,
                'message'    => 'Video not updated,please enter required fields properly',
                'status_code'=> 400
                ];
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
            }
        }
        
        //Deleting video by media id [GET]
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

            $record = $this->artists_model->delete_video($id);
            if($record) {
                $message = [
                    'status'     => TRUE,
                    'id'         => $id,
                    'message'    => 'Video deleted successfully',
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

?>
