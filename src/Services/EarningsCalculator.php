<?php

namespace Calculator\Services;

/**
 * Calcular el valor de una accion usando el beneficio neto y las acciones en circulación
 */
class EarningsCalculator extends AbstractCalculator
{
    /**
     * @var array $earnings
     */
    private array $earnings;

    /**
     * @param array $earnings
     * 
     * @return $this
     */
    public function setEarnings(array $earnings): EarningsCalculator
    {
        $this->earnings = $earnings;

        return $this;
    }

    /**
     * @override
     */
    public function getFinalPrice(): void
    {
        $earningsGrowths = $this->getLastGrowths($this->earnings);
        $earningsStats   = $this->getStats($earningsGrowths);

        $futureEarnings  = $this->getFutureGrowths($this->earnings, $earningsStats['average']);
        $multiples  = $this->getStats($this->multiples);

        $totalShares = $this->totalShares;

        foreach ($futureEarnings as $year => $futureEarning) {

            if ($this->dilutionPercentage) {
                $totalShares = ($totalShares + ($totalShares * $this->dilutionPercentage));
            }

            $eps = ($futureEarning / $totalShares);

            foreach ($multiples as $type => $multiple) {
                $price = round($multiple * $eps, self::PRECISION);
                echo('============================================') . PHP_EOL;
                echo "$year | $eps | $multiple | $type | $price" . PHP_EOL;
            }
            echo PHP_EOL;
        }

        $this->totalShares = $totalShares;
    }
}
