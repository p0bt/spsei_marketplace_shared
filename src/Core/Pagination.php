<?php

class Pagination
{
    public $items_per_page = 1;
    
    private $items_count;
    private $current_page;
    private $page_url;
    private $page_count;

    public function __construct($items_count, $page_url, $key_name)
    {
        $this->items_count = $items_count;
        $this->page_url = $page_url;
        $this->set_items_per_page($this->items_per_page);
        $this->current_page = 1;
        $this->key_name = $key_name;
    }

    public function render()
    {
        $result = '';
        
        for($i = 0; $i < $this->page_count; $i++)
        {
            $result .= '<a href="' . $this->generate_url($i) . '" class="me-2 text-decoration-none link-dark' . (($this->get_current_page() == $i) ? " fw-bold" : "") . '">' . $i + 1 . '</a>';
        }

        return $result;
    }

    public function get_limit_a()
    {
        return $this->get_current_page() * $this->get_items_per_page();
    }

    public function get_limit_b()
    {
        return $this->get_items_per_page();
    }
    
    public function set_items_per_page($items_per_page)
    {
        $this->items_per_page = $items_per_page;
        $this->page_count = intval(ceil(($this->items_count / $this->items_per_page)));
        if($this->page_count == 0) $this->page_count = 1;
    }

    public function get_items_per_page()
    {
        return $this->items_per_page;
    }

    private function generate_url($page_index)
    {
        return $this->page_url . '?' . http_build_query(array_merge($_GET, [$this->key_name => ($page_index * $this->items_per_page)]));
    }
        
    private function get_current_page()
    {
        return isset($_GET[$this->key_name]) ? $_GET[$this->key_name] / $this->get_items_per_page() : 0;
    }
}