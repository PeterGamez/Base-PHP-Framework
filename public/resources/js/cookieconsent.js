/**
 * Cookie Consent
 * https://github.com/orestbida/cookieconsent
 */

import { run } from 'https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v3.0.0-rc.16/dist/cookieconsent.esm.js';

run({
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
        functionality: {},
        analytics: {}
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