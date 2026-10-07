<?php

namespace App\Livewire\Pages\Seller\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Seller dashboard')]
class Index extends Component
{
    /**
     * Render the seller dashboard.
     */
    public function render()
    {
        return view('pages.seller.dashboard.index');
    }
}
