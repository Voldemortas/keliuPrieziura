<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Service\AdminService;

class AppExtension extends AbstractExtension
{
  private $adminService;
  public function __construct(AdminService $adminService)
  {
    $this->adminService = $adminService;
  }

  public function getFunctions()
  {
    return [
      new TwigFunction('is_admin', [$this, 'getAdmin']),
    ];
  }

  public function getAdmin(): bool
  {
    return $this->adminService->isAdmin();
  }
}
