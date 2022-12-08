<?php

namespace App\ViewVariables;

class ViewErrorVariables
{
    public function getName(): string
    {
        return 'errors';
    }

    public function getValue(): array
    {
        return $_SESSION['errors'] ?? [];
    }
}