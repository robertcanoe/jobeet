<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

class IndexController extends BaseController
{
    public function homeAction(): void
    {
        $this->redirect(\url('jobs'));
    }
}
