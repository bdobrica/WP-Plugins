<?php
SD_Player::logout ();
header ('Location:' . SD_Theme::HOME, 303);
exit (1);
?>
