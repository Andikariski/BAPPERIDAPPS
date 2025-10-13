<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public $title;
    public $size;
    public $closable;

    public function __construct($title = '', $size = 'modal-lg', $closable = true)
    {
        $this->title = $title;
        $this->size = $size;
        $this->closable = $closable;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal');
    }
}
