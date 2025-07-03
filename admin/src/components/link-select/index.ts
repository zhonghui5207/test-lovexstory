export enum LinkTypeEnum {
    'SHOP_PAGES' = 'shop',
    'CUSTOM_LINK' = 'custom',
    'ARTICLE_LIST' = 'article',
    'MINI_PROGRAM' = 'mini_program'
}

export interface Link {
    path: string
    name?: string
    type: string
    query?: Record<string, any>
}
