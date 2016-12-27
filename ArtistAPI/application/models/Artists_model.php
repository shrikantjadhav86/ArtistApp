<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Artists_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
    
    /*****Login*****/
    public function check_user_record($username) {
        $this->db->select('id,user_type,username,password,fname,lname,email,password,'
                        . 'profile_picture,gender,dob,address,contact_no,fb_url,'
                        . 'twitter_url,insta_url,auth_token');
        
        $this->db->from('users');
        $this->db->where("(email = '$username' OR username = '$username')");
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function update_auth_token($user_id,$data){
        $this->db->where("id", $user_id);
        $this->db->update('users', $data);
        return $this->db->affected_rows();
    }
    
    public function is_valid_token($user_id,$auth_token) {
        //echo $user_id.$auth_token;die;
        $this->db->select('id,username,fname,lname,email');
        $this->db->from('users');
        $this->db->where("(id = '$user_id' AND auth_token = '$auth_token')");
        $query = $this->db->get();
        return $rowcount = $query->num_rows();
    }
    /*****Login*****/
    
    /*****User*****/
    public function get_all_users($limit = 0,$records_limit = 0,$filter_data = '') {
        if($records_limit > 0){
            $numberofrecords = $records_limit;
        }else{
            $numberofrecords = (int) $this->config->item('record_limit');
        }
        
        if ($limit > 0){
            $limit = $limit - 1;
        }
        $startRecord = $limit * $numberofrecords;
        
        
        $this->db->select('id,username,fname,lname,email,user_type,address');
        $this->db->from('users');
        $this->db->order_by("id", "desc");
        $this->db->where('user_type','2');
        if($filter_data != ''){
            $this->db->where("(email like '%$filter_data%' OR username like '%$filter_data%' OR fname ='$filter_data' OR lname = '$filter_data' OR status = '$filter_data')");
        }
        $this->db->limit($numberofrecords, $startRecord);
        
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function get_user_details($user_id){
        $this->db->select('id,username,fname,lname,gender,email,dob,contact_no,address,about');
        $this->db->from('users');
        $this->db->where('id',$user_id);
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function get_auth_token($user_id){
        $this->db->select('id,auth_token');
        $this->db->from('users');
        $this->db->where('id',$user_id);
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function update_user($id, $data) {
        $this->db->where("id", $id);
        $this->db->update('users', $data);
        return $this->db->affected_rows();
    }
    /*****User*****/
    
    
    /*****Member*****/
    public function register_member($userData) {
        $this->db->insert('users', $userData);
        return $this->db->insert_id();
        //echo $this->db->last_query(); exit;
    }
    /*****Member*****/
    
    
    
    
    
    /*****Event*****/
    public function get_all_event($limit = 0,$records_limit = 0,$condition_arr) {
        
        if($records_limit > 0){
            $numberofrecords = $records_limit;
        }else{
            $numberofrecords = (int) $this->config->item('record_limit');
        }
        
        if ($limit > 0){
            $limit = $limit - 1;
        }
        $startRecord = $limit * $numberofrecords;
        
        $this->db->select('e.*,u.username as event_created_by');
        $this->db->from('event e');
        $this->db->join('users u','u.id = e.user_id');
        $this->db->order_by("id", "desc");
        
        //get events according to user_id
        if(isset($condition_arr) && $condition_arr['user_id'] != '' )
        {
            $this->db->where('user_id',$condition_arr['user_id']);
        }
        
        //get events according to filter
        if(isset($condition_arr) && $condition_arr['filter'] != '' )
        {
            $filter_data = $condition_arr['filter'];
            //$this->db->where("(event_name = '$filter_data' OR event_location = '$filter_data')");
            $this->db->where("(event_name like '%$filter_data%' OR event_location like '%$filter_data%')");
        }
        
        $this->db->limit($numberofrecords, $startRecord);
        
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function get_event_count($condition_arr) {
        $this->db->select('id');
        $this->db->from('event');
        
        if(isset($condition_arr) && $condition_arr['user_id'] != '' )
        {
            $this->db->where("user_id",$condition_arr['user_id']);
        }
        
        $query = $this->db->get();
        return $rowcount = $query->num_rows();
    }
    
    public function get_event_details($event_id) {
        
        $this->db->select('e.*,u.username as event_created_by');
        $this->db->from('event e');
        $this->db->join('users u','u.id = e.user_id');
        $this->db->where('e.id',$event_id);
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function add_event($eventData) {
        $this->db->insert('event', $eventData);
        return $this->db->insert_id();
        //echo $this->db->last_query(); exit;
    }
    
    public function update_event($id, $data) {
        $this->db->where("id", $id);
        $this->db->update('event', $data);
        return $this->db->affected_rows();
    }
    
    public function delete_event($id) {
        $this->db->where("id", $id);
        $this->db->delete('event');
        return $this->db->affected_rows();
    }
    /*****Event*****/
    
    
    /*****Blogs*****/
    public function get_all_blog($limit = 0,$records_limit = 0,$condition_arr) {
        
        if($records_limit > 0){
            $numberofrecords = $records_limit;
        }else{
            $numberofrecords = (int) $this->config->item('record_limit');
        }
        
        if ($limit > 0){
            $limit = $limit - 1;
        }
        $startRecord = $limit * $numberofrecords;
        //echo $numberofrecords.$startRecord;die;
        $this->db->select('b.*,u.username as blog_created_by');
        $this->db->from('blogs b');
        $this->db->join('users u','u.id = b.user_id');
        $this->db->order_by("id", "desc");
        
        //get blogs according to user_id
        if(isset($condition_arr) && $condition_arr['user_id'] != '' )
        {
            $this->db->where('user_id',$condition_arr['user_id']);
        }
        
        //get blogs according to filter
        if(isset($condition_arr) && $condition_arr['filter'] != '' )
        {
            $filter_data = $condition_arr['filter'];
            $this->db->where("(blog_title = '$filter_data')");
        }
        
        $this->db->limit($numberofrecords, $startRecord);
        
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function get_blog_count($condition_arr) {
        $this->db->select('id');
        $this->db->from('blogs');
        
        if(isset($condition_arr) && $condition_arr['user_id'] != '' )
        {
            $this->db->where("user_id",$condition_arr['user_id']);
        }
        
        $query = $this->db->get();
        return $rowcount = $query->num_rows();
    }
    
    public function get_blog_details($blog_id) {
        
        $this->db->select('b.*,u.username as blog_created_by');
        $this->db->from('blogs b');
        $this->db->join('users u','u.id = b.user_id');
        $this->db->where('b.id',$blog_id);
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function add_blog($blogData) {
        $this->db->insert('blogs', $blogData);
        return $this->db->insert_id();
        //echo $this->db->last_query(); exit;
    }
    
    public function update_blog($id, $data) {
        $this->db->where("id", $id);
        $this->db->update('blogs', $data);
        return $this->db->affected_rows();
    }
    
    public function delete_blog($id) {
        $this->db->where("id", $id);
        $this->db->delete('blogs');
        return $this->db->affected_rows();
    }
    /*****Blogs*****/
    
    /*****Songs*****/
    public function get_all_song($limit = 0,$records_limit = 0,$condition_arr) {
        
        if($records_limit > 0){
            $numberofrecords = $records_limit;
        }else{
            $numberofrecords = (int) $this->config->item('record_limit');
        }
        
        if ($limit > 0){
            $limit = $limit - 1;
        }
        $startRecord = $limit * $numberofrecords;
        //echo $numberofrecords.$startRecord;die;
        $this->db->select('am.*,u.username as song_added_by');
        $this->db->from('artist_media am');
        $this->db->join('users u','u.id = am.user_id');
        $this->db->order_by("am.id", "desc");
        $this->db->where("am.media_type", "song");
        
        //get songs according to user_id
        if(isset($condition_arr) && $condition_arr['user_id'] != '' )
        {
            $this->db->where('user_id',$condition_arr['user_id']);
        }
        
        //get songs according to filter
        if(isset($condition_arr) && $condition_arr['filter'] != '' )
        {
            $filter_data = $condition_arr['filter'];
            $this->db->where("(media_title = '$filter_data')");
        }
            
        $this->db->limit($numberofrecords, $startRecord);
        
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function get_song_count($condition_arr) {
        $this->db->select('id');
        $this->db->from('artist_media');
        $this->db->where("media_type", "song");
        if(isset($condition_arr) && $condition_arr['user_id'] != '')
        {
            $this->db->where("user_id",$condition_arr['user_id']);
        }
        
        $query = $this->db->get();
        return $rowcount = $query->num_rows();
    }
    
    public function get_song_details($media_id) {
        
        $this->db->select('am.*,u.username as song_added_by');
        $this->db->from('artist_media am');
        $this->db->join('users u','u.id = am.user_id');
        $this->db->where('am.id',$media_id);
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function add_song($songData) {
        $this->db->insert('artist_media', $songData);
        return $this->db->insert_id();
        //echo $this->db->last_query(); exit;
    }
    
    public function update_song($id, $data) {
        $this->db->where("id", $id);
        $this->db->update('artist_media', $data);
        return $this->db->affected_rows();
    }
    
    public function delete_song($id) {
        $this->db->where("id", $id);
        $this->db->delete('artist_media');
        return $this->db->affected_rows();
    }
    /*****Songs*****/
    
    
    /*****Videos*****/
    public function get_all_video($limit = 0,$records_limit = 0,$condition_arr) {
        
        if($records_limit > 0){
            $numberofrecords = $records_limit;
        }else{
            $numberofrecords = (int) $this->config->item('record_limit');
        }
        
        if ($limit > 0){
            $limit = $limit - 1;
        }
        $startRecord = $limit * $numberofrecords;
        //echo $numberofrecords.$startRecord;die;
        $this->db->select('am.*,u.username as blog_created_by');
        $this->db->from('artist_media am');
        $this->db->join('users u','u.id = am.user_id');
        $this->db->order_by("am.id", "desc");
        $this->db->where("am.media_type", "video");
        
        //get videos according to user_id
        if(isset($condition_arr) && $condition_arr['user_id'] != '' )
        {
            $this->db->where('user_id',$condition_arr['user_id']);
        }
        
        //get videos according to filter
        if(isset($condition_arr) && $condition_arr['filter'] != '')
        {
            $filter_data = $condition_arr['filter'];
            //$this->db->where("(media_title = '$filter_data')");
            $this->db->where("am.media_title","$filter_data");
        }
        
        $this->db->limit($numberofrecords, $startRecord);
        
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function get_video_count($condition_arr) {
        $this->db->select('id');
        $this->db->from('artist_media');
        $this->db->where('media_type','video');
        if(isset($condition_arr) && $condition_arr['user_id'] != '')
        {
            $this->db->where("user_id",$condition_arr['user_id']);
        }
        
        $query = $this->db->get();
        return $rowcount = $query->num_rows();
    }
    
    public function get_video_details($media_id) {
        
        $this->db->select('am.*,u.username as video_added_by');
        $this->db->from('artist_media am');
        $this->db->join('users u','u.id = am.user_id');
        $this->db->where('am.id',$media_id);
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function add_video($videoData) {
        $this->db->insert('artist_media', $videoData);
        return $this->db->insert_id();
        //echo $this->db->last_query(); exit;
    }
    
    public function update_video($id, $data) {
        $this->db->where("id", $id);
        $this->db->update('artist_media', $data);
        return $this->db->affected_rows();
    }
    
    public function delete_video($id) {
        $this->db->where("id", $id);
        $this->db->delete('artist_media');
        return $this->db->affected_rows();
    }
    /*****Videos*****/
    
    /*****Songs*****/
    public function get_all_image($limit = 0,$records_limit = 0,$condition_arr) {
        
        if($records_limit > 0){
            $numberofrecords = $records_limit;
        }else{
            $numberofrecords = (int) $this->config->item('record_limit');
        }
        
        if ($limit > 0){
            $limit = $limit - 1;
        }
        $startRecord = $limit * $numberofrecords;
        //echo $numberofrecords.$startRecord;die;
        $this->db->select('am.*,u.username as image_added_by');
        $this->db->from('artist_media am');
        $this->db->join('users u','u.id = am.user_id');
        $this->db->order_by("am.id", "desc");
        $this->db->where("am.media_type", "image");
        
        //get songs according to user_id
        if(isset($condition_arr) && $condition_arr['user_id'] != '' )
        {
            $this->db->where('user_id',$condition_arr['user_id']);
        }
        
        //get songs according to filter
        if(isset($condition_arr) && $condition_arr['filter'] != '' )
        {
            $filter_data = $condition_arr['filter'];
            $this->db->where("(media_title = '$filter_data')");
        }
            
        $this->db->limit($numberofrecords, $startRecord);
        
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function get_image_count($condition_arr) {
        $this->db->select('id');
        $this->db->from('artist_media');
        $this->db->where("media_type", "image");
        if(isset($condition_arr) && $condition_arr['user_id'] != '')
        {
            $this->db->where("user_id",$condition_arr['user_id']);
        }
        
        $query = $this->db->get();
        return $rowcount = $query->num_rows();
    }
    
    public function get_image_details($media_id) {
        
        $this->db->select('am.*,u.username as image_added_by');
        $this->db->from('artist_media am');
        $this->db->join('users u','u.id = am.user_id');
        $this->db->where('am.id',$media_id);
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function add_image($imageData) {
        $this->db->insert('artist_media', $imageData);
        return $this->db->insert_id();
        //echo $this->db->last_query(); exit;
    }
    
    public function update_image($id, $data) {
        $this->db->where("id", $id);
        $this->db->update('artist_media', $data);
        return $this->db->affected_rows();
    }
    
    public function delete_image($id) {
        $this->db->where("id", $id);
        $this->db->delete('artist_media');
        return $this->db->affected_rows();
    }
    /*****Songs*****/
    
    
}

?>
