<div id='confirmPopup' style='text-align: center;'>


<form id='confirmForm' action='' method='post' data-abide>
    <input type='hidden' name='modName'  value='user' />
    <input type='hidden' name='methName' value='inviteConfirm' />
    <div class='row'>
        <div class='row collapse'>
            <div class='large-12 columns'>
                 <p id="p-confirm">Please insert your desired username and password
                     to complete the registration process.</p>
            </div>
        </div>
        <div class='row collapse'>
            <div class='large-6 columns'>
                <label for='first_name'></label>
                <input type='text'
                    required pattern='alpha'
                    placeholder='First name'
                    name='first_name' value=''>
                <small class='error'>Obligatoriu!</small>
            </div>
            <div class='large-6 columns'>
                <label for='last_name'></label>
                <input type='text'
                    required pattern='alpha'
                    placeholder='Last name'
                    name='last_name'>
                <small class='error'>Obligatoriu!</small>
            </div>
        </div>
        <div class='row collapse'>
            <div class='large-6 columns'>
                <label for='loginName'></label>
                <input type='text'
                    required pattern='alpha'
                    placeholder='User name'
                    name='loginName' value=''>
            </div>
            <div class='large-6 columns'>
                <label for='password'></label>
                <input type='password'
                    required pattern='alpha'
                    placeholder='Password'
                    name='password'>
                <small class='error'>Obligatoriu!</small>
            </div>
        </div>
        <div class='row collapse'>
            <div class='large-6 columns'>
            </div>
            <div class='large-6 columns'>
                <label for='confirm'></label>
                <input type='password'
                    required pattern='alpha'
                    placeholder='Confirm password'
                    name='confirm' data-equalto='password'>
                <small class='error'>Obligatoriu!</small>
            </div>
        </div>
    </div>
    <input type='submit' name='logSubm'  value='Complete registration' class='ivy' />
</form>
<script src='/fw/LOCALS/gldproject/js/foundation/foundation.js'></script>
<script src='/fw/LOCALS/gldproject/js/foundation/foundation.abide.js'></script>
<script>
    $(document).foundation();
</script>
</div>


