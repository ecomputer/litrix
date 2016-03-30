/**
 * Created by miguelangelborraz on 06/02/2015.
 */
$(document).ready(function(e){

  //  MiniApplet = undefined;
    firma=false;


    $('html').on('click', function(e) {
        if (typeof $(e.target).data('original-title') == 'undefined' &&
            !$(e.target).parents().is('.popover.in')) {
            $('[data-original-title]').popover('hide');
        }
    });

    $("#form_activo").bootstrapSwitch({
        onColor: "success",
        offColor: "danger",
        onText: "Si",
        offText:"No"
    });
    $(document).on("click", "#edituser",function(){
        window.location.href = $(this).attr("data-url");
    });
    $(document).on("click",".messagepanel",function(){
        $('#myModal'+$(this).data("id")).modal('toggle')
    });
    $(".modal").on("hidden.bs.modal",function(e){
        var mensaje_div = $("#message"+$(this).data("id"));
        $.messageVisto("/user/messagevisto/",{"id_msg" : $(this).data("id"),"id_de":$(this).data("deid"), "id_para":$(this).data("paraid") },mensaje_div);
    });
    $(document).on("click", "#edit_user_form input[type='password']", function(e){
        bootbox.confirm("Se modificará permanentemente la contraseña del usuario.<br />¿Desea continuar?", function(result)
        {
            if(result)
            {
                setTimeout(function() {
                    $("#form_pass").focus();

                }, 100);

            }
        });
    });

    $(document).on("click", "#delete_user", function(e){
        e.preventDefault();
        var url = $(this).data("url");
        bootbox.confirm("Se borrará permanentemente el usuario y <b>todos</b> sus <b>doumentos</b>.<br />¿Desea continuar?", function(result)
        {
            if(result)
            {
                $.callAjaxGet(url ,true,function(response){
                    if(response.status == "ok")
                    {
                        $.notification("warning",5000,"<strong>Éxito</strong>");
                    }
                    else
                    {
                        $.notification("error",5000,"<strong>Error</strong>");
                    }
                });
            }
        });
    });

    $(document).on("click", "#select_all", function(e){
        e.preventDefault();
        if($("#form_usuarios option").attr("selected") == "selected") {
            $.each($("#form_usuarios option"), function(){
                $(this).removeAttr("selected");
            });
        }
        else
            $("#form_usuarios option").attr("selected", "selected");
    });



    $(document).on("click", "#edituser",function(){
        window.location.href = $(this).attr("data-url");
    });
    /** AJAX CALLS **/
    $("#edit_user_form").submit(function(event){
       event.preventDefault();
        $.callAjax($(this),"json",true,function(response){
            if(response.status == "ok")
            {
                $.notification("warning",5000,"<strong>Éxito</strong>");
            }
            else
            {
                $.notification("warning",5000,"<strong>Error</strong>");
            }
        });
    });
    $('#create_user_form').submit(function(event){
        event.preventDefault();
        $.callAjax($(this),"json",true,function(response){

            $.handleAjaxResponse(response);

        });
    });
    $('#filter_user_form').submit(function(event){
        event.preventDefault();
        $.callAjax($(this),"json",false,function(response){
            $.cargaUsers(response);

        });
    });
    $('#todos_form_button').click(function(event){
        event.preventDefault();
        $.callAjax($('#filter_user_form'),"json",false,function(response){

            $.cargaUsers(response);
        });
    });



    $(document).bind('drop dragover', function (e) {
        e.preventDefault();
    });



    /** FIN AJAX CALLS **/
    //caja de confimración.
    bootbox.setDefaults({
        /**
         * @optional String
         * @default: en
         * which locale settings to use to translate the three
         * standard button labels: OK, CONFIRM, CANCEL
         */
        locale: "es",

        /**
         * @optional Boolean
         * @default: true
         * whether the dialog should be shown immediately
         */
        show: true

    });

    $.initToolTip();
   // $.initIntervalCheks()
    //$.checkAppletLoadedUI();
    $.checkWindowSize();


    //login submit
    $('.form-signin input').bind('keypress', function(e) {
        var code = e.keyCode || e.which;
        if(code == 13) { //Enter keycode
            $('.form-signin').submit();
        }
    });

});