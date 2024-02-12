<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum Regions: string
{
    use EnumToArray;

    case Volcano = "Volcano";
    case CinnabarGym = "Cinnabar Gym";
    case Mansion = "Mansion";
    case CinnabarLabKanto = "Cinnabar Lab - Kanto";
    case Hoenn = "Hoenn";

}