<?php

include('common.php');

const INPUT_FILE = 'day1-input.txt';
const TARGET = 2020;

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1($data): ?int
{
    $rows = count($data);
    for ($i = 0; $i < $rows; $i++) {
        for ($j = $i; $j < $rows; $j++) {
            if ($data[$i] + $data[$j] === TARGET) {
                return $data[$i] * $data[$j];
            }
        }
    }

    return null;
}

function part2($data): ?int
{
    $rows = count($data);
    for ($i = 0; $i < $rows; $i++) {
        for ($j = $i; $j < $rows; $j++) {
            for ($k = $j; $k < $rows; $k++) {
                if ($data[$i] + $data[$j] + $data[$k] === TARGET) {
                    return $data[$i] * $data[$j] * $data[$k];
                }
            }
        }
    }

    return null;
}
