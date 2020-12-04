<?php

include('common.php');

const INPUT_FILE = 'day4-input.txt';

$data = getInput(INPUT_FILE, false);
$data = collapseLines($data);

print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function collapseLines(array $rows): array
{
    $lines = [];
    $line = '';
    foreach ($rows as $row) {
        if ($row === '') {
            if ($line !== '') {
                $lines[] = $line;
                $line = '';
            }
        } else {
            $line .= ' ' . $row;
        }
    }

    if ($line !== '') {
        $lines[] = $line;
    }

    return $lines;
}

function part1(array $data): ?int
{
    $valid = 0;
    foreach ($data as $item) {
        $passport = new Passport($item);
        if ($passport->isValid()) {
            $valid++;
        }
    }

    return $valid;
}

function part2(array $data): ?int
{
    $valid = 0;
    foreach ($data as $item) {
        $passport = new Passport($item);
        if ($passport->isValidStrict()) {
            $valid++;
        }
    }

    return $valid;
}

class Passport
{
    private array $items = [];

    const REQUIRED = ['byr', 'iyr', 'eyr', 'hgt', 'hcl', 'ecl', 'pid'];

    public function __construct(string $data)
    {
        $this->splitPassport($data);
    }

    private function splitPassport(string $data)
    {
        $parts = explode(' ', $data);
        foreach ($parts as $part) {
            $itemParts = explode(':', $part);
            if (count($itemParts) === 2) {
                $this->items[$itemParts[0]] = $itemParts[1];
            }
        }
    }

    public function isValid(): bool
    {
        foreach (self::REQUIRED as $item) {
            if (!array_key_exists($item, $this->items)) {
                return false;
            }
        }

        return true;
    }

    public function isValidStrict(): bool
    {
        if (!$this->isValid()) return false;
        if (!$this->isBirthYearValid()) return false;
        if (!$this->isIssueYearValid()) return false;
        if (!$this->isExpiryYearValid()) return false;
        if (!$this->isHeightValid()) return false;
        if (!$this->isHairValid()) return false;
        if (!$this->isEyeValid()) return false;
        if (!$this->isPassportIdValid()) return false;
        return true;
    }

    private function isBirthYearValid(): bool
    {
        return $this->isYearValid('byr', 1920, 2002);
    }

    private function isIssueYearValid(): bool
    {
        return $this->isYearValid('iyr', 2010, 2020);
    }

    private function isExpiryYearValid(): bool
    {
        return $this->isYearValid('eyr', 2020, 2030);
    }

    private function isYearValid(string $key, int $min, int $max): bool
    {
        $value = $this->items[$key];
        if (!preg_match('/[\d]{4}/', $value)) return false;
        if ($value < $min || $value > $max) return false;
        return true;
    }

    private function isHeightValid(): bool
    {
        preg_match('/([\d]+)(cm|in)/', $this->items['hgt'], $matches);
        if (count($matches) !== 3) return false;
        if ($matches[2] === 'cm') {
            if ($matches[1] < 150 || $matches[1] > 193) return false;
            return true;
        }
        if ($matches[2] === 'in') {
            if ($matches[1] < 59 || $matches[1] > 76) return false;
            return true;
        }
        return false;
    }

    private function isHairValid(): bool
    {
        return preg_match('/^#[0-9a-f]{6}$/', $this->items['hcl']);
    }

    private function isEyeValid(): bool
    {
        $allowed = ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth'];
        return in_array($this->items['ecl'], $allowed);
    }

    private function isPassportIdValid(): bool
    {
        return preg_match('/^[\d]{9}$/', $this->items['pid']);
    }
}
