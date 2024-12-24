<?php

// todo: Just check the tiles that the guard visits on his first run... then detect loop if it takes longer than x turns...

class Guard
{
    public int $x;
    public int $y;
    public array $map;
    public string $direction = 'u';
    public array $visited = [];
    public array $turnHistory = [-1, -1, -1, -1, -1, -1, -1, -1];

    public function __construct($x, $y, $map)
    {
        $this->x = $x;
        $this->y = $y;
        $this->map = $map;
        $this->visited[$x . '|' . $y] = 1;
    }

    function move()
    {
        $tmpX = $this->x;
        $tmpY = $this->y;

        switch ($this->direction) {
            case 'u':
                $tmpY--;
                break;
            case 'r':
                $tmpX++;
                break;
            case 'd':
                $tmpY++;
                break;
            case 'l':
                $tmpX--;
                break;
        }
        if ($tmpX < 0 || $tmpY < 0 || $tmpX >= count($this->map[0]) || $tmpY >= count($this->map)) {
            return 'oob';
        }

        if ($this->map[$tmpY][$tmpX] !== '#') {
            $this->x = $tmpX;
            $this->y = $tmpY;
            $this->visited[$tmpX . '|' . $tmpY] = 1;
            return 'ok';
        }

        return 'forbidden';
    }

    function turn()
    {
        // $this->addToTurnHistory($this->x . '|' . $this->y);
        switch ($this->direction) {
            case 'u':
                $this->direction = 'r';
                break;
            case 'r':
                $this->direction = 'd';
                break;
            case 'd':
                $this->direction = 'l';
                break;
            case 'l':
                $this->direction = 'u';
                break;
        }
    }

    public function addToTurnHistory($value)
    {
        array_unshift($this->turnHistory, $value);
        array_pop($this->turnHistory);
        if (in_array(-1, $this->turnHistory)) {
            return;
        }

        $equals = 0;
        for ($i = 0; $i < 4; $i++) {
            if ($this->turnHistory[$i] === $this->turnHistory[$i + 4]) {
                $equals++;
            }
        }

        if ($equals === 4) {
            throw new Exception('Infinite Loop!');
        }
    }

}

$buffer = fopen('./map.txt', 'r');
$map = [];

while (false !== ($row = fgets($buffer))) {
    $map[] = str_split(trim($row));
}


function findGuard($map)
{
    foreach ($map as $y => $row) {
        foreach ($row as $x => $place) {
            if ($place !== '.' && $place !== '#') {
                return ['guard' => $place, 'x' => $x, 'y' => $y];
            }
        }
    }
    return false;
}


function main($map)
{
    try {

        $tmp = findGuard($map);
        $guard = new Guard($tmp['x'], $tmp['y'], $map);

        $i = 0;
        while ($i < 50000) {
            $res = $guard->move();
            if ($res === 'oob') {
                echo 'OOB after ' . count($guard->visited) . ' locations!' . PHP_EOL;
                return $guard->visited;
            }

            if ($res === 'forbidden') {
                $guard->turn();
            }
            $i++;
        }
    } catch (Exception $e) {
        echo $e->getMessage() .PHP_EOL;
        return 0;
    }
    return 0;
}


$history = main($map);

$locations = 0;
foreach ($history as $his => $val) {
    $x = intval(explode('|', $his)[0]);
    $y = intval(explode('|', $his)[1]);
    echo $x . ' // ' . $y . PHP_EOL;
    if ($x < 0 || $y < 0 || $y >= count($map) || $x >= count($map[0]) || $map[$y][$x] !== '.') {
        continue;
    }

    $tmp = $map;
    $tmp[$y][$x] = '#';
    $locations += (main($tmp) === 0 ? 1 : 0);
}


echo $locations;

// for each # check horizontally to the right if it has a #. Foreach of these, check if it has a hashtag below it.