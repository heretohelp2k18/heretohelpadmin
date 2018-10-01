<div class="input-group option-item margin-top-10">
    <span class="input-group-addon select-addon lbl">
        <select class="form-control answer" name="answer">
            <option value="">...</option>
            <?php echo $answers; ?>
        </select>
    </span>
    <input type="text" class="form-control answer-flow" name="sequence" placeholder="value>action" value="<?php echo $value.">".$action; ?>">
    <span class="input-group-btn">
        <button type="button" class="btn btn-danger del-option"><i class="fa fa-times"></i></button>
    </span>
</div>