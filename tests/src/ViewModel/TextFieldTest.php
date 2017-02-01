<?php

namespace tests\eLife\Patterns\ViewModel;

use eLife\Patterns\ViewModel\FormLabel;
use eLife\Patterns\ViewModel\TextField;

final class TextFieldTest extends ViewModelTest
{
    /**
     * @test
     */
    public function it_has_data()
    {
        $data = [
            'inputType' => 'email',
            'label' => [
                'labelText' => 'label',
                'for' => 'id',
                'isVisuallyHidden' => false,
            ],
            'name' => 'someName',
            'id' => 'id',
            'placeholder' => 'placeholder',
            'required' => true,
            'disabled' => true,
            'autofocus' => true,
            'value' => 'value',
            'classNames' => 'text-field--error',
        ];
        $textField = TextField::emailInput(
            new FormLabel($data['label']['labelText'], $data['label']['for']),
            $data['id'],
            $data['name'],
            $data['placeholder'],
            $data['required'],
            $data['disabled'],
            $data['autofocus'],
            $data['value'],
            TextField::STATUS_ERROR
        );

        $this->assertSame($data['name'], $textField['name']);
        $this->assertSame($data['id'], $textField['id']);
        $this->assertSame($data['label'], $textField['label']->toArray());
        $this->assertSame($data['placeholder'], $textField['placeholder']);
        $this->assertSame($data['required'], $textField['required']);
        $this->assertSame($data['disabled'], $textField['disabled']);
        $this->assertSame($data['autofocus'], $textField['autofocus']);
        $this->assertSame($data['value'], $textField['value']);
        $this->assertSame($data['classNames'], $textField['classNames']);
        $this->assertSame($data, $textField->toArray());
    }

    public function viewModelProvider() : array
    {
        return [
            'minimal email input' => [TextField::emailInput(new FormLabel('label', 'id'), 'id', 'some name')],
            'complete email input' => [TextField::emailInput(new FormLabel('label', 'id'), 'id', 'some name', 'placeholder', true, true, true, 'value', TextField::STATUS_ERROR)],
            'minimal text input' => [TextField::textInput(new FormLabel('label', 'id'), 'id', 'some name')],
            'complete text input' => [TextField::textInput(new FormLabel('label', 'id'), 'id', 'some name', 'placeholder', true, true, true, 'value', TextField::STATUS_ERROR)],
        ];
    }

    protected function expectedTemplate() : string
    {
        return '/elife/patterns/templates/text-field.mustache';
    }
}
