<?php

include('common.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day17-input.txt';

const CYCLES = 6;

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $space = initialiseSpace($data);

    for ($i = 0; $i < CYCLES; $i++) {
        // printSpace($space);
        $space = runCycle($space);
    }

    return countActiveCubes($space);
}

function part2(array $data): ?int
{
    $space = initialiseSpace4($data);

    for ($i = 0; $i < CYCLES; $i++) {
        $space = runCycle4($space);
    }

    return countActiveCubes4($space);
}

function initialiseSpace(array $data): array
{
    $space = [];
    $columns = strlen($data[0]);
    foreach ($data as $row => $rowData) {
        for ($col = 0; $col < $columns; $col++) {
            $space[$row][$col][0] = ($rowData[$col] === '#');
        }
    }

    return $space;
}

function runCycle(array $space): array
{
    $newSpace = expandSpace($space);
    foreach ($newSpace as $x => $xData) {
        foreach ($xData as $y => $yData) {
            foreach ($yData as $z => $active) {
                $newState  = isCellActive($space, $x, $y, $z);
                $newSpace[$x][$y][$z] = $newState;
            }
        }
    }

    return $newSpace;
}

function expandSpace(array $space): array
{
    $newSpace = [];

    $xKeys = array_keys($space);
    $yKeys = array_keys($space[0]);
    $zKeys = array_keys($space[0][0]);

    for ($x = min($xKeys) - 1; $x <= max($xKeys) + 1; $x++) {
        for ($y = min($yKeys) - 1; $y <= max($yKeys) + 1; $y++) {
            for ($z = min($zKeys) - 1; $z <= max($zKeys) + 1; $z++) {
                $newSpace[$x][$y][$z] = getCellState($space, $x, $y, $z);
            }
        }
    }

    return $newSpace;
}

function getCellState(array $space, int $x, int $y, int $z): bool
{
    if (array_key_exists($x, $space)) {
        if (array_key_exists($y, $space[$x])) {
            if (array_key_exists($z, $space[$x][$y])) {
                return $space[$x][$y][$z];
            }
        }
    }

    return false;
}

function isCellActive(array $space, int $cellX, int $cellY, int $cellZ): bool
{
    $cellState = getCellState($space, $cellX, $cellY, $cellZ);

    $activeNeighbours = 0;
    for ($x = $cellX - 1; $x <= $cellX + 1; $x++) {
        for ($y = $cellY - 1; $y <= $cellY + 1; $y++) {
            for ($z = $cellZ - 1; $z <= $cellZ + 1; $z++) {
                // Skip the current cell
                if ($x === $cellX && $y === $cellY && $z === $cellZ) {
                    continue;
                }

                if (getCellState($space, $x, $y, $z)) {
                    $activeNeighbours++;
                }
            }
        }
    }

    if (($cellState === true) && ($activeNeighbours === 2 || $activeNeighbours === 3)) {
            return true;
    }

    if (($cellState === false) && ($activeNeighbours === 3)) {
        return true;
    }

    return false;
}

function countActiveCubes(array $space): int
{
    $active = 0;
    foreach ($space as $row) {
        foreach ($row as $col) {
            foreach ($col as $cell) {
                if ($cell) {
                    $active++;
                }
            }
        }
    }

    return $active;
}

function printSpace(array $space)
{
    $maps = [];

    $xKeys = array_keys($space);
    $yKeys = array_keys($space[0]);
    $zKeys = array_keys($space[0][0]);

    for ($x = min($xKeys); $x <= max($xKeys); $x++) {
        for ($y = min($yKeys); $y <= max($yKeys); $y++) {
            for ($z = min($zKeys); $z <= max($zKeys); $z++) {
                $state = getCellState($space, $x, $y, $z);
                if (!array_key_exists($z, $maps)) {
                    $maps[$z] = '';
                }

                $maps[$z] .= $state ? '#' : '.';
            }
        }
        foreach ($maps as $z => $map) {
            $maps[$z] .= "\n";
        }
    }

    foreach ($maps as $z => $map) {
        print "z = $z:\n$map\n\n";
    }
}

function initialiseSpace4(array $data): array
{
    $space = [];
    $columns = strlen($data[0]);
    foreach ($data as $row => $rowData) {
        for ($col = 0; $col < $columns; $col++) {
            $space[$row][$col][0][0] = ($rowData[$col] === '#');
        }
    }

    return $space;
}

function runCycle4(array $space): array
{
    $newSpace = expandSpace4($space);
    foreach ($newSpace as $x => $xData) {
        foreach ($xData as $y => $yData) {
            foreach ($yData as $z => $zData) {
                foreach ($zData as $w => $active) {
                    $newState = isCellActive4($space, $x, $y, $z, $w);
                    $newSpace[$x][$y][$z][$w] = $newState;
                }
            }
        }
    }

    return $newSpace;
}

function expandSpace4(array $space): array
{
    $newSpace = [];

    $xKeys = array_keys($space);
    $yKeys = array_keys($space[0]);
    $zKeys = array_keys($space[0][0]);
    $wKeys = array_keys($space[0][0][0]);

    for ($x = min($xKeys) - 1; $x <= max($xKeys) + 1; $x++) {
        for ($y = min($yKeys) - 1; $y <= max($yKeys) + 1; $y++) {
            for ($z = min($zKeys) - 1; $z <= max($zKeys) + 1; $z++) {
                for ($w = min($wKeys) - 1; $w <= max($wKeys) + 1; $w++) {
                    $newSpace[$x][$y][$z][$w] = getCellState4($space, $x, $y, $z, $w);
                }
            }
        }
    }

    return $newSpace;
}

function getCellState4(array $space, int $x, int $y, int $z, int $w): bool
{
    if (array_key_exists($x, $space)) {
        if (array_key_exists($y, $space[$x])) {
            if (array_key_exists($z, $space[$x][$y])) {
                if (array_key_exists($w, $space[$x][$y][$z])) {
                    return $space[$x][$y][$z][$w];
                }
            }
        }
    }

    return false;
}

function isCellActive4(array $space, int $cellX, int $cellY, int $cellZ, int $cellW): bool
{
    $cellState = getCellState4($space, $cellX, $cellY, $cellZ, $cellW);

    $activeNeighbours = 0;
    for ($x = $cellX - 1; $x <= $cellX + 1; $x++) {
        for ($y = $cellY - 1; $y <= $cellY + 1; $y++) {
            for ($z = $cellZ - 1; $z <= $cellZ + 1; $z++) {
                for ($w = $cellW - 1; $w <= $cellW + 1; $w++) {
                    // Skip the current cell
                    if ($x === $cellX && $y === $cellY && $z === $cellZ && $w === $cellW) {
                        continue;
                    }

                    if (getCellState4($space, $x, $y, $z, $w)) {
                        $activeNeighbours++;
                    }
                }
            }
        }
    }

    if (($cellState === true) && ($activeNeighbours === 2 || $activeNeighbours === 3)) {
        return true;
    }

    if (($cellState === false) && ($activeNeighbours === 3)) {
        return true;
    }

    return false;
}

function countActiveCubes4(array $space): int
{
    $active = 0;
    foreach ($space as $xData) {
        foreach ($xData as $yData) {
            foreach ($yData as $zData) {
                foreach ($zData as $cell) {
                    if ($cell) {
                        $active++;
                    }
                }
            }
        }
    }

    return $active;
}
