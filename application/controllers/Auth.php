<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	
	public function index()
	{
		$this->load->view('login');
	}

	public function login()
    {
        $username = $this->input->post('username');
        $password =  $this->input->post('password');
		// $password =  hash('sha256', $this->input->post('password'));
		

        $user = $this->db->get_where('users', ['username' => $username])->row_array();

        if ($user) {

            if ($user['is_active'] == 1) {

                if ($password == $user['password']) {
                    $data = [
                        'id_user' => $user['id'],
                        'username' => $user['username'],
                        'user_group_id' => $user['user_group_id']
                    ];

                    $this->session->set_userdata($data);
                    redirect('index.php/dashboard');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!');
                    redirect('gagal');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Your account is inactive, please contact the admin!');
                redirect('gagal');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Your account is not registered');
            redirect('gagal');
        }
    }

    public function logout()
    {

        $this->session->unset_userdata('username');
        $this->session->unset_userdata('user_group_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logged out');
        redirect('index.php/auth');
    }
}