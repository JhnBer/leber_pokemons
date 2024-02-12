<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum PokemonShapes: string
{
    use EnumToArray;

    case Head = "head";
    case HeadLegs = "head_legs";
    case Fins = "fins";
    case Wings = "wings";

}