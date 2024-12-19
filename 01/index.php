<?php

$file = fopen('./input.txt', 'r');

$tmp = true;
$map1 = [];
$map2 = [];

while ($tmp) {
  $tmp = fgets($file);
  if (!$tmp) {
    break;
  }
  $splits = explode('   ', $tmp);
  $map1[] = $splits[0];
  $map2[] = intval($splits[1]);
}

sort($map1);
sort($map2);
function getDiff($array1, $array2, $index)
{
  return abs($array1[$index] - $array2[$index]);
}

$result = 0;
for ($i = 0; $i < count($map1); $i++) {
  $result += getDiff($map1, $map2, $i);
}

echo $result;

$scores1 = [];
foreach ($map1 as $id) {
  if (empty($scores1[$id])) {
    $scores1[$id] = 1;
  } else {
    $scores1[$id] = $scores1[$id] + 1;
  }
}

echo json_encode($scores1, JSON_PRETTY_PRINT);

$scores2 = [];
foreach ($map2 as $id) {
  if (empty($scores2[$id])) {
    $scores2[$id] = 1;
  } else {
    $scores2[$id] = $scores2[$id] + 1;
  }
}

$similarity = 0;
foreach ($map1 as $id) {
  if (!empty($scores2[$id])) {
    $similarity += $id * $scores2[$id];
  }
}

echo $similarity;
