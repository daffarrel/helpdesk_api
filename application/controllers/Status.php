<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Status extends REST_Controller {
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
        $this->load->model('status_model','tickets');
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
            $this->response(array('response' => $tickets), 200);
        } else {
            $this->response(array('error' => 'There are no data in database...'), 404);
        }
    }

}