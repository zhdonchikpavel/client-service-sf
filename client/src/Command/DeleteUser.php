<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DeleteUser extends BaseApiCommand
{
    protected $apiEndpoint = '/users/{id}';

    protected function configure()
    {
        $this->setName('delete-user');
        $this->setDescription('Deletes user');
        $this->addArgument('id', InputArgument::REQUIRED, 'user id');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $itemId = $input->getArgument('id');
        return $this->deleteItemResource($output, $itemId);
    }

    protected function prepareData() : array {
        return [];
    }
}