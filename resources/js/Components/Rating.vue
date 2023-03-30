<script setup>
import { reactive, ref } from 'vue';

const props = defineProps({
    grade: {
        type: Number,
        default: 0,
    },
    maxStars: {
        type: Number,
        default: 5,
    },
});

const emit = defineEmits(['rating']);

const stars = ref(props.grade);

const rateText = reactive(['bad', 'unsatisfactory', 'normal', 'nice', 'excellent']);

const rate = (star) => {
    if (typeof star === 'number' && star <= props.maxStars && star >= 0) {
        stars.value = stars.value === star ? star - 1 : star;
        emit('rating', stars.value);
    }
};
</script>

<template>
    <div class="flex justify-center">
        <ul class="stars">
            <li
                v-for="star in maxStars"
                :key="star"
                :class="[star <= stars ? 'text-purple-600 dark:text-purple-400' : 'text-gray-500']"
                class="star inline-flex cursor-pointer flex-col items-center p-4 hover:text-purple-600 hover:dark:text-purple-400"
                @click="rate(star)"
            >
                <font-awesome-icon :icon="[star <= stars ? 'fas' : 'far', 'star']" size="2xl" />
                <span class="mt-2 hidden text-sm font-medium uppercase tracking-wider sm:inline-block">
                    {{ rateText[star - 1] }}
                </span>
            </li>
        </ul>
    </div>
</template>

<style scoped>
.stars:hover .star {
    @apply text-purple-600 dark:text-purple-400;
}

.star:hover ~ .star:not(.text-purple-600) {
    @apply text-gray-500;
}
</style>
