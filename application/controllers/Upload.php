<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use Restserver\Libraries\REST_Controller;

class Upload extends REST_Controller {
    public function __construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }
        parent::__construct();
        $this->load->model('ticket_model','tickets');
    }

    public function index_post() {
        if (!$this->post('id_ticket')) {
            $this->response("No Data Sent", 400);
        }
        
        $id = $this->post('id_ticket');
        //$file = $this->input->post('file');
        $update = $this->upload_file($id);

        if (!is_null($update)) {
            $this->response(array('response' => 'Upload Success!'), 200);
        } else {
            $this->response(array('error', 'Fail to Upload...'.$update), 400);
        }
    }

    function upload_file($id){
        $file = $_FILES['file'];
        if($file){
            $tahun = (new DateTime())->format('Y');
            $bulan = (new DateTime())->format('M');
            $hari  = (new DateTime())->format('D');
            $type  = 'attachment';
            $path  = $_FILES['file']['name'];
            $ext   = pathinfo($path, PATHINFO_EXTENSION);

            $new_name   = "attch_".$hari."_".$bulan."_".$tahun."_".$id;
            $user = $this->tickets->get_user($id);      
            $folderName = $user['id_user'];
            $config['file_name']   = $new_name;
            $config['upload_path'] = './file/'.$folderName.'/'.$type.'/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
            $config['quality'] = '75';
            $config['max_size'] = 4096;

            if(!is_dir('./file/'.$folderName.'/'.$type))
            {
                mkdir('./file/'.$folderName.'/'.$type, 0777,true);
            }

            $this->upload->initialize($config);

            if($this->upload->do_upload('file'))
            {
                $gbr     = $this->upload->data();
                $configer =  array(
                    'image_library'   => 'gd2',
                    'source_image'    =>  $gbr['full_path'],
                    'maintain_ratio'  =>  TRUE,
                    'width'           =>  1440,
                    'height'          =>  1920,
                );
                $this->image_lib->clear();
                $this->image_lib->initialize($configer);
                $this->image_lib->resize();
                $gambar  = $gbr['file_name']; //Mengambil file name dari gambar yang diupload
                $type    = $gbr['image_type'];
                $now = (new DateTime())->format('Y-m-d');
                $this->tickets->simpan_upload($id,$gambar,$type);
                return TRUE;
            }
            else{
                return FALSE;
            }
        }
    }
}