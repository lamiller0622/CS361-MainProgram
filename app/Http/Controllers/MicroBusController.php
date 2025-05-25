<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MicroBusController extends Controller
{

    public function grocery(Request $req)
    {
        $cmd        = ucfirst($req->input('command'));      // "Add" or "Remove"
        $ingredient = $req->input('ingredient', '');
        $path       = base_path('A/GroceryList.txt');

        // write command + ingredient
        file_put_contents($path, $cmd . "\n" . $ingredient . "\n");

        // wait for the microservice to write back "Success" or "Fail"
        $start = microtime(true);
        $status = '';
        while (microtime(true) - $start < 5) {
            $content = @file_get_contents($path);
            if ($content !== false) {
                $lines = explode("\n", trim($content));
                $last  = end($lines);
                if (in_array($last, ['Success','Fail'], true)) {
                    $status = $last;
                    break;
                }
            }
            usleep(100_000);
        }

        return ['status' => $status ?: 'Timeout'];
    }

    public function viewGrocery()
    {
        $path = base_path('A/GroceryList.txt');

        // 1) send the View command
        file_put_contents($path, "View\n\n");

        // poll for up to 5 seconds
        $start = microtime(true);
        $timeoutSeconds = 5;
        $items = [];

        while (microtime(true) - $start < $timeoutSeconds) {
            $content = @file_get_contents($path);
            if ($content !== false) {
                // split on any line endings and remove blank lines
                $lines = array_filter(
                    preg_split('/\r\n|\r|\n/', $content),
                    fn($line) => trim($line) !== ''
                );
                $lines = array_values($lines);

                // >>> only remove the first line if it's actually "View"
                if (isset($lines[0]) && $lines[0] === 'View') {
                    array_shift($lines);
                }

                if (count($lines) > 0) {
                    $items = $lines;
                    break;
                }
            }

            usleep(100_000);
        }

        // if we never got anything back, return a 500
        if (empty($items)) {
            return response()->json([
                'error' => 'Timeout waiting for grocery-list microservice'
            ], 500);
        }

        return response()->json(['items' => $items]);
    }

    public function sort(Request $req)
    {
        $rid = (string) Str::uuid();

        // pull the user’s recipes (already loaded in the index view)
        $recipes = \App\Models\Recipe::get(['id','title','created_at', 'image_url'])
                 ->toArray();

        file_put_contents(
            base_path('bus/sort_in.txt'),
            json_encode([
                'request_id' => $rid,
                'by'         => $req->input('by','title'),
                'dir'        => $req->input('dir','asc'),
                'recipes'    => $recipes            // 👈 send the list
            ]).PHP_EOL,
            FILE_APPEND
        );

        $reply = $this->await(
            base_path('bus/sort_out.txt'),
            function ($j) use ($rid) {
                return isset($j['request_id']) && $j['request_id'] === $rid;
            },
            5000
        );

        return $reply['recipes'] ?? [];
    }

    public function generate(Request $req)  // Micro-C wrapper
    {
        $rid    = (string) Str::uuid();
        $prompt = $req->input('prompt', '');    // ← grab the prompt field

        // write the prompt (not title) into ai_in.txt
        file_put_contents(
            base_path('bus/ai_in.txt'),
            json_encode([
                'recipe_id' => $rid,
                'prompt'    => $prompt
            ]).PHP_EOL,
            FILE_APPEND
        );

        // wait for the microservice to reply
        $out = base_path('bus/ai_out.txt');
        $reply = $this->await(
            $out,
            function ($j) use ($rid) {
                return isset($j['recipe_id']) && $j['recipe_id'] === $rid;
            },
            8000
        );

        // return the URL (or empty/error)
        return ['url' => $reply['image_url'] ?? ''];
    }

    public function tip(Request $req)
    {
        $rid = (string) Str::uuid();

        // write the request
        file_put_contents(
            base_path('bus/tips_in.txt'),
            json_encode(['request_id' => $rid]).PHP_EOL,
            FILE_APPEND
        );

        // wait for the reply
        $reply = $this->await(
            base_path('bus/tips_out.txt'),
            function ($j) use ($rid) {
                return isset($j['request_id']) && $j['request_id'] === $rid;
            },
            5000
        );

        // return JSON 
        if ($reply && isset($reply['tip'])) {
            return ['tip' => $reply['tip']];
        }

        // fallback
        return response()->json(['tip' => 'Couldn’t load tip.'], 500);
    }

    private function await($file, $filter, $ms=2000)
    {
        $start = microtime(true);
        while (microtime(true) - $start < $ms/1000) {
            foreach (file($file) as $line) {
                $json = json_decode($line,true);
                if ($json && $filter($json)) return $json;
            }
            usleep(100_000);               // 0.1 s
        }
        return [];
    }
}
