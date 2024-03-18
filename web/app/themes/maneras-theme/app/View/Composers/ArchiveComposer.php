<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class ArchiveComposer extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'archive', // Asegúrate de que este slug coincida con el nombre de tu vista de archivo.
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'year'      => get_query_var('year'),
            'monthnum'  => get_query_var('monthnum'),
            'monthname' => $this->getMonthName(get_query_var('monthnum')),
        ];
    }

    /**
     * Convert month number to month name.
     *
     * @param int $monthnum
     * @return string
     */
    protected function getMonthName($monthnum)
    {
        return strftime("%B", mktime(0, 0, 0, $monthnum, 10));
    }
}
