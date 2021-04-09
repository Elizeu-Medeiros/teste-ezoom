<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Model
 *
 * @package ezoom
 * @subpackage Unidades
 * @category Model
 */
class Unidades_m extends MY_Model
{
    public $table = 'site_unit';
    public $table_description = 'site_unit_description';
    public $table_gallery = 'site_unit_gallery';
    public $primary_key = 'id';
    public $foreign_key = 'id_unit';

    public $image_fields = array('id_image', 'id_landing', 'id_landing_mobile');

    public function get($params = array())
    {
        $options = array(
            'search'    => FALSE,
            'offset'    => FALSE, // A partir de qual row retornar
            'limit'     => FALSE, // Quantidade de rows a retornar
            'order_by'  => FALSE, // Ordenação das colunas
            'count'     => FALSE, // TRUE para trazer apenas a contagem / FALSE para trazer os resultados
            'max'       => FALSE,
            'id'        => FALSE, // Trazer apenas um registro específico pelo id
            'where'     => FALSE, // Array especifico de where,
            'slug'      => FALSE, // Array especifico de where
        );
        $params = array_merge($options, $params);

        if ($params['search']){
            if (isset($params['search']['title']) && $params['search']['title'] != '')
                $this->db->where($this->table_description.'.title LIKE "%'.$params['search']['title'].'%"');
        }
        if ($params['slug']){
            $this->db->where($this->table_description.'.slug', $params['slug']);
        }

        $this->db->select("
            $this->table.*,
            ez_city.id AS id_city,
            ez_city.id_state AS id_state,
            x(lat_lng) AS lat, y(lat_lng) AS lng
            ")
            ->join('ez_city', 'ez_city.name = '.$this->table.'.city', 'LEFT');
        $toReturn = parent::get($params);
        return $toReturn;
    }

    public function insert($data)
    {
        $this->db->trans_start();

        $insert = array(
            'id_company' => $this->auth->data('company'),
            'order_by' => ($this->get(array('max' => TRUE)) + 1),
            'status' => !empty($data['status']) ? 1 : 0,
            'address'          => isset($data['location']['street']) ? $data['location']['street'] : NULL,
            'number'           => isset($data['location']['number']) ? $data['location']['number'] : NULL,
            'complement'       => isset($data['location']['additional_info']) ? $data['location']['additional_info'] : NULL,
            'district'         => isset($data['location']['suburb']) ? $data['location']['suburb'] : NULL,
            'city'             => isset($data['location']['city']) ? $data['location']['city'] : NULL,
            'state'            => isset($data['location']['state']) ? $data['location']['state'] : NULL,
            'id_country'       => isset($data['location']['id_country']) && $data['location']['id_country']? $data['location']['id_country'] : NULL,
            'zipcode'          => isset($data['location']['zip_code']) ? $data['location']['zip_code'] : NULL,
        );
        // Imagens
        $this->insert_single_file($data, $insert);

        $this->db->insert(
            $this->table,
            $insert
        );

        $id = $this->db->insert_id();

        if (isset($data['gallery']) && !empty($data['gallery']))
            $this->insert_gallery_images($data['gallery'], $id);

        foreach ($data['value'] as $lang => $values) {
            $array = array(
                $this->foreign_key => $id,
                'id_language' => $lang,
                'slug' => slug($values['title'], $this->table_description)
            );
            $array = array_merge($array, $values);
            $array = array_map(array($this,'check_null'), $array);
            $this->db->insert($this->table_description, $array);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function update($id, $data)
    {
        $sqls = array();
        $update = array();
        $delete_images = array();

        // Pega dados atuais
        $current = $this->get( array('id' => $id));

        $this->update_single_file($data, $update, $delete_images, $current);

        $update['status']     = !empty($data['status']) ? 1 : 0;
        $update['updated']    = date('Y-m-d H:i:s');
        $update['address']    = isset($data['location']['street']) ? $data['location']['street'] : NULL;
        $update['number']     = isset($data['location']['number']) ? $data['location']['number'] : NULL;
        $update['complement'] = isset($data['location']['additional_info']) ? $data['location']['additional_info'] : NULL;
        $update['district']   = isset($data['location']['suburb']) ? $data['location']['suburb'] : NULL;
        $update['city']       = isset($data['location']['city']) ? $data['location']['city'] : NULL;
        $update['state']      = isset($data['location']['state']) ? $data['location']['state'] : NULL;
        $update['id_country'] = isset($data['location']['id_country']) && $data['location']['id_country'] ? $data['location']['id_country'] : NULL;
        $update['zipcode']    = isset($data['location']['zip_code']) ? $data['location']['zip_code'] : NULL;

        $this->db->trans_start();

        if (!empty($update)) {
            $this->db->where(array($this->primary_key => $id))
                               ->update($this->table, $update);
        }
        foreach ($data['value'] as $lang => $values) {
            $values = array_map(array($this,'check_null'), $values);
            $values['slug'] = slug($values['title'], $this->table_description, array($this->foreign_key.' !=' => $id));
            $values[$this->foreign_key] = $id;
            $values['id_language'] = $lang;
            $this->db->replace($this->table_description, $values);
        }

        if(isset($data['gallery']) && !empty($data['gallery']))
            $this->insert_gallery_images($data['gallery'], $id);
        $this->update_gallery_images(
            (isset($data['oldImagesimages'])) ? $data['oldImagesimages'] : NULL
        );

        $this->db->trans_complete();

        // Confere se possui imagens cadastradas e deleta caso tudo ocorreu certo
        $this->delete_single_file($delete_images);

        return $this->db->trans_status();
    }

}
