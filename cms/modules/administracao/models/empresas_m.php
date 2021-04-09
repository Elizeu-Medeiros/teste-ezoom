<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Model
 *
 * @package ezoom
 * @subpackage associacoes
 * @category Model
 * @author Diogo Taparello
 * @copyright 2016 Ezoom
 */
class Empresas_m extends MY_Model
{
    public $table = 'ez_company';
    public $table_description = 'ez_company_description';
    public $table_language = 'ez_language';
    public $primary_key = 'id';
    public $foreign_key = 'id_company';
    public $directory;

    public $image_fields = array('image');

    public function __construct() {
        parent::__construct();
        $this->directory = dirname(FCPATH).((ENVIRONMENT == 'development') ? DS.'framework-ezoom-codeigniter' : '').DS.'userfiles'.DS.'empresas'.DS;
    }

    public function get($params = array() )
    {
        $options = array(
            'slug' => FALSE,
            'id' => FALSE,
            'reverse' => FALSE, //Ao invés de procurar por igual, procura por diferente do parametro
            'show_lang' => FALSE, //Envia code da lingua principal da empresa
        );
        $params = array_merge($options, $params);

        $this->db->select("*, x(lat_lng) AS lat, y(lat_lng) AS lng, district as suburb, address as street, complement as additional_info")
                 ->from($this->table);

        if( $params['show_lang'] )
            $this->db->select('code')
                     ->join($this->table_language, $this->table_language.'.id = '.$this->table.'.language_main');

        if( $params['id'] )
            $this->db->where($this->table.'.'.$this->primary_key, $params['id']);

        if( $params['slug'] )
            $this->db->where($this->table.'.slug', $params['slug']);

        if(isset($this->image_fields) && is_array($this->image_fields) && count($this->image_fields) > 0){
            foreach($this->image_fields as $keyImage => $image_field){
                $this->db->select('file_'.$keyImage.'.file as '.str_replace('id_', '', $image_field));
                $this->db->join($this->table_file.' as file_'.$keyImage, $this->table.'.'.$image_field.' = file_'.$keyImage.'.id', 'left', false);
            }
        }

        $query = $this->db->get();
        $data = $query->row();
        if (empty($data)) {
            $retorno = false;
        } else {
            $data->languages = array();

            $this->db->select('*')
                     ->from($this->table_description)
                     ->where($this->foreign_key, $data->id);
            $query = $this->db->soft_delete(false)->get();
            $result = $query->result();

            foreach ($result as $key => $value) {
                $data->languages[$value->id_language] = $value;
            }
            $retorno = $data;
        }

        return $retorno;
    }

    public function get_all($params = array() )
    {
        $options = array(
            'id' => FALSE,
            'slug' => FALSE,
            'reverse' => FALSE,
            'search' => FALSE,
            'start' => 0,
            'max' => FALSE,
            'total' => FALSE,
            'where' => FALSE,
            'children' => FALSE,
            'not-children' => FALSE,
        );
        $params = array_merge($options, $params);

        if($params['total'])
            $this->db->select('COUNT(*) AS count');
        else{
            $this->db->select("*");
            //if($this->auth->data('id') != 1)
            //$this->db->select('IF( ( '.$this->table.'.id IN ('.$this->auth->data('companies').') OR '.$this->table.'.id = '.$this->auth->data('id_company').'), 1, 0) as permission_asc', false);
            $this->db->select('IF( '.$this->table.'.id = '.$this->auth->data('id_company').', 1, 0) as permission_asc', false);
        }

        $this->db->from($this->table)
                 ->join($this->table_description, $this->table_description . '.' . $this->foreign_key . ' = ' . $this->table . '.' . $this->primary_key.' and id_language = (SELECT id_language FROM ez_company_description WHERE ez_company_description.id_company=ez_company.id AND id_language IN (1,'.$this->current_lang.') ORDER BY id_language DESC limit 1)', 'left')
                 ->order_by('status', 'DESC');

        if ($this->auth->data('admin') != 1){
            //$this->db->where('( '.$this->table.'.id IN ('.$this->auth->data('companies').') OR '.$this->table.'.id = '.$this->auth->data('id_company').')');
            $this->db->where($this->table.'.id = '.$this->auth->data('id_company'));
        }

        $condition = $params['reverse'] ? ' != ' : '';

        if( $params['id'] )
            $this->db->where($this->primary_key.$condition, $params['id']);

        if( $params['slug'] )
            $this->db->where('slug'.$condition, $params['slug']);

        if( $params['max'])
            $this->db->limit($params['max'], $params['start']);

        if ($params['search'])
            $this->db->where('('.
                $this->table.'.fantasy_name LIKE "%'.$params['search'].'%"  OR '.
                $this->table.'.company_name LIKE "%'.$params['search'].'%"'.
            ')');

        if ( $params['where'] !== FALSE ){
            if (is_array($params['where']))
                $this->db->where($params['where']);
            else
                $this->db->where($params['where'], FALSE, FALSE);
        }

        $query = $this->db->get();
        if( $params['total'] ){
            $retorno = $query->row('count');
        }else {
            $retorno = $query->result();
            foreach ($query->result() as $key => $value) {
                if( $params['children'] ){
                    $retorno[$key]->children = $this->get_all(array('children' => TRUE, 'where' => array('ez_company.status' => 1)));
                }
            }
        }
        return $retorno;

    }

    public function get_estados()
    {
        $this->db->select('id,state')
                 ->from('ez_state');

        $query = $this->db->get();

        return $query->result();
    }

    public function insert($data)
    {
        $image = (isset($data['file']['image']) && strlen($data['file']['image']) > 0) ? $data['file']['image']: null;
        $favicon = (isset($data['file']['favicon']) && strlen($data['file']['favicon']) > 0) ? $data['file']['favicon']: null;

        $this->db->trans_start();

        $slug = (isset($data['slug']) && $data['slug'] ? $data['slug'] : slug($data['fantasy_name'], $this->table) );

        if (isset($data['domain']) && $data['domain']){
            $domain = prep_url($data['domain']);
            $domain  = rtrim(str_replace('http://www.', 'http://', $domain),'/').'/';
        }

        if(isset($data['location']['lat']) && isset($data['location']['lng'])){
            $this->db->set('lat_lng',"GeomFromText('POINT(".$data['location']['lat'].' '.$data['location']['lng'].")')",false);
        }

        $this->db->set('fantasy_name', $data['fantasy_name'])
                 ->set('company_name', $data['company_name'])
                 ->set('slug', $slug)
                 ->set('language_main', $data['language_main'])
                 ->set('languages_site', (isset($data['languages_site']) ? implode(',', $data['languages_site']) : 1))
                 ->set('phone', $data['phone'])
                 ->set('sac', $data['sac'])
                 ->set('email', $data['email'])
                 ->set('domain', (isset($domain) ? $domain : null))
                 ->set('google_tag_manager', $data['google_tag_manager'])
                 ->set('image', $image)
                 ->set('favicon', $favicon)
                 ->set('id_country', (isset($data['location']['id_country']) && $data['location']['id_country'] != '' ? $data['location']['id_country'] : null) )
                 ->set('state', isset($data['location']['state']) ? $data['location']['state'] : null)
                 ->set('city', isset($data['location']['city']) ? $data['location']['city'] : null)
                 ->set('zipcode', isset($data['location']['zip_code']) ? $data['location']['zip_code'] : null)
                 ->set('district', isset($data['location']['suburb']) ? $data['location']['suburb'] : null)
                 ->set('address', isset($data['location']['street']) ? $data['location']['street'] : null)
                 ->set('number', isset($data['location']['number']) ? $data['location']['number'] : null)
                 ->set('complement', isset($data['location']['additional_info']) ? $data['location']['additional_info'] : null)
                 ->set('active_site', (isset($data['active_site']) ? '1' : '0') )
                 ->set('colors', isset($data['colors']) ? $data['colors'] : null )
                 ->set('css_file', isset($data['css_file']) ? $data['css_file'] : null)
                 ->set('status', (isset($data['status']) ? '1' : '0') );

        $this->db->insert($this->table);

        $id = $this->db->insert_id();
        foreach ($data['value'] as $lang => $values) {
            $array = array($this->foreign_key => $id, 'id_language' => $lang);
            $values = array_map(array($this, 'set_null'), $values);
            $array = array_merge($array, $values);
            $this->db->insert($this->table_description, $array);
        }

        // Duplica conteúdo comum da empresa principal
        $this->db->select('id_company')->from('site_common_content')->where('id_company', 1)->limit(1);
        $query = $this->db->get();
        $ultima = $query->result_array();
        if (!empty($ultima)) {
            $this->db->select('*')
                     ->from('site_common_content')
                     ->where('id_company', $ultima[0]['id_company']);
            $query = $this->db->get();
            $common_content = $query->result();
            if (!empty($common_content)) {
                foreach ($common_content as $item) {
                    $this->db->insert(
                        'site_common_content',
                        array(
                            'id_company' => $id,
                            'slug' => $item->slug,
                            'status' => $item->status
                        )
                    );
                    $id_common_content = $this->db->insert_id();

                    $this->db->select('*')
                             ->from('site_common_content_configuration')
                             ->where('id_common_content', $item->id);
                    $query = $this->db->get();
                    $configuration = $query->result();

                    if (!empty($configuration)) {
                        foreach ($configuration as $conf) {
                            $this->db->insert(
                                'site_common_content_configuration',
                                array(
                                    'id_common_content' => $id_common_content,
                                    'enable_title' => $conf->enable_title,
                                    'enable_subtitle' => $conf->enable_subtitle,
                                    'enable_area' => $conf->enable_area,
                                    'enable_subarea' => $conf->enable_subarea,
                                    'enable_slug' => $conf->enable_slug,
                                    'enable_text' => $conf->enable_text,
                                    'enable_image' => $conf->enable_image,
                                    'enable_archive' => $conf->enable_archive,
                                    'enable_link' => $conf->enable_link,
                                    'enable_status' => $conf->enable_status,
                                    'enable_gallery' => $conf->enable_gallery,
                                    'enable_edit' => $conf->enable_edit,
                                    'enable_delete' => $conf->enable_delete
                                )
                            );
                        }
                    }

                    $this->db->select('*')
                             ->from('site_common_content_description')
                             ->where('id_common_content', $item->id);
                    $query = $this->db->get();
                    $common_languages = $query->result();

                    if (!empty($common_languages)) {
                        foreach ($common_languages as $language) {

                            if (isset($language->image) && $language->image){
                                $explode = explode('.', $language->image);
                                $newName = md5(date('U'). uniqid(rand(), TRUE) . $language->image). '.'. strtolower(end($explode));
                                $originPath = FCPATH . '../userfiles/paginas/' . $language->image;

                                if (is_file($originPath)){
                                    copy($originPath, FCPATH . '../userfiles/paginas/' . $newName);
                                    $language->image = $newName;
                                } else
                                    $language->image = null;

                            }

                            if (isset($language->archive) && $language->archive){
                                $explode = explode('.', $language->archive);
                                $newName = md5(date('U') . uniqid(rand(), TRUE) . $language->archive) . '.' . strtolower(end($explode));
                                $originPath = FCPATH . '../userfiles/paginas/' . $language->archive;

                                if (is_file($originPath) && copy($originPath, FCPATH . '../userfiles/paginas/' . $newName))
                                    $language->archive = $newName;
                                else
                                    $language->archive = null;

                            }

                            $this->db->insert(
                                'site_common_content_description',
                                array(
                                    'id_language' => $language->id_language,
                                    'id_common_content' => $id_common_content,
                                    'title' => $language->title,
                                    'subtitle' => $language->subtitle,
                                    'area' => $language->area,
                                    'subarea' => $language->subarea,
                                    'text' => $language->text,
                                    'link' => $language->link,
                                    'link_label' => $language->link_label,
                                    'youtube_id' => $language->youtube_id,
                                    'image' => isset($language->image) ? $language->image : null,
                                    'archive' => isset($language->archive) ? $language->archive : null,
                                )
                            );
                        }
                    }

                    $this->db->select('*')
                             ->from('site_common_content_gallery')
                             ->where('id_common_content', $item->id);
                    $query = $this->db->get();
                    $common_photos = $query->result();

                    if (!empty($common_photos)) {
                        foreach ($common_photos as $photo) {

                            if (isset($photo->image) && $photo->image){

                                $newName = md5(date('U') . uniqid(rand(), TRUE) . $photo->image) . '.' . strtolower(end(explode('.', $photo->image)));
                                $originPath = FCPATH . '../userfiles/paginas/' . $photo->image;

                                if (is_file($originPath) && copy($originPath, FCPATH . '../userfiles/paginas/' . $newName)){

                                    $this->db->insert(
                                        'site_common_content_gallery',
                                        array(
                                            'id_common_content' => $id_common_content,
                                            'image' => $newName,
                                            'legend' => $photo->legend,
                                            'order_by' => $photo->order_by,
                                            'highlighted' => $photo->highlighted
                                        )
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }

        // Insere as permissões de visualização de conteúdo no menu de módulos
        $this->db->select('id')
                 ->from('ez_module');
        $query = $this->db->get();
        $modules = $query->result();

        $relation_module = array();
        foreach ($modules as $item) {
            $relation_module[] = array(
                'id_company' => $id,
                'id_module' => $item->id
            );
        }
        $this->db->insert_batch('ez_module_company',$relation_module);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function set_null($value)
    {
        return $value ? $value : null;
    }

    public function update($data, $id)
    {
        $sqls = array();

        if (isset($data['domain']) && $data['domain']){
            $domain = prep_url($data['domain']);
            $domain  = str_replace('http://www.', 'http://', $domain);
            $domain  = substr($domain, -1) != '/' ? $domain.'/' : $domain;
        }

        // Pega imagens atuais
        $current = $this->get( array('id' => $id ) );

        $favicon = (isset($data['file']['favicon']) && strlen($data['file']['favicon']) > 0) ? $data['file']['favicon']: null;
        $image = (isset($data['file']['image']) && strlen($data['file']['image']) > 0) ? $data['file']['image']: null;
        $slug = (isset($data['slug']) && $data['slug'] ? $data['slug'] : null);

        if(isset($data['location']['lat']) && isset($data['location']['lng']) && $data['location']['lat'] != '' && $data['location']['lng'] != '' )
            $this->db->set('lat_lng',"GeomFromText('POINT(".$data['location']['lat'].' '.$data['location']['lng'].")')",false);

        if ($favicon || isset($data['delete-file']['favicon']))
            $this->db->set('favicon',$favicon);

        if ($image || isset($data['delete-file']['image']))
            $this->db->set('image',$image);

        $this->db->set('fantasy_name', $data['fantasy_name'])
                 ->set('company_name', $data['company_name'])
                 ->set('slug', $slug )
                 ->set('language_main', $data['language_main'])
                 ->set('languages_site', (isset($data['languages_site']) ? implode(',', $data['languages_site']) : 1) )
                 ->set('phone', (isset($data['phone']) && $data['phone'] != '' ? $data['phone'] : null) )
                 ->set('sac', (isset($data['sac']) && $data['sac'] != '' ? $data['sac'] : null) )
                 ->set('email', (isset($data['email']) && $data['email'] != '' ? $data['email'] : null) )
                 ->set('domain', (isset($domain) ? $domain : null))
                 ->set('google_tag_manager', $data['google_tag_manager'])
                 ->set('id_country', (isset($data['location']['id_country']) && $data['location']['id_country'] != '' ? $data['location']['id_country'] : null) )
                 ->set('state', isset($data['location']['state']) ? $data['location']['state'] : null)
                 ->set('city', isset($data['location']['city']) ? $data['location']['city'] : null)
                 ->set('zipcode', isset($data['location']['zip_code']) ? $data['location']['zip_code'] : null)
                 ->set('district', isset($data['location']['suburb']) ? $data['location']['suburb'] : null)
                 ->set('address', isset($data['location']['street']) ? $data['location']['street'] : null)
                 ->set('status', (isset($data['status']) ? '1' : '0') )
                 ->set('active_site', (isset($data['active_site']) ? '1' : '0') )
                 ->set('number', isset($data['location']['number']) ? $data['location']['number'] : null)
                 ->set('complement', isset($data['location']['additional_info']) ? $data['location']['additional_info'] : null)
                 ->set('colors', isset($data['colors']) ? $data['colors'] : null )
                 ->set('css_file', isset($data['css_file']) ? $data['css_file'] : null);

        $this->db->trans_start();

        $this->db->where(array($this->primary_key => $id))->update($this->table);

        foreach ($data['value'] as $lang => $values) {
            $values = array_map(array($this, 'set_null'), $values);
            $values[$this->foreign_key] = $id;
            $values['id_language'] = $lang;
            $this->db->replace($this->table_description, $values);
        }

        if ($this->db->trans_status()) {
            if ((!is_null($image) || isset($data['delete-file']['image'])) && $current->image) {
                if ($image != $current->image)
                    delete_file($this->directory . $current->image);
            }
            if ((!is_null($favicon) || isset($data['delete-file']['favicon'])) && $current->favicon) {
                if ($favicon != $current->favicon)
                    delete_file($this->directory . $current->favicon);
            }
            //Deleta o css anterior
            if (!isset($data['css_file']) || (isset($data['css_file']) && $data['css_file'] != $current->css_file))
                delete_file($this->directory . $current->css_file);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function delete($id = null, $post = array())
    {
        $empresa = $this->get( array( 'id' => $id ) );
        // Deleta ambas as imagens
        delete_file($this->directory . $empresa->favicon);
        delete_file($this->directory . $empresa->image);

        $delete = $this->db->where('id', $id)->delete($this->table);

        return $delete;
    }

    public function delete_multiple($ids = array(), $post = array())
    {
        $delete = array();
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            /** exclui e armazena o resultado **/
            $delete[$id] = $this->delete($id);
        }

        return !array_search(false, $delete);
    }

    /**
     * Ativa/Inativa banners
     * @author Vanessa de Almeida França [vanessa@ezoom.com.br]
     * @date   2015-07-22
     * @param  [array]   $data
     * @return [boolean]
     */
    public function toggleStatus($data)
    {
        $this->db->where('id', $data['id']);
        $sql = $this->db->update($this->table, array($data['type'] => ($data['actived'] == 'true' ? 1 : 0)));

        return $sql;
    }
}
