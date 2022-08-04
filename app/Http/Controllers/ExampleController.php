<?php

namespace App\Http\Controllers;

use Solarium;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function ping(){
        $adapter = new Solarium\Core\Client\Adapter\Curl();
        // $eventDispatcher = new Symfony\Component\EventDispatcher\EventDispatcher();

        $config = [ 'endpoint' => [
            'localhost' => [
                'host' => '172.17.62.112',
                'port' => 8983,
                'path' => '/solr/domas_shard1_replica1',
                'core' => '',
            ]
        ]];
        // var_dump($adapter);die;
        $client = new Solarium\Client($adapter, new EventDispatcher, $config);
        $ping = $client->createPing();

        // var_dump($client);die;
        // execute the ping query
        try {// get a select query instance
            $query = $client->createQuery($client::QUERY_SELECT);
            $result = $client->ping($ping);
            // echo '<br/><pre>';
            // var_dump($query);
            // echo '</pre>';//die;
            
            // this executes the query and returns the result
            // $resultset = $client->execute($query);
            
            // display the total number of documents found by Solr
            // echo 'NumFound: '.$resultset->getNumFound();
            
            echo 'Ping query successful';
            echo '<br/><pre>';
            // var_dump($result);
            echo '</pre>';
        } catch (Exception $e) {
            echo 'Ping query failed';
        }

        return 'Solarium library version: ' . Solarium\Client::getVersion() . ' - ';
    }

    //
}
