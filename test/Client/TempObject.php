<?php

namespace MoceanTest\Client;

use Mocean\Client;

class TempObject implements Client\ClientAwareInterface
{
    use Client\ClientAwareTrait;
}
