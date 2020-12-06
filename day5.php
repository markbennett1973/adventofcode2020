<?php

include('common.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day5-input.txt';

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $highestId = 0;
    foreach ($data as $row) {
        $pass = new Pass($row);
        if ($pass->getID() > $highestId) {
            $highestId = $pass->getID();
        }
    }

    return $highestId;
}

function part2(array $data): ?int
{
    $seats = [];
    foreach ($data as $row) {
        $pass = new Pass($row);
        $seats[$pass->getID()] = '';
    }

    // Find a seat that is not in $seats, but +1 and -1 are in seats
    for ($i = 0; $i <= 915; $i++) {
        if (!array_key_exists($i, $seats)
            && array_key_exists($i + 1, $seats)
            && array_key_exists($i - 1, $seats)) {
            return $i;
        }
    }

    return null;
}

class Pass
{
    private int $row;
    private int $column;

    public function __construct(string $spec)
    {
        $this->parseSpec($spec);
    }

    public function getID(): int
    {
        return ($this->row * 8) + $this->column;
    }

    private function parseSpec(string $spec)
    {
        $this->row = $this->parseRow(substr($spec, 0, 7));
        $this->column = $this->parseColumn(substr($spec, 7, 3));
    }

    private function parseRow(string $row): int
    {
        $row = str_replace(['F', 'B'], ['0', '1'], $row);
        return bindec($row);
    }

    private function parseColumn(string $row): int
    {
        $row = str_replace(['L', 'R'], ['0', '1'], $row);
        return bindec($row);
    }
}
