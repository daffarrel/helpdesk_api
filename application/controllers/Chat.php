<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Chat extends REST_Controller {
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }
        parent::__construct();
        $this->load->model('chat_model','data');
        $this->load->model('ticket_model','ticket');
    }

    public function index_get()
    {
        $id = $this->get('id');
        
        if($id == ''){
            //$result = $this->data->get();
            $this->response(null, 400);
        }else{
            $result = $this->data->get($id);
        }

        $user = $this->ticket->get_user($id);

        foreach($result as &$message) {
            $message['me'] = $user['id_user'] == $message['user'];
        }

        if (!is_null($result)) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'There are no data in database...'), 204);
        }
    }
    
    public function index_post()
    {
        if (!$this->post('message')) {
            $this->response(null, 400);
        }
        
        $id = $this->data->save($this->post('message'));

        if (!is_null($id)) {
            $this->response(array('response' => "ID : ".$id." Success"), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'), 400);
        }
    }
}