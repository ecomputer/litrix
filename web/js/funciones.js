/**
 * Created by miguelangelborraz on 06/02/2015.
 *
 */
//Funciones jquery
(function ( $ ) {
    $.extend({
        callAjax: function(form,response_type,redirect,callbackFunction,custom_url) {

            var url;
            var formulario;

            if(!custom_url)
            {
                formulario=form.serialize();
                url = form.attr("action");

            }
            else
            {
                formulario={};

                var values, index;

                // Get the parameters as an array
                values = form.serializeArray();

                // Find and replace `content` if there
                for (index = 0; index < values.length; ++index) {

                    if (values[index].name != "form[_token]") {
                        values[index].value = "";
                    }
                }
                // Add it if it wasn't there
                if (index >= values.length) {
                    values.push({
                        name: "form[activo]",
                        value: 1
                    });
                }
                // Convert to URL-encoded string
                formulario = jQuery.param(values);

                url=custom_url;
            }
            console.log("url",url);
            var jqxhr = $.ajax({
                url: url,
                data: formulario,
                type: 'post',
                dataType: response_type,

                beforeSend: function () {
                    if(response_type=='json') {
                        if(url.search("filterusers") > 0)
                        {
                            $("#filter_user_form_button").html("<span class='glyphicon glyphicon-refresh'></span> Filtrando");
                            var $icon = $("#filter_user_form_button").find( ".glyphicon-refresh" ), animateClass = "icon-refresh-animate";
                            $icon.addClass( animateClass );

                        }
                        else {
                            //waitingDialog.show('Cargando Datos');
                        }
                    }

                }

            }).done(function (response)
            {
                if(response_type=='json')
                {
                    if(response.status == "ok" && redirect)
                        window.location.href = response.url;
                }
                callbackFunction.call(this, response);


            }).fail(function (jqXHR, textStatus, errorThrown) {
                waitingDialog.hide();
            })
        },
        callAjaxGet: function(url,redirect,callbackFunction,type) {
            var html;
            if(!type) type='json';
            var jqxhr = $.ajax({
                url: url,
                type: 'GET',
                dataType: type,
                beforeSend: function ()
                {

                }
            }).done(function (response)
            {
                if(response.status == "ok" && redirect)
                    window.location.href = response.url;

                callbackFunction.call(this, response);
            }).fail(function (jqXHR, textStatus, errorThrown) {

                console.log(errorThrown);
                if(url =="/login/check")
                    location.reload();
            });
        },
        cargaUsers: function(data){
            //var datos = JSON.parse(data);
            $("#filter_user_form_button").html("Filtrar");
            $("#tableusers tbody").remove();
            $("#tableusers thead").remove();
            var html_head='';
            var html = "<tbody>";

           if($.isEmptyObject(data)==false)
           {
               html_head='<thead><th>Nombre</th><th>Email</th><th>Activo</th></thead>';
               $.each(data, function(i, item){

                   if(data[i].id && data[i].id.trim()!='1')
                   {
                      '<button id="edituser" type="button" data-url = "'+data[i].ruta+'" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-edit"></span> Editar</button>';

                       html+=
                           '<tr class="hovered-tr"  id="main-row-'+data[i].id.trim()+'"  data-rowid="'+data[i].id.trim()+'">'+

                               '<td>'+data[i].nombre.trim()+' '+data[i].apellidos.trim()+'</td>'+
                               '<td>'+ data[i].email.trim() +'</td>'+
                               '<td>'+ data[i].activo.trim() + '</td>'+
                               '<td style="text-align: center;" class="hovered-td td-n-'+data[i].id.trim()+'">'+

                                    '<button id="edituser" type="button" data-url = "'+data[i].ruta+'" class="btn btn-xs btn-default margin-left-10"> Editar</button>'+
                                '</td>'+

                           '</tr>';
                   }


               });
           }
           else
           {
               html +='<tr><td colspan="3" class="danger">No se encontraraon coincidencias</td></tr>';
           }



            html += "</tbody>";
            $("#tableusers").append(html_head+html);


            $(".mensaje").popover({
                html: true,
                title: function(){
                    return $("#popover-head"+$(this).data("id")).html();
                },
                content: function(){
                    return $("#popover-content"+$(this).data("id")).html();
                },
                    placement: "left"
            });
        },

        hideAlltrList: function()
        {
            $('.uploader-single-container').hide("fast");
            $('.showdocs-single-container').hide("fast");
            $('.showmsgs-single-container').hide("fast");

        },

        limpiaFormMessages: function(){
            $(".mensaje").val("");
            $(".asunto").val("");
        },
        messageVisto: function(url,mensaje,mensaje_div){
            var jqxhr = $.ajax({
                url: url,
                data: mensaje ,
                type: 'POST',
                dataType: 'json',
                beforeSend: function ()
                {

                }
            }).done(function (response)
            {
                if(response.status == "ok")
                {
                    mensaje_div.removeClass("info");
                    mensaje_div.addClass("success");
                }
                //callbackFunction.call(this, response);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                
            });
        },
        notification: function(tipo,time,mensaje)
        {
            var notificacion = $(".alert");
            notificacion.hide();
            notificacion.html(mensaje);
            notificacion.addClass("alert-"+tipo).show();
            setTimeout(function() {
                notificacion.hide();

            }, time);
        },
        hideClosestRow: function(element)
        {
            $(element).closest('tr').hide("slow");
        },
        handleAjaxResponse: function(response)
        {
            if(response.status == "ok")
            {
                $.notification("warning",5000,"<strong>Éxito</strong>");
            }
            else if(response.status=="ko" && response.message)
            {
                $.notification("warning",5000,"<strong>"+response.message+"</strong>");
                console.log(response.url);


            }
            else
            {
                $.notification("warning",5000,"<strong>Error</strong>");
            }
        },
        ajaxSessionCheck: function()
        {
            //cada 3 segundos checkeamos el login
            setInterval(function(){
                $.callAjaxGet('/login/check',false,function(response){

                    if(response.status != "logged")
                        window.location.href = '/';

                });
                },3000);
        },

        initIntervalCheks: function(){
            $.ajaxSessionCheck();

        },



        checkWindowSize: function()
        {
            var heigh=window.innerHeight-250;
            $('.headingUsers .panel-body').css('height',heigh+'px');

            $( window ).resize(function() {
                var heigh=window.innerHeight-250;
                $('.headingUsers .panel-body').css('height',heigh+'px');
            });
        },
        initToolTip: function(){
            //mensajes de ayuda de la UI
            $('[data-toggle="tooltip"]').tooltip();
        }


    });


})( jQuery );
