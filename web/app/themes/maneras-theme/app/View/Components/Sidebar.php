<?php
/**
 * Sidebar component. The side bar can change its content and/or subcomponents order depending on the page.
 */

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public $type;

    public function __construct($type = 'default')
    {
        $this->type = $type;
    }

    public function render()
    {
        return view('components.sidebar');
    }
}
