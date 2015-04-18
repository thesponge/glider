<?php

trait Singleton {

    protected static $instance;
    final public static function getInstance() {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static(func_get_args());
    }

    final private function __construct() {
        # {{{ Dead code
        /*
         * This is a neat, not appropriate, object creation via ReflectionClass,
         * when the number of parameters is uncertain.
         */
        #$reflection = new ReflectionClass(__CLASS__);
        #return $reflection->newInstanceArgs(func_get_args());

        /*
         * This was the old post-construct callback
         */
        //$this->init(func_get_args()); # }}}

        //array_push(func_get_args(), $C);
        //print "construct: ";
        //var_dump(func_get_args());

        // Hack needed - double usage of func_get_args() increases array's depth by 1
        $args = func_get_args();
        call_user_func_array(array(__CLASS__, "init"), $args[0]);

    }

    protected function init() {}
    final private function __wakeup() {}
    final private function __clone() {}
}
