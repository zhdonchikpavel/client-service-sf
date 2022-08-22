<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GetUserGroup extends BaseApiCommand
{
    protected $outputHeaders = ['Group ID', 'Group Name'];

    protected $apiEndpoint = '/user_groups/{id}';

    protected function configure()
    {
        $this->setName('get-group');
        $this->setDescription('Loads group from api');
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
        ];
        return $item;
    }
}