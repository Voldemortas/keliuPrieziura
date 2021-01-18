<?php

namespace App\Service;

use App\Repository\AdminRepository;
use Symfony\Component\HttpFoundation\Response;

class AdminService
{

  private $adminRepository;
  public function __construct(AdminRepository $adminRepository)
  {
    $this->adminRepository = $adminRepository;
  }

  public function isAdmin(): bool
  {
    return $this->adminRepository->find(1)->getToggled();
  }
}
