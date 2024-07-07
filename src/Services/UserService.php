<?php

namespace App\Services;


use App\Core\Helper;
use App\Dto\DeviceDto;
use App\Dto\SumDto;
use App\Enums\Currency;
use App\Factories\DeviceDtoFactory;
use App\Factories\SaleDtoFactory;
use App\Factories\SumDtoFactory;
use Exception;

final class UserService extends Service
{
    protected function getMainRoute(): string
    {
        return $this->userDto->uuid;
    }

    /**
     * @throws Exception
     */
    public function getDevicesDto(): array
    {
        $resp = $this->api->get($this->getRoute('devices'));
        $devices = [];
        foreach ($resp['devices'] as $device) {
            $devices[] = DeviceDtoFactory::make($device);
        }
        return $devices;
    }


    public function getDevicesId(): array
    {
        $resp = $this->api->get($this->getRoute('devices'));
        $devices = [];
        foreach ($resp['devices'] as $device) {
            $id = $device['Id'];
            $address = $device['Info']['Address'];
            $devices[$id] = $this->rearrangeAddress($address);
        }

        return $devices;
    }

    private function rearrangeAddress($address): string
    {
        // Регулярное выражение для поиска части адреса до первой запятой и оставшейся части адреса
        $pattern = '/^([^,]+),\s*(.*)$/';
        // Замена частей адреса
        $replacement = '$2, $1';
        // Применение замены
        return preg_replace($pattern, $replacement, $address);
    }

    /**
     * @throws Exception
     */
    public function getSales(int $period = 1, ?string $next = null): array
    {
        $data = [
            'userId' => $this->userDto->uuid,
            'limit' => 10,
            'from' => Helper::getDateFromDays(-$period + 1),
            'to' => Helper::getDateFromDays(1),
        ];

        if (!is_null($next)) {
            [$pk, $sk] = explode('.', $next);
            $nextArr = [
                'pk' => 'SL#' . $pk,
                'sk_gspk' => $sk,
                'gspk' => $this->userDto->uuid,
                'gssk3' => $sk,
            ];
            $data['next'] = base64_encode(json_encode($nextArr));
        }


        $resp = $this->api->get($this->getRoute('sales'), $data);
        $data['Currency'] = Currency::get($resp['Currency']['Code']);

        foreach ($resp['Items'] as $sale) {
            $data['Sales'][] = SaleDtoFactory::make($sale);
        }

        if(!empty($resp['Next']))
        {
            $nextDecode = json_decode(base64_decode($resp['Next']), true);
            $nextStr = substr($nextDecode['pk'], 3) . '.' . $nextDecode['sk_gspk'];
            $data['Next'] = $nextStr;
        }

        return $data;
    }

    /**
     * @throws Exception
     */
    public function getSum(int $period = 1): SumDto
    {
        $resp = $this->api->get($this->getRoute('sales/sum'), [
            'userId' => $this->userDto->uuid,
            'from' => Helper::getDateFromDays(-$period + 1),
            'to' => Helper::getDateFromDays(1),
        ]);

        return SumDtoFactory::make($resp);
    }
}