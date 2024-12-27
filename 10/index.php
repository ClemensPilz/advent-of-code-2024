<?php

function getMap()
{
  $buff = fopen('./map.txt', 'r');
  $map = [];
  while (false !== ($ln = fgets($buff))) {
    $ln = trim($ln);
    $map[] = array_map(fn($el) => intval($el), str_split(trim($ln)));
  }

  return $map;
}

function findPeaks($y, $x, $map)
{
  return null;
}

function move($y, $x, &$map)
{
  $elev = $map[$y][$x];
  $result = 0;

  if ($elev === 9) {
    return 1;
  }

  if ($y > 0 && $map[$y - 1][$x] === $elev + 1) {
    $tmp = move($y - 1, $x, $map);
    if ($tmp !== 0) {
      $result += $tmp;
    }
  }

  if ($x < count($map[0]) - 1 && $map[$y][$x + 1] === $elev + 1) {
    $tmp = move($y, $x + 1, $map);
    if ($tmp !== 0) {
      $result += $tmp;
    }
  }

  if ($y < count($map) - 1 && $map[$y + 1][$x] === $elev + 1) {
    $tmp = move($y + 1, $x, $map);
    if ($tmp !== 0) {
      $result += $tmp;
    }
  }

  if ($x > 0 && $map[$y][$x - 1] === $elev + 1) {
    $tmp = move($y, $x - 1, $map);
    if ($tmp !== 0) {
      $result += $tmp;
    }
  }

  return $result;
}

// function getPeakCount($y, $x, $map)
// {
//   $tmp = $map;
//   $peaks = 0;
//   while (true) {
//     $res = move($y, $x, $tmp);
//     if ($res === 0) {
//       return $peaks;
//     }

//     $peaks++;
//   }
// }

$map = getMap();
$trailheads = [];
foreach ($map as $y => $ln) {
  foreach ($ln as $x => $val) {
    if ($val === 0) {
      $trailheads[] = ['y' => $y, 'x' => $x];
    }
  }
}

$totalScore = 0;
foreach ($trailheads as $trailhead) {
  $totalScore += move($trailhead['y'], $trailhead['x'], $map);
}

echo $totalScore;
