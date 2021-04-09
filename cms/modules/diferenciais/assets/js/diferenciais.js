/*!
 * Classe Diferenciais
 *
 * Copyright (c) 2014 Ezoom Agency
 */
/* jslint devel: true, unparam: true, indent: 4 */
'use strict';

$('document').ready(function(){

    /**
     * Inicia propriedades do objeto
     */
    var Diferenciais = function() {
        return this.__constructor();
    };

    /**
     * Extende Comum
     * @type {Comum}
     */
    Diferenciais.prototype = Comum;
    Diferenciais.prototype.constructor = Diferenciais;

    /**
     * Construtor da classe
     * @author Diogo taparello [diogo@ezoom.com.br]
     * @date   2015-03-04
     * @return {Diferenciais}
     */
    Diferenciais.prototype.__constructor = function() {
        this.upload();
        this.sortable();
        this.bootstrap();
        this.toggleStatus();
        this.deleteRegisters('image', '');
        this.colorPicker();

        $(".inputmask-phone").inputmask("mask", {"mask": "(99) 9999-9999[9]" });
        
        return this;
    };

    window.Diferenciais = new Diferenciais();
    return Diferenciais;

});
