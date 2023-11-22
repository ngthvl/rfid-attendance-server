export interface ClientErrorType {
  message?: string
  errors?: {
    [key: string]: string[]
  }[]
}