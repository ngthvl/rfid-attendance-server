import { JsonResourceType } from "~/types/meta";
import { TimeInOutLog } from "./detectionLog";
import { Student } from "./student"


interface DailyAttendanceResponseType extends JsonResourceType {
  data: TimeInOutLog[]
}

export const useStudentDailyAttendanceStore = defineStore('student-daily', () => {
    const listDailyAttendance = async (student: Student) => {
      const {data, error} = await useApi(`/admin/students/${student.id}/daily-attendance`, {
        method: "GET"
      })

      if(!error.value){
        const response: DailyAttendanceResponseType = data.value as DailyAttendanceResponseType;
        return response.data;
      }

      return [];
    }
    return {
      listDailyAttendance
    }
})