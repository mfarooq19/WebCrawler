<?php

class WebCrawler {
    private $urlQueue = []; 
    private $visitedUrls = [];
    private $depthLimit = 5;
    
    // Init crawler with seed
    public function __construct($seedUrl) {
        $this->urlQueue[] = $seedUrl;
    }
