/**
 * Cookie Consent
 * https://github.com/orestbida/cookieconsent
 */

import 'https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v3.0.0/dist/cookieconsent.umd.js';

CookieConsent.run({
    autoShow: false,
    guiOptions: {
        consentModal: {
            layout: "box inline",
            position: "bottom left",
            equalWeightButtons: false,
            flipButtons: false
        },
        preferencesModal: {
            layout: "box",
            position: "right",
            equalWeightButtons: false,
            flipButtons: false
        }
    },
    categories: {
        necessary: {
            readOnly: true
        },
        functionality: {
            enabled: true
        },
        analytics: {
            enabled: true
        }
    },
    language: {
        default: "th",
        translations: {
            th: "./resources/json/cookieconsent-th.json",
            en: "./resources/json/cookieconsent-en.json"
        },
        autoDetect: "browser"
    }
});

setTimeout(CookieConsent.show, 1000)