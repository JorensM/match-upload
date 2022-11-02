<?php
    session_start();
    $_SESSION["cancel_upload"] = true;
    session_write_close();