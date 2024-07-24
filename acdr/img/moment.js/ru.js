//! moment.js locale configuration
//! locale : Russian [ru]
//! author : Viktorminator : https://github.com/Viktorminator
//! Author : Menelion Elensúle : https://github.com/Oire
//! author : Коренберг Марк : https://github.com/socketpair

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
       && typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define(['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';


function plural(word, num) {
    var forms = word.split('_');
    return num % 10 === 1 && num % 100 !== 11 ? forms[0] : (num % 10 >= 2 && num % 10 <= 4 && (num % 100 < 10 || num % 100 >= 20) ? forms[1] : forms[2]);
}
function relativeTimeWithPlural(number, withoutSuffix, key) {
    var format = {
        'ss': withoutSuffix ? 'секунда_секунди_секунд' : 'секунду_секунди_секунд',
        'mm': withoutSuffix ? 'хвилина_хвилини_хвилин' : 'хвилину_хвилини_хвилин',
        'hh': 'година_години_годин',
        'dd': 'день_дня_днів',
        'MM': 'місяць_місяця_місяців',
        'yy': 'рік_року_років'
    };
    if (key === 'm') {
        return withoutSuffix ? 'хвилина' : 'хвилину';
    }
    else {
        return number + ' ' + plural(format[key], +number);
    }
}
var monthsParse = [/^січ/i, /^лют/i, /^берез/i, /^квіт/i, /^трав/i, /^черв/i, /^лип/i, /^серп/i, /^верес/i, /^жовт/i, /^листоп/i, /^груд/i];

// Скорочення місяців:
// CLDR data:          http://www.unicode.org/cldr/charts/28/summary/ru.html#1753
var ru = moment.defineLocale('ru', {
    months : {
        format: 'січня_лютого_березня_квітня_травня_червня_липня_серпня_вересня_жовтня_листопада_грудня'.split('_'),
        standalone: 'січень_лютий_березень_квітень_травень_червень_липень_серпень_вересень_жовтень_листопад_грудень'.split('_')
    },
    monthsShort : {
        // за CLDR саме " лип." і "черв.", але який сенс міняти букву на крапку ?
        format: 'січ._лют._берез._квіт._травня_червня_липня_серп._верес._жовт._листоп._груд.'.split('_'),
        standalone: 'січ._лют._березень_квіт._травень_червень_липень_серп._верес._жовт._листоп._груд.'.split('_')
    },
    weekdays : {
        standalone: 'неділя_понеділок_вівторок_середа_четвер_пятниця_субота'.split('_'),
        format: 'неділя_понеділок_вівторок_середу_четвер_пятницю_суботу'.split('_'),
        isFormat: /\[ ?[Вв] ?(?:минулу|наступну|цю)? ?\] ?dddd/
    },
    weekdaysShort : 'нд_пн_вт_ср_чт_пт_сб'.split('_'),
    weekdaysMin : 'нд_пн_вт_ср_чт_пт_сб'.split('_'),
    monthsParse : monthsParse,
    longMonthsParse : monthsParse,
    shortMonthsParse : monthsParse,

    // повні назви з відмінками, по три літери, для деяких, по 4 літери, скорочення з крапкою і без крапки
    monthsRegex: /^(січень|січ\.?|лютий|лют?\.?|березень?|берез\.?|квітень|квіт\.?|травень|червень|черв\.?|липень|лип\.?|серпень?|серп\.?|вересень|верес?\.?|жовтень|жовт\.?|листопад|листоп?\.?|грудень|груд\.?)/i,

    // копія попереднього
    monthsShortRegex: /^(січень|січ\.?|лютий|лют?\.?|березень?|берез\.?|квітень|квіт\.?|травень|червень|черв\.?|липень|лип\.?|серпень?|серп\.?|вересень|верес?\.?|жовтень|жовт\.?|листопад|листоп?\.?|грудень|груд\.?)/i,

    // повні назви з відмінками
    monthsStrictRegex: /^(січень|лютий|березень?|квітень|травень|червень|липень|серпень?|вересень|жовтень|листопад|грудень)/i,

    // Вираз, який відповідає тільки скороченим формам
    monthsShortStrictRegex: /^(січ\.|лют?\.|берез|квіт\.|травень|червень|липень|серп\.|верес?\.|жовт\.|листоп?\.|груд\.)/i,
    longDateFormat : {
        LT : 'H:mm',
        LTS : 'H:mm:ss',
        L : 'DD.MM.YYYY',
        LL : 'D MMMM YYYY г.',
        LLL : 'D MMMM YYYY г., H:mm',
        LLLL : 'dddd, D MMMM YYYY г., H:mm'
    },
    calendar : {
        sameDay: '[Сьогодні в] LT',
        nextDay: '[Завтра в] LT',
        lastDay: '[Вчора в] LT',
        nextWeek: function (now) {
            if (now.week() !== this.week()) {
                switch (this.day()) {
                    case 0:
                        return '[В наступне] dddd [в] LT';
                    case 1:
                    case 2:
                    case 4:
                        return '[В наступний] dddd [в] LT';
                    case 3:
                    case 5:
                    case 6:
                        return '[В наступну] dddd [в] LT';
                }
            } else {
                if (this.day() === 2) {
                    return '[У] dddd [в] LT';
                } else {
                    return '[В] dddd [в] LT';
                }
            }
        },
        lastWeek: function (now) {
            if (now.week() !== this.week()) {
                switch (this.day()) {
                    case 0:
                        return '[В минуле] dddd [в] LT';
                    case 1:
                    case 2:
                    case 4:
                        return '[В минулий] dddd [в] LT';
                    case 3:
                    case 5:
                    case 6:
                        return '[В минулу] dddd [в] LT';
                }
            } else {
                if (this.day() === 2) {
                    return '[У] dddd [в] LT';
                } else {
                    return '[В] dddd [в] LT';
                }
            }
        },
        sameElse: 'L'
    },
    relativeTime : {
        future : 'через %s',
        past : '%s назад',
        s : 'декілька секунд',
        ss : relativeTimeWithPlural,
        m : relativeTimeWithPlural,
        mm : relativeTimeWithPlural,
        h : 'годину',
        hh : relativeTimeWithPlural,
        d : 'день',
        dd : relativeTimeWithPlural,
        M : 'місяць',
        MM : relativeTimeWithPlural,
        y : 'рік',
        yy : relativeTimeWithPlural
    },
    meridiemParse: /ночі|ранку|дня|вечора/i,
    isPM : function (input) {
        return /^(дня|вечора)$/.test(input);
    },
    meridiem : function (hour, minute, isLower) {
        if (hour < 4) {
            return 'ночі';
        } else if (hour < 12) {
            return 'ранку';
        } else if (hour < 17) {
            return 'дня';
        } else {
            return 'вечора';
        }
    },
    dayOfMonthOrdinalParse: /\d{1,2}-(й|го|я)/,
    ordinal: function (number, period) {
        switch (period) {
            case 'M':
            case 'd':
            case 'DDD':
                return number + '-й';
            case 'D':
                return number + '-го';
            case 'w':
            case 'W':
                return number + '-я';
            default:
                return number;
        }
    },
    week : {
        dow : 1, // Monday is the first day of the week.
        doy : 4  // The week that contains Jan 4th is the first week of the year.
    }
});

return ru;

})));
