<form action='' method='post'  style='text-align: center;'>

    <p>
        Are you sure you want to deactivate
        <br />
        <b>your own account?</b>
    </p>

    <input type='hidden' name='uid' value='<?php echo $_GET['uid']; ?>' />
    <input type='hidden' name='modName' value='user' />
    <input type='hidden' name='methName' value='deactivateUser' />
    <br>
    <br>
    <input type='submit' name='deactivateAccount' value='deactivate account' class='ivy' />

</form>
