<?php

namespace eLife\Patterns\ViewModel;

use Assert\Assertion;
use eLife\Patterns\ArrayAccessFromProperties;
use eLife\Patterns\ArrayFromProperties;
use eLife\Patterns\ComposedAssets;
use eLife\Patterns\ViewModel;
use Traversable;

final class ContentHeader implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;
    use ComposedAssets;

    private $title;
    private $longTitle;
    private $image;
    private $impactStatement;
    private $header;
    private $authors;
    private $institutions;
    private $download;
    private $button;
    private $selectNav;
    private $meta;
    private $licence;

    public function __construct(
        string $title,
        Picture $image = null,
        string $impactStatement = null,
        bool $header = false,
        array $subjects = [],
        Profile $profile = null,
        array $authors = [],
        array $institutions = [],
        string $download = null,
        Button $button = null,
        SelectNav $selectNav = null,
        Meta $meta = null,
        string $licence = null
    ) {
        Assertion::notBlank($title);
        Assertion::allIsInstanceOf($subjects, Link::class);
        Assertion::allIsInstanceOf($authors, Author::class);
        Assertion::allIsInstanceOf($institutions, Institution::class);

        $this->title = $title;
        if (strlen(strip_tags($title)) >= 20) {
            $this->longTitle = true;
        }
        $this->image = $image;
        $this->impactStatement = $impactStatement;
        if ($header) {
            $this->header = ['possible' => true];
            if ($subjects) {
                $this->header['hasSubjects'] = true;
                $this->header['subjects'] = $subjects;
            }
            if ($profile) {
                $this->header['hasProfile'] = true;
                $this->header['profile'] = $profile;
            }
        }
        if ($authors) {
            $this->authors = ['list' => $authors];
            if ($institutions) {
                $this->institutions = ['list' => $institutions];
            }
        }
        $this->download = $download;
        $this->button = $button;
        $this->selectNav = $selectNav;
        $this->meta = $meta;
        $this->licence = $licence;
    }

    public function getTemplateName() : string
    {
        return 'resources/templates/content-header.mustache';
    }

    protected function getLocalStyleSheets() : Traversable
    {
        yield 'resources/assets/css/content-header.css';
    }

    protected function getComposedViewModels() : Traversable
    {
        yield $this->button;
        yield $this->selectNav;
        yield $this->meta;
    }
}
