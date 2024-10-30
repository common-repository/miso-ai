<?php

namespace Miso\Exceptions;

class DataFormatException extends \Exception {

protected $message;
protected $data;

public function __construct(string $message, array $data) {
    $this->message = $message;
    $this->data = $data;
    parent::__construct('Data format error: ' . $message . implode(' ', $data));
}

public function getData() {
    return $this->data;
}

public function __toString() {
    return __CLASS__ . ': ' . $this->message . implode(' ', $this->data);
}

}
