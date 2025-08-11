import { format, formatDistanceToNow, isSameWeek, differenceInCalendarYears } from 'date-fns';
import { es } from 'date-fns/locale';

type InputDate = Date | string | number;

export function formatMessageDate(input: InputDate): string {
    if (!input) return '—';
    const d = new Date(input);

    if (isNaN(d.getTime())) return '—';

    // 1) Dentro de la semana actual -> nombre del día (Lunes, Martes, ...)
    if (isSameWeek(d, new Date(), { locale: es, weekStartsOn: 1 })) {
        return format(d, 'EEEE', { locale: es });
    }

    // 2) Si excede el año -> DD/MM/YY
    if (differenceInCalendarYears(new Date(), d) >= 1) {
        return format(d, 'dd/MM/yy', { locale: es });
    }

    // 3) En otro caso -> relativo con sufijo: "hace 7 días", "hace 1 mes", etc.
    return formatDistanceToNow(d, { addSuffix: true, locale: es });
}
