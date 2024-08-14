<?php

namespace App\Support;

class Breadcrumb
{
    protected $breadcrumbs = [];

    public function add($label, $url = null)
    {
        $this->breadcrumbs[] = [
            'label' => $label,
            'url' => $url,
        ];

        return $this;
    }

    public function get()
    {
        return $this->breadcrumbs;
    }
}