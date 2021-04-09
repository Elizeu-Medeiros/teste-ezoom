/**
 * js
 *
 * @package Colégio Murialdo
 * @subpackage Main
 * @category js
 * @author Thiago Macedo Medeiros
 * @copyright 2020 Ezoom
 */

'use strict';

var app, hammer,
    $this,
    $body = $('body'),
    $window = $(window);

function Main() {
    this.init();
    this.removeHoverTouch();
};

Main.prototype.init = function() {
    this.fixedMenu();
    this.ajaxForm();
    this.dialog.init();

    if ($window.width() < 960)
        this.menuMobile();

    $('.lazyload').lazyload();

    if ($('.select2').length) {
        $('.select2').select2({
            dropdownCssClass: 'no-search',
            language: 'pt-BR'
        });
    }
};

Main.prototype.menuMobile = function() {
    $('.open-menu').click(function() {
        $body.toggleClass('menu-open');
    });

    $('span.nav-a').click(function(e) {
        e.stopImmediatePropagation();

        $('.drop').removeClass('active');
        $(this).find('.drop').addClass('active');
    });

    $('.common-button.boletos').click(function(e) {
        e.stopImmediatePropagation();

        // $('.submenu').removeClass('active');
        $(this).find('.submenu').toggleClass('active');
    });
};


Main.prototype.initBanner = function() {
    var $banner = $('#banner-images'),
        $images = $banner.find('.lazyload');

    $banner.slick({
        autoplay: true,
        speed: 1200,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: false,
        fade: true,
        arrows: true,
        dots: true,
        pauseOnHover: false,
        pauseOnFocus: false,
        cssEase: 'ease',
        autoplaySpeed: 6000,
        appendDots: $('#banner-dots'),
        appendArrows: $('#banner-arrows'),
        responsive: [
            {
                breakpoint: 960,
                settings: {
                    arrows: false
                }
            }
        ]
    }).on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        if ($images.eq(nextSlide).hasClass('loading'))
            $banner.slick('slickPause');

        $images.eq(nextSlide).lazyload('load');
    }).slick('slickPause');

    $images.lazyload({
        autoload: false,
        background: true,
        onLoad: function() {
            $banner.slick('slickPlay');
        }
    }).eq(0).lazyload('load');

    $('#btn-scroll').click(function() {
        $('body, html').animate({ scrollTop: $('#classes').offset().top - 100 }, 1000);
    });
};

Main.prototype.fixedMenu = function() {
    var $header = $('#header');

    $window.on('scroll', function(e) {
        if ($window.scrollTop() >= 210)
            $header.addClass('fixed');
        else
            $header.removeClass('fixed');
    });
};

Main.prototype.slickNews = function() {
    var slides;

    $('.news-items').each(function(i, el) {
        $this = $(el);
        slides = $this.data('slides') ? $this.data('slides') : 2;

        $this.slick({
            slidesToShow: slides,
            slidesToScroll: slides,
            infinite: true,
            fade: false,
            arrows: false,
            dots: true,
            pauseOnHover: false,
            pauseOnFocus: false,
            cssEase: 'ease',
            autoplaySpeed: 6000,
            appendDots: $this.parent().find('.common-dots'),
            responsive: [
                {
                    breakpoint: 960,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false
                    }
                }
            ]
        });
    });
};

Main.prototype.masks = function () {
    // $('.phone-mask').mask('(00) 0000.00009');
    $('.cpf-mask').mask('000.000.000-00');
    $('.cnpj-mask').mask('00.000.000/0000-00');
    $('.date-mask').mask('99/99/9999');
    $('.cep-mask').mask('99999-999');

    if ($('.phone-mask').length) {
        var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        };
        $('.phone-mask').mask(SPMaskBehavior, { onKeyPress: function (val, e, field, options) { field.mask(SPMaskBehavior.apply({}, arguments), options); } });
    }
};

Main.prototype.ajaxForm = function() {
    var $form_selector = $('.ajax-form');

    $form_selector.each(function() {
        var form = $(this);

        var SPMaskBehavior = function(val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        };

        form.find('input[type="tel"]').mask(SPMaskBehavior, { onKeyPress: function(val, e, field, options) { field.mask(SPMaskBehavior.apply({}, arguments), options); }});
        form.find('input[type="tel"]').mask(SPMaskBehavior, { onKeyPress: function(val, e, field, options) { field.mask(SPMaskBehavior.apply({}, arguments), options); }});

        
        form.validate({
            errorPlacement: function(e, ee) {
                return false;
            },
            submitHandler: function(form) {
                if ($(form).data('loading'))
                    return false;

                $(form).data('loading', true).addClass('sending');

                $.ajax({
                    url: $(form).attr('action'),
                    dataType: 'JSON',
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(response) {
                        $(form).data('loading', false).removeClass('sending');

                        if (response.status) {
                            if (!response.keep) {
                                $(form).find('input[type="text"], input[type="email"], input[type="tel"], input[type="number"], input[type="password"], select, textarea').val('');
                                $(form).find('input[type="checkbox"]').prop('checked', false)

                                if ($(form).find('select').length)
                                    $(form).find('select').select2("val", " ");
                            } else {
                                $(form).find('input[type="password"]').val('');
                            }

                            if(response.redirect) {
                                if (response.message) {
                                    swal({
                                        title: response.title,
                                        text: response.message,
                                        type: response.class
                                    }, function() {
                                        window.location = response.redirect;
                                    });
                                } else {
                                    window.location = response.redirect;
                                }
                            } else {
                                if (response.message) {
                                    swal(response.title, response.message, response.class);
                                }
                            }
                        } else {
                            if (response.message) {
                                swal(response.title, response.message, response.class);
                            }
                        }
                    },
                    error: function(response) {
                        $(form).data('loading', false).removeClass('sending');
                        swal(i18n['ajax_error_title'], i18n['ajax_error_message'], 'warning');
                    }
                });
            }
        });
    });

    $('.select2').on('change', function() {
        $(this).removeClass('error');
    })
};

Main.prototype.dialog = {
    initialized: false,
    init: function(){
        if (this.initialized)
            return;
        this.initialized = true;
        $('body').append('\
            <div id="dialog-overflow"></div>\
            <div id="dialog-window">\
                <div class="dialog-message">\
                    <button type="button" id="close-dialog"></button>\
                    <div class="icon-message">\
                        <div class="alert">\
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 1l-12 22h24l-12-22zm-1 8h2v7h-2v-7zm1 11.25c-.69 0-1.25-.56-1.25-1.25s.56-1.25 1.25-1.25 1.25.56 1.25 1.25-.56 1.25-1.25 1.25z"/></svg>\
                        </div>\
                        <div class="success">\
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 11.522l1.578-1.626 7.734 4.619 13.335-12.526 1.353 1.354-14 18.646z"/></svg>\
                        </div>\
                        <div class="error">\
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 3.752l-4.423-3.752-7.771 9.039-7.647-9.008-4.159 4.278c2.285 2.885 5.284 5.903 8.362 8.708l-8.165 9.447 1.343 1.487c1.978-1.335 5.981-4.373 10.205-7.958 4.304 3.67 8.306 6.663 10.229 8.006l1.449-1.278-8.254-9.724c3.287-2.973 6.584-6.354 8.831-9.245z"/></svg>\
                        </div>\
                    </div>\
                    <p class="title"></p>\
                    <div class="message"></div>\
                </div>\
                <div class="dialog-buttons">\
                    <button type="button" id="cancel-dialog"><span>Cancelar</span></button>\
                    <button type="button" id="confirm-dialog"><span></span></button>\
                </div>\
            </div>\
        ');
    },
    open: function(options){

        var defaults = {
            class: 'success',
            button: 'Fechar',
            buttonCancel: 'Cancelar',
            title: '',
            message: '',
            redirect: false,
            cancel: false,
            callback: function(){},
            closeOnClick: true,
            autoClose: 0,
        }, data =  $.extend( {}, defaults, options );
        var $dialog = $('#dialog-window');
        $dialog.removeClass('error success alert').addClass(data.class).find('.message').html(data.message);
        $dialog.find('.title').html(data.title);
        $dialog.find('#confirm-dialog').on('click', $.proxy(function(){
            if (typeof data.callback == 'function') {
                data.callback.apply();
            }
            if (data.closeOnClick)
                this.close(data.redirect);
        }, this)).find('span').html(data.button);
        if (data.autoClose > 0){
            this.timer = setTimeout($.proxy(function(){
                this.close(data.redirect);
            }, this), data.autoClose);
        }
        // Open Dialog
        $('body').addClass('dialog-open');
        // Close Events
        $('#close-dialog, #cancel-dialog').on('click', $.proxy(function(){
            this.close(data.redirect);
        }, this));
        $('#dialog-overflow').on('click', $.proxy(function(){
            if ($('body').hasClass('dialog-open')){
                this.close(data.redirect);
            }
        }, this));
        setTimeout(function() {
            $dialog.find('#confirm-dialog').focus();
        }, 50);
        // Cancel button
        if (data.cancel){
            $dialog.addClass('with-cancel').find('#cancel-dialog').show().find('span').html(data.buttonCancel);
        }
        else
            $dialog.removeClass('with-cancel').find('#cancel-dialog').hide();
    },
    close: function(redirect){
        clearTimeout(this.timer);
        $('#dialog-window').find('#confirm-dialog').off('click');
        // Close Dialog
        $('body').removeClass('dialog-open');
        if (redirect){
            window.location = redirect;
        }
        $('#close-dialog, #cancel-dialog').off('click');
        $('#dialog-overflow').off('click');
    }
};

//=====================================
//  Função que troca :hover por :active na regra de css quando tiver touch
//  Evita o bug de quando clicar, fazer o hover e nao abrir o link nos mobiles
//=====================================
Main.prototype.removeHoverTouch = function() {
    function hasTouch() {
        return 'ontouchstart' in document.documentElement
            || navigator.maxTouchPoints > 0
            || navigator.msMaxTouchPoints > 0;
    }

    if (hasTouch()) {
        try { // exceção para navegadores que não suportam DOM StyleSheets
            for (var si in document.styleSheets) {
                var styleSheet = document.styleSheets[si];
                if (!styleSheet.rules) continue;

                for (var ri = styleSheet.rules.length - 1; ri >= 0; ri--) {
                    if (!styleSheet.rules[ri].selectorText) continue;

                    if (styleSheet.rules[ri].selectorText.match(':hover')) {
                        styleSheet.rules[ri].selectorText = styleSheet.rules[ri].selectorText.replace(/\:hover/g, ':active');
                    }
                }
            }
        } catch (ex) { }
    }
};

$(document).ready(function() {
    app = new Main();
});