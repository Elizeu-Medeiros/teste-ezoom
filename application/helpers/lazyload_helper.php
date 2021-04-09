<?php (defined('BASEPATH')) or exit('No direct script access allowed');

if (! function_exists('lazyload')) {
    /**
     * @author Ralf da Rocha [ralf@ezoom.com.br]
     * @date   2016-09-05
     * @param  array      $params
     * @return [string]
     */
    function lazyload($params = array())
    {
        $options = array(
            'src'       => FALSE,
            'view'      => FALSE,
            'slick'     => FALSE,
            'itemprop'  => FALSE,
            'tag'       => 'div',
            'viewport'  => true
        );
        $params = array_merge($options, $params);
        if (!$params['src'])
            return '';

        $UA =& load_class('User_agent', 'libraries');

        $view = $params['view'];
        $tag = $params['tag'];
        $src = $params['src'];
        $slick = $params['slick'];
        $itemprop = $params['itemprop'];
        $background = isset($params['data-background']) ? $params['data-background'] : FALSE;
        
        if (filter_var($src, FILTER_VALIDATE_URL)) {
            $query_string = parse_url($src, PHP_URL_QUERY);
            parse_str($query_string, $prm);
            if(isset($prm["w"]) && isset($prm["h"])){
                $size = array((int)$prm["w"], (int)$prm["h"]);
            }
        }
        if(!isset($size)){
            $size = getimagesize($src);
        }

        unset($params['src'], $params['view'], $params['tag'], $params['slick'], $params['itemprop']);

        $container = '<'.$tag;

        if ($UA->is_robot()){
            $img = '<img src="'.$src.'"'.(isset($params['alt']) ? ' alt="'.$params['alt'].'"' : '').($itemprop ? ' itemprop="'.$itemprop.'"' : '').' />';
            $view = $img . $view;
        } else {

            if (!$slick){
                if (!$background && isset($size) && $size && count($size) > 1){
                    $adjusted_height = ($size[1]/$size[0])*100;
                    $view = '<div class="preloader" style="padding-bottom:'.str_replace(',', '.', $adjusted_height).'%"></div>' . $view;
                } else {
                    $view = '<div class="preloader"></div>' . $view;
                }

                $params['data-src'] = $src;
                if (isset($params['alt']))
                    $params['data-alt'] = $params['alt'];
            }else{
                $img = '<div class="lazyload-slick"><img data-lazy="'.$src.'"'.(isset($params['alt']) ? ' alt="'.$params['alt'].'"' : '').' /><div class="loader"></div></div>';
                $view = $img . $view;
            }
        }
        unset($params['alt']);

        foreach ($params as $key => $value) {
            $container .= " " . $key . "='" . $value . "'";
        }

        $container .= '>'.($view ? $view : '');
        if ($itemprop && !$UA->is_robot()){
            $container .= '<meta itemprop="'.$itemprop.'" content="'.$src.'" />';
        }
        $container .= '</'.$tag.'>';

        return $container;
    }
}