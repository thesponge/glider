<form action='' method='post'  style='text-align: center;'>
    <input type='password' name='oldpw' placeholder='Old Password' class='ivy-light'  />
    <br>
    <input type='password' name='newpw' placeholder='New Password' class='ivy-light'  />
    <br>
    <input type='password' name='confirm' placeholder='Retype Password'  class='ivy-light'  />

    <input type='hidden' name='uid' value='<?php echo $_GET['uid']; ?>' />
    <input type='hidden' name='modName' value='user' />
    <input type='hidden' name='methName' value='changePassword' />
    <br>
    <br>
    <input type='submit' name='changePassword' value='change password' class='ivy' />

</form>
