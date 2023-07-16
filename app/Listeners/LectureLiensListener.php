<?php

namespace App\Listeners;

use Throwable;
use DOMDocument;
use App\Models\Document;
use App\Events\LectureLiensEvent;
use Illuminate\Queue\InteractsWithQueue;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LectureLiensListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LectureLiensEvent $event): void
    {
        ini_set('max_execution_time', 0);
        $client = HttpClient::create();
        $handles = fopen($event->path, "r");
        if($handles){
            while (($line = fgets($handles)) !== false) {
                $link_exist=Document::where("links", $line)->get()->count();
                if($link_exist==0){
                    try {
                        set_time_limit(240);
                         // Send a GET request to the URL and get the response
                        //  set_time_limit(180);
                        $response = $client->request('GET', $line);
                        // Get the HTML content from the response
                        $html = $response->getContent();
                        // Create a new Crawler instance with the HTML content
                        $crawler = new Crawler($html);
                        // Extract the desired data from the crawler
                        $contenu = $crawler->filter('body')->text();
                        $id=Document::create([
                            'links'=>$line,
                            'contenu'=>$contenu
                        ]);

                    } catch (TransportExceptionInterface $e) {
                        // Handle transport-related exceptions
                        #echo "A transport exception occurred: " . $e->getMessage();
                        continue;
                    }catch (ClientException $e) {
                        // Handle client-related exceptions
                        #echo "A client exception occurred: " . $e->getMessage();
                        continue;
                    }catch (ClientExceptionInterface $e) {
                        // Handle client-related exceptions
                        #echo "A client exception occurred: " . $e->getMessage();
                        continue;
                    } catch (\Exception $e) {
                        // Handle other types of exceptions
                        #echo "An unknown exception occurred: " . $e->getMessage();
                        continue;
                    }catch (Throwable $e) {
                        if ($e instanceof FatalError) {
                            // Gérer l'erreur de dépassement du temps d'exécution ici
                            // ...
                            continue;
                            //echo "Le temps d'exécution maximum a été dépassé.";
                        } else {
                            continue;
                            // Gérer les autres exceptions ici
                            // ...
                        }
                    }

                }
            }
            fclose($handles);
        }
    }
}
