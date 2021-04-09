/*!
 * Classe Unidades
 *
 * Copyright (c) 2014 Ezoom Agency
 */
/* jslint devel: true, unparam: true, indent: 4 */
'use strict';

$('document').ready(function(){

    /**
     * Inicia propriedades do objeto
     */
    var Unidades = function() {
        return this.__constructor();
    };

    /**
     * Extende Comum
     * @type {Comum}
     */
    Unidades.prototype = Comum;
    Unidades.prototype.constructor = Unidades;

    /**
     * Construtor da classe
     * @author Diogo taparello [diogo@ezoom.com.br]
     * @date   2015-03-04
     * @return {Unidades}
     */
    Unidades.prototype.__constructor = function() {
        this.upload();
        this.sortable();
        this.bootstrap();
        this.toggleStatus();
        this.deleteRegisters('image', '');
        this.colorPicker();

        return this;
    };

    window.Unidades = new Unidades();
    return Unidades;

});
