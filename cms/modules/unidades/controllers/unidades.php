<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Controller
 *
 * @package ezoom
 * @subpackage Unidades
 * @category Controller
 * @author Thiago Macedo Medeiros
 * @copyright 2020 Ezoom
 */
class Unidades extends MY_Controller
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

        $countries = $this->comum_m->get_countries();
        $states = $this->comum_m->get_state();
        
        $this->ckeditor();
        $this->fileupload();
        $this->template->add_css('css/endereco', 'endereco')
            ->add_js('js/endereco', 'endereco')
            ->add_js('https://maps.googleapis.com/maps/api/js?key='.GMAPS_KEY)
            ->set('countries', $countries)
            ->set('states', $states)
            ->add_css('css/gallery', 'gallery')
            ->add_js('js/gallery', 'gallery')
            ->add_css('css/unidades.css')
            ->add_js('js/unidades.js');
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
