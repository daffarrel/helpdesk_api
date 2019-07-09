<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Category extends REST_Controller {
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
        $this->load->model('category_model','category');
    }

    public function index_get()
    {
        $id = $this->get('id');
        
        if($id == ''){
            $category = $this->category->get();
        }else{
            $category = $this->category->get($id);
        }

        if (!is_null($category)) {
            $this->response($category, 200);
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
        if (!$this->post('category')) {
            $this->response(null, 400);
        }
        
        $id = $this->category->save($this->post('category'));

        if (!is_null($id)) {
            $this->response(array('response' => "ID : ".$id." Success"), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'), 400);
        }
    }

    public function index_put()
    {
        if (!$this->put('category')) {
            $this->response(null, 400);
        }

        $update = $this->category->update($this->put('category'));

        if (!is_null($update)) {
            $this->response(array('response' => 'Category Updated!'), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'), 400);
        }
    }

    public function index_delete($id)
    {
        if (!$id) {
            $this->response(null, 400);
        }

        $delete = $this->category->delete($id);

        if (!is_null($delete)) {
            $this->response(array('response' => 'Category Deleted!'), 200);
        } else {
            $this->response(array('error', 'Something has broken in the server...'), 400);
        }
    }
}