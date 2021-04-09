<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Model
 *
 * @package ezoom
 * @subpackage Diferenciais
 * @category Model
 */
class Diferenciais_m extends MY_Model
{
    public $table = 'site_differential';
    public $table_description = 'site_differential_description';
    public $table_unit = 'site_differential_unit';
    public $primary_key = 'id';
    public $foreign_key = 'id_differential';

    public $image_fields = array('id_image');

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
            'where'     => FALSE, // Array especifico de where
            'id_unit'   => FALSE,
        );
        $params = array_merge($options, $params);

        if ($params['search']){
            if (isset($params['search']['title']) && $params['search']['title'] != '')
                $this->db->where($this->table_description.'.title LIKE "%'.$params['search']['title'].'%"');
        }

        if($params["id_unit"]){
            $this->db->join($this->table_unit, $this->table_unit.'.'.$this->foreign_key.' = '.$this->table.'.'.$this->primary_key)
                ->where($this->table_unit.'.id_unit', $params["id_unit"]);
        }

        $toReturn = parent::get($params);

        if(is_object($toReturn)) {
            $toReturn->units = $this->_get_units($toReturn->id, $this->current_lang);
        }

        if(is_array($toReturn)) {
            foreach ($toReturn as $key => $value) {
                $value->units = $this->_get_units($value->id, $this->current_lang);
            }
        }

        return $toReturn;
    }

    public function insert($data)
    {
        $this->db->trans_start();

        $insert = array(
            'id_company' => $this->auth->data('company'),
            'order_by' => ($this->get(array('max' => TRUE)) + 1),
            'status' => !empty($data['status']) ? 1 : 0
        );
        // Imagens
        $this->insert_single_file($data, $insert);

        $this->db->insert(
            $this->table,
            $insert
        );

        $id = $this->db->insert_id();
        foreach ($data['value'] as $lang => $values) {
            $array = array($this->foreign_key => $id, 'id_language' => $lang);
            $array = array_merge($array, $values);
            $array = array_map(array($this,'check_null'), $array);
            $this->db->insert($this->table_description, $array);
        }

        if(isset($data["unit"]))
            foreach($data["unit"] as $id_unit){
                $this->db->insert($this->table_unit, array(
                    "id_differential"  => $id,
                    "id_unit" => $id_unit
                ));
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

        $update['status'] = !empty($data['status']) ? 1 : 0;
        $update['updated'] = date('Y-m-d H:i:s');

        $this->db->trans_start();

        if (!empty($update)) {
            $this->db->where(array($this->primary_key => $id))
                               ->update($this->table, $update);
        }
        foreach ($data['value'] as $lang => $values) {
            $values = array_map(array($this,'check_null'), $values);
            $values[$this->foreign_key] = $id;
            $values['id_language'] = $lang;
            $this->db->replace($this->table_description, $values);
        }

        $this->db->where("id_differential", $id)->delete($this->table_unit);
        if(isset($data["unit"]))
            foreach($data["unit"] as $id_unit){
                $this->db->insert($this->table_unit, array(
                    "id_differential"  => $id,
                    "id_unit" => $id_unit
                ));
            }

        $this->db->trans_complete();

        // Confere se possui imagens cadastradas e deleta caso tudo ocorreu certo
        $this->delete_single_file($delete_images);

        return $this->db->trans_status();
    }

    private function _get_units($id, $id_language = FALSE)
    {
        $this->db
            ->select(['*'])
            ->from($this->table_unit)
            ->join('site_unit_description', 'site_unit_description.id_unit = '.$this->table_unit.'.id_unit')
            ->where($this->table_unit.'.id_differential', $id);
        if ($id_language)
            $this->db->where('site_unit_description.id_language', $id_language);
        $query = $this->db->get();

        return $query->result();
    }
}
