<?php

include('common.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day18-input.txt';

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $total = 0;
    foreach ($data as $index => $row) {
        $total += evaluateSum($row, true);
    }

    return $total;
}

function part2(array $data): ?int
{
    $total = 0;
    foreach ($data as $index => $row) {
        $total += evaluateSum($row, false);
    }

    return $total;
}

function evaluateSum(string $sum, bool $basic): int
{
    // Get rid of the spaces
    $sum = str_replace(' ', '', $sum);

    // evaluate contents of brackets - find any open brackets with matching close brackets
    // and repeat until all brackets are gone
    while (strpos($sum, '(') !== false) {
        preg_match_all('/\([\d+*]+\)/', $sum, $matches);
        foreach ($matches[0] as $match) {
            $sum = str_replace($match, evaluatePart($match, $basic), $sum);
        }
    }

    return evaluatePart($sum, $basic);
}

function evaluatePart(string $sum, bool $basic): int
{
    return $basic ? evaluatePartBasic($sum) : evaluatePartAdvanced($sum);
}

function evaluatePartBasic(string $sum): int
{
    $sum = str_replace(['(', ')'], ['', ''], $sum);
    while (preg_match('/[+*]/', $sum)) {
        preg_match('/^([\d]+)([+*])([\d]+)/', $sum, $matches);
        if ($matches[2] === '+') {
            $newValue = $matches[1] + $matches[3];
        } else {
            $newValue = $matches[1] * $matches[3];
        }

        // Use preg_replace to only replace the first occurrence
        $from = '/'.preg_quote($matches[0], '/').'/';
        $sum = preg_replace($from, $newValue, $sum, 1);
    }

    return $sum;
}

function evaluatePartAdvanced(string $sum): int
{
    $sum = str_replace(['(', ')'], ['', ''], $sum);

    // evaluate additions
    while (strpos($sum, '+') !== false) {
        preg_match('/([\d]+)([+])([\d]+)/', $sum, $matches);
        $newValue = $matches[1] + $matches[3];

        // Use preg_replace to only replace the first occurrence
        $from = '/'.preg_quote($matches[0], '/').'/';
        $sum = preg_replace($from, $newValue, $sum, 1);
    }

    // repeat for multiplication
    while (strpos($sum, '*') !== false) {
        preg_match('/([\d]+)([*])([\d]+)/', $sum, $matches);
        $newValue = $matches[1] * $matches[3];

        // Use preg_replace to only replace the first occurrence
        $from = '/'.preg_quote($matches[0], '/').'/';
        $sum = preg_replace($from, $newValue, $sum, 1);
    }

    return $sum;
}
