<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Auth extends REST_Controller {
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
        $this->load->model('user_model','data');
    }

    public function index_get()
    {
        $id = $this->get('id');
        
        if($id == ''){
            $this->response(array('error' => 'There are no user in database...'), 404);
        }else{
            $result = $this->data->login($id);
        }

        if (!is_null($result)) {
            $this->response(array('id_user' => $result['id_user'],
                                  'user'    => $result['username'],
                                  'id_role' => $result['role'],
                                  'permissions' => $result['role_name'],
                                  'ip_addr' => $result['ip_address'],
                                  'login'   => TRUE
                                ), 200);
        } else {
            $this->response(array('error' => 'Login Fail',
                                  'permissions' => 'Not_User',
                                  'login' => FALSE
                                ), 201);
        }
    }
}