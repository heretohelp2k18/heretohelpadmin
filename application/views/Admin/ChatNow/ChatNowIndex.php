<script src="https://www.gstatic.com/firebasejs/5.5.4/firebase.js"></script>
<script type = 'text/javascript' src = "<?php echo base_url();?>js/firebase-script.js"></script>
<input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION['admin']['user_id']; ?>">
<div class="col-xs-12">
    <h1>Chat Now</h1>
    <br>
    <div class="col-xs-12 no-gutter">
    </div>
</div>

<!--Modals-->

<div class="modal fade" id="chatnotif-modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
          <h4 class="modal-title">Chat Request</h4>
        </div>
        <div class="modal-body" style="max-height: 70vh; overflow-y: scroll;">
            <div class="row">
                <div class="col-xs-12">
                    <input type="hidden" id="chatroom" name="chatroom">
                    <div class="form-group">
                        <img id="chatnotif_image" class="img-responsive">
                    </div>
                    <div class="form-group">
                        <span class="chatnotif_name"></span>
                    </div>
                    <div class="form-group form-group-lg" style="margin-top: 10px;">
                        <div class="col-xs-12 col-sm-6 chatnotif-action-holder">
                            <input type="button" class="chatnotif-action btn btn-lg btn-success form-control" data-value="1" value="Approve">
                        </div>
                        <div class="col-xs-12 col-sm-6 chatnotif-action-holder">
                            <input type="button" class="chatnotif-action btn btn-lg btn-danger form-control" data-value="2" value="Deny">
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<!--End of Modals-->
<style>
    #chatnotif_image
    {
        border-radius: 50%;
        max-width: 200px;
        margin: 0 auto;
    }
    
    .chatnotif_name {
        display: block;
        text-align: center;
        font-size: 40px;
        padding: 20px 15px;
    }
    
    .chatnotif-action-holder
    {
        margin-bottom: 20px;
    }
</style>
<script>
// Render chat notif modal
var renderChatNotif = function(chatNotif)
{
    console.log(chatNotif);
    $("#chatroom").val(chatNotif.chatroom);
    $(".chatnotif_name").html(chatNotif.name);
    if(chatNotif.gender == "Male")
    {
        $("#chatnotif_image").attr("src","/images/boy.png");
    }
    else
    {
        $("#chatnotif_image").attr("src","/images/girl.png");
    }
    $("#chatnotif-modal").modal();
};

$(document).ready(function(){
    var userId = $("#userid").val();
    fireObj.Online(userId);
    fireObj.ChatNotif(userId, renderChatNotif);
});
</script>