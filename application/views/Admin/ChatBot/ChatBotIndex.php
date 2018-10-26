<div class="col-xs-12">
    <h1>Chat Bot Sequence</h1>
    <div class="col-xs-12 no-gutter">
        <div class="col-xs-12 col-sm-3 no-gutter">
            <ul class="list-group">
                <li class="list-group-item">
                    <form id="newsequence">
                        <div class="input-group">
                            <select id="tag" class="form-control" required>
                                <option value="">...</option>
                                <?php echo $options ?>
                            </select>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-info">+</button>
                            </span>
                        </div>
                    </form>
                </li>
                <?php echo $list ?>
            </ul>
        </div>
        <div class="col-xs-12 col-sm-9 mob-no-gutter">
            <form id="saveSequence">
                <div class="input-group">
                    <span class="input-group-addon lbl">Title</span>
                    <input type="hidden" id="sequenceid" name="sequenceid" value="<?php echo $botId; ?>">
                    <input type="hidden" id="botTag" name="botTag" value="<?php echo $botTag; ?>">
                    <span class="form-control height-auto"><?php echo $title ?></span>
                    <span class="input-group-addon delete">
                        <i class="fa fa-trash"></i>
                    </span>
                </div>
                <br>
                <blockquote class="no-margin-bottom">
                    <?php echo $content; ?>
                </blockquote>
                <br>
                <div class="pull-left"><h3 style="margin-top: 0;">Options</h3></div>
                <div class="pull-right">
                    <button type="button" class="btn btn-success add-option"><i class="fa fa-plus"></i></button>
                </div>
                <div class="col-xs-12 no-gutter option-list">
                    <div class="input-group option-template hidden">
                        <span class="input-group-addon select-addon lbl">
                            <select class="form-control answer" name="answer">
                                <option value="">...</option>
                                <?php echo $answers; ?>
                            </select>
                        </span>
                        <input type="text" class="form-control answer-flow" name="sequence" placeholder="value>action" value="">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-danger del-option"><i class="fa fa-times"></i></button>
                        </span>
                    </div>
                    <?php echo $sequenceList; ?>
                </div>
                <div class="col-xs-12 margin-top-10 no-gutter">
                    <div class="input-group">
                        <span class="input-group-addon lbl">Follow Up</span>
                        <input id="follow" type="text" class="form-control" name="follow" placeholder="..." value="<?php echo $follow ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-info margin-top-10 pull-right" style="min-width: 100px;">SAVE</button>
            </form>
        </div>
    </div>
</div>
<script>

$("#newsequence").submit(function(){
    $.ajax({
        url : '/admin/addChatBotSequence',
        method : 'POST',
        data : {
            tag : $("#tag").val()
        },
        dataType : "json",
        beforeSend : function(){
            
        },
        success: function(data) {
            if(data.success)
            {
                swal({
                   title: "Successful",
                   text: data.message,
                   type: "success"
                 },
                 function(){
                    window.location.href="/admin/chatbot/" + data.id;
                 });
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
    return false; 
});

$("#saveSequence").submit(function(){
    var answer = [];
    $('.answer').each(function(){
        answer.push($(this).val());
    });
    
    var sequence = {data : []};
    var ctr = 1;
    $(".option-list .option-item").each(function(){
        var tag = answer[ctr];
        var flow = $(this).children(".answer-flow").val();
        var answerFlow = flow.split(">");
        var actionValue = answerFlow[0];
        var action = answerFlow[1];
        
        if((actionValue != null) && (action != null))
        {
            var optionItem = {
                tag : tag,
                value : actionValue,
                action : action
            };

            sequence.data.push(optionItem);
        }
        else
        {
            swal("Oops..", "Invalid sequence found.", "error");
            return false;
        }
        ctr ++;
    });
    
    $.ajax({
        url : '/admin/updateChatBotSequence',
        method : 'POST',
        data : {
            id : $("#sequenceid").val(),
            sequence : JSON.stringify(sequence),
            follow : $("#follow").val()
        },
        dataType : "json",
        beforeSend : function(){
            
        },
        success: function(data) {
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
                swal("Error", "Error connecting to server.", "error");
            }
        },
        error : function()
        {
            swal("Error", "Error connecting to server.", "error");
        }
    });
    return false;
});

$(".delete").click(function(){
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
      },
      function(){
            DeleteSequence($("#sequenceid").val());
      });
    return false; 
});

function DeleteSequence(id)
{
    $.ajax({
        url : '/admin/deleteChatBotSequence',
        method : 'POST',
        data : {
            id : id
        },
        dataType : "json",
        beforeSend : function(){
            
        },
        success: function(data) {
            if(data.success)
            {
                swal({
                   title: "Successful",
                   text: data.message,
                   type: "success"
                 },
                 function(){
                    window.location.href="/admin/chatbot/";
                 });
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
}

$(".add-option").click(function(){
    var new_option = '<div class="input-group option-item margin-top-10">' + $(".option-template").html() + '</div>';
    $(".option-list").append(new_option);
});

$(".option-list").on("click", ".del-option", function(){
    $(this).parents(".option-item").remove(); 
});

$(".option-list").on("change", ".option-item .answer", function(){
    var selected = $(this).val();
    var elem = $(this);
    var match = 0;
    $(".option-item .answer").each(function(){
        if($(this).val() == selected)
        {
            match ++;
        }
        
        if(match > 1)
        {
            swal("Oops..", "Duplicate answer", "error");
            $(elem).val("");
            return false;
        }
    });
});

</script>