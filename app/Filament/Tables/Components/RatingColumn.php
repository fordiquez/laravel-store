<?php

namespace App\Filament\Tables\Components;

use Closure;
use Filament\Tables\Columns\Column;

/**
 * Rating field for the Filament forms
 */
class RatingColumn extends Column
{
    protected string $view = 'filament.tables.components.rating-column';

    protected string $color = '#EAB308';

    protected string $disabledColor = '#6B7280';

    protected string|Closure $icon = 'heroicon-o-star';

    protected string|Closure $selectedIcon = 'heroicon-s-star';

    protected int|Closure $min = 1;

    protected int|Closure $max = 5;

    protected int|Closure $width = 6;

    protected int|Closure $height = 6;

    protected string $cursor = 'default';

    // protected string $disabledCursor = 'not-allowed';
    protected string $clearIconTooltip = '';

    protected array $tooltips = [];

    public function color(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function disabledColor(string $disabledColor): self
    {
        $this->disabledColor = $disabledColor;

        return $this;
    }

    public function clearIconTooltip(string $clearIconTooltip): self
    {
        $this->clearIconTooltip = $clearIconTooltip;

        return $this;
    }

    public function icons(string $icon, string $solidIcon): self
    {
        $this->icon($icon);
        $this->selectedIcon($solidIcon);

        return $this;
    }

    public function icon(string|Closure $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function selectedIcon(string|Closure $selectedIcon): self
    {
        $this->selectedIcon = $selectedIcon;

        return $this;
    }

    public function minValue(int|Closure $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function maxValue(int|Closure $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function options(array|Closure $options): self
    {
        if (is_callable($options)) {
            $options = $options();
        }
        $this->minValue(1);
        $this->maxValue(count($options));
        $this->tooltips = [];

        foreach ($options as $option) {
            $this->tooltips[] = $option;
        }

        return $this;
    }

    public function getRateTooltip(int $index): mixed
    {
        return $this->tooltips[$index - 1] ?? '';
    }

    public function width(int|Closure $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function height(int|Closure $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function size(int|Closure $size): self
    {
        $this->width = $size;
        $this->height = $size;

        return $this;
    }

    // public function cursor(string $cursor): self
    // {
    //     $this->cursor = $cursor;

    //     return $this;
    // }

    // public function disabledCursor(string $disabledCursor): self
    // {
    //     $this->disabledCursor = $disabledCursor;

    //     return $this;
    // }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getDisabledColor(): string
    {
        return $this->disabledColor;
    }

    public function getClearIconTooltip(): string
    {
        return $this->clearIconTooltip;
    }

    public function getIcon(): string
    {
        return $this->evaluate($this->icon);
    }

    public function getSelectedIcon(): string
    {
        return $this->evaluate($this->selectedIcon);
    }

    public function getMinValue(): int
    {
        return $this->evaluate($this->min);
    }

    public function getMaxValue(): int
    {
        return $this->evaluate($this->max);
    }

    public function getWidth(): int
    {
        return $this->evaluate($this->width);
    }

    public function getHeight(): int
    {
        return $this->evaluate($this->height);
    }

    public function getCursor(): string
    {
        return $this->cursor;
    }

    public function getFinalColorStyle(): string
    {
        return $this->isDisabled()
            ? $this->getDisabledColor()
            : $this->getColor();
    }

    public function hasEffects(): bool
    {
        return $this->evaluate($this->effects);
    }

    public function getSizeClass(): string
    {
        return sprintf('w-%s h-%s', $this->getWidth(), $this->getHeight());
    }

    public function getCursorClass(): string
    {
        return $this->getCursor();
    }
}
