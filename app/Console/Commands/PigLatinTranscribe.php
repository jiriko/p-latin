<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PigLatinTranscribe extends PigLatin
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pig-latin:transcribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transcribe a word from Pig Latin';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->word = $this->ask('What word would you like to transcribe?');

        if (!$this->validate()) {
            $this->error('Must enter a single word and must contain a vowel, a dash or a \'');
            return $this->handle();
        }

        $prepend = Str::before($this->word, $this->getSeparator());
        $lastCharacter = substr($prepend, -1);
        $append = Str::after($this->word, $this->getSeparator());
        if ($lastCharacter !== 'y') {
            return $this->info($append.$lastCharacter);
        }

        return $this->info($append);
    }

    protected function validate()
    {
        try {
            app(Request::class)->merge([
                'word' => $this->word
            ])->validate([
                'word' => 'alpha_dash'
            ]);
        } catch (ValidationException $e) {
            return false;
        }

        return !!$this->getSeparator();
    }

    protected function getSeparator()
    {
        foreach (str_split($this->word) as $character) {
            if (in_array($character, ['\'', '-'])) {
                return $character;
            }
        }

        return false;
    }
}
