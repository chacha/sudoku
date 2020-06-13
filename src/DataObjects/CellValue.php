<?php


namespace App\DataObjects;


use Exception;
use InvalidArgumentException;

class CellValue
{

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var int
     */
    private ?int $value;

    /**
     * CellValue constructor.
     * @param int|null $initialValue
     */
    public function __construct(int $initialValue = null)
    {
        $this->value = $initialValue;
        try {
            $this->identifier = sha1(random_int(0, 100) . random_int(0, 100));
        } catch (Exception $e) {
            echo 'Error setting random value';
        }
    }

    public function getId(): string
    {
        return $this->identifier;
    }

    public function storeValue(int $number): void
    {
        if ($number < 0) {
            throw new  InvalidArgumentException('Cannot store negative numbers');
        }

        $this->value = $number;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

}
