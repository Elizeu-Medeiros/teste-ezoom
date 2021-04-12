/**
 * js
 *
 * @package Colégio Murialdo
 * @subpackage Unidade
 * @category js
 * @author Thiago Macedo Medeiros
 * @copyright 2020 Ezoom
 */

'use strict';

function Unidade() {
    this.init();
};
var cloneTemplate;
Unidade.prototype.init = function () {
    app.initBanner();
    app.slickNews();
    app.masks();

    this.slickGallery('.carrousel.slide-images');
    this.slickGallery('.carrousel.slide-valores', 5);
    this.slickGallery('.carrousel.slide-depoimentos', 3);
    this.slickGallery('.carrousel.slide-diferenciais', 3);
    this.initMenu();
    this.modal();
    this.mfp('.carrousel');
    this.scrollFade();

    if ($window.width() < 960)
        this.menuMobile();

    cloneTemplate = $('.template').clone();
    $(cloneTemplate).removeClass('template');
    $(cloneTemplate).find('input,select').val('');
    $(cloneTemplate).find('select').removeClass("select2");
    $(cloneTemplate).find('.select2').remove();
    $(cloneTemplate).css({ display: 'none' });
    cloneTemplate = $(cloneTemplate)[0].outerHTML;

    $('#form-subscribe').on('click', '.btn-more', function (e) {
        //  console.log('add template', $(cloneTemplate).html());
        $(this).parent().parent().append('<div class="field field-normal remove"><a class="btn-remove"><i class="fas fa-minus"></i></a></div>');
 
        //  $(this).remove();
        let el = $(cloneTemplate);
        el.find('select').addClass('select2').select2({ 'width': '100%' });
        el.insertBefore('.field-submit');
        app.masks();
        el.fadeIn();
    });

    $('#form-subscribe').on('click', '.remove', function (e) {
        //  $(this).parent().fadeOut('fast', function(){
        $(this).parent().remove();
        //  });
    });

    $('[name*="unit"]').on('change', function () {
        var value = $(this).val();
        var level_select = $('.select-level');

        if (!value || value == undefined) {
            return false;
        }

        $.ajax({
            url: site_url + 'unidade/get_levels',
            dataType: 'JSON',
            type: 'POST',
            data: { id_unit: value },
            success: function (response) {
                if (response.status) {
                    var levels = response.levels;
                    var options = '';

                    console.log(levels);
                    for (var i = 0; i < levels.length; i++) {
                        options += '<option value="' + levels[i].id + '">' + levels[i].title + '</option>';
                    }

                    level_select.find('option:not(:selected)').each(function () {
                        $(this).remove();
                    });
                    level_select.append(options);
                    level_select.removeAttr('disabled');
                    level_select.select2("destroy");
                    level_select.select2({
                        'width': '100%'
                    });
                }
            }
        });
    }).change();
};

Unidade.prototype.initMenu = function () {
    $(document).on('click', '.menu-target', function () {
        let body = $("html, body");
        body.stop().animate({ scrollTop: $('.' + $(this).data('target')).offset().top - 200 }, 500, 'swing');
    })
};

Unidade.prototype.menuMobile = function () {
    $('.open-menu').click(function () {
        if ($('body').hasClass('menu-open') === false) {
            $('body').removeClass('menu-open');
            console.log('aaaa');
        } else {
            $('body').addClass('menu-open');
            console.log('bbbb');
        }
    });

    $('a.nav-a').click(function (e) {

        $('body').removeClass('menu-open');
    });

};

Unidade.prototype.mfp = function (selector) {
    parent = $(selector);
    var defaults = {
        delegate: '.slick-slide:not(.slick-cloned) a',
        type: 'image',
        iframe: {
            patterns: {
                youtube: {
                    index: 'youtube.com',
                    id: 'v=',
                    src: '//www.youtube.com/embed/%id%?autoplay=0'
                }
            }
        },
        tClose: '',
        mainClass: 'mfp-fade',
        removalDelay: 300,
        gallery: {
            enabled: true,
            tPrev: '',
            tNext: '',
            tCounter: '<span class="mfp-counter">%curr% / %total%</span>'
        }
    }


    console.log(parent);
    parent.each(function () {
        console.log(this, parent);
        $(this).magnificPopup(defaults);
    });
}

Unidade.prototype.modal = function () {
    var self = this;

    $('.btn-more.open-modal').click(function (e) {
        e.preventDefault();
        let el = $(this);
        let html = $(this).parent().find('.modal-content')[0].outerHTML;
        $('.modal-ez').find('.content').html(html);
        setTimeout(function () {
            let height = $('.modal-ez').find('.lazyload').height();
            if (el.data('type') == 'niveis') {
                $('.modal-ez').find('.right').css({ height: height });
            }
        }, 500);
        $('body').addClass('modal-open');
    });

    $('body').delegate('button.close-modal', 'click', function () {
        self.closeModal();
    });

    $(document).click(function (event) {
        if ($('body').hasClass('modal-open')) {
            if (!$(event.target).closest('#modal-contact, .btn-more.open-modal').length)
                self.closeModal();
        }
    });
};

Unidade.prototype.closeModal = function () {
    $('body').removeClass('modal-open');
};

Unidade.prototype.scrollFade = function () {
    $(window).scroll(function () {
        $('.fadeOnScroll').each(function (i) {
            var bottom_of_object = $(this).offset().top + 150;
            var bottom_of_window = $(window).scrollTop() + $(window).height();
            if (bottom_of_window > bottom_of_object) {
                $(this).animate({ 'opacity': '1' }, 500);
            }
        });
    });
}

Unidade.prototype.slickGallery = function (select, slidesToShow) {
    var $ref = $(select);


    if (typeof slidesToShow == 'undefined') {
        slidesToShow = 4;
    }

    if ($ref.length > 0) {
        var $banner = $ref.find('.slider');


        $banner.slick({
            infinite: true,
            slidesToShow: slidesToShow,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1100,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 960,
                    settings: {
                        slidesToShow: select == '.carrousel.slide-images' ? 2 : 1,
                        slidesToScroll: select == '.carrousel.slide-images' ? 2 : 1,
                        autoplay: true
                    }
                },
            ],
            speed: 600,
            autoplay: true,
            autoplaySpeed: 8000, adaptiveHeight: true,

            arrows: true,
            prevArrow: $ref.find('.prev'),
            nextArrow: $ref.find('.next'),
            dots: false,
        });


    }
}

$(document).ready(function () {
    new Unidade();
});