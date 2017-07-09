<?php
wp_logout ();
header ('Location:' . SD_Theme::HOME . '/trainer', 303);
exit (1);
?>
