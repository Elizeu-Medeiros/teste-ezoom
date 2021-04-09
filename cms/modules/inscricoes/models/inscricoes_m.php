<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * @package CMS
 * @subpackage  Inscrições
 * @category    Model
 * @author Thiago Macedo Medeiros <thiago@ezoom.com.br>
 * @date      2020
 * @copyright Copyright  (c) 2020, Ezoom
 */
class Inscricoes_m extends MY_Model
{
    public $table = 'site_subscription';
    public $table_subscription_student = 'site_subscription_student';
    public $table_unit_description = 'site_unit_description';
    public $primary_key = 'id';
    public $foreign_key = 'id_subscription';

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
        );
        $params = array_merge($options, $params);

        if ($params['count'])
            $this->db->select('COUNT(DISTINCT '.$this->table.'.id) AS count');
        else{
            $this->db->select($this->table.'.*')
                    ->select('DATE_FORMAT('.$this->table.'.created,"%d/%m/%Y") as date', false)
                    ->select('DATE_FORMAT('.$this->table.'.created,"%H:%i:%s") as hour', false);
        }

        if ($params['search']){
            if (isset($params['search']['title']) && $params['search']['title'] != '')
                $this->db->where($this->table_description.'.title LIKE "%'.$params['search']['title'].'%"');
        }

        $this->db->select($this->table_unit_description.".title as unit")
            ->join($this->table_unit_description, $this->table_unit_description.".id_unit = ".$this->table.".id_unit");

        $toReturn = parent::get($params);

        if(is_object($toReturn)) {
            $toReturn->students = $this->_get_students($toReturn->id, $this->current_lang);
        }

        if(is_array($toReturn)) {
            foreach ($toReturn as $key => $value) {
                $value->students = $this->_get_students($value->id, $this->current_lang);
            }
        }

        return $toReturn;
    }

    public function insert($data)
    {
        $this->db->trans_start();

        $insert = array(
            'id_company' => $this->auth->data('company'),
            'order_by'   => ($this->get(array('max' => TRUE)) + 1),
            'status'     => !empty($data['status']) ? 1 : 0,
            'id_unit'    => $data["id_unit"],
            'name'       => $data["name"],
            'phone'      => $data["phone"],
            'email'      => $data["email"]
        );
        $this->db->insert(
            $this->table,
            $insert
        );

        $id = $this->db->insert_id();
        if(isset($data['subscription']["name"])){
            for ($i=0;$i<count($data['subscription']["name"]);$i++) {
                $array = array(
                    $this->foreign_key  => $id,
                    "name"              => $data['subscription']["name"][$i],
                    "birth"             => implode("-", array_reverse(explode("/", $data['subscription']["birth"][$i]))),
                    "id_teaching_level" => $data['subscription']["level"][$i]
                );
                $this->db->insert($this->table_subscription_student, $array);
            }
        }
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function export($params = null)
    {
        $query = $this->db
            ->select('name AS Nome, email AS Email, phone AS Telefone, DATE_FORMAT(created, "%d/%m/%Y às %H:%i:%s") as Data', FALSE)
            ->from($this->table)
            ->where('deleted', null)
            ->where($this->table.'.id_company', $this->auth->data('company'));

        if(isset($params['where']))
            foreach ($params['where'] as $column => $value)
                $this->db->where($column, $value);

        $query = $this->db->get();

        $retorno = array(
            'fields_cnt' => $query->list_fields(),
            'result'     => $query->result()
        );

        return $retorno;
    }

    private function _get_students($id, $id_language = FALSE)
    {
        $this->db
            ->select(['*'])
            ->select('DATE_FORMAT('.$this->table_subscription_student.'.birth,"%d/%m/%Y") as birth', false)
            ->select('site_teaching_level_description.title as level')
            ->from($this->table_subscription_student)
            ->join('site_teaching_level_description', 'site_teaching_level_description.id_teaching_level = '.$this->table_subscription_student.'.id_teaching_level')
            ->where($this->table_subscription_student.'.id_subscription', $id);
        $query = $this->db->get();

        return $query->result();
    }
}