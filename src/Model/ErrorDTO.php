<?php

namespace App\Model;

class ErrorDTO
{
    public string $code;
    public string $description;

    public function __construct(string $code, string $description)
    {
        $this->code = $code;
        $this->description = $description;
    }
}