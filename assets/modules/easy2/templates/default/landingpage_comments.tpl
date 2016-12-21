
<p>
<form method="post" action="">
    <div class="e2com_container">
        <table cellspacing="0" cellpadding="2" border="0" width="98%">
            <tr>
                <td colspan="4"><b>[+easy2:comment_add+]</b> ( [+easy2:waitforapproval+] )</td>
            </tr>
            <tr>
                <td width="40"><b>[+easy2:name+]:</b></td>
                <td><input name="name" type="text"[+WEBUSER_NAME+]></td>
                <td width="40"><b>[+easy2:email+]:</b></td>
                <td><input name="email" type="text"[+WEBUSER_EMAIL+]></td>
            </tr>
            <tr>
                <td colspan="4"><b>[+easy2:usercomment+]:</b><br />
                    <textarea name="comment" rows="3" cols="100%">[+WEBUSER_COMMENT+]</textarea>
                </td>
            </tr>
            <!--
            <tr>
                <td>
                -->
                
                [+easy2:recaptcha+]
                
                <!--
                </td>
            </tr>
            -->
            <tr>
                <td colspan="4"><input type="submit" value="[+easy2:send_btn+]"></td>
            </tr>
        </table>
    </div>
    <input type="hidden" name="ip_address">
</form>
</p>
[+easy2:pages_permalink+]
[+easy2:comment_pages+]
[+easy2:comment_body+]

