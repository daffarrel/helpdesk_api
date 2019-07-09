<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pic_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    var $table = 'm_pic';
    var $view  = 'vw_pic';

    public function get($id = null)
    {
        if (!is_null($id)) {
            $query = $this->db->select('*')->from($this->table)->where('id_pic', $id)->where('id_pic != 0')->get();
            if ($query->num_rows() === 1) {
                return $query->row_array();
            }

            return null;
        }

        $query = $this->db->select('*')->from($this->table)->where('id_pic != 0')->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }

        return null;
    }

    public function save($pic)
    {
        $this->db->set($this->_setPic($pic))->insert($this->table);

        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }

        return null;
    }

    public function update($pic)
    {
        $id = $pic['id'];

        $this->db->set($this->_setTicket($pic))->where('id_ticket', $id)->update($this->table);

        if ($this->db->affected_rows() === 1) {
            return true;
        }

        return null;
    }

    public function delete($id)
    {
        //$this->db->where('id_ticket', $id)->delete('ticket');
        $this->db->set('stat','C')->where('id_ticket',$id)->update($this->table);
        if ($this->db->affected_rows() === 1) {
            return true;
        }

        return null;
    }

    private function _setPic($pic)
    {
        return array(
            'problems'      => $pic['problems'],
            'attachment'    => $pic['attachment'],
            'id_category'   => $pic['id_category'],
            'id_pic'        => $pic['id_pic'],
            'id_user'       => $pic['id_user'],
            'progress'      => $pic['progress'],
            'stat'          => $pic['stat'],
            'created_date'  => $pic['created_date'],
            'created_by'    => $pic['created_by'],
        );
    }
}