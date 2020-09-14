<?php
if ($_POST['payload']) {
    shell_exec("cd /var/www/csprep/ && sudo git reset –-hard HEAD && sudo git pull");
}
