<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Sample extends REST_Controller {
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
        $this->load->model('M_sample','user');
    }

    public function index_get()
    {
        $id = $this->get('id');
        
        if($id == ''){
            $data= $this->user->get();
        }else{
            $data = $this->user->get($id);
        }

        if (!is_null($data)) {
            $this->response($data, 200);
        } else {
            $this->response(array('error' => 'There are no data in database...'), 404);
        }
    }
    
    public function index_post()
    {
        if (!$this->post('user')) {
            $this->response(null, 400);
        }
        
        $id = $this->user->save($this->post('user'));

        if (!is_null($id)) {
            $this->response(array('response' => "ID : ".$id." Success"), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'), 400);
        }
    }

    public function index_put()
    {
        if (!$this->put('user')) {
            $this->response(null, 400);
        }
        
        $update = $this->user->update($this->put('user'));

        if (!is_null($update)) {
            $this->response(array('response' => 'Data Updated!'), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'.$update), 400);
        }
    }

    public function index_delete($id)
    {
        if (!$id) {
            $this->response(null, 400);
        }

        $delete = $this->user->delete($id);

        if (!is_null($delete)) {
            $this->response(array('response' => 'Data Deleted!'), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'), 400);
        }
    }
}