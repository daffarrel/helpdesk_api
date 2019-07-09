<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_sample extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    var $table = 'tbl_sample';

    public function get($id = null)
    {
        if (!is_null($id)) {
            $query = $this->db->select('*')->from($this->table)->where('id', $id)->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }

            return null;
        }

        $query = $this->db->select('*')->from($this->table)->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return null;
    }

    public function save($data)
    {
        $this->db->set($this->_setTicket($data))->insert($this->table);

        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }

        return null;
    }

    public function update($data)
    {
        $id = $data['id'];

        $this->db->set($this->_setTicket($data))->where('id', $id)->update($this->table);

        if ($this->db->affected_rows() === 1) {
            return true;
        }

        return null;
    }


    public function delete($id)
    {
        $this->db->where('id', $id)->delete($this->table);
        if ($this->db->affected_rows() === 1) {
            return true;
        }

        return null;
    }

    private function _setTicket($data)
    {
        return array(
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
        );
    }
}