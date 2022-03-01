<?php

namespace Filament\GlobalSearch;

class GlobalSearchResult
{
    public string $title;
    public string $url;
    public array $details = [];
    public function __construct(string $title, string $url, array $details = [])
    {
        $this->title = $title;
        $this->url = $url;
        $this->details = $details;
    }
}
