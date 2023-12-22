import { Student } from "./student"


export const usePrintableStore = defineStore('rfid_terminal', () => {
    const printableStudents: Ref<Student[]> = ref([]);

    const doPrint = (printables:Student[]) => {
        printableStudents.value = printables
        const router = useRouter();

        router.push('/printID');
    }
    
    return {
        printableStudents,
        doPrint
    }
})