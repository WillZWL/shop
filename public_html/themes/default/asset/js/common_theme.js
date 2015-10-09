function addJSProduct(currentProduct) {
    $('.thumbs_list_' + currentProduct).serialScroll({
        items: 'li:visible',
        prev: '.view_scroll_left_' + currentProduct,
        next: '.view_scroll_right_' + currentProduct,
        axis: 'y',
        offset: 0,
        start: 0,
        stop: true,
        duration: 700,
        step: 1,
        lazy: true,
        lock: false,
        force: false,
        cycle: false
    });
    $('.thumbs_list_' + currentProduct).trigger('goto', 1); // SerialScroll Bug on goto 0 ?
    $('.thumbs_list_' + currentProduct).trigger('goto', 0);
}

// offcanvas menu
$(document).ready(function() {

    var timeout;
    $('.product-feature-carousel .carousel-inner').css("overflow", "inherit");
    $(".carousel-control").each(function() {
        $(this).click(function() {
            $(".carousel-inner ").css("overflow", "hidden");
            $('.product-feature-carousel .carousel-inner').css("overflow", "inherit");
            clearTimeout(timeout);
        });
    });

    $(".carousel-inner .product-block:first-child").mouseenter(function() {
        $(".carousel-inner").css("overflow", "inherit");
        $('.product-feature-carousel .carousel-inner').css("overflow", "inherit");
    });

    $(".carousel-inner").mouseleave(function() {
        $(".carousel-inner").css("overflow", "hidden");
        $('.product-feature-carousel .carousel-inner').css("overflow", "inherit");
    });

    $(".thumb_more_info").each(function() {
        addJSProduct($(this).attr("data-rel"));
    });

    //hover image
    $(".thumb_more_info").each(function() {
        var pav_preview = this;
        var speed = 800;
        var effect = "easeInOutQuad";
        $(pav_preview).find(".pav-hover-image").each(function() {
            $(this).mouseover(function() {
                var big_image = $(this).attr("data-rel");
                imgElement = $(pav_preview).parent().find(".product_image img").first();
                if (imgElement.length) {
                    $(imgElement).attr("src", big_image);
                    $(imgElement).attr("data-rel", big_image);
                }
            });
        });
    });

    $(document).ready(function() {
        var $scrollingDiv = $(".pavrecentlyviewed");

        $(window).scroll(function() {
            if ($(window).scrollTop() < 300) {
                $(".pavrecentlyviewed").addClass('no-active').removeClass('active');
            } else {
                $(".pavrecentlyviewed").removeClass('no-active').addClass('active');
                $scrollingDiv
                    .stop()
                    .animate({
                        "marginTop": ($(window).scrollTop() + 350) + "px"
                    }, "slow");
            }
        });
    });

    // Currency
    $('.currency .currency-select').on('click', function(e) {
        e.preventDefault();

        $('.currency input[name=\'code\']').attr('value', $(this).attr('data-name'));

        $('.currency').submit();
    });


    // Adding the clear Fix
    cols1 = $('#column-right, #column-left').length;

    if (cols1 == 2) {
        $('#content .product-layout:nth-child(2n+2)').after('<div class="clearfix visible-md visible-sm"></div>');
    } else if (cols1 == 1) {
        $('#content .product-layout:nth-child(3n+3)').after('<div class="clearfix visible-lg"></div>');
    } else {
        $('#content .product-layout:nth-child(4n+4)').after('<div class="clearfix"></div>');
    }

    $('[data-toggle="offcanvas"]').click(function() {
        $('.row-offcanvas').toggleClass('active')
    });

    /* Search */
    $('#offcanvas-search input[name=\'search\']').parent().find('button').on('click', function() {
        url = $('base').attr('href') + 'index.php?route=product/search';

        var value = $('.sidebar-offcanvas input[name=\'search\']').val();

        if (value) {
            url += '&search=' + encodeURIComponent(value);
        }

        location = url;
    });

    $('#offcanvas-search input[name=\'search\']').on('keydown', function(e) {
        if (e.keyCode == 13) {
            $('.sidebar-offcanvas input[name=\'search\']').parent().find('button').trigger('click');
        }
    });
});

$(document).ready(function() {
    $('.product-zoom').magnificPopup({
        type: 'image',
        closeOnContentClick: true,

        image: {
            verticalFit: true
        }
    });

    $('.iframe-link').magnificPopup({
        type: 'iframe'
    });



    // mega megamenu
    // $('.megamenu > li.dropdown').hover(function() {
    //     $('div.dropdown-menu', this).first().stop(true, true).slideDown();
    //     $(this).addClass('open');
    //   }, function() {
    //     $('div.dropdown-menu', this).first().stop(true, true).slideUp();
    //     $(this).removeClass('open');
    // });

    //  Fix First Click Menu /
    $(document.body).on('click', '.megamenu [data-toggle="dropdown"], .verticalmenu [data-toggle="dropdown"]', function() {
        if (!$(this).parent().hasClass('open') && this.href && this.href != '#') {
            window.location.href = this.href;
        }
    });

});


$(document).ready(function() {
    $('#top').click(function() {
        $('html, body').animate({
            scrollTop: 0
        }, 'slow');
    });

    $('.dropdown-menu input').click(function(e) {
        e.stopPropagation();
    });

    // grid list switcher
    $("button.btn-switch").bind("click", function(e) {
        e.preventDefault();
        var theid = $(this).attr("id");
        var row = $("#products");

        if ($(this).hasClass("active")) {
            return false;
        } else {
            if (theid == "list-view") {
                $('#list-view').addClass("active");
                $('#grid-view').removeClass("active");

                // remove class list
                row.removeClass('product-grid');
                // add class gird
                row.addClass('product-list');

            } else if (theid == "grid-view") {
                $('#grid-view').addClass("active");
                $('#list-view').removeClass("active");

                // remove class list
                row.removeClass('product-list');
                // add class gird
                row.addClass('product-grid');

            }
        }
    });


    $(".quantity-adder .add-action").click(function() {
        if ($(this).hasClass('add-up')) {
            $("[name=quantity]", '.quantity-adder').val(parseInt($("[name=quantity]", '.quantity-adder').val()) + 1);
        } else {
            if (parseInt($("[name=quantity]", '.quantity-adder').val()) > 1) {
                $("input", '.quantity-adder').val(parseInt($("[name=quantity]", '.quantity-adder').val()) - 1);
            }
        }
    });
    /****/
    $(document).ready(function() {
        $('.popup-with-form').magnificPopup({
            type: 'inline',
            preloader: false,
            focus: '#input-name',

            // When elemened is focused, some mobile browsers in some cases zoom in
            // It looks not nice, so we disable it:
            callbacks: {
                beforeOpen: function() {
                    if ($(window).width() < 700) {
                        this.st.focus = false;
                    } else {
                        this.st.focus = '#input-name';
                    }
                }
            }
        });
    });
});

// Cart add remove functions
var cart = {
    'addcart': function(sku, qty) {
        $.ajax({
            url: '/cart/ajaxAddItem',
            type: 'post',
            data: 'sku=' + sku + '&qty=' + (typeof(qty) != 'undefined' ? qty : 1),
            dataType: 'json',
            success: function(json) {
                $('.alert, .text-danger').remove();
                if (json['redirect']) {
                    location = json['redirect'];
                }

                if (json['success']) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'fast');
                    $('#notification').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    var html = '<div class="cart-alert-container"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>';
                    //  $('#notification').html();
                    $("#pav-modalbox .modal-body").html(html);
                    $("#pav-modalbox").modal('show');

                    if ($("#cart-total").hasClass("cart-mini-info")) {
                        json['total'] = json['total'].replace(/-(.*)+$/, "");
                    }

                    var out = (json['total']) ? json['total'] : '';
                    //alert(out);
                    $('#cart-total').html(out + ' items');

                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                }
            }
        });
    },
    'update': function(sku, qty) {
        $.ajax({
            url: '/cart/ajaxSetItem',
            type: 'post',
            data: 'sku=' + sku + '&qty=' + (typeof(qty) != 'undefined' ? qty : 1),
            dataType: 'json',
            beforeSend: function() {
                $('#cart > button').button('loading');
            },
            success: function(json) {
                if (json['redirect']) {
                    location = json['redirect'];
                }
                $('#cart > button').button('reset');

                $('#cart-total').html(json['total']);
                if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
                    location = 'index.php?route=checkout/cart';
                } else {
                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                }
            }
        });
    },
    'remove': function(sku) {
        $.ajax({
            url: '/cart/ajaxRemoveItem',
            type: 'post',
            data: 'sku=' + sku,
            dataType: 'json',
            beforeSend: function() {
                $('#cart > button').button('loading');
            },
            success: function(json) {
                if (json['redirect']) {
                    location = json['redirect'];
                }
                $('#cart > button').button('reset');

                var out = (json['total']) ? json['total'] : '';
                $('#cart-total').html(out + ' items');

                if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
                    location = 'index.php?route=checkout/cart';
                } else {
                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                }
            }
        });
    }
}

var wishlist = {
    'addwishlist': function(product_id) {
        $.ajax({
            url: 'index.php?route=account/wishlist/add',
            type: 'post',
            data: 'product_id=' + product_id,
            dataType: 'json',
            success: function(json) {
                $('.alert').remove();

                if (json['success']) {
                    $('#notification').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                if (json['info']) {
                    $('#notification').html('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + json['info'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                $('#wishlist-total').html(json['total']);

                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
            }
        });
    },
    'remove': function() {

    }
}
var standardWaitingScreen = {
    "showPleaseWait": function() {
        $("#pleaseWaitDialog").modal("show");
    },
    "hidePleaseWait": function() {
        $("#pleaseWaitDialog").modal("hide");
    }
};