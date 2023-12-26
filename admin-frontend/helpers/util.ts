import dayjs, { Dayjs } from "dayjs"

export const transformDateTime = (dt: string) => {
    const transform = dayjs(dt)

    return transform.format('ddd DD MMM YYYY hh:mm A')
}

export const transformDate = (dt: Dayjs) => {
    return dt.format('ddd DD MMM YYYY')
}