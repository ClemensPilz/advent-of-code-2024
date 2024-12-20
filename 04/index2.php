<?php

$buff = fopen("./input.txt", "r");

$map = [];
while (false !== ($line = fgets($buff))) {
    $line = rtrim($line);
    $map[] = str_split($line);
}


function checkX($map, $rowIndex, $colIndex)
{
    $axisCount = 0;
    $resLeftToRight = '';
    $resRightToLeft = '';

    $resLeftToRight .= !empty($map[$rowIndex - 1][$colIndex - 1]) ? $map[$rowIndex - 1][$colIndex - 1] : '';
    $resLeftToRight .= !empty($map[$rowIndex + 1][$colIndex + 1]) ? $map[$rowIndex + 1][$colIndex + 1] : '';
    $resRightToLeft .= !empty($map[$rowIndex - 1][$colIndex + 1]) ? $map[$rowIndex - 1][$colIndex + 1] : '';
    $resRightToLeft .= !empty($map[$rowIndex + 1][$colIndex - 1]) ? $map[$rowIndex + 1][$colIndex - 1] : '';

    if ($resLeftToRight == 'MS' || $resLeftToRight == 'SM') {
        $axisCount++;
    }

    if ($resRightToLeft == 'MS' || $resRightToLeft == 'SM') {
        $axisCount++;
    }

    return $axisCount == 2 ? 1 : 0;

}

$total = 0;
foreach ($map as $rowIndex => $row) {
    foreach ($row as $colIndex => $char) {
        if ($char == 'A') {
            $total += checkX($map, $rowIndex, $colIndex);
        }
    }
}


echo $total;
