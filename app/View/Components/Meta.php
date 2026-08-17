<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Meta extends Component
{
    public function __construct(
        public string $title = 'Dashboard',
        public string $description = '',
        public string $keywords = '',
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.meta');
    }
}