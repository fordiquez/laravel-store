export function useFormat() {
    const formatMoney = (value, format = 'ua', asCurrency = true, currency = 'USD') => {
        return new Intl.NumberFormat(format, {
            style: asCurrency ? 'currency' : 'decimal',
            currency
        }).format(value)
    }

    const formatCardNumber = (cardNumber) => (cardNumber ? cardNumber.match(/.{1,4}/g).join(' ') : '')

    return { formatMoney, formatCardNumber }
}
