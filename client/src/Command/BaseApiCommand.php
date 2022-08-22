<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Console\Helper\Table;

abstract class BaseApiCommand extends Command
{
    const ID_PLACEHOLDER = '{id}';

    private $requestOptions = [
        'headers' => [
            'Accept' => 'application/ld+json',
        ]
    ];

    private $apiUrl;

    protected $client;

    protected $outputHeaders = [];

    protected $apiEndpoint = '';

    protected $apiResponse;

    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
        $this->apiUrl = $_ENV['API_URL'];
    }

    protected function getListResource(OutputInterface $output, $pageNum = 1, $itemId = '') {
        $requestOptions = $this->requestOptions;
        $requestOptions['query'] = ['page' => $pageNum];
        $endpoint = $this->apiUrl . str_replace(self::ID_PLACEHOLDER, $itemId, $this->apiEndpoint);
        $response = $this->client->request(
            'GET',
            $endpoint,
            $requestOptions
        );

        return $this->outputResponseTable($output, $response);
    }

    protected function getItemResource(OutputInterface $output, $itemId) {
        $requestOptions = $this->requestOptions;
        $endpoint = $this->apiUrl . str_replace(self::ID_PLACEHOLDER, $itemId, $this->apiEndpoint);
        $response = $this->client->request(
            'GET',
            $endpoint,
            $requestOptions
        );

        return $this->outputResponseTable($output, $response);
    }

    protected function createItemResource(OutputInterface $output, $inputData) {
        $requestOptions = $this->requestOptions;
        $requestOptions['headers']['content-type'] = 'application/json';
        $requestOptions['body'] = json_encode($inputData);
        $endpoint = $this->apiUrl . $this->apiEndpoint;
        $response = $this->client->request(
            'POST',
            $endpoint,
            $requestOptions
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 201) {
            return $this->outputApiFailureMessage($output, 'CREATING', $response);
        }

        $output->writeln(['--- NEW ITEM CREATED ---']);

        return $this->outputResponseTable($output, $response);
    }

    protected function updateItemResource(OutputInterface $output, $itemId, $inputData) {
        $requestOptions = $this->requestOptions;
        $requestOptions['headers']['content-type'] = 'application/json';
        $requestOptions['body'] = json_encode($inputData);
        $endpoint = $this->apiUrl . str_replace(self::ID_PLACEHOLDER, $itemId, $this->apiEndpoint);
        $response = $this->client->request(
            'PUT',
            $endpoint,
            $requestOptions
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            return $this->outputApiFailureMessage($output, 'UPDATING', $response);
        }

        $output->writeln(['--- ITEM UPDATED ---']);

        return $this->outputResponseTable($output, $response);
    }

    protected function deleteItemResource(OutputInterface $output, $itemId) {
        $requestOptions = $this->requestOptions;
        $requestOptions['headers']['content-type'] = 'application/json';
        $endpoint = $this->apiUrl . str_replace(self::ID_PLACEHOLDER, $itemId, $this->apiEndpoint);
        $response = $this->client->request(
            'DELETE',
            $endpoint,
            $requestOptions
        );

        $statusCode = $response->getStatusCode();
        
        if ($statusCode !== 204) {
            return $this->outputApiFailureMessage($output, 'DELETING', $response);
        }

        $output->writeln(['--- ITEM DELETED ---']);

        return self::SUCCESS;        
    }

    private function outputResponseTable(OutputInterface $output, $response) {
        $this->apiResponse = $response->toArray();
        $table = new Table($output);
        $this->addListHeader($table);
        $this->addListFooter($table);
        $table
            ->setHeaders($this->outputHeaders)
            ->setRows($this->prepareData())
        ;

        $table->render();

        return self::SUCCESS;
    }

    private function outputApiFailureMessage(OutputInterface $output, $operation, $response) {
        $this->apiResponse = $response->toArray();
        $output -> writeln([
            '--- ERROR ' . strtoupper($operation) . ' ITEM ---',
            '-------------------------------',
            $this->apiResponse['hydra:description'] ?? 'unknown error',
        ]);

        return self::FAILURE;
    }

    private function addListHeader(Table $outputTable) {
        if (!$this->apiResponse['hydra:totalItems']) {
            return;
        }
        
        $outputTable->setHeaderTitle("Total Items: {$this->apiResponse['hydra:totalItems']}");
    }

    private function addListFooter(Table $outputTable) {
        if (!$this->apiResponse['hydra:totalItems']) {
            return;
        }
   
        $currentPage = $this->getApiPageNumber('@id');
        $totalPages = $this->getApiPageNumber('hydra:last');

        if (!$currentPage || !$totalPages) {
            return;
        }

        $outputTable->setFooterTitle("Page $currentPage/$totalPages");
    }

    private function getApiPageNumber($paramName) {
        if (!$this->apiResponse['hydra:view'][$paramName]) {
            return null;
        }

        $search = [$this->apiResponse['@id'], '?page='];
        $replace = ['', ''];
        return str_replace($search, $replace, $this->apiResponse['hydra:view'][$paramName]);
    }

    abstract protected function prepareData() : array;
}