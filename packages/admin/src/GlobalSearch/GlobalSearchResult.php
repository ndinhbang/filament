<?php

namespace Filament\GlobalSearch;

class GlobalSearchResult
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $url;
    /**
     * @var mixed[]
     */
    public $details = [];
    public function __construct(string $title, string $url, array $details = [])
    {
        $this->title = $title;
        $this->url = $url;
        $this->details = $details;
    }
}
