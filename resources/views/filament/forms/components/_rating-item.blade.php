<x-dynamic-component
    x-on:click="clickHandler"
    x-on:mouseenter="() => false"
    :component="$component"
    style="color : {{$getFinalColorStyle()}}"
    class="mr-2 ml-1 rtl:ml-2 rtl:-mr-1 flex-shrink-0 {{ $getSizeClass() }} {{ $getCursorClass() }}"
/>
