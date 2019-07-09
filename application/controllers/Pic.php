<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Pic extends REST_Controller {
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
        $this->load->model('pic_model','pic');
    }

    public function index_get()
    {
        $id = $this->get('id');
        
        if($id == ''){
            $pic = $this->pic->get();
        }else{
            $pic = $this->pic->get($id);
        }

        if (!is_null($pic)) {
            $this->response($pic, 200);
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
        if (!$this->post('pic')) {
            $this->response(null, 400);
        }
        
        $id = $this->pic->save($this->post('pic'));

        if (!is_null($id)) {
            $this->response(array('response' => "ID : ".$id." Success"), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'), 400);
        }
    }

    public function index_put()
    {
        if (!$this->put('pic')) {
            $this->response(null, 400);
        }

        $update = $this->pic->update($this->put('pic'));

        if (!is_null($update)) {
            $this->response(array('response' => 'PIC Updated!'), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'), 400);
        }
    }

    public function index_delete($id)
    {
        if (!$id) {
            $this->response(null, 400);
        }

        $delete = $this->pic->delete($id);

        if (!is_null($delete)) {
            $this->response(array('response' => 'PIC Deleted!'), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'), 400);
        }
    }
}