<div class="col-xs-12">
    <h1 class="pull-left">Users</h1>
    <div class="pull-right" style="margin-top: 25px;">
        <button type="button" id="muldelbtn" class="btn btn-danger">Delete Selected</button>
    </div>
    <div class="col-xs-12 no-gutter users table-responsive">
        <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><input type="checkbox" id="muldel"> All</th>
                <th>Lastname</th>
                <th>Firstname</th>
                <th>Username</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="appUsersBody">
          <?php echo $list; ?>
        </tbody>
      </table>
    </div>

    <div class="modal fade" id="add-app-user-modal" role="dialog">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Add User</h4>
            </div>
              <div class="modal-body" style="max-height: 70vh; overflow-y: scroll;">
                <div class="row">
                    <form id="app_user_form" class="col-xs-12">
                        <div class="form-group">
                          <label for="usr">First Name:</label>
                          <input type="text" class="form-control input-sm" id="firstname" name="firstname" required>
                        </div>
                        <div class="form-group">
                          <label for="usr">Middle Name:</label>
                          <input type="text" class="form-control input-sm" id="middlename" name="middlename">
                        </div>
                        <div class="form-group">
                          <label for="usr">Last Name:</label>
                          <input type="text" class="form-control input-sm" id="lastname" name="lastname" required>
                        </div>
                        <div class="form-group">
                          <label for="usr">Age</label>
                          <input type="number" class="form-control input-sm" id="age" name="age" required>
                        </div>
                        <div class="form-group">
                            <label for="usr">Gender</label>
                            <select class="form-control input-sm" id="gender" name="gender" required>
                                <option value="">...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="usr">User Type:</label>
                            <select class="form-control input-sm" id="position" name="position" required>
                                <option value="">...</option>
                                <option value="User">Regular User</option>
                                <option value="Psychologist">Psychologist</option>
                                <option value="Web Administrator">Web Administrator</option>
                            </select>
                        </div>
                        <div class="form-group">
                          <label for="pwd">Email:</label>
                          <input type="email" class="form-control input-sm" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                          <label for="pwd">Username:</label>
                          <input type="text" class="form-control input-sm" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                          <label for="pwd">Password:</label>
                          <input type="password" class="form-control input-sm" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                          <label for="pwd">Confirm Password:</label>
                          <input type="password" class="form-control input-sm" id="confirmpassword" name="confirmpassword" required>
                        </div>
                        <div class="form-group">
                            <label for="pwd">Web Admin User:</label>
                            <label class="switch switch-add-app-user">
                                <input type="checkbox" name="is_admin">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-submit pull-right">
                        </div>
                    </form>
                </div>
            </div>
          </div>
        </div>
      </div>

    <div class="modal fade" id="update-app-user-modal" role="dialog">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Update App User</h4>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: scroll;">
                <div class="row">
                    <form id="update_app_user_form" class="col-xs-12">
                        <input type="hidden" id="edit_id" name="edit_id">
                        <div class="form-group">
                          <label for="usr">First Name:</label>
                          <input type="text" class="form-control input-sm" id="edit_firstname" name="firstname" required>
                        </div>
                        <div class="form-group">
                          <label for="usr">Middle Name:</label>
                          <input type="text" class="form-control input-sm" id="edit_middlename" name="middlename">
                        </div>
                        <div class="form-group">
                          <label for="usr">Last Name:</label>
                          <input type="text" class="form-control input-sm" id="edit_lastname" name="lastname" required>
                        </div>
                        <div class="form-group">
                          <label for="usr">Age</label>
                          <input type="number" class="form-control input-sm" id="edit_age" name="age" required>
                        </div>
                        <div class="form-group">
                            <label for="usr">Gender</label>
                            <select class="form-control input-sm" id="edit_gender" name="gender" required>
                                <option value="">...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="usr">User Type:</label>
                            <select class="form-control input-sm" id="edit_position" name="position" required>
                                <option value="">...</option>
                                <option value="User">Regular User</option>
                                <option value="Psychologist">Psychologist</option>
                                <option value="Web Administrator">Web Administrator</option>
                            </select>
                        </div>
                        <div class="form-group">
                          <label for="pwd">Email:</label>
                          <input type="email" class="form-control input-sm" id="edit_email" name="email" required>
                        </div>
                        <div class="form-group">
                          <label for="username">Username:</label>
                          <input type="text" class="form-control input-sm" id="edit_username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password: <i>(Note: Leave this blank to retain old password)</i></label>
                            <input type="password" class="form-control input-sm" id="edit_password" name="password">
                        </div>
                        <div class="form-group">
                          <label for="pwd">Confirm Password:</label>
                          <input type="password" class="form-control input-sm" id="edit_confirmpassword" name="confirmpassword">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Web Admin User:</label>
                            <label class="switch">
                                <input id="is-admin-update" type="checkbox" name="is_admin">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-submit pull-right">
                        </div>
                    </form>
                </div>
            </div>
          </div>
        </div>
    </div>
    
    <div class="modal fade" id="review-modal" role="dialog">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Applicant</h4>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: scroll;">
                <div class="row">
                    <div class="col-xs-12">
                        <input type="hidden" id="review_id" name="review_id">
                        <table class="table table-striped" id="review-user-info">
                        </table>
                        <div class="form-group">
                            <img id="review_image" class="img-responsive">
                        </div>
                        <div class="form-group" style="margin-top: 10px;">
                            <input type="button" class="review-action btn btn-danger pull-right" data-value="2" value="Deny">
                            <input type="button" class="review-action btn btn-success pull-right" data-value="1" style="margin-right: 10px;" value="Approve">
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>

<span class="floating-button add-app-user">
    <i class="fa fa-plus" aria-hidden="true"></i>
</span>

<script>

    var password = document.getElementById("password");
    var confirm_password = document.getElementById("confirmpassword");
    var edit_password = document.getElementById("edit_password");
    var edit_confirm_password = document.getElementById("edit_confirmpassword");

    function validatePassword(){
      if(password.value != confirm_password.value) {
        confirm_password.setCustomValidity("Passwords Don't Match");
      } else {
        confirm_password.setCustomValidity('');
      }
    }
    
    function editValidatePassword(){
      if(password.value != confirm_password.value) {
        confirm_password.setCustomValidity("Passwords Don't Match");
      } else {
        confirm_password.setCustomValidity('');
      }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
    edit_password.onchange = editValidatePassword;
    edit_confirm_password.onkeyup = editValidatePassword;
    
    $(document).ready(function(){
        if($("#appUsersBody").children("tr").length == 0)
        {
           $("#appUsersBody").html('<tr><td colspan="5" class="text-center"><i>No records found...</i></td></tr>') 
        }
        
       $("#position").change(function(){
          var value = $(this).val();
          if(value == "Web Administrator")
          {
              $(".switch-add-app-user").trigger("click");
          }
       });
       
       $(".review_app_user").click(function(){
            var id = $(this).attr("data-id");
            $.ajax({
                url : '/admin/GetAppUserById',
                method : 'POST',
                data : {
                    id : id
                },
                dataType : "json",
                beforeSend : function(){
                    loading();
                },
                success : function(data){
                    dismissLoading();
                    if(data.success)
                    {
                        $("#review-user-info").html("");
                        var refined_data = [
                            {
                                label : "First Name",
                                value : data.info.firstname
                            },
                            {
                                label : "Middle Name",
                                value : data.info.middlename
                            },
                            {
                                label : "Last Name",
                                value : data.info.lastname
                            },
                            {
                                label : "Age",
                                value : data.info.age
                            },
                            {
                                label : "Gender",
                                value : data.info.gender
                            },
                            {
                                label : "Email Address",
                                value : data.info.email
                            }
                        ];
                        
                        var info_details = "";
                        $.each(refined_data, function(key, row){
                            info_details += '<tr><td style="width:25%;">'+ row.label +'</td><td>'+ row.value +'</td></tr>';
                        });
                        
                        $("#review_id").val(data.info.id);
                        $("#review_image").attr("src","/images/uploads/"+data.info.idimage);
                        $("#review-user-info").html(info_details);
                        $("#review-modal").modal();
                    }
                },
                error : function(){
                    dismissLoading();
                }
            });
        });
        
        $(".review-action").click(function(){
            var action = $(this).attr("data-value");
            $.ajax({
                url : '/admin/SetApproveUser',
                method : 'POST',
                data : {
                    id : $("#review_id").val(),
                    action : action
                },
                dataType : "json",
                beforeSend : function(){
                    loading();
                },
                success : function(data){
                    dismissLoading();
                    if(data.success)
                    {
                        swal({
                            title: "Successful",
                            text: data.message,
                            type: "success"
                          },
                          function(){
                             window.location.reload();
                          });
                    }
                    else
                    {
                        swal("Oops..",data.message,"error");
                    }
                },
                error : function(){
                    dismissLoading();
                    swal("Oops..","Error connecting to server.","error");
                }
                    
            });
        });
        
        $("#muldel").change(function(){
            if($(this).is(":checked"))
            {
                $(".chkmuldel").prop("checked", true);
            }
            else
            {
                $(".chkmuldel").prop("checked", false);
            }
        });
        
        $("#muldelbtn").click(function(){
            var selected = [];
            $('.chkmuldel:checked').each(function() {
                selected.push($(this).val());
            });
            console.log(selected);
            if(selected.length == 0)
            {
                swal("Oops..", "No selected user.","error");
            }
            else
            {
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover these records!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                  },
                  function(){
                        DeleteAppUser(selected);
                  });
            }
        });
        
    });

</script>