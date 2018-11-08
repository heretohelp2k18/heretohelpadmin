<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=<?php echo TINYMCE_API_KEY; ?>"></script>
<script>
tinymce.init({
   selector: "textarea",
   plugins: "a11ychecker, advcode, linkchecker, media mediaembed, powerpaste, tinymcespellchecker",
   toolbar: "a11ycheck, code, tinycomments"
});
</script>
<div class="col-xs-12">
    <h1>Preferences</h1>
    <div class="col-xs-12 no-gutter">
        <div class="col-xs-12 col-sm-3 no-gutter">
            <ul class="list-group">
                <li class="list-group-item">
                    <form id="newpref">
                        <div class="input-group">
                            <input id="title" type="text" class="form-control" placeholder="New preference" required>
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
            <form id="savePref">
                <input type="hidden" id="prefid" value="<?php echo $prefid ?>">
                <div class="input-group">
                    <span class="input-group-addon lbl">TITLE</span>
                    <input id="preftitle" type="text" class="form-control" name="preftitle" placeholder="..." value="<?php echo $title ?>">
                    <span class="input-group-addon delete">
                        <i class="fa fa-trash"></i>
                    </span>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon lbl">TAG</span>
                    <input id="tag" type="text" class="form-control" name="tag" placeholder="..." value="<?php echo $tag ?>">
                </div>
                <br>
                <textarea id="content" style="height:35vh;"><?php echo $content ?></textarea>
                <br>
                <button type="submit" class="btn btn-info pull-right" style="min-width: 100px;">SAVE</button>
            </form>
        </div>
    </div>
</div>
<script>
$("#newpref").submit(function(){
    $.ajax({
        url : '/admin/addPreference',
        method : 'POST',
        data : {
            title : $("#title").val()
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
                    window.location.href="/admin/preferences/" + data.id;
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

$("#savePref").submit(function(){
    $.ajax({
        url : '/admin/updatePreference',
        method : 'POST',
        data : {
            id : $("#prefid").val(),
            title : $("#preftitle").val(),
            tag : $("#tag").val(),
            content : tinyMCE.get('content').getContent()
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
                swal("Oops..", data.message, "error");
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
            DeletePreference($("#prefid").val());
      });
    return false; 
});

function DeletePreference(id)
{
    $.ajax({
        url : '/admin/deletePreference',
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
                    window.location.href="/admin/preferences/";
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
</script>