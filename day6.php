<?php

include('common.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day6-input.txt';

$data = getInput(INPUT_FILE, false);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $groupAnswers = '';
    $total = 0;
    foreach ($data as $row) {
        if ($row === '') {
            $total += countDistinctLetters($groupAnswers);
            $groupAnswers = '';
        } else {
            $groupAnswers .= $row;
        }
    }

    return $total;
}

function part2(array $data): ?int
{
    $groupAnswers = [];
    $total = 0;
    foreach ($data as $row) {
        if ($row === '') {
            $total += countCommonLetters($groupAnswers);
            $groupAnswers = [];
        } else {
            $groupAnswers[] = $row;
        }
    }

    return $total;
}

function countDistinctLetters(string $data): int
{
    $letters = [];
    $count = strlen($data);
    for ($i = 0; $i < $count; $i++)
    {
        $letters[$data[$i]] = '';
    }

    return count($letters);
}

function countCommonLetters(array $answers): int
{
    $common = 0;
    $count = strlen($answers[0]);
    for ($i = 0; $i < $count; $i++) {
        $target = $answers[0][$i];

        foreach ($answers as $answer) {
            if (strpos($answer, $target) === false) {
                continue 2;
            }
        }

        $common++;
    }

    return $common;
}
