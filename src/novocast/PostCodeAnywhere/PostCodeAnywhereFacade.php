<?php namespace novocast\PostCodeAnywhere;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Html\HtmlBuilder
 */
class PostCodeAnywhereFacade extends Facade
{

    /**
     * Get the registered name of the component.
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'PostCodeAnywhere';

    }
}
