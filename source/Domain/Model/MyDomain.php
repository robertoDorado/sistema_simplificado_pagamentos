<?php
namespace Source\Domain\Model;

/**
 * MyDomain Domain\Model
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Domain\Model
 */
class MyDomain
{
    public function calculate(float $number)
    {
        return 2 * $number;
    }
    
    public function isString(string $string): string
    {
        return $string;
    }
}
