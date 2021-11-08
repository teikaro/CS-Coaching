<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            new TwigFilter('size', [$this, 'size']),
            new TwigFilter('excerpt', [$this, 'excerpt']), // Enregistrement du nouveau filtre auprès de Twig
        ];
    }

    /**
     * Filtre pour compter le nombre de caractère d'une chaîne
     */
    public function size(string $value) : int
    {
        return mb_strlen($value);
    }


    /**
     * Filtre qui retournera la chaîne de texte donnée tronquée à "$nbWords" mots. Si trop petite le filtre retourne juste la chaîne sans y toucher
     */
    public function excerpt(string $text, int $nbWords): string
    {

        $arrayText = explode(' ', $text, ($nbWords + 1));

        if( count($arrayText) > $nbWords ){
            array_pop($arrayText);
            return implode(' ', $arrayText) . '...';
        }

        return $text;

    }


}