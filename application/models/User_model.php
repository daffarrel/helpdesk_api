<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    var $ip          = 'ip_address';
    var $table_user  = 'm_user';
    var $table_pic   = 'm_pic';
    var $view_user   = 'vw_user';

    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = null){
        if (!is_null($id)) {
            $query = $this->db->select('id_user')->from('ticket')->where('id_ticket', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }

            return null;
        }

        $query = $this->db->select('*')->from('ticket')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return null;
    }

    function login($id){
        if (!is_null($id)) {
            $query = $this->db->select('id_user,ip_address,username,role,role_name')->from($this->view_user)->where($this->ip, $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }

            return null;
        }
    }

    function getUserEmail($id){
        if (!is_null($id)) {
            $query = $this->db->select('email')->from($this->table_user)->where('id_user', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }

            return null;
        }
        return null;
    }

    function getPICEmail($id){
        if (!is_null($id)) {
            $query = $this->db->select('email')->from($this->table_user)->where('id_user', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }

            return null;
        }
        return null;
    }

    function getPICName($id){
        if (!is_null($id)) {
            $query = $this->db->select('username')
                              ->from($this->view_user)
                              ->where('role','2')
                              ->where('id_user', $id)
                              ->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }

            return null;
        }
        return null;
    }

    function getModEmail(){
        $query = $this->db->select('email')
                            ->from($this->table_pic)
                            ->where('role','3')
                            ->get();
        if ($query->num_rows() === 1) {
            return $query->row_array();
        }

        return null;
    }

}