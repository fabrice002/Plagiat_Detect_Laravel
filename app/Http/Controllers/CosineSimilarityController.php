<?php
namespace App\Http\Controllers;

require base_path('vendor\autoload.php');
// ini_set('max_execution_time', 0);

use FFI;
use Exception;
use Throwable;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Events\LectureLiensEvent;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class CosineSimilarityController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //

        return view('project.content.home.index');
    }
    public function calcaculateText(Request $request){
        ini_set('ffi.enable', true);
        $contenu =$request->contenu;
        $contenu_search=str_replace(' ', '+', $contenu);
        $result_link=1;
        try{
            // Augmenter la limite de temps d'exécution
            ini_set('max_execution_time', 420); // Augmente la limite à 120 secondes
            $result_link=$this->return_link($contenu_search);

        }catch (Throwable $e) {

        }
        if($result_link==0){
            $paths=base_path("public/google_links.txt");
            if (file_exists($paths)) {
                // Vérifier la taille du fichier
                if (filesize($paths) != 0) {
                    event(new LectureLiensEvent(50, $paths));
                }
            }
        }
        //$ffi = FFI::cdef('double calculate_similarity(const char* filename1, const char* filename2);', base_path('lib\libcosine_similarityTes.dll'));
        $documents=Document::get()->all();
        $i=0;
        $resultats= [];
        $seuil=20 ;
        foreach ($documents as $document) {
            # code...
            // Appel de la fonction
            $file2=$document->contenu;
            $result = $this->cosine_similarity($contenu, $file2)*100;
            if($result >= $seuil)  {
                $tab=   array('links'=>$document->links, 'resultat'=>$result);
                $resultats[$i]=$tab;
                $i=$i+1;
            }
        }
        return response()->json($resultats);
    }
    protected function cosine_similarity($contenu, $contenu_suspect){
        $ffi = FFI::cdef('double calculate_similarity(const char* filename1, const char* filename2);', base_path('lib\libcosine_similarityTes.dll'));
        return $ffi->calculate_similarity($contenu, $contenu_suspect);
    }
    protected function sent_tokenize($data, $filetype){
        $ffi = FFI::cdef('int selected_sentences(char* filename1, char* type_file);', base_path('lib\libselected_sentencez.dll'));
        return $ffi->selected_sentences($data, $filetype);


    }
    protected function return_link($line_to_search){
        $ffi = FFI::cdef('int returnLinks(const char* argument);', base_path('lib\libgooglesearch.dll'));
        return $ffi->returnLinks($line_to_search);
    }
    protected function scrapping_url($url){
        $ffi = FFI::cdef('int scrapping_url(const char* argument);', base_path('lib\libgooglescrap.dll'));
        return $ffi->scrapping_url($url);
    }
    public function readContext(Request $request){
        $file = $request->file('file');
        $filename=time().'.'.$request->file->extension();
        $filePath = $request->file->storeAs('documents', $filename, 'public');
        $extension = $file->extension();
        $file_sr=Storage::url($filePath);
        $file_sr="public".$file_sr;
        $file_sr=base_path($file_sr);
        $param1=str_replace( "\\", "/", $file_sr);
        $pythonScript = base_path('lib\main.py');
        $param2 = "";
        // dd('ok');
    if ($extension === 'txt') {
        $param2 = "txt";
    } elseif ($extension === 'docx') {
        $param2 = "word";
    } elseif ($extension === 'pdf') {
        $param2 = "pdf";
    } else {
        Storage::disk('public')->delete($filePath);
        return response()->json(['error' => 'Unsupported file type']);
    }

    set_time_limit(360);
    $command = "python \"{$pythonScript}\" \"{$param1}\" {$param2}";
    $text=shell_exec($command);
    if($text==0){
        $filename = base_path("public\\extracted_text.txt");
        $content = file_get_contents($filename);
        Storage::disk('public')->delete($filePath);
        return response()->json(['text' => $content]);

    }

    return response()->json(['errors' => 'Unknown']);

    }
    public function selected_sentences(Request $request){
        ini_set('ffi.enable', true);
        set_time_limit(360);
        $file_content=$request->file_content;
        $result=1;
        $result=$this->sent_tokenize($file_content, "pdf");
        if($result==0){
            $filename=base_path("public\selected_sentences.txt");
            $content =[];
            $i=0;
            $handle = fopen($filename, "r");
                    if ($handle) {
                        while (($line = fgets($handle)) !== false) {
                            $content[$i]=$line;
                            $i++;
                        }
                        fclose($handle);
                    }
            return response()->json($content);

        }
        return response()->json(['errors' => 'Unknown']);

    }
    public function google_search(Request $request){
        $pathed=base_path("public\selected_sentences.txt");
        $paths=base_path("public\google_links.txt");
            // dd('ok');
            if (file_exists($pathed)) {
                // dd(file_exists($pathed));
                    $handle = fopen($pathed, "r");
                    if ($handle) {
                        while (($line = fgets($handle)) !== false) {
                            $line=str_replace(' ', '+', $line);

                            // Lire le contenu du fichier dans un tableau
                                try{
                                    // Augmenter la limite de temps d'exécution
                                    set_time_limit(120); // Augmente la limite à 120 secondes
                                    $res=$this->return_link($line);
                                    // Lire le contenu du fichier dans un tableau
                                    if($res==0){
                                        $fileLines = file($paths);

                                        // Compter le nombre de lignes
                                        $lineCount = count($fileLines);
                                        if($lineCount > $request->number){
                                            break;
                                        }
                                    }

                                }catch (Throwable $e) {
                                    continue;
                                }
                        }
                        fclose($handle);
                    }
            }
            $content =[];
            $i=0;
            $handle = fopen($paths, "r");
                    if ($handle) {
                        while (($line = fgets($handle)) !== false) {
                            $content[$i]=$line;
                            $i++;
                        }
                        fclose($handle);
                    }
            return response()->json($content);

    }
    public function scrapping_link(Request $request){
        $paths=base_path("public\google_links.txt");
        event(new LectureLiensEvent(1, $paths));
        $result=0;
        return response()->json($result);
    }
    public function searchDB(Request $request){
        ini_set('ffi.enable', true);

        $documents=Document::get()->all();
        $i=0;
        $resultats= [];
        $seuil=$request->seuil;
        $file_content=$request->file_content;
        foreach ($documents as $document) {
            # code...
            // Appel de la fonction
            try{
                // Augmenter la limite de temps d'exécution
                set_time_limit(180); // Augmente la limite à 120 secondes
                $file2=$document->contenu;
                $result = $this->cosine_similarity($file_content, $file2)*100;
                if($result >= $seuil)  {
                    $tab=   array('links'=>$document->links, 'resultat'=>$result);
                    $resultats[$i]=$tab;
                    $i=$i+1;
                }

            }catch (Throwable $e) {
                continue;
            }

        }
        return response()->json($resultats);
    }
    public function calculateSimilarity(Request $request)
    {
        // ini_set('max_execution_time', 0);
        ini_set('ffi.enable', true);
        $file = $request->file('file');
        $filename=time().'.'.$request->file->extension();
        $filePath = $request->file->storeAs('documents', $filename, 'public');
        $extension = $file->extension();
        $file_sr=Storage::url($filePath);
        $file_sr="public".$file_sr;
        $file_sr=base_path($file_sr);
        $param1=str_replace( "\\", "/", $file_sr);
        // dd($param1);
        // Output the text content
        $pythonScript = base_path('lib/main.py');
        $result=2;

        if ($extension === 'txt') {
            $param2="txt";
            $command="python {$pythonScript} {$param1} {$param2}";
            $text=shell_exec($command);
            $result=$this->sent_tokenize($text, "text");
            // C'est un fichier texte (.txt)
        } elseif ($extension === 'docx') {
            $param2="word";
            $command="python {$pythonScript} {$param1} {$param2}";
            $text=shell_exec($command);
            $result=$this->sent_tokenize($text, "docx");
            // C'est un fichier Word (.docx)
        } elseif ($extension === 'pdf') {
            $param2="pdf";
            $command="python {$pythonScript} {$param1} {$param2}";
            $text=shell_exec($command);
            $result=$this->sent_tokenize($text, "pdf");
            // C'est un fichier PDF (.pdf)
        } else {
            return "error";
            // Type de fichier non pris en charge
        }
        // dd($text);
        Storage::disk('public')->delete($filePath);
        // return $text;
        //dd($result);
        $res=1;
        $paths=base_path("public\google_links.txt");
        if($result == 0){
            $pathed=base_path("public\selected_sentences.txt");
            dd('ok');
            if (file_exists($pathed)) {
                // dd(file_exists($pathed));
                    $handle = fopen($pathed, "r");
                    if ($handle) {
                        while (($line = fgets($handle)) !== false) {
                            $line=str_replace(' ', '+', $line);

                            // Lire le contenu du fichier dans un tableau
                                try{
                                    // Augmenter la limite de temps d'exécution
                                    set_time_limit(120); // Augmente la limite à 120 secondes
                                    $res=$this->return_link($line);
                                    // Lire le contenu du fichier dans un tableau
                                    if($res==0){
                                        $fileLines = file($paths);

                                        // Compter le nombre de lignes
                                        $lineCount = count($fileLines);
                                        if($lineCount > 100){
                                            break;
                                        }
                                    }

                                }catch (Throwable $e) {
                                    continue;
                                }


                            //$res=$ffRe->returnLinks($argument, $filename);
                        }
                        fclose($handle);
                    }
            }

        }
        //dd($res);
        if($res==0){

            //dd('àk');


            // Vérifier si le fichier existe
            if (file_exists($paths)) {
                // Vérifier la taille du fichier
                if (filesize($paths) != 0) {
                    event(new LectureLiensEvent(1, $paths));
                }
            }
        }
        /* if(file_exists($paths)){
            unlink($paths);
        }
        if(file_exists($pathed)){
            unlink($pathed);
        } */

        $documents=Document::get()->all();
        $i=0;
        $resultats= [];
        $seuil=20 ;
        //$ffi = FFI::cdef('double calculate_similarity(const char* filename1, const char* filename2);', base_path('lib\libcosine_similarityTe.dll'));
        foreach ($documents as $document) {
            # code...
            // Appel de la fonction
            $file2=$document->contenu;
            $result = $this->cosine_similarity($text, $file2)*100;
            if($result >= $seuil)  {
                $tab=   array('links'=>$document->links, 'resultat'=>$result);
                $resultats[$i]=$tab;
                $i=$i+1;
            }
        }

        Storage::disk('public')->delete($filePath);
        // Trie le tableau en utilisant la fonction de comparaison
        /* usort($resultats, 'compareResultatDesc'); */
        return response()->json($resultats);
            }
}
