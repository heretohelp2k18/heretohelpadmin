<script src="https://www.gstatic.com/firebasejs/5.5.4/firebase.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script type = 'text/javascript' src = "<?php echo base_url();?>js/firebase-script.js"></script>
<input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION['admin']['user_id']; ?>">
<div class="col-xs-12">
    <h1>Chat Now</h1>
    <br>
    <div class="col-xs-12 no-gutter">
        <div class="col-xs-12 col-sm-4 col-md-3 chat-history-contaner">
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9 chat-box-container">
            <div id="vueapp" >
                <div v-for="(msg) in messages" class="col-xs-12 no-gutter">
                    <div v-if="msg.id != <?php echo $_SESSION['admin']['user_id']; ?>" class="pull-left">{{ msg.comment }}</div>
                    <div v-else class="pull-right">{{ msg.comment }}</div>
                </div>
                
            </div>
            <div class="col-xs-12 chat-waiting">
                <img src='/images/rolling.svg' class="chat-loading">
                <h4 class="center-blocked text-center">We're finding you a member to talk to...</h4>
            </div>
            <div class="input-group chat-input hidden">
                <input type="text" id="chatText" class="form-control" placeholder="Your chat here...">
                <span class="input-group-addon send-chat">
                    <i class="fa fa-send"></i>
                </span>
            </div>
        </div>
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
                            <input type="button" class="chatnotif-action btn btn-lg btn-info form-control" data-value="1" data-dismiss="modal" value="Accept">
                        </div>
                        <div class="col-xs-12 col-sm-6 chatnotif-action-holder">
                            <input type="button" class="chatnotif-action btn btn-lg btn-danger form-control" data-value="2" data-dismiss="modal" value="Deny">
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
    fireObj.CurrentChatNotif = chatNotif;
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
    
    $("#chatnotif-modal").modal({
        backdrop: 'static',
        keyboard: false
    });
};

// Vue js part
var vueApp = new Vue({
        el: '#vueapp',
        data: {
          messages : {}
        },
        methods : {
            populateMessages : function(chatroomId)
            {
                var chatroom = fdb.ref('/chatroom');
                var messages = chatroom.child(chatroomId).child("messages");
                messages.on("value",function(snapshot){
                   vueApp.messages = snapshot.val(); 
                });
            }
        }
    });

var populateMessages = function(chatroomId)
{
    vueApp.populateMessages(chatroomId);
};

function sendChat()
{
    var chatText = $("#chatText").val();
    if(chatText.trim() == "")
    {
        $("#chatText").val("");
    }
    else
    {
        fireObj.InsertMessage(userId, chatText);
        $("#chatText").val("");
    }
}

$(document).ready(function(){
    var userId = $("#userid").val();
    // Setting online
    fireObj.Online(userId);
    // Checking for notification
    fireObj.ChatNotif(userId, renderChatNotif);
    
    $(".chatnotif-action").click(function(){
        var action = $(this).attr("data-value");
        if(action == 1)
        {
            var chatroom = $("#chatroom").val();
            fireObj.AcceptNotif(userId,chatroom,populateMessages);
        }
        else if(action == 2)
        {
            fireObj.DenyNotif(userId);
        }
    });
    
    $(".send-chat").click(function(){
        sendChat();
    });
    
    $("#chatText").keyup(function(event){
        if (event.keyCode === 13) {
            sendChat();
        }
    });
});
</script>