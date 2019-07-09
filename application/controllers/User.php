<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class User extends REST_Controller {
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
        $this->load->model('user_model','tickets');
    }

    public function index_get()
    {
        $id = $this->get('id');
        
        if($id == ''){
            $tickets = $this->tickets->get();
        }else{
            $tickets = $this->tickets->get($id);
        }

        if (!is_null($tickets)) {
            $this->response($tickets, 200);
        } else {
            $this->response(array('error' => 'There are no data in database...'), 404);
        }
    }

    /*
    public function find_get($id)
    {
        if (!$id) {
            $this->response(null, 400);
        }
        $ticket = $this->tickets->get($id);

        if (!is_null($ticket)) {
            $this->response(array('response' => $ticket), 200);
        } else {
            $this->response(array('error' => 'Ticket Not Found...'), 404);
        }
    }
    */
    
    public function index_post()
    {
        if (!$this->post('ticket')) {
            $this->response(null, 400);
        }
        
        $id = $this->tickets->save($this->post('ticket'));

        if (!is_null($id)) {
            $this->response(array('response' => "ID : ".$id." Success"), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'), 400);
        }
    }

    public function index_put()
    {
        if (!$this->put('ticket')) {
            $this->response(null, 400);
        }
        
        $update = $this->tickets->update($this->put('ticket'));

        if (!is_null($update)) {
            $this->response(array('response' => 'Ticket Updated!'), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'.$update), 400);
        }
    }

    public function index_delete($id)
    {
        if (!$id) {
            $this->response(null, 400);
        }

        $delete = $this->tickets->delete($id);

        if (!is_null($delete)) {
            $this->response(array('response' => 'Ticket Canceled!'), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'), 400);
        }
    }
}