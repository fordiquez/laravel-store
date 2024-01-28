<div>
    <ul class="ml-auto flex">
        @for ($i = $getMinValue(); $i <= $getMaxValue(); $i++)
            <li class="rating-item"
                data-index="{{ $i }}"
                x-tooltip.raw="{{ $getRateTooltip($i) }}">
                @include('filament.forms.components._rating-item', [
                    'component' => $i <= $getState() ? $getSelectedIcon() : $getIcon(),
                ])
            </li>
        @endfor
    </ul>
</div>
