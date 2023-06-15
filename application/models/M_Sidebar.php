<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Sidebar extends CI_Model
{

    private $tableA = 'menu';


    public function getSidebarMenu($user_group_id)
    {
        $this->db->select('*')
            ->from($this->tableA)
            ->where([
                'user_group_id' => $user_group_id,
                'is_active' => '1'
            ]);
            
        return $this->db->get()->result_array();
    }
}
