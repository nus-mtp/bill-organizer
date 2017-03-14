<?php
/**
 * Created by PhpStorm.
 * User: kenan
 * Date: 13/3/17
 * Time: 2:52 AM
 */

namespace app\Exceptions;


class HighlightExceptionFunctionHandler extends \Whoops\Handler\Handler
{

    /**
     * @return int|null A handler may return nothing, or a Handler::HANDLE_* constant
     */
    public function handle()
    {
        $inspector = $this->getInspector();
        $frames    = $inspector->getFrames();
        $frames->map(function($frame){
            if ($function = $frame->getFunction()){
                $frame->addComment("This frame is within function '$function'", 'cpt-obvious');
            }
            return $frame;
        });

        // return nothing
    }
}