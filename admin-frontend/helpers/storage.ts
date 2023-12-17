export const asset = (path: string)=>{
  const config = useRuntimeConfig();

  return `${config.public.assetBase}${path}`
}
