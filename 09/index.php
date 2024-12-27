<?php
function getDisk(): array
{
    $buff = fopen('./diskmap.txt', 'r');

    $ln = fgets($buff);
    fclose($buff);

    $arr = array_map(fn($el) => intval($el), str_split($ln));

    $map = [];
    for ($i = 0; $i < count($arr); $i++) {
        $val = $arr[$i];
        $isFile = ($i % 2 !== 0);

        for ($j = 0; $j < $val; $j++) {
            if ($isFile) {
                $map[] = '.';
            } else {
                $map[] = $i / 2;
            }
        }
    }
    return $map;
}

function findLeftmostFreeSpaceIndex(array &$disk, int $length) {

    $tmp = 0;
    for ($i = 0; $i < count($disk); $i++) {
        if ($disk[$i] === '.') {
            $tmp ++;
        } else {
            $tmp = 0;
        }
        if ($tmp === $length) {
            return $i - $length +1; // 
        }
    }

    return -1;
}

function getFileSize(array &$disk, int $endIndex) 
{
    $val = $disk[$endIndex];
    for ($i = $endIndex; $i > -1; $i--) {
        if ($disk[$i] !== $val) {
            return $endIndex - $i;
        }
    }

    return -1;
}


// if index of the . is larger than the index you are at...

// function defrag(array $disk, int $lastDotIndex, int $lastFileIndex): array
// {

//     $dotTestIndex = $lastDotIndex + 1;
//     $dotIndex = null;
//     while (!$dotIndex) {
//         $val = $disk[$dotTestIndex];
//         if ($val === '.') {
//             $dotIndex = $dotTestIndex;
//         } else {
//             $dotTestIndex++;
//         }
//     }

//     $fileTestIndex = $lastFileIndex - 1;
//     $fileIndex = null;
//     while (!$fileIndex) {
//         $val = $disk[$fileTestIndex];
//         if ($val === '.') {
//             $fileTestIndex--;
//         } else {
//             $fileIndex = $fileTestIndex;
//         }
//     }

//     if ($fileIndex < $dotIndex) {
//         return ['result' => $disk];
//     }

//     $dots = 1;
//     $dotting = true;
//     while ($dotting) {
//         if ($disk[$dotIndex + $dots] === '.') {
//             $dots++;
//         } else {
//             $dotting = false;
//         }
//     }

//     $tmp = $disk[$fileIndex];
//     $files = 1;
//     $filing = true;
//     while ($filing) {
//         if ($disk[$fileIndex - $files] === $tmp) {
//             $files++;
//         } else {
//             $filing = false;
//         }
//     }

//     if ($dots < $files) {
//         return [$disk, $lastDotIndex, $fileIndex - $files + 1];
//     }

//     for ($i = 0; $i < $files; $i++) {
//         $disk[$dotIndex + $i] = $tmp;
//         $disk[$fileIndex - $i] = '.';
//     }

//     $disk = array_values($disk);

//     return [$disk, $dotIndex + $dots -1, $fileIndex - $files +1];
// }

function defrag(&$disk, $endIndex = null)
{
    
    if ($endIndex === null) {
        $endIndex = count($disk) -1;
    }

    $fileEndIndex = null;
    for ($i = $endIndex; $i > -1; $i--) {
        if ($disk[$i] !== '.') {
            $fileEndIndex = $i;
            break;
        }
    }

    if($fileEndIndex === null) {
        return -1;
    }

    $fileSize = getFileSize($disk, $fileEndIndex);
    if ($fileSize === -1) {
        return -1;
    }

    $dotStartIndex = findLeftmostFreeSpaceIndex($disk, $fileSize);

    if ($fileEndIndex - $fileSize <= 0) {
        return -1;
    }

    if ($dotStartIndex === -1 || $dotStartIndex > $fileEndIndex) {
        return $fileEndIndex - $fileSize;
    }

    // add values to leftmost free space and remove from original location
    $val = $disk[$fileEndIndex];
    for ($i = 0; $i < $fileSize; $i ++) {
        $disk[$dotStartIndex + $i] = $val;
        $disk[$fileEndIndex - $i] = '.';
    }

    $disk = array_values($disk);

    return $fileEndIndex - $fileSize;
}

$disk = getDisk();
$endIndex = defrag($disk);
// $endIndex = 200;

while($endIndex  > 1) 
{
    $endIndex = defrag($disk, $endIndex);
    echo $endIndex . PHP_EOL;
}

$checksum = 0;
$i = 0;
$multiplier = 0;

while ($i < count($disk)) {
    $val = $disk[$i];
    if ($val === '.') {
        $i++;
        continue;
    } else {
        $checksum += ($i* $val);
        $i++;
        $multiplier++;
    }
}

echo PHP_EOL . 'checksum: ' . $checksum . PHP_EOL;