/*!
 * Classe Servicos
 *
 * @author Rodrigo Danna
 * @date   2020-05-06
 * Copyright (c) 2017 Ezoom Agency
 */
/* jslint devel: true, unparam: true, indent: 4 */
'use strict';

$('document').ready(function () {

    /**
     * Inicia propriedades do objeto
     */
    var Importa_traducoes = function () {
        return this.__constructor();
    };

    /**
     * Extende Comum
     * @type {Comum}
     */
    Importa_traducoes.prototype = Comum;
    Importa_traducoes.prototype.constructor = Importa_traducoes;

    /**
     * Construtor da classe
     * @return {Importa_traducoes}
     */
    Importa_traducoes.prototype.__constructor = function () {
        this.initUpload();
        return this;
    };

    Importa_traducoes.prototype.initUpload = function () {
        $('[data-upload]').each(function () {
            var container = $(this);

            container.find('.btn-upload').click(function () {
                container.find('input[type="file"]').trigger('click');
            });

            container.find('input[type="file"]').change(function () {
                if (this.files.length)
                    container.find('input[type="text"]').val(this.files[0].name);
            });

            container.find('button.btn-send').click(function () {
                var containsFile = !!container.find('input[type="file"]')[0].files.length;

                if (!containsFile) {
                    var obj = { layout: 'top', text: 'Informe o arquivo de importação', type: 'error' };
                    openNotification(obj);

                    return;
                }

                var formData = new FormData();
                formData.append('file', container.find('input[type="file"]')[0].files[0]);

                container.find('.btn-send').addClass('loading');

                $.ajax({
                    url: site_url + container.data('upload'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        container.find('.btn-send').removeClass('loading');

                        if (data.status) {
                            var obj = { layout: 'top', text: 'Importação realizada com sucesso!', type: 'success' };

                            var log = data.log;
                            for (let index = 0; index < log.length; index++) {
                                const el = log[index];

                                var row = '<tr class="' + (el.status == true ? 'success' : 'danger') + '">'+
                                        '<td>' + el.file + '</td>' +
                                        '<td>' + el.table + '</td>' +
                                        '<td>' + el.count + '</td>' +
                                        '<td>' + el.message + '</td>' +
                                    '</tr >';

                                $('#importResult tbody').append(row);
                            }
                        }
                        else {
                            var obj = { layout: 'top', text: data.error, type: 'error' };
                        }

                        openNotification(obj);
                    },
                    error: function(){
                        container.find('.btn-send').removeClass('loading');
                    }
                });
            });
        });
    };

    window.Importa_traducoes = new Importa_traducoes();
    return Importa_traducoes;

});
