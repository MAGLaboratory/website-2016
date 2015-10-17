<?php

foreach($invader_logs as $log){
  try {
    $entry = json_decode($log['payload'], true);
    echo $entry['output'];
  } catch(Exception $e){
    echo "|::|";
  }
}
