<?php

namespace App\Livewire\Pages\Admin\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Admin dashboard')]
class Index extends Component
{
    /**
     * Render the component.
     */
    public function render()
    {
        return view('pages.admin.dashboard.index');
    }
}
