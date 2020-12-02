<?php

include('common.php');

const INPUT_FILE = 'day2-input.txt';

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $valid = 0;
    foreach ($data as $row) {
        $parts = explode(':', $row);
        $rule = new Rule($parts[0]);
        if ($rule->isPasswordValidPart1($parts[1])) {
            $valid++;
        }
    }

    return $valid;
}

function part2(array $data): ?int
{
    $valid = 0;
    foreach ($data as $row) {
        $parts = explode(':', $row);
        $rule = new Rule($parts[0]);
        if ($rule->isPasswordValidPart2($parts[1])) {
            $valid++;
        }
    }

    return $valid;
}

class Rule {
    private $letter;
    private $min;
    private $max;

    function __construct(string $rule)
    {
        $rule = trim($rule);
        $parts = explode(' ', $rule);
        $this->letter = $parts[1];
        $lengthParts = explode('-', $parts[0]);
        $this->min = $lengthParts[0];
        $this->max = $lengthParts[1];
    }

    function isPasswordValidPart1(string $password): bool
    {
        $password = trim($password);
        $matches = 0;
        $length = strlen($password);
        for ($i = 0; $i < $length; $i++) {
            if ($password[$i] === $this->letter) {
                $matches++;
            }
        }

        if ($matches >= $this->min && $matches <= $this->max) {
            return true;
        }

        return false;
    }

    function isPasswordValidPart2(string $password): bool
    {
        $password = trim($password);
        $matches = 0;
        if ($password[$this->min - 1] === $this->letter) {
            $matches++;
        }

        if ($password[$this->max - 1] === $this->letter) {
            $matches++;
        }

        return $matches === 1;
    }
}
