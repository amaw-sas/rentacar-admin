<?php

namespace Tests\Feature\Localiza;

use App\Rentcar\Localiza\VehAvailRate\LocalizaAPIVehAvailRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Exceptions\HttpResponseException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LocalizaAPIVehAvailRateTest extends TestCase
{
    use RefreshDatabase;

    #[Group("localiza_veh_avail_rate")]
    #[Group("localiza")]
    #[Test]
    public function get_data_of_category_FY_availability(): void {


        Http::preventStrayRequests();

        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-xml'))->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
            false
        );

        $data = $localiza->getData();

        dump($data[0]);

        $this->assertContains([
            'categoryCode'          =>  "FY",
            'categoryDescription'   =>  "HÍBRIDO",
            'totalAmount'           =>  204_002,
            'estimatedTotalAmount'  =>  305_000,
            'vehicleDayCharge'      =>  204_002,
            'numberDays'            =>  1,
            'discountAmount'        =>  95_998,
            'discountPercentage'    =>  32,
            'taxFeeAmount'          =>  23_300,
            'taxFeePercentage'      =>  10,
            'IVAFeeAmount'          =>  48_697,
            'reference'             =>  "qQsAAB+LCAAAAAAABACNVsmO20YQ/RWBZ2nQFBeRunG0xELskTDDGDAsH3rIltwIyVaa5ETwQF+TQz7EP5ZqNpfmIjMXAWK92l9V17vmsz9JcsA8oDjSltpMNy1LtyzDsLSp9pGmGV6xJKQBZisWkxL29V3bhSTJ6AkEIeMDkLnjIGS5aDqCnKvQz4SnmMGXqfYEgP2J8AxDVPvt5tn3Ji++97T2ntcQ2YqF9Mx8zOlJAJDu6LoN39ckDViSsQNYB7c5jj7RhMZg09AfXNedGzayB2H42od5GaevecbSKhJIXKhykUIlBPe/x5OP9I0T7TYdBhw4y8jPf3/+wyYhm6ww50y7favSWEUUgiAij4VhmO58oTWiPM0I31zhJwFLSR5FsjjDktr5sFga3byBO9YR+fTC1izIYyGrHcFf3v8sy7GJwVuG/8oh8hOOUjLVDixNc+oTHrNUtpqRVFtmPCetqHdJy3ebIuMQD6qasJhCR+y6UjgRvCuTEayGetpzxwUyO4Ian0iGQ9CGz+9Hfkwmk2Pb6mPEIBfKjtpyUpipUNKBdyYJMPaZZJSDIYE6ap73uPePWg2VpZUKAmEj1JiRiZV2Goxr2citUZLTzwRKXOCkH6TbhmsrjiRsxTgn6QUqLQhUQcUsKNDezDWu69GrsL/x/KKEf9S2XxRLa5zhD4z3opujuTHT9dnc9XUX5nlpGg+u6SwsfTFDxhKhQRtqHaWF+QzpPnJAoaNTjusLjS8RSYVK2UJVKj4r4ysBt3tG/ricwX1Rs/c+aIvpFfeI0JIOG9jARGRQ7b4uTXBcCpTge2zocqqq2f16oU69Ko0DJ0HBbeh9v0+LpWkvkfOAFi3FIrVdQgM5B6gr2tK4Sq1b4B2w8Fo5U1JU6IZsw1YsFjPbiJV6NVyUu7E1qAPYEqaWsdqlrfTaa3HMamtRjoeg7q0xtFi39RqTAcPM3uOszAtekZhBY6BoPXp1XtmsaTrBrhmQV2N2clA4M2EjztwTwjMjtBdED03r1QyUWeus/F878uEBIN5jD9QxUnZHxjMw180+EZxlW3od8fsBqpeRlNzzXxkew+2KzSjvkoyz6D5Q9Q7nEo5IRNOY3cMf8BkXqXuQd0AvoCYw8pkcNOpBw+AcSUV36Y9y0DsxrHB8wcl3LN/fX6M/40ga/T/g3usaC/4UzUjvWB4BCX53iiNEN606EnqUro+IAYl6vazpWx6dhWv1HJLw5tue0zOJ4T6w5GkjddUzaXCuSiZC2OVxyem4Un11FHfPpj587gOLgHo3s7Yv7r5XjvkTe/nO/i5Lcvt2+w+2iUC/qQsAAA==",
            'coverageUnitCharge'    =>  29_000,
            'coverageQuantity'      =>  1,
            'coverageTotalAmount'   =>  29_000,
            'extraHoursQuantity'    =>  0,
            'extraHoursUnityAmount' =>  0,
            'extraHoursTotalAmount' =>  0,
            'totalCoverageUnitCharge' => 61_085,
            'totalAmountPlusTotalCoverage' => 236_087,
            'taxFeeWithTotalCoverage'   => 23_609,
            'ivaFeeWithTotalCoverage'   => 49_342,
            'totalPriceWithTotalCoverage' => 309_038,
        ],$data);
    }

    #[Group("localiza_veh_avail_rate")]
    #[Group("localiza")]
    #[Test]
    public function get_data_of_category_FX_availability(): void {

        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-xml'))->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
        );

        $data = $localiza->getData();

        $this->assertContains([
            'categoryCode'          =>  "FX",
            'categoryDescription'   =>  "COMPACTO AUTOMÁTICO",
            'totalAmount'           =>  194_835,
            'estimatedTotalAmount'  =>  293_000,
            'vehicleDayCharge'      =>  194_835,
            'numberDays'            =>  1,
            'discountAmount'        =>  105_165,
            'discountPercentage'    =>  35,
            'taxFeeAmount'          =>  22_383,
            'taxFeePercentage'      =>  10,
            'IVAFeeAmount'          =>  46_782,
            'reference'             =>  "vwsAAB+LCAAAAAAABACNVsmO2zgQ/RVDZ6tB7ZJvai8TY5K20a0JAsQ5sG3aIUYSPZTUY6Thr5nDfEh+bIqiFmpxPH0w0KpXrxa+Iutdi9ifJN1ivqc41maa7ni+Y5uW52hT7SPNcjxn6YHuMZuzhFSor+/a+kDSnB7BcGB8BGL6PkKOb0zvIE0V+pnwDDP4MtWeALA5Ep5jSGqzWj5H4eQlCp8W4fMCMpuzAz2xCHN6FABk+IbhwvcFyfYszdkW2CFsgeNPNKUJcFrOA3IchCzfCMo/dxSNLzfRYc7pa5GzrM4L2iAYuCioNkIyvyeTj/SNE+06HQdsOcvJz39//sMmBzaZY86Zdv1WFzWPKeRCRFWeZdmB6WmtqchywpcX+EmBKS3iWLZq3NIEHzdL0uUbhGM9U0TPbMH2RSJsTSD4lw8/y3YsE4iW478KyPyI44xMtS3LsoJGhCcskwfPSKbNcl6QTtbrtBO7K5j7kBC6mrKEwom4TadwKlRYFSMkLrRtWLZnWI5v29DSTyTHB/AHw/uO79LJZNflfYwZVEPZTptNSqIaJUOEJ5KCgp9JTjkQCdROC8PHTbTTGqhsrnQQCBehlkaWVvG0mMBxUdCgpMafCTS5xMk4yHCtwFUCSdiccU6yM/RaSKiGitlQoIMZbEM3o1hjf+PFWUl/p62+KEwLnOMPjA+yM5Fp6Yahm0FkBDDfM9t6CGzfcwxPR9YMoVEOtY+SwdSRESEfHHo+1dy+0OQck0y4VEeoWsXn4RxL3PUW1x/nE2RRtu59CFphesEDPXSs4wRLGI0cmj70pSlOKoNSw0AUfWnVrbvdNtRrW+2x5WRfShwkMDwub2a7M+Q/IK/jWJa2TulejgPqm1Y0qUvrN3gNYrzUwZQSFdUh13IVxnJ4W7PSr1aS8pLszOsItoKpbawv1U553fvxHmvnxryfgnqB3UOLe7e5z2TCMLq3NCvrguckYXAw0LSBvHqPb94eOsGBvSevln700UG3TT/QgyPCunVwPWIcbOfV3isj17v7fx0ogpeAhI8DUI+kOh2Zz8h4t9eK0Cxb0cuduB+geznJyK34NfE93Lq8IOW6knMW3waq0WGJwjGJaZawW/gtPuGy9BDq3tMzuAmMfC9HSUM4MFhPMnG69Ec16L0c5jg54/Q7lg/xr9GfcSxJ/w948MwmQj/lYWQ3mO+AhL57zRGmq1ZvCwNJN9vEiEVdYxb0rYhPIrS6F0l4+23D6YkksCg4cseRvuq+NDpXlRIh7Wrn5PS+U7N+lAvQstmAbgPLhAartLYpF8BXjvkTe/nO/q5acv12/Q/ygFZqvwsAAA==",
            'coverageUnitCharge'    =>  29_000,
            'coverageQuantity'      =>  1,
            'coverageTotalAmount'   =>  29_000,
            'extraHoursQuantity'    =>  0,
            'extraHoursUnityAmount' =>  0,
            'extraHoursTotalAmount' =>  0,
            'totalCoverageUnitCharge' => 61_085,
            'totalAmountPlusTotalCoverage' => 226_920,
            'taxFeeWithTotalCoverage'   => 22_692,
            'ivaFeeWithTotalCoverage'   => 47_426,
            'totalPriceWithTotalCoverage' => 297_038,
        ],$data);
    }

    #[Group("localiza_veh_avail_rate")]
    #[Group("localiza")]
    #[Test]
    public function when_total_charge_data_is_not_found_throw_exception(): void {

        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-no-data-xml'))->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
        );

        $this->assertThrows(fn() => $localiza->getData(), HttpResponseException::class);
    }

    #[Group("localiza_veh_avail_rate")]
    #[Group("localiza")]
    #[Test]
    public function when_there_arent_prices_raise_exception(): void {
        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-noprices-xml'))->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
        );

        $this->assertThrows(fn() => $localiza->getData(), HttpResponseException::class);
    }

    #[Group("localiza_veh_avail_rate")]
    #[Group("localiza")]
    #[Test]
    public function when_prices_are_zero_raise_exception(): void {

        $xml = (View::make('localiza.tests.responses.vehavailrate.vehavailrate-zeroprices-xml'))->render();

        Http::fake([
            '*' =>  Http::response($xml, 200)
        ]);

        $pickupLocation = "AABOT";
        $returnLocation = "AABOT";
        $pickupDateTime = "2024-01-15T23:00:00";
        $returnDateTime = "2024-01-17T13:00:00";

        $localiza = new LocalizaAPIVehAvailRate(
            $pickupLocation,
            $returnLocation,
            $pickupDateTime,
            $returnDateTime,
        );

        $this->assertThrows(fn() => $localiza->getData(), HttpResponseException::class);
    }


}
