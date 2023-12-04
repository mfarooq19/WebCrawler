<?php

class WebCrawler {
    private $urlQueue = [];
    private $visitedUrls = [];
    private $depthLimit = 5;

    // Init crawler with seed
    public function __construct($seedUrl) {
        $this->urlQueue[] = $seedUrl;
    }

    // Starting point for crawler
    public function crawl() {
        while (!empty($this->urlQueue)) {
            $currentUrl = array_shift($this->urlQueue);

            // Check prev visits, if any, to url
            if (!in_array($currentUrl, $this->visitedUrls)) {
                $htmlContent = $this->fetchContent($currentUrl);

                // Branch executed in case of successful html retrieval
                if ($htmlContent !== false) {
                    $this->parseAndExtractData($htmlContent);
                    $this->visitedUrls[] = $currentUrl;
                    $this->extractLinks($htmlContent, $currentUrl);
                }
            }
        }
    }

    // Fetch Content Method sends an HTTP request and retrieves HTML content
    private function fetchContent($url) {
        $htmlContent = file_get_contents($url);

        // Handle error if content cannot be fetched
        if ($htmlContent === false) {
            echo "Error fetching content from: $url\n";
        }

        return $htmlContent;
    }

    // Parse and Extract Data Method extracts data from HTML content
    private function parseAndExtractData($htmlContent) {
        $dom = new DOMDocument;
        @$dom->loadHTML($htmlContent); // Suppress warnings

        $articles = $dom->getElementsByTagName('article');

        foreach ($articles as $article) {
            $titleElements = $article->getElementsByTagName('h2');
            if ($titleElements->length > 0) {
                $title = $titleElements->item(0)->textContent;

                // Display or log the extracted data
                echo "Article Title: $title\n";
            }
        }
    }

    // Extract Links Method extracts hyperlinks from HTML content
    private function extractLinks($htmlContent, $currentUrl) {
        $dom = new DOMDocument;
        @$dom->loadHTML($htmlContent); // Suppress warnings

        $links = $dom->getElementsByTagName('a');
        foreach ($links as $link) {
            $url = $link->getAttribute('href');

            // Convert relative URLs to absolute URLs
            $url = $this->makeAbsoluteUrl($url, $currentUrl);

            // Ensure the depth limit is not exceeded
            $depth = $this->calculateDepth($currentUrl);
            if ($depth <= $this->depthLimit && !in_array($url, $this->urlQueue) && !in_array($url, $this->visitedUrls)) {
                $this->urlQueue[] = $url;
            }
        }
    }

    // Make Absolute URL Method converts relative URLs to absolute URLs
    private function makeAbsoluteUrl($url, $baseUrl) {
        $urlComponents = parse_url($url);

        // If the URL is relative, convert to absolute
        if (empty($urlComponents['scheme'])) {
            $baseUrlComponents = parse_url($baseUrl);
            $url = $baseUrlComponents['scheme'] . '://' . $baseUrlComponents['host'] . $url;
        }

        return $url;
    }

    // Calculate Depth Method calculates the depth of a URL in relation to the seed URL
    private function calculateDepth($url) {
        $urlParts = parse_url($url);
        $path = $urlParts['path'] ?? '';
        $depth = count(array_filter(explode('/', $path)));

        return $depth;
    }
}

// Function for crawling (replace with actual crawling logic)
public function crawl() {
    while (!empty($this->urlQueue)) {
        $currentUrl = array_shift($this->urlQueue);

        // Check prev visits, if any, to url
        if (!in_array($currentUrl, $this->visitedUrls)) {
            $htmlContent = $this->fetchContent($currentUrl);

            // Branch executed in case of successful HTML retrieval
            if ($htmlContent !== false) {
                $this->parseAndExtractData($htmlContent);
                $this->visitedUrls[] = $currentUrl;
                $this->extractLinks($htmlContent, $currentUrl);
            }
        }
    }
}

// Function for searching content (replace with actual search logic)
public function searchContent() {
    $searchString = "your_search_string"; // Replace with the actual string you want to search for

    while (!empty($this->urlQueue)) {
        $currentUrl = array_shift($this->urlQueue);

        // Check prev visits, if any, to url
        if (!in_array($currentUrl, $this->visitedUrls)) {
            $htmlContent = $this->fetchContent($currentUrl);

            // Branch executed in case of successful HTML retrieval
            if ($htmlContent !== false) {
                // Replace this with actual search logic
                if (strpos($htmlContent, $searchString) !== false) {
                    echo "Found in: $currentUrl\n";
                }

                $this->visitedUrls[] = $currentUrl;
                $this->extractLinks($htmlContent, $currentUrl);
            }
        }
    }
}

// Check which button was clicked
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'crawl':
            echo crawl();
            break;
        case 'search':
            echo searchContent();
            break;
        default:
            echo "Invalid action.";
            break;
    }
}

// Example usage
$seedUrl = "https://www.webmd.com/eye-health/nearsightedness-myopia";
$crawler = new WebCrawler($seedUrl);
$crawler->crawl();
?>
