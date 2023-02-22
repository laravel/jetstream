<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class NavigationMenu extends Component
{
    /**
     * The component's listeners.
     *
     * @var array<string, string>
     */
    protected $listeners = [
        'refresh-navigation-menu' => '$refresh',
    ];

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('navigation-menu');
    }
}
