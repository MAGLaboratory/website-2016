<?php

function timeline_graph_data($from, $to){
  $mysqli = get_mysqli();
  if($stmt = $mysqli->prepare("SELECT sensor, UNIX_TIMESTAMP(start_at), UNIX_TIMESTAMP(progress_at), UNIX_TIMESTAMP(end_at) FROM haldor WHERE start_at BETWEEN FROM_UNIXTIME(?) AND FROM_UNIXTIME(?) AND (end_at IS NULL OR end_at BETWEEN FROM_UNIXTIME(?) AND FROM_UNIXTIME(?))")){
    $stmt->bind_param('iiii', $from, $to, $from, $to);
    $stmt->execute();
    if($res = $stmt->get_result()){
      $data = $res->fetch_all();
      return $data;
    }
  }
  return null;
}

function timeline_graph_json($from, $to){
  $data = timeline_graph_data($from, $to);
  if($data){
    $response = [];
    
    foreach($data as $value){
      $name = str_replace('_', ' ', $value[0]);
      $end_at = $value[1];
      if($value[2] && $value[2] > $end_at){
        $end_at = $value[2];
      }
      
      if($value[3] && $value[3] > $end_at){
        $end_at = $value[3];
      }
      array_push($response, "['${name}', '', new Date(${value[1]}000), new Date(${end_at}000)]");
    }
  
    $response = implode(",\n", $response);
    return "[${response}]";
  }  
  # [Name, Subname, from, to]
  return "[]";
}

function get_latest($sensor){
  $mysqli = get_mysqli();
  if($stmt = $mysqli->prepare("SELECT UNIX_TIMESTAMP(progress_at), UNIX_TIMESTAMP(end_at), UNIX_TIMESTAMP(mark_at), last_value FROM haldor WHERE sensor = ? ORDER BY progress_at DESC LIMIT 1")){
    $stmt->bind_param('s', str_replace(' ', '_', $sensor));
    $stmt->execute();
    if($res = $stmt->get_result()){
      $data = $res->fetch_all()[0];
      return $data;
    }
  }

  return null;
}

function latest_changes(){
  $change_items = array(
    'Open Switch' => [],
    'Front Door' => [],
    'Main Door' => [],
    'Office Motion' => [],
    'Shop Motion' => [],
    'Temperature' => []
    );
  
  $now = time();
  
  // TODO: May be better to manually run each one to avoid extra strpos calls
  foreach($change_items as $sensor => &$value){
    $data = get_latest($sensor);
    
    if($data == null){
      array_push($value, 'No Data');
      array_push($value, null);
      array_push($value, false);
      continue;
    }
    
    # Doors are either open or closed. Easy
    if(strpos($sensor, 'Door') !== false){
      if($data[1] == null && $data[2] == null){
        array_push($value, 'Open');
        array_push($value, $data[0]);
        array_push($value, true);
      } else {
        array_push($value, 'Closed');
        array_push($value, $data[1] | $data[2]);
        array_push($value, false);
      }
    }
    
    if(strpos($sensor, 'Motion') !== false){
      if($data[1] == null && $data[2] == null){
        array_push($value, 'Moving');
        array_push($value, $data[0]);
        array_push($value, true);
      } elseif($now - 20*60 < $data[0] && ($data[1] or $data[2])){
        array_push($value, 'Moving');
        array_push($value, $data[0]);
        array_push($value, true);
      } else {
        array_push($value, 'No Movement since');
        array_push($value, $data[0]);
        array_push($value, false);
      }
    }
    
    if(strpos($sensor, 'Switch') !== false){
      if($data[1] == null && $data[2] == null){
        array_push($value, 'Flipped ON');
        array_push($value, $data[0]);
        array_push($value, true);
      } else {
        array_push($value, 'Flipped OFF');
        array_push($value, $data[1] | $data[2]);
        array_push($value, false);
      }
    }
    
    if($sensor == 'Temperature'){
      array_push($value, '' . sprintf("%.2f °C/ %.2f °F", (($data[3] | 0) / 1000), ((($data[3] | 0) / 1000)*1.8 + 32)));
      array_push($value, $data[2] | $data[1] | $data[0]);
    }
  }

  $change_items['Page Loaded'] = ['at', $now];

  return $change_items;
}

function is_maglabs_open($latest){
  # Space is open when:
  # a) a door is open (main or front)
  # b) one of the following is true: open switch ON, movement in last 20 minutes
  $movement = $latest['Open Switch'][2] | $latest['Office Motion'][2] | $latest['Shop Motion'][2];
  $doors = $latest['Front Door'][2] | $latest['Main Door'][2];
  
  return $movement && $doors;
  
}
