<?php

$buff = fopen("./input.txt", "r");

function getHorizontalCount($line)
{
    $xmasArr = explode('XMAS', $line);
    $xmasCount = $xmasArr ? count($xmasArr) - 1 : 0;

    $samxArr = explode('SAMX', $line);
    $samxCount = $samxArr ? count($samxArr) - 1 : 0;

    return $xmasCount + $samxCount;
}

function getDiagonalRightCount($map, $targets)
{
    $wordCount = 0;
    foreach ($map as $lineIndex => $line) {
        foreach ($line as $colIndex => $char) {
            if ($char === $targets[0]) {

                $i = 0;
                $correctPositions = 0;
                while ($i < count($targets)) {
                    if (!empty($map[$lineIndex + $i][$colIndex + $i]) &&
                        $map[$lineIndex + $i][$colIndex + $i] == $targets[$i]) {
                        $correctPositions++;
                    }
                    $i++;
                }

                if ($correctPositions == count($targets)) {
                    $wordCount++;
                }
            }
        }
    }

    return $wordCount;
}

function getDiagonalLeftCount($map, $targets)
{
    $wordCount = 0;
    foreach ($map as $lineIndex => $line) {
        foreach ($line as $colIndex => $char) {
            if ($char === $targets[0]) {

                $i = 0;
                $correctPositions = 0;
                while ($i < count($targets)) {
                    if (!empty($map[$lineIndex + $i][$colIndex - $i]) &&
                        $map[$lineIndex + $i][$colIndex - $i] == $targets[$i]) {
                        $correctPositions++;
                    }
                    $i++;
                }

                if ($correctPositions == count($targets)) {
                    $wordCount++;
                }
            }
        }
    }

    return $wordCount;
}

function getVerticalCount($map, $targets)
{
    $wordCount = 0;
    foreach ($map as $lineIndex => $line) {
        foreach ($line as $colIndex => $char) {
            if ($char == $targets[0]) {
                $i = 0;
                $correctPositions = 0;
                while ($i < count($targets)) {
                    if (!empty($map[$lineIndex + $i][$colIndex]) &&
                        $map[$lineIndex + $i][$colIndex] == $targets[$i]) {
                        $correctPositions++;
                    }
                    $i++;
                }

                if ($correctPositions == count($targets)) {
                    $wordCount++;
                }
            }
        }
    }

    return $wordCount;
}

$total = 0;
$map = [];
while (false !== ($line = fgets($buff))) {
    $line = rtrim($line);
    $total += getHorizontalCount($line);

    $map[] = str_split($line);
}

$total += getDiagonalRightCount($map, str_split('XMAS'));
$total += getDiagonalLeftCount($map, str_split('XMAS'));
$total += getDiagonalRightCount($map, str_split('SAMX'));
$total += getDiagonalLeftCount($map, str_split('SAMX'));
$total += getVerticalCount($map, str_split('XMAS'));
$total += getVerticalCount($map, str_split('SAMX'));


echo $total;
