<?php
$stones = array_map('intval', explode(' ', '70949 6183 4 3825336 613971 0 15 182'));
$stonesMap = array_fill_keys($stones, 1);

function getNumberLength($number)
{
  return floor(log($number, 10)) + 1;
}

function blink($number)
{
  if ($number === 0) {
    return [1];
  }

  if (getNumberLength($number) % 2 === 0) {
    $n = pow(10, getNumberLength($number) / 2);
    return [intval($number / $n), $number % $n];
  }

  return [$number * 2024];
}

for ($i = 0; $i < 75; $i++) {
  $tmp = [];
  foreach ($stonesMap as $stone => $count) {
    $res = blink($stone);
    foreach ($res as $newStone) {
      $tmp[$newStone] =
        !empty($tmp[$newStone]) ?
        $tmp[$newStone] + $count :
        $count;
    }
  }

  $stonesMap = $tmp;
}

echo array_sum($stonesMap);
