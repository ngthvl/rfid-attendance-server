interface Links {
  url: String
  label: String
  active: boolean
}

export interface ResponseMeta {
  current_page: number
  from: number
  last_page: number
  links: Links[]
  path: String
  per_page: number
  to: number
  total: number
}

export const ResponseMetaDefaults = {
  current_page: 1,
  from: 1,
  last_page: 1,
  links: [],
  path: '',
  per_page: 10,
  to: 0,
  total: 0
}
