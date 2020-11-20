<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PigLatinTranslate extends PigLatin
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pig-latin:translate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translate a word to Pig Latin';

    const VOWELS = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->word = $this->ask('What word would you like to translate?');

        if (!$this->validate()) {
            $this->error('Must enter a single word and must contain a vowel');
            return $this->handle();
        }

        if (Str::startsWith($this->word, SELF::VOWELS)) {
            return $this->vowelStart();
        }

        return $this->consonantStart();
    }

    protected function vowelStart()
    {
        $append = ['hay', 'way', 'yay'];

        $this->info($this->word . '\'' . Arr::random($append));
    }

    protected function consonantStart()
    {
        $consonant = Str::before($this->word,$this->firstVowel());
        $prepend = Str::after($this->word, $consonant);

        $this->info($prepend . '-' .$consonant . 'ay');
    }

    protected function validate()
    {
        try {
            app(Request::class)->merge([
                'word' => $this->word
            ])->validate([
                'word' => 'alpha'
            ]);
        } catch (ValidationException $e) {
            return false;
        }

        return !! $this->firstVowel();
    }

    protected function firstVowel()
    {
        foreach(str_split($this->word) as $character) {
            if(in_array($character, SELF::VOWELS)) {
                return $character;
            }
        }

        return false;
    }
}
