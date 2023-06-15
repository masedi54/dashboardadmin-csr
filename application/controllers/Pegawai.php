<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Pegawai', 'p');
        $this->load->model('M_Auth', 'm_auth');
        $this->load->model('M_Sidebar', 'm_sidebar');
    }
	
	public function index()
	{
        $currentUser = $this->m_auth->getCurrentUser();
        $menu = $this->m_sidebar->getSidebarMenu($currentUser['user_group_id']);
        $data['menu'] = $menu;
        $data['unit'] = $this->p->getUnit();

        $this->load->view('template/sidebar', $data);
        $this->load->view('template/navbar');
        $this->load->view('admin/dataPegawai');
        $this->load->view('template/footer');
	}

	public function dataPegawai()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $query = $this->p->getDataPegawai();
        
        // var_dump($query);
        // die;
        $data = [];
        
        foreach($query->result() as $key => $r){          
            $data[] = array(
                'no' => $key+1,
                'id_pegawai' => $r->id_pegawai,
                'nama' => $r->nama,
                'hp' => $r->no_tlp,
                'unit' => $r->region,
                // 'action' => '
                // <a type="button" href="javascript:void(0)" onclick=" editPegawai('."'".$r->id_pegawai."'".')" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                // <a href="#" data-delete-url="' . site_url("index.php/pegawai/hapus/". $r->id_pegawai). '" role="button" onclick="return deleteConfirm(this)" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></a>'
                'action' => ($is_active = 0  ? '
                <form class="w-25" action="" method="post">
                <div class="form-check form-switch w-50">
                <input class="form-check-input" type="checkbox" role="switch" checked>
                </div>
                </form>
                ' : '
                <form class="w-25" action="" method="post">
                <div class="form-check form-switch w-50">
                <input class="form-check-input" onclick=changeStatus(' . $r->id . ',' . $r->is_active . ') id="switchActive' . $r->id . '" type="checkbox" role="switch" ' . $this->isChecked($r->is_active) . '>
                </div>
                </form>
                '),
            ); 
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $query->num_rows(),
            "recordFiltered" => $query->num_rows(),
            "data" => $data
        );
        echo json_encode($result);
        exit(); 
    }

    private function isChecked($is_active)
    {
        if ($is_active == 1) {
            return 'checked';
        } else {
            return '';
        }
    }

    public function changeStatus()
    {
        $param = $this->input->post();
        // print_r($param['checkPosition']);
        // print_r($param['id']);

        // die;
        if ($param) {
            $ubahStatus = $this->p->ubahStatusPegawai($param['id'], $param['checkPosition']);
            if ($ubahStatus['success'] == true) {
                return true;
            }
        }
    }

    public function tambahPegawai()
    {         
        
        $users = array(
            'nama' => $this->input->post('nama'),
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
            'region' => $this->input->post('unit'),  
            'user_group_id' => 3,
            'is_active' => 1,
        );
    
        $this->p->tambahUsers($users);
        $id_user = $this->db->insert_id();
        
        $data = [
            'id_user' => $id_user,
            'no_tlp' => $this->input->post('hp'),
            'unit_kerja' => $this->input->post('unit'),  
        ];

        $this->p->tambahPegawai($data);
        echo json_encode(['success' =>true, 'message' => 'Data Pegawai Berhasil Ditambahkan!']);
        
    }

    public function editPegawai()
    {

        $id = $this->input->post('id');
        $id_pegawai = $this->input->post('id_pegawai');

        $users = array(
            'nama' => $this->input->post('nama'),
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
            'user_group_id' => 3,
            'is_active' => 1,
        );
       
        $this->p->editUser($users, $id);

        $data = [
            'id_user' => $id,
            'no_tlp' => $this->input->post('hp'),
        ];

        $this->p->editPegawai($data, $id_pegawai);
        echo json_encode(['success' =>true, 'message' => 'Data Berhasil Diperbarui!']);
    }

    public function edit($id_pegawai)
    {
        $data = $this->p->pegawaiId($id_pegawai);
        // var_dump($data);
        // die;
        echo json_encode($data);
    }

    public function hapus($id_pegawai)
    {
        $this->p->hapusPegawai($id_pegawai);
        $this->p->hapusUsers($id);
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus!']); 
    }
}