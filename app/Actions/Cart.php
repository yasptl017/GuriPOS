<?php

namespace app\Actions;

class Cart extends \Cart
{

    public static function restore($indentifier)
    {
        //dump('identifier');
        parent::restore($indentifier);
        $items = parent::content();
        parent::store($indentifier);
        return $items;
    }

}
