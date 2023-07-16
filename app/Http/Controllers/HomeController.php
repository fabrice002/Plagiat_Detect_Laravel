<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use JanDrda\LaravelGoogleCustomSearchEngine\LaravelGoogleCustomSearchEngine;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('project.content.home.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        /* $fulltext = new LaravelGoogleCustomSearchEngine(); // initialize
        $results = $fulltext->getResults("Database");
        dd($results);
        print_r($results); */
        #$ffi = FFI::cdef(file_get_contents('points.h'), __DIR__ . '/points.so');
        #$response = Http::get('https://www.justintodata.com/logistic-regression-example-in-python/');
        #$response = Http::get('https://python.sdv.univ-paris-diderot.fr/03_affichage/');
        #$response = Http::get('https://fr.wikipedia.org/wiki/Base_de_donn%C3%A9es');
        /* $response = Http::acceptJson()->get('https://www.google.com/search?q=Comment+supprimer+les+balises+HTML+d%27un+texte+en+c%2B%2B%3F&rlz');
        return $response->headers();
        return Http::dd()->get('https://www.google.com/search?q=Comment+supprimer+les+balises+HTML+d%27un+texte+en+c%2B%2B%3F&rlz'); */
        $response = Http::get('https://www.google.com/search?q=Comment+supprimer+les+balises+HTML+d%27un+texte+en+c%2B%2B%3F&rlz');
        dd($response->body());
        return Http::get('https://www.google.com/search?q=Comment+supprimer+les+balises+HTML+d%27un+texte+en+c%2B%2B%3F&rlz');

        /* $res=Http::get('https://www.google.com/search',[
            'q'=>'Base+de+donnee'
        ]); */
        #dd($res->ok());
        #return Http::dd()->get('https://www.justintodata.com/logistic-regression-example-in-python/');
        /* $jsonD=$response->json();
        return $response->collect(); */
        #return $this->test_input($response->body());

    }
    protected function test_input($data) {
        $data = trim($data);
        /* $data = strip_tags($data); */
        /* $data = stripslashes($data);
        $data = htmlspecialchars($data); */
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        /* dd($contents); */
        $output="";
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $name = time().rand(1,100).'.'.$file->extension();
            $path=$file->storeAs('upload', $name, 'public');
         }
        if ($request->hasfile('file2')) {
            $file = $request->file('file2');
            $name = time().rand(1,100).'.'.$file->extension();
            $path2=$file->storeAs('upload', $name, 'public');
         }
         dd($request->file('file')->store('upload', 'public'));
         $contents = storage_path($path);
         $content2 = storage_path($path2);
         echo $contents."<br>";
         echo $content2."<br>";
        exec('D:\laragon\www\DetectionPlagiat\detect.exe '.$content2.' '.$contents, $output);
        echo "<br>";
        print_r($output) ;
        /* dd($output); */
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
