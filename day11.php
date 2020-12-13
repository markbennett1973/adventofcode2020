<?php

include('common.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day11-input.txt';

$data = getInput(INPUT_FILE);
$map = buildMap($data);

print 'Part 1: ' . (part1($map) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($map) ?? 'No answer') . "\n";

function part1(array $map): ?int
{
    $moved = -1;
    while ($moved !== 0) {
        $moved = updateMap($map);
    }

    return countOccupiedSeats($map);
}

function part2(array $map): ?int
{
    $moved = -1;
    while ($moved !== 0) {
        $moved = updateMap2($map);
    }

    return countOccupiedSeats($map);
}

function buildMap(array $data): array
{
    $map = [];
    foreach ($data as $row=> $rowData) {
        for ($col = 0; $col < strlen($rowData); $col++) {
            $map[$row][$col] = $rowData[$col];
        }
    }

    return $map;
}

function updateMap(array &$map): int
{
    $moved = 0;
    $oldMap = arrayClone($map);
    foreach ($oldMap as $row => $rowData) {
        foreach ($rowData as $col => $cell) {
            switch ($cell) {
                case 'L':
                    if (countAdjacentSeats($oldMap, $row, $col) === 0) {
                        $map[$row][$col] = '#';
                        $moved++;
                    }
                    break;

                case '#':
                    if (countAdjacentSeats($oldMap, $row, $col) >= 4) {
                        $map[$row][$col] = 'L';
                        $moved++;
                    }
                    break;
            }
        }
    }

    return $moved;
}

function updateMap2(array &$map): int
{
    $moved = 0;
    $oldMap = arrayClone($map);
    foreach ($oldMap as $row => $rowData) {
        foreach ($rowData as $col => $cell) {
            switch ($cell) {
                case 'L':
                    if (countVisibleSeats($oldMap, $row, $col) === 0) {
                        $map[$row][$col] = '#';
                        $moved++;
                    }
                    break;

                case '#':
                    if (countVisibleSeats($oldMap, $row, $col) >= 5) {
                        $map[$row][$col] = 'L';
                        $moved++;
                    }
                    break;
            }
        }
    }

    return $moved;
}

function countAdjacentSeats(array $map, int $targetRow, int $targetCol): int
{
    $occupied = 0;
    for ($row = $targetRow - 1; $row <= $targetRow + 1; $row++) {
        for ($col = $targetCol - 1; $col <= $targetCol + 1; $col++) {
            if (!array_key_exists($row, $map)) {
                continue;
            }

            if (!array_key_exists($col, $map[$row])) {
                continue;
            }

            if ($row === $targetRow && $col === $targetCol) {
                continue;
            }

            if ($map[$row][$col] === '#') {
                $occupied++;
            }
        }
    }

    return $occupied;
}

function countVisibleSeats(array $map, int $targetRow, int $targetCol): int
{
    $occupied = 0;

    // Check in all 8 directions
    if (isVisibleSeatOccupied($map, $targetRow, $targetCol, -1, -1)) $occupied++;
    if (isVisibleSeatOccupied($map, $targetRow, $targetCol, -1, 0)) $occupied++;
    if (isVisibleSeatOccupied($map, $targetRow, $targetCol, -1, 1)) $occupied++;
    if (isVisibleSeatOccupied($map, $targetRow, $targetCol, 0, -1)) $occupied++;
    if (isVisibleSeatOccupied($map, $targetRow, $targetCol, 0, 1)) $occupied++;
    if (isVisibleSeatOccupied($map, $targetRow, $targetCol, 1, -1)) $occupied++;
    if (isVisibleSeatOccupied($map, $targetRow, $targetCol, 1, 0)) $occupied++;
    if (isVisibleSeatOccupied($map, $targetRow, $targetCol, 1, 1)) $occupied++;

    return $occupied;
}

/**
 * Is the first visible seat from an origin taking specified step directions occupied?
 * @param array $map
 * @param int $startRow
 * @param int $startCol
 * @param int $rowStep
 * @param int $colStep
 * @return bool
 */
function isVisibleSeatOccupied(array $map, int $startRow, int $startCol, int $rowStep, int $colStep): bool
{
    $row = $startRow;
    $col = $startCol;

    while (true) {
        $row = $row + $rowStep;
        $col = $col + $colStep;

        // if we've gone off the edge of the map, then quit - we didn't find anything
        if (!array_key_exists($row, $map) || !array_key_exists($col, $map[$row])) {
            return false;
        }

        // Skip if we found an empty space
        if ($map[$row][$col] === '.') {
            continue;
        }

        return $map[$row][$col] === '#';
    }
}

function countOccupiedSeats(array $map): int
{
    $occupied = 0;
    foreach ($map as $row) {
        foreach ($row as $col => $cell) {
            if ($cell === '#') {
                $occupied++;
            }
        }
    }

    return $occupied;
}

/**
 * @see https://stackoverflow.com/a/17729234/108756
 * @param $array
 * @return array|array[]
 */
function arrayClone($array): array
{
    return array_map(function ($element) {
        return ((is_array($element))
            ? arrayClone($element)
            : ((is_object($element))
                ? clone $element
                : $element
            )
        );
    }, $array);
}
