<?php

// for each order check each number against the rules array

$rulesMap = [];
$buffer = fopen('./rules.txt', 'r');
while (false !== ($rule = fgets($buffer))) {
    $rules = array_map(function ($el) {return intval($el);}, explode('|', trim($rule)));
    if (count($rules) !== 2) {
        echo 'invalid count: ' . $rule;
        continue;
    }
    $rulesMap[$rules[0]][] = $rules[1];
}
fclose($buffer);

$ordersArray = [];
$buffer = fopen('./orders.txt', 'r');
while (false !== ($order = fgets($buffer))) {
    $orders = array_map(function ($el) { return intval($el);}, explode(',', $order));
    $ordersArray[] = $orders;
    if (count($orders) % 2 === 0) {
        echo 'attention: ' . json_encode($orders);
    }
}
fclose($buffer);

function getMiddleNumber($order) {
    return $order[floor(count($order) / 2)];
}

function isValidOrder($order, $rulesMap) {
    foreach ($order as $index => $number) {
        if ($index === 0) {
            continue;
        }

        // there is a rule for this number
        if (!empty($rulesMap[$number])) {
            for($i = 0; $i < $index; $i++ ) {
                // a forbidden number was found
                if (in_array($order[$i], $rulesMap[$number])) {
                    return false;
                }
            }
        }
    }

    return true;
}

$invalidOrders = [];
$output= 0;
foreach ($ordersArray as $order) {
       if (isValidOrder($order, $rulesMap)) {
           $output += getMiddleNumber($order);
       } else {
           $invalidOrders[] = $order;
       }
}

function getInsertOffset($arr, $number, $rulesMap) {
    foreach ($arr as $index => $num) {
        if (in_array($num, $rulesMap[$number])) {
            return $index;
        }
    }
    return count($arr);
}

function fixOrder($order, $rulesMap) {
    $fixed = [];
    foreach($order as $index => $el) {
        if ($index === 0) {
            $fixed[] = $el;
            continue;
        }
        array_splice($fixed, getInsertOffset($fixed, $el, $rulesMap), 0,$el);
    }

    return $fixed;
}


$output2 = 0;
foreach ($invalidOrders as $order) {
    echo "Original: " . json_encode($order) . "\n";
    $fixedOrder = fixOrder($order, $rulesMap);
    echo "Fixed: " . json_encode($fixedOrder) . "\n";
    echo isValidOrder($fixedOrder, $rulesMap) ? "Valid\n" : "Still Invalid\n";

    $output2 += getMiddleNumber($fixedOrder);
}

echo 'OUTPUT2: ' . $output2;

