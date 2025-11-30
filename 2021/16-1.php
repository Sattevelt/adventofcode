<?php

declare(strict_types=1);

require_once('common.php');
$input = getPuzzleInput(16);
$input = "D2FE28";

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

$output = parsePackets($bin);
print_r($output);die;
var_dump(getVersionSum($output));

function getVersionSum(array $packets) {
    $versionSum = 0;
    foreach ($packets as $packet) {
        if ($packet instanceof Packet) {
            $versionSum += $packet->getVersion() + getVersionSum($packet->getValues());
        }
    }
    return $versionSum;
}

function parsePackets(string $binString, int $limit = PHP_INT_MAX): array
{
    $packets = [];

    while (strlen($binString) >= 11 && $limit > 0) {
        $version = bindec(substr($binString, 0, 3));
        $type = bindec(substr($binString, 3, 3));
        $binString = substr_replace($binString, '', 0, 6); // Remove header
        $packet = new Packet($type, $version);

        if ($type === 4) { // Literal value
            $continue = true;
            $value = '';
            $count = 0;
            while ($continue) {
                $continue = substr($binString, 0, 1) === '1';
                $value .= substr($binString, 1, 4);
                $binString = substr_replace($binString, '', 0, 5);
                $count++;
            }
            $packet->setPacketLength(6 + $count * 5);
            $packet->addValue(bindec($value));
        } else { // Operators
            $lengthTypeId = substr($binString, 0, 1);
            if ($lengthTypeId == 0) {
                $bitLength = bindec(substr($binString, 1, 15));
                $subPackets = parsePackets(substr($binString, 16, $bitLength));
                $packet->setValues($subPackets);
                $packet->setPacketLength(6 + 16 + $bitLength);
                $binString = substr_replace($binString, '', 0, 16 + $bitLength);
            } elseif ($lengthTypeId == 1) {
                $subPackets = bindec(substr($binString, 1, 11));
                $subPackets = parsePackets(substr($binString, 12), $subPackets);
                $subLength = 0;
                /** @var Packet $subPacket */
                foreach ($subPackets as $subPacket) {
                    $packet->addValue($subPacket);
                    $subLength += $subPacket->getPacketLength();
                }
                $packet->setPacketLength(6 + 12 + $subLength);
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
