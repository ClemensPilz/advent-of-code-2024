<?php
$stones = array_map(fn($el) => intval($el), explode(' ', '70949 6183 4 3825336 613971 0 15 182'));

function ruleOne($index, &$stones)
{
  $stones[$index] = 1;
}
function ruleTwo($index, &$stones)
{
  $val = "$stones[$index]";
  $length = strlen($val);
  $newStones = [intval(str_split($val, $length / 2)[0]), intval(str_split($val, $length / 2)[1])];
  array_splice($stones, $index, 1, $newStones);
  $stones = array_values($stones);
  // keep in mind that the indexes changed if you iterate through stones!!
}

function ruleThree($index, &$stones)
{
  $stones[$index] = $stones[$index] * 2024;
}


function blink(&$stones)
{

  for ($i = 0; $i < count($stones); $i++) {
    if ($stones[$i] === 0) {
      ruleOne($i, $stones);
      continue;
    }
    if (strlen("$stones[$i]") % 2 === 0) {
      ruleTwo($i, $stones);
      $i++;
      continue;
    }

    ruleThree($i, $stones);
  }
}

$stones = [2680];

for ($i = 0; $i < 75; $i++) {
  echo $i . ' -> ' . count($stones) . PHP_EOL;
  blink($stones);
}

echo 'length: ' . count($stones);
