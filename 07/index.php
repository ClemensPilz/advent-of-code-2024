<?php

function getInputArray(): array
{
    $buff = fopen('input.txt', 'r');
    $inputArray = [];
    while (false !== ($ln = fgets($buff))) {
        try {

            $parts = explode(': ', $ln);
            $target = intval($parts[0]);
            $values = array_map(function ($el) {
                return intval($el);
            }, explode(' ', $parts[1]));

            $inputArray[] = ['target' => $target, 'values' => $values];
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            continue;
        }
    }

    fclose($buff);
    return $inputArray;
}

function generateCombinations(array $operators, int $length): array
{
    if ($length === 0) {
        return [[]];
    }

    $combinations = [];
    foreach ($operators as $operator) {
        foreach (generateCombinations($operators, $length - 1) as $combination) {
            array_unshift($combination, $operator);
            $combinations[] = $combination;
        }
    }

    return $combinations;
}

function isValidInput($target, $numbersArray, &$combinations): bool
{
    $length = count($numbersArray) - 1;
    if (empty($combinations[$length])) {
        $combinations[$length] = generateCombinations(['+', '*', '||'], $length);
    }

    $combinationsToTry = $combinations[$length];
    foreach ($combinationsToTry as $comb) {
        $result = $numbersArray[0];
        foreach ($comb as $index => $op) {
            if ($op === '+') {
                $result = $result + $numbersArray[$index + 1];
            } else if ($op === '*') {
                $result = $result * $numbersArray[$index + 1];
            } else if ($op === '||') {
                $result = intval($result . $numbersArray[$index + 1]);
            }
        }
        if ($result === $target) {
            return true;
        }
    }
    return false;
}

$res = getInputArray();
$combinations = [];
$sum = 0;
foreach ($res as $row) {
    $sum += isValidInput($row['target'], $row['values'], $combinations) ?
        $row['target'] :
        0;
}
echo $sum;



