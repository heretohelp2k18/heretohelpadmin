<?php
echo "
<tr>
    <td>$lastname</td>
    <td>$firstname</td>
    <td>$username</td>
    <td>$position</td>
    <td>
        <a class='action-link edit_app_user' data-id='$id'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>
        <a class='action-link delete_app_user' data-id='$id'><i class='fa fa-trash-o' aria-hidden='true'></i></a>
    </td>
</tr>
";
?>