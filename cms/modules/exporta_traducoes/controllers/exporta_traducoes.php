<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Controller
 *
 * @package ezoom
 * @subpackage Exporta_traducoes
 * @category Controller
 * @author Rodrigo Danna
 * @copyright 2010 Ezoom
 */
class Exporta_traducoes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $tables = $this->exporta_traducoes_m->getTables();

        $this->template
             ->set('title', SITE_NAME.' - '.$this->current_module->name)
             ->set('breadcrumb_route', array($this->current_module->name))
             ->set('tables', $tables)
             ->build('exporta_traducoes');
    }

    public function export()
    {
        ini_set('default_charset','UTF-8');
        header('Content-Type: text/html; charset=utf-8');

        $data = $this->input->post();
        $clearTags = (isset($data['config']['cleartags']) && $data['config']['cleartags']) ? true : false;
        $langs = isset($data['config']['langs']) ? $data['config']['langs'] : false;

        if(!isset($data['tables']) || empty($data['tables'])){
            $return = array(
                'status' => false,
                'message' => T_('Nenhuma tabela selecionada!'),
                'classe' => 'error'
            );
        }else{
            $out = array();

            foreach ($data['tables'] as $table_key => $table) {

                //linguagens para tradução
                if($langs && !empty($langs)){
                    $new_columns = array();
                    $select_columns = array();

                    foreach ($table['columns'] as $column) {

                        if(strpos($column, 'id_') === false){
                            $new_columns[] = $column;
                            $select_columns[] = $column;

                            foreach ($langs as $lang) {
                                $new_columns[] = $column.'-'.$lang;
                                $select_columns[] = "'' as '".$column.'-'.$lang."'";
                            }
                        }else{
                            $new_columns[] = $column;
                            $select_columns[] = $column;
                        }
                    }

                    $table['columns'] = $new_columns;
                }else{
                    $select_columns = $table['columns'];
                }

                //Busca dados
                $select_columns = implode(',', $select_columns);

                $result = $this->db->select($select_columns, false)
                         ->from($table['table_name'])
                         ->where('id_language', '1')
                         ->get()
                         ->result();

                //Inicia var schema
                $schema_insert = '';

                //Monta Cabeçalho
                foreach ($table['columns'] as $value) {
                    $list = '"' . str_replace(
                        '"',
                        "\\" . '"',
                        stripslashes($value)
                    ) . '"';
                    $schema_insert .= $list;
                    $schema_insert .= ";";
                }

                $out[$table_key] = "\xEF\xBB\xBF".trim(substr($schema_insert, 0, -1));
                $out[$table_key] .= "\n";

                //Monta resultado
                $totalFields = count($table['columns']);
                foreach ($result as $key => $row) {
                    $schema_insert = '';
                    $i = 0;

                    foreach ($table['columns'] as $k => $value) {
                        $rowValue = '';

                        if(isset($row->{$value}) && $row->{$value} != '')
                            $rowValue = $row->{$value};

                        if(!empty($rowValue))
                        {
                            if ('"' == '')
                                $schema_insert .= html_entity_decode($rowValue);
                            else
                            {
                                $schema_insert .= '"' .
                                str_replace('"', "\\" . '"', html_entity_decode($rowValue)) . '"';
                            }
                        }
                        else
                        {
                            $schema_insert .= '';
                        }

                        if ($i < $totalFields - 1)
                            $schema_insert .= ";";

                        $i++;
                    }

                    //tratativas
                    // $schema_insert = mb_convert_encoding($schema_insert, 'UTF-16LE', 'UTF-8');
                    $schema_insert = chr(239) . chr(187) . chr(191) . $schema_insert;

                    if($clearTags){
                        $schema_insert = strip_tags($schema_insert);
                    }

                    $out[$table_key] .= $schema_insert;
                    $out[$table_key] .= "\n";
                }
            }

            //Configs zip
            $path = dirname(FCPATH). DS . 'userfiles' . DS . 'exporta-traducoes' . DS;
            $zipname = 'traducoes'.'_'.date('d-m-Y').'.zip';
            $full_path = $path.$zipname;

            //Deleta caso ja exista
            unlink($full_path);

            //Inicia zip
            $zip = new ZipArchive;
            $zip->open($full_path, ZipArchive::CREATE);

            // loop para criar todos os csv
            foreach ($out as $key => $out_table) {
                $zip->addFromString($data['tables'][$key]['table_name'].'.csv', $out_table);
            }

            //Fecha zip
            $zip->close();

            //Faz download
            $download_path = base_url('../userfiles/exporta-traducoes/'.$zipname);

            $return = array(
                'status' => true,
                'message' => T_('Arquivo gerado com sucesso!'),
                'classe' => 'success',
                'download' => $download_path
            );
        }

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($return));
    }
}