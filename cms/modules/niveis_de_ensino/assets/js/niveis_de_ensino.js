/*!
 * Classe Niveis_de_ensino
 *
 * Copyright (c) 2014 Ezoom Agency
 */
/* jslint devel: true, unparam: true, indent: 4 */
'use strict';

$('document').ready(function(){

    /**
     * Inicia propriedades do objeto
     */
    var Niveis_de_ensino = function() {
        return this.__constructor();
    };

    /**
     * Extende Comum
     * @type {Comum}
     */
    Niveis_de_ensino.prototype = Comum;
    Niveis_de_ensino.prototype.constructor = Niveis_de_ensino;

    /**
     * Construtor da classe
     * @author Diogo taparello [diogo@ezoom.com.br]
     * @date   2015-03-04
     * @return {Niveis_de_ensino}
     */
    Niveis_de_ensino.prototype.__constructor = function() {
        this.upload();
        this.sortable();
        this.bootstrap();
        this.toggleStatus();
        this.deleteRegisters('image', '');
        this.colorPicker();

        $(".inputmask-phone").inputmask("mask", {"mask": "(99) 9999-9999[9]" });
        
        return this;
    };

    window.Niveis_de_ensino = new Niveis_de_ensino();
    return Niveis_de_ensino;

});
