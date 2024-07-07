<?php

namespace App\Services;

use App\Core\Helper;
use App\Dto\SaleDto;
use App\Dto\SumDto;
use App\Dto\UserDto;
use App\Enums\Currency;
use App\Factories\SaleDtoFactory;
use App\Factories\SumDtoFactory;
use Exception;

class DeviceService extends Service
{
    private string $deviceId;

    public function __construct(UserDto $dto, $deviceId)
    {
        $this->deviceId = $deviceId;
        parent::__construct($dto);
    }

    protected function getMainRoute(): string
    {
        return 'device/' . $this->deviceId;
    }

    /**
     * @throws Exception
     */
    public function getSalesToday(?int $limit = 10): array
    {
        $resp = $this->api->get($this->getRoute('sales'), [
            'deviceId' => $this->deviceId,
            'limit' => $limit,
            'from' => Helper::getDateFromDays(0),
            'to' => Helper::getDateFromDays(1)
        ]);

        $data['Currency'] = Currency::get($resp['Currency']['Code']);
        $data['Next'] = $resp['Next'];
        foreach ($resp['Items'] as $device) {
            $data['Sales'][] = SaleDtoFactory::make($device);
        }

        return $data;
    }

    /**
     * @throws Exception
     */
    public function getSumToday(): SumDto
    {
        $resp = $this->api->get($this->getRoute('sales/sum'), [
            'deviceId' => $this->deviceId,
            'limit' => 50,
            'from' => Helper::getDateFromDays(0),
            'to' => Helper::getDateFromDays(1)
        ]);

        return SumDtoFactory::make($resp);
    }

}