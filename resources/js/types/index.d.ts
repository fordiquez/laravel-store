import { Config } from 'ziggy-js'

export interface User {
    id: number
    first_name: string
    last_name: string
    email: string
    email_verified_at: string
    avatar: string
    phone: string
    birth_date: string
    gender: string
}

export type Address = {
    id: number
    user_id: number
    is_main: boolean
    country_id: number
    country: Country
    state_id: number | null
    state: State
    city_id: number | null
    city: City
    street: string
    house: string
    flat: string | null
    postal_code: string | null
}

export type Country = {
    id: number
    name: string
    iso2: string
}

export type State = {
    id: number
    country_id: number
    name: string
}

export type City = {
    id: number
    state_id: number
    name: string
}

export type Cart = {
    count: number
    total: number
    items: any
    goods: any
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    user: User
    categories: any[]
    breadcrumbs: any[]
    cart: Cart
    notification: any
    ziggy: Config & { location: string }
}
