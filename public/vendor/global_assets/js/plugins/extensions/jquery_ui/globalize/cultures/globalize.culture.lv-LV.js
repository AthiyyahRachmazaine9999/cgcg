/*
 * Globalize Culture lv-LV
 *
 * http://github.com/jquery/globalize
 *
 * Copyright Software Freedom Conservancy, Inc.
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * This file was generated by the Globalize Culture Generator
 * Translation: bugs found in this file need to be fixed in the generator
 */

(function( window, undefined ) {

var Globalize;

if ( typeof require !== "undefined" &&
	typeof exports !== "undefined" &&
	typeof module !== "undefined" ) {
	// Assume CommonJS
	Globalize = require( "globalize" );
} else {
	// Global variable
	Globalize = window.Globalize;
}

Globalize.addCultureInfo( "lv-LV", "default", {
	name: "lv-LV",
	englishName: "Latvian (Latvia)",
	nativeName: "latviešu (Latvija)",
	language: "lv",
	numberFormat: {
		",": " ",
		".": ",",
		negativeInfinity: "-bezgalība",
		positiveInfinity: "bezgalība",
		percent: {
			pattern: ["-n%","n%"],
			",": " ",
			".": ","
		},
		currency: {
			pattern: ["-$ n","$ n"],
			",": " ",
			".": ",",
			symbol: "Ls"
		}
	},
	calendars: {
		standard: {
			"/": ".",
			firstDay: 1,
			days: {
				names: ["svētdiena","pirmdiena","otrdiena","trešdiena","ceturtdiena","piektdiena","sestdiena"],
				namesAbbr: ["sv","pr","ot","tr","ce","pk","se"],
				namesShort: ["sv","pr","ot","tr","ce","pk","se"]
			},
			months: {
				names: ["janvāris","februāris","marts","aprīlis","maijs","jūnijs","jūlijs","augusts","septembris","oktobris","novembris","decembris",""],
				namesAbbr: ["jan","feb","mar","apr","mai","jūn","jūl","aug","sep","okt","nov","dec",""]
			},
			monthsGenitive: {
				names: ["janvārī","februārī","martā","aprīlī","maijā","jūnijā","jūlijā","augustā","septembrī","oktobrī","novembrī","decembrī",""],
				namesAbbr: ["jan","feb","mar","apr","mai","jūn","jūl","aug","sep","okt","nov","dec",""]
			},
			AM: null,
			PM: null,
			patterns: {
				d: "yyyy.MM.dd.",
				D: "dddd, yyyy'. gada 'd. MMMM",
				t: "H:mm",
				T: "H:mm:ss",
				f: "dddd, yyyy'. gada 'd. MMMM H:mm",
				F: "dddd, yyyy'. gada 'd. MMMM H:mm:ss",
				M: "d. MMMM",
				Y: "yyyy. MMMM"
			}
		}
	}
});

}( this ));
