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

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    user: User
    categories: any[]
    breadcrumbs: any[]
    cart: any
    notification: any
    ziggy: Config & { location: string }
}
