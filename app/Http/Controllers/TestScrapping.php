<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;

class TestScrapping extends Controller
{
    private $results = array();
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /* $code = file_get_contents (“http://quotes.toscrape.com”); 
        echo "<pre>";
        print_r($page);
        echo "</pre>"; */
        /* return view('p1roject.content.home.scraper'); */
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
