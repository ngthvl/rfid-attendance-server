import dayjs, { Dayjs } from "dayjs";
import { DetectionLog } from "~/models/detectionLog";

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