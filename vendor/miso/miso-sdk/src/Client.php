<?php

namespace Miso;

class Client {

    protected $core;

    public $products;

    public function __construct($args) {
        $core = $this->core = new Core($args);
        $this->products = new Products($core);
    }

}
