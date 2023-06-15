<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('M_Sidebar', 'm_sidebar');
        $this->load->model('M_Auth', 'm_auth');

        $user_group_id = $this->session->userdata('user_group_id');


        if ($this->session->has_userdata('id_user') == false) {
            redirect('index.php/auth');
        }
    }
	
	public function index()
	{
        $currentUser = $this->m_auth->getCurrentUser();
        $menu = $this->m_sidebar->getSidebarMenu($currentUser['user_group_id']);
        $data['menu'] = $menu;

        $this->load->view('template/sidebar', $data);
        $this->load->view('template/navbar');
        $this->load->view('dashboard');
        $this->load->view('template/footer');
	}

	
}