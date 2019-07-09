<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Progress extends REST_Controller {
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
        $this->load->model('ticket_model','progress');
    }

    public function index_get()
    {
        $id = $this->get('id');
        
        if($id == ''){
            $data = $this->progress->get_progress();
        }else{
            $data = $this->progress->get_progress($id);
        }

        if (!is_null($data)) {
            $this->response($data, 200);
        } else {
            $this->response(array('error' => 'There are no data in database...'.$id), 404);
        }
    }
    
    public function index_post()
    {
        if (!$this->post('ticket')) {
            $this->response(null, 400);
        }
        
        $id = $this->progress->save($this->post('ticket'));

        if (!is_null($id)) {
            $this->response(array('response' => "ID : ".$id." Success"), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'), 400);
        }
    }

    public function index_put()
    {   
        if (!$this->put('progress')) {
            $this->response(null, 200);
        }
        
        $update = $this->progress->update_progress($this->put('progress'));

        if (!is_null($update)) {
            $this->response(array('response' => 'Progress Updated!'), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'.$this->put('ticket')['id_ticket'].' '.$this->put('ticket')['progress']), 200);
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