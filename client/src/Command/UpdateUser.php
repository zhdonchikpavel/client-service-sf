<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UpdateUser extends BaseApiCommand
{
    protected $outputHeaders = ['ID', 'Name', 'Email', 'Group ID', 'Group Name'];

    protected $apiEndpoint = '/users/{id}';

    protected function configure()
    {
        $this->setName('update-user');
        $this->setDescription('Updates user');
        $this->addArgument('id', InputArgument::REQUIRED, 'user id');
        $this->addArgument('name', InputArgument::REQUIRED, 'user name');
        $this->addArgument('email', InputArgument::REQUIRED, 'user email');
        $this->addArgument('groupId', InputArgument::REQUIRED, 'user group ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $itemId = $input->getArgument('id');
        $inputData = [
            'name' => $input->getArgument('name'),
            'email' => $input->getArgument('email'),
            'userGroup' => '/api/user_groups/' . $input->getArgument('groupId'),
        ];
        
        return $this->updateItemResource($output, $itemId, $inputData);
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