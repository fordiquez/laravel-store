<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
    labels: Object,
    isNumberMasked: Boolean,
    randomBackgrounds: {
        type: Boolean,
        default: true
    },
    backgroundImage: String
})

const isCardFlipped = ref(false)
const placeholders = reactive({
    amex: '#### ###### #####',
    diners: '#### ###### ####',
    default: '#### #### #### ####'
})
const currentPlaceholder = ref('')

onMounted(() => {
    currentPlaceholder.value =
        brand.value === 'amex' ? placeholders.amex : brand.value === 'dinersclub' ? placeholders.diners : placeholders.default
})

const currentCardBackground = computed(() => {
    let random = Math.floor(Math.random() * 25 + 1)
    return !props.backgroundImage && props.randomBackgrounds ? `${usePage().props.ziggy.url}/static/cards/${random}.jpeg` : null
})

const getIsNumberMasked = (index, n) => index < 14 && props.labels.number.length > index && n.trim() !== '' && props.isNumberMasked

const brand = computed(() => {
    if (props.labels.brand) return props.labels.brand

    let number = props.labels.number

    switch (!number) {
        case /^4/.test(number):
            return 'visa'
        case /^(34|37)/.test(number):
            return 'amex'
        case /^5[1-5]/.test(number):
            return 'mastercard'
        case /^6011/.test(number):
            return 'discover'
        case /^62/.test(number):
            return 'unionpay'
        case /^9792/.test(number):
            return 'troy'
        case /^3(?:0([0-5]|9)|[689]\d?)\d{0,11}/.test(number):
            return 'dinersclub'
        case /^35(2[89]|[3-8])/.test(number):
            return 'jcb'
        default:
            return ''
    }
})
</script>

<template>
    <div
        class="card-item relative z-20 mx-auto h-44 w-11/12 max-w-sm cursor-pointer 2xs:h-52 xs:h-72 xs:w-full xs:max-w-lg"
        :class="{ '-active': isCardFlipped }"
        @click="isCardFlipped = !isCardFlipped"
    >
        <div class="card-item__side -front h-full overflow-hidden rounded-xl">
            <div
                class="card-item__focus pointer-events-none absolute left-0 top-0 z-30 h-full w-full overflow-hidden rounded-md border-2 border-white/60 opacity-0"
            ></div>
            <div
                class="absolute left-0 top-0 h-full w-full overflow-hidden rounded-xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"
            >
                <img
                    v-if="currentCardBackground"
                    :src="currentCardBackground"
                    class="block h-full max-h-full w-full max-w-full object-cover"
                    alt="Card background"
                />
            </div>
            <div class="card-item__wrapper relative z-40 h-full select-none px-1 py-4 xs:px-4 xs:py-6">
                <div class="mb-4 flex items-start justify-between px-2.5 py-0 2xs:mb-6 xs:mb-10">
                    <svg viewBox="0 0 511 511" class="h-10 w-12 fill-purple-200 xs:h-14 xs:w-16">
                        <path
                            d="M455.5,56h-400C24.897,56,0,80.897,0,111.5v288C0,430.103,24.897,455,55.5,455h400c30.603,0,55.5-24.897,55.5-55.5v-288
	C511,80.897,486.103,56,455.5,56z M464,248H343v-56.5c0-4.687,3.813-8.5,8.5-8.5H464V248z M343,263h121v65H343V263z M479,223h17v65
	h-17V223z M479,208v-65h17v65H479z M464,168H351.5c-12.958,0-23.5,10.542-23.5,23.5V408H183V103h272.5c4.687,0,8.5,3.813,8.5,8.5
	V168z M168,248H47v-65h121V248z M32,288H15v-65h17V288z M47,263h121v65H47V263z M263,88V71h137v17H263z M248,88H111V71h137V88z
	 M168,103v65H47v-56.5c0-4.687,3.813-8.5,8.5-8.5H168z M32,208H15v-65h17V208z M15,303h17v65H15V303z M47,343h121v65H55.5
	c-4.687,0-8.5-3.813-8.5-8.5V343z M248,423v17H111v-17H248z M263,423h137v17H263V423z M343,408v-65h121v56.5
	c0,4.687-3.813,8.5-8.5,8.5H343z M479,303h17v65h-17V303z M496,111.5V128h-17v-16.5c0-12.958-10.542-23.5-23.5-23.5H415V71h40.5
	C477.832,71,496,89.168,496,111.5z M55.5,71H96v17H55.5C42.542,88,32,98.542,32,111.5V128H15v-16.5C15,89.168,33.168,71,55.5,71z
	 M15,399.5V383h17v16.5c0,12.958,10.542,23.5,23.5,23.5H96v17H55.5C33.168,440,15,421.832,15,399.5z M455.5,440H415v-17h40.5
	c12.958,0,23.5-10.542,23.5-23.5V383h17v16.5C496,421.832,477.832,440,455.5,440z"
                        />
                    </svg>
                    <div class="relative ml-auto flex justify-end">
                        <font-awesome-icon
                            :icon="brand ? ['fab', `cc-${brand}`] : ['fas', 'credit-card']"
                            class="h-10 w-12 text-white xs:h-14 xs:w-16"
                        />
                    </div>
                </div>
                <div class="mb-4 inline-block p-2.5 text-sm font-medium text-white 2xs:text-xl xs:mb-6 xs:px-4 xs:py-2.5 xs:text-2xl">
                    <template v-for="(n, index) in currentPlaceholder" :key="index">
                        <span class="inline-block w-2 2xs:w-3 xs:w-4" v-if="getIsNumberMasked(index, n)">*</span>
                        <span
                            v-else-if="labels.number.length > index"
                            :class="['inline-block', n.trim() === '' ? 'w-2 2xs:w-4 xs:w-6' : 'w-3 xs:w-4']"
                            :key="currentPlaceholder"
                        >
                            {{ labels.number[index] }}
                        </span>
                        <span
                            v-else
                            :class="['inline-block', n.trim() === '' ? 'w-2 2xs:w-4 xs:w-6' : 'w-3 xs:w-4']"
                            :key="currentPlaceholder + 1"
                        >
                            {{ n }}
                        </span>
                    </template>
                </div>
                <div class="flex items-center text-white">
                    <div class="block w-full max-w-[calc(100%-85px)] px-2.5 font-medium text-white xs:px-4 xs:py-2.5">
                        <div class="mb-1 text-xs opacity-70 xs:mb-1.5 xs:text-sm">Card Holder</div>
                        <span
                            v-if="labels.name.length"
                            class="max-w-full overflow-hidden text-ellipsis whitespace-nowrap text-base uppercase leading-4 xs:text-lg"
                        >
                            <span
                                class="relative inline-block min-w-[8px]"
                                v-for="(n, index) in labels.name.replace(/\s\s+/g, ' ')"
                                :key="index + 1"
                            >
                                {{ n }}
                            </span>
                        </span>
                        <span
                            v-else
                            class="max-w-full overflow-hidden text-ellipsis whitespace-nowrap text-base uppercase leading-4 xs:text-lg"
                        >
                            Full Name
                        </span>
                    </div>
                    <div class="ml-auto inline-flex w-20 shrink-0 flex-wrap justify-end whitespace-nowrap px-2.5 text-base xs:text-lg">
                        <div class="w-full pb-1 text-right text-xs opacity-70 xs:pb-1.5 xs:text-sm">Expires</div>
                        <div class="relative">
                            <span v-if="labels.exp_month" :key="labels.exp_month">
                                {{ labels.exp_month < 10 ? '0' + labels.exp_month : labels.exp_month }}
                            </span>
                            <span v-else key="2">MM</span>
                        </div>
                        /
                        <div class="inline-block w-5">
                            <span v-if="labels.exp_year" :key="labels.exp_year">
                                {{ String(labels.exp_year).slice(2, 4) }}
                            </span>
                            <span v-else key="2">YY</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-item__side -back absolute left-0 top-0 z-20 h-full w-full overflow-hidden rounded-xl p-0">
            <div
                class="absolute left-0 top-0 h-full w-full -rotate-180 overflow-hidden rounded-xl bg-[#1c1d27] bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 after:absolute after:left-0 after:top-0 after:h-full after:w-full after:bg-[#06021d] after:bg-opacity-40 after:content-['']"
            >
                <img
                    v-if="currentCardBackground"
                    :src="currentCardBackground"
                    class="block h-full max-h-full w-full max-w-full object-cover"
                    alt="Card background"
                />
            </div>
            <div class="relative z-20 mt-2.5 h-10 w-full 2xs:mt-5 2xs:h-[50px] xs:mt-8"></div>
            <div class="relative z-20 px-4 py-2.5 text-left 2xs:p-4">
                <div class="mb-1 pr-2.5 text-right font-medium text-white">CVC</div>
                <div
                    class="mb-4 flex items-center justify-end rounded bg-white pr-2.5 text-right text-lg text-[#1a3b5d] 2xs:mb-5 2xs:h-10 xs:mb-8 xs:h-11"
                >
                    ***
                </div>
                <div class="relative ml-auto flex justify-end opacity-70">
                    <font-awesome-icon
                        :icon="brand ? ['fab', `cc-${brand}`] : ['fas', 'credit-card']"
                        class="h-10 w-12 text-white xs:h-14 xs:w-16"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.card-item.-active .card-item__side.-front {
    transform: perspective(1000px) rotateY(180deg) rotateX(0deg) rotateZ(0deg);
}
.card-item.-active .card-item__side.-back {
    transform: perspective(1000px) rotateY(0) rotateX(0deg) rotateZ(0deg);
}
.card-item__focus {
    transition: all 0.35s cubic-bezier(0.71, 0.03, 0.56, 0.85);
}
.card-item__focus:after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    background: rgb(8, 20, 47);
    height: 100%;
    border-radius: 5px;
    filter: blur(25px);
    opacity: 0.5;
}
.card-item__side {
    box-shadow: 0 20px 60px 0 rgba(14, 42, 90, 0.55);
    transform: perspective(2000px) rotateY(0deg) rotateX(0deg) rotate(0deg);
    transform-style: preserve-3d;
    transition: all 0.8s cubic-bezier(0.71, 0.03, 0.56, 0.85);
    backface-visibility: hidden;
}
.card-item__side.-back {
    transform: perspective(2000px) rotateY(-180deg) rotateX(0deg) rotate(0deg);
}
.card-item__wrapper {
    font-family: 'Source Code Pro', monospace;
    text-shadow: 7px 6px 10px rgba(14, 42, 90, 0.8);
}
</style>
