<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Unidade extends MY_Controller
{
    public function __construct($isRun = FALSE)
    {
        parent::__construct($isRun);
        $this->load->model('comum/comum_m');
        $this->load->model(PATH_TO_MODEL.'unidades/models/unidades_m');
        $this->load->model(PATH_TO_MODEL.'diferenciais/models/diferenciais_m');
        $this->load->model(PATH_TO_MODEL.'valores/models/valores_m');
        $this->load->model(PATH_TO_MODEL.'niveis_de_ensino/models/niveis_de_ensino_m');
        $this->load->model(PATH_TO_MODEL.'depoimentos/models/depoimentos_m');
        $this->load->helper('text');
    }

    public function index($slug=false)
    {

        if(!$slug && !is_array($slug)){
            show_404();
        }else{
            $current_unit = $this->unidades_m->get(array("slug" => reset($slug)));
            $contentPage = (object) array(
                'banner_header'       => $this->comum_m->getPageContent(array("slug" => "home-banner")),
                'current_unit'        => $current_unit,
                'units'               => $this->unidades_m->get(),
                'differentials'       => $this->diferenciais_m->get(array("id_unit" => $current_unit->id)),
                'worth'               => $this->valores_m->get(array("id_unit" => $current_unit->id)),
                'levels'              => $this->niveis_de_ensino_m->get(array("id_unit" => $current_unit->id)),
                'testimonial'         => $this->depoimentos_m->get(array("id_unit" => $current_unit->id)),
                'content_bilingue'    => $this->comum_m->getPageContent(array("slug" => "home-programa-bilingue")),
                'content_extras'      => $this->comum_m->getPageContent(array("slug" => "home-atividades-extras")),
                'content_testimonial' => $this->comum_m->getPageContent(array("slug" => "home-depoimentos"))
            );

            if ($contentPage->current_unit)
                $this->template->set('title', T_("Colégio Murialdo"). " - ".$contentPage->current_unit->title);
            
            $this->template
                ->add_css('css/unidade')
                ->add_js('plugins/jquery.validate.min', 'comum')
                ->add_js('plugins/masks.min', 'comum')
                ->add_js('plugins/slick/slick.min.js', 'comum')
                ->add_js('plugins/calendar.js', 'comum')
                ->add_js('js/unidade')
                ->add_css('plugins/select2/select2.min', 'comum')
                ->add_js('plugins/select2/select2.full.min', 'comum')
                ->set("contentPage", $contentPage)
                ->set_partial('header', 'header', 'comum')
                ->set_partial('footer', 'footer', 'comum')
                ->build('unidade');
        }
    }

    public function send()
    {
        $this->input->is_ajax_request() || show_404();

        // Validação
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', T_('Nome'), 'trim|required');
        $this->form_validation->set_rules('email', T_('E-mail'), 'trim|required|valid_email');
        $this->form_validation->set_rules('phone', T_('Telefone'), 'trim|required');

        if ($this->form_validation->run()) {

            $this->load->model(PATH_TO_MODEL.'inscricoes/models/inscricoes_m');

            $data = $this->input->post();
            $this->load->helper('email_helper');

            $emails = array(
                'to'        => ENVIRONMENT == 'production' ? $this->company->email : 'thiago@ezoom.com.br',
                'replyTo'   => $data['email']
            );

            $body = array();
            $body['Nome']         = mb_convert_encoding($data['name'], 'utf-8', 'auto');
            $body['E-mail']       = mb_convert_encoding($data['email'], 'utf-8', 'auto');
            $body['Telefone']     = mb_convert_encoding($data['phone'], 'utf-8', 'auto');

            //enviar_email($emails, T_('Inscrição').' - '.$this->company->meta_title, $body);

            $this->inscricoes_m->insert($data);

            $retorno = array(
                'status'    => TRUE,
                'class'     => 'success',
                'title'     => T_('Sucesso'),
                'message'   => T_('Sua inscrição foi enviada com sucesso. Em breve entraremos em contato.')
            );
             
        }else {
            $retorno = array('status'=> FALSE, 'class' => 'alert', 'title' => T_('Ocorreu um erro!'), 'message' => validation_errors(), 'fail' => $this->form_validation->error_array(TRUE));
        }

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($retorno));
    }

    public function get_levels()
    {
        if (!$this->input->is_ajax_request())
            show_404();

        $retorno['status'] = true;
        $retorno['levels'] = $this->niveis_de_ensino_m->get(array("id_unit" => $this->input->post('id_unit')));

        $this->output->set_content_type('application/json')
            ->set_output(json_encode($retorno));
    }

    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method))
            return call_user_func_array(array($this, $method), $params);
        else{
            $segments = $params;
            array_unshift($segments, $method);
            $segments = array_map(array($this, 'fix_slug'), $segments);

            $pg = end($segments);
            if( is_numeric($pg) ){
                array_pop($segments);
                call_user_func_array(array($this, 'index'), array(0 => ( $segments ? $segments : false), 1 => $pg) );
            } else
                call_user_func_array(array($this, 'index'), array($segments) );

        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
