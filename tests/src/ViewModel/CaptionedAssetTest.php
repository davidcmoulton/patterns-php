<?php

namespace tests\eLife\Patterns\ViewModel;

use eLife\Patterns\ViewModel\CaptionedAsset;
use eLife\Patterns\ViewModel\CaptionText;
use eLife\Patterns\ViewModel\Doi;
use eLife\Patterns\ViewModel\Image;
use eLife\Patterns\ViewModel\Link;
use eLife\Patterns\ViewModel\MediaSource;
use eLife\Patterns\ViewModel\MediaType;
use eLife\Patterns\ViewModel\Picture;
use eLife\Patterns\ViewModel\Table;
use eLife\Patterns\ViewModel\Video;

final class CaptionedAssetTest extends ViewModelTest
{
    /**
     * @test
     */
    public function it_has_data()
    {
        $widthFirst = 500;
        $widthSecond = 250;
        $data = [
            'captionText' => [
                'heading' => 'heading',
            ],
            'picture' => [
                'fallback' => [
                    'altText' => 'the alt text',
                    'defaultPath' => '/default/path',
                    'srcset' => '/path/to/image/'.$widthFirst.'/wide '.$widthFirst.'w, /default/path '.$widthSecond.'w',
                ],
                'sources' => [
                    [
                        'srcset' => '/path/to/svg',
                    ],
                ],
            ],
            'doi' => [
                'doi' => '10.7554/eLife.10181.001',
                'variant' => 'asset',
            ],
            'download' => [
                'link' => 'http://google.com/',
                'filename' => 'filename',
            ],
        ];
        $captionedImage = new CaptionedAsset(
            new Picture(
                [['srcset' => $data['picture']['sources'][0]['srcset']]],
                new Image(
                    $data['picture']['fallback']['defaultPath'],
                    [$widthFirst => '/path/to/image/'.$widthFirst.'/wide', $widthSecond => '/default/path'],
                    $data['picture']['fallback']['altText']
                )
            ),
            new CaptionText($data['captionText']['heading']),
            new Doi($data['doi']['doi']),
            new Link($data['download']['filename'], $data['download']['link'])
        );

        $this->assertSameWithoutOrder($data, $captionedImage->toArray());

        $widthFirst = 500;
        $widthSecond = 250;
        $data = [
            'captionText' => [
                'heading' => 'heading',
            ],
            'image' => [
                'altText' => 'the alt text',
                'defaultPath' => '/default/path',
                'srcset' => '/path/to/image/'.$widthFirst.'/wide '.$widthFirst.'w, /default/path '.$widthSecond.'w',
            ],
            'doi' => [
                'doi' => '10.7554/eLife.10181.001',
            ],
            'download' => [
                'link' => 'http://google.com/',
                'filename' => 'filename',
            ],
        ];
        $captionedImage = new CaptionedAsset(
            new Image(
                $data['image']['defaultPath'],
                [$widthFirst => '/path/to/image/'.$widthFirst.'/wide', $widthSecond => '/default/path'],
                $data['image']['altText']
            ),
            new CaptionText($data['captionText']['heading']),
            new Doi($data['doi']['doi']),
            new Link($data['download']['filename'], $data['download']['link'])
        );

        $this->assertSameWithoutOrder($data, $captionedImage);

        $data = [
            'captionText' => [
                'heading' => 'heading',
            ],
            'tables' => ['<table><thead><tr><th>F(Dfn, Dfd)</th><th>Partial η<sup>2</sup></th><th>Original effect size <em>f</em></th><th>Replication total sample size</th><th>Detectable effect size <em>f</em></th></tr></thead><tbody><tr><td>F(24,39) = 0.8678 (interaction)</td><td>0.348120</td><td>0.7307699</td><td>169<a class="xref-table-fn" href="#tblfn2">*</a></td><td>0.3895070<a class="xref-table-fn" href="#tblfn3">†</a></td></tr><tr><td>F(2,39) = 0.8075 (treatments)</td><td>0.039766</td><td>0.2035014</td><td>169<a class="xref-table-fn" href="#tblfn2">*</a></td><td>0.2415459<a class="xref-table-fn" href="#tblfn3">†</a></td></tr><tr><td>F(12,39) = 187.6811 (hematology parameters)</td><td>0.982978</td><td>7.599178</td><td>169<a class="xref-table-fn" href="#tblfn2">*</a></td><td>0.3331365<a class="xref-table-fn" href="#tblfn4">‡</a></td></tr></tbody></table>'],
            'doi' => [
                'doi' => '10.7554/eLife.10181.001',
                'variant' => 'asset',
            ],
            'download' => [
                'link' => 'http://google.com/',
                'filename' => 'filename',
            ],
        ];

        $figure = new CaptionedAsset(
            new Table($data['tables'][0]),
            new CaptionText($data['captionText']['heading']),
            new Doi($data['doi']['doi']),
            new Link($data['download']['filename'], $data['download']['link'])
        );
        $this->assertSameWithoutOrder($data, $figure->toArray());
    }

    public function test_it_works_with_latest_json()
    {
    }

    public function viewModelProvider() : array
    {
        return [
            'Captioned image' => [
                new CaptionedAsset(
                    new Picture(
                        [['srcset' => '/path/to/svg']],
                        new Image('/default/path', [500 => '/path/to/image/500/wide', 250 => '/default/path'],
                            'the alt text')
                    ),
                    new CaptionText('heading')
                ),
            ],
            'Captioned table' => [
                new CaptionedAsset(
                    new Table(
                        '<table><thead><tr><th>F(Dfn, Dfd)</th><th>Partial η<sup>2</sup></th><th>Original effect size <em>f</em></th><th>Replication total sample size</th><th>Detectable effect size <em>f</em></th></tr></thead><tbody><tr><td>F(24,39) = 0.8678 (interaction)</td><td>0.348120</td><td>0.7307699</td><td>169<a class="xref-table-fn" href="#tblfn2">*</a></td><td>0.3895070<a class="xref-table-fn" href="#tblfn3">†</a></td></tr><tr><td>F(2,39) = 0.8075 (treatments)</td><td>0.039766</td><td>0.2035014</td><td>169<a class="xref-table-fn" href="#tblfn2">*</a></td><td>0.2415459<a class="xref-table-fn" href="#tblfn3">†</a></td></tr><tr><td>F(12,39) = 187.6811 (hematology parameters)</td><td>0.982978</td><td>7.599178</td><td>169<a class="xref-table-fn" href="#tblfn2">*</a></td><td>0.3331365<a class="xref-table-fn" href="#tblfn4">‡</a></td></tr></tbody></table>'
                    ),
                    new CaptionText('heading')
                ),
            ],
            'Captioned video' => [
                new CaptionedAsset(
                    new Video('http://some.image.com/test.jpg',
                        [new MediaSource('/file.mp4', new MediaType('video/mp4'))]),
                    new CaptionText('heading'),
                    new Doi('10.7554/eLife.10181.001'),
                    new Link('filename', 'link')
                ),
            ],
        ];
    }

    protected function expectedTemplate() : string
    {
        return '/elife/patterns/templates/captioned-asset.mustache';
    }
}