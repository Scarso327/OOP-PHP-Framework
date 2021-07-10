<form accept-charset="utf8" method="post" action="<?=URL;?>login">
    <input type="hidden" name="token" value="<?=$this->token;?>"/>
    <div>
        <?php
        $handlers = \System\Auth\Login::GetHandlers();

        if ($handlers) {
            foreach ($handlers as $handler) {
                echo "<button type=\"submit\" name=\"_loginHandler\" value=\"$handler->id\">Sign in with $handler->name</button>";
            }
        }
        ?>
    </div>
</form>