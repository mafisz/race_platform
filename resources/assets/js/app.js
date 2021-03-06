/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app'
// });

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('click', '.editBtn', function(){
    var data_text = $(this).data('text');
    var data_check = $(this).data('check');
    var data_radio = $(this).data('radio');
    var data_img = $(this).data('img');
    var data_tinymce = $(this).data('tinymce');
    var data_order = $(this).data('order');

    $.each(data_text, function(key, value){
        $('#edit_' + key).val(value);
    })

    $.each(data_check, function(key, value){
        var check = false;
        
        if(value == 1)
            check = true;

        $('#edit_' + key).attr("checked", check);
    })

    $.each(data_radio, function(key, value){
        $('[name='+key+']').each(function(){
            $(this).prop("checked", false);
        })
        $('[name='+key+'][value="' + value +'"]').prop("checked", true);
    })

    $.each(data_img, function(key, value){
        $('#edit_' + key).attr('src', value);
    })

    $.each(data_tinymce, function(key, value){
        tinyMCE.activeEditor.setContent(value);
    })

    $.each(data_order, function(key, value){
        var res = value.split(",");
        $('#' + key).html('');

        $.each(res, function(key1, value1){
            $('#' + key).append('<div class="btn btn-info m-2 shadow btn-move" data-id="' + value1 + '">' + value1 + '</div>')   
        })
    })
})

$(document).on('click', '.laurels-short', function(){
    $(this).parent().addClass('click');
})

$(document).on('click', '.laurels', function(){
    $(this).parent().removeClass('click');
})

$(document).on('click', '.deleteBtn', function(){
    var id = $(this).data('id');
    $('#delete_id').val(id);
})

$(document).on('click', '.deleteBtn2', function(){
    var id = $(this).data('id');
    $('#delete_id2').val(id);
})

$(document).on('click', '.banBtn', function(){
    var id = $(this).data('id');
    $('#ban_id').val(id);
})

$(document).on('click', '.unbanBtn', function(){
    var id = $(this).data('id');
    $('#unban_id').val(id);
})

$(document).on('click', '.signBtn', function(){
    var id = $(this).data('id');
    var staff = $(this).data('staff');

    $('#form_id').val(id);
})

$(document).on('click', '.editSignPress', function(){
    var id = $(this).data('id');
    $('#edit_form_id').val(id);
    var staff = $(this).data('staff');

    $.each(staff, function(key, value){
        $('#edit_staff_' + value).prop("checked", true);
    })
})

$(document).on('click', '.editSignService', function(){
    var id = $(this).data('round-id');
    var sign = $(this).data('sign-id');
    $('#service_round_id').val(id);
    $('#service_sign_id').val(sign);

    $.ajax({
        url: '/getSigns',
        method: 'POST',
        data: {id: id, sign: sign},
    }).done(function(response){
        $('#service_list').html('');
        $('#service_list').append(response);
    });
})

$(document).on('click', '.changeSignAdmin', function(e){
    e.preventDefault();
    var id = $(this).data('round-id');
    var sign = $(this).data('sign-id');
    $('#change_round_id').val(id);
    $('#change_sign_id').val(sign);

    $.ajax({
        url: '/getReserveSigns',
        method: 'POST',
        data: {id: id},
    }).done(function(response){
        $('#reserve_list').html('');
        $('#reserve_list').append(response);
    });
})

$(document).on('click', '.editSignServiceAdmin', function(){
    var id = $(this).data('round-id');
    var sign = $(this).data('sign-id');
    $('#service_round_id').val(id);
    $('#service_sign_id').val(sign);

    $.ajax({
        url: '/getSigns',
        method: 'POST',
        data: {id: id, sign: sign},
    }).done(function(response){
        $('#service_list').html('');
        $('#service_list').append(response);
    });
})

$(document).on('click', '.editSignPressAdmin', function(){
    var id = $(this).data('id');
    $('#edit_form_id').val(id);
    var user = $(this).data('user');
    $('#edit_form_user').val(user);
    var staff = $(this).data('staff');

    $.ajax({
        url: '/getStaff',
        method: 'POST',
        data: {id: user},
    }).done(function(response){
        $('.staff').html('');
        $('.staff').append(response);

        $.each(staff, function(key, value){
            $('#edit_staff_' + value).prop("checked", true);
        })
    });
})

$(document).on('click', '.addSection', function(e){
    e.preventDefault();
    var id = $(this).data('id');

    $.ajax({
        url: '/addSection',
        method: 'POST',
        data: {id: id},
    }).done(function(response){
        $('.sections').append(response);
    });

    $(this).data('id', id + 1);
})

$(document).on('change', '#sign_pilot', function(e){
    var id = $(this).val();

    $.ajax({
        url: '/getPilot',
        method: 'POST',
        data: {id: id},
    }).done(function(response){
        $.each(response, function(key, value){
            $('#pilot_' + key).val(value);
        })
    });
})

$(document).on('change', '#edit_sign_pilot', function(e){
    var id = $(this).val();

    $.ajax({
        url: '/getPilot',
        method: 'POST',
        data: {id: id},
    }).done(function(response){
        $.each(response, function(key, value){
            $('#edit_pilot_' + key).val(value);
        })
    });
})

$(document).on('click', '#getDriver', function(e){
    e.preventDefault();
    var uid = $('#sign_driver_uid').val();
    if(!uid)
        var uid = $('#saved').val();

    var form = $('#form_id').val();

    $.ajax({
        url: '/getDriver',
        method: 'POST',
        data: {uid: uid, form: form},
    }).done(function(response){
        $('#noDriver').remove();
        $('.car_option').remove();
        if(response != 'blad'){
            $.each(response, function(key, value){
                $('#driver_' + key).val(value);
            })

            $.each(response['cars'], function(key, value){
                $('#sign_car').append('<option class="car_option" value="' + value['id'] + '">' + value['marka'] + ' ' + value['model'] + '</option>');
            })
        }
        else
        {
            $('#driver_uid_container').append('<p class="text-danger" id="noDriver">Kierowca nie istnieje lub jest już zgłoszony</p>');
        }
    });
})

$(document).on('click', '#editP_getDriver', function(e){
    e.preventDefault();
    var uid = $('#editP_sign_driver_uid').val();
    if(!uid)
        var uid = $('#editP_saved').val();

    var form = $('#editP_form_id').val();

    $.ajax({
        url: '/getDriver',
        method: 'POST',
        data: {uid: uid, form: form},
    }).done(function(response){
        $('#editP_noDriver').remove();
        $('.car_option').remove();
        $('#editP_sign_car').empty();
        $('#editP_sign_car').append('<option disabled selected value>Wybierz samochód</option>');

        if(response != 'blad'){
            $.each(response, function(key, value){
                $('#editP_driver_' + key).val(value);
            })

            $.each(response['cars'], function(key, value){
                $('#editP_sign_car').append('<option class="car_option" value="' + value['id'] + '">' + value['marka'] + ' ' + value['model'] + '</option>');
            })
        }
        else
        {
            $('#editP_driver_uid_container').append('<p class="text-danger" id="editP_noDriver">Kierowca nie istnieje lub jest już zgłoszony</p>');
        }
    });
})

$(document).on('click', '#getPilotUid', function(e){
    e.preventDefault();
    var uid = $('#sign_pilot_uid').val();
    if(!uid)
        var uid = $('#saved').val();
    var form = $('#form_id').val();

    $.ajax({
        url: '/getPilotUid',
        method: 'POST',
        data: {uid: uid, form: form},
    }).done(function(response){
        $('#noPilot').remove();
        if(response != 'blad'){
            $.each(response, function(key, value){
                $('#pilot_' + key).val(value);
            });
        }
        else
        {
            $('#pilot_uid_container').append('<p class="text-danger" id="noPilot">Pilot nie istnieje lub jest już zgłoszony</p>');
        }
    });
})

$(document).on('click', '#edit_getPilotUid', function(e){
    e.preventDefault();
    var uid = $('#edit_sign_pilot_uid').val();
    if(!uid)
        var uid = $('#edit_saved').val();
    var form = $('#edit_form_id').val();

    $.ajax({
        url: '/getPilotUid',
        method: 'POST',
        data: {uid: uid, form: form},
    }).done(function(response){
        $('#edit_noPilot').remove();
        if(response != 'blad'){
            $.each(response, function(key, value){
                $('#edit_pilot_' + key).val(value);
            });
        }
        else
        {
            $('#edit_pilot_uid_container').append('<p class="text-danger" id="edit_noPilot">Pilot nie istnieje lub jest już zgłoszony</p>');
        }
    });
})

$(document).on('change', '#sign_car', function(){
    var id = $(this).val();

    $.ajax({
        url: '/getCar',
        method: 'POST',
        data: {id: id},
    }).done(function(response){
        $.each(response, function(key, value){
            $('#car_' + key).val(value);
        })

        $.each(response, function(key, value){
            var check = false;
            
            if(value == 1)
                check = true;

            $('.car_' + key).attr("checked", check);
            $('.car_' + key).val(value);
        })
    });

    $.ajax({
        url: '/getKlasa',
        method: 'POST',
        data: {id: id},
    }).done(function(response){
        $('#sign_klasa').html('<option disabled="" selected="" value="">Wybierz klasę</option>');
        $('#sign_klasa').append(response);
    });
})

$(document).on('change', '#edit_sign_car', function(){
    var id = $(this).val();

    $.ajax({
        url: '/getCar',
        method: 'POST',
        data: {id: id},
    }).done(function(response){
        $.each(response, function(key, value){
            $('#edit_car_' + key).val(value);
        })

        $.each(response, function(key, value){
            var check = false;
            
            if(value == 1)
                check = true;

            $('.car_' + key).attr("checked", check);
            $('.car_' + key).val(value);
        })
    });

    $.ajax({
        url: '/getKlasa',
        method: 'POST',
        data: {id: id},
    }).done(function(response){
        $('#edit_sign_klasa').html('<option disabled="" selected="" value="">Wybierz klasę</option>');
        $('#edit_sign_klasa').append(response);
    });
})

$(document).on('change', '#editP_sign_car', function(){
    var id = $(this).val();

    $.ajax({
        url: '/getCar',
        method: 'POST',
        data: {id: id},
    }).done(function(response){
        $.each(response, function(key, value){
            $('#editP_car_' + key).val(value);
        })

        $.each(response, function(key, value){
            var check = false;
            
            if(value == 1)
                check = true;

            $('.car_' + key).attr("checked", check);
            $('.car_' + key).val(value);
        })
    });

    $.ajax({
        url: '/getKlasa',
        method: 'POST',
        data: {id: id},
    }).done(function(response){
        $('#editP_sign_klasa').html('<option disabled="" selected="" value="">Wybierz klasę</option>');
        $('#editP_sign_klasa').append(response);
    });
})

$(document).on('click', '.editSign', function(){
    var id = $(this).data('id');
    $('#sign_id').val(id);

    var data_text = $(this).data('text');
    var data_check = $(this).data('check');

    $.each(data_text, function(key, value){
        $('#' + key).val(value);

        if(key == 'klasa')
            $('#klasa option[value="'+value+'"]').attr('selected', 'selected');
    })

    $.each(data_check, function(key, value){
        var check = false;
        
        if(value == 1)
            check = true;

        $('#' + key).attr("checked", check);
    })
})

$(document).on('click', '.editSignUser', function(){
    var form_id = $(this).data('form-id');
    var pilot = $(this).data('pilot-id');

    var name = $(this).data('pilot-name');
    var lastname = $(this).data('pilot-lastname');

    var car = $(this).data('car');
    $('#edit_form_id').val(form_id);

    if(pilot){
        $.ajax({
            url: '/getPilotById',
            method: 'POST',
            data: {id: pilot},
        }).done(function(response){
            $('#edit_saved option[value="'+response.uid+'"]').attr('selected', 'selected');
            $.each(response, function(key, value){
                $('#edit_pilot_' + key).val(value);
            })
        });
    }
    else{
        $.ajax({
            url: '/getPilotByName',
            method: 'POST',
            data: {name: name, lastname: lastname},
        }).done(function(response){
            $.each(response, function(key, value){
                $('#edit_pilot_' + key).val(value);
            })
        });
    }

    $.ajax({
        url: '/getCarByNr',
        method: 'POST',
        data: {car: car},
    }).done(function(response){
        $.each(response, function(key, value){
            $('#edit_car_' + key).val(value);
        })

        $.each(response, function(key, value){
            var check = false;
            
            if(value == 1)
                check = true;

            $('.car_' + key).attr("checked", check);
            $('.car_' + key).val(value);
        })
    });

    $.ajax({
        url: '/getKlasaByNr',
        method: 'POST',
        data: {car: car},
    }).done(function(response){
        $('#edit_sign_klasa').html('<option disabled="" selected="" value="">Wybierz klasę</option>');
        $('#edit_sign_klasa').append(response);
    });
})

$(document).on('click', '.editSignPilot', function(){
    var form_id = $(this).data('form-id');
    var user = $(this).data('user-id');

    var car = $(this).data('car');
    $('#editP_form_id').val(form_id);

    if(user){
        $.ajax({
            url: '/getDriverById',
            method: 'POST',
            data: {id: user},
        }).done(function(response){
            $('.car_option').remove();
            $('#editP_noDriver').remove();
            if(response != 'blad'){
                $('#editP_saved option[value="'+response.uid+'"]').attr('selected', 'selected');
                $.each(response, function(key, value){
                    $('#editP_driver_' + key).val(value);
                })

                $.each(response['cars'], function(key, value){
                    if(value['nr_rej'] == car)
                        $('#editP_sign_car').append('<option class="car_option" selected value="' + value['id'] + '">' + value['marka'] + ' ' + value['model'] + '</option>');
                    else
                        $('#editP_sign_car').append('<option class="car_option" value="' + value['id'] + '">' + value['marka'] + ' ' + value['model'] + '</option>');
                })
            }
            else
            {
                $('#driver_uid_container').append('<p class="text-danger" id="editP_noDriver">Kierowca nie istnieje lub jest już zgłoszony</p>');
            }
        });
    }



    $.ajax({
        url: '/getCarByNr',
        method: 'POST',
        data: {car: car},
    }).done(function(response){
        $.each(response, function(key, value){
            $('#editP_car_' + key).val(value);
        })

        $.each(response, function(key, value){
            var check = false;
            
            if(value == 1)
                check = true;

            $('.car_' + key).attr("checked", check);
            $('.car_' + key).val(value);
        })
    });

    $.ajax({
        url: '/getKlasaByNr',
        method: 'POST',
        data: {car: car},
    }).done(function(response){
        $('#editP_sign_klasa').html('<option disabled="" selected="" value="">Wybierz klasę</option>');
        $('#editP_sign_klasa').append(response);
    });
})

$(document).on('click', '.cancelSign', function(){
    var id = $(this).data('id');
    $('#cancel_id').val(id);
})

$(document).on('click', '.enableSign', function(){
    var id = $(this).data('id');
    $('#enable_id').val(id);
})

$(document).on('click', '.deleteSign', function(){
    var id = $(this).data('id');
    $('#delete_id').val(id);
})

var el = document.getElementsByClassName('sortable_items');
$.each(el, function(key, value){
    var sortable = Sortable.create(value,{
        animation: 150,
        onEnd: function (evt) {
            var order = sortable.toArray();

            $.ajax({
                url: '/update-position',
                method: 'POST',
                data: {order: order},
            }).done(function(response){
                console.log('ok');
            });
        },
    });
})

if(el != null && el.length){
    //Grouping
    var foo = document.getElementById("items");
    var items = Sortable.create(foo, { group: "items" });

    var bar = document.getElementById("dropdown");
    var dropped = Sortable.create(bar, { group: "items" });
}


var el2 = document.getElementById("editRoundForm");
if(el2 != null && el2.length){
    var foo = document.getElementById("items");
    var items = Sortable.create(foo, { 
        group: "items",
        animation: 240
    });
}

var el3 = document.getElementById("newRoundForm");
if(el3 != null && el3.length){
    var foo = document.getElementById("items_new");
    var items_new = Sortable.create(foo, { 
        group: "items_new",
        animation: 240
    });
}

var el4 = document.getElementById("saveTableUsers");
if(el4 != null && el4.length){
    var foo = document.getElementById("items");
    var items = Sortable.create(foo, { group: "items", animation: 240});

    var bar = document.getElementById("dropdown");
    var dropped = Sortable.create(bar, { group: "items", animation: 240 });
}

$(document).on('click', '.select_all', function(e){
    e.preventDefault();
    $(this).toggleClass('check');

    if(!$(this).hasClass('check'))
        $(this).closest('form').find('input').prop('checked', false);
    else
        $(this).closest('form').find('input').prop('checked', true);
    
})

$(document).on('click', '#saveTable', function(e){
    e.preventDefault();
    var order = items.toArray();
    $('#file_items').val(order);
    $(this).closest('form').submit();
})

$(document).on('click', '#generateFile', function(e){
    e.preventDefault();
    var order = dropped.toArray();
    $('#file_items').val(order);
    $(this).closest('form').submit();
})

$(document).on('click', '#saveRound', function(e){
    e.preventDefault();
    var order = items.toArray();
    $('#order_items').val(order);
    $(this).closest('form').submit();
})

$(document).on('click', '#saveNewRound', function(e){
    e.preventDefault();
    var order = items_new.toArray();
    $('#order_items_new').val(order);
    $(this).closest('form').submit();
})

$(document).ready(function() {  
    var editor_config = {
        path_absolute : "/",
        selector: "textarea.tinymce",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
        relative_urls: false,
        file_browser_callback : function(field_name, url, type, win) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
            if (type == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.open({
                file : cmsURL,
                title : 'Filemanager',
                width : x * 0.8,
                height : y * 0.8,
                resizable : "yes",
                close_previous : "no"
            });
        }
    };

    var user_config = {
        selector: "textarea.tinymce_user",
        menubar:false,
        statusbar: true,
        plugins: [
            "advlist lists",
        ],
        toolbar: "bold italic | alignleft aligncenter | bullist numlist",
    };

    tinymce.init(editor_config);

    tinymce.init(user_config);

    $('.owl-carousel').owlCarousel({
        items: 5,
        loop: true,
        nav: false,
        navText: ['', ''],
        autoplay: true,
        autoplayTimeout: 8000,
        autoplayHoverPause: false,
        mouseDrag: true,
        dots: false,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
            },
            576:{
                items:2,
                slideBy:2
            },
            768:{
                items:4,
                slideBy:4
            }
        }
    });

    $('#randomPartner').modal('show')
});

function initializeClock(id){
    var clock = $('#'+id);
    var end = clock.data('deadline');

    countdown.setLabels(
        ' | sek.| min.| godz.| dzień',
        ' | sek.| min.| godz.| dni',
        ' ',
        ' ',
        ' ',
        function(n){
            var formattedNumber = ("0" + n).slice(-2);
            return formattedNumber;
        }
    );
  var timeinterval = setInterval(function(){
    var time = countdown(new Date(end), null, countdown.DAYS|countdown.HOURS|countdown.MINUTES|countdown.SECONDS, 3);
    if(time.value < 0)
        clock.html(time.toString());
    else
        clock.html("00 min. 00 sek.");
  },1000);
}

initializeClock('counter');

$('.datetimepicker').datetimepicker({
    format: 'yyyy-mm-dd hh:ii',
    language: 'pl',
    autoclose: true
});

$('.search-class').click(function(){
    var klasa = $(this).data('klasa');
    $('.filter-box .active').removeClass('active');
    $(this).addClass('active');
    userList.search(klasa);
    $('.wszyscy').show();
    $('.teamy').hide();
    $('.press').hide();
    $('.generalka').hide();
})

$('.search-clear').click(function(){
    $('.filter-box .active').removeClass('active');
    $(this).addClass('active');
    userList.search();
    $('.wszyscy').show();
    $('.teamy').hide();
    $('.press').hide();
    $('.generalka').hide();
})

$('.search-team').click(function(){
    $('.filter-box .active').removeClass('active');
    $(this).addClass('active');
    $('.teamy').show();
    $('.wszyscy').hide();
    $('.press').hide();
    $('.generalka').hide();
})

$('.search-generalka').click(function(){
    $('.filter-box .active').removeClass('active');
    $(this).addClass('active');
    $('.generalka').show();
    $('.wszyscy').hide();
    $('.press').hide();
    $('.teamy').hide();
})

$('.search-press').click(function(){
    $('.filter-box .active').removeClass('active');
    $(this).addClass('active');
    $('.press').show();
    $('.teamy').hide();
    $('.wszyscy').hide();
    $('.generalka').hide();
})


function WHCreateCookie(name, value, days) {
    var date = new Date();
    date.setTime(date.getTime() + (days*24*60*60*1000));
    var expires = "; expires=" + date.toGMTString();
    document.cookie = name+"="+value+expires+"; path=/";
}
function WHReadCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

window.onload = WHCheckCookies;

function WHCheckCookies() {
    if(WHReadCookie('cookies_accepted') != 'T') {
        var message_container = document.createElement('div');
        message_container.id = 'cookies-message-container';
        var html_code = '<div id="cookies-message" style="padding: 5px 0px; font-size: 14px; border-bottom: 1px solid #ffc400; text-align: center; position: fixed; bottom: 0px; color:#FFF; background-color: #060709; width: 100%; z-index: 999;">Ta strona używa ciasteczek (cookies), dzięki którym nasz serwis może działać lepiej. &nbsp; <a href="/polityka_prywatnosci" target="_blank" style="color:#FFF;">  <b>Dowiedz się więcej >></b></a><button id="accept-cookies-checkbox" name="accept-cookies" style="background-color: #ffbb00; padding: 3px 10px; color: #000; border-radius: 4px; -moz-border-radius: 4px; -webkit-border-radius: 4px; display: inline-block; margin-left: 10px; text-decoration: none; cursor: pointer;border:none">Rozumiem</button></div>';
        message_container.innerHTML = html_code;
        document.body.appendChild(message_container);
    }
}

$(document).on('click', '#accept-cookies-checkbox', function(){
    WHCreateCookie('cookies_accepted', 'T', 365);
    document.getElementById('cookies-message-container').removeChild(document.getElementById('cookies-message'));
});

$(document).on('click', '.details-btn', function(){
    $(this).parent().find('.details').slideToggle(300);
    $(this).parent().find('.arrow_down').toggleClass('open');
})

$(document).on('click', '.switch-img', function(){
    $(this).parent().toggleClass('no-images');
})