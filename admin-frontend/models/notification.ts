
interface notificationType {
    title: string
    message?: string
    shown: boolean
    timeout: number
    color: string
}

export const useNotificationStore = defineStore('notification', ()=>{
    const notifications: Ref<notificationType[]> = ref([])

    const pushNotification = (title:string, message?:string, color:string="success", timeout:number=3000) => {
        notifications.value.push({
            title: title,
            message: message,
            shown: true,
            timeout: timeout,
            color: color
        })
    }

    return {
        notifications,
        pushNotification
    }
});