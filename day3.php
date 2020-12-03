<?php

include('common.php');

const INPUT_FILE = 'day3-input.txt';

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $collisions = 0;
    foreach ($data as $row => $rowTemplate) {
        $currentColumn = $row * 3;
        if (isCollision($rowTemplate, $currentColumn)) {
            $collisions++;
        }
    }

    return $collisions;
}

function part2(array $data): ?int
{
    $paths = [
        [1, 1],
        [3, 1],
        [5, 1],
        [7, 1],
        [1, 2],
    ];

    $product = 1;
    foreach ($paths as $path) {
        $collisions = 0;
        foreach ($data as $row => $rowTemplate) {
            // some paths don't hit every row
            if ($row % $path[1] === 0) {
                $currentColumn = calculateColumn($path, $row);
                if (isCollision($rowTemplate, $currentColumn)) {
                    $collisions++;
                }
            }
        }
        $product = $product * $collisions;
    }

    return $product;
}

function calculateColumn(array $path, int $row): int
{
    return ($row / $path[1]) * $path[0];
}

function isCollision(string $row, int $currentColumn): bool
{
    // Keep doubling the row until it's long enough to check the current column
    while (strlen($row) < $currentColumn + 1) {
        $row = $row . $row;
    }

    if ($row[$currentColumn] === '#') {
        return true;
    }

    return false;
}
