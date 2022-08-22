<?php

namespace App\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ListGroupUsers extends BaseApiCommand
{
    protected $outputHeaders = ['ID', 'Name', 'Email', 'Group'];

    protected $apiEndpoint = '/group_users/{id}';

    protected function configure()
    {
        $this->setName('list-group-users');
        $this->setDescription('Loads group users list from api');
        $this->addArgument('groupId', InputArgument::REQUIRED, 'user group id');
        $this->addArgument('page', InputArgument::OPTIONAL, 'list page', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $groupId = $input->getArgument('groupId');
        $page = $input->getArgument('page');
        return $this->getListResource($output, $page, $groupId);
    }

    protected function prepareData() : array {
        $rows = array_map(function($item) {
            return [$item['id'], $item['name'], $item['email'], $item['userGroup']['name']];
        }, $this->apiResponse['hydra:member']);

        return $rows;
    }
}