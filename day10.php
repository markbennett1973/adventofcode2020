<?php

include('common.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day10-input.txt';

$data = buildData(getInput(INPUT_FILE));

print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $diffs = [];

    $count = count($data);
    for ($i = 1; $i < $count; $i++) {
        $diff = $data[$i] - $data[$i - 1];
        if (array_key_exists($diff, $diffs)) {
            $diffs[$diff]++;
        } else {
            $diffs[$diff] = 1;
        }
    }

    return $diffs[1] * $diffs[3];
}

function part2(array $data): ?int
{
    return null;
}


function buildData(array $data): array
{
    $data = array_map(function ($a) {
        return (int) $a;
    }, $data);

    // Add zero for the starting joltage
    $data[] = 0;
    sort($data);
    $max = end($data);
    // add device at 3 more than the max joltage
    $data[] = $max + 3;

    return $data;
}