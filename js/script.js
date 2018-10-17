window.timeoutId = 0;

$(document).ready(function()
{
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
});

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
                swal("Good job!", data.message, "success");
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
                    $("#edit_middlename").val(data.info.middlename);
                    $("#edit_lastname").val(data.info.lastname);
                    $("#edit_age").val(data.info.age);
                    $("#edit_gender").val(data.info.gender);
                    $("#edit_email").val(data.info.email);
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