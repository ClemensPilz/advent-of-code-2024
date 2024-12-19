<?php

$file = fopen('./input.txt', 'r');

$reports = [];
$line = true;

while ($line) {
  $line = fgets($file);
  if (!$line) {
    break;
  }

  $reports[] = explode(' ', rtrim($line));
}

function isSave($report)
{
  $direction = 0;
  $tmp = null;

  foreach ($report as $level) {
    if ($tmp) {

      if (abs($tmp - $level) > 3 || abs($tmp - $level) < 1) {
        return false;
      }

      if ($direction === 0) {
        $direction = $tmp > $level ? -1 : 1;
      }

      $currentDirection = $tmp > $level ? -1 : 1;

      if ($direction !== $currentDirection) {
        return false;
      }
    }

    $tmp = $level;
  }

  return true;
}

$result = 0;
foreach ($reports as $report) {
  if (isSave($report)) {
    $result++;
  }
}

echo $result;