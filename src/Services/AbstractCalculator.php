<?php

namespace Calculator\Services;

abstract class AbstractCalculator
{
    /**
     * @const int
     */
    const FIRST_YEAR    = 1;
    const PRECISION     = 2;
    const DEFAULT_YEARS = 10;

    /**
     * @var float $dilutionPercentage
     */
    protected float $dilutionPercentage = 0;

    /**
     * @var float $discount
     */
    protected float $discount = 0;

    /**
     * @var array $multiples
     */
    protected array $multiples;

    /**
     * @var float $price
     */
    protected float $price;

    /**
     * @var int $totalShares
     */
    protected float $totalShares;

    /**
     * @var int $years
     */
    protected int $years;

    public function __construct()
    {
        $this->years = self::DEFAULT_YEARS;
    }

    /**
     * @return void
     */
    abstract public function getFinalPrice(): void;

    /**
     * @param float $dilutionPercentage
     * 
     * @return $this
     */
    public function setDilutionPercentage(float $dilutionPercentage): AbstractCalculator
    {
        $this->dilutionPercentage = $dilutionPercentage;

        return $this;
    }

    /**
     * @param float $discount
     *
     * @return $this
     */
    public function setDiscount(float $discount): AbstractCalculator
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @param array $multiples
     *
     * @return $this
     */
    public function setMultiples(array $multiples): AbstractCalculator
    {
        $this->multiples = $multiples;

        return $this;
    }

    /**
     * @param float $price
     *
     * @return $this
     */
    public function setPrice(float $price): AbstractCalculator
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @param float $totalShares
     *
     * @return $this
     */
    public function setTotalShares(float $totalShares): AbstractCalculator
    {
        $this->totalShares = $totalShares;

        return $this;
    }

    /**
     * @param int $years
     *
     * @return $this
     */
    public function setYears(int $years): AbstractCalculator
    {
        $this->years = $years;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return float
     */
    protected function getAverage(array $data): float
    {
        $total = array_sum($data);

        return round($total / count($data), self::PRECISION);
    }

    /**
     * @return array
     */
    protected function getLastGrowths(array $data): array
    {
        $growths = [];

        for ($index = self::FIRST_YEAR; $index < count($data); $index++) {
            $previous = $data[$index - 1]; 
            $current  = $data[$index];
            $growths[] = (($current - $previous) / $previous);
        }

        return $growths;
    }

    /**
     * @param array $data
     * @param float $growthAverage
     *
     * @return array
     */
    protected function getFutureGrowths(array $data, float $growthAverage): array
    {
        $lastData = end($data);
        $futureGrowths = [];

        foreach (range(self::FIRST_YEAR, $this->years) as $year) {
            $newData = $lastData + ($lastData * $growthAverage);

            if ($this->discount > 0) {
                $newData = $newData - ($newData * $this->discount);
            }

            $futureGrowths[$year] = round($newData, self::PRECISION);
            $lastData = $newData;
        }

        return $futureGrowths;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getStats($data): array
    {
        $min = min($data);
        $max = max($data);
        $average = $this->getAverage($data);

        return compact('min', 'max', 'average');
    }
}
