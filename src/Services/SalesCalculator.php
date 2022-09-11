<?php

namespace Calculator\Services;

/**
 * Calcular el valor de una accion usando las ventas
 *
 * Formula
 * (ventas futuras * múltiplo futuro ) / número de acciones en circulación
 */
class SalesCalculator extends AbstractCalculator
{
    /**
     * @var array $sales
     */
    private array $sales;

    /**
     * @var array $sales
     */
    public function setSales(array $sales): SalesCalculator
    {
        $this->sales = $sales;

        return $this;
    }

    /**
     * @override
     */
    public function getFinalPrice(): void
    {
        $salesGrowths = $this->getLastGrowths($this->sales);
        $salesStats   = $this->getStats($salesGrowths);

        $futuresales = $this->getFutureGrowths($this->sales, $salesStats['average']);
        $multiples   = $this->getStats($this->multiples);

        $totalShares = $this->totalShares;

        foreach ($futuresales as $year => $sales) {
            if ($this->dilutionPercentage) {
                $totalShares = ($totalShares + ($totalShares * $this->dilutionPercentage));
            }

            foreach ($multiples as $type => $multiple) {
                $price = round(($sales * $multiple) / $totalShares, self::PRECISION);
                echo('============================================') . PHP_EOL;
                echo "$year | $multiple | $type | $price" . PHP_EOL;
            }
            echo PHP_EOL;
        }
    }
}
