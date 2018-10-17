// Set the configuration for your app
// TODO: Replace with your project's config object
// Initialize Firebase
var config = {
  apiKey: "AIzaSyDrNSuf2rZQNdKHuLFPVQ4KX5D26Mq49zI",
  authDomain: "chatme-cd075.firebaseapp.com",
  databaseURL: "https://chatme-cd075.firebaseio.com",
  projectId: "chatme-cd075",
  storageBucket: "chatme-cd075.appspot.com",
  messagingSenderId: "654013816146"
};
firebase.initializeApp(config);

// Get a reference to the database service
var fdb = firebase.database();

var fireObj = {
    Online : function(userId)
    {
        conRef = fdb.ref('online/');
        var con = conRef.child(userId);
        con.set({
            online: true,
            available: true
        });
        con.onDisconnect().remove();
    },
    CurrentChatNotif : null,
    ChatNotif : function(userId, renderChatNotifListener)
    {
        var chatNotif = fdb.ref('chatnotif/' + userId);
        chatNotif.on('value', function(snapshot) {
            var notifVal = snapshot.val();
            if(notifVal != null)
            {
                renderChatNotifListener(notifVal);
            }
        });
    },
    DenyNotif : function(userId){
        var chatNotif = fdb.ref('chatnotif/' + userId);
        chatNotif.remove();
        
        var onlinePsych = fdb.ref('online/');
        onlinePsych.child(userId).child("available").set(true);
        onlinePsych.on('value', function(snapshot) {
            var onlinePsychVal = snapshot.val();
            if(onlinePsychVal != null)
            {
                $.each(onlinePsychVal, function(key, row){
                    if(row != null)
                    {
                        if((row.available) && (key != userId))
                        {
                            if(fireObj.CurrentChatNotif != null)
                            {
                                var passChatNotif = fdb.ref('chatnotif/' + key);
                                passChatNotif.set(fireObj.CurrentChatNotif);
                                fireObj.CurrentChatNotif = null;
                                var onlinePsych = fdb.ref('online/');
                                alert(key);
                                onlinePsych.child(key).child("available").set(false);
                            }
                        }
                    }
                });
            }
        });
    },
    CurrentChatRoom : null,
    AcceptNotif : function(userId, chatroomId, populateMessagesListener){
        var chatNotif = fdb.ref('chatnotif/' + userId);
        chatNotif.remove();
        
        var chatRoom = fdb.ref('chatroom/');
        var chatRoomRow = chatRoom.child(chatroomId);
        chatRoomRow.on('value', function(snapshot) {
            var chatRoomVal = snapshot.val();
            if(fireObj.CurrentChatNotif != null)
            {
                if(chatRoomVal.expired)
                {
                    swal("Oops..", "Chat request expired.", "error");
                }
                else
                {
                    chatRoomRow.child("psychoid").set(userId);
                    var dataObj = {
                        psychoid : userId,
                        userid : chatRoomVal.userid,
                        chatroom : chatroomId
                    };
                    fireObj.InsertChatRoom(dataObj);
                    fireObj.CurrentChatRoom = chatroomId;
                    populateMessagesListener(chatroomId);
                    fireObj.CurrentChatNotif = null;
                }
            }
        });
    },
    InsertChatRoom : function(dataObj)
    {
        $.ajax({
            url : '/admin/AddChatroom',
            method : 'POST',
            data : dataObj,
            dataType : "json",
            beforeSend : function(){
                loading();
            },
            success: function(data) {
                dismissLoading();
                if(data.success)
                {
                }
                else
                {
                    swal("Error", "Error connecting to server.", "error");
                }
            },
            error : function()
            {
                dismissLoading();
                swal("Error", "Error connecting to server.", "error");
            }
        });
    },
    InsertMessage : function(userId, comment)
    {
        var chatRoomId = fireObj.CurrentChatRoom;
        var chatRoom = fdb.ref("/chatroom");
        var chatRoomRow = chatRoom.child(chatRoomId).child("messages");
        var newChat = chatRoomRow.push();
        newChat.set({
            comment : comment,
            id : userId,
            usertype : "Psychologist"
        });
        
    }
};

/*
function writeUserData(userId, name, email) {
  fdb.ref('users/' + userId).set({
    username: name,
    email: email
  });
}

//writeUserData("2","jethro acosta","jethro@kayweb.com.au");
function getData()
{
    var user = fdb.ref('/users');
    user.on("value",function(snapshot){
       snapshot.val(); 
    });
}

function UpdateUserData(userId,name,email)
{
    var updates = {};
    updates['/users/'+userId] = {username : name,
                                email : email};
    fdb.ref().update(updates);
}

//UpdateUserData("1","Jethro pogi","jethropogi@gmail.com");

function removeData(userId)
{
    fdb.ref('/users/'+userId).remove();
}

//removeData("3");

function addUser(username,email)
{
    var usersListRef = fdb.ref("/users");
    var newUser = usersListRef.push();
    newUser.set({
        username : username,
        email : email
    });
}

//addUser("Jethro with auto key","jethro@autokey.com");
*/