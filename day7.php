<?php

include('common.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day7-input.txt';

$data = getInput(INPUT_FILE);
print 'Part 1: ' . (part1($data) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($data) ?? 'No answer') . "\n";

function part1(array $data): ?int
{
    $rules = [];
    foreach ($data as $row) {
        $rules[] = new Rule($row);
    }

    return countContainers('shiny gold', $rules);
}

function part2(array $data): ?int
{
    $rules = [];
    foreach ($data as $row) {
        $rules[] = new Rule($row);
    }

    return countContents('shiny gold', $rules);
}

/**
 * @param string $startColour
 * @param array|Rule[] $rules
 * @return int
 */
function countContainers(string $startColour, array $rules): int
{
    // Add our start colour, so we don't repeat adding it in
    $containers[] = $startColour;

    $added = 1;
    while ($added !== 0) {
        $added = 0;
        foreach ($containers as $colour) {
            foreach ($rules as $rule) {
                if ($rule->contains($colour)) {
                    if (!in_array($rule->containerColour, $containers)) {
                        $containers[] = $rule->containerColour;
                        $added++;
                    }
                }
            }
        }
    }

    // $containers includes our starting colour - we don't want to count that
    return count($containers) - 1;
}

function countContents(string $startColour, array $rules): int
{
    $totalBags = 0;
    addBags($rules, 1, $startColour, $totalBags);
    return $totalBags;
}

/**
 * @param array|Rule[] $rules
 * @param int $containerQuantity
 * @param string $containerColour
 * @param int $totalBags
 */
function addBags(array $rules, int $containerQuantity, string $containerColour, int &$totalBags)
{
    $rule = findRuleForColour($containerColour, $rules);
    foreach ($rule->contents as $colour => $quantity) {
        $bagsOfThisColour = $containerQuantity * $quantity;
        $totalBags += $bagsOfThisColour;
        addBags($rules, $bagsOfThisColour, $colour, $totalBags);
    }
}

/**
 * @param string $colour
 * @param array|Rule[] $rules
 * @return Rule|null
 */
function findRuleForColour(string $colour, array $rules): ?Rule
{
    foreach ($rules as $rule) {
        if ($rule->containerColour === $colour) {
            return $rule;
        }
    }

    return null;
}

class Rule
{
    public string $containerColour;

    /**
     * What does this rule contain?
     * @var array
     *   colour => quantity
     */
    public array $contents = [];

    public function __construct(string $ruleDescription)
    {
        // Get rid of the junk we don't need
        $ruleDescription = str_replace(
            ['bags', 'bag', '.'],
            ['', '', ''],
            $ruleDescription
        );

        $parts = explode('contain', $ruleDescription);
        $this->containerColour = trim($parts[0]);

        if (strpos($parts[1], 'no other bags')) {
            return;
        }

        $contentParts = explode(',', $parts[1]);
        foreach ($contentParts as $contentPart) {
            preg_match('/(\d+) (.+)/', $contentPart, $matches);
            if (count($matches) === 3) {
                $colour = trim($matches[2]);
                $quantity = (int) $matches[1];
                $this->contents[$colour] = $quantity;
            }
        }
    }

    /**
     * Does this rule contain this colour?
     * @param string $targetColour
     * @return bool
     */
    public function contains(string $targetColour): bool
    {
        foreach ($this->contents as $colour => $quantity) {
            if ($colour === $targetColour) {
                return true;
            }
        }

        return false;
    }
}
