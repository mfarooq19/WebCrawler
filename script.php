<?php

class WebCrawler {
    private $urlQueue = []; 
    private $visitedUrls = [];
    private $depthLimit = 5;
    
    // Init crawler with seed
    public function __construct($seedUrl) {
        $this->urlQueue[] = $seedUrl;
    }
 // Starting point for crawla
    public function crawl() {
        while (!empty($this->urlQueue)) {
            $currentUrl = array_shift($this->urlQueue);

            // Check prev visits, if any, to url
            if (!in_array($currentUrl, $this->visitedUrls)) {
                $htmlContent = $this->fetchContent($currentUrl);

                // Branch executed in case of successful html retireval 
                if ($htmlContent !== false) {
                    $this->parseAndExtractData($htmlContent);
                    $this->visitedUrls[] = $currentUrl;
                    $this->extractLinks($htmlContent, $currentUrl);
                }
            }
        }
    }
