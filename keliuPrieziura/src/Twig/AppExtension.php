<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Repository\AdminRepository;

class AppExtension extends AbstractExtension
{
  private $adminRepository;
  public function __construct(AdminRepository $adminRepository)
  {
    $this->adminRepository = $adminRepository;
  }

  public function getFunctions()
  {
    return [
      new TwigFunction('is_admin', [$this, 'getAdmin']),
    ];
  }

  public function getAdmin(): bool
  {
    return $this->adminRepository->findOneBy(['id' => 1])->getToggled();
  }
}
