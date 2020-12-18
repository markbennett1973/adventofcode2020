<?php

include('common.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day15-input.txt';

$data = array_map(
    function (string $a): int {
        return (int) $a;
    },
    explode(',', getInput(INPUT_FILE)[0])
);

print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    return playGame($data, 2020);
}

function part2(array $data): ?int
{
    return playGame($data, 30000000);
}

function playGame(array $data, int $maxTurns): int
{
    /** @var Number[] $numbers */
    $numbers = [];
    $lastNumber = 0;

    foreach ($data as $index => $number) {
        $numbers[$number] = new Number($index + 1);
        $lastNumber = $number;
    }

    $turn = count($numbers) + 1;
    while ($turn <= $maxTurns) {
        $number = $numbers[$lastNumber]->getValue();
        if (!array_key_exists($number, $numbers)) {
            $numbers[$number] = new Number($turn);
        }

        $numbers[$number]->speakNumber($turn);
        $lastNumber = $number;
        $turn++;
    }

    return $lastNumber;
}

class Number
{
    private int $lastTurn;
    private ?int $lastButOneTurn = null;

    public function __construct(int $currentTurn)
    {
        $this->lastTurn = $currentTurn;
    }

    public function getValue(): int
    {
        if ($this->lastButOneTurn === null) {
            return 0;
        }

        return $this->lastTurn - $this->lastButOneTurn;
    }

    public function speakNumber(int $currentTurn)
    {
        $this->lastButOneTurn = $this->lastTurn;
        $this->lastTurn = $currentTurn;
    }
}
