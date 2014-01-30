 <div id='confirmPopup' style='text-align: center;'>

     <div id="popup-body">
         <div id="popup-left">
             <p id="p-confirm">Please insert your desired username and password
                 to complete the registration process.</p>
         </div>
         <div id="popup-right">
             <!-- Form Login  -->

            <form id='confirmForm' action='' method='post'>
                <input name='first_name' placeholder='First name' type='text'
                    style="width: 95px;" class='ivy-light'  />
                <input name='last_name' placeholder='Last name' type='text'
                    style="width: 95px;" class='ivy-light'  />
                <br>
                <input name='loginName' placeholder='Username' type='text'
                    style="width: 95px;" class='ivy-light'  />
                <select name="title" style="width: 106px;">
                    <option value="journalist">Journalist</option>
                    <option value="coder">Coder</option>
                </select>
                <br>
                <input name='password' placeholder='Password' type='password'
                    style="width: 95px;" class='ivy-light'  />
                <input name='confirm' placeholder='Confirm password' type='password'
                    style="width: 95px;" class='ivy-light'  />
                <br>
                <input type='hidden' name='modName'  value='user' />
                <input type='hidden' name='methName' value='inviteConfirm' />
                <input type='submit' name='logSubm'  value='Complete registration' class='ivy' />
            </form>

        </div>
     </div>
     <div class="clearfix"></div>
     <div id="popup-footer">
         <p>
            Errors during registration process? Please
             <a href="mailto:devops@theblacksea.eu">
                 contact the website administration
             </a>.
         </p>
     </div>

</div>
