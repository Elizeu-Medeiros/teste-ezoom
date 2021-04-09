<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class home extends MY_Controller
{
    private $tables = array(
        //'produtos' => array('model' => 'produtos/produtos_m', 'pathImage' => 'produtos/'),
        // 'eventos' => array('model' => 'eventos/evento_m', 'pathImage' => 'eventos/evento/'),
        // 'notícias' => array('model' => 'redacao/noticias_m', 'pathImage' => 'redacao/noticias/'),
        // 'links' => array('model' => 'links/links_m', 'pathImage' => 'links/'),
      );

    public function index()
    {
        $showPreview = array();

        foreach ($this->tables as $key => $value) {
            $this->load->model($value['model'], $key);
            $data = $this->$key->get( array( 'limit' => 10 ) );
            if( !empty($data) )
                $showPreview[$key] = $data;
        }

        $this->template
            ->add_css('css/home')
            ->add_js('js/home')
            ->add_css('plugins/owl-carousel/owl.carousel', 'comum')
            ->add_js('plugins/owl-carousel/owl.carousel.min', 'comum')
            ->set('showPreview', $showPreview )
            ->set('tables', $this->tables )
            ->build('home');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
