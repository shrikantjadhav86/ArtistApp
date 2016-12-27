<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Event extends REST_Controller {

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
    
        //Event
        
        //Listing of all event [GET]
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
            
            //for specific event
            if(isset($get_arr['id']) && $get_arr['id'] != ''){
                $event_arr  = $this->artists_model->get_event_details($get_arr['id']);//remaining
                $msg        = 'Event Details';
            }
            else //list of all events
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
                
                $condition_arr = array('user_id'=>$user_id,'filter'=>$filter_data);//events list by specific user_id / filter
                $event_arr     = $this->artists_model->get_all_event($pagination_data['page'],$pagination_data['limit'],$condition_arr);
                $msg           = 'List of all events';
            }
            
            $condition_arr = array('user_id'=>$user_id);
            $event_count   = $this->artists_model->get_event_count($condition_arr);
            
            
            if(isset($event_arr) && $event_count > 0){
                $message = [
                    'status'         => TRUE,
                    'message'        => $msg,
                    'data'           => $event_arr,
                    'data_count'     => $event_count,
                    'pagination_data'=> $pagination_data,
                    'status_code'    => 200
                ];
                $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $message = [
                'status'     => TRUE,
                'message'    => 'No event present',
                'status_code'=> 200
                ];
                
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            
        }
        
        //Adding event by artist [POST]
        public function index_post()
        {
            $auth_token                    = $this->post('auth_token');
            $eventData['user_id']          = $this->post('user_id');
            $eventData['event_name']       = $this->post('event_name');
            $eventData['event_location']   = $this->post('event_location');
            $eventData['event_starttime']  = $this->post('event_starttime');
            $eventData['event_endtime']    = $this->post('event_endtime');
            $eventData['event_picture']    = $this->post('event_picture');
            $eventData['event_description']= $this->post('event_description');
            $eventData['event_status']     = '0'; //status=>pending
            $eventData['created_date']     = time();
            
            
            //convert date into unix timestamp [hybrid app]
            if (DateTime::createFromFormat('Y-m-d G:i:s', $eventData['event_starttime']) !== FALSE) {
                $eventData['event_starttime'] = strtotime($eventData['event_starttime']);
            }
            if (DateTime::createFromFormat('Y-m-d G:i:s', $eventData['event_endtime']) !== FALSE) {
                $eventData['event_endtime'] = strtotime($eventData['event_endtime']);
            }
            
            //checking auth token
            $record = $this->artists_model->is_valid_token($eventData['user_id'],$auth_token);
            if($record == 0){
                //redirect to login
                $this->response([
                    'status'      => FALSE,
                    'message'     => 'Unauthorize user,please login again',
                    'status_code' => 401
                ], REST_Controller::HTTP_UNAUTHORIZED); // UNAUTHORIZED (401) being the HTTP response code
            }
            
            if($eventData['user_id'] != '' && $eventData['event_name'] != '' && $eventData['event_location'] != ''
                    && $eventData['event_starttime'] != '' && $eventData['event_endtime'] && $eventData['event_picture'] != '' && $eventData['event_description'] != '')
            {
                    $record = $this->artists_model->add_event($eventData);
                    $message = [
                    'status'     => TRUE, 
                    'message'    => 'Event added successfully',
                    'status_code'=> 200
                    ];
                    $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }else{
                $message = [
                'status'     => FALSE,
                'message'    => 'Event not added,please enter required fields properly',
                'status_code'=> 400
                ];
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
                
            }
            
        }
        
        //Updating event by artist [POST]
        public function edit_post($event_id)
        {
            
            $user_id    = $this->post('user_id');
            $auth_token = $this->post('auth_token');
            
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
            
            //$event_id                      = $this->post('event_id');
            $eventData['event_name']       = $this->post('event_name');
            $eventData['event_location']   = $this->post('event_location');
            $eventData['event_starttime']  = $this->post('event_starttime');
            $eventData['event_endtime']    = $this->post('event_endtime');
            $eventData['event_picture']    = $this->post('event_picture');
            $eventData['event_description']= $this->post('event_description');
            $eventData['event_status']     = '0';
            $eventData['updated_date']     = time();
            
            
            if($event_id != '' && $eventData['event_name'] != '' && $eventData['event_location'] != '' && $eventData['event_starttime'] != '' 
            && $eventData['event_endtime'] != '' && $eventData['event_picture'] != '' && $eventData['event_description'] !='')
            {
                $record     = $this->artists_model->update_event($event_id,$eventData);
                $event_data = $this->artists_model->get_event_details($event_id);
                
                $message = [
                'status'     => TRUE, 
                'message'    => 'Event updated successfully',
                'event_data' => $event_data,
                'status_code'=> 200
                ];
                $this->set_response($message, REST_Controller::HTTP_OK);
            }
            else
            {
                $message = [
                'status' => FALSE,
                'message' => 'Event not updated,please enter required fields properly',
                'status_code'=> 400
                ];
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST); 
            }
        }
        
        //Deleting event by event_id [GET]
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

            $record = $this->artists_model->delete_event($id);
            if($record) {
                $message = [
                    'status'     => TRUE,
                    'id'         => $id,
                    'message'    => 'Event deleted successfully',
                    'status_code'=> 200
                ];

                $this->set_response($message, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $message = [
                    'status'     => FALSE,
                    'id'         => $id,
                    'message'    => 'Invalid event id',
                    'status_code'=> 400
                ];
                
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        //Upload event picture [POST]
        public function uploadEventPic_post()
        {
            if($_FILES['uploadImage']['name'] != "")
            {
                $upload_path = 'uploads/event_picture/';
                $data        = $this->do_upload($upload_path);
                
                if(isset($data['success']) && $data['success'] == "True")
                {
                        //$imgae_data['image_name']    = $data['file_name'];			
                        $data['file_location'] = base_url().'uploads/event_picture/'.$data['file_name'];			

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
        
        //call by upload_event_pic
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
