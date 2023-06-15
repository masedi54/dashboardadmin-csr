<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Auth extends CI_Model
{
    private $_table = "users";
    private $tableB = 'users_group';
    const SESSION_KEY = 'id';


    public function current_user()
    {
        if (!$this->session->has_userdata(self::SESSION_KEY)) {
            return null;
        }
        $id_user = $this->session->userdata(self::SESSION_KEY);
        $query = $this->db->get_where($this->_table, ['id' => $id_user]);
        return $query->row();
    }

    public function getCurrentUser()
    {
        $this->db->select('*')
            ->from($this->_table)
            ->where(['id' => $this->session->userdata('id_user')]);
        return $this->db->get()->row_array();
    }

    public function users($username)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('user.username', $username);
        $query = $this->db->get();
        return $query;
    }
}