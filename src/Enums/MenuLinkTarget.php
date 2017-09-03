<?php

namespace Viviniko\Menu\Enums;

class MenuLinkTarget
{
    const SELF = '_self';
    const BLANK = '_blank';

    public static function values()
    {
        return [
            static::SELF => 'Same Tab/Window',
            static::BLANK => 'New Tab/Window',
        ];
    }
}