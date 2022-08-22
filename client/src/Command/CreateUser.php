<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateUser extends BaseApiCommand
{
    protected $outputHeaders = ['ID', 'Name', 'Email', 'Group ID', 'Group Name'];
    
    protected $apiEndpoint = '/users';

    protected function configure()
    {
        $this->setName('create-user');
        $this->setDescription('Creates new user');
        $this->addArgument('name', InputArgument::REQUIRED, 'user name');
        $this->addArgument('email', InputArgument::REQUIRED, 'user email');
        $this->addArgument('groupId', InputArgument::REQUIRED, 'user group ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputData = [
            'name' => $input->getArgument('name'),
            'email' => $input->getArgument('email'),
            'userGroup' => '/api/user_groups/' . $input->getArgument('groupId'),
        ];
        

        return $this->createItemResource($output, $inputData);
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