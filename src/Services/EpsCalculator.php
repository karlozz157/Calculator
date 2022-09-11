<?php

namespace Calculator\Services;

/**
 * Calcular el valor de una accion usando el eps
 */
class EpsCalculator extends AbstractCalculator
{
    /**
     * @var array $eps
     */
    private $eps = [];

    /**
     * @param array $eps
     * @param bool  $reverse
     *
     * @return $this
     */
    public function setEps(array $eps, bool $reverse = false): EpsCalculator
    {
        $this->eps = $reverse ? array_reverse($eps) : $eps;

        return $this;
    }

    /**
     * @override
     */
    public function getFinalPrice(): void
    {
        $epsGrowths = $this->getLastGrowths($this->eps);
        $epsStats   = $this->getStats($epsGrowths);
        $epsAverage = $epsStats['average'];

        echo(sprintf('Crecimiento Pasado: %s', $epsAverage)) . PHP_EOL;

        $futureEps  = $this->getFutureGrowths($this->eps, $epsAverage);
        $multiples  = $this->getStats($this->multiples);

        foreach ($futureEps as $year => $eps) {
            foreach ($multiples as $type => $multiple) {
                $price = round($multiple * $eps, self::PRECISION);
                echo('============================================') . PHP_EOL;
                echo "$year | $eps | $multiple | $type | $price" . PHP_EOL;
            }
            echo PHP_EOL;
        }
    }
}
