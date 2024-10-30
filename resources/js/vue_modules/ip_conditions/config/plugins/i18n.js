import Vue from "vue";
import VueI18n from "vue-i18n";
import languages from "./../languages";

Vue.use(VueI18n);

function loadLocaleMessages() {
    const messages = {};
    const locales = require.context(
        "../../locales",
        true,
        /[A-Za-z0-9-_,\s]+\.json$/i
    );

    locales.keys().forEach((key) => {
        const [, lang] = key.match(/\/([\w-]+)+?\./i);

        if (languages.all.includes(lang)) {
            messages[lang] = { ...locales(key) };
        }
    });

    return messages;
}

export default new VueI18n({
    locale: languages.default,
    fallbackLocale: languages.default,
    messages: loadLocaleMessages(),
});
