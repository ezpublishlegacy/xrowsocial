
{switch match=$login_status.status}
    {case match=1}
        Thank you for activating.
    {/case}
 
    {case match=0}
        Login failed, please click <a href="{$login_status.status_message}">here</a> to login.
    {/case}
 
    {case}
        Login failed, please check your login-settings.
    {/case}
{/switch}