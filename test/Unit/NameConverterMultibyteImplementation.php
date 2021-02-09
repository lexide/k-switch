<?php

namespace Lexide\KSwitch\Test\Unit;

use Lexide\KSwitch\NameConverterMultibyteTrait;
use Lexide\KSwitch\NameConverterTrait;

/**
 * NameConverterImplementation
 */
class NameConverterMultibyteImplementation
{
    use NameConverterMultibyteTrait {
        toStudlyCaps as public;
        toCamelCase as public;
        toSplitCase as public;
        convertArrayKeys as public;
    }
}
