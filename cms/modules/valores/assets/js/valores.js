/*!
 * Classe Valores
 *
 * Copyright (c) 2014 Ezoom Agency
 */
/* jslint devel: true, unparam: true, indent: 4 */
'use strict';

$('document').ready(function(){

    /**
     * Inicia propriedades do objeto
     */
    var Valores = function() {
        return this.__constructor();
    };

    /**
     * Extende Comum
     * @type {Comum}
     */
    Valores.prototype = Comum;
    Valores.prototype.constructor = Valores;

    /**
     * Construtor da classe
     * @author Diogo taparello [diogo@ezoom.com.br]
     * @date   2015-03-04
     * @return {Valores}
     */
    Valores.prototype.__constructor = function() {
        this.upload();
        this.sortable();
        this.bootstrap();
        this.toggleStatus();
        this.deleteRegisters('image', '');
        this.colorPicker();

        $(".inputmask-phone").inputmask("mask", {"mask": "(99) 9999-9999[9]" });
        
        return this;
    };

    window.Valores = new Valores();
    return Valores;

});
