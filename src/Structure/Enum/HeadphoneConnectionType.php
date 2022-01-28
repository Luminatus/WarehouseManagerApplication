<?php

namespace Lumie\WarehouseManagerApplication\Structure\Enum;

enum HeadphoneConnectionType: string
{
    case JACK_3_5_MM = 'jack_3_5_mm';
    case JACK_6_35_MM = 'jack_6_35_mm';
    case USB = 'usb';
    case BLUETOOTH = 'bluetooth';
    case WIFI = 'wifi';
}
