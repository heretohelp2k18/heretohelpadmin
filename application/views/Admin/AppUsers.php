<div class="col-xs-12">
    <h1>Users</h1>
    <div class="col-xs-12 no-gutter users">
        <table class="table table-striped table-hover">
        <thead>
          <tr>
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
            <div class="modal-body">
                <div class="row">
                    <form id="app_user_form" class="col-xs-12">
                        <div class="form-group">
                          <label for="usr">First Name:</label>
                          <input type="text" class="form-control input-sm" id="firstname" name="firstname" required>
                        </div>
                        <div class="form-group">
                          <label for="usr">Last Name:</label>
                          <input type="text" class="form-control input-sm" id="lastname" name="lastname" required>
                        </div>
                        <div class="form-group">
                            <label for="usr">Position:</label>
                            <select class="form-control input-sm" id="position" name="position" required>
                                <option value="">...</option>
                                <option value="User">Regular User</option>
                                <option value="Psychologist">Psychologist</option>
                                <option value="Web Administrator">Web Administrator</option>
                            </select>
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
                            <label class="switch">
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
            <div class="modal-body">
                <div class="row">
                    <form id="update_app_user_form" class="col-xs-12">
                        <input type="hidden" id="edit_id" name="edit_id">
                        <div class="form-group">
                          <label for="usr">First Name:</label>
                          <input type="text" class="form-control input-sm" id="edit_firstname" name="firstname" required>
                        </div>
                        <div class="form-group">
                          <label for="usr">Last Name:</label>
                          <input type="text" class="form-control input-sm" id="edit_lastname" name="lastname" required>
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

</script>
