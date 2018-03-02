<?php

namespace Lexide\KSwitch\Test;

use Lexide\KSwitch\NameConverterTrait;

/**
 * NameConverterImplementation
 */
class NameConverterImplementation
{
    use NameConverterTrait {
        toStudlyCaps as public;
        toSplitCase as public;
        convertArrayKeys as public;
    }
}
