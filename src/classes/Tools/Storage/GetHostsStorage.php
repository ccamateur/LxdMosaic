<?php

namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Tools\Hosts\GetClustersAndStandaloneHosts;
use dhope0000\LXDClient\Objects\Host;

class GetHostsStorage
{
    public function __construct(
        GetClustersAndStandaloneHosts $getClustersAndStandaloneHosts
    ) {
        $this->getClustersAndStandaloneHosts = $getClustersAndStandaloneHosts;
    }

    public function getAll()
    {
        $clusters = $this->getClustersAndStandaloneHosts->get();

        $stats = [
            "storage"=>[
                "total"=>0,
                "used"=>0
            ],
            "inodes"=>[
                "total"=>0,
                "used"=>0
            ],
        ];

        foreach ($clusters["clusters"] as $clusterIndex => $cluster) {
            foreach ($cluster["members"] as $hostIndex => &$host) {
                $pools = $this->getHostPools($host);
                $stats = $this->calculateStats($stats, $pools);
                $host->setCustomProp("pools", $pools);
            }
        }

        foreach ($clusters["standalone"]["members"] as $hostIndex => &$host) {
            $pools = $this->getHostPools($host);
            $stats = $this->calculateStats($stats, $pools);
            $host->setCustomProp("pools", $pools);
        }

        return [
            "hostDetails"=>$clusters,
            "stats"=>$stats
        ];
    }

    private function calculateStats($stats, $pools)
    {
        foreach ($pools as $pool) {
            $stats["storage"]["total"] += $pool["resources"]["space"]["total"];
            $stats["storage"]["used"] += $pool["resources"]["space"]["used"];

            $stats["inodes"]["total"] += $pool["resources"]["inodes"]["total"];
            $stats["inodes"]["used"] += $pool["resources"]["inodes"]["used"];
        }
        return $stats;
    }

    private function getHostPools(Host $host)
    {
        if (!$host->hostOnline()) {
            return [];
        }

        //TODO Recursion
        $pools = $host->storage->all();
        $withResources = [];
        foreach ($pools as $pool) {
            $withResources[] = [
                "name"=>$pool,
                "resources"=>$host->storage->resources->info($pool)
            ];
        }

        return $withResources;
    }
}
