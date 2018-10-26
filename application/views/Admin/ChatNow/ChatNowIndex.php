<script src="https://www.gstatic.com/firebasejs/5.5.4/firebase.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script type = 'text/javascript' src = "<?php echo base_url();?>js/firebase-script.js"></script>
<input type="hidden" id="userid" name="userid" value="<?php echo $_SESSION['admin']['user_id']; ?>">

<div id="vueapp" class="col-xs-12 no-gutter chat-now-parent">
    <div class="col-xs-12 visible-xs no-gutter"><button type="button" class="btn btn-info show-history">Show Chat History >></button></div>
    <div class="col-xs-12 col-sm-4 col-md-3  no-gutter chat-history-container">
        <div class="col-xs-12 chat-history-label">
            <h4>Chat History</h4>
        </div>
        <div class="chat-history-list">
            <div v-for="item in historyItem" class="col-xs-12 no-gutter history-item">
                <a class="history-item-trigger col-xs-12 no-gutter" v-bind:data-chatroom="item.chatroom">
                    <div v-if="item.gender == 'Male'" class="col-xs-3 col-sm-3 chat-img-container">
                        <img src="/images/boy.png" class="img-responsive chat-img">
                    </div>
                    <div v-else class="col-xs-3 col-sm-3 chat-img-container">
                        <img src="/images/girl.png" class="img-responsive chat-img">
                    </div>
                    <div class="col-xs-9 col-sm-9">
                        <span class="ch-name">{{ item.chatmate }}</span>
                        <span class="ch-date">{{ item.chatdate }}</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-8 col-md-9 chat-box-container no-gutter">
        <div class="col-xs-12 chat-history-label">
            <h4 class="chat-now-label">Chat Now
            <a style="float:right;cursor: pointer;" onclick="window.location.reload()">Start New Conversation</a>
        </div>
            </h4>
            
        <div id="chat-container" class="chat-container col-xs-12">
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
    
    .show-history
    {
        margin-bottom: 10px;
    }
</style>
<script>
// My Info
var MyInfo = JSON.parse('<?php echo $info; ?>');
//console.log("MyInfo:::");
//console.log(MyInfo);
    
// Render chat notif modal
var renderChatNotif = function(chatNotif)
{
    fireObj.CurrentChatNotif = chatNotif;
//    console.log(chatNotif);
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

function loadChatHistory(userId){
    $.ajax({
            url : '/admin/GetChatroom',
            method : 'POST',
            data : {
                userid : userId
            },
            dataType : "json",
            beforeSend : function(){
            },
            success: function(data) {
                if(data.success)
                {
//                    console.log("data.chatroom_data:::");
//                    console.log(data.chatroom_data);
                    vueApp.populateHistoryItems(data.chatroom_data);
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
            },
            error : function()
            {
                swal("Error", "Error connecting to server.", "error");
            }
        });
};

// Vue js part
var vueApp = new Vue({
        el: '#vueapp',
        data: {
          messages : {},
          historyItem : {}
        },
        methods : {
            populateMessages : function(chatroomId)
            {
                var chatroom = fdb.ref('/chatroom');
                var messages = chatroom.child(chatroomId).child("messages");
                messages.on("value",function(snapshot){
                   vueApp.messages = snapshot.val();
                   setTimeout(function(){
                        var objDiv = document.getElementById("chat-container");
                        objDiv.scrollTop = objDiv.scrollHeight;
                    }, 500);
                });
            },
            populateHistoryItems : function(historyItems){
                vueApp.historyItem = historyItems;
            }
        }
    });

var populateMessages = function(chatroomId)
{
    vueApp.populateMessages(chatroomId);
    $(".chat-waiting").hide();
    $(".chat-input").removeClass("hidden");
};

function sendChat(userId)
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
        sendChat(userId);
    });
    
    $("#chatText").keyup(function(event){
        if (event.keyCode === 13) {
            sendChat(userId);
        }
    });
    
    // Load Chat History
    loadChatHistory(userId);
    
    $(".chat-history-list").on("click", ".history-item-trigger", function(){
        $(".chat-history-container").removeClass("mob-show");
        $(".show-history").html("Show Chat History >>");
        var chatroom = $(this).attr("data-chatroom");
        populateMessages(chatroom);
        fireObj.CurrentChatRoom = chatroom;
    });
    
    $(".show-history").click(function(){
        var visib = $(".chat-history-container").hasClass("mob-show");
        if(visib)
        {
            $(".chat-history-container").removeClass("mob-show");
            $(".show-history").html("Show Chat History >>");
        }
        else
        {
            $(".chat-history-container").addClass("mob-show");
            $(".show-history").html("<< Hide Chat History");
        }
    });
});
</script>