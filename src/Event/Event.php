<?php

declare(strict_types=1);

namespace Pengxul\Payf\Event;

use Pengxul\Payf\Rocket;

class Event
{
    public ?Rocket $rocket = null;

    public function __construct(?Rocket $rocket = null)
    {
        $this->rocket = $rocket;
    }
}
