window.timeoutId = 0;

$(document).ready(function()
{
    $("#search-crime-report").keyup(function(){
        var key = $(this).val();
        clearTimeout(timeoutId); 
        timeoutId = setTimeout(function() {
            SearchCrimeReport(key);
        },500);
    });
    
    $(".offences-list").on("click",".edit_record",function(){
        var id = $(this).attr('data-id');
        $("#edit_id").val(id);
        $("#offenseform input[type=submit]").val("Update");
        $('.offense-form').html("Update Offense");
        $(".add-offense").addClass('toggled');
        $(".offense-form-section").show();
        $(".offences").hide();
        RenderOffenseForEdit(id);
    });
    
    $('.offences-list').on("click",'.delete_record',function(){
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
          },
          function(){
                DeleteOffense(id);
          });
    });
    
    $(".add-offense").click(function(){
        if(!$(this).hasClass('toggled'))
        {
            $("#offenseform input[type=submit]").val("Submit");
            $('.offense-form').html("Add Offense");
            $(this).addClass('toggled');
            $(".offense-form-section").show();
            $(".offences").hide();
        }
        else
        {
            $(this).removeClass('toggled');
            $(".offense-form-section").hide();
            $(".offences").show();
        }
    });
    
    $("#offenseform").submit(function(){
       var action = $("#offenseform input[type=submit]").val();
       if(action == 'Submit')
       {
           SaveOffense();
       }
       else if(action == 'Update')
       {
           UpdateOffense();
       }
       return false; 
    });
    
    $('.add-app-user').click(function(){
        $("#add-app-user-modal").modal();
    });
    
    $('#app_user_form').submit(function(){
        var data = $(this).serialize();
        AddAppUser(data);
        return false;
    });
    
    $('#update_app_user_form').submit(function(){
        var data = $(this).serialize();
        UpdateUser(data);
        return false;
    });
    
    $('#appUsersBody').on("click",'.edit_app_user',function(){
        var id = $(this).attr('data-id');
        LoadAppUserEditMode(id);
    });
    
    $('#appUsersBody').on("click",'.delete_app_user',function(){
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
          },
          function(){
                DeleteAppUser(id);
          });
    });
    
    $('#tableBody').on("click",'.delete-crime-report',function(){
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
          },
          function(){
                DeleteCrimeReport(id);
          });
    });
    
    $('.add-station').click(function(){
        if(!$(this).hasClass('toggled'))
        {
            $(this).addClass('toggled');
            $('.police-stations').hide();
            $('.map').addClass('show');
            $('.police-form').html('Add Police Station');
        }
        else
        {
            $(this).removeClass('toggled');
            $('.police-stations').show();
            $('.map').removeClass('show');
            $("#stationform")[0].reset();
            $('.btn-submit').val("Submit");
        }
        
    });
    
    $('.add-wanted').click(function(){
        if(!$(this).hasClass('toggled'))
        {
            $(this).addClass('toggled');
            $('.wanted-list-form').hide();
            $('.wanted-form-holder').fadeIn();
        }
        else
        {
            $(this).removeClass('toggled');
            $('.wanted-form-holder').hide();
            $('.wanted-list-form').fadeIn();
            $("#wantedform")[0].reset();
            $('.btn-submit').val("Submit");
        }
    });
    
    $('.add-blotter').click(function(){
        if(!$(this).hasClass('toggled'))
        {
            $(this).addClass('toggled');
            $('.blotters').hide();
            $('.blotter-form-map').addClass('show');
            $('.blotter-form').html('Add Blotter');
            $(".suspect-data-container").html('');
            $(".child-data-container").html('');
            $(".victim-data-container").html('');
        }
        else
        {
            $("#blotterform")[0].reset();
            $(".suspect-data-container").html('');
            $(".child-data-container").html('');
            $(".victim-data-container").html('');
            $('.btn-submit').val("Submit");
            $(this).removeClass('toggled');
            $('.blotters').show();
            $('.blotter-form-map').removeClass('show');
        }
        
    });
    
    $(".main-container").on("click", ".add-suspect",function()
    {
        $(".suspect-data-container").append($("#suspect-form-template").html());
        var ctr = 1;
        $(".suspect-data-container").find(".form-caption").each(function(){
            $(this).html("<div class='col-xs-12 caption-holder'><span class='caption'>SUSPECT "+ctr+"</span><span class='clear'>x</span></div>");
            ctr++;
        });
    });
    
    $(".main-container").on("click", ".add-victim",function()
    {
        $(".victim-data-container").append($("#victim-form-template").html());
        var ctr = 1;
        $(".victim-data-container").find(".form-caption").each(function(){
            $(this).html("<div class='col-xs-12 caption-holder'><span class='caption'>VICTIM "+ctr+"</span><span class='clear'>x</span>");
            ctr++;
        });
    });
    
    $(".main-container").on("click", ".add-child",function()
    {
        $(".child-data-container").append($("#child-form-template").html());
        var ctr = 1;
        $(".child-data-container").find(".form-caption").each(function(){
            $(this).html("<div class='col-xs-12 caption-holder'><span class='caption'>CHILD IN CONFLICT WITH THE LAW "+ctr+"</span><span class='clear'>x</span>");
            ctr++;
        });
    });
    
    $(".main-container").on("click", ".clear",function()
    {
        var element = $(this);
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
          },
          function(){
                if($(element).parents(".data-form").hasClass("inDb"))
                {
                    var id = $(element).parents(".data-form").attr("data-id");
                    var table = $(element).parents(".data-form").attr("data-table");
                    GenericDelete(id,table);
                }
                else
                {
                    setTimeout(function(){
                        swal("Deleted!", "Record successfully removed.", "success");
                    },500);
                }
                
                $(element).parents(".data-form").remove();

                var ctr = 1;
                $(".suspect-data-container").find(".form-caption").each(function(){
                    $(this).html("<div class='col-xs-12 caption-holder'><span class='caption'>SUSPECT "+ctr+"</span><span class='clear'>x</span></div>");
                    ctr++;
                });

                var ctr = 1;
                $(".victim-data-container").find(".form-caption").each(function(){
                    $(this).html("<div class='col-xs-12 caption-holder'><span class='caption'>VICTIM "+ctr+"</span><span class='clear'>x</span>");
                    ctr++;
                });

                var ctr = 1;
                $(".child-data-container").find(".form-caption").each(function(){
                    $(this).html("<div class='col-xs-12 caption-holder'><span class='caption'>CHILD IN CONFLICT WITH THE LAW "+ctr+"</span><span class='clear'>x</span>");
                    ctr++;
                });
          });
    });
    
    $("#stationform").submit(function(){
        var data = $(this).serialize();
        var lat = map.getCenter().lat();
        var long = map.getCenter().lng();
        data += '&lat='+lat;
        data += '&long='+long;
        
        var mode = $('.btn-submit').val();
        
        if(mode == 'Submit')
        {
            SaveStation(data);
        }
        else
        {
            UpdateStation(data);
        }

        return false;
    });
    
    $('.stations-list').on("click",".edit_record",function(){
        var id = $(this).attr("data-id");
        LoadPoliceStationsEditMode(id);
    });
    
    $('.stations-list').on("click",'.delete_record',function(){
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
          },
          function(){
                DeleteStation(id);
          });
    });
    
    $('.blotters-list').on("click",'.delete_record',function(){
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
          },
          function(){
                DeleteBlotter(id);
          });
    });
    
    $('.blotters-list').on("click",".edit_record",function(){
        var id = $(this).attr("data-id");
        LoadBlotterEditMode(id);
    });
    
    $('.blotters-list').on("click",".print_record",function(){
        var id = $(this).attr("data-id");
        //window.location.href = '/admin/PrintBlotter/'+id;
        window.open('/admin/PrintBlotter/'+id,'_blank','Print-Window');
    });
    
    $('.wanted-list-form').on("click",".print_record",function(){
        var id = $(this).attr("data-id");
        //window.location.href = '/admin/PrintBlotter/'+id;
        window.open('/admin/PrintWanted/'+id,'_blank','Print-Window');
    });
    
    $("#wantedform").submit(function(){
       loading(); 
    });

    $('.wanted-list').on("click",".edit_record",function(){
        var id = $(this).attr("data-id");
        LoadWantedEditMode(id);
    });
    
    $('.wanted-list').on("click",'.delete_record',function(){
        var id = $(this).attr('data-id');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this record!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
          },
          function(){
                DeleteWanted(id);
          });
    });
    
    $("#blotterform").submit(function(){
        var data = $("#blotterform").serialize();
        var lat = map.getCenter().lat();
        var long = map.getCenter().lng();
        data += '&lat='+lat;
        data += '&long='+long;
        
        var mode =$(".btn-submit").val();
        if(mode == 'Submit Blotter')
        {
            $.ajax({
                url : '/admin/AddBlotter',
                method : 'POST',
                data : data,
                dataType : "json",
                beforeSend : function(){
                    loading();
                },
                success : function(data){
                    if(data.success)
                    {
                        LoadBlotters();
                        $('.add-blotter').trigger("click");
                        swal("Good job!", "Blotter successfully added!", "success");
                    }
                    else
                    {
                        swal("Error", "Error connecting to server.", "error");
                    }

                    dismissLoading();
                },
                error : function(){
                    dismissLoading();
                    swal("Error", "Error connecting to server.", "error");
                }
            });
        }
        else if(mode == 'Update Blotter')
        {
            $.ajax({
                url : '/admin/UpdateBlotter',
                method : 'POST',
                data : data,
                dataType : "json",
                beforeSend : function(){
                    loading();
                },
                success : function(data){
                    if(data.success)
                    {
                        LoadBlotters();
                        $('.add-blotter').trigger("click");
                        swal("Good job!", "Blotter successfully updated!", "success");
                    }
                    else
                    {
                        swal("Error", "Error connecting to server.", "error");
                    }

                    dismissLoading();
                },
                error : function(){
                    dismissLoading();
                    swal("Error", "Error connecting to server.", "error");
                }
            });
        }
        
        return false;
    });
    
    $(".comment-submit").click(function(){
       var comment = $("#comment").val();
       var id = $("#crime_report_id").val();
       if(comment.trim() != '')
       {
           $("#comment").val('');
           $.ajax({
                url : '/admin/AddComment',
                method : 'POST',
                data : {
                    id : id,
                    comment : comment
                },
                dataType : "json",
                beforeSend : function(){
                },
                success : function(data){
                    if(data.success)
                    {
                        RenderComments();
                    }
                    else
                    {
                        swal("Error", "Error connecting to server.", "error");
                    }
                },
                error : function(){
                    dismissLoading();
                    swal("Error", "Error connecting to server.", "error");
                }
            });
       }
    });
});

var DeleteBlotter = function(id)
{
    $.ajax({
            url : '/admin/DeleteBlotter',
            method : 'POST',
            data : {
                id :id
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    LoadBlotters();
                    swal("Deleted!", "Blotter has been deleted.", "success");
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                
                dismissLoading();
            },
            error : function(){
                dismissLoading();
                swal("Error", "Error connecting to server.", "error");
            }
        });
};

var UpdateUser = function(data)
{
    $.ajax({
        url : '/admin/UpdateAppUser',
        method : 'POST',
        data : data,
        dataType : "json",
        beforeSend : function(){
            loading();
        },
        success : function(data){
            if(data.success)
            {
                $('#update_app_user_form')[0].reset();
                $("#update-app-user-modal").modal('hide');
                LoadAppUsers();
                swal("Good job!", "User successfully updated!", "success");
            }
            else
            {
                swal("Error", "Error connecting to server.", "error");
            }

            dismissLoading();
        },
        error : function(){
            dismissLoading();
            swal("Error", "Error connecting to server.", "error");
        }
    });
};

var AddAppUser = function(data)
{    
    $.ajax({
        url : '/admin/AddAppUser',
        method : 'POST',
        data : data,
        dataType : "json",
        beforeSend : function(){
            loading();
        },
        success : function(data){
            if(data.success)
            {
                $('#app_user_form')[0].reset();
                $("#add-app-user-modal").modal('hide');
                LoadAppUsers();
                swal("Good job!", "User successfully added!", "success");
            }
            else
            {
                swal("Error", "Error connecting to server.", "error");
            }

            dismissLoading();
        },
        error : function(){
            dismissLoading();
            swal("Error", "Error connecting to server.", "error");
        }
    });
};

var LoadWantedList = function()
{
    setTimeout(function(){
        window.location.reload();
    },1000);
};

var DeleteWanted = function(id)
{
    $.ajax({
            url : '/admin/DeleteWanted',
            method : 'POST',
            data : {
                id :id
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    LoadWantedList();
                    swal("Deleted!", "Wanted has been deleted.", "success");
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                
                dismissLoading();
            },
            error : function(){
                dismissLoading();
                swal("Error", "Error connecting to server.", "error");
            }
        });
};

var LoadWantedEditMode = function(id)
{
    var frm = $("#wantedform");
    $.ajax({
            url : '/admin/GetWantedById',
            method : 'POST',
            data : {
                id :id
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    $.each(data.info, function(key, value){
                        $('[name='+key+']', frm).val(value);
                    });
                    
                    $('.add-wanted').trigger("click");
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                
                dismissLoading();
            },
            error : function(){
                dismissLoading();
                swal("Error", "Error connecting to server.", "error");
            }
        });
};

var DeleteStation = function(id)
{
    $.ajax({
            url : '/admin/DeleteStation',
            method : 'POST',
            data : {
                id :id
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    LoadPoliceStations();
                    swal("Deleted!", "Station has been deleted.", "success");
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                
                dismissLoading();
            },
            error : function(){
                dismissLoading();
                swal("Error", "Error connecting to server.", "error");
            }
        });
};

var SaveStation = function(data)
{
    $.ajax({
        url : '/admin/AddStation',
        method : 'POST',
        data : data,
        dataType : "json",
        beforeSend : function(){
            loading();
        },
        success : function(data){
            if(data.success)
            {
                $('#stationform')[0].reset();
                LoadPoliceStations();
                swal("Good job!", "Police station successfully saved!", "success");
            }
            else
            {
                swal("Error", "Error connecting to server.", "error");
            }

            dismissLoading();
        },
        error : function(){
            dismissLoading();
            swal("Error", "Error connecting to server.", "error");
        }
    });
};

var UpdateStation = function(data)
{
    $.ajax({
        url : '/admin/UpdateStation',
        method : 'POST',
        data : data,
        dataType : "json",
        beforeSend : function(){
            loading();
        },
        success : function(data){
            if(data.success)
            {
                LoadPoliceStations();
                swal("Good job!", "Police station successfully updated!", "success");
            }
            else
            {
                swal("Error", "Error connecting to server.", "error");
            }

            dismissLoading();
        },
        error : function(){
            dismissLoading();
            swal("Error", "Error connecting to server.", "error");
        }
    });
};

var LoadPoliceStationsEditMode = function(id)
{
    var frm = $("#stationform");
    $.ajax({
            url : '/admin/GetStationById',
            method : 'POST',
            data : {
                id :id
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    $.each(data.info, function(key, value){
                        $('[name='+key+']', frm).val(value);
                    });
                    
                    map.setCenter({
                        lat:parseFloat(data.info.g_lat),
                        lng:parseFloat(data.info.g_long)
                    });
                    
                    map.setZoom(17);
                    
                    $('.add-station').trigger("click");
                    $('.police-form').html('Update Police Station');
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                
                dismissLoading();
            },
            error : function(){
                dismissLoading();
                swal("Error", "Error connecting to server.", "error");
            }
        });
};

var LoadPoliceStations = function()
{
    setTimeout(function(){
        window.location.reload();
    },1000);
};

var DeleteAppUser = function(id)
{
    $.ajax({
            url : '/admin/DeleteAppUser',
            method : 'POST',
            data : {
                id :id
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    LoadAppUsers();
                    swal("Deleted!", "User has been deleted.", "success");
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                
                dismissLoading();
            },
            error : function(){
                dismissLoading();
                swal("Error", "Error connecting to server.", "error");
            }
        });
};

var DeleteCrimeReport = function(id)
{
    $.ajax({
            url : '/admin/DeleteCrimeReport',
            method : 'POST',
            data : {
                id :id
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    swal({
                        title : "Deleted!",
                        text : "Report has been deleted.", 
                        type : "success"
                        },
                        function(){
                            window.location.reload();
                        });
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                
                dismissLoading();
            },
            error : function(){
                dismissLoading();
                swal("Error", "Error connecting to server.", "error");
            }
        });
};

var LoadAppUserEditMode = function(id)
{
    $.ajax({
            url : '/admin/GetAppUserById',
            method : 'POST',
            data : {
                id :id
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    $("#edit_id").val(data.info.id);
                    $("#edit_firstname").val(data.info.firstname);
                    $("#edit_lastname").val(data.info.lastname);
                    $("#edit_username").val(data.info.username);
                    $("#edit_position").val(data.info.position);
                    if(data.info.is_admin == 1)
                    {
                        $("#is-admin-update").prop("checked",true);
                    }
                    else
                    {
                        $("#is-admin-update").prop("checked",false);
                    }
                    $("#update-app-user-modal").modal();
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                
                dismissLoading();
            },
            error : function(){
                dismissLoading();
                swal("Error", "Error connecting to server.", "error");
            }
        });
};

var LoadAppUsers = function()
{
    setTimeout(function(){
        window.location.reload();
    },1000);
};

var LoadBlotters = function()
{
    setTimeout(function(){
        window.location.reload();
    },1000);
};

var LoadBlotterEditMode = function(id)
{
    var frm = $("#blotterform");
    $.ajax({
            url : '/admin/GetBlotterById',
            method : 'POST',
            data : {
                id :id
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    $('.add-blotter').trigger("click");
                    //Rendering of Blotter data
                    $.each(data.info, function(key, value){
                        $('[name='+key+']', frm).val(value);
                    });
                    
                    //Rendering Reporting person data
                    $.each(data.reporting, function(key, value){
                        $('[name='+key+']', frm).val(value);
                    });
                    
                    if(data.reporting.r_is_victim == 1)
                    {
                        $("#r_is_victim").prop("checked",true);
                    }
                    
                    //Rendering Suspects data
                    $(".suspect-data-container").html('');
                    $.each(data.suspect_data_list, function(key, row){
                        $(".suspect-data-container").append(row);
                    });
                    
                    //Rendering Victim data
                    $(".victim-data-container").html('');
                    $.each(data.victim_data_list, function(key, row){
                        $(".victim-data-container").append(row);
                    });
                    
                    //Rendering Victim data
                    $(".child-data-container").html('');
                    $.each(data.child_data_list, function(key, row){
                        $(".child-data-container").append(row);
                    });
                    
                    try
                    {
                        map.setCenter({
                            lat:parseFloat(data.info.g_lat),
                            lng:parseFloat(data.info.g_long)
                        });
                    }
                    catch(err) 
                    {
                        swal("Error", "Trouble loading the map. Check your internet connection.", "error");
                        dismissLoading();
                    }
                    
                    map.setZoom(17);
                    $('.blotter-form').html('Update Blotter');
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                
                dismissLoading();
            },
            error : function(){
                dismissLoading();
                swal("Error", "Error connecting to server.", "error");
            }
        });
};

var loading = function()
{
    $('.loader').slideDown();
};

var dismissLoading = function()
{
    $('.loader').slideUp();
};

var GenericDelete = function(id,table)
{
    $.ajax({
            url : '/admin/GenericDelete',
            method : 'POST',
            data : {
                id : id,
                table : table
            },
            dataType : "json",
            beforeSend : function(){
            },
            success : function(data){
                if(data.success)
                {
                    swal("Good job!", "Record successfully deleted.", "success");
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
            },
            error : function(){
                swal("Error", "Error connecting to server.", "error");
            }
        });
};

var RenderComments = function()
{
    $.ajax({
            url : '/admin/GetComments',
            method : 'POST',
            data : {
                id : $("#crime_report_id").val()
            },
            dataType : "json",
            beforeSend : function(){
            },
            success : function(data){
                if(data.success)
                {
                    $("#comments_list").html(data.comments);
                    setTimeout(RenderComments(),5000);
                }
                else
                {
                    RenderComments();
                }
            },
            error : function(){
                RenderComments();
            }
        });
};

var SaveOffense = function()
{
    $.ajax({
            url : '/admin/SaveOffense',
            method : 'POST',
            data : {
                crime : $("#crime").val(),
                type : $("#type").val()
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    swal({ 
                        title: "Good job!",
                        text: "Offense successfully saved.",
                        type: "success" 
                        },
                        function(){
                          window.location.reload();
                        });
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                dismissLoading();
            },
            error : function(){
                swal("Error", "Error connecting to server.", "error");
                dismissLoading();
            }
        });
};

var UpdateOffense = function()
{
    $.ajax({
            url : '/admin/UpdateOffense',
            method : 'POST',
            data : {
                crime : $("#crime").val(),
                type : $("#type").val(),
                id : $("#edit_id").val()
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    swal({ 
                        title: "Good job!",
                        text: "Offense successfully updated.",
                        type: "success" 
                        },
                        function(){
                          window.location.reload();
                        });
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                dismissLoading();
            },
            error : function(){
                swal("Error", "Error connecting to server.", "error");
                dismissLoading();
            }
        });
};

var RenderOffenseForEdit = function(id)
{
    $.ajax({
            url : '/admin/GetOffenseById',
            method : 'POST',
            data : {
                id : id
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    $("#crime").val(data.info.crime);
                    $("#type").val(data.info.type);
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                dismissLoading();
            },
            error : function(){
                swal("Error", "Error connecting to server.", "error");
                dismissLoading();
            }
        });
};

var DeleteOffense = function(id)
{
    $.ajax({
            url : '/admin/DeleteOffense',
            method : 'POST',
            data : {
                id :id
            },
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success : function(data){
                if(data.success)
                {
                    swal({ 
                        title: "Good job!",
                        text: "Offense has been deleted.",
                        type: "success" 
                        },
                        function(){
                          window.location.reload();
                        });
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
                
                dismissLoading();
            },
            error : function(){
                dismissLoading();
                swal("Error", "Error connecting to server.", "error");
            }
        });
};

var SearchCrimeReport = function(key)
{
    if(key.trim() != '')
    {
        $(".pagination").hide();
        $.ajax({
                url : '/admin/SearchCrimeReport',
                method : 'POST',
                data : {
                    key : key
                },
                dataType : "json",
                beforeSend : function(){
                },
                success : function(data){
                    if(data.success)
                    {
                        $("#tableBody").html(data.list);
                    }
                    else
                    {
                        swal("Error", "Error connecting to server.", "error");
                    }

                },
                error : function(){
                    swal("Error", "Error connecting to server.", "error");
                }
            });
    }
};