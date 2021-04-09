<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Controller
 *
 * @package ezoom
 * @subpackage Importa_traducoes
 * @category Controller
 * @author Rodrigo Danna
 * @copyright 2010 Ezoom
 */
class Importa_traducoes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->template
             ->set('title', SITE_NAME.' - '.$this->current_module->name)
             ->set('breadcrumb_route', array($this->current_module->name))
             ->build('importa_traducoes');
    }

    public function import()
    {
        $this->load->model('exporta_traducoes/exporta_traducoes_m');
        $tables = $this->exporta_traducoes_m->getTables(true);

        $upload = $this->_do_upload();

        if($upload['status']){
            $upload_path = $upload['path'];
            $files = array();

            if(strpos($upload['filename'], '.zip') !== false){
                $extract = $this->_extract_zip($upload['filename'], $upload['path']);

                if($extract['status']){
                    $files = $extract['files'];
                }
            }else{
                $files = array(
                    $upload['filename']
                );
            }

            $this->load->library('ExcelReader');

            $log = array();
            foreach ($files as $key => $file) {
                $log[$key]['file'] = $file;

                if(strpos($file, '.xlsx') === false && strpos($file, '.xls') === false){
                    $log[$key]['status'] = false;
                    $log[$key]['message'] = T_('Formato inválido para importação');
                    continue;
                }

                $remove = [
                    '0',
                    '1',
                    '2',
                    '3',
                    '4',
                    '5',
                    '6',
                    '7',
                    '8',
                    '9',
                    '.xlsx',
                    '.xls'
                ];

                $table = str_replace($remove, '', $file);
                $log[$key]['table'] = $table;

                if( !in_array($table, $tables) ){
                    $log[$key]['status'] = false;
                    $log[$key]['message'] = T_('Tabela não existe');
                    continue;
                }

                $header = $this->excelreader->read_file($upload_path.$file, array(), false, 0);
                $result = $this->excelreader->read_file($upload_path.$file, $header[0], false, 1);

                $import = $this->importa_traducoes_m->import($table, $result);

                if($import){
                    $log[$key]['status'] = $import['status'];
                    $log[$key]['message'] = $import['message'];
                    $log[$key]['count'] = $import['count'];
                }else{
                    $log[$key]['status'] = false;
                    $log[$key]['message'] = T_('Ocorreu um erro na importação do arquivo');
                }
            }

            $return = array(
                'status' => true,
                'log' => $log
            );
        }else{
            $return = array(
                'status' => false,
                'error'  => $this->upload->display_errors()
            );
        }

        $this->output->set_content_type('application/json')
                     ->set_output(json_encode($return));
    }

    private function _do_upload()
    {
        $config['upload_path'] = 'userfiles/importa_traducoes/';
        $config['allowed_types'] = 'zip|xlsx|xls';

        $this->load->library('upload', $config);

        if($this->upload->do_upload('file'))
        {
            $file_info = $this->upload->data();
            return array(
                'status' => true,
                'filename' => str_replace('-', '_', $file_info['file_name']),
                'path' => $config['upload_path']
            );
        }
        else
        {
            return array(
                'status' => false,
                'error'  => $this->upload->display_errors()
            );
        }
    }

    function _extract_zip( $zipFile = '', $zipDir = '' )
    {
        $zip = new ZipArchive;

        if ($zip->open($zipDir.$zipFile) === TRUE) {
            $zip->extractTo($zipDir);

            $extractedFiles = array();
            for( $i = 0; $i < $zip->numFiles; $i++ ){
                $stat = $zip->statIndex( $i );
                $extractedFiles[] = $stat['name'];
            }

            $zip->close();

            unlink($zipDir.$zipFile);

            $return = array(
                'status' => true,
                'files'  => $extractedFiles
            );
        } else {
            $return = array(
                'status' => false,
                'error'  => T_('Erro ao extrair zip.')
            );
        }

        return $return;
    }
}