<?php
$g_name = trim(shell_exec('git config --global user.name'));
$g_email = trim(shell_exec('git config --global user.email'));
$l_name = trim(shell_exec('git config user.name'));
$l_email = trim(shell_exec('git config user.email'));
$out = "Global: $g_name <$g_email>\nLocal: $l_name <$l_email>\n";
file_put_contents('config_out.txt', $out);
echo "Done";
