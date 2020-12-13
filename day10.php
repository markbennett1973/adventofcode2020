<?php

include('common.php');

const INPUT_FILE = 'test.txt';
// const INPUT_FILE = 'day10-input.txt';

$data = buildData(getInput(INPUT_FILE));

print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $diffs = getDiffs($data);
    return $diffs[1] * $diffs[3];
}

/**
 * Works well for test cases, but too inefficient for actual problem
 * @param array $data
 * @return int
 */
function part2(array $data): ?int
{
    // Initialise with the first joltage
    $chains = [reset($data)];

    while (true) {
        $added = 0;
        foreach ($chains as $index => $value) {
            $nextValues = getNextValues($data, $value);
            $added += count($nextValues);
            if (count($nextValues) === 0) {
                continue;
            }

            $chains[$index] = array_shift($nextValues);
            $chains = array_merge($chains, $nextValues);
        }

        if ($added === 0) {
            return count($chains);
        }
    }
}

function getDiffs(array $data): array
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

    return $diffs;
}

function getNextValues(array $data, int $value): array
{
    $nextValues = [];
    foreach ($data as $item) {
        if ($item > $value && $item <= $value + 3) {
            $nextValues[] = $item;
        }

        if ($item > $value + 3) {
            return $nextValues;
        }
    }

    return $nextValues;
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
