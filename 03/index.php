<?php

function getValue($token)
{
  $isActive = -1;
  $endIndex = strpos($token, ')');
  if ($endIndex === false) {
    return ['value' => 0, 'isActive' => $isActive];
  }

  $tmp = explode(')', $token);
  foreach ($tmp as $tmpToken) {
    if (strpos($tmpToken, 'do(') !== false) {
      $isActive = true;
    }
    if (strpos($tmpToken, 'don\'t(') !== false) {
      $isActive = false;
    }
  }

  $token = $tmp[0];
  $tokens = explode(',', $token);
  if (count($tokens) !== 2) {
    return ['value' => 0, 'isActive' => $isActive];
  }

  foreach ($tokens as $val) {
    if (!ctype_digit($val)) {
      return ['value' => 0, 'isActive' => $isActive];
    }
  }

  return ['value' => intval($tokens[0]) * intval($tokens[1]), 'isActive' => $isActive];
}

$buffer = fopen('./input.txt', 'r');

$string = '';
while (false !== ($char = fgetc($buffer))) {
  $string .= $char;
}

$result = 0;
$isActive = true;
$arr = explode('mul(', $string);
foreach ($arr as $token) {
  $val = getValue($token);
  if ($isActive) {
    $result += $val['value'];
  }

  if ($val['isActive'] !== -1) {
    $isActive = $val['isActive'];
  }
}

echo $result;
