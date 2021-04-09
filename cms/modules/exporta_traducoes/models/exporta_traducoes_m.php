<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Model
 *
 * @package ezoom
 * @subpackage Exporta_traducoes
 * @category Model
 * @author Michael Cruz
 * @copyright 2016 Ezoom
 */
class Exporta_traducoes_m extends MY_Model
{
    private $ignore_tables = array(
        'ez_continent_description',
        'ez_country_description',
        'ez_route_description',
        'ez_module_description'
    );

    private $ignore_columns = array(
        'id_language'
    );

    public function getTables($only_name = false)
    {
        //Pega todas tabelas
        $tables = $this->db->select('table_name')
                ->from('information_schema.tables')
                ->where('table_schema', $this->db->database)
                ->like('table_name', '_description')
                ->where_not_in('table_name', $this->ignore_tables)
                ->get()
                ->result();

        $toReturn = array();
        foreach ($tables as $key => $table) {
            if($only_name){
                $toReturn[] = $table->table_name;
            } else {
                $toReturn[$key]['table_name'] = $table->table_name;

                //Pega todas colunas da tabela
                $columns = $this->db->select('column_name')
                        ->from('information_schema.columns')
                        ->where('table_schema', $this->db->database)
                        ->where('TABLE_NAME', $table->table_name)
                        ->where_not_in('column_name', $this->ignore_columns)
                        ->get()
                        ->result();

                foreach ($columns as $key2 => $column) {
                    $toReturn[$key]['table_columns'][$key2] = $column->column_name;
                }
            }
        }

        return $toReturn;
    }
}
