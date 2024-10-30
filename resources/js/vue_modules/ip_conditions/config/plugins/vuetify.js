import Vue from "vue";
import Vuetify from "vuetify";
import "vuetify/dist/vuetify.min.css";
import en from "vuetify/src/locale/en.ts";
import de from "vuetify/src/locale/de.ts";

Vue.use(Vuetify);

const opts = {
    lang: {
        locales: { en, de },
    },
};

export default new Vuetify(opts);
