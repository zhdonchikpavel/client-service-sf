<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DeleteUserGroup extends BaseApiCommand
{
    protected $apiEndpoint = '/user_groups/{id}';

    protected function configure()
    {
        $this->setName('delete-group');
        $this->setDescription('Deletes user group');
        $this->addArgument('id', InputArgument::REQUIRED, 'group id');
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