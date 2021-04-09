<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Controller
 *
 * @package ezoom
 * @subpackage Depoimentos
 * @category Controller
 * @author Thiago Macedo Medeiros
 * @copyright 2020 Ezoom
 */
class Depoimentos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($pg = 1)
    {
        parent::index($pg);
    }

    public function cadastrar()
    {
        $this->formulario();
    }

    public function editar($id)
    {
        parent::editar($id);
        $this->formulario($id);
    }

    protected function formulario($id = false)
    {
        $this->load->model('unidades/unidades_m');
        $units = $this->unidades_m->get();

        $this->ckeditor();
        $this->fileupload();
        $this->template
             ->add_css('css/gallery', 'gallery')
             ->add_css('css/diferenciais.css')
             ->add_js('js/gallery', 'gallery')
             ->add_js('js/diferenciais.js')
             ->set('units', $units);
        parent::formulario($id);
    }

    public function add()
    {
        $this->load->library('form_validation');

        foreach($this->languages as $key => $language){
            if ($language->code == 'pt') {
                $this->form_validation->set_rules('value['.$language->id.'][title]', T_('Título').' ('.$language->code.')', 'trim|required');
            }
        }
        if ($this->form_validation->run() === TRUE){
            parent::add();
        } else {
            $errors = array_values($this->form_validation->error_array());
            $response = array('status'=> false, 'classe'=> 'error','message' => $errors[0], 'redirect' => false);
            $this->output->set_output(json_encode($response));
        }
    }

    public function edit($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', 'ID', 'trim|required');

        foreach($this->languages as $key => $language){
            if ($language->code == 'pt') {
                $this->form_validation->set_rules('value['.$language->id.'][title]', T_('Título').' ('.$language->code.')', 'trim|required');
            }
        }
        if ($this->form_validation->run() === TRUE){
            parent::edit($id);
        } else {
            $errors = array_values($this->form_validation->error_array());
            $response = array('status'=> false, 'classe'=> 'error','message' => $errors[0], 'redirect' => false);
            $this->output->set_output(json_encode($response));
        }
    }
}
