<?php

// for each pair of the same symbols find the antinodes

//find antinodes: get x and y deviation of the nodes and apply / reverse
// check if oob

function getMap()
{
    $buff = fopen('map.txt', 'r');
    $map = [];
    while (false !== ($row = fgets($buff))) {
        $map[] = str_split(rtrim($row));
    }
    return $map;
}

function getAntiNodes($ya, $xa, $yb, $xb, $map)
{

    $yOffset = $ya - $yb;
    $xOffset = $xa - $xb;

    if ($yOffset === 0) {
        $xOffset = 1;
    } else if ($xOffset === 0) {
        $yOffset = 1;
    } else if ($yOffset % $xOffset === 0) {
        $gcd = gmp_gcd($yOffset, $xOffset);

        $yOffset = $yOffset / $gcd;
        $xOffset = $xOffset / $gcd;

    }


    $antiNodes = [];

    $inBounds = true;
    $i = 0;
    while ($inBounds) {
        $antiNode1 = [$yb - $yOffset * $i, $xb - $xOffset * $i];
        $inBounds = !isOutOfBounds($antiNode1[0], $antiNode1[1], $map);
        if ($inBounds) {
            $antiNodes[] = $antiNode1;
            $i++;
        }

    }

    $inBounds = true;
    $i = 0;
    while ($inBounds) {
        $antiNode1 = [$yb + $yOffset * $i, $xb + $xOffset * $i];
        $inBounds = !isOutOfBounds($antiNode1[0], $antiNode1[1], $map);
        if ($inBounds) {
            $antiNodes[] = $antiNode1;
            $i++;
        }
    }

    return $antiNodes;
}

function isOutOfBounds($y, $x, $map)
{
    return $y >= count($map) || $x >= count($map[0]) || $y < 0 || $x < 0;
}

function getNodeList($map)
{
    $nodeList = [];

    foreach ($map as $y => $row) {
        foreach ($row as $x => $char) {
            if ($char === '.') {
                continue;
            }
            $nodeList[$char][] = [$y, $x];
        }
    }

    return $nodeList;
}

function getAntiNodeList($nodeList, $map)
{
    $antiNodeList = [];
    foreach ($nodeList as $char => $nodes) {
        foreach ($nodes as $index => $node) {
            for ($i = $index + 1; $i < count($nodes); $i++) {
                $antiNodes = getAntiNodes($node[0], $node[1], $nodes[$i][0], $nodes[$i][1], $map);

                foreach ($antiNodes as $antiNode) {
                    if (isOutOfBounds($antiNode[0], $antiNode[1], $map)) {
                        continue;
                    }

                    $antiNodeList[] = $antiNode;
                }
            }
        }
    }

    return $antiNodeList;
}

$map = getMap();
$nodeList = getNodeList($map);
$antiNodeList = getAntiNodeList($nodeList, $map);

$uniqueLocations = [];

foreach ($antiNodeList as $antiNode) {
    if (in_array($antiNode[0] . '|' . $antiNode[1], $uniqueLocations)) {
        continue;
    }

    $uniqueLocations[] = $antiNode[0] . '|' . $antiNode[1];
}

echo count($uniqueLocations);
