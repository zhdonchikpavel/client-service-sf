<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ListUsers extends BaseApiCommand
{
    protected $outputHeaders = ['ID', 'Name', 'Email', 'Group'];

    protected $apiEndpoint = '/users';

    protected function configure()
    {
        $this->setName('list-users');
        $this->setDescription('Loads users list from api');
        $this->addArgument('page', InputArgument::OPTIONAL, 'list page', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $page = $input->getArgument('page');
        return $this->getListResource($output, $page);
    }

    protected function prepareData() : array {
        $rows = array_map(function($item) {
            return [$item['id'], $item['name'], $item['email'], $item['userGroup']['name']];
        }, $this->apiResponse['hydra:member']);

        return $rows;
    }
}