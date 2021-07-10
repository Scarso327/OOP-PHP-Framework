<?php

use System\Config;
use System\Session;

?>
<!DOCTYPE html>
<html>
    <head>
        <base href="<?=URL;?>"></base>
        <title><?=\System\Page::Title();?> - <?=Config::GetDynamic("site-name");?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?=$css;?>
    </head>
    <body>
        <nav class="main">
            <div class="container">
                <a href="" >Home</a>
                <div class="profile">
                    <?=(Session::I()->IsLoggedIn()) ? "<a href=\"settings\">Settings</a><a href=\"logout\">Logout</a>" : "<a href=\"login\">Login</a>";?>
                </div>
            </div>
        </nav>
        <div class="container">
            <?=$content?>
        </div>
    </body>
    <script> </script>
</html>