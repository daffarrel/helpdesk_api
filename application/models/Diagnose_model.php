<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnose_model extends CI_Model
{
    var $id = 'id_ticket';
    var $table = 'diagnose';
    var $view = 'vw_diagnose';

    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = null)
    {
        if (!is_null($id)) {
            $query = $this->db->select('*')->from($this->view)->where($this->id, $id)->get();
            if ($query->num_rows() > 0) {
                return $query->result_array();
            }

            return null;
        }

        $query = $this->db->select('*')->from($this->view)->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return null;
    }

    public function save($ticket)
    {
        $this->db->set($this->_setData($ticket))->insert($this->table);

        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }

        return null;
    }

    public function update($ticket)
    {
        $id = $ticket['id_ticket'];

        $this->db->set($this->_setTicket($ticket))->where('id_ticket', $id)->update('ticket');

        if ($this->db->affected_rows() === 1) {
            return true;
        }

        return null;
    }

    private function _setData($data)
    {
        return array(
            'id_ticket'     => $data['id_ticket'],
            'str_diagnose'  => $data['str_diagnose'],
            'date'          => date("Y-m-d H:i:s"),
            'user'          => $data['user'],
        );
    }

    function getDiagnoseData($id){
        $query = $this->db->select('str_diagnose')
                 ->from($this->table)
                 ->where('id_ticket',$id)
                 ->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
}