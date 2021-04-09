/*!
 * Classe Exporta_traducoes
 *
 * @author Rodrigo Danna [rodrigo.danna@ezoom.com.br]
 * @date   17-04-2020
 * Copyright (c) 2020 Ezoom Agency
 */
'use strict';

$('document').ready(function(){

    /**
     * Inicia propriedades do objeto
     * @author Rodrigo Danna [diogo@ezoom.com.br]
     * @date   17-04-2020
     */
    var Exporta_traducoes = function() {
        return this.__constructor();
    };

    /**
     * Extende Comum
     * @type {Comum}
     */
    Exporta_traducoes.prototype = Comum;
    Exporta_traducoes.prototype.constructor = Exporta_traducoes;

    /**
     * Construtor da classe
     * @author Rodrigo Danna [diogo@ezoom.com.br]
     * @date   17-04-2020
     * @return {Exporta_traducoes}
     */
    Exporta_traducoes.prototype.__constructor = function() {

        $('.check-table').on('click', function(){
            if ($(this).is(":checked")){
                $(this).closest('.panel').removeClass('disabled');
                $(this).closest('.panel').find('.panel-body .check-column').attr("disabled", false);
                $(this).closest('.panel').find('.panel-body .id-column').attr("disabled", false);
            }else{
                $(this).closest('.panel').addClass('disabled');
                $(this).closest('.panel').find('.panel-body .check-column').attr("disabled", true);
                $(this).closest('.panel').find('.panel-body .id-column').attr("disabled", true);
            }
        });

        return this;
    };

    window.Exporta_traducoes = new Exporta_traducoes();
    return Exporta_traducoes;

});
