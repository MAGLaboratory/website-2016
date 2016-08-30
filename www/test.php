<?php

header('Content-Type: text/plain');
$tidy = new tidy();
echo $tidy->repairString('<p>hi</p>');
