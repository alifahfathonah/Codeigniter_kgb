<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Laporan extends CI_Controller {
    public function __construct() {   
        parent::__construct();
        if($this->session->userdata('logged_in_admin') !== TRUE){
            redirect('login');
        }
        $this->load->model('M_duk');
        $this->load->model('M_pegawai');
    }
    
    public function check(){
        $id=$this->uri->segment(3);
        $nip=$this->uri->segment(4);
        $data['pangkat']=[
            'Pembina Utama','Pembina Utama Madya','Pembina Utama Muda','Pembina Tingkat I','Pembina',
            'Penata Tingkat I','Penata','Penata Muda Tingkat I', 'Penata Muda',
            'Pengatur Tingkat I','Pengatur','Pengatur Muda Tingkat I','Pengatur Muda',
            'Juru Tingkat I','Juru','Juru Muda Tingkat I','Juru Muda'
        ];
        $data['gol']=[
            'IV/a','IV/b','IV/c','IV/d','IV/e',
            'III/a','III/b','III/c','III/d',
            'II/a','II/b','II/c','II/d',
            'I/a','I/b','I/c','I/d'
        ];
        $data['pegawai']=$this->M_pegawai->getPegawaiById($id);
        $data['duk']=$this->M_duk->getDataDukById($id);
        $data['gaji']=$this->M_duk->dataGaji($id,$nip);
        $data['gaji_baru']=$this->M_duk->dataGajiBaru($id,$nip);
        $this->form_validation->set_rules('no1','Nomor','xss_clean|required');
        $this->form_validation->set_rules('nip','NIP','xss_clean|required|numeric|max_length[18]');
        $this->form_validation->set_rules('nama','Nama','xss_clean|required');
        $this->form_validation->set_rules('pangkat','Pangkat','xss_clean|required');
        $this->form_validation->set_rules('golongan','Golongan','xss_clean|required');
        $this->form_validation->set_rules('jabatan','Jabatan','xss_clean|required');
        $this->form_validation->set_rules('unit','Unit','xss_clean|required');
        $this->form_validation->set_rules('pejabat','Pejabat','xss_clean|required');
        $this->form_validation->set_rules('tmt_gaji1','Terhitung Mulai Tanggal','xss_clean|required');
        $this->form_validation->set_rules('no2','Nomor','xss_clean|required');
        $this->form_validation->set_rules('gaji_pokok','Gaji Pokok','xss_clean|required');
        $this->form_validation->set_rules('mkgt1','MKGT','xss_clean|required');
        $this->form_validation->set_rules('mkgb1','MKGB','xss_clean|required');
        $this->form_validation->set_rules('gaji_pokok2','Gaji Pokok Baru','xss_clean|required|numeric');
        $this->form_validation->set_rules('mkgt2','MKGT2','xss_clean|required');
        $this->form_validation->set_rules('mkgb2','MKGB2','xss_clean|required');
        $this->form_validation->set_rules('golongan2','Golongan2','xss_clean|required');
        $this->form_validation->set_rules('tmt_gaji2','tmt_gaji2','xss_clean|required');
        $this->form_validation->set_rules('tmt_gaji3','tmt_gaji3','xss_clean|required');
        if($this->form_validation->run() == FALSE){
            $data['sidebar']="#mn1";
            $this->load->view('header');
            $this->load->view('check',$data);
            $this->load->view('footer', $data);
        }else{
            $data=[
                'no1'=>$this->input->post('no1',true),
                'nip'=>$this->input->post('nip',true),
                'nama'=>$this->input->post('nama',true),
                'pangkat'=>$this->input->post('pangkat',true),
                'golongan'=>$this->input->post('golongan',true),
                'jabatan'=>$this->input->post('jabatan',true),
                'unit'=>$this->input->post('unit',true),
                'pejabat'=>$this->input->post('pejabat',true),
                'tmt_gaji1'=>$this->input->post('tmt_gaji1',true),
                'no2'=>$this->input->post('no2',true),
                'gaji_pokok'=>$this->input->post('gaji_pokok',true),
                'mkgt1'=>$this->input->post('mkgt1',true),
                'mkgb1'=>$this->input->post('mkgb1',true),
                'gaji_pokok2'=>$this->input->post('gaji_pokok2',true),
                'mkgt2'=>$this->input->post('mkgt2',true),
                'mkgb2'=>$this->input->post('mkgb2',true),
                'golongan2'=>$this->input->post('golongan2',true),
                'tmt_gaji2'=>$this->input->post('tmt_gaji2',true),
                'tmt_gaji3'=>$this->input->post('tmt_gaji3',true)
            ];
            $data['ketua']=$this->M_duk->getKetua();
            $data['nipketua']=$this->M_duk->getNipKetua();
            $this->M_duk->updateDataKGB($this->input->post('id'));
            $this->load->library('Pdf');
            $this->load->view('laporan',$data);
        }
    }

    public function cek($id){
        $data['sidebar']="#mn1";
        $this->load->view('header');
        $this->load->view('cek',$data);
        $this->load->view('footer', $data);
    }

    public function print($id){
        $this->load->library('Pdf');
        $this->load->view('laporan2');
    }
}