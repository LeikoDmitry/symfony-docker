<?php

namespace App\Command;

use App\Service\RoleService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:make:admin', description: 'Promotes user to be admin')]
class MakeAdminCommand extends Command
{
    public function __construct(private RoleService $roleService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('user-id', InputArgument::REQUIRED, 'User id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->roleService->grantAdmin((int) $input->getArgument('user-id'));

        return Command::SUCCESS;
    }
}
