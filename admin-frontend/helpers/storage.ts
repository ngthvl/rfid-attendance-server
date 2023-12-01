export const storage = (path: string)=>{
  const config = useRuntimeConfig();

  return `${config.public.storageBase}${path}`
}