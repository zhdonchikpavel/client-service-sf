<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateUserGroup extends BaseApiCommand
{
    protected $outputHeaders = ['Group ID', 'Group Name'];

    protected $apiEndpoint = '/user_groups';

    protected function configure()
    {
        $this->setName('create-group');
        $this->setDescription('Creates new user group');
        $this->addArgument('name', InputArgument::REQUIRED, 'group name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputData = [
            'name' => $input->getArgument('name'),
        ];
        
        return $this->createItemResource($output, $inputData);
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