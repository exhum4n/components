<?php

declare(strict_types=1);

namespace Exhum4n\Components\Http\Requests;

/**
 * Class IPRequest.
 *
 * @property string ip
 */
abstract class IPRequest extends AbstractRequest
{
    /**
     * @param null $keys
     *
     * @return array
     */
    public function all($keys = null): array
    {
        $data = parent::all($keys);

        $data['ip'] = $this->ip();

        return $data;
    }
}
