<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Home extends MY_Controller
{
    public function __construct($isRun = FALSE)
    {
        parent::__construct($isRun);
    }

    public function index()
    {
        $this->template->set('title', T_("Colégio Murialdo"));

        $this->load->model(PATH_TO_MODEL.'unidades/models/unidades_m');
        $units = $this->unidades_m->get();
        
        $this->template
             ->add_css('css/home')
             ->add_js('js/home')
             ->set('units', $units)
             ->build('home');
    }

}

