<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Image extends REST_Controller {

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
    
        //Image
        
        //Listing of all image [GET]
        public function index_get()
        {
            $get_arr    = $this->query();
            
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
            
            //for specific image
            if(isset($get_arr['id']) && $get_arr['id'] != ''){
                $image_arr  = $this->artists_model->get_image_details($get_arr['id']);//remaining
                $msg        = 'Image Details';
            }
            else //list of all images
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
                
                $condition_arr = array('user_id'=>$user_id,'filter'=>$filter_data);//imgae list by specific user_id / filter
                $image_arr      = $this->artists_model->get_all_image($pagination_data['page'],$pagination_data['limit'],$condition_arr);
                $msg           = 'List of all images';
            }
            
            $condition_arr = array('user_id'=>$user_id);
            $image_count    = $this->artists_model->get_image_count($condition_arr);
            
            if(isset($image_arr) && $image_count > 0)
            {
                $message = [
                'status'         => TRUE,
                'message'        => $msg,
                'data'           => $image_arr,
                'data_count'     => $image_count,
                'pagination_data'=> $pagination_data,
                'status_code'    => 200
                ];
                
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            else
            {
                $message = [
                'status'      => TRUE,
                'message'     => 'No image present',
                'status_code' => 200
                ];
                
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
        }
        
        //Add image [POST]
        public function index_post()
        {
            $auth_token                    = $this->post('auth_token');
            $imageData['user_id']          = $this->post('user_id');
            $imageData['media_title']      = $this->post('image_title');
            $imageData['link']             = $this->post('link');
            $imageData['media_type']       = 'image';
            $imageData['created_date']     = time();
            
            //checking auth token
            $record = $this->artists_model->is_valid_token($imageData['user_id'],$auth_token);
            if($record == 0){
                //redirect to login
                $this->response([
                    'status'      => FALSE,
                    'message'     => 'Unauthorize user,please login again',
                    'status_code' => 401
                ], REST_Controller::HTTP_UNAUTHORIZED); // UNAUTHORIZED (401) being the HTTP response code
            }
            
            if($imageData['user_id'] != '' && $imageData['link'] != '')
            {
                    $record  = $this->artists_model->add_image($imageData);
                    $message = [
                    'status'     => TRUE, 
                    'message'    => 'Image added successfully',
                    'status_code'=> 200
                    ];
                    $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $message = [
                'status'     => FALSE,
                'message'    => 'Image not added,Please enter required fields properly',
                'status_code'=> 400
                ];
                
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
            }
            
        }
        
        //Updating image by media_id [POST]
        public function edit_post($media_id)
        {
            $user_id                  = $this->post('user_id');
            $auth_token               = $this->post('auth_token');
            
            $imageData['media_title']  = $this->post('image_title');
            $imageData['link']         = $this->post('link');
            $imageData['updated_date'] = time();
            
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
            
            
            if($media_id != '' && $imageData['media_title'] !='' && $imageData['link'] != '')
            {
                $record    = $this->artists_model->update_song($media_id,$imageData);
                $image_data = $this->artists_model->get_song_details($media_id);
                
                $message = [
                'status'     => TRUE,
                'message'    => 'Image updated successfully',
                'song_data'  => $image_data,
                'status_code'=> 200
                ];
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            else
            {
                $message = [
                'status'     => FALSE,
                'message'    => 'Image not updated,please enter required fields properly',
                'status_code'=> 400
                ];
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
            }
        }
        
        //Deleting image by media id [GET]
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

            $record = $this->artists_model->delete_image($id);
            if($record) {
                $message = [
                    'status'     => TRUE,
                    'id'         => $id,
                    'message'    => 'Image deleted successfully',
                    'status_code'=> 200
                ];

                $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $message = [
                    'status'     => FALSE,
                    'id'         => $id,
                    'message'    => 'Invalid image id',
                    'status_code'=> 400
                ];
                
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        
        
        //Upload camera picture [POST]
        public function uploadCameraPic_post()
        {
            if($_FILES['uploadImage']['name'] != "")
            {
                $upload_path = 'uploads/camera_picture/';
                $data        = $this->do_upload($upload_path);
                
                if(isset($data['success']) && $data['success'] == "True")
                {
                        //$imgae_data['image_name']    = $data['file_name'];			
                        $data['file_location'] = base_url().'uploads/camera_picture/'.$data['file_name'];			

                        $message = [
                            'status'     => TRUE,
                            'message'    => 'Image uploaded successfully',
                            'image_data' => $data
                        ];

                        $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
                else
                {
                    $message = [
                        'status'    => FALSE,
                        'message'   => $data['error']

                    ];

                    $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);


                }		
            }
            else
            {
                $message = [
                    'status'    => FALSE,
                    'message'   => 'File not selected'

                ];
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
            }
        }
        
        //call by upload_camera_pic
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
