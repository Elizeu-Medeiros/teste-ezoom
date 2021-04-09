/*!
 * Classe Empresas
 *
 * @author Diogo taparello [diogo@ezoom.com.br]
 * @date   2016-02-11
 * Copyright (c) 2014 Ezoom Agency
 */
/* jslint devel: true, unparam: true, indent: 4 */
'use strict';

$('document').ready(function(){

    /**
     * Inicia propriedades do objeto
     * @author Diogo taparello [diogo@ezoom.com.br]
     * @date   2016-02-11
     */
    var Empresas = function() {
        return this.__constructor();
    };

    /**
     * Extende Comum
     * @type {Comum}
     */
    Empresas.prototype = Comum;
    Empresas.prototype.constructor = Empresas;

    /**
     * Construtor da classe
     * @author Diogo taparello [diogo@ezoom.com.br]
     * @date   2016-02-11
     * @return {Empresas}
     */
    Empresas.prototype.__constructor = function() {
        this.upload({
            formData: {
                gallerypath: 'userfiles/empresas/',
                gallerytable: 'ez_company'
            }
        });
        this.masks();
        this.bootstrap();
        this.toggleStatus();
        $('.colorpicker2').colorpicker();
        $('.colorpicker2 input').on('keyup', function() {
            var val = $(this).val();
                val = (val.indexOf('#') == -1) ? '#'+val : val;
            $(this).parent().colorpicker('setValue', val);
        });
        return this;
    };

    Empresas.prototype.masks = function(){
        //MASCARA PHONE
        if ($('[class*="inputmask"]').length>0){
            $.extend($.inputmask.defaults, {
                'autounmask': true,
                'clearMaskOnLostFocus': true,
                'placeholder': "_"
            });
            $(".inputmask-cnpj").inputmask("mask", {"mask": "99.999.999/9999-99"});
        }
        $(".inputmask-cep").inputmask("mask", {"mask": "99999-999"});

        $(".inputmask-phone").inputmask("mask", {"mask": "(99) 9999-9999[9]" });
    };

    window.Empresas = new Empresas();
    return Empresas;
});
