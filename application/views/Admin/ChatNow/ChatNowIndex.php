<script src="https://www.gstatic.com/firebasejs/5.5.4/firebase.js"></script>
<script type = 'text/javascript' src = "<?php echo base_url();?>js/firebase-script.js"></script>
<input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION['admin']['user_id']; ?>">
<div class="col-xs-12">
    <h1>Chat Now</h1>
    <br>
    <div class="col-xs-12 no-gutter">
    </div>
</div>
<style>
</style>
<script>
$(document).ready(function(){
    var userId = $("#userid").val();
    fireObj.Online(userId);
    fireObj.ChatNotif(userId);
});  
</script>