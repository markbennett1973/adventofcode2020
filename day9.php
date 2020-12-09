<?php

include('common.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day9-input.txt';

const PREAMBLE_LENGTH = 25;

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data, part1($data)) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $position = PREAMBLE_LENGTH;
    while (true) {
        if (!isNumberValid(array_slice($data, $position - PREAMBLE_LENGTH, PREAMBLE_LENGTH), $data[$position])) {
            return $data[$position];
        }

        $position++;
    }
}

function part2(array $data, int $target): ?int
{
    $max = count($data) - 1;
    for ($start = 0; $start <= $max; $start++) {
        for ($end = $start + 1; $end <= $max; $end++) {
            $range = array_slice($data, $start, $end - $start);
            $sum = array_sum($range);
            if ($sum === $target) {
                return getRangeSum($range);
            }

            if ($sum > $target) {
                break;
            }
        }
    }

    return null;
}

function isNumberValid(array $range, int $target): bool
{
    for ($i = 0; $i < PREAMBLE_LENGTH; $i++) {
        for ($j = $i + 1; $j < PREAMBLE_LENGTH; $j++) {
            if ($range[$i] + $range[$j] === $target) {
                return true;
            }
        }
    }

    return false;
}

function getRangeSum(array $range): int
{
    sort($range);
    return reset($range) + end($range);
}
