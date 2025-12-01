<?php

declare(strict_types=1);

require_once('common.php');
$input = getPuzzleInput(16);
//$input = "9C0141080250320F1802104A08";

// Parse input
$hex2dec = [
    '0' => '0000',
    '1' => '0001',
    '2' => '0010',
    '3' => '0011',
    '4' => '0100',
    '5' => '0101',
    '6' => '0110',
    '7' => '0111',
    '8' => '1000',
    '9' => '1001',
    'A' => '1010',
    'B' => '1011',
    'C' => '1100',
    'D' => '1101',
    'E' => '1110',
    'F' => '1111',
];
$bin = '';
foreach (str_split($input) as $char) {
    $bin .= $hex2dec[$char];
}

// Start parsing packets into nested data structure (Packets containing packets)
$output = parsePackets($bin);

// Apply operators to subpackets.
echo packetToNumber($output[0]) . "\n";

/**
 * Resolve every packet to a number. If subpackets contain operators, resolve them to numbers first before
 * applying the packet operator.
 * @param Packet $packet
 * @return int
 */
function packetToNumber(Packet $packet): int {
    $values = [];
    foreach ($packet->getValues() as $value) {
        if ($value instanceof Packet) {
            $values[] = packetToNumber($value);
        } else {
            $values[] = $value;
        }
    }

    switch ($packet->getType()) {
        case 0: // sum
            return array_sum($values);
        case 1: // product
            return array_product($values);
        case 2: // min
            return min($values);
        case 3: // max
            return max($values);
        case 4: // literal
            return $values[0];
        case 5: // >
            return ($values[0] > $values [1] ? 1 : 0);
        case 6: // <
            return ($values[0] < $values [1] ? 1 : 0);
        case 7: // ==
            return ($values[0] == $values [1] ? 1 : 0);
            break;
    }
}

/**
 * Turn $binString into an array of (nested) packets.
 *
 * Always works from the start of the string and removes that part of the string that is dealt with.
 * IE: Read 3 bytes for version, next 3 bytes for type -> Remove first 6 bytes we have handled.
 *
 * @param string $binString
 * @param int $limit  Used when only looking for the next n packets.
 * @return array
 */
function parsePackets(string $binString, int $limit = PHP_INT_MAX): array
{
    $packets = [];

    while (strlen($binString) >= 11 && $limit > 0) {
        $version = bindec(substr($binString, 0, 3));
        $type = bindec(substr($binString, 3, 3));
        $packet = new Packet($type, $version);

        $binString = substr_replace($binString, '', 0, 6); // Remove header

        if ($type === 4) { // Literal value
            $continue = true;
            $value = '';
            $count = 0;
            while ($continue) {
                $continue = substr($binString, 0, 1) === '1';
                $value .= substr($binString, 1, 4);
                $count++;

                // cleanup!
                $binString = substr_replace($binString, '', 0, 5);
            }
            $packet->setPacketLength(6 + $count * 5);
            $packet->addValue(bindec($value));
        } else { // Operators
            $lengthTypeId = substr($binString, 0, 1);
            if ($lengthTypeId == 0) {
                // Read next bits to determine length of subpackets
                $bitLength = bindec(substr($binString, 1, 15));
                // Parse subpackets
                $subPackets = parsePackets(substr($binString, 16, $bitLength));

                // Update packet with values (subpackets) and packet lenth
                $packet->setValues($subPackets);
                $packet->setPacketLength(6 + 16 + $bitLength);
                // cleanup!
                $binString = substr_replace($binString, '', 0, 16 + $bitLength);
            } elseif ($lengthTypeId == 1) {
                // Get next n subpackets
                $subPackets = bindec(substr($binString, 1, 11));
                $subPackets = parsePackets(substr($binString, 12), $subPackets);
                $subLength = 0;
                // Retrieve packetlength from subpackets to know after the fact how much bits were read.
                /** @var Packet $subPacket */
                foreach ($subPackets as $subPacket) {
                    $packet->addValue($subPacket);
                    $subLength += $subPacket->getPacketLength();
                }
                $packet->setPacketLength(6 + 12 + $subLength);

                // cleanup!
                $binString = substr_replace($binString, '', 0, 12 + $subLength);
            }
        }
        $packets[] = $packet;
        $limit--;
    }

    return $packets;
}

class Packet
{
    private int $type;
    private int $version;
    /**
     * Contains either one or more Packet instances, or a literal value.
     * @var array
     */
    private array $values = [];
    private int $packetLength = 0;

    public function __construct(int $type, int $version)
    {
        $this->type = $type;
        $this->version = $version;
    }

    public function addValue($value) {
        $this->values[] = $value;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param int $version
     */
    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values): void
    {
        $this->values = $values;
    }

    /**
     * @return int
     */
    public function getPacketLength(): int
    {
        return $this->packetLength;
    }

    /**
     * @param int $packetLength
     */
    public function setPacketLength(int $packetLength): void
    {
        $this->packetLength = $packetLength;
    }
}
