<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UpdateUserGroup extends BaseApiCommand
{
    protected $outputHeaders = ['Group ID', 'Group Name'];

    protected $apiEndpoint = '/user_groups/{id}';

    protected function configure()
    {
        $this->setName('update-group');
        $this->setDescription('Updates existing user group');
        $this->addArgument('id', InputArgument::REQUIRED, 'group id');
        $this->addArgument('name', InputArgument::REQUIRED, 'group name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $itemId = $input->getArgument('id');
        $inputData = [
            'name' => $input->getArgument('name'),
        ];

        return $this->updateItemResource($output, $itemId, $inputData);
    }

    protected function prepareData() : array {
        $item = [];
        $item[] = [
            $this->apiResponse['id'], 
            $this->apiResponse['name'], 
        ];
        return $item;
    }
}