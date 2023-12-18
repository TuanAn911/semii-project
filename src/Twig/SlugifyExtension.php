<?php
// src/Twig/SlugifyExtension.php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\String\Slugger\AsciiSlugger;

class SlugifyExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('slugify', [$this, 'slugifyFilter']),
        ];
    }

    public function slugifyFilter($string)
    {
        $slugger = new AsciiSlugger();
        return $slugger->slug($string);
    }
}