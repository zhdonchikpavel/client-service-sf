<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ListUserGroups extends BaseApiCommand
{
    protected $outputHeaders = ['ID', 'Name'];

    protected $apiEndpoint = '/user_groups';

    protected function configure()
    {
        $this->setName('list-groups');
        $this->setDescription('Loads groups from api');
        $this->addArgument('page', InputArgument::OPTIONAL, 'list page', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->getListResource($output);
    }

    protected function prepareData() : array {
        $rows = array_map(function($item) {
            return [$item['id'], $item['name']];
        }, $this->apiResponse['hydra:member']);

        return $rows;
    }
}