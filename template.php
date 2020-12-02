<?php

include('common.php');

const INPUT_FILE = 'test.txt';

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    return null;
}

function part2(array $data): ?int
{
    return null;
}
