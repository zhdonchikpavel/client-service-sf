<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GetUser extends BaseApiCommand
{
    protected $outputHeaders = ['ID', 'Name', 'Email', 'Group ID', 'Group Name'];

    protected $apiEndpoint = '/users/{id}';

    protected function configure()
    {
        $this->setName('get-user');
        $this->setDescription('Loads user from api');
        $this->addArgument('id', InputArgument::REQUIRED, 'item id');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        return $this->getItemResource($output, $id);
    }

    protected function prepareData() : array {
        $item = [];
        $item[] = [
            $this->apiResponse['id'], 
            $this->apiResponse['name'], 
            $this->apiResponse['email'], 
            $this->apiResponse['userGroup']['id'], 
            $this->apiResponse['userGroup']['name']
        ];
        return $item;
    }
}