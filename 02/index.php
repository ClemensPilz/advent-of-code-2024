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

function isSaveWithBuffer($report)
{
  for ($i = 0; $i < count($report); $i++) {
    $tmp = array_values($report);
    unset($tmp[$i]);
    $tmp = array_values($tmp);

    if (isSave($tmp)) {
      return true;
    }
  }

  return false;
}

$result = 0;
foreach ($reports as $report) {
  if (isSave($report)) {
    $result++;
    continue;
  }

  if (isSaveWithBuffer($report)) {
    $result++;
  }
}

echo $result;