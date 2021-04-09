<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Model
 *
 * @package ezoom
 * @subpackage comum
 * @category Model
 * @author Ralf da Rocha
 * @copyright 2015 Ezoom
 */
class comum_m extends MY_Model
{
    public $table = 'site_common_content';
    public $table_description = 'site_common_content_description';
    public $table_config = 'site_common_content_configuration';
    public $table_gallery = 'site_common_content_gallery';
    //public $table_gallery_description = 'site_common_content_gallery_description';
    public $table_videos = 'site_common_content_video';
    public $primary_key = 'id';
    public $foreign_key = 'id_common_content';

    public $table_file = 'ez_file';
    public $table_file_description = 'ez_file_description';

    public $image_fields = array('image', 'favicon', 'landing', 'landing_mobile');

    public function __construct()
    {
        parent::__construct();
    }

    public function get_states($id_state = false)
    {
        $this->db->select('*')
            ->from('ez_state')
            ->order_by('uf');
        if ($id_state) {
            $this->db->where('id', $id_state);
        }
        $query = $this->db->get();

        return ($id_state) ? $query->row() : $query->result();
    }

    public function get_cities($id_state = false, $id_city = false)
    {
        $this->db->select('*')
            ->from('ez_city')
            ->order_by('name');

        if ($id_state)
            $this->db->where('id_state', $id_state);

        if ($id_city)
            $this->db->where('id', $id_city);

        $query = $this->db->get();

        return ($id_city) ? $query->row() : $query->result();
    }

    public function get_countries()
    {
        $this->db->select('*')
            ->from('ez_country')
            ->join('ez_country_description', 'ez_country.id = ez_country_description.id_country', 'INNER')
            ->where('ez_country_description.id_language', 1)
            ->order_by('name');

        $query = $this->db->get();

        return $query->result();
    }

    public function get_continents($where = false)
    {
        $id_lang = $this->lang->id();
        $this->db->select('*')
            ->from('ez_continent')
            ->join('ez_continent_description', 'ez_continent.id = ez_continent_description.id_continent', 'INNER')
            ->where('ez_continent.status', 1)
            ->where('ez_continent_description.id_language', $id_lang)
            ->order_by('name');

        if ($where)
            $this->db->where($where);

        $query = $this->db->get();
        return ($where) ? $query->row() : $query->result();
    }

    /**
     * Lista os conteÃºdos relacionados Ã s empresas ativas
     * @author Ralf da Rocha [ralf@ezoom.com.br]
     * @date   2016-07-27
     * @return [type]     [description]
     */
    public function getActiveCompanies()
    {
        $this->db->select('ez_company.id, fantasy_name AS name, slug, domain, phone, email')
            ->from('ez_company');

        foreach($this->image_fields as $keyImage => $image_field){
            $this->db->select('file_'.$keyImage.'.file as '.str_replace('id_', '', $image_field));
            $this->db->join($this->table_file.' as file_'.$keyImage, 'ez_company.'.$image_field.' = file_'.$keyImage.'.id', 'left', false);
        }
        $this->db->where('ez_company.status', 1)
            ->where('ez_company.active_site', 1)
            ->order_by('order_by', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Retorna as meta keys e descritions
     * @author Diogo Taparello [diogo@ezoom.com.br]
     * @date   2015-04-28
     * @return [object]
     */
    public function getMetas($route)
    {
        $this->db->select('ez_route_description.seo_title, ez_route_description.seo_description, ez_route_description.seo_keywords')
            ->from('ez_route_description')
            ->where('ez_route_description.url', $route);
        $query = $this->db->get();
        return $query->row();
    }

    public function getGalleryContent($id)
    {
        return $this->get_gallery_images($id);
        // $this->db->select('gallery.file')
        //          ->from('site_common_content_gallery as gallery')
        //          ->where('id_common_content', $id)
        //          ->order_by('highlighted DESC, order_by ASC');

        // $this->db->select('gallery.subtitle');
        /*$this->db->select('gallery_desc.subtitle')
                 ->join('site_common_content_gallery_description as gallery_desc', 'gallery_desc.id_gallery = gallery.id')
                 ->join('ez_language', 'gallery_desc.id_language = ez_language.id')
                 ->where('ez_language.code' , $this->lang->lang());*/

        // $query = $this->db->get();
        // return $query->result();
    }

    public function getVideosContent($id)
    {
        $this->db->select('title,link')
            ->from('site_common_content_video')
            ->where('id_common_content', $id)
            ->where('id_language', $this->current_lang)
            ->order_by('id');

        $query = $this->db->get();
        return $query->result();
    }

    public function getDifferentials($id){
        $this->db->select('D.*')
                 ->from('site_common_content_differentials A')
                 ->join('site_differential_description D', 'D.id_differential = A.id_differential AND D.id_language = '. $this->current_lang ,'left')
                 ->where('id_common_content', $id);

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Retorna as paginas padroes
     * @author Diogo Taparello [diogo@ezoom.com.br]
     * @date   2015-04-20
     * @param  [boolean]     pega apenas endereÃ§o principal
     * @return [object]
     */
    public function getPageContent($params = array())
    {
        $options = array(
            'slug' => false,
            'area' => false,
            'hierarchy' => false,
            'key' => 0 //Chave do array quando nÃ£o Ã© hierarquico { (1) key (0) slug }
        );
        $params = array_merge($options, $params);

        $retorno = array();

        $this->db->select('site_common_content.slug, site_common_content_description.*, site_common_content.id AS id, site_common_content_configuration.enable_gallery, site_common_content_configuration.enable_videos')
            ->from('site_common_content')
            ->join('site_common_content_configuration', 'site_common_content_configuration.id_common_content = site_common_content.id ', 'INNER')
            ->join('site_common_content_description', 'site_common_content_description.id_common_content = site_common_content.id ', 'left')
            ->join('ez_language', 'site_common_content_description.id_language = ez_language.id')
            ->where('ez_language.code', $this->lang->lang())
            ->where('site_common_content.id_company', $this->auth->data('company'))
            ->select('file_description_image.file as image')
            ->join('ez_file as file_description_image', 'site_common_content_description.image = file_description_image.id', 'left', false);

        if ($params['area'])
            $this->db->where('site_common_content_description.area', $params['area']);

        if ($params['hierarchy'])
            $this->db->where('site_common_content_description.subarea', 'pagina');

        if ($params['slug'])
            $this->db->where('site_common_content.slug', $params['slug']);

        $query = $this->db->get();

        if ($query->num_rows()) {
            if ($params['slug']) {
                $data = $query->row();

                if ($data->enable_gallery === 'enabled')
                    $data->gallery = $this->getGalleryContent($data->id);
                if ($data->enable_videos === 'enabled')
                    $data->videos = $this->getVideosContent($data->id);
                return $data;
            }
            foreach ($query->result() as $key => $value) {

                if ($params['hierarchy']) {
                    $retorno[$key] = $value;

                    if ($value->enable_gallery === 'enabled')
                        $retorno[$key]->gallery = $this->getGalleryContent($value->id);
                    if ($value->enable_videos === 'enabled')
                        $retorno[$key]->videos = $this->getVideosContent($value->id);

                    $this->db->select('site_common_content.slug, site_common_content_description.*, site_common_content.id AS id, site_common_content_configuration.enable_gallery')
                        ->from('site_common_content')
                        ->join('site_common_content_configuration', 'site_common_content_configuration.id_common_content = site_common_content.id ', 'INNER')
                        ->join('site_common_content_description', 'site_common_content_description.id_common_content = site_common_content.id ', 'left')
                        ->join('ez_language', 'site_common_content_description.id_language = ez_language.id')
                        ->where('ez_language.code', $this->lang->lang());

                    if ($params['hierarchy'])
                        $this->db->where('site_common_content_description.subarea != ', 'pagina');

                    if ($params['area'])
                        $this->db->where('site_common_content_description.area', $params['area']);

                    $queryInterna = $this->db->get();
                    foreach ($queryInterna->result() as $k => $block) {

                        $retorno[$key]->sections[$k] = $block;
                        //GALERIA DOS CONTEUDOS INTERNOS
                        if ($retorno[$key]->sections[$k]->enable_gallery === 'enabled')
                            $retorno[$key]->sections[$k]->gallery = $this->getGalleryContent($block->id);
                        //GALERIA DOS CONTEUDOS INTERNOS
                        if ($retorno[$key]->sections[$k]->enable_videos === 'enabled')
                            $retorno[$key]->sections[$k]->videos = $this->getVideosContent($block->id);
                    }

                } else {
                    $chave = ($params['key'] == 0) ? $value->slug : $key;
                    $retorno[$chave] = $value;

                    if ($value->enable_gallery === 'enabled')
                        $retorno[$chave]->gallery = $this->getGalleryContent($value->id);

                    if ($value->enable_videos === 'enabled')
                        $retorno[$chave]->videos = $this->getVideosContent($value->id);

                }

                //GALERIA DOS CONTEUDOS
            }
        }

        return !empty($retorno) && ($params['hierarchy']) ? $retorno[0] : $retorno;
    }

}