<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class header extends Component
{
    public $showToggle;

    public function __construct($showToggle = true)
    {
        $this->showToggle = $showToggle;
    }

    public function render(): View|Closure|string
    {
        return view('components.header');
    }
}
