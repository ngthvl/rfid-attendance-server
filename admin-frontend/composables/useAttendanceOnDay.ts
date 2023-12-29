import dayjs, { Dayjs } from "dayjs";
import { DetectionLog, TimeInOutLog } from "~/models/detectionLog";


export const attendanceOnDay = (date_time: Dayjs, detections?:DetectionLog[]) => {
  const currDay = date_time;
  let attendance = 'A';
  if(detections){
    detections.forEach((v, i)=>{
      const ddate = dayjs(v.detection_dt)
      if (ddate.isSame(currDay, 'date')){
        attendance = 'P';
      }
    })
  }
  return attendance
}

export const attendanceOnDay2 = (date_time: Dayjs, timeLog?:TimeInOutLog[]) => {
  const currDay = date_time;
  let attendance = 'A';
  if(timeLog){
    timeLog.forEach((v, i)=>{
      const ddate = dayjs(v.detection_dt)
      if (ddate.isSame(currDay, 'date')){
        attendance = 'P';
      }
    })
  }
  return attendance
}

export const getTimeIn = (date_time: Dayjs, timeLog?:TimeInOutLog[]) => {
  const currDay = date_time;
  let attendance = 'N/A';
  if(timeLog){
    timeLog.forEach((v, i)=>{
      const ddate = dayjs(v.detection_dt)
      if (ddate.isSame(currDay, 'date')){
        const t = dayjs(v.time_in)
        attendance = t.format('HH:mm:ss A');
      }
    })
  }
  return attendance
}

export const getTimeOut = (date_time: Dayjs, timeLog?:TimeInOutLog[]) => {
  const currDay = date_time;
  let attendance = 'N/A';
  if(timeLog){
    timeLog.forEach((v, i)=>{
      const ddate = dayjs(v.detection_dt)
      if (ddate.isSame(currDay, 'date')){
        const t = dayjs(v.time_out)
        attendance = t.format('HH:mm:ss A');
      }
    })
  }
  return attendance
}