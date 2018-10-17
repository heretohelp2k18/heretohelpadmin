<input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION['admin']['user_id']; ?>">
<div class="col-xs-12">
    <div class="col-xs-12 col-md-6 no-gutter">
        <form id="update_app_user_form" class="col-xs-12">
            <input type="hidden" id="edit_id" name="edit_id">
            <input type="hidden" id="action" name="action" value="account">
            <div class="form-group">
                <h1>My Account</h1>
            </div>
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
            <div class="form-group form-group-lg" style="margin-bottom: 30px;">
                <input type="submit" class="btn btn-submit">
            </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function(){
   LoadAppUserEditMode($("#userid").val());
});  
</script>