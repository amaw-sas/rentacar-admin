<?php

namespace App\Rentcar\Localiza\VehAvailRate;

use SimpleXMLElement;
use Illuminate\Contracts\Support\Arrayable;
use App\Rentcar\Localiza\Exceptions\NoDataFoundException;

class VehAvail implements Arrayable {

    public $node;

    public function __construct(SimpleXMLElement $node) {
        $this->node = $node;
    }

    /**
     * get category code
     *
     */
    private function getCategoryCode(): array {
        $node = $this->node->xpath(".//A:Vehicle");
        $result = [
            'categoryCode' => null,
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['categoryCode'] = (string) $node->attributes()->Code;
        }
        else abort(new NoDataFoundException);

        return $result;
    }

    /**
     * get category code
     *
     */
    private function getCategoryDescription(): array {
        $node = $this->node->xpath(".//A:Vehicle");
        $result = [
            'categoryDescription' => null,
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['categoryDescription'] = (string) $node->attributes()->Description;
        }
        else abort(new NoDataFoundException);

        return $result;
    }

    /**
     * get total charge data
     *
     */
    private function getTotalCharge(): array {
        $node = $this->node->xpath(".//A:TotalCharge");
        $result = [
            'totalAmount' => 0,
            'estimatedTotalAmount' => 0,
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['totalAmount'] = $this->roundPrice($node->attributes()->RateTotalAmount);
            $result['estimatedTotalAmount'] = $this->roundPrice($node->attributes()->EstimatedTotalAmount);
        }
        else abort(new NoDataFoundException);

        //TODO  convert to other currency

        return $result;
    }

    /**
     * get vehicle charge data
     *
     * @return array
     */
    private function getVehicleCharge(): array {
        $node = $this->node->xpath('.//A:VehicleCharge[@Description="Diárias"]//A:Calculation');
        $result = [
            'vehicleDayCharge' => 0,
            'numberDays' => 0,
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['vehicleDayCharge'] = $this->roundPrice($node->attributes()->UnitCharge);
            $result['numberDays'] = (int) $node->attributes()->Quantity;
        }
        else abort(new NoDataFoundException);

        //TODO  convert to other currency

        return $result;
    }

    /**
     * get discount data
     *
     * @return array
     */
    private function getDiscount(): array {
        $node = $this->node->xpath('.//A:Discount');
        $result = [
            'discountAmount' => 0,
            'discountPercentage' => 0,
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['discountAmount'] = $this->roundPrice($node->attributes()->Amount);
            $result['discountPercentage'] = $this->roundPrice($node->attributes()->Percent,2);
        }

        //TODO  convert to other currency

        return $result;
    }

    /**
     * get tax fee data
     *
     * @return array
     */
    private function getTaxFee(): array {
        $node = $this->node->xpath('.//A:Fees//A:Fee[@Description="Taxa"]//A:Calculation');
        $result = [
            'taxFeeAmount' => 0,
            'taxFeePercentage' => 0
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['taxFeeAmount'] = $this->roundPrice($node->attributes()->Total);
            $result['taxFeePercentage'] = $this->roundPrice($node->attributes()->Percentage, 2);
        }
        else abort(new NoDataFoundException);

        //TODO  convert to other currency

        return $result;
    }

    /**
     * get IVA fee data
     *
     * @return array
     */
    private function getIVAFee(): array {
        $node = $this->node->xpath('.//A:Fees//A:Fee[@Description="IVA"]');
        $result = [
            'IVAFeeAmount' => 0
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['IVAFeeAmount'] = $this->roundPrice($node->attributes()->Amount);
        }
        else abort(new NoDataFoundException);

        //TODO  convert to other currency

        return $result;
    }

    /**
     * get reference data
     *
     * @param SimpleXMLElement $rootEl root element of category
     * @return array
     */
    private function getReference(): array {
        $node = $this->node->xpath('.//A:Reference');
        $result = [
            'reference' => ""
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['reference'] = (string) $node->attributes()->ID;
        }
        else abort(new NoDataFoundException);

        return $result;
    }

    /**
     * get coverage data
     *
     * @return array
     */
    private function getCoverage(): array {
        $node = $this->node->xpath('.//A:PricedCoverage//A:Charge[@Description="Proteções com co-participação regular"]//A:Calculation');
        $result = [
            'coverageUnitCharge' => 0,
            'coverageQuantity' => 0,
            'coverageTotalAmount' => 0,
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['coverageUnitCharge'] = $this->roundPrice($node->attributes()->UnitCharge);
            $result['coverageQuantity'] = (int) $node->attributes()->Quantity;
            $result['coverageTotalAmount'] = $this->roundPrice($node->attributes()->Total);
        }

        //TODO  convert to other currency

        return $result;
    }

    /**
     * get extra hours data
     *
     * @return array
     */
    private function getExtraHours(): array {
        $node = $this->node->xpath('.//A:VehicleCharge[@Description="Horas extras"]//A:Calculation');
        $result = [
            'extraHoursQuantity' => 0,
            'extraHoursUnityAmount' => 0,
            'extraHoursTotalAmount' => 0,
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['extraHoursQuantity'] = $this->roundPrice($node->attributes()->Quantity);
            $result['extraHoursUnityAmount'] = (int) $node->attributes()->UnitCharge;
            $result['extraHoursTotalAmount'] = $this->roundPrice($node->attributes()->Total);
        }

        //TODO  convert to other currency

        return $result;
    }

    /**
     * get return fee
     *
     * @return array
     */
    private function getReturnFee(): array {
        $node = $this->node->xpath('.//A:Fees//A:Fee[@Description="Taxa de retorno"]');
        $result = [
            'returnFeeAmount' => 0
        ];

        if(count($node) > 0){
            $node = $node[0];
            $result['returnFeeAmount'] = $this->roundPrice($node->attributes()->Amount);
        }

        //TODO  convert to other currency

        return $result;
    }

    private function getTotalAmountPlusTotalCoverage(): array {
        [
            'totalAmount'   =>  $totalAmount
        ] = $this->getTotalCharge();

        [
            'returnFeeAmount'   =>  $returnFeeAmount
        ] = $this->getReturnFee();

        [
            'coverageUnitCharge' => $coverageUnitCharge,
            'coverageQuantity' => $coverageQuantity,
        ] = $this->getCoverage();

        $result = [
            'totalAmountPlusTotalCoverage' => 0
        ];

        if($totalAmount){
            $coveragePrice = $coverageUnitCharge * $coverageQuantity;
            $totalCoveragePrice = $this->getTotalCoveragePrice();
            $result['totalAmountPlusTotalCoverage'] = (int) $totalAmount + $returnFeeAmount + $totalCoveragePrice - $coveragePrice;
        }

        return $result;
    }

    private function getTaxFeeWithTotalCoverage(): array {
        [
            'taxFeePercentage' => $taxFeePercentage
        ] = $this->getTaxFee();

        [
            'totalAmountPlusTotalCoverage'   =>  $totalAmountPlusTotalCoverage
        ] = $this->getTotalAmountPlusTotalCoverage();

        $result = [
            'taxFeeWithTotalCoverage' => 0
        ];

        if($taxFeePercentage && $totalAmountPlusTotalCoverage){
            $result['taxFeeWithTotalCoverage'] = (int) round(($totalAmountPlusTotalCoverage * $taxFeePercentage) / 100);
        }

        return $result;
    }

    private function getIvaFeeWithTotalCoverage(): array {
        [
            'totalAmountPlusTotalCoverage' => $totalAmountPlusTotalCoverage
        ] = $this->getTotalAmountPlusTotalCoverage();

        [
            'taxFeeWithTotalCoverage' => $taxFeeWithTotalCoverage
        ] = $this->getTaxFeeWithTotalCoverage();

        $result = [
            'ivaFeeWithTotalCoverage'   =>  0
        ];

        $ivaPercentage = config('localiza.ivaPercentage');

        if($taxFeeWithTotalCoverage && $totalAmountPlusTotalCoverage){
            $sum = $totalAmountPlusTotalCoverage + $taxFeeWithTotalCoverage;
            $result['ivaFeeWithTotalCoverage'] = (int) round(($sum * $ivaPercentage) / 100);
        }

        return $result;
    }

    private function getTotalPriceWithTotalCoverage(): array {
        [
            'totalAmountPlusTotalCoverage'  => $totalAmountPlusTotalCoverage
        ] = $this->getTotalAmountPlusTotalCoverage();

        [
            'taxFeeWithTotalCoverage'  => $taxFeeWithTotalCoverage
        ] = $this->getTaxFeeWithTotalCoverage();

        [
            'ivaFeeWithTotalCoverage'  => $ivaFeeWithTotalCoverage
        ] = $this->getIvaFeeWithTotalCoverage();

        $result = [
            'totalPriceWithTotalCoverage' => 0
        ];

        if($totalAmountPlusTotalCoverage && $taxFeeWithTotalCoverage && $ivaFeeWithTotalCoverage)
            $result['totalPriceWithTotalCoverage'] = (int) $totalAmountPlusTotalCoverage + $taxFeeWithTotalCoverage + $ivaFeeWithTotalCoverage;
        else
            abort(new NoDataFoundException);

        return $result;
    }

    private function getTotalCoverageUnitCharge(): array {
        [
            'coverageUnitCharge' => $coverageUnitCharge,
            'coverageQuantity' => $coverageQuantity,
        ] = $this->getCoverage();

        $result = [
            'totalCoverageUnitCharge' => 0
        ];

        $totalCoveragePriceLowGamma = (int) config('localiza.totalCoveragePriceLowGamma');
        $totalCoveragePriceHighGamma = (int) config('localiza.totalCoveragePriceHighGamma');

        if($coverageQuantity && $coverageUnitCharge)
            $result['totalCoverageUnitCharge'] = (int) ($coverageUnitCharge <= 35000) ? $totalCoveragePriceLowGamma : $totalCoveragePriceHighGamma;

        return $result;
    }

    private function getTotalCoveragePrice(): int {

        [
            'totalCoverageUnitCharge' => $totalCoverageUnitCharge,
        ] = $this->getTotalCoverageUnitCharge();

        [
            'coverageQuantity' => $coverageQuantity,
        ] = $this->getCoverage();


        if($totalCoverageUnitCharge && $coverageQuantity)
            return (int) $totalCoverageUnitCharge * $coverageQuantity;
        else abort(new NoDataFoundException);

    }

    /**
     * get round price
     *
     * @param string $price
     * @param int $precision
     */
    private function roundPrice($price, $precision = 0): int {
        return (int) round((float) $price, $precision);
    }


    public function toArray(): array {
        return array_merge(
            $this->getCategoryCode(),
            $this->getCategoryDescription(),
            $this->getTotalCharge(),
            $this->getVehicleCharge(),
            $this->getDiscount(),
            $this->getTaxFee(),
            $this->getIVAFee(),
            $this->getReturnFee(),
            $this->getReference(),
            $this->getCoverage(),
            $this->getExtraHours(),
            $this->getTotalCoverageUnitCharge(),
            $this->getTotalAmountPlusTotalCoverage(),
            $this->getTaxFeeWithTotalCoverage(),
            $this->getIvaFeeWithTotalCoverage(),
            $this->getTotalPriceWithTotalCoverage(),
        );
    }
}
