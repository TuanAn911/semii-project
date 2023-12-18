<?php
// src/Twig/AppExtension.php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppExtension extends AbstractExtension
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