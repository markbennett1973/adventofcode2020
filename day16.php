<?php

include('common.php');

// const INPUT_FILE = 'test.txt';
const INPUT_FILE = 'day16-input.txt';

$rules = [];
$tickets = [];
$myTicket = [];

parseData(getInput(INPUT_FILE));
print 'Part 1: ' . (part1($rules, $tickets) ?? 'No answer') . "\n";
print 'Part 2: ' . (part2($rules, $tickets, $myTicket) ?? 'No answer') . "\n";

/**
 * @param array|Rule[] $rules
 * @param array $tickets
 * @return int|null
 */
function part1(array $rules, array $tickets): ?int
{
    $errorRate = 0;
    foreach ($tickets as $ticket) {
        foreach ($ticket as $ticketField) {
            foreach ($rules as $rule) {
                if ($rule->isFieldValid($ticketField)) {
                    continue 2;
                }
            }
            $errorRate += $ticketField;
        }
    }

    return $errorRate;
}

function part2(array $rules, array $tickets, array $myTicket): ?int
{
    removeInvalidTickets($rules, $tickets);
    $rulePositions = deriveRulePositions($rules, $tickets);
    return getDepartureValues($rulePositions, $myTicket);
}

function parseData(array $lines)
{
    global $tickets, $myTicket, $rules;

    $reachedMyTicket = $reachedOtherTickets = false;
    foreach ($lines as $line) {
        if (preg_match('/[a-z: ]+\d/', $line)) {
            $rules[] = new Rule($line);
        }

        if ($reachedOtherTickets) {
            $tickets[] = array_map('intval', explode(',', $line));
        }

        if ($reachedMyTicket && preg_match('/^[\d]/', $line)) {
            $myTicket = array_map('intval', explode(',', $line));
        }

        if (strpos($line, 'nearby tickets') !== false) {
            $reachedOtherTickets = true;
            $reachedMyTicket = false;
        }

        if (strpos($line, 'your ticket') !== false) {
            $reachedMyTicket = true;
        }
    }
}

function removeInvalidTickets($rules, &$tickets)
{
    foreach ($tickets as $index => $ticket) {
        foreach ($ticket as $ticketField) {
            foreach ($rules as $rule) {
                if ($rule->isFieldValid($ticketField)) {
                    continue 2;
                }
            }

            // Ticket didn't match any rules, so must be invalid
            unset($tickets[$index]);
        }
    }
}

/**
 * @param array|Rule[] $rules
 * @param array $tickets
 * @return array
 */
function deriveRulePositions(array $rules, array $tickets): array
{
    // make a list of field positions with valid rules for each one
    $possiblePositions = [];
    $ruleCount = count($rules);
    $fieldCount = count(reset($tickets));
    for ($i = 0; $i < $fieldCount; $i++) {
        for ($j = 0; $j < $ruleCount; $j++) {
            $possiblePositions[$i][$j] = '';
        }
    }

    // We know all tickets are valid, so go through each field for each ticket, and
    // remove any rules which are not applicable to that field position
    foreach ($tickets as $ticket) {
        foreach ($ticket as $fieldPosition => $fieldValue) {
            foreach ($rules as $rulePosition => $rule) {
                if ($rule->isFieldValid($fieldValue) === false) {
                    if (array_key_exists($rulePosition, $possiblePositions[$fieldPosition])) {
                        unset($possiblePositions[$fieldPosition][$rulePosition]);
                    }
                }
            }
        }
    }

    $finalRulePositions = [];
    $removedRules = true;
    while ($removedRules) {
        $removedRules = false;
        foreach ($possiblePositions as $fieldIndex => $rulePositions) {
            // If there's only one possible rule for this field index, remember it, and
            // remove it from the other field indexes
            if (count($rulePositions) === 1) {
                $ruleIndex = array_key_first($rulePositions);
                $finalRulePositions[$fieldIndex] = $rules[$ruleIndex];
                foreach ($possiblePositions as $i => $rulePositions2) {
                    if (array_key_exists($ruleIndex, $rulePositions2)) {
                        unset($possiblePositions[$i][$ruleIndex]);
                        $removedRules = true;
                    }
                }
                continue 2;
            }
        }
    }

    return $finalRulePositions;
}

/**
 * @param array|Rule[] $rulePositions
 * @param array|int[] $myTicket
 * @return int
 */
function getDepartureValues(array $rulePositions, array $myTicket): int
{
    $total = 1;
    foreach ($rulePositions as $fieldIndex => $rule) {
        if (substr($rule->getLabel(), 0, 9) === 'departure') {
            $total = $total * $myTicket[$fieldIndex];
        }
    }

    return $total;
}

class Rule {
    private array $ranges = [];
    private string $label = '';

    public function __construct($rangeString)
    {
        preg_match_all('/([a-z ]+): ([\d]+-[\d]+) or ([\d]+-[\d]+)/', $rangeString, $matches);
        $this->label = $matches[1][0];
        $this->ranges[] = array_map('intval', explode('-', $matches[2][0]));
        $this->ranges[] = array_map('intval', explode('-', $matches[3][0]));
    }

    public function isFieldValid(int $fieldValue): bool
    {
        foreach ($this->ranges as $range) {
            if ($fieldValue >= $range[0] && $fieldValue <= $range[1]) {
                return true;
            }
        }

        return false;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
