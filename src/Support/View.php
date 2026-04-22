<?php

declare(strict_types=1);

namespace App\Support;

use Smarty\Smarty;

final class View
{
    public function __construct(private readonly Smarty $smarty)
    {
    }

    public function render(string $template, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->display($template);
    }
}
